function ChangeEntity(object)
{
    if ($(object).hasClass('btn-entity-active')) {
        return false;
    }
    $('.crm-entity-list a.btn-entity-active').removeClass('btn-entity-active');
    $(object).addClass('btn-entity-active');
    var entityType = $(object).data('entityType');
    var entityId = $(object).data('entityId');
    var wait = BX.showWait('infinity-page');
    var phoneComment = $('#phone_comment').val();

    $.ajax({
        url: window.location.href,
        data: {
            ajax: "Y",
            entity_type: entityType,
            entity_id: entityId,
            call_id: createdPhoneId,
            phone_comment: phoneComment
        },
        success: function(data)
        {
            $('div.infinity-page').html(data);
            BX.closeWait('infinity-page', wait);
        }
    });
}

/*****************<добавление компании>************************/
function company_add() {

    var popup = new BX.PopupWindow("my-popup", null, {
        closeIcon: {right: "12px", top: "10px"},
        titleBar: {
            content: BX.create("span", {
                html: '<b>Создание компании</b>',
                'props': {'className': 'access-title-bar'}
            })
        },
        closeByEsc: true,
        draggable: {restrict: true},
        zIndex: '-500',
        overlay: {opacity: 75},
        buttons: [
            new BX.PopupWindowButton({
                text: "Добавить",
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

    BX.ajax.get('/local/components/sw/infinity.call/templates/.default/ajax_edit_company_form.php', function (data) {
        popup.setContent(data);
        popup.show();
    });
}
/*****************</добавление компании>************************/

/*****************<добавление сделки>************************/
function deal_pop() {
    var popup = new BX.PopupWindow("my-popup", null, {
        closeIcon: {right: "12px", top: "10px"},
        titleBar: {
            content: BX.create("span", {
                html: '<b>Создание сделки</b>',
                'props': {'className': 'access-title-bar'}
            })
        },
        closeByEsc: true,
        draggable: {restrict: true},
        zIndex: '-500',
        offsetTop: 10,
        overlay: {opacity: 75},
        buttons: [
            new BX.PopupWindowButton({
                text: "Добавить",
                className: "popup-window-button-accept",
                events: {
                    click: function () {

                        $('#CRM_DEAL_EDIT_V12_saveAndView').click();
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

    BX.ajax.get('/local/components/sw/infinity.call/templates/.default/ajax_show_deal_form.php?edit=Y', function (data) {
        popup.setContent(data);
        popup.show();
    });
}
/*****************</добавление сделки>************************/

$(document).ready(function(){
    $(document).on("click", ".company_add", function () {
        company_add();
        return false;
    });
    $(document).on("click", ".deal_add", function () {
        deal_pop();
        return false;
    });
});