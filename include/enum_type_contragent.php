<?include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");?>
<?php 
CModule::IncludeModule('crm');
$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_CONTR_TYPE_ID',
    'USER_TYPE_ID'      => 'enumeration',
'XML_ID'            => 'XML_ID_CONTRAGENT_CATEGORY_ID_FIELD',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Тип контрагента',
        'en'    => 'Сontragent type',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Тип контрагента',
        'en'    => 'Сontragent type',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Тип контрагента',
'en'    => 'Сontragent type',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении типа контрагента',
        'en'    => 'An error in completing the contragent field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

global $DB;
$category = $DB->Query("SELECT * from 1c_category_contragent"); 
$counter = 1;
while($row = $category->fetch()){
	$arAddEnum['n'.$counter] = array(
	  'XML_ID' => $counter,
      'VALUE' => $row["category"],
      'DEF' => 'N',
      'SORT' => $counter
	);
	$counter++;
	$id = $row['id'];
	$DB->Query("UPDATE 1c_category_contragent SET bx_flag = 1 WHERE id = $id"); 
}


$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId); 
$obEnum = new CUserFieldEnum();
$obEnum->SetEnumValues($iUserFieldId, $arAddEnum);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_COUNTRY_ID',
    'USER_TYPE_ID'      => 'enumeration',
'XML_ID'            => 'XML_COUNTRY_ID_FIELD',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Страна регистрации',
        'en'    => 'Country',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Страна регистрации',
        'en'    => 'Country',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Страна регистрации',
'en'    => 'Country',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении страны',
        'en'    => 'An error in completing the сountry field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

global $DB;
$country = $DB->Query("SELECT * from 1c_country"); 
$counter = 1;
while($row = $country->fetch()){
	$arAddEnum['n'.$counter] = array(
	  'XML_ID' => $counter,
      'VALUE' => $row["COUNTRY_NAME"] . " " . $row["CITY_NAME"],
      'DEF' => 'N',
      'SORT' => $counter
	);
	$counter++;
	$id = $row['id'];
	$DB->Query("UPDATE 1c_country SET bx_flag = 1 WHERE id = $id"); 
}


$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId); 
$obEnum = new CUserFieldEnum();
$obEnum->SetEnumValues($iUserFieldId, $arAddEnum);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_CODE',
    'USER_TYPE_ID'      => 'string',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Код',
        'en'    => 'Code',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Код',
        'en'    => 'Code',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Код',
'en'    => 'Code',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении кода',
        'en'    => 'An error in completing the code field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId); 

//==============================================================================================================================================================

CModule::IncludeModule('crm');
$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_CONTR_CAT_ID',
    'USER_TYPE_ID'      => 'enumeration',
'XML_ID'            => 'XML_ID_CONTRAGENT_CATEGORY_ID_FIELD',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Категория контрагента',
        'en'    => 'Сontragent category',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Категория контрагента',
        'en'    => 'Сontragent category',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Категория контрагента',
'en'    => 'Сontragent category',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении категории контрагента',
        'en'    => 'An error in completing the contragent category field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

global $DB;
$path = $DB->Query("SELECT * from 1c_path"); 
$counter = 1;
while($row = $path->fetch()){
	$arAddEnum['n'.$counter] = array(
	  'XML_ID' => $counter,
      'VALUE' => $row["name"],
      'DEF' => 'N',
      'SORT' => $counter
	);
	$counter++;
	$id = $row['id'];
	$DB->Query("UPDATE 1c_path SET bx_flag = 1 WHERE id = $id"); 
}


$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId); 
$obEnum = new CUserFieldEnum();
$obEnum->SetEnumValues($iUserFieldId, $arAddEnum);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_ACTION_ON',
    'USER_TYPE_ID'      => 'string',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Действ. на основании',
        'en'    => 'Acting on the basis of',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Действ. на основании',
        'en'    => 'Acting on the basis of',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Действ. на основании',
'en'    => 'Acting on the basis of',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении основания',
        'en'    => 'An error in completing the basis field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId); 

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_OFFSHORE',
    'USER_TYPE_ID'      => 'boolean',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Резидент оффшорной зоны',
        'en'    => 'Resident of the offshore zone',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Резидент оффшорной зоны',
        'en'    => 'Resident of the offshore zone',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Резидент оффшорной зоны',
'en'    => 'Resident of the offshore zone',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении оффшора',
        'en'    => 'An error in completing the offshore field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_IDEPENDENT',
    'USER_TYPE_ID'      => 'boolean',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Взаимозависимое лицо',
        'en'    => 'Interdependent person',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Взаимозависимое лицо',
        'en'    => 'Interdependent person',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Взаимозависимое лицо',
'en'    => 'Interdependent person',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении взаимозависимого лица',
        'en'    => 'An error in completing the interdependent person field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_DEAL',
    'USER_TYPE_ID'      => 'boolean',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Сделка по перечню',
        'en'    => 'Deal by list',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Сделка по перечню',
        'en'    => 'Deal by list',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Сделка по перечню',
'en'    => 'Deal by list',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении сделки по перечню',
        'en'    => 'An error in completing the deal by list field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_ORG_BIG',
    'USER_TYPE_ID'      => 'boolean',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Включает крупные платежи',
        'en'    => 'Includes large payments',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Включает крупные платежи',
        'en'    => 'Includes large payments',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Включает крупные платежи',
'en'    => 'Includes large payments',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении',
        'en'    => 'An error in completing the Includes large payments field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_CODE_FILL',
    'USER_TYPE_ID'      => 'string',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Код филиала',
        'en'    => 'Branch code',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Код филиала',
        'en'    => 'Branch code',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Код филиала',
'en'    => 'Branch code',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении кода филиала',
        'en'    => 'An error in completing the branch code field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_ORDER',
    'USER_TYPE_ID'      => 'string',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Приказ',
        'en'    => 'Order',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Приказ',
        'en'    => 'Order',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Приказ',
'en'    => 'Order',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении кода приказа',
        'en'    => 'An error in completing the Order field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_RS',
    'USER_TYPE_ID'      => 'string',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Р/С',
        'en'    => 'Р/С',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Р/С',
        'en'    => 'Р/С',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Р/С',
'en'    => 'Р/С',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении Р/С',
        'en'    => 'An error in completing the Р/С field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_LIC',
    'USER_TYPE_ID'      => 'string',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Лицензия',
        'en'    => 'Lic',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Лицензия',
        'en'    => 'Lic',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Лицензия',
'en'    => 'Lic',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении лицензии',
        'en'    => 'An error in completing the Lic field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_COMPANY',
    'FIELD_NAME'        => 'UF_CRM_INVOICE',
    'USER_TYPE_ID'      => 'string',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Корр. счёт',
        'en'    => 'Invoice',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Корр. счёт',
        'en'    => 'Invoice',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Корр. счёт',
'en'    => 'Invoice',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении корр. счёта',
        'en'    => 'An error in completing the Invoice field',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_CONTACT',
    'FIELD_NAME'        => 'UF_CRM_ACCESS',
    'USER_TYPE_ID'      => 'string',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Код доступа',
        'en'    => 'access_code',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Код доступа',
        'en'    => 'access_code',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Код доступа',
'en'    => 'access_code',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении кода доступа',
        'en'    => 'An error in completing the access_code',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);

//==============================================================================================================================================================


$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_CONTACT',
    'FIELD_NAME'        => 'UF_CRM_SALARY',
    'USER_TYPE_ID'      => 'integer',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Оклад',
        'en'    => 'salary',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Оклад',
        'en'    => 'salary',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Оклад',
'en'    => 'salary',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении оклада',
        'en'    => 'An error in completing the salary',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);


//==============================================================================================================================================================


$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_CONTACT',
    'FIELD_NAME'        => 'UF_CRM_RATE',
    'USER_TYPE_ID'      => 'integer',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Ставка сдельщика',
        'en'    => 'rate_pieceworker',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Ставка сдельщика',
        'en'    => 'rate_pieceworker',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Ставка сдельщика',
'en'    => 'rate_pieceworker',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении ставки сдельщика',
        'en'    => 'An error in completing the rate_pieceworker',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);


//==============================================================================================================================================================


$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_CONTACT',
    'FIELD_NAME'        => 'UF_CRM_PERIOD',
    'USER_TYPE_ID'      => 'boolean',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Исправлять закрытый период',
        'en'    => 'closed_period',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Исправлять закрытый период',
        'en'    => 'closed_period',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Исправлять закрытый период',
'en'    => 'closed_period',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении Исправлять закрытый период',
        'en'    => 'An error in completing the closed_period',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);


//==============================================================================================================================================================


$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_CONTACT',
    'FIELD_NAME'        => 'UF_CRM_SECRET',
    'USER_TYPE_ID'      => 'boolean',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'секретно',
        'en'    => 'secretly',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'секретно',
        'en'    => 'secretly',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'секретно',
'en'    => 'secretly',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении секретно',
        'en'    => 'An error in completing the secretly',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);


//==============================================================================================================================================================

$oUserTypeEntity    = new CUserTypeEntity();
 
$aUserFields    = array(
    'ENTITY_ID'         => 'CRM_CONTACT',
    'FIELD_NAME'        => 'UF_CRM_FROM_1C',
    'USER_TYPE_ID'      => 'boolean',
'XML_ID'            => 'XML_ID_CODE',
'SORT'              => 500,
'MULTIPLE'          => 'N',
    'MANDATORY'         => 'N',
    'SHOW_FILTER'       => 'N',
    'SHOW_IN_LIST'      => '',
    'EDIT_IN_LIST'      => '',
    'IS_SEARCHABLE'     => 'N',
    'SETTINGS'          => array(
        'DEFAULT_VALUE' => '',
        'SIZE'          => '100',
        'ROWS'          => '1',
        'MIN_LENGTH'    => '0',
        'MAX_LENGTH'    => '0',
        'REGEXP'        => '',
),
    'EDIT_FORM_LABEL'   => array(
        'ru'    => 'Сотрудник 1С',
        'en'    => '1C',
    ),
    'LIST_COLUMN_LABEL' => array(
        'ru'    => 'Сотрудник 1С',
        'en'    => '1C',
    ),
    'LIST_FILTER_LABEL' => array(
        'ru'    => 'Сотрудник 1С',
'en'    => '1C',
),
    'ERROR_MESSAGE'     => array(
        'ru'    => 'Ошибка при заполнении Сотрудник 1С',
        'en'    => 'An error in completing the 1C',
),
    'HELP_MESSAGE'      => array(
        'ru'    => '',
'en'    => '',
    ),
);

$iUserFieldId = $oUserTypeEntity->Add( $aUserFields ); // int
echo($iUserFieldId);


?>