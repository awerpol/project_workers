<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Type\DateTime;

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

////////////////////////////////////////////
echo "<pre>";
var_dump($arResult);
echo "</pre>";
////////////////////////////////////////////

// время начала и окончания смены
if ($arResult["PROPERTIES"]["SHIFT_START"]["VALUE"]) {
    $time_start = new DateTime($arResult["PROPERTIES"]["SHIFT_START"]["VALUE"]);
    $time_end = $arResult["PROPERTIES"]["SHIFT_END"]["VALUE"] ?
        new DateTime($arResult["PROPERTIES"]["SHIFT_END"]["VALUE"]) :
        $time_end;
    $arResult["SHIFT_PERIOD"] = $time_start->format("H:i") . " - " . $time_end->format("H:i");
} else {
    $arResult["SHIFT_PERIOD"] = "время не задано";
} 


$men_qty = $wemen_qty=0; // кол-во М/Ж

// // массив пользователей (для таблицы)
if ($arUserIds = $arResult["PROPERTIES"]["WORKERS"]["VALUE"]) {
    foreach ($arUserIds as $userId) {
        $arUser = CUser::GetByID($userId)->Fetch();
    
            if ($arUser["PERSONAL_GENDER"] == "M") {
                $arUser["PERSONAL_GENDER"] = "М";
                $men_qty++;
            } elseif ($arUser["PERSONAL_GENDER"] == "F") {
                $arUser["PERSONAL_GENDER"] = "Ж";
                $wemen_qty++;
            } else {
                $arUser["PERSONAL_GENDER"] = "-";
            }

            $arResult["WORKERS"][] = $arUser;
    }
}


// кол-во М/Ж
$arResult["MEN_COUNT"] = $men_qty;
$arResult["WEMEN_COUNT"] = $wemen_qty;