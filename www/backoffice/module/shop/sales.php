<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "../admin/menu.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("homepage_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$_GET['mode'] = "4";	//	$que_where .= " and A.order_state in (9) ";

$scale = 10;
if($_GET['page_size']){
	$scale = $_GET['page_size'];	
}
$pagescale = 10;

if(!$_REQUEST['sh_date']) $_REQUEST['sh_date']="order_date";

if(!$_REQUEST['s_date']){
	$_REQUEST['s_date'] = date("Y-m-d");
}
if(!$_REQUEST['e_date']){
	$_REQUEST['e_date'] = date("Y-m-d");
}

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getOrderListAll(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_state']), 
	$scale, $_REQUEST['offset']);
$arrSum = getOrderSalesSum(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date'])
);

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
	var conTxt = "환불처리 하시겠습니까?";
	if(stat=="2"){conTxt = "환불취소처리 하시겠습니까?";}
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
function delOrder(order_no){
	var cfm;
	cfm =false;
	cfm = confirm(order_no + " 이 주문건을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmOrderListHidden.order_no.value = order_no;
		document.frmOrderListHidden.submit();
	}
}
function fnSearch(frm){
	frm.submit();
}
</script>
<div class="container">

	<div class="title">매출관리</div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
		<input type="hidden" name="mode" value="<?=$_GET['mode']?>">
	<div class="inbox top_search">
		<dl>
			<dt>주문경로</dt>
			<dd>
				<select name="coupon_type" id="coupon_type" style="width:120px;">
					<option value="">전체</option>					
					<option value="order" <?=$_GET['coupon_type']=="order"?" selected":""?>>PC</option>
					<option value="goods" <?=$_GET['coupon_type']=="goods"?" selected":""?>>MO</option>					
				</select>
			</dd>
		</dl>
		<dl>
			<dt>결제방법</dt>
			<dd>
				<select name="coupon_type" id="coupon_type" style="width:120px;">
					<option value="">전체</option>					
					<option value="order" <?=$_GET['coupon_type']=="order"?" selected":""?>>신용카드</option>
					<option value="goods" <?=$_GET['coupon_type']=="goods"?" selected":""?>>무통장입금</option>	
					<option value="goods" <?=$_GET['coupon_type']=="goods"?" selected":""?>>네이버페이</option>	
					<option value="goods" <?=$_GET['coupon_type']=="goods"?" selected":""?>>카카오페이</option>	
					<option value="goods" <?=$_GET['coupon_type']=="goods"?" selected":""?>>삼성페이</option>					
				</select>
			</dd>
		</dl>
		<!--
		<dl>
			<dt>거래증빙</dt>
			<dd>
				<select name="coupon_type" id="coupon_type" style="width:120px;">
					<option value="">전체</option>					
					<option value="order" <?=$_GET['coupon_type']=="order"?" selected":""?>>Y</option>					
				</select>
			</dd>
		</dl>
		-->
		<dl>
			<dt>주문일시</dt>
			<dd>
				<input type="text" class="w1 datepicker" name="s_date" value="<?=$_REQUEST['s_date']?>" maxlength="10" /><em>&nbsp;~&nbsp;</em>
				<input type="text" class="w1 datepicker" name="e_date" value="<?=$_REQUEST['e_date']?>" maxlength="10" />
			</dd>
		</dl>			
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:100px;">
					<!--<option value="">전체　　</option>-->
					<option value='name'<?=$_GET['sw']=="name"?" selected='selected'":""?>>이름</option>
					<option value='id'<?=$_GET['sw']=="id"?" selected='selected'":""?>>아이디</option>
					<option value='ono'<?=$_GET['sw']=="ono"?" selected='selected'":""?>>주문번호</option>
					<option value='tel'<?=$_GET['sw']=="tel"?" selected='selected'":""?>>휴대폰번호</option>
				</select>	
				<input type="text" name="sk" value="<?=$_GET['sk']?>" />
				<button type="button" class="search" onclick="fnSearch(document.form1)">검색</button>
			</dd>
		</dl>
	</div>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
			</div>
			<div class="count">
				<select name="page_size" onchange="document.form1.submit()" style="width:60px;">
					<option value="100"	<?=$scale=="100"?'selected':""?>>100</option>
					<option value="50"	<?=$scale=="50"?'selected':""?>>50</option>
					<option value="40"	<?=$scale=="40"?'selected':""?>>40</option>
					<option value="30"	<?=$scale=="30"?'selected':""?>>30</option>
					<option value="20"	<?=$scale=="20"?'selected':""?>>20</option>
					<option value="15"	<?=$scale=="15"?'selected':""?>>15</option>
					<option value="10"	<?=$scale=="10"?'selected':""?>>10</option>
				</select>
				개씩 보기
			</div>
			</form>
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w8p">
						<col class="w12p">
						<col class="w6p">
						<col class="w12p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w8p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">주문일시</th>
							<th class="pc_vw">주문번호</th>
							<th class="pc_vw">주문자(ID)</th>
							<th class="pc_vw">주문상품</th>
							<th class="pc_vw">총 상품금액</th>
							<th class="pc_vw">배송비</th>
							<th class="pc_vw">할인금액</th>							
							<th class="pc_vw">실결제금액</th>
							<th class="pc_vw">배송비 제외<br/> 총 주문금액</th>
						</tr>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							//합계금액 계산
							$totalPrice = $arrList["list"][$i]['total_amount']+$arrList["list"][$i]['ship_amount'];

							$arrQtyList = getOrderGoodList($arrList["list"][$i]['order_no']);
							
							$trColor="#fffff";
							if($arrList["list"][$i]['order_state']=="2" || $arrList["list"][$i]['order_state']=="3" || $arrList["list"][$i]['order_state']=="4" || $arrList["list"][$i]['order_state']=="5"){
								$trColor="#f1dede";
							}
							$dis_amount[$i] = $arrList['list'][$i]['coupon_amount'] + $arrList['list'][$i]['using_point'];
					?>
						<tr>
							<td><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>		
							<td><i class="mo_vw">주문일시</i><?=$arrList['list'][$i]['order_date']?></td>
							<td><i class="mo_vw">주문번호</i><a href="order_detail.php?order_no=<?=$arrList["list"][$i]['order_no']?>"><?=$arrList["list"][$i]['order_no']?></a></td>
							<td><i class="mo_vw">주문자(ID)</i><a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList["list"][$i]['order_id']?>"><?=$arrList["list"][$i]['order_name']?>(<?=$arrList["list"][$i]['order_id']?>)</a></td>
							<td><i class="mo_vw">주문상품</i><?=$arrList['list'][$i]['order_summary']?></td>
							<td><i class="mo_vw">총 상품금액</i><?=number_format($arrList['list'][$i]['total_amount'])?></td>
							<td><i class="mo_vw">배송비</i><?=number_format($arrList['list'][$i]['ship_amount'])?></td>
							<td><i class="mo_vw">할인금액</i><?=number_format($dis_amount[$i])?></td>
							<td><i class="mo_vw">실결제금액</i><?=number_format($arrList['list'][$i]['pay_amount'])?></td>
							
							<td><i class="mo_vw">배송비 제외 총 주문금액</i><?=number_format($arrList['list'][$i]['pay_amount']-$arrList['list'][$i]['ship_amount'])?></td>
							
							<!--<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="order_detail.php?order_no=<?=$arrList['list'][$i]['order_no']?>&<?=$_SERVER['QUERY_STRING']?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="delOrder('<?=$arrList['list'][$i]['order_no']?>')">삭제</button>
								</div>
							</td>-->
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td width="100%" colspan="10" >검색된 데이터가 없습니다.</td>
					</tr>
					<?}?>
					<tr>
						<td colspan="2" style="background: #cccccc;font-weight: bold;">매출집계(판매이익)</td>
						<td colspan="8" style="text-align: right; font-weight: bold;padding-right:50px;"><?=number_format($arrSum['list'][0]['tot_amount']-$arrSum['list'][0]['ship_amount'])?></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="bdr_btm">
			<div class="paging">			
			<?
			############### paging ############### ST
			$queryString = explode("&",$_SERVER['QUERY_STRING']);
			$reQueryString = "";
			$comma = "";
			for($i=0;$i<count($queryString);$i++){
				if(strpos($queryString[$i],"offset=")===false){
					$reQueryString .= $comma.$queryString[$i];
					$comma = "&";
				}
			}
			//	echo pageNavigationUser($arrBoardList["total"],$arrBoardInfo["list"][0]["scale"],$arrBoardInfo["list"][0]["pagescale"],$_GET['offset'],$reQueryString);
			echo pageNavigationBackoffice($arrList['total'], $scale, $pagescale, $_GET['offset'], $reQueryString);
			############### paging ############### ED
			?>
			</div>
			<div class="btns">	
			<!--	<a href="./member_coupon_info.php" class="btn">신규등록</a>-->
			</div>
		</div>
	</div>
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

<?php include("pub/inc/footer.php") ?>
<script type="text/javascript">
//<![CDATA[
$(window).load(function(){
//달력
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.gif", 
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});
});
//]]>
</script>


<?#####################################################################################################################################################?>
<!--
		<form name="frmSort" method="get" action="<?=$_SERVER['PHP_SELF']?>">
		<input type="hidden" name="mode" value="<?=$_GET['mode']?>">
		
			<tr>
			  <th>통합검색</th>
			  <td class="space-left">
				<select name="sw">
				<option value="all"<?=$_REQUEST['sw']=="all"?" selected":""?>>주문자명+회원ID</option>
				<option value="name"<?=$_REQUEST['sw']=="name"?" selected":""?>>주문자명</option>
				<option value="id"<?=$_REQUEST['sw']=="id"?" selected":""?>>회원ID</option>
				</select>
				<input type="text" name="sk" value="<?=$_REQUEST['sk']?>" class="input" />&nbsp;&nbsp;&nbsp;
		
				<select name="sw2">
				<option value="goodname"<?=$_REQUEST['sw2']=="goodname"?" selected":""?>>상품명</option>
				</select>
				<input type="text" name="sk2" value="<?=$_REQUEST['sk2']?>" class="input" />
			</td>
			<td rowspan="6"><span class="btn_pack xlarge"><input type="submit" style="width:100px;font-weight:bold" value=" 검 색 " /></span></td>
		  </tr>
		  <tr>
			 <th>주문상태</th>
			 <td class="space-left">
				<? if($_GET['mode']=="1") {?>
				<input type="checkbox" name="order_states[]" value="1" <? if (preg_match("/1/", $orderstate)) echo "checked"; ?>/>입금대기 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="6" <? if (preg_match("/6/", $orderstate)) echo "checked"; ?> />입금확인 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="7" <? if (preg_match("/7/", $orderstate)) echo "checked"; ?> />배송준비중 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="8" <? if (preg_match("/8/", $orderstate)) echo "checked"; ?> />배송중 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="9" <? if (preg_match("/9/", $orderstate)) echo "checked"; ?> />구매완료 &nbsp;&nbsp;

				<input type="checkbox" name="order_states[]" value="2" <? if (preg_match("/2/", $orderstate)) echo "checked"; ?>/>취소요청 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="3" <? if (preg_match("/3/", $orderstate)) echo "checked"; ?>/>취소완료 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="4" <? if (preg_match("/4/", $orderstate)) echo "checked"; ?>/>교환/반품요청 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="5" <? if (preg_match("/5/", $orderstate)) echo "checked"; ?>/>교환/반품완료 &nbsp;&nbsp;
				<?}else if($_GET['mode']=="2") {?>
				<input type="checkbox" name="order_states[]" value="2" <? if (preg_match("/2/", $orderstate)) echo "checked"; ?>/>취소요청 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="3" <? if (preg_match("/3/", $orderstate)) echo "checked"; ?>/>취소완료 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="4" <? if (preg_match("/4/", $orderstate)) echo "checked"; ?>/>교환/반품요청 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="5" <? if (preg_match("/5/", $orderstate)) echo "checked"; ?>/>교환/반품완료 &nbsp;&nbsp;
				<?}else if($_GET['mode']=="3") {?>
				미주문 &nbsp;&nbsp;
				<?}?>
			 </td>
		  </tr>
		  <tr>
			 <th>처리일자</th>
			 <td class="space-left"><input type="radio" name="sh_date" value="order_date" <?=$_REQUEST["sh_date"]=="order_date"?"checked":""?>>주문일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="ipkum_date" <?=$_REQUEST["sh_date"]=="ipkum_date"?"checked":""?>>입금일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="shipping_date" <?=$_REQUEST["sh_date"]=="shipping_date"?"checked":""?>>배송일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="finish_date" <?=$_REQUEST["sh_date"]=="finish_date"?"checked":""?>>거래완료일 &nbsp;&nbsp;
				<input type="text" name="s_date" id="s_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['s_date']?>" /> ~ <input type="text" name="e_date" id="e_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['e_date']?>" />
				&nbsp;
				<?
				$yes_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*1));
				$yes3_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*3));
				$to_day = date('Y-m-d');
				$week_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*7));
				$month_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*30));
				?>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$to_day?>')" style="color:#660000;">오늘</a></span>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$yes_day?>')" style="color:#660000;">어제</a></span>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$yes3_day?>')" style="color:#660000;">3일전</a></span>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$week_day?>')" style="color:#660000;">1주일</a></span>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$month_day?>')" style="color:#660000;">1개월</a></span>
			</td>
		  </tr>
		  <tr>
			<th>결제가격</th>
			<td class="space-left">
				<input type="text" name="s_price" style="width:80px;" value="<?=$_REQUEST['s_price']?>" style="text-align:right" class="input" />원 ~ <input type="text" name="e_price" style="width:80px;" value="<?=$_REQUEST['e_price']?>" style="text-align:right" class="input" />원
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
			<th>쿠폰 & 포인트 확인</th>
			<td class="space-left">
				<label><input type="checkbox" name="coupon_amount" value="Y" <?=$_REQUEST['coupon_amount']=="Y"?"checked":""?>/>쿠폰사용</label> &nbsp;&nbsp;
				<label><input type="checkbox" name="using_point" value="Y" <?=$_REQUEST['using_point']=="Y"?"checked":""?>/>포인트사용</label> &nbsp;&nbsp;
			</td>
		  </tr>
		</form>
-->