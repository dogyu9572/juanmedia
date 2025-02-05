<?php include("../inc/header.php"); ?>
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/module/member/member.lib.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/module/category/category.lib.php";

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$arrMemberInfo = getUserInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

// DB해제
SetDisConn ( $dblink );
?>
<?php $gNum="01"; $sNum="04"; $gName="미디어교육"; $sName="강사지원"; ?>
<!-- Container -->
<div class="container sub" id="container">
    <!-- subTopBg -->
    <div class="subTopBg mediaEdu">
        <div class="inner">
            <div class="enName">MEDIA EDUCATION</div>
            <div class="korName">미디어교육</div>
            <?php include("../inc/sub_navi.php"); ?>
        </div>
    </div>
    <!-- //subTopBg -->
    <!-- pageTitle -->
    <div class="pageTitle inner">강사지원</div>
    <!-- //pageTitle -->
    <!-- subSec -->
    <form name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data" onsubmit="return frmSubmit(this);">
        <input type="hidden" name="boardid" value="teacher">
        <input type="hidden" name="returnURL" value="<?=$_SERVER["PHP_SELF"]?>">
        <input type="hidden" name="evnMode" value="write">
        <input type="hidden" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
    <div class="subSec last pt0">
        <div class="expFrom">
            <div class="title">
                <div class="tit">정보입력</div>
                <div class="eq"><span>*</span> 는 필수 입력 사항입니다.</div>
            </div>
            <!-- formBox -->
            <div class="formBox">
                <!--<div class="row">
                 <div class="formTit">이름<span>*</span></div>
                 <div class="right">
                     <div class="checks_flex">
                         <label class="check"><input type="checkbox"><i></i>상설교육</label>
                         <label class="check"><input type="checkbox"><i></i>공동체교육</label>
                         <label class="check"><input type="checkbox"><i></i>미디어체험</label>
                     </div>
                     <p>중복 체크 가능합니다</p>
                 </div>
             </div>-->
                <div class="row">
                    <div class="formTit">이름<span>*</span></div>
                    <div class="right">
                        <div class="baseInput">
                            <input type="text" name="name" id="name">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="formTit">연락처<span>*</span></div>
                    <div class="right">
                        <div class="baseInput">
                            <input type="text" name="contact" id="contact">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="formTit">이메일<span>*</span></div>
                    <div class="right">
                        <div class="baseInput">
                            <input type="text" name="email" id="email">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="formTit">주소</div>
                    <div class="right">
                        <div class="baseInput">
                            <input type="text" name="address" id="address">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="formTit">제목</div>
                    <div class="right">
                        <div class="baseInput">
                            <input type="text" name="title" id="title">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="formTit">내용</div>
                    <div class="right">
                        <div class="baseInput">
                            <textarea name="content" id="content" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //formBox -->
            <div class="btnOrder two mt">
                <a href="javascript:history.back();" class="btnType1 gray">취소하기</a>
                <a href="javascript:void(0);" class="btnType1" onclick="if (frmSubmit(document.form1)) { document.form1.submit(); }">신청하기</a>
            </div>
        </div>
    </div>
</div>

<script>
    function frmSubmit(frm){
        if(frm.name.value.length < 1){
            alert("이름을 입력해주세요.");
            frm.name.focus();
            return false;
        } else if(frm.contact.value.length < 1){
            alert("연락처를 입력해주세요.");
            frm.contact.focus();
            return false;
        } else if(frm.email.value.length < 1){
            alert("이메일을 입력해주세요.");
            frm.email.focus();
            return false;
        } else {
            return true;
        }
    }
</script>
<!-- //Container -->
<?php include("../inc/quick.php"); ?>
<?php include("../inc/footer.php"); ?>
</div>
<!-- //Wrap -->
</body>
</html>