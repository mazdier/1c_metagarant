<?php
$eventManager = \Bitrix\Main\EventManager::getInstance(); 
$eventManager->addEventHandlerCompatible('main', 'OnUserTypeBuildList', 
  array(
    'MyUserType',
    'GetUserTypeDescription'
  )
);
class MyUserType extends CUserTypeString
{

  const USER_TYPE_ID = 'myusertype';

  function GetUserTypeDescription ()
  {
    return array(
      'USER_TYPE_ID' => static::USER_TYPE_ID,
      'CLASS_NAME' => __CLASS__,
      'DESCRIPTION' => 'Кастомное поле',
      'BASE_TYPE' => \CUserTypeManager::BASE_TYPE_STRING,
      'EDIT_CALLBACK' => array(
        __CLASS__,
        'GetPublicEdit'
      ),
      'VIEW_CALLBACK' => array(
        __CLASS__,
        'GetPublicView'
      )
    );
  }

  function GetDBColumnType ($arUserField)
  {
    global $DB;
    switch (strtolower($DB->type))
    {
      case "mysql":
        return "text";
      case "oracle":
        return "varchar2(2000 char)";
      case "mssql":
        return "varchar(2000)";
    }
  }

  public static function GetPublicView ($arUserField, 
      $arAdditionalParameters = array())
  {
    Extension::load('ui.buttons');
    return '<a href="#" class="ui-btn ui-btn-danger">Button From Custom Field</a>';
  }

  public function getPublicEdit($arUserField, $arAdditionalParameters = array())
	{
		$fieldName = static::getFieldName($arUserField, $arAdditionalParameters);
		$value = static::getFieldValue($arUserField, $arAdditionalParameters);

		$html = '';

		foreach($value as $res)
		{
			$attrList = array();

			if($arUserField["EDIT_IN_LIST"] != "Y")
			{
				$attrList['disabled'] = 'disabled';
			}

			if($arUserField["SETTINGS"]["SIZE"] > 0)
			{
				$attrList['size'] = intval($arUserField["SETTINGS"]["SIZE"]);
			}

			if(array_key_exists('attribute', $arAdditionalParameters))
			{
				$attrList = array_merge($attrList, $arAdditionalParameters['attribute']);
			}

			if(isset($attrList['class']) && is_array($attrList['class']))
			{
				$attrList['class'] = implode(' ', $attrList['class']);
			}

			$attrList['class'] = static::getHelper()->getCssClassName().(isset($attrList['class']) ? ' '.$attrList['class'] : '');

			$attrList['name'] = $fieldName;

			$attrList['type'] = 'text';
			$attrList['value'] = $res;
			$attrList['tabindex'] = '0';

			$html .= static::getHelper()->wrapSingleField('<input '.static::buildTagAttributes($attrList).'/>');
		}

		if($arUserField["MULTIPLE"] == "Y" && $arAdditionalParameters["SHOW_BUTTON"] != "N")
		{
			$html .= static::getHelper()->getCloneButton($fieldName);
		}

		static::initDisplay();

		return static::getHelper()->wrapDisplayResult($html);
	}
}
?>