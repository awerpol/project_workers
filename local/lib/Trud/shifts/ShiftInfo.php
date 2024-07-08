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

    public static function getAllInfo($shiftId)
    {
        $iblockId = InfoIblock::getIdByCode('SHIFT_BEING_FORMED');
        $result = [];

        // Получение базовых полей элемента
        $rsElement = CIBlockElement::GetByID($shiftId);
        if ($arElement = $rsElement->GetNext()) {
            $result['ID'] = $arElement['ID'];
            $result['NAME'] = $arElement['NAME'];
        }
 
        // Получение свойств элемента
        $rsProperties = CIBlockElement::GetProperty($iblockId, $shiftId, array("sort" => "asc"), array("EMPTY" => "N"));
        while ($arProperty = $rsProperties->Fetch()) {
            if ($arProperty['MULTIPLE'] == 'Y') {
                if (!isset($result[$arProperty['CODE']])) {
                    $result[$arProperty['CODE']] = [];
                }
                $result[$arProperty['CODE']][] = $arProperty['VALUE'];
            } else {
                $result[$arProperty['CODE']] = $arProperty['VALUE'];
            }        
        }

        return $result;
    }
}