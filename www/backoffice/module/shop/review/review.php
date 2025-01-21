<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/review/review.lib.php";
if(!in_array("review_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$sclae="20";

$arrList = getReviewListAll(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), $scale, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['offset']));
//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function delReview(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 독서리뷰를 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmReviewHidden.idx.value = idx;
		document.frmReviewHidden.submit();
	}
}
</script>
<table border="0" width="100%" cellpadding="3" cellspacing="0" align="center">
  <tr>
    <td><b>전체 : <?=number_format($arrList['total'])?> 개</b></td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
<tr height="25" align="center" bgcolor="#646464">
	<td width="5%"><font color="#ffffff">No.</font></td>
	<td width="10%"><font color="#ffffff">이름</font></td>
	<td width="60%"><font color="#ffffff">제목</font></td>
	<td width="15%"><font color="#ffffff">작성일</font></td>
	<td width="10%"><font color="#ffffff">수정 | 삭제</font></td>
</tr>
<?if($arrList['list']['total'] > 0):?>

<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
<tr height="25" align="center">
  <td><?=number_format($arrList['total']-$i-$offset)?></td>
  <td><?=$arrList['list'][$i]['user_name']?><?if($arrList['list'][$i]['user_id']):?> (<a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>"><?=$arrList['list'][$i]['user_id']?></a>)<?endif;?></td>
  <td align="left"><a href="review_info.php?o_type=<?=$arrList['list'][$i]['o_type']?>&idx=<?=$arrList['list'][$i]['idx']?>"><?=stripslashes($arrList['list'][$i]['subject'])?></a></td>
  <td><?=$arrList['list'][$i]['wdate']?></td>
  <td class="b02"><a href="review_info.php?o_type=<?=$arrList['list'][$i]['o_type']?>&idx=<?=$arrList['list'][$i]['idx']?>">수정</a> | <a href="javascript:delReview('<?=$arrList['list'][$i]['idx']?>');">삭제</a></td>
</tr>
<tr>
  <td colspan="10" height="1" bgcolor="646464"></td>
</tr>
<?}?>

<?else:?>
<tr height="100" align="center">
  <td width="100%" colspan="8" >등록된 독서리뷰건이 없습니다.</td>
</tr>
<tr>
  <td colspan="10" height="1" bgcolor="646464"></td>
</tr>
<?endif;?>
</table>

<br />
<table border="0" cellpadding="0" cellspacing="1" width="100%">
<tr height="25" align="center">
  <td><?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"")?></td>
</tr>
</table>

<form name="frmReviewHidden" method="post" action="review_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
<input type="hidden" name="rt_url" value="<?=$_SERVER['REQUEST_URI']?>">
</form>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>