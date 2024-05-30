<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/lib/Trud/tgBot/botConfig.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/lib/Trud/tgBot/Bot.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/lib/Trud/tgBot/User.php';

use Trud\TgBot\Bot;
use Trud\TgBot\User;
use Bitrix\Main\Type\DateTime;


$logsFileErrors = $_SERVER['DOCUMENT_ROOT'] . '/local/logs/botErrors.log';
$logsFile       = $_SERVER['DOCUMENT_ROOT'] . '/local/logs/botLogs.log';

// Получаем входящие данные из вебхука
$update = json_decode(file_get_contents('php://input'), true);

// Проверяем, что данные получены
if (!$update) {
    file_put_contents($logsFileErrors, 'Error: No data received from Telegram' . PHP_EOL, FILE_APPEND);
    exit;
}

// Логирование полученных данных
logUpdate($update, $logsFile);


// Создаем объект User
$user = new User();

// дебуг: Логирование объекта User
// file_put_contents($logsFile, 'Created User object' . PHP_EOL, FILE_APPEND);


// Создаем объект Bot и передаем ему объект User
$bot = new Bot($user);

// дебуг: Логирование объекта Bot
// file_put_contents($logsFile, 'Created Bot object' . PHP_EOL, FILE_APPEND);


// Обрабатываем входящее обновление
$bot->processUpdate($update);

// дебуг: Логирование после обработки
// file_put_contents($logsFile, 'Processed update' . PHP_EOL, FILE_APPEND);



/* ============ Функция логирования прямо здесь ============== */
function logUpdate ($update, $logsFile) {
    $currentTime = date('d.m.Y H:i:s');
    $logData = sprintf (
        'update: %s, chat: %s, user: %s, text: %s',
        $currentTime,
        $update['message']['chat']['id'] ?? 'unknown',
        $update['message']['from']['username'] ?? 'unknown',
        $update['message']['text'] ?? 'no text'
    );
    
    file_put_contents($logsFile, $logData . PHP_EOL, FILE_APPEND);
}
/* ============================================================ */
