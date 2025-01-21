<?
/**
API테스트 인증키   아이디   인증키
   NYFOOD2024   4da818102cc24484b8932aca45cb565dd9
이카운트테스트계정   거래처코드   
   0123456789   
   1123456789   
   2123456789
   CC
   {"EXPIRE_DATE":null,"COM_CODE":"22349","DOMAIN":".ecount.com","STATUS":"E","ZONE":"CC","DB_SHARD_NO":null,"CS_COM_CODE":"goldenbrown","ACCESS_ALL":null,"APP_DT_FROM":null,"APP_DT_TO":null,"MSG":"","DB_CON_FLAG":"83","EMPTY_ZONE":null,"SIP":"59465444460a1603071a1e0a1a"},"Status":"200","Errors":null,"Error":null,"Timestamp":"2024-02-22 14:54:43.673","RequestKey":null,"IsEnableNoL4":false,"RefreshTimestamp":"0","AsyncActionKey":null}
**/
//수신자 정보
function ecount_zone(){	
	$api_server = "https://sboapi.ecount.com";				// 서버	https://oapi.ecount.com/OAPI/V2/Zone
	$api_url = "/OAPI/V2/Zone";		// url
	$COM_CODE = "22349";

	$jsonVars  = '
		"COM_CODE":"'.$COM_CODE.'"
	';                   

	//	$jsonVars .= ', "attach":{"button":[{"name":"홈페이지 바로가기","type":"WL","url_mobile":"http://www.ellaboutique.com/","url_pc":"http://www.ellaboutique.com/"}]}';		// 버튼처리/웹링크

	$postvars = '{'.$jsonVars.'}';      //JSON 데이터

	//echo $postvars;

	$headers = array("content-type: application/json; charset=utf-8");	// header

	// api 호출
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $api_server.$api_url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$response = curl_exec($ch);

	curl_close($ch);

	//print_r($response);

	$jsonValue = json_decode($response, true);
	return $jsonValue['Data']['ZONE'];
}
function ecount_session_id(){
	$zone = ecount_zone();
	$api_server = "https://sboapi".$zone.".ecount.com";				// 서버	https://oapi.ecount.com/OAPI/V2/Zone
	$api_url = "/OAPI/V2/OAPILogin";		// url
	$COM_CODE = "22349";

	$jsonVars  = '
		"COM_CODE":"'.$COM_CODE.'",                 
		"USER_ID":"NYFOOD2024",                
		"API_CERT_KEY":"4da818102cc24484b8932aca45cb565dd9",    
		"LAN_TYPE":"ko-KR",                 
		"ZONE":"'.$zone.'"
	';                   

	//	$jsonVars .= ', "attach":{"button":[{"name":"홈페이지 바로가기","type":"WL","url_mobile":"http://www.ellaboutique.com/","url_pc":"http://www.ellaboutique.com/"}]}';		// 버튼처리/웹링크

	$postvars = '{'.$jsonVars.'}';      //JSON 데이터

	//echo $postvars;

	$headers = array("content-type: application/json; charset=utf-8");	// header

	// api 호출
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $api_server.$api_url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$response = curl_exec($ch);

	curl_close($ch);

	//	print_r($response);

	$jsonValue = json_decode($response, true);
	return $jsonValue['Data']['Datas']['SESSION_ID'];
}
//	echo ecount_zone();
//	echo ecount_session_id();
?>