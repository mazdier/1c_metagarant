<?php
use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Iblock;
Loader::includeModule("iblock");
Loader::includeModule("crm");
$eventManager = \Bitrix\Main\EventManager::getInstance(); 
/*$eventManager->addEventHandlerCompatible('main', 'OnUserTypeBuildList', 
  array(
    'CrmListInvoice',
    'GetUserTypeDescription'
  )
);*/
$eventManager->addEventHandlerCompatible("iblock", "OnIBlockPropertyBuildList", 
array(
    'CrmListInvoice',
    'GetUserTypeDescription'
  ));
class CrmListInvoice extends \Bitrix\Main\UserField\TypeBase
{

  const USER_TYPE_ID = 'list_invoice';

	public static function getUserTypeDescription()
	{
		$className = __CLASS__;
		return array(
			'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_STRING,
			'USER_TYPE' => self::USER_TYPE_ID,
			'DESCRIPTION' => '1Счета crm для документооборота',
			'GetPublicEditHTML' => array($className, 'getPublicEditHTML'),
			'GetPublicEditHTMLMulty' => array($className, 'getPublicEditHTMLMulty'),
			'GetPublicViewHTML' => array($className, 'getPublicViewHTML'),
			'GetPublicViewHTMLMulty' => array($className, 'getPublicViewHTMLMulty'),
			'GetPropertyFieldHtml' => array($className, 'getPropertyFieldHtml'),
			'GetPropertyFieldHtmlMulty' => array($className, 'getPropertyFieldHtmlMulty'),
			'GetAdminListViewHTML' => array($className, 'getAdminListViewHTML'),
			'PrepareSettings' => array($className, 'prepareSettings'),
			'GetSettingsHTML' => array($className, 'getSettingsHTML'),
			'CheckFields' => array($className, 'checkFields'),
			'GetLength' => array($className, 'getLength'),
			'ConvertToDB' => array($className, 'convertToDB'),
			'ConvertFromDB' => array($className, 'convertFromDB'),
			'GetValuePrintable' => array($className, 'getValuePrintable'),
			'ConvertFromDB' => array($className, 'convertFromDB'),
			'ddGetValuePrintable' => array($className, 'getValuePrintable'),
		);
	}
	public static function ConvertToDB($arProperty, $value)
	{
		if (strlen($value["VALUE"])>0)
		return preg_replace("/[^a-z0-9]/i", '', $value);
	}

	public static function ConvertFromDB($arProperty, $value, $format = '')
	{
		if(strlen($value["VALUE"])>0)
		return preg_replace("/[^a-z0-9]/i", '', $value);
	}
	public static function getPublicEditHTMLMulty($property, $value, $controlSettings)
	{
		
		return  static::GetPublicEditHTML($property, $value, $controlSettings);
	}
	public static function GetPublicEditHTML($property, $value, $strHTMLControlName)
	{	
		global $APPLICATION;
		$dir=str_replace($_SERVER['DOCUMENT_ROOT'],'',__DIR__);
		
		$inputName=htmlspecialcharsbx($strHTMLControlName["VALUE"]);
		$SelectName=str_replace(['[',']'],['_'],$inputName);
		$s = '<input type="text" name="'.$inputName.'" size="25" style="display:none" value="'.htmlspecialcharsbx($value["VALUE"]).'" />';
		$APPLICATION->AddHeadString('<script src="'.$dir.'/ajax.js"></script>', true);
		if($value["VALUE"]){
			$arInvoice=CCrmInvoice::Getlist([],['ID'=>$value["VALUE"]],false,false,['ID','ORDER_TOPIC','ACCOUNT_NUMBER'])->Fetch();
			$AccountNumber = $arInvoice['ACCOUNT_NUMBER'];
			$selectData = $arInvoice['ORDER_TOPIC'];
			$InvoiceId = (int)$value["VALUE"];
		}
		else {$selectData = 'нет';$InvoiceId = 0;}
		ob_start();
		echo "<select id='$SelectName' ><option value='$InvoiceId'>$AccountNumber -- $selectData</option></select>";
		echo '<script>var UpdateR=function (){GetInvoiceForDeal("'.$inputName.'","'.$dir.'","'.$SelectName.'");};BX.addCustomEvent("onPopupClose", UpdateR ); </script>';
		echo '<script>document.addEventListener("DOMContentLoaded", function () {GetInvoiceForDeal("'.$inputName.'","'.$dir.'","'.$SelectName.'");document.getElementById("'.$SelectName.'").addEventListener("focus", function(){ GetInvoiceForDeal("'.$inputName.'","'.$dir.'","'.$SelectName.'")})})</script>';
		$s .= ob_get_contents();
		ob_end_clean();
		return  $s;
	}
	public static function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
	{
		if (strlen($value["VALUE"]) > 0)
		{
			$arInvoice=CCrmInvoice::Getlist([],['ID'=>$value["VALUE"]],false,false,['ID','ORDER_TOPIC','ACCOUNT_NUMBER'])->Fetch();
			$AccountNumber = $arInvoice['ACCOUNT_NUMBER'];
			$selectData = $arInvoice['ORDER_TOPIC'];
			$InvoiceId = (int)$value["VALUE"];
			$addresInvoice='/crm/invoice/show/'.$InvoiceId.'/';
			$show="<a href='".$addresInvoice."' target='_blank'>Счет № $AccountNumber -- $selectData</a>";
			return $show;
		}

		return '';
	}
	public static function GetPublicViewHTMLMulty($arProperty, $value, $strHTMLControlName)
	{
		return  static::GetPublicViewHTML($property, $value, $controlSettings);
	}
	public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
	{
		$arPropertyFields = array(
			"HIDE" => array("ROW_COUNT", "COL_COUNT"),
		);

		return '';
	}
	public static function GetPublicFilterHTML($arProperty, $value)
	{
		return  '';
	}
	public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
	{
		return  '';
	}
}
?>