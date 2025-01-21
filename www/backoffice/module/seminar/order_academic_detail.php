<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory = getCategoryAll();	// 전체카테고리

$arrInfo = getOrderInfoAdmin(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["order_no"]));

################################################## 세미나 정보 ##############################################							
$arrGoodInfo = getGoodInfo($arrInfo["good_list"][0]['g_idx'],"");
$arrExtCat = getGoodExtCat($arrGoodInfo["list"][0]["idx"]);
for($j=0;$j<$arrExtCat["total"];$j++){
	$arrExtCatCode = explode("/", $arrExtCat["list"][$j]["cat_code"]);
	if(in_array("183",$arrExtCatCode)){	## 분류
		$viewCategory = $arrAllCategory[$arrExtCatCode[2]];
	}
	if(in_array("18",$arrExtCatCode)){	## 연자
		$viewYunja = $arrAllCategory[$arrExtCatCode[2]];
	}	
}				
$stateText = "";
if($arrInfo["good_list"][0]["g_opt_1"]=="free"){							
	$stateText = "제한없음";
}else if($arrInfo["good_list"][0]["g_opt_1"]=="30D"){
	$stateText = str_replace("-",".",substr($arrInfo["list"][0]['order_date'],0,10))." ~ ".date("Y.m.d", strtotime($arrInfo["good_list"][0]['list']." +30 days"));								
}

################################ 세미나 보기 ################################ ST
$relidxs	= $arrGoodInfo['list'][0]['rel_a_idx'];
$relidxsby	= $arrGoodInfo['list'][0]['rel_a_orderby'];
$arrRelidxBy = explode(",",$relidxsby);

$Query = "select * from tbl_board_academic WHERE idx IN (".$relidxs.") ORDER BY idx DESC";
$arrList = getFreeQueryR($Query);
for($i=0;$i<$arrList['list']['total'];$i++){	
	$arrList["list"][$i]['orderbynum'] = $arrRelidxBy[$i];
}
rsort($arrRelidxBy);

for($i=0;$i<$arrList['list']['total'];$i++){	
	for($j=0;$j<count($arrRelidxBy);$j++){
		if($arrList["list"][$j]['orderbynum'] == $arrRelidxBy[$i]){
			$arrReList["list"][$i] = $arrList["list"][$j];
		}
	}
	########################################### 수강률 #######
	$studyInfo = studyList("def", $arrList["list"][$i]['idx'], $arrInfo["list"][0]['order_id']);
	if($studyInfo['list'][0]['curr']){
		$sukang = floor($studyInfo['list'][0]['curr']/$studyInfo['list'][0]['durat']*100);
	}else{
		$sukang = 0;
	}
	if($sukang>95){ $sukang = 100; }
	$totSukang += $sukang;
}
$totSukang = floor($totSukang/$arrList['list']['total']);
################################ 세미나 보기 ################################ ED

//DB해제
//	SetDisConn($dblink);

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
		window.open('https://www.ilogen.com/web/personal/trace/'+besongIdx,'','width=1200, height=800, scrollbars=yes');
		//window.open('https://www.lotteglogis.com/open/tracking?invno='+besongIdx,'','width=800, height=600, scrollbars=yes');
	}
}	
function fnFrmCheck(){
	var frm = document.frmOrderInfo;
	if(frm.shipping_no.value){
		//alert(frm.order_state.value);
		frm.order_state.value = "9";
		//alert(frm.order_state.value);
	}
	frm.submit();
}
function fnAdminOK(yn,ono){
	if(yn=="Y"){
		alert('수료증 발급 가능 상태로 변경합니다.');
	}else{
		alert('미발급 상태로 변경합니다.');
	}
	var apiUrl = "/module/shop/ajax_edit_certificate_yn.php";
	$.post(apiUrl, { "yn":yn, "order_no":ono }, 
		function(data){
		//alert(data);		
		if(data=="true"){		
			location.reload();
		}else{
			alert(data);	
		}
	});	
}
//-->
</script>
<div class="container">

	<div class="title">세미나 신청(구매)정보</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmOrderInfo" id="frmOrderInfo" method="post" action="order_evn.php">
			<input type="hidden" name="evnMode" value="update">
			<input type="hidden" name="order_no" value="<?=$arrInfo["list"][0]["order_no"]?>">
			<input type="hidden" name="rt_url" value="/backoffice/module/shop/order_detail.php?order_no=<?=$arrInfo["list"][0]["order_no"]?>&mode=<?=$_GET['mode']?>">
			<input type="hidden" name="pay_point" value="<?=$arrInfo["list"][0]["pay_point"]=="N"?"Y":"N"?>"><?#################### 결제완료시 적립금 적립 확인용 ##?>
			<input type="hidden" name="stock_apply" value="<?=$arrInfo["list"][0]["stock_apply"]=="N"?"Y":"N"?>"><?################ 재고수량 변경용 ################?>

			<table>
				<tr>
					<th>이름(ID)</th>
					<td><em><?=$arrInfo["list"][0]['order_name']?> ( <?=$arrInfo["list"][0]['order_id']?> )</em></td>
				</tr>				
				<tr>
					<th>세미나명</th>
					<td><em><?=$arrGoodInfo["list"][0]['g_name']?></em></td>
				</tr>
				<tr>
					<th>연자</th>
					<td><em><?=$viewYunja?></em></td>
				</tr>
				<tr>
					<th>수강기간</th>
					<td><em><?=$stateText?></em></td>
				</tr>
				<tr>
					<th>전체 수강률</th>
					<td><em><progress value="<?=$totSukang?>" max="100" style="margin-top:5px;"></progress><span style="padding-left:10px;"><?=$totSukang?>%</span></em></td>
				</tr>
				<tr>
					<th>파일 다운로드 일시</th>
					<td><em><?=$arrInfo["list"][0]['bdate']?></em></td>
				</tr>
				<tr>
					<th>수료증 발급일시</th>
					<td><em><?=$arrInfo["list"][0]['cdate']?></em></td>
				</tr>
				<tr>
					<th>수료증 관리자 발급</th>
					<td>	
						<?
						if($arrInfo["list"][0]['certificateYN']=="Y"){
							echo "발급";
						?>
						<a href="javascript:void(0);" class="btn refund" onclick="fnAdminOK('N', '<?=$arrInfo["list"][0]["order_no"]?>')">발급취소</a>						
						<?
						}else{
							echo "미발급";
						?>					
						<a href="javascript:void(0);" class="btn refund" onclick="fnAdminOK('Y', '<?=$arrInfo["list"][0]["order_no"]?>')">발급</a>					
						<?}?>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<div class="bdr_list tac" style="width:100%;">
							<table>
								<colgroup>
									<col width="10%">
									<col width="40%">
									<col width="15%">
									<col width="15%">
									<col width="20%">
								</colgroup>
								<thead>
								<tr>							
									<th style="text-align:center;padding-left:0;">No</th>
									<th style="text-align:center;padding-left:0">강의명</th>
									<th style="text-align:center;padding-left:0">영상시간</th>
									<th style="text-align:center;padding-left:0">수강률</th>
									<th style="text-align:center;padding-left:0">마지막 수강일시</th>
								</tr>
								<tbody>	
								<?
								######################################## 세미나 보기 (본 동영상) ######################################## ST
								for($i=0;$i<$arrList['list']['total'];$i++){	
									$listNum[$i] = sprintf('%02d', ($i+1));
									########################################### 수강률 ########################################### ST
									$studyInfo = studyList("def", $arrReList["list"][$i]['idx'], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);
									if($studyInfo['list'][0]['curr']){
										$sukang = floor($studyInfo['list'][0]['curr']/$studyInfo['list'][0]['durat']*100);
									}else{
										$sukang = 0;
									}
									if($sukang>95){ $sukang = 100; }
									########################################### 수강률 ########################################### ED
								?>
									<tr>
										<td style="text-align:center;"><?=$listNum[$i]?></td>
										<td style="text-align:center;"><a href="#this"><?=stripslashes($arrReList['list'][$i]['subject'])?></a></td>
										<td style="text-align:center;"><?=$arrReList["list"][$i]['etc_2']?></td>
										<td style="text-align:center;"><progress value="<?=$sukang?>" max="100" style="margin-top:5px;"></progress><span style="padding-left:10px;"><?=$sukang?>%</span></td>
										<td style="text-align:center;">
										<?if($sukang<100){?><!--<a href="javascript:void(0);" onclick="fnSuryo()">[수료처리]<?=$studyInfo['list'][0]['durat']?></a>--><?}?>
										<?=$studyInfo['list'][0]['homepage']?></td>
									</tr>
								<?
								}
								######################################## 세미나 보기 (본 동영상) ######################################## ED
								?>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
							
			</table>		

			<div class="btns">
				<a href="javascript:void(0);" onclick="history.back()" class="btn btn_list">목록보기</a>				
			</div>
		</form>
	</div> <!-- //inbox -->

</div>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>
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
