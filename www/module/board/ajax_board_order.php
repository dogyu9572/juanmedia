<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$boardid = $_POST["boardid"];

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

for($i=0;$i<count($_POST["idx"]);$i++){
	$result = updateBoardOrder($boardid,$_POST['idx'][$i],$_POST["b_order"][$i]);
}

echo $result;

//DB해제
SetDisConn($dblink);
?>