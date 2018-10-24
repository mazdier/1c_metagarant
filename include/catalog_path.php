<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $DB;
$catalog_id=\Bitrix\Main\Config\Option::get("servit_1c", "catalog_id");
$bs = new CIBlockSection;
 $result_path = $DB->Query("SELECT `1c_id`, `name`,`id`
                   FROM 1c_product_path
                   WHERE 1c_product_path.`bx_flag`=1");
while($ar_result_path = $result_path->Fetch())
{
   $pathList=$bs->GetList([],["IBLOCK_ID" => $catalog_id,"NAME" =>$ar_result_path['1c_id']])->Fetch();
    $Fields = [
                      "IBLOCK_SECTION_ID" =>$pathList['IBLOCK_SECTION_ID'],
                      "NAME" => $ar_result_path['name'],
                      "DESCRIPTION" => strtoupper($ar_result_path['name'])
                  ];
    if(!empty($pathList['ID'])){
        $bs->Update((int)$pathList['ID'],$Fields);
        $arFields = ["gen_id"=>"'".$pathList['ID']."'","bx_flag"=>'1'];
        $DB->Update("1c_product_path", $arFields, "WHERE `id`=".$ar_result_path['id']."", $err_mess.__LINE__);
    }
}
 
?>