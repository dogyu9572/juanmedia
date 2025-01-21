<?if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){?>
<div id="layerMemo" style="position:relative; background:#7baaed; left:expression(document.body.clientWidth/2+410); top:111; width:200; height:200; visibility: none; z-index:1; display:none"></div> 
<script language="javascript">
function new_memo_check(){
	new Ajax.Request('/module/memo/ajax_memo.php',
	{
		method:'get',
		parameters: {id : '<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>'},
		encoding: 'euc-kr',
		//contentType: 'application/x-www-form-urlencoded',

		onSuccess: function(transport){
		var response = transport.responseText;

		//알리지 않은 쪽지가 있을때에만 쪽지레이더 띄움
		if(response != '0'){
			LayerShowMemo(response);
		}
		},

		onFailure: function(){
			//alert('Something went wrong...');
		}
	});
}

function LayerHideMemo() {
	//$('layerMemo').fade();
	Effect.Shrink('layerMemo');
	$('layerMemo').innerHTML="";
}

function LayerShowMemo(res){
	$('layerMemo').innerHTML=res;
	Effect.Grow('layerMemo');
}

//10초에 한번씩체크
setInterval(new_memo_check,10000);
</script>
<?}?>
