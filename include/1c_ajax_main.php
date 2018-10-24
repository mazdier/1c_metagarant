<?php
##############################################
# Service IT Site Manager                    #
# Copyright (c) 2016-2017 Service IT         #
# https://nfr.servit.by                      #
# mailto:admin@servit.by                     #
##############################################
echo "<pre>";
//$request_ajax=$_POST['data'];
$request_ajax='{"id":30,"save":"add","manager":"1"}';
//file_put_contents("loga.txt", "\n ".$request_ajax."\n",FILE_APPEND);
$request_ajax= json_decode($request_ajax,true);
require_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");//_before
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
global $USER_FIELD_MANAGER;
function GetUserFieldVal ($list_id)
{
$opa=CUserFieldEnum::GetList([],['ID'=>$list_id])->Fetch();
return $val=$opa["VALUE"];
}
global $DB;
global $USER;
$IBLOCK_ID_LISTS = 33;
$IBLOCK_ID_INVOICE = 27;
\Bitrix\Main\Loader::includeModule('crm');
$current = date("d.m.y H:i:s");
if($request_ajax['save']){
        $CCrmCompany=new CCrmCompany(false);
        $CCrmInvoice=new CCrmInvoice(false);
        $res=$CCrmInvoice->getList(["SORT"=>"ASC", "PROPERTY_PRIORITY"=>"ASC"],["ID"=>$request_ajax['id'],'CHECK_PERMISSIONS' => 'N'])->Fetch();    
        $result_user = $USER_FIELD_MANAGER->GetUserFields('ORDER');
        foreach($result_user as $key=>$resn){              
                 $zob=CUserTypeEntity::GetByID($resn['ID']);
                 if($zob['LIST_COLUMN_LABEL']['ru']=='На основании')
                    $arDopFilds['Na_osnovanii']=$res[$zob['FIELD_NAME']];    
                 elseif($zob['LIST_COLUMN_LABEL']['ru']=='Цель приобретения'){
                        if(isset($res[$zob['FIELD_NAME']]))
                                $arDopFilds['Cel_priobreteniia']=GetUserFieldVal($res[$zob['FIELD_NAME']]);
                        else $arDopFilds['Cel_priobreteniia']=0;
                        
                        }
                 elseif($zob['LIST_COLUMN_LABEL']['ru']=='Порядок расчетов'){
                        if(isset($res[$zob['FIELD_NAME']]))
                        $arDopFilds['Poriadok_raschetov']=GetUserFieldVal($res[$zob['FIELD_NAME']]);
                        else $arDopFilds['Poriadok_raschetov']=0;
                 }    
                elseif($zob['LIST_COLUMN_LABEL']['ru']=='Срок отгрузки')
                    $arDopFilds['Srok_otgruzki']=$res[$zob['FIELD_NAME']];
                elseif($zob['LIST_COLUMN_LABEL']['ru']=='дополнение')
                    $arDopFilds['dopolnenie']=$res[$zob['FIELD_NAME']];
                elseif($zob['LIST_COLUMN_LABEL']['ru']=='Договор')
                    $arDopFilds['Kod_dogovora']=$res[$zob['FIELD_NAME']];
                elseif($zob['LIST_COLUMN_LABEL']['ru']=='За счет продовца')
                    $arDopFilds['Za_schet_prodovca']=$res[$zob['FIELD_NAME']];
                elseif($zob['LIST_COLUMN_LABEL']['ru']=='С доставкой')
                    $arDopFilds['S_dostavkoy']=$res[$zob['FIELD_NAME']];
                elseif($zob['LIST_COLUMN_LABEL']['ru']=='Срок отгрузки')
                    $arDopFilds['Srok_otgruzki']=$res[$zob['FIELD_NAME']];
                elseif($zob['LIST_COLUMN_LABEL']['ru']=='Бан.дни счета')
                    $arDopFilds['Ban.dni_scheta']=$res[$zob['FIELD_NAME']];
                elseif($zob['LIST_COLUMN_LABEL']['ru']=='Дни отсрочки')
                    $arDopFilds['Dni_otsrochki']=$res[$zob['FIELD_NAME']];
                //elseif($zob['LIST_COLUMN_LABEL']['ru']=='Товар тн')
                //    $arDopFilds['Tovar_tn']=$res[$zob['FIELD_NAME']];
               // elseif($zob['LIST_COLUMN_LABEL']['ru']=='Товар м\п')
                 //   $arDopFilds['Tovar_mp']=$res[$zob['FIELD_NAME']];
                 elseif($zob['LIST_COLUMN_LABEL']['ru']=='Доп услуги')
                    $arDopFilds['Dop_uslugi']=$res[$zob['FIELD_NAME']];
                    elseif($zob['LIST_COLUMN_LABEL']['ru']=='Доп услуги')
                    $arDopFilds['garanty']=$res[$zob['FIELD_NAME']]; 
        }
        $arDopFilds['CURS']=CCurrency::GetCurrency('USD');
        $arDopFilds['STORAGE']=1;
        
        $requisite = new \Bitrix\Crm\EntityRequisite();
        $resDBcom = $requisite->getList([
            "filter" => [
                "ENTITY_ID" =>$res['UF_COMPANY_ID'] ,
                "ENTITY_TYPE_ID" => CCrmOwnerType::Company,
            ]
        ])->Fetch();
        $bank_req2=\Bitrix\Crm\EntityBankDetail::getList(["filter" =>["ENTITY_TYPE_ID" => 8,'ENTITY_ID'=>$resDBcom['ID']]]);
        while($bank_req=$bank_req2->Fetch()){
          $settlement_invoice_payer=$bank_req["RQ_ACC_NUM"];
        }
        $dbBasket = CSaleBasket::GetList(
               array("DATE_INSERT" => "ASC", "NAME" => "ASC"),
               array("ORDER_ID" => $request_ajax['id'])
           )->Fetch();
        $dbResMultiFields = CCrmFieldMulti::GetList(array(),array('ENTITY_ID' => 'COMPANY', 'ELEMENT_ID' => $res['UF_COMPANY_ID']));
        while($arMultiFields = $dbResMultiFields->Fetch())
        {
            if($arMultiFields["TYPE_ID"]=="EMAIL"){
                $email_com[]=$arMultiFields["VALUE"];
            }
            elseif($arMultiFields["TYPE_ID"]=="PHONE")
                $phone_com[]=$arMultiFields["VALUE"];      
        }
        $email_invoice=implode(",", $email_com);
        $phone_invoice=implode(",", $phone_com);
        if($resDBcom['RQ_INN']) $INN=$resDBcom['RQ_INN'];
        if($res['UF_COMPANY_ID']>0){
        $reqcompId=$DB->Query('SELECT * FROM 1c_contragent WHERE 1c_contragent.gen_id= '.$res['UF_COMPANY_ID'])->Fetch();
        }
        else $reqcompId=$DB->Query('SELECT * FROM 1c_contragent WHERE 1c_contragent.gen_id= 1')->Fetch();
        //z($reqcompId);
        $vat=$dbBasket["VAT_RATE"]*100;
        $summ_not_vac=$dbBasket["PRICE"]*$dbBasket["QUANTITY"];
        $sum_vac=$summ_not_vac+$summ_not_vac*$dbBasket["VAT_RATE"];
        //-------------------------Вынимаем договор-----------------
        
        $CCrmCompanyDB=$CCrmCompany->getList(["SORT"=>"ASC", "PROPERTY_PRIORITY"=>"ASC"],["ID"=>$res['UF_COMPANY_ID'],'CHECK_PERMISSIONS' => 'N'])->Fetch();    
        $result_company_filds = $USER_FIELD_MANAGER->GetUserFields('CRM_COMPANY');
        foreach($result_company_filds as $keys=>$resns){
                 $zobs=CUserTypeEntity::GetByID($resns['ID']);
                 if($zobs['LIST_COLUMN_LABEL']['ru']=='Договор по умолчанию'){
                    $rsItems = CIBlockElement::GetProperty($IBLOCK_ID_LISTS, $CCrmCompanyDB[$zobs['FIELD_NAME']], array("sort" => "asc"), ['CODE' => 'NAIMENOVANIE'])->Fetch();
                      $arDopFilds['Kod_dogovora'] = $rsItems['VALUE'];
                }   
        }
        //----------------------------------------------------------
        //-------------------------Вынимаем номер счета плательщика-----------------
        $requisite = new \Bitrix\Crm\EntityRequisite();
        $requisiteEntityList[] =
                                        array('ENTITY_TYPE_ID' => CCrmOwnerType::Invoice, 'ENTITY_ID' => $request_ajax['id']);
        $requisiteInfoLinked = $requisite->getDefaultRequisiteInfoLinked($requisiteEntityList);
        $bank_invoice=(new \Bitrix\Crm\EntityBankDetail)->GetByID($requisiteInfoLinked[BANK_DETAIL_ID]);
        $arDopFilds['Kod_invoice']=$bank_invoice[RQ_ACC_NUM];
       /* $CCrmCompanyDB=$CCrmCompany->getList(["SORT"=>"ASC", "PROPERTY_PRIORITY"=>"ASC"],["ID"=>$res['UF_COMPANY_ID'],'CHECK_PERMISSIONS' => 'N'])->Fetch();    
        $result_company_filds = $USER_FIELD_MANAGER->GetUserFields('CRM_COMPANY');
        foreach($result_company_filds as $keys=>$resns){
                 $zobs=CUserTypeEntity::GetByID($resns['ID']);
                 if($zobs['LIST_COLUMN_LABEL']['ru']=='Рассчетный счет по умолчанию'){
                    $rsItems = CIBlockElement::GetProperty($IBLOCK_ID_INVOICE, $CCrmCompanyDB[$zobs['FIELD_NAME']], array("sort" => "asc"), ['CODE' => 'NOMER_SCHETA'])->Fetch();
                      $arDopFilds['Kod_invoice'] = $rsItems['VALUE'];
                     // z($arDopFilds['Kod_invoice']);
                }   
        }*/
        //----------------------------------------------------------
        //-------------------------Вынимаем цель приобретения-----------------
        $settlement_procedure=$DB->Query("SELECT * FROM `1с_purpose_acquisition` WHERE `name` = '".$arDopFilds['Cel_priobreteniia']."'")->Fetch();
        $arDopFilds['Cel_priobreteniia']=$settlement_procedure['id'];
        //----------------------------------------------------------
        //-------------------------Вынимаем порядок расчетов-----------------
        $settlement_procedure=$DB->Query("SELECT * FROM `1c_settlement_procedure` WHERE `name` = '".$arDopFilds['Poriadok_raschetov']."'")->Fetch();
        $arDopFilds['Poriadok_raschetov']=$settlement_procedure['id'];
        //----------------------------------------------------------
        $arFields = [
                "invoice"=>"'".$res['ACCOUNT_NUMBER']."'",
                "date"=> "'".$current."'",
                "payer_id"=> "'".$reqcompId['id']."'",
                "delivery_flag" => "'".$arDopFilds['S_dostavkoy']."'",
                "1с_сhecking_account" => "'".$arDopFilds['Kod_invoice']."'",
                "purpose_acquisition_id" => "'".$arDopFilds['Cel_priobreteniia']."'",
                "contract_id" => "'".$arDopFilds['Kod_dogovora']."'",
                "addition" => "'".$arDopFilds['dopolnenie']."'",
                "settlement_procedure_id" => "'".$arDopFilds['Poriadok_raschetov']."'",
                "period_shipment" => "'".$arDopFilds['Srok_otgruzki']."'",
                "seller_expense" => "'".$arDopFilds['Za_schet_prodovca']."'",
                "manager_id" => 'NULL',
                "based" => "'".$arDopFilds['Na_osnovanii']."'",
                "ban" => "'".$arDopFilds['Ban.dni_scheta']."'",
                "days_delay" => "'".$arDopFilds['Dni_otsrochki']."'",
               // "product_tn" => "'".$arDopFilds['Tovar_tn']."'",
               // "product_mp" => "'".$arDopFilds['Tovar_mp']."'",
                "storage_id" => "'".$arDopFilds['STORAGE']."'",
                "аdditional_services" => "'".$arDopFilds['Dop_uslugi']."'",
                "number" => 'NULL',
                "storage_id" => "'".$arDopFilds['STORAGE']."'",
                "bx_flag" => '1',
                "1c_flag" =>'0',      
                "del_flag"=>'0'
                ];
    if($request_ajax['save']=='update'){
		
        file_put_contents("loga.txt", "".json_encode($arFields)."\n",FILE_APPEND);
        $DB->Update("1c_invoice", $arFields, "WHERE gen_id='".$request_ajax['id']."'", $err_mess.__LINE__);
        $fetchBasket = $CCrmInvoice->GetProductRows($request_ajax['id']);
        $x=count($fetchBasket);
        $i=0;
                foreach($fetchBasket as $dbBasket){
                    $searchFlag=0;
                    $vat=$dbBasket["VAT_RATE"]*100;
                    $vat_price=$dbBasket["VAT_RATE"]*$summ_not_vac;
                    $summ_not_vac=$dbBasket["PRICE"]*$dbBasket["QUANTITY"];
                    $sum_vac=$summ_not_vac+$summ_not_vac*$dbBasket["VAT_RATE"];
                    $arrInvoice=$DB->Query('SELECT 1c_invoice.`id` FROM 1c_invoice WHERE 1c_invoice.`gen_id`='.$request_ajax['id'].'')->Fetch();
                    $arrProduct=$DB->Query('SELECT 1c_product.`id` FROM 1c_product WHERE 1c_product.`gen_id`='.$dbBasket["PRODUCT_ID"].'')->Fetch();  
                    $arrInvoiceSum=$DB->Query('SELECT * FROM 1c_invoice_sum WHERE 1c_invoice_sum.`1c_invoice_id`='.$arrInvoice['id'].'');
                    $arrayMeasure=$DB->Query('SELECT 1c_id FROM 1c_package_type WHERE 1c_package_type.`code_kei`='.$dbBasket["MEASURE_CODE"].'')->Fetch();
                    $vat_price=$dbBasket["VAT_RATE"]*$summ_not_vac;
                     $arFields = [
								"basket_id" => "'".$arrInvoice['id']."'",
                                "1c_invoice_id" => "'".$arrInvoice['id']."'",
                                "1c_product_id" => "'".$arrProduct['id']."'",
                                "product_tn" => "'".$dbBasket["NAME"]."'",
                                "product_mp" => "'".$dbBasket["QUANTITY"]."'",
                                "roz_unit" => "'".$arrayMeasure['1c_id']."'",
                                "discont" => "'".$dbBasket["DISCOUNT_PRICE"]."'",
                                "price" => "'".$dbBasket["PRICE"]."'",
                                "sum" => "'".$summ_not_vac."'",
                                "percentage_vac" => "'".$vat."'",
                                "vac" => "'".$vat_price."'",
                                "total" => "'".$sum_vac."'",
                                "garanty" => "'".$arDopFilds['garanty']."'",
                                "cargo_mass" => "'".$arDopFilds['garanty']."'",
                                "bx_flag" => '1',
                                "1c_flag" => '0',
                                ];
					$z=0;
                     while($arrProductID=$arrInvoiceSum->Fetch()){
							if($i===$z){   
									$DB->Update("1c_invoice_sum", $arFields,"WHERE id='".$arrProductID['id']."'",$err_mess.__LINE__);
							}
						 $z++;
						 if($z>$x){
							$DB->Query('DELETE FROM 1c_invoice_sum WHERE id ='.$arrProductID['id']);
						 }
						}
						$i++;  
						if($i>$z){
						$DB->Insert("1c_invoice_sum", $arFields,$err_mess.__LINE__);
						}
					}
    }
    elseif($request_ajax['save']=='add'){
       // z($arFields);
        $ID=$DB->Insert("1c_invoice", $arFields,$err_mess.__LINE__);
        $arFields=[];
         $arFields = ["gen_id"=>"'".$request_ajax['id']."'"];
        $DB->Update("1c_invoice", $arFields, "WHERE `id`=".$ID, $err_mess.__LINE__);
        $fetchBasket = $CCrmInvoice->GetProductRows($request_ajax['id']);
               foreach($fetchBasket as $dbBasket){
                    $vat=$dbBasket["VAT_RATE"]*100;
                    $summ_not_vac=$dbBasket["PRICE"]*$dbBasket["QUANTITY"];
                    $sum_vac=$summ_not_vac+$summ_not_vac*$dbBasket["VAT_RATE"];
                    $arrInvoice=$DB->Query('SELECT 1c_invoice.`id` FROM 1c_invoice WHERE 1c_invoice.`gen_id`='.$request_ajax['id'].'')->Fetch();
                    $arrProduct=$DB->Query('SELECT 1c_product.`id` FROM 1c_product WHERE 1c_product.`gen_id`='.$dbBasket["PRODUCT_ID"].'')->Fetch();
                    $arrayMeasure=$DB->Query('SELECT 1c_id FROM 1c_package_type WHERE 1c_package_type.`code_kei`='.$dbBasket["MEASURE_CODE"].'')->Fetch();
                    $vat_price=$dbBasket["VAT_RATE"]*$summ_not_vac;
                     $arFields = [
                                       // "1c_invoice_id" => "'".$arrProductID['id']."'",
                                "1c_invoice_id" => "'".$arrInvoice['id']."'",
                                "1c_product_id" => "'".$arrProduct['id']."'",
                                "product_tn" => "'".$dbBasket["NAME"]."'",
                                "product_mp" => "'".$dbBasket["QUANTITY"]."'",
                                "roz_unit" => "'".$arrayMeasure['1c_id']."'",
                                "discont" => "'".$dbBasket["DISCOUNT_PRICE"]."'",
                                "price" => "'".$dbBasket["PRICE"]."'",
                                "sum" => "'".$summ_not_vac."'",
                                "percentage_vac" => "'".$vat."'",
                                "vac" => "'".$vat_price."'",
                                "total" => "'".$sum_vac."'",
                                "garanty" => "'".$arDopFilds['garanty']."'",
                                "cargo_mass" => "'".$arDopFilds['garanty']."'",
                                "bx_flag" => '1',
                                "1c_flag" => '0',
                                ];
                     $arFields['basket_id']=$dbBasket['ID'];
                     $product_invoice_id=$DB->Insert("1c_invoice_sum", $arFields,$err_mess.__LINE__);
                     $arr_invoice_product=$DB->Query('SELECT 1c_invoice.`arr_invoice_product` FROM 1c_invoice WHERE 1c_invoice.`id`='.$ID.'')->Fetch();
                     if(empty($arr_invoice_product['arr_invoice_product'])){
                        $product_invoice_id=json_encode([$product_invoice_id]);
                         $arFields = [
                                 "arr_invoice_product" => "'".$product_invoice_id."'",
                        ];
                         
                        $DB->Update("1c_invoice", $arFields, "WHERE `id`=".$ID, $err_mess.__LINE__);
                     }
                     else{
                        $arr_invoice=json_decode($arr_invoice_product['arr_invoice_product'],true);
                        is_array($arr_invoice) ? $arr_invoice[]=$product_invoice_id : $arr_invoice=[$arr_invoice,$product_invoice_id];
                        $arr_invoice_product=json_encode($arr_invoice);
                        $arFields = [
                                 "arr_invoice_product" => "'".$arr_invoice_product."'",
                        ];
                        $DB->Update("1c_invoice", $arFields, "WHERE `id`=".$ID, $err_mess.__LINE__);
                     }
                }
    }
    elseif($request_ajax['save']=='del'){
         $deldb_invoice='UPDATE 1c_invoice SET `del_flag`=1 WHERE 1c_invoice.`gen_id` = '.$request_ajax['id'];  
        $DB->Query($deldb_invoice);
    }
}

?>
