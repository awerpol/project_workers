<?php

namespace Trud\Users;

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Trud\TgBot\Bot;
use Bitrix\Main\UserGroupTable;
use Bitrix\Main\Type\DateTime;
use Trud\TgBot\MessageBuilder;
use Trud\Helpers\Helper;
use Trud\TgBot\Notifier;

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

    public static function checkDatesOfDocuments(){

        // // Выбираем пользователей из группы "WORKERS" ['GROUP_ID'=> '5']
        $select = [
            'ID'                        => 'USER_ID',
            'NAME'                      =>'USER.NAME',
            'LAST_NAME'                 =>'USER.LAST_NAME', 
            'PERSONAL_GENDER'           =>'USER.PERSONAL_GENDER',
            'PERSONAL_PHONE'            =>'USER.PERSONAL_PHONE', 
            'UF_RULES'                  =>'USER.UF_RULES', 
            'UF_TELEGRAM_ID'            =>'USER.UF_TELEGRAM_ID',
            'UF_RATING'                 =>'USER.UF_RATING',
            'PASSPORT_EXPIRATION'       =>'USER.UF_PASSPORT_EXPIRATION',
            'REGISTRATION_EXPIRATION'   =>'USER.UF_REGISTRATION_EXPIRATION',
            'SANITARY_EXPIRATION'       =>'USER.UF_SANITARY_EXPIRATION'
        ];

        $filter = ['GROUP_ID'=> '5'];
        $res = UserGroupTable::getList(['select' => $select, 'filter' => $filter]);
        $users = $res->fetchAll();

        $currentTime = (new DateTime())->getTimestamp();
        $badUsers = [];
        
        foreach ($users as $user) {
            $userIssues = ['user' => $user, 'documents' => []];
    
            foreach (['PASSPORT_EXPIRATION', 'REGISTRATION_EXPIRATION', 'SANITARY_EXPIRATION'] as $field) {
                $expirationDate = $user[$field] ? new DateTime($user[$field]) : null;
                $expirationTimestamp = $expirationDate ? $expirationDate->getTimestamp() : null;
                $daysLeft = $expirationTimestamp ? ($expirationTimestamp - $currentTime) / 86400 : null;
                $type = strtolower(explode('_', $field)[0]);
    
                // Определяем статус документа
                if (!$expirationDate) {
                    $userIssues['documents'][$type] = 'empty';
                } elseif ($daysLeft <= 0) {
                    $userIssues['documents'][$type] = 'expired';
                } elseif ($daysLeft <= 3) {
                    $userIssues['documents'][$type] = '3_days';
                } elseif ($daysLeft <= 7) {
                    $userIssues['documents'][$type] = '7_days';
                }
            }
    
            // Если у пользователя есть документы с проблемами, добавляем его в $badUsers
            if (!empty($userIssues['documents'])) {
                $badUsers[] = $userIssues;

                // Уведомляем каждого пользователя о проблемных документах
                if ($user['UF_TELEGRAM_ID'] != null){
                    // self::notifyBadUser($user, $userIssues);
                    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                    // !!!!!!!!! раскоментировать после заполнения !!!!!!!!!!!!!!!!!!!!!!
                    // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                }   
            }
        }
    
        // отправить уведомление Модератору
        self::sendReportToManager($badUsers); 

        return $badUsers;
    }

    // Уведомляем каждого пользователя о проблемных документах
    public static function notifyBadUser ($user, $userIssues){
        $message = "${user['NAME']}, выходит срок действия твоих документов:\n";
        // $message['keyboard'] = null;
        $keyboard = null;

        // Сопоставление кодов документов с их русскими названиями
        $documentNames = [
            'sanitary'     => 'санитарная книжка',
            'passport'     => 'патент',
            'registration' => 'прописка'
        ];

        // Формируем строки для каждого проблемного документа
        foreach ($userIssues['documents'] as $document => $status) {
            $documentName = $documentNames[$document] ?? $document;

            $statusMessage = match ($status) {
                'empty' => "не заполнен",
                '7_days' => "истекает через 7 дней",
                '3_days' => "истекает через 3 дня",
                'expired' => "уже истёк"
            };

            // Добавляем строку о каждом документе в сообщение
            $message .= "- Документ $documentName $statusMessage\n";
        }
// echo "<pre>";
// var_dump($message);
// echo "</pre>";
            $tg_id = $user['UF_TELEGRAM_ID'];
            Notifier::sendMessageToOne($tg_id, $message, $keyboard);
    }

    // отправить уведомление Модератору о проблемах с документами
    public static function sendReportToManager($badUsers)
    {
        $reportText = "Отчёт по истекающим и отсутствующим документам сотрудников:\n\n";
    
        // Сопоставление кодов документов с их русскими названиями
        $documentNames = [
            'sanitary'     => 'санитарная книжка',
            'passport'     => 'патент',
            'registration' => 'прописка'
        ];

        foreach ($badUsers as $userIssue) {
            $user = $userIssue['user'];
            $reportText .= "{$user['ID']}. {$user['LAST_NAME']} {$user['NAME']}:\n";
    
            foreach ($userIssue['documents'] as $document => $status) {
                // Получаем русское название документа
                $documentName = $documentNames[$document] ?? $document;

                $statusMessage = match ($status) {
                    'empty'   => "не заполнен",
                    '7_days'  => "до истечения меньше <b>7</b> дней",
                    '3_days'  => "до истечения меньше <b>3</b> дня",
                    'expired' => "уже истёк"
                };
                $reportText .= "- <b>$documentName</b> $statusMessage\n";
            }
    
            $reportText .= "\n";
        }

        if (!empty($reportText)) {

            /* разбивка сообщения на строки, т.к. лимит 4096 байт */
            // Разбиваем сообщение по строкам и отправляем порциями
            $maxLength = 4000;
            $messageParts = [];
            $currentPart = "";

            foreach (explode("\n", $reportText) as $line) {
                // Добавляем строку к текущей части, если она не превышает максимальную длину
                if (mb_strlen($currentPart . $line . "\n") < $maxLength) {
                    $currentPart .= $line . "\n";
                } else {
                    // Если превышает, сохраняем текущую часть и начинаем новую
                    $messageParts[] = $currentPart;
                    $currentPart = $line . "\n";
                }
            }

            // Добавляем последний остаток сообщения, если он не пуст
            if (!empty(trim($currentPart))) {
                $messageParts[] = $currentPart;
            }

            /* разбивка сообщения на строки, т.к. лимит 4096 байт */

            // Отправляем отчёт руководителю
                $moderators = Helper::whoModerators();
                foreach ($messageParts as $messagePart) {
                    $message['text'] = $messagePart;
// echo  '<pre>';
// var_dump ($messagePart);
// echo  '</pre>';

                   Notifier::informModerator (0, $message, '', $moderators);
                }
            }
    }


}