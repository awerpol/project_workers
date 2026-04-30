<?php

namespace Trud\TgBot;

use Bitrix\Main\UserTable;
use Trud\TgBot\BotLoger;
use Trud\TgBot\Bot;

class BotUser
{
    protected $id_tg;
    protected $userData;
    protected $state;

    public function __construct($id_tg = null) {
        if ($id_tg !== null) {
            $this->id_tg = $id_tg;
            $this->loadUserByTgId($id_tg);
            $this->state = $this->userData['UF_TG_DIALOG_STATUS'] ?? 'default';
        }
    }

    // загрузить данные юзера по id телеграм
    private function loadUserByTgId($id_tg) {
        $this->userData = self::findUserByField ('UF_TELEGRAM_ID', $id_tg);
    }

    public function getUserData() {
        return $this->userData;
    }

    // регистрация юзера
    public function registerUser($phoneNumber) {
        $user = self::findUserByPhone($phoneNumber);

        if ($user) {
            self::setTgId($user['ID'], $this->id_tg);
            $this->userData = $user;
            $this->userData['UF_TELEGRAM_ID'] = $this->tg_id;
            return true;
        }

        return false;
    }

    // проверка, зарегистрирован ли пользователь
    public function isRegistered() {
        return !empty($this->userData);
    }

    // установка состояния диалога
    public function setState($state) {
        $this->state = $state;
        $this->userData['UF_TG_DIALOG_STATUS'] = $state;
        return self::updateUserField($this->userData['ID'], 'UF_TG_DIALOG_STATUS', $state);
    }

    // получение состояния
    public function getState(){
        return $this->state;
    }

    // найти юзера по номеру телефона
    public static function findUserByPhone($phoneNumber) { 
        // Удаляем все символы, кроме цифр
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        if (empty($phoneNumber)) {
            return [];
        }

        // Проверяем, начинается ли номер с кода страны или с "8"
        if (strlen($phoneNumber) == 11 && ( substr($phoneNumber, 0, 1) == '8' || substr($phoneNumber, 0, 1) == '7')) {
            $phoneNumber = '+7' . substr($phoneNumber, 1);
        } elseif (strlen($phoneNumber) == 10) {
            $phoneNumber = '+7' . $phoneNumber;
        } 
        
        // на всякий проверим в поле мобильного телефона
        $result = self::findUserByField('PERSONAL_PHONE', $phoneNumber); 
        if (empty($result)) {
            $result = self::findUserByField('PERSONAL_MOBILE', $phoneNumber); 
        }
        return $result;
    } 
    
    // добавить id телеги в юзера
    public static function setTgId($id_bx, $id_tg) { 
        return self::updateUserField($id_bx, 'UF_TELEGRAM_ID', $id_tg);
    }

    // обновление поля пользователя
    private static function updateUserField($id_bx, $fieldName, $fieldValue) {
        $user = new \CUser;
        $result = $user->Update($id_bx, [$fieldName => $fieldValue]);
        return $result;
    }

    // ищем первого пользователя по полю
    private static function findUserByField($fieldName, $fieldValue) {
        // сразу фильтруем по тг id
        $filter = [$fieldName => $fieldValue];
        $select = [
            'ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 
            'PERSONAL_PHONE', 'PERSONAL_MOBILE', 'UF_RULES', 
            'UF_RATING','UF_BUSY', 'UF_TELEGRAM_ID', 'UF_CARMA_SUMM', 
            'UF_TG_DIALOG_STATUS'
        ];

        $res = UserTable::getList(['select' => $select, 'filter' => $filter, 'order' => ['UF_RATING' => 'DESC'] ]);
        $users = $res->fetchAll();

        $user = [];
        if(!empty($users)) {
            $user = $users[0];
            
        }
        return $user;
    }

    // есть ли у пользователя (по id) телеграм-ид
    public static function isSetTelegramId($id_bx) {
        $user = new \CUser;
        $userInfo = $user->GetByID($id_bx)->Fetch();

        return $userInfo && !empty($userInfo['UF_TELEGRAM_ID']);
    }

    // // получить телеграм-ид (по id битрикс)
    // public static function getTelegramId($id_bx) {
    //     $user = new \CUser;
    //     $userInfo = $user->GetByID($id_bx)->Fetch();

    //     return $userInfo['UF_TELEGRAM_ID'];
    // }
}