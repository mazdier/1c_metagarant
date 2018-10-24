<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $APPLICATION;
$APPLICATION->ShowHead();
?>
<? $APPLICATION->IncludeComponent(
    "bitrix:crm.lead.show",
    "infinity",
    array("ELEMENT_ID" => $_REQUEST["ID"])
); ?>
