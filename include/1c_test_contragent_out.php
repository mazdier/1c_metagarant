<?include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");?>
<?php 
//

CModule::IncludeModule('crm');
global  $USER ;
$USER ->Authorize(1);
$company = CCrmCompany::GetList(Array('DATE_CREATE' => 'DESC'), $arFilter = Array("ID"=>31845, 'CHECK_PERMISSIONS' => 'N'))->GetNext(); 
var_dump(CCrmFieldMulti::GetList(array(),array('ENTITY_ID' => 'COMPANY', 'ELEMENT_ID' => 31844))->fetch());
		$requisite = new \Bitrix\Crm\EntityRequisite();
		$rs = $requisite->getList(["filter" => ["ENTITY_ID" => 31845, "ENTITY_TYPE_ID" => CCrmOwnerType::Company, 'PRESET_ID' => 1]]);
		$requisite->addFromData(CCrmOwnerType::Company, 31845, array("RQ_INN" => "sd", 'PRESET_ID' => 1));
		$rs = $rs->fetch(); 
        $address = Bitrix\Crm\EntityRequisite::getAddresses($rs["ID"]);
        $company = CCrmCompany::GetByID(31845)["ASSIGNED_BY_ID"];
		print_r($company);
		print_r($rs);
		print_r($address); 
		$dbResMultiFields = CCrmFieldMulti::GetList(array(),array('ENTITY_ID' => 'COMPANY', 'ELEMENT_ID' => 31845)); 
		while($arMultiFields = $dbResMultiFields->Fetch()){
				print_r($arMultiFields); 
		}
		$bankReq = (new \Bitrix\Crm\EntityBankDetail)->getList(['filter' => ['ENTITY_ID' => $rs['ID'],'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite]])->fetch();
		var_dump($bankReq);
		$resDBcom = $requisite->getList([
        "filter" => [
            "ENTITY_ID" =>31845 ,
            "ENTITY_TYPE_ID" => CCrmOwnerType::Company,
        ]
    ])->Fetch();
z($resDBcom);
$arFilds=[
          "ENTITY_ID" =>31845 ,
          "ENTITY_TYPE_ID" => CCrmOwnerType::Company,
          "NAME"=>Организация,
		  "PRESET_ID" => 1,
		  "RQ_INN"=>"222"
          ];
$resDB = $requisite->add($arFilds);
z($resDB->getId());


$arFild=[
    "ENTITY_ID" =>27 ,
          "ENTITY_TYPE_ID" => 8,
		  "NAME" => "Банковские реквизиты 1",
		  "RQ_ACC_NUM" => "1111",
		  "RQ_COR_ACC_NUM" => "2222"
          ];
		  
		 // var_dump((new \Bitrix\Crm\EntityBankDetail)->add($arFild));
//$res=$requisite->Update(21,$arFild);
//z($res);
\Bitrix\Crm\AddressTable::upsert(array('TYPE_ID' => 6, 'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite, 'ENTITY_ID' => 34, "ADDRESS_1" => "adreeeeeeeeeees"));
var_dump($entityAddress);
/*
file_put_contents("log.txt", "\nначало скрипта\n", FILE_APPEND );
file_put_contents('log.txt', json_encode($_POST). "\n", FILE_APPEND);
global $DB;
global  $USER ;
$USER->Authorize(1);
CModule::IncludeModule('crm');
if($_POST["type"]=="company" && !empty($_POST["arrID"])){
	file_put_contents("log.txt", "arrID = " . $_POST["arrID"] . "\n", FILE_APPEND );
	$arrID = explode(",",$_POST["arrID"]);
		goto update;
}
if( $_POST["type"]=="company" && !empty($_POST["id"]) ) {
	$id_company = $_POST["id"];
	goto update;
}
$user_id = $_POST["user_id"];
$title = $_POST["title_company"];
file_put_contents("log.txt", "title = " . $_POST["title_company"] . "\n", FILE_APPEND );
file_put_contents("log.txt", "user_id = " . $user_id . "\n", FILE_APPEND );
$is_company = $_POST['map_entity'] == "company"; 
if($_POST['map_entity'] != "company"){
	$is_company = strpos(" ".$_POST['url'], "/crm/company/details");
}
file_put_contents("log.txt", "is_company = " . $is_company . "\n", FILE_APPEND );
if($is_company){
	$url = parse_url($_POST['url']);
	parse_str($url["query"], $output_url);
	$id_company  = $output_url["eid"];
	file_put_contents("log.txt", "id_company с вызовом фрейма = " . $id_company . "\n", FILE_APPEND );
	if(!isset($id_company)) $id_company = str_replace("/?IFRAME=Y&IFRAME_TYPE=SIDE_SLIDER" , "" ,str_replace("/crm/company/details/" , "" , urldecode($_POST['url'])));
	file_put_contents("log.txt", "id_company = " . $id_company . "\n", FILE_APPEND );
	if($id_company == 0){
		file_put_contents("log.txt", "Добавляем новую\n", FILE_APPEND );
		file_put_contents("log.txt", "засыпаем и делаем выборку time: ".time()."\n", FILE_APPEND );
		time_sleep_until(time()+2);
		$company = CCrmCompany::GetList(Array('DATE_CREATE' => 'DESC'), $arFilter = Array("CREATED_BY"=>$user_id, "TITLE" => $title, 'CHECK_PERMISSIONS' => 'N'));
		file_put_contents("log.txt", "сделали выборку time: ".time()."\n", FILE_APPEND );
		$company = $company->GetNext();
		$stmp = MakeTimeStamp($company["DATE_CREATE"], "DD.MM.YYYY HH:MI:SS");
		file_put_contents("log.txt", "создание записи time: ".$stmp."\n", FILE_APPEND );
		if(time()-$stmp > 5){
				file_put_contents("log.txt", "я умер, т.к. последняя запись была создана более 3-ёх секунд назад \n", FILE_APPEND );
				$USER->logout();
				die();
		} 
		file_put_contents("log.txt", "данные записи: ". json_encode($company) ."\n", FILE_APPEND );
		$requisite = new \Bitrix\Crm\EntityRequisite();
		$rs = $requisite->getList(["filter" => ["ENTITY_ID" => $company["ID"], "ENTITY_TYPE_ID" => CCrmOwnerType::Company,'PRESET_ID' => 1]]);
		$rs = $rs->fetch();
		file_put_contents("log.txt", "реквизиты записи: ". json_encode($rs) ."\n", FILE_APPEND );
		$address = Bitrix\Crm\EntityRequisite::getAddresses($rs["ID"]);
		$bankReq = (new \Bitrix\Crm\EntityBankDetail)->getList(['filter' => ['ENTITY_ID' => $rs['ID'],'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite]])->fetch();
		$id = $DB->Query("SELECT max(id) from 1c_contragent");
		$id = $id->fetch();
        $id = $id["max(id)"] + 1;	
		file_put_contents("log.txt", "user_id " . $user_id . "\n", FILE_APPEND );
		file_put_contents("log.txt", "title " . $title . "\n", FILE_APPEND );		
		$gen_id = $company["ID"];
		file_put_contents("log.txt", "gen_id " . $gen_id . "\n", FILE_APPEND );
		$obEnum = new CUserFieldEnum();
		$list = $obEnum->getList();
		foreach($list->arResult as $value){
			if($value["ID"]==$company["UF_CRM_CONTR_TYPE_ID"]){
				$contragent_category_id_name = $value["VALUE"];
			}  
        }
		file_put_contents("log.txt", "тип контрагента: " . $contragent_category_id_name . "\n", FILE_APPEND );
		$category_id = $DB->Query("SELECT id from 1c_category_contragent where category = '$contragent_category_id_name'"); 
		$category_id = $category_id->fetch();
		$category_id = $category_id["id"];
		if(!isset($category_id)) $category_id = "null";
		$full_name = $rs["RQ_COMPANY_FULL_NAME"]; 
		$obEnum = new CUserFieldEnum();
		$list = $obEnum->getList();
		foreach($list->arResult as $value){
			if($value["ID"]==$company["UF_CRM_COUNTRY_ID"]){
				$contragent_country_reg_id = $value["VALUE"];
			}  
        }
		file_put_contents("log.txt", "Наименование страны: " . $contragent_country_reg_id . "\n", FILE_APPEND );
		$country_reg_id = $DB->Query("SELECT * FROM `1c_country` where concat(`COUNTRY_NAME`, \" \", `CITY_NAME`) = '$contragent_country_reg_id'"); 
		$country_reg_id = $country_reg_id->fetch();
		$country_reg_id = $country_reg_id["id"];
		if(!isset($country_reg_id)) $country_reg_id = "null";
		$ur_address = $address[6]["ADDRESS_1"] . " " . $address[6]["ADDRESS_2"]; 
		$dbResMultiFields = CCrmFieldMulti::GetList(array(),array('ENTITY_ID' => 'COMPANY', 'ELEMENT_ID' => $company["ID"])); 
		while($row = $dbResMultiFields->fetch()){
			if($row["COMPLEX_ID"] == "EMAIL_WORK") $mail_address = $row["VALUE"];
			if($row["COMPLEX_ID"] == "PHONE_WORK") $phone = $row["VALUE"];
		}
		$code = $company["UF_CRM_CODE"];
		$dop_info = $company["COMMENTS"]; 
		$inn = $rs["RQ_INN"]; 
		$kpp = $rs["RQ_KPP"]; 
		$okpo = $rs["RQ_OKPO"]; 
		$bin = $rs["RQ_OGRN"];
		$main_contract_id = $company["UF_CRM_1510760262138"]; 
		if(!isset($main_contract_id)) $main_contract_id = "null";
		$boss_id = $company["UF_CRM_1510760322395"]; 
		if(!isset($boss_id)) $boss_id = "null";
		$order = $company["UF_CRM_ORDER"]; 
		$obEnum = new CUserFieldEnum();
		$list = $obEnum->getList();
		foreach($list->arResult as $value){
			if($value["ID"]==$company["UF_CRM_CONTR_CAT_ID"]){
				$category_id_name = $value["VALUE"];
			}  
        }
		file_put_contents("log.txt", "Категория контрагента: " . $category_id_name . "\n", FILE_APPEND );
		$contragent_category_id = $DB->Query("SELECT id from 1c_path where name = '$category_id_name'"); 
		$contragent_category_id = $contragent_category_id->fetch();
		$contragent_category_id = $contragent_category_id["id"];
		if(!isset($contragent_category_id)) $contragent_category_id = "null";
		$manager_id = $company["UF_CRM_1510760381268"]; 
		if(!isset($manager_id)) $manager_id = "null";
		$code_fil = $company["UF_CRM_CODE_FILL"]; 
		$zavis_face = $company["UF_CRM_IDEPENDENT"]; 
		$rezident_off_area = $company["UF_CRM_OFFSHORE"]; 
		$deal_po = $company["UF_CRM_DEAL"]; 
		$org_big = $company["UF_CRM_ORG_BIG"]; 
		$contract_id = $company["UF_CRM_1510760435139"]; 
		if(!isset($contract_id)) $contract_id = "null";
		$action_on = $company["UF_CRM_ACTION_ON"];
		$invoice_k = $bankReq["RQ_COR_ACC_NUM"];
		$lic = $company["UF_CRM_LIC"];
		$rs = $bankReq["RQ_ACC_NUM"];
		$SQLquery = "INSERT INTO `1c_contragent`

		(`id`, 
		`bin`, 
		`invoice_k`,
		`lic`,
		`rs`,
		`contragent_category_id`, 
		`gen_id`, 
		`name`, 
		`full_name`,
		`country_reg_id`,
		`ur_address`, 
		`mail_address`, 
		`phone`, 
		`code`,
		`dop_info`,
		`inn`, 
		`kpp`,
		`okpo`,
		`main_contract_id`,
		`action_on`, 
		`boss_id`,
		`order`,
		`category_id`,
		`manager_id`,
		`code_fil`,
		`zavis_face`, 
		`rezident_off_area`, 
		`deal_po`,
		`org_big`, 
		`contract_id`, 
		`bx_flag`,
		`1c_flag`)
		
		
		VALUES (
		'$id',
		'$bin',
		'$invoice_k',
		'$lic',
		'$rs',
		$contragent_category_id, 
		'$gen_id',
		'$title',
		'$full_name', 
		$country_reg_id,
		'$ur_address', 
		'$mail_address',
		'$phone',
		'$code',
		'$dop_info',
		'$inn',
		'$kpp',
		'$okpo',
		$main_contract_id,
		'$action_on',
		$boss_id,
		'$order',
		$category_id,
		$manager_id,
		'$code_fil',
		'$zavis_face',
		'$rezident_off_area',
		'$deal_po', 
		'$org_big',
		$contract_id,
		'1',
		'0'
		);";
		file_put_contents("log.txt",$SQLquery,FILE_APPEND);
		$USER->logout();
		$result = $DB->Query($SQLquery);
		file_put_contents("log.txt", "result insert: " . $result . "\n", FILE_APPEND );		
		die();
	} else {
		update:
		if(empty($arrID)){
			$counter = 1;
			$arrID[] = $id_company;
		} else {
			$counter = count($arrID);
		}
		for($i = 0; $i < $counter; $i++){
			file_put_contents("log.txt", "Апдейдим старую\n", FILE_APPEND );
			$company = CCrmCompany::GetList(Array('DATE_CREATE' => 'DESC'), $arFilter = Array("ID"=>$arrID[$i]));
			$company = $company->GetNext();
			$gen_id = $company["ID"];
			file_put_contents("log.txt", "gen_id = ".$gen_id."\n", FILE_APPEND );
			$name = $company["TITLE"];
			$DB->Query("UPDATE `1c_contragent` SET
			`contragent_category_id` = NULL,
			`name` = $name,
			`full_name` = 'ООО \"Аполо-Сервис\"',
			`country_reg_id` = NULL,
			`ur_address` = 'РБ,г.Витебск, Московский пр-т,57А',
			`mail_address` = '',
			`phone` = '212',
			`code` = '4',
			`dop_info` = '',
			`inn` = '300544796',
			`kpp` = '0',
			`okpo` = '0',
			`main_contract_id` = NULL,
			`action_on` = '',
			`boss_id` = NULL,
			`order` = '0',
			`category_id` = NULL,
			`manager_id` = NULL,
			`code_fil` = '0',
			`zavis_face` = '0',
			`rezident_off_area` = '0',
			`deal_po` = '0',
			`org_big` = '0',
			`contract_id` = NULL,
			`bx_flag` = '1'
			WHERE `gen_id` = $gen_id ;");
			$USER->logout();
		}
	}
}*/
?>