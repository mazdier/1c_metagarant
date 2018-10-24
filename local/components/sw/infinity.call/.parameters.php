<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = array(
	'GROUPS' => array(
	),
	'PARAMETERS' => array(
		'PHONE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('CRM_PHONE'),
			'TYPE' => 'STRING',
			'DEFAULT' => '={$_REQUEST["phone"]}'
		),
		'INFINITY_ADDRESS' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('INFINITY_ADDRESS'),
			'TYPE' => 'STRING',
			'DEFAULT' => ''
		),
		'LEAD_NAME_TEMPLATE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('LEAD_NAME_TEMPLATE'),
			'TYPE' => 'STRING',
			'DEFAULT' => 'Лид из звонка #PHONE#'
		),
		'INFINITY_USER_ID' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('INFINITY_USER_ID'),
			'TYPE' => 'STRING',
			'DEFAULT' => '={$_REQUEST["userid"]}'
		),
		'INFINITY_CALL_ID' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('INFINITY_CALL_ID'),
			'TYPE' => 'STRING',
			'DEFAULT' => '={$_REQUEST["phoneid"]}'
		),
	)
);
?>