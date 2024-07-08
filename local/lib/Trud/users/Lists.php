<?php

namespace Trud\Users;

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Trud\TgBot\Bot;


// use Trud\IBlock\InfoIblock;


class Lists
{
    // оставить только свободных пользователей
    public static function getFreeUsers($arUsers) 
    {
        $freeUsers = [];

        $user = new \CUser;

        foreach ($arUsers as $userId) {
            $userInfo = $user->GetByID($userId)->Fetch();
            if ($userInfo && $userInfo['UF_BUSY'] == 0) {
                $freeUsers[] = $userId;
            }
        }

        return $freeUsers;
    }

    // получаем имена, фамилии и статусы работников
    public static function getNames($arUsers){
        $user = new \CUser;
        $result = [];

        foreach ($arUsers as $userId) {
            $userInfo = $user->GetByID($userId)->Fetch();

            // записываем пока только И.Ф.
            $id = $userInfo['ID'];
            // $result["$id"] = $userInfo['NAME'] . ' ' . $userInfo['LAST_NAME'];

            $result["$id"] = [
                'NAME' => $userInfo['NAME'],
                'LAST_NAME' => $userInfo['LAST_NAME'],
                'STATUS' => $userInfo['UF_TG_DIALOG_STATUS']
            ];
        }

// echo"<pre>";
// var_dump($result);


        // // Обработка результатов
        // while ($arUser = $rsUsers->Fetch()) {
        //     $result[$arUser['ID']] = $arUser['NAME'] . ' ' . $arUser['LAST_NAME'];
        //     // $result[$arUser['ID']] = [
        //     //     'NAME' => $arUser['NAME'],
        //     //     'LAST_NAME' => $arUser['LAST_NAME'],
        //     //     'STATUS' => $arUser['UF_TG_DIALOG_STATUS']
        //     // ];
        // }

        return $result;
    }

    // "освобождаем" одного пользователя (УСТАРЕВШИЙ ФУНКЦИОНАЛ)
    public static function makeHimFree($userId) 
    { 
        $user = new \CUser;
        $user->Update($userId, ['UF_BUSY' => 0]);
        $user->Update($userId, ['UF_TG_DIALOG_STATUS' => 'default']);        // обнулить статус телеграм

        // занулить последнее приглашение
        self::resetLastTgInvite($userId);
    }

    // "занимаем" одного пользователя (УСТАРЕВШИЙ ФУНКЦИОНАЛ)
    public static function makeHimBusy($userId) 
    { 
        $user = new \CUser;
        $user->Update($userId, ['UF_BUSY' => 1]);
    }

    
    // добавить юзерам, что они заняты в этой смене
    public static function addShiftToUsers($arUsers, $shiftID)
    {
        $user = new \CUser;

        foreach ($arUsers as $userId) {
            // $user->Update($userId, ['UF_WHERE_ENGAGED' => $shiftID]);
            // Получаем текущие значения UF_WHERE_ENGAGED для пользователя
            $userData = UserTable::getList([
                'select' => ['ID', 'UF_WHERE_ENGAGED'],
                'filter' => ['ID' => $userId]
            ])->fetch();

            // Добавляем новое значение в массив
            $updatedShifts = $userData['UF_WHERE_ENGAGED'];
            $updatedShifts[] = $shiftID;

            // Удаляем возможные дубликаты
            $updatedShifts = array_unique($updatedShifts);

            // Обновляем значения UF_WHERE_ENGAGED
            $user->Update($userId, ['UF_WHERE_ENGAGED' => $updatedShifts]);
        }
    }

    // убрать у юзеров занятость в этой смене
    public static function removeShiftFromUsers($arUsers, $shiftID)
    {
        foreach ($arUsers as $userId) {
            // Получаем информацию о пользователе, включая пользовательское поле UF_WHERE_ENGAGED
            $user = UserTable::getList([
                'select' => ['ID', 'UF_WHERE_ENGAGED'],
                'filter' => ['ID' => $userId]
            ])->fetch();

            // Получаем текущие значения UF_WHERE_ENGAGED
            $shifts = $user['UF_WHERE_ENGAGED'];

            if (empty($shifts)) {
                return false; // Нечего удалять
            }

            // Удаляем значение из массива
            $shifts = array_filter($shifts, function($shift) use ($shiftID) {
                return $shift != $shiftID;
            });

            // Обновляем значения UF_SUF_WHERE_ENGAGEDHIFTS
            $user = new \CUser;
            $result = $user->Update($userId, ['UF_WHERE_ENGAGED' => $shifts]);
        }
    }

    // "освобождаем" несколько пользователей (УСТАРЕВШИЙ ФУНКЦИОНАЛ)
    public static function makeThemFree($arUsers) { 
        self::changeField($arUsers, 'UF_BUSY', 0);        
        self::changeField($arUsers, 'UF_TG_DIALOG_STATUS', 'default');  // обнулить статус телеграм

        // занулить последнее приглашение
        // foreach ($arUsers as $userId) {
        //     self::resetLastTgInvite($userId); 
        // }
    }

    // делаем "занятыми" пользователей (УСТАРЕВШИЙ ФУНКЦИОНАЛ)
    public static function makeThemBusy($arUsers) { self::changeField($arUsers, 'UF_BUSY', 1); }
    
    // Приватный метод для изменения поля пользователей
    private static function changeField($arUsers, $fieldName, $fieldValue)
    {
        $user = new \CUser;

        foreach ($arUsers as $userId) {
            $user->Update($userId, [$fieldName => $fieldValue]);
        }
    }

    // найти $tg_id юзера и занулить его последнее приглашение
    public static function resetLastTgInvite($userId){
        $user = new \CUser;
        $userInfo = $user->GetByID($userId)->Fetch();
        if ($userInfo['UF_TELEGRAM_ID']) {
            $bot = new Bot();
            $bot->resetLastInvite($userInfo['UF_TELEGRAM_ID']);
        }
    }

}