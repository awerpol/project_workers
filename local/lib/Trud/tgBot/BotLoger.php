<?php

namespace Trud\TgBot;

class BotLoger 
{
    public static function logUpdate ($update) {
        $logsFile    = $_SERVER['DOCUMENT_ROOT'] . '/local/logs/botLogs.log';
        $currentTime = date('d.m.Y H:i:s');
        $logData     = sprintf (
            '%s chat: %s, user: %s, text: %s',
            $currentTime,
            $update['message']['chat']['id'] ?? 'unknown',
            $update['message']['from']['username'] ?? 'unknown',
            $update['message']['text'] ?? 'no text'
        );

                /* -------- */
                // ob_start(); // Начать буферизацию вывода
                // var_dump($update); 
                // $logData = ob_get_clean(); // очистить буфер
                // /* ------- */

        file_put_contents($logsFile, $logData . PHP_EOL, FILE_APPEND);
    }

    public static function logSystem ($message) {
        $logsFile    = $_SERVER['DOCUMENT_ROOT'] . '/local/logs/botLogs.log';
        $logData     = date('d.m.Y H:i:s') . ' ' . $message;
        file_put_contents($logsFile, $logData . PHP_EOL, FILE_APPEND);
    }

    public static function logError ($message) {
        $logsFile    = $_SERVER['DOCUMENT_ROOT'] . '/local/logs/botErrors.log';
        $logData     = date('d.m.Y H:i:s') . ' Error: ' . $message;
        file_put_contents($logsFile, $logData . PHP_EOL, FILE_APPEND);
    }

    public static function logChat ($tgId, $message) {
        $logsFile    = $_SERVER['DOCUMENT_ROOT'] . '/local/logs/chats/'. $tgId . '.log';
        $logData     = date('d.m.Y H:i:s') . ' ' . $message;
        file_put_contents($logsFile, $logData . PHP_EOL, FILE_APPEND);
    }

    /* =============================== состояния диалога =============================== */

    // Функция для добавления нового статуса в массив и сохранения в файл
    public static function addUserStatus($tgId, $status) {
        $userStatuses = self::loadUserStatuses();
        $userStatuses[$tgId] = $status;
        self::saveUserStatuses($userStatuses);
    }

    public static function getUserStatus ($tgId) {
        $userStatuses = self::loadUserStatuses();
        return $userStatuses[$tgId] ?? null;
    }    
    
    // Функция для загрузки массива статусов из файла JSON
    private static function loadUserStatuses() {
        $file = $_SERVER["DOCUMENT_ROOT"] . '/local/logs/chat_statuses.json';
        if (file_exists($file)) {
            $data = file_get_contents($file);
            return json_decode($data, true);
        } else {
            return array(); // Если файл не существует, возвращаем пустой массив
        }
    }

    // Функция для сохранения массива в файл JSON
    private static function saveUserStatuses($userStatuses) {
        $file = $_SERVER["DOCUMENT_ROOT"] . '/local/logs/chat_statuses.json';
        $json = json_encode($userStatuses, JSON_PRETTY_PRINT);
        file_put_contents($file, $json);
    }


    /* =============================== состояния диалога =============================== */

}