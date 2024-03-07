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

    if ($oRequest->getPost('getUser') === 'Y') {
        $select = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING'];
        $filter = ['UF_RULES' => '1', '!=ID' => explode(',', $oRequest->getPost('listUser'))];
        $res = UserTable::getList(['select' => $select, 'filter' => $filter]);
        $users = $res->fetchAll();
        $arResultUsers = [];

        foreach ($users as $key => $user) {
            //TODO: добавить подсчет кол-во Ж и М
            $arResultUsers[ 'COUNT_GENDER_F' ] = 1;
            $arResultUsers[ 'COUNT_GENDER_M' ] = 2;
            $arResultUsers[ 'USERS' ][] = [
                'ID'     => $user[ 'ID' ],
                'NAME'   => $user[ 'LAST_NAME' ].' '.$user[ 'NAME' ],
                'GENDER' => $user[ 'PERSONAL_GENDER' ],
                'PHONE'  => $user[ 'PERSONAL_PHONE' ],
                'RATING' => $user[ 'UF_RATING' ] ?? '0',
            ];
        }


        $aResult = [
            'message' => 'Успешно сформирован список пользователей!',
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


