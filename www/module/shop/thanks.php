<?
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	$tp = "1";

	$arrMemInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]));

	$arrEmail = explode("@",$arrMemInfo["list"][0]['email']);
	$arrPhone = explode("-",$arrMemInfo["list"][0]['phone']);
	$arrMobile = explode("-",$arrMemInfo["list"][0]['mobile']);
	$arrFax = explode("-",$arrMemInfo["list"][0]['fax']);
	$arrZip = explode("-",$arrMemInfo["list"][0]['zip']);
	$arrTel = explode("-",$arrMemInfo["list"][0]['regnum1']);
}else{
	$tp = "2";
}	
$arrInfo = getOrderInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_no"]));

//DB해제
SetDisConn($dblink);

//주문번호 확인 => 주문번호가 있어야만 주문가능
if($arrInfo["total"] > 0){
?>
	<div id="sub_container">
		<div class="content">

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/include/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="location">
					<p class="local"><span class="home"></span><span class="current">CART</span></p>
				</div>
				<!-- //location -->
				
				<div class="con">
				<!-- 내용 : s -->

					<div class="cartCon">
						<p class="mb40"><img src="/img/cartCon_img_step03.jpg" alt="03 주문완료" /></p>
						
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
										<td><?=number_format(($arrInfo["good_list"][$i]['g_price']*$arrInfo["good_list"][$i]['g_qty'])+($optionPrice * $arrInfo["good_list"][$i]['g_qty']))?>원</td>
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
										<!-- <tr>
											<th scope="row">택배사</th>
											<td></td>
										</tr>
										<tr>
											<th scope="row">송장정보</th>
											<td></td>
										</tr> -->
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

					<div class="btnC">
						<a href="/shop.php?goPage=MyPage" class="btn_brown2">마이페이지</a>
						<a href="/shop.php?goPage=OrderList" class="btn_gray4">주문배송조회</a>
					</div>

				
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
<!-- 다음CTS 컨버젼 스크립트 START -->
<script type="text/javascript">
//<![CDATA[
var DaumConversionDctSv="type=P,orderID=<?=$arrInfo["list"][0]["order_no"]?>,amount=<?=$arrInfo["list"][0]["pay_amount"]?>";
var DaumConversionAccountID="3xUjpa2CVKaug-EfmMq1_w00";
if(typeof DaumConversionScriptLoaded=="undefined"&&location.protocol!="file:"){
	var DaumConversionScriptLoaded=true;
	document.write(unescape("%3Cscript%20type%3D%22text/javas"+"cript%22%20src%3D%22"+(location.protocol=="https:"?"https":"http")+"%3A//s1.daumcdn.net/svc/original/U03/commonjs/cts/vr200/dcts.js%22%3E%3C/script%3E"));
}
//]]>
</script>
<!-- 다음CTS 컨버젼 스크립트 END -->

	

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
?>