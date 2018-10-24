<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $APPLICATION;
$APPLICATION->ShowHead();
?>
<? 
/*
$APPLICATION->IncludeComponent(
		'bitrix:crm.lead.menu',
		'',
		array(
			'ELEMENT_ID' => $_REQUEST["ID"],
			'TYPE' => 'edit'
		)
		//, $component
	);*/
	
$basedURL = "/infinity/?phone=$_REQUEST[phone]&userid=$_REQUEST[userid]&phoneid=$_REQUEST[phoneid]";
	
$APPLICATION->IncludeComponent(
    "bitrix:crm.lead.".(isset($_REQUEST["show"])?"show":"edit"), //"bitrix:crm.lead.convert",
    "",
    array(
        "ELEMENT_ID" => $_REQUEST["ID"],
        "phone" => $_REQUEST["phone"],
		"PATH_TO_LEAD_SHOW" => $basedURL.'&lead_id=#lead_id#&show',
		"PATH_TO_LEAD_EDIT" => $basedURL.'&lead_id=#lead_id#&edit'
    )
); ?>
