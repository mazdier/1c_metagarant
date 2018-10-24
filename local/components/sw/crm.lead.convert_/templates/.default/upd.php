<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?CModule::IncludeModule('crm');

$arFields=array(  'STATUS_ID' => 'JUNK',);

$CCrmLead = new CCrmLead();

echo $CCrmLead->Update($_REQUEST["id"], $arFields);
?>