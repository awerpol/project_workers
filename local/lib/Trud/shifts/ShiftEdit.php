<?php

namespace Trud\Shifts;

use Bitrix\Main\Loader;
use CIBlockElement;


class ShiftEdit
{
    public static function updateStage($id, $newStage) 
    {
        Loader::includeModule('iblock');

        $fields = [
            'SHIFT_STAGE' => \Trud\IBlock\InfoIblock::getFieldIdByXML_ID($newStage) // айдишник по коду поля
        ];

        CIBlockElement::SetPropertyValuesEx($id, false, $fields);
    }

}

