<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$timeidx = $_REQUEST["timeidx"];
$end_date = date($_REQUEST["rdate"]); 
$d_day = floor(( strtotime(substr($end_date,0,10)) - strtotime(date('Y-m-d')) )/86400);

if($d_day < 0){		## 당일 미만 날짜는 예약 안됨
	echo "<option value=''>해당 날짜는 예약할수 없습니다.</option>";
	exit();
}

$arrBoardList = getTimeTable("timetable", "", $_REQUEST["rdate"], $scale=0, $offset=0);
// api 연결은 따로 구현해야함
echo '<option value="">==시간 선택==</option>';
for($i=0; $i < $arrBoardList["list"]["total"]; $i++){	
		$timeselect = "";
		if($timeidx==$arrBoardList["list"][$i]['idx']){ $timeselect = "selected"; }
	if($d_day > 0){		// 제한인원 변경 무제한 / 당일예약가능 
		echo "<option value=".$arrBoardList["list"][$i]['idx']." ".$timeselect."> ".$arrBoardList["list"][$i]['etc_1'].":".$arrBoardList["list"][$i]['etc_2']." (".$arrBoardList["list"][$i]['tc']."/30)</option>";
	}else{
		if((int)$arrBoardList["list"][$i]['etc_1'] > date("H")){
			echo "<option value=".$arrBoardList["list"][$i]['idx']." ".$timeselect."> ".$arrBoardList["list"][$i]['etc_1'].":".$arrBoardList["list"][$i]['etc_2']." (".$arrBoardList["list"][$i]['tc']."/30)</option>";
		}
	}
}


if($arrBoardList==true){
//	echo "true";
}else{
//	echo "false".$_REQUEST["rdate"]."//".$_REQUEST["qcate"]."//".$_REQUEST["dweek"];
}

//DB해제
SetDisConn($dblink);
?>