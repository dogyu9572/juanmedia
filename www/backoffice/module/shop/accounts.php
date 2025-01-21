<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("shop_accounts_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if(!$_REQUEST['s_date']){
	$_REQUEST['s_date'] = date("Y-m-d");
}

if(!$_REQUEST['e_date']){
	$_REQUEST['e_date'] = date("Y-m-d");
}

$arrList = getAccountStatus(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']));

//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);
?>
<script src="/backoffice/js/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/datePicker/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/datePicker/jquery-ui.css" />
<script>
$(function() {
// $.datepicker.setDefaults($.datepicker.regional["ko"]);
    $(".datePicker").datepicker({ 
     dateFormat: 'yy-mm-dd',
     monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
     dayNamesMin: ['일','월','화','수','목','금','토'],
	 weekHeader: 'Wk',
     changeMonth: true, //월변경가능
     changeYear: true, //년변경가능
     showMonthAfterYear: true //년 뒤에 월 표시
  });
 });
</script>

<div id="admin-container">
	<? include "menu_order.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">매출 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 주문 관리 &nbsp;&gt;&nbsp; 매출 목록</div>
	</div>

		<h3 class="admin-title-middle">매출조회</h3>
		<form name="frmSort" method="get" action="<?=$_SERVER['PHP_SELF']?>">
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
			  <th>결제사이트</th>
			  <td class="space-left">
				<select name="site">
					<option value="">전체</option>
					<option value="P"<?=$_GET['site']=="P"?" selected":""?>>PC</option>
					<option value="M"<?=$_GET['site']=="M"?" selected":""?>>모바일</option>
				</select>
			  </td>
			</tr>
			<tr>
			  <th>기간검색</th>
			  <td class="space-left">
				<input type="text" name="s_date" style="width:80px;" value="<?=$_REQUEST['s_date']?>" class="input datePicker" /> ~ <input type="text" name="e_date" style="width:80px;" value="<?=$_REQUEST['e_date']?>" class="input datePicker" />
				<input type="image" src="/backoffice/images/btn_search.gif" alt="검색" />
			  </td>
			</tr>
		  </tbody>
		</table>
		</form>

		<br />

		<table class="admin-table-type1">
		  <thead>
			<tr>
			  <th width="7%">날짜</th>
			  <th width="5%">건수</th>
			  <th width="10%">상품주문금액</th>
			  <th width="10%">사용적립금</th>
			  <th width="10%">배송비</th>
			  <th width="10%">실제결제금액</th>
			  <?foreach($_SITE["SHOP"]["PAY_TYPE"] AS $key => $val){?>
			  <th width="10%"><?=$val?></th>
			  <?}?>
		    </tr>
		  </thead>
		<!-- 데이터 루프 -->
		<?
		if($arrList["total"]>0){
			foreach ($arrList["list"] AS $list_key => $list_val){
		?>
		  <tr>
			<td><a href="order.php?sw=all&sk=&s_date=<?=$list_key?>&e_date=<?=$list_key?>"><?=$list_key?></a></td>
			<td><?=number_format($list_val[order_count])?></td>
			<td class="space-right"><?=number_format($list_val[total_amount])?></td>
			<td class="space-right"><?=number_format($list_val[using_point])?></td>
			<td class="space-right"><?=number_format($list_val[ship_amount])?></td>
			<td class="space-right"><?=number_format($list_val[pay_amount])?></td>
			<?foreach($_SITE["SHOP"]["PAY_TYPE"] AS $key => $val){?>
			<td width="10%" class="space-right"><?=number_format($arrList["p_list"][$list_key][$key][pay_amount])?></td>
			<?}?>
		  </tr>
		<?
			}
		?>
		  <!-- 합계 -->
		  <tr bgcolor="#f4f4f4">
			<td><strong>합계</strong></td>
			<td><strong><?=number_format($arrList["list_sum"][order_count])?></strong></td>
			<td class="space-right"><strong><?=number_format($arrList["list_sum"][total_amount])?></strong></td>
			<td class="space-right"><strong><?=number_format($arrList["list_sum"][using_point])?></strong></td>
			<td class="space-right"><strong><?=number_format($arrList["list_sum"][ship_amount])?></strong></td>
			<td class="space-right"><strong><?=number_format($arrList["list_sum"][pay_amount])?></strong></td>
			<?foreach($_SITE["SHOP"]["PAY_TYPE"] AS $key => $val){?>
			<td width="10%"  class="space-right"><strong><?=number_format($arrList["p_list_sum"][$key][pay_amount])?></strong></td>
			<?}?>
		  </tr>
		<?
		}else{
		?>
			<tr height="100">
				<td colspan="11">매출건이 없습니다.</td>
			</tr>
		<?}?>
		</table>

	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>