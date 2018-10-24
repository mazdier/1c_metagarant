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
		BX.ajax.get('<?=$templateFolder?>/ajax_convert_lead_form.php?ID=<?= $arResult["ID"] ?>&phone=<?= $_REQUEST["phone"] ?>', function(data) {
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
			"INFINITY_ADDRESS" => $arParams["INFINITY"],
			"INFINITY_USER_ID" => $_REQUEST["userid"]
		)
	); ?>
</div>

<script type="text/javascript">

	/*****************<добавление компании>************************/
	function company_add()
	{

		var popup = new BX.PopupWindow("my-popup", null, {
			closeIcon: {right: "12px", top: "10px"},
			titleBar: {content: BX.create("span", {html: '<b>Создание компании</b>', 'props': {'className': 'access-title-bar'}})},
			closeByEsc : true,
			draggable: {restrict: true},
			zIndex: '-500',
			buttons: [
				new BX.PopupWindowButton({
					text: "Добавить",
					className: "popup-window-button-accept",
					events: {click: function(){

						$('#CRM_COMPANY_EDIT_V12_saveAndView').click();
						setTimeout(this.popupWindow.close(), 1000);

					}

					}
				})
			],
			events: {
				onPopupClose : function(popupWindow){
					popupWindow.destroy();
				}
			}
		});

		BX.ajax.get('<?=$templateFolder?>/comp.php?edit=Y&contact_id=<?=$arResult["ID"]?>', function(data) {
			popup.setContent(data);
			popup.show();
		});
	}
	$(".company_add").click(function()
	{
		company_add();
		return false;
	});
	/*****************</добавление компании>************************/


	/*****************<добавление сделки>************************/
	function deal_pop()
	{
		var popup = new BX.PopupWindow("my-popup", null, {
			closeIcon: {right: "12px", top: "10px"},
			titleBar: {content: BX.create("span", {html: '<b>Создание сделки</b>', 'props': {'className': 'access-title-bar'}})},
			closeByEsc : true,
			draggable: {restrict: true},
			zIndex: '-500',
			offsetTop : 10,
			buttons: [
				new BX.PopupWindowButton({
					text: "Добавить",
					className: "popup-window-button-accept",
					events: {click: function(){

						$('#CRM_DEAL_EDIT_V12_saveAndView').click();
						setTimeout(this.popupWindow.close(), 1000);

					}

					}
				})
			],
			events: {
				onPopupClose : function(popupWindow){
					popupWindow.destroy();
				}
			}
		});

		BX.ajax.get('<?=$templateFolder?>/deal.php?edit=Y&contact_id=<?=$arResult["ID"]?>', function(data) {
			popup.setContent(data);
			popup.show();
		});
	}

	$(".deal_add").click(function()
	{
		deal_pop();
		return false;
	});
	/*****************</добавление сделки>************************/

</script>
