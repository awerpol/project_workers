<?php

use Bitrix\Main\Context;

use Trud\IBlock\InfoIblock;
use Trud\Users\Carma;

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
    if($oRequest->getPost('todo') == 'rate'){

        $el = new CIBlockElement;

        $parts = explode('_', $oRequest->getPost('carmaAdd'));
        $sign = $parts[0];
        $num = intval($parts[1]);
        if ($sign === "MINUS") {
            $num = -$num;
        }
    

        foreach ($oRequest->getPost('userList') as $workerId) {

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