<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$catalog_id=\Bitrix\Main\Config\Option::get("servit_1c", "catalog_id");
$bs = new CIBlockSection;
$el = new CIBlockElement;
global $DB;
$propertycib=new CIBlockPropertyEnum;//свойства инфоблока товаров, списки
$pathList=$bs->GetList([],["IBLOCK_ID" => $catalog_id]);
$i=0;
while($pathListF=$pathList->Fetch()){
    //z($pathListF["ID"]);
    $bs->Delete($pathListF["ID"], $bCheckPermissions = false);
   // if($i==100) die();
    $i++;
}
$i=0;
$product=$el->GetList(["SORT"=>"ASC", "PROPERTY_PRIORITY"=>"ASC"],["IBLOCK_ID"=>$catalog_id]);
//z($product);
while($productF=$product->Fetch()){
    //z($productF['ID']);
    $el->Delete($pathListF["ID"], $bCheckPermissions = false);
    //if($i==100) die();
    $i++;
}
$res = $DB->Query("SELECT 1c_product.*
                   FROM 1c_product
                   
                   WHERE 1c_product.`bx_flag`=1");

while($ar = $res->Fetch())
{

    //z($ar);
     $arFields = [
                "gen_id" => '0',
                "bx_flag" => '0',
    
                
                ];
    $DB->Update("1c_product", $arFields, "WHERE 1c_product.id='".$ar['id']."'", $err_mess.__LINE__);

    
}
$res = $DB->Query("SELECT 1c_product_path.*
                   FROM 1c_product_path
                   
                   WHERE 1c_product_path.`bx_flag`=1");

while($ar = $res->Fetch())
{

    //z($ar);
     $arFields = [
                "gen_id" => '0',
                "bx_flag" => '0',
     
                
                ];
    $DB->Update("1c_product_path", $arFields, "WHERE 1c_product_path.id='".$ar['id']."'", $err_mess.__LINE__);

    
}
?>