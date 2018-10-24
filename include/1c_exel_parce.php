<head>
	<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
</head>
<form action="" method="post" enctype="multipart/form-data">
    Выберите XLSX файл для загрузки:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Загрузить файл" name="submit">
</form>
<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$IblockID = 40;
$target_dir = $_SERVER['DOCUMENT_ROOT']."/upload/tmp/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
\Bitrix\Main\Loader::IncludeModule('crm');
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
	if($imageFileType != "xlsx") {
		echo "Принимаються только файлы формата XLSX. ";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo " Извините ваш файл не загружен";
	// if everything is ok, try to upload file
	} else {
		//print_r($_FILES["fileToUpload"]);
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "Файл ".$_FILES["fileToUpload"]["name"]. " был загружен.";
				\Bitrix\Main\Loader::IncludeModule('iblock');			
			require_once($_SERVER['DOCUMENT_ROOT']."/local/include/PHPExcel.php");
			echo '<pre>';
			
			$xls = PHPExcel_IOFactory::load($target_file);

			$xls->getProperties()->setCreator("Servit")
											 ->setLastModifiedBy("Servit")
											 ->setTitle("PHPExcel Document")
											 ->setSubject("PHPExcel Document")
											 ->setDescription("Document for PHPExcel, generated using PHP classes.")
											 ->setKeywords("Office PHPExcel php")
											 ->setCategory("Result file");
			// Устанавливаем индекс активного листа
			$xls->setActiveSheetIndex(0);
			// Получаем активный лист
			$sheet = $xls->getActiveSheet();
			$z=0;
			$indexRow=false;
			$nColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
			echo '<select>';
			
			for ($j = 0; $j < $nColumn; $j++) {
				$value = $sheet->getCellByColumnAndRow($j, '1')->getValue();
				
				$arrFields[$j]=$value;
				echo '<option>'.$value.'</option>';
			}
			echo '</select>';
			for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {  
				$nColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
				$ArrFilter=[];
				for ($j = 0; $j < $nColumn; $j++) {
					$value = $sheet->getCellByColumnAndRow($j, $i)->getValue();
					$pos = strpos($arrFields[$j], 'PROPERTY_');
					if ($pos === false) {
						$ArrFilter=array_merge($ArrFilter,[$arrFields[$j] => $value]);
					}
					else{
						preg_match('/PROPERTY_(\w+)/',$arrFields[$j],$matches);
						$kod = $matches[1];
						$flag = 0;
						//print_r([$kod => $value]);
						switch($kod){
							case 'GOROD':
								$ArrFilter = array_merge_recursive($ArrFilter,['PROPERTY_VALUES' => [$kod => ($value == 'Могилев' )?351:350]]);	
								$flag = 1;
							break;

						}
						if($flag == 0)
						$ArrFilter = array_merge_recursive($ArrFilter,['PROPERTY_VALUES' => [$kod => $value]]);	
						
					}
				
				}
				if($indexRow) $z++;
				$ArrFilter=array_merge($ArrFilter,['IBLOCK_ID' => $IblockID]);
				
				if($ArrFilter['NAME']){
					$arFields = $ArrFilter;
					$ArrFilter['PROPERTY_RAZMER'] = $ArrFilter['PROPERTY_VALUES']['RAZMER'] ;
					$ArrFilter['PROPERTY_GOROD'] = $ArrFilter['PROPERTY_VALUES']['GOROD'];
					unset($ArrFilter['PROPERTY_VALUES']);
					$arrElement = (new CIBlockElement)->GetList([],$ArrFilter)->Fetch();
					print_r($arFields);
					 if($arrElement){
						 (new CIBlockElement)->Update($arrElement['ID'],$arFields);
					 }
					else (new CIBlockElement)->Add($arFields);
					//break; 
				}
			}
		}
	}
}
?>