<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["SECTION_ID"] = intval($arParams["SECTION_ID"]);
$arParams["SECTION_CODE"] = trim($arParams["SECTION_CODE"]);

$arParams["SECTION_URL"]=trim($arParams["SECTION_URL"]);

$arParams["TOP_DEPTH"] = intval($arParams["TOP_DEPTH"]);
if($arParams["TOP_DEPTH"] <= 0)
	$arParams["TOP_DEPTH"] = 2;
$arParams["COUNT_ELEMENTS"] = $arParams["COUNT_ELEMENTS"]!="N";
$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"]!="N"; //Turn on by default

$arResult["SECTIONS"]=array();

/*************************************************************************
			Work with cache
*************************************************************************/
if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))
{
	if(!\Bitrix\Main\Loader::includeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	$arFilter = array(
		"ACTIVE" => "Y",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"]
	);
	if (isset($arParams["PARENT_ID"]) && intval($arParams["PARENT_ID"]))
	{
		$arFilter["PROPERTY_PARENT_ID"] = $arParams["PARENT_ID"];

		$rsElements = CIBlockElement::GetList(array(), array("ID" => $arParams["PARENT_ID"]), FALSE, FALSE, array("ID", "ACTIVE", "NAME", "PROPERTY_HEAD", "DETAIL_PAGE_URL"));
		if ($arElement = $rsElements->GetNext())
		{
			$arResult["CUR_ELEMENT"] = $arElement;
		}
		unset($rsElements, $arElement);
	}
	else
	{
		$arFilter["PROPERTY_PARENT_ID"] = false;
	}

	$arSelect = array();
	$arSelect[] = "ID";
	$arSelect[] = "NAME";
	$arSelect[] = "IBLOCK_ID";
	$arSelect[] = "DETAIL_PAGE_URL";
	$arSelect[] = "PROPERTY_PARENT_ID";
	$arSelect[] = "PROPERTY_HEAD";

	//ORDER BY
	$arSort = array(
		"NAME"=>"ASC",
	);
	//EXECUTE
	$rsElements = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
	$rsElements->SetUrlTemplates($arParams["DETAIL_PAGE_URL"]);
	while($arElement = $rsElements->GetNext())
	{
		if ($arParams["SHOW_SECOND_LEVEL"] == "Y" && (!isset($arParams["PARENT_ID"]) || !intval($arParams["PARENT_ID"])))
		{
			$arFilter["PROPERTY_PARENT_ID"] = $arElement["ID"];
			$rsChElements = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
			$rsChElements->SetUrlTemplates($arParams["DETAIL_PAGE_URL"]);
			while($arChElement = $rsChElements->GetNext())
			{
				$arElement["CHILD_ELEMENTS"][] = $arChElement;
			}
		}

		$arResult["ITEMS"][] = $arElement;
	}

	$arResult["ITEMS_COUNT"] = count($arResult["ITEMS"]);

	$this->SetResultCacheKeys(array(
		"ITEMS_COUNT",
		"ITEMS",
	));

	$this->IncludeComponentTemplate();
}

if($arResult["ITEMS_COUNT"] > 0 || isset($arResult["ITEMS"]))
{
	if($arParams["ADD_SECTIONS_CHAIN"] && isset($arResult["SECTION"]) && is_array($arResult["SECTION"]["PATH"]))
	{
		foreach($arResult["SECTION"]["PATH"] as $arPath)
		{
			if (isset($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
				$APPLICATION->AddChainItem($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arPath["~SECTION_PAGE_URL"]);
			else
				$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
		}
	}
}
?>