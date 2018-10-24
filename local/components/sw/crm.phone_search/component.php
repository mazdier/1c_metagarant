<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $USER;
global $APPLICATION;

$templatePage = "";

if (!CModule::IncludeModule('crm'))
{
    ShowError(GetMessage('CRM_MODULE_NOT_INSTALLED'));
    return;
}
$searchPhone = $arParams["PHONE"];
if (isset($arParams["PHONE"]) && !empty($arParams["PHONE"]))
{
    if (strlen($arParams["PHONE"]) > 9)
    {
        $searchPhone = substr($arParams["PHONE"], -9, 9);
    }
    elseif (strlen($arParams["PHONE"]) == 7)
    {
        $searchPhone = "17" . $arParams["PHONE"];
        $arParams["PHONE"] = $searchPhone;
    }
}

//check is convert form
/*if(($_SERVER['REQUEST_METHOD'] == 'POST' && check_bitrix_sessid()) && (isset($_POST['save']) || isset($_POST['apply'])))
{

}*/

//company
$phone = array();
$phone_company_temp = CCrmFieldMulti::GetListEx(
    array(),
    array(
        'ENTITY_ID' => array('CONTACT'),
        "TYPE_ID" => "PHONE",
        '?VALUE' => $searchPhone
    )
);
while ($temp = $phone_company_temp->Fetch())
{
    $phone[] = $temp;
}
unset($temp);


// echo "<pre>";print_r($phone); echo "</pre>";

if (count($phone) > 1)
{
    foreach ($phone as $v)
    {
        if ($v["ENTITY_ID"] == "CONTACT")
        {
            $cont[] = $v;
        }
        if ($v["ENTITY_ID"] == "LEAD")
        {
            $lead[] = $v;
        }
    }

    if (count($cont) > 0)
    {
        $new_phone = $cont;
    }
    else
    {
        $new_phone = $lead;
    }
}
else
{
    $new_phone = $phone;
}


foreach ($new_phone as $key => $value)
{

    /*echo "<pre>";
    print_r($value);
    echo "</pre>";*/

    if ($value["VALUE_TYPE"] == "WORK")
    {
        $phone_title = GetMessage("phone_in").$value["VALUE"]." (".GetMessage("phone_WORK").")";
    }
    if ($value["VALUE_TYPE"] == "MOBILE")
    {
        $phone_title = GetMessage("phone_in").$value["VALUE"]." (".GetMessage("phone_MOBILE").")";
    }
    if ($value["VALUE_TYPE"] == "FAX")
    {
        $phone_title = GetMessage("phone_in").$value["VALUE"]." (".GetMessage("phone_FAX").")";
    }
    if ($value["VALUE_TYPE"] == "HOME")
    {
        $phone_title = GetMessage("phone_in").$value["VALUE"]." (".GetMessage("phone_HOME").")";
    }
    if ($value["VALUE_TYPE"] == "OTHER")
    {
        $phone_title = GetMessage("phone_in").$value["VALUE"]." (".GetMessage("phone_OTHER").")";
    }

    $APPLICATION->SetTitle($phone_title); //УСТАНАВЛИВАЕМ ТАЙТЛ С НОМЕРОМ ПОЗВОНИВШЕГО

    if ($value["ENTITY_ID"] == "COMPANY")
    {
        $arResult = array(
            "ID" => $value["ELEMENT_ID"],
            "ENTITY_ID" => $value["ENTITY_ID"]
        );
        $templatePage = "company";

    }
    elseif ($value["ENTITY_ID"] == "CONTACT")
    {
        $arResult = array(
            "ID" => $value["ELEMENT_ID"],
            "ENTITY_ID" => $value["ENTITY_ID"]
        );
    }

    //показываем лид
    if ($value["ENTITY_ID"] == "LEAD")
    {
        $deal_temp = CCrmLead::GetList(array("ID" => "DESC"), array("ID" => $value["ELEMENT_ID"]), array());
        if ($temp = $deal_temp->GetNext())
        {
            if ($temp["STATUS_ID"] == "CONVERTED")
            {
                $templatePage = "show_lead";
            }
            else
            {
                $templatePage = "edit_lead";
            }
            $arResult = array(
                "ID" => $value["ELEMENT_ID"],
                "ENTITY_ID" => $value["ENTITY_ID"]
            );
        }
    }
}

//создаем новый лид
if (empty($phone))
{
    $CCrmLead = new CCrmLead();
    $title = str_replace("#PHONE#", $arParams["PHONE"], $arParams["LEAD_NAME_TEMPLATE"]);
    $arFields = array(
        'TITLE' => $title, 'SOURCE_ID' => 'CALL', 'STATUS_ID' => 'NEW', 'OPENED' => 'Y', 'ASSIGNED_BY_ID' => $USER->GetID(),
        'FM' => array('PHONE' => array('n1' => array('VALUE' => $arParams["PHONE"], 'VALUE_TYPE' => 'WORK',))),
        'CURRENCY_ID' => 'RUB', 'EXCH_RATE' => 1,
    );

    if ($id_lead = $CCrmLead->Add($arFields, true, array('REGISTER_SONET_EVENT' => true)))
    {
        $arResult["ENTITY_ID"] = "LEAD";
        $arResult['ID'] = $id_lead;
        $templatePage = "new_lead";
    }
    else
    {
        echo $CCrmLead->LAST_ERROR;
        return;
    }
    // unset($CCrmLead);

}
// echo "<pre>";print_r($arResult); echo "</pre>";


$this->IncludeComponentTemplate($templatePage);
// $APPLICATION->IncludeComponent("sw:crm.lead.convert","",array("ELEMENT_ID"=>119));

?>
