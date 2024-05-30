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
	// private $crmUserId = 0;
	private $user;
    // private $logsFile;
    
	function __construct(User $user) {
		global $botConfig;

		$this->TOKEN 	= $botConfig['token'];
		$this->BOT_NAME = $botConfig['botName'];
		$this->user 	= $user;
		$this->init();
	}
	
    // инициализация
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

    // public function logUpdate ($update) {
    //     $logData = sprintf (
    //         'Received update: chatId: %s, userId: %s, text: %s',
    //         $update['message']['chat']['id'] ?? 'unknown',
    //         $update['message']['from']['id'] ?? 'unknown',
    //         $update['message']['text'] ?? 'no text'
    //     );
        
    //     file_put_contents($logsFile, $logData . PHP_EOL, FILE_APPEND);
    // }


    // обрпботка обновлений
	public function processUpdate($update)
    {
        if (isset($update['message']['text'])) {
            $messageText = $update['message']['text'];
            $chatId = $update['message']['chat']['id'];
            $userId = $update['message']['from']['id'];

            if ($messageText === '/start') {
                $this->handleStartCommand($chatId, $userId);
            } else {
                $this->handleMessage($chatId, $messageText, $userId);
            }
        }
    }

    protected function handleStartCommand($chatId, $userId)
    {
        if (!$this->user->isUserRegistered($userId)) {
            $this->user->registerUser($userId);
            $welcomeMessage = "Привет! Вы зарегистрированы.";
        } else {
            $welcomeMessage = "Привет! Вы уже зарегистрированы.";
        }
        $this->sendTextMessage($chatId, $welcomeMessage);
    }

    protected function handleMessage($chatId, $messageText, $userId)
    {
        $response = "Вы сказали: " . $messageText;
        $this->sendTextMessage($chatId, $response);
    }


    public function sendTextMessage($chatId, $text)
    {
        $message = $this->bot->sendMessage([
            'chat_id' => $chatId,
            'text' => $text
        ]);

		var_dump($message);
    }



}

