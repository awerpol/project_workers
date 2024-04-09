<?php

namespace Trud\IBlock;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;

class InfoIblock
{
    public static function getIdByCode(string $code): ?int
    {
        Loader::includeModule('iblock');

        $arIblock = IblockTable::getList([
            'filter' => ['CODE' => $code],
            'select' => ['ID']
        ])->fetch();

        return $arIblock[ 'ID' ];
    }

    // захардкодил
    public static function getFieldIdByXML_ID(string $code): ?int
    {
        switch ($code) {
            case "FORMING": return 6;
            case "IN_WORK": return 7;
            case "ARCHIVE": return 8;
            default:        return null; 
        }
    }

    public static function getCarmaCaseFielIdByXML_ID(string $code) : ?int 
    {
        Loader::includeModule('iblock');
        /* получаем список вариантов оценки */
        $iblockId = InfoIblock::getIdByCode('KARMA_ACT');

        $arValues = [];

        $rsEnum = \CIBlockPropertyEnum::GetList(
            ['SORT' => 'ASC'],
            ['IBLOCK_ID'     => $iblockId, 'PROPERTY_CODE' => 'CASE']
        );
        
        while ($arEnum = $rsEnum->GetNext()) {
            if ($arEnum['XML_ID'] == $code) {
                $res = $arEnum['ID'];
                break;
            }

        }

        return $res;
    }


}

