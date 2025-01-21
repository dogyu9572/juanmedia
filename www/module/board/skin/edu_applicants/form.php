<?
################################################### PHP 7 Set ST
if(!isset($_GET["category"])){	$_GET["category"]=""; }
if(!isset($_GET["sw"])){		$_GET['sw']="";	}
if(!isset($_GET["sk"])){		$_GET['sk']="";	}
if(!isset($_GET["offset"])){	$_GET['offset']="";	}
if(!isset($_GET["s_date"])){	$_GET['s_date']="";	}
if(!isset($_GET["e_date"])){	$_GET['e_date']="";	}
if(!isset($_GET["page_size"])){	$_GET['page_size']=""; }
if(!isset($arrBoardArticle["total_files"])){		$arrBoardArticle["total_files"]=0; }
if(!isset($arrBoardArticle["list"][0]['subject'])){ $arrBoardArticle["list"][0]['subject']=""; }
if(!isset($arrBoardArticle["list"][0]['etc_1'])){	$arrBoardArticle["list"][0]['etc_1']=""; }
if(!isset($arrBoardArticle["list"][0]['contents'])){	$arrBoardArticle["list"][0]['contents']=""; }
################################################### PHP 7 Set ED
include_once $_SERVER ['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
$referralArray = explode('|', $arrBoardArticle["list"][0]['referral']);

$email = $arrBoardArticle["list"][0]['email'];
$encodedEmail = base64_encode($email);

$arrList = getMemberList(
    mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['jb']),
    mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']),
    mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']),
    $scale,
    $_REQUEST['offset'],
    "AND (email = '$email' OR email = '$encodedEmail' OR user_id = '$email' OR user_id = '$encodedEmail')"
);

$arrLevel = getArticleList ( $_conf_tbl ["member_level"], 0, 0, "order by level_no desc " );
for($i = 0; $i < $arrLevel["total"]; $i ++) {
    $arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}

if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] && $_SERVER["PHP_SELF"]=="/backoffice/module/board/board_view.php"){
###################################################### 관리자 페이지 ######################################################?>
<?if($_GET['mode']=="write"){$inputText="등록";}else{$inputText="수정";}?>
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
function frmCheck(frm){
	/*
	if(frm.subject.value.length < 1){
		alert('제목을 입력해 주세요.');
		frm.subject.focus();
		return ;
	}
	*/
	
	try{ contents.outputBodyHTML(); } catch(e){ }

	frm.submit();

}
$(document).ready(function() {
	$.each($('input.calendar'), function() {
		set_datepicker($(this));
	});	
	// 숫자만 입력
	$(".numberOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9]/g,""));
	});
});
function set_datepicker($cont) {
	$cont.prop('readonly', true).datepicker({
		closeText: '닫기',
		prevText: '',
		nextText: '',
		currentText: '오늘',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)','7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		defaultDate: '+1w',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '년 ',
		changeMonth: true,
		changeYear: true,
		yearRange: '1921:c+5'
	});
}

//첨부파일 열 추가
var rowcount = 0;
function append() {   
	var tbl = document.getElementById("files_table").getElementsByTagName("TBODY")[0];  
	var html1 = "<input name='upfiles[]' type='file' style='width: 400px;'>";  
	var row = document.createElement("tr"); 
	var col1 = document.createElement("td");   
	row.appendChild(col1);  
	col1.innerHTML = html1;  
	tbl.appendChild(row);  
	rowcount++;
}
var filecount = 0;
function appendfile(){
	if(filecount<20){
		filecount++;
		$("#filetd"+filecount).css("display","");	
	}
}
function removefile(){
	$("#filetd"+filecount).css("display","none");	
	if(filecount>0){
		filecount--;
	}
}
function remove() {  
	if(rowcount > 0){
		var tbl = document.getElementById("files_table").getElementsByTagName("TBODY")[0];  
		if (tbl.hasChildNodes()) {      
			tbl.removeChild(tbl.lastChild);     // 마지막 로우   //tbl.removeChild(tbl.firstChild);  // 첫번째 로우  
		}
		rowcount--;
	}
}
//첨부파일 열 추가
</script>	
<script language="javascript">
function fileDownload(boardid,b_idx,idx){
	obj = window.open("/module/board/download.php?boardid="+boardid+"&b_idx="+b_idx+"&idx="+idx,"download","width=100,height=100,menubars=0, toolbars=0");
}
</script>
<div class="container">

	<div class="title"><?=$arrBoardInfo["list"][0]["boardname"]?> <?=$inputText?></div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
		<input type="hidden" name="boardid" value="<?=$arrBoardInfo["list"][0]["boardid"]?>">
		<input type="hidden" name="altYN" value="N">
		<input type="hidden" name="returnURL" value="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&category=<?=$_GET['category']?>&offset=<?=$_GET['offset']??""?>">
		<input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
        <input type="hidden" name="category1" value="<?=$arrBoardArticle["list"][0]["category1"]?>">
        <input type="hidden" name="category2" value="<?=$arrBoardArticle["list"][0]["category2"]?>">
        <input type="hidden" name="edu_no" value="<?=$arrBoardArticle["list"][0]["edu_no"]?>">
        <input type="hidden" name="totalamount" id="totalamount" value="<?=$arrBoardArticle["list"][0]["totalamount"]?>">
            <input type="hidden" name="discountamount" id="discountamount" value="<?= $arrBoardArticle["list"][0]["discountamount"] ?? 0 ?>">
        <input type="hidden" name="finalamount" id="finalamount" value="<?=$arrBoardArticle["list"][0]["finalamount"]?>">
        <input type="hidden" name="fee" value="<?=$arrBoardArticle["list"][0]["totalamount"]?>">
        <input type="hidden" name="user_level" value="<?=$arrBoardArticle["list"][0]["user_level"]?>">
        <input type="hidden" name="edu_idx" value="<?=$arrBoardArticle["list"][0]["edu_idx"]?>">
        <input type="hidden" name="email" value="<?=$arrBoardArticle["list"][0]["email"]?>">
		<?if($_REQUEST['mode']=="reply"):?>
		<input type="hidden" name="evnMode" value="reply">
		<?elseif($_REQUEST['mode']=="modify"):?>
		<input type="hidden" name="evnMode" value="modify">
		<?else:?>
		<input type="hidden" name="evnMode" value="write">
		<?endif;?>

		<div class="tit"><?=$arrBoardInfo["list"][0]["boardname"]?> 정보 <i>*</i></div>
		<table>
            <tr style="display:none;">
                <th>순서</th>
                <td><div class="inputs"><input type="text" class="w2" style="text-align:right;" name="b_sort" maxlength="100" value="<?=$arrBoardArticle["list"][0]['b_sort']?$arrBoardArticle["list"][0]['b_sort']:"0"?>"></div></td>
            </tr>
            <tr>
                <th>회원상태<span>*</span></th>
                <td>
                    <div class="inputs">
                        <?php

                        //$user_level = $arrList["list"][0]["user_level"];
                        $user_level = $arrBoardArticle["list"][0]['user_level'];
                        if (isset($arrayLevel[$user_level])) {
                            $level = $arrayLevel[$user_level];
                            echo "<div id='userLevelContainer'>$level</div>";
                        }
                        ?>
                        <div id="userLevelContainer"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>이메일(아이디)<span>*</span></th>
                <td>
                    <div class="flex">
                        <input type="hidden" name="instructor_idx" id="instructor_idx" value="<?=$arrBoardArticle["list"][0]['instructor_idx']?>">
                        <div class="inputs"><input type="text" class="w3" name="w_user" id="w_user" maxlength="100" value="<?=$arrBoardArticle["list"][0]['w_user']?>" style="background:#cccccc;" readonly></div>
                        <div class="btns" style="justify-content:flex-start; align-items :center; margin:0 !important; padding:0;"><button class="btn btn_save" type="button" onclick="OpenApplyView('joinidxs')" style="margin:0;">회원검색</button></div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>이름<span>*</span></th>
                <td><div class="inputs"><input type="text" name="name" class="w4" value="<?=stripslashes($arrBoardArticle["list"][0]['name'])?>"></div></td>
            </tr>
            <tr>
                <th>연락처<span>*</span></th>
                <td><div class="inputs"><input type="text" name="tel" class="w4" value="<?=stripslashes($arrBoardArticle["list"][0]['tel'])?>"></div></td>
            </tr>
            <tr>
                <th>생년월일<span>*</span></th>
                <td><div class="inputs"><input type="text" name="birthdate" class="w4 datepicker" value="<?=stripslashes($arrBoardArticle["list"][0]['birthdate'])?>"></div></td>
            </tr>
            <tr>
                <th>교육명<span>*</span></th>
                <td>
                    <div class="flex">
                        <input type="hidden" name="instructor_idx" id="instructor_idx" value="<?=$arrBoardArticle["list"][0]['instructor_idx']?>">
                        <div class="inputs"><input type="text" class="w3" name="subject" id="subject" maxlength="100" value="<?=$arrBoardArticle["list"][0]['subject']?>" style="background:#cccccc;" readonly></div>
                        <div class="btns" style="justify-content:flex-start; align-items :center; margin:0 !important; padding:0;"><button class="btn btn_save" type="button" onclick="OpenPersonView('edu')" style="margin:0;">교육검색</button></div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>신청상태<span>*</span></th>
                <td>
                    <div class="inputs">
                        <select name="status">
                            <option value="">선택</option>
                            <option value="신청완료" <?= $arrBoardArticle["list"][0]['status'] == '신청완료' ? 'selected' : '' ?>>신청완료</option>
                            <option value="대기자" <?= $arrBoardArticle["list"][0]['status'] == '대기자' ? 'selected' : '' ?>>대기자</option>
                            <option value="완료" <?= $arrBoardArticle["list"][0]['status'] == '완료' ? 'selected' : '' ?>>완료</option>
                            <option value="취소" <?= $arrBoardArticle["list"][0]['status'] == '취소' ? 'selected' : '' ?>>취소</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <th>수료여부<span>*</span></th>
                <td>
                    <div class="inputs">
                        <label class='radio'><input type="radio" name="usehtml" value="Y" <?= $arrBoardArticle["list"][0]['usehtml'] == 'Y' ? 'checked' : '' ?>><i></i> Y</label>
                        <label class='radio'><input type="radio" name="usehtml" value="N" <?= $arrBoardArticle["list"][0]['usehtml'] == 'N' ? 'checked' : '' ?>><i></i> N</label>
                    </div>
                </td>
            </tr>
            <tr>
                <th>비고</th>
                <td><div class="inputs"><textarea name="contents" cols="30" rows="10" class="text w40p" placeholder="내용을 입력해주세요."><?=stripslashes($arrBoardArticle["list"][0]['contents'])?></textarea></div></td>
            </tr>
            <tr>
                <th>유입경로</th>
                <td>
                    <div class="inputs">
                        <label class='check'><input type="checkbox" name="referral[]" value="홈페이지" <?= in_array('홈페이지', $referralArray) ? "checked" : "" ?>><i></i> 홈페이지</label>
                        <label class='check'><input type="checkbox" name="referral[]" value="블로그" <?= in_array('블로그', $referralArray) ? "checked" : "" ?>><i></i> 블로그</label>
                        <label class='check'><input type="checkbox" name="referral[]" value="페이스북" <?= in_array('페이스북', $referralArray) ? "checked" : "" ?>><i></i> 페이스북</label>
                        <label class='check'><input type="checkbox" name="referral[]" value="인스타그램" <?= in_array('인스타그램', $referralArray) ? "checked" : "" ?>><i></i> 인스타그램</label>
                        <label class='check'><input type="checkbox" name="referral[]" value="당근마켓" <?= in_array('당근마켓', $referralArray) ? "checked" : "" ?>><i></i> 당근마켓</label>
                        <label class='check'><input type="checkbox" name="referral[]" value="카카오톡 채널" <?= in_array('카카오톡 채널', $referralArray) ? "checked" : "" ?>><i></i> 카카오톡 채널</label>
                        <label class='check'><input type="checkbox" name="referral[]" value="문자" <?= in_array('문자', $referralArray) ? "checked" : "" ?>><i></i> 문자</label>
                        <label class='check'><input type="checkbox" name="referral[]" value="이메일" <?= in_array('이메일', $referralArray) ? "checked" : "" ?>><i></i> 이메일</label>
                        <label class='check'><input type="checkbox" name="referral[]" value="기타" <?= in_array('기타', $referralArray) ? "checked" : "" ?> onchange="toggleReferralOther(this)"><i></i> 기타</label>
                        <div class="baseInput">
                            <input type="text" name="referral_other" id="referral_other"  value="<?=$arrBoardArticle["list"][0]['referral_other']?>" <?= in_array('기타', $referralArray) ? "" : "disabled" ?>>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>할인적용</th>
                <td>
                    <div class="inputs">
                        <select id="discountSelect" name="discount" onchange="calculateDiscount()" class="w3">
                            <option value="">선택</option>
                            <?php
                            $discounts = [
                                '일반' => 'edu_discount1',
                                '경로우대' => 'edu_discount2',
                                '국가유공자' => 'edu_discount3',
                                '기초생활수급자' => 'edu_discount4',
                                '장애인' => 'edu_discount5',
                                '청소년' => 'edu_discount6',
                                '한부모가족' => 'edu_discount7',
                                '다문화가족' => 'edu_discount8',
                                '다자녀가정' => 'edu_discount9',
                                '새터민' => 'edu_discount10',
                                '사회복지시설수용자' => 'edu_discount11',
                                '정회원 지원' => 'edu_discount12',
                                '동아리 지원' => 'edu_discount13',
                                '교육 지원' => 'edu_discount14',
                                '구청 지원' => 'edu_discount15',
                                '공익 지원(공문)' => 'edu_discount16',
                                '미추홀구민 할인' => 'edu_discount17',
                            ];

                                foreach ($discounts as $label => $name) {
                                    if ($arrDiscountInfo['list'][0][$name] == 'Y') {
                                        $value = $arrDiscountInfo['list'][0][$name . '_value'];
                                        $selected = ($arrBoardArticle["list"][0]['discount_text'] == $label) ? 'selected' : '';
                                        echo "<option value='{$value}' data-label='{$label}' {$selected}>{$label}</option>";
                                    }
                                }
                            ?>
                        </select>
                        &nbsp;<div id="finalAmountDisplay" style="display: flex; align-items: center;">
                             결제금액 : <?= number_format($arrBoardArticle["list"][0]['finalamount']) ?>원
                        </div>
                        <input type="hidden" id="discountText" name="discount_text" value="<?=$arrBoardArticle["list"][0]['discount_text']?>">
                    </div>
                </td>
            </tr>
            <tr>
                <th>증명서제출</th>
                <td>
                    <div class="inputs">
                        <select name="certificate" class="w3" onchange="toggleFileInput(this)">
                            <option value="">선택</option>
                            <option value="온라인 제출" <?= $arrBoardArticle["list"][0]['certificate'] == '온라인 제출' ? 'selected' : '' ?>>온라인 제출</option>
                            <option value="현장 제출" <?= $arrBoardArticle["list"][0]['certificate'] == '현장 제출' ? 'selected' : '' ?>>현장 제출</option>
                        </select>
                        <div id="fileAddWrap" style="display:none;width: 100%;">
                            <div class="filebutton">
                                <span>파일 선택</span>
                                <input name="upfiles[]" type="file" class="searchfile" title="파일 찾기">
                            </div>
                            <div class="filebox">선택된 파일 없음</div>
                        </div>
                        <?
                        if($arrBoardArticle["total_files"]>0 && $_REQUEST['mode']=="modify"){
                            ?>
                            <table id="files_list" border="0" cellpadding="3" cellspacing="1" width="100%" style="padding:1%">
                                <tbody>
                                <?
                                for($i=0;$i<$arrBoardArticle["total_files"];$i++){
                                    if(substr($arrBoardArticle["files"][$i]['re_name'],0,2) != "l_" && substr($arrBoardArticle["files"][$i]['re_name'],0,2) != "v_") {
                                        ?>
                                        <tr>
                                            <td><label class="check"><input type="checkbox" name="filedel[]" value="<?=$arrBoardArticle["files"][$i]['idx']?>"><i></i>삭제</label>
                                                file :  <a href="javascript:void(0);" onclick="fileDownload('<?=$arrBoardArticle["files"][$i]['boardid']?>','<?=$arrBoardArticle["files"][$i]['b_idx']?>','<?=$arrBoardArticle["files"][$i]['idx']?>');"><?=$arrBoardArticle["files"][$i]['ori_name']?></a>
                                            </td>
                                        </tr>
                                        <?
                                    }
                                }?>
                                </tbody>
                            </table>
                        <?}?>

                    </div>
                </td>
            </tr>
		</table>

        <table>
            <tr>
                <th>관리자 메모</th>
                <td>
                    <div class="btns" style="height:30px;margin-top:0;margin-bottom:10px; justify-content: left">
                        <a href="javascript:void(0);" class="btn" onclick="fnChildAdd()">추가</a>
                    </div>
                    <div class="bdr_list tac" style="width:100%;board:1px">
                        <table>
                            <colgroup>
                                <col width="50%">
                                <col width="25%">
                                <col width="25%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th style="text-align:center;padding:20px 0;">내용</th>
                                <th style="text-align:center;padding:20px 0;">생성일</th>
                                <th style="text-align:center;padding:20px 0;">수정/삭제</th>
                            </tr>
                            </thead>
                            <tbody id="childlist">
					        <?
					        $arrChildAdmin = explode("||", $arrBoardArticle["list"][0]['child_admin']);
					        $arrChildWdate = explode("||", $arrBoardArticle["list"][0]['child_wdate']);
                            if ($arrBoardArticle["list"][0]['child_admin']){
                                for ($i = 0; $i < count($arrChildAdmin); $i++) {
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="text-content"><?=$arrChildAdmin[$i]?></span>
                                            <input type="text" class="w4 input-content" name="child_admin[]" value="<?=$arrChildAdmin[$i]?>" style="display:none;">
                                        </td>
                                        <td>
                                            <span class="text-content"><?=$arrChildWdate[$i]?></span>
                                            <input type="text" class="w3 datepicker input-content" name="child_wdate[]" value="<?=$arrChildWdate[$i]?>" style="display:none;">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="toggleEdit(this)" class="btn edit" style="display: inline-block;">수정</a>
                                            <a href="javascript:void(0);" onclick="fnChildDel(this, <?=($i+1)?>)" class="btn del" style="display: inline-block;">삭제</a>
                                        </td>
                                    </tr>
                                    <?
                                }
                            }
					        ?>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

		<div class="btns">
			<a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&category=<?=$_GET['category']?>" class="btn btn_list">목록보기</a>
			<a href="javascript:void(0)" onclick="location.reload()" class="btn btn_cancel">취소</a>
			<button class="btn btn_save" type="button" onclick="frmCheck(document.form1)">저장</button>
		</div>
		</form>
	</div> <!-- //inbox -->
</div>
    <script type="text/javascript">
        function fnChildAdd() {
            var htm = `
        <tr>
            <td>
                <input type="text" class="w4 input-content" name="child_admin[]" value="">
            </td>
            <td>
                <input type="text" class="w3 datepicker input-content" name="child_wdate[]" value="">
            </td>
            <td>
                <a href="javascript:void(0);" onclick="toggleAdd(this)" class="btn add" style="display: inline-block;">추가</a>
                <a href="javascript:void(0);" onclick="fnChildDel(this)" class="btn del" style="display: inline-block;">삭제</a>
            </td>
        </tr>`;
            $("#childlist").append(htm);

            // Initialize datepicker for the new input
            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd',
                showMonthAfterYear: true,
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
        }

        function toggleEdit(button) {
            var row = button.closest('tr');
            var textContents = row.querySelectorAll('.text-content');
            var inputContents = row.querySelectorAll('.input-content');

            if (button.textContent === '수정') {
                textContents.forEach(function(textContent) {
                    textContent.style.display = 'none';
                });

                inputContents.forEach(function(inputContent) {
                    inputContent.style.display = 'inline';
                });

                button.textContent = '저장';
            } else {
                inputContents.forEach(function(inputContent, index) {
                    textContents[index].textContent = inputContent.value;
                    textContents[index].style.display = 'inline';
                    inputContent.style.display = 'none';
                });

                button.textContent = '수정';
            }
        }

        function toggleAdd(button) {
            var row = button.closest('tr');
            var textContents = row.querySelectorAll('.text-content');
            var inputContents = row.querySelectorAll('.input-content');

            inputContents.forEach(function(inputContent) {
                var span = document.createElement('span');
                span.className = 'text-content';
                span.textContent = inputContent.value;
                inputContent.style.display = 'none';
                inputContent.parentNode.insertBefore(span, inputContent);
            });

            button.textContent = '수정';
            button.className = 'btn edit';
            button.setAttribute('onclick', 'toggleEdit(this)');
        }
        function fnChildDel(ths, tmp) {
            $(ths).parent('td').parent('tr').remove();
        }
        function toggleFileInput(selectElement) {
            var fileAddWrap = document.getElementById('fileAddWrap');
            if (selectElement.value === '온라인 제출') {
                fileAddWrap.style.display = 'block';
            } else {
                fileAddWrap.style.display = 'none';
            }
        }
        // Ensure the file input section is enabled if "온라인 제출" is already selected on page load
        document.addEventListener('DOMContentLoaded', function() {
            var selectElement = document.querySelector('select[name="certificate"]');
            toggleFileInput(selectElement);
        });

        function toggleReferralOther(checkbox) {
            var referralOther = document.getElementById('referral_other');
            referralOther.disabled = !checkbox.checked;
        }

        document.querySelector('select[name="certificate"]').addEventListener('change', function() {
            var fileAddWrap = document.getElementById('fileAddWrap');
            if (this.value === '온라인 제출') {
                fileAddWrap.style.display = 'block';
            } else {
                fileAddWrap.style.display = 'none';
            }
        });

        function calculateDiscount() {
            var discountSelect = document.getElementById('discountSelect');
            var discountValue = discountSelect.value;
            var discountLabel = discountSelect.options[discountSelect.selectedIndex].getAttribute('data-label');
            var discountamount = 0;

            if (discountValue) {
                discountamount = totalamount * (parseFloat(discountValue) / 100);
            }

            var finalamount = totalamount - discountamount;

            // Update hidden input fields
            document.getElementById('totalamount').value = totalamount;
            document.getElementById('discountamount').value = discountamount;
            document.getElementById('finalamount').value = finalamount;
            document.getElementById('discountText').value = discountLabel;

            // Display the final amount
            document.getElementById('finalAmountDisplay').innerText = '결제금액 : ' +  finalamount.toLocaleString() + '원';
        }

        document.getElementById('discountSelect').addEventListener('change', calculateDiscount);

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
	
	$("select").niceSelect();
});
//]]>
</script>
    <?######################################### iframe fancybox ######################################### ST?>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <style type="text/css">
        .fancybox__content { padding: 5px 0;border-radius: 4px; }
        .fancybox__slide {padding-bottom:20px;}

        /* Ensure the select box is above the file input */
        select[name="waitlist"] {
            position: relative;
            z-index: 10;
        }

        /* Adjust the file input container */
        .filebutton {
            position: relative;
            z-index: 1;
        }
    </style>
    <script type="text/javascript">
        <!--
        function OpenPersonView(fname)
        {
            var requestUrl = "/backoffice/module/board/pop_board_view.php?boardid=edu&fname="+fname;	// 일반게시판
            //	var requestUrl = "/backoffice/module/member/pop_member.php?fname="+fname;		// 회원
            //	var requestUrl = "/backoffice/module/shop/pop_good.php?fname="+fname;			// 상품
            Fancybox.show([
                {
                    src: requestUrl,
                    type: "iframe",
                    preload: false,
                    width: 1100,
                    height: 700
                },
            ]);
        }

        function OpenApplyView(fname)
        {
            //	var requestUrl = "/backoffice/module/board/pop_board_view.php?boardid=tbl_member&fname="+fname;
            var requestUrl = "/backoffice/module/member/pop_member.php?fname="+fname;
            Fancybox.show([
                {
                    src: requestUrl,
                    type: "iframe",
                    preload: false,
                    width: 1100,
                    height: 700
                },
            ]);
        }
        //-->
        <?php if (isset($arrBoardArticle["list"][0]["totalamount"])): ?>
          var totalamount = <?= $arrBoardArticle["list"][0]["totalamount"] ?>;
        <?php else: ?>
          var totalamount = 0;
        <?php endif; ?>

        function fnGoodSelect(stridx, inputName) {
            if (stridx) { $(".is-close-btn").click(); }
            if (inputName == "edu") {
                $.post("/module/board/ajax_edu.php", { mid: stridx },
                    function(data) {
                        var frm = document.form1;
                        const eduInfo = data.split("|");
                        frm.edu_no.value = eduInfo[1];
                        frm.category1.value = eduInfo[2];
                        frm.category2.value = eduInfo[3];
                        frm.subject.value = eduInfo[4]; // 교육명
                        frm.fee.value = eduInfo[5];
                        frm.totalamount.value = eduInfo[5];
                        frm.finalamount.value = eduInfo[5];
                        frm.edu_idx.value = eduInfo[6];
                        document.getElementById('finalAmountDisplay').innerText = '결제금액 : ' + parseFloat(eduInfo[5]).toLocaleString() + '원';

                        // Update the global totalamount variable
                        totalamount = parseFloat(eduInfo[5]);
                    }
                );
            }
        }
        function fnMemberSelect(user_id, inputName){
            if(user_id){ $(".is-close-btn").click();	}
            $.post("/module/member/ajax_member.php", { user_id : user_id },
                function(data){
                    var frm = document.form1;
                    frm.w_user.value  = user_id;

                    const memInfo = data.split("|");
                    frm.name.value		= memInfo[1];
                    frm.email.value		= memInfo[2];
                    frm.tel.value		= memInfo[3];
                    var userLevelContainer = document.getElementById('userLevelContainer');
                    userLevelContainer.innerText = memInfo[4]; // Assuming user_level is the 5th element in the response
                    frm.user_level.value	= memInfo[5];

                    // alert(memInfo[0]+memInfo[1]+memInfo[2]+memInfo[3]+memInfo[4]+memInfo[5])

                    //	$('#category').val(memInfo[0]).prop("selected",true);
                }
            );
        }

    </script>
    <?######################################### iframe fancybox ######################################### ED?>
<?}else{###################################################### 사용자 페이지 ######################################################?>
<?
//관리자만 글쓰기 기능 체크
if($arrBoardInfo["list"][0]["useadminonly"] !="Y" || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]):
	if($_REQUEST['mode']=="reply" && $arrBoardInfo["list"][0]["usereply"] !="Y"):
		jsMsg("답글쓰기가 제한된 게시판 입니다.");
		jsHistory("-1");
		exit;
	endif;
?>
<script type="text/javascript">
<!--
function frmCheck(frm){	
	if(frm.subject.value.length < 1){
		alert('제목을 입력해 주세요.');
		frm.subject.focus();
		return ;
	}
	
	try{ contents.outputBodyHTML(); } catch(e){ }

	frm.submit();

}	
//-->
</script>
<div class="inner">
	<form name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
		<input type="hidden" name="boardid" value="<?=$arrBoardInfo["list"][0]["boardid"]?>">
		<input type="hidden" name="altYN" value="N">
		<input type="hidden" name="returnURL" value="/mypage/mypage_01.php">
		<input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
		<input type="hidden" name="usehtml" value="N">
       	<?if($_REQUEST['mode']=="reply"):?>
		<input type="hidden" name="evnMode" value="reply">
		<?elseif($_REQUEST['mode']=="modify"):?>
		<input type="hidden" name="evnMode" value="modify">
		<?else:?>
		<input type="hidden" name="evnMode" value="write">
		<?endif;?>
		<input type="hidden" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
		<input type="hidden" name="name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>">
		<input type="hidden" name="tel" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"]?>">
		<input type="hidden" name="email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["EMAIL"]?>">
		<h3 class="heading3">Q&A</h3>
		<div class="form-wrap">
			<dl class="row">
				<dt class="label">제목</dt>
				<dd class="value">
					<input type="text" name="subject" maxlength="100" value="<?=stripslashes($arrBoardArticle["list"][0]['subject'])?>" placeholder="제목을 입력해주세요.">
				</dd>
			</dl>
			<dl class="row">
				<dt class="label">내용</dt>
				<dd class="value">				
					<textarea id="contents" name="contents"><?=stripslashes($arrBoardArticle["list"][0]['contents'])?></textarea>
					<?
					$CKContent = "contents";
					include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
					?>				
				</dd>
			</dl>
			<dl class="row">
				<dt class="label">첨부파일</dt>
				<dd class="value">
					<input name="upfiles[]" type="file" class="searchfile" title="파일 찾기">				
				</dd>
			</dl>
		</div>
		<div class="btn-wrap">
			<button type="button" class="btn primary" onclick="frmCheck(document.form1)">문의하기</button>
		</div>
	</form>
</div>
<?
else:
jsMsg("관리자만 등록/수정/삭제 할 수 있는 게시판 입니다.");
jsHistory("-1");
endif;
?>
<?}?>