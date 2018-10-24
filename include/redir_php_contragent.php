<?php
require($_SERVER['DOCUMENT_ROOT']."/bitrix/header.php");
global $USER; 
$user_id = $USER->GetID();
$curlString = "curl -d \"title_company=".$_POST["title_company"]."&url=".urlencode($_POST["url"])."&user_id=".$user_id."&id=".$_POST['id']."&type=".implode(",",$_POST["arrID"])."&map_entity=".$_POST['map_entity']."\" -X POST 'http://192.168.200.50/include/1c_test_contragent_out.php' > /dev/null 2>&1 &";
exec($curlString);
file_put_contents("log.txt", "\n====================================================================================================\n", FILE_APPEND );
file_put_contents("log.txt", $curlString, FILE_APPEND);

require($_SERVER['DOCUMENT_ROOT']."/bitrix/footer.php");
?>

