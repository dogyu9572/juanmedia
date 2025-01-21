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

$arrList = getCommentList("", "", 0, 0);

$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","엑셀다운로드")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","이벤트댓글")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );

$EXCEL_TXT = "
<table border='1'>
<tr>
   <td>No</td>
   <td>게시판ID</td>
   <td>게시판고유번호</td>
   <td>작성자ID</td>
   <td>작성자이름</td>   
   <td>내용</td>
   <td>IP</td>
   <td>입력일</td>
</tr>
";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	


	$EXCEL_TXT .= "
	<tr>
		<td>".($i+1)."</td>
		<td>".strip_tags($arrList["list"][$i]['boardid'])."</td>
		<td>".strip_tags($arrList["list"][$i]['board_idx'])."</td>	
		<td>".strip_tags($arrList["list"][$i]['user_id'])."</td>
		<td>".strip_tags($arrList["list"][$i]['user_name'])."</td>
		<td>".strip_tags($arrList["list"][$i]['comment'])."</td>
		<td>".strip_tags($arrList["list"][$i]['ip'])."</td>
		<td>".substr($arrList["list"][$i]['wdate'],0,10)."</td>
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
	</tr>
	";		
}

$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>