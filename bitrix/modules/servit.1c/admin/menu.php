<?
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight('conversion') == 'D')
{
	return false;
}
else
{
	$menu = array(
		array(
			'parent_menu' => 'global_menu_settings',
			'sort' => 100,
			'text' => 'serviceit 1c',
			'title' => 'Интеграция 1с',
			'icon' => 'excel_menu_icon',
			'page_icon' => 'excel_menu_icon',
			'items_id' => 'menu_excel',
			'url' => 'servit_1c.php',
			'module_id' => '1c'
//			'items' => array(
//				array(
//					'text' => Loc::getMessage('CONVERSION_MENU_SUMMARY_TEXT'),
//					'title' => Loc::getMessage('CONVERSION_MENU_SUMMARY_TEXT'),
//					'url' => 'conversion_summary.php?lang='.LANGUAGE_ID,
//				),
//			),
		),
	);

	return $menu;
}
?>