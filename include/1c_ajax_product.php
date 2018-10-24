<?

if($_REQUEST){
	
	include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
	global $USER;
	global $BD;
	$IblockProductId = 30;
	$IblockListCut = 40;
	$userId = $USER->GetID();
	$CIBlockElement=new CIBlockElement(false);
	$filter = ['ID'=>$userId];
	$rsUsers = CUser::GetList($by, $order, $filter);
	while ($arUser = $rsUsers->Fetch()) {
		$WorkSity=$arUser['WORK_CITY'];
	}
	switch ($_REQUEST['action']){
		case 'product_calc':
		$arSelect = ['NAME','ID'];
		$arrProperty=\CIBlockElement::GetProperty($IblockProductId,$_REQUEST['ID'],$by="sort", $order="asc");
				while($arrPropertyF=$arrProperty->GetNext()){
					$arrResult[$arrPropertyF['CODE']]=$arrPropertyF['VALUE'];
				}
		$ib_list['USER_ID'] = $userId;
		$ib_list['CITY'] = $WorkSity;
		$ib_list=array_merge($ib_list,$arrResult);
		print_r(json_encode($ib_list));
		break;
		
		case 'product_tab':
		$arFields['IBLOCK_ID'] = $IblockListCut;
			switch ($WorkSity){
				case 'Могилев':
					$arFields['PROPERTY_GOROD'] = 351;
				break;
				case 'Минск':
					$arFields['PROPERTY_GOROD'] = 350;
				break;
				case 'Могилёв':
					$arFields['PROPERTY_GOROD'] = 351;
				break;
			}
		$ProductId=$DB->Query("SELECT 1c_product.* FROM 1c_product WHERE 1c_product_table.`id`=".$_REQUEST['PODUCT_BD_ID'])->Fetch()['product_id'];
		$InternalCode = $CIBlockElement -> GetList([],['IBLOCK_ID'=>$IblockProductId,'ID'=>$ProductId],false,false,['PROPERTY_INTERNAL_CODE'])->Fetch()['PROPERTY_INTERNAL_CODE_VALUE'];
		$price = $CIBlockElement -> GetList([],['IBLOCK_ID'=>$IblockListCut,'PROPERTY_VNUTRENNIY_KOD'=>$InternalCode],false,false,['PROPERTY_PRICE_WITH_NDS'])->Fetch()['PROPERTY_PRICE_WITH_NDS_VALUE'];
		//print_r(json_encode($price));
		break;
		case 'product_tab_save_data_map':
			$RequestData = $_REQUEST['data'];
			$dataMap = $DB->Query("SELECT 1c_ymaps.* FROM 1c_ymaps WHERE 1c_ymaps.`invoice_id`=".$RequestData['InvoiceId'])->Fetch();
			 $arFields = [
				'invoice_id' => $RequestData['InvoiceId'],
				'price' => $RequestData['price'],
				'distance_city' => $RequestData['distanceCity'],
				'distance_under_city' => $RequestData['distanceUnderCity'],
			 ];
			if($dataMap){
				echo json_encode('ok');
				$DB->Update("1c_ymaps", $arFields, "WHERE id='".$dataMap['id']."'", $err_mess.__LINE__);
			}
			else {
				$id = $DB->Insert("1c_ymaps", $arFields,$err_mess.__LINE__);
				echo json_encode($id);
			}
			//print_r(json_encode($_REQUEST['data']));
		break;
		case 'product_tab_get_data_map':
			$dataMap = $DB->Query("SELECT 1c_ymaps.* FROM 1c_ymaps WHERE 1c_ymaps.`invoice_id`=".$_REQUEST['InvoiceId'])->Fetch();
			print_r(json_encode($dataMap));
		break;
	}
}
?>