<?php


namespace Trud\TgBot;

use WeStacks\TeleBot\TeleBot;
use WeStacks\TeleBot\Objects\Keyboard;
use Trud\TgBot\BotUser;
use Trud\TgBot\BotLoger;

// use WeStacks\TeleBot\Handlers\CommandHandler;

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/lib/Trud/tgBot/botConfig.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';


session_start();

class Bot
{
    private $TOKEN;
	private $BOT_NAME;
	private $inited = false;
	private $bot;
    private $userStates = [];
    private $messages;
    
	function __construct() {
		global $botConfig;
        // global $messages;

		$this->TOKEN 	= $botConfig['token'];
		$this->BOT_NAME = $botConfig['botName'];
        $this->messages = include $_SERVER['DOCUMENT_ROOT'] . '/local/lib/Trud/tgBot/messages.php';
		$this->init();

	}
	
    // инициализация
	private function init() {
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


    // ============================================================
    // обработка обновлений
	public function processUpdate($update) {

        // /* ---дебуг--- */
        // ob_start(); // Начать буферизацию вывода
        // var_dump($update);
        // $res = ob_get_clean(); 
        // $this->sendMessage($update['message']['chat']['id'], $res);
        // /* ---дебуг--- */

        // текстовые сообщения (не кнопки)
        if (isset($update['message']['text'])) {  
            $tgId      = $update['message']['from']['id']; 
            $botUser   = new BotUser($tgId);     
            $inMessage = $update['message'];
            if ($inMessage['text'] === '/start') { // TODO: сделать обработку команд
                $this->handleStartCommand($botUser, $inMessage);
            } else if ($inMessage['text'] === '/demo') { // <------------- DEMO
                $this->startDemo($botUser, $inMessage);
            } else {
                $this->handleMessage($botUser, $inMessage);
            }
        }

        // нажатые кнопки (inline callback)
        if (isset($update['callback_query'])) {
            $tgId      = $update['callback_query']['from']['id'];
            $botUser   = new BotUser($tgId);
            $this->handleInline($botUser, $update);
        }

        // получение контакта
        if (isset($update['message']['contact'])) { 
            $tgId        = $update['message']['from']['id']; 
            $botUser     = new BotUser($tgId);
            $update['message']['text'] = $update['message']['contact']['phone_number']; // а дальше как текстовое сообщение
            $this->handleMessage($botUser, $update['message']); 
        }   

    }

    // ============================== Обработка входящих сообщений ==============================
    // старт
    protected function handleStartCommand($botUser, $inMessage) {
        $messageId = $inMessage['message_id']; // id сообщения для замены
        $chatId    = $inMessage['chat']['id'];
        $tgId      = $inMessage['from']['id'];
        $name = $botUser->getUserData()['NAME'];
        // если НЕ зарегистрирован
        if (!$botUser->isRegistered()) {
            BotLoger::addUserStatus($tgId, 'awaiting_phone');
            $welcomeMessage = $this->messages['start']['text'];
            $keyboard       = $this->messages['start']['keyboard'];
        } else {
            $welcomeMessage = $name . $this->messages['welcome_registered'];
        }
        $this->sendMessage($chatId, $welcomeMessage, $keyboard);
    }

    // обработка сообщения (текстового, не кнопок)
    protected function handleMessage($botUser, $inMessage) {
        $messageId   = $inMessage['message_id']; // id сообщения для замены
        $chatId      = $inMessage['chat']['id'];
        $tgId        = $inMessage['from']['id'];
        $messageText = $inMessage['text'];
        
        // если ждем телефон
        if(BotLoger::getUserStatus($tgId) == 'awaiting_phone') {
            // Пытаемся зарегистрировать пользователя с номером телефона
            if ($botUser->registerUser($messageText)) {
                $name = $botUser->getUserData()['NAME'];
                $response = $name . $this->messages['register_ok']; // TODO: нормально сделать получение текстов и сообщений
                // TODO: добавить запись о регистрации в лог чата
            } else {
                $response = $this->messages['register_not_ok'] . $messageText; // TODO: нормально сделать получение текстов и сообщений
            }
            BotLoger::addUserStatus($tgId, 'default');
            $this->sendMessage($chatId, $response, ['remove_keyboard' => true]);
        } else {
            // удаляем лишнее текстовое сообщение
            $this->deleteMessage($chatId, $messageId);
            // $this->sendMessage($chatId, "messageId = <i>$messageId</i> "."<b>Вы сказали:</b> " . $messageText); // дебуг
        }
    }

    // обработка нажатых кнопок (inline)
    protected function handleInline($botUser, $update) {
        $chatId     = $update['callback_query']['message']['chat']['id'];
        $pressed    = $update['callback_query']['data'];
        $messageId  = $update['callback_query']['message']['message_id'];
        $tgId       = $update['callback_query']['from']['id'];

        $response = "messageId = <i>$messageId</i> "."<b>Вы нажали:</b> " . $pressed;
        $keyboard = null;

        //TODO: что-то делать с нажатыми кнопками: учитывать статус диалога, нажатую кнопку и т.д.
        //TODO: логировать ключевые действия
        //TODO: id сообщения - в статус - чтобы потом его загасить, когда неактуально будет
        switch ($pressed) {
            case 'yes_i_go': // <---------- DEMO
                $tomorrow       = date('d.m.Y', strtotime('+1 day'));
                $response = "Ждем вас в Гурмане 
                $tomorrow в 8:00";
                $keyboard = [ 'inline_keyboard' => [[
                    ['text' => '👣 Как добраться', 'callback_data' => 'address'],
                    ['text' => '👥 Кто идет', 'callback_data' => 'other_collegues'],
                ]]];

                $botUser->setState('confirmed');
                BotLoger::logChat ($tgId, "подтвердил участие в смене");

                break;
            case 'address': // <---------- DEMO
                $response = "<b>Адрес:</b> ул. Станционная, 62/1
<b>Автобусы:</b> 145, 8
<b>Маршрутки:</b> 1145, 1298
Проходная слева от здания";
                $keyboard = [ 'inline_keyboard' => [[
                    ['text' => '🔙 Назад', 'callback_data' => 'yes_i_go'],
                ]]];
                break;
            case 'other_collegues': // <---------- DEMO
                $response = "Петров
Иванов
Сидоров
Кузнецов";
                $keyboard = [ 'inline_keyboard' => [[
                    ['text' => '🔙 Назад', 'callback_data' => 'yes_i_go'],
                ]]];
                break;
            case 'cancel': // <---------- DEMO
                $response = "Ваш рейтинг понижен. Ждите другое приглашение";

                $botUser->setState('refused');
                BotLoger::logChat ($tgId, "отказался от участия в смене");

                break;
        }

        $this->editMessage($chatId, $messageId, $response, $keyboard);
    }

    // ============================== Тест-демонстрация ==============================
    public function startDemo($botUser, $inMessage) {
        $name = $botUser->getUserData()['NAME'];
        $messageId   = $inMessage['message_id']; // id сообщения для замены
        $chatId      = $inMessage['chat']['id'];
        $tgId        = $inMessage['from']['id'];
        
        $tomorrow       = date('d.m.Y', strtotime('+1 day'));
        $response = "$name, вы приглашены на смену
        $tomorrow 8:00, Гурман
        <b>Подтвердите, что идете</b>";
        // $response = "$name, <b>вы приглашены на смену.</b><br>. $tomorrow";
        // $response       = $name . ', <b>вы приглашены на смену.</b><br>' . $tomorrow . ' 8:00 <br>' . 'Гурман' . '<b>Подтвердите, что идете</b>';
        $inlineKeyboard = [ 'inline_keyboard' => [[
            ['text' => '✅ иду', 'callback_data' => 'yes_i_go'],
            ['text' => '❌ нет', 'callback_data' => 'cancel'],
        ]]];
        $this->sendMessage($chatId, $response, $inlineKeyboard);
        // $this->sendMessage($chatId, $response);

    }
    // ============================== Тест-демонстрация ==============================


    // ============================== Отправка сообщений ==============================
    public function sendMessage($chatId, $text, $keyboard = null) {

        // TODO: убрать клавиатуры в отдельный файл (или в файл сообщений)
        $removeKeyboard = ['remove_keyboard' => true];
        // $keyboard = $inlineKeyboard; 

        $params = [
            'chat_id'      => $chatId,
            'text'         => $text,
            'parse_mode'   => 'HTML'
        ];
        if ($keyboard) $params['reply_markup'] = $keyboard;
        $response = $this->bot->sendMessage($params);

        return $response->message_id;
    }

    // тестируем - отправка сообщения из библиотеки
    public function sendLibMessage($chatId, $key) {
        $params = [
            'chat_id'      => $chatId,
            'text'         => $this->messages[$key]['text'],
            'parse_mode'   => 'HTML'
        ];
        if ($this->messages[$key]['keyboard']) $params['reply_markup'] = $this->messages[$key]['keyboard'];
        $this->bot->sendMessage($params);
    }

    // изменение сообщения (использовать для кнопок)
    public function editMessage($chatId, $messageId, $newText, $keyboard = null) {
        $params = [
            'chat_id'      => $chatId,
            'message_id'   => $messageId,
            'text'         => $newText,
            'parse_mode'   => 'HTML'
        ];
        if ($keyboard) $params['reply_markup'] = $keyboard;
        $this->bot->editMessageText($params);
    }

    // удаление сообщения
    public function deleteMessage($chatId, $messageId) {
            $response = $this->bot->deleteMessage([
                'chat_id' => $chatId,
                'message_id' => $messageId
            ]);
            return $response;
    }

    // сбросить последнее приглашение
    public function resetLastInvite($chatId) {
        $messageId = BotLoger::getUserStatus ($chatId);
        $resetText = "приглашение не актуально";

        // if ($messageId) {
        //     $this->editMessage($chatId, $messageId, $resetText, $keyboard = null);
        // }

        try {
             $this->editMessage($chatId, $messageId, $resetText, $keyboard = null);
        } catch (\Exception $e) {
            
        }
    }

}

