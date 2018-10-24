<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule('lists');
/*$com=new CCrmCompany(false);
$comBD=$com->GetListEx();
while($comBDs=$comBD->Fetch()){
$com->Delete($comBDs['ID']);
file_put_contents("l.txt", "\n ".$comBDs['ID']."\n",FILE_APPEND);
}*/
$bs = new CIBlockSection;
global $DB;
$res = $DB->Query("SELECT 1c_product.*
                   FROM 1c_product
                   WHERE 1c_product.`bx_flag`=1");

while($ar = $res->Fetch())
{
     $pathList=$bs->GetList([],["IBLOCK_ID" => $catalog_id,"NAME" =>$ar['1c_id']])->Fetch();
     
   // z($pathList);
    $Fields = [
                      "IBLOCK_SECTION_ID" =>$pathList['IBLOCK_SECTION_ID'],
                      "NAME" => $ar['name'],
                      "DESCRIPTION" => strtoupper($ar['name'])
                  ];
      //z($Fields);
      //z($pathList);
     // z($pathList['ID']);
 $bs->Update((int)$pathList['ID'],$Fields);
  //$bs->Update(82,["NAME" => '3M (коврики+противоскольз.ленты)']);
   $arFields = ["gen_id"=>"'".$pathList['ID']."'","bx_flag"=>'1'];
       $DB->Update("1c_product_path", $arFields, "WHERE `id`=".$ar['id']."", $err_mess.__LINE__);
}
/*$catalog_id=\Bitrix\Main\Config\Option::get("servit_1c", "catalog_id");
$bs = new CIBlockSection;
$iblock_id=34;
$field_id='PROPERTY_137';
$label='Номер договора';
$sort=500;
//$arrBD=$list->GetFields();
if (CModule::IncludeModule('lists')) { 
$iblock=new CIBlock(false);
$el=new CIBlockElement(false);
 $arFields=
 [ 
        'NAME' => 'Новое поле',//Название поля
        'IS_REQUIRED' => 'N',//Обязательное ли
        'MULTIPLE' => 'N',//Множественное
        'TYPE' => 'S:ECrm',//L-список,S -строка,S:ECrm - привязка к элементам CRM, N -число, S:Date -дата,S:DateTime -дата время,S:HTML-редактор,NAME название,SORT сортировака,F файл,G -привязка к разделам,E -привязка к элементам,E:EList - привязка к элементам в виде списка,N:Sequence --счетчик,S:map_yandex --Привязка к яндекс карте,S:employee - Привязка к сотруднику,S:Money - деньги,S:DiskFile - файл диск.
        'CODE' => 'NOVOE_POLE',//Код поля латинские
        'SORT' => '500',//Сортировка
        'SETTINGS' => 
        [
            'SHOW_ADD_FORM' => 'Y',//Показываеть в CRM форме
          'SHOW_EDIT_FORM' => 'Y', //Показывать в редактировании формы
          'ADD_READ_ONLY_FIELD' => 'N',//Только для чтения (форма добавления)
          'EDIT_READ_ONLY_FIELD' => 'N',//Только для чтения (форма редактирования)
          'SHOW_FIELD_PREVIEW' => 'N',//Показать поле при формировании ссылки на элемент списка
        ]
];
if($arFields['TYPE']=='L'){
    $arFields['LIST']=[
          'n0' =>['SORT' => '10','VALUE' => 'dsds',],//n-обязательный 
          'n1' =>['SORT' => '20','VALUE' => 'dsdsd',],
        ];
    $arFields['LIST_TEXT_VALUES']='';
    $arFields['LIST_DEF']=[0 => '',];
}
if($arFields['TYPE']=='S'){
    $arFields += [
        'DEFAULT_VALUE' => '',
        'ROW_COUNT' => '3',
        'COL_COUNT' => '3',
    ]; 
}
if($arFields['TYPE']=='S:ECrm'){
    $arFields += [
        'DEFAULT_VALUE' => '',
  'USER_TYPE_SETTINGS' => 
  [
    'COMPANY' => 'Y',//Привязка к компании
    'VISIBLE' => 'Y',
  ],
    ]; 
}
if($arFields['TYPE']=='S:HTML'){
    $arFields += [
        'DEFAULT_VALUE' => ['TYPE' => 'html','TEXT' => '', ],
        'USER_TYPE_SETTINGS' => ['height' => '200',],
    ]; 
}
//$lfild=new ($iblock_id, $field_id, $label, $sort);
$contractDB=$DB->Query("SELECT 1c_contract_invoice.`id`,1c_contract_invoice.`gen_id`,1c_contract_invoice.`bx_flag`,1c_contract_invoice.`1c_flag`,
					   contract_number,document_date,contract_type_id,contract_kind_id,client_id,comment,subdivision_id,
					   invoice_currency_id ,1c_invoice_currency.tiny_name AS invoice_currency_id,1с_contract_type.name AS contract_type_id,
					   1c_contract_kind.name AS contract_kind_id,con.name AS client_id,1c_contragent.name AS subdivision_id
					   FROM 1c_contract_invoice
					   INNER JOIN 1c_invoice_currency  ON 1c_contract_invoice.invoice_currency_id=1c_invoice_currency.id
					   INNER JOIN 1с_contract_type ON 1c_contract_invoice.contract_type_id=1с_contract_type.id
					   INNER JOIN 1c_contract_kind ON 1c_contract_invoice.contract_kind_id=1c_contract_kind.id
					   INNER JOIN 1c_contragent AS con ON 1c_contract_invoice.client_id=con.id
					   INNER JOIN 1c_contragent  ON 1c_contract_invoice.subdivision_id=1c_contragent.id  
					   WHERE 1c_contract_invoice.bx_flag=0");//1c_contract_invoice.contract_type_id AS contract_type_id
while($contractFH=$contractDB->Fetch()){
	z($contractFH);
	$date=strtotime($contractFH['document_date']);
	$DATA_PODPISANIYA_DOKUMENTA=date("j.n.Y",$date);
	z($DATA_PODPISANIYA_DOKUMENTA);
	$arLoadProductArray = [
			"NAME" =>$contractFH['contract_number'],
			"IBLOCK_ID"      => $iblock_id,
			"CREATED_BY"     => 1,
			"ACTIVE"         => "Y"
        ];        
        //create new element
        $PRODUCT_ID=36911;
        if(true
           //$PRODUCT_ID = $el->Add($arLoadProductArray, true)
           ) {
           echo 'New ID: '.$PRODUCT_ID;
        } else {
           echo 'Error: '.$el->LAST_ERROR;
        }
		//$opa=$el->GetList([],["ID"=>36911])->Fetch();
		$list=new CList($iblock_id);
		$arUpdateProductArray =[
									"DATA_PODPISANIYA_DOKUMENTA"  => $DATA_PODPISANIYA_DOKUMENTA,
									"VALYUTA_NAKLADNOY"   => $contractFH['invoice_currency_id'],
									"TIP_DOGOVORA_"   => $contractFH['contract_type_id'],
									"VID_DOGOVORA"   => $contractFH['contract_kind_id'],
									"KLIENT"   => $contractFH['client_id'],
									"KOMMENTARIY_"   => $contractFH['comment'],
									"PODRAZDELENIE"   => $contractFH['subdivision_id'],
								];
		
		$el->SetPropertyValuesEx($PRODUCT_ID,$iblock_id,$arUpdateProductArray);
		$GetPropertyBD=$el->GetProperty($iblock_id,$PRODUCT_ID);
		while($GetProperty=$GetPropertyBD->Fetch()){
			$propValue=CIBlockElement::GetProperty($iblock_id, $PRODUCT_ID, array("sort" => "asc"), Array("CODE"=>$GetProperty["CODE"]))->Fetch();
			// z($propValue);
		}
			//$bbbb=$list->AddField($arFields);//Добавление элемента.
			//$arFields=$lfild->GetArray();
			//$lfildadd=$lfild->Update($arFields);
			//$arrBD=$iblock->GetArrayByID(34, "FIELDS");
			//$listay=$list->GetAllTypes();
				
			   // z($list);
		}
}

*/

/*$ar['product_path_id']='zz/bb';
$path_arr=explode('/',$ar['product_path_id']);
$resPath='';
if(strlen($path_arr[0])>0){  
    foreach($path_arr as $val){
        $pathList=$bs->GetList([],["IBLOCK_ID" => $catalog_id,"NAME" => $val,"SECTION_ID" =>$resPath])->Fetch();
        z($pathList);
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
}*/
//z($resPath);
/*global $DB;
\Bitrix\Main\Loader::includeModule('lists');
$ID=18;
$fetchBasket = CSaleBasket::GetList(
               array("DATE_INSERT" => "ASC", "NAME" => "ASC"),
               array("ORDER_ID" => 18)
           );

while ($dbBasket=$fetchBasket->GetNext()){
    $vat=$dbBasket["VAT_RATE"]*100;
    $summ_not_vac=$dbBasket["PRICE"]*$dbBasket["QUANTITY"];
    $sum_vac=$summ_not_vac+$summ_not_vac*$dbBasket["VAT_RATE"];
   $product_table_id=$DB->Query($dbBasket['PRODUCT_ID']);
     $arFields = [
        "1c_invoice_id"=>"'".$ID."'",
         "1c_product_id"=>"'".$ID."'",
        "product_tn" => "'".$dbBasket["NAME"]."'",
        "product_mp" => "'".$dbBasket["QUANTITY"]."'",
        "unit" => "'".$dbBasket["MEASURE_NAME"]."'",
        "discont" => "'".$dbBasket["DISCOUNT_PRICE"]."'",
        "price_not_vac" => "'".$dbBasket["PRICE"]."'",
        "summ_not_vac" => "'".$summ_not_vac."'",
        "vac" => "'".$vat."'",
        "sum" => "'".$sum_vac."'",
        "b" => "'".$arDopFilds['B']."'",
        "otcl" => 'NULL',
        "plan" => "'".$arDopFilds['PLAN']."'",
        "garanty" => "'".$arDopFilds['WARRANTY']."'",
     ];
    //z($dbBasket);
    z($dbBasket);
    //$DB->Insert("1c_invoice", $arFields,$err_mess.__LINE__);
}
*/
?>