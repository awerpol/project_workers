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
foreach($arResult["ITEMS"] as $key => $arItem){

    // время начала и окончания смены
    if ($arItem["PROPERTIES"]["SHIFT_START"]["VALUE"] && $arItem["PROPERTIES"]["SHIFT_END"]["VALUE"]) {
        $time_start = new DateTime($arItem["PROPERTIES"]["SHIFT_START"]["VALUE"]);
        $time_end = $arItem["PROPERTIES"]["SHIFT_END"]["VALUE"] ?
            new DateTime($arItem["PROPERTIES"]["SHIFT_END"]["VALUE"]) :
            $time_end;

        // $arResult["ITEMS"][$key]["SHIFT_PERIOD"] = $time_start->format("H:i") . " - " . $time_end->format("H:i");
        // меняем на день/ночь
        if ((int)$time_start->format('H') >= 15) {
            $arResult["ITEMS"][$key]["SHIFT_PERIOD"] = "ночь";
        } else {
            $arResult["ITEMS"][$key]["SHIFT_PERIOD"] = "день";
        }
        $arResult["ITEMS"][$key]["SHIFT_DATE"] = $time_start->format("d.m.Y");
    } else {
        $arResult["ITEMS"][$key]["SHIFT_PERIOD"] = "время не задано";
        $arResult["ITEMS"][$key]["SHIFT_DATE"] = "-";
    }


    $men_qty = $wemen_qty=0; // кол-во М/Ж

    // // массив пользователей (для таблицы)
    if ($arUserIds = $arItem["PROPERTIES"]["WORKERS"]["VALUE"]) {
        foreach ($arUserIds as $userId) {
            $arUser = CUser::GetByID($userId)->Fetch();

            if ($arUser["PERSONAL_GENDER"] == "M") {
                // $arUser["PERSONAL_GENDER"] = "М";
                $men_qty++;
            } elseif ($arUser["PERSONAL_GENDER"] == "F") {
                // $arUser["PERSONAL_GENDER"] = "Ж";
                $wemen_qty++;
            } else {
                $arUser["PERSONAL_GENDER"] = "-";
            }

            // $arItem["WORKERS"][] = $arUser;
        }
    }

    // кол-во М/Ж
    $arResult["ITEMS"][$key]["MEN_COUNT"] = $men_qty;
    $arResult["ITEMS"][$key]["WOMEN_COUNT"] = $wemen_qty;
}

CModule::IncludeModule("iblock");

$iblockId = InfoIblock::getIdByCode('CLIENTS');

// получаем всех клиентов
$rsClients = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $iblockId), false, false, array('ID', 'NAME'));
$arResult["CLIENTS"] = array();

while ($arClient = $rsClients->Fetch()) {
    $arResult["CLIENTS"][] = array(
        "ID" => $arClient['ID'],
        "NAME" => $arClient['NAME']
    );
}
