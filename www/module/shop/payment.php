<?
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	$tp = "1";
}else{
	$tp = "2";
}	
$arrInfo = getOrderInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $tp, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_no"]));

//DB해제
SetDisConn($dblink);


switch($arrInfo["list"][0]["pay_type"]){
		case "card"://신용카드
			$_paymethod = "CARD";break;
		case "online"://계좌이체
			$_paymethod = "BANK";break;
		case "escrow"://가상계좌
			$_paymethod = "VBANK";break;
		case "hp";//휴대폰
			$_paymethod = "CELLPHONE";break;
}

$VbankExpDate =  date("Ymd", strtotime($day."1 day"));
// 클라이언트 ip 가져오기
$ip = $_SERVER['REMOTE_ADDR'];
// 서버 ip 가져오기
$server_ip = $_SERVER['SERVER_NAME'];	
// 전문생성일시
$ediDate = date("YmdHis");
// 상점서명키 (꼭 해당 상점키로 바꿔주세요)
$merchantKey = "0/4GFsSd7ERVRGX9WHOzJ96GyeMTwvIaKSWUCKmN3fDklNRGw3CualCFoMPZaS99YiFGOuwtzTkrLo4bR4V+Ow==";
//$MID = "SMTPAY001m"; 
$MID = $_SITE["SHOP"]["PG"]["MALLID"];
$goodsAmt = $arrInfo["list"][0]["pay_amount"];
// 웹링크 결제 서버 IP 세팅
$payActionUrl = "https://tpay.smilepay.co.kr";
$encryptData = base64_encode(md5($ediDate.$MID.$goodsAmt.$merchantKey));



//주문번호 확인 => 주문번호가 있어야만 주문가능
if($arrInfo["total"] > 0){
?>
<script language='javascript' src='/module/shop/pg/SMILEPAY/js/incMerchant_utf8.js'></script>
<?php
    echo("<script language=javascript>setPayActionUrl(\"$payActionUrl\");</script>");
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
									<col width="150px" />
									<col width="150px" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col" colspan="2">상품정보</th>
										<th scope="col">판매금액</th>
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
						

							<p class="tit">결제정보</p>
							<div class="bread">
								<table>
									<colgroup>
										<col width="25%" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th scope="row" >결제방법</th>
											<td><?=$_SITE["SHOP"]["PAY_TYPE"][$arrInfo["list"][0]["pay_type"]]?></td>
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

					<form method="post" name="tranMgr" id="tranMgr" action="">
					<div class="btnC" id="submitPreView">
						<a href="javascript:;" class="btn_gray4" onClick="return goInterface();">결제하기</a>
						<a href="javascript:cancelPopup()" class="btn_brown2">구매 취소</a>
					</div>
					<div class="btnC" id="submitNextView" style="display:none">
						<strong style="color:blue">처리중입니다. 잠시만 기다려주세요.</strong> <img src="/img/loadings.gif">
					</div>

					
					<input type="hidden" name="GoodsURL"/>
					<input type="hidden" name="EncryptData" value="<?php echo $encryptData ?>">
					<input type="hidden" name="selectType" value="<?=$_paymethod?>">
					<input type="hidden" name="GoodsName" id="GoodsName" value="<?=$arrInfo["list"][0]["order_summary"]?>">     			<!-- 상품정보 -->
					<input type="hidden" name="Amt" id="Amt" value="<?=$goodsAmt?>">     			<!-- 결제가격 -->
					<input type="hidden" name="Moid" id="Moid"  value="<?=$arrInfo["list"][0]["order_no"]?>">                        <!-- 주문번호 -->
					<input type="hidden" name="MID" id="MID" value="<?= $MID ?>">                        <!-- 상점아이디 -->
					<input type="hidden" name="ReturnURL" id="ReturnURL"	value="http://<?=$_SERVER["SERVER_NAME"]?>/module/shop/pg/SMILEPAY/return_pay.php">                   <!-- 결제결과전송 URL -->
					<input type="hidden" name="ResultYN" id="ResultYN" value="Y">                        <!-- 결제결과창유무 -->
					<input type="hidden" name="RetryURL" value="http://<?=$_SERVER["SERVER_NAME"]?>/module/shop/pg/SMILEPAY/inform.php">                        <!-- 결제결과 RETRY URL -->
					<input type="hidden" name="mallUserID" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">           			<!-- 구매자ID -->
					<input type="hidden" name="BuyerName" id="BuyerName" value="<?=$arrInfo["list"][0]["order_name"]?>">           			<!-- 구매자 -->
					<input type="hidden" name="BuyerTel" id="BuyerTel" value="<?=$arrInfo["list"][0]["order_phone"]?>">           			<!-- 구매자 연락처 -->
					<input type="hidden" name="BuyerEmail" id="BuyerEmail" value="<?=$arrInfo["list"][0]["order_email"]?>">           			<!-- 구매자 이메일 -->
					<input type="hidden" name="BuyerAddr" id="BuyerAddr" value="<?=$arrInfo["list"][0]["ship_address"]?> <?=$arrInfo["list"][0]["ship_address_ext"]?>">           			<!-- 구매자 주소 -->
					<input type="hidden" name="BuyerPostNo" id="BuyerPostNo" value="<?=$arrInfo["list"][0]["ship_zip"]?>">           			<!-- 구매자 우편번호 -->
					<input type="hidden" name="MallIP" id="MallIP" value="<?=$server_ip?>">
					<input type="hidden" name="VbankExpDate" id="VbankExpDate" value="<?=$VbankExpDate?>">           			<!-- 가상계좌입금기한 -->
					<input type="hidden" name="EncodingType" id="EncodingType" value="utf8">
					<input type="hidden" name="GoodsCl" id="GoodsCl" value="1">
					<input type="hidden" name="OpenType" id="OpenType" value="KR">
					<input type="hidden" name="SocketYN" id="SocketYN" value="N">

					<input type="hidden" name="BuyerAuthNum" id="BuyerAuthNum" value="">
					<input type="hidden" name="ParentEmail" id="ParentEmail" value="">
					<input type="hidden" name="SkinColor" id="SkinColor" value="">
					<input type="hidden" name="EncodeParameters" id="EncodeParameters" value="">
					<input type="hidden" name="GoodsCnt" id="GoodsCnt" value="<?=$arrInfo["good_total"]?>">
					<input type="hidden" name="OfferPeriod" id="OfferPeriod" value="">
					<input type="hidden" name="SUB_ID" id="SUB_ID" value="">
					<input type="hidden" name="BrowserType">
					<input type="hidden" name="MallReserved" value="<?=$arrInfo["list"][0]["giftgb"]?>">
				</form>

					<br>
				
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
	<form name="payForm" method="post" accept-charset="EUC-KR">
	<input type="hidden" name="payType">
	<input type="hidden" name="GoodsCnt">
	<input type="hidden" name="GoodsName">
	<input type="hidden" name="Amt">
	<input type="hidden" name="GoodsURL">
	<input type="hidden" name="Moid">
	<input type="hidden" name="MID">
	<input type="hidden" name="ReturnURL">
	<input type="hidden" name="ResultYN">
	<input type="hidden" name="RetryURL">
	<input type="hidden" name="mallUserID">
	<input type="hidden" name="BuyerName">
	<input type="hidden" name="BuyerAuthNum">
	<input type="hidden" name="BuyerTel">
	<input type="hidden" name="BuyerEmail">
	<input type="hidden" name="ParentEmail">
	<input type="hidden" name="BuyerAddr">
	<input type="hidden" name="BuyerPostNo">
	<input type="hidden" name="UserIP"          value="<?php echo $ip ?>">
	<input type="hidden" name="MallIP">
	<input type="hidden" name="VbankExpDate"   value="<?php echo $VbankExpDate ?>" >
	<input type="hidden" name="BrowserType">
	<input type="hidden" name="PayMethod">
	<input type="hidden" name="ediDate"			value="<?php echo $ediDate ?>">
	<input type="hidden" name="EncryptData">
	<input type="hidden" name="MallReserved">
	<input type="hidden" name="FORWARD" value="Y">
	<input type="hidden" name="MallResultFWD"   value="N">
	<input type="hidden" name="SUB_ID">
	<input type="hidden" name="EncodingType">
	<input type="hidden" name="OpenType" value="">
	<input type="hidden" name="GoodsCl">
	<input type="hidden" name="OfferPeriod" value="2013081020120810">
	<input type="hidden" name="SkinColor">
	<input type="hidden" name="SocketYN">
	<input type="hidden" name="EncodeParameters">
	</form>
	<iframe src="/module/shop/pg/SMILEPAY/blank.html" name="payFrame" frameborder="no" width="0" height="0" scrolling="yes"  align="center"></iframe>

<script type="text/javascript">
function cancelPopup(){
	var cfm;
	cfm =false;
	cfm = confirm("현재 주문한 상품을 삭제 하시겠습니까?");
	if(cfm==true){
		document.getElementById("submitPreView").style.display = "none";
		document.getElementById("submitNextView").style.display = "block";

		document.frmOrderListHidden.submit();
	}
}
</script>

<form name="frmOrderListHidden" method="post" action="/module/shop/order_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="order_no" value="<?=$arrInfo["list"][0]["order_no"]?>">
<input type="hidden" name="directpoint" value="Y">
<input type="hidden" name="listURL" value="/">
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
?>