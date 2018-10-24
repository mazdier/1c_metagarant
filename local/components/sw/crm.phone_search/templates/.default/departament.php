<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<? CModule::IncludeModule('iblock');
/*получение подразделений компании + сотрудники этих подразделений*/
$arParams['IBLOCK_ID'] = COption::GetOptionInt('intranet', 'iblock_structure', 0);


$arFilters = array('IBLOCK_ID' => $arParams['IBLOCK_ID']); // выберет потомков без учета активности
$rsSects = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilters, false, array("UF_PHONE_INFINITY"));
while ($arSects = $rsSects->GetNext())
{
    $arSects['ID'];
    if (!empty($arSects["ID"]))
    {

        /*------------users департаментов----------------*/
        $temp = CIntranetUtils::GetDepartmentEmployees($arSects['ID']);
        while ($temp_dep = $temp->GetNext())
        {
            $arSects['user'][] = $temp_dep;
        }
        /*-----------------------------------------------*/
        $mas['dep'][$arSects["ID"]] = $arSects;

        $rsParentSection = array();
        $rsParentSection = CIBlockSection::GetByID($arSects["ID"]);
        if ($arParentSection = $rsParentSection->GetNext())
        {
            /*------------users департаментов----------------*/
            $temp = CIntranetUtils::GetDepartmentEmployees($arParentSection['ID']);
            while ($temp_dep = $temp->GetNext())
            {
                $arParentSection['user'][] = $temp_dep;
            }
            /*-----------------------------------------------*/

            // $mas['dep'][$arParentSection["IBLOCK_SECTION_ID"]]['elem'][$arParentSection["ID"]]=$arParentSection;

            $rsSect = array();
            $arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'], '>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'], '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'], '>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
            $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter);
            while ($arSect = $rsSect->GetNext())
            {

                /*------------users департаментов----------------*/
                $temp = CIntranetUtils::GetDepartmentEmployees($arSect['ID']);
                while ($temp_dep = $temp->GetNext())
                {
                    $arSect['user'][] = $temp_dep;
                }
                /*-----------------------------------------------*/

                // $mas['dep'][$arParentSection["IBLOCK_SECTION_ID"]]['elem'][$arSect["IBLOCK_SECTION_ID"]]['elem'][]=$arSect;

            }
        }
    }

}
// echo "<pre>";print_r($mas); echo "</pre>";
?>

<form name="form2" action="" method="GET">
    <?

    foreach ($mas['dep'] as $key => $value)
    {


        if ($value["DEPTH_LEVEL"] == 1)
        {
            $dep["REFERENCE"][] = "• " . $value["NAME"];
        }
        if ($value["DEPTH_LEVEL"] == 2)
        {
            $dep["REFERENCE"][] = "•• " . $value["NAME"];
        }
        if ($value["DEPTH_LEVEL"] == 3)
        {
            $dep["REFERENCE"][] = "••• " . $value["NAME"];
        }

        $dep["REFERENCE_ID"][] = $value["UF_PHONE_INFINITY"];

        foreach ($value["user"] as $key => $value)
        {
            $dep["REFERENCE"][] = "… " . $value["NAME"] . " " . $value["LAST_NAME"];
            $dep["REFERENCE_ID"][] = $value["UF_PHONE_INFINITY"];
        }

    }

    echo SelectBoxFromArray("DEP", $dep, $CHOICE, "", "class=\"bxhtmled-top-bar-select\"", true, "form2");
    ?>
</form>


