<?
//include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");//_before
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$el=new CIBlockElement(false);
$iblock_id=34;
$arSelect=['PROPERTY_KLIENT','ID','NAME'];
	 $ib_list = $el->GetList([],["IBLOCK_ID" => $iblock_id,'IBLOCK_ACTIVE'=>'Y'],false,[],$arSelect);
	 $companyid=31941;
	 while($ib_listFC=$ib_list->GetNext()){
		if(trim($ib_listFC['PROPERTY_KLIENT_VALUE'],'CO_')==$companyid){
				z($ib_listFC);
		}
	 }
?>
<script>
$('.crm-deal-client-selector-title').parent().before("<li class='item'>Тест</li>");
</script>