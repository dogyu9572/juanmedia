<?
session_start();
include $_SERVER[DOCUMENT_ROOT] . "/common/conf/config.inc.php";
include $_SERVER[DOCUMENT_ROOT] . "/backoffice/auth/auth.php";
include $_SERVER[DOCUMENT_ROOT] . "/module/coupon/coupon.lib.php";
if(!in_array("point_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getCouponInfo(mysql_escape_string($_REQUEST["idx"]));

$arrList = getCouponUserListAdmin(mysql_escape_string($_REQUEST["idx"]), 0, 0);
//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);

//Header("Content-type: file/unknown");
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename=쿠폰_".iconv("utf-8","euc-kr",stripslashes($arrInfo["list"][0][coupon_name]))."_".date(m)."월".date(d)."일".date(h)."시".date(i)."분.csv" );
header( "Content-Description: PHP4 Generated Data" );
header("Pragma: no-cache");
header("Expires: 0");

	echo "번호,";
	echo "쿠폰번호,";
	echo "회원아이디,";
	echo "회원명,";
	echo "사용여부,";
	echo "사용일,";
	echo "등록일\n";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	echo $i+1 . ",";
	echo $arrList["list"][$i][coupon_no] . ",";
	echo $arrList["list"][$i][user_id] . ",";
	echo iconv("utf-8","euc-kr",$arrList["list"][$i][user_name]) . ",";
	echo $arrList["list"][$i][coupon_use] . ",";
	echo $arrList["list"][$i][udate] . ",";
	echo $arrList["list"][$i][wdate] . "\n";
}
?>