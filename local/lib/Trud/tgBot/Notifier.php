<?php

namespace Trud\TgBot;

use CIBlockElement;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

use Trud\IBlock\InfoIblock;
use Trud\Shifts\ShiftInfo;
use Trud\TgBot\Bot;
use Trud\TgBot\BotLoger;
use Trud\TgBot\MessageBuilder;

// CModule::IncludeModule("iblock");


class Notifier 
{
    public static function remindThemShift ($shiftId, $message, $newTgDialogStatus, $logEvent) {
        $bot = new Bot();

        // получим данные о смене
        $startDate  = ShiftInfo::getPropValue($shiftId, 'SHIFT_START');
        $clientID   = ShiftInfo::getPropValue($shiftId, 'CLIENT');
        $userList   = ShiftInfo::getPropValue($shiftId, 'WORKERS');

        //  Получим юзеров с помощью getList:
        $filter = ['ID' => $userList];
        $select = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING', 'UF_CARMA_SUMM', 'UF_TELEGRAM_ID', 'UF_TG_DIALOG_STATUS'];

        $res = UserTable::getList(['select' => $select, 'filter' => $filter]);
        $users = $res->fetchAll();

        // // имя заказчика
        $rsElement = CIBlockElement::GetByID($clientID);
        $arElement = $rsElement->GetNext();
        $clienName = $arElement['NAME'];
// echo "<pre>";
// var_dump($userList);

        $user = new \CUser;
        foreach ($users as $worker) {
            $tg_id  = $worker['UF_TELEGRAM_ID']; 
            if (!$tg_id) continue; // если не зареган в телеграме

            $name   = $worker['NAME'];

            // занулить последнее (предыдущее) соообщение
            // $bot->resetLastInvite($tg_id);

            // сборка и отправка сообщения
            $messageID = $bot->sendMessage($tg_id, $message['text'], $message['keyboard']);

            // меняем статус у пользователя, что отправлено сообщение
            // $user = new \CUser;
            $user->Update($worker['ID'], ['UF_TG_DIALOG_STATUS' => $newTgDialogStatus]);
// echo"<pre>";
// var_dump($tg_id);            
            // лог чата, что сообщение отправлено 
            $logMessage = $logEvent . " Смена: $shiftId, начало:  $startDate, заказчик: $clienName";
            BotLoger::logChat($tg_id, $logMessage);

            // номер сообщения - в json-статус диалога 
            BotLoger::addUserStatus($tg_id, $messageID);
        }

    }

    public static function informModerator($shiftId, $message, $newTgDialogStatus, $moderators)  {
        $bot = new Bot();

        // получим данные о модераторах
        // $startDate  = ShiftInfo::getPropValue($shiftId, 'SHIFT_START');
        // $clientID   = ShiftInfo::getPropValue($shiftId, 'CLIENT');
        $userList   = $moderators;

        //  Получим юзеров с помощью getList:
        $filter = ['ID' => $userList];
        $select = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING', 'UF_CARMA_SUMM', 'UF_TELEGRAM_ID', 'UF_TG_DIALOG_STATUS'];

        $res = UserTable::getList(['select' => $select, 'filter' => $filter]);
        $users = $res->fetchAll();

        $user = new \CUser;
        foreach ($users as $worker) {
            $tg_id  = $worker['UF_TELEGRAM_ID']; 
            if (!$tg_id) continue; // если не зареган в телеграме

            $name   = $worker['NAME'];

            // занулить последнее (предыдущее) соообщение
            // $bot->resetLastInvite($tg_id);

            // сборка и отправка сообщения
            $messageID = $bot->sendMessage($tg_id, $message['text'], $message['keyboard']);

            // меняем статус у пользователя, что отправлено сообщение
            // $user = new \CUser;
            // $user->Update($worker['ID'], ['UF_TG_DIALOG_STATUS' => $newTgDialogStatus]);
            
            // лог чата, что сообщение отправлено 
            // $logMessage = $logEvent . " Смена: $shiftId, начало:  $startDate, заказчик: $clienName";
            // BotLoger::logChat($tg_id, $logMessage);

            // номер сообщения - в json-статус диалога 
            BotLoger::addUserStatus($tg_id, $messageID);
        }

        
    }

    public static function sendMessageToOne($tg_id, $message, $keyboard) {
        $bot = new Bot();
        $messageID = $bot->sendMessage($tg_id, $message, $keyboard);

        // лог чата, что сообщение отправлено 
        $logMessage = "Уведомление: " . $message;
        BotLoger::logChat($tg_id, $logMessage);

        // return $messageID;
    }


}