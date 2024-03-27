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

        $filter         = ["IBLOCK_ID" => $iblockId, "PROPERTY_ID_WORKER" =>  $userId];
        $arSelectFields = ["ID", "IBLOCK_ID", "NAME", "PROPERTY_ACT_SIGN", "PROPERTY_ID_WORKER"];

        // Получаем список элементов из инфоблока с ID=3 для данного пользователя
        $dbItems = \CIBlockElement::GetList(
            [],
            $filter,
            false,
            false,
            $arSelectFields 
        );

        // Суммируем карму
        $newCarma = 0;
        while ($item = $dbItems->fetch()) {
            $carmaValue = $item['PROPERTY_ACT_SIGN_VALUE'];
            $newCarma += $carmaValue;
        }
       
        // добавляем пользователю его Карму
        $user = new \CUser;
        $userUpdateResult = $user->Update($userId, ['UF_CARMA_SUMM' => $newCarma]);

        if (!$userUpdateResult) {
            throw new \Exception('Error updating user');
        }

        return $newCarma;
    }

}


