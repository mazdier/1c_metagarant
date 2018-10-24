<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule('crm'))
	return false;

$arComponentParameters = Array(
	'PARAMETERS' => array(
		'PHONE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('CRM_PHONE'),
			'TYPE' => 'STRING',
			'DEFAULT' => '={$_REQUEST["PHONE"]}'
		),
		'INFINITY' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('INFINITY'),
			'TYPE' => 'STRING',
			'DEFAULT' => ''
		),
		'LEAD_NAME_TEMPLATE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('LEAD_NAME_TEMPLATE'),
			'TYPE' => 'STRING',
			'DEFAULT' => 'Лид из звонка #PHONE#'
		),
	)	
);
?>