<?php

namespace Trud\Users;

use Bitrix\Main\Loader;
use Trud\IBlock\InfoIblock;


class Carma
{
    // считаем карму по id юзера и добавляем ему в поле
    public static function countForUser($userId) 
    {
        Loader::includeModule('main');
        Loader::includeModule('iblock');
      
        $iblockId = InfoIblock::getIdByCode('KARMA_ACT');
        $userId = intval($userId);

        // элементы инфоблока KARMA_ACT для данного пользователя
        $filter         = ["IBLOCK_ID" => $iblockId, "PROPERTY_ID_WORKER" =>  $userId];
        $arSelect = ["ID", "IBLOCK_ID", "NAME", "PROPERTY_ACT_SIGN", "PROPERTY_ID_WORKER"];

        // Получаем инфоблоки (элементы) Кармы по юзеру
        $dbItems = \CIBlockElement::GetList(
            [],
            $filter,
            false,
            false,
            $arSelect 
        );

        // Суммируем карму
        $newCarma = 0;
        while ($item = $dbItems->fetch()) {
            $carmaValue = $item['PROPERTY_ACT_SIGN_VALUE'];
            $newCarma += $carmaValue;
        }
       
        // обновляем пользователю его Карму
        $user = new \CUser;
        $userUpdateResult = $user->Update($userId, ['UF_CARMA_SUMM' => $newCarma]);

        if (!$userUpdateResult) {
            throw new \Exception('Error updating user');
        }

        return $newCarma;
    }

}


