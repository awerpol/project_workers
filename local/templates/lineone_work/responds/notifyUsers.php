<?php
/** @global CUser $USER */

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

use Trud\IBlock\InfoIblock;
use Trud\Shifts\ShiftInfo;
use Trud\TgBot\Bot;
use Trud\TgBot\BotLoger;


require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/modules/main/include/prolog_before.php");

if (!$USER->IsAuthorized()) {
    die(json_encode([
        'success' => false,
        'message' => 'No Authorize',
    ]));
}

CModule::IncludeModule("iblock");

$oRequest = Context::getCurrent()->getRequest();

if ($oRequest->isAjaxRequest()) {
    if($oRequest->getPost('todo') == 'send'){

        // если никого не выделили галками, отправить всем.
        $userList = $oRequest->getPost('userList');
        if (empty($userList)) {
            $userList = explode(',', $oRequest->getPost('oldListUser'));
        }

        // TODO: если совсем пустой массив, прервать
        if (empty($userList)) {
            $aResult = [
                'success' => false,
                'message' => 'ошибка отправки сообщений',
            ];
            die(json_encode($aResult));
        }

        $bot = new Bot();

        //  Получим юзеров с помощью getList:
        $filter = ['ID' => $userList];
        $select = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING', 'UF_CARMA_SUMM', 'UF_TELEGRAM_ID', 'UF_TG_DIALOG_STATUS'];

        $res = UserTable::getList(['select' => $select, 'filter' => $filter]);
        $users = $res->fetchAll();

        // получим данные о смене
        $shiftId    = $oRequest->getPost('shiftID'); // id смены
        $startDate  = ShiftInfo::getPropValue($shiftId, 'SHIFT_START');
        $clientID   = ShiftInfo::getPropValue($shiftId, 'CLIENT');

        // имя заказчика
        $rsElement = CIBlockElement::GetByID($clientID);
        $arElement = $rsElement->GetNext();
        $clienName = $arElement['NAME'];

        foreach ($users as $worker) {
            $tg_id  = $worker['UF_TELEGRAM_ID']; 
            if (!$tg_id) continue; // если не зареган в телеграме

            $name   = $worker['NAME'];
            // вся инфа пользователя по ID
            // $arUser = CUser::GetByID($workerId)->Fetch();

            // занулить последнее (предыдущее) соообщение
            $bot->resetLastInvite($tg_id);

            // сборка и отправка сообщения
            // TODO: - куда-то убрать сообщения, собирать их отдельно с учетом языка
            $messageText = $name . ", вы приглашены на смену \n" .
                "в <b>" . $clienName . "</b>\n" .
                "начало: <b>" . $startDate . "</b>\n" .
                "<b>Подтвердите, что идете</b>";
            $keyboard = [ 'inline_keyboard' => [[
                    ['text' => '✅ иду',    'callback_data' => 'yes_i_go'],
                    ['text' => '❌ не иду', 'callback_data' => 'cancel'],
                ]]];
            $messageID = $bot->sendMessage($tg_id, $messageText, $keyboard);

            // меняем статус, что отправлено сообщение
            $user = new \CUser;
            $user->Update($worker['ID'], ['UF_TG_DIALOG_STATUS' => 'invited']);
            
            // лог чата, что сообщение отправлено 
            $logMessage = "Выслано приглашение. Смена: $shiftId, начало:  $startDate, заказчик: $clienName";
            BotLoger::logChat($tg_id, $logMessage);

            // номер сообщения - в json-статус диалога 
            BotLoger::addUserStatus($tg_id, $messageID);

            // ============ дебуг ============= //
            // $tgId = 'button_debug';
            // BotLoger::logChat ($tgId, $logMessage);

            // ob_start(); // Начать буферизацию вывода
            // var_dump($worker); 
            // $message = ob_get_clean(); // очистить буфер
            // $tgId = 'button_debug';
            // BotLoger::logChat ($tgId, $message);
            // ================================ 
        }

        $aResult = [
            'success' => true,
            'message' => 'Успешно сделана рассылка!',
        ];
        die(json_encode($aResult));
    } else {
        die(json_encode([
            'success' => false,
            'message' => 'No key',
        ]));
    }



}