<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $USER;
$arUser = $USER->GetByID($USER->GetID())->Fetch();
$Extension = $arUser["UF_PHONE_INNER"];
?>
<script>	
function CallToInfinity(obj)
{

	function getXmlHttp(){
		var xmlhttp;	 
		  try {		 
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');		 
		  } 
		  catch (e) {		 
			try {		 
			  xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');		 
			} catch (E) {		 
			  xmlhttp = false;		 
			}		 
		  }		 
		  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {		 
			xmlhttp = new XMLHttpRequest(); 
		  }
		  return xmlhttp;	 
		}

			
		var req = getXmlHttp(); 
		 
		req.onreadystatechange = function() { 

			if (req.readyState == 4) {

				if(req.status == 200) {
					if(req.responseText == 'error')
						alert('Ошибка ajax');
				 }
	 
			}
		}
			
		var number = obj.innerHTML.replace(/[^\d]/g,''); // only numbers
		
		var params = 'number='+number+'&user_id='+'<?=$USER->GetID()?>'+'&Extension='+'<?=$Extension?>';
		req.open('POST', '/crm/ajax.php', true); 
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	 
		req.send(params);

}
</script>