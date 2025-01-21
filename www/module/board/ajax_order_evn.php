<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_POST['evnMode']=="order_save"){
	$arrData = array();
	
	$rs = setBoardOrderSave();

	if($rs){
		$order_no = makeOrderNo();
		
		$oid_sql = "
		UPDATE tbl_board_accept SET
			order_no		='".$order_no."'
		where r_user= '".mysqli_real_escape_string($dblink, $_POST['r_user'])."'
		";
		//echo $oid_sql;
		$oid_rs = mysqli_query($dblink, $oid_sql);
		if($oid_rs){
			$arrInfo = getBoardViewNoid("accept", $order_no, $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["USERCODE"]);

			$arrData["success"] = true;
			$arrData["oid"] = $order_no;
			$arrData["price"] = $arrInfo["list"][0]["pay_amount"];
		}else{
			$arrData["success"] = false;
			$arrData["msg"] = "주문번호 제작 실패";
		}
		echo json_encode($arrData);
	}else{
		$arrData["success"] = false;
		$arrData["msg"] = "주문서 작성 실패";
		echo json_encode($arrData);
	}
}

//DB해제
SetDisConn($dblink);
?>