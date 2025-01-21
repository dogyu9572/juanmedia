<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

?>
<div id="admin-container">
	<? include "menu_good.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">상품옵션 등록</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 상품 관리 &nbsp;&gt;&nbsp; 상품옵션 등록</div>
	</div>

<script language="javascript">
function checkForm(frm) {
	if (frm.option_name.value==""){
		alert("옵션명을 입력해 주십시요.");
		frm.option_name.focus();
		return false;
	}
	if (frm.option_name_0.value==""){
		alert("옵션항목을 입력해 주십시요.");
		frm.option_name_0.focus();
		return false;
	}

}
</script>

<form name="frmInfo" method="post" action="good_evn.php" onSubmit="return checkForm(this)">
<input type="hidden" name="evnMode" value="insertOption">

		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>옵션명</th>
				<td class="space-left"><input type="text" name="option_name" style="width:200px" value="" class="input" placeholder="예) 색상" /></td>
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
						<!-- <tr>
							<td><input type="text" name="opn_name" style="width:200px" value="" class="input" placeholder="예) 블랙" /></td>
							<td><input type="text" name="opn_price" style="width:100px" value="" class="input" /></td>
						</tr> -->
					  </tbody>
					</table>
				</div>
				</td>
			</tr>
		  </tbody>
		</table>

		<div class="admin-buttons">
			<div class="cen">
				<span class="btn_pack xlarge icon"><span class="check"></span><input type="submit" value="옵션등록" style="font-weight:bold" /></span>
			</div>
		</div>	


		</form>
	</div>
</div>
<script>
append_opt2();
</script>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>
