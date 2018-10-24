 <?

$host = $_SERVER['HTTP_HOST'];
$handlerDir = '/local/placement/handler';
//---------------Массив подключений placement----------------- 
$placementHandlers = array(
	array(
		'PLACEMENT' => CRM_CONTACT_DETAIL_ACTIVITY,//Места встроки для placement
		'TITLE' => 'Подсчет звонков',//&
		'DESCRIPTION' => 'Подсчет звонков',//& TITLE и DESCRIPTION менять после отключения плэсмент иначе появиться несколько плэйсментов.
		'HANDLER' => 'https://'.$host.$handlerDir.'/podschet_zvonkov.php'//Путь до файла который открываеться в плэйсмент.
	),
	array(
		'PLACEMENT' => CALL_CARD,
		'TITLE' => 'Дополнительно',
		'DESCRIPTION' => 'Дополнительно',
		'HANDLER' => 'https://'.$host.$handlerDir.'/card_call.php'
	),

);
//------------------------------------------------------------------
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<script type="text/javascript" src="https://api.bitrix24.com/api/v1/"></script>
</head>
<body>
<div class="main-wrap">
	<h1>Установка.Удаление Плейсметов</h1>
	<ul>
<?
	echo '<pre>'; print_r($_REQUEST); echo '</pre>';
foreach($placementHandlers as $handler)
{
?>
	<li><a href="javascript:void(0)" onclick="BX24.callMethod('placement.bind', {
				PLACEMENT:'<?=$handler['PLACEMENT']?>',
                HANDLER:'<?=$handler['HANDLER']?>',
				TITLE:'<?=$handler['TITLE']?>',
               DESCRIPTION:'<?=$handler['DESCRIPTION']?>'},function(res){console.log(res)})">Установить обработчик "<?=$handler['TITLE']?>" в <?=$handler['PLACEMENT']?></a></li>
			   <li><a href="javascript:void(0)" onclick="BX24.callMethod('placement.unbind',{
						   PLACEMENT:'<?=$handler['PLACEMENT']?>',
						   HANDLER:'<?=$handler['HANDLER']?>'
			   },function(res){console.log(res)})">Отключить обработчик "<?=$handler['TITLE']?>" в <?=$handler['PLACEMENT']?></a></li>
<?
}
?>
	</ul>
	</div>
	<a href="javascript:void(0)" onclick="BX24.placement.call('getStatus', {}, function (result) {
    console.log(result);
});
">GetStatus</a><br/>
<a href="javascript:void(0)" onclick="BX24.callMethod('telephony.externalcall.register',{
		USER_PHONE_INNER:'800',
		USER_ID:'1',
		PHONE_NUMBER:'375297568919',
		TYPE:1
			},function(res){console.log(res)})">Карточка звонка сергей</a><br/>
<a href="javascript:void(0)" onclick="BX24.callMethod('placement.list', {}, function(res){
console.log(res.answer.result);
})">Места встроки</a><br/>
<a href="javascript:void(0)" onclick="BX24.callMethod('placement.get', {}, function (result) {
    console.log(result.answer.result);
    console.log('42');
})
">CALLBACK</a>

</body>
</html>
