<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/one_to_one/one_to_one.lib.php";
if(!in_array("one_to_one_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getOneToOneInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[idx]));

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function checkForm(frm){
	if (frm.re_contents.value.length < 2){
		alert("내용을 입력해 주세요.");
		frm.re_contents.focus();
		return false;
	}
}
</script>
<div id="admin-container">
	<? include "../board/menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">1:1 문의관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 게시판 관리 &nbsp;&gt;&nbsp; 1:1 문의 수정</div>
	</div>


<form name="oneToOneForm" method="post" action="one_to_one_evn.php" onsubmit="return checkForm(this)">
<input type="hidden" name="evnMode" value="edit">
<input type="hidden" name="idx" value="<?=$arrInfo["list"][0][idx]?>">
<input type="hidden" name="rt_url" value="<?=$_SERVER[REQUEST_URI]?>">

		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140px" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>이름</th>
				<td class="space-left"><?=stripslashes($arrInfo["list"][0][user_name])?></td>
			</tr>
			<!--tr height="25" align="center">
				<td width="15%" bgcolor="#646464"><font color="#ffffff">질문분류</font></td>
				<td width="85%" align="left"><?=$_SITE["ONE_TO_ONE"][$arrInfo["list"][0][q_type]]?></td>
			</tr-->
			<tr>
				<th>제목</th>
				<td class="space-left"><?=stripslashes($arrInfo["list"][0][subject])?></td>
			</tr>
			<tr>
				<th>내용</th>
				<td class="space-left"><?=stripslashes(nl2br($arrInfo["list"][0][contents]))?></td>
			</tr>
			<tr>
				<th>답변상태</th>
				<td class="space-left">
					<select name="status">
					<option value="N" style="color:red"<?=$arrInfo["list"][0][status]=="N"?" selected":""?>>접수중</option>
					<option value="Y" style="color:blue"<?=$arrInfo["list"][0][status]=="Y"?" selected":""?>>답변완료</option>
					</select></td>
			</tr>
			<tr>
				<th>답변내용</th>
				<td class="space-left"><textarea name="re_contents"  style="width: 95%;" rows="10" class="textarea"><?=stripslashes($arrInfo["list"][0][re_contents])?></textarea></td>
			</tr>
		  </tbody>
		</table>
		
		<div class="admin-buttons">
			<div class="cen">
				<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="답변완료" style="font-weight:bold" /></span>
			</div>
		</div>	
</form>
	</div>
</div>

<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>