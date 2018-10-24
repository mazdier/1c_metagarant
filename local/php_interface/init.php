<?
require_once "/home/bitrix/www/local/lib/USER_TYPE_FIELDS/UserFieldsInclude.php";//Подключение пользовательских полей
//CModule::IncludeModule("servit.1c");
/*AddEventHandler('crm', 'OnAfterCrmInvoiceAdd', 'addInvoice');
function addInvoice(&$arFields) {
	 $x=json_encode(['id'=>$arFields['ID'],'save'=>'add','manager'=>'1']);
    $curlstring="curl -d 'data=$x' 'https://crm.metagarant.by/include/1c_ajax_main.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}
AddEventHandler('crm', 'OnAfterCrmInvoiceUpdate', 'updateInvoice');
function updateInvoice(&$arFields) {
    
	$x=json_encode(['id'=>$arFields['ID'],'save'=>'update','manager'=>'1']);
    $curlstring="curl -d 'data=$x' 'https://crm.metagarant.by/include/1c_ajax_main.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}
AddEventHandler('crm', 'OnAfterCrmCompanyAdd', 'addCompany');
function addCompany(&$arFields) {
	$x=json_encode(['id'=>$arFields['ID'],'save'=>'add']);
    $curlstring="curl -d 'data=$x' 'https://crm.metagarant.by/include/1c_contragent_out.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}
AddEventHandler('crm', 'OnAfterCrmCompanyUpdate', 'updateCompany');
function updateCompany(&$arFields) {
	$x=json_encode(['id'=>$arFields['ID'],'save'=>'update']);
    $curlstring="curl -d 'data=$x' 'https://crm.metagarant.by/include/1c_contragent_out.php' > /dev/null 2>&1 & ";
	exec($curlstring);
}*/
require_once $_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include.php";
CModule::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
AddEventHandler("subscribe", "AfterPostingSendMail", Array("PostingSendMailHandler", "AfterPostingSendMailHandler"));

class PostingSendMailHandler
{
    function AfterPostingSendMailHandler($arFields)
    {
        $arPosting = CPosting::GetByID($arFields["ID"])->Fetch();
        $subject = $arPosting["SUBJECT"];
        if (strripos($subject, "[CRM:"))
        {
            $activityId = substr($subject, strripos($subject, "[CRM:") + 5, strlen($subject));
            $activityId = substr($activityId, 0, strpos($activityId, "-"));
            $activityId = intval($activityId);

            if (IsModuleInstalled("crm") && CModule::IncludeModule("crm"))
            {
                $arActivity = CCrmActivity::GetByID($activityId, false);
            }

            if ($arActivity["TYPE_ID"] != CCrmActivityType::Email)
            {
                return;
            }

            if ($arFields["STATUS"] == "S")
            {
                $mess = 'Письмо для '.$arPosting["BCC_FIELD"].' отправлено!';
            }
            elseif ($arFields["STATUS"] == "E")
            {
                $mess = 'Произошла ошибка отправки писем на '.$arPosting["BCC_FIELD"].'.<a href="/include/resend_posting.php?ID='.$arPosting["ID"].'" target="_blank">Отправить повторно</a>';
            }
            else
            {
                return;
            }

            if (IsModuleInstalled("im") && CModule::IncludeModule("im") && !empty($mess))
            {
                $arMessageFields21 = array(
                    "TO_USER_ID" => $arActivity["AUTHOR_ID"], // получатель
                    "FROM_USER_ID" => 0, // отправитель (может быть >0)
                    "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM, // тип уведомления
                    "NOTIFY_MODULE" => "im", // модуль запросивший отправку уведомления
                    "NOTIFY_TAG" => "CRM_EMAIL_NOTICE", // символьный тэг для группировки (будет выведено только одно сообщение), если это не требуется - не задаем параметр
                    "NOTIFY_MESSAGE" => $mess // текст уведомления на сайте (доступен html и бб-коды)
                );

                CIMNotify::Add($arMessageFields21);
            }
        }
    }
}

//AddEventHandler("crm", "OnActivityAdd", "OnActivityAddHandler");
function OnActivityAddHandler($ID, &$arFields)
{
    if ($arFields["OWNER_TYPE_ID"] == CCrmOwnerType::Contact && $arFields["TYPE_ID"] == CCrmActivityType::Call)
    {
        $arContact = CCrmContact::GetByID($arFields["OWNER_ID"]);
        if (!empty($arContact["COMPANY_ID"]) && intval($arContact["COMPANY_ID"]) > 0)
        {
            $arNewActivityFields = array(
                "BINDINGS" => $arFields["BINDINGS"]
            );

            $arNewActivityFields["BINDINGS"][] = array(
                "OWNER_ID" => intval($arContact["COMPANY_ID"]),
                "OWNER_TYPE_ID" => CCrmOwnerType::Company
            );

            if (CCrmActivity::Update($arFields["ID"], $arNewActivityFields, false, true, array('REGISTER_SONET_EVENT' => true)))
            {
                $arCommunicationFields = CCrmActivity::GetCommunications($arFields["ID"]);
                $arCommunicationFields = $arCommunicationFields[0];
                unset($arCommunicationFields["ENTITY_SETTINGS"]);

                $arCommunicationFields["ENTITY_ID"] = intval($arContact["COMPANY_ID"]);
                $arCommunicationFields["ENTITY_TYPE_ID"] = CCrmOwnerType::Company;

                CCrmActivity::SaveCommunications($arFields["ID"], $arCommunicationFields);
            }
        }
    }
}

class CPhoneRecording
{
    const TABLE_NAME = "bo_call_relations";
    const INFINITY_API_ADDRESS = "http://192.168.80.188:10080";
    const INFINITY_REC_USER = 'Администратор';
    const INFINITY_REC_PASS = 'InfinityX14';
    const INFINITY_REC_ADDR = "192.168.80.188";
    public $_infinityApiAddress = "http://192.168.80.188:10080";
    protected $_infinityRecAddress = "192.168.80.188";
    private $_infinityRecUser = 'Администратор';
    private $_infinityRecPass = 'InfinityX14';
    public static function AttachRecordToActivity($callRelationId, $AddressInfinity, $WebHook)
    {
		file_put_contents($_SERVER["DOCUMENT_ROOT"]."/local/log_infinity.txt", "starting agent with callRelationId = ".$callRelationId."\n", FILE_APPEND);
        $arRelation = self::_getRelationById($callRelationId);
        if (!self::_isActiveCall($arRelation["UF_INFINITY_USER_ID"], $arRelation["UF_INFINITY_CALL_ID"],$AddressInfinity))
        {
			file_put_contents($_SERVER["DOCUMENT_ROOT"]."/local/log_infinity.txt", "  _fillCallOnFinished( $arRelation[UF_INFINITY_CALL_ID], $arRelation[UF_ACTIVITY_ID], $AddressInfinity, $WebHook) callRelationId = ".$callRelationId." \n", FILE_APPEND);
			if(self::_fillCallOnFinished( $arRelation["UF_INFINITY_CALL_ID"], $arRelation["UF_ACTIVITY_ID"], $AddressInfinity, $WebHook))
			{
				file_put_contents($_SERVER["DOCUMENT_ROOT"]."/local/log_infinity.txt", "FINISHED $callRelationId\n", FILE_APPEND);
				return "";
			}
        }
        return "CPhoneRecording::AttachRecordToActivity(".$callRelationId.",'".$AddressInfinity."','".$WebHook."');";
    }
    public static function AddRelation($arFields)
    {
        $hlblock = HL\HighloadBlockTable::getList(array("filter" => array("TABLE_NAME" => self::TABLE_NAME)))->fetch();
        if ($hlblock)
        {
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $dataClass = $entity->getDataClass();
            $result = $dataClass::add($arFields);
            if(!$result->isSuccess())
            {
                return false;
            }
            else
            {
                return $result->getId();
            }
        }
        return false;
    }
    protected function _getRelationById($id)
    {
        if (isset($id) && !empty($id) && intval($id) > 0)
        {
            $id = intval($id);
        }
        else
        {
            return false;
        }
        $hlblock = HL\HighloadBlockTable::getList(array("filter" => array("TABLE_NAME" => self::TABLE_NAME)))->fetch();
        if ($hlblock)
        {
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $main_query = new Entity\Query($entity);

            $main_query->setSelect(array("*"));
            $main_query->setFilter(array("ID" => $id));

            $result = $main_query->exec();

            $result = new CDBResult($result);
            if ($row = $result->Fetch())
            {
                return $row;
            }
        }
        return false;
    }

    protected function _isActiveCall($userId, $callId,$AddressInfinity)
    {
		$url = $AddressInfinity.'/call/getactivecalls/?Extension='.$userId;
		//$url = self::INFINITY_API_ADDRESS.'/call/getactivecalls/?IDUser='.$userId;
        $result = file_get_contents($url);
        $result = str_replace(array("\r\n", "\n", "\r", "\t"), "", $result);
        $result = str_replace("\\", "/", $result);
        $arResponse = json_decode($result, true);
        if (isset($arResponse["result"]["Data"]) && is_array($arResponse["result"]["Data"]) && count($arResponse["result"]["Data"]))
        {
            foreach($arResponse["result"]["Data"] as $arPhoneData)
            {
                if ($arPhoneData["ID"] == $callId)
                {
                    return true;
                }
            }
        }
        return false;
    }
	public function _inf_float($val)
	{
		return floatval(preg_replace("/,/",'.',$val));
	}
	public function _fillCallOnFinished($callid, $activityId, $AddressInfinity, $WebHook)
	{
		CModule::IncludeModule("crm");
		$arResponse  = self::_server("/stat/connectionsbycall/?IDCall=$callid&AllInfo=1",$AddressInfinity);
		if (isset($arResponse["result"]["Data"]) && is_array($arResponse["result"]["Data"]) && count($arResponse["result"]["Data"]))
		{
			$arActivity = CCrmActivity::GetByID($activityId, false);
			$provider_params = $arActivity['PROVIDER_PARAMS'];
			if(!is_array($provider_params)) $provider_params = unserialize($arActivity['PROVIDER_PARAMS']);
			if(is_array($provider_params))
			{
				file_put_contents($_SERVER["DOCUMENT_ROOT"]."/local/log_infinity.txt", "WE HAVE PARAMS $callid, $activityId \n".print_r($provider_params,true)."\n", FILE_APPEND);
				$provider_params['finished'] = '1';
				$connects = array();
				$all_connects_time_full = 0.0;
				$all_connects_time_wait = 0.0;
				$all_connects_time_talk = 0.0;

				foreach($arResponse["result"]["Data"] as $arConnData)
				{
					if(preg_match("/[^\d\+]/",$arConnData['ANumber'].$arConnData['BNumber'])) continue; // Is technical connect, will Skip
					$connect = array_intersect_key( $arConnData, array_flip(array("ID", 'ANumber','BNumber',"BIDUser", "TimeStart", "TimeStartDate","TimeStartTime","DurationFull","DurationWait", "DurationTalk","IDSeance","BAbonentType","AAbonentType","ConnectionDirection","DurationHold","DurationConference" )) );
					$connect['TimeStartTime'] = self::_inf_float($connect['TimeStartTime']);
					$connect['TimeStartDate'] = self::_inf_float($connect['TimeStartDate']);
					$connect['DurationFull'] = self::_inf_float($connect['DurationFull']);
					$connect['DurationWait'] = self::_inf_float($connect['DurationWait']);
					$connect['DurationTalk'] = self::_inf_float($connect['DurationTalk']);
					$connect['DurationConference'] = self::_inf_float($connect['DurationConference']);
					$all_connects_time_full += floatval($connect['DurationFull']);
					$all_connects_time_wait += floatval($connect['DurationWait']);
					$all_connects_time_talk += floatval($connect['DurationTalk']);
					$connects[] = $connect;
				}
				$provider_params['connects'] = $connects;
				$provider_params['DurationFull'] = $all_connects_time_full;
				$provider_params['DurationWait'] = $all_connects_time_wait;
				$provider_params['DurationTalk'] = $all_connects_time_talk;
				$provider_params['BIDUser'] = !empty($connects)?$connects[0]['BIDUser']:'0';
				$provider_params['IDSeance'] = !empty($connects)?$connects[0]['IDSeance']:'0';
				$arFields = array();
				$arFields["PROVIDER_PARAMS"] = $provider_params;
				$arFields["COMPLETED"] = "Y";
				$arFields['PROVIDER_ID'] ='VOXIMPLANT_CALL';
				if (CCrmActivity::Update($activityId, $arFields, false, false))
                {
					$act_f = CCrmActivity::GetByID($activityId, false);
					$RESPONSIBLE_ID = $act_f['RESPONSIBLE_ID'];
					$ORIGIN_ID = $act_f['ORIGIN_ID'];
					$CALL_ID = str_replace('VI_','',$ORIGIN_ID);
					$queryUrl = $WebHook."/telephony.externalcall.finish";
					$queryParams = array(
					  'USER_ID' => $RESPONSIBLE_ID,
					  'CALL_ID' => $CALL_ID,
						//'PHONE_ID' => $_REQUEST['phoneid'],
					  'DURATION' => $all_connects_time_talk*86400,
					  'ADD_TO_CHAT' => 0,
					  'STATUS_CODE' => 200
					);
					$queryData = http_build_query($queryParams);
					$curl = curl_init();
					curl_setopt_array($curl, array(
					  CURLOPT_HEADER => 0,
					  CURLOPT_RETURNTRANSFER => 1,
					  CURLOPT_URL => $queryUrl.'?'.$queryData,
					));
					$result = curl_exec($curl);
					$result =json_decode($result,true);
					CCrmActivity::Delete($activityId, $checkPerms = false, $regEvent = false);
					CCrmActivity::Update($result['result']['CRM_ACTIVITY_ID'], $arFields, false, false);
					curl_close($curl);            
					return true;
                }
			} else return true;
		}
		return false;
	}

    protected function _updateActivity($activityId, $aFilePath, $bFilePath)
    {
        $fileA = 'http://'.self::INFINITY_REC_USER.':'.self::INFINITY_REC_PASS.'@'.self::INFINITY_REC_ADDR.'/'.$aFilePath;
        $fileName = substr($aFilePath, strripos($aFilePath, '/') + 1);
        $newFileA = $_SERVER['DOCUMENT_ROOT'] . '/upload/infinity/tmp/'.$fileName;

        $fileB = 'http://'.self::INFINITY_REC_USER.':'.self::INFINITY_REC_PASS.'@'.self::INFINITY_REC_ADDR.'/'.$bFilePath;
        $fileName = substr($bFilePath, strripos($bFilePath, '/') + 1);
        $newFileB = $_SERVER['DOCUMENT_ROOT'] . '/upload/infinity/tmp/'.$fileName;

        $resultFile = str_replace("wav", "mp3", $fileName);
        $resultFile = $_SERVER['DOCUMENT_ROOT'] . '/upload/infinity/tmp/result/' . $resultFile;
        if (copy($fileA, $newFileA) && copy($fileB, $newFileB))
        {
            $commandString = '/root/bin/ffmpeg -i ' . $newFileA . ' -i ' . $newFileB . ' ' . $resultFile;
            exec($commandString);
            $arFields = array();
            $arFile = CFile::MakeFileArray($resultFile);
            if ($fileId = CFile::SaveFile($arFile, "infinity/crm/activity"))
            {
                $arFile = CFile::GetFileArray($fileId);
                $storageFileId = Bitrix\Crm\Integration\StorageManager::saveFile($arFile);
                $arActivity = CCrmActivity::GetByID($activityId);
                $arActivity["STORAGE_ELEMENT_IDS"] = unserialize($arActivity["STORAGE_ELEMENT_IDS"]);
                if (is_array($arActivity["STORAGE_ELEMENT_IDS"]) && count($arActivity["STORAGE_ELEMENT_IDS"]) > 0)
                {
                    $arFields["STORAGE_ELEMENT_IDS"] = array_merge($arActivity["STORAGE_ELEMENT_IDS"], array($storageFileId));
                }
                else
                {
                    $arFields["STORAGE_ELEMENT_IDS"] = array($storageFileId);
                }
                if (CCrmActivity::Update($activityId, $arFields, false, false))
                {
                    return true;
                }
            }

        }
        return false;
    }

	protected function _server($path,$AddressInfinity)
	{

		$url = $AddressInfinity.$path;
		//$url = self::INFINITY_API_ADDRESS.$path;
        $result = file_get_contents($url);
        $result = str_replace(array("\r\n", "\n", "\r", "\t"), "", $result);
        $result = str_replace("\\", "/", $result);
        $arResponse = json_decode($result, true);
		return $arResponse;
	}

}

AddEventHandler("main", "OnBeforeProlog", "IncludeCustomScript", 50);
function IncludeCustomScript()
{
    global $APPLICATION;
    $APPLICATION->AddHeadScript('/local/include/infinity.js');
	 $APPLICATION->AddHeadScript('/local/js/jquery.min.js');
	 //$APPLICATION->AddHeadScript('/local/js/1c_ajax.js');
	
    define('INTR_SKIP_EVENT_ADD', 'Y');
}

AddEventHandler("crm", "OnGetActivityProviders", "IncludeInfinityCallProvider", 100);
CModule::IncludeModule("crm");
require_once($_SERVER['DOCUMENT_ROOT'].'/local/lib/provider/call.php'); // TODO: add from CModule::AddAutoloadClasses... in module
function IncludeInfinityCallProvider()
{
//print_r(Sw\Infinity\CallInfinity::className());//INFINITY_CALL,
    return array(
       // Sw\Infinity\CallInfinity::getId() => Sw\Infinity\CallInfinity::className()
	   'VOXIMPLANT_CALL'=>Sw\Infinity\CallInfinity::className()
    );
}


//region clear notice
/*Ссылки к задачам в уведомлениях + прочтение уведомлений по задаче*/
AddEventHandler("main", "OnBeforeProlog", "ClearTaskNotice", 100);
CModule::IncludeModule("im");
class CBoIMNotify extends CIMNotify
{
    public function GetTasksNotifyList($arParams = array())
    {
        global $DB;
        if (isset($arParams['USER_ID']) && intval($arParams['USER_ID']) > 0)
        {
            $arParams["USER_ID"] = intval($arParams["USER_ID"]);
        }
        else
        {
            global $USER;
            if (!$USER->IsAuthorized())
            {
                return array();
            }
            else
            {
                $arParams["USER_ID"] = $USER->GetID();
            }
        }

        $bTimeZone = isset($arParams['USE_TIME_ZONE']) && $arParams['USE_TIME_ZONE'] == 'N'? false: true;

        $sqlStr = "
			SELECT COUNT(M.ID) as CNT, M.CHAT_ID
			FROM b_im_relation R
			INNER JOIN b_im_message M ON M.CHAT_ID = R.CHAT_ID
			WHERE R.USER_ID = ".$arParams["USER_ID"]." AND R.MESSAGE_TYPE = '".IM_MESSAGE_SYSTEM."'
			GROUP BY M.CHAT_ID
		";
        $res_cnt = $DB->Query($sqlStr);
        $res_cnt = $res_cnt->Fetch();
        $cnt = $res_cnt["CNT"];
        $chatId = $res_cnt["CHAT_ID"];

        $arNotify = Array();
        if ($cnt > 0)
        {
            if (!$bTimeZone)
                CTimeZone::Disable();

            $strSql ="
				SELECT
					M.ID,
					M.CHAT_ID,
					M.MESSAGE,
					M.MESSAGE_OUT,
					DATE_CREATE,
					M.NOTIFY_TYPE,
					M.NOTIFY_MODULE,
					M.NOTIFY_EVENT,
					M.NOTIFY_TITLE,
					M.NOTIFY_BUTTONS,
					M.NOTIFY_TAG,
					M.NOTIFY_SUB_TAG,
					M.NOTIFY_READ,
					".$arParams['USER_ID']." TO_USER_ID,
					M.AUTHOR_ID FROM_USER_ID
				FROM b_im_message M
				WHERE M.CHAT_ID = ".$chatId." AND M.NOTIFY_EVENT = 'comment' AND
				(M.NOTIFY_TAG LIKE 'TASKS|TASK|".$arParams["TASK_ID"]."%' OR
				 M.NOTIFY_TAG LIKE 'TASKS|COUNTERS_NOTICE|".$arParams["TASK_ID"]."%' OR
				 M.NOTIFY_TAG LIKE 'TASKS|COMMENT|".$arParams["TASK_ID"]."%')
				ORDER BY M.DATE_CREATE DESC, ID DESC
			";
            if (!$bTimeZone)
                CTimeZone::Enable();


            $dbRes = new CDBResult();
            $dbRes->NavQuery($strSql, $cnt, Array('iNumPage' => 0, 'nPageSize' => $cnt));

            $arResult = Array();
            while ($arRes = $dbRes->Fetch())
            {
                $arResult[] = $arRes;
            }
            return $arResult;
        }
        return $arNotify;
    }

    public function GetBlogPostNotifyList($arParams = array())
    {
        global $DB;
        if (isset($arParams['USER_ID']) && intval($arParams['USER_ID']) > 0)
        {
            $arParams["USER_ID"] = intval($arParams["USER_ID"]);
        }
        else
        {
            global $USER;
            if (!$USER->IsAuthorized())
            {
                return array();
            }
            else
            {
                $arParams["USER_ID"] = $USER->GetID();
            }
        }

        $bTimeZone = isset($arParams['USE_TIME_ZONE']) && $arParams['USE_TIME_ZONE'] == 'N'? false: true;

        $sqlStr = "
			SELECT COUNT(M.ID) as CNT, M.CHAT_ID
			FROM b_im_relation R
			INNER JOIN b_im_message M ON M.CHAT_ID = R.CHAT_ID
			WHERE R.USER_ID = ".$arParams["USER_ID"]." AND R.MESSAGE_TYPE = '".IM_MESSAGE_SYSTEM."'
			GROUP BY M.CHAT_ID
		";
        $res_cnt = $DB->Query($sqlStr);
        $res_cnt = $res_cnt->Fetch();
        $cnt = $res_cnt["CNT"];
        $chatId = $res_cnt["CHAT_ID"];

        $arNotify = Array();
        if ($cnt > 0)
        {
            if (!$bTimeZone)
                CTimeZone::Disable();

            $strSql ="
				SELECT
					M.ID,
					M.CHAT_ID,
					M.MESSAGE,
					M.MESSAGE_OUT,
					DATE_CREATE,
					M.NOTIFY_TYPE,
					M.NOTIFY_MODULE,
					M.NOTIFY_EVENT,
					M.NOTIFY_TITLE,
					M.NOTIFY_BUTTONS,
					M.NOTIFY_TAG,
					M.NOTIFY_SUB_TAG,
					M.NOTIFY_READ,
					".$arParams['USER_ID']." TO_USER_ID,
					M.AUTHOR_ID FROM_USER_ID
				FROM b_im_message M
				WHERE M.CHAT_ID = ".$chatId." AND M.NOTIFY_EVENT = 'comment' AND M.NOTIFY_TAG LIKE 'BLOG|COMMENT|".$arParams["POST_ID"]."%'
				ORDER BY M.DATE_CREATE DESC, ID DESC
			";
            if (!$bTimeZone)
                CTimeZone::Enable();


            $dbRes = new CDBResult();
            $dbRes->NavQuery($strSql, $cnt, Array('iNumPage' => 0, 'nPageSize' => $cnt));

            $arResult = Array();
            while ($arRes = $dbRes->Fetch())
            {
                $arResult[] = $arRes;
            }
            return $arResult;
        }
        return $arNotify;
    }
}

function ClearTaskNotice()
{
    global $APPLICATION;
    $APPLICATION->AddHeadScript('/local/include/infinity.js');

    if (isset($_GET["task_id"])
        && isset($_GET["clear_im_notify"])
        && intval($_GET["task_id"])
        && $_GET["clear_im_notify"] == "Y"
        && CModule::IncludeModule("im")
    )
    {
        $obIMNotify = new CBoIMNotify();
        $arNotifyList = $obIMNotify->GetTasksNotifyList(array("TASK_ID" => $_GET["task_id"]));
        foreach($arNotifyList as $arNotify)
        {
            //$obIMNotify->MarkNotifyRead($arNotify["ID"]);
            $obIMNotify->Delete($arNotify["ID"]);
        }
    }
    elseif (isset($_GET["post_id"])
        && isset($_GET["clear_im_notify"])
        && intval($_GET["post_id"])
        && $_GET["clear_im_notify"] == "Y"
        && CModule::IncludeModule("im")
    )
    {
        $obIMNotify = new CBoIMNotify();
        $arNotifyList = $obIMNotify->GetBlogPostNotifyList(array("POST_ID" => $_GET["post_id"]));
        foreach($arNotifyList as $arNotify)
        {
            //$obIMNotify->MarkNotifyRead($arNotify["ID"]);
            $obIMNotify->Delete($arNotify["ID"]);
        }
    }
}


function addToURL($key, $value, $url)
{
    $arUrl = explode("#", $url);
    $info = parse_url( $arUrl[0] );
    parse_str( $info['query'], $query );
    $newUrl = "";
    if (!empty($info['scheme']))
    {
        $newUrl .= $info['scheme'] . '://';
    }
    $newUrl .= $info['host'] . $info['path'] . '?' . http_build_query( $query ? array_merge( $query, array($key => $value ) ) : array( $key => $value ) );
    if (strlen($arUrl[1]))
    {
        $newUrl .= "#".$arUrl[1];
    }
    return $newUrl;
}

function replaceAllUrl($str, $arParams)
{
    preg_match_all('/URL=([^\]]+)/', $str, $arUrl);
    foreach($arUrl[1] as $url)
    {
        $newUrl = $url;
        foreach($arParams as  $key => $value)
        {
            $newUrl = addToURL($key, $value, $newUrl);
        }

        $str = str_replace($url, $newUrl, $str);
    }
    return $str;
}

function replaceAllUrlInHtml($str, $arParams)
{
    preg_match_all("/<a[^>]*?href=\"(.*)\"/iU", $str, $arUrl);
//ищу подстроки url
    foreach($arUrl[1] as $url)
    {
        $newUrl = $url;
        foreach($arParams as  $key => $value)
        {
            $newUrl = addToURL($key, $value, $newUrl);
        }

        $str = str_replace($url, $newUrl, $str);
    }
    return $str;
    /*for ($i=0; $i< count($urls[0]); $i++) {
        $msg= str_replace($urls[$i], '<a href="'.$urls[$i].'">'.$urls[$i].'</a>' , $msg);
    }*/
}

AddEventHandler("im", "OnBeforeMessageNotifyAdd", "OnBeforeMessageNotifyAddHandler");
function OnBeforeMessageNotifyAddHandler(&$arFields)
{
    if ($arFields["NOTIFY_MODULE"] == "tasks")
    {
        $arTag = explode("|", $arFields["NOTIFY_TAG"]);
        $taskId = $arTag[2];

        $arFields["NOTIFY_MESSAGE"] = replaceAllUrl($arFields["NOTIFY_MESSAGE"], array(
            "clear_im_notify" => "Y",
            "task_id" => $taskId
        ));
        $arFields["MESSAGE"] = replaceAllUrl($arFields["MESSAGE"], array(
            "clear_im_notify" => "Y",
            "task_id" => $taskId
        ));

        /*$delUrl = $_SERVER["HTTP_ORIGIN"]."/?clear_im_notify=Y&task_id=".$taskId;
        $delUrl = $APPLICATION->GetCurPageParam("task_id=".$taskId."&clear_im_notify=Y", array("task_id", "clear_im_notify"));
        $arFields["NOTIFY_MESSAGE"] .= " #BR#[URL=".$delUrl."]Прочесть все уведомления по задаче[/URL]";
        $arFields["MESSAGE"] .= " #BR#[URL=".$delUrl."]Прочесть все уведомления по задаче[/URL]";*/
    }
    elseif ($arFields["NOTIFY_MODULE"] == "blog")
    {
        $arTag = explode("|", $arFields["NOTIFY_TAG"]);
        $postId = $arTag[2];

        $arFields["NOTIFY_MESSAGE"] = replaceAllUrlInHtml($arFields["NOTIFY_MESSAGE"], array(
            "clear_im_notify" => "Y",
            "post_id" => $postId,
            "trololol" => "Y"
        ));
        $arFields["MESSAGE"] = replaceAllUrlInHtml($arFields["MESSAGE"], array(
            "clear_im_notify" => "Y",
            "post_id" => $postId,
            "trololol" => "Y"
        ));
    }
}
//endregion

class My_Agent_Function {

    function ADD() {
			$arFields = array(
				"MESSAGE_TYPE" => "S", # P - private chat, G - group chat, S - notification
				"TO_USER_ID" => 782,
				"FROM_USER_ID" => 782,
				"MESSAGE" => 'ddd',
				"AUTHOR_ID" => 1,
				"EMAIL_TEMPLATE" => "some",

				"NOTIFY_TYPE" => 2,  # 1 - confirm, 2 - notify single from, 4 - notify single
				"NOTIFY_MODULE" => "main", # module id sender (ex: xmpp, main, etc)
				"NOTIFY_EVENT" => "IM_GROUP_INVITE", # module event id for search (ex, IM_GROUP_INVITE)
				"NOTIFY_TITLE" => "title to send email", # notify title to send email
			);

			CModule::IncludeModule('im');
			CIMMessenger::Add($arFields);
		return "My_Agent_Function::ADD();";
    }
}
class Vacansy {

    function ADD() {
		require_once($_SERVER['DOCUMENT_ROOT']."/crm/start/novaya_stranitsa.php");
		return "Vacansy::ADD();";
    }
}
class Birthday {

    function Happy() {
		require_once($_SERVER['DOCUMENT_ROOT']."/crm/start/birthday.php");
		return "Birthday::Happy();";
    }
}

/*
class CheckStatusCall {

    function Check($ExtensionUser,$entity_type,$entity_id,$activity_id,$phone_number,$phoneid) {

	$isCall = false;

	$queryUrl = "http://192.168.80.188:10080/call/getactivecalls";
	$queryData = http_build_query($queryParams = array(
	  'Extension' => $ExtensionUser,
	));

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_HEADER => 0,
	  CURLOPT_RETURNTRANSFER => 1,
	 // CURLOPT_URL => $queryUrl,
	  CURLOPT_URL => $queryUrl.'?'.$queryData,
	));

	$result = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($result, 1);

	foreach($result['result']['Data'] as $value)
	{
		if($value["State"] == "21") $isCall = true;
	}

	if($isCall)
	{
		$queryUrl = "https://nfr.servit.by/infinity/ajax/ajax_create_agent.php";
		$queryData = http_build_query($queryParams1 = array(
			'url' => 'http://192.168.80.188:10080',
			'ExtensionUser' => $ExtensionUser,
			'phone_id' => $phoneid,
			'entity_type' => $entity_type,
			'entity_id' => $entity_id,
			'phone' => $phone_number,
			'id_act' => $activity_id
		));

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_HEADER => 0,
		  CURLOPT_RETURNTRANSFER => 1,
		  CURLOPT_URL => $queryUrl.'?'.$queryData,
		));
		$result = curl_exec($curl);
		curl_close($curl);

		return "";
	}


		return "CheckStatusCall::Check(".$ExtensionUser.",".$entity_type.",".$entity_id.",".$activity_id.",".$phone_number.",".$phoneid.");";
    }
}

class CheckCallID {

    function Check($number)
	{
		$ExtensionUser = 0;
		$phoneid = 0;
		$queryUrl = "http://192.168.80.188:10080/data/getdata/";
		$queryData = http_build_query($queryParams = array(
		  'ProviderName' => 'Monitoring.Calls',
		));

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_HEADER => 0,
		  CURLOPT_RETURNTRANSFER => 1,
		 // CURLOPT_URL => $queryUrl,
		  CURLOPT_URL => $queryUrl.'?'.$queryData,
		));

		$result = curl_exec($curl);
		curl_close($curl);

		$result = json_decode($result, 1);

		foreach($result['result']['Data'] as $value)
		{
			if(substr($value["AbonentNumber"], -9, 9) == substr($number, -9, 9))
			{
				if(substr($value["Extension"], 0, 1) != "z")
				{
					$ExtensionUser = $value["Extension"];
					$phoneid = $value["ID"];
					break;
				}
			}
		}

		if($ExtensionUser)
		{

			$number = substr($number, -9, 9);

			$queryUrl = "https://nfr.servit.by/rest/691/ycweoxpyumjbqn4x/telephony.externalcall.register";
			$queryData = http_build_query($queryParams = array(
				//'USER_PHONE_INNER' => $_REQUEST['ExtensionUser'],
			  'USER_PHONE_INNER' => $ExtensionUser,
			  'PHONE_NUMBER' => $number,
				//'PHONE_ID' => $_REQUEST['phoneid'],
			  'CRM_CREATE' => 1,
			  'TYPE' => 2
			));

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_HEADER => 0,
			  CURLOPT_RETURNTRANSFER => 1,
			  CURLOPT_URL => $queryUrl.'?'.$queryData,
			));

			$result = curl_exec($curl);
			curl_close($curl);

			$result = json_decode($result, 1);




			$entity_type = $result["result"]["CRM_ENTITY_TYPE"];
			$entity_id = $result["result"]['CRM_ENTITY_ID'];
			$activity_id = $result["result"]["CRM_ACTIVITY_ID"];
			//$phoneid = $_REQUEST['phoneid'];


			CAgent::AddAgent("CheckStatusCall::Check({$ExtensionUser},{$entity_type},{$entity_id},{$activity_id},{$number},{$phoneid});", "", "Y", 5);

			return "";

		}
		else return "CheckCallID::Check(".$number.");";

	}
}
*/

class UpdateActivity {

    function Update($ID_ACT)
	{
			CModule::IncludeModule('crm');
          //  global $USER;

            $now = ConvertTimeStamp(time() + CTimeZone::GetOffset(), 'FULL', SITE_ID);
            $nowStr = ConvertTimeStamp(MakeTimeStamp($now), 'FULL', SITE_ID);

            $arPhoneFields = array();

            //$is_incoming = $arPhoneData["Direction"] == "1";

           // $arPhoneFields["SUBJECT"] ="Входящий звонок";
            /*$arPhoneFields["DIRECTION"] = $is_incoming ? CCrmActivityDirection::Incoming : CCrmActivityDirection::Outgoing;

            $arPhoneFields["OWNER_ID"] = $_REQUEST["entity_id"];
            $arPhoneFields["OWNER_TYPE"] = $_REQUEST["entity_type"];
            $arPhoneFields["TYPE_ID"] = CCrmActivityType::Call;
            //$arPhoneFields["SUBJECT"] = "Исходящий звонок";
            $arPhoneFields["END_TIME"] = $nowStr;
            $arPhoneFields["START_TIME"] = $nowStr;
            $arPhoneFields["NOTIFY_TYPE"] = CCrmActivityNotifyType::None;
            $arPhoneFields["COMPLETED"] = "Y";
            $arPhoneFields["PRIORITY"] = CCrmActivityPriority::Medium;
           // $arPhoneFields["RESPONSIBLE_ID"] = intval($USER->GetID());
            //$arPhoneFields["DIRECTION"] = CCrmActivityDirection::Outgoing;
            $arPhoneFields["BINDINGS"][] = array("OWNER_TYPE_ID" => CCrmOwnerType::ResolveID($_REQUEST["entity_type"]), "OWNER_ID" => $_REQUEST["entity_id"]);

            $arPhoneFields["PROVIDER_ID"] = "VOXIMPLANT_CALL";
            $arPhoneFields["PROVIDER_PARAMS"] = array(
														"callid" => $arPhoneData["ID"],
														"direction"=>($is_incoming?"incoming":"outgoing"),
														"finished" => "0"
														,"inituserid" => $_REQUEST["ExtensionUser"]
														);
			*/

			$up = new CCrmActivity(false);
			$up->Update($ID_ACT, $arPhoneFields);

			$arComm[] = array(
                    "ID" => $ID_ACT,
                    "ENTITY_TYPE_ID" => CCrmOwnerType::ResolveID($_REQUEST["entity_type"]),
                    "TYPE" => "PHONE",
                    "ENTITY_ID" => $_REQUEST["entity_id"],
                    "VALUE" => $_REQUEST["phone"]
                );


                CCrmActivity::SaveCommunications($ID_ACT, $arComm);

			global $DB;

           $parent = $DB->query(sprintf(
                "UPDATE b_crm_act SET PROVIDER_ID = 'VOXIMPLANT_CALL' WHERE ID = ".$ID_ACT
            ));

			return "";
	}
}
/*CJSCore::RegisterExt('im_phone_call_view', array(
	'js' => '/local/js/im/phone_call_view.js',
	'css' => array('/bitrix/js/im/css/phone_call_view.css', '/bitrix/components/bitrix/crm.card.show/templates/.default/style.css'),
	'lang' => '/bitrix/modules/im/lang/'.LANGUAGE_ID.'/js_phone_call_view.php',
	'rel' => array('applayout', 'crm_form_loader')
));*/
AddEventHandler("tasks", "OnTaskUpdate", "TaskUpdateHandler");
function TaskUpdateHandler($ID)
{
	/*CModule::IncludeModule('im');
	$attach = new CIMMessageParamAttach(null, CIMMessageParamAttach::NORMAL);
$attach->AddMessage("Done");
		$arMessageFields = array(
			"TO_USER_ID" => $resp_id,
			"FROM_USER_ID" => 0,
			"NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
			"ATTACH" => Array($attach)
		);
		$mess = CIMNotify::Add($arMessageFields);
*/
	CModule::IncludeModule("tasks");

		//$res = CTasks::GetList(Array(), Array("ID" => $ID));
		
		//while ($arTask = $res->GetNext())
		//{
			$task = CTasks::GetById($ID)->Fetch();

			if (stristr($task["TITLE"], "Доставка для клиента"))
			{

				if ($task["REAL_STATUS"] == 5)
				{
					$title = "Обзвон клиента ".substr($task["TITLE"], 21);
					$new_task = new \Bitrix\Tasks\Item\Task();
					$new_task['TITLE'] = $title;
					$new_task['CREATED_BY'] = $task["RESPONSIBLE_ID"];
					$new_task['RESPONSIBLE_ID'] = $task["CREATED_BY"];
					$new_task['DESCRIPTION'] = $task["DESCRIPTION"];
					$new_task['ALLOW_CHANGE_DEADLINE'] = "Y";
					$result = $new_task->save();
				}
			}
		//}

}
