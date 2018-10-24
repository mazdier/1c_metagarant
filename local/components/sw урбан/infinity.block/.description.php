<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	"NAME" => Loc::getMessage('COMPONENT_NAME'),
	"DESCRIPTION" => Loc::getMessage('COMPONENT_DESCRIPTION'),
	"ICON" => '/images/icon.gif',
	"SORT" => 20,
	"PATH" => array(
		"ID" => 'infinity',
		"NAME" => Loc::getMessage('COMPONENT_GROUP'),
		"SORT" => 10,
		"CHILD" => array(
			"ID" => 'standard',
			"NAME" => Loc::getMessage('COMPONENT_DIR'),
			"SORT" => 10
		)
	),
);

?>