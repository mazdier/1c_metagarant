<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Пример рисования многоугольника с выводом координат - API Яндекс.Карт v 2.x</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>	
    <script type="text/javascript">
        // Как только будет загружен API и готов DOM, выполняем инициализацию
if(window.ymaps)  init();
			else{   
				var script = document.createElement('script'); 
				document.head.appendChild(script);  
				script.type = 'text/javascript';
				script.src = "https://api-maps.yandex.ru/2.1/?lang=ru_RU";
				script.onload = init;
			}
			jQueryCode = function(){
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
			}
			
			
			function init () {
ymaps.ready(function(){
			console.log(42);
            var myMap = new ymaps.Map("map", {
                    center: [53.894548, 30.330654],
                    zoom: 15
                }),
                polygon = new ymaps.GeoObject({
        geometry: {
            type: "Polygon",
            coordinates: []
        }
    });
 
    myMap.geoObjects.add(polygon);
    polygon.editor.startDrawing();
 
	$('input').attr('disabled', false);
 
            // Обработка нажатия на любую кнопку.
            $('input').click(
                function () {
                    // Отключаем кнопки, чтобы на карту нельзя было
                    // добавить более одного редактируемого объекта (чтобы в них не запутаться).
                    $('input').attr('disabled', true);
 
                    polygon.editor.stopEditing();
 
					printGeometry(polygon.geometry.getCoordinates());
 
                });	
 
 })
        }
 
		// Выводит массив координат геообъекта в <div id="geometry">
        function printGeometry (coords) {
            $('#geometry').html('Координаты: ' + stringify(coords));
 
            function stringify (coords) {
                var res = '';
                if ($.isArray(coords)) {
                    res = '[ ';
                    for (var i = 0, l = coords.length; i < l; i++) {
                        if (i > 0) {
                            res += ', ';
                        }
                        res += stringify(coords[i]);
                    }
                    res += ' ]';
                } else if (typeof coords == 'number') {
                    res = coords.toPrecision(6);
                } else if (coords.toString) {
                    res = coords.toString();
                }
 
                return res;
            }
        }
				

 
    </script>
</head>
 
<body>
<h2>Пример рисования многоугольника с выводом координат</h2>
 
<div id="map" style="width:800px; height:600px"></div>
<input type="button" value="Завершить редактирование" id="stopEditPolyline"/>
<div id="geometry"/></div>
</body>
 
</html>