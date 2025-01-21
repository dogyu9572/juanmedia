<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));
$arrLevel = getArticleList($_conf_tbl["member_level"], $scale, $_REQUEST['offset'], "order by level_no desc ");

$arrCUList = getCouponMemberList($_REQUEST["user_id"], $scale, $_REQUEST['offset']);
$arrCouponList = getCouponListAdmin(0, 0, "Y");

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

$arrBrandList = getCategoryList(3,'Y');			//회원등급

//DB해제
SetDisConn($dblink);


?>
<script src='https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js'></script>
<script type="text/javascript">
<!--
function execDaumPostcode(pr_zip, pr_Add1, pr_Add2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var addr = ''; // 주소 변수
			var extraAddr = ''; // 참고항목 변수

			//사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				addr = data.roadAddress;
			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				addr = data.jibunAddress;
			}
			// 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
			if(data.userSelectedType === 'R'){
				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
					extraAddr += data.bname;
				}
				// 건물명이 있고, 공동주택일 경우 추가한다.
				if(data.buildingName !== '' && data.apartment === 'Y'){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if(extraAddr !== ''){
					extraAddr = ' (' + extraAddr + ')';
				}
				// 조합된 참고항목을 해당 필드에 넣는다.
//				document.getElementById(pr_Add1).value = extraAddr;
			
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(pr_zip).value = data.zonecode;
			document.getElementById(pr_Add1).value = addr + " " + extraAddr;
			// 커서를 상세주소 필드로 이동한다.
			document.getElementById(pr_Add2).focus();
		}
	}).open();
}	
//-->
</script>
<script language="javascript">
function checkForm(frm){
	if(frm.user_pw.value.length > 0){
		if (frm.user_pw.value==""){
			alert("비밀번호를 입력해 주세요.");
			frm.user_pw.focus();
			return false;
		}
		if (frm.user_pw2.value==""){
			alert("비밀번호 확인을 입력해 주세요.");
			frm.user_pw2.focus();
			return false;
		}
		if (frm.user_pw.value != frm.user_pw2.value){
			alert("비밀번호가 일치하지 않습니다.");
			frm.user_pw2.focus();
			return false;
		}
	}
	if (frm.user_name.value.length < 1){
		alert("이름을 입력해 주세요.");
		frm.user_name.focus();
		return false;
	}
	if (frm.mobile.value.length < 1){
		alert("휴대번호를 입력해 주세요.");
		frm.mobile.focus();
		return false;
	}
}

function inNumber(str){
	// 숫자만 입력
	str.value = str.value.replace(/[^0-9\-]/g,"");	
}
</script>
<div class="container">

	<div class="title">회원 수정</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="memberForm" method="post" action="member_evn.php" onsubmit="return checkForm(this)">
		<input type="hidden" name="evnMode" value="edit">
		<input type="hidden" name="user_id" value="<?=$arrInfo["list"][0]['user_id']?>">
		<input type="hidden" name="rt_url" value="<?=$_REQUEST['listURL']?>">

		<table>
			<tr>
				<th>처리상태</th>
				<td><div class="inputs">
					<select name="user_level" style="width:120px;">
					<option value="0">회원등급선택</option>
					<?for ($i=0;$i<$arrLevel['total'];$i++) {?>
					<option value="<?=$arrLevel['list'][$i]['level_no']?>"<?=$arrLevel['list'][$i]['level_no']==$arrInfo["list"][0]["user_level"]?" selected":""?>><?=$arrLevel['list'][$i]['level_name']?></option>
					<?}?>
					</select>
				</div></td>
			</tr>
			<tr>
				<th>거부 사유</th>
				<td><div class="inputs"><input type="text" class="w4" name="etc_7" value="<?=$arrInfo["list"][0]["etc_7"]?>" maxlength="20"></div></td>
			</tr>	
			<tr>
				<th>회원등급 설정</th>
				<td>
					<select name="a_class" id="a_class" style="width:180px;">
						<option value="">회원등급 선택</option>
						<?
						for($i=0;$i<$arrBrandList["total"];$i++){									
						?>
						<option value="<?=$arrBrandList["list"][$i]['cat_no']?>"<?=$arrInfo["list"][0]['a_class']==$arrBrandList["list"][$i]['cat_no']?" selected":""?>><?=$arrBrandList["list"][$i]['cat_name']?></option>
						<?}?>
					</select>
				</td>
			</tr>				
			<tr>
				<th>메모</th>
				<td><textarea name="etc_8" style="width:50%;height:100px;padding:10px;"><?=$arrInfo["list"][0]["etc_8"]?></textarea></td>
			</tr>	
			<tr>
				<th>알림톡 발송</th>
				<td>
					<label class="check"><input type="checkbox" name="talk" value="1" ><i></i><span>승인완료<?=$arrInfo["list"][0]["smsdt"]?" &nbsp;&nbsp;( ".$arrInfo["list"][0]["smsdt"]." 발송)":""?></span></label>
					<!--<label class="check"><input type="checkbox" name="talk" value="2" ><i></i><span>승인거절</span></label>-->
				</td>
			</tr>
		</table>
		<div class="tit">매장정보 <i>*</i></div>
		<table>
			<tr>
				<th>상호명</th>
				<td><div class="inputs"><input type="text" class="w4" name="etc_1" value="<?=$arrInfo["list"][0]["etc_1"]?>" maxlength="50"></div></td>
			</tr>	
			<tr>
				<th>사업자등록번호</th>
				<td><div class="inputs"><input type="text" class="w4" name="etc_2" value="<?=$arrInfo["list"][0]["etc_2"]?>" maxlength="50"></div></td>
			</tr>				
			<tr>
				<th>거래처등록코드</th>
				<td><div class="inputs"><input type="text" class="w4" name="etc_10" value="<?=$arrInfo["list"][0]["etc_10"]?>" maxlength="50"></div></td>
			</tr>			
			<tr>
				<th>사업자등록증</th>
				<td>
				<?if($arrInfo["total_files"]>0){?>
					<a href="/uploaded/member/<?=$arrInfo["files"][0]['re_name']?>" download="<?=$arrInfo["files"][0]['ori_name']?>"><?=$arrInfo["files"][0]['ori_name']?></a>
				<?}?>
				</td>
			</tr>
			<tr>
				<th>대표자명</th>
				<td><div class="inputs"><input type="text" class="w4" name="etc_3" value="<?=$arrInfo["list"][0]["etc_3"]?>" maxlength="50"></div></td>
			</tr>	
			<tr style="display:none;">
				<th>계산서 수신메일</th>
				<td><div class="inputs"><input type="text" class="w4" name="etc_4" value="<?=$arrInfo["list"][0]["etc_4"]?>" maxlength="50"></div></td>
			</tr>	
			<tr>
				<th>계산서 수신메일</th>
				<td><div class="inputs"><input type="text" class="w4" name="email" maxlength="100" value="<?=$arrInfo["list"][0]["email"]?>"></div></td>
			</tr>	
			<tr>
				<th>매장번호</th>
				<td><div class="inputs"><input type="text" class="w4" name="etc_5" value="<?=$arrInfo["list"][0]["etc_5"]?>" maxlength="50"></div></td>
			</tr>	
			<tr>
				<th>배송지</th>
				<td>
				<div class="inputs"><input type="text" class="w1" id="zip" name="zip" maxlength="5" value="<?=$arrInfo["list"][0]["zip"]?>" readonly onclick="execDaumPostcode('zip','address','address_ext')"></div>
				<div class="inputs" style="margin-top:4px;"><input type="text" class="w4" name="address" id="address" value="<?=$arrInfo["list"][0]["address"]?>" maxlength="100" readonly onclick="execDaumPostcode('zip','address','address_ext')"></div>
				<div class="inputs" style="margin-top:4px;"><input type="text" class="w4" name="address_ext" id="address_ext" value="<?=$arrInfo["list"][0]["address_ext"]?>" maxlength="100"></div></td>
			</tr>	
			<tr>
				<th>매장오픈시간</th>
				<td>
					<select name="etc_6" id="etc_6" style="width:180px;">
						<option value="">매장오픈시간 선택</option>
						<?
						for($i=5;$i<16;$i++){
							if($arrInfo["list"][0]["etc_6"]==sprintf('%02d', $i).":00"){
								$select_6[$i] = "selected";
							}
						?>
							<option value="<?=sprintf('%02d', $i)?>:00" <?=$select_6[$i]?>><?=sprintf('%02d', $i)?>:00</option>
						<?}?>
					</select>
				
				
				</td>
			</tr>	
			<?
			$arrHoliday = explode(",",$arrInfo["list"][0]["holiday"]);
			?>
			<tr>
				<th>매장휴무일</th>
				<td><div class="inputs">
					<label class="check"><input type="checkbox" name="holiday[]" value="7" <?=in_array("7", $arrHoliday)?"checked":""?>><i></i><span>일요일</span></label>
					<label class="check"><input type="checkbox" name="holiday[]" value="1" <?=in_array("1", $arrHoliday)?"checked":""?>><i></i><span>월요일</span></label>
					<label class="check"><input type="checkbox" name="holiday[]" value="2" <?=in_array("2", $arrHoliday)?"checked":""?>><i></i><span>화요일</span></label>
					<label class="check"><input type="checkbox" name="holiday[]" value="3" <?=in_array("3", $arrHoliday)?"checked":""?>><i></i><span>수요일</span></label>
					<label class="check"><input type="checkbox" name="holiday[]" value="4" <?=in_array("4", $arrHoliday)?"checked":""?>><i></i><span>목요일</span></label>
					<label class="check"><input type="checkbox" name="holiday[]" value="5" <?=in_array("5", $arrHoliday)?"checked":""?>><i></i><span>금요일</span></label>
					<label class="check"><input type="checkbox" name="holiday[]" value="6" <?=in_array("6", $arrHoliday)?"checked":""?>><i></i><span>토요일</span></label>
				</div></td>
			</tr>	


			<tr>
				<th>ID(아이디)</th>
				<td><div class="inputs"><em><?=$arrInfo["list"][0]["user_id"]?></em></div></td>
			</tr>	
			<tr>
				<th>이름</th>
				<td><div class="inputs"><input type="text" class="w3" name="user_name" maxlength="50" value="<?=$arrInfo["list"][0]["user_name"]?>">
				</div></td>
			</tr>	
			<tr>
				<th>비밀번호</th>
				<td><div class="inputs"><input type="password" class="w4" name="user_pw" maxlength="50" value=""></div></td>
			</tr>	
			<tr>
				<th>비밀번호확인</th>
				<td><div class="inputs"><input type="password" class="w4" name="user_pw2" maxlength="50" value=""></div></td>
			</tr>			
			<tr>
				<th>담당자 연락처</th>
				<td><div class="inputs"><input type="text" class="w4" name="mobile" value="<?=$arrInfo["list"][0]["mobile"]?>" maxlength="20"></div></td>
			</tr>			
			
			<tr>
				<th>가입일</th>
				<td>
					<?=$arrInfo["list"][0]["wdate"]?>
				</td>
			</tr>
			
		</table>

		<div class="btns">
			<a href="javascript:void(0);" onclick="history.back();" class="btn btn_list">목록보기</a>
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