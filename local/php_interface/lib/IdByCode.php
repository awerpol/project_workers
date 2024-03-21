<?

namespace Local\Lib;

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;

class IdByCode
{
    public static function getIdByCode($code)
    {
        Loader::includeModule('iblock');

        $iblock = IblockTable::getList([
            'filter' => ['CODE' => $code],
            'select' => ['ID']
        ])->fetch();

        return $iblock ? $iblock['ID'] : null;
    }
}

