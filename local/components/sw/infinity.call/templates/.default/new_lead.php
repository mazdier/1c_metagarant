<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule('crm');
global $APPLICATION;
$APPLICATION->SetAdditionalCSS("/bitrix/themes/.default/crm-entity-show.css");
CJSCore::Init(array("jquery"));
CJSCore::Init(array("popup"));
CUtil::InitJSCore(array('window'));
CCrmComponentHelper::RegisterScriptLink('/bitrix/js/crm/activity.js');
CCrmComponentHelper::RegisterScriptLink('/bitrix/js/crm/interface_grid.js');

?>
<script type="text/javascript">
	$(document).ready(function(){
		BX.ajax.get('<?=$templateFolder?>/ajax_convert_lead_form.php?ID=<?= $arResult["ID"] ?>&phone=<?= $_REQUEST["phone"] ?>&userid=<?= $_REQUEST["userid"] ?>&phoneid=<?= $_REQUEST["phoneid"] ?>', function(data) {
			$('#crm-lead-form').html(data);
			<?/*Clear last td html*/?>
			$('table.bx-edit-tabs tbody tr td:last').html('');
			<?/*Add hidden input with phone comment*/?>
			$('#form_CRM_LEAD_CONVERT').prepend('<input type="hidden" name="comment"/>');
		});
	});
</script>
<div class="gl_class">
    <? $id_lead = $arResult['ID']; ?>
	<div id="crm-lead-form" style="width: 70%; padding-top: 3%; padding-bottom: 1%;">
    </div>
	<? $APPLICATION->IncludeComponent(
		"sw:infinity.block",
		".default",
		array(
			"ENTITY_ID" => $arResult["ENTITY_ID"],
			"ELEMENT_ID" => $arResult["ID"],
			"INFINITY_ADDRESS" => $arParams["INFINITY_ADDRESS"],
			"INFINITY_USER_ID" => $arParams["INFINITY_USER_ID"],
			"INFINITY_CALL_ID" => $arParams["INFINITY_CALL_ID"]
		)
	); ?>
</div>
