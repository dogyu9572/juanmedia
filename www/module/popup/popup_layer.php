<script language=javascript> 
 function popupClose<?=$arrPopupList["list"][$i]['idx']?>(){
	if ( document.frm<?=$arrPopupList["list"][$i]['idx']?>.no_popup.checked ){
		setCookie("POPUP<?=$arrPopupList["list"][$i]['idx']?>", "done", 1);
	}
	popup<?=$arrPopupList["list"][$i]['idx']?>.style.display = 'none';
  }

//이미지 클릭시 이동
function go<?=$arrPopupList["list"][$i]['idx']?>(){
	<?if($arrPopupList["list"][$i]['p_target']=="O"):?>
	document.location.href='<?=$arrPopupList["list"][$i]['p_url']?>';
	<?else:?>
	obj = window.open('<?=$arrPopupList["list"][$i]['p_url']?>','','');
	<?endif;?>
	//self.close();
}
</script>

<div class="popupLayerCs" id="popup<?=$arrPopupList["list"][$i]['idx']?>" style="z-index:100; position:absolute; left: <?=$arrPopupList["list"][$i]['pop_left']?>px; top: <?=$arrPopupList["list"][$i]['pop_top']?>px; background-color:#fff; border:1px solid black;">
<table class="popupTableCs" border="0"  cellspacing="0" cellpadding="0" style="width:<?=$arrPopupList["list"][$i]['width']?>px;">
<form name="frm<?=$arrPopupList["list"][$i]['idx']?>">
  <tr>
    <td valign="top">
    
      <table border="0" width="<?=$arrPopupList["list"][$i]['width']?>px" height="<?=$arrPopupList["list"][$i]['height']?>" cellpadding="0" cellspacing="0">
	   <tr>
	    <td>
		  <? if($arrPopupList["list"][$i]['p_type']=="IMG")://이미지타입일경우?>
			<? if($arrPopupList["list"][$i]['p_url']){?>
				<a href="javascript:go<?=$arrPopupList["list"][$i]['idx']?>();"><img src="/uploaded/popup/<?=stripslashes($arrPopupList["list"][$i]['p_image'])?>" border="0" style="width:<?=$arrPopupList["list"][$i]['width']?>px;"></a>
			<? }else{?>
				<img src="/uploaded/popup/<?=stripslashes($arrPopupList["list"][$i]['p_image'])?>" style="width:<?=$arrPopupList["list"][$i]['width']?>px;" border="0">
			<? }?>
		<? else:?>
			<?=stripslashes($arrPopupList["list"][$i]['contents'])?>
		<?endif;?>
		  </td>
	    </tr>
      </table>

      <table width="100%" height="25" bgcolor="#1c4289" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;<font color=#ffffff style="FONT-SIZE: 13PX;">오늘 그만 보기</font> <input type="checkbox" name="no_popup" onClick="popupClose<?=$arrPopupList["list"][$i]['idx']?>();">&nbsp; </td>
		  <td align="right"><a href="javascript:popupClose<?=$arrPopupList["list"][$i]['idx']?>()" style="color:#eee;font-size:9pt">[닫기]</a>&nbsp;</td>
        </tr>
      </table>

      </td>
  </tr>
</form>
</table>
</div>
<!--	<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>	-->
<script language="javascript">
<!--
//$("#popup<?=$arrPopupList["list"][$i]['idx']?>").draggable();	// 오류로 주석처리
if ( getCookie( "POPUP<?=$arrPopupList["list"][$i]['idx']?>" ) == "done" ){
  popup<?=$arrPopupList["list"][$i]['idx']?>.style.display = 'none';
}
-->
if($(window).innerWidth()<750){
	$(".popupLayerCs").prop("style","z-index:1000; position:absolute; left:60px; top: 340px; background-color:#fff; border:1px solid black;");
	$(".popupTableCs").prop("style","width:250px;");
	//$(".popupLayerCs").prop("style","z-index:1000; position:absolute; left:0; top: 0; background-color:#fff; border:1px solid black;");
	//$(".popupTableCs").prop("style","width:"+$(window).innerWidth()-10+"px;");
}
</script>
