<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

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

$arParams[ 'USE_SHARE' ] = (string) ($arParams[ 'USE_SHARE' ] ?? 'N');
$arParams[ 'USE_SHARE' ] = $arParams[ 'USE_SHARE' ] === 'Y' ? 'Y' : 'N';
$arParams[ 'SHARE_HIDE' ] = (string) ($arParams[ 'SHARE_HIDE' ] ?? 'N');
$arParams[ 'SHARE_HIDE' ] = $arParams[ 'SHARE_HIDE' ] === 'Y' ? 'Y' : 'N';
$arParams[ 'SHARE_TEMPLATE' ] = (string) ($arParams[ 'SHARE_TEMPLATE' ] ?? 'N');
$arParams[ 'SHARE_HANDLERS' ] ??= [];
$arParams[ 'SHARE_HANDLERS' ] = is_array($arParams[ 'SHARE_HANDLERS' ]) ? $arParams[ 'SHARE_HANDLERS' ] : [];
$arParams[ 'SHARE_SHORTEN_URL_LOGIN' ] = (string) ($arParams[ 'SHARE_SHORTEN_URL_LOGIN' ] ?? 'N');
$arParams[ 'SHARE_SHORTEN_URL_KEY' ] = (string) ($arParams[ 'SHARE_SHORTEN_URL_KEY' ] ?? 'N');
