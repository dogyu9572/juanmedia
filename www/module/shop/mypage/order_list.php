<?php
//로그인확인
if(!$_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"]){//비회원로그인도 하지 않았다면
	include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
}

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){//회원로그인을 한 상태라면
	$arrList = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_status']), $scale, $_REQUEST['offset']);

	$arrOrder1 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "1", 0, 0);	//주문접수,입금
	$arrOrder6 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "6", 0, 0);	//결제완료
	$arrOrder7 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "7", 0, 0);	//출고준비
	$arrOrder8 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "8", 0, 0);	//배송중
	$arrOrder9 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "9", 0, 0);	//배송완료

	$arrOrder2 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "2,3", 0, 0);	//취소
	$arrOrder4 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "4,5", 0, 0);	//교환/반품

}else{
	$arrList = getOrderListGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], $scale, $_REQUEST['offset']);

	$arrOrder1 = getOrderListGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], 0, 0, "1");	//주문접수,입금
	$arrOrder6 = getOrderListGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], 0, 0, "6");	//결제완료
	$arrOrder7 = getOrderListGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], 0, 0, "7");	//출고준비
	$arrOrder8 = getOrderListGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], 0, 0, "8");	//배송중
	$arrOrder9 = getOrderListGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], 0, 0, "9");	//배송완료

	$arrOrder2 = getOrderListGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], 0, 0, "2,3");	//취소
	$arrOrder4 = getOrderListGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], 0, 0, "4,5");	//교환/반품

}


//DB해제
SetDisConn($dblink);
?>

<div id="sub_container">
	<div class="content">

		<!-- leftArea : s -->
		<? include $_SERVER['DOCUMENT_ROOT'].'/mypage/left.php'; ?>
		<!-- leftArea : e -->

		<div id="rightArea">
			<div class="con">
			<!-- 내용 : s -->
			<div class="location">
				<p class="local"><span class="home"></span><span class="route">마이페이지</span><span class="route">나의 쇼핑현황</span><span class="current">전체주문내역</span></p>
			</div>
			<!-- //location -->
			<h2>전체주문내역</h2>
			<div class="mypageCon orderListCon">
				<h3>진행 중인 주문</h3>
				<div class="orderIng">
					<ul class="orderly">
						<li class="li01">입금대기 <span><?=number_format($arrOrder1["total"])?></span></li> 
						<li class="li02">결제완료  <span><?=number_format($arrOrder6["total"])?></span></li> 
						<li class="li03">상품준비중 <span><?=number_format($arrOrder7["total"])?></span></li>  
						<li class="li04">배송  <span><?=number_format($arrOrder8["total"])?></span></li>    
						<li class="li05">배송완료<span><?=number_format($arrOrder9["total"])?></span></li> 
					</ul>
					<ul class="deal">
						<li>취소 <span><?=number_format($arrOrder2["total"])?>건</span></li>
						<li>교환/반품 <span><?=number_format($arrOrder4["total"])?>건</span></li>
					</ul>
				</div>
				<!-- //orderIng -->
				<p class="helpEx mb40">* 구매확정이 완료된 주문은 진행중인 주문에 포함되지 않으며 진행상태에 따라 배송지 변경, 취소, 교환, 반품신청이 가능합니다.</p>

				<h3>주문내역 (<?=number_format($arrList["total"])?>) 
					<!-- <span class="stateSelect">
						<select name="">
							<option value="">주문상태 전체 (13)</option>
						</select>
					</span> -->
				</h3>
				<div class="blist">
					<table>
						<colgroup>
							<col width="200">
							<col width="*">
							<col width="95">
							<col width="95">
							<col width="100">
							<col width="110">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">주문번호</th>
								<th scope="col">주문내역</th>
								<th scope="col">결제방법</th>
								<th scope="col">결제금액</th>
								<th scope="col">주문상태</th>
								<th scope="col">주문일시</th>
							</tr>
						</thead>
						<tbody>
							<?
						if($arrList["total"]>0){
							for($i=0;$i<$arrList["list"]["total"];$i++){
						?>
						<tr>
							<td><a href="/shop.php?goPage=OrderInfo&order_no=<?=$arrList["list"][$i]['order_no']?>" class="num"><?=$arrList["list"][$i]['order_no']?></a></td>
							<td class="space-left"><?=stripslashes($arrList["list"][$i]['order_summary'])?></td>
							<td><?=$_SITE["SHOP"]["PAY_TYPE"][$arrList["list"][$i]['pay_type']]?></td>
							<td><?=number_format($arrList["list"][$i]['pay_amount'])?>원</td>
							<td><?=$_SITE["SHOP"]["ORDER_STATE"][$arrList["list"][$i]['order_state']]?></td>
							<td><span class="date"><?=substr($arrList["list"][$i]['order_date'],0,10)?></span></td>
						</tr>
						<?	
							}
						?>
						<?
						}else{
						?>
						<tr height="100">
							<td colspan="7" align="center">주문내역이 없습니다.</td>
						</tr>
						<?}?>
						</tbody>
					</table>
				</div>
				<!-- //blist --> 
                <p class="helpEx">* 주문번호를 클릭하시면 자세한 구매내역과 함께 취소가 가능합니다.</p>
				<p class="helpEx">* 판매자가 상품을 발송했거나, 판매자 부담 추가 배송비가 발생하는 경우에는 판매자의 승인 후 취소/반품/교환 처리가 가능합니다.</p>
				<p class="helpEx">* 구매자 책임 사유로 인한 취소/반품 후 환불 시, 유효기간이 만료된 비현금성 포인트는 자동 소멸됩니다.</p>
				<p class="helpEx mb40">* 배송 주문의 반품신청 및 상품 이상에 대한 문의가 있으신 경우, ‘문의사항 > 1:1문의’에 글을 남겨주세요.</p>
				
				<!-- 취소/교환/반품 절차 -->
				<? include $_SERVER['DOCUMENT_ROOT'].'/mypage/state/cancelProcess.php'; ?>

			</div>
			<!-- //mypageCon -->
				
			<!-- 내용 : e -->	
			</div>
			<!-- //con -->
		</div>
		<!-- //rightArea -->
	</div>
	<!--//content --> 
</div>
