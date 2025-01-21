<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";


//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$ptype	= $_POST['ptype'];
$dweek	= $_POST['dweek'];
$gCat	= $_POST['gCat'];
$sCat	= $_POST['sCat'];

$arrBoardList = getInwonInfo($ptype, $dweek, $gCat, $sCat);

echo $arrBoardList["list"][0]['paywon'];
//echo "".$_REQUEST["ptype"]."//".$_REQUEST["dweek"]."//".$_REQUEST["gCat"]."//".$arrBoardList["list"][0]['paywon'];





		
//DB해제
SetDisConn($dblink);
?>