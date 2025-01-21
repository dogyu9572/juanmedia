<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<?php

$btToken	= "eyJhbGciOiJIUzI1NiJ9.eyJic2lkIjoiZWxsYWJvdXRpcXVlIiwiZXhwIjoxNzA1NTU1NjEyLCJpYXQiOjE3MDU0NjkyMTIsImlwQWRkciI6IjIxMS40My4xNC4yMTUifQ.lawoFXwprsUAYZ__nXZsmm3BSj3Fh88b3unC4dj_4f0";					//필수입력
$senderKey	= "ef98eb58a1638f88ed586fd5e7b3ffd260a63aeb";

$msgIdx		= "860514";
$tmpltCode	= "ella-001";
$message	= "[엘라부티크]

회원가입이 완료되었습니다.

안녕하세요. 지현수님
저희 엘라부티크와 함께 해주셔서 감사합니다.

지현수님의 ID : jeejin

매일 인스타 그램 라이브 방송과 홈페이지에서 다양한 제품들을 만나보실 수 있습니다.

▶ 홈페이지 바로가기
http://www.ellaboutique.com/";
$recipient	= "01066440212";

//수신자 정보
$api_server = "https://www.biztalk-api.com";		// 서버
$api_url = "/v2/kko/sendAlimTalk";					// url

$jsonVars  = '"msgIdx":"'.$msgIdx.'"';				// 메시지 일련번호
$jsonVars .= ', "countryCode":"82"';				// 국가코드
$jsonVars .= ', "resMethod":"PUSH"';				// 전송방식
$jsonVars .= ', "senderKey":"'.$senderKey.'"';		// 카카오 발신프로필 키
$jsonVars .= ', "tmpltCode":"'.$tmpltCode.'"';		// 템플릿 코드
$jsonVars .= ', "message":"'.$message.'"';			// 템플릿 내용
$jsonVars .= ', "recipient":"'.$recipient.'"';		// 수신자 전화번호
$jsonVars .= ', "attach":{"button":[{"name":"홈페이지 바로가기","type":"WL","url_mobile":"http://www.ellaboutique.com/","url_pc":"http://www.ellaboutique.com/"}]}';		// 버튼처리/웹링크

$postvars = '{'.$jsonVars.'}';      //JSON 데이터

$headers = array("cache-control: no-cache", "content-type: application/json; charset=utf-8", "bt-token:".$btToken);	// header

// api 호출
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $api_server.$api_url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
curl_setopt($ch,CURLOPT_TIMEOUT, 60);
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
