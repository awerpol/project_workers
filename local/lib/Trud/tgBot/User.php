<?php

namespace Trud\TgBot;

class User
{
    protected $userData = [];

    public function getUserData($userId)
    {
        return isset($this->userData[$userId]) ? $this->userData[$userId] : null;
    }

    public function setUserData($userId, $data)
    {
        $this->userData[$userId] = $data;
    }

    public function registerUser($userId)
    {
        if (!isset($this->userData[$userId])) {
            $this->userData[$userId] = ['registered' => true];
        }
    }

    public function isUserRegistered($userId)
    {
        return isset($this->userData[$userId]);
    }
}