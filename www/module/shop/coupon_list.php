<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	$tp = "1";
}else{
	$tp = "2";
}	
$arrList = getPreOrderList($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$tp);
if($arrList["total"]>0){
for($i=0;$i<$arrList["total"];$i++){
	$arrIdx .= $arrList["list"][$i]['g_idx']."|";

	if($arrList["list"][$i]['price_1'] > 0) {
		$price = $arrList["list"][$i]['price_1'];
	} else {
		$price = $arrList["list"][$i]['price'];
	}
	$arrOpt1[$i] = explode("|",$arrList["list"][$i]['opt_1']);
	$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
	$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
	$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
	$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
	$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);

	//추가금액 계산
	$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'];

	$cart_qty[$arrList["list"][$i]['g_idx']] = $arrList["list"][$i]['qty'];
	$cart_price[$arrList["list"][$i]['g_idx']] = $price+$optionPrice;

	$totalPrice += ($price*$arrList["list"][$i]['qty'])+($optionPrice * $arrList["list"][$i]['qty']);
}
}

$arrGoodList = getCouponGoodList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], substr($arrIdx,0,-1), "N");

$arrGoodList2 =getMypageCouponList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "Y1", 0, 0, $totalPrice);

//DB해제
SetDisConn($dblink);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />
<title></title>
<link type="text/css" rel="stylesheet" href="/css/style.css" />
<script type="text/javascript" language="javascript" src="/js/common.js"></script>
<style type="text/css">
html {overflow-y:auto;}

.pop_bidding_bg {padding:0;}
.pop_bidding_wrap {border:1px solid #dddddd; padding:24px; background:#fff;}
.pop_bidding_wrap h1 {height:32px; color:#000; line-height:25px; font-size:22px; border-bottom:1px solid #ddd; font-family:'NanumGothic','나눔고딕', 'Nanum Gothic'; font-weight:700; margin:0 0 30px 0;}
.pop_bidding_wrap .section {margin-bottom:30px;}
.pop_bidding_wrap .section h2 {height:25px;}
</style>
<script>
function setPrice() {
	var i;
	var discount_price = 0;
	var coupon_idx = "";

	for(i=0;i<document.forms.length;i++){

		if(document.forms[i].id != null){
			if(document.forms[i].coupon_check){
				if(document.forms[i].coupon_check.checked){
					discount_price = discount_price + (document.forms[i].discount_price.value*1);
					coupon_idx = coupon_idx + document.forms[i].idx.value + "|";
				}
			}
		}
	}
	document.getElementById("disPrice").innerHTML = add_comma(discount_price);
	document.getElementById("getPrice").value = discount_price;
	document.getElementById("getIdx").value = coupon_idx;
}

function inPrice(){
	var payPrice = parseInt(parent.document.getElementById("hiddenPayAmount").value) - parseInt(parent.document.getElementById("using_point").value) - parseInt(document.getElementById("getPrice").value);
	

	parent.document.getElementById("coupon_price").value = document.getElementById("getPrice").value;
	parent.document.getElementById("coupon_idx").value = document.getElementById("getIdx").value;

	parent.document.getElementById("showPriceTotal").innerHTML =  add_comma(payPrice) + "원";
	parent.jQuery.fancybox.close();
}

function add_comma(val){
	var val = String(val);
	var result = "";
	var temp = 0;

	for(var i = 0; i < val.length; i++){
		temp = val.length-(i+1);
		
		if(i%3 == 0 && i != 0){ 
			result = ',' + result;
		}

	result = val.charAt(temp) + result;
	}
	return result;
}
</script>
</head>
<body class="pop_bidding_bg">
	<div class="pop_bidding_wrap">
		<h1>쿠폰목록</h1>
		<h3>상품할인 쿠폰</h3>
		<div class="OrderTable">
			<table>
			<colgroup>
				<col />
				<col width="14%" />
				<col width="8%" />
				<col width="6%" />
				<col width="12%" />
				<col width="13%" />
				<col width="6%" />
			</colgroup>
			<tr>
				<th>상품명</th>
				<th>가격</th>
				<th>쿠폰</th>
				<th>수량</th>
				<th>할인액</th>
				<th>쿠폰적용가</th>
				<th>사용</th>
			</tr>
			<?
			if($arrGoodList["total"]>0){
				for($i=0;$i<$arrGoodList["total"];$i++){

					if($arrGoodList["list"][$i]['coupon_unit']=="P") {
						$couponprice = ($arrGoodList["list"][$i]['coupon_dis']*$cart_price[$arrGoodList["list"][$i]['g_idx']])/100;
					} else {
						$couponprice = $arrGoodList["list"][$i]['coupon_dis'];
					}
			?>
			<form>
		    <input type="hidden" name="idx" value="<?=$arrGoodList["list"][$i]['idx']?>">
			<input type="hidden" name="discount_price" value="<?=$couponprice*$cart_qty[$arrGoodList["list"][$i]['g_idx']]?>">
			<tr>
				<td><?=stripslashes($arrGoodList["list"][$i]['coupon_name'])?></td>
				<td><?=number_format($cart_price[$arrGoodList["list"][$i]['g_idx']])?>원</td>
				<td><?=number_format($arrGoodList["list"][$i]['coupon_dis'])?><?=$arrGoodList["list"][$i]['coupon_unit']=="P"?"%":"원"?></td>
				<td><?=$cart_qty[$arrGoodList["list"][$i]['g_idx']]?></td>
				<td><?=number_format($couponprice*$cart_qty[$arrGoodList["list"][$i]['g_idx']])?>원</td>
				<td><?=number_format( ($cart_price[$arrGoodList["list"][$i]['g_idx']]*$cart_qty[$arrGoodList["list"][$i]['g_idx']]) - ($couponprice*$cart_qty[$arrGoodList["list"][$i]['g_idx']]) )?>원</td>
				<td><input type="checkbox" name="coupon_check" value="true" onclick="setPrice()"></td>

			</tr>
			</form>
			<?	
				}
			?>
			<?
			}else{
			?>
			<tr height="60">
				<td colspan="7" align="center">등록된 쿠폰이 없습니다.</td>
			</tr>
			<?}?>
			</table>
		</div>

		<h3>이벤트 쿠폰</h3>
		<div class="OrderTable">
			<table>
			<colgroup>
				<col width="50%" />
				<col width="28%" />
				<col width="14%" />
				<col width="8%" />
			</colgroup>
			<tr>
				<th>쿠폰명</th>
				<th>기간</th>
				<th>할인액</th>
				<th>사용</th>
			</tr>
			<?
			if($arrGoodList2["total"]>0){
				for($i=0;$i<$arrGoodList2["total"];$i++){
					if($arrGoodList2["list"][$i]['coupon_unit']=="P") {
						if($arrGoodList2["list"][$i]['over_price'] < $totalPrice && $arrGoodList2["list"][$i]['over_price']!="0") {
							$couponprice = ($arrGoodList2["list"][$i]['coupon_dis']*$arrGoodList2["list"][$i]['over_price'])/100;
						} else {
							$couponprice = ($arrGoodList2["list"][$i]['coupon_dis']*$totalPrice)/100;
						}
					} else {
						$couponprice = $arrGoodList2["list"][$i]['coupon_dis'];
					}
			?>
			<form>
		    <input type="hidden" name="idx" value="<?=$arrGoodList2["list"][$i]['idx']?>">
			<input type="hidden" name="discount_price" value="<?=$couponprice?>">
			<tr>
				<td><?=stripslashes($arrGoodList2["list"][$i]['coupon_name'])?></td>
				<td><?=$arrGoodList2["list"][$i]['coupon_sdate']?> ~ <?=$arrGoodList2["list"][$i]['coupon_edate']?></td>
				<td><?=number_format($arrGoodList2["list"][$i]['coupon_dis'])?><?=$arrGoodList2["list"][$i]['coupon_unit']=="P"?"%":"원"?></td>
				<td><input type="checkbox" name="coupon_check" value="true" onclick="setPrice()"></td>
			</tr>
			</form>
			<?}
			}else{
			?>
			<tr height="60">
				<td colspan="7" align="center">등록된 쿠폰이 없습니다.</td>
			</tr>
			<?}?>
			</table>
		</div>
		<input type="hidden" name="getIdx" id="getIdx" value="">
		<input type="hidden" name="getPrice" id="getPrice" value="0">
		<div align="right" style="font-size:120%;font-weight:bold">할인금액: <span id="disPrice">0</span>원 &nbsp;&nbsp;&nbsp;<a href="#none" onclick="inPrice()" style="color:blue">[ 쿠폰 사용하기 ]</a></div>

	</div>
</body>
</html>