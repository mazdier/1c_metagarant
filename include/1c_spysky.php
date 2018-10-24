<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $DB;
$i=0;
\Bitrix\Main\Loader::IncludeModule('crm');
$company=new CCrmCompany(false);
	$bs = new CIBlockSection;
	$el=new CIBlockElement(false);
	$iblock_id=33;
	$sort=500;
	$contractDB=$DB->Query("SELECT 1c_contract.`id`,1c_contract.`gen_id`,1c_contract.`bx_flag`,1c_contract.`1c_flag`,1c_contract.name,
						   number,foreign_exchange,currency_id,type_contract,brief_characteristics,
						1c_contragent.gen_id AS 1c_contragent_id
						FROM 1c_contract						
						LEFT JOIN 1c_contragent  ON 1c_contract.1c_contragent_id=1c_contragent.1c_id
						WHERE 1c_contract.`bx_flag`=1");// subdivision_id, ,1c_contragent.gen_id AS subdivision_id  INNER JOIN 1c_contragent  ON 1c_contract.subdivision_id=1c_contragent.id
/*//1c_contragent.gen_id AS 1c_contragent_id,
						  // 1с_currency.currency AS currency_id
 * //INNER JOIN 1с_currency ON 1c_contract.currency_id=1с_currency.id
						  // INNER JOIN 1c_contragent  ON 1c_contract.1c_contragent_id=1c_contragent.id   */

	while($contractFH=$contractDB->Fetch()){
		if($contractFH['currency_id']){
			$currencyDB=$DB->Query("SELECT * FROM 1с_currency WHERE 1с_currency.`id`=".$contractFH['currency_id']."")->Fetch();
			$contractFH['currency_id']=$currencyDB['currency'];
		}
		
	//z($contractFH);
if($i==40){
	//die();
}
		$date=strtotime($contractFH['document_date']);
		$DATA_PODPISANIYA_DOKUMENTA=date("j.n.Y",$date);
		//z($DATA_PODPISANIYA_DOKUMENTA);
		$arLoadProductArray = [
				"NAME" => $contractFH['name']."  Договор №".$contractFH['number'].' от '.$DATA_PODPISANIYA_DOKUMENTA,
				"IBLOCK_ID"      => $iblock_id,
				"CREATED_BY"     => 1,
				"ACTIVE"         => "Y"
			];        
			//create new element
			if($contractFH['gen_id']==0){
				if($PRODUCT_ID = $el->Add($arLoadProductArray, true)){
					echo 'New ID: '.$PRODUCT_ID;
				} else {
				   echo 'Error: '.$el->LAST_ERROR;
				}
						$arFields = ["gen_id" => "'".$PRODUCT_ID."'","bx_flag" => '1'];
						$DB->Update("1c_contract", $arFields, "WHERE `id`=".$contractFH['id'], $err_mess.__LINE__);
						if($contractFH['foreign_exchange']==1){
				$valutniy='ДА';
			}
			if($contractFH['foreign_exchange']==0){
				$valutniy='НЕТ';
			}
			$arUpdateProductArray =[
										"KRATKIE_KHARAKTERISTIKI"   => $contractFH['brief_characteristics'],
										"VID_DOGOVORA"   => $contractFH['type_contract'],
										"KONTRAGENT"   => "CO_".$contractFH['1c_contragent_id'],
										"VALYUTA"   => $contractFH['currency_id'],
										"VALYUTNYY"   => $valutniy,
										"NAIMENOVANIE"   => $contractFH['name'],
										'NUMBER' =>$contractFH['number'],
										//"PODRAZDELENIE"   => "CO_".$contractFH['subdivision_id'],
									];
			
			$el->SetPropertyValuesEx($PRODUCT_ID,$iblock_id,$arUpdateProductArray);
			$companyDB=$company->GetByID($contractFH['client_id'], $bCheckPerms = false);
			$arLoadProductArray=[
										"NAME"  => $contractFH['name']."  Договор №".$contractFH['number'].' от '.$DATA_PODPISANIYA_DOKUMENTA,
									];
			$el->Update($PRODUCT_ID, $arLoadProductArray);
			$arFields = ["bx_flag" => '1'];
			$DB->Update("1c_contract", $arFields, "WHERE `id`=".$contractFH['id'], $err_mess.__LINE__);
			}
			else $PRODUCT_ID=$contractFH['gen_id'];
			if($contractFH['gen_id']==5637){
				
			}
			if($contractFH['foreign_exchange']==1){
				$valutniy='ДА';
			}
			if($contractFH['foreign_exchange']==0){
				$valutniy='НЕТ';
			}
			$arUpdateProductArray =[
										"KRATKIE_KHARAKTERISTIKI"   => $contractFH['brief_characteristics'],
										"VID_DOGOVORA"   => $contractFH['type_contract'],
										"KONTRAGENT"   => "CO_".$contractFH['1c_contragent_id'],
										"VALYUTA"   => $contractFH['currency_id'],
										"VALYUTNYY"   => $valutniy,
										"NAIMENOVANIE"   => $contractFH['name'],
										'NUMBER' =>$contractFH['number'],
										//"PODRAZDELENIE"   => "CO_".$contractFH['subdivision_id'],
									];
		
			$el->SetPropertyValuesEx($PRODUCT_ID,$iblock_id,$arUpdateProductArray);
			$companyDB=$company->GetByID($contractFH['client_id'], $bCheckPerms = false);
			$arLoadProductArray=[
										"NAME"  => $contractFH['name']."  Договор №".$contractFH['number'].' от '.$DATA_PODPISANIYA_DOKUMENTA,
									];
			$el->Update($PRODUCT_ID, $arLoadProductArray);
			$arFields = ["bx_flag" => '1'];
			$DB->Update("1c_contract", $arFields, "WHERE `id`=".$contractFH['id'], $err_mess.__LINE__);
			//z($arUpdateProductArray);
		$i++;
	}




?>