document.addEventListener("DOMContentLoaded", function(event) { 
	window.flag_popup_show=false;
	window.MZ=[];
	window.Iz=0;
	window.closeWindow='';
var AddProduct;

				
	window.calc=function(arrayProperty=false){
		const Lang = "ru";
		window.Props = arrayProperty;
		AddProduct = function(arrayProperty){
				var InvoiceID;
				var index;
				var pathnameID = window.location.pathname.match(/edit\/(\d+)\//);
				var UnitCol;
				var ArrCalcData=[];
				var ProductFlag;
				var indexLastRow;
				if(pathnameID!=null){
				   InvoiceID = pathnameID[1];
				}
				InvoiceAttrID = (InvoiceID!=0)?'invoice_'+InvoiceID+'_product_editor_product_table':'new_invoice_product_editor_product_table';
				//console.log(InvoiceAttrID);
				
				ArrCalcData.Summa=$('.summOst input').val();
				var jsEventsManagerId = '';
				for (var key in BX.Crm) {
					var matchKey=key.match(/PageEventsManager_(\w+)/);
					if(matchKey)
					if(typeof matchKey[1] != 'undefined'){
						jsEventsManagerId = matchKey[0];
						//console.log(jsEventsManagerId);
					}
				}
				var ObjProductDialog = new BX.Crm.ProductSearchDialog({jsEventsManagerId:jsEventsManagerId});
				//console.log(arrayProperty.ID);
				ObjProductDialog.SelEl({'id':arrayProperty.ID,'type':'E'});
				BX.addCustomEvent('onAjaxSuccessFinish', BX.delegate(function(data){
						
						if(data.url == ('/bitrix/components/bitrix/crm.product_row.list/ajax.php?sessid=' + arrayProperty['sessid'])){
							$('#'+InvoiceAttrID+' tr').each(function(i,v){
								if(typeof $(this).attr('id') != 'undefined' ){	
									var NumRow=(InvoiceID!=0)?new RegExp('invoice_'+InvoiceID+'_product_editor_product_row_(\\d+)'):new RegExp('new_invoice_product_editor_product_row_(\\d+)');
									var NumRow = $(this).attr('id').match(NumRow);
									indexLastRow = (NumRow[1] == i)?i:i-1;
									//console.log(i,NumRow,indexLastRow);						
								}
							})
							UpdateProductRow(InvoiceID,indexLastRow,ArrCalcData);
							//console.log(indexLastRow);
						}
					}, this));
				var UpdateProductRow = function(InvoiceID,indexLastRow,ArrCalcData){
					var elMesId = (InvoiceID != 0)?"invoice_"+InvoiceID+"_product_editor_product_row_"+indexLastRow:"new_invoice_product_editor_product_row_"+indexLastRow;
					//console.log(elMesId);
					$("#"+elMesId+"_MEASURE option").each(function(){ 
						if($(this).text()==arrayProperty.UNITS_RETAIL){
							$(this).attr('selected',true);
						}
						else $(this).attr('selected',false);
					});
					var eChange = new Event('change');
					document.getElementById(elMesId+"_MEASURE").dispatchEvent(eChange);
					switch(arrayProperty.UNITS_RETAIL){
						case 'м/п':
							UnitCol = $('td.mtOst input').val();						
						break;
						case 'тн':	
							UnitCol = $('td.tnOst input').val();
						break;
						case 'кг':	
							UnitCol = ($('td.tnOst input').val()/1000);
						break;
						case 'шт':
							UnitCol = $('td.unitOst input').val();						
						break;
					}
					//console.log(UnitCol,arrayProperty.PRICE,ArrCalcData.Summa);
					var eKeyup = new KeyboardEvent('keyup',{'key':'2'});
					$("#"+elMesId+"_QUANTITY").val(UnitCol);
					$("#"+elMesId+"_QUANTITY").attr('value',UnitCol);
					$("#"+elMesId+"_QUANTITY_v").text(UnitCol);
					document.getElementById(elMesId+"_QUANTITY").dispatchEvent(eKeyup);
					$("#"+elMesId+"_PRICE").val(arrayProperty.PRICE);
					$("#"+elMesId+"_PRICE").attr('value',arrayProperty.PRICE);
					$("#"+elMesId+"_PRICE_v").text(arrayProperty.PRICE);
					document.getElementById(elMesId+"_PRICE").dispatchEvent(eKeyup);
					$("#"+elMesId+"_SUM").val(ArrCalcData.Summa);
					$("#"+elMesId+"_SUM").attr('value',ArrCalcData.Summa);
					$("#"+elMesId+"_SUM_v").text(ArrCalcData.Summa);
					document.getElementById(elMesId+"_SUM").dispatchEvent(eKeyup);
				}
						
			};
				//$('.popup-window-button.popup-window-button-accepts').on('click',function(){AddProduct(arrayProperty);});
			
		let headText = "",
			headTextLine = '',
			zUnit = '',
			zMtU = (arrayProperty.SIZE)?arrayProperty.SIZE:'',
			zPrice = (arrayProperty.PRICE)?arrayProperty.PRICE:'',
			zSumm = "",
			zUDweight = (arrayProperty.SPECIFIC_WEIGHT)?arrayProperty.SPECIFIC_WEIGHT:'',
			zTn = (arrayProperty.OSTATOK)?arrayProperty.OSTATOK:'',
			zMt = "";
		switch (Lang) {
		  case "ru":
			headText = [" ", "ЦЕНА", "ВЕС  Метра", "ТОНЫ", "МЕТРЫ", "ШТУКИ", "В штуке метров ", "СУММА"];
			headTextLine = ["ОСТАТОК", "ЗАКАЗАТЬ"];
			break;
		  case "en":
			headText = [" ", "PRICE", "WEIGHT Meter", "TONES", "METERS", "UNIT", "In unit of meter ", "TOTAL"];
			headTextLine = ["REMAINDER", "TO ORDER"];
			break;
		}

		class InputTable extends React.Component {
		  constructor(props) {
			super(props);
			this.state = { val: "" };
			this.handleChange = this.handleChange.bind(this);
		  }

		  handleChange(e) {
			  
			this.props.onHandleChange(e.target.value);
		  }
		  render() {
			const val = this.props.val;
			return React.createElement("input", {
			  style: { width: "80px" },
			  value: val,
			  onChange: this.handleChange
			});
		  }
		}

		class Calculate extends React.Component {
		  constructor(props) {
			super(props);
			//this.handleChange = this.handleChange.bind(this);
			this.state = {
			  priceOst: zPrice,
			  summOst: "",
			  uDweightOst: zUDweight,
			  tnOst: '0',
			  mtOst: "",
			  mtUOst: zMtU,
			  unitOst: "",
			  zUnit: '', zMtU: zMtU, zPrice: zPrice,
			  zSumm: zSumm,
			  zUDweight: zUDweight,
			  zTn: zTn,
			  zMt: zMt
			};
			this.handlePriceOstChange = this.handlePriceOstChange.bind(this);
			this.handleUnitOstChange = this.handleUnitOstChange.bind(this);
			this.handleSummOstChange = this.handleSummOstChange.bind(this);
			this.handleUdWeightOstChange = this.handleUdWeightOstChange.bind(this);
			this.handleTnOstChange = this.handleTnOstChange.bind(this);
			this.handleMtOstChange = this.handleMtOstChange.bind(this);
			this.handleMtUOstChange = this.handleMtUOstChange.bind(this);
			this.state.zUnit = zUnit;
		  }
		  
		  handlePriceOstChange(val) {
			
			this.setState({ priceOst: val });
			if (this.state.tnOst.length !== 0) {
			  const summOst = val * this.state.tnOst;
			  this.setState({ summOst: summOst });
			} else {
			  const summOst = val * this.state.unitOst;
			  this.setState({ summOst: summOst });
			}
		  }
		  handleUnitOstChange(val, mtOstNew = false) {
			this.setState({ unitOst: val });
			if (this.state.mtUOst.length !== 0) {
			  /*if (zUnit.length !== 0) {
				console.log(zUnit);
				let zUnitNew = zUnit - val;
				this.setState({ zUnit: zUnitNew });
			  }*/
			  const mtOst = mtOstNew ? mtOstNew : val * this.state.mtUOst;
			  this.setState({ mtOst: mtOst });
			  const tnOst = mtOst * this.state.uDweightOst / 1000;

			  if (val.length !== 0) {
				if (mtOstNew) {
				  this.setState(this.handleTnOstChange(tnOst, val, false));
				} else this.setState(this.handleTnOstChange(tnOst, val, false));
			  }
			} else {
			  const summOst = val * this.state.priceOst;
			  this.setState({ summOst: summOst });
			}
		  }
		  handleSummOstChange(val) {
			this.setState({ summOst: val });
			const tnOst = val / this.state.priceOst;
			this.setState({ tnOst: tnOst });
			let calcState = this.state;
		  }
		  handleUdWeightOstChange(val) {
			this.setState({ uDweightOst: val });
			const tnOst = this.state.mtOst * (val / 1000);
			this.setState(this.handleTnOstChange(tnOst, this.state.unitOst, false));
		  }
		  handleTnOstChange(val, unitOstNew = false, mtUOst = true) {
			this.setState({ tnOst: val });
			const summOst = val * this.state.priceOst;
			this.setState({ summOst: summOst });
			if (this.state.mtUOst) {
			  const mtOst = val * 1000 / this.state.mtUOst;
			  const unitOst = unitOstNew ? unitOstNew : mtOst / this.state.mtUOst;
			  this.setState({ unitOst: unitOst });
			  if (mtUOst) {
				this.setState(this.handleMtOstChange(mtOst, false, val));
			  }
			}
		  }
		  handleMtOstChange(val, mtUOst = false, tnOst = false) {
			this.setState({ mtOst: val });
			if (val.length !== 0) {
			  if (tnOst === false) {
				if (mtUOst === false) {
				  const unitOst = val / this.state.mtUOst;
				  this.setState(this.handleUnitOstChange(unitOst, val));
				} else {
				  const tnOst = val * mtUOst / 1000;
				  const unitOst = val / mtUOst;
				  this.setState(this.handleTnOstChange(tnOst, unitOst, false));
				}
			  }
			}
		  }
		  handleMtUOstChange(val) {
			this.setState({ mtUOst: val });
			const mtOst = val * this.state.unitOst;
			if (val.length !== 0) {
			  this.setState(this.handleMtOstChange(mtOst, val));
			}
		  }
		  render() {
			const priceOst = this.state.priceOst;
			const uDweightOst = this.state.uDweightOst;
			const tnOst = this.state.tnOst;
			const mtOst = this.state.mtOst;
			const mtUOst = this.state.mtUOst;
			const unitOst = this.state.unitOst;
			const summOst = this.state.summOst;
			const zPriceNew = this.state.zPrice;
			const zUDweightNew = this.state.zUDweight;
			const zTnNew = this.state.zTn;
			const zMtNew = this.state.zMt;
			const zUnitNew = this.state.zUnit;
			const zMtUNew = this.state.zMtU;
			const zSummNew = this.state.zSumm;
			return React.createElement(
			  "div",
			  { id: "calculate_main" },
			  React.createElement(
				"table",
				{ id: "dynamic", width: "650", border: "1" },
				React.createElement(
				  "thead",
				  null,
				  React.createElement(
					"tr",
					null,
					headText.map(function (item, i) {
					  return React.createElement(
						"th",
						{ key: i },
						item
					  );
					})
				  )
				),
				React.createElement(
				  "tbody",
				  null,
				  React.createElement(
					"tr",
					null,
					React.createElement(
					  "td",
					  null,
					  headTextLine["0"]
					),
					React.createElement(
					  "td",
					  null,
					  zPriceNew
					),
					React.createElement(
					  "td",
					  null,
					  zUDweightNew
					),
					React.createElement(
					  "td",
					  null,
					  zTnNew
					),
					React.createElement(
					  "td",
					  null,
					  zMtNew
					),
					React.createElement(
					  "td",
					  null,
					  zUnitNew
					),
					React.createElement(
					  "td",
					  null,
					  zMtUNew
					),
					React.createElement(
					  "td",
					  null,
					  zSummNew
					)
				  ),
				  React.createElement(
					"tr",
					null,
					React.createElement(
					  "td",
					  null,
					  headTextLine["1"]
					),
					React.createElement(
					  "td",
					  { className: "priceOst" },
					  React.createElement(InputTable, {
						val: priceOst,
						onHandleChange: this.handlePriceOstChange
					  })
					),
					React.createElement(
					  "td",
					  { className: "uDweightOst" },
					  React.createElement(InputTable, {
						val: uDweightOst,
						onHandleChange: this.handleUdWeightOstChange
					  })
					),
					React.createElement(
					  "td",
					  { className: "tnOst" },
					  React.createElement(InputTable, {
						val: tnOst,
						onHandleChange: this.handleTnOstChange
					  })
					),
					React.createElement(
					  "td",
					  { className: "mtOst" },
					  React.createElement(InputTable, {
						val: mtOst,
						onHandleChange: this.handleMtOstChange
					  })
					),
					React.createElement(
					  "td",
					  { className: "unitOst" },
					  React.createElement(InputTable, {
						val: unitOst,
						onHandleChange: this.handleUnitOstChange
					  })
					),
					React.createElement(
					  "td",
					  { className: "mtOst" },
					  React.createElement(InputTable, {
						val: mtUOst,
						onHandleChange: this.handleMtUOstChange
					  })
					),
					React.createElement(
					  "td",
					  { className: "summOst" },
					  React.createElement(InputTable, {
						val: summOst,
						onHandleChange: this.handleSummOstChange
					  })
					)
				  )
				)
			  )
			);
		  }
		}	
ReactDOM.render(React.createElement(Calculate, null), document.getElementById("MZcalc"));
}
	document.onkeypress = function(e) {
		if(e.ctrlKey){
			//console.log(e.charCode);
			if(e.charCode=='17'||e.charCode=='113'){

					
					if(closeWindow&&flag_popup_show==false){
						closeWindow.remove();
					}
					var calculate=new MZ.PopupCalculater('calculate','Калькулятор');
					if(flag_popup_show){
						calc(); 
					}
			}
		}
	};
	MZ.PopupCalculater = function (uniquePopupId,title,product_id = false){
		this._popupWindow = null;
		this.uniquePopupId = uniquePopupId;
		if(!flag_popup_show){
			var popupWindow = new BX.PopupWindow(uniquePopupId, null, {
			width : 760,
			height : 400,
			closeByEsc : true,
			closeIcon : true,
			overlay : {
			  opacity : 50,
			  backgroundColor : '#000'
			},
			titleBar : title,
			content : '<div id="MZcalc"></div>',
			
			buttons : [ new BX.PopupWindowButton({
			  text : 'Выбрать',
			  className : 'popup-window-button-accepts',
			  events : {
				click : function (){UpdateProductTable(this)}
			  }
			}),
			new BX.PopupWindowButton({
						  text: "Закрыть" ,
						  className: "webform-button-link-cancel" ,
						  events: {click: function(){
							 this.popupWindow.close();
						  }}
					   })
			]
		  });
		  BX.addCustomEvent(popupWindow, "onPopupShow", function(){flag_popup_show=true});
		  BX.addCustomEvent(popupWindow, "onPopupClose", function(){flag_popup_show=false});
		  popupWindow.show();
		  this._popupWindow=popupWindow;
		  this.contentContainer=popupWindow.contentContainer;
			closeWindow=document.getElementById(uniquePopupId);
		}
	};
	UpdateProductTable = function(objPopup){
		//console.log(Props);
		AddProduct(Props);
		objPopup.popupWindow.close();
	}
})
