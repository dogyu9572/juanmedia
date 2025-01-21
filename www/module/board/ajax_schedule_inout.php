<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getBoardListBase("schedule", "", "schedule_date", $_REQUEST["sday"], 0, 0, $subQuery="");

$arrBesh = explode(",",$_REQUEST["besongh"]);
$arrBush = explode(",",$_REQUEST["businessh"]);

for($i=0;$i<$arrList['list']['total'];$i++){		######### 검색후 삭제
	if($arrList['list'][$i]['subject']=="배송 휴무"){
		for($s=0;$s<$arrList['list']['total'];$s++){
			if($arrList['list'][$i]['schedule_date']==$arrBesh[$s]){
				$tf[$i] = true;
			}
		}		
	}else if($arrList['list'][$i]['subject']=="영업 휴무"){	## 영업 휴무
		for($s=0;$s<$arrList['list']['total'];$s++){
			if($arrList['list'][$i]['schedule_date']==$arrBush[$s]){
				$tf[$i] = true;
			}
		}		
	}else if($arrList['list'][$i]['subject']=="공휴일"){	## 공휴일
		$tf[$i] = true;
	}
	if(!$tf[$i]){	## 삭제				
		deleteBoardAdmin("schedule", $arrList['list'][$i]['idx']);
	}
}

for($i=0;$i<count($arrBesh);$i++){	## 검색후 등록 / 배송 휴무
	for($j=0;$j<$arrList['list']['total'];$j++){
		if($arrList['list'][$j]['schedule_date']==$arrBesh[$i] && $arrList['list'][$j]['subject']=="배송 휴무"){
			$tfBesh[$i] = true;
		}
	}
	if(!$tfBesh[$i]){	## 등록
		getScheduleInsert("배송 휴무", $arrBesh[$i]);
	}
}

for($i=0;$i<count($arrBush);$i++){	## 검색후 등록 / 영업 휴무
	for($j=0;$j<$arrList['list']['total'];$j++){
		if($arrList['list'][$j]['schedule_date']==$arrBush[$i] && $arrList['list'][$j]['subject']=="영업 휴무"){
			$tfBush[$i] = true;
		}
	}	
	if(!$tfBush[$i]){	## 등록
		//	echo  $arrBush[$i];
		getScheduleInsert("영업 휴무", $arrBush[$i]);
	}
}
//echo $_REQUEST["besongh"]."//".$_REQUEST["businessh"]."//".$_REQUEST["sday"];

//DB해제
SetDisConn($dblink);
?>