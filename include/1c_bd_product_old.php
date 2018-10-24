<?
/*
Добаление в битрикс из БД. Страны, валюты, единицы измерения,товары, склады,каталоги для товаров, доп поля для счетов.
*/
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

?>
<?
class Timer
{private static $start = .0;static function start(){
   self::$start = microtime(true);}static function finish(){return print_r(microtime(true) - self::$start);}
}
?>
<?

//for($i=0;$i<5;$i++){
//echo "начало";
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
  // echo "<pre>";
 
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
         /*  $z=CCurrency::GetList();
            while($BDres=$z->Fetch()){
             print_r($BDres);
           }*/
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
 } 
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
//---------------собираем инфу с таблици товаров---------------

 $res = $DB->Query("SELECT 1c_product.*
                   FROM 1c_product
                   
                   WHERE 1c_product.`bx_flag`=0");//INNER JOIN 1c_type ON (1c_product.`type_id`= 1c_type.`id`)
while($ar = $res->Fetch())
{
  // print_r($ar);
  //формирование путей для каталога.
   if($ar["bx_flag"] == 0){
      //z($ar['product_path_id']);
      $path_arr=explode('/',$ar['product_path_id']);
      $resPath=NULL;
      if(strlen($path_arr[0])>0){  
          foreach($path_arr as $val){
              $pathList=$bs->GetList([],["IBLOCK_ID" => $catalog_id,"NAME" => $val,"SECTION_ID" =>$resPath])->Fetch();
              //z($pathList);
              if(empty($pathList)){
                  $arFields = [
                      "ACTIVE" => 'Y',
                      "IBLOCK_SECTION_ID" =>$resPath,
                      "IBLOCK_ID" => $catalog_id,
                      "NAME" => $val,
                      "SORT" => 500,
                      "DESCRIPTION" => strtoupper($val),
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

 //адекватные имена для каталога.
 $result_path = $DB->Query("SELECT `1c_id`, `name`,`id`
                   FROM 1c_product_path
                   WHERE 1c_product_path.`bx_flag`=0");
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
        $DB->Update("1c_product_path", $arFields, "WHERE `id`=".$ar['id']."", $err_mess.__LINE__);
    }
}
 

     
      //добавляем gen_id в таблицу 1c_product
      $DB->Query("UPDATE 1c_product SET 1c_product.`gen_id`=".$id." ,`bx_flag`=1 WHERE 1c_product.`id`= ".$ar['id'] );
     // print_r($id);
      if ($id > 0)
      {
            //---------------добавляем ид товара---------------
               $storage_sumBD=bxFlag0('1c_storage_sum');
               while($storage_sumBD_rez=$storage_sumBD->Fetch()){
                 if($storage_sumBD_rez['product_gen_id']==0||$storage_sumBD_rez['product_gen_id']==null){
                    if($storage_sumBD_rez['product_1c_id']>0){
                       $req=$DB->Query('SELECT 1c_product.`gen_id` FROM 1c_product WHERE 1c_product.`1с_id`='.$storage_sumBD_rez['product_1c_id'].'')->Fetch();
                      // var_dump($req['gen_id']);
                       //$DB->Query("INSERT 1c_storage_sum(product_gen_id) VALUES(".(int)$req['gen_id'].") " );
                       $DB->Query("UPDATE 1c_storage_sum SET product_gen_id = ".(int)$req['gen_id']." WHERE 1c_storage_sum.`product_1c_id`= ".$storage_sumBD_rez['product_1c_id'] );
                    }  
                 }  
               }      
                 // создание товара 
           // $arProd=CCatalogProduct::GetByID(176);
           // print_r($arProd);
            $restovar = $DB->Query("SELECT `col`,`rez`
                   FROM 1c_storage_sum
                   WHERE 1c_storage_sum.`1c_product_id`=".$ar['1с_id'])->Fetch();
               $arUpdateProductArray =[
						"ENG_NAME"  => $ar['eng_name'],
						"ARTICUL1"   => $ar['art_1'],
						"ARTICUL2"   => $ar['art_2'],
						"VID_TOVARA"   => $ar['vid_product'],
						"WEIGHT"   => $ar['weight'],
						"VVOZA"   => $ar['country_purchase_id'],
						"PROISHOJDENIY"   => $ar['country_origin_id'],
						"PREYSKURANT"   => $ar['pricelist'],
						"TNVD"   => $ar['tnvd'],
                  'RESERV' => $restovar['rez'],
						'OSTATOK'=>$restovar['col']
						];
              /* $resUnit = $DB->Query("SELECT `gen_id`
                               FROM 1c_package_type
                               WHERE 1c_package_type.`id`=".$ar['units_id'])->Fetch();*/
               $fields = [
                              'ID' => $id,
                              'QUANTITY_TRACE' => \Bitrix\Catalog\ProductTable::STATUS_DEFAULT,
                              'CAN_BUY_ZERO' => \Bitrix\Catalog\ProductTable::STATUS_DEFAULT,
                              'WEIGHT' => $ar['weight'],
                              'MEASURE' => $ar['gen_id']//ID_единицы_измерения
                           ];
               $el->SetPropertyValuesEx($id,$catalog_id,$arUpdateProductArray);
               $result = CCatalogProduct::Add($fields);
               if (!$result)
               {
                  // добавление коэффициента единицы измерения товара
                  $arFields=[
                     'PRODUCT_ID' => $id,
                     'RATIO' => 1//коэффициент_единицы_измерения
                     ];
                  $result = \Bitrix\Catalog\MeasureRatioTable::add($arFields);
               }
            
            // добавление цены
            //$rezBd=$DB->Query('SELECT * FROM 1c_storage_sum WHERE 1c_storage_sum.`1c_product_id`='.$id)->Fetch();
				$rezBd=$DB->Query('SELECT * FROM 1c_product WHERE 1c_product.`id`='.$id)->Fetch();
				//print_r($rezBd['selling_price_without_nds']);
                     $arFields=[
                        'PRODUCT_ID' => $id,
                        'CATALOG_GROUP_ID' => 1,//ID_типа_цены
                        'PRICE' => $rezBd['selling_price_without_nds'],
                        'CURRENCY' => $ar["currency_id"]
                     ];
                     //print_r($rezBd);
                  $bb=CPrice::GetList([],['PRODUCT_ID'=>$rezBd['product_gen_id']])->Fetch();
                    // print_r($bb);  
                     if($bb) {
                           CPrice::Update($bb["ID"], $arFields);
                        } 
                     else  CPrice::Add($arFields);
              // print_r($rezBd);   
      }
      //print_r($ar);//----------------добавляем товары на склад------------------
      $storage_sumBD=bxFlag0('1c_storage_sum');
      while($ar_storage_sumBD=$storage_sumBD->Fetch()){
          if($id>0&&$ar_storage_sumBD['product_gen_id']==$id){
            //print_r($ar_storage_sumBD);
               $arFields = [
                  "PRODUCT_ID" => $id,
                  "STORE_ID" => $ar_storage_sumBD['storage_bx_id'],
                  "AMOUNT" => $ar_storage_sumBD['col'],
                 ];
                     $IDcat = CCatalogStoreProduct::UpdateFromForm($arFields);
            $z=CCatalogStoreProduct::GetList([],["STORE_ID" => $ar_storage_sumBD['storage_bx_id'],"PRODUCT_ID"=>$id])->Fetch();
           // print_r($z);
           $DB->Query("UPDATE 1c_storage_sum SET bx_flag = 1  WHERE 1c_storage_sum.`id`= ".$ar_storage_sumBD['id'] );
          }
      }
   }
}
$arFields = array(
        "MESSAGE_TYPE" => "S", # P - private chat, G - group chat, S - notification
        "TO_USER_ID" => 1,
        "FROM_USER_ID" => 1,
        "MESSAGE" => 'OPA',
            "AUTHOR_ID" => 1,

        "EMAIL_TEMPLATE" => "some",
        "NOTIFY_TYPE" => 4,  # 1 - confirm, 2 - notify single from, 4 - notify single
            //"NOTIFY_MODULE" => "main", # module id sender (ex: xmpp, main, etc)
            //"NOTIFY_EVENT" => "IM_GROUP_INVITE", # module event id for search (ex, IM_GROUP_INVITE)
        //"NOTIFY_TITLE" => "title to send email", # notify title to send email
        );
        
        CModule::IncludeModule('im');
        //CIMMessenger::Add($arFields);
/*   $fields = [
            'IBLOCK_ID' => 10,
            'NAME' => $arFields['NAME'],
            'ACTIVE' => "Y",
            'SEARCHABLE_CONTENT' => $arFields['NAME'],
            'CREATED_BY' => '1',
            'MODIFIED_BY' => '1',
            'DATE_CREATE' => $arFields['DATE_CREATE'],
            'CODE' => $arFields['ID'],
            'PROPERTY_VALUES' => $PROP
      ];
    $arr=$el->GetByID(16038)->Fetch();*/

/*$itemsSection = GetIBlockSectionList($catalog_id);//список папок католога товаров
while($arItem = $itemsSection->GetNext())
      {    
          print_r($arItem);
      }*/
 
                 //   print_r($arr);
     /* if ($PRODUCT_ID = $el->Add($fields)) {
         echo 'Добавлен элемент, ID: ' . $PRODUCT_ID;
   } else {
      echo "Error[" . $PRODUCT_ID . "]: " . $el->LAST_ERROR . '<br />'; 
   }*/
}
//Timer::finish() . ' сек.';
?>
<? //require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
//}//корневой for
//адекватные имена для каталога.

?>




