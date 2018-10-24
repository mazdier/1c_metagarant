<script>
RequestUpdate = function(){
	var TabId = "<?=$_REQUEST['tab']?>";
	var InvoiceId = "<?=$_REQUEST['InvoiceId']?>";
	 jQueryCode = function(){
		$('[name="form_'+TabId+'"]').prepend('<input name="InvoiceId"  value="'+InvoiceId+'" type="hidden">');
		$('[name="form_'+TabId+'"]').prepend('<input name="tab"  value="'+TabId+'" type="hidden">');
		$('#edit_button_produc_cut').on('click',function(){$('table#produc_cut input').each(function(item){
			var ObjectThis;
			var arrayRowNumberNameHeader = $(this).attr('name').match(/([^\[]+)(?=\])/g);
			if(arrayRowNumberNameHeader !== null){
				ObjectThis = this;
				switch (arrayRowNumberNameHeader[1]) {
					case 'col':
						var ProductBDID = arrayRowNumberNameHeader[0]; 
						$.ajax({
								url: '/include/1c_ajax_product.php',
								dataType: 'json',
								data:{'action':'product_tab','PODUCT_BD_ID':ProductBDID},
								success: function(priceInList){
									onProductLoadCalcSum(priceInList,ObjectThis,arrayRowNumberNameHeader);
								}
							});
					break;
				};
				
			}
			
		})});
	}
	if(window.jQuery)  jQueryCode();
	else{   
		var script = document.createElement('script'); 
		document.head.appendChild(script);  
		script.type = 'text/javascript';
		script.src = "//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js";

		script.onload = jQueryCode;
	}
}
var onProductLoadCalcSum = function(priceInList,element,arrayRowNumberNameHeader){
	console.log(priceInList);
	var price,summa,unit_metr,col;
	var NameInputWithoutHeader = 'FIELDS['+arrayRowNumberNameHeader[0]+']';
	var metr_unit = $(element).parent().parent().find('td')[2].innerText.match(/([^\(]+)(?=м\))/g);
	metr_unit = (metr_unit)?metr_unit[0]:'';
	if(metr_unit)$("[name='"+NameInputWithoutHeader+"\[unit_metr\]']").val(metr_unit);
	if(priceInList!=null||priceInList!='')$("[name='"+NameInputWithoutHeader+"\[price\]']").val(priceInList);
	if(!$("[name='"+NameInputWithoutHeader+"\[price\]']").val())$("[name='"+NameInputWithoutHeader+"\[price\]']").val(0);
	CalculateRez($("[name='"+NameInputWithoutHeader+"\[col\]']"));
	CalculateRez($("[name='"+NameInputWithoutHeader+"\[price\]']"));
	$(element).parent().parent().find("[name*='rez']").each(function(){
		CalculateRez($(this));
	})
}
var CalculateRez = function(element){
	$(element).keyup(function(e){ 
		var ColRez = 0;
		
		var price,summa,unit_metr,col;
		unit_metr = $(element).parent().parent().find("[name*='unit_metr']").val();
		var lengt = unit_metr;
		$(element).parent().parent().find("[name*='rez']").each(function(){
			var metr_rez = $(this).val();
			if(metr_rez !== ''){
				console.log(ColRez,$(this).val());
				ColRez++;
				lengt = lengt -  $(this).val();
				if(lengt<=0)ColRez--;
			}
		});
		$(element).parent().parent().find("[name*='kol_rzov']").val(ColRez);
		price = $(element).parent().parent().find("[name*='price']").val();
		col = $(element).parent().parent().find("[name*='col']").val();
		summa = $(element).parent().parent().find("[name*='summa']").val(price * col * ColRez);
		console.log(ColRez);
	});
}

RequestUpdate();
</script>
<?
//print_r($_REQUEST);
include_once("/home/bitrix/www/bitrix/modules/main/include/prolog_before.php");
global $DB;
global $USER;
$rsUsers = $USER->GetList(($by="LOGIN") , ($order="desc") , ["ID" =>$USER->GetID()],['SELECT' => ["UF_DEPARTMENT"]]);//

while($res=$rsUsers->Fetch()){ 
$res['PERSONAL_CITY'] = strtolower($res['PERSONAL_CITY']);
	switch($res['PERSONAL_CITY']){
		case 'минск':
			$UserCity = 'minsk';
		break;
		case 'могилев':
			$UserCity = 'mogilev';
		break;
		case 'могилёв':
			$UserCity = 'mogilev';
		break;
	}
}
$InvoiceID = $_REQUEST['InvoiceId']?$_REQUEST['InvoiceId']:false;
$TabID = $_REQUEST['tab']?$_REQUEST['tab']:false;
if($InvoiceID):
	if($TabID&&$TabID == 'produc_cut'){
		\Bitrix\Main\Loader::includeModule('crm');
		 $CCrmInvoice=new CCrmInvoice(false);
		//здесь обработка POST (см. ниже)
		//echo '<pre>';
		//это список групп пользователей, нужен будет для фильтра
		/*$aGroups = array();
		$gr_res = CGroup::GetDropDownList("AND ID!=2");
		while($aGr = $gr_res->Fetch())
		   $aGroups[$aGr["REFERENCE_ID"]] = $aGr["REFERENCE"];*/

		//уникальный идентификатор грида
		$arResult["GRID_ID"] = $TabID;

		//определяем фильтр, поля фильтра типизированы
		//по умолчанию тип text, поддерживается date, list, number, quick (поле ввода и список), custom
		$arResult["FILTER"] = array(
		   array("id"=>"FIND", "name"=>"Найти", "type"=>"quick", "items"=>array("login"=>"Логин", "email"=>"Email", "name"=>"Имя")),
		   array("id"=>"PERSONAL_BIRTHDAY", "name"=>"День рождения", "type"=>"date"),
		   array("id"=>"PERSONAL_PROFESSION", "name"=>"Профессия"),
		   array("id"=>"PERSONAL_WWW", "name"=>"Веб"),
		   array("id"=>"PERSONAL_ICQ", "name"=>"АйСикЮ", "params"=>array("size"=>15)),
		   array("id"=>"PERSONAL_GENDER", "name"=>"Пол", "type"=>"list", "items"=>array(""=>"(пол)", "M"=>"Мужской", "F"=>"Женский")),
		   array("id"=>"GROUPS_ID", "name"=>"Группы пользователей", "type"=>"list", "items"=>$aGroups, "params"=>array("size"=>5, "multiple"=>"multiple"), "valign"=>"top"),
		   array("id"=>"PERSONAL_PHONE", "name"=>"Телефон"),
		   array("id"=>"PERSONAL_MOBILE", "name"=>"Мобильник"),
		   array("id"=>"PERSONAL_CITY", "name"=>"Город"),
		);

		//инициализируем объект с настройками пользователя для нашего грида
		$grid_options = new CGridOptions($arResult["GRID_ID"]);

		//какую сортировку сохранил пользователь (передаем то, что по умолчанию)
		//$aSort = $grid_options->GetSorting(array("sort"=>array("id"=>"desc"), "vars"=>array("by"=>"by", "order"=>"order")));

		//размер страницы в постраничке (передаем умолчания)
		$aNav = $grid_options->GetNavParams(array("nPageSize"=>10));

		//получим текущий фильтр (передаем описанный выше фильтр)
		$aFilter = $grid_options->GetFilter($arResult["FILTER"]);
		//некоторые названия полей фильтра могут не совпадать с API 
		/*if(isset($aFilter["PERSONAL_BIRTHDAY_from"]))
		   $aFilter["PERSONAL_BIRTHDAY_1"] = $aFilter["PERSONAL_BIRTHDAY_from"];
		if(isset($aFilter["PERSONAL_BIRTHDAY_to"]))
		   $aFilter["PERSONAL_BIRTHDAY_2"] = $aFilter["PERSONAL_BIRTHDAY_to"];
		if(isset($aFilter["FIND"]))
		   $aFilter[strtoupper($aFilter["FIND_list"])] = $aFilter["FIND"];*/

		//это собственно выборка данных с учетом сортировки и фильтра, указанных пользователем
		//$aSortArg = each($aSort["sort"]);
		//$db_res = CCrmProduct::GetList([], $aFilter);
		
		if($_REQUEST['action_button_'.$_REQUEST['tab']] == 'edit'){
			foreach($_REQUEST['FIELDS'] as $key => $value){
				$DB->Update("1c_product_table", $value, "WHERE id='".$key."'", $err_mess.__LINE__);
			}
		}	
		$ArrElementBasket = $CCrmInvoice->GetProductRows($InvoiceID);
		//print_r($ArrElementBasket);
		$db_res = $DB->Query("SELECT * FROM 1c_product_table WHERE invoice_id =".$InvoiceID);
		//постраничка с учетом размера страницы
		$db_res->NavStart($aNav["nPageSize"]);
		if($db_res->SelectedRowsCount() == 0 || $db_res->SelectedRowsCount() != count($ArrElementBasket)){	
				UpdateProductTableDB($InvoiceID,$ArrElementBasket);
				$db_res = $DB->Query("SELECT * FROM 1c_product_table WHERE invoice_id =".$InvoiceID);
		}
		

		//в этом цикле построчно заполняем данные для грида
		$aRows = array();
		while($aRes = $db_res->GetNext())
		{
			$aRes["ID"] = $aRes["id"];
		//в этой переменной - поля, требующие нестандартного отображения (не просто значение)
		   $aCols = array(
			 // "PERSONAL_GENDER" => ($aRes["PERSONAL_GENDER"] == "M"? "Мужской":($aRes["PERSONAL_GENDER"] == "F"? "Женский":"")),
			 // "EMAIL" => '<a href="mailto:'.$aRes["EMAIL"].'">'.$aRes["EMAIL"].'</a>',
			 // "ID" => $aRes["id"],
			 // "LOGIN" => '<a href="main.interface.form.php?ID='.$aRes["ID"].'">'.$aRes["LOGIN"].'</a>',
		   );

		//это определения для меню действий над строкой
		 /*  $aActions = Array(
			  array("ICONCLASS"=>"edit", "TEXT"=>"Изменить", "ONCLICK"=>"jsUtils.Redirect(arguments, 'main.interface.form.php?ID=".$aRes["ID"]."')", "DEFAULT"=>true),
			  array("ICONCLASS"=>"copy", "TEXT"=>"Добавить копию", "ONCLICK"=>"jsUtils.Redirect(arguments, '/bitrix/admin/user_edit.php?COPY_ID=".$aRes["ID"]."')"),
			  array("SEPARATOR"=>true),
			  array("ICONCLASS"=>"delete", "TEXT"=>"Удалить", "ONCLICK"=>"if(confirm('Вы уверены, что хотите удалить данного пользователя?')) window.location='/bitrix/admin/user_admin.php?action=delete&ID=".$aRes["ID"]."&".bitrix_sessid_get()."';"),
		   );
		*/
		//запомнили данные. "data" - вся выборка,  "editable" - можно редактировать строку или нет
		   $aRows[] = array("data"=>$aRes, 
		   //"actions"=>$aActions,
		   "columns"=>$aCols,
		   "editable"=>true);
		}
		//наши накопленные данные
		$arResult["ROWS"] = $aRows;

		//информация для футера списка
		$arResult["ROWS_COUNT"] = $db_res->SelectedRowsCount();

		//сортировка
		$arResult["SORT"] = $aSort["sort"];
		$arResult["SORT_VARS"] = $aSort["vars"];

		//объект постранички - нужен гриду. Убираем ссылку "все".
		$db_res->bShowAll = false;
		$arResult["NAV_OBJECT"] = $db_res;

		//покажем панель с кнопками
		/*$APPLICATION->IncludeComponent(
		   "bitrix:main.interface.toolbar",
		   "",
		   array(
			  "BUTTONS"=>array(
				 array(
					"TEXT"=>"Список",
					"TITLE"=>"Список пользователей",
					"LINK"=>$APPLICATION->GetCurPage(),
					"ICON"=>"btn-list",
				 ),
				 array("SEPARATOR"=>true), 
				 array(
					"TEXT"=>"Скопировать админа",
					"TITLE"=>"Скопировать пользователя номер 1",
					"LINK"=>"/bitrix/admin/user_edit.php?COPY_ID=1",
					"ICON"=>"btn-copy",
				 ),
				 array(
					"TEXT"=>"Скопировать себя",
					"TITLE"=>"Скопировать пользователя номер 1",
					"LINK"=>"/bitrix/admin/user_edit.php?COPY_ID=".$GLOBALS["USER"]->GetID(),
					"ICON"=>"btn-copy",
				 ),
				 array("NEWBAR"=>true), 
				 array(
					"TEXT"=>"Добавить",
					"TITLE"=>"Добавить пользователя или группу",
					"MENU"=>array(
					   array("ICONCLASS"=>"add", "TEXT"=>"Пользователя", "ONCLICK"=>"jsUtils.Redirect(arguments, '/bitrix/admin/user_edit.php')"),
					   array("ICONCLASS"=>"add", "TEXT"=>"Группу пользователей", "ONCLICK"=>"jsUtils.Redirect(arguments, '/bitrix/admin/group_edit.php')"),
					),
					"ICON"=>"btn-new",
				 ),
			  ),
		   ),
		   $component
		);*/?>

		<?

		//вызовем компонент грида для отображения данных
		$APPLICATION->IncludeComponent(
		   "bitrix:main.interface.grid",
		   "",
		   array(
		//уникальный идентификатор грида
			  "GRID_ID"=>$arResult["GRID_ID"],
		//описание колонок грида, поля типизированы
			  "HEADERS"=>array(
				 array("id"=>"product_name", "name"=>"Название продукта",  "default"=>false, "editable"=>false),
				 array("id"=>"unit_metr", "name"=>"В шт. метров", "default"=>true, "editable"=>true),
				 array("id"=>"col", "name"=>"Штук", "default"=>true, "editable"=>true),
				 array("id"=>"rez_1", "name"=>"1 Рез", "default"=>true, "editable"=>true),
				 array("id"=>"rez_2", "name"=>"2 Рез", "default"=>true, "editable"=>true),
				 array("id"=>"rez_3", "name"=>"3 Рез", "default"=>true, "editable"=>true),
				 array("id"=>"kol_rzov", "name"=>"Количество резов", "default"=>true, "editable"=>true),
				 array("id"=>"price", "name"=>"Цена", "default"=>true, "editable"=>true),
				 array("id"=>"summa", "name"=>"Сумма", "default"=>true, "editable"=>true),
				 
//				array("id"=>"LAST_NAME", "name"=>"Фамилия",  "sort"=>"last_name", "default"=>true, "editable"=>array("size"=>20, "maxlength"=>255)),
//				 array("id"=>"SECOND_NAME", "name"=>"Отчество",  "sort"=>"second_name"),
//				 array("id"=>"EMAIL", "name"=>"Email",  "sort"=>"email", "default"=>true, "editable"=>array("size"=>20, "maxlength"=>255)),
//				 array("id"=>"LAST_LOGIN", "name"=>"Аторизовывался", "sort"=>"last_login"),
//				 array("id"=>"DATE_REGISTER", "name"=>"Зарегистрирован", "sort"=>"date_register"),
//				 array("id"=>"ID", "name"=>"ИД", "sort"=>"id", "default"=>true, "align"=>"right"),
//				 array("id"=>"PERSONAL_BIRTHDAY", "name"=>"День рождения", "sort"=>"personal_birthday", "default"=>true, "type"=>"date", "editable"=>true),
//				 array("id"=>"PERSONAL_PROFESSION", "name"=>"Профессия", "sort"=>"personal_profession"),
//				 array("id"=>"PERSONAL_WWW", "name"=>"Веб", "sort"=>"personal_www"),
//				 array("id"=>"PERSONAL_ICQ", "name"=>"АйСикЮ", "sort"=>"personal_icq"),
//				 array("id"=>"PERSONAL_GENDER", "name"=>"Пол", "sort"=>"personal_gender", "default"=>true, "type"=>"list", "editable"=>array("items"=>array(""=>"(пол)", "M"=>"Мужской", "F"=>"Женский"))),
//				 array("id"=>"PERSONAL_PHONE", "name"=>"Телефон", "sort"=>"personal_phone"),
//				 array("id"=>"PERSONAL_MOBILE", "name"=>"Мобильник", "sort"=>"personal_mobile"),
//				 array("id"=>"PERSONAL_CITY", "name"=>"Город", "sort"=>"personal_city"),
//				 array("id"=>"PERSONAL_STREET", "name"=>"Улица", "sort"=>"personal_street"),
//				 array("id"=>"WORK_COMPANY", "name"=>"Компания", "sort"=>"work_company"),
//				 array("id"=>"WORK_DEPARTMENT", "name"=>"Отдел", "sort"=>"work_department"),
//				 array("id"=>"WORK_POSITION", "name"=>"Должность", "sort"=>"work_position"),
//				 array("id"=>"WORK_WWW", "name"=>"Раб. веб", "sort"=>"work_www"),
//				 array("id"=>"WORK_PHONE", "name"=>"Раб. тел.", "sort"=>"work_phone"),
//				 array("id"=>"WORK_CITY", "name"=>"Раб. город", "sort"=>"work_city"),
//				 array("id"=>"XML_ID", "name"=>"Символьный код", "sort"=>"xml_id"),
//				 array("id"=>"EXTERNAL_AUTH_ID", "name"=>"Внешний код"),
			  ),
		//сортировка
			 // "SORT"=>$arResult["SORT"],
		//это необязательный
			//  "SORT_VARS"=>$arResult["SORT_VARS"],
		//данные
			  "ROWS"=>$arResult["ROWS"],
		//футер списка, можно задать несколько секций
			 // "FOOTER"=>array(array("title"=>"Всего", "value"=>$arResult["ROWS_COUNT"])),
		//групповые действия
			 /* "ACTIONS"=>array(
		//можно удалять
			   //  "delete"=>true, 
		//выпадающий список действий
				// "list"=>array("activate"=>"Активировать", "deactivate"=>"Деактивировать"),
		//либо произвольный html
				 "custom_html"=>'
					<select name="action_on_files" onchange="this.form.folder_id.style.display=(this.value==\'move\'? \'\':\'none\');">
					   <option>- действия -</option>
					   <option value="move">Переместить</option>
					</select>
					<select name="folder_id" style="display:none">
					   <option value="folder1">folder1</option>
					   <option value="folder2">folder2</option>
					</select>
				 ',
			  ),*/
		//разрешить действия над всеми элементами
			  "ACTION_ALL_ROWS"=>true,
		//разрешено редактирование в списке
			 "EDITABLE"=>true,
		//объект постранички
			 // "NAV_OBJECT"=>$arResult["NAV_OBJECT"],
		//можно использовать в режиме ajax
			  "AJAX_MODE"=>"Y",
			  "AJAX_OPTION_JUMP"=>"Y",
			  "AJAX_OPTION_STYLE"=>"Y",
		//фильтр
			 // "FILTER"=>$arResult["FILTER"],
		   ),
		   $component
		);
	}
	else if($TabID&&$TabID == 'map'):
		//print_r($UserCity);
		?>
		<div id="map" style="width:900px; height:300px; float:left;margin:30px;"></div>
		<div style="float:right;margin-top:50px;width: 30%;">
			<button class="crm-toolbar-btn ui-btn ui-btn-md ui-btn-light-border crm-toolbar-menu-left" id="minsk" title="Переключиться на Минск">Минск</button>
			<button class="crm-toolbar-btn ui-btn ui-btn-md ui-btn-light-border crm-toolbar-menu-left" id="mogilev" title="Переключиться на Могилев">Могилев</button>
		</div>
		<div style="float:right;margin-top:50px;width: 30%;">
			
				<div id='city'>Расстояние по городу: <input type="text"></div></br>
				<div id = 'undercity'>Расстояние за городом: <input type="text"></div></br>
				<div id = 'price'>Цена:  <input type="text"></div></br></br>
			
			<div id= 'sv'>
				
			</div>
		</div>
		<script type="text/javascript">

YmapsCode = function(){
			ymaps.ready(init);
			var elMi = document.getElementById('minsk');
			elMi.onclick = function() {document.getElementById('map').innerHTML = '';init('minsk');$('#sv').html('')}
			var elMi = document.getElementById('mogilev');
			elMi.onclick = function() {document.getElementById('map').innerHTML = '';init('mogilev');$('#sv').html('')}
			

	function init(city = '') {
		var UserCity = "<?=$UserCity?>";
		UserCity =(city)?city:UserCity;
		var CordinateInPoint = (UserCity == 'mogilev')?'Могилёв улица Челюскинцев, 140':'Минск улица Змитрока Бядули, 13';
		var CentorCityCoordinate = (UserCity == 'mogilev')?[53.894548, 30.330654]:[53.904082, 27.560458];
		//console.log(CordinateInPoint);
		// Стоимость за километр.
		window.SumDistanceCity = 0;
		var DELIVERY_TARIFF = 0.35,
			DELIVERY_TARIFF_CITY = 19,
		// Минимальная стоимость.
			MINIMUM_COST = 5,
			
			myMap = new ymaps.Map('map', {
				center: CentorCityCoordinate,
				zoom: 10,
				controls: []
			}),
		// Создадим панель маршрутизации.
			routePanelControl = new ymaps.control.RoutePanel({
				options: {
					// Добавим заголовок панели.
					showHeader: true,
					title: 'Расчёт доставки'
				}
			}),
			zoomControl = new ymaps.control.ZoomControl({
				options: {
					size: 'small',
					float: 'none',
					position: {
						bottom: 145,
						right: 10
					}
				}
			});
			
		// Пользователь сможет построить только автомобильный маршрут.
		routePanelControl.routePanel.options.set({
			types: {auto: true}
		});
		
		routePanelControl.routePanel.state.set({
			fromEnabled: false,
			from: CordinateInPoint
		 });

		myMap.controls.add(routePanelControl).add(zoomControl);

		// Получим ссылку на маршрут.
		routePanelControl.routePanel.getRouteAsync().then(function (route) {
			
			// Зададим максимально допустимое число маршрутов, возвращаемых мультимаршрутизатором.
			route.model.setParams({results: 1}, true);

			// Повесим обработчик на событие построения маршрута.
			route.model.events.add('requestsuccess', function () {
				var activeRoute = route.getActiveRoute();
				if (activeRoute) {
					window.activeRoute = activeRoute;
					
					var CordinateOutPoint = activeRoute.model.multiRoute.properties._data.waypoints[1].request;
					var routeNew;
						function onPolygonLoad (json) {
							minskPolygon = new ymaps.Polygon(json.coordinates);
							// Если мы не хотим, чтобы контур был виден, зададим соответствующую опцию.
							minskPolygon.options.set('visible', false);
							// Чтобы корректно осуществлялись геометрические операции
							// над спроецированным многоугольником, его нужно добавить на карту.
							myMap.geoObjects.add(minskPolygon);
							routeNew = ymaps.route([CordinateInPoint, CordinateOutPoint]);
							routeNew.then(
								function (res) {
									var length = 0;SumDistanceCity = 0;
									// Объединим в выборку все сегменты маршрута.
									var pathsObjects = ymaps.geoQuery(res.getPaths()),
										edges = [];
										
									// Переберем все сегменты и разобьем их на отрезки.
									pathsObjects.each(function (path) {
										var coordinates = path.geometry.getCoordinates();
										for (var i = 1, l = coordinates.length; i < l; i++) {
											edges.push({
												type: 'LineString',
												coordinates: [coordinates[i], coordinates[i - 1]]
											});
										}
									});
									
									// Создадим новую выборку, содержащую:
									// - отрезки, описываюшие маршрут;
									// - начальную и конечную точки;
									// - промежуточные точки.
									var routeObjects = ymaps.geoQuery(edges)
											.add(res.getWayPoints())
											.add(res.getViaPoints())
											.setOptions('strokeWidth', 3)
											.addToMap(myMap);
										// Найдем все объекты, попадающие внутрь МКАД.
										var objectsInMinsk = routeObjects.searchInside(minskPolygon);
										// Найдем объекты, пересекающие МКАД.
										var boundaryObjects = routeObjects.searchIntersect(minskPolygon);
									
									var objectsInMinsk = objectsInMinsk;
									var boundaryObjects = boundaryObjects;
									function degreesToRadians(degrees) {
									  return degrees * Math.PI / 180;
									}

									function distanceInKmBetweenEarthCoordinates(lat1, lon1, lat2, lon2) {
									  var earthRadiusKm = 6371;

									  var dLat = degreesToRadians(lat2-lat1);
									  var dLon = degreesToRadians(lon2-lon1);

									  lat1 = degreesToRadians(lat1);
									  lat2 = degreesToRadians(lat2);

									  var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
											  Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
									  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
									  return earthRadiusKm * c;
									}
									
									objectsInMinsk.each(function(item,index){ SumDistanceCity = SumDistanceCity + distanceInKmBetweenEarthCoordinates(
																					item.geometry.getBounds()[0][0],
																					item.geometry.getBounds()[0][1],
																					item.geometry.getBounds()[1][0],
																					item.geometry.getBounds()[1][1]
									)});
									// Раскрасим в разные цвета объекты внутри, снаружи и пересекающие МКАД.									
									/*boundaryObjects.setOptions({
										strokeColor: '#06ff00',
										preset: 'islands#greenIcon'
									});
									objectsInMinsk.setOptions({
										strokeColor: '#ff0005',
										preset: 'islands#redIcon'
									});*/
									// Объекты за пределами МКАД получим исключением полученных выборок из
									// исходной.
									routeObjects.remove(objectsInMinsk).remove(boundaryObjects).setOptions({
										strokeColor: '#0010ff',
										preset: 'islands#blueIcon'
									});
									window.routeObjects = routeObjects;
									// Получим протяженность маршрута.
					//console.log(route.getWayPoints().get(0));
					   length = route.getActiveRoute().properties.get("distance");
						length = Math.round(length.value / 1000) - SumDistanceCity;
					// Вычислим стоимость доставки.
					var	price = calculate(length,SumDistanceCity);
					// Создадим макет содержимого балуна маршрута.
					var distance = (length<=0)?'<span>Расстояние по городу: ' + SumDistanceCity.toFixed(3) + ' км.</span><br/>':'<span>Расстояние по городу: ' + SumDistanceCity.toFixed(3) + ' км. Расстояние за городом ' + length.toFixed(3) + ' км.</span><br/>';
					$('#city').find('input').val(SumDistanceCity.toFixed(3));
					if (length) $('#undercity').find('input').val(length.toFixed(3));
					$('#price').find('input').val(price.toFixed(2));
					$('#sv').html('<button class="crm-toolbar-btn ui-btn ui-btn-md ui-btn-light-border crm-toolbar-menu-left" id="save" title="Сохранить результат">Сохранить</button>');
					$('#save').on('click',function(){
						var invoice_id = "<?=$_REQUEST['InvoiceId']?>";
						var data = {InvoiceId : invoice_id, distanceCity : SumDistanceCity.toFixed(3), distanceUnderCity : length.toFixed(3), price : price.toFixed(2)}
						$.ajax({
								url: '/include/1c_ajax_product.php',
								dataType: 'json',
								data:{ action : 'product_tab_save_data_map', data : data},
								success: function(data){
									console.log(data);
								}
							});
					});
					var	balloonContentLayout = ymaps.templateLayoutFactory.createClass(
							distance +
							'<span style="font-weight: bold; font-style: italic">Стоимость доставки: ' + price.toFixed(2) + ' р.</span>');
					// Зададим этот макет для содержимого балуна.
					route.options.set('routeBalloonContentLayout', balloonContentLayout);
					// Откроем балун.
					activeRoute.balloon.open();routeObjects.removeFromMap(myMap);
								}
							);
							
						}
						//route.setParent(routeNew);
						function jQueryCode(){	
						var url = (UserCity == 'mogilev')?'https://crm.metagarant.by/include/mogilev.json':'https://crm.metagarant.by/include/minsk.json';
							$.ajax({
								url: url,
								dataType: 'json',
								success: onPolygonLoad
							});
						}
						if(window.jQuery)  jQueryCode();
						else{   
							var script = document.createElement('script'); 
							document.head.appendChild(script);  
							script.type = 'text/javascript';
							script.src = "//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.js";
							script.onload = jQueryCode;
						}
					
					
				}
			});
		});
		// Функция, вычисляющая стоимость доставки.
		function calculate(routeLength,DistanceCity) {
			console.log(routeLength,DistanceCity);
			return (routeLength<=0)?DELIVERY_TARIFF_CITY:(Math.max(routeLength * DELIVERY_TARIFF, MINIMUM_COST)+ DELIVERY_TARIFF_CITY);
		}
	}
};
function GetDataMap(){
	var invoice_id = "<?=$_REQUEST['InvoiceId']?>";
	$.ajax({
		url: '/include/1c_ajax_product.php',
		dataType: 'json',
		data:{'action':'product_tab_get_data_map','InvoiceId':invoice_id},
		success: function(data){
			if(typeof data === 'object'){
				if(typeof data.id !== 'undefined'){
					$('#city').find('input').val(data.distance_city);
					$('#undercity').find('input').val(data.distance_under_city);
					$('#price').find('input').val(data.price);
					$('#sv').html('<button class="crm-toolbar-btn ui-btn ui-btn-md ui-btn-light-border crm-toolbar-menu-left" id="save" title="Сохранить результат">Сохранить</button>');
					$('#save').on('click',function(){
						//console.log(data);
						var datas = {InvoiceId : data.invoice_id, distanceCity : data.distance_city, distanceUnderCity : data.distance_under_city, price : data.price};
						$.ajax({
								url: '/include/1c_ajax_product.php',
								dataType: 'json',
								data:{ action : 'product_tab_save_data_map', data : datas},
								success: function(data){
									console.log(data);
								}
							});
					});
				}
			}
		}
	});
}
if(window.jQuery)  GetDataMap();
else{   
	var script = document.createElement('script'); 
	document.head.appendChild(script);  
	script.type = 'text/javascript';
	script.src = "//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.js";
	script.onload = jQueryCode;
}
if(window.ymaps)  YmapsCode();
else{   
	var script = document.createElement('script'); 
	document.head.appendChild(script);  
	script.type = 'text/javascript';
	script.src = "https://api-maps.yandex.ru/2.1/?lang=ru_RU";
	script.onload = YmapsCode;
}
			/*jQueryCode = function(){
				$('[name="point_1"]').on('click',function(event){
					PointFlag=1;
				})
				$('[name="point_2"]').on('click',function(event){
					PointFlag=2;
				})
			}
			if(window.jQuery)  jQueryCode();
			else{   
				var script = document.createElement('script'); 
				document.head.appendChild(script);  
				script.type = 'text/javascript';
				script.src = "//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js";

				script.onload = jQueryCode;
			}*/
		</script>
		<?
	endif;
endif;
/**
 * @param $InvoiceID
 * @param $ArrElementBasket
 */
function UpdateProductTableDB($InvoiceID, $ArrElementBasket){
	foreach($ArrElementBasket as $ElementBasket){
		if($ElementBasket){
		    if($ElementBasket['PRODUCT_NAME'] !== 'Резка' || $ElementBasket['PRODUCT_NAME'] !== 'Доставка'){
                $_res = $GLOBALS['DB']->Query("SELECT * FROM 1c_product_table WHERE basket_id =".$ElementBasket["ID"]);
                if($_res->SelectedRowsCount() == 0){
                    $arFields = [
                    "basket_id"=>"'".$ElementBasket["ID"]."'",
                    "invoice_id"=>"'".$InvoiceID."'",
                    "product_id" => "'".$ElementBasket["PRODUCT_ID"]."'",
                    "product_name" => "'".$ElementBasket["PRODUCT_NAME"]."'",
                    ];
                    $GLOBALS['DB']->Insert("1c_product_table", $arFields,$err_mess.__LINE__);
                }
                else{

                }
                $arElemId[] = $ElementBasket["ID"];
            }
		}
	}
	$arElemId = implode(',',$arElemId);
	if(!empty($arElemId)){
		$_res = $GLOBALS['DB']->Query("SELECT * FROM 1c_product_table WHERE basket_id NOT IN (".$arElemId.") AND invoice_id = ".$InvoiceID);
		if($_res->SelectedRowsCount() == 0);
		while($ElementBD = $_res->Fetch()){
			$GLOBALS['DB']->Query("DELETE  FROM 1c_product_table WHERE basket_id =".$ElementBD['basket_id']);
		}
	}
}
?>
