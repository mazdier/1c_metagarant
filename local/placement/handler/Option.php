<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
COption::SetOptionString("infinity","email_from","admin@site.com");
$arTabs = array(
	array("DIV" => "tabSource", "TAB" => 'Настройки Infinity', "TITLE"=>'Настройки Infinity'),
);
$tabControl->BeginNextTab();

if(empty($_POST)) $stage=0;
else $stage=1;
CModule::IncludeModule("iblock");
$res = CIBlock::GetList(
    ['IBLOCK_TYPE_ID' => 'CRM_PRODUCT_CATALOG',"SORT"=>"DSC"], 
    [
		//'ID'=>3,
        'IBLOCK_TYPE_ID' => 'CRM_PRODUCT_CATALOG',
		  'LIST_PAGE_URL' => '/crm/product/list/',
        'ACTIVE'=>'Y', 
        "CNT_ACTIVE"=>"Y", 
    ]
);
while($ar_res = $res->Fetch())
{
	if($ar_res["IBLOCK_TYPE_ID"]==='CRM_PRODUCT_CATALOG'){
		$USEcat[]=["ID"=>$ar_res["ID"],"NAME"=>$ar_res["NAME"]];

	}
}
//COption::SetOptionString('crm', 'default_product_catalog_id', 33);
?>
<?//if($stage==0):

?>
ID Каталога: <select name='catalog_id'>
<?foreach($USEcat as $valuse):?>
     <option value='<?=$valuse["NAME"]?>' ><?=$valuse["NAME"]?>
	 <?if($_POST['catalog_id']===$valuse["NAME"]):?>
	 <?COption::SetOptionString('crm', 'default_product_catalog_id', $valuse["ID"]);?>
	 <?\Bitrix\Main\Config\Option::set('servit_1c','catalog_id',$valuse["ID"]);?>
	 <?endif?>
<?endforeach?>
     </select><br/><br/>
	  </option>

<input type="submit" name="saveButton" value=" <?=GetMessage("SAVE_BUTTON")?>" class="adm-btn-save">
<?//endif
?>
<?

$tabControl->EndTab();
?>
