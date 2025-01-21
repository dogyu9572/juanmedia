<?
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/giftcard/giftcard.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//로그인확인
if(!$_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"]){//비회원로그인도 하지 않았다면
	include $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
}

$_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"] = "";

//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	$tp = "1";
	$pointunit = $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVELPOINT"];

	//미주문 내역삭제
	$arrMiList = getOrderListAdmin("id2", $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "10", 0, 0);

	if($arrMiList["total"]>0){
	for($i=0;$i<$arrMiList["total"];$i++){
		delOrderInfoAdmin($arrMiList["list"][$i]["order_no"]);
	}
	}

}else{
	$tp = "2";
	$pointunit = "0";
}	
$arrList = getPreOrderList($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$tp);

$arrCouponList =getMypageCouponList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "Y1", 0, 0, $totalPrice, "N");
$arrCouponList1 =getMypageCouponList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "Y1", 0, 0, $totalPrice, "Y");
$arrGiftcardList =getMypageGiftcardList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "Y1", 0, 0, $totalPrice, "N");
$arrGiftcardList1 =getMypageGiftcardList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "Y1", 0, 0, $totalPrice, "Y");

//재고체크
checkPreOderStock($arrList);

//회원정보 가져오기
$arrMemInfo = getUserInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

$nowPoint = getNowPoint($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

$arrAddressList = getAddressList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], 0, 0);

if($arrMemInfo["list"][0]['phone']=="--") {
	$arrPhone = explode("-",$arrMemInfo["list"][0]['mobile']);
} else {
	$arrPhone = explode("-",$arrMemInfo["list"][0]['phone']);
}
$arrMobile = explode("-",$arrMemInfo["list"][0]['mobile']);
$arrZip = explode("-",$arrMemInfo["list"][0]['zip']);

$defaultAddressGb="N";
for($i=0; $i<$arrAddressList["total"]; $i++) {
	if($arrAddressList["list"][$i]["d_addr"]=="Y") {
		$addr1 = $arrAddressList["list"][$i]["name"];
		$addr2 = $arrAddressList["list"][$i]["zip"];
		$addr3 = $arrAddressList["list"][$i]["address"];
		$addr4 = $arrAddressList["list"][$i]["address_ext"];
		$addr5 = explode("-", $arrAddressList["list"][$i]["phone"]);
		$addr6 = explode("-", $arrAddressList["list"][$i]["mobile"]);
		
		$defaultAddressGb="Y";
	}
}

if($defaultAddressGb=="N") {
		$addr1 = $arrMemInfo["list"][0]["user_name"];
		$addr2 = $arrMemInfo["list"][0]["zip"];
		$addr3 = $arrMemInfo["list"][0]["address"];
		$addr4 = $arrMemInfo["list"][0]["address_ext"];
		$addr5 = explode("-", $arrMemInfo["list"][0]["phone"]);
		$addr6 = explode("-", $arrMemInfo["list"][0]["mobile"]);
}
?>
<script>
function defaultAddress() {
	f = document.frmOrderForm;
	f.ship_name.value = "<?=$addr1?>";
	f.ship_zip.value = "<?=$addr2?>";
	f.ship_address.value = "<?=$addr3?>";
	f.ship_address_ext.value = "<?=$addr4?>";
	f.ship_phone1.value = "<?=$addr5[0]?>";
	f.ship_phone2.value = "<?=$addr5[1]?>";
	f.ship_phone3.value = "<?=$addr5[2]?>";
	f.ship_mobile1.value = "<?=$addr6[0]?>";
	f.ship_mobile2.value = "<?=$addr6[1]?>";
	f.ship_mobile3.value = "<?=$addr6[2]?>";
}

function chkAddress(name, zip, addr1,addr2, phone1, phone2, phone3, mobile1, mobile2, mobile3) {
	f = document.frmOrderForm;
	f.ship_name.value = name;
	f.ship_zip.value = zip;
	f.ship_address.value = addr1;
	f.ship_address_ext.value = addr2;
	f.ship_phone1.value = phone1;
	f.ship_phone2.value = phone2;
	f.ship_phone3.value = phone3;
	f.ship_mobile1.value = mobile1;
	f.ship_mobile2.value = mobile2;
	f.ship_mobile3.value = mobile3;
	$.fancybox.close();
}

function setPrice2(str) {
	var optArray = new Array();
	optArray = str.split("|");

	document.getElementById("getIdx2").value = optArray[0];
	document.getElementById("disPrice2").value = optArray[1];
}

function inPrice(){
	
	var payPrice = parseInt(parent.document.getElementById("hiddenPayAmount").value) - parseInt(parent.document.getElementById("using_point").value) - parseInt(document.getElementById("disPrice").value) - parseInt(document.getElementById("disPrice2").value);
	
	document.getElementById("coupon_price").value = add_comma(parseInt(document.getElementById("disPrice").value));
	//document.getElementById("coupon_idx").value = document.getElementById("getIdx").value;
	document.getElementById("shopCouponPrice").innerHTML =  "<span>-</span>" + add_comma(parseInt(document.getElementById("disPrice").value) + parseInt(document.getElementById("disPrice2").value) + parseInt(document.getElementById("using_point").value)) + "원";
	if(payPrice<0) {
		document.getElementById("showPriceTotal").innerHTML = "0원";
		document.getElementById("addpoint").value = - parseInt(payPrice);
		document.getElementById("addPointTxt").innerHTML = "* 상품권 나머지 금액 "+ add_comma( - parseInt(payPrice)) +"원이 적립금으로 전환됩니다.";
		document.getElementById("using_point").disabled = true;
	} else {
		document.getElementById("showPriceTotal").innerHTML = add_comma(payPrice) + "원";
		document.getElementById("addPointTxt").innerHTML = "";
		document.getElementById("addpoint").value = "";
	}
	document.getElementById("coupon_idx").value="";
	for(i=0; i< <?=$arrList["total"]?>; i++) {
		if(document.getElementById("popup_coupon_idx_"+i).value) {
			document.getElementById("coupon_idx").value += document.getElementById("popup_coupon_idx_"+i).value+"|";
		}
	}
	jQuery.fancybox.close();
}

function inPrice2(){
	
	if(document.getElementById("getIdx2").value=="") {
		alert("상품권을 선택해주세요.");
		return ;
	}

	var payPrice = parseInt(parent.document.getElementById("hiddenPayAmount").value) - parseInt(parent.document.getElementById("using_point").value) - parseInt(document.getElementById("disPrice").value) - parseInt(document.getElementById("disPrice2").value);
	
	document.getElementById("giftcard_price").value = add_comma(parseInt(document.getElementById("disPrice2").value));
	document.getElementById("giftcard_idx").value = document.getElementById("getIdx2").value;
	document.getElementById("shopCouponPrice").innerHTML =  "<span>-</span>" + add_comma(parseInt(document.getElementById("disPrice").value) + parseInt(document.getElementById("disPrice2").value) + parseInt(document.getElementById("using_point").value)) + "원";
	if(payPrice<0) {
		document.getElementById("showPriceTotal").innerHTML = "0원";
		document.getElementById("addpoint").value = - parseInt(payPrice);
		document.getElementById("addPointTxt").innerHTML = "* 상품권 나머지 금액 "+ add_comma( - parseInt(payPrice)) +"원이 적립금으로 전환됩니다.";
		document.getElementById("using_point").disabled = true;
	} else {
		document.getElementById("showPriceTotal").innerHTML = add_comma(payPrice) + "원";
		document.getElementById("addPointTxt").innerHTML = "";
		document.getElementById("addpoint").value = "";
	}
	jQuery.fancybox.close();
	document.getElementById("giftcard_check1").checked = false;
	document.getElementById("giftcard_check2").checked = false;
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
						<p class="mb40"><img src="/img/cartCon_img_step02.jpg" alt="02 주문서 작성" /></p>
						
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
										<th scope="col">합계</th>
									</tr>
								</thead>
								<tbody>
									<?
									if($arrList["total"]>0){
										for($i=0;$i<$arrList["total"];$i++){
											$arrOpt1[$i] = explode("|",$arrList["list"][$i]['opt_1']);
											$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
											$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
											$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
											$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
											$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);
											$arrOptRel2[$i] = explode("|",$arrList["list"][$i]['opt_rel_2']);

											//적립금계산
											//if($arrList["list"][$i]['point_unit']=="P"){
												$thisPoint = (($pointunit*$arrList["list"][$i]['price'])/100) * $arrList["list"][$i]['qty'];
											//}else{
											//	$thisPoint = $arrList["list"][$i]['point'] * $arrList["list"][$i]['qty'];
											//}

											//추가금액 계산
											$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'] + $arrOptRel2[$i]['1'] + $arrOptRel2[$i]['1'];

											//합계금액 계산
											$totalPrice += ($arrList["list"][$i]['price']*$arrList["list"][$i]['qty'])+($optionPrice * $arrList["list"][$i]['qty']);
									?>
									<tr>
										<td><img src="/uploaded/shop_good/<?=$arrList["list"][$i]['idx']?>/<?=$arrList["list"][$i]['image_s']?>" width="76" /></td>
										<td class="tl">
											<p class="name"><?=stripslashes($arrList["list"][$i]['g_name'])?></p>
											<p class="option"><?=$arrOpt1[$i]['0']?"| ".$arrOpt1[$i]['0']:""?><?=$arrOpt1[$i]['1']?" +".number_format($arrOpt1[$i]['1']):""?>
											<?=$arrOpt2[$i]['0']?"| ".$arrOpt2[$i]['0']:""?><?=$arrOpt2[$i]['1']?" +".number_format($arrOpt2[$i]['1']):""?>
											<?=$arrOpt3[$i]['0']?"| ".$arrOpt3[$i]['0']:""?><?=$arrOpt3[$i]['1']?" +".number_format($arrOpt3[$i]['1']):""?>
											<?=$arrOpt4[$i]['0']?"| ".$arrOpt4[$i]['0']:""?><?=$arrOpt4[$i]['1']?" +".number_format($arrOpt4[$i]['1']):""?>
											<?=$arrOpt5[$i]['0']?"| ".$arrOpt5[$i]['0']:""?><?=$arrOpt5[$i]['1']?" +".number_format($arrOpt5[$i]['1']):""?>
											<?=$arrOptRel1[$i]['0']?"| ".$arrOptRel1[$i]['0']:""?><?=$arrOptRel1[$i]['1']?" +".number_format($arrOptRel1[$i]['1']):""?>
											<?=$arrOptRel2[$i]['0']?"| ".$arrOptRel2[$i]['0']:""?><?=$arrOptRel2[$i]['1']?" +".number_format($arrOptRel2[$i]['1']):""?></p>
										</td>
										<td><?=number_format($arrList["list"][$i]['price']+$optionPrice)?>원</td>
										<td><?=number_format($thisPoint)?></td>
										<td><?=$arrList["list"][$i]['qty']?></td>										
										<td><?=number_format(($arrList["list"][$i]['price']*$arrList["list"][$i]['qty'])+($optionPrice * $arrList["list"][$i]['qty']))?>원</td>
									</tr>
									<?	
										}
										//배송비 계산
										if($totalPrice < $_SITE["SHOP"]["SHIP"]["FREE_PRICE"]){
											$shipPrice = $_SITE["SHOP"]["SHIP"]["SHIP_PRICE"];
										}else{
											$shipPrice = 0;
										}
									}else{
									?>
									<tr height="100">
										<td colspan="5" align="center">장바구니가 비었습니다.</td>
									</tr>
									<?}?>	
								</tbody>
							</table>
						</div>
						<!-- //blist --> 

<?
//주문번호 확인 => 주문번호가 있어야만 주문가능
if($arrList["list"][0]['order_no'] !=""){

//아래 결제대행사 파일에서 사용하는 변수
//주문요약 정보
if($arrList["total"]==1){
	$order_summary = $arrList["list"][0]["subject"];
}else{
	$order_summary = $arrList["list"][0]["subject"] . " 외 " . ($arrList["total"]-1). "건";
}

//결제금액
$payPrice = $totalPrice+$shipPrice;

//주문번호
$order_no = $arrList["list"][0]['order_no'];
?>

<form name="frmOrderForm" method="post">
<input type="hidden" name="order_no" value="<?=$order_no?>">
<input type="hidden" id="coupon_idx" name="coupon_idx" value="">
<input type="hidden" id="giftcard_idx" name="giftcard_idx" value="">
<input type="hidden" id="hiddenMyPoint" name="hiddenMyPoint" value="<?=$nowPoint[nowpoint]?>">
<input type="hidden" id="hiddenPayAmount" name="hiddenPayAmount" value="<?=$payPrice?>">
<input type="hidden" id="pointunit" name="pointunit" value="<?=$pointunit?>">
<input type="hidden" id="addpoint" name="addpoint" value="">

						<div class="orderInfo">
							
							<?
							if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){//회원 로그인 시에만
							?>	
							<p class="tit"><span class="titName">주문자 정보 입력</span><span class="myAddr"><a href="#addrNew" class="fancybox">나의 배송주소록</a></span></p>

							<input type="hidden" name="order_name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>">
							<input type="hidden" name="order_email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
							<input type="hidden" name="order_phone1" value="<?=$arrPhone[0]?>">
							<input type="hidden" name="order_phone2" value="<?=$arrPhone[1]?>">
							<input type="hidden" name="order_phone3" value="<?=$arrPhone[2]?>">
							<input type="hidden" name="order_zip" value="<?=$arrMemInfo["list"][0]['zip']?>">
							<input type="hidden" name="order_address" value="<?=$arrMemInfo["list"][0]['address']?>">
							<input type="hidden" name="order_address_ext" value="<?=$arrMemInfo["list"][0]['address_ext']?>">

							<div class="memberOrder">
								<div class="destination">
									<div class="orderWrite">
										<table>
											<colgroup>
												<col width="100px" />
												<col width="*" />
											</colgroup>
											<tbody>
												<tr>
													<th scope="row">배송지 선택</th>
													<td>
														<input type="radio" name="addArr" id="addArr1" value="0" onClick="shipInfoNew()" />새로운 배송지 
														<input type="radio" name="addArr" id="addArr2" value="1" onClick="defaultAddress()" />기본 배송지
													</td>
												</tr>
												<tr>
													<th scope="row">전화번호</th>
													<td>
														<input type="text" id="ship_phone1" name="ship_phone1" value="<?=$arrPhone[0]?>" style="width:75px;" maxlength="4" /> -
														<input type="text" id="ship_phone2" name="ship_phone2" value="<?=$arrPhone[1]?>" style="width:106px;" maxlength="4" /> -
														<input type="text" id="ship_phone3" name="ship_phone3" value="<?=$arrPhone[2]?>" style="width:106px;" maxlength="4" />
													</td>
												</tr>
												<tr>
													<th scope="row">배송지</th>
													<td>
														<span class="disB mb10">
															<input type="text" id="ship_postcode" name="ship_zip" value="<?=$arrMemInfo["list"][0]['zip']?>" style="width:194px;" maxlength="5" />
															<a href="javascript:execDaumPostcode(2);" class="checkBtn">우편번호 검색</a>
														</span>
														<span class="disB mb10"><input type="text" id="ship_address" name="ship_address" value="<?=$arrMemInfo["list"][0]['address']?>" class="w100" /></span>
														<span class="disB mb10"><input type="text" id="ship_address2" name="ship_address_ext" value="<?=$arrMemInfo["list"][0]['address_ext']?>" class="w100" /></span>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- //orderWrite -->
								</div>
								<!-- //destination -->

								<div class="recipient">
									<div class="orderWrite">
										<table>
											<colgroup>
												<col width="100px" />
												<col width="*" />
											</colgroup>
											<tbody>
												<tr>
													<th scope="row">수령인</th>
													<td><input type="text" id="ship_name" name="ship_name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>" style="width:187px;" /></td>
												</tr>
												<tr>
													<th scope="row">휴대전화</th>
													<td>
														<input type="text" id="ship_mobile1" name="ship_mobile1" value="<?=$arrMobile[0]?>" style="width:75px;" maxlength="4" /> -
														<input type="text" id="ship_mobile2" name="ship_mobile2" value="<?=$arrMobile[1]?>" style="width:105px;" maxlength="4" /> -
														<input type="text" id="ship_mobile3" name="ship_mobile3" value="<?=$arrMobile[2]?>" style="width:105px;" maxlength="4" />
													</td>
												</tr>
												<tr>
													<th scope="row">배송시 <br />요구사항</th>
													<td>
														<textarea name="order_comment" id="order_comment"></textarea>
														<p class="help_t">부재시 연락가능한 전화번호 또는 상품수령이 가능한 장소를남겨주세요.</p>
													</td>
												</tr>	
											</tbody>
										</table>
									</div>
									<!-- //orderWrite -->
								</div>
								<!-- //recipient -->
							</div>
							<!-- //memberOrder -->
							<!-- //회원주문입력 -->

							<?} else {?>
							<div class="nonmemberOrder">
								<div class="orderer">
									<p class="tit">주문자 정보 입력</p>
									<div class="in">
										<div class="orderWrite">
											<table>
												<colgroup>
													<col width="100px" />
													<col width="*" />
												</colgroup>
												<tbody>
													<tr>
														<th scope="row">주문자</th>
														<td><input type="text" id="order_name" name="order_name" value="" style="width:187px;" /></td>
													</tr>
													<tr>
														<th scope="row">전화번호</th>
														<td>
															<input type="text" id="order_phone1" name="order_phone1" value="" style="width:75px;" maxlength="4" /> -
															<input type="text" id="order_phone2" name="order_phone2" value="" style="width:105px;" maxlength="4" /> -
															<input type="text" id="order_phone3" name="order_phone3" value="" style="width:105px;" maxlength="4" />
														</td>
													</tr>
													<tr>
														<th scope="row">이메일</th>
														<td>
															<input type="text" id="order_email" name="order_email" value="" style="width:300px;" />
															
														</td>
													</tr>	
													<tr>
														<th scope="row">주문 비밀번호</th>
														<td>
															<input type="password" id="order_pw" name="order_pw" value="" class="w100 mb10" maxlength="15" />
															<p class="help_t mb5">(영문, 숫자, 특수문자 가능 4자~15자)</p>
														</td>
													</tr>
													<tr>
														<th scope="row">비밀번호 확인</th>
														<td><input type="password" id="order_pw2" name="order_pw2" value="" class="w100" maxlength="15" /></td>
													</tr>
													<tr>
														<td colspan="2">
															<div class="help_t mt20">
																<p>비회원님의 안전한 주문관리를 위해 주문비밀번호를 등록해주세요.</p>
																<p class="mb25">비회원님의 주문배송조회를 위한 로그인은 결제번호와 비밀번호로 하실 수
																있습니다.</p>

																<p class="mb25">결제번호와 비밀번호 및 구매내역에 관한 정보는 등록하신 이메일로
																자동발송되며,올바르지 않은 주문회원정보는 배송지연 및 구매손실을 가져올
																수 있으므로 정확히 입력해주시기 바랍니다.</p>

																<p>만14세 이상만 구매 가능합니다.</p>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										<!-- //orderWrite -->	
									</div>
									<!-- //in -->	
								</div>
								<!-- //orderer -->	
								<div class="destination">
									<p class="tit">배송지 정보 입력</p>
									<div class="in">
										<div class="orderWrite">
											<table>
												<colgroup>
													<col width="100px" />
													<col width="*" />
												</colgroup>
												<tbody>
													<tr>
														<th scope="row">수령인</th>
														<td><input type="text" id="ship_name" name="ship_name" value="" style="width:187px;" /></td>
													</tr>
													<tr>
														<th scope="row">전화번호</th>
														<td>
															<input type="text" id="ship_phone1" name="ship_phone1" value="" style="width:75px;" maxlength="4" maxlength="4" /> -
															<input type="text" id="ship_phone2" name="ship_phone2" value="" style="width:105px;" maxlength="4" maxlength="4" /> -
															<input type="text" id="ship_phone3" name="ship_phone3" value="" style="width:105px;" maxlength="4" maxlength="4" />
														</td>
													</tr>
													<tr>
														<th scope="row">휴대번호</th>
														<td>
															<input type="text" id="ship_mobile1" name="ship_mobile1" value="" style="width:75px;" maxlength="4" maxlength="4" /> -
															<input type="text" id="ship_mobile2" name="ship_mobile2" value="" style="width:105px;" maxlength="4" maxlength="4" /> -
															<input type="text" id="ship_mobile3" name="ship_mobile3" value="" style="width:105px;" maxlength="4" maxlength="4" />
														</td>
													</tr>
													<tr>
														<th scope="row">배송지</th>
														<td>
															<span class="disB mb10">
																<input type="text" id="ship_postcode" name="ship_zip" value="" style="width:192px;" maxlength="5" />
																<a href="javascript:execDaumPostcode(2);" class="checkBtn">우편번호 검색</a>
															</span>
															<span class="disB mb10"><input type="text" id="ship_address" name="ship_address" value="" class="w100" /></span>
															<span class="disB mb10"><input type="text" id="ship_address2" name="ship_address_ext" value="" class="w100" /></span>
														</td>
													</tr>
													<tr>
														<th scope="row">배송시 <br />요구사항</th>
														<td>
															<textarea name="order_comment" id="order_comment"></textarea>
															<p class="help_t">부재시 연락가능한 전화번호 또는 상품수령이 가능한 장소를남겨주세요.</p>
														</td>
													</tr>	
												</tbody>
											</table>
										</div>
										<!-- //orderWrite -->	
									</div>
									<!-- //in -->	
								</div>
								<!-- //destination -->
							</div>
							<!-- //nonmemberOrder -->
							<?}?>
							<?
							if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]) {
								// 포인트 사용제한 처리
								// 관리자 설정포인트보다 작을 경우
								if ($nowPoint[nowpoint]==0 || $nowPoint[nowpoint]<$_SITE["SHOP"]["POINT"]["LOW_ACCOUNT"]|| $totalPrice<$_SITE["SHOP"]["POINT"]["LOW_PRICE"]){
								//if ($nowPoint[nowpoint]==0 || $payPrice < 50000){
									$temp_point_use="disabled";
								}else{
									$temp_point_use="";
								}
							?>
							<p class="tit">쿠폰/상품권</p>
							<div class="methodPayment">
								<ul>
								쿠 &nbsp;&nbsp;&nbsp;폰 : <input type="text" id="coupon_price" name="coupon_price" value="0" style="width:200px;text-align:right" onfocus="blur()" readonly/>원&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#couponPopup" class="checkBtn fancybox">쿠폰 사용</a> &nbsp;&nbsp;&nbsp;
								<a href="javascript:couponCancel()" class="checkBtn">쿠폰사용 취소</a>
								</ul>
								<br>
								<ul>
								상품권 : <input type="text" id="giftcard_price" name="giftcard_price" value="0" style="width:200px;text-align:right" onfocus="blur()" readonly/>원&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#giftcardPopup" class="checkBtn fancybox">상품권 사용</a> &nbsp;&nbsp;&nbsp;
								<a href="javascript:giftcardCancel()" class="checkBtn">상품권사용 취소</a>
								</ul>
							</div>
							<br />
							
							<p class="tit">적립금</p>
							<div class="methodPayment">
								<table>
									<colgroup>
										<col width="200px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th scope="row">현재 보유 적립금</th>
											<td><?=number_format($nowPoint[nowpoint])?>원</td>
										</tr>
										<tr>
											<th scope="row">적립금 사용</th>
											<td>
												적립금으로 결재할 금액 <select name="using_point"  id="using_point" <?=$temp_point_use?> onchange="calUsingPoint(this.form, this.value);">
													<? for($i=0; $i<=$nowPoint[nowpoint];$i+=1000) {?>
													<option value="<?=$i?>"><?=number_format($i)?></option>
													<?}?>
												<select>원 <br />
												* 적립금의 경우 보유 적립금이 <span class="point"><?=number_format($_SITE["SHOP"]["POINT"]["LOW_ACCOUNT"])?>점</span> 이상 될 경우에만 사용가능합니다. (1,000점씩 사용가능합니다.)<br />
												* 적립금의 경우 구매 총금액이 <span class="point"><?=number_format($_SITE["SHOP"]["POINT"]["LOW_PRICE"])?>원</span> 이상 될 경우에만 사용가능합니다.
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<br />
							<?} else {?>
							<input type="hidden" name="using_point" id="using_point" value="0">
							<?}?>

							<p class="tit">결제수단선택</p>
							<div class="methodPayment">
								<ul>
									<?foreach($_SITE["SHOP"]["PAY_TYPE"] AS $key => $val){?>
									<li><input type="radio" id="lbl<?=$key?>" type=radio name="pay_type" value="<?=$key?>" onclick="javascript:check_pay_type('<?=$key?>')"/><?=$val?></li>
									<?}?>
								</ul>
								<table id="tblPayInfo" style="display:none">
									<colgroup>
										<col width="100px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th scope="row">입금계좌</th>
											<td>
												<select name="bank_type" id="bankType" class="select" style="width:300px;">
													<option value="">입금하실 계좌를 선택하세요</option>
													<?
													foreach ($_SITE["SHOP"]["BANK"] AS $VAL){
													?>
													<option value="<?=$VAL?>"><?=$VAL?></option>
													<?}?>
												</select>
											</td>
										</tr>
										<tr>
											<th scope="row">입금자명</th>
											<td>
												<input type="text" id="bank_name" name="bank_name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>" style="width:200px;" />
											</td>
										</tr>
										<tr>
											<th scope="row">입금예정일</th>
											<td>
												<input type="text" id="bank_date" name="bank_date" value="<?=date("Y-m-d",strtotime("+1 day"))?>" style="width:200px;" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- //methodPayment -->

						</div>
						<!-- //orderInfo -->
						
						<div class="orderBox">
							<div class="box">
								<p class="tit">상품금액</p>
								<div class="price"><?=number_format($totalPrice)?>원</div>
							</div>
							<!-- //box -->
							
							<div class="box">
								<p class="tit">배송비</p>
								<div class="price"><span>+</span><?=number_format($shipPrice)?>원</div>
							</div>
							<!-- //box -->

							<div class="box">
								<p class="tit">쿠폰/상품권/적립금</p>
								<div class="price" id="shopCouponPrice"><span>-</span>0원</div>
							</div>
							<!-- //box -->

							<div class="box payment">
								<p class="tit">최종결제금액</p>
								<div class="price" id="showPriceTotal"><?=number_format($totalPrice+$shipPrice)?>원</div>
							</div>
							<!-- //box -->
							<div id="addPointTxt" align="right"></div>
						</div>
						<!-- //orderBox -->

					</div>	
					<!-- //cartCon -->
					<div class="btnC">
						<a href="javascript:history.back();" class="btn_gray3">이전단계</a>
						<a href="javascript:check_order_form(document.frmOrderForm,'<?=$_SITE["SHOP"]["PG"]["COMPANY"]?>', 0)" class="btn_gray4">결제하기</a>
					</div>

				
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>


		<!-- 쿠폰/상품권 레이아팝업-->
		<div id="couponPopup" class="popupWrite">
			<p class="tit">쿠폰조회 및 적용하기</p>
			<input type="hidden" id="disPrice" name="disPrice" value="0">
			<div class="blist">
				<table>
					<colgroup>
						<col width="*" />
						<col width="50px" />
						<col width="100px" />
						<col width="100px" />
						<col width="70px" />					
					</colgroup>
					<thead>
						<tr>
							<th scope="col">상품명</th>
							<th scope="col">수량</th>
							<th scope="col">주문금액</th>
							<th scope="col">할인금액</th>
							<th scope="col">쿠폰선택</th>
						</tr>
					</thead>
					<tbody>
						<?
						if($arrList["total"]>0){
						for($i=0;$i<$arrList["total"];$i++){

							$arrListCate = explode("/", $arrList["list"][$i]['cat_code']); 

							$arrOpt1[$i] = explode("|",$arrList["list"][$i]['opt_1']);
							$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
							$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
							$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
							$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
							$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);
							$arrOptRel2[$i] = explode("|",$arrList["list"][$i]['opt_rel_2']);

							//추가금액 계산
							$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'] + $arrOptRel2[$i]['1'] + $arrOptRel2[$i]['1'];
						?>
						<input type="hidden" id="popup_coupon_price_<?=$i?>" name="popup_coupon_price_<?=$i?>" value="0">
						<input type="hidden" id="popup_coupon_idx_<?=$i?>" name="popup_coupon_idx_<?=$i?>">
						<tr>
							<td><?=stripslashes($arrList["list"][$i]['g_name'])?></td>
							<td><?=$arrList["list"][$i]['qty']?></td>
							<td><?=number_format($arrList["list"][$i]['price']+$optionPrice)?>원</td>										
							<td><span id="popcoupon_<?=$i?>">0</span>원</td>
							<td><select id="couponselect_<?=$i?>" name="couponselect_<?=$i?>" onChange="setCoupon(<?=$i?>, this.value)">
							<option value="">쿠폰을 선택해 주세요.</option>
							<?
							if($arrCouponList["total"]>0){
							for($j=0;$j<$arrCouponList["total"];$j++){
								if($arrCouponList["list"][$j][coupon_unit]=="P") {
									if($arrCouponList["list"][$j][over_price] < ($arrList["list"][$i]['price']+$optionPrice) && $arrCouponList["list"][$j][over_price]!="0") {
										$couponprice = ($arrCouponList["list"][$j][coupon_dis]*$arrCouponList["list"][$j][over_price])/100;
									} else {
										$couponprice = ($arrCouponList["list"][$j][coupon_dis]*($arrList["list"][$i]['price']+$optionPrice))/100;
									}
								} else {
									$couponprice = $arrCouponList["list"][$j][coupon_dis];
								}
							?>
							<option value="<?=$arrCouponList["list"][$j][idx]?>|<?=$couponprice?>"><?=stripslashes($arrCouponList["list"][$j][coupon_name])?>(<?=number_format($arrCouponList["list"][$j][coupon_dis])?><?=$arrCouponList["list"][$j][coupon_unit]=="P"?"%":"원"?>)</option>
							<?
							}}

							if($arrCouponList1["total"]>0){
							for($j=0;$j<$arrCouponList1["total"];$j++){
								if($arrCouponList1["list"][$j][coupon_unit]=="P") {

									if($arrCouponList1["list"][$j][over_price] < ($arrList["list"][$i]['price']+$optionPrice) && $arrCouponList1["list"][$j][over_price]!="0") {
										$couponprice1 = ($arrCouponList1["list"][$j][coupon_dis]*$arrCouponList1["list"][$j][over_price])/100;
									} else {
										$couponprice1 = ($arrCouponList1["list"][$j][coupon_dis]*($arrList["list"][$i]['price']+$optionPrice))/100;
									}
								} else {
									$couponprice1 = $arrCouponList1["list"][$j][coupon_dis];
								}

								if($arrCouponList1["list"][$j][cat_code]==$arrListCate[0]."/".$arrListCate[1]."/"){ 
							?>
							<option value="<?=$arrCouponList1["list"][$j][idx]?>|<?=$couponprice1?>"><?=stripslashes($arrCouponList1["list"][$j][coupon_name])?>(<?=number_format($arrCouponList1["list"][$j][coupon_dis])?><?=$arrCouponList1["list"][$j][coupon_unit]=="P"?"%":"원"?>)</option>
							<?
								}
							}}
							?>
							</select></td>
						</tr>
						<?	
							}
						}?>
					</tbody>
				</table>
			</div>
			<div class="blist">
				<table>
					<colgroup>
						<col width="200px" />
						<col width="400px" />
					</colgroup>
					<tbody>
					<td>쿠폰할인금액</td>
					<td><span id="pop_coupon_total">0</span>원</td>
					</tbody>
				</table>
			</div>
			<div class="btnC">
				<a href="javascript:inPrice();" class="btn_gray4">적용하기</a>
			</div>
		</div>

		<div id="giftcardPopup" class="popupWrite">
		<p class="tit">상품권 사용</p>
		<input type="hidden" id="getIdx2" name="getIdx2">
		<input type="hidden" id="disPrice2" name="disPrice2" value="0">
			<div class="blist">
				<table>
					<colgroup>
						<col width="*" />
						<col width="250px" />
						<col width="100px" />
						<col width="50px" />					
					</colgroup>
					<thead>
						<tr>
							<th scope="col">상품권명</th>
							<th scope="col">기간</th>
							<th scope="col">할인액</th>
							<th scope="col">사용</th>
						</tr>
					</thead>
					<tbody>
						<?
						if($arrGiftcardList["total"]>0){
							for($i=0;$i<$arrGiftcardList["total"];$i++){
								if($arrGiftcardList["list"][$i]['giftcard_unit']=="P") {
									if($arrGiftcardList["list"][$i]['over_price'] < $payPrice && $arrGiftcardList["list"][$i]['over_price']!="0") {
										$giftcardprice = ($arrGiftcardList["list"][$i]['giftcard_dis']*$arrGiftcardList["list"][$i]['over_price'])/100;
									} else {
										$giftcardprice = ($arrGiftcardList["list"][$i]['giftcard_dis']*$totalPrice)/100;
									}
								} else {
									$giftcardprice = $arrGiftcardList["list"][$i]['giftcard_dis'];
								}
						?>
						<tr>
							<td><?=stripslashes($arrGiftcardList["list"][$i]['giftcard_name'])?></td>
							<td><?=$arrGiftcardList["list"][$i]['giftcard_sdate']?> ~ <?=$arrGiftcardList["list"][$i]['giftcard_edate']?></td>
							<td><?=number_format($arrGiftcardList["list"][$i]['giftcard_dis'])?><?=$arrGiftcardList["list"][$i]['giftcard_unit']=="P"?"%":"원"?></td>										
							<td><input type="radio" id="giftcard_check1" name="giftcard_check" value="true" onclick="setPrice2('<?=$arrGiftcardList["list"][$i]['idx']?>|<?=$giftcardprice?>')"></td>
						</tr>
						<?	
							}
						}?>	

						<?
						if($arrGiftcardList1["total"]>0){
							for($i=0;$i<$arrGiftcardList1["total"];$i++){
								if($arrGiftcardList1["list"][$i]['giftcard_unit']=="P") {
									if($arrGiftcardList1["list"][$i]['over_price'] < $payPrice && $arrGiftcardList1["list"][$i]['over_price']!="0") {
										$giftcardprice = ($arrGiftcardList1["list"][$i]['giftcard_dis']*$arrGiftcardList1["list"][$i]['over_price'])/100;
									} else {
										$giftcardprice = ($arrGiftcardList1["list"][$i]['giftcard_dis']*$totalPrice)/100;
									}
								} else {
									$giftcardprice = $arrGiftcardList1["list"][$i]['giftcard_dis'];
								}

								if(in_array($arrGiftcardList1["list"][$i]['cat_code'], $chkCate)){ 
						?>
						<tr>
							<td><?=stripslashes($arrGiftcardList1["list"][$i]['giftcard_name'])?></td>
							<td><?=$arrGiftcardList1["list"][$i]['giftcard_sdate']?> ~ <?=$arrGiftcardList1["list"][$i]['giftcard_edate']?></td>
							<td><?=number_format($arrGiftcardList1["list"][$i]['giftcard_dis'])?><?=$arrGiftcardList1["list"][$i]['giftcard_unit']=="P"?"%":"원"?></td>										
							<td><input type="radio" id="giftcard_check2" name="giftcard_check" value="true" onclick="setPrice2('<?=$arrGiftcardList1["list"][$i]['idx']?>|<?=$giftcardprice?>')"></td>
						</tr>
						<?	
								}
							}
						}

						if($arrGiftcardList["total"]<=0 && $arrGiftcardList1["total"]<=0) {
						?>
						<tr height="100">
							<td colspan="5" align="center">등록된 상품권이 없습니다.</td>
						</tr>
						<?}?>	
					</tbody>
				</table>
			</div>
			<div class="btnC">
				<a href="javascript:inPrice2();" class="btn_gray4">적용하기</a>
			</div>
			<!-- //btnR -->
		</div>
		<!-- //popupWrite -->

		<!-- 신규등록 레이아팝업-->
		<div id="addrNew" class="popupWrite2">
			<p class="tit">나의 배송주소록</p>
			<div class="blist">
				<table>
					<colgroup>
						<col width="50px" />
						<col width="120px" />	
						<col width="100px" />
						<col width="250px" />
						<col width="*" />
					</colgroup>
					<thead>
						<tr>
							<th scope="col">선택</th>
							<th scope="col">배송지</th>
							<th scope="col">수령인</th>
							<th scope="col">전화번호/핸드폰</th>
							<th scope="col">주소</th>
						</tr>
					</thead>
					<tbody>
						<?
						for($i=0; $i<$arrAddressList["total"]; $i++) {
							$arrAddrPhone = explode("-", $arrAddressList['list'][$i]['phone']);
							$arrAddrMobile = explode("-", $arrAddressList['list'][$i]['mobile']);
						?>
						<tr>
							<td><input type="radio" id="chkAddr" name="chkAddr" onclick="chkAddress('<?=$arrAddressList['list'][$i]['name']?>','<?=$arrAddressList['list'][$i]['zip']?>','<?=$arrAddressList['list'][$i]['address']?>','<?=$arrAddressList['list'][$i]['address_ext']?>','<?=$arrAddrPhone[0]?>','<?=$arrAddrPhone[1]?>','<?=$arrAddrPhone[2]?>','<?=$arrAddrMobile[0]?>','<?=$arrAddrMobile[1]?>','<?=$arrAddrMobile[2]?>')" /></td>
							<td><?=$arrAddressList['list'][$i]['shipping']?><br><?=$arrAddressList['list'][$i]['d_addr']=="Y"?"기본배송지":""?></td>
							<td><?=$arrAddressList['list'][$i]['name']?></td>
							<td><?=$arrAddressList['list'][$i]['phone']?> / <?=$arrAddressList['list'][$i]['mobile']?></td>
							<td>(<?=$arrAddressList['list'][$i]['zip']?>) <?=stripslashes($arrAddressList['list'][$i]['address'])?> <?=stripslashes($arrAddressList['list'][$i]['address_ext'])?></td>
						</tr>
						<?}?>
					</tbody>
				</table>
			</div>
		</div>
	</form>

<iframe name="hiddenFrame" frameborder=0 width=0 height=0 border=0></iframe>
<script>
function setCoupon(num, str) {
	var optArray = new Array();
	optArray = str.split("|");	

	if(str) {
		//쿠폰중복사용체크
		for(i=0; i < <?=$arrList["total"]?>; i++) {
			if(document.getElementById("popup_coupon_idx_"+i).value == optArray[0]) {
				alert("선택하신 쿠폰은 이미 다른상품에 할인을 적용하셨습니다.\r\n할인방법을 변경하고 싶으시면 먼저 선택되어진 할인적용을 취소하시기 바랍니다.");
				var selectbox = document.getElementById("couponselect_"+num); 
				selectbox.options[0].selected = true; 
				return ;
			}
		}

		document.getElementById("popup_coupon_idx_"+num).value = optArray[0];
		document.getElementById("popup_coupon_price_"+num).value = optArray[1];
		document.getElementById("popcoupon_"+num).innerHTML = add_comma(optArray[1]);
	} else {
		document.getElementById("popup_coupon_price_"+num).value = "0";
		document.getElementById("popcoupon_"+num).innerHTML = "0";
		document.getElementById("popup_coupon_idx_"+num).value = "";
	}
	


	var pop_total_coupon = "0";
	for(i=0; i < <?=$arrList["total"]?>; i++) {
		if(document.getElementById("popup_coupon_price_"+i).value) {
			var pop_total_coupon = parseInt(pop_total_coupon) + parseInt(document.getElementById("popup_coupon_price_"+i).value);
		}
	}
	document.getElementById("pop_coupon_total").innerHTML = add_comma(pop_total_coupon);
	document.getElementById("disPrice").value = pop_total_coupon;
}

function couponCancel() {
	var payPrice = parseInt(parent.document.getElementById("hiddenPayAmount").value) - parseInt(parent.document.getElementById("using_point").value) - parseInt(document.getElementById("disPrice2").value);
	
	document.getElementById("shopCouponPrice").innerHTML =  "<span>-</span>" + (parseInt(document.getElementById("disPrice2").value) + parseInt(document.getElementById("using_point").value)) + "원";
	
	if(payPrice<0) {
		document.getElementById("showPriceTotal").innerHTML = "0원";
		document.getElementById("addpoint").value = - parseInt(payPrice);
		document.getElementById("addPointTxt").innerHTML = "* 상품권 나머지 금액 "+ add_comma( - parseInt(payPrice)) +"원이 적립금으로 전환됩니다.";
		document.getElementById("using_point").disabled = true;
	} else {
		document.getElementById("showPriceTotal").innerHTML = add_comma(payPrice) + "원";
		document.getElementById("addPointTxt").innerHTML = "";
		document.getElementById("addpoint").value = "";
	}
	document.getElementById("coupon_idx").value = '';
	document.getElementById("disPrice").value = '0';
	document.getElementById("coupon_price").value = '0';
}

function giftcardCancel() {
	var payPrice = parseInt(parent.document.getElementById("hiddenPayAmount").value) - parseInt(parent.document.getElementById("using_point").value) - parseInt(document.getElementById("disPrice").value);
	document.getElementById("shopCouponPrice").innerHTML =  "<span>-</span>" + (parseInt(document.getElementById("disPrice").value) + parseInt(document.getElementById("using_point").value)) + "원";
	if(payPrice<0) {
		document.getElementById("showPriceTotal").innerHTML = "0원";
		document.getElementById("addpoint").value = - parseInt(payPrice);
		document.getElementById("addPointTxt").innerHTML = "* 상품권 나머지 금액 "+ add_comma( - parseInt(payPrice)) +"원이 적립금으로 전환됩니다.";
		document.getElementById("using_point").disabled = true;
	} else {
		document.getElementById("showPriceTotal").innerHTML = add_comma(payPrice) + "원";
		document.getElementById("addPointTxt").innerHTML = "";
		document.getElementById("addpoint").value = "";
	}

	document.getElementById("getIdx2").value = '';
	document.getElementById("disPrice2").value = '0';
	document.getElementById("giftcard_price").value = '0';
	document.getElementById("using_point").disabled = false;
}
</script>

		</div>
	</div>
<?
}//주문번호 확인 => 주문번호가 있어야만 주문가능
?>
