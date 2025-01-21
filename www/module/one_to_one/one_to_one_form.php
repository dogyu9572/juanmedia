<?
//로그인확인
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getOneToOneList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $scale, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[offset]));

$arrGoodList = getGoodListBaseNFile("", " A.sort_num DESC, A.idx DESC ", "", "", 0, 0,"Y");

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function checkForm(frm){
/*
	if (frm.q_type.value==""){
		alert("분류를 선택해 주세요.");
		frm.q_type.focus();
		return false;
	}
*/
	if (frm.subject.value.length < 2){
		alert("제목을 입력해 주세요.");
		frm.subject.focus();
		return false;
	}

	try{ contents.outputBodyHTML(); } catch(e){ }
}
</script>
	<? include $_SERVER['DOCUMENT_ROOT'] . "/include/left_mypage.php"; ?>
	<div id="content">		
		<div id="sub_title">
			<div class="path">Home &gt; 마이페이지 &gt; <strong>나의 Q&amp;A</strong></div>
		</div>
		<div id="con_area">		

		<form name="oneToOneForm" method="post" action="/module/one_to_one/one_to_one_evn.php" onsubmit="return checkForm(this)">
		<input type="hidden" name="evnMode" value="write">

		<div class="board_write">
		<fieldset>
			<legend>글 작성</legend>
			<p class="top">
				<label for="user_name" class="title">작성자</label>
				<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>(<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>)
			</p>
			<p>
				<label for="title" class="title">상품선택</label>
				<select name="g_idx">
				<option value=""> == 선 택 ==</option>
				<?if($arrGoodList['list']['total'] > 0):
				for ($i=0;$i<$arrGoodList['list']['total'];$i++) {?>
				<option value="<?=$arrGoodList['list'][$i]['idx']?>"><?=stripslashes($arrGoodList['list'][$i]['g_name'])?></option>
				<? } endif; ?>
				</select>
			</p>
			<p>
				<label for="title" class="title">제목</label>
				<input type="text" name="subject" id="title" title="제목" class="input" style="width:90%" />
			</p>
			<p>
				<label for="title" class="title">내용</label>
				<? $edit_content = stripslashes($arrBoardArticle["list"][0][contents]); 
				$edit_height="240"; 
				include $_SERVER['DOCUMENT_ROOT'] . "/webedit/Editor.html";
				?>		
			</p>
		</fieldset>
	</div>

	<div class="buttons">
		<div class="cen">
			<span class="btn_pack large"><input type="submit" value="확인" /></span>
			<span class="btn_pack large"><a href="/shop.php?goPage=MyQna">취소</a></span>
		</div>
	</div>
	</form>
		</div>
	</div>
