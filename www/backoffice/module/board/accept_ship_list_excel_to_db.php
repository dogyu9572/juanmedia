<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

$chatlist_jsonlist = json_decode($_POST['chatlist'],true);     //POST로 받은 값을 json형식으로 decode

//DB연결
$dblink = SetConn($_conf_db["main_db"]); 

$type = mysqli_real_escape_string($dblink,$_POST["type"]);

$order_no				= $chatlist_jsonlist['등록번호'];
$ship_link1				= $chatlist_jsonlist['언론보도'];
$ship_link1_date		= $chatlist_jsonlist['언론보도 발송일'];
$ship_link1_time		= $chatlist_jsonlist['언론보도 발송시간'];
$ship_link2				= $chatlist_jsonlist['온라인발급품'];
$ship_link2_date		= $chatlist_jsonlist['온라인발급품 발송일'];
$ship_link2_time		= $chatlist_jsonlist['온라인발급품 발송시간'];
$ship_link2_show_date	= $chatlist_jsonlist['온라인발급품 다운로드 기간'];
$ship_number			= $chatlist_jsonlist['오프라인 발급품운송장'];
$ship_number_date		= $chatlist_jsonlist['오프라인 발급품 발송일'];
$ship_number_time		= $chatlist_jsonlist['오프라인 발급품 발송시간'];


if($type == "link1"){
	$sql = "update tbl_board_accept set ship_link1 = '".$ship_link1."', ship_link1_date = '".$ship_link1_date."', ship_link1_time = '".$ship_link1_time."'  where order_no = '".$order_no."' ";	

	$orderRS = mysqli_query($dblink, $sql);

}else if($type == "link2"){
	$sql = "update tbl_board_accept set ship_link2 = '".$ship_link2."', ship_link2_date = '".$ship_link1_date."', ship_link2_time = '".$ship_link1_time."',ship_link2_show_date = '".$ship_link2_show_date."'  where order_no = '".$order_no."' ";	

	$orderRS = mysqli_query($dblink, $sql);

}else if($type == "ship_number"){ 

	if($ship_number != ""){

		$sql = "update tbl_board_accept set ship_number_date = '".$ship_number_date."', ship_number_time = '".$ship_number_time."' where order_no = '".$order_no."' ";	
		$orderRS = mysqli_query($dblink, $sql);

		$sql = "SELECT ship_number FROM tbl_board_accept WHERE order_no = '".$order_no."' and ship_number = '".$ship_number."' "; // 운송장번호가 기존과 동일한지 체크
		$arrDupleCheck = getFreeQueryR($sql);

		if($arrDupleCheck["total"] < 1){// 운송장 번호가 기존과 동일하지 않을경우
			$sql = "update tbl_board_accept set ship_number = '".$ship_number."', ship_date = now() where order_no = '".$order_no."' ";	
			$orderRS = mysqli_query($dblink, $sql);
		}else{// 운송장 번호가 기존과 동일 할 경우
			$orderRS = true;
		}
	}else{
		$orderRS = true;
	}
}

if($orderRS){
	echo "OK";
}else{
	echo "error";
}

//DB해제
SetDisConn($dblink);
?>