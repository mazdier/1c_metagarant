<?php
//require_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
##############################################
# Service IT Site Manager                    #
# Copyright (c) 2016-2017 Service IT         #
# https://nfr.servit.by                      #
# mailto:admin@servit.by                     #
##############################################

global $DB;
CModule::IncludeModule('crm');
$contactsSQLResult = $DB->Query("
SELECT * FROM 1c_employee
WHERE (bx_flag = 0 OR bx_flag = NULL) AND del_flag = 0
");


while($contact = $contactsSQLResult->fetch()){
	$contactFields["NAME"] = $contact["full_name"];
    $contactFields["POST"] = $contact["position"];
    $contactFields["OPENED"] = "N";
    $contactFields["EXPORT"] = "Y";
	$contactFields["COMMENTS"] = "Сотрудник 1С";
	$contactFields["SOURCE_DESCRIPTION"] = "Перенесён из 1С";
	
	$contactFields["UF_CRM_ACCESS"] = $contact["access_code"];
	$contactFields["UF_CRM_SALARY"] = $contact["salary"];
	$contactFields["UF_CRM_RATE"] = $contact["rate_pieceworker"];
	$contactFields["UF_CRM_PERIOD"] = $contact["closed_period"];
	$contactFields["UF_CRM_SECRET"] = $contact["secretly"];
	$contactFields["UF_CRM_FROM_1C"] = true;
	
    $contactCRM = new CCrmContact();
    $contactID = $contactCRM->add($contactFields);
    $c_contactID = $contact["id"];
    $DB->Query("
    UPDATE 1c_employee
    SET bx_flag = 1, gen_id = $contactID
    WHERE id = $c_contactID
    ");
	
        $arFilds=[
              "ENTITY_ID" =>$contactID ,
              "ENTITY_TYPE_ID" => CCrmOwnerType::Contact,
              "NAME"=>"Сотрудник 1С",
              "PRESET_ID" => 3, // физ. лицо
              "RQ_IDENT_DOC"=>"Паспорт",
              "RQ_IDENT_DOC_SER"=>$contact["passport_series"],
              "RQ_IDENT_DOC_NUM"=>$contact["passport_id"],
              "RQ_IDENT_DOC_DATE"=>$contact["passport_date"],
              "RQ_IDENT_DOC_ISSUED_BY"=>$contact["issuing_authority"],
			  "RQ_IDENT_DOC_DEP_CODE"=>$contact["subdivision"]
              ];
        $requisite = new \Bitrix\Crm\EntityRequisite();
        $resDB = $requisite->add($arFilds);
}

 ?>