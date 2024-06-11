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

    // "освобождаем" одного пользователя
    public static function makeHimFree($userId) 
    { 
        $user = new \CUser;
        $user->Update($userId, ['UF_BUSY' => 0]);
        $user->Update($userId, ['UF_TG_DIALOG_STATUS' => 'default']);        // обнулить статус телеграм

        // занулить последнее приглашение
        self::resetLastTgInvite($userId);
    }

    // "занимаем" одного пользователя
    public static function makeHimBusy($userId) 
    { 
        $user = new \CUser;
        $user->Update($userId, ['UF_BUSY' => 1]);
    }

    // "освобождаем" несколько пользователей
    public static function makeThemFree($arUsers) { 
        self::changeField($arUsers, 'UF_BUSY', 0);        
        self::changeField($arUsers, 'UF_TG_DIALOG_STATUS', 'default');  // обнулить статус телеграм

        // занулить последнее приглашение
        foreach ($arUsers as $userId) {
            var_dump($userId);
            self::resetLastTgInvite($userId);
        }
    }

    // делаем "занятыми" пользователей
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