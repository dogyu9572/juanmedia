<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/banner/subbanner.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

if(!in_array("banner_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo("tbl_sub_banner", $_GET['idx']);

$arrCategoryList = getCategoryList(1);

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">서브이미지 수정</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 서브이미지 관리 &nbsp;&gt;&nbsp; 서브이미지 수정</div>
	</div>

<script language="javascript">
function CheckForm(frm){
	if (frm.b_subject.value==""){
		alert("타이틀을 입력해 주십시요.");
		frm.b_subject.focus();
		return false;
	}
}

function chkType(txt) {
	if(txt == "5") {
		document.getElementById("b_brand").style.display="";
	} else {
		document.getElementById("b_brand").style.display="none";
	}
}
</script>

<form name="frmInfo" method="post" action="sub_banner_evn.php" ENCTYPE="multipart/form-data" onSubmit="return CheckForm(this)">
<input type="hidden" name="evnMode" value="update">
<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]['idx']?>">


<!-- 기본정보 -->
<table class="admin-table-type1">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
	<tr>
		<th>서브이미지 타입</th>
		<td class="space-left">
		<select name="b_type">
			<option value="1"<?=$arrInfo["list"][0]['b_type']=="1"?" selected":""?>>회사소개</option>
			<option value="2"<?=$arrInfo["list"][0]['b_type']=="2"?" selected":""?>>CCM</option>
			<option value="3"<?=$arrInfo["list"][0]['b_type']=="3"?" selected":""?>>브랜드&제품</option>
			<option value="4"<?=$arrInfo["list"][0]['b_type']=="4"?" selected":""?>>사회공헌</option>
			<option value="5"<?=$arrInfo["list"][0]['b_type']=="5"?" selected":""?>>홍보센터</option>
			<option value="6"<?=$arrInfo["list"][0]['b_type']=="6"?" selected":""?>>고객센터</option>
		</select>
		</td>
	</tr>
	<tr>
		<th>언어</th>
		<td class="space-left">
		<select name="b_lang">
			<option value="0"<?=$arrInfo["list"][0]['b_lang']=="0"?" selected":""?>>국문</option>
			<option value="1"<?=$arrInfo["list"][0]['b_lang']=="1"?" selected":""?>>영문</option>
		</select>
		</td>
	</tr>
	<tr>
		<th>타이틀</th>
		<td class="space-left"><input type="text" name="b_subject" style="width:50%" maxlength="200" value="<?=stripslashes($arrInfo["list"][0]['b_subject'])?>" class="input" /></td>
	</tr>
	
	<tr>
		<th>서브타이틀</th>
		<td class="space-left"><input type="text" name="b_url" style="width:50%" maxlength="255" value="<?=stripslashes($arrInfo["list"][0]['b_url'])?>" class="input" /></td>
	</tr>
	<tr>
		<th>메인이미지</th>
		<td class="space-left"><input type="file" name="image_file" style="width:50%" /> </td>
	</tr>
	<tr style="display:none;">
		<th>새창</th>
		<td class="space-left">
		<input type="radio"  id="radio1" name="b_target" value="_blank"<?=$arrInfo["list"][0]['b_target']=="_blank"?" checked":""?>><label for="radio1">_blank (새창)</label> &nbsp;&nbsp;
		<input type="radio"  id="radio2" name="b_target" value="_self"<?=$arrInfo["list"][0]['b_target']=="_self"?" checked":""?>><label for="radio1">_self (현재페이지)</label> &nbsp;&nbsp;
		<input type="radio"  id="radio3" name="b_target" value="_top"<?=$arrInfo["list"][0]['b_target']=="_top"?" checked":""?>><label for="radio1">_top</label>
		</td>
	</tr>
	<tr>
		<th>보이기</th>
		<td class="space-left">
		<input type="radio"  id="radio4" name="b_show" value="Y"<?=$arrInfo["list"][0]['b_show']=="Y"?" checked":""?>><label for="radio4">보임</label> &nbsp;&nbsp;
		<input type="radio"  id="radio5" name="b_show" value="N"<?=$arrInfo["list"][0]['b_show']=="N"?" checked":""?>><label for="radio5">숨김</label>
		</td>
	</tr>
	
	<tr>
		<th>정렬순서</th>
		<td class="space-left"><input type="text" name="b_sort" size="10" maxlength="10" value="<?=$arrInfo["list"][0]['b_sort']?>" class="input" /> (숫자가 높을수록 위쪽에 나타남)</td>
	</tr>
  </tbody>
</table>
<div class="admin-buttons">
	<div class="cen">
		<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="메인이미지 수정" style="font-weight:bold" /></span>
	</div>
</div>

</form>
  </div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>