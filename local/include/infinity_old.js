var asdfg = null;
function CallToInfinity(obj)
{
	 
	
	if(typeof(event) !== 'undefined' ) event.preventDefault ? event.preventDefault() : event.returnValue = false;
	
	if(BX.CrmActivityEditor._default == null) // || typeof($.ajax) == 'undefined')
	{
		//alert("Oups...");
		
		BX.showWait(); 
				
		var pp = location.pathname.split(/[\/\?]/); // '/' or '?'
		 		
		loadHTML('/infinity/ajax/ajax_preload_activity_editor.php?entity_type='+((""+pp[2]).toUpperCase())+'&entity_id='+(pp[4]), null, function(){
			setTimeout(function(){  CallToInfinity(obj); BX.closeWait();  }, 200);
		});
		
		
		return;
	}
	
	
    var ownerType = BX.CrmActivityEditor._default._settings.ownerType;
    var ownerID = BX.CrmActivityEditor._default._settings.ownerID;

	if(ownerID == null || ownerID == 0)
	{		
		
		var ch = BX.findChildren(BX(obj).closest('tr, table'), {tag: 'a'},true,true);
		
		ch.forEach(function(item, i, arr) {
		
		  
		  if(ownerID == 0 && item.getAttribute('href') != null)
		  {
			  var pp = item.getAttribute('href').split(/[\/\?]/);
			  
			  if(pp.length >= 5 && pp[1] == 'crm')
			  {
				  ownerType = (""+pp[2]).toUpperCase();
				  ownerID = pp[4];
				  
				  console.log("set owner:", ownerType, ownerID );
				  
			  }
		  }
		  
		});
		
		if(ownerID == 0) {
			
			console.log("Не найден контекст, звонок не будет прикреплён");
			
			//return;
		}
	}
	
	
    var number = obj.innerHTML.replace(/[^\d]/g,''); // only numbers
    var infinityUrl = 'http://192.168.200.204:10081';
    var extension = '';
	
	BX.showWait();
	
	BX.ajax.loadJSON('/infinity/ajax/ajax_get_extension.php',
        function (data)
        {
			
            extension = data.EXTENSION;
            var makeCallUrl = encodeURI(infinityUrl + '/call/make/?Extension=' + extension + '&Number=' + number);

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
                            url: infinityUrl, //getCallInfoUrl,
							ExtensionUser: extension, 
                            phone_id: callId,
                            entity_type: ownerType,
                            entity_id: ownerID,
                            phone: number
                        }, function(data) {
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

function loadHTML(url, block, onload)
{
	BX.ajax.get(url, function(data){
				
		//console.log(block);
		
		if(typeof(block) === 'undefined' || block == null) block = document.body;
		
		//console.log(block);
		
		var tag = document.createElement("div");  		
		tag.innerHTML = data;
		
		document.body.appendChild(tag);
		
		if(typeof(onload) === 'function') onload(tag);
		
				
	}); 
}

function loadScript(url)
{
	var script = document.createElement('script');
	script.src = url;
	script.async = false; // чтобы гарантировать порядок
	
	document.head.appendChild(script);
	
  //document.write('<script src="', url, '" type="text/javascript"></script>');
}