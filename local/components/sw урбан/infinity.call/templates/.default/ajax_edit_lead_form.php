<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?
global $APPLICATION;
$APPLICATION->ShowHead();
?>
<table cellspacing="0" class="bx-edit-tabs" width="100%">
    <tbody>
    <tr>
        <td class="bx-tab-indent"><div class="empty"></div></td>
        <td title="Просмотр лида" id="tab_cont_tab_show" class="bx-tab-container">
            <table cellspacing="0">
                <tbody><tr>
                    <td class="bx-tab" id="tab_tab_show">Просмотр</td>
                </tr></tbody>
            </table>
        </td>
		<!--<td title="Конвертация лида"
            id="tab_cont_tab_1"
            class="bx-tab-container">
            <table cellspacing="0">
                <tbody><tr>
                    <td class="bx-tab" id="tab_tab_1">Конвертация</td>
                </tr></tbody>
            </table>
        </td>-->
        <td title="Редактировать лид" id="tab_cont_tab_2" class="bx-tab-container-selected">
            <table cellspacing="0">
                <tbody><tr>
                    <td class="bx-tab-selected" id="tab_tab_2">Редактировать</td>
                </tr></tbody>
            </table>
        </td>
        <td width="100%" style="white-space:nowrap; text-align:right">
        </td>
    </tr>
    </tbody>
</table>
<? $APPLICATION->IncludeComponent(
    "bitrix:crm.lead.edit",
    "",
    array("ELEMENT_ID" => $_REQUEST["ID"])
); ?>
