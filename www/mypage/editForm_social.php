<?php include("../inc/header.php"); ?>
<?php include_once $_SERVER["DOCUMENT_ROOT"]."/module/board/board.lib.php"?>
<?php include_once $_SERVER["DOCUMENT_ROOT"]."/module/member/member.lib.php"?>
<?php include_once $_SERVER["DOCUMENT_ROOT"]."/module/category/category.lib.php"?>
<?php

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] != ""){
    $arrMemberInfo = getUserInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

}else{
    $is_member = false;
}
//DB해제
SetDisConn($dblink);

include $_SERVER['DOCUMENT_ROOT'] . "/_api/_NICE/niceapi_token_inc.php";
$_SESSION["SEAR_TYPE"] = "member_info";

?>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    // 우편번호 찾기 화면을 넣을 element


    function closeDaumPostcode() {
        var element_layer = document.getElementById('layer');
        // iframe을 넣은 element를 안보이게 한다.
        element_layer.style.display = 'none';
    }

    function execDaumPostcode(zip_id,address_id,address_ext_id) {
        var element_layer = document.getElementById('layer');
        new daum.Postcode({
            oncomplete: function(data) {
                var element_layer = document.getElementById('layer');
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

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
                    addr += extraAddr;

                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                //document.getElementById(zip_id).value = data.zonecode;
                document.getElementById(address_id).value = addr;
                // 커서를 상세주소 필드로 이동한다.
                document.getElementById(address_ext_id).focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_layer.style.display = 'none';
            },
            width : '100%',
            height : '100%',
            maxSuggestItems : 5
        }).embed(element_layer);

        // iframe을 넣은 element를 보이게 한다.
        element_layer.style.display = 'block';

        // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
        initLayerPosition();
    }

    // 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
    // resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
    // 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
    function initLayerPosition(){
        var element_layer = document.getElementById('layer');
        var width = 500; //우편번호서비스가 들어갈 element의 width
        var height = 400; //우편번호서비스가 들어갈 element의 height
        var borderWidth = 5; //샘플에서 사용하는 border의 두께

        // 위에서 선언한 값들을 실제 element에 넣는다.
        element_layer.style.width = width + 'px';
        element_layer.style.height = height + 'px';
        element_layer.style.border = borderWidth + 'px solid';
        // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
        element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
        element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
    }
</script>

<!-- Container -->
<div class="container sub" id="container">

    <!-- subTopBg -->
    <div class="subTopBg myPage">
        <div class="inner">
            <div class="enName">MY PAGE</div>
            <div class="korName">마이페이지</div>
            <div class="lnb">
                <a href="/"><img src="/images/ico_home.svg" alt="home"></a>
                <div class="lnbSub">
                    <div class="tit">마이페이지</div>
                    <ul>
                        <li><a href="/edu/info.php">미디어교육</a></li>
                        <li><a href="/equ/info.php">장비대여</a></li>
                        <li><a href="/place/info.php">공간대관</a></li>
                        <li><a href="/media/info.php">미디어체험</a></li>
                        <li><a href="/center/intro.php">센터안내</a></li>
                        <li><a href="/cm/notice.php">게시판</a></li>
                    </ul>
                </div>
                <div class="lnbSub">
                    <div class="tit">나의 정보 관리</div>
                    <ul>
                        <li><a href="orderList.php">신청 내역</a></li>
                        <li><a href="freeList.php">나의 활동 관리</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- //subTopBg -->

    <!-- subSec -->
    <div class="subSec pt80 last">
        <div class="mySide inner">

            <div class="menu">
                <div class="inMenu">
                    <div class="box">
                        <div class="tit">신청 내역</div>
                        <ul>
                            <li><a href="orderList.php">교육신청</a></li>
                            <li><a href="orderListEq.php">장비대여</a></li>
                            <li><a href="orderListPlace.php">공간대여</a></li>
                            <li><a href="orderListVideo.php">상영회</a></li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="tit">나의 활동 관리</div>
                        <ul>
                            <li><a href="freeList.php">자유게시판</a></li>
                            <li><a href="stopList.php">자격 정지 내역</a></li>
                        </ul>
                    </div>
                    <div class="box">
                        <div class="tit">나의 정보 관리</div>
                        <ul>
                            <li><a href="edit.php" class="active">회원정보 수정</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="rightCont">
                <form action='/module/member/member_evn.php' method='post' name='form1' enctype="multipart/form-data" onsubmit="return submitForm();">
                    <input type="hidden" name="evnMode" value="edit" />
                    <input type="hidden" name="idx" value="<?=$arrMemberInfo["list"][0]["idx"]?>" />
                    <input type="hidden" name="returnUrl" value="/member/mypage.php" />
                    <input type="hidden" id="dupcheck" name="dupcheck">
                    <input type="hidden" id="email_dupcheck" name="email_dupcheck" value="<?=$arrMemberInfo["list"][0]["email"]?>">
                    <input type="hidden" name="user_id" value="<?=$arrMemberInfo["list"][0]["user_id"]?>">
                    <input type="hidden" name="is_change_company" id="is_change_company" value="N">
                    <input type="hidden" name="rt_url" id="rt_url" value="/mypage/editForm_social.php">
                    <div class="bigTit">회원정보 수정</div>

                    <div class="joinWrap edit">
                        <div class="title">
                            <div class="tit">가입정보</div>
                            <div class="eq"><span>*</span> 는 필수 입력 사항입니다.</div>
                        </div>
                        <div class="formJoin">
                            <div class="line">
                                <div class="tit">이름<span>*</span></div>
                                <div class="right">
                                    <div class="baseInput">
                                        <input type="text" name="user_name" id="user_name"  value="<?=$arrMemberInfo['list'][0]['user_name']?>" readonly class="readonly-input">
                                    </div>
                                </div>
                            </div>
                            <div class="line">
                                <div class="tit">성별<span>*</span></div>
                                <div class="baseInput flex_half">
                                    <div class="baseRadio">
                                        <input type="radio" name="gender" id="gnd1" value="M" <?= $arrMemberInfo['list'][0]['gender'] == 'M' ? 'checked' : '' ?>>
                                        <label for="gnd1">남</label>
                                    </div>
                                    <div class="baseRadio">
                                        <input type="radio" name="gender" id="gnd2" value="F" <?= $arrMemberInfo['list'][0]['gender'] == 'F' ? 'checked' : '' ?>>
                                        <label for="gnd2">여</label>
                                    </div>
                                </div>
                            </div>
                            <div class="line">
                                <div class="tit">연락처<span>*</span></div>
                                <div class="right">
                                    <div class="btnInput">
                                        <div class="baseInput">
                                            <input type="text" name="mobile"  id="mobile" oninput="checkPhoneNumber(this)" placeholder="010-0000-0000" value="<?=$_SESSION['uMobile']?$_SESSION['uMobile']:$arrMemberInfo["list"][0]['mobile']?>" readonly class="readonly-input">
                                        </div>
                                        <a href="javascript:fnPopup();" class="btn sm">변경</a>
                                    </div>
                                </div>
                            </div>
                            <div class="line">
                                <div class="tit solo">아이디<span>*</span></div>
                                <div class="right">
                                    <div class="text"><?=$arrMemberInfo['list'][0]['user_id']?></div>
                                </div>
                            </div>
                            <div class="line">
                                <div class="tit">이메일<span>*</span></div>
                                <div class="right">
                                    <div class="baseInput">
                                        <input type="text" name="email" id="email"   value="<?=$arrMemberInfo['list'][0]['email']?>" readonly class="readonly-input">
                                    </div>
                                </div>
                            </div>
                            <div class="line">
                                <div class="tit">주소<span>*</span></div>
                                <div class="right">
                                    <div class="btnInput">
                                        <div class="baseInput">
                                            <input type="text" name="address" id="address" value="<?=$arrMemberInfo["list"][0]['address']?>">
                                        </div>
                                        <a href="javascript:execDaumPostcode('zip','address','address_ext');" class="btn">주소검색</a>
                                    </div>
                                    <div class="baseInput">
                                        <input type="text" name="address_ext" id="address_ext" value="<?=$arrMemberInfo["list"][0]['address_ext']?>">
                                    </div>
                                </div>
                            </div>
                            <div class="line">
                                <div class="tit">가입경로<span>*</span></div>
                                <div class="right">
                                    <div class="baseInput">
                                        <input type="text"  name="etc_1" placeholder="내용을 입력해주세요." value="<?=$arrMemberInfo["list"][0]['etc_1']?>">
                                    </div>
                                    <div class="warnIco sm">미추홀구 주민일 경우 주소의 동을 별도로 입력해주시기 바랍니다. 예)주안1동</div>
                                </div>
                            </div>
                            <div class="line agreeLine">
                                <div class="tit">정보수신 동의<br />(선택)</div>
                                <div class="right">
                                    <div class="ck">
                                        <div class="baseCheck">
                                            <input type="checkbox" id="email_accept" name="email_accept" value="Y" <?=$arrMemberInfo["list"][0]['email_accept'] == "Y"?"checked":""?>/>
                                            <label for="email_accept">이메일 수신 동의</label>
                                        </div>
                                        <div class="baseCheck">
                                            <input type="checkbox" id="sns" name="sms_accept" value="Y" <?=$arrMemberInfo["list"][0]['sms_accept'] == "Y"?"checked":""?>/>
                                            <label for="sns">SNS 수신 동의</label>
                                        </div>
                                    </div>
                                    <div class="warnIco sm">체크 시 정보를 수신할 수 있습니다.</div>
                                </div>
                            </div>
                        </div>
                        <div class="btnOut">
                            <a href="javascript:fncWithdrawal()">회원탈퇴</a>
                        </div>
                        <div class="btnCenter two mini">
                            <a href="javascript:void(0);" class="btnType1 black" onclick="submitForm();">정보수정</a>
                            <a href="/mypage/orderList.php" class="btnType1 gray">취소</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- //subSec -->

</div>
<!-- //Container -->
<div id="layer" style="display:none;position:fixed;overflow:hidden;z-index:1;-webkit-overflow-scrolling:touch;">
    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" onclick="closeDaumPostcode()" alt="닫기 버튼">
</div>
<style>
    .readonly-input {
        background-color: #f0f0f0; /* Light gray background */
        color: #666; /* Darker text color */
    }
</style>
<script src="//code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function(){
        $(".searchfile").on('change',function(){
            val = $(this).val().split("\\");
            f_name = val[val.length-1];
            s_name = f_name.substring(f_name.length-4, f_name.length);
            $(this).parent().siblings('.filebox').html(f_name);
        });

        $(".btn_opcl").click(function(){
            $(this).stop(false,true).toggleClass("on");
            $(".opcl_box").stop(false,true).slideToggle("fast");
        });

        $(".btn_withdrawal").click(function(){
            $(".pop_withdrawal").fadeIn("fast")
        });
        $(".popup .btn_close, .popup .dm,.popup .btn_col").click(function(){
            $(".popup").fadeOut("fast")
        });

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
    });
    //]]>

    function check_email(email){
        if(email ==""){
            alert('이메일을 입력하신 후 클릭해 주세요.');
            document.form1.email.focus();
        } else {
            $.get("/module/member/ajax_check_email_member.php", {email: email},
                function(data){
                    if(data== "0"){
                        alert('사용 가능한 이메일 입니다.');
                        document.form1.email_dupcheck.value = email;
                    } else if(data== "1"){
                        alert('이미 사용 중인 email 입니다.');
                    } else {
                        alert('오류가 발생하였습니다. 다시 시도해 주세요.');
                    }
                    document.form1.email.focus();
                });
        }
    }

    function validatePassword(password) {
        var regex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/;
        return regex.test(password);
    }

    function validateEmail(email){
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    };

    function checkForm(frm){

        if (frm.email.value.length < 1){
            alert("이메일을 입력해주세요.");
            frm.email.focus();
            return false;
        }else if (!validateEmail(frm.email.value)){
            alert("이메일 형식을 맞춰주세요.");
            frm.email.focus();
            return false;
        }else{
            return true;
        }
    }

    function checkPhoneNumber(obj) {
        // 입력된 값
        let inputValue = obj.value;

        // 숫자만 남기고 나머지는 제거
        let numericValue = inputValue.replace(/[^0-9]/g, '');

        // 앞에 02 또는 01로 시작하는 번호에 하이픈 추가
        let formattedValue = numericValue.replace(/^(02|01[0-9])/, '$1-');

        // 뒤에 나머지 번호에 하이픈 추가
        formattedValue = formattedValue.replace(/^(02|01[0-9])-?([0-9]{3,4})/, '$1-$2');

        formattedValue = formattedValue.replace(/^(02|01[0-9])-?([0-9]{3,4})-?([0-9]{4})/, '$1-$2-$3');

        // 원본 입력값 갱신
        obj.value = formattedValue;
    }

    function submitForm() {
        var form = document.form1;

        if (!checkForm(form)) {
            return false;
        } else {
            form.submit();
            return true;
        }
    }
</script>
<script>
    // 비밀번호 확인 함수
    function checkPassword() {
        var password1 = document.getElementById('user_pw').value;
        var password2 = document.getElementById('user_pw2').value;
        var errorMessage = document.querySelector('.pw_unavailable');

        if (password1 !== password2) {
            errorMessage.style.display = 'block';
        } else {
            errorMessage.style.display = 'none';
        }
    }

    // 비밀번호 확인 이벤트 핸들러 등록
    document.getElementById('user_pw').addEventListener('input', checkPassword);
    document.getElementById('user_pw2').addEventListener('input', checkPassword);

    function fncWithdrawal(){
        if (confirm("정말 탈퇴하시겠습니까?")) {
            $.get("/module/member/ajax_member_withdrawal.php", function(result){
                if(result == "1"){
                    alert("정상적으로 탈퇴되었습니다.");
                    location.href='/';
                }else{
                    alert("탈퇴에 실패했습니다. 관리자에게 문의해주세요.");
                    location.reload();
                }
            });
        }
    }

</script>
<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

</div>
<!-- //Wrap -->

</body>
</html>



