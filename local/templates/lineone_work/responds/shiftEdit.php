<?php
/** @global CUser $USER */

use Bitrix\Main\Context;
use Bitrix\Main\Type\DateTime;

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
    if(1) {
        
        $el = new CIBlockElement;

        $duration=$oRequest->getPost('duration');
        $shiftStart = $oRequest->getPost('date') . " " . $oRequest->getPost('startTime') . ":00";
        $shiftEnd = new DateTime($shiftStart);
        $shiftEnd->add($duration . ' hours');
        
        // название клиента по id
        $rsElement = CIBlockElement::GetByID($oRequest->getPost('client'));
        if ($arElement = $rsElement->GetNext()) {
            $client_name = $arElement['NAME'];
        } else {
            $client_name =  "Заказчик не найден";
        }

        $shiftName = $client_name; 

        $arShiftPropeties = [
            "IBLOCK_SECTION_ID" => false,         // нет разделов
            "IBLOCK_ID"         => Trud\IBlock\InfoIblock::getIdByCode('SHIFT_BEING_FORMED'),
            "NAME"              => $shiftName,    // название смены
            "ACTIVE"            => "Y",
            "PROPERTY_VALUES"   => [
                // "SHIFT_IS_CTIVE"    => 5,         // ID варианта 'Y' // больше не используем
                // "SHIFT_STAGE"       => 6,         // ID варианта "Формируется"
                "SHIFT_STAGE"       => Trud\IBlock\InfoIblock::getFieldIdByXML_ID('FORMING'),  // ID варианта "Формируется"
                "CLIENT"            => $oRequest->getPost('client'),
                "SHIFT_START"       => $shiftStart,
                "SHIFT_END"         => $shiftEnd,
                "SHIFT_COUNT_M"     => $oRequest->getPost('needM'),
                "SHIFT_COUNT_F"     => $oRequest->getPost('needF'),
            ]
        ];

        $result = $el->Add($arShiftPropeties);

        // обработка возможной ошибки
        if ($result) {
            $aResult = [
                'success' => true,
                'message' => 'Успешно добавлена Смена!',
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