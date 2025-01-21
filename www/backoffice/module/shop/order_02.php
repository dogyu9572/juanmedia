<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale = 20;
if(!$_REQUEST['sh_date']) $_REQUEST['sh_date']="order_date";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getOrderListAdmin(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_state']), 
	$scale, $_REQUEST['offset']);


//DB해제
SetDisConn($dblink);

if($_REQUEST['pay_type']) {
	for($oo=0; $oo < count($_REQUEST['pay_type']); $oo++){
		$paytype .= $_REQUEST['pay_type'][$oo].",";
	}
}
if($_REQUEST['order_states']) {
	for($os=0; $os < count($_REQUEST['order_states']); $os++){
		$orderstate .= "/".$_REQUEST['order_states'][$os]."/,";
	}
}
?>
<script language="javascript">
function delOrder(order_no){
	var cfm;
	cfm =false;
	cfm = confirm(order_no + " 이 주문건을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmOrderListHidden.order_no.value = order_no;
		document.frmOrderListHidden.submit();
	}
}

function orderStateChange(order_no, currorderstatus, val) {
	document.frmOrderChangeHidden.order_no.value = order_no;
	document.frmOrderChangeHidden.currorderstatus.value = currorderstatus;
	document.frmOrderChangeHidden.state.value = val;
	document.frmOrderChangeHidden.submit();
}

// 기간설정
function setPeriod(pdate){
	document.frmSort.s_date.value = pdate;
	document.frmSort.e_date.value = "<?=date("Y-m-d")?>";
}
</script>

<div id="admin-container">
	<? include "menu_order.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">배송 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 주문 관리 &nbsp;&gt;&nbsp; <? if($_GET['mode']=="1") {?>배송 관리<? } else if($_GET['mode']=="2") {?>취소/교환/반품<? } else if($_GET['mode']=="3") {?>미주문<?}?></div>
	</div>

		<script language="javascript">
		function delOrder(order_no){
			var cfm;
			cfm =false;
			cfm = confirm(order_no + " 이 주문건을 삭제 하시겠습니까?");
			if(cfm==true){
				document.frmOrderListHidden.order_no.value = order_no;
				document.frmOrderListHidden.submit();
			}
		}
		</script>

		<h3 class="admin-title-middle">주문검색</h3>
		<form name="frmSort" method="get" action="<?=$_SERVER['PHP_SELF']?>">
		<input type="hidden" name="mode" value="<?=$_GET['mode']?>">
		<table  class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  <col width="140" />
		  </colgroup>
		  <tbody>
			<tr>
			  <th>주문자</th>
			  <td class="space-left">
				<select name="sw">
				<option value="all"<?=$_REQUEST['sw']=="all"?" selected":""?>>주문자명+회원ID</option>
				<option value="name"<?=$_REQUEST['sw']=="name"?" selected":""?>>주문자명</option>
				<option value="id"<?=$_REQUEST['sw']=="id"?" selected":""?>>회원ID</option>
				</select>
				<input type="text" name="sk" value="<?=$_REQUEST['sk']?>" class="input" />
			</td>
			<td rowspan="6"><span class="btn_pack xlarge"><input type="submit" style="width:100px;font-weight:bold" value=" 검 색 " /></span></td>
		  </tr>
		  <tr>
			 <th>처리일자</th>
			 <td class="space-left">
				  <input type="radio" name="sh_date" value="ipkum_date" <?=$_REQUEST["sh_date"]=="ipkum_date"?"checked":""?> checked>결제일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="shipping_date" <?=$_REQUEST["sh_date"]=="shipping_date"?"checked":""?>>출고일 &nbsp;&nbsp;
				
				<input type="text" name="s_date" id="s_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['s_date']?>" /> ~ 
				<input type="text" name="e_date" id="e_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['e_date']?>" />			
			</td>
		  </tr>
		</table>
		</form>

		<br />

		<h3 class="admin-title-middle">엑셀업로드</h3>
		<table  class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
			  <th>운송장 엑셀파일</th>
			  <td class="space-left">
					<iframe style="height:25px; width:500px;" src="/backoffice/module/shop/order_xlsx_input.php" frameborder="0"></iframe>
			  </td>				
			</td>
			
		  </tr>
		  <tr>
			 <th>엑셀양식다운로드</th>
			 <td class="space-left">
			 <a href="./ex.xls" >양식다운로드.xls</a>
			 <span style="color:#b72427;padding-left:20px;">※ 출고일자(shipping_date)는 서식 변경(텍스트)후 업로드하세요.</span>
			 <br/><span style="color:#b72427;">※ 「주문목록 엑셀로 받기」로 다운로드한 파일을 바로 운송장으로 업로드 시 파일 형식을 「Excel 통합문서」로 저장 후 업로드하시면 됩니다.</span>
			 
			</td>
		  </tr>
		</table>
		<br />

		<div class="clfix mgb5">
			<div class="fl" style="padding-top:5px;">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
			<div class="fr"><span class="btn_pack medium icon"><span class="download"></span><a href="/backoffice/module/shop/order_to_xls.php?s_date=<?=$_REQUEST['s_date']?>&e_date=<?=$_REQUEST['e_date']?>&s_price=<?=$_REQUEST['s_price']?>&e_price=<?=$_REQUEST['e_price']?>&sw=<?=$_REQUEST['sw']?>&sk=<?=$_REQUEST['sk']?>&sk2=<?=$_REQUEST['sk2']?>&order_state=<?=$_REQUEST['order_state']?>&sh_date=<?=$_REQUEST['sh_date']?>&orderstate=<?=$orderstate?>&paytype=<?=$paytype?>&mode=<?=$_REQUEST['mode']?>" target="_blank">주문목록 엑셀로 받기</a></span></div>
		</div>

		<table class="admin-table-type1">
		  <thead>
			<tr>
				<th>주문번호</th>
				<th>운송장번호</th>
				<th>상품명</th>
				<th>수량(박스)</th>
				<th>배송형태</th>
				<th>주문자</th>
				<th>수취인</th>
				<th>주문가격</th>
				<th>배송비</th>				
				<th>쿠폰사용</th>
				<th>포인트</th>
				<th>실결제</th>
				<th>주문상태</th>
				<th>결제방법</th>
				<th>주문일자</th>
				<th>결제일자</th>
				<th>출고일자</th>
				<th>주문관리</th>
			</tr>
		  </thead>
		  <tbody>
			<?
			if($arrList["total"]>0){
				//DB연결
				$dblink = SetConn($_conf_db["main_db"]);

				for($i=0;$i<$arrList["list"]["total"];$i++){
					//합계금액 계산
					$totalPrice = $arrList["list"][$i]['total_amount']+$arrList["list"][$i]['ship_amount'];

					$arrQtyList = getOrderGoodList($arrList["list"][$i]['order_no']);
			?>
				<tr>
					<td><a href="order_detail.php?order_no=<?=$arrList["list"][$i]['order_no']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><?=$arrList["list"][$i]['order_no']?></a></td>
					<td><a href="order_detail.php?order_no=<?=$arrList["list"][$i]['order_no']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><?=$arrList["list"][$i]['shipping_no']?></a></td>
					<td>
			<?
					$brtag = "";
					for($k=0;$k<$arrQtyList["total"];$k++){
						echo $brtag.$arrQtyList["list"][$k]['g_name'];
						$brtag = "<br/>";
					}
					
			?>
					</td>
					<td>
			<?
					$brtag = "";
					for($k=0;$k<$arrQtyList["total"];$k++){
						echo $brtag.$arrQtyList["list"][$k]['g_qty']."개";
						$brtag = "<br/>";
					}
					
			?>
					</td>
					<td><?=$arrList["list"][$i]['shipping_type']=="visit"?"방문출고":""?><?=$arrList["list"][$i]['shipping_type']!="visit"?"택배출고":""?></td>
					<?if($arrList["list"][$i]['order_id'] && $arrList["list"][$i]['order_id']!="guest"):?>
					<td><a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList["list"][$i]['order_id']?>"><?=$arrList["list"][$i]['order_name']?>(<?=$arrList["list"][$i]['order_id']?>)</a></td>
					<?else:?>
					<td><?=$arrList["list"][$i]['order_name']?>(비회원)</td>
					<?endif;?>
					<td><?=$arrList["list"][$i]['ship_name']?>(<?=$arrList["list"][$i]['ship_phone']?>)</td>
					<td style="color:blue;"><?=number_format($arrList["list"][$i]['total_amount'])?></td>
					<td style="color:blue;"><?=number_format($arrList["list"][$i]['ship_amount'])?></td>
					<td style="color:red;"><?=number_format($arrList["list"][$i]['coupon_amount'])?></td>
					<td style="color:red;"><?=number_format($arrList["list"][$i]['using_point'])?></td>
					<td style="color:blue;"><?=number_format($arrList["list"][$i]['pay_amount'])?>원</td>
					<td><?=$_SITE["SHOP"]["ORDER_STATE"][$arrList["list"][$i]['order_state']]?></td>
					<td><?=$_SITE["SHOP"]["PAY_TYPE"][$arrList["list"][$i]['pay_type']]?></td>
					<td><?=substr($arrList["list"][$i]['order_date'],0,10)?></td>
					<td><?=substr($arrList["list"][$i]['ipkum_date'],0,10)?></td>
					<td><?=substr($arrList["list"][$i]['shipping_date'],0,10)?></td>
					<td><span class="btn_pack small icon"><span class="delete"></span><a href="javascript:delOrder('<?=$arrList['list'][$i]['order_no']?>');">주문삭제</a></span></td>
				</tr>
			<?	
				}
				//DB해제
				SetDisConn($dblink);
			?>
			<?
			}else{
			?>
			<tr height="100">
				<td colspan="12" align="center">주문내역이 없습니다.</td>
			</tr>
			<?}?>
		  </tbody>
		</table>

		<div class="paginate">
			<?=pageNavigation($arrList["total"],$scale,$pagescale,$_GET['offset'],"sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&sk2=".$_REQUEST['sk2']."&s_date=".$_REQUEST['s_date']."&e_date=".$_REQUEST['e_date']."&s_price=".$_REQUEST['s_price']."&e_price=".$_REQUEST['e_price']."&orderstate=".$orderstate."&paytype=".$paytype."&sh_date=".$_REQUEST['sh_date']."&mode=".$_REQUEST['mode']."&shipping_type=".$_REQUEST['shipping_type']."&using_point=".$_REQUEST['using_point'])?>
		</div>

		<form name="frmOrderListHidden" method="post" action="order_evn.php">
		<input type="hidden" name="evnMode" value="delete">
		<input type="hidden" name="order_no">
		<input type="hidden" name="mode" value="<?=$_REQUEST['mode']?>">
		</form>

		<form name="frmOrderChangeHidden" method="post" action="order_evn.php">
		<input type="hidden" name="evnMode" value="order">
		<input type="hidden" name="order_no">
		<input type="hidden" name="currorderstatus">
		<input type="hidden" name="state">
		<input type="hidden" name="listURL" value="<?=$_SERVER['REQUEST_URI']?>">
		</form>

	</div>
</div><?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>