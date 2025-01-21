<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";


if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("login");
	jsHistory("-1");
endif;

//DB 
$dblink = SetConn($_conf_db["main_db"]);

$scale=0;

$arrList = getXlsList("eventgoods", 0, 0);

$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","굿즈응모")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","굿즈응모")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );

$EXCEL_TXT = "
<table border='1'>
<tr>
   <td>No</td>
   <td>".iconv("UTF-8","EUC-KR","날짜")."</td>
   <td>ID</td>
   <td>".iconv("UTF-8","EUC-KR","이름")."</td>
   <td>".iconv("UTF-8","EUC-KR","연락처")."</td>   
   <td>".iconv("UTF-8","EUC-KR","주소")."</td>
</tr>
";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	$EXCEL_TXT .= "
	<tr>
		<td>".($i+1)."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['wdate'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['etc_1'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['etc_2'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['etc_3'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['etc_5'])."</td>	
	</tr>
	";		
}

for ( $i=0 ; $i < $arrListXls["total"] ; $i++ ) {

	$EXCEL_TXT .= "
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	";		
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>