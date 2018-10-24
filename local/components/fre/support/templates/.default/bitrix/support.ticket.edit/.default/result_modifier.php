<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (intval($arResult["TICKET"]["ID"]))
{
    $arResult["USER_FIELDS"] = CTicket::GetList(
        $by = "ID",
        $order = "asc",
        array("ID" => $arResult["TICKET"]["ID"]),
        $isFiltered,
        "Y",
        "Y",
        "Y",
        false,
        Array("SELECT" => array("UF_*"))
    )->Fetch();
    $arResult["SHOW_FIELDS"] = array();
    switch($arResult["TICKET"]["CATEGORY_ID"])
    {
        case 24:
            $arResult["SHOW_FIELDS"] = array(
                "TEC_NEED" => 'Наличие технической необходимости выдачи: <input type="checkbox" disabled readonly '.($arResult["USER_FIELDS"]["UF_NEWPC_TEC_NEED"] ? 'checked' : '').'>',
                "TEC_OPP" => 'Наличие технической возможности выдачи: <input type="checkbox" disabled readonly '.($arResult["USER_FIELDS"]["UF_NEWPC_TEC_OPP"] ? 'checked' : '').'>',
                "PROD_NEED" => 'Наличие производственной необходимости выдачи: <input type="checkbox" disabled readonly '.($arResult["USER_FIELDS"]["UF_PROD_NEED"] ? 'checked' : '').'>',
            );
            break;
        default:
            break;
    }
}
