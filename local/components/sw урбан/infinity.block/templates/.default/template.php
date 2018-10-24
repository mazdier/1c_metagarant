<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?
use \Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);
CJSCore::Init('jquery');
?>
<div style="display:none" class="hid">
    <?//лайфках для открытия звонка

    $APPLICATION->IncludeComponent(
        "sw:crm.activity.editor",
        "",
        array(
            "ENTITY_COMMUNICATIONS" => array(
                "ENTITY_TYPE" => 2,
                "ENTITY_ID" => 1,
                "COMMUNICATION_TYPE" => "PHONE",
                "OWNER_ID" => $arParams["ELEMENT_ID"],
                "OWNER_TYPE" => $arParams["ENTITY_ID"]
            )
        ),
        $component
    );

    ?>
</div>
<div class="block_infin" style="  width: 300px;">
    <div class="crm-feed-right-side" style="min-width: 300px;">
        <div class="crm-right-block_inf">
            <? if($arParams["ENTITY_ID"] == "CONTACT"): ?>
                <h2 class="odin" style="  margin-left: -5%;">
                    <a target="_blank" href="/crm/contact/edit/<?= $arResult['ID'] ?>/"
                       onclick="contact_edit(<?= $arResult['ID'] ?>); return false;">
                        <?= $arResult["FULL_NAME"] ?>
                    </a>
                </h2>

                <? if (intval($arResult['COMPANY_ID'])): ?>
                    <h2 class="odin_min" style="margin-left: -5%;">
                        <?= $arResult["POST"]; ?>
                        <a target="_blank" href="/crm/company/edit/<?= $arResult['COMPANY_ID'] ?>/"
                           onclick="company_edit(<?= $arResult['COMPANY_ID'] ?>); return false;">
                            <?= $arResult["COMPANY_TITLE"] ?>
                        </a>
                    </h2>
                <? endif; ?>
            <? elseif($arParams["ENTITY_ID"] == "COMPANY"): ?>
                <h2 class="odin" style="  margin-left: -5%;">
                    <a target="_blank" href="/crm/contact/edit/<?= $arResult['ID'] ?>/"
                       onclick="company_edit(<?= $arResult['ID'] ?>); return false;">
                        <?= $arResult["TITLE"] ?>
                    </a>
                </h2>
            <? else: ?>
                <h2 class="odin" style="  margin-left: -5%;">
                    <a href="#" onclick="javascript:void(0)">
                        <?= $arResult["TITLE"] ?>
                    </a>
                </h2>
            <? endif; ?>


            <span
                class="img-logo"
                style="background-image: url('<?= $arResult['IMAGE'] ?>');"
                <?= $arParams["ENTITY_ID"] == "LEAD" ? 'data-lead-id="'.$arResult['ID'].'""' : '';  ?>
                >
            </span>

            <? if ($arResult["BIRTHDATE"] != ""): ?>
                <h2 class="odin_min"
                    style="<?= $arResult["IS_BIRTHDAY_TODAY"] == "Y" ? 'color:red; ' : ''; ?>margin-left: -14%;">
                    <?= $arResult["STR_BIRTHDAY"] ?>
                </h2>
            <? endif; ?>

            <h2 class="odin_zagol_comment">Комментарий к звонку:</h2>

            <textarea class="comment_new" id="phone_comment"><?= $_REQUEST["phone_comment"]; ?></textarea>

            <div class="inf-btn">
                <img id="take_call" title="Поднять трубку"
                     src="<?= $templateFolder ?>/img/take_w.png">
                <img id="end_call" data-entity-id="<?= $arParams["ENTITY_ID"]; ?>" title="Завершить вызов"
                     src="<?= $templateFolder ?>/img/end_w.png">
                <img id="hold_call" class="" title="Удержать вызов"
                     src="<?= $templateFolder ?>/img/hold_w.png">
                <img id="fwd_call" title="Переадресовать вызов"
                     src="<?= $templateFolder ?>/img/per_w.png">
            </div>
        </div>
    </div>
</div>
<? if(isset($_REQUEST["call_id"]) && intval($_REQUEST["call_id"])): ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $.getJSON('<?=$templateFolder?>/ajax_update_activity_owner.php', {
                entity_type: '<?= $arParams["ENTITY_ID"]; ?>',
                entity_id: '<?= $arParams["ELEMENT_ID"]; ?>',
                activity_id: createdPhoneId
            }).success(function(data) {
                console.log(data);
                /*update activity list for use editing activity form*/
                var editor = BX.CrmActivityEditor.items['_crm_activity_grid_editor'];
                editor.setLocked(true);
                editor.setLockMessage("Пожалуйста, дождитесь обновления списка дел");
                editor.release();
                editor.removeActivityChangeHandler(this);
                BX.CrmInterfaceGridManager.reloadGrid("CRM_ACTIVITY_LIST_<?= strtolower($arParams["ENTITY_ID"]); ?>_<?= $arParams["ELEMENT_ID"]; ?>");
            });
        });
    </script>
<? endif; ?>
<? if(!isset($_REQUEST["ajax"]) || $_REQUEST["ajax"] != "Y"): ?>
    <script type="text/javascript">
    //var XHR = ("onload" in new XMLHttpRequest()) ? XMLHttpRequest : XDomainRequest;
    var getFilePathInterval = setInterval(getFilesPath, 2000);
    var isCreatedPhone = <?= isset($_REQUEST["cal_id"]) && intval($_REQUEST["call_id"]) ? 'true' : 'false'; ?>;
    var createdPhoneId = <?= isset($_REQUEST["cal_id"]) && intval($_REQUEST["call_id"]) ? intval($_REQUEST["call_id"]) : 0; ?>;

    function UpdateContent()
    {
        $.ajax({
            url: window.location.href,
            data: {
                ajax: "Y"
            },
            success: function(data)
            {
                $('div.infinity-page').html(data);
            }
        });
    }

    function contact_edit(id)
    {
        var popup = new BX.PopupWindow("my-popup", null, {
            closeIcon: {right: "12px", top: "10px"},
            titleBar: {
                content: BX.create("span", {
                    html: '<b>Просмотр контакта</b>',
                    'props': {'className': 'access-title-bar'}
                })
            },
            overlay: {opacity: 75},
            closeByEsc: true,
            draggable: {restrict: true},
            zIndex: '-500',
            buttons: [
                new BX.PopupWindowButton({
                    text: "Изменить",
                    className: "popup-window-button-accept",
                    events: {
                        click: function () {

                            $('#CRM_CONTACT_EDIT_V12_saveAndView').click();
                            setTimeout(this.popupWindow.close(), 1000);

                        }


                    }
                })
            ],
            events: {
                onPopupClose: function (popupWindow) {
                    popupWindow.destroy();
                }
            }
        });

        BX.ajax.get('<?=$templateFolder?>/ajax_contact_edit.php?edit=Y&id=' + id, function (data) {
            popup.setContent(data);
            popup.show();
        });
    }

    function company_edit(id)
    {
        var popup = new BX.PopupWindow("my-popup", null, {
            closeIcon: {right: "12px", top: "10px"},
            titleBar: {
                content: BX.create("span", {
                    html: '<b>Просмотр компании</b>',
                    'props': {'className': 'access-title-bar'}
                })
            },
            overlay: {opacity: 75},
            closeByEsc: true,
            draggable: {restrict: true},
            zIndex: '-500',
            buttons: [
                new BX.PopupWindowButton({
                    text: "Изменить",
                    className: "popup-window-button-accept",
                    events: {
                        click: function () {

                            $('#CRM_COMPANY_EDIT_V12_saveAndView').click();
                            setTimeout(this.popupWindow.close(), 1000);

                        }

                    }
                })
            ],
            events: {
                onPopupClose: function (popupWindow) {
                    popupWindow.destroy();
                }
            }
        });

        BX.ajax.get('<?=$templateFolder?>/ajax_company_edit.php?edit=Y&id=' + id, function (data) {
            popup.setContent(data);
            popup.show();
        });
    }

    function fwd_call()
    {
        $.getJSON(
            '<?=$templateFolder?>/ajax_get_active_call.php',
            {
                url: '<?= $arParams["INFINITY_ADDRESS"]; ?>',
                IDUser: '<?= $arParams["INFINITY_USER_ID"]; ?>',
                phone_id: '<?=$_REQUEST["phoneid"]?>'
            }
        ).success(function(msg) {
                if (msg.RESULT == null)
                {
                    alert("Не найден текущий звонок для переадресации");
                    return false;
                }
                var Dialog_per = new BX.CDialog({
                    title: "Переадресация вызова",
                    content_url: '<?=$templateFolder?>/ajax_department.php',
                    content_post: '',
                    width: 500,
                    height: 200,
                    overlay: {opacity: 75},
                    buttons:
                        [{
                            title: "ОК",
                            name: "ОК",
                            id: "ОК",
                            action: function ()
                            {
                                var id_dep = $("select[name=DEP]").val();
                                this.parentWindow.Close();

                                if (id_dep != "")
                                {
                                    var infinityUrl = '<?= $arParams["INFINITY_ADDRESS"]; ?>/call/quicktransfer/?IDUser=<?=$arParams["INFINITY_USER_ID"]?>&IDCall=<?= $_REQUEST["phoneid"]; ?>&Number=' + id_dep;
                                    $.getJSON('<?=$templateFolder?>/ajax_send_request.php', {url: infinityUrl});
                                    $('#take_call, #end_call_cont, #end_call, #fwd_call, #hold_call').css('display', 'none');
                                }
                                else
                                {
                                    alert("У выбранного департамента/контакта не установлен внутренний номер Infinity");
                                }
                            }

                        }]
                });
                Dialog_per.Show();
            }
        );
    }

    function getFilesPath()
    {
        if (isCreatedPhone)
        {
            clearInterval(getFilePathInterval);
            return;
        }
        $.getJSON('<?=$templateFolder?>/ajax_create_agent.php', {
            url: '<?= $arParams["INFINITY_ADDRESS"]; ?>',
            IDUser: '<?= $arParams["INFINITY_USER_ID"]; ?>',
            phone_id: '<?= $arParams["INFINITY_CALL_ID"]; ?>',
            entity_type: '<?= $arParams["ENTITY_ID"]; ?>',
            entity_id: '<?= $arParams["ELEMENT_ID"]; ?>',
            phone: '<?= $_REQUEST["phone"]; ?>',
            type: '<?= isset($_REQUEST["type"]) ? $_REQUEST["type"] : "incoming"  ?>'
        }).success(function(data) {
            //console.log(data);
            if (data == null)
            {
                return;
            }
            if (data.PHONE.STATUS == "OK" && data.RELATION.STATUS == "OK")
            {
                isCreatedPhone = true;
                createdPhoneId = data.PHONE.ID;
                /*update activity list for use editing activity form*/
                var editor = BX.CrmActivityEditor.items['_crm_activity_grid_editor'];
                editor.setLocked(true);
                editor.setLockMessage("Пожалуйста, дождитесь обновления списка дел");
                editor.release();
                editor.removeActivityChangeHandler(this);
                BX.CrmInterfaceGridManager.reloadGrid("CRM_ACTIVITY_LIST_<?= strtolower($arParams["ENTITY_ID"]); ?>_<?= $arParams["ELEMENT_ID"]; ?>");
            }
        });
    }

    $('#infinity-page').on('click', "#fwd_call", function()
    {
        fwd_call();
    });

    $('#infinity-page').on('click', "#hold_call", function()
    {
        var class_hold = $(this).attr("class");
        if(class_hold == "")
        {
            $(this).toggle(function() {
                $(this).animate(function(){$(this).attr('src','/local/components/sw/infinity.block/templates/.default/img/hold_g.png')}, 2000, 'linear');
            }, function() {
                $(this).animate(function(){$(this).attr('src','/local/components/sw/infinity.block/templates/.default/img/hold_w.png')}, 2000, 'swing');
            });

            var infinityUrl = encodeURI('<?=$arParams["INFINITY_ADDRESS"]?>/call/hold/?IDUser=<?=$arParams["INFINITY_USER_ID"]?>&IDCall=<?=$_REQUEST["phoneid"]?>');
            var jqxhr = $.getJSON('<?=$templateFolder?>/ajax_send_request.php', {url: infinityUrl})
                .success(function() {
                    $('#hold_call').addClass("on_hold_inf");
                }
            );
        }

        if(class_hold == "on_hold_inf")
        {
            var infinityUrl = encodeURI('<?=$arParams["INFINITY_ADDRESS"]?>/call/unhold/?IDUser=<?=$arParams["INFINITY_USER_ID"]?>&IDCall=<?=$_REQUEST["phoneid"]?>');
            var jqxhr = $.getJSON('<?=$templateFolder?>/ajax_send_request.php', {url: infinityUrl})
                .success(function() {
                    $('#hold_call').removeClass("on_hold_inf");
                }
            );
        }

    });

    $('#infinity-page').on('click', "#take_call", function()
    {
        var infinityUrl = encodeURI('<?=$arParams["INFINITY_ADDRESS"]?>/call/accept/?IDUser=<?=$_REQUEST["userid"]?>&IDCall=<?=$_REQUEST["phoneid"]?>');
        var jqxhr = $.getJSON('<?=$templateFolder?>/ajax_send_request.php', {url: infinityUrl})
            .success(function() {
                $('#take_call').css('display', 'none');
            }
        );

        $('.comment_new').focus();
    });

    $('#infinity-page').on('click', "#end_call", function()
    {
            var infinityUrl = encodeURI('<?=$arParams["INFINITY_ADDRESS"]?>/call/drop/?IDUser=<?=$_REQUEST["userid"]?>&IDCall=<?=$_REQUEST["phoneid"]?>');
            var jqxhr = $.getJSON('<?=$templateFolder?>/ajax_send_request.php', {url: infinityUrl})
            .success(function() {
                $('#take_call, #end_call_cont, #end_call, #fwd_call, #hold_call').css('display', 'none');
                if ($("#end_call").data('entityId') != 'LEAD')
                {
                    var desc = $("textarea#phone_comment").val();
                    if (isCreatedPhone)
                    {
                        BX.CrmActivityEditor.items['_crm_activity_grid_editor'].openActivityDialog(
                            BX.CrmDialogMode.edit,
                            createdPhoneId,
                            {}
                        );
                        /*edit phone activity window opens after 100ms, so attach a comment after 150ms*/
                        var intervalId = window.setTimeout(function(){
                            document.getElementById("description").value = $("textarea#phone_comment").val();
                            $('textarea#description').focus(function(){
                                setTimeout(function(){
                                    var curValue = document.getElementById("description").value;
                                    var commentValue = $("textarea#phone_comment").val();
                                    if (curValue.length <= 0)
                                    {
                                        curValue = commentValue;
                                    }
                                    document.getElementById("description").value = curValue;
                                    document.getElementById("description").setSelectionRange(curValue.length, curValue.length);
                                    $('textarea#description').unbind();
                                },100);
                            });
                        }, 150);

                    }
                    else
                    {
                        alert("Не удалось создать звонок.\nСоздайте звонок вручную.")
                    }
                }
            }
        );

        /*var calEventSettings =
        {
            defaultStorageTypeId: 3,
            direction: 1,
            typeID: 2
        };

        BX.CrmActivityEditor.addCall(calEventSettings);*/

        if ($(this).data('entityId') != 'LEAD')
        {
            /*$('div.hid div#crm_default_container div#crm_default_activity_editor_toolbar a.crm-menu-bar-btn.btn-new.crm-activity-command-add-call span').click();

            $('textarea#crm_default_description').val($("textarea.comment_new").val());
            $('textarea#crm_default_description').addClass("bx-crm-dialog-description-form-active");
            $('textarea#crm_default_description').css("color","black");

            $("span#crm_default_direction").text("Входящий звонок");
            $("span#crm_default_status_text").text("Завершено");
            var obr=$("textarea.comment_new").val().split('.');
            $("input#crm_default_subject").val(obr[0]);*/
            // $("table.bx-crm-dialog-activity-table td.bx-crm-dialog-activity-table-right div.bx-crm-dialog-comm-block").html("<span class='bx-crm-dialog-contact'><span class='bx-crm-dialog-contact-name'><?=$arResult['FULL_NAME']?></span><span class='bx-crm-dialog-contact-phone'><?=$tel_val?></span><span class='finder-box-selected-item-icon'></span></span>");
        }


    });

    $('#infinity-page').on('keyup', '#phone_comment', function(eventObject)
    {
        if (!$("#form_CRM_LEAD_CONVERT input[name=comment]").length)
        {
            $('#form_CRM_LEAD_CONVERT').prepend('<input type="hidden" name="comment"/>');
        }
        $("#form_CRM_LEAD_CONVERT input[name=comment]").val($(this).val());
    });

    <? if($arParams["ENTITY_ID"] == "LEAD"): ?>
    var isJunkLead = <? if ($arResult["STATUS_ID"] == "JUNK"): ?>true<? else: ?>false<? endif; ?>;
    if (isJunkLead) {
        $("span.img-logo").css("background", "url('<?=$templateFolder?>/img/hidef-avatar_3.png') no-repeat");
        $("span.img-logo").css("background-size", "100%");
        $("span.img-logo").attr("title", "<?= GetMessage('bad_lead'); ?>");
    }
    $('#infinity-page').on('click', "span.img-logo", function () {
        if (isJunkLead) {
            return false;
        }
        var leadId = $(this).data("leadId");
        $.ajax({
            url: "<?=$templateFolder?>/ajax_junk_lead.php",
            data: {
                id: leadId,
                comment: $('#phone_comment').val(),
                phone: '<?= $_REQUEST['phone']; ?>'
            },
            success: function(data)
            {
                var result = JSON.parse(data);
                if (result.PHONE.STATUS != "OK")
                {
                    alert(result.PHONE.MESSAGE)
                }
                if (result.LEAD.STATUS != "OK")
                {
                    alert(result.LEAD.MESSAGE)
                }
                else
                {
                    $("span.img-logo").css("background", "url('<?=$templateFolder?>/img/hidef-avatar_3.png') no-repeat");
                    $("span.img-logo").css("background-size", "100%");
                    $("span.img-logo").css("cursor", "default");
                    $("span.img-logo").attr("title", "<?= GetMessage('bad_lead'); ?>");
                    isJunkLead = true;

                    UpdateContent();
                }
            }
        });
    });

    $('#infinity-page').on('hover', "span.img-logo", function () {
            if (isJunkLead) {
                return false;
            }
            $(this).css("background", "url('<?=$templateFolder?>/img/hidef-avatar_3.png') no-repeat");
            $(this).css("background-size", "100%");
            $(this).css("cursor", "pointer");
            $(this).attr("title", "<?= GetMessage('bad_lead'); ?>");
        },
        function () {
            if (isJunkLead) {
                return false;
            }
            $(this).css("background", "url('<?=$templateFolder?>/img/hidef-avatar.png') no-repeat");
            $(this).css("background-size", "100%");
        }
    );
    <? endif; ?>

    </script>
<? endif; ?>