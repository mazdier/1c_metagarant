<?
include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
global $DB;
if($_REQUEST['action']){
	switch($_REQUEST['action']){
		case 'AddProductID':
			\Bitrix\Main\Loader::includeModule('crm');
			$ProductRow =  CCrmInvoice::GetProductRows($_REQUEST['invoice']);
			foreach($ProductRow as  $value){
				$arResult[] = $value['PRODUCT_ID'];
			}
		break;
		case 'UpdateOstRez':
			foreach($_POST['ArrProductID'] as $ID){
				$id_1c_product=$DB->Query("SELECT `id` FROM 1c_product WHERE `gen_id` = '".$ID."'")->Fetch();
				if($id_1c_product)
				$ArrRez=$DB->Query('SELECT `col`, `rez` FROM 1c_storage_sum WHERE 1c_storage_sum.1c_product_id = '.$id_1c_product['id'])->Fetch();
				$ArrRez['product_id'] = $ID;
				$arResult[]=$ArrRez ;
			}
		break;
	}
}
$arResult = $arResult?$arResult:'';
print_r(json_encode($arResult));

?>