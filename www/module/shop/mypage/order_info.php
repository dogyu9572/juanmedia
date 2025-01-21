<?php
//로그인확인
if(!$_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"]){//비회원로그인도 하지 않았다면
	include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
}

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){//회원로그인을 한 상태라면
	$arrInfo = getOrderInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], 1, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_no"]));
}else{
	$arrInfo = getOrderInfoGuest($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"], $_SESSION[$_SITE["DOMAIN"]]["GUEST"]["PW"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_no"]));
}

//DB해제
SetDisConn($dblink);
?>
<?
//주문번호 확인 
if($arrInfo["total"] > 0){
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
				<p class="local"><span class="home"></span><span class="route">마이페이지</span><span class="route">나의 쇼핑현황</span><span class="current">주문상세내역</span></p>
			</div>
			<!-- //location -->
			<h2>주문상세내역</h2>
			
			<div class="con">
				<!-- 내용 : s -->

					<div class="cartCon">
						
						<div class="blist">
							<table>
								<colgroup>
									<col width="150px" />
									<col width="*" />
									<col width="150px" />
									<col width="100px" />
									<col width="80px" />
									<col width="150px" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col" colspan="2">상품정보</th>
										<th scope="col">판매금액</th>
										<th scope="col">적립금</th>
										<th scope="col">수량</th>
										<th scope="col">합계/추가금</th>
									</tr>
								</thead>
								<tbody>
									<?
									if($arrInfo["good_total"]>0){
										for($i=0;$i<$arrInfo["good_total"];$i++){
											//추가금액 계산
											$optionPrice = $arrInfo["good_list"][$i]['g_opt_1_price'] + $arrInfo["good_list"][$i]['g_opt_2_price'] + $arrInfo["good_list"][$i]['g_opt_3_price'] + $arrInfo["good_list"][$i]['g_opt_4_price'] + $arrInfo["good_list"][$i]['g_opt_5_price'];

											//합계금액 계산
											$totalPrice += ($arrInfo["good_list"][$i]['g_price']*$arrInfo["good_list"][$i]['g_qty'])+($optionPrice * $arrInfo["good_list"][$i]['g_qty']);
									?>
									<tr>
										<td><img src="/uploaded/shop_good/<?=$arrInfo["good_list"][$i]['g_idx']?>/<?=$arrInfo["good_list"][$i]['image_s']?>" width="76" /></td>
										<td class="tl">
											<p class="name"><?=stripslashes($arrInfo["good_list"][$i]['g_name'])?></p>
											<p class="option"><?=$arrInfo["good_list"][$i]['g_opt_1']?" ".$arrInfo["good_list"][$i]['g_opt_1']:""?><?=$arrInfo["good_list"][$i]['g_opt_1_price']?" +".number_format($arrInfo["good_list"][$i]['g_opt_1_price']):""?>
											<?=$arrInfo["good_list"][$i]['g_opt_2']?", ".$arrInfo["good_list"][$i]['g_opt_2']:""?><?=$arrInfo["good_list"][$i]['g_opt_2_price']?" +".number_format($arrInfo["good_list"][$i]['g_opt_2_price']):""?>
											<?=$arrInfo["good_list"][$i]['g_opt_3']?", ".$arrInfo["good_list"][$i]['g_opt_3']:""?><?=$arrInfo["good_list"][$i]['g_opt_3_price']?" +".number_format($arrInfo["good_list"][$i]['g_opt_3_price']):""?>
											<?=$arrInfo["good_list"][$i]['g_opt_4']?", ".$arrInfo["good_list"][$i]['g_opt_4']:""?><?=$arrInfo["good_list"][$i]['g_opt_4_price']?" +".number_format($arrInfo["good_list"][$i]['g_opt_4_price']):""?>
											<?=$arrInfo["good_list"][$i]['g_opt_5']?", ".$arrInfo["good_list"][$i]['g_opt_5']:""?><?=$arrInfo["good_list"][$i]['g_opt_5_price']?" +".number_format($arrInfo["good_list"][$i]['g_opt_5_price']):""?></p>
										</td>
										<td><?=number_format($arrInfo["good_list"][$i]['g_price']+$optionPrice)?>원</td>
										<td><?=number_format($arrInfo["good_list"][$i]['g_point'])?></td>
										<td><?=number_format($arrInfo["good_list"][$i]['g_qty'])?></td>
										<td><?=number_format(($arrInfo["good_list"][$i]['g_price']*$arrInfo["good_list"][$i]['g_qty'])+($optionPrice * $arrInfo["good_list"][$i]['g_qty']))?>원
											<? if( $arrInfo["list"][0]["order_state"] == "7" || $arrInfo["list"][0]["order_state"] == "8"  || $arrInfo["list"][0]["order_state"] == "9") {?>
											<br><br><a href="/mypage/inquiry/post.php?boardid=after&mode=write&g_idx=<?=$arrInfo["good_list"][$i]['g_idx']?>"><font color="blue">[상품리뷰쓰기]</font></a><?}?>
										</td>
									</tr>
									<?	
										}
									}else{
									?>
									<tr height="100">
										<td colspan="12" align="center">구매항목이 없습니다.</td>
									</tr>
									<?}?>	
								</tbody>
							</table>
						</div>
						<!-- //blist --> 

						<div class="orderBox">
							<div class="box">
								<p class="tit">상품금액</p>
								<div class="price"><?=number_format($arrInfo["list"][0]["total_amount"])?>원</div>
							</div>
							<!-- //box -->
							
							<div class="box">
								<p class="tit">배송비</p>
								<div class="price"><span>+</span><?=number_format($arrInfo["list"][0]["ship_amount"])?>원</div>
							</div>
							<!-- //box -->

							<div class="box">
								<p class="tit">쿠폰/상품권/적립금</p>
								<div class="price"><span>-</span><?=number_format($arrInfo["list"][0]["coupon_amount"]+$arrInfo["list"][0]["giftcard_amount"]+$arrInfo["list"][0]["using_point"])?>원</div>
							</div>
							<!-- //box -->

							<div class="box payment">
								<p class="tit">최종결제금액</p>
								<div class="price"><?=number_format($arrInfo["list"][0]["pay_amount"])?>원</div>
							</div>
							<!-- //box -->
						</div>
						<!-- //orderBox -->
						
						<div class="orderInfo">
							<p class="tit">주문고객 정보</p>
							<div class="bread">
								<table>
									<colgroup>
										<col width="25%" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th scope="row">주문자</th>
											<td><?=$arrInfo["list"][0]["order_name"]?></td>
										</tr>
										<tr>
											<th scope="row">전화번호</th>
											<td><?=$arrInfo["list"][0]["order_phone"]?></td>
										</tr>
										<tr>
											<th scope="row">이메일</th>
											<td><?=$arrInfo["list"][0]["order_email"]?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- //bread --> 

							<p class="tit">배송지 정보</p>
							<div class="bread">
								<table>
									<colgroup>
										<col width="25%" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th scope="row">택배사</th>
											<td><?=$arrInfo["list"][0]["shipping_company"]?></td>
										</tr>
										<tr>
											<th scope="row">송장정보</th>
											<td><a href="http://www.ilogen.com/iLOGEN.Web.New/TRACE/TraceNoView.aspx?slipno=<?=$arrInfo["list"][0]["shipping_no"]?>&gubun=slipno" target="_blank"><?=$arrInfo["list"][0]["shipping_no"]?></a><br /><strong>※ 송장번호를 클릭하시면 배송조회를 하실 수 있습니다.</strong></td>
										</tr>
										<tr>
											<th scope="row">수령인</th>
											<td><?=$arrInfo["list"][0]["ship_name"]?></td>
										</tr>
										<tr>
											<th scope="row">전화번호</th>
											<td><?=$arrInfo["list"][0]["ship_phone"]?></td>
										</tr>
										<tr>
											<th scope="row">휴대번호</th>
											<td><?=$arrInfo["list"][0]["ship_mobile"]?></td>
										</tr>
										<tr>
											<th scope="row">주소</th>
											<td>[<?=$arrInfo["list"][0]["ship_zip"]?>] <?=$arrInfo["list"][0]["ship_address"]?> <?=$arrInfo["list"][0]["ship_address_ext"]?></td>
										</tr>
										<tr>
											<th scope="row">배송시 요구사항</th>
											<td><?=$arrInfo["list"][0]["order_comment"]?></td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- //bread --> 

							<p class="tit">결제수단</p>
							<div class="bread">
								<table>
									<colgroup>
										<col width="25%" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th scope="row">주문번호</th>
											<td><?=$arrInfo["list"][0]["order_no"]?></td>
										</tr>
										<tr>
											<th scope="row">상품금액</th>
											<td><?=number_format($arrInfo["list"][0]["total_amount"])?>원</td>
										</tr>
										<tr>
											<th scope="row">배송비</th>
											<td><?=number_format($arrInfo["list"][0]["ship_amount"])?>원</td>
										</tr>
										<tr>
											<th scope="row" rowspan="2">결제방법</th>
											<td><?=$_SITE["SHOP"]["PAY_TYPE"][$arrInfo["list"][0]["pay_type"]]?></td>
										</tr>
										<tr>
											<td>쿠폰/상품권/적립금 : <?=number_format($arrInfo["list"][0]["coupon_amount"]+$arrInfo["list"][0]["giftcard_amount"]+$arrInfo["list"][0]["using_point"])?>원</td>
										</tr>
										<tr>
											<th scope="row">최종결제금액</th>
											<td><?=number_format($arrInfo["list"][0]["pay_amount"])?>원</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- //bread -->
						</div>
						<!-- //orderInfo -->

					</div>	
					<!-- //cartCon -->

					<div class="btnC" id="submitPreView">
						<a href="/shop.php?goPage=MyPage" class="btn_brown2">마이페이지</a>
						
						<? if($arrInfo["list"][0]['order_state']=="1" || $arrInfo["list"][0]['order_state']=="6") { //입금대기, 입금확인 ?>
						<a href="javascript:cancelPopup(<?=$arrInfo["list"][0]['order_state']?>)" class="btn_gray4">주문취소</a>
						<?}?>
						<? if($arrInfo["list"][0]['order_state']=="2"  || $arrInfo["list"][0]['order_state']=="4") { //취소요청중, 반품신청중?>
						<a href="javascript:alert('<?=$_SITE["SHOP"]["ORDER_STATE"][$arrInfo["list"][0]['order_state']]?>중입니다.');" class="btn_gray4"><?=$_SITE["SHOP"]["ORDER_STATE"][$arrInfo["list"][0]['order_state']]?></a>
						<?}?>
						<? if($arrInfo["list"][0]['order_state']=="3" || $arrInfo["list"][0]['order_state']=="5") { //취소완료, 반품/반품완료?>
						<a href="javascript:alert('정상적으로 <?=$_SITE["SHOP"]["ORDER_STATE"][$arrInfo["list"][0]['order_state']]?> 되었습니다.');" class="btn_gray4"><?=$_SITE["SHOP"]["ORDER_STATE"][$arrInfo["list"][0]['order_state']]?></a>
						<?}?>
						<? if($arrInfo["list"][0]['order_state']=="7" || $arrInfo["list"][0]['order_state']=="8" || $arrInfo["list"][0]['order_state']=="9") { //출고대기, 배송중, 배송완료 ?>
						<a href="javascript:returnPopup()" class="btn_gray4">교환/반품요청</a>
						<? }?>
						<? if($arrInfo["list"][0]['order_state']=="10") { //미주문 ?>
						<a href="javascript:cancelPopup(1)" class="btn_gray4">주문취소</a>
						<a href="/shop.php?goPage=Payment&order_no=<?=$arrInfo["list"][0]['order_no']?>" class="btn_brown2">결제하기</a>
						<?}?>
					</div>
					<div class="btnC" id="submitNextView" style="display:none">
						<strong style="color:blue">처리중입니다. 잠시만 기다려주세요.</strong> <img src="/img/loadings.gif">
					</div>
				
			<!-- 내용 : e -->	
			</div>
			<!-- //con -->
		</div>
		<!-- //rightArea -->
	</div>
	<!--//content --> 
</div>
<script type="text/javascript">
function cancelPopup(gb){
	//if(gb=="1") {
		var cfm;
		cfm =false;
		cfm = confirm("현재 주문한 상품을 삭제 하시겠습니까?");
		if(cfm==true){
			document.getElementById("submitPreView").style.display = "none";
			document.getElementById("submitNextView").style.display = "block";

			document.frmOrderListHidden.submit();
		}
	/*
	} else {
		var cfm;
		cfm =false;
		cfm = confirm("현재 주문한 상품을 취소 요청하시겠습니까?");
		if(cfm==true){
			document.frmOrderListHidden.evnMode.value="cancel";
			document.frmOrderListHidden.submit();
		}
	}
	*/
}

function returnPopup(){
 w = 600;
 h = 400;
 x = (screen.availWidth - w) / 2;
 y = (screen.availHeight - h) / 2;
 window.open('../mypage/return.php?order_no=<?=$arrInfo["list"][0]["order_no"]?>', 'return','width='+w+', height='+h+', left='+x+', top='+y+', scrollbars=yes');
}
</script>

<form name="frmOrderListHidden" method="post" action="/module/shop/order_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="order_no" value="<?=$arrInfo["list"][0]["order_no"]?>">
<input type="hidden" name="listURL" value="/shop.php?goPage=OrderList">
</form>

<?
}else{
?>
<table width="100%" border="1">
	<tr align="center">
		<td height="100">해당하는 주문내역이 없습니다.</td>
	</tr>
</table>
<?
}

//DB해제
SetDisConn($dblink);
?>