<?php

namespace Trud\Helpers;

use Bitrix\Main\Loader;
use CIBlockElement;
use Bitrix\Main\UserTable;
use Bitrix\Main\Type\DateTime;

use Trud\IBlock\InfoIblock;
use Trud\Shifts\ShiftEdit;

class Helper
{
    // получаем значение пользовательского поля (одиночное или множ.)
    public static function getPropValue($iblockCode, $elementId, $field)
    {
        $iblockId = InfoIblock::getIdByCode($iblockCode);

        $rsProperties = CIBlockElement::GetProperty($iblockId, $elementId);
        
        while ($arProperty = $rsProperties->Fetch()) {
            if ($arProperty['CODE'] == $field) {        // Находим нужное свойство
                if ($arProperty['MULTIPLE'] === 'Y') {  // Если поле множественное
                    $result[] = $arProperty['VALUE'];   // Добавляем значение в массив
                } else {
                    $result = $arProperty['VALUE'];     // Значение поля
                }
            }
        }

        return $result;
    }

    // подобрать пользователей (одного пола) с учетом исключений
    public static function pickUpUsers($needed, $gender, $arException, $shiftID = null) 
    {
        $arResultUsers = [];

        if ($needed > 0) {
            // доступные=1, кроме тех кто в левой таблице, Мужчины
            $filter = ['UF_RULES' => '1',  '!=ID' => $arException, 'PERSONAL_GENDER' => [$gender] ];
            $select = ['ID', 'NAME', 'LAST_NAME', 'PERSONAL_GENDER', 'PERSONAL_PHONE', 'UF_RULES', 'UF_RATING', 'UF_BUSY', 'UF_WHERE_ENGAGED'];
    
            $res = UserTable::getList(['select' => $select, 'filter' => $filter, 'order' => ['UF_RATING' => 'DESC'] ]);
            $users = $res->fetchAll();

            // получить время начала и конца текущей смены в формате timestamp
            $thisShiftTimes = self::getShiftTimes($shiftID);
            $thisShiftStart = $thisShiftTimes["$shiftID"][ 'start' ];
            $thisShiftEnd   = $thisShiftTimes["$shiftID"][ 'end' ];
    
            $anotherShiftsTimes = []; // времена начала и конца смен, в которых работники заняты

            foreach ($users as $key => $user) {    
                $isBuzy = false;
                $userShiftsList = $user[ 'UF_WHERE_ENGAGED' ];

                foreach ($userShiftsList as $anotherShiftID) {
                    // если нет такой во временном массиве, то добавить
                    if (!$anotherShiftsTimes["$anotherShiftID"]) {
                        $tmp = self::getShiftTimes($anotherShiftID);
                        $anotherShiftsTimes["$anotherShiftID"] = $tmp["$anotherShiftID"];
                        // $anotherShiftsTimes[] = Helper::getShiftTimes($anotherShiftID);
                    }
                    
                    // сравнить время начала и конца текущей смены с временами смены[$anotherShiftID]
                    if ($thisShiftStart <= $anotherShiftsTimes["$anotherShiftID"][ 'end' ] 
                       && $thisShiftEnd >= $anotherShiftsTimes["$anotherShiftID"][ 'start' ]) {
                        // если условие сработало, то работник занят 
                        $isBuzy = true;
                    }
                } 

                // если работник не занят, то добавить его в результирующий массив
                if (!$isBuzy) { // TODO если он свободен 
                    $arResultUsers[] = $user[ 'ID' ];
                }

                if (count($arResultUsers) >= $needed) {
                    break; // кол-во
                }
            }
        }

        return $arResultUsers;
    }

    // получить массив времен смен
    public static function getShiftTimes($shifts)
    {
        Loader::includeModule('main');
        Loader::includeModule('iblock');

        $iblockId = InfoIblock::getIdByCode('SHIFT_BEING_FORMED');

        // берем все нужные смены
        $filter   = ["IBLOCK_ID" => $iblockId, "ID" => $shifts];
        $arSelect = ["ID", "NAME", "PROPERTY_SHIFT_START", "PROPERTY_SHIFT_END"];

        // Получаем инфоблоки (элементы)
        $dbItems = \CIBlockElement::GetList(
            [],
            $filter,
            false,
            false,
            $arSelect 
        );

        $result = [];
        while ($item = $dbItems->fetch()) {
            // $id = $item['ID'] . "_";
            $id = $item['ID'];
            $startTime = new DateTime($item['PROPERTY_SHIFT_START_VALUE']);
            $startTime = $startTime->getTimestamp();
            // $startTime = $startTime->format('d.m.Y H:i:s');

            $endTime = new DateTime($item['PROPERTY_SHIFT_END_VALUE']);
            $endTime = $endTime->getTimestamp();

            $result["$id"]["start"] = $startTime; 
            $result["$id"]["end"]   = $endTime;
        }

        return $result;
    }

    // при наступлении времени меняем стадию смены
    public static function shiftsChangeStageByCron()
    {
        Loader::includeModule('main');
        Loader::includeModule('iblock');
      
        $iblockId = InfoIblock::getIdByCode('SHIFT_BEING_FORMED');

        $stageFormingId = InfoIblock::getFieldIdByXML_ID('FORMING');
        $stageInWorkId  = InfoIblock::getFieldIdByXML_ID('IN_WORK');
        $stageArchiveId = InfoIblock::getFieldIdByXML_ID('ARCHIVE');

        // смены в работе и в архиве
        $filter   = ["IBLOCK_ID" => $iblockId, "PROPERTY_SHIFT_STAGE" => [$stageFormingId, $stageInWorkId]];
        $arSelect = ["ID", "NAME", "PROPERTY_SHIFT_START", "PROPERTY_SHIFT_END", "PROPERTY_SHIFT_STAGE"];

        // Получаем инфоблоки (элементы)
        $dbItems = \CIBlockElement::GetList(
            [],
            $filter,
            false,
            false,
            $arSelect 
        );

        // timestamp
        $currentTime = new DateTime();
        $currentTime = $currentTime->getTimestamp();

        // тут меняем их стадию
        while ($item = $dbItems->fetch()) {
            $stageNow = $item['PROPERTY_SHIFT_STAGE_ENUM_ID'];

            $startTime = new DateTime($item['PROPERTY_SHIFT_START_VALUE']);
            $startTime = $startTime->getTimestamp();

            $endTime = new DateTime($item['PROPERTY_SHIFT_END_VALUE']);
            $endTime = $endTime->getTimestamp();

            // добавить 2ч для стадии "в работе"
            $transferTime = $startTime + 2 * 60 * 60;

            // формируются и началась -> в работу
            if (($stageNow == $stageFormingId) && ($currentTime >= $startTime)) {
                ShiftEdit::updateStage($item['ID'], 'IN_WORK');
            } 
            
            // закончилась -> в архив
            if ($currentTime >= $endTime) {
                ShiftEdit::updateStage($item['ID'], 'ARCHIVE');
            } 
        }
    }
}