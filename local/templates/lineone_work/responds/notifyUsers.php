<?php
/** @global CUser $USER */

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

use Trud\IBlock\InfoIblock;
use Trud\Shifts\ShiftInfo;
use Trud\TgBot\Bot;
use Trud\TgBot\BotLoger;
use Trud\TgBot\MessageBuilder;

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

        // если совсем пустой массив, прервать
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

            // занулить последнее (предыдущее) соообщение
            // $bot->resetLastInvite($tg_id);

            // сборка и отправка сообщения
            $message = MessageBuilder::workersNotification($name, $shiftId);
            $messageID = $bot->sendMessage($tg_id, $message['text'], $message['keyboard']);

            // меняем статус у пользователя, что отправлено сообщение
            $user = new \CUser;
            $user->Update($worker['ID'], ['UF_TG_DIALOG_STATUS' => 'invited']);
            
            // лог чата, что сообщение отправлено 
            $logMessage = "Выслано приглашение. Смена: $shiftId, начало:  $startDate, заказчик: $clienName";
            BotLoger::logChat($tg_id, $logMessage);

            // номер сообщения - в json-статус диалога 
            BotLoger::addUserStatus($tg_id, $messageID);
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