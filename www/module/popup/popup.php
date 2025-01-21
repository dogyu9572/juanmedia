<?
include ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["popup"], $_REQUEST['idx']);

//DB해제
SetDisConn($dblink);
?>
<html>
<head>
	<title><?=stripslashes($arrInfo["list"][0]['subject'])?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script language=javascript> 
		//창크기 조정
		//resizeTo('<?=$arrInfo["list"][0]['width']?>','<?=$arrInfo["list"][0]['height']?>');

		//쿠키설정
		function setCookie( name, value, expiredays ) {
			var todayDate = new Date();
			todayDate.setDate( todayDate.getDate() + expiredays );
			document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
        }

		//쿠키를 설정하여 창이 하루동안 안열리게 한다.
		function popupClose(){
			if ( document.forms[0].no_popup.checked ){
				setCookie( "POPUP<?=$arrInfo["list"][0]['idx']?>", "done" , 1);
			}
			self.close();
		}


		//이미지 클릭시 이동
		function go(){
			<?if($arrInfo["list"][0]['p_target']=="O"):?>
			opener.location.href='<?=$arrInfo["list"][0]['p_url']?>';
			<?else:?>
			obj = window.open('<?=$arrInfo["list"][0]['p_url']?>','','');
			<?endif;?>
			self.close();
		}
	</script>
</head>
 
<body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<form>
<tr>
	<td colspan="2">
	<? if($arrInfo["list"][0]['p_type']=="IMG")://이미지타입일경우?>
		<? if($arrInfo["list"][0]['p_url']){?>
			<a href="javascript:go();"><img src="/uploaded/popup/<?=stripslashes($arrInfo["list"][0]['p_image'])?>" border="0" style="width:<?=$arrInfo["list"][0]['width']-18?>px;"></a>
		<? }else{?>
			<img src="/uploaded/popup/<?=stripslashes($arrInfo["list"][0]['p_image'])?>" border="0">
		<? }?>
	<? else:?>
	<?=stripslashes($arrInfo["list"][0]['contents'])?>
	<?endif;?>
	</td>
</tr>
<tr bgcolor="#000000">
	<td style="color:#FFCC66;font-size:9pt"><input id="popup_check" type="checkbox" name="no_popup" OnClick="javascript:popupClose();"><label for="popup_check">오늘하루 창열지 않기</label></td>
	<td align="right"><a href="javascript:self.close()" style="color:#FFCC66;font-size:9pt">[창닫기]</a></td>
</tr>
</form>
</table>
</body>
</html>