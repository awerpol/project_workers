<?php

namespace Trud\Shifts;

use Bitrix\Main\Loader;
use CIBlockElement;
use Trud\IBlock\InfoIblock;


class ShiftEdit
{
    public static function updateStage($shiftId, $newStage) 
    {
        Loader::includeModule('iblock');

        $fields = [
            'SHIFT_STAGE' => \Trud\IBlock\InfoIblock::getFieldIdByXML_ID($newStage) // айдишник по XML_ID поля
        ];

        CIBlockElement::SetPropertyValuesEx($shiftId, false, $fields);

        // если в архив, то всех освободить
        if ($newStage == 'ARCHIVE') {
            self::freeUsers($shiftId);
        }
    }

    public static function freeUsers($shiftId)
    {
        // Loader::includeModule('iblock');
        $user = new \CUser;

        $iblockId = InfoIblock::getIdByCode('SHIFT_BEING_FORMED');

        // получить пользователей из этого инфоблока и освободить;
        $rsProperties = CIBlockElement::GetProperty($iblockId, $shiftId);
        
        while ($arProperty = $rsProperties->Fetch()) {
            if ($arProperty['CODE'] == 'WORKERS') { // Проверяем, что это нужное свойство

                $userId = $arProperty['VALUE']; // Значение поля WORKERS
                $user->Update($userId, ['UF_BUSY' => 0]);
            }
        }

        // return $userUpdateResult;
    }
}

