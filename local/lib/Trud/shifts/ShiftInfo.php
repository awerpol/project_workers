<?php

namespace Trud\Shifts;

use Bitrix\Main\Loader;
use CIBlockElement;
use Trud\IBlock\InfoIblock;

class ShiftInfo
{
    public static function getPropValue($shiftId, $field)
    {
        $iblockId = InfoIblock::getIdByCode('SHIFT_BEING_FORMED');

        $rsProperties = CIBlockElement::GetProperty($iblockId, $shiftId);
        
        while ($arProperty = $rsProperties->Fetch()) {
            if ($arProperty['CODE'] == $field) {
                if ($arProperty['MULTIPLE'] === 'Y') {
                    $result[] = $arProperty['VALUE'];  
                } else {
                    $result = $arProperty['VALUE']; 
                }
            }
        }

        return $result;
    }

}