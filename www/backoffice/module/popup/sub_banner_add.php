<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

if(!in_array("popup_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleList("tbl_sub_banner", 0,0,"");
//_DEBUG($arrInfo);

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function checkform(f){
	if(f.b_subject.value==""){
		alert("제목을 입력하세요.");
		f.subject.focus();
		return false;
	}else{
		f.submit();
	}
}

</script>
<div class="container">

	<div class="title">띠배너 등록/수정</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmPopup" method="post" action="popup_evn.php" ENCTYPE="multipart/form-data">
		<input type="hidden" name="evnMode" value="subBanner">
		<input type="hidden" name="b_target" value="_self">
		<input type="hidden" name="b_sort" value="0">
		<input type="hidden" name="b_type" value="0">
		

		<div class="tit">띠배너 정보 <i>*</i></div>
		<table>
			<tr>
				<th>띠배너 제목</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_subject" value="<?=$arrInfo["list"][0]["b_subject"]?>" maxlength="100"></div></td>
			</tr>
			<tr>
				<th>클릭시 이동주소</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_url" value="<?=$arrInfo["list"][0]["b_url"]?>" maxlength="250"></div> # url 또는 외부 주소를 입력해주세요.</td>
			</tr>

		</table>		

		<div class="btns">
			<button class="btn btn_save" type="button" onclick="checkform(document.frmPopup);">저장</button>
		</div>
		</form>
	</div> <!-- //inbox -->
</div>
<script type="text/javascript">
//<![CDATA[
$(window).load(function(){
//달력
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.gif", 
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});
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