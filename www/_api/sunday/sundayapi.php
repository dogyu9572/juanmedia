<?
##	https://www.data.go.kr/iim/api/selectAPIAcountView.do	//	한국천문연구원_특일 정보
$ch = curl_init();
$url = 'http://apis.data.go.kr/B090041/openapi/service/SpcdeInfoService/getHoliDeInfo'; /*URL*/
##	getHoliDeInfo : 국경일
##	getRestDeInfo : 공휴일
$queryParams = '?' . urlencode('serviceKey') . '=JeoUfC00XQQ6w3bFhm4%2F3CAmxcqxFu9ysZNoCIsgKD4mbcr%2FXX8YEg9LdBWOcNYy5rnFLzflFeQ0RayoqQ%2FpDQ%3D%3D'; /*Service Key*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /**/
$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('20'); /**/
$queryParams .= '&' . urlencode('solYear') . '=' . urlencode('2024'); /**/
$queryParams .= '&' . urlencode('solMonth') . '=' . urlencode('03'); /**/

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);
// var_dump($response);

$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$array = json_decode($json,TRUE);

	var_dump($array);

echo "<br><br><br>".$array['body']['items']['item']['locdate'];
if($array['body']['items']['item']['locdate']){
	echo $array['body']['items']['item']['locdate'].$array['body']['items']['item']['dateName'];
}else{
	for($i=0;$i<count($array['body']['items']['item']);$i++){
		echo $array['body']['items']['item'][$i]['locdate'].$array['body']['items']['item'][$i]['dateName']."<br/>";
	}
}
?>