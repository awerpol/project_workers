<?php

namespace Sprint\Migration;


class Version20240328183653 extends Version
{
    protected $description = "пользовательских полей";

    protected $moduleVersion = "4.6.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'USER',
  'FIELD_NAME' => 'UF_RULES',
  'USER_TYPE_ID' => 'boolean',
  'XML_ID' => '',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DEFAULT_VALUE' => 0,
    'DISPLAY' => 'CHECKBOX',
    'LABEL' => 
    array (
      0 => '',
      1 => '',
    ),
    'LABEL_CHECKBOX' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Допущен',
    'ru' => 'Допущен',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'USER',
  'FIELD_NAME' => 'UF_RATING',
  'USER_TYPE_ID' => 'integer',
  'XML_ID' => '',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'MIN_VALUE' => 0,
    'MAX_VALUE' => 0,
    'DEFAULT_VALUE' => 0,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Рейтинг',
    'ru' => 'Рейтинг',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'USER',
  'FIELD_NAME' => 'UF_TELEGRAM_ID',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => '',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'I',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'USER',
  'FIELD_NAME' => 'UF_CARMA_SUMM',
  'USER_TYPE_ID' => 'double',
  'XML_ID' => '',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'PRECISION' => 4,
    'SIZE' => 20,
    'MIN_VALUE' => 0.0,
    'MAX_VALUE' => 0.0,
    'DEFAULT_VALUE' => NULL,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'USER',
  'FIELD_NAME' => 'UF_BUSY',
  'USER_TYPE_ID' => 'boolean',
  'XML_ID' => '',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'Y',
  'SETTINGS' => 
  array (
    'DEFAULT_VALUE' => 0,
    'DISPLAY' => 'CHECKBOX',
    'LABEL' => 
    array (
      0 => '',
      1 => '',
    ),
    'LABEL_CHECKBOX' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
    }

    public function down()
    {
        //your code ...
    }
}
