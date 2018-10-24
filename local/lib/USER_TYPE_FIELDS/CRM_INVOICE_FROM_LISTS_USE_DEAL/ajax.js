document.addEventListener('DOMContentLoaded', function () {
	window.GetInvoiceForDeal= function(attrFieldName,dir,SelectName=false){
		var dealLink;
		var DealId;
		var ObjInputInvoice = $("[name='" + attrFieldName + "']");
		var InvoiceId = ObjInputInvoice.val();
		$("td .field_crm_entity").each(function(){
				dealLink=$(this).find('a').attr('href');
				if(typeof dealLink !== "undefined"){
					if((dealLink.match(/crm\/(\w+)/)[1]=='deal')){
						DealId = dealLink.match(/(\d+)/)[0];
					}
				}
		})
		if(typeof dealLink !== "undefined"){
			$.ajax({
				type: "POST",
				data:"DealId=" + DealId +"&InvoiceId=" + InvoiceId,
				url: dir + '/ajax.php',
				success: function(data){
					var InvoiceArray = JSON.parse(data);
					InvoiceArray.forEach(function(value,i){
						var flag=0;
						if(i==0){
							if(value.length!=0){
								$('#' + SelectName + ' :first').val(value['ID']);
								$('#' + SelectName + ' :first').text(value['ACCOUNT_NUMBER'] + ' -- ' + value['ORDER_TOPIC']);
								$('#'+SelectName+' option').each(function(i){
									if(i!=0){
										this.remove();
									}
								})
							}
							else{
								$('#' + SelectName + ' :first').val('0');
								$('#' + SelectName + ' :first').text('нет');
								$('#'+SelectName+' option').each(function(i){
									if(i!=0){
										this.remove();
									}
								})
								flag=1;
							}	
						}
						else if(flag==0){
							$('#'+SelectName).append( $('<option value="' + value['ID'] + '">' + value['ACCOUNT_NUMBER'] + ' -- ' + value['ORDER_TOPIC'] + '</option>'));
							//console.log(value['ID']);
							//console.log(value['ORDER_TOPIC']);
						}
					})
					$('#'+SelectName).change(function(){
						ObjInputInvoice.val($(this).val());
					});
				}
			});
		}
		else{
			ObjInputInvoice.val('');
			$('#'+SelectName+' option').each(function(i){
				if(i!=0){
					this.remove();
				}
				else {$(this).text('нет');$(this).val(0);}
			})
			
		}
	}
});