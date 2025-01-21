<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_POST["sub_cat_no"]){
	//카테고리 목록
	$arrList = getBoardListBase("course","","s",$_POST["subject"],0,0, $_POST["sub_cat_no"]);
}else if($_POST["cat_no"]){
	//카테고리 목록
	$arrList = getBoardListBase("course",$_POST["cat_no"],"s",$_POST["subject"],0,0); 
}
//DB해제
SetDisConn($dblink);


for($i=0;$i<$arrList["total"];$i++){
	if($_POST['outidx']){
		if(strpos($_POST['outidx'],$arrList["list"][$i]['idx'])===false){
			echo '<option value="'.$arrList["list"][$i]['idx'].'">'.$arrList["list"][$i]['subject'].'</option>';		## 출력
		}
	}else{
		echo '<option value="'.$arrList["list"][$i]['idx'].'">'.$arrList["list"][$i]['subject'].'</option>';			## 출력
	}	
}
?>