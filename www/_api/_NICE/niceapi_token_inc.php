<?php
// 전송해야 할 설정값
$client_id		= "afc57dad-df5f-449a-9eb4-0591f3ba1d0a";
$client_secret	= "d491dc855a0fe98c24c6bf7fba661cdf";

$api_server			= "https://svc.niceapi.co.kr:22001";
$api_url			= "/digital/niceid/api/v1.0/common/crypto/token";
$access_token		= "58a3a714-c896-4156-ad5f-2b859699bec5";
$current_timestamp	= time();
$reqDtim			= date("YmdHis");
$reqNo				= "111111111122222222223333334333";
$encMode			= "1";		## 암호화 구분 1: AES128/CBC/PKCS7

$postvars = [
"dataHeader"=> ["CNTY_CD"=>"ko"],
"dataBody"=> ["req_dtim"=>$reqDtim, "req_no"=>$reqNo, "enc_mode"=>$encMode]
];
// 배열 형태로 저장한 값들을 json 형태로 변환해서 전송
$json_portvars = json_encode($postvars);

$productId		= "ProductID:2101979031";
$authorization	= "Authorization:bearer ".base64_encode($access_token.":".$current_timestamp.":".$client_id); 
// http 호출 헤더값 설정
$http_header = array();
$http_header[0] = "Content-Type: application/json";
$http_header[1] = $authorization;
$http_header[2] = $productId;

// api 호출
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_server.$api_url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
curl_setopt($ch, CURLOPT_POST, TRUE);			//POST 방식으로 호출
curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
curl_setopt($ch, CURLOPT_HEADER, TRUE);			//response에 header 값도 수신
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_portvars);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
}
curl_close($ch);

$jsonRs = substr($response,(strpos($response,"DENY")+5),strlen($response));
$arrRs = json_decode(trim($jsonRs), true);

//	echo $response;
//	echo $error_msg;

#################### 토큰 암호화 key/vi
$token_version_id	= $arrRs['dataBody']['token_version_id'];
$token_val			= $arrRs['dataBody']['token_val'];
$sitecode			= $arrRs['dataBody']['site_code'];

$svalue		= trim($reqDtim).trim($reqNo).trim($token_val);
$hvalue		= hash("sha256", $svalue, true);
$resultVal	= base64_encode($hvalue);
$key		= substr($resultVal,0,16);
$iv			= substr($resultVal,-16);
$hmacKey	= substr($resultVal,0,32);

$_SESSION["REQ_KEY"]		= $key;
$_SESSION["REQ_IV"]			= $iv;
$_SESSION["REQ_HMACKEY"]	= $hmacKey;
/*
$reqData	= [
	"requestno"=>"REQ2022042799",
	"returnurl"=>"https://naea.snu.ac.kr/module/_NICE_new/niceapi_token_access.php",
	"sitecode"=>$sitecode, 
	"methodtype"=>"get",
	"popupyn"=>"Y",
	"receivedata"=>"xxxxdddeee"
];
$jsonReqData = json_encode($reqData);
*/
if(!isset($_SERVER["HTTPS"])){ $httpVal = "http://"; }else{ $httpVal = "https://"; }
$returnUrl = $httpVal.$_SERVER['SERVER_NAME']."/_api/_NICE/niceapi_token_return.php";

//$jsonReqData = '{"requestno":"REQ2022042799","returnurl":"https://naea.snu.ac.kr/module/_NICE_new/niceapi_token_return.php","sitecode":"'.$sitecode.'","methodtype":"get","popupyn":"Y","receivedata":"xxxxdddeee"}';
$jsonReqData = '{"requestno":"REQ'.$reqDtim.'","returnurl":"'.$returnUrl.'","sitecode":"'.$sitecode.'","methodtype":"get","popupyn":"Y","receivedata":"xxxxdddeee"}';

function pkcs5_pad($text, $blockSize = 16) {	## 패딩 사용시 오류
  $pad = $blockSize - (strlen($text) % $blockSize);
  return $text . str_repeat(chr($pad), $pad);
}

$enc_data			= base64_encode(openssl_encrypt($jsonReqData, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
$integrity_value	= base64_encode(hash_hmac('sha256', $enc_data, $hmacKey, true));
?>
<script language='javascript'>
	window.name ="Parent_window";
	
	function fnPopup(){
		window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
		document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/service.cb";
		document.form_chk.target = "popupChk";
		document.form_chk.submit();
	}
</script>
<form name="form_chk" id="form_chk">
      <input type="hidden" id="m" name="m" value="service" />
      <input type="hidden" id="token_version_id" name="token_version_id" value="<?=$token_version_id?>" />
      <input type="hidden" id="enc_data" name="enc_data" value="<?=$enc_data?>" />
      <input type="hidden" id="integrity_value" name="integrity_value" value="<?=$integrity_value?>"/>
</form>
<!--<a href="javascript:fnPopup();"> CheckPlus 안심본인인증 Click</a>-->