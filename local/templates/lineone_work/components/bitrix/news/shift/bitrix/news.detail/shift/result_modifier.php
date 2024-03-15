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

            $arResult["WORKERS"][] = $arUser;
    }
}

// кол-во М/Ж
$arResult["MEN_COUNT"] = $men_qty;
$arResult["WOMEN_COUNT"] = $women_qty;