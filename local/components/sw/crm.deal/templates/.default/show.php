<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
$APPLICATION->SetAdditionalCSS('/bitrix/js/crm/css/crm.css');
?>
<div style="display:none">
<?
$APPLICATION->IncludeComponent(
	'bitrix:crm.control_panel',
	'',
	array(
		'ID' => 'DEAL_SHOW',
		'ACTIVE_ITEM_ID' => 'DEAL',
		'PATH_TO_COMPANY_LIST' => isset($arResult['PATH_TO_COMPANY_LIST']) ? $arResult['PATH_TO_COMPANY_LIST'] : '',
		    "AJAX_MODE" => "Y",  // режим AJAX
	"AJAX_OPTION_SHADOW" => "N", // затемнять область
	"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
	"AJAX_OPTION_STYLE" => "Y", // подключать стили
	"AJAX_OPTION_HISTORY" => "N",
		'PATH_TO_COMPANY_EDIT' => isset($arResult['PATH_TO_COMPANY_EDIT']) ? $arResult['PATH_TO_COMPANY_EDIT'] : '',
		'PATH_TO_CONTACT_LIST' => isset($arResult['PATH_TO_CONTACT_LIST']) ? $arResult['PATH_TO_CONTACT_LIST'] : '',
		'PATH_TO_CONTACT_EDIT' => isset($arResult['PATH_TO_CONTACT_EDIT']) ? $arResult['PATH_TO_CONTACT_EDIT'] : '',
		'PATH_TO_DEAL_LIST' => isset($arResult['PATH_TO_DEAL_LIST']) ? $arResult['PATH_TO_DEAL_LIST'] : '',
		'PATH_TO_DEAL_EDIT' => isset($arResult['PATH_TO_DEAL_EDIT']) ? $arResult['PATH_TO_DEAL_EDIT'] : '',
		'PATH_TO_LEAD_LIST' => isset($arResult['PATH_TO_LEAD_LIST']) ? $arResult['PATH_TO_LEAD_LIST'] : '',
		'PATH_TO_LEAD_EDIT' => isset($arResult['PATH_TO_LEAD_EDIT']) ? $arResult['PATH_TO_LEAD_EDIT'] : '',
		'PATH_TO_QUOTE_LIST' => isset($arResult['PATH_TO_QUOTE_LIST']) ? $arResult['PATH_TO_QUOTE_LIST'] : '',
		'PATH_TO_QUOTE_EDIT' => isset($arResult['PATH_TO_QUOTE_EDIT']) ? $arResult['PATH_TO_QUOTE_EDIT'] : '',
		'PATH_TO_INVOICE_LIST' => isset($arResult['PATH_TO_INVOICE_LIST']) ? $arResult['PATH_TO_INVOICE_LIST'] : '',
		'PATH_TO_INVOICE_EDIT' => isset($arResult['PATH_TO_INVOICE_EDIT']) ? $arResult['PATH_TO_INVOICE_EDIT'] : '',
		'PATH_TO_REPORT_LIST' => isset($arResult['PATH_TO_REPORT_LIST']) ? $arResult['PATH_TO_REPORT_LIST'] : '',
		'PATH_TO_DEAL_FUNNEL' => isset($arResult['PATH_TO_DEAL_FUNNEL']) ? $arResult['PATH_TO_DEAL_FUNNEL'] : '',
		'PATH_TO_EVENT_LIST' => isset($arResult['PATH_TO_EVENT_LIST']) ? $arResult['PATH_TO_EVENT_LIST'] : '',
		'PATH_TO_PRODUCT_LIST' => isset($arResult['PATH_TO_PRODUCT_LIST']) ? $arResult['PATH_TO_PRODUCT_LIST'] : ''
	),
	$component
);
?><div class="bx-crm-view">
<?$APPLICATION->IncludeComponent(
	'bitrix:crm.deal.menu', 
	'', 
	array(
		'PATH_TO_DEAL_LIST' => $arResult['PATH_TO_DEAL_LIST'],
		'PATH_TO_DEAL_SHOW' => $arResult['PATH_TO_DEAL_SHOW'],
		'PATH_TO_DEAL_EDIT' => $arResult['PATH_TO_DEAL_EDIT'],
		'PATH_TO_DEAL_FUNNEL' => $arResult['PATH_TO_DEAL_FUNNEL'],
		'PATH_TO_DEAL_IMPORT' => $arResult['PATH_TO_DEAL_IMPORT'],
		'ELEMENT_ID' => $arResult['VARIABLES']['deal_id'],    
		    "AJAX_MODE" => "Y",  // режим AJAX
	"AJAX_OPTION_SHADOW" => "N", // затемнять область
	"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
	"AJAX_OPTION_STYLE" => "Y", // подключать стили
	"AJAX_OPTION_HISTORY" => "N",
		'TYPE' => 'show'
	),
	$component
);?>
</div>
</div>
<div class="bx-crm-view-form">
<?$APPLICATION->IncludeComponent(
	'bitrix:crm.deal.show', 
	'', 
	array(
		'PATH_TO_DEAL_SHOW' => $arResult['PATH_TO_DEAL_SHOW'],
		'PATH_TO_DEAL_EDIT' => $arResult['PATH_TO_DEAL_EDIT'],
		'PATH_TO_DEAL_LIST' => $arResult['PATH_TO_DEAL_LIST'],
		'PATH_TO_LEAD_CONVERT' => $arResult['PATH_TO_LEAD_CONVERT'],		
		'PATH_TO_LEAD_EDIT' => $arResult['PATH_TO_LEAD_EDIT'],		
		'PATH_TO_LEAD_SHOW' => $arResult['PATH_TO_LEAD_SHOW'],	
		'PATH_TO_CONTACT_EDIT' => $arResult['PATH_TO_CONTACT_EDIT'],
		'PATH_TO_CONTACT_SHOW' => $arResult['PATH_TO_CONTACT_SHOW'],
		'PATH_TO_COMPANY_EDIT' => $arResult['PATH_TO_COMPANY_EDIT'],
		'PATH_TO_COMPANY_SHOW' => $arResult['PATH_TO_COMPANY_SHOW'],
		'PATH_TO_USER_PROFILE' => $arResult['PATH_TO_USER_PROFILE'],
		'PATH_TO_PRODUCT_EDIT' => $arResult['PATH_TO_PRODUCT_EDIT'],
		'PATH_TO_PRODUCT_SHOW' => $arResult['PATH_TO_PRODUCT_SHOW'],
		'ELEMENT_ID' => $arResult['VARIABLES']['deal_id'],
		    "AJAX_MODE" => "Y",  // режим AJAX
	"AJAX_OPTION_SHADOW" => "N", // затемнять область
	"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
	"AJAX_OPTION_STYLE" => "Y", // подключать стили
	"AJAX_OPTION_HISTORY" => "N",
		'NAME_TEMPLATE' => $arParams['NAME_TEMPLATE']
	),
	$component
);?>

</div>
