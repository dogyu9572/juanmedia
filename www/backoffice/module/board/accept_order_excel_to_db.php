<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

$chatlist_jsonlist = json_decode($_POST['chatlist'],true);     //POST로 받은 값을 json형식으로 decode

//DB연결
$dblink = SetConn($_conf_db["main_db"]); 

$r_user			= $chatlist_jsonlist['등록번호'];
$order_state	= $chatlist_jsonlist['등록상태'];
$proof_yn		= $chatlist_jsonlist['거래증빙 발행여부'];

$arrOrderState = array();
foreach($_SITE["SHOP"]["ORDER_STATE"] as $key => $val){
	$arrOrderState[$val] = $key;
	$arrOrderState[$key] = $key;
}
if($arrOrderState[$order_state]){
	$sql = "update tbl_board_accept set proof_yn = '".$proof_yn."', order_state = '".$arrOrderState[$order_state]."'  where r_user = '".$r_user."' ";	
	//echo $sql;
	//exit;
	$orderRS = mysqli_query($dblink, $sql);

	if($orderRS){
		echo "OK";
	}else{
		echo "error";
	}
}else{
	echo "OK";
}

//DB해제
SetDisConn($dblink);
?>