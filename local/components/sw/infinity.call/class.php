<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc as Loc;
use Bitrix\Main\SystemException;

class CInfinityCallComponent extends CBitrixComponent
{
    protected $_templatePage = "";
    protected $_incomingCallNum = "";
    protected $_infinityAddress = "";
    protected $_infinityUserId = "";
    protected $_infinityCallId = "";
    protected $_curBxUserId = "";
    protected $_phoneForSearch = "";

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
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    /**
     * выполняет логику работы компонента
     */
    public function executeComponent()
    {
        try
        {
            $this->_checkParams();
            $this->_checkModules();
            $this->_prepareParams();

            $this->_getResult();
            //$this->_printAr($this->arResult);
            $this->includeComponentTemplate($this->_templatePage);
        }
        catch (Exception $e)
        {
            ShowError($e->getMessage());
        }
    }

    protected function _getResult()
    {
        if ($this->_findEntity("CONTACT"))
        {
            $this->_templatePage = "contact";
        }
        elseif ($this->_findEntity("COMPANY"))
        {
            $this->_templatePage = "company";
        }
        elseif ($this->_findLead())
        {
            if ($this->arResult["ITEMS"][0]["STATUS_ID"] == "CONVERTED")
            {
                $this->_templatePage = "show_lead";
            }
            else
            {
                $this->_templatePage = "edit_lead";
            }
        }
        else
        {
            $this->_addLead();
            $this->_templatePage = "new_lead";
        }

        //если несколько сущностей и выбрана сущность
        if ($this->arResult["IS_SEVERAL_ENTITIES"] == "Y"
            && isset($_REQUEST["ajax"])
            && $_REQUEST["ajax"] == "Y"
            && intval($_REQUEST["entity_id"]))
        {
            $this->arResult['ID'] = $_REQUEST["entity_id"];
        }
    }

    /**
     * проверяет подключение необходиимых модулей
     * @throws LoaderException
     */
    protected function _checkModules()
    {
        if (!Main\Loader::includeModule('crm'))
            throw new LoaderException(Loc::getMessage('CRM_MODULE_NOT_INSTALLED'));
    }

    /**
     * проверяет заполнение обязательных параметров
     * @throws ArgumentNullException
     */
    protected function _checkParams()
    {
        if (!isset($this->arParams["INFINITY_ADDRESS"]) || empty($this->arParams["INFINITY_ADDRESS"]))
        {
            throw new ArgumentNullException('INFINITY_ADDRESS');
        }
        if (!isset($this->arParams["INFINITY_USER_ID"]) || empty($this->arParams["INFINITY_USER_ID"]))
        {
            throw new ArgumentNullException('INFINITY_USER_ID');
        }
        if (!isset($this->arParams["INFINITY_CALL_ID"]) || empty($this->arParams["INFINITY_CALL_ID"]))
        {
            throw new ArgumentNullException('INFINITY_CALL_ID');
        }
    }

    protected function _prepareParams()
    {
        global $USER;
        $this->_curBxUserId = $USER->GetID();
        $this->_incomingCallNum = $this->arParams["PHONE"];
        $this->_infinityAddress = $this->arParams["INFINITY_ADDRESS"];
        $this->_infinityUserId = $this->arParams["INFINITY_USER_ID"];
        $this->_infinityCallId = $this->arParams["INFINITY_CALL_ID"];

        $this->_phoneForSearch = $this->_incomingCallNum;
        if (isset($this->_incomingCallNum) && !empty($this->_incomingCallNum))
        {
            if (strlen($this->_incomingCallNum) > 9)
            {
                $this->_phoneForSearch = substr($this->_incomingCallNum, -9, 9);
            }
            elseif (strlen($this->_incomingCallNum) == 7)
            {
                $this->_phoneForSearch = "17" . $this->_incomingCallNum;
                $this->_incomingCallNum = $this->_phoneForSearch;
            }
        }
    }

    /**
     * @param $entityType
     * @return bool
     */
    protected function _findEntity($entityType)
    {
        $arResult = array();
        $obCFM = CCrmFieldMulti::GetListEx(
            array("ID" => "DESC"),
            array(
                'ENTITY_ID' => array($entityType),
                "TYPE_ID" => "PHONE",
                '?VALUE' => $this->_phoneForSearch
            ),
            false,
            false,
            array("ID", "ENTITY_ID", "ELEMENT_ID")
        );
        while ($arCFM = $obCFM->Fetch())
        {
            $arResult[] = array(
                "ID" => $arCFM["ELEMENT_ID"],
                "ENTITY_ID" => $arCFM["ENTITY_ID"]
            );
        }

        if (count($arResult) > 0)
        {
            $this->arResult["ENTITY_ID"] = $entityType;
            $this->arResult['ID'] = $arResult[0]["ID"];
            $this->arResult["ITEMS"] = $arResult;

            if (count($arResult) > 1)
            {
                $this->arResult["IS_SEVERAL_ENTITIES"] = "Y";

                //для title'ов на "вкладках"
                foreach($this->arResult["ITEMS"] as &$arItem)
                {
                    if ($entityType == "CONTACT")
                    {
                        $arItem = CCrmContact::GetByID($arItem["ID"]);
                        $arItem["TITLE"] = $arItem["FULL_NAME"];
                    }
                    elseif ($entityType == "COMPANY")
                    {
                        $arItem = CCrmCompany::GetByID($arItem["ID"]);
                    }
                }
            }
            else
            {
                $this->arResult["IS_SEVERAL_ENTITIES"] = "N";
            }

            return true;
        }

        return false;
    }

    protected function _findLead()
    {
        $arResult = array();
        $obCFM = CCrmFieldMulti::GetListEx(
            array("ID" => "DESC"),
            array(
                'ENTITY_ID' => array("LEAD"),
                "TYPE_ID" => "PHONE",
                '?VALUE' => $this->_phoneForSearch
            ),
            false,
            false,
            array("ID", "ENTITY_ID", "ELEMENT_ID")
        );
        while ($arCFM = $obCFM->Fetch())
        {
            $arResult[] = array(
                "ID" => $arCFM["ELEMENT_ID"],
                "ENTITY_ID" => $arCFM["ENTITY_ID"]
            );
        }

        if (count($arResult) > 0)
        {
            foreach ($arResult as &$arEntity)
            {
                $arEntity = CCrmLead::GetByID($arEntity["ID"]);
            }

            $this->arResult["ENTITY_ID"] = "LEAD";
            $this->arResult['ID'] = $arResult[0]["ID"];
            $this->arResult["ITEMS"] = $arResult;

            if (count($arResult) > 1)
            {
                $this->arResult["IS_SEVERAL_ENTITIES"] = "Y";
            }
            else
            {
                $this->arResult["IS_SEVERAL_ENTITIES"] = "N";
            }

            return true;
        }

        return false;
    }

    /**
     * Добавляет лид в CRM с текущим номером телефона
     * @throws Exception
     */
    protected function _addLead()
    {
        $CCrmLead = new CCrmLead();
        $title = str_replace("#PHONE#", $this->_incomingCallNum, $this->arParams["LEAD_NAME_TEMPLATE"]);
        $arFields = array(
            'TITLE' => $title,
            'SOURCE_ID' => 'CALL',
            'STATUS_ID' => 'NEW',
            'OPENED' => 'Y',
            'ASSIGNED_BY_ID' => $this->_curBxUserId,
            'FM' => array(
                'PHONE' => array(
                    'n1' => array(
                        'VALUE' => $this->_incomingCallNum,
                        'VALUE_TYPE' => 'WORK'
                    )
                )
            ),
            'CURRENCY_ID' => 'RUB',
            'EXCH_RATE' => 1
        );

        if ($id_lead = $CCrmLead->Add($arFields, true, array('REGISTER_SONET_EVENT' => true)))
        {
            $this->arResult["ENTITY_ID"] = "LEAD";
            $this->arResult['ID'] = $id_lead;
        }
        else
        {
            throw new Exception($CCrmLead->LAST_ERROR);
        }
        return true;
    }

    private function _printAr($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
}

?>