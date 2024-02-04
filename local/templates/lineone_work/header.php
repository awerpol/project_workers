<?
/**
 * @global $APPLICATION
 * @global $USER
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Page\Asset;

global $APPLICATION;

?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <title><? $APPLICATION->ShowTitle() ?></title>
    <?
    $APPLICATION->ShowHead();
    $APPLICATION->ShowHead();
    $APPLICATION->AddHeadString('<meta charset="UTF-8">');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/assets/css/bootstrap.min.css");
    $APPLICATION->AddHeadString('<script type="text/javascript" src="'.SITE_TEMPLATE_PATH.'/moscow/js/jquery-3.5.1.min.js" data-skip-moving="true"></script>');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/moscow/js/slick.js");
    ?>
</head>
<body>
<? $APPLICATION->ShowPanel() ?>

