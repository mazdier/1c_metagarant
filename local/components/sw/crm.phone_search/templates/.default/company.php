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
    <div style="width: 70%; padding-top: 3%; padding-bottom: 1%;">
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
                "BINDINGS" => array(array("TYPE_NAME" => "COMPANY", "ID" => $arResult["ID"]))
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
                "FORM_ID" => "CRM_COMPANY_SHOW_V12_T",
                "INTERNAL_CONTEXT" => array("COMPANY_ID" => $arResult["ID"]),
                "AJAX_MODE" => "Y",  // режим AJAX
                "AJAX_OPTION_SHADOW" => "N", // затемнять область
                "AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
                "AJAX_OPTION_STYLE" => "Y", // подключать стили
                "AJAX_OPTION_HISTORY" => "N",
                "INTERNAL_FILTER" => array("COMPANY_ID" => $arResult["ID"])
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
        "INFINITY_ADDRESS" => $arParams["INFINITY"],
        "INFINITY_USER_ID" => $_REQUEST["userid"]
    )
); ?>


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



