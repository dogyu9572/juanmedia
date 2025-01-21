<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB 
$dblink = SetConn($_conf_db["main_db"]);

if(!isset($boardid)){
	$boardid = $_REQUEST['boardid'];
}

//게시판 정보
$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], $boardid);

$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET['sw'], $_GET['sk'], 0, 0);

$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","연수생")."_".date(m).date(d).date(h).date(i).".xls";
/*
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","연수생")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );
*/
header( "Content-type: application/vnd.ms-excel; charset=euc-kr"); 
header( "Content-Description: PHP4 Generated Data" ); 
header( "Content-Disposition: attachment; filename=".$filename );
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=euc-kr\">");



$EXCEL_TXT = "
<table border='1'>
<tr style='background-color:#ffff00;'>
   <td>No</td>
   <td>".iconv("UTF-8","EUC-KR","연수과정")."</td>   
   <td>".iconv("UTF-8","EUC-KR","ID(E-mail)")."</td>
   <td>".iconv("UTF-8","EUC-KR","이름")."</td>
   <td>".iconv("UTF-8","EUC-KR","비밀번호")."</td>
   <td>".iconv("UTF-8","EUC-KR","휴대번호")."</td>
   <td>".iconv("UTF-8","EUC-KR","국적")."</td>
   <td>".iconv("UTF-8","EUC-KR","여권번호")."</td>
   <td>".iconv("UTF-8","EUC-KR","등록일")."</td>
</tr>
";

for ( $i=0 ; $i < $arrBoardList["total"] ; $i++ ) {
	
	$EXCEL_TXT .= "
	<tr>
		<td>".($i+1)."</td>
		<td>".iconv("UTF-8","EUC-KR",$GLOBALS['arrCourseName'][$arrBoardList["list"][$i]['category']])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['email'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['name'])."</td>	
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['pass'])."</td>	
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['tel'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['homepage'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['etc_1'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['wdate'])."</td>
	</tr>
	";		
}
$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>