<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\Loader::includeModule('crm');
$IBLOCK_ID_LISTS = 33;
global $USER_FIELD_MANAGER;
$CCrmCompany=new CCrmCompany(false);
        $CCrmCompanyDB=$CCrmCompany->getList(["SORT"=>"ASC", "PROPERTY_PRIORITY"=>"ASC"],["ID"=>8,'CHECK_PERMISSIONS' => 'N'])->Fetch();    
        $result_company_filds = $USER_FIELD_MANAGER->GetUserFields('CRM_COMPANY');
        foreach($result_company_filds as $keys=>$resns){
                 $zobs=CUserTypeEntity::GetByID($resns['ID']);
                 if($zobs['LIST_COLUMN_LABEL']['ru']=='Договор по умолчанию'){
                    $rsItems = CIBlockElement::GetProperty($IBLOCK_ID_LISTS, $CCrmCompanyDB[$zobs['FIELD_NAME']], array("sort" => "asc"), ['CODE' => 'NAIMENOVANIE'])->Fetch();
                         z($rsItems['VALUE']);
                }   
        }      
?>