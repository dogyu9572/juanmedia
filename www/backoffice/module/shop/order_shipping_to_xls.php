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


$arrList = getOrderListAll(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_state']), 
	$scale, $_REQUEST['offset']);


$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","배송사전달자료")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","배송사전달자료")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );

$EXCEL_TXT = "
<table border='1'>
<tr>
	<td>화주코드</td>
	<td>납기일자</td>
	<td>거래처코드</td>
	<td>거래처명</td>
	<td>품목코드</td>
	<td>품목명</td>
	<td>수량</td>
	<td>단가</td>
	<td>공급가액</td>
	<td>부가세</td>
	<td>합계</td>
	<td>모바일</td>
</tr>
";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	$sql = "select * from tbl_member where user_id='".$arrInfo["list"][0]['order_id']."'";
	$arrUserInfo = getFreeQueryR($sql);

	$arrInfo = getOrderInfoAdmin($arrList["list"][$i]['order_no']);

	for($g=0;$g<$arrInfo["good_total"];$g++){
		if($arrInfo["good_list"][$g]["g_vendor"]=="admin"){
			$arrInfo["list"][$g]["show_price"] = "0";
		}else{
			$arrChoice	= explode("|",$arrInfo["good_list"][$g]['member_choice']);
			$arrPrice	= explode("|",$arrInfo["good_list"][$g]['member_price']);
			$arrSale	= explode("|",$arrInfo["good_list"][$g]['member_sale']);	
			for($j=0;$j<count($arrChoice);$j++){
				if($arrUserInfo['list'][0]['a_class']==$arrChoice[$j]){
					$arrInfo["good_list"][$g]["show_price"]		= $arrPrice[$j];
					$arrInfo["good_list"][$g]["show_tax"]		= $arrInfo["good_list"][$g]["show_price"]*0.1;
					$arrInfo["good_list"][$g]["show_gong"]		= $arrInfo["good_list"][$g]["show_price"]-$arrInfo["good_list"][$g]["show_tax"];		
				}
			}
		}

		$EXCEL_TXT .= "
		<tr>
			<td>2522</td>		
			<td>".$arrList["list"][$i]['shipping_date']."</td>
			<td>".$arrUserInfo["list"][0]['etc_10']."</td>
			<td>".$arrUserInfo["list"][0]['etc_1']."</td>
			<td>".$arrInfo["good_list"][$g]['g_code']."</td>
			<td>".$arrInfo["good_list"][$g]['g_name']."</td>
			<td>".$arrInfo["good_list"][$g]['g_qty']."</td>
			<td>".$arrInfo["good_list"][$g]['p_price']."</td>
			<td>".$arrInfo["good_list"][$g]["show_gong"]."</td>
			<td>".$arrInfo["good_list"][$g]["show_tax"]."</td>
			<td>".$arrInfo["good_list"][$g]["show_price"]."</td>
			<td style=mso-number-format:'\@'>".$arrUserInfo["list"][0]['mobile']."</td>			
		</tr>
		";
		##	<td style=mso-number-format:'\@'></td>
	}
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>