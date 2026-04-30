<?php

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true); 
// define('CHK_EVENT', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Trud\TgBot\Bot;
use Trud\TgBot\BotLoger;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Получаем входящие данные из вебхука
$update = json_decode(file_get_contents('php://input'), true);

if (!$update) {
    BotLoger::logError('No data received from Telegram');
    exit;
}
// Логирование полученных данных
BotLoger::logUpdate($update);

$bot = new Bot();

// Обрабатываем входящее обновление
$bot->processUpdate($update);
// BotLoger::logSystem('Update processed');

echo 'OK';