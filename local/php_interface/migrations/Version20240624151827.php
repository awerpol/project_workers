<?php

namespace Sprint\Migration;


class Version20240624151827 extends Version
{
    protected $description = "пользовательские настройки";

    protected $moduleVersion = "4.6.1";

    public function up()
    {
        $helper = $this->getHelperManager();
    $helper->UserOptions()->saveUserGrid(array (
  'views' => 
  array (
    'default' => 
    array (
      'columns' => 
      array (
        0 => 'LOGIN',
        1 => 'ACTIVE',
        2 => 'TIMESTAMP_X',
        3 => 'NAME',
        4 => 'LAST_NAME',
        5 => 'EMAIL',
        6 => 'ID',
        7 => 'PERSONAL_GENDER',
        8 => 'PERSONAL_PHONE',
        9 => 'UF_RULES',
        10 => 'UF_TELEGRAM_ID',
        11 => 'UF_BUSY',
      ),
      'columns_sizes' => 
      array (
        'expand' => 1,
        'columns' => 
        array (
        ),
      ),
      'sticked_columns' => 
      array (
      ),
      'custom_names' => 
      array (
      ),
      'last_sort_by' => 'id',
      'last_sort_order' => 'asc',
    ),
  ),
  'filters' => 
  array (
  ),
  'current_view' => 'default',
));

    }

    public function down()
    {
        //your code ...
    }
}
