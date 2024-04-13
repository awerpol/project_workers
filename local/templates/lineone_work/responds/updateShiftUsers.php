<?php
/** @global CUser $USER */

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

use Trud\Users\Lists;
use Trud\Shifts\ShiftInfo;
use Trud\Shifts\ShiftEdit;
use Trud\Helpers\Helper;

/*
 * use Bitrix\Main\{Context, UserTable};
 * */

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
            $startList = explode(',', $oRequest->getPost('oldListUser'));    // начальный список слева
            $arToAdd =  Lists::getFreeUsers($oRequest->getPost('userList')); // добавляем выделенных, только свободных из них
            
            $userList = array_merge($arToAdd, $startList);  // итоговый массив
            Lists::makeThemBusy($arToAdd);                  // помечаем, что добавленные заняты

        } elseif($oRequest->getPost('todo') == 'deleteUser'){
            $startList = explode(',', $oRequest->getPost('oldListUser')); // начальный список слева
            $arToDelete = $oRequest->getPost('userList');

            $userList = array_diff($startList, $arToDelete); // итоговый массив

            ShiftEdit::addToBlackList($oRequest->getPost('shiftID'), $arToDelete); // добавляем удаленных юзеров в поле "черный список временный"
            Lists::makeThemFree($arToDelete);                  // помечаем, что удаленные свободны

        } elseif($oRequest->getPost('todo') == 'fillUsers'){
            $startList = explode(',', $oRequest->getPost('oldListUser')); // начальный список слева

            $blackList = ShiftInfo::getPropValue($oRequest->getPost('shiftID'), 'BLACK_LIST_SHIFT'); // черный список смены
            $arExcept = array_merge($startList, $blackList);  // список исключений

            $getM = Helper::pickUpUsers($oRequest->getPost('needM'), 'M', $arExcept);
            $getF = Helper::pickUpUsers($oRequest->getPost('needF'), 'F', $arExcept);
            $arToAdd = array_merge($getM, $getF);

            $userList = array_merge($arToAdd, $startList);  // итоговый массив
            Lists::makeThemBusy($arToAdd);                  // помечаем, что добавленные заняты
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

