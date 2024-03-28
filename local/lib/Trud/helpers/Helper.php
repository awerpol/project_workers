<?php

namespace Trud\Helpers;

use Bitrix\Main\Loader;
use CIBlockElement;
use Trud\IBlock\InfoIblock;

class Helper
{
    public static function getPropValue($iblockCode, $elementId, $field)
    {
        $iblockId = InfoIblock::getIdByCode($iblockCode);

        $rsProperties = CIBlockElement::GetProperty($iblockId, $elementId);
        
        while ($arProperty = $rsProperties->Fetch()) {
            if ($arProperty['CODE'] == $field) {        // Находим нужное свойство
                if ($arProperty['MULTIPLE'] === 'Y') {  // Если поле множественное
                    $result[] = $arProperty['VALUE'];   // Добавляем значение в массив
                } else {
                    $result = $arProperty['VALUE'];     // Значение поля
                }
            }
        }

        return $result;
    }
}