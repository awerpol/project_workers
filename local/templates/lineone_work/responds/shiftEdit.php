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

        $shiftName = $oRequest->getPost('date') . " " . $oRequest->getPost('startTime') . "/" . $duration . "ч"; 
        $shiftStart = $oRequest->getPost('date') . " " . $oRequest->getPost('startTime') . ":00";
        $shiftEnd = new DateTime($shiftStart);
        $shiftEnd->add($duration . ' hours');

        // $shiftName = $shiftEnd . "-". $duration;


        $arShiftPropeties = [
            "IBLOCK_SECTION_ID" => false, // раздел 
            "IBLOCK_ID" => 2, // ID инфоблока
            "NAME" => $shiftName, // название смены
            "ACTIVE" => "Y",
            "PROPERTY_VALUES" => [
                "SHIFT_IS_CTIVE"    => 5, // ID варианта Y
                "CLIENT"            => $oRequest->getPost('client'),
                "SHIFT_START"       => $shiftStart,
                "SHIFT_END"         => $shiftEnd,
                "SHIFT_COUNT_M"     => $oRequest->getPost('needM'),
                "SHIFT_COUNT_F"     => $oRequest->getPost('needF'),
            ]
            // ... другие свойства (если есть)
        ];

        $result = $el->Add($arShiftPropeties);

        if ($result) {
        // элемент успешно создан
        // ... 
        } else {
        // ошибка при создании элемента
        // ... 
        }



        $aResult = [
            'message' => 'Успешно создан инфоблок Смена!',
            'resultM' => $arResultUsers,
            'success' => true,
        ];
        die(json_encode($aResult));
    } else {
        die(json_encode([
            'success' => false,
            'message' => 'No key',
        ]));
    }



}