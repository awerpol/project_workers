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
}

