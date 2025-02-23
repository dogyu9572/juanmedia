<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));
$arrLevel = getArticleList($_conf_tbl["member_level"], $scale, $_REQUEST['offset'], "order by level_no desc ");

//DB해제
SetDisConn($dblink);

$todate = date("YmdHis");	// 현재일
$user_id = "member_".sha1($todate);

?>
<script language="javascript">
function checkForm(frm){

    if (frm.user_name.value.length < 2){
        alert("이름을 입력해 주세요.");
        frm.user_name.focus();
        return false;
    }
	if (frm.user_id.value.length < 2){
		alert("ID를 입력해 주세요.");
		frm.user_id.focus();
		return false;
	}	
	if (frm.mobile.value.length < 1){
		alert("연락처를 입력해 주세요.");
		frm.mobile.focus();
		return false;
	}
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
	if (frm.birth.value.length < 1){
		alert("생년월일을 입력해 주세요.");
		frm.birth.focus();
		return false;
	}
	if (frm.gender.value.length < 1){
		alert("성별을 입력해 주세요.");
		frm.gender.focus();
		return false;
	}	
}

function inNumber(str){
	// 숫자만 입력
	str.value = str.value.replace(/[^0-9]/g,"");	
}
</script>
<div class="container">

	<div class="title">회원 등록</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="memberForm" method="post" action="member_evn.php" onsubmit="return checkForm(this)">
		<input type="hidden" name="evnMode" value="insert">
        <input type="hidden" name="join_type" value="<?=$arrInfo["list"][0]["join_type"]?$arrInfo["list"][0]["join_type"]:"homepage"?>">
		<input type="hidden" name="rt_url" value="<?=$_REQUEST['listURL']?>">

		<div class="tit">회원정보 <i>*</i></div>
		<table>
            <tr>
                <th>회원 상태 <i>*</i></th>
                <td>
                    <select name="user_level" class="w2">>
                        <option value="">전체</option>
                        <?for ($i=0;$i<$arrLevel['total'];$i++) {?>
                            <option value="<?=$arrLevel['list'][$i]['level_no']?>" <?=$arrLevel['list'][$i]['level_no']==$arrInfo["list"][0]['user_level']?" selected":""?>><?=$arrLevel['list'][$i]['level_name']?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>가입 구분 <i>*</i></th>
                <td>일반</td>
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
                <td><div class="inputs"><input type="text" class="w3" name="mobile" value="<?=$mobile?>" maxlength="20"><button type="button" onclick="sendAcc(document.memberForm.mobile)" class="btn">연락처 중복확인</button></td></div>
            </tr>
            <tr>
                <th>생년월일<i>*</i></th>
                <td><div class="inputs"><input type="text" class="w3 datepicker" name="birth" value="<?=$arrInfo["list"][0]['birth']?>" maxlength="20"></td></div>
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

		<div class="btns">
			<a href="member.php" class="btn btn_list">목록보기</a>
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
    <script type="text/javascript">
        function sendAcc(mobile){
            if (mobile.value.length < 1){
                alert("핸드폰번호를 입력해 주세요.");
                mobile.focus();
                return;
            }
            $.get("/module/member/ajax_check_mobile.php", {mobile: mobile.value},
                function(data){
                    if(data == 0){
                        alert('사용 가능한 연락처입니다.');
                    } else if(data == 1){
                        alert('이미 사용 중인 연락처입니다. 다른 연락처를 입력해주세요.');
                        document.memberForm.mobile.value = '';
                    } else {
                        alert('오류가 발생하였습니다. 다시 시도해 주세요.');
                    }
                    document.memberForm.mobile.focus();
                });
        }
        function fnAddViolation() {
            var htm = `
     <tr>
        <td>
            <input type="text" class="w4 input-content" name="child_violation[]" value="">
        </td>
        <td>
            <select class="w4 input-content" name="child_category[]">
                <option value="정지">정지</option>
                <option value="주의">주의</option>
            </select>
        </td>
        <td>
            <input type="text" class="w4 datepicker input-content" name="child_violation_wdate[]" value="">
        </td>
        <td>
            <a href="javascript:void(0);" onclick="toggleAddViolation(this)" class="btn edit" style="display: inline-block;">추가</a>
            <a href="javascript:void(0);" onclick="fnDeleteViolation(this)" class="btn del" style="display: inline-block;">삭제</a>
        </td>
    </tr>`;
            $("#violationList").append(htm);

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

        function toggleAddViolation(button) {
            var row = button.closest('tr');
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
            button.setAttribute('onclick', 'toggleEditViolation(this)');
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
        $(document).ready(function(){
            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd',
                showMonthAfterYear:true,
                showOn: "both",
                buttonImage: "../images/ico_cal.svg",
                buttonImageOnly: true,
                changeYear: true,
                changeMonth: true,
                yearRange: 'c-100:c+10',
                yearSuffix: "년 ",
                monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                dayNamesMin: ['일','월','화','수','목','금','토']
            });
        });
    </script>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>