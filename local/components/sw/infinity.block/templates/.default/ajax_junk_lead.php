<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
CModule::IncludeModule('crm');
global $USER;

$now = ConvertTimeStamp(time() + CTimeZone::GetOffset(), 'FULL', SITE_ID);
$nowStr = ConvertTimeStamp(MakeTimeStamp($now), 'FULL', SITE_ID);

$arPhoneFields = array();

$arPhoneFields["OWNER_ID"] = $_REQUEST["id"];
$arPhoneFields["OWNER_TYPE"] = CCrmOwnerType::LeadName;
$arPhoneFields["TYPE_ID"] = 2;
$arPhoneFields["SUBJECT"] = "Звонок";
$arPhoneFields["END_TIME"] = $nowStr;
$arPhoneFields["START_TIME"] = $nowStr;
$arPhoneFields["NOTIFY_TYPE"] = 0;
$arPhoneFields["COMPLETED"] = "Y";
$arPhoneFields["PRIORITY"] = 2;
$arPhoneFields["RESPONSIBLE_ID"] = intval($USER->GetID());
$arPhoneFields["DESCRIPTION"] = $_REQUEST["comment"];
$arPhoneFields["DESCRIPTION_TYPE"] = 1;
$arPhoneFields["DIRECTION"] = 1;
$arPhoneFields["storageTypeID"] = \Bitrix\Crm\Integration\StorageType::getDefaultTypeID();
$arPhoneFields["BINDINGS"][] = array("OWNER_TYPE_ID" => CCrmOwnerType::Lead, "OWNER_ID" => $_REQUEST["id"]);

if ($ID_ACT = CCrmActivity::Add($arPhoneFields))
{
    $arPhoneFields["ID"] = $ID_ACT;

    $arCommunicationFields[] = array(
        "ID" => $ID_ACT,
        "ENTITY_TYPE_ID" => CCrmOwnerType::Lead,
        "TYPE" => "PHONE",
        "ENTITY_ID" => $_REQUEST["id"],
        "VALUE" => $_REQUEST["phone"]
    );

    CCrmActivity::SaveCommunications($ID_ACT, $arCommunicationFields);

    $result["PHONE"] = array(
        "STATUS" => "OK",
        "MESSAGE" => ""
    );
}
else
{
    $result["PHONE"] = array(
        "STATUS" => "ERROR",
        "MESSAGE" => "Не удалось сохранить звонок"
    );
}

$CCrmLead = new CCrmLead();
$arFields = array('STATUS_ID' => 'JUNK');
if ($CCrmLead->Update($_REQUEST["id"], $arFields))
{
    $result["LEAD"] = array(
        "STATUS" => "OK",
        "MESSAGE" => ""
    );
}
else
{
    $result["LEAD"] = array(
        "STATUS" => "ERROR",
        "MESSAGE" => "Не удалось обновить данные лида"
    );
}

echo json_encode($result);
?>