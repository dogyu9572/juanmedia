<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("product_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory = getCategoryAll();

if($_GET['idx']) {

	$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["category_banner"], $_GET['idx']);

	$mode = "update";
	$ment = "수정";
} else {
	$mode = "insert";
	$ment = "입력";
}

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "../shop/menu_good.php"; ?>
    <div id="admin-content">
		<h2 class="admin-title">분류 배너관리</h2>

<script language="javascript">
function CheckForm(frm){
	if (frm.b_subject.value==""){
		alert("제목을 입력해 주십시요.");
		frm.b_subject.focus();
		return false;
	}
	if (frm.b_url.value==""){
		alert("링크주소를 입력해 주십시요.");
		frm.b_url.focus();
		return false;
	}
}
</script>

<form name="frmInfo" method="post" action="category_evn.php" ENCTYPE="multipart/form-data" onSubmit="return CheckForm(this)">
<input type="hidden" name="evnMode" value="<?=$mode?>">
<input type="hidden" name="cat_no" value="<?=$_GET['cat_no']?>">
<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]['idx']?>">

<div class="clfix mgb5">
  <div class="fl">&nbsp; <strong><font color="red"><?=$arrAllCategory[$_GET['cat_no']]?></font></strong></div>
</div>

<!-- 기본정보 -->
<table class="admin-table-type1">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
	<tr>
		<th>제목</th>
		<td class="space-left"><input type="text" name="c_subject" style="width:50%" maxlength="200" value="<?=stripslashes($arrInfo["list"][0]['c_subject'])?>" class="input" /></td>
	</tr>
	<tr>
		<th>배너</th>
		<td class="space-left"><input type="file" name="image_file" style="width:50%" /> </td>
	</tr>
	<tr>
		<th>링크주소</th>
		<td class="space-left"><input type="text" name="c_url" style="width:50%" maxlength="255" value="<?=stripslashes($arrInfo["list"][0]['c_url'])?>" class="input" /></td>
	</tr>
	<tr>
		<th>새창</th>
		<td class="space-left">
		<input type="radio"  id="radio1" name="c_target" value="_blank"<?=$arrInfo["list"][0]['c_target']=="_blank"?" checked":""?>><label for="radio1">_blank</label> &nbsp;&nbsp;
		<input type="radio"  id="radio2" name="c_target" value="_self"<?=$arrInfo["list"][0]['c_target']=="_self"?" checked":""?>><label for="radio1">_self</label> &nbsp;&nbsp;
		<input type="radio"  id="radio3" name="c_target" value="_top"<?=$arrInfo["list"][0]['c_target']=="_top"?" checked":""?>><label for="radio1">_top</label>
		</td>
	</tr>
	<? if($_GET['gb']=="brand") {?>
	<tr>
		<th>배너타입</th>
		<td class="space-left">
		<select name="c_type">
		<option value="1"<?=$arrInfo["list"][0]['c_type']=="1"?" selected":""?>>브랜드 상단 배너(871px * 530px)</option>
		<option value="2"<?=$arrInfo["list"][0]['c_type']=="2"?" selected":""?>>브랜드 레이어 배너</option>
		</select>
		</td>
	</tr>
	<? } else {?>
	<tr>
		<th>배너사이즈</th>
		<td class="space-left">
		<select name="c_type">
		<option value="1"<?=$arrInfo["list"][0]['c_type']=="1"?" selected":""?>>홈페이지용 (1207px * 530px)</option>
		<option value="2"<?=$arrInfo["list"][0]['c_type']=="2"?" selected":""?>>모바일용 (640px * 280px)</option>
		</select>
		</td>
	</tr>
	<? } ?>
	<tr>
		<th>정렬순서</th>
		<td class="space-left"><input type="text" name="c_sort" size="10" maxlength="10" value="<?=$arrInfo["list"][0]['c_sort']?>" class="input" /> (숫자가 높을수록 위쪽에 나타남)</td>
	</tr>
  </tbody>
</table>
<div class="admin-buttons">
	<div class="cen">
		<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="분류 배너 <?=$ment?>" style="font-weight:bold" /></span>
		<span class="btn_pack xlarge"><input type="button" value="취소" style="font-weight:bold" onclick="history.go(-1)" /></span>
	</div>
</div>

</form>
  </div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>