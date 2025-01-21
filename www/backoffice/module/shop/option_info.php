<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//옵션정보
$arrInfo = getOptionInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_code']));

//DB해제
SetDisConn($dblink);

?>
<div id="admin-container">
	<? include "menu_good.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">상품옵션 수정</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 상품 관리 &nbsp;&gt;&nbsp; 상품옵션 수정</div>
	</div>

<script language="javascript">
function checkForm(frm) {
	if (frm.option_name.value==""){
		alert("옵션명을 입력해 주십시요.");
		frm.option_name.focus();
		return false;
	}
}

function deleteOptVal(idx){
	var cfm;
	cfm =false;
	cfm = confirm("해당 옵션항목을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmListHidden.idx.value = idx;
		document.frmListHidden.submit();
	}
}
</script>

<form name="frmInfo" method="post" action="good_evn.php" onSubmit="return checkForm(this)">
<input type="hidden" name="evnMode" value="editOption">
<input type="hidden" name="opt_code" value="<?=$arrInfo["list"][0]["opt_code"]?>">

		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>옵션명</th>
				<td class="space-left"><input type="text" name="option_name" style="width:200px" class="input" value="<?=$arrInfo["list"][0]["opt_name"]?>" /></td>
			</tr>
			<tr>
				<th>옵션항목</th>
				<td class="space-left">
				<a href="javascript:append_opt2();"><img src="/backoffice/images/k_add.gif" alt="추가" align="top" /></a> <a href="javascript:remove_opt2();"><img src="/backoffice/images/k_delete.gif" alt="삭제" align="top" /></a>
				(추가금액은 숫자만 입력해주세요)
				<div style="width:400px;">
					<table width="400" class="admin-table-type1" id="option_opt">
					  <colgroup>
					  <col width="200" />
					  <col width="130" />
					  </colgroup>
					  <tbody>
						<tr>
							<th class="space-center">옵션항목</th>
							<th class="space-center">추가금액</th>
						</tr>
						<?for ($i=0;$i<$arrInfo['total_opt'];$i++) {?>
						<tr><input type="hidden" name="edit_opt[]" value="<?=$arrInfo["opt"][$i]["idx"]?>">
							<td><input type="text" name="e_o_name[<?=$arrInfo["opt"][$i]["idx"]?>]" style="width:200px" value="<?=$arrInfo["opt"][$i]["opt_value"]?>" class="input" /></td>
							<td><input type="text" name="e_o_price[<?=$arrInfo["opt"][$i]["idx"]?>]" style="width:100px" value="<?=$arrInfo["opt"][$i]["opt_price"]?>" class="input" /> <a href="javascript:deleteOptVal(<?=$arrInfo["opt"][$i]["idx"]?>);"><img src="/backoffice/images/k_delete.gif" alt="삭제" align="top" /></a></td>
						</tr>
						<?}?>
					  </tbody>
					</table>
				</div>
				</td>
			</tr>
		  </tbody>
		</table>

		<div class="admin-buttons">
			<div class="cen">
				<span class="btn_pack xlarge icon"><span class="check"></span><input type="submit" value="옵션 수정" style="font-weight:bold" /></span>
			</div>
		</div>	


		</form>
	</div>
</div>

<form name="frmListHidden" method="post" action="good_evn.php">
<input type="hidden" name="evnMode" value="deleteOptionValue">
<input type="hidden" name="idx">
<input type="hidden" name="returnURL" value="<?=$_SERVER['REQUEST_URI']?>">
</form>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>
