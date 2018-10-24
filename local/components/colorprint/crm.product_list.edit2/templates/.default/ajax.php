<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>

<? $APPLICATION->IncludeComponent('colorprint:crm.product_list.edit',
	'',
	array
	(
		"OWNER_ID" => $_REQUEST["OWNER_ID"],
		"OWNER_TYPE" => $_REQUEST["OWNER_TYPE"],
		"IS_AJAX_CALL" => "Y"
	),
	false,
	array('ACTIVE_COMPONENT'=>'Y')
); ?>