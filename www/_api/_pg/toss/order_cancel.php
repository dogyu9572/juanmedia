<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

############################################ 전체환불완료 상태값 3 ############################################ / 부분환불은 _part

$paymentKey	= $_POST['pkey'];
$order_no	= $_POST['order_no'];

//	$paymentKey	= "pd12AjJexmnRQoOaPz8LJ7pMDLwg5Vy47BMw6vl0gkYqDNEK";	## 테스트용
//	$order_no	= "20230619160343T06147";								## 테스트용
$order_state = "3"; //3:취소 5:환불
$cancelReason = "주문취소";

//부분 취소에서만 사용
$cancelAmount = 300;

//refundReceiveAccount - 가상계좌 거래에 대해 입금후에 취소하는 경우만 필요
$bank = "신한";
$accountNumber = "12345678901234";
$holderName = "홍길동";

//중복 취소를 막기위해 취소 가능금액을 전송
$refundableAmount = 300;


$secretKey = 'test_sk_4vZnjEJeQVx1Aw6E17qrPmOoBN0k';	## 실제키로 변경해야함
$url = 'https://api.tosspayments.com/v1/payments/'. $paymentKey .'/cancel';
/*
$data = ['cancelReason' => $cancelReason, 'cancelAmount' => $cancelAmount,
'refundReceiveAccount' => ['bank' => $bank, 'accountNumber' => $accountNumber, 'holderName' => $holderName],
'refundableAmount' => $refundableAmount];
*/
//	$data = ['cancelReason' => $cancelReason, 'cancelAmount' => $cancelAmount];
$data = ['cancelReason' => $cancelReason];

$credential = base64_encode($secretKey . ':');

$curlHandle = curl_init($url);


curl_setopt_array($curlHandle, [
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => [
        'Authorization: Basic ' . $credential,
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($curlHandle);

$httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
$isSuccess = $httpCode == 200;
$responseJson = json_decode($response);


if ($isSuccess) {
	##	<h1>취소 성공</h1>
	##	<p>결과 데이터 : echo json_encode($responseJson, JSON_UNESCAPED_UNICODE);
	##	<p>orderName : echo $responseJson->orderName
	##  <p>method : echo $responseJson->method
	##  <p>cancels -> cancelReason : echo $responseJson->cancels[0]->cancelReason
	echo "Y";		## 삭제금지 주석금지
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

		//현재 주문정보 가져오기
		$arrInfo = getOrderInfoAdmin($order_no);
		kakaoAllim("19", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], $arrInfo["list"][0]["order_summary"], $arrInfo["list"][0]["order_no"], "", "", "");
		
		$updateQuery = "update tbl_shop_order_good set order_status='".$order_state."' where order_no='".$order_no."' ";
		getFreeQueryCud($updateQuery);

		$updateQuery = "update tbl_shop_order_info set order_state='".$order_state."',	finish_date=now(),	claim_date=now() where order_no='".$order_no."' ";
		getFreeQueryCud($updateQuery);

	//DB해제
	SetDisConn($dblink);
} else {
	##	<h1>취소 실패</h1>
	##	<p>에러메시지 : echo $responseJson->message
	##	<span>에러코드: echo $responseJson->code
	echo $responseJson->message;
}
?>