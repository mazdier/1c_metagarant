<?php 
##############################################
# Service IT Site Manager                    #
# Copyright (c) 2016-2018 Service IT         #
# https://nfr.servit.by                      #
# mailto:admin@servit.by                     #
##############################################

/* Добавление контрагента из битрикса в буферную базу */ 

$requestData=$_POST['data'];
//\Bitrix\Main\Diag\Debug::writeToFile($requestData, "var_name", "log/_log2_.php");
//$requestData='{"id":6097,"save":"update"}';
$requestData= json_decode($requestData,true);

file_put_contents("log.txt", json_encode($requestData)."\n", FILE_APPEND);

include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

CModule::IncludeModule('crm');
$contragentFullInfo=["bx_flag"=>1,"1c_flag"=>0,"del_flag"=>0];
		/*$contragentFullInfo["bx_flag"] = 1;	
		$contragentFullInfo["1c_flag"] = 0;
		$contragentFullInfo["del_flag"] = 0;*/
switch ($requestData["save"]) {
    case "add":
        $contragentFullInfo = getContragentFullInfo($requestData["id"]);
		$id = intval($DB->Query("SELECT max(id) from 1c_contragent")->fetch()["max(id)"] + 1);
        $contragentFullInfo["manager_id"] = getAssignedManagerForQuery($requestData["id"]);
		
		$GLOBALS['DB']->Insert("1c_contragent", $contragentFullInfo, $err_mess.__LINE__, true, $id);
        break;
    case "update":
		$contragentFullInfo = getContragentFullInfo($requestData["id"]);
		//print_r($contragentFullInfo);
		//file_put_contents("/home/bitrix/www/log/log.txt", "42\n", FILE_APPEND);
		$GLOBALS['DB']->Update("1c_contragent", $contragentFullInfo, "WHERE gen_id=".$contragentFullInfo["gen_id"], $err_mess.__LINE__);
        break;
    case "delete":
        $GLOBALS['DB']->Query("UPDATE 1c_contragent SET del_flag = 1 WHERE gen_id = '".$requestData["id"]."'");
        break;
}

function getContragentFullInfo($id_company){

	$company = CCrmCompany::GetList(Array('DATE_CREATE' => 'DESC'), $arFilter = Array("ID"=>$id_company, 'CHECK_PERMISSIONS' => 'N'))->GetNext();
	$rs = (new \Bitrix\Crm\EntityRequisite())->getList(["filter" => ["ENTITY_ID" => $id_company, "ENTITY_TYPE_ID" => CCrmOwnerType::Company,'PRESET_ID' => 1]])->fetch();
	$bankReq = (new \Bitrix\Crm\EntityBankDetail)->getList(['filter' => ['ENTITY_ID' => $rs['ID'],'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite]])->fetch();
	$address = Bitrix\Crm\EntityRequisite::getAddresses($rs["ID"]);
	$MF = CCrmFieldMulti::GetList(array(),array('ENTITY_ID' => 'COMPANY', 'ELEMENT_ID' => $id_company)); 
	
	$contragentFullInfo["gen_id"] = "'".$company["ID"]."'";
	$contragentFullInfo["name"] = "'".$company["TITLE"]."'";
	$contragentFullInfo["dop_info"] = "'".$company["COMMENTS"]."'";
	//$contragentFullInfo["code"] = "'".$company["UF_CRM_CODE"]."'";
	$contragentFullInfo["code_fil"] = "'".$company["UF_CRM_CODE_FILL"]."'";
	$contragentFullInfo["zavis_face"] = "'".$company["UF_CRM_IDEPENDENT"]."'";
	$contragentFullInfo["rezident_off_area"] = "'".$company["UF_CRM_OFFSHORE"]."'";
	$contragentFullInfo["deal_po"] = "'".$company["UF_CRM_DEAL"]."'";
	$contragentFullInfo["org_big"] = "'".$company["UF_CRM_ORG_BIG"]."'";
	$contragentFullInfo["order"] = "'".$company["UF_CRM_ORDER"]."'";
	$contragentFullInfo["action_on"] = "'".$company["UF_CRM_ACTION_ON"]."'";
	$contragentFullInfo["lic"] = "'".$company["UF_CRM_LIC"]."'";
	$contragentFullInfo["full_name"] = "'".$rs["RQ_COMPANY_FULL_NAME"]."'";
	$contragentFullInfo["inn"] = "'".$rs["RQ_INN"]."'";
	$contragentFullInfo["kpp"] = "'".$rs["RQ_KPP"]."'";
	$contragentFullInfo["okpo"] = "'".$rs["RQ_OKPO"]."'";
	$contragentFullInfo["bin"] = "'".$rs["RQ_OGRN"]."'";
	$contragentFullInfo["invoice_k"] = "'".$bankReq["RQ_COR_ACC_NUM"]."'";
	//$contragentFullInfo["rs"] = "'".$bankReq["RQ_ACC_NUM"]."'";
	$contragentFullInfo["category_id"] = "'".getBufferDBID($company["UF_CRM_CONTR_TYPE_ID"], "1c_category_contragent", "category")."'";
	if($contragentFullInfo["category_id"] === "''") $contragentFullInfo["category_id"] = 'NULL';
	$contragentFullInfo["country_reg_id"] = getBufferDBID($company["UF_CRM_COUNTRY_ID"], "1c_country", "concat(`COUNTRY_NAME`, \" \", `CITY_NAME`)");
	if($contragentFullInfo["country_reg_id"] === "''" || empty($contragentFullInfo["country_reg_id"])) $contragentFullInfo["country_reg_id"] = 'NULL';
	$contragentFullInfo["contragent_category_id"] = getBufferDBID($company["UF_CRM_CONTR_CAT_ID"], "1c_path", "name");
	if($contragentFullInfo["contragent_category_id"] === "''") $contragentFullInfo["contragent_category_id"] = 'NULL';
	$contragentFullInfo["ur_address"] = "'".$address[6]["ADDRESS_1"] . " " . $address[6]["ADDRESS_2"]."'";
	while($row = $MF->fetch()){
		if($row["COMPLEX_ID"] == "EMAIL_WORK") $contragentFullInfo["mail_address"] = "'".$row["VALUE"]."'";
		if($row["COMPLEX_ID"] == "PHONE_WORK") $contragentFullInfo["phone"] = "'".$row["VALUE"]."'";
	}

	return $contragentFullInfo;
}

function getAssignedManagerForQuery($id_company){
    $companyAssignedByID = CCrmCompany::GetByID($id_company)["ASSIGNED_BY_ID"];
    $id = $GLOBALS['DB']->Query("SELECT * FROM 1c_manager WHERE gen_id = '$companyAssignedByID'")->fetch()["id"];
    return isset($id) ? "'".$id."'" : 'NULL';
}

function getBufferDBID($id, $table, $col){
		$obEnum = new CUserFieldEnum();
		$list = $obEnum->getList();
		foreach($list->arResult as $value){
			if($value["ID"]==$id){
				$id_name = $value["VALUE"];
			}  
        }
		$bufferDBID = (($GLOBALS['DB']->Query("SELECT id from $table where $col = '$id_name'"))->fetch())["id"];
		return $bufferDBID = (($GLOBALS['DB']->Query("SELECT id from $table where $col = '$id_name'"))->fetch())["id"];
}

?>