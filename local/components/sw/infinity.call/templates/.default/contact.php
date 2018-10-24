<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}
CModule::IncludeModule('crm');
global $APPLICATION;
$APPLICATION->SetAdditionalCSS("/bitrix/themes/.default/crm-entity-show.css");
CJSCore::Init(array("jquery"));
CJSCore::Init(array("popup"));
CUtil::InitJSCore(array('window'));
CCrmComponentHelper::RegisterScriptLink('/bitrix/js/crm/activity.js');
CCrmComponentHelper::RegisterScriptLink('/bitrix/js/crm/interface_grid.js');

?>
<div class="gl_class">
    <div style="width: 70%; padding-top: 3%; padding-bottom: 1%;">
        <? if($arResult["IS_SEVERAL_ENTITIES"] == "Y"): ?>
            <div class="crm-list-top-bar crm-entity-list">
                <? foreach($arResult["ITEMS"] as $arItem): ?>
                    <a
                        class="crm-menu-bar-btn btn-new btn-entity<?= $arItem['ID'] == $arResult['ID'] ? ' btn-entity-active' : ''; ?>"
                        data-entity-type="<?= $arResult['ENTITY_ID'] ?>"
                        data-entity-id="<?= $arItem['ID'] ?>"
                        onclick="ChangeEntity(this)"
                        href="#">
                        <span><?= $arItem["TITLE"]; ?></span>
                    </a>
                <? endforeach; ?>
            </div>
        <? endif; ?>

        <p><a href="" class="company_add">Создать компанию</a></p>

        <p><a href="" class="deal_add">Создать сделку</a></p>

        <h1 class="odin_zagol">Дела:</h1>
        <? $APPLICATION->IncludeComponent(
            "sw:crm.activity.list",
            "grid",
            array(
                "TAB_ID" => "tab_activity",
                "PERMISSION_TYPE" => "WRITE",
                "ENABLE_CONTROL_PANEL" => false,
                "USE_QUICK_FILTER" => "Y",
				"FORM_TYPE" => "show",
                "AJAX_MODE" => "Y",  // режим AJAX
                "AJAX_OPTION_SHADOW" => "N", // затемнять область
                "AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
                "AJAX_OPTION_STYLE" => "Y", // подключать стили
                "AJAX_OPTION_HISTORY" => "N",
                "BINDINGS" => array(array("TYPE_NAME" => "CONTACT", "ID" => $arResult["ID"]))
            )
        ); ?>
    </div>
    <!--</div>-->

    <div style="width: 70%; padding-top: 3%; padding-bottom: 1%;" class="deal">
        <h1 class="odin_zagol">Сделки:</h1>
        <? $APPLICATION->IncludeComponent(
            "sw:crm.deal.list",
            "",
            array(
                "TAB_ID" => "tab_deal",
                "ENABLE_CONTROL_PANEL" => false,
                "USE_QUICK_FILTER" => "Y",
                "FORM_TYPE" => "show",
                "FORM_ID" => "CRM_CONTACT_SHOW_V12_T",
                "INTERNAL_CONTEXT" => array("CONTACT_ID" => $arResult["ID"]),
                "AJAX_MODE" => "Y",  // режим AJAX
                "AJAX_OPTION_SHADOW" => "N", // затемнять область
                "AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
                "AJAX_OPTION_STYLE" => "Y", // подключать стили
                "AJAX_OPTION_HISTORY" => "N",
                "INTERNAL_FILTER" => array("CONTACT_ID" => $arResult["ID"])
            )
        ); ?>
    </div>
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



