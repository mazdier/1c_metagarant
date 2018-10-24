<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc as Loc;

class FreSupportSectionListComponent extends CBitrixComponent
{
    protected $_page = "";

    /**
     * подключает языковые файлы
     */
    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    /**
     * подготавливает входные параметры
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    /**
     * проверяет подключение необходиимых модулей
     * @throws LoaderException
     */
    protected function checkModules()
    {
        if (!Main\Loader::includeModule('iblock'))
        {
            throw new Main\LoaderException(Loc::getMessage('SUPPORT_SECTION_LIST_CLASS_IBLOCK_MODULE_NOT_INSTALLED'));
        }
    }

    /**
     * проверяет заполнение обязательных параметров
     * @throws SystemException
     */
    protected function checkParams()
    {
        if ($this->arParams['IBLOCK_ID'] <= 0)
        {
            throw new Main\ArgumentNullException('IBLOCK_ID');
        }
    }

    /**
     * выполяет действия перед кешированием
     */
    protected function executeProlog()
    {
    }

    /**
     * получение результатов
     */
    protected function getResult()
    {
        $arResult = array();
        $arSort = array(
            $this->arParams['SORT_FIELD1'] => $this->arParams['SORT_DIRECTION1'],
            $this->arParams['SORT_FIELD2'] => $this->arParams['SORT_DIRECTION2']
        );
        $arFilter = array(
            "ACTIVE" => "Y",
            "GLOBAL_ACTIVE" => "Y",
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
            "CNT_ACTIVE" => "Y",
        );
        if($this->arParams["SECTION_ID"]>0)
        {
            $arFilter["ID"] = $this->arParams["SECTION_ID"];
            $rsSections = CIBlockSection::GetList(array(), $arFilter);
            $rsSections->SetUrlTemplates("", $this->arParams["SECTION_URL"]);
            $arResult["SECTION"] = $rsSections->GetNext();
        }
        elseif('' != $this->arParams["SECTION_CODE"])
        {
            $arFilter["=CODE"] = $this->arParams["SECTION_CODE"];
            $rsSections = CIBlockSection::GetList(array(), $arFilter, $this->arParams["COUNT_ELEMENTS"]);
            $rsSections->SetUrlTemplates("", $this->arParams["SECTION_URL"]);
            $arResult["SECTION"] = $rsSections->GetNext();
        }

        if(is_array($arResult["SECTION"]))
        {
            unset($arFilter["ID"]);
            unset($arFilter["=CODE"]);
            $arFilter["LEFT_MARGIN"]=$arResult["SECTION"]["LEFT_MARGIN"]+1;
            $arFilter["RIGHT_MARGIN"]=$arResult["SECTION"]["RIGHT_MARGIN"];
            $arFilter["<="."DEPTH_LEVEL"]=$arResult["SECTION"]["DEPTH_LEVEL"] + $this->arParams["TOP_DEPTH"];

            $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["ID"]);
            $arResult["SECTION"]["IPROPERTY_VALUES"] = $ipropValues->getValues();

            $arResult["SECTION"]["PATH"] = array();
            $rsPath = CIBlockSection::GetNavChain($arResult["SECTION"]["IBLOCK_ID"], $arResult["SECTION"]["ID"]);
            $rsPath->SetUrlTemplates("", $this->arParams["SECTION_URL"]);
            while($arPath = $rsPath->GetNext())
            {
                $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($this->arParams["IBLOCK_ID"], $arPath["ID"]);
                $arPath["IPROPERTY_VALUES"] = $ipropValues->getValues();
                $arResult["SECTION"]["PATH"][]=$arPath;
            }
        }
        else
        {
            $arResult["SECTION"] = array("ID"=>0, "DEPTH_LEVEL"=>0);
            $arFilter["<="."DEPTH_LEVEL"] = $this->arParams["TOP_DEPTH"];
        }
        $intSectionDepth = $arResult["SECTION"]['DEPTH_LEVEL'];

        $rsSections = CIBlockSection::GetList(array("left_margin"=>"asc"), $arFilter, false, array());
        $rsSections->SetUrlTemplates("", $this->arParams["SECTION_URL"]);
        while($arSection = $rsSections->GetNext())
        {
            $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection["IBLOCK_ID"], $arSection["ID"]);
            $arSection["IPROPERTY_VALUES"] = $ipropValues->getValues();

            $arSection['RELATIVE_DEPTH_LEVEL'] = $arSection['DEPTH_LEVEL'] - $intSectionDepth;

            $arButtons = CIBlock::GetPanelButtons(
                $arSection["IBLOCK_ID"],
                0,
                $arSection["ID"],
                array("SESSID"=>false, "CATALOG"=>true)
            );
            $arSection["EDIT_LINK"] = $arButtons["edit"]["edit_section"]["ACTION_URL"];
            $arSection["DELETE_LINK"] = $arButtons["edit"]["delete_section"]["ACTION_URL"];

            $arResult["SECTIONS"][]=$arSection;
        }

        if (count($arResult["SECTIONS"]) <= 0 && is_array($arResult["SECTION"]) && $this->arParams["SECTION_ID"] > 0)
        {
            $this->_page = "new_ticket";
        }

        $this->arResult = $arResult;
    }

    /**
     * выполняет действия после выполения компонента, например установка заголовков из кеша
     */
    protected function executeEpilog()
    {
        global $APPLICATION;
        //установка хлебных крошек
        if($this->arParams["ADD_SECTIONS_CHAIN"] && isset($this->arResult["SECTION"]) && is_array($this->arResult["SECTION"]["PATH"]))
        {
            foreach($this->arResult["SECTION"]["PATH"] as $arPath)
            {
                if (isset($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
                    $APPLICATION->AddChainItem($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arPath["~SECTION_PAGE_URL"]);
                else
                    $APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
            }
        }
        if ($this->arParams["SET_TITLE"] == "Y" && isset($this->arResult["SECTION"]) && strlen($this->arResult["SECTION"]["NAME"]))
        {
            $APPLICATION->SetTitle($this->arResult["SECTION"]["NAME"]);
        }
    }

    /**
     * выполняет логику работы компонента
     */
    public function executeComponent()
    {
        try
        {
            $this->checkModules();
            $this->checkParams();
            $this->executeProlog();
            if ($this->startResultCache())
            {
                $this->getResult();
                $this->includeComponentTemplate($this->_page);
            }
            $this->executeEpilog();
        }
        catch (Exception $e)
        {
            $this->abortResultCache();
            ShowError($e->getMessage());
        }
    }
}

?>