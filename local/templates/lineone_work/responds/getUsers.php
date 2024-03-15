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

        $filter = ['UF_RULES' => '1',  '!=ID' => explode(',', $oRequest->getPost('listUser')), 'PERSONAL_GENDER' => [] ];
        // если еще нужны М
        if ($oRequest->getPost('needM') == 'true') {
            $filter['PERSONAL_GENDER'][] = 'M';
        }
        // если еще нужны Ж
        if ($oRequest->getPost('needF') == 'true') {
            $filter['PERSONAL_GENDER'][] = 'F';
        }
        // если не нужны М/Ж
        if (empty($filter['PERSONAL_GENDER'])) {
            $filter['PERSONAL_GENDER'] = '';
        }

        $select = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING'];
        $res = UserTable::getList(['select' => $select, 'filter' => $filter]);
        $users = $res->fetchAll();
        $arResultUsers = [];

        $m=$f=0;
        foreach ($users as $key => $user) {    
            if ($user[ 'PERSONAL_GENDER' ] == "M") {
                $m++;
            } elseif ($user[ 'PERSONAL_GENDER' ] == "F") {
                $f++;
                $user[ 'PERSONAL_GENDER' ] = "Ж";
            } 

            $arResultUsers[ 'USERS' ][] = [
                'ID'     => $user[ 'ID' ],
                'NAME'   => $user[ 'LAST_NAME' ].' '.$user[ 'NAME' ],
                'GENDER' => $user[ 'PERSONAL_GENDER' ],
                'PHONE'  => $user[ 'PERSONAL_PHONE' ],
                'RATING' => $user[ 'UF_RATING' ] ?? '0',
            ];

        }

        $arResultUsers[ 'COUNT_GENDER_M' ] = $m;
        $arResultUsers[ 'COUNT_GENDER_F' ] = $f;


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


