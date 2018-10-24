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

<div class="gl_class">
    <div id="crm-lead-form" style="width: 70%; padding-top: 3%; padding-bottom: 1%;">
        <? $APPLICATION->IncludeComponent(
            "bitrix:crm.lead.show",
            "",
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