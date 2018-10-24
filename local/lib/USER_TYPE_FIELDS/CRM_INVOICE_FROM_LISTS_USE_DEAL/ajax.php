<?
if($_POST['DealId']){
	include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
	$arInvoice=CCrmInvoice::Getlist([],['UF_DEAL_ID'=>$_POST['DealId']],false,false,['ID','ORDER_TOPIC','ACCOUNT_NUMBER']);
	$arResult=[];
	while($arInvoiceF=$arInvoice->Fetch()){
		if($arInvoiceF['ID']!=$_POST['InvoiceId']){
			$arResult[]=$arInvoiceF;
		}
		else array_unshift($arResult, $arInvoiceF);
	}
	print_r(json_encode($arResult));
	
}
?>