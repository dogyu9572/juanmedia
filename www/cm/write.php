<?php
include("../inc/header.php");
function sanitizeInput($input) {
	if (is_array($input)) {
		return array_map('sanitizeInput', $input);
	}
	return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// POST 데이터 필터링
$_POST = sanitizeInput($_POST);
?>


		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg notice">
				<div class="inner">
					<div class="enName">NOTICE BOARD</div>
					<?php include("../inc/sub_navi.php"); ?>
				</div>
			</div>
			<!-- //subTopBg -->

                <!-- pageTitle -->
			<div class="pageTitle inner">보도자료</div>
			<!-- //pageTitle -->

        <form name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
            <input type="hidden" name="boardid" value="free">
            <input type="hidden" name="returnURL" value="/cm/free.php">
            <input type="hidden" name="usehtml" value="Y">
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
								<div class="formTit">작성자<span>*</span></div>
								<div class="right">
									<div class="baseInput">
										<input type="text" name="name" id=name>
									</div>
								</div>
							</div>
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
								<div class="formTit">비밀번호</div>
								<div class="right">
									<div class="baseInput">
										<input type="password" name="pass" id="pass">
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
        if (!frm.name.value) {
            alert('작성자를 입력해 주세요.');
            frm.name.focus();
            return;
        }

        if(!frm.subject.value){
            alert('제목을 입력해 주세요.')
            frm.subject.focus();
            return;
        }

        if (!frm.pass.value) {
            alert('비밀번호를 입력해 주세요.');
            frm.pass.focus();
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



