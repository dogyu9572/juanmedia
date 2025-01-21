<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale = 100;
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
$(function(){
    $(".check_all").click(function(){		
        var chk = $(this).is(":checked");//.attr('checked');
        if(chk) $(".chk_list").prop('checked', true);
        else  $(".chk_list").prop('checked', false);
    });
});

function getSelections(str){
	var ss = "0";

	var rows = $('input:checkbox[name=chk_list]:checked');
	
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		//ss.push(row.idx);
		ss += ","+row.value;
	}
	if(rows.length>0){
		//alert(ss);
		orderCheck(ss,str);
	}else{
		alert('선택된 항목이 없습니다.');
	}	
}
function orderCheck(val,stat){		
	var conTxt = "출고처리 하시겠습니까?";
	if(stat=="6"){conTxt = "출고취소처리 하시겠습니까?";}
	if(confirm(conTxt)) {
		$.post("/module/shop/ajax_order_admin.php", { evnMode: "state", g_idx: val, states: stat },
		function(data){		
//			alert(data);
			if(data=="OK"){
				location.reload();		
			}
		});
	}
}
</script>

<div id="admin-container">
	<? include "menu_order.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">출고 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 주문 관리 &nbsp;&gt;&nbsp; <? if($_GET['mode']=="1") {?>출고 관리<? } else if($_GET['mode']=="2") {?>취소/교환/반품<? } else if($_GET['mode']=="3") {?>미주문<?}?></div>
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
			 <th>처리일자</th>
			 <td class="space-left"><input type="radio" name="sh_date" value="order_date" <?=$_REQUEST["sh_date"]=="order_date"?"checked":""?>>주문일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="ipkum_date" <?=$_REQUEST["sh_date"]=="ipkum_date"?"checked":""?>>결제일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="shipping_date" <?=$_REQUEST["sh_date"]=="shipping_date"?"checked":""?>>출고일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="finish_date" <?=$_REQUEST["sh_date"]=="finish_date"?"checked":""?>>거래완료일 &nbsp;&nbsp;
				<input type="text" name="s_date" id="s_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['s_date']?>" /> ~ <input type="text" name="e_date" id="e_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['e_date']?>" />
				&nbsp;
				
			</td>
			<td rowspan="6"><span class="btn_pack xlarge"><input type="submit" style="width:100px;font-weight:bold" value=" 검 색 " /></span></td>
		  </tr>
		  <tr>
			<th>결제방식</th>
			<td class="space-left">
				 <input type="radio" name="pay_type" value="" <?=!$_REQUEST["pay_type"]?"checked":""?>>전체 &nbsp;&nbsp;
				 <input type="radio" name="pay_type" value="card" <?=$_REQUEST["pay_type"]=="card"?"checked":""?>>신용카드 &nbsp;&nbsp;
				 <input type="radio" name="pay_type" value="DirectBank" <?=$_REQUEST["pay_type"]=="DirectBank"?"checked":""?>>계좌이체 &nbsp;&nbsp;
				 <input type="radio" name="pay_type" value="cash" <?=$_REQUEST["pay_type"]=="cash"?"checked":""?>>무통장 &nbsp;&nbsp;<!--
				 <input type="radio" name="pay_type" value="cacaopay" <?=$_REQUEST["pay_type"]=="cacaopay"?"checked":""?>>카카오페이 &nbsp;&nbsp;
				 <input type="radio" name="pay_type" value="naverpay" <?=$_REQUEST["pay_type"]=="naverpay"?"checked":""?>>네이버페이 &nbsp;&nbsp;
				 -->
			</td>
		  </tr>
		  <tr>
			<th>배송형태</th>
			<td class="space-left">
				<label><input type="radio" name="shipping_type" value="" <?=!$_REQUEST["shipping_type"]?"checked":""?>>전체</label>&nbsp;&nbsp;
				<label><input type="radio" name="shipping_type" value="visit" <?=$_REQUEST["shipping_type"]=="visit"?"checked":""?> >방문출고</label>&nbsp;&nbsp;
				<label><input type="radio" name="shipping_type" value="delivery" <?=$_REQUEST["shipping_type"]=="delivery"?"checked":""?>>택배출고</label>
			</td>
		  </tr>
		  <tr>
			<th>출고여부</th>
			<td class="space-left">
				<label><input type="radio" name="shipping_state" value="" <?=!$_REQUEST["shipping_state"]?"checked":""?>>전체</label>&nbsp;&nbsp;
				<label><input type="radio" name="shipping_state" value="8" <?=$_REQUEST["shipping_state"]=="8"?"checked":""?> >출고</label>
				<label><input type="radio" name="shipping_state" value="6" <?=$_REQUEST["shipping_state"]=="6"?"checked":""?> >입금확인</label>
			</td>
		  </tr>
		</table>
		</form>

		<br />

		<div class="clfix mgb5">
			<div class="fl" style="padding-top:5px;">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
			<div class="fr"><span class="btn_pack medium icon"><span class="download"></span><a href="/backoffice/module/shop/order_to_xls.php?s_date=<?=$_REQUEST['s_date']?>&e_date=<?=$_REQUEST['e_date']?>&s_price=<?=$_REQUEST['s_price']?>&e_price=<?=$_REQUEST['e_price']?>&sw=<?=$_REQUEST['sw']?>&sk=<?=$_REQUEST['sk']?>&sk2=<?=$_REQUEST['sk2']?>&order_state=<?=$_REQUEST['order_state']?>&sh_date=<?=$_REQUEST['sh_date']?>&orderstate=<?=$orderstate?>&paytype=<?=$paytype?>&mode=<?=$_REQUEST['mode']?>" target="_blank">주문목록 엑셀로 받기</a></span></div>
		</div>

		<table class="admin-table-type1">
		  <thead>
			<tr>
				<th><input type="checkbox" class="check_all" value="Y" /></th>
				<th>주문번호</th>
				<th>상품명</th>
				<th>수량(박스)</th>
				<th>주문자</th>
				<th>배송형태</th>
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
					<td><input type="checkbox" class="chk_list" value="<?=$arrList["list"][$i]['idx']?>" name="chk_list" /></td>
					<td><a href="order_detail.php?order_no=<?=$arrList["list"][$i]['order_no']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><?=$arrList["list"][$i]['order_no']?></a></td>
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
					<?if($arrList["list"][$i]['order_id'] && $arrList["list"][$i]['order_id']!="guest"):?>
					<td><a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList["list"][$i]['order_id']?>"><?=$arrList["list"][$i]['order_name']?>(<?=$arrList["list"][$i]['order_id']?>)</a></td>
					<?else:?>
					<td><?=$arrList["list"][$i]['order_name']?>(비회원)</td>
					<?endif;?>
					<td><?=$arrList["list"][$i]['shipping_type']=="visit"?"방문출고":""?><?=$arrList["list"][$i]['shipping_type']!="visit"?"택배출고":""?></td>
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
				<td colspan="16" align="center">주문내역이 없습니다.</td>
			</tr>
			<?}?>
		  </tbody>
		</table>
		<div style="padding-top:10px;">
		<span class="btn_pack medium icon"><span class="check"></span><a href="javascript:void();" onclick="getSelections('8')">선택출고</a></span>
		<span class="btn_pack medium icon"><span class="delete"></span><a href="javascript:void();" onclick="getSelections('6')">선택출고취소</a></span>
		</div>

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