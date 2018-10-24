<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc as Loc;
use Bitrix\Main\SystemException;

class CInfinityBlockComponent extends CBitrixComponent
{
    protected $_elementId = 0;
    protected $_defaultPhotoSrc = "";

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
     * @throws Main\ArgumentNullException
     * @internal param $params
     * @internal param array $arParams
     */
    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams["ENTITY_ID"]) || empty($arParams["ENTITY_ID"]))
        {
            $arParams["ENTITY_ID"] = "LEAD";
        }
        $arParams["ELEMENT_ID"] = intval($arParams["ELEMENT_ID"]);
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

            $this->_getResult();

            $this->includeComponentTemplate();
        }
        catch (Exception $e)
        {
            ShowError($e->getMessage());
        }
    }

    protected function _getResult()
    {
        $this->_elementId = $this->arParams["ELEMENT_ID"];
        $this->_defaultPhotoSrc = $this->getPath()."/templates/".$this->GetTemplateName()."/img/hidef-avatar.png"; //хрень какая-то

        switch($this->arParams["ENTITY_ID"])
        {
            case "COMPANY":
                $arCompany = CCrmCompany::GetByID($this->_elementId);
                if (!empty($arCompany["LOGO"]))
                {
                    $arCompany["IMAGE"] = CFile::GetPath($arCompany["LOGO"]);
                }
                else
                {
                    $arCompany["IMAGE"] = $this->_defaultPhotoSrc;
                }

                $this->arResult = $arCompany;
                break;
            case "CONTACT":
                if (!$arContact = CCrmContact::GetByID($this->_elementId))
                {
                    throw new Exception(Loc::getMessage("CONTACT_NOT_FOUND", array("#ID#" => $this->_elementId)));
                }
                if (!empty($arContact["PHOTO"]))
                {
                    $arContact["IMAGE"] = CFile::GetPath($arContact["PHOTO"]);
                }
                else
                {
                    $arContact["IMAGE"] = $this->_defaultPhotoSrc;
                }

                if (intval($arContact["COMPANY_ID"]))
                {
                    $arCompany = CCrmCompany::GetByID(intval($arContact["COMPANY_ID"]));
                    if (!empty($arCompany["LOGO"]))
                    {
                        $arCompany["IMAGE"] = CFile::GetPath($arCompany["LOGO"]);
                    }
                    else
                    {
                        $arCompany["IMAGE"] = $this->_defaultPhotoSrc;
                    }
                    $arContact["COMPANY"] = $arCompany;
                }

                //<editor-fold desc="Work with Birthday">
                //$arContact["ENTITY_ID"] = $this->arParams["ENTITY_ID"];  //контакт или компания /\ бред какой-то
                if ($arContact["BIRTHDATE"] != "")
                {
                    $datetime1 = date_create('now');
                    $t1 = date_format($datetime1, 'Y-m-d');
                    $time1 = date_create($t1);

                    $datetime2 = date_create($arContact["BIRTHDATE"]);
                    $t2 = date_format($datetime2, 'Y-m-d');
                    $time2 = date_create($t2);

                    $interval = date_diff($time1, $time2);

                    $date_birth = (int)$interval->format('%R%a');
                    $numb_birth = (int)$interval->format('%a');
                    if ($numb_birth <= 3)
                    {
                        if ($date_birth < 0)
                        {
                            $arContact["STR_BIRTHDAY"] = Loc::getMessage("BIRTHDAY_WAS_X_DAYS_AGO", array("#NUM_DAYS#" => $numb_birth));
                        }
                        elseif ($date_birth == 0)
                        {
                            $arContact["STR_BIRTHDAY"] = Loc::getMessage("BIRTHDAY_TODAY");
                            $arContact["IS_BIRTHDAY_TODAY"] = "Y";
                        }
                        elseif ($date_birth > 0)
                        {
                            $arContact["STR_BIRTHDAY"] = Loc::getMessage("BIRTHDAY_WILL_BE_X_DAYS", array("#NUM_DAYS#" => $numb_birth));
                        }
                    }
                    elseif ($numb_birth >= 3)
                    {
                        $arContact["STR_BIRTHDAY"] = Loc::getMessage("BIRTHDAY_DATE", array("#DATE#" => $datetime2->format('d.m.Y')));
                    }
                    unset($datetime1);
                    unset($datetime2);
                }
                //</editor-fold>

                $this->arResult = $arContact;
                break;
            case "LEAD":
                if (!$arLead = CCrmLead::GetByID($this->_elementId))
                {
                    throw new Exception(Loc::getMessage("LEAD_NOT_FOUND", array("#ID#" => $this->_elementId)));
                }
                $this->arResult = $arLead;
                $this->arResult["IMAGE"] = $this->_defaultPhotoSrc;
                break;
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
     * @throws SystemException
     */
    protected function _checkParams()
    {
        if (empty($this->arParams["ELEMENT_ID"]) || $this->arParams["ELEMENT_ID"] <= 0)
        {
            throw new ArgumentNullException('ELEMENT_ID');
        }
        if (!isset($this->arParams["INFINITY_ADDRESS"]) || empty($this->arParams["INFINITY_ADDRESS"]))
        {
            throw new ArgumentNullException('INFINITY_ADDRESS');
        }
        if (!isset($this->arParams["INFINITY_USER_ID"]) || empty($this->arParams["INFINITY_USER_ID"]))
        {
            throw new ArgumentNullException('INFINITY_USER_ID');
        }
    }

    private function _printAr($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
}

?>