
<script type="text/javascript">
			var PointFlag = 0;
			YmapsCode = function(){
				var minsk_map,Point1,Point2;
				var CordsPoint1,CordsPoint2;
				ymaps.ready(function(){
					minsk_map = new ymaps.Map("map", {
						center: [53.904082, 27.560458],
						zoom: 10
					});
					
					minsk_map.events.add('click', function (e) {
						// Получение координат щелчка
						var coords = e.get('coords');
						
						if(PointFlag == 1){
							if(Point1){
								Point1 = new ymaps.Placemark(coords, {iconContent:'Откуда', hintContent: 'Точка 1', balloonContent: 'Точка 1' },{
										preset: 'islands#blueStretchyIcon',
										iconColor: '#ff0000'
								});
								minsk_map.geoObjects.splice(0, 1, Point1);
							}
							else{
								Point1 = new ymaps.Placemark(coords, {iconContent:'Откуда', hintContent: 'Точка 1', balloonContent: 'Точка 1' },{
										preset: 'islands#blueStretchyIcon',
										iconColor: '#ff0000'
								});
								minsk_map.geoObjects.splice(0, 1, Point1);
							}
							CordsPoint1 = coords;
							//console.log(CordsPoint1 , CordsPoint2);
							$('[name="point_1"]').val(coords.join(', '));
						}
						else if(PointFlag == 2){
							if(Point1){
								Point2 = new ymaps.Placemark(coords, { iconContent:'Куда', hintContent: 'Точка 2', balloonContent: 'Точка 2' },{
										preset: 'islands#redStretchyIcon'
								});
								minsk_map.geoObjects.splice(1, 1, Point2);
							}
							else{
								Point2 = new ymaps.Placemark(coords, {iconContent:'Куда', hintContent: 'Точка 2', balloonContent: 'Точка 2' },{
										preset: 'islands#redStretchyIcon'
								});
								minsk_map.geoObjects.splice(1, 1, Point2);
							}
							CordsPoint2 = coords;
							//console.log(CordsPoint1 , CordsPoint2);
							$('[name="point_2"]').val(coords.join(', '));
							$('#length').text(Math.round((ymaps.coordSystem.geo.getDistance(CordsPoint1, CordsPoint2)/1000)*100)/100+' км');
							//console.log(ymaps.coordSystem.geo.getDistance(CordsPoint1, CordsPoint2)/1000);
							
						}
						
					});
				});
				
			}
			if(window.ymaps)  YmapsCode();
			else{   
				var script = document.createElement('script'); 
				document.head.appendChild(script);  
				script.type = 'text/javascript';
				script.src = "https://api-maps.yandex.ru/2.1/?lang=ru_RU";
				script.onload = YmapsCode;
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
			
		</script>
<head>
	
</head>
<body>
    <div id="map" style="width:400px; height:300px"></div>
	<input name="point_1"  value="" type="text">
	<input name="point_2"  value="" type="text">
	<div id='length'></div>
</body>