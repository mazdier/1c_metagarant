<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $DB;
\Bitrix\Main\Loader::IncludeModule('crm');
$requisite = new \Bitrix\Crm\EntityRequisite();
$company=new CCrmCompany(false);
	$bs = new CIBlockSection;
	$el=new CIBlockElement(false);
	$iblock_id=27;
	$sort=500;
	$contractDB=$DB->Query("SELECT 1с_сhecking_account.id,1с_сhecking_account.1c_id,1с_сhecking_account.gen_id,
								1с_сhecking_account.name,number_invoice,bik,cbu,bank_name,legal_address,1с_сhecking_account.bx_flag,1с_сhecking_account.1c_flag,
								contragent_id
						FROM 1с_сhecking_account						
						WHERE 1с_сhecking_account.`bx_flag`=1");//`bx_flag`=1
	while($contractFH=$contractDB->Fetch()){
		$invoice_flag=0;
		$rs = $requisite->getList(["filter" => ["ENTITY_ID" => $contractFH['contragent_id'], "ENTITY_TYPE_ID" => CCrmOwnerType::Company,'PRESET_ID' => 1]]);
		while($rsF=$rs->Fetch()){

			if($contractFH['gen_id']==0){
				$arFild=[
				"NAME"=>'Банковские реквизиты: '.$contractFH["number_invoice"]." - ".$contractFH['bank_name'],
				  "RQ_BANK_NAME" => $contractFH['bank_name'],
				  "RQ_ACC_NUM" => $contractFH["number_invoice"],
				  "RQ_BIK" => $contractFH["bik"],
				  'RQ_BANK_ADDR'=> $contractFH["legal_address"],
					"ENTITY_ID" =>$rsF['ID'] ,
				  "ENTITY_TYPE_ID" => 8,
				  ];
				$new_bank=(new \Bitrix\Crm\EntityBankDetail)->add($arFild);
				$new_bankID=$new_bank->getId();
				$arFields = ["gen_id"=>"'".$new_bankID."'","bx_flag"=>'1'];
				$DB->Update("1с_сhecking_account", $arFields, "WHERE `id`=".$contractFH['id'], $err_mess.__LINE__);
				
			}
			else{
				
				$arFild=[
				"NAME"=>'Банковские реквизиты: '.$contractFH["number_invoice"]." - ".$contractFH['bank_name'],
				  "RQ_BANK_NAME" => $contractFH['bank_name'],
				  "RQ_ACC_NUM" => $contractFH["number_invoice"],
				  "RQ_BIK" => $contractFH["bik"],
				  'RQ_BANK_ADDR'=> $contractFH["legal_address"],
					"ENTITY_ID" =>$rsF['ID'] ,
				  "ENTITY_TYPE_ID" => 8,
				  ];
				(new \Bitrix\Crm\EntityBankDetail)->Update($contractFH['gen_id'],$arFild);

				$arFields = ["bx_flag"=>'1'];
				$DB->Update("1с_сhecking_account", $arFields, "WHERE `id`=".$contractFH['id'], $err_mess.__LINE__);
			}
				//----------Удаление банковских реквизитов, и gen_id из базы-----------
			/*$bankReq = (new \Bitrix\Crm\EntityBankDetail)->getList(['filter' => ['ENTITY_ID' => $rsF['ID'],'ENTITY_TYPE_ID' => CCrmOwnerType::Requisite]]);
			while($bankReqF=$bankReq->Fetch()){
				//z($bankReqF['ID']);
				(new \Bitrix\Crm\EntityBankDetail)->Delete($bankReqF['ID']);
				$arFields = ["gen_id"=>'0'];
				$DB->Update("1с_сhecking_account", $arFields, "WHERE `id`=".$contractFH['id'], $err_mess.__LINE__);
			}*/
		}
	}
	

?>