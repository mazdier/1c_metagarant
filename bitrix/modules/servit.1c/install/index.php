<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);
class servit_1c extends CModule
{
	public $MODULE_ID;
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_GROUP_RIGHTS;
	public $PARTNER_NAME;
	public $PARTNER_URI;
	public $SHOW_SUPER_ADMIN_GROUP_RIGHTS;
	//var $exclusionAdminFiles;
	public function __construct()
	{
		$arModuleVersion=array();
		include(__DIR__."/version.php");
		$this->MODULE_ID = 'servit.1c';
		$this->MODULE_VERSION =$arModuleVersion['VERSION'] ;
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = "SERVIT_1C";
		$this->MODULE_DESCRIPTION = 'Модуль интеграции 1с';
		$this->MODULE_GROUP_RIGHTS = 'Y';
		$this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
		$this->PARTNER_NAME = 'SERVIT';
		$this->PARTNER_URI = "http://nfr.servit.by";
	}
	function isVersionD7()
	{
		return CheckVersion( \Bitrix\Main\ModuleManager::getVersion('main'),'14.00.00');
	}
	function InstallFiles()
	{
		if ($this->isVersionD7()) {
			CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/servit.1с/install/components",
				$_SERVER["DOCUMENT_ROOT"] . "/include2", true, true);
			return true;
		}
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx("/include2");;
		return true;
	}
	public function doInstall()
	{

		ModuleManager::registerModule($this->MODULE_ID);
		$this->InstallFiles();
	}

	public function doUninstall()
	{
		ModuleManager::unregisterModule($this->MODULE_ID);
		$this->UnInstallFiles();

	}


}
?>