<?
##	https://www.data.go.kr/iim/api/selectAPIAcountView.do	//	한국천문연구원_특일 정보
$ch = curl_init();
$url = 'http://apis.data.go.kr/B551011/KorService1/areaCode1'; /*URL*/
##	areaCode1		: 지역코드조회
##	categoryCode1	: 서비스분류코드조회
##	areaBasedList1	: 지역기반관광정보조회
##	x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C/WF0qsKvcRDrxGsEi5hAw2eA==

$areaCode		= $sidoCode[$_GET['sido']];
if(!$areaCode){	$areaCode = "1"; }

$queryParams = '?' . urlencode('serviceKey') . '=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D'; /*Service Key*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1');		/**/
$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('100');	/**/
$queryParams .= '&' . urlencode('MobileOS') . '=' . urlencode('ETC');	/**/
$queryParams .= '&' . urlencode('MobileApp') . '=' . urlencode('TestApp');	/**/
$queryParams .= '&' . urlencode('areaCode') . '=' . urlencode($areaCode);	/* 서울 */

//	echo $url . $queryParams."<br/>";

//	http://apis.data.go.kr/B551011/KorService1/areaCode1?serviceKey=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D&numOfRows=10&pageNo=1&MobileOS=ETC&MobileApp=TestApp&_type=json
//	http://apis.data.go.kr/B551011/KorService1/areaCode1?serviceKey=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D&pageNo=1&numOfRows=10&MobileOS=ETC&MobileApp=TestApp

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);
// var_dump($response);

$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$arrGugun = json_decode($json,TRUE);

//	var_dump($arrGugun);

/**
for($i=0;$i<count($arrGugun['body']['items']['item']);$i++){
	echo $arrGugun['body']['items']['item'][$i]['code'].$arrGugun['body']['items']['item'][$i]['name']."<br/>";
}
**/
?>