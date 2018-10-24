<?php
file_put_contents("loga.txt", "\n 1 начало\n",FILE_APPEND);
include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER['DOCUMENT_ROOT']."/bitrix/header.php");
global $USER;
$x=json_encode(['id'=>$_POST['id'],'save'=>$_POST['save'],'start'=>date("m.d.y H:i:s").','.microtime(true),'user_id'=>$USER->GetID(),'name'=>5]);
    $curlstring="curl -d 'data=".$x."' 'http://192.168.200.50/include/1c_ajax_main.php' > /dev/null 2>&1 & ";
exec($curlstring);


//  require($_SERVER['DOCUMENT_ROOT']."/bitrix/footer.php");
?>