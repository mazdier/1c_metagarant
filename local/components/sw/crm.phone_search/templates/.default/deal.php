<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
global $APPLICATION;
$APPLICATION->ShowHead();
?>

<div style="padding: 1%!important;width: 1052px!important;">

<?
CJSCore::Init(array('jquery', 'date', 'ajax', 'window'));
?>

<?$APPLICATION->IncludeComponent(
	"sw:crm.deal",
	"",
	Array(
'PATH_TO_COMPANY_SHOW' => '/crm/company/show/#company_id#/',
    'PATH_TO_COMPANY_LIST' => '/crm/company/list/',
    'PATH_TO_COMPANY_EDIT' => '/crm/company/edit/#company_id#/',
    'PATH_TO_USER_PROFILE' => '/company/personal/user/#user_id#/',
    'ELEMENT_ID' => 0,
    'CACHE_TYPE' => 'A',
    '~PATH_TO_COMPANY_SHOW' => '/crm/company/show/#company_id#/',
   '~PATH_TO_COMPANY_LIST' => '/crm/company/list/',
    '~PATH_TO_COMPANY_EDIT' => '/crm/company/edit/#company_id#/',
    '~PATH_TO_USER_PROFILE' => '/company/personal/user/#user_id#/',
    '~ELEMENT_ID' => 0,
    '~CACHE_TYPE' => 'A',
    'NAME_TEMPLATE' => '#NAME# #LAST_NAME#',
    "AJAX_MODE" => "Y",  // режим AJAX
	"AJAX_OPTION_SHADOW" => "N", // затемнять область
	"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
	"AJAX_OPTION_STYLE" => "Y", // подключать стили
	"AJAX_OPTION_HISTORY" => "N",
		)
);
?>
</div>
<script type="text/javascript">$("div.webform-buttons ").hide();</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>