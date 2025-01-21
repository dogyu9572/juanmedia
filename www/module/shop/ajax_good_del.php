<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrIdx = explode(",",$_REQUEST["g_idx"]);
for($i=0;$i<count($arrIdx);$i++){
//	echo $arrIdx[$i];
	$deleteRS = deleteGood($arrIdx[$i]);
}

if($deleteRS==true){
	echo "true";
}else{
	echo "false".$_REQUEST["g_idx"];
}

//DB해제
SetDisConn($dblink);
?>