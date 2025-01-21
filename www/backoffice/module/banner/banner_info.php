<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/banner/banner.lib.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["banner"], $_GET['idx']);

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function CheckForm(frm){
	if (frm.b_subject.value==""){
		alert("이미지명을 입력해 주세요.");
		frm.b_subject.focus();
		return false;
	}
}
function fnGateTxt(sval){
	if(sval>18 && sval<27){
		$("#gateAtext").show();
	}else{
		$("#gateAtext").hide();
	}
}
</script>
<?######################################### color picker######################################### ST?>
<script src="/_api/_minicolors/js/jquery.minicolors.js"></script>
<link rel="stylesheet" href="/_api/_minicolors/js/jquery.minicolors.css">
<script>
$(document).ready( function() {
	$('.picker').each( function() {
		$(this).minicolors({
			control: $(this).attr('data-control') || 'hue',
			defaultValue: $(this).attr('data-defaultValue') || '',
			format: $(this).attr('data-format') || 'hex',
			keywords: $(this).attr('data-keywords') || '',
			inline: $(this).attr('data-inline') === 'true',
			letterCase: $(this).attr('data-letterCase') || 'lowercase',
			opacity: $(this).attr('data-opacity'),
			position: $(this).attr('data-position') || 'bottom',
			swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
			change: function(value, opacity) {
				if( !value ) return;
				if( opacity ) value += ', ' + opacity;
				if( typeof console === 'object' ) {
					console.log(value);
				}
			},
			theme: 'bootstrap'
		});
	});
});
</script>
<?######################################### color picker######################################### ED?>
<div class="container">

	<div class="title">이미지 수정</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmInfo" method="post" action="banner_evn.php" ENCTYPE="multipart/form-data" onSubmit="return CheckForm(this)">
		<input type="hidden" name="evnMode" value="update">
		<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]['idx']?>">

		<div class="tit">이미지정보 <i>*</i></div>
		<table>
			<tr>
				<th>구분</th>
				<td><div class="inputs">
					<select name="b_type" style="width:250px;">
						<option value="1"<?=$arrInfo["list"][0]['b_type']=="1"?" selected":""?>>KCA메인비주얼</option>
						<option value="2"<?=$arrInfo["list"][0]['b_type']=="2"?" selected":""?>>KCA메인배너</option>
						<option value="3"<?=$arrInfo["list"][0]['b_type']=="3"?" selected":""?>>KCIA</option>
					</select>
				</div></td>
			</tr>
			<tr>
				<th>배너 타입</th>
				<td><div class="inputs">
					<select name="b_device" style="width:250px;">
						<option value="1"<?=$arrInfo["list"][0]['b_device']=="1"?" selected":""?>>PC</option>
						<option value="2"<?=$arrInfo["list"][0]['b_device']=="2"?" selected":""?>>MO</option>
					</select>
				</div></td>
			</tr>
			<tr>
				<th>제목</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_subject" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['b_subject'])?>">
				<!-- &nbsp;<input type="text" class="w2 picker" name="b_color_1" maxlength="10" value="<?=$arrInfo["list"][0]['b_color_1']?>"> -->
				</div></td>
			</tr>			
			<tr style="display:none;">
				<th>텍스트1</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_contents" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['b_contents'])?>">
				&nbsp;<input type="text" class="w2 picker" name="b_color_2" maxlength="10" value="<?=$arrInfo["list"][0]['b_color_2']?>">
				</div></td>
			</tr>
			<tr style="display:none;">
				<th>텍스트2</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_etc_1" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['b_etc_1'])?>">
				&nbsp;<input type="text" class="w2 picker" name="b_color_3" maxlength="10" value="<?=$arrInfo["list"][0]['b_color_3']?>">
				</div></td>
			</tr>
			<tr style="display:none;">
				<th>텍스트3</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_etc_2" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['b_etc_2'])?>">
				&nbsp;<input type="text" class="w2 picker" name="b_color_4" maxlength="10" value="<?=$arrInfo["list"][0]['b_color_4']?>">
				</div></td>
			</tr>	
			<tr style="display:none;">
				<th>텍스트 배치</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="b_brand" value="1" <?=$arrInfo["list"][0]['b_brand']=="1"?"checked":""?> <?=$arrInfo["list"][0]['b_brand']?"":"checked"?>><i></i>상</label>
					<label class="radio"><input type="radio" name="b_brand" value="2" <?=$arrInfo["list"][0]['b_brand']=="2"?"checked":""?>><i></i>중</label> 
					<label class="radio"><input type="radio" name="b_brand" value="3" <?=$arrInfo["list"][0]['b_brand']=="3"?"checked":""?>><i></i>하</label> 
					<label class="radio"><input type="radio" name="b_brand" value="4" <?=$arrInfo["list"][0]['b_brand']=="4"?"checked":""?>><i></i>사용안함</label> 
					<em></em>
				</div></td>
			</tr>			
			<tr>
				<th>등록된이미지</th>
				<td><img src="/uploaded/banner/<?=$arrInfo['list'][0]['b_image']?>" style="max-height:300px;max-width:300px;"></td>
			</tr>
			<tr>
				<th>이미지</th>
				<td>
					<div class="inputs">
						<div class="filebutton">
							<span>파일 선택</span>
							<input type="file" name="image_file" class="searchfile" title="파일 찾기">
						</div>
						<div class="filebox">선택된 파일 없음</div>
					</div>
				</td>
			</tr>
			<tr>
				<th>링크</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_url" maxlength="250" value="<?=stripslashes($arrInfo["list"][0]['b_url'])?>"></div></td>
			</tr>
			<tr>
				<th>링크설정</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="b_target" value="_blank" <?=$arrInfo["list"][0]['b_target']=="_blank"?" checked":""?>><i></i>_blank (새창)</label>
					<label class="radio"><input type="radio" name="b_target" value="_self"	<?=$arrInfo["list"][0]['b_target']=="_self"?" checked":""?>><i></i>_self (현재페이지)</label> 
					<label class="radio"><input type="radio" name="b_target" value="_top"	<?=$arrInfo["list"][0]['b_target']=="_top"?" checked":""?>><i></i>_top</label> 
				</div></td>
			</tr>
			<tr>
				<th>정렬순서</th>
				<td><div class="inputs"><input type="text" class="w1" name="b_sort" maxlength="10" value="<?=$arrInfo["list"][0]['b_sort']?>"><em>&nbsp;(숫자가 높을수록 위쪽에 나타남)</em></div>
				</td>
			</tr>
			<tr>
				<th>표시 여부</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="b_show" value="Y" <?=$arrInfo["list"][0]['b_show']=="Y"?" checked":""?>><i></i>표시</label>
					<label class="radio"><input type="radio" name="b_show" value="N" <?=$arrInfo["list"][0]['b_show']=="N"?" checked":""?>><i></i>숨김</label> 
				</div></td>
			</tr>

		</table>		

		<div class="btns">
			<a href="javascript:void(0);" onclick="history.back()" class="btn btn_list">목록보기</a>
			<button class="btn btn_save" type="submit">저장</button>
		</div>
		</form>
	</div> <!-- //inbox -->
</div>
<script type="text/javascript">
//<![CDATA[
$(window).load(function(){
//파일선택
	$(".searchfile").on('change',function(){
		val = $(this).val().split("\\");
		f_name = val[val.length-1]; 
		s_name = f_name.substring(f_name.length-4, f_name.length);
		$(this).parent().siblings('.filebox').html(f_name);
	});
});
//]]>
</script>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>