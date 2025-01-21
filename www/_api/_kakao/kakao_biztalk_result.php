<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<?php

$btToken	= "eyJhbGciOiJIUzI1NiJ9.eyJic2lkIjoiZWxsYWJvdXRpcXVlIiwiZXhwIjoxNzA1NTU1NjEyLCJpYXQiOjE3MDU0NjkyMTIsImlwQWRkciI6IjIxMS40My4xNC4yMTUifQ.lawoFXwprsUAYZ__nXZsmm3BSj3Fh88b3unC4dj_4f0";					//필수입력
$senderKey	= "ef98eb58a1638f88ed586fd5e7b3ffd260a63aeb";

$msgIdx		= "860514";
$tmpltCode	= "ella-001";
$recipient	= "01066440212";

//수신자 정보
$api_server = "https://www.biztalk-api.com";		// 서버
$api_url = "/v2/kko/getResultAll";					// url

$jsonVars  = '"msgIdx":"'.$msgIdx.'"';				// 메시지 일련번호
$jsonVars .= ', "countryCode":"82"';				// 국가코드
$jsonVars .= ', "resMethod":"PUSH"';				// 전송방식
$jsonVars .= ', "senderKey":"'.$senderKey.'"';		// 카카오 발신프로필 키
$jsonVars .= ', "tmpltCode":"'.$tmpltCode.'"';		// 템플릿 코드
$jsonVars .= ', "message":"'.$message.'"';			// 템플릿 내용
$jsonVars .= ', "recipient":"'.$recipient.'"';		// 수신자 전화번호
//	$postvars = '{'.$jsonVars.'}';      //JSON 데이터

$headers = array("cache-control: no-cache", "content-type: application/json; charset=utf-8", "bt-token:".$btToken);	// header

// api 호출
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $api_server.$api_url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,10);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);


//curl 에러 확인
if(curl_errno($ch)){
    echo 'Curl error: ' . curl_error($ch);
}else{
    print_r($response);
}

curl_close ($ch);

?>
</body>
</html>
