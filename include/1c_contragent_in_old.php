<?php 
//include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
##############################################
# Service IT Site Manager                    #
# Copyright (c) 2016-2017 Service IT         #
# https://nfr.servit.by                      #
# mailto:admin@servit.by                     #
##############################################

/* Добавление контрагента из буферной базы в Битрикс */
file_put_contents("loga.txt", "\n 1 начало\n",FILE_APPEND);
$fd = fopen("loga.txt", 'a+') or die("не удалось создать файл");
$str = "Привет мир";
fwrite($fd, $str);
fclose($fd);
$arFields = array(
        "MESSAGE_TYPE" => "S", # P - private chat, G - group chat, S - notification
        "TO_USER_ID" => 1,
        "FROM_USER_ID" => 1,
        "MESSAGE" => 'OPA',
            "AUTHOR_ID" => 1,

        "EMAIL_TEMPLATE" => "some",
        "NOTIFY_TYPE" => 4,  # 1 - confirm, 2 - notify single from, 4 - notify single
            //"NOTIFY_MODULE" => "main", # module id sender (ex: xmpp, main, etc)
            //"NOTIFY_EVENT" => "IM_GROUP_INVITE", # module event id for search (ex, IM_GROUP_INVITE)
        // "NOTIFY_TITLE" => "title to send email", # notify title to send email
        );
        
    
        //CModule::('im');
        //CIMMessenger::Add($arFields);
global $DB;
CModule::IncludeModule('crm');
$contactsSQLResult = $DB->Query("
SELECT * FROM 1c_manager
WHERE bx_flag = 0
");
$contactsID = array();
while($contact = $contactsSQLResult->fetch()){
    global $DB;
    $contactFields["NAME"] = $contact["name"];
    $contactFields["FM"] = array(
        'EMAIL' => array(
            'n0' => array('VALUE' => $contact["email"], 'VALUE_TYPE' => 'WORK')
        ),
        'PHONE' => array(
            'n0' => array('VALUE' => $contact["number"], 'VALUE_TYPE' => 'WORK')
        )
    );
    $contactFields["POST"] = "Руководитель";
    $contactFields["OPENED"] = "N";
    $contactFields["EXPORT"] = "Y";
    $contactCRM = new CCrmContact();
    $contactID = $contactCRM->add($contactFields);
    $c_contactID = $contact["id"];
    $DB->Query("
    UPDATE 1c_manager
    SET bx_flag = 1, gen_id = $contactID
    WHERE id = $c_contactID
    ");
    $contactsID[$contact["id"]] = $contactID;
}

$SQLResult = $DB->Query("SELECT * FROM 1c_contragent WHERE del_flag = 1");
while($contragent = $SQLResult->fetch()){
    $CCrmCompany = new CCrmCompany();
    $CCrmCompany->delete((int) $contragent["gen_id"]);
}

$contragentsSQLResult = $DB->Query("
 SELECT 1c_contragent.*, 
 1c_manager.number AS 1c_manager_number,
 1c_manager.email AS 1c_manager_email,
 1c_manager.name AS 1c_manager_name,
 1c_manager.name AS 1c_manager_name,
 1c_manager.name_rad AS 1c_manager_name_rad,
 1c_contract.number AS 1c_contract_number, 
 1c_contract.name AS 1c_contract_name,
 1c_contract.foreign_exchange AS 1c_contract_foreign_exchange,
 1c_contract.currency_id AS 1c_contract_currency_id,
 1c_contract.type_contract AS 1c_contract_type_contract,
 1c_contract.brief_characteristics AS 1c_contract_brief_characteristics,
 1c_contract.id AS 1c_contract_id,
 1c_contract.gen_id AS 1c_contract_gen_id,
 1c_contract.bx_flag AS 1c_contract_bx_flag,
 1c_contract.1c_flag AS 1c_contract_1c_flag,
 1c_country.id AS 1c_country_id,
 1c_country.COUNTRY_ID AS 1c_country_COUNTRY_ID,
 1c_country.REGION_ID AS 1c_country_REGION_ID,
 1c_country.CITY_ID AS 1c_country_CITY_ID,
 1c_country.COUNTRY_NAME_ORIG AS 1c_country_COUNTRY_NAME_ORIG,
 1c_country.COUNTRY_SHORT_NAME AS 1c_country_COUNTRY_SHORT_NAME,
 1c_country.REGION_NAME_ORIG AS 1c_country_REGION_NAME_ORIG,
 1c_country.CITY_NAME_ORIG AS 1c_country_CITY_NAME_ORIG,
 1c_country.REGION_SHORT_NAME AS 1c_country_REGION_SHORT_NAME,
 1c_country.CITY_SHORT_NAME AS 1c_country_CITY_SHORT_NAME ,
 1c_country.COUNTRY_NAME AS 1c_country_COUNTRY_NAME,
 1c_country.REGION_NAME AS 1c_country_REGION_NAME,
 1c_country.CITY_NAME AS 1c_country_CITY_NAME,
 1c_category_contragent.id AS 1c_category_contragent_id,
 1c_category_contragent.category AS 1c_category_contragent_category ,
 1c_category_contragent.bx_flag AS 1c_category_contragent_bx_flag ,
 1c_category_contragent.1c_flag AS 1c_category_contragent_1c_flag,
 1с_currency.currency AS  1с_currency_currency
 FROM 1c_contragent
 LEFT JOIN 1c_manager ON 1c_contragent.boss_id = 1c_manager.id 
 LEFT JOIN 1c_contract ON 1c_contragent.main_contract_id = 1c_contract.id
 LEFT JOIN 1c_country ON 1c_contragent.country_reg_id = 1c_country.id
 LEFT JOIN 1c_category_contragent ON 1c_contragent.category_id = 1c_category_contragent.id
 LEFT JOIN 1с_currency ON 1c_contract.currency_id = 1с_currency.id
 WHERE (1c_contragent.bx_flag = 0 OR 1c_contragent.bx_flag IS NULL) AND (1c_contragent.del_flag = 0 OR 1c_contragent.del_flag IS NULL)
 ");
while($contragent = $contragentsSQLResult->fetch()){
    global $DB;
    //print_r($contragent);

    if(!isset($contragent["gen_id"]) || $contragent["gen_id"] == 0){
        $companyFields["TITLE"] = $contragent["name"];
        $contragentContactID_1C = $contragent["boss_id"];
        if(!empty($contragentContactID_1C)){
        $contragentContactSQL = $DB->Query("
        SELECT * FROM 1c_manager
        WHERE id = $contragentContactID_1C;
        ");
        $contragentContactID = $contragentContactSQL->fetch();
        $contragentContact = $contragentContactID;} 
        else {
            $contragentContact = null;
        }
       // print_r("+++++++++++++++++++++++++++".$contragentContact["gen_id"]);
        $companyFields["CONTACT_ID"] = array($contragentContact["gen_id"]);
        $companyFields["CURRENCY_ID"] = $contragent["1с_currency_currency"];
        $contactCRM = new CCrmContact(false);
        $contact = $contactCRM->GetByID($contactsID[$contragent["boss_id"]]);
        $companyFields["INDUSTRY"] = "OTHER";
        $companyFields["COMPANY_TYPE"] = "OTHER";
        $companyFields["COMMENTS"] = $contragent["dop_info"];
        $companyFields["FM"] = array(
                'EMAIL' => array(
                    'n1' => array('VALUE' => $contragent["mail_address"], 'VALUE_TYPE' => 'WORK')
                ),
                'PHONE' => array(
                    'n1' => array('VALUE' => $contragent["phone"], 'VALUE_TYPE' => 'WORK')
                )
        );
        $companyFields["UF_CRM_IDEPENDENT"] = $contragent["zavis_face"]; 
        $companyFields["UF_CRM_OFFSHORE"] = $contragent["rezident_off_area"]; 
        $companyFields["UF_CRM_DEAL"] = $contragent["deal_po"]; 
        $companyFields["UF_CRM_ORG_BIG"] = $contragent["org_big"]; 
        $companyFields["UF_CRM_CODE_FILL"] = $contragent["code_fil"]; 
        $companyFields["UF_CRM_CODE"] = $contragent["code"]; 
        $companyFields["UF_CRM_ORDER"] = $contragent["order"];
        $companyFields["UF_CRM_ACTION_ON"] = $contragent["action_on"]; 	
        $companyFields["UF_CRM_LIC"] = $contragent["lic"]; 	
        $CCrmCompany = new CCrmCompany();
        z($companyFields);
        $companyID = $CCrmCompany->Add($companyFields);
        $arFilds=[
              "ENTITY_ID" =>$companyID ,
              "ENTITY_TYPE_ID" => CCrmOwnerType::Company,
              "NAME"=>"Организация",
              "PRESET_ID" => 1,
              "RQ_INN"=>$contragent["inn"],
              "RQ_KPP"=>$contragent["kpp"],
              "RQ_OKPO"=>$contragent["okpo"],
              "RQ_OGRN"=>$contragent["bin"],
              "RQ_COMPANY_FULL_NAME"=>$contragent["full_name"]
              ];
        $requisite = new \Bitrix\Crm\EntityRequisite();
        $resDB = $requisite->add($arFilds);
      //  var_dump($arFilds);
        $arFild=[
        "ENTITY_ID" =>$resDB->getId() ,
              "ENTITY_TYPE_ID" => 8,
              "NAME" => "Банковские реквизиты",
              "RQ_ACC_NUM" => $contragent["rs"],
              "RQ_COR_ACC_NUM" => $contragent["invoice_k"]
              ];
        (new \Bitrix\Crm\EntityBankDetail)->add($arFild);
        \Bitrix\Crm\AddressTable::upsert(array('TYPE_ID' => 6, 'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite, 
        'ENTITY_ID' => $resDB->getId(),
        "ADDRESS_1" => $contragent["ur_address"]));
        $contragentID = $contragent["id"];
        $DB->Query("
        UPDATE 1c_contragent
        SET bx_flag = 1, gen_id = $companyID
        WHERE id = $contragentID;
        "); 
    } else {

        $companyFields["TITLE"] = $contragent["name"];
        $contragentContactID_1C = $contragent["boss_id"];
        if(!empty($contragentContactID_1C)){
        $contragentContactSQL = $DB->Query("
        SELECT * FROM 1c_manager
        WHERE id = $contragentContactID_1C;
        ");
        $contragentContactID = $contragentContactSQL->fetch();
        $contragentContact = $contragentContactID;} 
        else {
            $contragentContact = null;
        }
        //print_r("+++++++++++++++++++++++++++".$contragentContact["gen_id"]);
        $companyFields["CONTACT_ID"] = array($contragentContact["gen_id"]);
        $companyFields["CURRENCY_ID"] = $contragent["1с_currency_currency"];
        $contactCRM = new CCrmContact(false);
        $contact = $contactCRM->GetByID($contactsID[$contragent["boss_id"]]);
        $companyFields["INDUSTRY"] = "OTHER";
        $companyFields["COMPANY_TYPE"] = "OTHER";
        $companyFields["COMMENTS"] = $contragent["dop_info"];
        
        $dbResult = CCrmFieldMulti::GetList(array(),
            array(
                'ENTITY_ID' => 'COMPANY',
                'ELEMENT_ID' => $companyFields["gen_id"],
            )
        );
        $CCrmFieldMulti = new CCrmFieldMulti();
        while($arLead = $dbResult->GetNext()){
        $arCompanyID = $arLead;
        $CCrmFieldMulti->delete($arCompanyID["ID"]);
        }
        $companyFields["FM"] = array(
            'EMAIL' => array(
                'n1' => array('VALUE' => $contragent["mail_address"], 'VALUE_TYPE' => 'WORK')
            ),
            'PHONE' => array(
                'n1' => array('VALUE' => $contragent["phone"], 'VALUE_TYPE' => 'WORK')
            )
        );

        $companyFields["UF_CRM_IDEPENDENT"] = $contragent["zavis_face"]; 
        $companyFields["UF_CRM_OFFSHORE"] = $contragent["rezident_off_area"]; 
        $companyFields["UF_CRM_DEAL"] = $contragent["deal_po"]; 
        $companyFields["UF_CRM_ORG_BIG"] = $contragent["org_big"]; 
        $companyFields["UF_CRM_CODE_FILL"] = $contragent["code_fil"]; 
        $companyFields["UF_CRM_CODE"] = $contragent["code"]; 
        $companyFields["UF_CRM_ORDER"] = $contragent["order"];
        $companyFields["UF_CRM_ACTION_ON"] = $contragent["action_on"]; 	
        $companyFields["UF_CRM_LIC"] = $contragent["lic"]; 	
        $CCrmCompany = new CCrmCompany();
        $companyID = $CCrmCompany->Update($contragent["gen_id"], $companyFields);

        $rs = (new \Bitrix\Crm\EntityRequisite())->getList(["filter" => ["ENTITY_ID" => $contragent["gen_id"], "ENTITY_TYPE_ID" => CCrmOwnerType::Company,'PRESET_ID' => 1]])->fetch();
        $bankReq = (new \Bitrix\Crm\EntityBankDetail)->getList(['filter' => ['ENTITY_ID' => $rs['ID'],'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite]])->fetch();

      //  var_dump($rs);

        $arFilds=[
            "RQ_INN"=>$contragent["inn"],
            "RQ_KPP"=>$contragent["kpp"],
            "RQ_OKPO"=>$contragent["okpo"],
            "RQ_OGRN"=>$contragent["bin"],
            "RQ_COMPANY_FULL_NAME"=>$contragent["full_name"]
            ];

        $requisite = new \Bitrix\Crm\EntityRequisite();
        $requisite->update((int) $rs['ID'], $arFilds);

        $arFild=[
                  "ENTITY_TYPE_ID" => 8,
                  "RQ_ACC_NUM" => $contragent["rs"],
                  "RQ_COR_ACC_NUM" => $contragent["invoice_k"]
                  ];
            (new \Bitrix\Crm\EntityBankDetail)->update((int) $bankReq["ID"], $arFild);

            \Bitrix\Crm\AddressTable::upsert(array('TYPE_ID' => 6, 'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite, 
            'ENTITY_ID' => (int) $rs["ID"],
            "ADDRESS_1" => $contragent["ur_address"]));
			
		$contragentID = $contragent["id"];
			
		$DB->Query("
        UPDATE 1c_contragent
        SET bx_flag = 1
        WHERE id = $contragentID;
        "); 

    }
}

?>