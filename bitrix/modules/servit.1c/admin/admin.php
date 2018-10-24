<?

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/prolog.php");
$APPLICATION->SetTitle(GetMessage("SETTING_1C_TITLE"));
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");
global $DB;
function rus_translit($text)
{
    // Русский алфавит
    $rus_alphabet = array(
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
        'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
        'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
        'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
        'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
    );
    
    // Английская транслитерация
    $rus_alphabet_translit = array(
        'A', 'B', 'V', 'G', 'D', 'E', 'IO', 'ZH', 'Z', 'I', 'I',
        'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F',
        'H', 'C', 'CH', 'SH', 'SH', '`', 'Y', '`', 'E', 'IU', 'IA',
        'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'i',
        'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f',
        'h', 'c', 'ch', 'sh', 'sh', '`', 'y', '`', 'e', 'iu', 'ia'
    );
    
    return str_replace($rus_alphabet, $rus_alphabet_translit, $text);
}
CAdminMessage::ShowMessage($strError);

$arTabs = array(
	array("DIV" => "tabSource", "TAB" => GetMessage("SETTING_TAB_1"), "TITLE"=>GetMessage("SETTING_TAB_1_DESC")),
	array("DIV" => "tabSettings", "TAB" => GetMessage("SETTING_TAB_2"), "TITLE"=>GetMessage("SETTING_TAB_2_DESC")),
	array("DIV" => "tabResults", "TAB" => GetMessage("SETTING_TAB_3"), "TITLE"=>GetMessage("SETTING_TAB_3_DESC")),
);
$tabControl = new CAdminTabControl("tabControl", $arTabs, false, true);
?>

<form method="post" enctype="multipart/form-data" action="<?=$APPLICATION->GetCurPage()?>" name="import_user_form">
<?
if(empty($tabStep)) $tabStep=0;
$tabControl->Begin();
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
$tabControl->BeginNextTab();
?>
<?

/*if($_POST['Update_fild']=='Обновить'){
	if(CModule::IncludeModule('crm')){
		$db_invoice_fild="SELECT * from 1c_bx_filds";
		$resDB=$DB->Query($db_invoice_fild);
		while($resDBf=$resDB->GetNext()){
					//z($resDBf);
					$namebx=strtoupper(str_replace([',',';','.','!',' '], '',substr(rus_translit($resDBf['namebx']), 0, 10)));
				if($resDBf['type']=='Список') {
					$massList=explode("/;", $resDBf['lists']);
				}
				elseif($resDBf['entity_type']=='address'){
					$setting_val=[
								'SHOW_MAP' => 'Y'
								];
				}
				else $massList=0;
				$setting_val=[
								'DEFAULT_VALUE' => '',
								'SIZE'          => '100',
								'ROWS'          => '1',
								'MIN_LENGTH'    => '0',
								'MAX_LENGTH'    => '0',
								'REGEXP'        => '',
								];
				$oUserTypeEntity    = new CUserTypeEntity();
				//масив поля
				$aUserFields    = [
					'ENTITY_ID'         => 'ORDER',
					'FIELD_NAME'        => 'UF_CRM_'.$namebx,
					'USER_TYPE_ID'      =>  $resDBf['entity_type'],
					'XML_ID'            => 'XML_UF_CRM_'.$namebx,
					'SORT'              => 500,
					'MULTIPLE'          => 'N',
					'MANDATORY'         => 'N',
					'SHOW_FILTER'       => 'N',
					'SHOW_IN_LIST'      => '',
					'EDIT_IN_LIST'      => '',
					'IS_SEARCHABLE'     => 'N',
					'SETTINGS'          => $setting_val,
					'EDIT_FORM_LABEL'   => [
						'ru'    => $resDBf['namebx'],
						'en'    => rus_translit($resDBf['namebx']),
					],
					'LIST_COLUMN_LABEL' => [
						'ru'    => $resDBf['namebx'],
						'en'    => rus_translit($resDBf['namebx']),
					],
					'LIST_FILTER_LABEL' => [
						'ru'    => $resDBf['namebx'],
				'en'    => rus_translit($resDBf['namebx']),
				],
					'ERROR_MESSAGE'     => [
						'ru'    => 'Ошибка при заполнении',

				];
			z($aUserFields);
			$iUserFieldId=$oUserTypeEntity->Add( $aUserFields );
			//массив списка
			$obEnum = new CUserFieldEnum();
				if($massList!=0){
					foreach($massList as $k=> $valList){
						$arAddEnum['n'.$k] = [
							'XML_ID' => md5($valList),
							'VALUE' => $valList,
							'DEF' => 'N',
							'SORT' => 100
						];			
						
					}
					//z($iUserFieldId);
					//z($massList);
					//z($arAddEnum);
					$obEnum->SetEnumValues($iUserFieldId, $arAddEnum);
					unset($arAddEnum);
				}

	
	//$fieldDB=CUserTypeEntity::GetByID(212);
	//$fieldDB=CUserFieldEnum::GetList([],["ID"=>212])->Fetch();
		
	//CUserTypeEntity::Delete();
	//while($fieldDBf=$fieldDB->GetNext()){
	
	
	//$obEnum->DeleteFieldEnum(212);
	
	//}
		}
	}
	//$fieldDB=$USER_FIELD_MANAGER->GetUserFields('ORDER');
	//	z($fieldDB);
	//$fieldDB=CUserTypeEntity::GetList(array(), array("ID"=>212))->Fetch();//Delete(212);
	//удаление полей


}*/
?>
<!--<input type="submit" name="Update_fild" value="Обновить" >
<input type="submit" name="saveButton" value=" <?=GetMessage("SAVE_BUTTON")?>" class="adm-btn-save">-->
<?
$tabControl->EndTab();
$tabControl->BeginNextTab();
?>

<input type="submit" name="saveButton" value=" <?=GetMessage("SAVE_BUTTON")?>" class="adm-btn-save">
<?
$tabControl->EndTab();
$tabControl->Buttons();
?>



<?$tabControl->End();?>
</form>

<iframe style="display:none;" id="progress" name="progress" src="javascript:''"></iframe>



<?require_once ($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");?>