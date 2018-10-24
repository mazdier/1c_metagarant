<? include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");?>
<?php 
global $DB;

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
 WHERE 1c_contragent.bx_flag = 0
 ");
while($contragent = $contragentsSQLResult->fetch()){
    global $DB;
    print_r($contragent);
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
    print_r("+++++++++++++++++++++++++++".$contragentContact["gen_id"]);
    $companyFields["CONTACT_ID"] = array($contragentContact["gen_id"]);
    $companyFields["CURRENCY_ID"] = $contragent["1с_currency_currency"];
    $contactCRM = new CCrmContact();
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
	var_dump($arFilds);
	$arFild=[
    "ENTITY_ID" =>$resDB->getId() ,
          "ENTITY_TYPE_ID" => 8,
		  "NAME" => "Банковские реквизиты",
		  "RQ_ACC_NUM" => $contragent["rs"],
		  "RQ_COR_ACC_NUM" => $contragent["invoice_k"]
          ];
	var_dump((new \Bitrix\Crm\EntityBankDetail)->add($arFild));
	\Bitrix\Crm\AddressTable::upsert(array('TYPE_ID' => 6, 'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite, 'ENTITY_ID' => $resDB->getId(), "ADDRESS_1" => $contragent["ur_address"]));
    $contragentID = $contragent["id"];
    $DB->Query("
    UPDATE 1c_contragent
    SET bx_flag = 1, gen_id = $companyID
    WHERE id = $contragentID;
    ");
}

/***Company
 * array(63) {
 * ["ID"]=> string(1) "1"
 * ["COMPANY_TYPE"]=> string(8) "CUSTOMER"
 * ["TITLE"]=> string(17) "ЗАО "МПЗК""
 * ["LOGO"]=> string(3) "256"
 * ["LEAD_ID"]=> NULL
 * ["HAS_PHONE"]=> string(1) "Y"
 * ["HAS_EMAIL"]=> string(1) "Y"
 * ["ASSIGNED_BY_ID"]=> string(1) "1"
 * ["ASSIGNED_BY_LOGIN"]=> string(5) "admin"
 * ["ASSIGNED_BY_NAME"]=> string(12) "Сервис"
 * ["ASSIGNED_BY_LAST_NAME"]=> string(9) "Ай-Ти"
 * ["ASSIGNED_BY_SECOND_NAME"]=> string(0) ""
 * ["ASSIGNED_BY_WORK_POSITION"]=> string(26) "Администратор"
 * ["ASSIGNED_BY_PERSONAL_PHOTO"]=> string(4) "3269"
 * ["CREATED_BY_ID"]=> string(1) "1"
 * ["CREATED_BY_LOGIN"]=> string(5) "admin"
 * ["CREATED_BY_NAME"]=> string(12) "Сервис"
 * ["CREATED_BY_LAST_NAME"]=> string(9) "Ай-Ти"
 * ["CREATED_BY_SECOND_NAME"]=> string(0) ""
 * ["MODIFY_BY_ID"]=> string(1) "1"
 * ["MODIFY_BY_LOGIN"]=> string(5) "admin"
 * ["MODIFY_BY_NAME"]=> string(12) "Сервис"
 * ["MODIFY_BY_LAST_NAME"]=> string(9) "Ай-Ти"
 * ["MODIFY_BY_SECOND_NAME"]=> string(0) ""
 * ["BANKING_DETAILS"]=> string(0) ""
 * ["INDUSTRY"]=> string(13) "MANUFACTURING"
 * ["REVENUE"]=> string(8) "10000000"
 * ["CURRENCY_ID"]=> string(3) "RUB"
 * ["EMPLOYEES"]=> string(11) "EMPLOYEES_3"
 * ["COMMENTS"]=> string(0) ""
 * ["DATE_CREATE"]=> string(19) "01.02.2017 09:36:31"
 * ["DATE_MODIFY"]=> string(19) "24.04.2017 21:15:02"
 * ["OPENED"]=> string(1) "N"
 * ["IS_MY_COMPANY"]=> string(1) "N"
 * ["WEBFORM_ID"]=> NULL
 * ["ORIGINATOR_ID"]=> NULL
 * ["ORIGIN_ID"]=> NULL
 * ["ORIGIN_VERSION"]=> NULL
 * ["ADDRESS"]=> NULL
 * ["ADDRESS_2"]=> NULL
 * ["ADDRESS_CITY"]=> NULL
 * ["ADDRESS_POSTAL_CODE"]=> NULL
 * ["ADDRESS_REGION"]=> NULL
 * ["ADDRESS_PROVINCE"]=> NULL
 * ["ADDRESS_COUNTRY"]=> NULL
 * ["ADDRESS_COUNTRY_CODE"]=> NULL
 * ["ADDRESS_LEGAL"]=> NULL
 * ["REG_ADDRESS"]=> NULL
 * ["REG_ADDRESS_2"]=> NULL
 * ["REG_ADDRESS_CITY"]=> NULL
 * ["REG_ADDRESS_POSTAL_CODE"]=> NULL
 * ["REG_ADDRESS_REGION"]=> NULL
 * ["REG_ADDRESS_PROVINCE"]=> NULL
 * ["REG_ADDRESS_COUNTRY"]=> NULL
 * ["REG_ADDRESS_COUNTRY_CODE"]=> NULL
 * ["ASSIGNED_BY"]=> string(1) "1"
 * ["CREATED_BY"]=> string(1) "1"
 * ["MODIFY_BY"]=> string(1) "1"
 * ["UTM_SOURCE"]=> NULL
 * ["UTM_MEDIUM"]=> NULL
 * ["UTM_CAMPAIGN"]=> NULL
 * ["UTM_CONTENT"]=> NULL
 * ["UTM_TERM"]=> NULL }
 *
 ***/

/***Contact
 * array(62)
 * { ["ID"]=> string(1) "1"
 * ["POST"]=> string(41) "Помощник руководителя"
 * ["COMMENTS"]=> string(0) ""
 * ["HONORIFIC"]=> NULL
 * ["NAME"]=> string(18) "Владислав"
 * ["SECOND_NAME"]=> string(0) "" ["LAST_NAME"]=> string(16) "Михайлов"
 * ["FULL_NAME"]=> string(35) "Владислав Михайлов"
 * ["PHOTO"]=> string(3) "249"
 * ["LEAD_ID"]=> NULL
 * ["TYPE_ID"]=> string(5) "SHARE"
 * ["SOURCE_ID"]=> string(4) "SELF"
 * ["SOURCE_DESCRIPTION"]=> string(0) ""
 * ["COMPANY_ID"]=> string(1) "2"
 * ["COMPANY_TITLE"]=> string(30) "ООО "МТД Реклама""
 * ["COMPANY_LOGO"]=> string(3) "257"
 * ["BIRTHDATE"]=> NULL
 * ["BIRTHDAY_SORT"]=> string(4) "1024"
 * ["EXPORT"]=> string(1) "Y"
 * ["HAS_PHONE"]=> string(1) "Y"
 * ["HAS_EMAIL"]=> string(1) "N"
 * ["DATE_CREATE"]=> string(19) "01.02.2017 09:36:31"
 * ["DATE_MODIFY"]=> string(19) "01.02.2017 09:36:31"
 * ["ASSIGNED_BY_ID"]=> string(1) "1"
 * ["ASSIGNED_BY_LOGIN"]=> string(5) "admin"
 * ["ASSIGNED_BY_NAME"]=> string(12) "Сервис"
 * ["ASSIGNED_BY_LAST_NAME"]=> string(9) "Ай-Ти"
 * ["ASSIGNED_BY_SECOND_NAME"]=> string(0) ""
 * ["ASSIGNED_BY_WORK_POSITION"]=> string(26) "Администратор"
 * ["ASSIGNED_BY_PERSONAL_PHOTO"]=> string(4) "3269"
 * ["CREATED_BY_ID"]=> string(1) "1"
 * ["CREATED_BY_LOGIN"]=> string(5) "admin"
 * ["CREATED_BY_NAME"]=> string(12) "Сервис"
 * ["CREATED_BY_LAST_NAME"]=> string(9) "Ай-Ти"
 * ["CREATED_BY_SECOND_NAME"]=> string(0) ""
 * ["MODIFY_BY_ID"]=> string(1) "1"
 * ["MODIFY_BY_LOGIN"]=> string(5) "admin"
 * ["MODIFY_BY_NAME"]=> string(12) "Сервис"
 * ["MODIFY_BY_LAST_NAME"]=> string(9) "Ай-Ти"
 * ["MODIFY_BY_SECOND_NAME"]=> string(0) ""
 * ["OPENED"]=> string(1) "N"
 * ["WEBFORM_ID"]=> NULL
 * ["ORIGINATOR_ID"]=> NULL
 * ["ORIGIN_ID"]=> NULL
 * ["ORIGIN_VERSION"]=> NULL
 * ["FACE_ID"]=> NULL
 * ["ADDRESS"]=> NULL
 * ["ADDRESS_2"]=> NULL
 * ["ADDRESS_CITY"]=> NULL
 * ["ADDRESS_POSTAL_CODE"]=> NULL
 * ["ADDRESS_REGION"]=> NULL
 * ["ADDRESS_PROVINCE"]=> NULL
 * ["ADDRESS_COUNTRY"]=> NULL
 * ["ADDRESS_COUNTRY_CODE"]=> NULL
 * ["ASSIGNED_BY"]=> string(1) "1"
 * ["CREATED_BY"]=> string(1) "1"
 * ["MODIFY_BY"]=> string(1) "1"
 * ["UTM_SOURCE"]=> NULL
 * ["UTM_MEDIUM"]=> NULL
 * ["UTM_CAMPAIGN"]=> NULL
 * ["UTM_CONTENT"]=> NULL
 * ["UTM_TERM"]=> NULL }
 */

?>