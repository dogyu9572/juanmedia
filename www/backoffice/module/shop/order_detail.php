<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu_order.php";

if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getOrderInfoAdmin(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_no"]));
$arrCouponInfo = getMyCouponInfo($arrInfo['list'][0]['coupon_idx']);

$arrUserInfo = getUserInfo($arrInfo["list"][0]['order_id']);

//DB해제
//	SetDisConn($dblink);

$bePay = 0;		## 총 주문금액
$totalPay = 0;	## 할인금액 구할용도 실상품금액
for($i=0;$i<$arrInfo["good_total"];$i++){		
	$thisPay = $arrInfo["good_list"][$i]["g_price"]*$arrInfo["good_list"][$i]["g_qty"];	
	$thisBePay = $arrInfo["good_list"][$i]["p_price"]*$arrInfo["good_list"][$i]["g_qty"];	
	$totalPay += $thisPay;
	$bePay += $thisBePay;
}
$disPay = $bePay - $totalPay;	## 기본할인
$totalDispay = $arrInfo['list'][0]['coupon_amount']+$arrInfo['list'][0]['using_point'];	## 총 할인금액
//	$Pay = $bePay - $totalDispay;	## 총 결제금액

//$totalDispay = $arrInfo['list'][0]['total_amount'] - $arrInfo['list'][0]['pay_amount'];	## 기본할인


if($arrInfo["total"] < 1){
	jsGo("/backoffice/module/shop/order.php?mode=1","","");
	exit();
}
?>
<script src='https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js'></script>
<script type="text/javascript">
<!--
function execDaumPostcode(pr_zip, pr_Add1, pr_Add2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var addr = ''; // 주소 변수
			var extraAddr = ''; // 참고항목 변수

			//사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				addr = data.roadAddress;
			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				addr = data.jibunAddress;
			}
			// 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
			if(data.userSelectedType === 'R'){
				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
					extraAddr += data.bname;
				}
				// 건물명이 있고, 공동주택일 경우 추가한다.
				if(data.buildingName !== '' && data.apartment === 'Y'){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if(extraAddr !== ''){
					extraAddr = ' (' + extraAddr + ')';
				}
				// 조합된 참고항목을 해당 필드에 넣는다.
//				document.getElementById(pr_Add1).value = extraAddr;			
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(pr_zip).value = data.zonecode;
			document.getElementById(pr_Add1).value = addr + " " + extraAddr;
			// 커서를 상세주소 필드로 이동한다.
			document.getElementById(pr_Add2).focus();			
		}
	}).open();
}	
//-->
</script>
<script type="text/javascript">
<!--
function besongSearch(){				
	var besongIdx = document.frmOrderInfo.shipping_no.value;
	if(besongIdx.length < 10){
		alert('정확한 송장번호를 입력하세요.');
		document.frmOrderInfo.shipping_no.focus();
	}else{
		window.open('http://www.ilogen.com/m/personal/trace.pop/'+besongIdx,'','width=1200, height=800, scrollbars=yes');
		//window.open('https://www.lotteglogis.com/open/tracking?invno='+besongIdx,'','width=800, height=600, scrollbars=yes');
	}
}	
function fnFrmCheck(){
	var frm = document.frmOrderInfo;
	frm.submit();
}
function fnPayCancel(htype, ono, tid, price, tpay, cancelPay){		
	var rem_mny = 0;
	if(htype=="STPC"){
		cfm = confirm("해당 주문을 부분환불 처리하시겠습니까?");
		rem_mny = tpay;
	}else{
		cfm = confirm("해당 주문을 전체환불 처리하시겠습니까?");
	}
	//	alert(htype+'|'+ono+'|'+tid+'|'+price+'|'+rem_mny+'|'+cancelPay);
	if(cfm==true){
		var rflag = fnAjaxPGApi(htype, ono, tid, price, rem_mny, cancelPay);
	}
}
function fnAjaxPGApi(mode, ono, tid, mod_mny, rem_mny, cancelPay){
	var apiUrl = "/_api/_pg/nhn_kcp/sample/cancel.php";
	//	alert(mode+"/"+tid+"/"+price);
	/************************************/
	$.post(apiUrl, {
		"order_no":ono,
		"tno":tid,
		"mod_type":mode,
		"mod_mny":mod_mny,
		"rem_mny":rem_mny,
		"cancelPay":cancelPay
	}, function(data){
		//	alert(data);		
		if(data=="true"){
			alert('환불되었습니다.');
			location.reload();
		}else{
			alert(data);	
		}
	});	
}
//-->
</script>
<?######################################### iframe fancybox ######################################### ST?>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
<style type="text/css">
.fancybox__content { padding: 5px 0;border-radius: 4px; }
.fancybox__slide {padding-bottom:20px;}
</style>
<script type="text/javascript">
<!--
function fnOrderGoodAdd(orderno)
{
	Fancybox.show([
	{
		src: "/backoffice/module/shop/pop_good.php?orderno="+orderno,
		type: "iframe",
		preload: false,
		width: 1200,
		height: 800
	},
	]);
}	
//-->
</script>
<?######################################### iframe fancybox ######################################### ED?>
<div class="container">

	<div class="title">주문 수정</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmOrderInfo" id="frmOrderInfo" method="post" action="order_evn.php">
			<input type="hidden" name="evnMode" value="update">
			<input type="hidden" name="order_no" value="<?=$arrInfo["list"][0]["order_no"]?>">			
			<input type="hidden" name="rt_url" value="/backoffice/module/shop/<?=$_GET['cm']?"order_list2.php":"order.php"?>?rlist=T">
			<input type="hidden" name="pay_point" value="<?=$arrInfo["list"][0]["pay_point"]=="N"?"Y":"N"?>"><?#################### 결제완료시 적립금 적립 확인용 ##?>
			<input type="hidden" name="stock_apply" value="<?=$arrInfo["list"][0]["stock_apply"]=="N"?"Y":"N"?>"><?################ 재고수량 변경용 ################?>

			<div class="tit">주문정보 <i>*</i></div>
			<table>
				
				<tr>
					<th>주문일시</th>
					<td><em><?=$arrInfo["list"][0]['order_date']?></em></td>
				</tr>
				<tr>
					<th>주문번호</th>
					<td><em><?=$arrInfo["list"][0]['order_no']?></em></td>
				</tr>
				<tr>
					<th>주문자(ID)</th>
					<td><em><?=$arrUserInfo["list"][0]['user_name']?> ( <?=$arrInfo["list"][0]['order_id']?> )</em></td>
				</tr>
				<tr>
					<th>상호(거래처등록번호)</th>
					<td><em><?=$arrInfo["list"][0]['order_cname']?> <?=$arrInfo["list"][0]['order_cust']?"( ".$arrInfo["list"][0]['order_cust']." )":""?></em></td>
				</tr>
				<tr>
					<th>주문자 휴대폰번호</th>
					<td><em><?=$arrUserInfo["list"][0]['mobile']?></em></td>
				</tr>
				<tr>
					<th>주문자 이메일</th>
					<td><em><?=$arrInfo["list"][0]['order_email']?></em></td>
				</tr>
				<tr>
					<td colspan="2"><div class="tit">배송정보 <i>*</i></div></td>
				</tr>
				<tr style="display:none;">
					<th>운송장번호</th>
					<td>
						<div class="inputs"><input type="text" class="w3" id="shipping_no" name="shipping_no" value='<?=stripslashes($arrInfo["list"][0]["shipping_no"])?>' maxlength="50" class="input" />
						&nbsp;<button type="button" class="delivery" onclick="besongSearch()">배송추적</button></div>
					</td>
				</tr>
				<tr>
					<th>진행상태</th>
					<td>
					  <select name="order_state">
						  <?
						  foreach ($_SITE["SHOP"]["ORDER_STATE"] AS $key => $val){
							  if($key<30){
						  ?>
						  <option value="<?=$key?>"<?=$arrInfo["list"][0]["order_state"]==$key?" selected":""?>><?=$val?></option>
						  <?}}?>
					  </select>	
					  <!--&nbsp;&nbsp;<label class="check"><input type="checkbox" id="allim" name="allim" value="Y"><i></i>알림톡 발송시 체크 ( 입금완료 )</label>-->
					</td>
				</tr>
				<tr>
					<th>알림톡</th>
					<td>
						<?=$arrInfo["list"][0]["mail_sms"]=="send"?"배송중 알림톡 발송됨":"미발송"?>
						<?=$arrInfo["list"][0]["send_date"]?"(".$arrInfo["list"][0]["send_date"].")":""?>
					</td>
				</tr>
				<style type="text/css">
					.silver{background: #cccccc;}
				</style>
				<tr>
					<th>발송희망일</th>
					<td>
					<input type="text" name="shipping_date" value='<?=$arrInfo["list"][0]["shipping_date"]=="0000-00-00"?"":$arrInfo["list"][0]["shipping_date"]?>' maxlength="10" class="w2 datepicker" />
					</td>
				</tr>
				<tr style="display:none;">
					<th>수취인</th>
					<td><div class="inputs"><input type="text" class="w4" name="ship_name" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['ship_name'])?>"></div></td>
				</tr>
				<input type="hidden" name="ship_zip" id="ship_zip" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['ship_zip'])?>" readonly>
				<tr>
					<th>주소</th>
					<td><div class="inputs"><input type="text" class="w4" name="ship_address" id="ship_address" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['ship_address'])?>" readonly onclick="execDaumPostcode('ship_zip','ship_address','ship_address_ext')" ></div>
					<div class="inputs" style="padding-top:4px;"><input type="text" class="w4" name="ship_address_ext" id="ship_address_ext" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['ship_address_ext'])?>"></div></td>
				</tr>
				<tr>
					<th>휴대폰번호</th>
					<td><div class="inputs"><input type="text" class="w4" name="ship_mobile" maxlength="20" value="<?=stripslashes($arrUserInfo["list"][0]['mobile'])?>"></div></td>
				</tr>
				<tr>
					<th>이메일</th>
					<td><div class="inputs"><input type="text" class="w4" name="ship_email" maxlength="20" value="<?=stripslashes($arrUserInfo["list"][0]['email'])?>"></div></td>
				</tr>
				<tr style="display:none;">
					<th>배송메시지</th>
					<td><div class="inputs"><input type="text" class="w4" name="order_comment" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['order_comment'])?>"></div></td>
				</tr>
				<tr>
					<td colspan="2"><div class="tit">주문상품 정보 <i>*</i></div></td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="bdr_list tac" style="width:80%;">
							<table>
								<colgroup>
									<col width="10%">
									<col width="20%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
									<col width="10%">
								</colgroup>
								<thead>
								<tr>								
									<td style="text-align:left;">										
										<a href="javascript:void(0);" class="btn refund" onclick="fnOrderGoodAdd('<?=$arrInfo["list"][0]['order_no']?>')">제품추가</a>
									</td>	
									<td style="text-align:right;" colspan="9">										
										<select name="order_status_all" style="width:130px;">
										<option value="">전체상태 일괄변경</option>
										<?
										foreach ($_SITE["SHOP"]["ORDER_STATE"] AS $key => $val){
											if($key<11){
												 if($key==10){}else{
										?>
										<option value="<?=$key?>"><?=$val?></option>
										<?}}}?>										
										</select>	
										<?if($arrInfo["list"][0]['tid'] && $arrInfo["list"][0]['claim_amount']<1){?>
										<a href="javascript:void(0);" class="btn refund" onclick="fnPayCancel('STSC', '<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>', '<?=$arrInfo['list'][0]['pay_amount']?>', 0, 0)">전체환불</a>
										<?}?>
										<?//=$arrInfo["list"][0]['claim_amount']?>
									</td>	
								</tr>
								<!--<tr>								
									<td style="text-align:right;" colspan="7">
										<select name="order_status_all" style="width:130px;">
										<option value="">전체상태 일괄변경</option>
										<?
										foreach ($_SITE["SHOP"]["ORDER_STATE"] AS $key => $val){
											if($key<11){
												 if($key==3 || $key==5){}else{
										?>
										<option value="<?=$key?>"><?=$val?></option>
										<?}}}?>										
										</select>				
										<a href="javascript:void(0);" class="btn refund" onclick="fnPayCancel('<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>', '','')">전체환불</a>
									</td>				
								</tr>-->
								<tr>							
									<th style="text-align:center;" colspan="2">제품명</th>
									<th style="text-align:center;padding-left:0">품목코드</th>
									<th style="text-align:center;padding-left:0;">수량</th>
									<th style="text-align:center;padding-left:0">단가</th>
									<th style="text-align:center;padding-left:0">공급가액</th>
									<th style="text-align:center;padding-left:0">부가세</th>
									<th style="text-align:center;padding-left:0">총 상품금액</th>
									<th style="text-align:center;padding-left:0">결제상태</th>
									<th style="text-align:center;padding-left:0">관리</th>
								</tr>
								<tbody>	
								<?
								$sql = "select a_class from tbl_member where user_id='".$arrInfo["list"][0]['order_id']."'";
								$arrUserInfo = getFreeQueryR($sql);

								if($arrInfo["good_total"]>0){
									for($i=0;$i<$arrInfo["good_total"];$i++){
										$arrInfo["good_list"][$i]['imgUrl'] = "/uploaded/shop_good/".$arrInfo["good_list"][$i]['g_idx']."/".$arrInfo["good_list"][$i]['image_s'];
										if(!$arrInfo["good_list"][$i]['image_s']){
											$arrInfo["good_list"][$i]['imgUrl'] = "/pub/images/no_image.svg";
										}
										if($arrInfo["good_list"][$i]['order_status']=="X" || $arrInfo["good_list"][$i]['order_status']=="R"){
											$viewState = $arrInfo["list"][0]["order_state"];
										}else{
											$viewState = $arrInfo["good_list"][$i]['order_status'];										
										}
										if($arrInfo["good_list"][$i]["g_vendor"]=="admin"){
											$arrInfo["list"][$i]["show_price"] = "0";
										}else{
											$arrChoice	= explode("|",$arrInfo["good_list"][$i]['member_choice']);
											$arrPrice	= explode("|",$arrInfo["good_list"][$i]['member_price']);
											$arrSale	= explode("|",$arrInfo["good_list"][$i]['member_sale']);	
											for($j=0;$j<count($arrChoice);$j++){
												if($arrUserInfo['list'][0]['a_class']==$arrChoice[$j]){
													$arrInfo["list"][$i]["show_gong"]		= $arrPrice[$j];
													$arrInfo["list"][$i]["show_tax"]		= $arrInfo["list"][$i]["show_gong"]*0.1*$arrInfo["good_list"][$i]['g_qty'];
													$arrInfo["list"][$i]["show_price"]		= $arrInfo["list"][$i]["show_gong"]*$arrInfo["good_list"][$i]['g_qty'];													
													$arrInfo["list"][$i]["show_won_price"]	= $arrInfo["list"][$i]["show_price"]+$arrInfo["list"][$i]["show_tax"];
												}
											}
										}
								?>
								<tr>							
									<td><img src="<?=$arrInfo["good_list"][$i]['imgUrl']?>" style="max-width:60px;"></td>					
									<td style="text-align:left;"><?=$arrInfo["good_list"][$i]['g_name']?><br/><?=$arrInfo["good_list"][$i]['g_opt_1']?"옵션 : ".$arrInfo["good_list"][$i]['g_opt_1']:""?></td>						
									<td style="text-align:center;"><?=$arrInfo["good_list"][$i]["g_code"]?></td>		
									<td style="text-align:center;"><?=number_format($arrInfo["good_list"][$i]['g_qty'])?></td>									
									<td style="text-align:center;"><?=number_format($arrInfo["list"][$i]["show_gong"])?></td>
									<td style="text-align:center;"><?=number_format($arrInfo["list"][$i]["show_price"])?></td>	
									<td style="text-align:center;"><?=number_format($arrInfo["list"][$i]["show_tax"])?></td>	
									<td style="text-align:center;"><?=number_format($arrInfo["list"][$i]["show_won_price"])?></td>	
									
									<td style="text-align:center;">
									<select name="order_status[]" style="width:110px;">
										<?
										foreach ($_SITE["SHOP"]["ORDER_STATE"] AS $key => $val){
											 if($key<11){
												 if($key==10){}else{
										?>
										<option value="<?=$arrInfo["good_list"][$i]['idx']?>|<?=$key?>"<?=$arrInfo["good_list"][$i]['order_status']==$key?" selected":""?>><?=$val?></option>
										<?}}}?>
										</select>				
									</td>									

									<?if($arrInfo["good_list"][$i]["g_vendor"]=="admin"){?>
									<td style="text-align:center;"><a href="javascript:void(0);" class="btn refund" onclick="fnGoodDel('<?=$arrInfo["good_list"][$i]['idx']?>')">삭제</td>					
									<?}else{?>									
									<td style="text-align:center;">								
									<?
									if($arrInfo["list"][0]['tid']){
										if(($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][$i]["show_won_price"]-$arrInfo["list"][0]['claim_amount'])>0){
										?>
									<a href="javascript:void(0);" class="btn refund" onclick="fnPayCancel('STPC','<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>','<?=$arrInfo["list"][$i]["show_won_price"]?>', '<?=($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][0]['claim_amount'])?>','<?=$arrInfo["list"][0]['claim_amount']?>')">부분환불</a>
									<?}}?>
									</td>					
									<?}?>
								</tr>
								<tr>								
								<?	
									}
								}
								?>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"><div class="tit">결제정보 <i>*</i></div></td>
				</tr>
				<tr>
					<th>결제방법</th>
					<td><em><?=$_SITE["SHOP"]["PAY_TYPE"][$arrInfo["list"][0]['pay_type']]?></em></td>
				</tr>
				<tr style="display:none;">
					<th>입금자명</th>
					<td><div class="inputs"><input type="text" class="w2" name="cash_name" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['cash_name'])?>"></div></td>
				</tr>
				<?if($arrInfo["list"][0]['pay_type']=="cash"){?>
				<tr>
					<th>입금주</th>
					<td><?=stripslashes($arrInfo["list"][0]['bank_name'])?></td>
				</tr>
				<?}else{?>
				<script type="text/javascript">
				<!--
				function openCartBill(){
					window.open("https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=card_bill&tno=<?=stripslashes($arrInfo["list"][0]['tid'])?>&order_no=<?=stripslashes($arrInfo["list"][0]['order_no'])?>&trade_mony=<?=stripslashes($arrInfo["list"][0]['pay_amount'])?>",'nhncartbill','');
					window.open("https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=card_bill&tno=<?=stripslashes($arrInfo["list"][0]['tid'])?>&order_no=<?=stripslashes($arrInfo["list"][0]['order_no'])?>&trade_mony=<?=stripslashes($arrInfo["list"][0]['pay_amount'])?>",'nhncartbill','');
				}
				//-->
				</script>
				<tr>
					<th>매출전표</th>
					<td><a href="javascript:void(0);" onclick="openCartBill()">매출전표바로가기</a></td>
				</tr>
				<?}?>
				<tr>
					<th>결제일</th>
					<td><em><?=$arrInfo["list"][0]['order_date']=="0000-00-00"?"":$arrInfo["list"][0]['order_date']?></em></td>
				</tr>
				<tr>
					<th>총 상품금액</th>
					<td><em><?=number_format($arrInfo['list'][0]['pay_amount']+$arrInfo['list'][0]['coupon_amount'])?>원</em></td>
				</tr>
				<tr>
					<th>할인금액</th>
					<td><?//	=number_format($arrInfo['list'][0]['coupon_amount'] + $arrInfo['list'][0]['using_point'])?>
					<p>총 할인금액 <?=number_format($totalDispay)?>원</p>
					<?if($arrInfo['list'][0]['coupon_amount']){?>
					<!--<p> ┕ 쿠폰할인 : <?=number_format($arrInfo['list'][0]['coupon_amount'])?>원</p>-->
					<?}?>
					<?if($arrInfo['list'][0]['coupon_idx']){?>
					<p> ┕ <?=$arrCouponInfo['list'][0]['coupon_name']?> : <?=number_format($arrInfo['list'][0]['coupon_amount'])?>원</p>
					<?}?>
					<?if($arrInfo['list'][0]['using_point']>0){?>
					<p> ┕ 적립금사용 : <?=number_format($arrInfo['list'][0]['using_point'])?>원</p>
					<?}?>
					</td>
				</tr>				
				<tr>
					<th>총 주문금액(실결제금액)</th>
					<td><em><?=number_format($arrInfo['list'][0]['pay_amount'])?>원</em></td>
				</tr>				
				<?
				##################################################### 1차예약주문이면 ##################################################### ST
				if($arrInfo["list"][0]['reserve_state']=="11"){
					$suqQuery = " AND A.order_no_parent = '".$arrInfo["list"][0]['order_no']."'  ";	## AND A.order_state Not in ('10','1')
					$arrReserveList = getOrderListAll("", "", "", "", "", 0, 0, $suqQuery);
				?>
				<tr>
					<td colspan="2"><div class="tit">예약결제정보 <i>*</i></div></td>
				</tr>
				<tr>
					<th>예약금 결제</th>
					<td><em><?=number_format($arrInfo['list'][0]['pay_amount'])?></em></td>
				</tr>
				<tr>
					<th>예약금 결제방법</th>
					<td><em><?=$_SITE["SHOP"]["PAY_TYPE"][$arrInfo["list"][0]['pay_type']]?></em></td>
				</tr>
				<tr>
					<th>예약금 결제일</th>
					<td><em><?=$arrInfo["list"][0]['order_date']=="0000-00-00"?"":$arrInfo["list"][0]['order_date']?></em></td>
				</tr>

				<tr>
					<th>잔금</th>
					<td><em><?=number_format($arrReserveList['list'][0]['pay_amount'])?></em></td>
				</tr>
				<tr>
					<th>예약금 결제방법</th>
					<td><em><?=$arrReserveList["list"][0]['order_state']=="1"?"":$_SITE["SHOP"]["PAY_TYPE"][$arrReserveList["list"][0]['pay_type']]?></em></td>
				</tr>
				<tr>
					<th>예약금 결제일</th>
					<td><em><?=$arrReserveList["list"][0]['order_state']=="1"?"":$arrReserveList["list"][0]['order_date']?></em></td>
				</tr>				
				<tr>
					<th>위약금(총 주문금액 5%)</th>
					<td><em><?=number_format(($arrInfo['list'][0]['pay_amount']+$arrReserveList['list'][0]['pay_amount'])*0.05)?></em></td>
				</tr>				
				<tr>
					<th>결제 마금일</th>
					<td><em><?=$arrReserveList["list"][0]['ipkum_date']?></em></td>
				</tr>
				<?
				if($arrInfo["list"][0]['tid']){
				?>
				<tr>
					<th>환불금액</th>
					<td><?=number_format($arrInfo["list"][0]['claim_amount'])?></td>
				</tr>
				<tr>
					<th>환불 1차</th>
					<td><div class="inputs"><input type="text" class="w3" id="tmp_amount1" name="tmp_amount1" value='<?=($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][0]['claim_amount'])?>' maxlength="50" class="input" />
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STPC', '<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>', document.frmOrderInfo.tmp_amount1.value, '<?=($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][0]['claim_amount'])?>','<?=$arrInfo["list"][0]['claim_amount']?>')">부분환불</button>
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STSC', '<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>', '<?=$arrInfo['list'][0]['pay_amount']?>', 0, 0)">전체환불</button>
						</div></td>
				</tr>
				<?
				}
				if($arrReserveList["list"][0]['tid']){
				?>
				<tr>
					<th>환불금액</th>
					<td><?=number_format($arrReserveList["list"][0]['claim_amount'])?></td>
				</tr>
				<tr>
					<th>환불 2차</th>
					<td><div class="inputs"><input type="text" class="w3" id="tmp_amount2" name="tmp_amount2" value='<?=($arrReserveList['list'][0]['pay_amount']-$arrReserveList["list"][0]['claim_amount'])?>' maxlength="50" class="input" />
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STPC', '<?=$arrReserveList["list"][0]['order_no']?>','<?=$arrReserveList["list"][0]['tid']?>', document.frmOrderInfo.tmp_amount2.value, '<?=($arrReserveList['list'][0]['pay_amount']-$arrReserveList["list"][0]['claim_amount'])?>','<?=$arrReserveList["list"][0]['claim_amount']?>' )">부분환불</button>
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STSC', '<?=$arrReserveList["list"][0]['order_no']?>','<?=$arrReserveList["list"][0]['tid']?>', '<?=$arrReserveList['list'][0]['pay_amount']?>', 0, 0)">전체환불</button>
						</div></td>
				</tr>				
				<?
					}
				}
				##################################################### 1차예약주문이면 ##################################################### ED				
				##################################################### 2차예약주문이면 ##################################################### ST
				if($arrInfo["list"][0]['reserve_state']=="12"){
					$suqQuery = " AND A.order_no = '".$arrInfo["list"][0]['order_no_parent']."'  ";	## AND A.order_state Not in ('10','1')
					$arrReserveList = getOrderListAll("", "", "", "", "", 0, 0, $suqQuery);
				?>
				<tr>
					<td colspan="2"><div class="tit">예약결제정보 <i>*</i></div></td>
				</tr>
				<tr>
					<th>예약금 결제</th>
					<td><em><?=number_format($arrReserveList['list'][0]['pay_amount'])?></em></td>
				</tr>
				<tr>
					<th>예약금 결제방법</th>
					<td><em><?=$_SITE["SHOP"]["PAY_TYPE"][$arrReserveList["list"][0]['pay_type']]?></em></td>
				</tr>
				<tr>
					<th>예약금 결제일</th>
					<td><em><?=$arrReserveList["list"][0]['order_date']=="0000-00-00"?"":$arrReserveList["list"][0]['order_date']?></em></td>
				</tr>

				<tr>
					<th>잔금</th>
					<td><em><?=number_format($arrInfo['list'][0]['pay_amount'])?></em></td>
				</tr>
				<tr>
					<th>예약금 결제방법</th>
					<td><em><?=$arrInfo["list"][0]['order_state']=="1"?"":$_SITE["SHOP"]["PAY_TYPE"][$arrInfo["list"][0]['pay_type']]?></em></td>
				</tr>
				<tr>
					<th>예약금 결제일</th>
					<td><em><?=$arrInfo["list"][0]['order_state']=="1"?"":$arrInfo["list"][0]['order_date']?></em></td>
				</tr>				
				<tr>
					<th>위약금(총 주문금액 5%)</th>
					<td><em><?=number_format(($arrInfo['list'][0]['pay_amount']+$arrReserveList['list'][0]['pay_amount'])*0.05)?></em></td>
				</tr>				
				<tr>
					<th>결제 마금일</th>
					<td><em><?=$arrInfo["list"][0]['ipkum_date']?></em></td>
				</tr>
				<?
				if($arrReserveList["list"][0]['tid']){
				?>
				<tr>
					<th>환불금액</th>
					<td><?=number_format($arrReserveList["list"][0]['claim_amount'])?></td>
				</tr>
				<tr>
					<th>환불 1차</th>
					<td><div class="inputs"><input type="text" class="w3" id="tmp_amount1" name="tmp_amount1" value='<?=($arrReserveList['list'][0]['pay_amount']-$arrReserveList["list"][0]['claim_amount'])?>' maxlength="50" class="input" />
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STPC', '<?=$arrReserveList["list"][0]['order_no']?>','<?=$arrReserveList["list"][0]['tid']?>', document.frmOrderInfo.tmp_amount1.value, '<?=($arrReserveList['list'][0]['pay_amount']-$arrReserveList["list"][0]['claim_amount'])?>','<?=$arrReserveList["list"][0]['claim_amount']?>' )">부분환불</button>
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STSC', '<?=$arrReserveList["list"][0]['order_no']?>','<?=$arrReserveList["list"][0]['tid']?>', '<?=$arrReserveList['list'][0]['pay_amount']?>', 0, 0)">전체환불</button>
						</div></td>
				</tr>
				<?
				}
				if($arrInfo["list"][0]['tid']){
				?>
				<tr>
					<th>환불금액</th>
					<td><?=number_format($arrInfo["list"][0]['claim_amount'])?></td>
				</tr>
				<tr>
					<th>환불 2차</th>
					<td><div class="inputs"><input type="text" class="w3" id="tmp_amount2" name="tmp_amount2" value='<?=($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][0]['claim_amount'])?>' maxlength="50" class="input" />
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STPC', '<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>', document.frmOrderInfo.tmp_amount2.value, '<?=($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][0]['claim_amount'])?>','<?=$arrInfo["list"][0]['claim_amount']?>' )">부분환불</button>
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STSC', '<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>', '<?=$arrInfo['list'][0]['pay_amount']?>', 0, 0)">전체환불</button>
						</div></td>
				</tr>				
				<?
					}
				}
				##################################################### 2차예약주문이면 ##################################################### ED
				?>
				<?
				##################################################### 환불 신청 사유 ##################################################### ST
				if($arrInfo["list"][0]["order_state"]=="2" || $arrInfo["list"][0]["order_state"]=="3" || $arrInfo["list"][0]["order_state"]=="4" || $arrInfo["list"][0]["order_state"]=="5"){								
				?>
				<tr>
					<td colspan="2"><div class="tit">환불 정보 <i>*</i></div></td>
				</tr>
				<tr>
					<th>환불 처리 날짜</th>
					<td><div class="inputs">
						<input type="text" name="claim_date" value="<?=$arrInfo["list"][0]["claim_date"]?substr($arrInfo["list"][0]["claim_date"],0,10):date("Y-m-d")?>" class="w2 datepicker" />								
					</div></td>
				</tr>

				<tr>
					<th>환불은행</th>
					<td><div class="inputs"><input type="text" class="w4" name="refund_bankname" maxlength="30" value="<?=stripslashes($arrInfo["list"][0]['refund_bankname'])?>"></div></td>
				</tr>
				<tr>
					<th>예금주</th>
					<td><div class="inputs"><input type="text" class="w4" name="refund_username" maxlength="30" value="<?=stripslashes($arrInfo["list"][0]['refund_username'])?>"></div></td>
				</tr>
				<tr>
					<th>환불계좌번호</th>
					<td><div class="inputs"><input type="text" class="w4" name="refund_number" maxlength="30" value="<?=stripslashes($arrInfo["list"][0]['refund_number'])?>"></div></td>
				</tr>
				<tr style="display:none;">
					<th>관리자 사유 선택</th>
					<td>
						<div class="inputs">							
							<label class="radio"><input type="radio" name="claim_type" value="C001" <?=$arrInfo["list"][0]["claim_type"]=="C001"?"checked":""?>><i></i><?=$_SITE["SHOP"]["REASON"]["C001"]?></label>
							<label class="radio"><input type="radio" name="claim_type" value="C002" <?=$arrInfo["list"][0]["claim_type"]=="C002"?"checked":""?>><i></i><?=$_SITE["SHOP"]["REASON"]["C002"]?></label>
							<label class="radio"><input type="radio" name="claim_type" value="C003" <?=$arrInfo["list"][0]["claim_type"]=="C003"?"checked":""?>><i></i><?=$_SITE["SHOP"]["REASON"]["C003"]?></label>
							<label class="radio"><input type="radio" name="claim_type" value="C004" <?=$arrInfo["list"][0]["claim_type"]=="C004"?"checked":""?>><i></i><?=$_SITE["SHOP"]["REASON"]["C004"]?></label>
						</div>
					</td>
				</tr>
				<tr>
					<th>비고</th>
					<td><div class="inputs"><textarea name="claim_comment" style="width:99%;height:50px;padding:10px;" class="textarea"><?=stripslashes($arrInfo["list"][0]["claim_comment"])?></textarea></div></td>
				</tr>
				<?
				if($arrInfo['list'][0]['pay_type']=="card"){
					$payTxt = "카드 결제 환불 금액(확인용)";
				}else{
					$payTxt = "무통장 입금 환불금(확인용)";
				}
				?>
				<tr>
					<th><?=$payTxt?></th>
					<td><div class="inputs"><input type="text" class="w2" name="claim_amount" maxlength="30" value="<?=stripslashes($arrInfo["list"][0]['claim_amount'])?>"></div></td>
				</tr>
				<?if($arrInfo["list"][0]['tid']){?>
				<tr>
					<th>카드 결제 환불금</th>
					<td><div class="inputs"><input type="text" class="w3" id="tmp_amount" name="tmp_amount" value='<?=($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][0]['claim_amount'])?>' maxlength="50" class="input" />
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STPC', '<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>', document.frmOrderInfo.tmp_amount.value,'<?=($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][0]['claim_amount'])?>','<?=$arrInfo["list"][0]['claim_amount']?>' )">부분환불</button>
						&nbsp;<button type="button" class="delivery" onclick="fnPayCancel('STSC', '<?=$arrInfo["list"][0]['order_no']?>','<?=$arrInfo["list"][0]['tid']?>', '<?=$arrInfo['list'][0]['pay_amount']?>', 0, 0)">전체환불</button>
						</div></td>
				</tr>
				<tr>
					<th>환불금 차액</th>
					<td><?=number_format($arrInfo['list'][0]['pay_amount']-$arrInfo["list"][0]['claim_amount'])?>원</td>
				</tr>
				<?}?>
				<?
				}
				##################################################### 환불 신청 사유 ##################################################### ED
				?>

				
				
				<tr>
					<td colspan="2"><div class="tit">관리자 메모<i>*</i></div></td>
				</tr>
				<tr>
					<th id="tmpquery">메모</th>
					<td><div class="inputs"><textarea name="admin_comment" style="width:99%;height:50px;" class="textarea"><?=stripslashes($arrInfo["list"][0]["admin_comment"])?></textarea></div></td>
				</tr>

			</table>		

			<div class="btns">
				<a href="javascript:void(0);" onclick="history.back()" class="btn btn_list">목록보기</a>
				<button class="btn btn_save" type="button" onclick="fnFrmCheck()">저장</button>
			</div>
		</form>
	</div> <!-- //inbox -->

</div>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>
<script type="text/javascript">
<!--
function fnGoodDel(idx){
	if(confirm("해당 데이터를 삭제합니다. 계속 하시겠습니까?")) {
		$.post("/module/shop/ajax_shop_good_del.php", {g_idx: idx},
			function(data){
				if(data){
					//alert(data);	
					//$("#tmpquery").html(data);
					location.reload();
				}else{
					alert("실패.");
				}
			}
		);
	}
}
function fnGoodAdd(gidx, gqty){
	$(".is-close-btn").click();
	if(gqty>0){
		$.post("/module/shop/ajax_shop_good_add.php", { order_no: '<?=$arrInfo["list"][0]['order_no']?>', order_id : '<?=$arrInfo["list"][0]['order_id']?>', g_idx: gidx, g_qty: gqty},
			function(data){
				if(data){
					//alert(data);	
					//$("#tmpquery").html(data);
					location.reload();
				}else{
					alert("실패.");
				}
			}
		);
	}
}
//-->
</script>
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
//파일선택
	$(".searchfile").on('change',function(){
		val = $(this).val().split("\\");
		f_name = val[val.length-1]; 
		s_name = f_name.substring(f_name.length-4, f_name.length);
		$(this).parent().siblings('.filebox').html(f_name);
	});
});
//]]>
</script>
<?
/*
* 우체국 택배
https://service.epost.go.kr/trace.RetrieveDomRigiTraceList.comm?displayHeader=N&sid1=
* 우체국 국제우편 (EMS)
https://service.epost.go.kr/trace.RetrieveEmsRigiTraceList.comm?displayHeader=N&POST_CODE=
* CJ 대한통운
http://nplus.doortodoor.co.kr/web/detail.jsp?slipno=
* 한진 택배
http://www.hanjinexpress.hanjin.net/customer/hddcw18.tracking?w_num=
* 롯데 택배
https://www.lotteglogis.com/open/tracking?invno=
* 로젠 택배
https://www.ilogen.com/web/personal/trace/
* KGB 택배
http://www.kgbps.com/delivery/delivery_result.jsp?item_no=
* CVSnet 편의점 택배
http://www.cvsnet.co.kr/reservation-inquiry/delivery/index.do?dlvry_type=domestic&invoice_no=
* CU 편의점 택배
https://www.cupost.co.kr/postbox/delivery/localResult.cupost?invoice_no=
* 경동 택배
http://kdexp.com/basicNewDelivery.kd?barcode=
* 대신 택배
http://home.daesinlogistics.co.kr/daesin/jsp/d_freight_chase/d_general_process2.jsp
* 일양로지스
http://www.ilyanglogis.com/functionality/card_form_waybill.asp?hawb_no=
*/
?>
<form name="frmContentsHidden" method="post" action="order_evn.php">
<input type="hidden" name="evnMode" value="giftcard">
<input type="hidden" name="idx">
<input type="hidden" name="price">
<input type="hidden" name="order_no" value="<?=$arrInfo["list"][0]["order_no"]?>">
<input type="hidden" name="returnURL" value="<?=$_SERVER['REQUEST_URI']?>">
</form>
