<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

$chatlist_jsonlist = json_decode($_POST['chatlist'],true);     //POST로 받은 값을 json형식으로 decode

//DB연결
$dblink = SetConn($_conf_db["main_db"]); 

$r_user	= $chatlist_jsonlist['접수번호'];
$etc_3	= $chatlist_jsonlist['최종부문1'];
$etc_4	= $chatlist_jsonlist['최종부문2'];
$accept_state	= $chatlist_jsonlist['심사상태'];
$subject	= $chatlist_jsonlist['n년수상업체'];
$contents	= $chatlist_jsonlist['n년수상업체_연도별'];

$arrAcceptState = array();
foreach($_SITE["SHOP"]["STATE"] as $key => $val){
	$arrAcceptState[$val] = $key;
	$arrAcceptState[$key] = $key;
}

$sql = "update tbl_board_accept set etc_3 = '".$etc_3."', etc_4 = '".$etc_4."', subject = '".$subject."', contents = '".$contents."', accept_state = '".$arrAcceptState[$accept_state]."'  where r_user = '".$r_user."' ";	
//echo $sql;
//exit;

$orderRS = mysqli_query($dblink, $sql);

if($orderRS){
	echo "OK";
}else{
	echo "error";
}

//DB해제
SetDisConn($dblink);
?>