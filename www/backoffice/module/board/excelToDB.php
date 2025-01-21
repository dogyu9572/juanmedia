<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

$chatlist_jsonlist = json_decode($_POST['chatlist'],true);     //POST로 받은 값을 json형식으로 decode

//DB연결
$dblink = SetConn($_conf_db["main_db"]); 

for($i = 0 ; $i < count($chatlist_jsonlist) ; $i++){
	//JSONArray에서 [$i] 번째 행의 JSONObject [' '] 항목의 값을 가져옴
	$order_no		= $chatlist_jsonlist[$i]['order_no'];
	$shipping_no	= $chatlist_jsonlist[$i]['shipping_no'];
	$shipping_date	= $chatlist_jsonlist[$i]['shipping_date'];
	
	$orderRS = getUpdateOrderShopping($order_no, $shipping_no, $shipping_date);
}

if($orderRS){
	echo "OK";
}else{
	echo "error";
}

//DB해제
SetDisConn($dblink);
?>