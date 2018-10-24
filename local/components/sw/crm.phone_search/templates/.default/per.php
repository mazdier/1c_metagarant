<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?

$url=$_REQUEST['infinity'].'/call/getactivecalls/?IDUser='.$_REQUEST["IDUser"];
http://192.168.0.182:10080/call/getactivecalls/?IDUser=5001790109
$somepage = file_get_contents($url);
echo $somepage;
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>