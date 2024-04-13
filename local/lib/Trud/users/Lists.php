<?php

namespace Trud\Users;

use Bitrix\Main\Loader;
// use Trud\IBlock\InfoIblock;


class Lists
{
    // оставить только свободных пользователей
    public static function getFreeUsers($arUsers) 
    {
        $freeUsers = [];

        $user = new \CUser;

        foreach ($arUsers as $userId) {
            $userInfo = $user->GetByID($userId)->Fetch();
            if ($userInfo && $userInfo['UF_BUSY'] == 0) {
                $freeUsers[] = $userId;
            }
        }

        return $freeUsers;
    }

    // "освобождаем" одного пользователя
    public static function makeHimFree($userId) 
    { 
        $user = new \CUser;
        $user->Update($userId, ['UF_BUSY' => 0]);
    }

    // "занимаем" одного пользователя
    public static function makeHimBusy($userId) 
    { 
        $user = new \CUser;
        $user->Update($userId, ['UF_BUSY' => 1]);
    }

    // "освобождаем" несколько пользователей
    public static function makeThemFree($arUsers) { self::changeField($arUsers, 'UF_BUSY', 0); }

    // делаем "занятыми" пользователей
    public static function makeThemBusy($arUsers) { self::changeField($arUsers, 'UF_BUSY', 1); }

    // Приватный метод для изменения поля пользователей
    private static function changeField($arUsers, $fieldName, $fieldValue)
    {
        $user = new \CUser;

        foreach ($arUsers as $userId) {
            $user->Update($userId, [$fieldName => $fieldValue]);
        }
    }
}