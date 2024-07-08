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

        $message['text'] = "Кто идет: \n" .

        "<u>Не ответили:\n</u><b>" .
        implode("\n", $groupedNames['other']) .
        "</b>\n" .
        "<u>отказались:\n</u><b>" .
        implode("\n", $groupedNames['refused']) . 
        "</b>\n" .
        "<u>подтвердили:\n</u><b>" .
        implode("\n", $groupedNames['confirmed']) .
        "</b>";

        $message['keyboard'] = [ 'inline_keyboard' => [[
            ['text' => '🔙 Назад', 'callback_data' => 'yesigo_'.$shiftId],
        ]]];
        return $message;
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