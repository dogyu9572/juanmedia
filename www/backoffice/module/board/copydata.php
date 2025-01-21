<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/_header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/calendar/calendar.lib.php";//일정관리 형식
include $_SERVER['DOCUMENT_ROOT'] . "/common/fckeditor/fckeditor.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;



//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$inDate = $_REQUEST['s_date'];
$endDate = $_REQUEST['e_date'];
$loopCnt = dateDiff($inDate,$endDate) + 1;
############################################### 해당 날짜의 일정 확인 ######################################## ST
$whereDay = "";
for($i=0;$i<$loopCnt;$i++){
	$timestamp = strtotime($inDate." +".$i." days");
	## 요일확인 ## ST
	for($k=0; $k<count($_POST['yoil']); $k++){
		if($_POST['yoil'][$k]==date('w', $timestamp)){
			$whereDay .= "'".date("Y-m-d", $timestamp)."'";
		}		
	}
	## 요일확인 ## ED
}
$whereDay = str_replace("''","','",$whereDay);
//echo $whereDay;
$subSql = "select count(idx) as cnt from tbl_board_ktoacal where schedule_date in (".$whereDay.") ";
$rs = mysqli_query($GLOBALS['dblink'],$subSql);
$row = mysqli_fetch_assoc($rs);
$q_total = $row['cnt'];
//echo $q_total;
if($q_total>0){
	jsGo("/backoffice/module/board/board_view.php?boardid=ktoacal&mode=writeall","","선택하신 날짜에 이미 등록된 일정이 있습니다.");
	exit();
}
############################################### 해당 날짜의 일정 확인 ######################################## ED

# 시작일 부터 입력 ST
for($i=0;$i<$loopCnt;$i++){
	$timestamp = strtotime($inDate." +".$i." days");
	## 요일확인 ## ST
	for($k=0; $k<count($_POST['yoil']); $k++){
		if($_POST['yoil'][$k]==date('w', $timestamp)){
			calInsert(date("Y-m-d", $timestamp) );
		}		
	}
	## 요일확인 ## ED	
}
# 시작일 부터 입력 ED


SetDisConn($dblink);

function calInsert($sdate){
	$tblid = "tbl_board_ktoacal";

	//main 번호 가져오기
	$q_main = mysqli_query($GLOBALS['dblink'],"select min(main) from $tblid ");	
	$c_main = @mysqli_result($q_main,0,0);
		
	if(!$c_main){	
		$main='99999999';
	}else{	
		$main=$c_main-1;
	}

	$sql = "INSERT INTO ".$tblid." set 		
		main='$main',
		sub='0',
		depth='0',		
		subject='',
		usereplyemail='N',
		usehtml='N',
		uselock='N',
		hit='0',
		ip='".$_SERVER['REMOTE_ADDR']."',
		schedule_date='".$sdate."',
		wdate=now() 
	";
	//echo $sql;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	$total = mysqli_affected_rows($GLOBALS['dblink']);
}

	jsGo("/backoffice/module/board/board_view.php?boardid=ktoacal","","등록되었습니다.");
?>
