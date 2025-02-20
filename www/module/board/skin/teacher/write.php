<!-- pageTitle -->
<div class="pageTitle inner">강사모집</div>
<!-- //pageTitle -->
<form name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
	<input type="hidden" name="boardid" value="teacher">
	<input type="hidden" name="returnURL" value="/edu/teacher.php">
    <input type="hidden" name="etc_1" value="Y">
    <input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
	<input type="hidden" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
    <?if($_REQUEST['mode']=="reply"):?>
        <input type="hidden" name="evnMode" value="reply">
    <?elseif($_REQUEST['mode']=="user_modify"):?>
        <input type="hidden" name="evnMode" value="modify">
    <?else:?>
        <input type="hidden" name="evnMode" value="write">
    <?endif;?>
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
								<input type="text" name="name" id="name" value="<?=$arrBoardArticle["list"][0]['name']?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="formTit">제목<span>*</span></div>
						<div class="right">
							<div class="baseInput">
								<input type="text" name="subject" id="subject" value="<?=stripslashes($arrBoardArticle["list"][0]['subject'])?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="formTit">내용</div>
						<div class="right">
							<textarea class="baseTextarea" name="contents" id="contents"><?=stripslashes($arrBoardArticle["list"][0]['contents'])?></textarea>
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
                            <?
                            if($arrBoardArticle["total_files"]>0 && $_REQUEST['mode']=="user_modify"){
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
					<a href="teacher.php" class="btnType1 fix gray">취소</a>
				</div>

			</div>

		</div>
	</div>
	<!-- //subSec -->
</form>

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