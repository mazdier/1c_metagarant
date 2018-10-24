
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

$(document).keypress("q",function(e) {	
  if(e.ctrlKey){
		var calculator=new MZ.PopupCalculater(htmlText,'calculater','Калькулятор');
		calculator.set('<div>fsdfsdf</div>');

	}
});
MZ.PopupCalculater = function (htmlText,uniquePopupId,title){
	this._popupWindow = null;
	this.uniquePopupId = uniquePopupId;
		if(!flag_popup_show){
			var popupWindow = new BX.PopupWindow('calculater', null, {
			width : 600,
			height : 400,
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
MZ.PopupCalculater.prototype.set = function(content){

	//this.contentContainer.innerHTML = "<div id=\"root\"></div>";
	//const element = <h1>Hello, world</h1>;
//ReactDOM.render(element, document.getElementById('root'));
	
}
