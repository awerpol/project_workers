<?php
/** @global CUser $USER */

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

require($_SERVER[ "DOCUMENT_ROOT" ]."/bitrix/modules/main/include/prolog_before.php");

if (!$USER->IsAuthorized()) {
    die(json_encode([
        'success' => false,
        'message' => 'No Authorize',
    ]));
}

$oRequest = Context::getCurrent()->getRequest();

if ($oRequest->isAjaxRequest()) {

    if ($oRequest->getPost('userList') && $oRequest->getPost('shiftID')) {
        CModule::IncludeModule('iblock');

        if($oRequest->getPost('todo') == 'addUser'){
            $userList = array_merge($oRequest->getPost('userList'), explode(',', $oRequest->getPost('oldListUser')));
        } elseif($oRequest->getPost('todo') == 'deleteUser'){
            $userList = array_diff(explode(',', $oRequest->getPost('oldListUser')), $oRequest->getPost('userList'));
        }

        $res = CIBlockElement::SetPropertyValueCode($oRequest->getPost('shiftID'), "WORKERS",$userList);
        $aResult = [
            'message' => 'Успешно сформирован список пользователей!',
            'resultM' => $oRequest->getPost('userList'),
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


