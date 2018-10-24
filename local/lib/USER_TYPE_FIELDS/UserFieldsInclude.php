<?php
$dir=__DIR__;
//require_once '/home/bitrix/www/local/lib/USER_TYPE_FIELDS/TEST_FIELD/UserField.php';
require_once '/home/bitrix/www/local/lib/USER_TYPE_FIELDS/CRM_INVOICE_FROM_LISTS_USE_DEAL/UserField.php';
//Читает первую вложенность директорий, и подключает из них все файлы
/*if ($handle = opendir($dir)) {
    while (false !== ($file = readdir($handle))) { 
        if ($file != "." && $file != ".."&&is_dir($file)) {
			if ($handle2 = opendir($dir.'/'.$file)) {
				while (false !== ($file2 = readdir($handle2))) { 
					if ($file2 != "." && $file2 != ".."&&is_file($dir.'/'.$file."/".$file2)) {
						require_once $dir.'/'.$file."/".$file2;
						//echo $dir.'/'.$file."/".$file2.'<br>';
					}
				}
				closedir($handle2);
			}		
        } 
    }
    closedir($handle); 
}*/
?>