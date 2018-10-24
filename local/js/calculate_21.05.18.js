document.addEventListener("DOMContentLoaded", function(event) { 
	window.flag_popup_show=false;
	window.MZ=[];
	window.Iz=0;
	window.closeWindow='';


				
	window.calc=function(arrayProperty=false){
		const Lang = "ru";
		var AddProduct = function(arrayProperty){
			console.log(arrayProperty);
			var InvoiceID;
			var index;
			var pathnameID = window.location.pathname.match(/edit\/(\d+)\//);
			var LastTR;
			var UnitCol;
			if(pathnameID!=null){
               InvoiceID = pathnameID[1];
			}
			InvoiceID = (InvoiceID.length!=0)?InvoiceID:0;
			$('#invoice_'+InvoiceID+'_product_editor_product_table tr').each(function(i){
				index = i;
				LastTR	= this;			
			})
			console.log(LastTR);
				var htmlText2=`
					<tr id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`" class="crm-items-table-odd-row">
								<td class="crm-item-cell crm-item-name">
									<span class="crm-item-cell-text">
										<span class="crm-table-name-left">
											<span class="crm-item-move-btn"></span><span id="invoice_`+InvoiceID+`_product_editor_product_row_2_NUM" class="crm-item-num">`+(index+1)+`.</span>
										</span>
										<span class="crm-item-inp-wrap" id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_PRODUCT_NAME_c" style="padding-right: 34px;">
											<input id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_PRODUCT_NAME" class="crm-item-name-inp" value="`+arrayProperty.NAME+`" autocomplete="off" type="text"><span class="crm-item-inp-btn crm-item-inp-arrow" title="Открыть карточку товара"></span>
										</span>
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<span class="crm-table-name-left">
											<span class="crm-item-move-btn view-mode"></span><span id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_NUM_v" class="crm-item-num">`+(index+1)+`.</span>
										</span>
										<span class="crm-item-txt-wrap">
											<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_PRODUCT_NAME_v" class="crm-item-name-txt">`+arrayProperty.NAME+`</div>
										</span>
									</span>
								</td>
								<td class="crm-item-cell crm-item-price">
									<span class="crm-item-cell-text">
										<input id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_PRICE" class="crm-item-table-inp" value="" type="text">
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_PRICE_v" class="crm-item-table-txt"></div>
									</span>
								</td>
								<td class="crm-item-cell crm-item-qua">
									<span class="crm-item-cell-text">
										<input id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_QUANTITY" class="crm-item-table-inp" value="1" type="text">
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_QUANTITY_v" class="crm-item-table-txt">1</div>
									</span>
								</td>
								<td class="crm-item-cell crm-item-unit">
									<span class="crm-item-cell-text">
										<select id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_MEASURE" class="crm-item-table-select">
											<option value="7771" selected="selected">бан</option>
												<option value="7772">бухта</option>
												<option value="7773">кв.м.</option>
												<option value="7774">кг</option>
												<option value="7775">км/п</option>
												<option value="7776">комплект</option>
												<option value="7777">л</option>
												<option value="7778">лист</option>
												<option value="7779">м/п</option>
												<option value="77710">н-р</option>
												<option value="77711">пар</option>
												<option value="77712">пачка</option>
												<option value="77713">рулон</option>
												<option value="77714">связка</option>
												<option value="77715">тыс. шт.</option>
												<option value="77716">тыс.шт</option>
												<option value="77717">упак</option>
												<option value="77718">хлыст</option>
												<option value="77719">шт</option>
												<option value="77720">шт</option>
												<option value="77722">м</option>
												<option value="77723">м2</option>
												<option value="77724">тн</option>
												<option value="77725">тн</option>
										</select>
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_MEASURE_v" class="crm-item-table-txtl">-</div>
									</span>
								</td>
								<td class="crm-item-cell crm-item-sale">
									<span class="crm-item-cell-text">
										<input id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_DISCOUNT" class="crm-item-table-inp" value="0" type="text"><span class="crm-item-sale-text-wrap"><span class="crm-item-sale-text">руб.</span></span>
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_DISCOUNT_v" class="crm-item-table-txt">0.00</div><span class="crm-item-sale-text-wrap"><span class="crm-item-sale-text">руб.</span></span>
									</span>
								</td>
								<td class="crm-item-cell crm-item-sum-sale">
									<span class="crm-item-cell-text">
										<input id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_DISCOUNT_SUBTOTAL" class="crm-item-table-inp" value="0.00" type="text">
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_DISCOUNT_SUBTOTAL_v" class="crm-item-table-txt">0.00</div>
									</span>
								</td>
												<td class="crm-item-cell crm-item-spacer"></td>
								<td class="crm-item-cell crm-item-tax">
									<span class="crm-item-cell-text">
										<select id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_TAX_RATE" class="crm-item-table-select">
											<option value="0" selected="selected">0%</option>
											<option value="10">10%</option>
											<option value="18">18%</option>
											<option value="20">20%</option>
										</select>
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_TAX_RATE_v" class="crm-item-table-txtl">0%</div>
									</span>
								</td>
								<td class="crm-item-cell crm-item-tax-included">
									<span class="crm-item-cell-text">
										<input id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_TAX_INCLUDED" type="checkbox">
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_TAX_INCLUDED_v" class="crm-item-table-txt">нет</div>
									</span>
								</td>
								<td class="crm-item-cell crm-item-tax-sum">
									<span class="crm-item-cell-text">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_TAX_SUM" class="crm-item-table-txt">0.00</div>
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_TAX_SUM_v" class="crm-item-table-txt">0.00</div>
									</span>
								</td>
												<td class="crm-item-cell crm-item-total">
									<span class="crm-item-cell-text">
										<input id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_SUM" value="" class="crm-item-table-inp" type="text">
									</span>
									<span class="crm-item-cell-view" style="display: none;">
										<div id="invoice_`+InvoiceID+`_product_editor_product_row_`+index+`_SUM_v" class="crm-item-table-txt"></div>
									</span>
								</td><td id="ostatki" class="crm-item-cell crm-item-ostatki_`+index+`" style="width: 62px;"><span class="crm-item-cell-text"></span></td><td class="crm-item-cell crm-item-rezerv_`+index+`" style="width: 62px;"><span class="crm-item-cell-text"></span></td>
								<td class="crm-item-cell crm-item-move"><span class="crm-item-del" title="Кликнуть для удаления"></span></td>
							</tr>
					` ;
					
					$(LastTR).after(htmlText2);
					$("#invoice_"+InvoiceID+"_product_editor_product_row_"+index+"_MEASURE option").each(function(){ 
						if($(this).text()==arrayProperty.UNITS_RETAIL){
							$(this).attr('selected',true);
						}
						else $(this).attr('selected',false);
					});
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
					$("#invoice_"+InvoiceID+"_product_editor_product_row_"+index+"_QUANTITY").val(UnitCol);
					$("#invoice_"+InvoiceID+"_product_editor_product_row_"+index+"_QUANTITY_v").text(UnitCol);
					$("#invoice_"+InvoiceID+"_product_editor_product_row_"+index+"_PRICE").val(arrayProperty.PRICE);
					$("#invoice_"+InvoiceID+"_product_editor_product_row_"+index+"_PRICE_v").val(arrayProperty.PRICE);
					
			};
				$('.popup-window-button.popup-window-button-accept').on('click',function(){AddProduct(arrayProperty);});
			
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
			  className : 'popup-window-button-accept',
			  events : {
				click : function (){setTimeout(this.popupWindow.close(), 500);}
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
})
