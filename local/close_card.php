<?
//require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/infinity/setting/SettingsList.php");
/*
$arFields = array(
							"MESSAGE_TYPE" => "S", # P - private chat, G - group chat, S - notification
							"TO_USER_ID" => 691,
							//"FROM_USER_ID" => 1,
							"MESSAGE" => "Запуск",
							//"AUTHOR_ID" => 1,
							"EMAIL_TEMPLATE" => "some",

							"NOTIFY_TYPE" => 4,  # 1 - confirm, 2 - notify single from, 4 - notify single
							//"NOTIFY_MODULE" => "main", # module id sender (ex: xmpp, main, etc)
							//"NOTIFY_EVENT" => "IM_GROUP_INVITE", # module event id for search (ex, IM_GROUP_INVITE)
							//"NOTIFY_TITLE" => "title to send email", # notify title to send email
							);
							
							CModule::IncludeModule('im');
							CIMMessenger::Add($arFields);

*/
$AddressInfinity = SettingsList::getSetting("AddressInfinity");
$WebHook = SettingsList::getSetting("WebHook");

//echo "<pre>";
$count = 0;
$masExt = array();

while($count<5)
{
	$count++;
	$ExtensionUsers = array();
	$phoneid = 0;
	$queryUrl = $AddressInfinity."/data/getdata/";
	//$queryUrl = "http:// :10080/data/getdata/";
	$queryData = http_build_query($queryParams = array(
	  'ProviderName' => 'Monitoring.Calls',
	));

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_SSL_VERIFYPEER => false,
	  CURLOPT_SSL_VERIFYHOST => false,
	  CURLOPT_HEADER => 0,
	  CURLOPT_RETURNTRANSFER => 1,
	 // CURLOPT_URL => $queryUrl,
	  CURLOPT_URL => $queryUrl.'?'.$queryData,
	));

	$result = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($result, 1);
	
	//print_r($result);

	foreach($result['result']['Data'] as $value)
	{
		if($value["CallState"] == 99 || $value["CallState"] == 100)
		{
			$ExtensionUsers[] = $value["Extension"];
		}				
	}
	
	foreach($ExtensionUsers as $Extension)
	{
		if(!in_array($Extension, $masExt))
		{
			$masExt[] = $Extension;
			$userID = 0;

			$AllUsers = CUser::GetList();
			while($AUser = $AllUsers->Fetch())
			{
				$rsUser = CUser::GetByID($AUser['ID']);
				$arUser = $rsUser->Fetch();
				if($arUser['UF_PHONE_INNER'] == $Extension)
				{
					$userID = $AUser['ID'];
					break;
				}			

			}
			//echo $userID;

			//$list = CCrmActivity::GetList(array('ID' => 'asc'), array('TYPE_ID' => 2));
			$list = CCrmActivity::GetList(array('ID' => 'desc'), array('CHECK_PERMISSIONS' => 'N', 'TYPE_ID' => 2, 'RESPONSIBLE_ID' => $userID), false, array("MAX"=>"ID"), array("ORIGIN_ID"));
			
			$act = $list->fetch();
			//print_r($act);
/*
				$arFields = array(
							"MESSAGE_TYPE" => "S", # P - private chat, G - group chat, S - notification
							"TO_USER_ID" => 691,
							//"FROM_USER_ID" => 1,
							"MESSAGE" => "act: ".$act['ID']."\n Extension: ".$Extension,
							//"AUTHOR_ID" => 1,
							"EMAIL_TEMPLATE" => "some",

							"NOTIFY_TYPE" => 4,  # 1 - confirm, 2 - notify single from, 4 - notify single
							//"NOTIFY_MODULE" => "main", # module id sender (ex: xmpp, main, etc)
							//"NOTIFY_EVENT" => "IM_GROUP_INVITE", # module event id for search (ex, IM_GROUP_INVITE)
							//"NOTIFY_TITLE" => "title to send email", # notify title to send email
							);
							
							CModule::IncludeModule('im');
							CIMMessenger::Add($arFields);

*/			
			$CALL_ID = substr($act['ORIGIN_ID'], 3);
			$RESPONSIBLE_ID = $act['RESPONSIBLE_ID'];
			
			$queryUrl = $WebHook."/telephony.externalcall.hide";
			$queryData = http_build_query($queryParams = array(
				//'USER_PHONE_INNER' => $_REQUEST['ExtensionUser'],
			  'USER_ID' => $RESPONSIBLE_ID,
			  'CALL_ID' => $CALL_ID
			));

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_SSL_VERIFYPEER => false,
			  CURLOPT_SSL_VERIFYHOST => false,
			  CURLOPT_HEADER => 0,
			  CURLOPT_RETURNTRANSFER => 1,
			  CURLOPT_URL => $queryUrl.'?'.$queryData,
			));

			$result = curl_exec($curl);
			curl_close($curl);
		}
		
	}
	//print_r($result);
	sleep(1);
}
echo "OK";
?>