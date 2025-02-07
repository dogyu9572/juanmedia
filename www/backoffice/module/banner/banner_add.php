<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/banner/banner.lib.php";
if(!in_array("banner_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

?>
<script language="javascript">
function CheckForm(frm){
	if (frm.b_subject.value==""){
		alert("배너명을 입력해 주세요.");
		frm.b_subject.focus();
		return false;
	}
	if(frm.b_showtype.value == 'f'){
		if (frm.image_file.value==""){
			alert("파일을 선택해 주세요.");
			frm.image_file.focus();
			return false;
		}
	}
}
</script>
<div class="container">

	<div class="title">배너 등록</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmInfo" method="post" action="banner_evn.php" ENCTYPE="multipart/form-data" onSubmit="return CheckForm(this)">
		<input type="hidden" name="evnMode" value="insert">

		<div class="tit">배너정보 <i>*</i></div>
		<table>
		<tr>
				<th>배너 타입</th>
				<td><div class="inputs">
					<select name="b_device">
						<option value="1">1. PC 슬라이드 배너 (1920px * 420px)</option>
						<option value="2">2. 모바일 슬라이드 배너 (750px * 1332px)</option>
                        <option value="3">3. 공통</option>
					</select>
				</div></td>
			</tr>
            <tr>
                <th>구분</th>
                <td>
                    <div class="inputs">
                        <label class="radio"><input type="radio" name="b_type" value="1"<?=$arrInfo["list"][0]['b_type']=="1"?" checked":""?>><i></i>메인 상단 배너</label>
                        <label class="radio"><input type="radio" name="b_type" value="2"<?=$arrInfo["list"][0]['b_type']=="2"?" checked":""?>><i></i>메인 하단 배너</label>
                    </div>
                </td>
            </tr>
			<tr>
				<th>제목</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_subject" maxlength="100"></div></td>
			</tr>
			<tr>
				<th>링크</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_url" maxlength="250" value="https://"></div></td>
			</tr>
			<tr>
				<th>링크설정</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="b_target" value="_blank" checked><i></i>_blank (새창)</label>
					<label class="radio"><input type="radio" name="b_target" value="_self"	><i></i>_self (현재페이지)</label> 
					<label class="radio"><input type="radio" name="b_target" value="_top"	><i></i>_top</label> 
				</div></td>
			</tr>
			<tr>
				<th>보이기</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="b_show" value="Y" checked><i></i>표시</label>
					<label class="radio"><input type="radio" name="b_show" value="N"	><i></i>숨김</label> 
				</div></td>
			</tr>
			<tr style="display:none;">
				<th>텍스트1</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_text1" maxlength="250" value=""></div></td>
			</tr>
			<tr style="display:none;">
				<th>텍스트2</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_text2" maxlength="250" value=""></div></td>
			</tr>
			<tr>
				<th>정렬순서</th>
				<td><div class="inputs"><input type="text" class="w1" name="b_sort" maxlength="10" value="0"><em>&nbsp;(숫자가 높을수록 위쪽에 나타남)</em></div>
				</td>
			</tr>
			<tr style="display:none;">
				<th>사용타입</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="b_showtype" value="f" checked><i></i>파일사용</label>
					<label class="radio"><input type="radio" name="b_showtype" value="y" ><i></i>유튜브사용</label> 
				</div></td>
			</tr>
			<tr>
				<th>썸네일</th>
				<td>
					<div class="inputs">
						<div class="filebutton">
							<span>파일 선택</span>
							<input type="file" name="image_file" class="searchfile" title="파일 찾기" accept="image/*,video/*">
						</div>
						<div class="filebox">선택된 파일 없음</div>
					</div>
				</td>
			</tr>
			<tr style="display:none;">
				<th>유튜브링크</th>
				<td><div class="inputs"><input type="text" class="w4" name="b_youbube" maxlength="250" value="https://"></div></td>
			</tr>
		</table>		

		<div class="btns">
			<a href="banner.php" class="btn btn_list">목록보기</a>
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