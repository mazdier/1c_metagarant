<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $APPLICATION;
$APPLICATION->ShowHead();
?>
<? $APPLICATION->IncludeComponent(
    "sw:crm.lead.convert",
    "",
    array(
        "ELEMENT_ID" => $_REQUEST["ID"],
        "phone" => $_REQUEST["phone"]
    )
); ?>
