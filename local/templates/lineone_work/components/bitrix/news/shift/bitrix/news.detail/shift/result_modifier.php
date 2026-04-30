<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Type\DateTime;

use Trud\IBlock\InfoIblock;


/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogTopComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */


$men_qty = $women_qty=0; // кол-во М/Ж
// // массив пользователей (для таблицы)
$arResult["LIST_USER_ID"] = [];
if ($arUserIds = $arResult["PROPERTIES"]["WORKERS"]["VALUE"]) {
    foreach ($arUserIds as $userId) {
        $arUser = CUser::GetByID($userId)->Fetch();
            $arResult["LIST_USER_ID"][] = $arUser["ID"];
            if ($arUser["PERSONAL_GENDER"] == "M") {
                $arUser["PERSONAL_GENDER"] = "М";
                $men_qty++;
            } elseif ($arUser["PERSONAL_GENDER"] == "F") {
                $arUser["PERSONAL_GENDER"] = "Ж";
                $women_qty++;
            } else {
                $arUser["PERSONAL_GENDER"] = "-";
            }

            // цвет кружка статуса телеграм в таблице
            switch ($arUser["UF_TG_DIALOG_STATUS"]) {
                case 'invited':
                    $arUser["TG_STATUS"] = "bg-warning"; // приглашение отправлено
                    break;
                case 'confirmed':
                    $arUser["TG_STATUS"] = "bg-success"; // подтвердил участие
                    break;
                case 'refused':
                    $arUser["TG_STATUS"] = "bg-error"; // отказался
                    break;
                default:
                    $arUser["TG_STATUS"] = "bg-current"; // по умолчанию синий
                    break;
            }
            if (!$arUser["UF_TELEGRAM_ID"]) $arUser["TG_STATUS"] = "bg-slate-500"; // нет id_телеграмм

            $arResult["WORKERS"][] = $arUser;
    }
}

// кол-во М/Ж
$arResult["MEN_COUNT"]   = $men_qty;
$arResult["WOMEN_COUNT"] = $women_qty;



/* получаем список вариантов оценки */
$iblockId = InfoIblock::getIdByCode('KARMA_ACT');

$arValues = [];

$rsEnum = CIBlockPropertyEnum::GetList(
    ['SORT' => 'ASC'],
    ['IBLOCK_ID'     => $iblockId, 'PROPERTY_CODE' => 'CASE']
);

while ($arEnum = $rsEnum->GetNext()) {

    $parts = explode('_', $arEnum['XML_ID']);
    $sign = $parts[0];
    $num = intval($parts[1]);
    if ($sign === "MINUS") {
        $num = -$num;
    }

    $arValues[] = [
        'ID'    => $arEnum['ID'],
        'VALUE' => $arEnum['XML_ID'],
        'NUM'   => $num,
        'NAME'  => $arEnum['VALUE'],
        'ALL'   => $arEnum
    ];
}

$arResult["CARMA_CASES"] = $arValues;
/* -------------------------------- */
