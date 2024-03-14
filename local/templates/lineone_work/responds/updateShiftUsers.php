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
        } elseif($oRequest->getPost('todo') == 'fillUsers'){
            $userList = array_merge(fillUsers($oRequest), explode(',', $oRequest->getPost('oldListUser')));
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

function fillUsers($oRequest) {

    $arResultUsers = [];

    if ($oRequest->getPost('needM') > 0) {
        // доступные=1, кроме тех кто в левой таблице, Мужчины
        $filterM = ['UF_RULES' => '1',  '!=ID' => explode(',', $oRequest->getPost('oldListUser')), 'PERSONAL_GENDER' => ['M'] ];
        $limitM = $oRequest->getPost('needM');
        $selectM = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING'];

        $resM = UserTable::getList(['select' => $selectM, 'filter' => $filterM, 'limit' => $limitM, 'order' => ['UF_RATING' => 'DESC'] ]);
        $usersM = $resM->fetchAll();

        foreach ($usersM as $key => $userM) {    
            $arResultUsers[] = $userM[ 'ID' ];
        }
    }
    
    if ($oRequest->getPost('needF') > 0) {
        // доступные=1, кроме тех кто в левой таблице, Женщины
        $filterF = ['UF_RULES' => '1',  '!=ID' => explode(',', $oRequest->getPost('oldListUser')), 'PERSONAL_GENDER' => ['F'] ];
        $limitF = $oRequest->getPost('needF');
        $selectF = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING'];

        $resF = UserTable::getList(['select' => $selectF, 'filter' => $filterF, 'limit' => $limitF, 'order' => ['UF_RATING' => 'DESC'] ]);
        $usersF = $resF->fetchAll();

        foreach ($usersF as $key => $userF) {    
            $arResultUsers[] = $userF[ 'ID' ];
        }
    }
    
    return $arResultUsers;
}
