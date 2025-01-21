<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/fckeditor/fckeditor.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo("tbl_category_info", $_REQUEST['idx']);
$arrLevelInfo = getArticleList($GLOBALS["_conf_tbl"]["member_level"], 0, 0, " order by level_no desc ");
//_DEBUG($arrInfo);

//DB해제
SetDisConn($dblink);
?>
<script>
function CheckForm(f) {
	try{ f_header.outputBodyHTML(); } catch(e){ }
	try{ f_footer.outputBodyHTML(); } catch(e){ }
}
</script>

<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">게시판 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 게시판 관리 &nbsp;&gt;&nbsp; 게시판 수정</div>
	</div>

<script language="javascript" src="/common/util.js"></script>

<form name="frmBBS" method="post" action="category_evn.php" onSubmit="return CheckForm(this)">
<input type="hidden" name="evnMode" value="editCategoryInfo">
<input type="hidden" name="idx" value="<?=$_REQUEST['idx']?>">

<div class="clfix mgb5">
  <div class="fl">&nbsp;<strong><font color="red"><?=$arrInfo["list"][0]['boardid']?> 수정</font></strong></div>
  <div class="fr"><a href="board.php"><img src="/backoffice/images/k_list.gif" alt="목록" /></a></div>
</div>
<table class="admin-table-type1">
  <colgroup>
  <col width="15%" />
  <col width="35%" />
  <col width="15%" />
  <col width="35%" />
  </colgroup>
  <tbody>
	<tr>
	  <th>게시판ID</td>
	  <td class="space-left"><?=$arrInfo["list"][0]['categoryid']?></td>
	  <th>게시판스킨</td>
	  <td class="space-left"><select name="f_skin">
		<?
		$dirhandle = opendir($_SITE["CATEGORY_PATH"]."/skin");
		while($filename = readdir($dirhandle)){
		  if($filename == '.' || $filename == '..'){
		  }else{
			  if($filename==$arrInfo["list"][0]['skin']){
				echo "<option value='$filename' selected>$filename</option>\n";
			  }else{
				echo "<option value='$filename'>$filename</option>\n";
			  }
		  }
		}
		?></select></td>
	</tr>
	<tr>
	  <th>게시판명</td>
	  <td colspan="3" class="space-left"><input type="text" name="f_categoryname" value="<?=$arrInfo["list"][0]['categoryname']?>" style="width:200px;" class="input" /></td>
	</tr>
  </tbody>
</table>

<div class="admin-buttons">
	<div class="cen">
		<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="정보수정" style="font-weight:bold" /></span>
		<span class="btn_pack xlarge"><input type="reset" value="수정취소" style="font-weight:bold;color:#888" /></span>
	</div>
</div>	
</form>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>