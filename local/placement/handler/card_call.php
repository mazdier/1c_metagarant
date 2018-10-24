<div id='root_card'><a href='' target="_blank">ссылка на контакт</a></div>
<script>
document.addEventListener('DOMContentLoaded', function(){
	setTimeout(function() {
		if(window.parent.document.getElementsByClassName("crm-card-show-detail-header-user-info").length!=0){
			var lincontact = window.parent.document.getElementsByClassName("crm-card-show-detail-header-user-info")["0"].children['0'].getAttribute('href');
			document.getElementById('root_card').children[0].href=lincontact;
		}
		else if(window.parent.document.getElementsByClassName("crm-card-show-user-name-item").length!=0){
			var lincontact = window.parent.document.getElementsByClassName("crm-card-show-user-name-item")["0"].children['0'].getAttribute('href');
			document.getElementById('root_card').children[0].href=lincontact;
		}

}, 100);
});
</script>