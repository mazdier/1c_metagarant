<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}

class FreSupportTicketCreateComponent extends \CBitrixComponent
{
    protected $_page = '';
    protected $_sectionId = '';

    /**
     * проверяет заполнение обязательных параметров
     * @throws \Bitrix\Main\ArgumentNullException
     */
    protected function checkParams()
    {
        if ($this->arParams['SECTION_ID'] <= 0)
        {
            throw new \Bitrix\Main\ArgumentNullException("SECTION_ID");
        }
        if ($this->arParams['IBLOCK_ID'] <= 0)
        {
            throw new \Bitrix\Main\ArgumentNullException('IBLOCK_ID');
        }
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
     * получение результатов
     */
    protected function getResult()
    {
        if (!CModule::IncludeModule("support"))
        {
            ShowError(GetMessage("MODULE_NOT_INSTALL"));
            return;
        }

        global $USER;
        global $APPLICATION;
        global $USER_FIELD_MANAGER;
        //Post
        $strError = "";
        if (isset($_REQUEST["add-ticket"]) && check_bitrix_sessid("key") && $USER->IsAuthorized())
        {
            $ID = intval($_REQUEST["ID"]);

            if ($ID <=0)
            {
                if (strlen(trim($_REQUEST["TITLE"]))<=0)
                    $strError .= GetMessage("SUP_FORGOT_TITLE")."<br>";

                if (strlen(trim($_REQUEST["MESSAGE"]))<=0)
                    $strError .= GetMessage("SUP_FORGOT_MESSAGE")."<br>";
            }

            $arFILES = array();
            if (is_array($_FILES) && count($_FILES)>0)
            {
                foreach ($_FILES as $key => $arFILE)
                {
                    if (strlen($arFILE["name"])>0)
                    {
                        $arFILE["MODULE_ID"] = "support";
                        $arFILES[] = $arFILE;
                    }
                }
            }

            if (is_array($arFILES) && count($arFILES)>0)
            {
                $max_size = COption::GetOptionString("support", "SUPPORT_MAX_FILESIZE");
                $max_size = intval($max_size)*1024;

                foreach ($arFILES as $key => $arFILE)
                {
                    if (intval($arFILE["size"])>$max_size || intval($arFILE["error"])>0)
                        $strError .= str_replace("#FILE_NAME#", $arFILE["name"], GetMessage("SUP_MAX_FILE_SIZE_EXCEEDING"))."<br>";
                }
            }

            if ($strError == "")
            {
                // check before writing,  user access to ticket
                $bSetTicket = false;
                if ($ID > 0)
                {
                    if (CTicket::IsAdmin())
                        $bSetTicket = true;
                    else
                    {
                        $rsTicket = CTicket::GetByID($ID, SITE_ID, $check_rights = "Y", $get_user_name = "N", $get_extra_names = "N");
                        if ($arTicket = $rsTicket->GetNext())
                            $bSetTicket = true;
                    }
                }
                else
                {
                    $bSetTicket = true;
                }

                if ($bSetTicket)
                {
                    if ($_REQUEST["OPEN"]=="Y")
                        $_REQUEST["CLOSE"]="N";
                    if ($_REQUEST["CLOSE"]=="Y")
                        $_REQUEST["OPEN"]="N";

                    $arFields = array(
                        'SITE_ID'					=> SITE_ID,
                        'CLOSE'						=> $_REQUEST['CLOSE'],
                        'TITLE'						=> $_REQUEST['TITLE'],
                        'CRITICALITY_ID'			=> $_REQUEST['CRITICALITY_ID'],
                        'CATEGORY_ID'				=> $this->_getSupportCategoryId(),
                        'MARK_ID'					=> $_REQUEST['MARK_ID'],
                        'MESSAGE'					=> $_REQUEST['MESSAGE'],
                        'HIDDEN'					=> 'N',
                        'FILES'						=> $arFILES,
                        'COUPON'					=> $_REQUEST['COUPON'],
                        'PUBLIC_EDIT_URL'			=> "",
                    );

                    $arrUF = $USER_FIELD_MANAGER->GetUserFields( "SUPPORT", 0, LANGUAGE_ID );

                    foreach( $_REQUEST as $k => $v )
                    {
                        if( array_key_exists( $k, $arrUF ) )
                        {
                            $arFields[$k] = $v;
                        }
                    }

                    $ID = CTicket::SetTicket($arFields, $ID, "Y", $NOTIFY = "Y");
                    if (intval($ID)>0)
                    {
                        if ($bpId = $this->_getBpId())
                        {
                            $this->_startBizProc($ID);
                        }
                        LocalRedirect("/support/ticket/");
                    }
                    else
                    {
                        $ex = $APPLICATION->GetException();
                        if ($ex)
                        {
                            $strError .= $ex->GetString() . '<br>';
                        }
                        else
                        {
                            $strError .= GetMessage('SUP_ERROR') . '<br>';
                        }
                    }
                }
                else
                {
                    LocalRedirect("/support/ticket/");
                }
            }
        }
        $this->arResult["ERRORS"] = $strError;
        $this->arResult["TICKET_NAME"] = $this->arParams["SECTION_NAME"];
        $this->arResult["QUESTIONS"] = $this->_getTicketQuestions();
    }

    protected function _getTicketQuestions()
    {
        $arResult = array();
        $arOrder = array("SORT" => "ASC");
        $arFilter = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
            "SECTION_ID" => $this->arParams["SECTION_ID"]
        );
        $arSelectFields = array("ID", "ACTIVE", "NAME", "IBLOCK_ID", "PROPERTY_SUPPORT_PROPERTY_CODE");
        $rsElements = CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
        while ($arElement = $rsElements->Fetch())
        {
            switch ($this->_page)
            {
                case "":
                    $arQuestion = array(
                        "QUESTION_NAME" => $arElement["NAME"],
                        "SUPPORT_PROPERTY_CODE" => $arElement["PROPERTY_SUPPORT_PROPERTY_CODE_VALUE"],
                        "SUPPORT_PROPERTY_INFO" => $this->_getUserFieldInfo($arElement["PROPERTY_SUPPORT_PROPERTY_CODE_VALUE"], $arElement["NAME"])
                    );
                    break;
                default:
                    $arQuestion = array(
                        "QUESTION_NAME" => $arElement["NAME"],
                        "SUPPORT_PROPERTY_CODE" => $arElement["PROPERTY_SUPPORT_PROPERTY_CODE_VALUE"],
                        "SUPPORT_PROPERTY_INFO" => $this->_getUserFieldInfo($arElement["PROPERTY_SUPPORT_PROPERTY_CODE_VALUE"], $arElement["NAME"])
                    );
                    break;
            }
            $arResult[] = $arQuestion;
        }
        return $arResult;
    }

    protected function _getUserFieldInfo($fieldCode, $labelTxt)
    {
        $arResult = array();
        global $USER_FIELD_MANAGER;
        if ($arUserField = CUserTypeEntity::GetList(array(), array("FIELD_NAME" => $fieldCode))->Fetch())
        {
            $arResult = $arUserField;
            $arResult["USER_TYPE"] = $USER_FIELD_MANAGER->GetUserType($arUserField["USER_TYPE_ID"]);
            $labelTxt .= $arResult["MANDATORY"] == "Y" ? "*" : "";
            $arResult["HTML"] = $this->_getUserFieldHTML1($arResult, array(
                "LABEL_HTML" => $labelTxt,
                "VALUE" => $_REQUEST[$fieldCode]
            ));
        }
        return $arResult;
    }

    //только элементы формы
    protected function _getUserFieldHTML($arUserField, $arForm = array())
    {
        global $USER_FIELD_MANAGER;
        if (is_callable(array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtml")))
        {
            $js = $USER_FIELD_MANAGER->ShowScript();

            if (!isset($arForm["VALUE"]))
            {
                $form_value = $arUserField["VALUE"];
            }
            elseif ($arUserField["USER_TYPE"]["BASE_TYPE"] == "file")
            {
                $form_value = $GLOBALS[$arUserField["FIELD_NAME"] . "_old_id"];
            }
            elseif ($arUserField["EDIT_IN_LIST"] == "N")
            {
                $form_value = $arUserField["VALUE"];
            }
            else
            {
                $form_value = $arForm["VALUE"];
            }

            if ($arUserField["MULTIPLE"] == "N")
            {
                $html = call_user_func_array(
                    array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtml"),
                    array(
                        $arUserField,
                        array(
                            "NAME" => $arUserField["FIELD_NAME"],
                            "VALUE" => is_array($form_value) ? $form_value : htmlspecialcharsbx($form_value)
                        ),
                    )
                );

                return $html.$js;
            }
            elseif (is_callable(array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtmlmulty")))
            {
                if (!is_array($form_value))
                {
                    $form_value = array();
                }
                foreach ($form_value as $key => $value)
                {
                    $form_value[$key] = htmlspecialcharsbx($value);
                }

                $html = call_user_func_array(
                    array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtmlmulty"),
                    array(
                        $arUserField,
                        array(
                            "NAME" => $arUserField["FIELD_NAME"] . "[]",
                            "VALUE" => $form_value
                        ),
                    )
                );

                return $html.$js;
            }
            else
            {
                if (!is_array($form_value))
                {
                    $form_value = array();
                }
                $html = "";
                $i = -1;
                foreach ($form_value as $i => $value)
                {

                    if (
                        (is_array($value) && (strlen(implode("", $value)) > 0))
                        || ((!is_array($value)) && (strlen($value) > 0))
                    )
                    {
                        $html .= '<tr><td>' . call_user_func_array(
                                array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtml"),
                                array(
                                    $arUserField,
                                    array(
                                        "NAME" => $arUserField["FIELD_NAME"] . "[" . $i . "]",
                                        "VALUE" => htmlspecialcharsbx($value),
                                    ),
                                )
                            ) . '</td></tr>';
                    }
                }
                //Add multiple values support
                $rowClass = "";
                $FIELD_NAME_X = str_replace('_', 'x', $arUserField["FIELD_NAME"]);
                $fieldHtml = call_user_func_array(
                    array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtml"),
                    array(
                        $arUserField,
                        array(
                            "NAME" => $arUserField["FIELD_NAME"] . "[" . ($i + 1) . "]",
                            "VALUE" => "",
                            "ROWCLASS" => &$rowClass
                        ),
                    )
                );

                return '<tr' . ($rowClass != '' ? ' class="' . $rowClass . '"' : '') . '><td class="adm-detail-valign-top">' . $strLabelHTML . '</td><td>' .
                '<table id="table_' . $arUserField["FIELD_NAME"] . '">' . $html . '<tr><td>' . $fieldHtml . '</td></tr>' .
                '<tr><td style="padding-top: 6px;"><input type="button" value="' . GetMessage(
                    "USER_TYPE_PROP_ADD"
                ) . '" onClick="addNewRow(\'table_' . $arUserField["FIELD_NAME"] . '\', /(' . $FIELD_NAME_X . '|' . $arUserField["FIELD_NAME"] . '|' . $arUserField["FIELD_NAME"] . '_old_id)[x\[]([0-9]*)[x\]]/gi, 2)"></td></tr>' .
                "<script type=\"text/javascript\">BX.addCustomEvent('onAutoSaveRestore', function(ob, data) {for (var i in data){if (i.substring(0," . (strlen(
                        $arUserField['FIELD_NAME']
                    ) + 1) . ")=='" . CUtil::JSEscape($arUserField['FIELD_NAME']) . "['){" .
                'addNewRow(\'table_' . $arUserField["FIELD_NAME"] . '\', /(' . $FIELD_NAME_X . '|' . $arUserField["FIELD_NAME"] . '|' . $arUserField["FIELD_NAME"] . '_old_id)[x\[]([0-9]*)[x\[]/gi, 2)' .
                "}}})</script>" .
                '</table>' .
                '</td></tr>' . $js;
            }
        }
        return "";
    }

    //с таблицей
    protected function _getUserFieldHTML1($arUserField, $arForm = array())
    {
        global $USER_FIELD_MANAGER;
        $strLabelHTML = $arForm["LABEL_HTML"];
        if ($arUserField["USER_TYPE"])
        {
            if (is_callable(array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtml")))
            {
                $js = $USER_FIELD_MANAGER->ShowScript();

                if ($arUserField["USER_TYPE"]["BASE_TYPE"] == "file")
                {
                    $form_value = $GLOBALS[$arUserField["FIELD_NAME"] . "_old_id"];
                }
                elseif ($arUserField["EDIT_IN_LIST"] == "N")
                {
                    $form_value = $arUserField["VALUE"];
                }

                if (!empty($arForm["VALUE"]) && empty($form_value))
                {
                    $form_value = $arForm["VALUE"];
                }
                elseif(empty($form_value))
                {
                    $form_value = $arUserField["VALUE"];
                }

                if ($arUserField["MULTIPLE"] == "N")
                {
                    $valign = "";
                    $rowClass = "";
                    $html = call_user_func_array(
                        array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtml"),
                        array(
                            $arUserField,
                            array(
                                "NAME" => $arUserField["FIELD_NAME"],
                                "VALUE" => is_array($form_value) ? $form_value : htmlspecialcharsbx($form_value),
                                "VALIGN" => &$valign,
                                "ROWCLASS" => &$rowClass
                            ),
                        )
                    );

                    return '<tr' . ($rowClass != '' ? ' class="' . $rowClass . '"' : '') . '><td' . ($valign <> 'middle' ? ' class="adm-detail-valign-top"' : '') . ' width="40%">' . $strLabelHTML . '</td><td width="60%">' . $html . '</td></tr>' . $js;
                }
                elseif (is_callable(array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtmlmulty")))
                {
                    if (!is_array($form_value))
                    {
                        $form_value = array();
                    }
                    foreach ($form_value as $key => $value)
                    {
                        $form_value[$key] = htmlspecialcharsbx($value);
                    }

                    $rowClass = "";
                    $html = call_user_func_array(
                        array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtmlmulty"),
                        array(
                            $arUserField,
                            array(
                                "NAME" => $arUserField["FIELD_NAME"] . "[]",
                                "VALUE" => $form_value,
                                "ROWCLASS" => &$rowClass
                            ),
                        )
                    );

                    return '<tr' . ($rowClass != '' ? ' class="' . $rowClass . '"' : '') . '><td class="adm-detail-valign-top">' . $strLabelHTML . '</td><td>' . $html . '</td></tr>' . $js;
                }
                else
                {
                    if (!is_array($form_value))
                    {
                        $form_value = array();
                    }
                    $html = "";
                    $i = -1;
                    foreach ($form_value as $i => $value)
                    {

                        if (
                            (is_array($value) && (strlen(implode("", $value)) > 0))
                            || ((!is_array($value)) && (strlen($value) > 0))
                        )
                        {
                            $html .= '<tr><td>' . call_user_func_array(
                                    array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtml"),
                                    array(
                                        $arUserField,
                                        array(
                                            "NAME" => $arUserField["FIELD_NAME"] . "[" . $i . "]",
                                            "VALUE" => htmlspecialcharsbx($value),
                                        ),
                                    )
                                ) . '</td></tr>';
                        }
                    }
                    //Add multiple values support
                    $rowClass = "";
                    $FIELD_NAME_X = str_replace('_', 'x', $arUserField["FIELD_NAME"]);
                    $fieldHtml = call_user_func_array(
                        array($arUserField["USER_TYPE"]["CLASS_NAME"], "geteditformhtml"),
                        array(
                            $arUserField,
                            array(
                                "NAME" => $arUserField["FIELD_NAME"] . "[" . ($i + 1) . "]",
                                "VALUE" => "",
                                "ROWCLASS" => &$rowClass
                            ),
                        )
                    );

                    return '<tr' . ($rowClass != '' ? ' class="' . $rowClass . '"' : '') . '><td class="adm-detail-valign-top">' . $strLabelHTML . '</td><td>' .
                    '<table id="table_' . $arUserField["FIELD_NAME"] . '">' . $html . '<tr><td>' . $fieldHtml . '</td></tr>' .
                    '<tr><td style="padding-top: 6px;"><input type="button" value="' . GetMessage(
                        "USER_TYPE_PROP_ADD"
                    ) . '" onClick="addNewRow(\'table_' . $arUserField["FIELD_NAME"] . '\', /(' . $FIELD_NAME_X . '|' . $arUserField["FIELD_NAME"] . '|' . $arUserField["FIELD_NAME"] . '_old_id)[x\[]([0-9]*)[x\]]/gi, 2)"></td></tr>' .
                    "<script type=\"text/javascript\">BX.addCustomEvent('onAutoSaveRestore', function(ob, data) {for (var i in data){if (i.substring(0," . (strlen(
                            $arUserField['FIELD_NAME']
                        ) + 1) . ")=='" . CUtil::JSEscape($arUserField['FIELD_NAME']) . "['){" .
                    'addNewRow(\'table_' . $arUserField["FIELD_NAME"] . '\', /(' . $FIELD_NAME_X . '|' . $arUserField["FIELD_NAME"] . '|' . $arUserField["FIELD_NAME"] . '_old_id)[x\[]([0-9]*)[x\[]/gi, 2)' .
                    "}}})</script>" .
                    '</table>' .
                    '</td></tr>' . $js;
                }
            }
        }

        return '';
    }

    protected function _getSupportCategoryId()
    {
        $arSort = array("SORT" => "ASC");
        $arFilter = array("IBLOCK_ID" => $this->arParams["IBLOCK_ID"], "ID" => $this->arParams["SECTION_ID"]);
        $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, array("UF_CATEGORY_ID"));
        if ($arSection = $rsSections->GetNext())
        {
            return $arSection["UF_CATEGORY_ID"];
        }
        return false;
    }

    protected function _getBpId()
    {
        $arSort = array("SORT" => "ASC");
        $arFilter = array("IBLOCK_ID" => $this->arParams["IBLOCK_ID"], "ID" => $this->arParams["SECTION_ID"]);
        $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, array("UF_BP_ID"));
        if ($arSection = $rsSections->GetNext())
        {
            return $arSection["UF_BP_ID"];
        }
        return false;
    }

    protected function _startBizProc($ticketId)
    {
        CModule::IncludeModule("bizproc");
        $documentId = CBPVirtualDocument::CreateDocument(
            0,
            array(
                "IBLOCK_ID" => 73,
                "NAME" => "Обращение",
                "CREATED_BY" => "user_" . $GLOBALS["USER"]->GetID(),
                "PROPERTY_AQSGDPR" => $ticketId,
                "PROPERTY_AQSGDRW" => $this->_getSupportCategoryId(),
            )
        );

        $arErrorsTmp = array();

        $mas_params = array(
            "TargetUser" => "user_" . intval($GLOBALS["USER"]->GetID())
        );
        $wfId = CBPDocument::StartWorkflow(
            $this->_getBpId(),
            array("bizproc", "CBPVirtualDocument", $documentId),
            $mas_params,
            $arErrorsTmp
        );

        //var_dump($mas_params);
    }

    protected function _setTemplatePage()
    {
        $arSection = CIBlockSection::GetByID($this->arParams["SECTION_ID"])->Fetch();
        $this->_page = $arSection["CODE"];
    }

    /**
     * выполняет логику работы компонента
     */
    public function executeComponent()
    {
        try
        {
            $this->checkParams();
            $this->_setTemplatePage();
            $this->getResult();
            $this->includeComponentTemplate($this->_page);
        }
        catch (Exception $e)
        {
            ShowError($e->getMessage());
        }
    }
}

?>