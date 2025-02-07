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
    <form name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
        <input type="hidden" name="boardid" value="teacher">
        <input type="hidden" name="returnURL" value="<?=$_SERVER["PHP_SELF"]?>">
        <input type="hidden" name="evnMode" value="write">
        <input type="hidden" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
        <!-- subSec -->
        <div class="subSec pt0 last">
            <div class="inner">
                <div class="btnBack">
                    <a href="javascript:history.back()">뒤로</a>
                </div>

                <div class="writeForm">
                    <!-- formBox -->
                    <div class="formBox">
                        <div class="row">
                            <div class="formTit">제목<span>*</span></div>
                            <div class="right">
                                <div class="baseInput">
                                    <input type="text" name="subject" id="subject">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">내용</div>
                            <div class="right">
                                <textarea class="baseTextarea" name="contents" id="contents"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="formTit">첨부파일</div>
                            <div class="right">
                                <div class="fileAddWrap">
                                    <div class="inputFile">
                                        <div class="fileInput">
                                            <button class="fileInputButton">파일 선택</button>
                                            <input name="upfiles[]" type="file" class="fileInputHidden" onchange="javascript: document.getElementById('fileName').value = this.value">
                                        </div>
                                        <input type="text" id="fileName" class="fileInputTextbox" readonly="readonly" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">자동등록방지<span>*</span></div>
                            <div class="right">
                                <div class="autoPrevent">
                                    <div class="baseInput">
                                        <div class="imgfit"><img src="/_securimage/securimage_show.php?sid=<?php echo md5(time()) ?>" alt="image" id="siimage" style="height: 50px"></div>
                                    </div>
                                    <button type="button" class="btnRe" onclick="document.getElementById('siimage').src = '/_securimage/securimage_show.php?sid=' + Math.random(); return false"></button>
                                    <div class="baseInput">
                                        <input type="text" class="text" name="code" maxlength="6" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //formBox -->

                    <div class="btnCenter two mt">
                        <a href="#;" class="btnType1 fix" onclick="fnFrmCheck(document.form1)">저장</a>
                        <a href="free.php" class="btnType1 fix gray">취소</a>
                    </div>

                </div>

            </div>
        </div>
        <!-- //subSec -->
    </form>
</div>
<!-- //Container -->

<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

</div>
<!-- //Wrap -->
<script type="text/javascript">
    <!--
    function fnFrmCheck(frm){
        if(!frm.subject.value){
            alert('제목을 입력해 주세요.')
            frm.subject.focus();
            return;
        }

        if(!frm.code.value){
            alert("자동등록방지 보안코드를 입력해 주세요.");
            frm.code.focus();
            return;
        }

        frm.submit();
    }
</script>

</body>
</html>
