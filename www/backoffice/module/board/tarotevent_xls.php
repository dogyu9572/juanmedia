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

$arrList = getXlsList("tarotevent", 0, 0);

$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","타로카드이벤트")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","타로카드이벤트")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );

$EXCEL_TXT = "
<table border='1'>
<tr>
   <td>No</td>
   <td>날짜</td>
   <td>Q</td>
   <td>S</td>
   <td>P</td>   
   <td>이미지다운로드</td>
   <td>링크</td>
   <td>카카오톡</td>
   <td>트위터</td>
   <td>페이스북</td>
</tr>
";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	$etc_4 = "";
	$etc_6 = "";
	$etc_7 = "";
	$etc_8 = "";
	$etc_9 = "";

	if($arrList["list"][$i]['etc_4']){$etc_4 = "O";}
	if($arrList["list"][$i]['etc_6']){$etc_6 = "O";}
	if($arrList["list"][$i]['etc_7']){$etc_7 = "O";}
	if($arrList["list"][$i]['etc_8']){$etc_8 = "O";}
	if($arrList["list"][$i]['etc_9']){$etc_9 = "O";}

	$EXCEL_TXT .= "
	<tr>
		<td>".($i+1)."</td>
		<td>".strip_tags($arrList["list"][$i]['wdate'])."</td>
		<td>".strip_tags($arrList["list"][$i]['etc_1'])."</td>
		<td>".strip_tags($arrList["list"][$i]['etc_2'])."</td>
		<td>".strip_tags($arrList["list"][$i]['etc_3'])."</td>
		<td>".$etc_4."</td>
		<td>".$etc_6."</td>
		<td>".$etc_7."</td>
		<td>".$etc_8."</td>
		<td>".$etc_9."</td>		
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