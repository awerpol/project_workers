<?php

$_SERVER['DOCUMENT_ROOT'] = "/home/bitrix/www";
// $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true); 
// define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


\Trud\Helpers\Helper::shiftsChangeStageByCron();
