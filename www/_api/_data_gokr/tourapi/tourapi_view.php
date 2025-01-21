<?
##	https://www.data.go.kr/iim/api/selectAPIAcountView.do	//	한국천문연구원_특일 정보
// 기본정보 가져오기
$ch = curl_init();
$url = 'http://apis.data.go.kr/B551011/KorService1/detailCommon1'; /*URL*/
##	areaCode1		: 지역코드조회
##	categoryCode1	: 서비스분류코드조회
##	areaBasedList1	: 지역기반관광정보조회
##	detailCommon1
$contentId = $_GET['cid'];
$contentTypeId = $_GET['ctype'];
$queryParams = '?' . urlencode('serviceKey') . '=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D'; /*Service Key*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1');		/**/
$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('10');	/**/
$queryParams .= '&' . urlencode('MobileOS') . '=' . urlencode('ETC');	/**/
$queryParams .= '&' . urlencode('MobileApp') . '=' . urlencode('TestApp');	/**/
$queryParams .= '&' . urlencode('contentTypeId') . '=' . urlencode($contentTypeId);	/* 관광지(12), 문화시설(14), 축제/공연/행사(15) */
$queryParams .= '&' . urlencode('contentId') . '=' . urlencode($contentId);	/* 콘텐츠ID */
$queryParams .= '&' . urlencode('defaultYN') . '=' . urlencode('Y');	/* 기본정보 */
$queryParams .= '&' . urlencode('firstImageYN') . '=' . urlencode('Y');	/* 대쵸이미지 */
$queryParams .= '&' . urlencode('addrinfoYN') . '=' . urlencode('Y');	/* 주소 */
$queryParams .= '&' . urlencode('overviewYN') . '=' . urlencode('Y');	/* 개요 */

//	echo $url . $queryParams."<br/>";

//	http://apis.data.go.kr/B551011/KorService1/areaCode1?serviceKey=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D&numOfRows=10&pageNo=1&MobileOS=ETC&MobileApp=TestApp&_type=json
//	http://apis.data.go.kr/B551011/KorService1/areaCode1?serviceKey=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D&pageNo=1&numOfRows=10&MobileOS=ETC&MobileApp=TestApp

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);

// 이미지 가져오기
$ch = curl_init();
$url = 'http://apis.data.go.kr/B551011/KorService1/detailImage1'; /*URL*/
##	areaCode1		: 지역코드조회
##	categoryCode1	: 서비스분류코드조회
##	areaBasedList1	: 지역기반관광정보조회
##	detailCommon1
$queryParams = '?' . urlencode('serviceKey') . '=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D'; /*Service Key*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1');		/**/
$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('10');	/**/
$queryParams .= '&' . urlencode('MobileOS') . '=' . urlencode('ETC');	/**/
$queryParams .= '&' . urlencode('MobileApp') . '=' . urlencode('TestApp');	/**/
$queryParams .= '&' . urlencode('contentId') . '=' . urlencode($contentId);	/* 콘텐츠ID */
$queryParams .= '&' . urlencode('imageYN') . '=' . urlencode('Y');	/* 기본 이미지 */
$queryParams .= '&' . urlencode('subImageYN') . '=' . urlencode('Y');	/* 서브 이미지 */

//	echo $url . $queryParams."<br/>";

//	http://apis.data.go.kr/B551011/KorService1/areaCode1?serviceKey=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D&numOfRows=10&pageNo=1&MobileOS=ETC&MobileApp=TestApp&_type=json
//	http://apis.data.go.kr/B551011/KorService1/areaCode1?serviceKey=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D&pageNo=1&numOfRows=10&MobileOS=ETC&MobileApp=TestApp

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$image_response = curl_exec($ch);
// 상세정보 들고오기 / 문의 및 안내만 사용
$ch = curl_init();
$url = 'http://apis.data.go.kr/B551011/KorService1/detailIntro1'; /*URL*/
##	areaCode1		: 지역코드조회
##	categoryCode1	: 서비스분류코드조회
##	areaBasedList1	: 지역기반관광정보조회
##	detailCommon1
$queryParams = '?' . urlencode('serviceKey') . '=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D'; /*Service Key*/
$queryParams .= '&' . urlencode('MobileOS') . '=' . urlencode('ETC');	/**/
$queryParams .= '&' . urlencode('MobileApp') . '=' . urlencode('TestApp');	/**/
$queryParams .= '&' . urlencode('contentTypeId') . '=' . urlencode($contentTypeId);	/* 관광지(12), 문화시설(14), 축제/공연/행사(15) */
$queryParams .= '&' . urlencode('contentId') . '=' . urlencode($contentId);	/* 콘텐츠ID */

//	echo $url . $queryParams."<br/>";

//	http://apis.data.go.kr/B551011/KorService1/areaCode1?serviceKey=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D&numOfRows=10&pageNo=1&MobileOS=ETC&MobileApp=TestApp&_type=json
//	http://apis.data.go.kr/B551011/KorService1/areaCode1?serviceKey=x02B8Db9kQoN2UzuzZAoVtWA6ZSc88tRo2fvdsSSx68eDsCwDn36llwa4L3x7C%2FWF0qsKvcRDrxGsEi5hAw2eA%3D%3D&pageNo=1&numOfRows=10&MobileOS=ETC&MobileApp=TestApp

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$detail_response = curl_exec($ch);
curl_close($ch);

//	var_dump($response);

$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$imgxml = simplexml_load_string($image_response, "SimpleXMLElement", LIBXML_NOCDATA);
$image_json = json_encode($imgxml);
$image_array = json_decode($image_json,TRUE);


$detailxml = simplexml_load_string($detail_response, "SimpleXMLElement", LIBXML_NOCDATA);
$detail_json = json_encode($detailxml);
$detail_array = json_decode($detail_json,TRUE);

//	var_dump($array);
/********
for($i=0;$i<count($array['body']['items']['item']);$i++){
	if($array['body']['items']['item'][$i]['firstimage']){
		echo "<img src='".$array['body']['items']['item'][$i]['firstimage']."' style='width:100px;'>";
	}
	echo $array['body']['items']['item'][$i]['title']."<br/>";
	echo $array['body']['items']['item'][$i]['addr1'].$array['body']['items']['item'][$i]['addr2']."<br/>";
	echo $array['body']['items']['item'][$i]['tel']."<br/>";
	echo $array['body']['items']['item'][$i]['mapx'].$array['body']['items']['item'][$i]['mapy']."<br/>";
}
********/
?>