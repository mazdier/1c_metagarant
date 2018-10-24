<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__); 

try
{
	if (!Main\Loader::includeModule('iblock'))
		throw new Main\LoaderException(Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_IBLOCK_MODULE_NOT_INSTALLED'));
	
	$iblockTypes = \CIBlockParameters::GetIBlockTypes(Array("-" => " "));
	
	$iblocks = array(0 => " ");
	if (isset($arCurrentValues['IBLOCK_TYPE']) && strlen($arCurrentValues['IBLOCK_TYPE']))
	{
	    $filter = array(
	        'TYPE' => $arCurrentValues['IBLOCK_TYPE'],
	        'ACTIVE' => 'Y'
	    );
	    $rsIBlock = \CIBlock::GetList(array('SORT' => 'ASC'), $filter);
	    while ($arIBlock = $rsIBlock -> GetNext())
	    {
	        $iblocks[$arIBlock['ID']] = $arIBlock['NAME'];
	    }
	}
	
	$sortFields = array(
		'ID' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_ID'),
		'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_NAME'),
		'ACTIVE_FROM' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_ACTIVE_FROM'),
		'SORT' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_SORT')
	);
	
	$sortDirection = array(
		'ASC' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_ASC'),
		'DESC' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_DESC')
	);
	
	$arComponentParameters = array(
		'GROUPS' => array(
		),
		'PARAMETERS' => array(
			'IBLOCK_TYPE' => Array(
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_IBLOCK_TYPE'),
				'TYPE' => 'LIST',
				'VALUES' => $iblockTypes,
				'DEFAULT' => '',
				'REFRESH' => 'Y'
			),
			'IBLOCK_ID' => array(
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_IBLOCK_ID'),
				'TYPE' => 'LIST',
				'VALUES' => $iblocks
			),
			'SHOW_NAV' => array(
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SHOW_NAV'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N'
			),
			'COUNT' =>  array(
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_COUNT'),
				'TYPE' => 'STRING',
				'DEFAULT' => '0'
			),
			'SORT_FIELD1' => array(
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_FIELD1'),
				'TYPE' => 'LIST',
				'VALUES' => $sortFields
			),
			'SORT_DIRECTION1' => array(
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_DIRECTION1'),
				'TYPE' => 'LIST',
				'VALUES' => $sortDirection
			),
			'SORT_FIELD2' => array(
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_FIELD2'),
				'TYPE' => 'LIST',
				'VALUES' => $sortFields
			),
			'SORT_DIRECTION2' => array(
				'PARENT' => 'BASE',
				'NAME' => Loc::getMessage('SUPPORT_SECTION_LIST_PARAMETERS_SORT_DIRECTION2'),
				'TYPE' => 'LIST',
				'VALUES' => $sortDirection
			),
			'CACHE_TIME' => array(
				'DEFAULT' => 3600
			)
		)
	);
}
catch (Main\LoaderException $e)
{
	ShowError($e -> getMessage());
}
?>