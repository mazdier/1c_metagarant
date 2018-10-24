$( document ).ready(function() {

  if(!window.location.query) {
    window.location.query = function(){
      var map = {};
      if ("" !== this.search) {
        var groups = this.search.substr(1).split("&"), i;
        for (i in groups) {
          i = groups[i].split("=");
          map[decodeURIComponent(i[0])] = decodeURIComponent(i[1]);
        }
      }
          var editID=window.location.pathname.match(/edit\/(\d+)\//);
         if(editID!==null){
               map.id=editID[1];
          }
          var entitys=window.location.pathname.match(/crm\/(\w+)\//);
          if(entitys!==null){
              map.entity=entitys[1];
          }
          var editIDnew=window.location.pathname.match(/invoice\/(\d+)\//);
         if(editIDnew!==null){
               map.id=editIDnew[1];
          }
          
      return map;
    };
  }
 var hrez=window.location.query();
 window.flag=0;
 if(hrez.entity==="invoice"){
 // $('.crm-deal-client-selector-title').parent().before("<li id='item'>Тест</li>");
 // $('.crm-deal-client-selector-title').parent().append("<li id='item'>Тест</li>");
window.id_arr=[];
window.msgs=[];
var name,x=0,selector_product;
if (hrez.id!=0) selector_product='#invoice_'+hrez.id;
else selector_product='#new_invoice';
//console.log(selector_product);
function OstRezUpdate(ArrProductID){
			if(ArrProductID.length > 0){
				
				$.ajax({
					dataType: 'json',
					type: "POST",
					url: "/include/1c_ajax_invoce.php",
					data: { "action": 'UpdateOstRez','ArrProductID':ArrProductID }
					}).done(function( msg ){
					 // console.log(msg);
					  $.each(msg,function(ind,value){
							if(value!==false){
								$(selector_product+'_product_editor_product_row_'+ (ind)).attr('product_id');
								$(selector_product+'_product_editor_product_table tr').each(function(i){
									if($(this).attr('product_id') == value.product_id){
										$('.crm-item-ostatki_'+(ind)).find('span').text(value.col);
										$('.crm-item-rezerv_'+(ind)).find('span').text(value.rez);
									}
								})
							}
							
                      });
				});
			}
          }
 window.GetProductListIds = function(){
	var ArrProduct = [];
	$(selector_product+'_product_editor_product_table tr').each(function(i){
		//console.log($(this).attr('product_id'));
		if(typeof $(this).attr('product_id') !== 'undefined'){
			ArrProduct.push($(this).attr('product_id'));
		}
	});
	
	return ArrProduct;
}
window.add_ost_rez=function(){
$('.crm-item-total').each(function( index ) {
  if(index==0){
    if(flag==0){
      $('.crm-items-table-header').find(".crm-item-move").width('40');
      $( this ).after('<td class="crm-item-cell crm-item-rezerv" style="width: 62px;"><span class="crm-item-cell-text">Резервы</span></td>');
      $( this ).after('<td id="ostatki" class="crm-item-cell crm-item-ostatki" style="width: 62px;"><span class="crm-item-cell-text">Остатки</span></td>');
	  if(hrez.id != 0){
          $.ajax({
                        dataType: 'json',
                        type: "POST",
                        url: "/include/1c_ajax_invoce.php",
                        data: { "action": 'AddProductID','invoice':hrez.id }
                      }).done(function( msg ){            
						$.each(msg,function(ind,value){
							if(value!==false){
								$(selector_product+'_product_editor_product_row_'+ (ind)).attr('product_id',value);
							}
                      });
					  OstRezUpdate(msg);
              });
       }
     
    }
    flag=1;
  }
  else{
      if($(".crm-item-ostatki_"+(index-1)).length===0){
          $( this ).after('<td id="ostatki" class="crm-item-cell crm-item-ostatki_'+(index-1)+'" style="width: 62px;"><span class="crm-item-cell-text"></span></td></td>');
          $( ".crm-item-ostatki_"+(index-1)+"" ).after('<td class="crm-item-cell crm-item-rezerv_'+(index-1)+'" style="width: 62px;"><span class="crm-item-cell-text"></span></td>');
		  //OstRezUpdate(GetProductListIds());
          if($(selector_product+"_product_editor_product_row_"+(index-1)).find(".crm-item-cell.crm-item-move").length===0){
            $( ".crm-item-rezerv_"+(index-1)+"" ).after('<td class="crm-item-cell crm-item-move" style="width: 10px;"><span class="crm-item-del" title="Кликнуть для удаления"></span></td>');
          }   
      }        
   }
  });
};
$(".bx-crm-view-fieldset-content").click(function(event) {
        add_ost_rez();
        x=$('.crm-items-table').find('tr').length-1;
    });
    
    add_ost_rez();
BX.addCustomEvent('onAjaxSuccessFinish', BX.delegate(function(data){
if(typeof sessid !== 'undefined')
	if(data.url === ('/bitrix/components/bitrix/crm.product_row.list/ajax.php?sessid=' + $(sessid).val())){
		var ParceDate = UrlParmetrParce(data.data);
		var LastProduct = countProperties(ParceDate.PRODUCTS)-1;
		$(selector_product+'_product_editor_product_row_'+ (LastProduct)).attr('product_id',ParceDate.PRODUCTS[LastProduct].PRODUCT_ID);
		OstRezUpdate(GetProductListIds());
		//console.log();
	}
}, this));
}

window.addButton = function (){
	var elButton='';
	var ProductID='';
	var arrayProperty=[];
	var TableName = $(".crm-catalog-left").attr('id').replace("_left_container","");
	$(".bx-crm-action").each(function(i){if(i!=0){$(this).on('click',function(){
		$(".bx-crm-action").parent().parent().each(function(){
			/*var mainTableClass = $(this).attr('class').match(/bx-crm-table-body/);
			if(mainTableClass != null){
				if(mainTableClass[0] == 'bx-crm-table-body'){
					//ArrayProductID[i]=$(this).attr('ondblclick').match(/\d+/)[0];
					//console.log(ArrayProductID[i]);
				}
			}*/
		});
		//var ProductID = this.
		setTimeout(function(){
			elButton = $("#bxMenu_tbl_product_search_crm_productrow_list_item_0");
			window.calculate = function(ProductElementClick){
				ProductID = $(ProductElementClick).prev().attr('onclick').match(/\d+/)[0];
				//console.log(ProductID);
					$.ajax({
						  url: "/include/1c_ajax_product.php",
						  type: "POST",
						  data: { "ID": ProductID ,'action':'product_calc'},
						  success: function(msg){
							  arrayProperty = JSON.parse(msg);
							 // console.log(arrayProperty);
							  arrayProperty.sessid = $(sessid).val();
								arrayProperty.ID = ProductID;
							  arrayProperty.PRICE = (arrayProperty.CITY === "Могилев")?arrayProperty.MANUFACTURE_PRICE_1:arrayProperty.MANUFACTURE_PRICE_2;
								if(closeWindow&&flag_popup_show===false){
									closeWindow.remove();
								}
								var calculate=new MZ.PopupCalculater('calculate','Калькулятор',ProductID);
								if(flag_popup_show){
									//console.log(arrayProperty);
									calc(arrayProperty); 
								}
						  }
					});
				/**/
			};
			if($("#bxMenu_tbl_product_search_crm_productrow_list_item_1").length === 0)
			elButton.after('<div id="bxMenu_tbl_product_search_crm_productrow_list_item_1" class="popupitem" onclick="calculate(this)" ><div style="width:100%;"><table style="width:100% !important" dir="ltr" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td class="gutter"><div class="icon"></div></td><td class="item default">Калькулятор</td></tr></tbody></table></div></div>');
		}, 100);
	})}})

}

//function() { BX.addCustomEvent('onAjaxSuccessFinish', addButtonFunction.add); };
BX.addCustomEvent('onAjaxSuccess', function(data,obj){
	if(obj !== null)
	if(typeof obj.url !== "undefined"){
		var urlfiltered = obj.url.match(/crm.product_row.list\/product_choice_dialog.php\?caller=crm_productrow_list&JS_EVENTS_MANAGER_ID/);
		var urlfiltered2 = obj.url.match(/crm.product_row.list\/product_choice_dialog.php\?mode=list/);
		if(urlfiltered !== null){
			urlfiltered = urlfiltered['0'];
					if(urlfiltered === 'crm.product_row.list/product_choice_dialog.php?caller=crm_productrow_list&JS_EVENTS_MANAGER_ID')
					addButton();
		}
		else if(obj.url === '/bitrix/components/bitrix/crm.product.section.tree/ajax.php'){
			addButton();
		}
		else if(urlfiltered2 !== null){
			urlfiltered2 = urlfiltered2['0'];
					if(urlfiltered2 === 'crm.product_row.list/product_choice_dialog.php?mode=list')
					addButton();
		}
	}
        });




  
});
//Add Product TAB	
var arTabLoading = []; 
BX.ready(function(){
 BX.addCustomEvent('BX_CRM_INTERFACE_FORM_TAB_SELECTED', BX.delegate(function(self, name, tab_id){
	// console.log(self.oTabsMeta[tab_id]);
        if (!arTabLoading[tab_id] && self.oTabsMeta[tab_id].title == 'cutting') {
            var innerTab = BX('inner_tab_'+tab_id), 
                InvoiceId = 0, matches = null, 
                waiter = BX.showWait(innerTab); 
            if (matches = window.location.href.match(/\/crm\/invoice\/show\/([\d]+)\//i)) { 
                var InvoiceId = parseInt(matches[1]); 
            }
            if (InvoiceId > 0) {
                arTabLoading[tab_id] = true;
					BX.ajax({ 
						url: '/include/1c_ajax_product_tab.php', 
						method: 'POST', 
						dataType: 'html', 
						data: { 
							InvoiceId: InvoiceId, tab:'produc_cut' 
						}, 
						onsuccess: function(data) 
						{ 
							console.log(data);
							innerTab.innerHTML = data; 
							BX.closeWait(this, waiter); 
						}, 
						onfailure: function(data) 
						{ 
							BX.closeWait(innerTab, waiter); 
						} 
					} 
				);
            }
        }

		if (!arTabLoading[tab_id] && self.oTabsMeta[tab_id].title == 'shipping') {
            var innerTab = BX('inner_tab_'+tab_id), 
                InvoiceId = 0, matches = null, 
                waiter = BX.showWait(innerTab); 
            if (matches = window.location.href.match(/\/crm\/invoice\/show\/([\d]+)\//i)) { 
                var InvoiceId = parseInt(matches[1]); 
            }
            if (InvoiceId > 0) {
                arTabLoading[tab_id] = true;
					BX.ajax({ 
						url: '/include/1c_ajax_product_tab.php', 
						method: 'POST', 
						dataType: 'html', 
						data: { 
							InvoiceId: InvoiceId, tab:'map' 
						}, 
						onsuccess: function(data) 
						{ 
							console.log(data);
							innerTab.innerHTML = data; 
							BX.closeWait(this, waiter); 
						}, 
						onfailure: function(data) 
						{ 
							BX.closeWait(innerTab, waiter); 
						} 
					} 
				);
            }
        }
    })); 
});
//------------------------------------------------

//кол элементов в массиве
function countProperties(obj) {
    return Object.keys(obj).length;
}

getProductRow = function(){
	
};

updateWithParams = function(){
	url = '/bitrix/components/bitrix/crm.product_row.list/ajax.php?sessid=' + $(sessid).val();
	invoiceID = 28;
// productData=[{
// 	CUSTOMIZED: "Y",
// 	DISCOUNT_RATE: 0,
// 	DISCOUNT_SUM: 0,
// 	DISCOUNT_TYPE_ID: 1,
// 	PRICE: 15165,
// 	PRICE_EXCLUSIVE: 15165,
// 	PRODUCT_ID: 23018,
// 	PRODUCT_NAME: "Арматура ф 6 S500 (6м), РБ",
// 	QUANTITY: 1,
// 	TAX_INCLUDED: "N",
// 	TAX_RATE: 0
// 	},
// 	{
// 	CUSTOMIZED: "Y",
// 	DISCOUNT_RATE: 0,
// 	DISCOUNT_SUM: 0,
// 	DISCOUNT_TYPE_ID: 1,
// 	PRICE: 15165,
// 	PRICE_EXCLUSIVE: 15165,
// 	PRODUCT_ID: 23018,
// 	PRODUCT_NAME: "Арматура ф 6 S500 (6м), РБ",
// 	QUANTITY: 1,
// 	TAX_INCLUDED: "N",
// 	TAX_RATE: 0
// 	},
// 	{
// 	CUSTOMIZED: "Y",
// 	DISCOUNT_RATE: 0,
// 	DISCOUNT_SUM: 0,
// 	DISCOUNT_TYPE_ID: 1,
// 	PRICE: 980,
// 	PRICE_EXCLUSIVE: 980,
// 	PRODUCT_ID: 23018,
// 	PRODUCT_NAME: "Арматура ф 6 S500 (6м), РБ",
// 	QUANTITY: 3,
// 	TAX_INCLUDED: "N",
// 	TAX_RATE: 0
// 	}
// 	];
	BX.ajax(
				{
					'url': url,
					'method': 'POST',
					'dataType': 'json',
					'data':
					{
						'MODE': 'CALCULATE_TOTALS',
						'OWNER_TYPE': 'I',
						'OWNER_ID': invoiceID,
						'PERMISSION_ENTITY_TYPE': '',
						'PRODUCTS': productData,
						'CURRENCY_ID': 'BYR',
						'CLIENT_TYPE_NAME': 'COMPANY',
						'SITE_ID': 's1',
						'LOCATION_ID': 0,
						'ALLOW_LD_TAX': 'N',
						'LD_TAX_PRECISION': 2
					},
					onsuccess: BX.delegate(this._onCalculateTotalsRequestSuccess, this),
					onfailure: BX.delegate(this._onCalculateTotalsRequestFailure, this)
				}
			);
};

UrlArrayToJsonString = function(ar,value){
var _string='';
var str='';
var _ArrString;
var k; var k2;
	function add(ar, add_ar = {}, b = 0){
		if(b<ar.length){
			str =k2;
			k = ar[b];
			k2 = ar[b+1];
			var string=(_string)?_string:'{"'+k+'":{"'+k2+'"}}';
			var str_reg= new RegExp('\\{\"'+k+'\"\\}');
			string=string.replace(str_reg, '{"'+k+'":'+((typeof k2 === 'undefined')?'"'+value+'"':'{"'+k2+'"}')+'}');
			b++;
			_string = string;
			add(ar,  add_ar, b);
		} 
	}
	add(ar);
  return _string;
};

JsonMergeRecursive=function (json1, json2){
    var out = {};
    for(var k1 in json1){
        if (json1.hasOwnProperty(k1)) out[k1] = json1[k1];
    }
    for(var k2 in json2){
        if (json2.hasOwnProperty(k2)) {
            if(!out.hasOwnProperty(k2)) out[k2] = json2[k2];
            else if(
                (typeof out[k2] === 'object') && (out[k2].constructor === Object) && 
                (typeof json2[k2] === 'object') && (json2[k2].constructor === Object)
            ) out[k2] = JsonMergeRecursive(out[k2], json2[k2]);
        }
    }
    return out;
};

UrlParmetrParce = function(parser){
       queries = parser.split('&');
var QueryParams = {};
var name = '' ;
var ArrMerge={};
       for(var i = 0; i < queries.length; i++ ) {
           split = queries[i].split('=');
name = split[0].replace(/\[/g, ";").replace(/\]/g, "");
name = name.split(';');

if(name.length>2){
      var_ArrString = UrlArrayToJsonString(name,split[1]);
	  _ArrString=JSON.parse (''+_ArrString+'');
	  ArrMerge = (ArrMerge)?ArrMerge:_ArrString;
		ArrMerge= JsonMergeRecursive(ArrMerge,_ArrString);
 }
         else{
           QueryParams[split[0]] = split[1];
         }         
   }
   return JsonMergeRecursive(ArrMerge,QueryParams);
};

