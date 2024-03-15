<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;

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

// Получаем список пользователей
$arResult['USERS'] = UserTable::getList([
    'select' => ['ID', 'NAME', 'LAST_NAME', 'EMAIL', 'PERSONAL_GENDER'],
    'order' => ['LAST_NAME' => 'ASC', 'NAME' => 'ASC'],
]);

echo "<pre>";
var_dump($arResult["USERS"]);
echo "</pre>";
// Include table template
// $this->IncludeComponentTemplate('table');

?>

