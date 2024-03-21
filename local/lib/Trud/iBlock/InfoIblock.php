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
}

