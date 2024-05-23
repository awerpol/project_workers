<?php


namespace Trud\TgBot;

use WeStacks\TeleBot\TeleBot;
// use WeStacks\TeleBot\Handlers\CommandHandler;

require_once( $_SERVER['DOCUMENT_ROOT'] . '/home/bitrix/www/local/vendor/autoload.php');


class Bot
{
    protected $bot;
    protected $chatId;

    public function __construct($token, $chatId)
    {
        // $token = "7148850888:AAE4HSsbl-Sk6jkjpuafRA2jnioVFQgJ-Uk";
        // $chatId = "512488094";

        // Инициализация бота с заданным токеном
        $this->bot = new TeleBot([
            'token' => $token
        ]);
        $this->chatId = $chatId;
    }

    public function sendMessageToBot($message)
    {
        // Отправка сообщения в указанный чат
        $this->bot->sendMessage([
            'chat_id' => $this->chatId,
            'text' => $message
        ]);
    }
}


// Проверка аргументов командной строки
if ($argc !== 2) {
    echo "Usage: php Bot.php <message>\n";
    exit(1);
}

// Получение аргументов
$token = "7148850888:AAE4HSsbl-Sk6jkjpuafRA2jnioVFQgJ-Uk";
$chatId = "512488094";
$message = $argv[1];

$bot = new Bot($token, $chatId);

// Отправка сообщения
$bot->sendMessageToBot($message);