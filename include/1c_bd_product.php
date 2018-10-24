<?
/*
Добаление в битрикс из БД. Страны, валюты, единицы измерения,товары, склады,каталоги для товаров, доп поля для счетов.
*/
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Sale, 
	Bitrix\Main\Diag\Debug,
	Bitrix\Main\Application;
 \Bitrix\Main\Loader::IncludeModule("iblock");
 \Bitrix\Main\Loader::IncludeModule("crm");


global $DB;
global $USER;
$catalog_id=\Bitrix\Main\Config\Option::get("servit_1c", "catalog_id");
$bs = new CIBlockSection;
$el = new CIBlockElement;
$propertycib=new CIBlockPropertyEnum;//свойства инфоблока товаров, списки

$IDproperty_1c_type=120;//тип -свойство инфоблока товаров
$IDproperty_1c_type_product=121;//тип товара, свойство инфоблока товаров
if (\Bitrix\Main\Loader::includeModule('catalog'))
   {     
   \Bitrix\Main\Loader::includeModule('sale');
   \Bitrix\Main\Loader::includeModule('currency');
   
$NDS=new CCatalogVat;
 //  echo "<pre>";
 
   function bxFlag0($tableName){
      global $DB;
      $dbrez=$DB->Query("SELECT ".$tableName.".* FROM ".$tableName." WHERE ".$tableName.".`bx_flag`=0");
      return $dbrez;
   }
   function bxFlag1($tableName,$genID,$whereName,$whereResName){
      global $DB;
      $dbrez=$DB->Query("UPDATE ".$tableName." SET gen_id = ".$genID.",bx_flag = 1 WHERE ".$tableName.".`".$whereName."`= '".$whereResName."'" );
      return $dbrez;
   }
   function addToBxList($IDproperty,$tableName){
      global $DB;
      $type_1c_BD=bxFlag0($tableName);
      while($arrtype_1c_BD=$type_1c_BD->Fetch()){
        if($arrtype_1c_BD){
            $type_gen_ID = (int)$GLOBALS['propertycib']->Add(['PROPERTY_ID'=>$IDproperty, 'VALUE'=>$arrtype_1c_BD['name']]);
            bxFlag1($tableName,$type_gen_ID,'name',$arrtype_1c_BD['name']);
        }
      } 
   }
   /*   $z=$GLOBALS['propertycib']->GetList([],["PROPERTY_ID"=>$IDproperty]);
     while($arrProp=$z->Fetch()){  
       print_r($arrProp);
       $GLOBALS['propertycib']->Delete($arrProp['ID']);
     }*/
 
   
  //---------------собираем тип---------------
  
  addToBxList($IDproperty_1c_type,'1c_type');
  
  //---------------собираем тип товара---------------
  
  addToBxList($IDproperty_1c_type_product,'1c_type_product');
  
  //---------------Ставки НДС---------------
  
   $rateBD=bxFlag0('1c_rate');
   while($rateBDres=$rateBD->Fetch()){
      $ndsID=(int)$NDS->Add(['ACTIVE'=>'Y','NAME'=>$rateBDres['name'],'RATE'=>$rateBDres['rate']]);
      bxFlag1('1c_rate',$ndsID,'name',$rateBDres['name']);
   }
  //$ndsList=$NDS->GetList();
  
  //---------------СТАТУС СЧЕТА---------------
/*  \Bitrix\Main\Loader::includeModule('crm');
$CCrmInvoice=new CCrmInvoice(false);
$reqDB=$DB->Query('SELECT `gen_id` FROM 1c_invoice WHERE 1c_invoice.bx_flag= 0 AND 1c_invoice.inv= 1')->Fetch();
$arFields=['STATUS_ID'=>'P'];
$CCrmInvoice->Update($reqDB['gen_id'], $arFields, $arOptions = array());
$arField = ["bx_flag" => '1',
                ];
$DB->Update("1c_invoice", $arField, "WHERE gen_id='".$reqDB['gen_id']."'", $err_mess.__LINE__);*/
  //---------------Сотрудники---------------
  
  $managerSQLResult = $DB->Query("
SELECT * FROM 1c_manager
WHERE bx_flag = 0
");
while($manager = $managerSQLResult->fetch()){
$arrName=explode(' ',$manager['name']);
$filter=['NAME'=>" $arrName[1] & $arrName[0]"];
$arUser=$USER->GetList(($by='last_name'), ($order='asc'), $filter)->Fetch();
$arFields = [
		"gen_id" => "'".$arUser['ID']."'",
		"first_name" => "'".$arrName["1"]."'",
		"last_name" => "'".$arrName["0"]."'",
		"middle_name" => "'".$arrName["2"]."'",
		"bx_flag" => '1',
		];
	$DB->Update("1c_manager", $arFields, "WHERE id='".$manager['id']."'", $err_mess.__LINE__);

}
  
  //---------------Страны---------------
  /*$z=CSaleLocation::GetList([],['COUNTRY_LID'=>'ru',"REGION_LID"=>"ru","CITY_LID"=>'ru']);
  while($zz=$z->Fetch()){
      print_r($zz);
      $DB->Query("INSERT 1c_country(gen_id,COUNTRY_ID,REGION_ID,CITY_ID,COUNTRY_NAME_ORIG,COUNTRY_SHORT_NAME,REGION_NAME_ORIG,CITY_NAME_ORIG,REGION_SHORT_NAME,CITY_SHORT_NAME,COUNTRY_NAME,REGION_NAME,CITY_NAME,1c_flag,bx_flag)
                 VALUES( ".$zz[ID].",".$zz[COUNTRY_ID].",".$zz[REGION_ID].",".$zz[CITY_ID].",'".$zz[COUNTRY_NAME_ORIG]."','".$zz[COUNTRY_SHORT_NAME]."','".$zz[REGION_NAME_ORIG]."','".$zz[CITY_NAME_ORIG]."','".$zz[REGION_SHORT_NAME]."','".$zz[CITY_SHORT_NAME]."','".$zz[COUNTRY_NAME]."','".$zz[REGION_NAME]."','".$zz[CITY_NAME]."',1,0)" );
  }*/
  $countryBD=bxFlag0('1c_country');
  while($countryBDres=$countryBD->Fetch()){
   $arFields = [
            "SORT" => 100,
            "COUNTRY_ID" => $countryBDres['COUNTRY_ID'],
            "WITHOUT_CITY" => "Y"
         ];        
         $arCountry = [
            "NAME" => $countryBDres['CITY_NAME'],
            "SHORT_NAME" => $countryBDres['COUNTRY_SHORT_NAME'],
            "ru" => [
               "LID" => "ru",
               "NAME" => $countryBDres['CITY_NAME'],
               "SHORT_NAME" => $countryBDres['COUNTRY_SHORT_NAME']
               ]
         ];        
       /*  $arFields["COUNTRY"] = $arCountry;
         $res = \Bitrix\Sale\Location\TypeTable::add(array(
                  'CODE' => 'COUNTRY',
                  'SORT' => '100', // уровень вложенности
                  'DISPLAY_SORT' => '200', // приоритет показа при поиске
                  'NAME' => array( // языковые названия
                      'ru' => array(
                          'NAME' => 'Город'
                      ),
                      'en' => array(
                          'NAME' => 'City'
                      ),
                  )
              ));
              if($res->isSuccess())
              {
                  print('Type added with ID = '.$res->getId());
              }*/
      /*  $arCity = [
            "NAME" => $countryBDres['CITY_NAME'],
            "SHORT_NAME" => $countryBDres['COUNTRY_SHORT_NAME'],
            "ru" => [
               "LID" => "ru",
               "NAME" => $countryBDres['CITY_NAME'],
               "SHORT_NAME" => $countryBDres['COUNTRY_SHORT_NAME']
               ]
         ];        
         $arFields["CITY"] = $arCity;*/
      // z($arFields);  
   if($countryBDres["gen_id"]==0||$countryBDres["gen_id"]==null){
      //z($countryBDres);
         $countryID = CSaleLocation::Add($arFields);
        // z($countryID);
         if(!isset($countryID)){
            bxFlag1('1c_country',$countryID,'id',$countryBDres['id']);
         }
    }
    else{
      CSaleLocation::Update($countryBDres["gen_id"], $arFields);
      bxFlag1('1c_country',$countryBDres["gen_id"],'gen_id',$countryBDres["gen_id"]);
    }
    //print_r($arFields);   
 }
      //---------------Валюта цены---------------
	$currencyBD=bxFlag0('1с_currency');
      while($currencyBDres=$currencyBD->Fetch()){ 
		  //print_r($currencyBDres);

		  $crmCurrency=CCrmCurrency::GetByID($currencyBDres['currency']);//$currencyBDres['currency']
		//print_r($crmCurrency);
		  $arFields=[
			  //'CURRENCY' => $currencyBDres['currency'],
			'AMOUNT_CNT' =>1,
			'AMOUNT' => $currencyBDres['AMOUNT'],
			'BASE'=>"N",
			'LANG'=>['ru'=>[
                     'FULL_NAME' => $currencyBDres['FULL_NAME'],
                     'DEC_POINT'=>'.', 
                     'FORMAT_STRING'=>'# '.$currencyBDres['FORMAT_STRING'], 
                     'THOUSANDS_VARIANT'=>'C',
                     'DECIMALS'=> 2,
                     'HIDE_ZERO'=> "Y",
					]],
		];
		 if(!empty($crmCurrency)){
			CCrmCurrency::Update($currencyBDres['currency'], $arFields);
		}
		  else {
			$arFields['CURRENCY']=$currencyBDres['currency'];
			$result=CCrmCurrency::Add($arFields);
		}
		$arFields = [
             "bx_flag"=>"'1'",
			];
	$DB->Update("1с_currency", $arFields, "WHERE currency='".$currencyBDres['currency']."'", $err_mess.__LINE__);
   }
  /*    $currencyBD=bxFlag0('1с_currency');
      while($currencyBDres=$currencyBD->Fetch()){ 
      //CCurrency::Delete('RUB');
      if(isset($currencyBDres['currency_number'])){ //если есть код валюты
         $currensy=strtoupper($currencyBDres['currency']);
         $arFields = [
            'NUMCODE' => $currencyBDres['currency_number'],
            "CURRENCY" => $currensy,
            'AMOUNT_CNT' => 1,
            'AMOUNT' => $currencyBDres['AMOUNT']
         ];
         CCurrency::Update($arFields);
            // Если запись существует, то обновляем, иначе добавляем новую
             $arFields = [
             "FORMAT_STRING" => "# ".$currencyBDres['FORMAT_STRING'], // символ # будет заменен реальной суммой при выводе
             "FULL_NAME" => $currencyBDres['FULL_NAME'],
             "DEC_POINT" => ".",
             "THOUSANDS_SEP" => "\xA0",  // неразрывный пробел
             "DECIMALS" => 2,
             "CURRENCY" => $currensy,
             "LID" => "ru"
            ];
         $db_result_lang = CCurrencyLang::GetByID($currensy, $arFields['LID']);
         //print_r($arFields);
         if ($db_result_lang){
            CCurrencyLang::Update($currensy, $arFields['LID'], $arFields);  
         }   
         else{
            CCurrencyLang::Add($arFields);            
      }
     
      }
      else{
         $currensy=strtoupper($currencyBDres['currency']);
         $arFields = [
            'NUMCODE' => $currencyBDres['currency_number'],
            "CURRENCY" => $currensy,
            'AMOUNT_CNT' => 1,
            'AMOUNT' => $currencyBDres['AMOUNT']
         ];
         CCurrency::Add($arFields);
            // Если запись существует, то обновляем, иначе добавляем новую
             $arFields = [
             "FORMAT_STRING" => "# ".$currencyBDres['FORMAT_STRING'], // символ # будет заменен реальной суммой при выводе
             "FULL_NAME" => $currencyBDres['FULL_NAME'],
             "DEC_POINT" => ".",
             "THOUSANDS_SEP" => "\xA0",  // неразрывный пробел
             "DECIMALS" => 2,
             "CURRENCY" => $currensy,
             "LID" => "ru"
            ];
         $db_result_lang = CCurrencyLang::GetByID($currensy, $arFields['LID']);
         //print_r($arFields);
         if ($db_result_lang){
            CCurrencyLang::Update($currensy, $arFields['LID'], $arFields);  
         }   
         else{
            CCurrencyLang::Add($arFields);            
      }
      }
 } */
  //---------------собираем единици измерений---------------
  
  //$arMeasureClassifier = CCatalogMeasureClassifier::getMeasureClassifier(); //каталог единиц измерения
  $measuresBD=bxFlag0('1c_package_type');

   while($measuresRes = $measuresBD->Fetch())
   {
      //z($measuresRes);
      if($measuresRes)
      {
         if(!$measuresRes['gen_id']||$measuresRes['gen_id']==0)//если нет ид битрикса
        {
         $measure=[
                   'CODE' => $measuresRes['code_kei'],
                   'MEASURE_TITLE' =>$measuresRes['full_name'],
                   'SYMBOL_RUS' => $measuresRes['symbol_rus'],
                   'SYMBOL_INTL' => $measuresRes['symbol_en'],
                   'SYMBOL_LETTER_INTL' => '',
                   'IS_DEFAULT' => 'N',
               ];
         $IDunit=CCatalogMeasure::add($measure);
         //z($IDunit);
         $DB->Query("UPDATE 1c_package_type SET gen_id = ".$IDunit." , bx_flag=1  WHERE 1c_package_type.`id`= '".$measuresRes['id']."'" );
              }                   
        }
     }
   //print_r($arMeasureClassifier);
   //-----------------Склады----------------
   $storageBD=bxFlag0('1c_storage');
   while($storageRes = $storageBD->Fetch()){
      //print_r($storageRes);
   $arFields = Array(
           "TITLE" => $storageRes['name'],//название склада;
           "ACTIVE" => "Y",//активность склада('Y' - активен, 'N' - не активен);
           "ADDRESS" => $storageRes['address'],//адрес склада;
           "DESCRIPTION" => $storageRes['description'],//описание склада;
           //IMAGE_ID/ => $fid,//ID картинки склада;
           "GPS_N" => $storageRes['GPS_N'],
           "GPS_S" => $storageRes['GPS_S'],
           "PHONE" => $storageRes['phone'],//телефон;
           "SCHEDULE" => $storageRes['shedule'],//расписание работы склада (максимальный размер поля 255 символов);
           //"XML_ID" => $XML_ID,//XML_ID склада для экспорта\импорта из 1С;
           'ISSUING_CENTER'=>'Y', //- пункт выдачи (Y/N);
            'SHIPPING_CENTER'=>'Y', //- для отгрузки (Y/N).
            "UF_STORE_LOAD"=>$storageRes['loading_place']
       );
   
   if($storageRes['gen_id']>0){
      CCatalogStore::Update($storageRes['gen_id'],$arFields);
      bxFlag1('1c_storage',$storageRes['gen_id'],'id',$storageRes['id']);
   }
    else {
      
      $ID = CCatalogStore::Add($arFields);
      bxFlag1('1c_storage',$ID,'id',$storageRes['id']);
    }
   }
 //-----------------Собираем остатки резервы----------
 function AddOstRez($catalog_id){
	 global $DB;
	 $el = new CIBlockElement;
	 $storage_sumBD=bxFlag0('1c_storage_sum');
	 while($storage_sumF = $storage_sumBD->Fetch()){ 
		if(!empty($storage_sumF['1c_product_id'])||$storage_sumF['1c_product_id']!=0){
			$product = $DB->Query("SELECT `gen_id`, `id` FROM 1c_product WHERE 1c_product.`id`=".$storage_sumF['1c_product_id'])->Fetch();
			if(!empty($product['gen_id'])||$product['gen_id']!=0){
				$arUpdateProductArray=[
					'RESERV' => $storage_sumF['rez'],
					'OSTATOK'=>$storage_sumF['col'],
				];
				$el->SetPropertyValuesEx($product['gen_id'],$catalog_id,$arUpdateProductArray);
				$arFields = ["bx_flag"=>"'1'"];
				$DB->Update("1c_storage_sum", $arFields, "WHERE `1c_product_id`=".$storage_sumF['1c_product_id'], $err_mess.__LINE__);
			}
		}
	 }
 }
 AddOstRez($catalog_id);
//---------------собираем инфу с таблици товаров---------------

 $res = $DB->Query("SELECT 1c_product.*
                   FROM 1c_product
                   
                   WHERE 1c_product.`bx_flag`=0");//INNER JOIN 1c_type ON (1c_product.`type_id`= 1c_type.`id`)
while($ar = $res->Fetch())
{
  //print_r($ar);
  //формирование путей для каталога.
   if($ar["bx_flag"] == 0){
      //z($ar['product_path_id']);
      $path_arr=explode('/',$ar['product_path_id']);
      $resPath=NULL;
      if(strlen($path_arr[0])>0){  
          foreach($path_arr as $val){
			$result_path = $DB->Query("SELECT `1c_id`, `name`,`id`
										   FROM 1c_product_path
										   WHERE 1c_product_path.`1c_id`=".$val)->Fetch();
              $pathList=$bs->GetList([],["IBLOCK_ID" => $catalog_id,"NAME" => $result_path['name'],"SECTION_ID" =>$resPath])->Fetch();
              //z($pathList);
              if(empty($pathList)){
                  $arFields = [
                      "ACTIVE" => 'Y',
                      "IBLOCK_SECTION_ID" =>$resPath,
                      "IBLOCK_ID" => $catalog_id,
                      "NAME" => $result_path['name'],
                      "SORT" => 500,
                      "DESCRIPTION" => strtoupper($result_path['name']),
                      "DESCRIPTION_TYPE" => 'text'
                  ];
                  $resPath = (int)$bs->Add($arFields);
                  //z($pathList);
              }
              else $resPath=$pathList['ID'];              
          }
      }  
      //$useStoreControl = (string)\Bitrix\Main\Config\Option::get('catalog', 'default_use_store_control') === 'Y';
     // print_r($resPath);
      $fields = [
         'IBLOCK_ID' =>$catalog_id ,//ID_торгового_каталога
         'NAME' => $ar['name'],
         'IBLOCK_SECTION_ID'=>$resPath
      ];
      //$opa=$el->GetByID(176)->Fetch();
      //fp($opa);
      //z($ar);
      if($ar["gen_id"]==0){
      $id = (int)$el->Add($fields);
      }
      else{
         $id =$ar["gen_id"];
         (int)$el->Update($id,$fields);
      }
      //INNER JOIN 1c_type ON (1c_product.`type_id`= 1c_type.`id`)


 

     
      //добавляем gen_id в таблицу 1c_product
      $DB->Query("UPDATE 1c_product SET 1c_product.`gen_id`=".$id." ,`bx_flag`=1 WHERE 1c_product.`id`= ".$ar['id'] );
     // print_r($id);
      if ($id > 0)
      {
            //---------------добавляем ид товара---------------
             /*  $storage_sumBD=bxFlag0('1c_storage_sum');
               while($storage_sumBD_rez=$storage_sumBD->Fetch()){
                 if($storage_sumBD_rez['product_gen_id']==0||$storage_sumBD_rez['product_gen_id']==null){
                    if($storage_sumBD_rez['product_1c_id']>0){
                       $req=$DB->Query('SELECT 1c_product.`gen_id` FROM 1c_product WHERE 1c_product.`1с_id`='.$storage_sumBD_rez['product_1c_id'].'')->Fetch();
                      // var_dump($req['gen_id']);
                       //$DB->Query("INSERT 1c_storage_sum(product_gen_id) VALUES(".(int)$req['gen_id'].") " );
                       $DB->Query("UPDATE 1c_storage_sum SET product_gen_id = ".(int)$req['gen_id']." WHERE 1c_storage_sum.`product_1c_id`= ".$storage_sumBD_rez['product_1c_id'] );
                    }  
                 }  
               }    */  
                 // создание товара 
           // $arProd=CCatalogProduct::GetByID(176);
           // print_r($arProd);
            $restovar = $DB->Query("SELECT `col`,`rez`
                   FROM 1c_storage_sum
                   WHERE 1c_storage_sum.`1c_product_id`=".$ar['id'])->Fetch();
			if($ar['product_retail_id']>0)
			$product_retail = $DB->Query("SELECT `name`
                   FROM 1c_product_retail
                   WHERE 1c_product_retail.`id`=".$ar['product_retail_id'])->Fetch();
			if($ar['units_id']>0)
			$units_id = $DB->Query("SELECT `symbol_rus`
                   FROM 1c_package_type
                   WHERE 1c_package_type.`id`=".$ar['units_id'])->Fetch();
			if($ar['units_retail_id']>0)
			$units_retail_id = $DB->Query("SELECT `symbol_rus`
                   FROM 1c_package_type
                   WHERE 1c_package_type.`id`=".$ar['units_retail_id'])->Fetch();
               $arUpdateProductArray =[
						"SHORT_NAME"  => $ar['short_name'],
						"MANUFACTURE_PRICE_1"   => $ar['Manufacture_price_1'],
						"MANUFACTURE_PRICE_2"   => $ar['Manufacture_price_2'],
						"PRODUCT_RETAIL"   => $product_retail['name'],
						"UNITS_OPT"   => $units_id['symbol_rus'],
						"UNITS_RETAIL"   => $units_retail_id['symbol_rus'],
						"SIZE"   => $ar['size'],
						"SPECIFIC_WEIGHT"   => $ar['specific_weight'],
						"SELLING_MINSK"   => $ar['selling'],
						'RESERV' => $restovar['rez'],
						'OSTATOK'=>$restovar['col'],
						'INTERNAL_CODE' => $ar['internal_code'],
						];
						//print_r($arUpdateProductArray);
				$el->SetPropertyValuesEx($id,$catalog_id,$arUpdateProductArray);
				$fields = [
								  'ID' => $id,
								  'QUANTITY_TRACE' => \Bitrix\Catalog\ProductTable::STATUS_DEFAULT,
								  'CAN_BUY_ZERO' => \Bitrix\Catalog\ProductTable::STATUS_DEFAULT,
								  'WEIGHT' => $ar['weight'],
							   ];
			if($ar['package_type_id']){
				  $resUnit = $DB->Query("SELECT `gen_id`
								   FROM 1c_package_type
								   WHERE 1c_package_type.`id`=".$ar['package_type_id'])->Fetch();
					$fields['MEASURE'] = $resUnit['gen_id'];//ID_единицы_измерения
			}	
					$result = CCatalogProduct::Add($fields);
				   if (!$result){
					  // добавление коэффициента единицы измерения товара
					  $arFields=[
						 'PRODUCT_ID' => $id,
						 'RATIO' => 1 //коэффициент_единицы_измерения
						 ];
					  $result = \Bitrix\Catalog\MeasureRatioTable::add($arFields);
				   }
				// добавление цены
				//$rezBd=$DB->Query('SELECT * FROM 1c_storage_sum WHERE 1c_storage_sum.`1c_product_id`='.$id)->Fetch();
					$rezBd=$DB->Query('SELECT * FROM 1c_product WHERE 1c_product.`gen_id`='.$id)->Fetch();
					
					if($ar['currency_id']>0)
				$currency_retail = $DB->Query("SELECT `currency`
					   FROM 1с_currency
					   WHERE 1с_currency.`id`=".$ar['currency_id'])->Fetch();
						 $arFields=[
							'PRODUCT_ID' => $rezBd['gen_id'],
							'CATALOG_GROUP_ID' => 1,//ID_типа_цены
							'PRICE' => $rezBd['selling_price_without_nds'],
							'CURRENCY' => $currency_retail["currency"]
						 ];
					  $bb=CPrice::GetList([],['PRODUCT_ID'=>$rezBd['gen_id']])->Fetch(); 
						 if($bb) {
							   CPrice::Update($bb["ID"], $arFields);
							} 
						 else $z = CPrice::Add($arFields);
			
      }
      //print_r($ar);//----------------добавляем товары на склад------------------
		

        /*  if($rezBd['id']&&$id>0){
			$StorageSumBd=$DB->Query('SELECT * FROM 1c_storage_sum WHERE 1c_storage_sum.`1c_product_id`='.$rezBd['id'])->Fetch();
            //print_r();
               $arFields = [
                  "PRODUCT_ID" => $id,
                  "STORE_ID" => $StorageSumBd['storage_1c_id'],
                  "AMOUNT" => $StorageSumBd['col'],
                 ];
                     $IDcat = CCatalogStoreProduct::UpdateFromForm($arFields);
            $z=CCatalogStoreProduct::GetList([],["STORE_ID" => $StorageSumBd['storage_1c_id'],"PRODUCT_ID"=>$id])->Fetch();
           // print_r($z);
           $DB->Query("UPDATE 1c_storage_sum SET bx_flag = 1  WHERE 1c_storage_sum.`id`= ".$StorageSumBd['id'] );
		   
          }*/
	
   }
}

}

?>