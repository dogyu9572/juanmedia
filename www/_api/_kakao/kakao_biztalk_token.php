<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<?php
$userid		= "ellaboutique";									// 필수입력	// 홈피pw : Ellaboutique1!
$userpw		= "d8979ea4bbf7d3ded176e9d27b5f1221b9bff754";		// d8979ea4bbf7d3ded176e9d27b5f1221b9bff754

//수신자 정보
$api_server = "https://www.biztalk-api.com";		// 서버
$api_url = "/v2/auth/getToken";						// 토큰

$jsonVars  = '"bsid":"'.$userid.'"';
$jsonVars .= ', "passwd":"'.$userpw.'"';
$postvars = '{'.$jsonVars.'}';      //JSON 데이터

//echo $postvars;

$headers = array("cache-control: no-cache","content-type: application/json; charset=utf-8");	// header

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
  //  echo 'Curl error: ' . curl_error($ch);
}else{
  //  print_r($response);
}
curl_close ($ch);


$jsonValue = json_decode($response, true);
echo $jsonValue['token'];

?>
</body>
</html>