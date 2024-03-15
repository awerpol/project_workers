<?php
/** @global CUser $USER */

use Bitrix\Main\Context;
// use Bitrix\Main\UserTable;
use Bitrix\Main\UserGroupTable;


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
        
        // $select = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING'];
        // $filter = [];
        // $res = UserTable::getList(['select' => $select, 'filter' => $filter]);

        // Выбираем пользователей из группы "WORKERS" ['GROUP_ID'=> '6']
        $select = ['ID' => 'USER_ID','NAME'=>'USER.NAME','LAST_NAME'=>'USER.LAST_NAME', 'PERSONAL_GENDER'=>'USER.PERSONAL_GENDER','PERSONAL_PHONE'=>'USER.PERSONAL_PHONE', 'UF_RULES'=>'USER.UF_RULES', 'UF_RATING'=>'USER.UF_RATING'];
        $filter = ['GROUP_ID'=> '6'];
        $res = UserGroupTable::getList(['select' => $select, 'filter' => $filter]);
        
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
                    'IS_ACTIVE'  => $user[ 'UF_RULES' ],
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


