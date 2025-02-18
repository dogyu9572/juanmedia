<?php include("../inc/header.php"); ?>
<?php
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
$nick_name = $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["USERCODE"];
$arrList = getUserInfoNnickname($nick_name);

//기존회원 아이디 찾기
if ($arrList["total"] == 0) {
	$name = $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"];
	$mobile = $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"];
	$arrList = getMemberInfoByNameAndMobile($name, $mobile);
}

//DB해제
SetDisConn($dblink);

if($arrList["total"] > 0){
	if($arrList["list"][0]["join_type"] != "homepage"){ // 소셜회원일 경우
		jsGo("/member/idCompleteSns.php",'',"");
	}
}else{
	jsGo("/member/login.php",'',"회원정보가 없습니다.");
}
?>

<!-- Container -->
<div class="container sub" id="container">

    <!-- pageTitle -->
    <div class="pageTitle inner only mb1">비밀번호 재설정</div>
    <!-- //pageTitle -->

    <div class="memberText">
        새로운 비밀번호를 설정해 주세요.
    </div>
    <form name="frmMember" id="frmMember" action="/module/member/member_evn.php" method="post" onsubmit="return frmSubmit(this)">
        <input type="hidden" name="evnMode" value="pw_change">
        <input type="hidden" name="returnURL" value="/member/find_pw_end.php">
        <!-- memberWrap -->
        <div class="memberWrap">
            <div class="pwSet shadowDiv">
                <div class="input">
                    <div class="baseInput first big">
                        <input type="password" name="user_pw" id="user_pw" placeholder="새 비밀번호">
                    </div>
                    <div class="baseInput big">
                        <input type="password" name="user_pw1" placeholder="새 비밀번호 확인">
                    </div>
                </div>
                <div class="warnIco sm">영문/숫자/특수문자를 포함하여 8~12자리로 입력해주세요.</div>
                <div class="btnCenter full">
                    <a href="#;" class="btnType1 black" onclick="submitForm();">변경</a>
                </div>
            </div>
        </div>
        <!-- //memberWrap -->


</div>
<!-- //Container -->


<!-- 컨텐츠팝업 -->
<div class="contentPop pwSetPop">
    <div class="bg"></div>
    <div class="popIn">
        <div class="content">
            <div class="pwSetTit">비밀번호가<br />정상적으로 변경되었습니다.</div>
            <div class="btn">
                <a href="#;" class="btnType1 sm black" onclick="redirectToLogin();">확인</a>
            </div>
        </div>
    </div>
</div>
<!-- //컨텐츠팝업 -->
<script>
    let pw_checker = 0;
    $(document).ready(function(){
        // 비밀번호 형식
        $('#user_pw').on("input",function(){
            if(/^(?!((?:[A-Za-z]+)|(?:[~!@#$%^&*()_+=]+)|(?:[0-9]+))$)[A-Za-z\d~!@#$%^&*()_+=]{8,16}$/.exec($(this).val())){	// 영문/숫자/특수문자 조합 두가지 이상
                pw_checker = 1;
            }else{
                pw_checker = 0;
            }
        });
    });

    function frmSubmit(frm){
        if(pw_checker == 0){
            alert('비밀번호 형식을 확인을 해주세요');
            frm.user_pw.focus();
            return false;
        }else if(frm.user_pw.value != frm.user_pw1.value){
            alert('비밀번호가 다릅니다.');
            frm.user_pw1.focus();
            return false;
        }
        return true;
    }

    function submitForm() {
        if (frmSubmit(document.getElementById('frmMember'))) {
            $.ajax({
                type: 'POST',
                url: '/module/member/member_evn.php',
                data: $('#frmMember').serialize(),
                success: function(response) {
                    if (response.includes("success")) {
                        $('.pwSetPop').show();
                    } else {
                        alert('비밀번호 변경에 실패했습니다.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: " + textStatus + ": " + errorThrown);
                    alert('AJAX 요청 중 에러가 발생했습니다.');
                }
            });
        }
    }

    function redirectToLogin() {
        window.location.href = '/member/login.php';
    }
</script>
<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

</div>
<!-- //Wrap -->


</body>
</html>