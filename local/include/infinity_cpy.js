function CallToInfinity(obj)
{
    event.preventDefault ? event.preventDefault() : event.returnValue = false;
    var ownerType = BX.CrmActivityEditor._default._settings.ownerType;
    var ownerID = BX.CrmActivityEditor._default._settings.ownerID;

    var number = obj.innerHTML;
    var infinityUrl = 'http://192.168.200.204:10081';
    var extension = '';
    $.getJSON('/infinity/ajax/ajax_get_extension.php').success(
        function (data)
        {
            extension = data.EXTENSION;
            var makeCallUrl = encodeURI(infinityUrl + '/call/make/?Extension=' + extension + '&Number=' + number);

            $.getJSON('/infinity/ajax/ajax_make_call.php', {url: makeCallUrl}).success(
                function (data)
                {
                    var callId = data.result.IDCall;
                    var getCallInfoUrl = encodeURI(infinityUrl + '/call/getactivecalls/?Extension=' + extension);
                    var isCreatedPhone = false;
                    var createdPhoneId = 0;
                    var getFilePathInterval = setInterval(function(){
                        if (isCreatedPhone)
                        {
                            clearInterval(getFilePathInterval);
                            return;
                        }
                        $.getJSON('/infinity/ajax/ajax_create_agent.php', {
                            url: getCallInfoUrl,
                            phone_id: callId,
                            entity_type: ownerType,
                            entity_id: ownerID,
                            phone: number
                        }).success(function(data) {
                            if (data == null)
                            {
                                return;
                            }
                            if (data.PHONE.STATUS == "OK" && data.RELATION.STATUS == "OK")
                            {
                                isCreatedPhone = true;
                                createdPhoneId = data.PHONE.ID;
                                BX.CrmActivityEditor._default.editActivity(createdPhoneId, { 'enableInstantEdit':true, 'enableEditButton':true });
                            }
                            else
                            {
                                console.log(data);
                                alert("Не удалось создать звонок.\nСоздайте звонок вручную.")
                            }
                        });
                    }, 2000);
                }
            );
        }
    );
}