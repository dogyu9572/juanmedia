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

$query = "SELECT etc_4,etc_1,etc_3,etc_10 FROM tbl_member_old WHERE LENGTH(etc_4)>4 ORDER BY etc_10 ASC ";
$arrList = getFreeQueryR($query);


$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","주문내역")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","주문내역")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );

$EXCEL_TXT = "
<table border='1'>
<tr>
	<td>이메일</td>
	<td>상호명</td>
	<td>대표자명</td>
	<td>거래처등록번호</td>
</tr>
";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {

	$EXCEL_TXT .= "
	<tr>
		<td style=mso-number-format:'\@'>".$arrList["list"][$i]['etc_4']."</td>
		<td>".$arrList["list"][$i]['etc_1']."</td>
		<td>".$arrList["list"][$i]['etc_3']."</td>
		<td style=mso-number-format:'\@'>".$arrList["list"][$i]['etc_10']."</td>
	</tr>
	";
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>