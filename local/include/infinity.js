function CallToInfinityNew(obj)
{

var number = obj.innerHTML.replace(/[^\d]/g,''); 
    var extension = '';
	
	BX.showWait();
	BX.ajax.loadJSON('/infinity/ajax/ajax_get_extension.php',
        function (data)
        {
            extension = data.EXTENSION;
			
			var ID_ACT = 0;
			
			BX.ajax.loadJSON('/infinity/ajax/ajax_show_card.php', {ExtensionUser: extension, phone: number}, 
				function (data)
				{
					ID_ACT = data;
				}
			);

            //var makeCallUrl = encodeURI(infinityUrl + '/call/make/?Extension=' + extension + '&Number=' + number);
            var makeCallUrl = encodeURI('/call/make/?Extension=' + extension + '&Number=' + number);

            BX.ajax.loadJSON('/infinity/ajax/ajax_make_call.php', {url: makeCallUrl} , 
                function (data)
                {
					BX.closeWait();

                    var callId = data.result.IDCall;
                    //var getCallInfoUrl = encodeURI(infinityUrl + '/call/getactivecalls/?Extension=' + extension);
                    var isCreatedPhone = false;
                    var createdPhoneId = 0;
                    var getFilePathInterval = setInterval(function(){
						
                        if (isCreatedPhone)
                        {
                            clearInterval(getFilePathInterval);
                            return;
                        }

                        BX.ajax.loadJSON('/infinity/ajax/ajax_create_agent.php', {
                            //url: infinityUrl, //getCallInfoUrl,
							ExtensionUser: extension, 
                            phone_id: callId,

                            phone: number,
							id_act: ID_ACT
                        }, function(data) {console.log(data);
                            if (data == null)
                            {
                                return;
                            }
                            if (data.PHONE.STATUS == "OK" && data.RELATION.STATUS == "OK")
                            {
                                isCreatedPhone = true;
                                createdPhoneId = data.PHONE.ID;
                                //BX.CrmActivityEditor._default.editActivity(createdPhoneId, { 'enableInstantEdit':true, 'enableEditButton':true });
                            }
                            else
                            {
                                console.log(data);
                               // alert("�� ������� ������� ������.\n�������� ������ �������.")
                            }
                        });
                    }, 2000);
                }
            );
        })

}