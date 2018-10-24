<?
define('STOP_STATISTICS', true);
define('BX_SECURITY_SHOW_MESSAGE', true);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (file_exists(__DIR__."/../../BoProductRows.php")) {
	require_once(__DIR__."/../../BoProductRows.php");
}

define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC','Y');
define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);


function isHavePerm($userId)
{
	$perms = new CCrmPerms($userId);
	return !$perms->HavePerm('DEAL', BX_CRM_PERM_NONE, 'READ');
}


if (!CModule::IncludeModule('crm'))
{
	return;
}

global $USER;
$result = array(
	"SUCCESS" => "OK",
	"MESSAGE" => ""
);
if ($USER->IsAuthorized() && isHavePerm($USER->GetID()))
{

	$obBoProductRows = new BoProductRows();
	foreach($_REQUEST["DATA_ROWS"] as $arRow)
	{
		if (!$obBoProductRows->updateRow($arRow["BX_ROW_ID"], $arRow["DESIGN_PRICE"]))
		{
			$result = array(
				"SUCCESS" => "ERROR",
				"MESSAGE" => $obBoProductRows->LAST_ERROR
			);
		}
	}
	$obDeal = new CCrmDeal();
	$obDeal->Update($_REQUEST["DEAL_ID"], $_REQUEST["DEAL_UPD_PROPS"]);
}
else
{
	$result = array(
		"SUCCESS" => "ERROR",
		"MESSAGE" => "Access is denied"
	);
}

echo json_encode($result);
?>