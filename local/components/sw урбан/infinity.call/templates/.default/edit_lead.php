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

<? if($arResult["STATUS_ID"] != "JUNK"): ?>
	<script type="text/javascript">
		$(document).ready(function(){
			<?/*Add edit lead tab*/?>
			//
			// $('table.bx-edit-tabs tbody tr td:last').before('<td title="Редактировать лид" id="tab_cont_tab_2" class="bx-tab-container"><table cellspacing="0"><tbody><tr><td class="bx-tab-left" id="tab_left_tab_2"><div class="empty"></div></td><td class="bx-tab" id="tab_tab_2">Редактировать лид</td><td class="bx-tab-right" id="tab_right_tab_2"><div class="empty"></div></td></tr></tbody></table></td>');

			<?/*Edit lead tab click*/?>
			$('#workarea-content').on('click', '#tab_cont_tab_2.bx-tab-container', function(){
				BX.ajax.get('<?=$templateFolder?>/ajax_edit_lead_form.php?ID=<?= $arResult["ID"] ?>', function(data) {
					$('#crm-lead-form').html(data);
					//Выбор статуса лида "некачественный"
					//$('#crm-lead-form select.crm-item-table-select[name="STATUS_ID"] option[value="JUNK"]').attr("selected", "selected");
				});
			});

			<?/*Convert lead tab click*/?>
			$('#workarea-content').on('click', '#tab_cont_tab_1.bx-tab-container', function(){
				BX.ajax.get('<?=$templateFolder?>/ajax_convert_lead_form.php?ID=<?= $arResult["ID"] ?>&phone=<?= $_REQUEST["phone"] ?>', function(data) {
					$('#crm-lead-form').html(data);
					$('table.bx-edit-tabs tbody tr td:first').after('<td title="Просмотр лида" id="tab_cont_tab_show" class="bx-tab-container"><table cellspacing="0"><tbody><tr><td class="bx-tab" id="tab_tab_show">Просмотр</td></tr></tbody></table></td>');
					$('table.bx-edit-tabs tbody tr td:last').before('<td title="Редактировать лид" id="tab_cont_tab_2" class="bx-tab-container"><table cellspacing="0"><tbody><tr><td class="bx-tab-left" id="tab_left_tab_2"><div class="empty"></div></td><td class="bx-tab" id="tab_tab_2">Редактировать лид</td><td class="bx-tab-right" id="tab_right_tab_2"><div class="empty"></div></td></tr></tbody></table></td>');
					<?/*Clear last td html*/?>
					$('table.bx-edit-tabs tbody tr td:last').html('');
					<?/*Add hidden input with phone comment*/?>
					$('#form_CRM_LEAD_CONVERT').prepend('<input type="hidden" name="comment"/>');
					$("#form_CRM_LEAD_CONVERT input[name=comment]").val($('#phone_comment').val());
				});
			});

			<?/*Show lead tab click*/?>
			$('#workarea-content').on('click', '#tab_cont_tab_show.bx-tab-container', function(){
				BX.ajax.get('<?=$templateFolder?>/ajax_convert_lead_form.php?ID=<?= $arResult["ID"] ?>', function(data) {
					$('#crm-lead-form').html(data);
				});
			});
		});
	</script>
<? endif; ?>

<div class="gl_class">
    <div id="crm-lead-form" style="width: 70%; padding-top: 3%; padding-bottom: 1%;">
		<?/* if ($arResult["STATUS_ID"] == "JUNK"): */?><!--
			<?/* $APPLICATION->IncludeComponent(
				"bitrix:crm.lead.edit",
				"",
				array("ELEMENT_ID" => $arResult["ID"])
			); */?>
		<?/* else: */?>
			<?/* $APPLICATION->IncludeComponent(
				"sw:crm.lead.convert",
				"",
				array("ELEMENT_ID" => $arResult["ID"])
			); */?>
		--><?/* endif; */?>
		<? $APPLICATION->IncludeComponent(
			"bitrix:crm.lead.show",
			"infinity",
			array("ELEMENT_ID" => $arResult["ID"])
		); ?>
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