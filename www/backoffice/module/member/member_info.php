<?PHP
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include_once "./menu.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
if(!in_array("member_manage",$arrLevel_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));

$arrLevel = getArticleList($_conf_tbl["member_level"], $scale, $_REQUEST['offset'], "order by level_no desc ");
for($i = 0; $i < $arrLevel["total"]; $i ++) {
	$arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}


$arrCUList = getCouponMemberList($_REQUEST["user_id"], $scale, $_REQUEST['offset']);
$arrCouponList = getCouponListAdmin(0, 0, "Y");

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

$arrBrandList = getCategoryList(3,'Y');			//회원등급

if($arrInfo['list'][0]['user_editdt']){
	$upQuery = "UPDATE tbl_member SET user_editdt = NULL WHERE idx='".$arrInfo['list'][0]['idx']."' ";
	mysqli_query($GLOBALS['dblink'], $upQuery);
}


$arrBoardList = getBoardListBase("accept","", "u_id", $arrInfo["list"][0]["nick_name"], 0, 0,"");

$arrAllCategory = getCategoryAll();	// 전체카테고리

$arrCsList = getCsList($arrInfo['list'][0]['idx'],0,0);

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
function frmCheck(frm){	
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
	
	if (frm.user_id.value.length < 4){
		alert("아이디는 4자 이상 입력해 주세요.");
		frm.user_id.focus();
		return ;
	}
	if(frm.user_name.value.length < 1){ //이름
		alert('이름을 입력해 주세요.');
		frm.user_name.focus();
		return ;
	}	
	
	if(frm.mobile.value.length < 1){ // 휴대폰 번호
		alert('연락처를 입력해 주세요.');
		frm.mobile.focus();
		return ;
	}
	frm.submit();
}

function inNumber(str){
	// 숫자만 입력
	str.value = str.value.replace(/[^0-9\-]/g,"");	
}
</script>
<style>
.inner_table {table-layout:auto;}
.inner_table th,
.inner_table td {font-size:12px; height:51px;}
.inner_table thead th {color:#555; font-weight:700; background:#e3ecf9;line-height: 0; padding: 0;text-align: center;vertical-align: middle;width:auto;}
.inner_table tbody td {color:#666; padding:0 10px; border-bottom:#ddd 1px solid; transition:.2s linear;text-align: center;vertical-align: middle;}
.inner_table tbody td .nice-select {margin:0 auto; float:none;}
.inner_table tbody td .btns {display: flex; justify-content: center; margin: 0;}
.inner_table tbody td .btns .btn {width:52px; height:20px; line-height:20px; font-size:12px; color:#fff; text-align:center; background:#2668b4; margin:0 5px; border-radius:3px;}
.inner_table tbody td .btns .btn:first-child {margin-left:0;}
.inner_table tbody td .btns .btn:last-child {margin-right:0;}
.inner_table tbody td .btns .btn:before {content:""; display:inline-block; vertical-align:top; height:20px; background:no-repeat 50% 50% / contain;}
.inner_table tbody td .btns .btn.del,.inner_table tbody td .btns .btn.cancel {background:#e1e4eb; color:#555;}
.inner_table tbody td .btns .btn.perf:before {background-image:url('/backoffice/pub/images/icon_perf.svg'); width:9px; margin-right:4px;}

.inner_table tbody td .btns .btn.modi:before {background-image:url('/backoffice/pub/images/icon_modi.svg'); width:11px; margin-right:1px;}
.inner_table tbody td .btns .btn.del:before {background-image:url('/backoffice/pub/images/icon_del.svg'); width:7px; margin-right:7px;}

.inner_btn {width:52px; height:20px; line-height:20px; font-size:12px; color:#fff; text-align:center; background:#2668b4; margin:0 5px; border-radius:3px;}
</style>
<div class="container">

	<div class="title">회원 수정</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="memberForm" method="post" action="member_evn.php" ENCTYPE="multipart/form-data">
			<?
			$evnMode = "edit";
			if(!$_GET['user_id']){ $evnMode = "insert"; }
			if($arrInfo["list"][0]["join_type"] == "homepage"){
				$is_pw_change = true;
			}else{
				$is_pw_change = false;
			}

            if ($arrInfo['list'][0]['before'] == 'Y') {
                $user_name = base64_decode($arrInfo['list'][0]['user_name']);
                $mobile = base64_decode($arrInfo['list'][0]['mobile']);
                $email = base64_decode($arrInfo['list'][0]['email']);
                $address_ext = base64_decode($arrInfo['list'][0]['address_ext']);
                $address = base64_decode($arrInfo['list'][0]['address']);

            }
            else {
                $user_name = $arrInfo['list'][0]['user_name'];
                $mobile = $arrInfo['list'][0]['mobile'];
                $email = $arrInfo['list'][0]['email'];
                $address_ext = $arrInfo['list'][0]['address_ext'];
                $address = $arrInfo['list'][0]['address'];
            }

			$mobile = str_replace("-","",$mobile);
			?>
			<input type="hidden" name="evnMode" value="<?=$evnMode?>">		
			<input type="hidden" name="rt_url" value="<?=$_REQUEST['listURL'] != ""?$_REQUEST['listURL']:"/backoffice/module/member/member.php"?>">
			<input type="hidden" name="join_type" value="<?=$arrInfo["list"][0]["join_type"]?$arrInfo["list"][0]["join_type"]:"homepage"?>">
			<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]["idx"]?$arrInfo["list"][0]["idx"]:""?>">
			<input type="hidden" name="etc_8" value="<?=$arrInfo["list"][0]["etc_8"]?>">
			<input type="hidden" name="etc_9" value="<?=$arrInfo["list"][0]["etc_9"]?>">
            <input type="hidden" name="email" value="<?=$email?>">
			<div class="tit">회원정보</div>
			<table>
                <tr>
                    <th>회원 상태 <i>*</i></th>
                    <td>
                        <select name="user_level" class="w2">
                            <option value="">전체</option>
                            <?for ($i=0;$i<$arrLevel['total'];$i++) {?>
                                <option value="<?=$arrLevel['list'][$i]['level_no']?>" <?=$arrLevel['list'][$i]['level_no']==$arrInfo["list"][0]['user_level']?" selected":""?>><?=$arrLevel['list'][$i]['level_name']?></option>
                            <?}?>
                        </select>
                    </td>
                </tr>
				<tr>
					<th>가입 구분 <i>*</i></th>
					<td><?=$_SITE["MEMBER_TYPE"][$arrInfo["list"][0]["join_type"]]?></td>
				</tr>
                <tr>
                    <th>이름 <i>*</i></th>
                    <td><div class="inputs"><input type="text" class="w3" name="user_name" maxlength="50" value="<?=$user_name?>">
                        </div></td>
                </tr>
                <tr>
					<th>이메일(아이디) <i>*</i></th>
					<td><div class="inputs"><input type="text" class="w3" name="user_id" maxlength="100" value="<?=$arrInfo["list"][0]['user_id']?>" <?=$evnMode=="edit"?"readonly":""?>>
					</div></td>
				</tr>
				<tr>
					<th>연락처<i>*</i></th>
					<td><div class="inputs"><input type="text" class="w4" name="mobile" value="<?=$mobile?>" maxlength="20"></div></td>
				</tr>
                <tr>
                    <th>생년월일<i>*</i></th>
                    <td><div class="inputs"><input type="text" class="w3 datepicker" name="birth" value="<?=$arrInfo["list"][0]['birth']?>" maxlength="20"></td></div>
                </tr>
				<tr <?=$is_pw_change?"":"style='display:none;'"?>>
					<th>비밀번호 <i>*</i></th>
					<td><div class="inputs"><input type="password" class="w4" name="user_pw" maxlength="50" value=""></div></td>
				</tr>	
				<tr <?=$is_pw_change?"":"style='display:none;'"?>>
					<th>비밀번호확인 <i>*</i></th>
					<td><div class="inputs"><input type="password" class="w4" name="user_pw2" maxlength="50" value=""></div></td>
				</tr>
                <tr>
                    <th>주소</th>
                    <td>
                        <div class="inputs">
                            <input type="text" name="zip" id="zip"  style="display:none;"  value="<?=$arrInfo["list"][0]['zip']?>" class="text w1" >
                                <input type="text" name="address" id="address" value="<?=$address?>" class="text w3" >  <button type="button" onclick="execDaumPostcode('zip','address','address_ext')" class="btn">우편번호찾기</button>
                                <input type="text" name="address_ext" id="address_ext" value="<?=$address_ext?>" class="text w4 mlong">

                        </div>
                    </td>
                </tr>
                <tr>
                    <th>성별 <i>*</i></th>
                    <td>
                        <div class="inputs">
                            <label class="radio"><input type="radio" name="gender" value="M" <?=$arrInfo["list"][0]["gender"] == "M"?"checked":""?>><i></i>남</label>
                            <label class="radio"><input type="radio" name="gender" value="F" <?=$arrInfo["list"][0]["gender"] == "F"?"checked":""?>><i></i>여</label>
                        </div>
                    </td>
                </tr>
				<tr>
					<th>이메일 수신동의 <i>*</i></th>
					<td>
						<div class="inputs">
							<label class="radio"><input type="radio" name="email_accept" value="Y" <?=$arrInfo["list"][0]["email_accept"] == "Y"?"checked":""?>><i></i>Y</label>
							<label class="radio"><input type="radio" name="email_accept" value="N" <?=$arrInfo["list"][0]["email_accept"] != "Y"?"checked":""?>><i></i>N</label>
						</div>
					</td>
				</tr>
				<tr>
					<th>SMS 수신동의 <i>*</i></th>
					<td>
						<div class="inputs">
							<label class="radio"><input type="radio" name="sms_accept" value="Y" <?=$arrInfo["list"][0]["sms_accept"] == "Y"?"checked":""?>><i></i>Y</label>
							<label class="radio"><input type="radio" name="sms_accept" value="N" <?=$arrInfo["list"][0]["sms_accept"] != "Y"?"checked":""?>><i></i>N</label>
						</div>
					</td>
				</tr>
                <tr>
                    <th>비고</th>
                    <td colspan="3">
                        <textarea id="user_memo" name="user_memo" cols="100" rows="5"><?=($arrInfo["list"][0]['user_memo'])?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>가입경로<i>*</i></th>
                    <td><div class="inputs"><input type="text" class="w3" name="etc_1" maxlength="50" value="<?=($arrInfo["list"][0]['etc_1'])?>">
                        </div></td>
                </tr>
                <tr>
                    <th>최종로그인</th>
                    <td>
                        <?=$arrInfo["list"][0]["login_last"]?>
                    </td>
                </tr>
                <tr>
                    <th>가입일</th>
                    <td>
                        <?=$arrInfo["list"][0]["wdate"]?>
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
								$arrChildAdmin = explode("||", $arrInfo["list"][0]['child_admin']);
								$arrChildWdate = explode("||", $arrInfo["list"][0]['child_wdate']);
								if ($arrInfo["list"][0]['child_admin']){
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
            <table>
                <tr>
                    <th>주의/정지 내역</th>
                    <td>
                        <div class="btns" style="height:30px;margin-top:0;margin-bottom:10px; justify-content: left">
                            <a href="javascript:void(0);" class="btn" onclick="fnAddViolation()">추가</a>
                        </div>
                        <div class="bdr_list tac" style="width:100%;board:1px">
                            <table>
                                <colgroup>
                                    <col width="20%">
                                    <col width="15%">
                                    <col width="15%">
                                    <col width="10%">
                                    <col width="15%">
                                    <col width="25%">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center;padding:20px 0;">위반내용</th>
                                    <th style="text-align:center;padding:20px 0;">시작일</th>
                                    <th style="text-align:center;padding:20px 0;">종료일</th>
                                    <th style="text-align:center;padding:20px 0;">구분</th>
                                    <th style="text-align:center;padding:20px 0;">등록일</th>
                                    <th style="text-align:center;padding:20px 0;">수정/삭제</th>
                                </tr>
                                </thead>
                                <tbody id="violationList">
								<?
								$arrChildViolation = explode("||", $arrInfo["list"][0]['child_violation']);
								$arrChildCategory = explode("||", $arrInfo["list"][0]['child_category']);
								$arrChildViolationWdate = explode("||", $arrInfo["list"][0]['child_violation_wdate']);
                                $arrChildViolationStart = explode("||", $arrInfo["list"][0]['child_violation_start_date']);
                                $arrChildViolationEnd = explode("||", $arrInfo["list"][0]['child_violation_end_date']);
								if ($arrInfo["list"][0]['child_violation_wdate']){
									for ($i = 0; $i < count($arrChildViolationWdate); $i++) {
										?>
                                        <tr>
                                            <td>
                                                <span class="text-content"><?=$arrChildViolation[$i]?></span>
                                                <input type="text" class="w4 input-content" name="child_violation[]" value="<?=$arrChildViolation[$i]?>" style="display:none;">
                                            </td>
                                            <td>
                                                <span class="text-content"><?=$arrChildViolationStart[$i]?></span>
                                                <input type="text" class="w3 datepicker input-content" name="child_violation_start_date[]" value="<?=$arrChildViolationStart[$i]?>" style="display:none;">
                                            </td>
                                            <td>
                                                <span class="text-content"><?=$arrChildViolationEnd[$i]?></span>
                                                <input type="text" class="w3 datepicker input-content" name="child_violation_end_date[]" value="<?=$arrChildViolationEnd[$i]?>" style="display:none;">
                                            </td>
                                            <td>
                                                <span class="text-content"><?=$arrChildCategory[$i]?></span>
                                                <select class="w4 input-content" name="child_category[]" style="display:none;" onload="this.style.display='none';">
                                                    <option value="정지" <?=$arrChildCategory[$i]=='정지'?'selected':''?>>정지</option>
                                                    <option value="주의" <?=$arrChildCategory[$i]=='주의'?'selected':''?>>주의</option>
                                                </select>
                                            </td>
                                            <td>
                                                <span class="text-content"><?=$arrChildViolationWdate[$i]?></span>
                                                <input type="text" class="w3 datepicker input-content" name="child_violation_wdate[]" value="<?=$arrChildViolationWdate[$i]?>" style="display:none;">
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
		</form>

		<div class="btns">
			<a href="javascript:void(0);" onclick="history.back();" class="btn btn_list">목록보기</a>
			<button class="btn btn_save" type="button" onclick="frmCheck(document.memberForm)">수정하기</button>
		</div>
	</div> <!-- //inbox -->

</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var elements = document.querySelectorAll('select[name="child_category[]"]');
        elements.forEach(function(element) {
            element.style.display = 'none';
        });
    });

    $(document).ready(function() {
        // Override the niceSelect function to do nothing
        $.fn.niceSelect = function() {
            return this;
        };

        // If you need to remove the existing niceSelect elements
        $('.nice-select').remove();
        $('select').show();
    });

    function fnAddViolation() {
        var today = new Date().toISOString().split('T')[0];
        var htm = `
    <tr>
        <td>
            <input type="text" class="w4 input-content" name="child_violation[]" value="">
        </td>
        <td>
            <input type="text" class="w4 datepicker input-content" name="child_violation_start_date[]" value="">
        </td>
        <td>
            <input type="text" class="w4 datepicker input-content" name="child_violation_end_date[]" value="">
        </td>
        <td>
            <select class="w4 input-content" name="child_category[]">
                <option value="정지">정지</option>
                <option value="주의">주의</option>
            </select>
        </td>
        <td>
            <input type="text" class="w4 datepicker input-content" name="child_violation_wdate[]" value="${today}" readonly>
        </td>
        <td>
            <a href="javascript:void(0);" onclick="toggleAddViolation(this)" class="btn edit" style="display: inline-block;">추가</a>
            <a href="javascript:void(0);" onclick="fnDeleteViolation(this)" class="btn del" style="display: inline-block;">삭제</a>
        </td>
    </tr>`;
        $("#violationList").append(htm);

        // Initialize datepicker for the new inputs
        initDatepicker();
    }

    function toggleAddViolation(button) {
        var row = button.closest('tr');
        var inputContents = row.querySelectorAll('.input-content');

        // Create text content elements and hide input fields
        inputContents.forEach(function(inputContent) {
            var span = document.createElement('span');
            span.className = 'text-content';

            // Handle select elements differently
            if (inputContent.tagName.toLowerCase() === 'select') {
                span.textContent = inputContent.options[inputContent.selectedIndex].value;
            } else {
                span.textContent = inputContent.value;
            }

            inputContent.style.display = 'none';
            inputContent.parentNode.insertBefore(span, inputContent);
        });

        // Change the button to "수정" (Edit)
        button.textContent = '수정';
        button.className = 'btn edit';
        button.setAttribute('onclick', 'toggleEdit(this)');
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
                // 카테고리 필드인 경우 select box로 변경
                if (inputContent.name === 'child_category[]') {
                    var selectBox = document.createElement('select');
                    selectBox.className = 'w4 input-content';
                    selectBox.name = 'child_category[]';

                    var options = [
                        {value: '정지', text: '정지'},
                        {value: '주의', text: '주의'}
                    ];

                    options.forEach(function(option) {
                        var opt = document.createElement('option');
                        opt.value = option.value;
                        opt.text = option.text;
                        if (inputContent.value === option.value) {
                            opt.selected = true;
                        }
                        selectBox.appendChild(opt);
                    });

                    inputContent.parentNode.replaceChild(selectBox, inputContent);
                } else {
                    inputContent.style.display = 'inline';
                }
            });

            // Re-initialize datepicker for visible date inputs
            initDatepicker();
            button.textContent = '저장';
        } else {
            inputContents.forEach(function(inputContent, index) {
                var value = inputContent.tagName.toLowerCase() === 'select' ?
                    inputContent.options[inputContent.selectedIndex].value :
                    inputContent.value;
                textContents[index].textContent = value;
                textContents[index].style.display = 'inline';
                inputContent.style.display = 'none';
            });

            button.textContent = '수정';
        }
    }

    function initDatepicker() {
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
    }

    function fnDeleteViolation(button) {
        $(button).closest('tr').remove();
    }
</script>
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
function changeCsModifyForm(idx,type=''){
	$.get("./get_cs_list_form.php?idx="+idx+"&type="+type,function(result){
		$("#cs_cont_"+idx).html(result);
	});
}

function frmCsSubmit(idx=""){
	let frm;
	if(idx == ""){
		frm = document.form2;
	}else{
		frm = document.form3;
		frm.category.value = $("#input_cs_category").val();
		frm.contents.value = $("#input_cs_contents").val();
		frm.schdule_date.value = $("#input_cs_schdule_date").val();
		frm.name.value = $("#input_cs_name").val();
		frm.idx.value = idx;
	}

	if(frm.name.value.length < 1){
		alert('이름을 입력해 주세요.');
		return ;
	}else if(frm.contents.value.length < 1){
		alert('메모를 입력해 주세요.');
		return ;
	}else{
		frm.submit();
	}
}

function delCs(idx){
	if(confirm("해당하는 CS 내역을 삭제하시겠습니까? 삭제후 복구가 불가능합니다.")){
		let frm = document.form3;
		frm.idx.value = idx;
		frm.evnMode.value = "delete";
	
		frm.submit();
	}
}


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