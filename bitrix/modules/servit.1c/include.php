<?php
//defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\EventManager;
use Bitrix\Main\SystemException;
$url='https://crm.metagarant.by/';
//Loader::registerAutoLoadClasses('servit.1с', array());
function zd($zd){
	echo "<pre>";
	var_dump($zd);
	echo "</pre>";
}
function z($z){
	echo "<pre>";
	print_r($z);
	echo "</pre>";
}



AddEventHandler('crm', 'OnAfterCrmInvoiceAdd', 'addInvoice');
function addInvoice(&$arFields) {
	global $USER;
	 $x=json_encode(['id'=>$arFields['ID'],'save'=>'add','manager'=>$USER->GetID()]);
    $curlstring="curl -d 'data=".$x."' 'https://crm.metagarant.by/include/1c_ajax_main.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}
AddEventHandler('crm', 'OnAfterCrmInvoiceUpdate', 'updateInvoice');
function updateInvoice(&$arFields) {
	global $USER;
	$x=json_encode(['id'=>$arFields['ID'],'save'=>'update','manager'=>$USER->GetID()]);
    $curlstring="curl -d 'data=".$x."' 'https://crm.metagarant.by/include/1c_ajax_main.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}
AddEventHandler('crm', 'OnAfterCrmCompanyAdd', 'addCompany');
function addCompany(&$arFields) {
	global $USER;
	$x=json_encode(['id'=>$arFields['ID'],'save'=>'add']);
    $curlstring="curl -d 'data=".$x."' 'https://crm.metagarant.by/include/1c_contragent_out.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}
AddEventHandler('crm', 'OnAfterCrmCompanyUpdate', 'updateCompany');
function updateCompany(&$arFields) {
	global $USER;
	$x=json_encode(['id'=>$arFields['ID'],'save'=>'update']);
    $curlstring="curl -d 'data=".$x."' 'https://crm.metagarant.by/include/1c_contragent_out.php' > /dev/null 2>&1 & ";
	\Bitrix\Main\Diag\Debug::writeToFile($curlstring, "var_name", "log/_log_.php");
	exec($curlstring);
}




AddEventHandler('crm', 'OnBeforeCrmCompanyDelete', 'deleteCompany');
function deleteCompany(&$arFields) {
	$x=json_encode(['id'=>$arFields['ID'],'save'=>'delete']);
    $curlstring="curl -d 'data=".$x."' 'https://crm.metagarant.by/include/1c_contragent_out.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}
AddEventHandler('crm', 'OnAfterCrmInvoiceDelete', 'deleteInvoice');
function deleteInvoice(&$arFields) {
	$x=json_encode(['id'=>$arFields['ID'],'save'=>'del']);
    $curlstring="curl -d 'data=".$x."' 'https://crm.metagarant.by/include/1c_ajax_main.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}

//if(B_PROLOG_INCLUDED===false) {require_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");}
AddEventHandler("main", "OnProlog", "IncludeCustom", 50);
function IncludeCustom()
{
	global $APPLICATION;
	require_once $_SERVER["DOCUMENT_ROOT"]."/include/1c_bd_product.php";
	require_once $_SERVER["DOCUMENT_ROOT"]."/include/1c_contragent_in.php";
	$APPLICATION->AddHeadString('<script src="/local/js/jquery.min.js"></script>', true);
	$APPLICATION->AddHeadString('<script src="/local/js/1c_ajax.js"></script>', true);
	$APPLICATION->AddHeadString('<script src="/local/js/core_popup.js"></script>', true);
	$APPLICATION->AddHeadString('<script src="/local/js/react.js"></script>', true);
	$APPLICATION->AddHeadString('<script src="/local/js/react-dom.js"></script>', true);
	$APPLICATION->AddHeadString('<script src="/local/js/calculate.js"></script>', true);
	$APPLICATION->AddHeadString('<script src="/bitrix/components/bitrix/crm.product.search.dialog/templates/.default/bitrix/catalog.product.search/.default/script.min.js"></script>', true);
}
?>