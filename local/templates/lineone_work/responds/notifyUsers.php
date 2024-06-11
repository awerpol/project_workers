<?php

use Bitrix\Main\Context;

use Trud\IBlock\InfoIblock;
use Trud\tgBot\Bot;


require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/modules/main/include/prolog_before.php");

if (!$USER->IsAuthorized()) {
    die(json_encode([
        'success' => false,
        'message' => 'No Authorize',
    ]));
}

CModule::IncludeModule("iblock"); // < ================================================

$oRequest = Context::getCurrent()->getRequest();


if ($oRequest->isAjaxRequest()) {
    if($oRequest->getPost('todo') == 'send'){

// дальше что? ================================================

        // $el = new CIBlockElement; // не с этим работаем, а с юзерами

        foreach ($oRequest->getPost('userList') as $workerId) {

            /*
            - получить tg-id юзера
            - отправить сообщение (бот)
            public function sendMessage($chatId, $text, $keyboard = null) {
            - поменять статус, что отправлено сообщение UF_TG_DIALOG_STATUS

            - ??? что еще??
            - лог в чат, что сообщение отправлено (BotLoger)
                public static function logChat ($tgId, $message) {
            - статус диалога в json - нужно?

            */
            $arCarmaPropeties = [
                "IBLOCK_SECTION_ID" => false,         // нет разделов
                "IBLOCK_ID"         => Trud\IBlock\InfoIblock::getIdByCode('KARMA_ACT'),
                "ACTIVE"            => "Y",
                "NAME"              => "user " . $workerId . " " . $oRequest->getPost('carmaAdd'),   // текст выбранного варианта 

                "PROPERTY_VALUES"   => [
                    "ID_WORKER"         => $workerId, 
                    "ACT_DATA"          => $oRequest->getPost('date'),
                    "CASE"              => Trud\IBlock\InfoIblock::getCarmaCaseFielIdByXML_ID($oRequest->getPost('carmaAdd')),
                    "ACT_SIGN"          => $num
                ]
            ];

            $result = $el->Add($arCarmaPropeties);

            // пересчитываем карму воркера
            Carma::countForUser($workerId);

        }

        // обработка возможной ошибки
        if ($result) {
            $aResult = [
                'success' => true,
                'message' => 'Успешно добавлена Оценка!',
            ];
        } else {
            $aResult = [
                'success' => false,
                'message' => 'ошибка создания инфоблока',
            ];
        }

        die(json_encode($aResult));
    } else {
        die(json_encode([
            'success' => false,
            'message' => 'No key',
        ]));
    }



}