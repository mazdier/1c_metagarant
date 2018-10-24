<?php
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('crm');
 if(isset($_POST['FullName'])){
$oLead = new CCrmLead;
$LidID=$oLead->GetByID($_POST['IDPhone']); 
//$LidID=$oLead->GetList(array(),array('ID'=>3261))->Fetch(); 

//echo "<pre>";
//print_r($LidID);

$name = explode(" ", $_POST['FullName']);




if($LidID['NAME']!=$name[0]||$LidID['LAST_NAME']!=$name[1]){
	$arFile = CFile::MakeFileArray($_POST['Image']);
	$arFields = Array(
 //   "TITLE" => "EVENT CREATION ".$_POST['eventName'],
 //   "COMPANY_TITLE" => "EVENT CREATION ".$_POST['eventName'],
 "NAME" => $name[0],
 "LAST_NAME" => $name[1],
// "SECOND_NAME" => "ОтчествоКонтакта",
// "POST" => "ДолжностьКонтакта",
 //   "ADDRESS" => $_POST['address'],
// "COMMENTS" => "КомментарийКомментарийКомментарий",
//    "SOURCE_DESCRIPTION" => $_POST['description'],
// "STATUS_DESCRIPTION" => "",
//"OPPORTUNITY" => 123456,
//"CURRENCY_ID" => "USD",
// "PRODUCT_ID" => "PRODUCT_1",
//"SOURCE_ID" => "SELF",
 //   "STATUS_ID" => "NEW",
 //   "ASSIGNED_BY_ID" => $_POST['owner'],
   "UF_CRM_1501062270" => $arFile,
 );
$oLead->Update($_POST['IDPhone'],$arFields);	
	
}

 }

?>