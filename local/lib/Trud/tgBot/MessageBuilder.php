<?php

namespace Trud\TgBot;

use Bitrix\Main\Context;
use Trud\IBlock\InfoIblock;
use Trud\Shifts\ShiftInfo;
use Trud\Users\Lists;
use CIBlockElement;


class MessageBuilder 
{
    // Уведомление о записи на смену
    public static function workersNotification($name, $shiftId) {
        $shift  = ShiftInfo::getAllInfo($shiftId);

        $startDate = $shift["SHIFT_START"];
        $clientID  = $shift["CLIENT"];
        // имя заказчика
        $clienName = self::getClientName($clientID);

        $message['text'] = $name . ", вы приглашены на смену \n" .
        "в <b>" . $clienName . "</b>\n" .
        "начало: <b>" . $startDate . "</b>\n" .
        "<b>Подтвердите, что идете</b>";

        $message['keyboard'] = [ 'inline_keyboard' => [[
            ['text' => '✅ иду',    'callback_data' => 'yesigo_'.$shiftId],
            ['text' => '❌ не иду', 'callback_data' => 'cancel_'.$shiftId],
        ]]];

        return $message;
    }

    // Уведомление за 2 ча до смены
    public static function notification2h($name, $shiftId) {
        $shift  = ShiftInfo::getAllInfo($shiftId);

        $startDate = $shift["SHIFT_START"];
        $clientID  = $shift["CLIENT"];
        // имя заказчика
        $clienName = self::getClientName($clientID);

        $message['text'] = "Cмена начнется через 2 часа \n" .
        "в <b>" . $clienName . "</b>\n" .
        "начало: <b>" . $startDate . "</b>\n" .
        "<b>Подтвердите, что идете</b>";

        $message['keyboard'] = [ 'inline_keyboard' => [[
            ['text' => '✅ иду',    'callback_data' => 'yesigo_'.$shiftId],
            ['text' => '❌ не иду', 'callback_data' => 'cancel_'.$shiftId],
        ]]];

        return $message;
    }
    
    // Уведомление за 1.5 часа до смены (только администратору)
    public static function notification90m($name, $shiftId) {
        $shift  = ShiftInfo::getAllInfo($shiftId);

        $startDate = $shift["SHIFT_START"];
        $clientID  = $shift["CLIENT"];
        $clienName = self::getClientName($clientID); // имя заказчика
        $workers = $shift["WORKERS"];
        $names = Lists::getNames($workers);
        
        $workersList = self::whoIsComing($names);

        $message['text'] = "Cмена через 1,5 часа: \n" .
        "<b>" . $clienName . "</b>\n" .
        "начало: <b>" . $startDate . "</b>\n" .
        "<b>Список работников:</b>\n" . $workersList;

        // $message['keyboard'] = null;
        $message['keyboard'] = [ 'inline_keyboard' => [[
        //     ['text' => '✅ иду',    'callback_data' => 'yesigo_'.$shiftId],
        //     ['text' => '❌ не иду', 'callback_data' => 'cancel_'.$shiftId],
            ['text' => 'Перейти на сайт', 'url' => "https://trud.awerpol.ru/shift/$shiftId/"]
        ]]];

        return $message;
    }

    // Сообщение, когда подтвердил участие
    public static function workerConfirmed($shiftId) {
        $shift  = ShiftInfo::getAllInfo($shiftId);
        
        $startDate = $shift["SHIFT_START"];
        $clientID  = $shift["CLIENT"];
         // имя заказчика
        $clienName = self::getClientName($clientID);

        $message['text'] = "Ждем вас в <b>" . $clienName . "</b>\n" . 
        "начало: <b>" . $startDate . "</b>\n";

        $message['keyboard'] = [ 'inline_keyboard' => [[
            ['text' => '👣 Как добраться', 'callback_data' => 'address_'.$shiftId],
            ['text' => '👥 Кто идет', 'callback_data' => 'collegues_'.$shiftId],
        ]]];

        return $message;
    }

    // адрес (схема проезда)
    public static function getAddress($shiftId) {
        $shift   = ShiftInfo::getAllInfo($shiftId);
        $clientID  = $shift["CLIENT"];
        // адрес заказчика
        $address = self::getClientAddress($clientID);

        $message['text'] = "Адрес: <b>" . $address . "</b>";

        $message['keyboard'] = [ 'inline_keyboard' => [[
            ['text' => '🔙 Назад', 'callback_data' => 'yesigo_'.$shiftId],
        ]]];
        return $message;
    }

    // сообщение со списком тех, кто еще идет
    public static function getCollegues($shiftId) {
        $shift   = ShiftInfo::getAllInfo($shiftId);
        $workers = $shift["WORKERS"];

        $names = Lists::getNames($workers);
        $workersList = self::whoIsComing($names);

        $message['text'] = "<b>Кто идет:</b>\n" . $workersList;
        $message['keyboard'] = [ 'inline_keyboard' => [[
            ['text' => '🔙 Назад', 'callback_data' => 'yesigo_'.$shiftId],
        ]]];
        return $message;
    }

    private static function whoIsComing($names) {
        // Группировка по статусам
        $groupedNames = [
            // 'default' => [],
            // 'invited' => [],
            'refused' => [],
            'confirmed' => [],
            'other' => [], // Для остальных статусов
        ];

        foreach ($names as $id => $info) {
            $status = $info['STATUS'];
            $fullName = $info['NAME'] . ' ' . $info['LAST_NAME'];

            if (isset($groupedNames[$status])) {
                $groupedNames[$status][] = $fullName;
            } else {
                $groupedNames['other'][] = $fullName;
            }
        }
            
        // $names = implode("\n", $names);

        $text = "<u>Не ответили:\n</u><b>" .
        implode("\n", $groupedNames['other']) .
        "</b>\n" .
        "<u>отказались:\n</u><b>" .
        implode("\n", $groupedNames['refused']) . 
        "</b>\n" .
        "<u>подтвердили:\n</u><b>" .
        implode("\n", $groupedNames['confirmed']) .
        "</b>";

        return $text;
    }

    private static function getClientName($clientID) {
        $rsElement = \CIBlockElement::GetByID($clientID);
        $arElement = $rsElement->GetNext();
        return $arElement["NAME"];
    }

    private static function getClientAddress($clientID) {
        $iblockId = InfoIblock::getIdByCode('CLIENTS');
        $rsProperties = CIBlockElement::GetProperty($iblockId, $clientID);

        while ($arProperty = $rsProperties->Fetch()) {
            if ($arProperty['CODE'] == 'ADDRESS') {
                $address = $arProperty['VALUE'];
            }
        }
        return $address;
    }
}