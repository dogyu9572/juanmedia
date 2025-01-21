<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory = getCategoryAll();	// 전체카테고리

$g_idx = $_POST["g_idx"];

$sql = "select * from tbl_board_accept where idx in (".$g_idx.") ";

$arrList = getFreeQueryR($sql);

for($i=0;$i<$arrList["total"];$i++){
	$arrEvaluationInfo = getBoardArticleView("evaluation", "", $arrList["list"][$i]["homepage"], "message");
	$arrVarData = array(
		"#{업종}" => $arrAllCategory[$arrEvaluationInfo["list"][0]["category"]],
		"#{2순위선정발표일}" => $arrEvaluationInfo["list"][0]["publication2_date"]
	);

	if($_SITE["SMS_USE"] && $arrList["list"][$i]["sms_accept"] == "Y"){ // 문자 전송
		smsLmsSureApi("kca_03", $arrVarData, $arrList["list"][$i]["tel"]);

		$sql = "
			insert into tbl_sms_log set 
			type = 'sms',
			templete_code = 'kca_03',
			tel = '".$arrList["list"][$i]["tel"]."',
			wdate = now()
		";
		$rs = mysqli_query($dblink,$sql);
	}
	if($_SITE["ARLIMTALK_USE"] && $arrList["list"][$i]["kakao_accept"] == "Y"){ // 카카오 알림톡 전송
		kakaoApiTalk("kca_03", $arrVarData, $arrList["list"][$i]["tel"]);

		$sql = "
			insert into tbl_sms_log set 
			type = 'kakao',
			templete_code = 'kca_03',
			tel = '".$arrList["list"][$i]["tel"]."',
			wdate = now()
		";
		$rs = mysqli_query($dblink,$sql);
	}
}

$arrData = array();
$arrData["success"] = true;
$arrData["msg"] = "";

echo json_encode($arrData);

//DB해제
SetDisConn($dblink);
?>