<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

global $APPLICATION;

if (!isset($arResult['INTERNAL']) || !$arResult['INTERNAL'])
{
	if(isset($arResult['CRM_CUSTOM_PAGE_TITLE']))
		$APPLICATION->SetTitle($arResult['CRM_CUSTOM_PAGE_TITLE']);
	elseif (isset($arResult['ELEMENT']['ID']))
	{
		$APPLICATION->AddChainItem(GetMessage('CRM_DEAL_NAV_TITLE_LIST'), $arParams['PATH_TO_DEAL_LIST']);
		if (!empty($arResult['ELEMENT']['ID']))
			$APPLICATION->SetTitle(GetMessage('CRM_DEAL_NAV_TITLE_EDIT', array('#NAME#' => $arResult['ELEMENT']['TITLE'])));
		else
			$APPLICATION->SetTitle(GetMessage('CRM_DEAL_NAV_TITLE_ADD')); 
	}
	else
	{
		$APPLICATION->SetTitle(GetMessage('CRM_DEAL_NAV_TITLE_LIST'));
	}
}
?>