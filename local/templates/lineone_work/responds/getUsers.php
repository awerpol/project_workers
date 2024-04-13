<?php
/** @global CUser $USER */

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

use Trud\Shifts\ShiftInfo;
use Trud\Helpers\Helper;

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
        
        $shiftID = $oRequest->getPost('shiftID');                // для черного списка
        $clientID = ShiftInfo::getPropValue($shiftID, 'CLIENT'); // ID клиента черного списка
        
        // массив тех, кого НЕ включаем в правую таблицу
        $exclude = array_unique(array_merge(
            explode(',', $oRequest->getPost('listUser')),                   // те, кто в левой таблице
            ShiftInfo::getPropValue($shiftID, 'BLACK_LIST_SHIFT'),          // кто в "черном списке" этой смены
            Helper::getPropValue('CLIENTS', $clientID, 'BLACK_LIST_CLIENT') // кто в черном списке заказчика
        ));

        $filter = ['UF_RULES' => '1',  '!=ID' => $exclude, 'PERSONAL_GENDER' => [], 'UF_BUSY' => 0 ];
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

        $select = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING', 'UF_CARMA_SUMM'];
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
                'CARMA'  => $user[ 'UF_CARMA_SUMM' ] ?? '0',
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


