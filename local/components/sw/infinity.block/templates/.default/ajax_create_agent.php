<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
/*define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/local/log.txt");
AddMessage2Log(print_r($_REQUEST, TRUE));*/

$url = $_REQUEST['url'].'/call/getactivecalls/?IDUser='.$_REQUEST["IDUser"];
$result = file_get_contents($url);
$result = str_replace(array("\r\n", "\n", "\r", "\t"), "", $result);
$result = str_replace("\\", "/", $result);
$arResponse = json_decode($result, true);
$arJsResult = null;
if (isset($arResponse["result"]["Data"]) && is_array($arResponse["result"]["Data"]) && count($arResponse["result"]["Data"]))
{
    //$arJsResult["RESPONSE"] = $arResponse;
    foreach($arResponse["result"]["Data"] as $arPhoneData)
    {
        if ($arPhoneData["ID"] == $_REQUEST["phone_id"] && 
               !empty($arPhoneData["AWavFile"])
            && !empty($arPhoneData["BWavFile"])
            && $arPhoneData["AWavFile"] != "null"
            && $arPhoneData["BWavFile"] != "null"
        )
        {
            CModule::IncludeModule('crm');
            global $USER;

            $now = ConvertTimeStamp(time() + CTimeZone::GetOffset(), 'FULL', SITE_ID);
            $nowStr = ConvertTimeStamp(MakeTimeStamp($now), 'FULL', SITE_ID);

            $arPhoneFields = array();

            $is_incoming = $arPhoneData["Direction"] == "1";

            $arPhoneFields["SUBJECT"] = $is_incoming ? "Входящий звонок" : "Исходящий звонок";
            $arPhoneFields["DIRECTION"] = $is_incoming ? CCrmActivityDirection::Incoming : CCrmActivityDirection::Outgoing;
			
            $arPhoneFields["OWNER_ID"] = $_REQUEST["entity_id"];
            $arPhoneFields["OWNER_TYPE"] = $_REQUEST["entity_type"];
            $arPhoneFields["TYPE_ID"] = CCrmActivityType::Call;
            //$arPhoneFields["SUBJECT"] = $_REQUEST["type"] != "incoming" ? "Исходящий звонок" : "Входящий звонок";
            $arPhoneFields["END_TIME"] = $nowStr;
            $arPhoneFields["START_TIME"] = $nowStr;
            $arPhoneFields["NOTIFY_TYPE"] = CCrmActivityNotifyType::None;
            $arPhoneFields["COMPLETED"] = "Y";
            $arPhoneFields["PRIORITY"] = CCrmActivityPriority::Medium;
            $arPhoneFields["RESPONSIBLE_ID"] = intval($USER->GetID());
            //$arPhoneFields["DIRECTION"] = $_REQUEST["type"] != "incoming" ? CCrmActivityDirection::Outgoing : CCrmActivityDirection::Incoming;
            $arPhoneFields["BINDINGS"][] = array("OWNER_TYPE_ID" => CCrmOwnerType::ResolveID($_REQUEST["entity_type"]), "OWNER_ID" => $_REQUEST["entity_id"]);

            $arPhoneFields["PROVIDER_ID"] = "INFINITY_CALL";
            $arPhoneFields["PROVIDER_PARAMS"] = array(
														"callid" => $arPhoneData["ID"], 
														"direction"=>($is_incoming?"incoming":"outgoing"), 
														"finished" => "0" 
														,"inituserid" => $_REQUEST["IDUser"]
														);
														
            if ($ID_ACT = CCrmActivity::Add($arPhoneFields))
            {
                $arComm[] = array(
                    "ID" => $ID_ACT,
                    "ENTITY_TYPE_ID" => CCrmOwnerType::ResolveID($_REQUEST["entity_type"]),
                    "TYPE" => "PHONE",
                    "ENTITY_ID" => $_REQUEST["entity_id"],
                    "VALUE" => $_REQUEST["phone"]
                );
                /*$arCommunicationFields = array(
                    "OWNER_ID" => $arPhoneFields["OWNER_ID"],
                    "OWNER_TYPE_ID" => CCrmOwnerType::ResolveID($_REQUEST["entity_type"])
                );*/

                CCrmActivity::SaveCommunications($ID_ACT, $arComm);

                $arJsResult["PHONE"] = array(
                    "STATUS" => "OK",
                    "MESSAGE" => "",
                    "ID" => $ID_ACT
                );

                $arFields = array(
                    "UF_ACTIVITY_ID" => $ID_ACT,
                    "UF_INFINITY_A_FILE" => $arPhoneData["AWavFile"],
                    "UF_INFINITY_B_FILE" => $arPhoneData["BWavFile"],
                    "UF_INFINITY_CALL_ID" => $_REQUEST["phone_id"],
                    "UF_INFINITY_USER_ID" => $_REQUEST["IDUser"]
                );
                if ($relationId = CPhoneRecording::AddRelation($arFields))
                {
                    $arJsResult["RELATION"] = array(
                        "STATUS" => "OK",
                        "MESSAGE" => ""
                    );

                    if (!$agentId = CAgent::AddAgent("CPhoneRecording::AttachRecordToActivity({$relationId});", "", "N", 30))
                    {
                        define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/local/log.txt");
                        global $APPLICATION;
                        if($ex = $APPLICATION->GetException())
                            $strError = $ex->GetString();
                        AddMessage2Log("error adding agent to db: ".$strError);
                    }
                }
                else
                {
                    $arJsResult["RELATION"] = array(
                        "STATUS" => "ERROR",
                        "MESSAGE" => "Не удалось сохранить связь звонка с записью"
                    );
                }
            }
            else
            {
                global $APPLICATION;
                if($ex = $APPLICATION->GetException())
                    $strError = $ex->GetString();
                $arJsResult["PHONE"] = array(
                    "STATUS" => "ERROR",
                    "MESSAGE" => "Не удалось сохранить звонок: ".$strError
                );
            }

			break;
        }
    }
} 
echo json_encode($arJsResult);

/*define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/local/log.txt");
AddMessage2Log("file_get_contents = ".$result);
AddMessage2Log('$arResponse = '.print_r($arResponse, true));*/
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>