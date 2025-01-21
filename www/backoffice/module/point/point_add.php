<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "../coupon/menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

if(!in_array("homepage_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));

$arrPoint = getNowPoint(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));
//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function frmCheck(f){
	if(f.point.value.length < 1){
		alert("적립금을 입력하세요.");
		f.point.focus();
		return false;
	}
	if(f.contents.value.length < 1){
		alert("내용을 입력하세요.");
		f.contents.focus();
		return false;
	}
	f.submit();
}
</script>
<div class="container">

	<div class="title">적립금 등록</div>
	
	<div class="inbox write_tbl mo_break_write">
		<div class="tit">기본정보 <i>*</i></div>
		<table>
			<tr>
				<th>아이디</th>
				<td><div class="inputs">
					<form name="frmSort" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
						<input type="text" name="user_id" value="<?=$_REQUEST['user_id']?>" class="input" />
						<input type="image" src="/backoffice/images/btn_search.gif" alt="검색" align="absmiddle" />
					</form>	
				</div></td>
			</tr>

			<?if($arrInfo["total"] > 0){?>
			<!-- S 개인정보입력 -->
			<form name="frmPoint" method="post" action="point_evn.php">
			<input type="hidden" name="evnMode" value="add">
			<input type="hidden" name="user_id" value="<?=$arrInfo["list"][0]["user_id"]?>">
			<input type="hidden" name="user_name" value="<?=$arrInfo["list"][0]["user_name"]?>">
			<tr>
				<th>아이디</th>
				<td><?=$arrInfo["list"][0]["user_id"]?></td>
			</tr>
			<tr>
				<th>이 름</th>
				<td><?=$arrInfo["list"][0]['user_name']?></td>
			</tr>
			<tr>
				<th>로그인 횟수</th>
				<td><?=number_format($arrInfo["list"][0]['login_count'])?></td>
			</tr>
			<tr>
				<th>최근로그인</th>
				<td><?=$arrInfo["list"][0]['login_last']?></td>
			</tr>
			<tr>
				<th>업데이트일</th>
				<td><?=$arrInfo["list"][0]['udate']?></td>
			</tr>
			<tr>
				<th>회원가입일</th>
				<td><?=$arrInfo["list"][0]['wdate']?></td>
			</tr>
			<tr>
				<th>적립금 잔액</th>
				<td><strong><?=number_format($arrPoint["nowpoint"])?></strong></td>
			</tr>
			<tr>
				<th>입력구분</th>
				<td>
					<div class="inputs">
						<select name="type" style="width:110px;">
							<option value="plus">적립</option>
							<option value="minus">사용</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<th>적립금</th>
				<td><input type="text" name="point" class="w2" maxlength="100"/></td>
			</tr>
			<tr>
				<th>내용</th>
				<td><input type="text" name="contents" class="w4" maxlength="250" /></td>
			</tr>
			</form>
			<?}?>
		</table>		
		<?
		################################ 목록보기
		$queryString = explode("&",$_SERVER['QUERY_STRING']);
		$reQueryString = "";
		$comma = "";
		for($i=0;$i<count($queryString);$i++){
			if(strpos($queryString[$i],"user_id=")===false){
				$reQueryString .= $comma.$queryString[$i];
				$comma = "&";
			}
		}
		?>
		<div class="btns">
			<a href="/backoffice/module/point/point_list.php?<?=$reQueryString?>" class="btn btn_list">목록보기</a>
			<button class="btn btn_save" type="button" onclick="frmCheck(document.frmPoint)">저장</button>
		</div>		
	</div> <!-- //inbox -->
</div>

<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>
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