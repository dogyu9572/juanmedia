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


$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","코로나검사예약")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","코로나검사예약")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );

$EXCEL_TXT = "
<table border='1'>
<tr style='background-color:#ffff00;'>
   <td>No</td>
   <td>".iconv("UTF-8","EUC-KR","구분")."</td>   
   <td>".iconv("UTF-8","EUC-KR","상태")."</td>
   <td>".iconv("UTF-8","EUC-KR","예약번호")."</td>
   <td>".iconv("UTF-8","EUC-KR","한글이름")."</td>
   <td>".iconv("UTF-8","EUC-KR","여권이름")."</td>
   <td>".iconv("UTF-8","EUC-KR","검사항목")."</td>
   <td>".iconv("UTF-8","EUC-KR","검사일")."</td>
   <td>".iconv("UTF-8","EUC-KR","검사시간")."</td>
   <td>".iconv("UTF-8","EUC-KR","검사결과")."</td>   
   <td>".iconv("UTF-8","EUC-KR","검사결과발송여부")."</td>
   <td>".iconv("UTF-8","EUC-KR","파일전송여부")."</td>   
   <td>".iconv("UTF-8","EUC-KR","생년월일")."</td>
   <td>".iconv("UTF-8","EUC-KR","연락처")."</td>
   <td>".iconv("UTF-8","EUC-KR","이메일")."</td>
   <td>".iconv("UTF-8","EUC-KR","성별")."</td>
</tr>
";

for ( $i=0 ; $i < $arrBoardList["total"] ; $i++ ) {
	if($arrBoardList["list"][$i]['category']=="1"){ $arrBoardList["list"][$i]['category'] = "예약완료";}
	if($arrBoardList["list"][$i]['category']=="2"){ $arrBoardList["list"][$i]['category'] = "검사중";}
	if($arrBoardList["list"][$i]['category']=="4"){ $arrBoardList["list"][$i]['category'] = "예약취소";}
	$rcode = str_replace("-","",substr($arrBoardList["list"][$i]['schedule_date'],2,10)).sprintf('%05d',$arrBoardList["list"][$i]['idx']);
	$timeTxt = "";			
	$Query2 = mysqli_query($GLOBALS['dblink'],"select etc_1,etc_2 from tbl_board_timetable where idx='".$arrBoardList["list"][$i]['time']."'");	
	$row = mysqli_fetch_array($Query2);
	$timeTxt = $row[0].":".$row[1];
	if($arrBoardList["list"][$i]['etc_2']=="Y"){ $etc_2 = "양성";}else{$etc_2 = "음성";}
	if($arrBoardList["list"][$i]['etc_2']){ $etc_send = "Y";}
	$tel = $arrBoardList["list"][$i]['phone_country'].")".$arrBoardList["list"][$i]['phone_number'];
	$EXCEL_TXT .= "
	<tr>
		<td>".($i+1)."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['etc_1'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['category'])."</td>	
		<td>".iconv("UTF-8","EUC-KR",$rcode)."</td>	
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['name'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['eng_name'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$pcrName[$arrBoardList["list"][$i]['arrpcr']])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['schedule_date'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$timeTxt)."</td>
		<td>".iconv("UTF-8","EUC-KR",$etc_2)."</td>
		<td>".iconv("UTF-8","EUC-KR",$etc_send)."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['etc_3'])."</td>	
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['bday'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$tel)."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['email'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrBoardList["list"][$i]['sex'])."</td>
	</tr>
	";	
}
$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>