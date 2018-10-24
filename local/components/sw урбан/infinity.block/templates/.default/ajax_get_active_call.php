<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
$url = $_REQUEST['url'].'/call/getactivecalls/?IDUser='.$_REQUEST["IDUser"];
$result = file_get_contents($url);
$result = str_replace(array("\r\n", "\n", "\r", "\t"), "", $result);
$result = str_replace("\\", "/", $result);
$arResponse = json_decode($result, true);
if (isset($arResponse["result"]["Data"]) && is_array($arResponse["result"]["Data"]) && count($arResponse["result"]["Data"]))
{
    foreach($arResponse["result"]["Data"] as $arPhoneData)
    {
        if ($arPhoneData["ID"] == $_REQUEST["phone_id"])
        {

            echo json_encode(array("RESULT" => $arPhoneData));
            die();
        }
    }
}

echo json_encode(array("RESULT" => null));

/*define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/local/log.txt");
AddMessage2Log("file_get_contents = ".$result);
AddMessage2Log('$arResponse = '.print_r($arResponse, true));*/
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>