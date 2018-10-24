document.addEventListener("DOMContentLoaded", function(event) { 
var flag_popup_show=false;
var MZ=[];
var htmlText='<script type="text/javascript">function get(){console.log(354)}</script><div onclick="get()">dfgfsdgsdfg</div>';
			/*chrome.extension.onConnect.addListener(function(port){   
			window.port = port;
			port.onMessage.addListener(function(request){
				popupWindow = window.open(request.URL, 'calculate', 'width=700,height=350');
				console.log(request.URL);
				popupWindow.focus();
			}); }); */	

document.onkeypress = function(e) {
	if(e.ctrlKey){
		//console.log(e.charCode);
		if(e.charCode=='17'||e.charCode=='113'){

					
				var calculator=new MZ.PopupCalculater(htmlText,'calculater','Калькулятор');
				calculator.set();
		}
	}
};
MZ.PopupCalculater = function (htmlText,uniquePopupId,title){
	this._popupWindow = null;
	this.uniquePopupId = uniquePopupId;
		if(!flag_popup_show){
			var popupWindow = new BX.PopupWindow('calculater', null, {
			width : 760,
			height : 200,
			closeByEsc : true,
			closeIcon : true,
			overlay : {
			  opacity : 50,
			  backgroundColor : '#000'
			},
			titleBar : 'Калькулятор',
			content : console.log(42),
			
			buttons : [ new BX.PopupWindowButton({
			  text : 'Посчитать',
			  className : 'popup-window-button-accept',
			  events : {
				click : function(){}
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
		console.log(popupWindow);
		}
};
MZ.PopupCalculater.prototype.set = function(){

	this.contentContainer.innerHTML = "<div id=\"root\"></div>";
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Lang = "ru";
var headText = "",
    headTextLine = "",
    zUnit = '',
    zMtU = '1',
    zPrice = "2",
    zSumm = "3",
    zUDweight = "4",
    zTn = "5",
    zMt = "6";
zUnit = 7;
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

var InputTable = function (_React$Component) {
  _inherits(InputTable, _React$Component);

  function InputTable(props) {
    _classCallCheck(this, InputTable);

    var _this = _possibleConstructorReturn(this, (InputTable.__proto__ || Object.getPrototypeOf(InputTable)).call(this, props));

    _this.state = { val: "" };
    _this.handleChange = _this.handleChange.bind(_this);
    return _this;
  }

  _createClass(InputTable, [{
    key: "handleChange",
    value: function handleChange(e) {
      this.props.onHandleChange(e.target.value);
    }
  }, {
    key: "render",
    value: function render() {
      var val = this.props.val;
      return React.createElement("input", {
        style: { width: "80px" },
        value: val,
        onChange: this.handleChange
      });
    }
  }]);

  return InputTable;
}(React.Component);

var Calculate = function (_React$Component2) {
  _inherits(Calculate, _React$Component2);

  function Calculate(props) {
    _classCallCheck(this, Calculate);

    //this.handleChange = this.handleChange.bind(this);
    var _this2 = _possibleConstructorReturn(this, (Calculate.__proto__ || Object.getPrototypeOf(Calculate)).call(this, props));

    _this2.state = {
      priceOst: "",
      summOst: "",
      uDweightOst: "",
      tnOst: "",
      mtOst: "",
      mtUOst: "",
      unitOst: "",
      zUnit: '', zMtU: zMtU, zPrice: zPrice,
      zSumm: zSumm,
      zUDweight: zUDweight,
      zTn: zTn,
      zMt: zMt
    };
    _this2.handlePriceOstChange = _this2.handlePriceOstChange.bind(_this2);
    _this2.handleUnitOstChange = _this2.handleUnitOstChange.bind(_this2);
    _this2.handleSummOstChange = _this2.handleSummOstChange.bind(_this2);
    _this2.handleUdWeightOstChange = _this2.handleUdWeightOstChange.bind(_this2);
    _this2.handleTnOstChange = _this2.handleTnOstChange.bind(_this2);
    _this2.handleMtOstChange = _this2.handleMtOstChange.bind(_this2);
    _this2.handleMtUOstChange = _this2.handleMtUOstChange.bind(_this2);
    _this2.state.zUnit = zUnit;
    return _this2;
  }

  _createClass(Calculate, [{
    key: "handlePriceOstChange",
    value: function handlePriceOstChange(val) {
      this.setState({ priceOst: val });
      if (this.state.tnOst.length !== 0) {
        var summOst = val * this.state.tnOst;
        this.setState({ summOst: summOst });
      } else {
        var _summOst = val * this.state.unitOst;
        this.setState({ summOst: _summOst });
      }
    }
  }, {
    key: "handleUnitOstChange",
    value: function handleUnitOstChange(val) {
      var mtOstNew = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

      this.setState({ unitOst: val });
      if (this.state.mtUOst.length !== 0) {
        if (zUnit.length !== 0) {
          console.log(zUnit);
          var zUnitNew = zUnit - val;
          /*if (zUnitNew<=0){
            val = zUnit;
            zUnitNew=0;
          }*/
          this.setState({ zUnit: zUnitNew });
        }
        var mtOst = mtOstNew ? mtOstNew : val * this.state.mtUOst;
        this.setState({ mtOst: mtOst });
        var tnOst = mtOst * this.state.uDweightOst / 1000;

        if (val.length !== 0) {
          if (mtOstNew) {
            this.setState(this.handleTnOstChange(tnOst, val, false));
          } else this.setState(this.handleTnOstChange(tnOst, val, false));
        }
      } else {
        var summOst = val * this.state.priceOst;
        this.setState({ summOst: summOst });
      }
    }
  }, {
    key: "handleSummOstChange",
    value: function handleSummOstChange(val) {
      this.setState({ summOst: val });
      var tnOst = val / this.state.priceOst;
      this.setState({ tnOst: tnOst });
    }
  }, {
    key: "handleUdWeightOstChange",
    value: function handleUdWeightOstChange(val) {
      this.setState({ uDweightOst: val });
      var tnOst = this.state.mtOst * (val / 1000);
      this.setState(this.handleTnOstChange(tnOst, this.state.unitOst, false));
    }
  }, {
    key: "handleTnOstChange",
    value: function handleTnOstChange(val) {
      var unitOstNew = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var mtUOst = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;

      this.setState({ tnOst: val });
      var summOst = val * this.state.priceOst;
      this.setState({ summOst: summOst });
      if (this.state.mtUOst) {
        var mtOst = val * 1000 / this.state.mtUOst;
        var unitOst = unitOstNew ? unitOstNew : mtOst / this.state.mtUOst;
        this.setState({ unitOst: unitOst });
        if (mtUOst) {
          this.setState(this.handleMtOstChange(mtOst, false, val));
        }
      }
    }
  }, {
    key: "handleMtOstChange",
    value: function handleMtOstChange(val) {
      var mtUOst = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
      var tnOst = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;

      this.setState({ mtOst: val });
      if (val.length !== 0) {
        if (tnOst === false) {
          if (mtUOst === false) {
            var unitOst = val / this.state.mtUOst;
            this.setState(this.handleUnitOstChange(unitOst, val));
          } else {
            var _tnOst = val * mtUOst / 1000;
            var _unitOst = val / mtUOst;
            this.setState(this.handleTnOstChange(_tnOst, _unitOst, false));
          }
        }
      }
    }
  }, {
    key: "handleMtUOstChange",
    value: function handleMtUOstChange(val) {
      this.setState({ mtUOst: val });
      var mtOst = val * this.state.unitOst;
      if (val.length !== 0) {
        this.setState(this.handleMtOstChange(mtOst, val));
      }
    }
  }, {
    key: "render",
    value: function render() {
      var priceOst = this.state.priceOst;
      var uDweightOst = this.state.uDweightOst;
      var tnOst = this.state.tnOst;
      var mtOst = this.state.mtOst;
      var mtUOst = this.state.mtUOst;
      var unitOst = this.state.unitOst;
      var summOst = this.state.summOst;
      var zPriceNew = this.state.zPrice;
      var zUDweightNew = this.state.zUDweight;
      var zTnNew = this.state.zTn;
      var zMtNew = this.state.zMt;
      var zUnitNew = this.state.zUnit;
      var zMtUNew = this.state.zMtU;
      var zSummNew = this.state.zSumm;
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
  }]);

  return Calculate;
}(React.Component);

ReactDOM.render(React.createElement(Calculate, null), document.getElementById('root'));
	
}

})