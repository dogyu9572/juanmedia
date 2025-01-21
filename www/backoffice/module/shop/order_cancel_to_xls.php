<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";


if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("login");
	jsHistory("-1");
endif;

//DB 
$dblink = SetConn($_conf_db["main_db"]);

$scale=0;

if($_GET['cm']=="1"){		##################################################### 취소
	$suqQuery .= " AND A.order_state in ('2','21')";
	$cmTxt	= "취소";
	$addTh	= "";
}else if($_GET['cm']=="2"){	##################################################### 교환
	$suqQuery .= " AND A.order_state in ('3','4','5','22')";
	$cmTxt	= "교환";
	$addTh = "<td>교환상품명</td>";
}else if($_GET['cm']=="3"){	##################################################### 환불
	$suqQuery .= " AND A.order_state in ('16','17','18','23')";
	$cmTxt	= "환불";
	$addTh = "
		<td>환불은행</td>
		<td>예금주</td>
		<td>환불계좌번호</td>
		<td>환불금액</td>
	";
}
if($_GET['claim_type']){
	$suqQuery .= " AND A.claim_type ='".$_GET['claim_type']."'";
}
	
$arrList = getOrderListAll(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_state']), 
	$scale, $_REQUEST['offset'], $suqQuery);


$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","주문내역")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","주문내역")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );

$EXCEL_TXT = "
<table border='1'>
<tr>
	<td>No</td>
	<td>진행상태</td>
	<td>주문일시</td>
	<td>주문번호</td>
	<td>주문자(ID)</td>
	<td>주문자 휴대폰번호</td>
	<td>주문자 이메일</td>
	<td>운송장번호</td>
	<td>발송일시</td>
	<td>주문상품 정보</td>
	<td>결제방법</td>
	<td>입금자명</td>
	<td>결제일</td>
	<td>총 상품금액</td>
	<td>할인금액</td>
	<td>총 주문금액(실결제금액)</td>

	<td>".$cmTxt."신청날짜</td>
	<td>관리자 사유</td>
	<td>비고</td>
	".$addTh."

	<td>수취인</td>
	<td>우편번호</td>
	<td>주소</td>
	<td>주소상세</td>
	<td>휴대폰번호</td>
	<td>배송메시지</td>
	<td>관리자메모</td>
</tr>
";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	$arrInfo = getOrderInfoAdmin($arrList["list"][$i]['order_no']);

	$bePay = 0;		## 총 주문금액
	$totalPay = 0;	## 할인금액 구할용도 실상품금액
	for($j=0;$j<$arrInfo["good_total"];$j++){		
		$thisPay = $arrInfo["good_list"][$j]["g_price"]*$arrInfo["good_list"][$j]["g_qty"];	
		$thisBePay = $arrInfo["good_list"][$j]["p_price"]*$arrInfo["good_list"][$j]["g_qty"];	
		$totalPay += $thisPay;
		$bePay += $thisBePay;
	}
	$disPay = $bePay - $totalPay;	## 기본할인
	$totalDispay = $disPay+$arrInfo['list'][0]['coupon_amount']+$arrInfo['list'][0]['using_point'];	## 총 할인금액

	$cash_request = "";

	if($arrInfo["list"][0]['cash_request'] == "N"){
		$cash_request = "미발행";
	}else if($arrInfo["list"][0]['cash_request'] == "Y"){
		$cash_request = "현금영수증";
	}else if($arrInfo["list"][0]['cash_request'] == "T"){
		$cash_request = "세금계산서";
	}

	if($arrInfo["list"][0]['cash_type'] == "1"){
		$cash_type = "소득공제용";
	}else if($arrInfo["list"][0]['cash_type'] == "2"){
		$cash_type = "지출증빙용";
	}
	if($_GET['cm']=="2"){	##################################################### 교환
		$addTd = "<td>".$arrInfo["list"][0]['re_goodname']."</td>";
	}else if($_GET['cm']=="3"){	##################################################### 환불
		$addTd	= "
			<td>".$arrInfo["list"][0]['refund_bankname']."</td>
			<td>".$arrInfo["list"][0]['refund_username']."</td>
			<td>".$arrInfo["list"][0]['refund_number']."</td>
			<td>".$arrInfo["list"][0]['claim_amount']."</td>
		";
	}

	$EXCEL_TXT .= "
	<tr>
		<td>".number_format($arrList["total"]-$i)."</td>
		<td>".$_SITE["SHOP"]["ORDER_STATE"][$arrInfo["list"][0]['order_state']]."</td>
		<td>".$arrInfo["list"][0]['order_date']."</td>
		<td>".$arrInfo["list"][0]['order_no']."</td>
		<td>".$arrInfo["list"][0]['order_name']." ( ".$arrInfo["list"][0]['order_id']." )</td>
		<td>".$arrInfo["list"][0]['order_mobile']."</td>
		<td>".$arrInfo["list"][0]['order_email']."</td>

		<td style=mso-number-format:'\@'>".$arrInfo["list"][0]['shipping_no']."</td>
		<td>".($arrInfo["list"][0]['shipping_date']=="0000-00-00"?"":$arrInfo["list"][0]["shipping_date"])."</td>

		<td>
			<table>
				<tbody>
					<tr>
						<th style='background: #d4d4d4'>상품명/옵션명</th>
						<th style='background: #d4d4d4'>수량</th>
						<th style='background: #d4d4d4'>판매가</th>
						<th style='background: #d4d4d4'>총 상품금액</th>
						<th style='background: #d4d4d4'>상태</th>
					</tr>
	";
		if($arrInfo["good_total"]>0){
			for($j=0;$j<$arrInfo["good_total"];$j++){
$EXCEL_TXT .= "
		<tr>
			<td>".$arrInfo["good_list"][$j]['g_name']."<br/>".($arrInfo["good_list"][$j]['g_opt_1'] ? "옵션 : ".$arrInfo["good_list"][$j]['g_opt_1'] :"" )."</td>					
			<td>".number_format($arrInfo["good_list"][$j]['g_qty'])."</td>			
			<td>".number_format($arrInfo["good_list"][$j]['g_price'])."</td>			
			<td>".number_format($arrInfo["good_list"][$j]['g_price']*$arrInfo["good_list"][$j]['g_qty'])."</td>	
			<td>".$_SITE["SHOP"]["ORDER_STATE"][$arrInfo["good_list"][$j]['order_status']]."</td>
		</tr>
";
			}
		}
$EXCEL_TXT .= "
				</tbody>
			</table>
		</td>

		<td>".$_SITE["SHOP"]["PAY_TYPE"][$arrInfo["list"][0]['pay_type']]."</td>
		<td>".stripslashes($arrInfo["list"][0]['cash_name'])."</td>
		<td>".($arrInfo["list"][0]['ipkum_date']=="0000-00-00"?"":$arrInfo["list"][0]['ipkum_date'])."</td>
		<td>".number_format($bePay)."</td>
		<td>
			<p>총 할인금액 ".number_format($totalDispay)."원</p>
			<p> ┕ 기본할인 20% : ".number_format($disPay)."원</p>
			".($arrInfo['list'][0]['coupon_idx']?"<p> ┕ ".$arrCouponInfo['list'][0]['coupon_name']." : ".number_format($arrInfo['list'][0]['coupon_amount'])."원</p>":"")."
			<p> ┕ 적립금사용 : ".number_format($arrInfo['list'][0]['using_point'])."원</p>
		<td>".number_format($arrInfo['list'][0]['pay_amount'])."</td>		

		<td>".substr($arrInfo["list"][0]['claim_date'],0,10)."</td>
		<td>".stripslashes($_SITE["SHOP"]["REASON"][$arrInfo["list"][0]['claim_type']])."</td>
		<td>".stripslashes($arrInfo["list"][0]['claim_comment'])."</td>
		".$addTd."

		<td>".stripslashes($arrInfo["list"][0]['ship_name'])."</td>
		<td>".stripslashes($arrInfo["list"][0]['ship_zip'])."</td>
		<td>".stripslashes($arrInfo["list"][0]['ship_address'])."</td>
		<td>".stripslashes($arrInfo["list"][0]['ship_address_ext'])."</td>
		<td>".stripslashes($arrInfo["list"][0]['ship_mobile'])."</td>
		<td>".stripslashes($arrInfo["list"][0]['order_comment'])."</td>
		<td>".stripslashes($arrInfo["list"][0]["admin_comment"])."</td>
	</tr>
	";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>