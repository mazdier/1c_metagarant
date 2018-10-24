<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
?>
<style>
	div.empty{width:1px;height:1px;overflow:hidden}div.bx-interface-form{margin:16px 0 16px 0}div.bx-interface-form div.bx-buttons{padding:10px;height:44px;border:1px solid #c2c2c2;border-bottom:1px solid #d4d4d4;border-top:0;background-color:#fafafa;background-image:url(images/buttons_bg.png);background-position:left top;background-repeat:repeat-x;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box}table.bx-edit-tabs{border-collapse:separate}table.bx-edit-tabs table{border-collapse:separate}table.bx-edit-tabs td{font-size:12px;font-weight:bold;color:#7c7c7c;white-space:nowrap;border-bottom:4px solid #878787;padding:0}table.bx-edit-tabs td.bx-tab-container{border:0;cursor:pointer;cursor:hand}table.bx-edit-tabs td.bx-tab-container-selected,table.bx-edit-tabs td.bx-tab-container-disabled{cursor:default}table.bx-edit-tabs td.bx-tab-container-disabled td{border:0;color:#d7d7d7}table.bx-edit-tabs td.bx-tab-indent div.empty{width:8px;height:29px}table.bx-edit-tabs td.bx-tab-left div.empty{width:3px;height:29px;background-image:url(images/edit/tab_l.gif)}table.bx-edit-tabs td.bx-tab-right div.empty{width:18px;height:29px;background-image:url(images/edit/tab_r.gif)}table.bx-edit-tabs td.bx-tab{vertical-align:top;padding:8px 3px 0 10px;background-image:url(images/edit/tab_bg.gif)}table.bx-edit-tabs td.bx-tab-left-selected,table.bx-edit-tabs td.bx-tab-selected,table.bx-edit-tabs td.bx-tab-right-selected,table.bx-edit-tabs td.bx-tab-right-last-selected{border:0}table.bx-edit-tabs td.bx-tab-left-selected div.empty{width:3px;height:29px;background-image:url(images/edit/tab_l_sel.png)}table.bx-edit-tabs td.bx-tab-right-selected div.empty{width:18px;height:29px;background-image:url(images/edit/tab_r_sel.png)}table.bx-edit-tabs td.bx-tab-selected{color:white;vertical-align:top;padding:7px 3px 0 10px;background-image:url(images/edit/tab_bg_sel.png)}table.bx-edit-tabs td.bx-tab-left-hover div.empty{width:3px;height:29px;background-image:url(images/edit/tab_l_hov.gif)}table.bx-edit-tabs td.bx-tab-right-hover div.empty{width:18px;height:29px;background-image:url(images/edit/tab_r_hov.gif)}table.bx-edit-tabs td.bx-tab-hover{vertical-align:top;padding:8px 3px 0 10px;background-image:url(images/edit/tab_bg_hov.gif)}table.bx-edit-tabs a.bx-context-button{display:inline-block;border:0;margin:1px;outline:0}table.bx-edit-tabs a.bx-context-button:hover{margin:0;border:1px solid #aeb6c2;background-color:#dbe3f2}table.bx-edit-tabs a.bx-context-button span{cursor:pointer;display:inline-block;background-repeat:no-repeat;background-position:3px 3px;height:18px;width:18px;border:0}table.bx-edit-tabs a.bx-down span{background-image:url(images/buttons/show.gif)}table.bx-edit-tabs a.bx-up span{background-image:url(images/buttons/hide.gif)}table.bx-edit-tabs a.bx-form-menu span{width:26px;background-image:url(images/buttons/menu.gif)}table.bx-edit-tabs a.pressed{margin:0;border:1px solid #aeb6c2;background-color:white}table.bx-edit-tab{border:1px solid #cfcfcf;border-top:0;width:100%;border-collapse:separate;background-color:#fff}table.bx-edit-tab td{padding:0}div.bx-edit-tab-title{background-color:#e8e8e8;padding-left:8px}table.bx-edit-tab-title{width:100%;border-collapse:separate}table.bx-edit-tab-title td.bx-icon{padding:5px 5px 5px 0;width:32px;height:32px}table.bx-edit-tab-title td.bx-form-title{width:100%;padding:10px 0 10px 0;font-size:140%;color:black;font-weight:bold;font-family:Arial,helvetica,sans-serif}div.bx-edit-table{margin:10px}table.bx-edit-table{width:100%;border-collapse:separate}table.bx-edit-table td{font-size:100%;padding:6px;color:black;background-color:#fff;background-image:url(images/row_bg.gif);background-position:left top;background-repeat:repeat-x}table.bx-edit-table tr.bx-top td{background-image:none}table.bx-edit-table tr.bx-after-heading td{background-image:none}table.bx-edit-table td.bx-field-name{width:30%;text-align:right;vertical-align:top}table.bx-edit-table td.bx-field-value{vertical-align:top}table.bx-edit-table td.bx-padding{padding-top:9px}table.bx-edit-table td.bx-heading{color:black;font-weight:bold;background-color:#e8e8e8;background-image:none}table.bx-edit-table table td{padding:0;border:0;background-image:none}table.bx-edit-table table.bx-edit-table td{padding:4px}table.bx-edit-table div{font-size:100%}span.required{color:red}option.bx-section{background-color:#e8e8e8}td.popupmenu div.popupitem div.form-settings{background-image:url(images/buttons/settings.gif)}td.popupmenu div.popupitem div.form-settings-off{background-image:url(images/buttons/settings_off.gif)}td.popupmenu div.popupitem div.form-settings-on{background-image:url(images/buttons/settings_on.gif)}td.popupmenu div.popupitem div.form-themes{background-image:url(images/buttons/themes.gif)}
</style>
<table cellspacing="0" class="bx-edit-tabs" width="100%">
	<tbody>
	<tr>
		<td class="bx-tab-indent"><div class="empty"></div></td>
		<td title="Просмотр лида" id="tab_cont_tab_show" class="bx-tab-container-selected">
			<table cellspacing="0">
				<tbody><tr>
					<td class="bx-tab-selected" id="tab_tab_show">Просмотр</td>
				</tr></tbody>
			</table>
		</td>
		<td title="Конвертация лида"
			id="tab_cont_tab_1"
			class="bx-tab-container">
			<table cellspacing="0">
				<tbody><tr>
					<td class="bx-tab" id="tab_tab_1">Конвертация</td>
				</tr></tbody>
			</table>
		</td>
		<td title="Редактировать лид" id="tab_cont_tab_2" class="bx-tab-container">
			<table cellspacing="0">
				<tbody><tr>
					<td class="bx-tab" id="tab_tab_2">Редактировать</td>
				</tr></tbody>
			</table>
		</td>
		<td width="100%" style="white-space:nowrap; text-align:right">
		</td>
	</tr>
	</tbody>
</table>
<?
use \Bitrix\Crm\Integration\StorageType;

global $APPLICATION;
$APPLICATION->AddHeadScript('/bitrix/js/crm/instant_editor.js');
$APPLICATION->SetAdditionalCSS('/bitrix/js/crm/css/crm.css');
$APPLICATION->SetAdditionalCSS("/bitrix/themes/.default/crm-entity-show.css");
if(SITE_TEMPLATE_ID === 'bitrix24')
{
	$APPLICATION->SetAdditionalCSS("/bitrix/themes/.default/bitrix24/crm-entity-show.css");
}

//Preliminary registration of disk api.
if(CCrmActivity::GetDefaultStorageTypeID() === StorageType::Disk)
{
	CJSCore::Init(array('uploader', 'file_dialog'));
}

$arResult['CRM_CUSTOM_PAGE_TITLE'] = GetMessage(
	'CRM_LEAD_SHOW_TITLE',
	array(
		'#ID#' => $arResult['ELEMENT']['ID'],
		'#TITLE#' => $arResult['ELEMENT']['TITLE']
	)
);

$enableInstantEdit = $arResult['ENABLE_INSTANT_EDIT'];
$instantEditorID = strtolower($arResult['FORM_ID']).'_editor';
$bizprocDispatcherID = strtolower($arResult['FORM_ID']).'_bp_disp';

$arTabs = array();
$arTabs[] = array(
	'id' => 'tab_1',
	'name' => GetMessage('CRM_TAB_1'),
	'title' => GetMessage('CRM_TAB_1_TITLE'),
	'icon' => '',
	'fields'=> $arResult['FIELDS']['tab_1'],
	'display' => false
);
if(!empty($arResult['FIELDS']['tab_details']))
{
	$arTabs[] = array(
		'id' => 'tab_details',
		'name' => GetMessage('CRM_TAB_DETAILS'),
		'title' => GetMessage('CRM_TAB_DETAILS_TITLE'),
		'icon' => '',
		'fields' => $arResult['FIELDS']['tab_details'],
		'display' => false
	);
}

$liveFeedTab = null;
if (!empty($arResult['FIELDS']['tab_live_feed']))
{
	$liveFeedTab = array(
		'id' => 'tab_live_feed',
		'name' => GetMessage('CRM_TAB_LIVE_FEED'),
		'title' => GetMessage('CRM_TAB_LIVE_FEED_TITLE'),
		'icon' => '',
		'fields' => $arResult['FIELDS']['tab_live_feed']
	);
	$arTabs[] = $liveFeedTab;
}
if (!empty($arResult['FIELDS']['tab_activity']))
{
	$arTabs[] = array(
		'id' => 'tab_activity',
		'name' => GetMessage('CRM_TAB_6'),
		'title' => GetMessage('CRM_TAB_6_TITLE'),
		'icon' => '',
		'fields' => $arResult['FIELDS']['tab_activity']
	);
}
$arTabs[] = array(
	'id' => $arResult['PRODUCT_ROW_TAB_ID'],
	'name' => GetMessage('CRM_TAB_PRODUCT_ROWS'),
	'title' => GetMessage('CRM_TAB_PRODUCT_ROWS_TITLE'),
	'icon' => '',
	'fields'=> $arResult['FIELDS'][$arResult['PRODUCT_ROW_TAB_ID']]
);
if ($arResult['ELEMENT']['STATUS_ID'] == 'CONVERTED'):
	if (!empty($arResult['FIELDS']['tab_contact']))
		$arTabs[] = array(
			'id' => 'tab_contact',
			//'name' => GetMessage('CRM_TAB_2')." ($arResult[CONTACT_COUNT])",
			'name' => GetMessage('CRM_TAB_2'),
			'title' => GetMessage('CRM_TAB_2_TITLE'),
			'icon' => '',
			'fields'=> $arResult['FIELDS']['tab_contact']
		);
	if (!empty($arResult['FIELDS']['tab_company']))
		$arTabs[] = array(
			'id' => 'tab_company',
			//'name' => GetMessage('CRM_TAB_3')." ($arResult[COMPANY_COUNT])",
			'name' => GetMessage('CRM_TAB_3'),
			'title' => GetMessage('CRM_TAB_3_TITLE'),
			'icon' => '',
			'fields'=> $arResult['FIELDS']['tab_company']
		);
	if (!empty($arResult['FIELDS']['tab_deal']))
		$arTabs[] = array(
			'id' => 'tab_deal',
			//'name' => GetMessage('CRM_TAB_4')." ($arResult[DEAL_COUNT])",
			'name' => GetMessage('CRM_TAB_4'),
			'title' => GetMessage('CRM_TAB_4_TITLE'),
			'icon' => '',
			'fields'=> $arResult['FIELDS']['tab_deal']
		);
endif;

if (!empty($arResult['FIELDS']['tab_quote']))
{
	$arTabs[] = array(
		'id' => 'tab_quote',
		'name' => GetMessage('CRM_TAB_8'),
		'title' => GetMessage('CRM_TAB_8_TITLE'),
		'icon' => '',
		'fields' => $arResult['FIELDS']['tab_quote']
	);
}

if (isset($arResult['BIZPROC']) && $arResult['BIZPROC'] === 'Y' && !empty($arResult['FIELDS']['tab_bizproc']))
{
	$arTabs[] = array(
		'id' => 'tab_bizproc',
		'name' => GetMessage('CRM_TAB_7'),
		'title' => GetMessage('CRM_TAB_7_TITLE'),
		'icon' => '',
		'fields' => $arResult['FIELDS']['tab_bizproc']
	);
}
$arTabs[] = array(
	'id' => 'tab_event',
	//'name' => GetMessage('CRM_TAB_HISTORY')." ($arResult[EVENT_COUNT])",
	'name' => GetMessage('CRM_TAB_HISTORY'),
	'title' => GetMessage('CRM_TAB_HISTORY_TITLE'),
	'icon' => '',
	'fields' => $arResult['FIELDS']['tab_event']
);
$element = isset($arResult['ELEMENT']) ? $arResult['ELEMENT'] : null;
$APPLICATION->IncludeComponent(
	'bitrix:crm.interface.form',
	'show',
	array(
		'FORM_ID' => $arResult['FORM_ID'],
		'GRID_ID' => $arResult['GRID_ID'],
		'TACTILE_FORM_ID' => 'CRM_LEAD_EDIT_V12',
		'QUICK_PANEL' => array(
			'ENTITY_TYPE_NAME' => CCrmOwnerType::LeadName,
			'ENTITY_ID' => $arResult['ELEMENT_ID'],
			'ENTITY_FIELDS' => $element,
			'ENABLE_INSTANT_EDIT' => $arResult['ENABLE_INSTANT_EDIT'],
			'INSTANT_EDITOR_ID' => $instantEditorID,
			'SERVICE_URL' => '/bitrix/components/bitrix/crm.lead.show/ajax.php?'.bitrix_sessid_get()
		),
		'TABS' => $arTabs,
		'DATA' => $element,
		'SHOW_SETTINGS' => 'Y'
	),
	$component, array('HIDE_ICONS' => 'Y')
);
?>
<?if($arResult['ENABLE_INSTANT_EDIT']):?>
<script type="text/javascript">
	BX.ready(
		function()
		{
			BX.CrmInstantEditorMessages =
			{
				editButtonTitle: '<?= CUtil::JSEscape(GetMessage('CRM_EDIT_BTN_TTL'))?>',
				lockButtonTitle: '<?= CUtil::JSEscape(GetMessage('CRM_LOCK_BTN_TTL'))?>'
			};

			var instantEditor = BX.CrmInstantEditor.create(
				'<?=CUtil::JSEscape($instantEditorID)?>',
				{
					containerID: [],
					ownerType: 'L',
					ownerID: <?=$arResult['ELEMENT_ID']?>,
					url: '/bitrix/components/bitrix/crm.lead.show/ajax.php?<?= bitrix_sessid_get()?>',
					callToFormat: <?=CCrmCallToUrl::GetFormat(CCrmCallToUrl::Bitrix)?>
				}
			);

			var prodEditor = BX.CrmProductEditor.getDefault();

			function handleProductRowChange()
			{
				if(prodEditor)
				{
					var haveProducts = prodEditor.getProductCount() > 0;
					instantEditor.setFieldReadOnly('OPPORTUNITY', haveProducts);
					instantEditor.setFieldReadOnly('CURRENCY_ID', haveProducts);
				}
			}

			function handleSelectProductEditorTab(objForm, objFormName, tabID, tabElement)
			{
				var productRowsTabId = "<?=$arResult['PRODUCT_ROW_TAB_ID']?>";
				if (typeof(productRowsTabId) === "string" && productRowsTabId.length > 0 && tabID === productRowsTabId)
					BX.onCustomEvent("CrmHandleShowProductEditor", [prodEditor]);
			}

			if(prodEditor)
			{
				BX.addCustomEvent(
					prodEditor,
					'sumTotalChange',
					function(ttl)
					{
						instantEditor.setFieldValue('OPPORTUNITY', ttl);
						if(prodEditor.isViewMode())
						{
							//emulate save field event to refresh controls
							instantEditor.riseSaveFieldValueEvent('OPPORTUNITY', ttl);
						}
					}
				);

				handleProductRowChange();

				BX.addCustomEvent(
					prodEditor,
					'productAdd',
					handleProductRowChange
				);

				BX.addCustomEvent(
					prodEditor,
					'productRemove',
					handleProductRowChange
				);

				BX.addCustomEvent(
					'BX_CRM_INTERFACE_FORM_TAB_SELECTED',
					handleSelectProductEditorTab
				);
			}

			<?if(isset($arResult['ENABLE_BIZPROC_LAZY_LOADING']) && $arResult['ENABLE_BIZPROC_LAZY_LOADING'] === true):?>
			var bpContainerId = "<?=$arResult['BIZPROC_CONTAINER_ID']?>";
			if(BX(bpContainerId))
			{
				BX.CrmBizprocDispatcher.create(
					"<?=CUtil::JSEscape($bizprocDispatcherID)?>",
					{
						containerID: bpContainerId,
						entityTypeName: "<?=CCrmOwnerType::LeadName?>",
						entityID: <?=$arResult['ELEMENT_ID']?>,
						serviceUrl: "/bitrix/components/bitrix/crm.lead.show/bizproc.php?lead_id=<?=$arResult['ELEMENT_ID']?>&post_form_uri=<?=urlencode($arResult['POST_FORM_URI'])?>&<?=bitrix_sessid_get()?>",
						formID: "<?=CUtil::JSEscape($arResult['FORM_ID'])?>",
						pathToEntityShow: "<?=CUtil::JSEscape($arResult['PATH_TO_LEAD_SHOW'])?>"
					}
				);
			}
			<?endif;?>
		}
	);
</script>
<?endif;?>

<?if(isset($arResult['ENABLE_LIVE_FEED_LAZY_LOAD']) && $arResult['ENABLE_LIVE_FEED_LAZY_LOAD'] === true):?>
<script type="text/javascript">
	(function()
	{
		var liveFeedContainerId = "<?=CUtil::JSEscape($arResult['LIVE_FEED_CONTAINER_ID'])?>";
		if(!BX(liveFeedContainerId))
		{
			return;
		}

		var params =
		{
			"ENTITY_TYPE_NAME" : "<?=CCrmOwnerType::LeadName?>",
			"ENTITY_ID": <?=$arResult['ELEMENT_ID']?>,
			"POST_FORM_URI": "<?=CUtil::JSEscape($arResult['POST_FORM_URI'])?>",
			"ACTION_URI": "<?=CUtil::JSEscape($arResult['ACTION_URI'])?>",
			"PATH_TO_USER_PROFILE": "<?=CUtil::JSEscape($arParams['PATH_TO_USER_PROFILE'])?>"
		};

		BX.addCustomEvent(
			window,
			"SonetLogBeforeGetNextPage",
			function(data)
				{
					if(!BX.type.isNotEmptyString(data["url"]))
					{
						return;
					}

					var request = {};
					for(var key in params)
					{
						if(params.hasOwnProperty(key))
						{
							request["PARAMS[" + key + "]"] = params[key];
						}
					}
					data["url"] = BX.util.add_url_param(data["url"], request);
				}
		);

		BX.CrmFormTabLazyLoader.create(
			"<?=CUtil::JSEscape(strtolower($arResult['FORM_ID'])).'_livefeed'?>",
			{
				containerID: liveFeedContainerId,
				serviceUrl: "/bitrix/components/bitrix/crm.entity.livefeed/lazyload.ajax.php?&site=<?=SITE_ID?>&<?=bitrix_sessid_get()?>",
				formID: "<?=CUtil::JSEscape($arResult['FORM_ID'])?>",
				tabID: "tab_live_feed",
				params: params
			}
		);
	})();
</script>
<?endif;?>
