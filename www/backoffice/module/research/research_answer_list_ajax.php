<?
@session_start();
header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/research/research.lib.php";
if(!in_array("research_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//이벤트의 게시물 제목 목록
$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["research_info"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[r_idx]));
$arrQInfo = getArticleInfo($GLOBALS["_conf_tbl"]["research_question"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[rq_idx]));
$arrList = getResearchAnswerList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[r_idx]),mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[rq_idx]),"");

//DB해제
SetDisConn($dblink);

?>
<form name="frmInfo" method="post" action="research_evn.php">
<input type="hidden" name="evnMode" value="editAnswer">
<input type="hidden" name="r_idx" value="<?=mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[r_idx])?>">
<input type="hidden" name="rq_idx" value="<?=mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[rq_idx])?>">


<!-- 설문항목 -->
<h3 class="admin-title-middle"><?=$arrInfo["list"][0][subject]?> > <?=$arrQInfo["list"][0][question]?> > 설문항목 &nbsp; <a href="javascript:append_2();"><img src="/backoffice/images/k_add.gif" alt="추가" align="top" /></a> <a href="javascript:remove_2();"><img src="/backoffice/images/k_delete.gif" alt="삭제" align="top" /></a></h3>
<table id="research_answer" class="admin-table-type1">
  <tbody>
	<tr>
		<th class="space-left">설문답변</th>
	</tr>
	<?
	if($arrList["total"] > 0){
		for($i=0;$i<$arrList["total"];$i++){
	?>
	<tr>
		<td class="space-left"><label for="delAnswer<?=$i?>">삭제</label><input type="checkbox" name="del_answer[]" id="delAnswer<?=$i?>" value="<?=$arrList["list"][$i][idx]?>">
		<input type='text' name='answer_list[<?=$arrList["list"][$i][idx]?>]' style='width:300px' class='input' maxlength='100' value="<?=stripslashes($arrList["list"][$i][answer])?>" /></td>				
	</tr>
	<?
		}
	}
	?>
  </tbody>
</table>

<div class="admin-buttons">
	<div class="cen">
		<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="답변항목 수정" style="font-weight:bold" /></span>
	</div>
</div>

</form>