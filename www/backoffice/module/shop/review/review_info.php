<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/review/review.lib.php";
if(!in_array("review_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getReviewInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));

//상품 정보
$arrGoodInfo = getGoodInfo($arrInfo["list"][0]['g_idx']);

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function checkForm(frm){

}
</script>

<!-- S 쓰기페이지 -->
<form name="oneToOneForm" method="post" action="review_evn.php" onsubmit="return checkForm(this)">
<input type="hidden" name="evnMode" value="edit">
<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]['idx']?>">

<table border="0" cellpadding="3" cellspacing="1" width="800">
	<tr height="25" align="center">
		<td width="15%" bgcolor="#646464"><font color="#ffffff">상품</font></td>
		<td width="85%" align="left"><a href="/shop.php?goPage=GoodDetail&g_code=<?=$arrGoodInfo["list"][0]['g_code']?>"><?=stripslashes($arrGoodInfo["list"][0]['g_name'])?></a><br /></td>
	</tr>
	<tr height="25" align="center">
		<td width="15%" bgcolor="#646464"><font color="#ffffff">이름</font></td>
		<td width="85%" align="left"><?=stripslashes($arrInfo["list"][0]['user_name'])?>(<?=stripslashes($arrInfo["list"][0]['user_id'])?>)</td>
	</tr>
	<tr height="25" align="center">
		<td width="15%" bgcolor="#646464"><font color="#ffffff">추천</font></td>
		<td align="left">
		<input type="radio" id="review_point_1" name="review_point" value="1"<?=$arrInfo["list"][0]['review_point']=="1"?" checked":""?>><label for="review_point_1">비추천</label> 
		<input type="radio" id="review_point_3" name="review_point" value="3"<?=$arrInfo["list"][0]['review_point']=="3"?" checked":""?>><label for="review_point_3">추천</label> 
		<input type="radio" id="review_point_5" name="review_point" value="5"<?=$arrInfo["list"][0]['review_point']=="5"?" checked":""?>><label for="review_point_5">강력추천</label></td>
	</tr>
	<tr height="25" align="center">
		<td width="15%" bgcolor="#646464"><font color="#ffffff">제목</font></td>
		<td width="85%" align="left"><input name="subject" type="text" class="input" size="77" value="<?=stripslashes($arrInfo["list"][0]['subject'])?>"</td>
	</tr>
	<tr height="100" align="center">
		<td width="15%" bgcolor="#646464"><font color="#ffffff">내용</font></td>
		<td width="85%" align="left"><textarea name="contents" cols="75" rows="12" class="input"><?=stripslashes($arrInfo["list"][0]['contents'])?></textarea></td>
	</tr>
	<tr height="25" align="center">
		<td width="15%" bgcolor="#646464"><font color="#ffffff">작성일시</font></td>
		<td width="85%" align="left"><?=stripslashes($arrInfo["list"][0]['wdate'])?></td>
	</tr>
	<tr height="25" align="center">
		<td width="15%" bgcolor="#646464"><font color="#ffffff">IP</font></td>
		<td width="85%" align="left"><?=stripslashes($arrInfo["list"][0]['ip'])?></td>
	</tr>
	<tr height="100" align="center">
		<td width="100%" colspan="2"><input type="submit" value="정보수정"></td>
	</tr>
</table>

</form>
<!-- E 쓰기페이지 -->

<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>