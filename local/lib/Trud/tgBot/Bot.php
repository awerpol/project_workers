<?php


namespace Trud\TgBot;

use WeStacks\TeleBot\TeleBot;
// use WeStacks\TeleBot\Handlers\CommandHandler;

// require_once $_SERVER['DOCUMENT_ROOT'] . '/local/lib/Trud/tgBot/botConfig.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . '/home/bitrix/www/local/vendor/autoload.php'; // для запуски извне битрикс
// ============ прописано в init.php ==========================================


class Bot
{
    private $TOKEN;
	private $BOT_NAME;
	private $inited = false;
	private $bot;
	private $crmUserId = 0;
    
	function __construct() {
		global $botConfig;

		$this->TOKEN = $botConfig['token'];
		$this->BOT_NAME = $botConfig['botName'];
		$this->init();
	}
	
	private function init()
    {
		$tBot = new TeleBot([
			'token'      => $this->TOKEN,
			'name'       => $this->BOT_NAME,
			'api_url'    => 'https://api.telegram.org/bot{TOKEN}/{METHOD}',
			'exceptions' => true,
			'async'      => false,
			'handlers'   => []
		]);
        
		$user = $tBot->getMe();
		if($user AND $user->id) {
			$this->inited = true;
			$this->bot = $tBot;
		}
	}





    public function sendTextMessage($chatId, $text)
    {
        $this->bot->sendMessage([
            'chat_id' => $chatId,
            'text' => $text
        ]);
    }



}


// // Проверка аргументов командной строки
// if ($argc !== 2) {
//     echo "Usage: php Bot.php <message>\n";
//     exit(1);
// }

// // Получение аргументов
// $token = "7148850888:AAE4HSsbl-Sk6jkjpuafRA2jnioVFQgJ-Uk";
// $chatId = "512488094";
// $message = $argv[1];

// $bot = new Bot($token, $chatId);

// // Отправка сообщения
// $bot->sendMessageToBot($message);