<?php
session_start();
header("Content-Type:text/html; charset=utf-8;");
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";

$tid = $_POST['tid'];
$amount = $_POST['amount'];

$clientId = 'S2_af4543a0be4d49a98122e01ec2059a56';
$secretKey = '9eb85607103646da9f9c02b128f2e5ee';

$resObject = '';

try {
	$res = requestPost(
		"https://sandbox-api.nicepay.co.kr/v1/payments/" . $tid,
		json_encode(array("amount" => $amount)),
		$clientId . ':' . $secretKey
	);
	$arrResData = json_decode($res,true);
} catch (Exception $e) {
	$e->getMessage();
}

//CURL: Basic auth, json, post
function requestPost($url, $json, $userpwd)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

if($arrResData["resultCode"] == "0000"){ // 성공했을경우
	// 공통
	$tid = $arrResData['tid'];
	$oid = $arrResData['orderId'];

	if($arrResData['payMethod'] == "vbank"){ // 가상계좌일 경우
		$bank_code = $arrResData['vbank']['vbankCode']; // 은행코드
		$bank_name = $arrResData['vbank']["vbankName"]; // 은행 명
		$bank_number = $arrResData['vbank']["vbankNumber"]; // 가상계좌 번호
		$bank_exp_date = date("Y-m-d H:i:s", strtotime($arrResData['vbank']["vbankExpDate"])); // 가상계좌 만료일
	}

	// 여기서부터 자체제작 코드 적용
	/*
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$sql = " UPDATE tbl_board_accept set tid = '$tid' order_state = '1', bank_code = '$bank_code', bank_name = '$bank_name', bank_number = '$bank_number', bank_exp_date = '$bank_exp_date' where order_no = '$oid' ";
	//echo $sql;
	$rs = mysqli_query($dblink, $sql);

	//DB해제
	SetDisConn($dblink);

	if($rs){
		// db 저장 성공
		if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] != ""){ // 로그인되어있을 경우
			jsGo("/mypage/payment_view.php?orderno=".$oid,"","");
		}else{ // 로그인되어있지 않은경우
			jsGo("/mypage/no_member_payment_view.php?orderno=".$oid,"","");
		}
	}else{
		// db 저장 실패
		$arrResData['innerResultMsg'] = "DB저장 실패";
	}
	*/
}else{ // 실패했을 경우
	$arrResData['innerResultMsg'] = "결제실패";
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Nicepay php</title>
	<meta httpEquiv="x-ua-compatible" content="ie=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>

<body style="display:none;">
	<h1><?php echo $arrResData['resultMsg'] ?></h1>
	<h2><?php echo $arrResData['innerResultMsg'] ?></h2>
	<p>상세한 응답 body는 log를 확인해주세요</p>
	<hr>
	<?php var_dump($arrResData);?>
</body>

</html>