<?php
error_reporting(E_ALL);
ini_set("display_errors", true);

$paymentKey = $_GET['paymentKey'];
$orderId = $_GET['orderId'];
$amount = $_GET['amount'];

$url = 'https://api.tosspayments.com/v1/payments/confirm';
$data = ['paymentKey' => $paymentKey, 'orderId' => $orderId, 'amount' => $amount];

$secretKey = 'test_sk_4vZnjEJeQVx1Aw6E17qrPmOoBN0k';


/**
 * 토스페이먼츠 API는 시크릿 키를 사용자 ID로 사용하고, 비밀번호는 사용하지 않습니다.
 * 비밀번호가 없다는 것을 알리기 위해 시크릿 키 뒤에 콜론을 추가합니다.
 * @see https://docs.tosspayments.com/reference/using-api/authorization#%EC%9D%B8%EC%A6%9D
 */
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
?>

<!DOCTYPE html>
<html lang="kr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>토스페이먼츠 샘플페이지-결제결과</title>
</head>

<body style="display:none;">
    <div>
        <div>
            <!-- 결제 성공 시 -->
            <?if($isSuccess){
				####################################### 결제 저장 ####################################### ST
				include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
				include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

				$ipkum_date = date("Y-m-d");
				$TID	= $responseJson->paymentKey;
				$OID	= $responseJson->orderId;
				$PTYPE	= $responseJson->method;
				## 'cash','card','naver','cacao','vbank','DirectBank'
				if($PTYPE=="카드"){		$PTYPE = "card";		}
				if($PTYPE=="무통장"){	$PTYPE = "cash";		}
				if($PTYPE=="네이버"){	$PTYPE = "naver";		}
				if($PTYPE=="카카오"){	$PTYPE = "cacao";		}
				if($PTYPE=="계좌이체"){	$PTYPE = "DirectBank";	}
				
				$dblink = SetConn($_conf_db["main_db"]);
					
					## 주문상태값 변경
					$sql = "update tbl_shop_order_info set order_state='11', ipkum_date='".$ipkum_date."', pay_type='".$PTYPE."', tid='".$TID."' where order_no='".$OID."'";
					$rs = mysqli_query($GLOBALS['dblink'], $sql);
					//	echo $sql;

					## 각 상품별 주문상태값 변경
					$sql2 = "update tbl_shop_order_good set order_status='11' where order_no='".$OID."'";
					$rs = mysqli_query($GLOBALS['dblink'], $sql2);
				
				SetDisConn($dblink);
				####################################### 결제 저장 ####################################### ED
			?>
				<script>
					onload = function winClose() {						
						document.location.href="/GATE_A/semina/semina_application_pay_end.php?order_no=<?=$OID?>";
					}
				</script>

                <h2 style="margin-top:10px; padding:20px 0px 10px 0px">
                    <img width="45px" src="https://static.toss.im/3d-emojis/u1F911-apng.png">
                    결제 성공
                </h2>

                <p>상품명 : <?php echo $responseJson->orderName ?></p>
                <p>결제수단 : <?php echo $responseJson->method ?> (
                    <?php if ($responseJson->method === "카드") {
                        echo $responseJson->card->number;
                    } ?>
                    <?php if ($responseJson->method === "가상계좌") {
                        echo $responseJson->virtualAccount->accountNumber;
                    } ?>
                    <?php if ($responseJson->method === "계좌이체") {
                        echo $responseJson->transfer->bank;
                    } ?>
                    <?php if ($responseJson->method === "휴대폰") {
                        echo $responseJson->mobilePhone->customerMobilePhone;
                    } ?>
                    )</p>

                <div>
                    <b>Response Data :</b>
                    <pre>
            <?php echo trim(json_encode($responseJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
          </pre>
                </div>

                <!-- 결제 실패 시 -->
            <?php } else { ?>

                <h2 style="margin-top:10px; padding:20px 0px 10px 0px">
                    <img width="45px" src="https://static.toss.im/3d-emojis/u1F975-apng.png">
                    결제 실패
                </h2>

                <div id="info">
                    <div>
                        <div>
                            <p>에러메시지 : <?php echo $responseJson->message ?></p>
                            <p>에러코드: <?php echo $responseJson->code ?></p>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
</body>

</html>