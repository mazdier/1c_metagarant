<??>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?
CModule::IncludeModule("crm");
$arPhoneFields["OWNER_ID"] = $_REQUEST["entity_id"];
$arPhoneFields["OWNER_TYPE"] = $_REQUEST["entity_type"];
$arPhoneFields["BINDINGS"][] = array("OWNER_TYPE_ID" => CCrmOwnerType::ResolveID($_REQUEST["entity_type"]), "OWNER_ID" => $_REQUEST["entity_id"]);
if (CCrmActivity::Update($_REQUEST["activity_id"], $arPhoneFields))
{
    $arCommunicationFields = CCrmActivity::GetCommunications($_REQUEST["activity_id"]);
    foreach($arCommunicationFields as &$arComm)
    {
        $arComm["ENTITY_ID"] = $_REQUEST["entity_id"];
        $arComm["ENTITY_TYPE_ID"] = CCrmOwnerType::ResolveID($_REQUEST["entity_type"]);
        unset($arComm["ENTITY_SETTINGS"]);
    }

    CCrmActivity::SaveCommunications($_REQUEST["activity_id"], $arCommunicationFields);
}