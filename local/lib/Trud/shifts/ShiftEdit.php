<?php

namespace Trud\Shifts;

use Bitrix\Main\Loader;
use CIBlockElement;
use Trud\IBlock\InfoIblock;

use Trud\Shifts\ShiftInfo;
use Trud\Users\Lists;


class ShiftEdit
{
    // изменяем стадию смены
    public static function updateStage($shiftId, $newStage) 
    {
        Loader::includeModule('iblock');

        $fields = [
            'SHIFT_STAGE' => \Trud\IBlock\InfoIblock::getFieldIdByXML_ID($newStage) // айдишник по XML_ID поля
        ];

        CIBlockElement::SetPropertyValuesEx($shiftId, false, $fields);

        // если в архив, то всех освободить
        if ($newStage == 'ARCHIVE') {
            self::freeWorkers($shiftId);
        }
    }

    // высвобождаем рабочих из смены (например, при закрытии смены)
    public static function freeWorkers($shiftId)
    {
        $workers = ShiftInfo::getPropValue($shiftId, 'WORKERS');

        Lists::makeThemFree($workers); 
    }

    // дополняем "черный список" смены
    public static function addToBlackList($shiftId, $badWorkers) 
    {
        Loader::includeModule('iblock');

        $blackList = ShiftInfo::getPropValue($shiftId, 'BLACK_LIST_SHIFT'); // было 
        $blackList = array_merge($badWorkers, $blackList);

        $fields = ['BLACK_LIST_SHIFT' => $blackList];
        CIBlockElement::SetPropertyValuesEx($shiftId, false, $fields);
    }
}

