<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";


if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$scale=0;


// 기간 정보가 없을 경우 오늘 날짜 데이터만 가져온다
if (!$_REQUEST['s_date'] && !$_REQUEST['e_date']){
	$_REQUEST['s_date']=date("Y-m-d");
	$_REQUEST['e_date']=date("Y-m-d");;
}



$arrList = getOrderListAdmin(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_state']), 
	$scale, $_REQUEST['offset']);	

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();



//_DEBUG($arrList);


//Header("Content-type: file/unknown");
//header( "Content-type: application/vnd.ms-excel" );
//header( "Content-Disposition: attachment; filename=".iconv("utf-8","euc-kr",$_SITE['NAME'])."_주문목록_".date(m)."월".date(d)."일".date(h)."시".date(i)."분.csv" );
//header( "Content-Description: PHP4 Generated Data" );
header("Pragma: no-cache");
header("Expires: 0");

	echo "수화주명,";
	echo "(수)우편,";
	echo "(수)주소1,";
	echo "(수)전화,";
	echo "(수)휴대폰,";
	echo "택배수량,";
	echo "택배운임,";
	echo "물품코드,";
	echo "물품명,";
	echo "물품옵션,";	
	echo "배송 메시지,";
	echo ",";
	echo ",";
	echo "주문번호,";
	echo ",";
	echo ",";
	echo ",";
	echo ",";
	echo ",";
	echo ",";
	echo ",";
	echo ",";
	echo ",";
	echo "항공선착불<br />";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {

	$arrInfo = getOrderInfoAdmin(mysqli_real_escape_string($GLOBALS['dblink'], $arrList["list"][$i]['order_no']));

	if($arrInfo["good_total"]>0){
	for($j=0;$j<$arrInfo["good_total"];$j++){
		$gname[$i] .= "[".$arrAllCategory[$arrInfo["good_list"][$j][g_cat_no]]."]".stripslashes($arrInfo["good_list"][$j][g_name])."//";
		$option[$i] .= stripslashes($arrInfo["good_list"][$j][g_name]).":".$arrInfo["good_list"][$j][g_opt_1]." ".$arrInfo["good_list"][$j][g_qty]."개//";
	}
	}
	
	$temp_addr=stripslashes($arrList["list"][$i]['ship_address'])." ".stripslashes($arrList["list"][$i]['ship_address_ext']);


		echo strip_tags(str_replace(",",".", stripslashes($arrList["list"][$i]['ship_name'])))  . ",";
		echo strip_tags(str_replace(",",".", stripslashes($arrList["list"][$i]['ship_zip'])))  . ",";
		echo strip_tags(str_replace(",",".", $temp_addr)) . ",";
		echo strip_tags(str_replace(",",".", stripslashes($arrList["list"][$i]['ship_phone'])))  . ",";	
		echo strip_tags(str_replace(",",".", stripslashes($arrList["list"][$i]['ship_mobile'])))  . ",";	
		echo "1,";
		echo ",";
		echo ",";
		echo strip_tags(str_replace(",",".", substr($gname[$i],0,-2)))  . ",";
		echo strip_tags(str_replace(",",".", substr($option[$i],0,-2)))  . ",";	
		echo strip_tags(str_replace(",",".", stripslashes($arrList["list"][$i]['order_comment'])))  . ",";
		echo ",";
		echo ",";
		echo $arrList["list"][$i]['order_no'].",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo "<br />";
}

//DB해제
SetDisConn($dblink);
?>