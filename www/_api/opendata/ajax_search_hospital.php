<?
header( "Content-Type:application/json;charset=UTF-8" );
########################################################### 병원 & 의원 검색용 ########################################################
$ch = curl_init();

$sKey = "JL27SqGPmRc4iAkAAOEqKItpvDBArIAplRko3JdYuk964f72K1ivd5qfI5zX3WKo2fcXC6g7lnhVtE%2BiVANW8g%3D%3D";

//$_POST['popsk'] = "시티";

$searchVal = urlencode($_POST['popsk']);

$page = $_POST['page'];
if(!$page){$page=1;}

$url = 'https://apis.data.go.kr/B552657/HsptlAsembySearchService/getHsptlMdcncListInfoInqire?serviceKey='.$sKey.'&QN='.$searchVal.'&ORD=NAME&pageNo=1&numOfRows=100';

## ex url : https://apis.data.go.kr/B552657/HsptlAsembySearchService/getHsptlMdcncListInfoInqire?serviceKey=JL27SqGPmRc4iAkAAOEqKItpvDBArIAplRko3JdYuk964f72K1ivd5qfI5zX3WKo2fcXC6g7lnhVtE%2BiVANW8g%3D%3D&QN=%EC%8B%9C%ED%8B%B0&ORD=NAME&pageNo=1&numOfRows=100

//	$url = 'http://apis.data.go.kr/1230000/UsrInfoService/getDminsttInfo?type=json&inqryDiv='.$inqryDiv.'&inqryBgnDt=201001010000&inqryEndDt='.date("Ymd").'2359&pageNo='.$page.'&numOfRows=100&'.$searchNm.'='.$searchVal.'&ServiceKey='.$sKey;

//	$url = 'http://apis.data.go.kr/1230000/UsrInfoService/getDminsttInfo?type=json&inqryDiv='.$inqryDiv.'&inqryBgnDt='.date("Ym").'010000&inqryEndDt='.date("Ymd").'2359&pageNo='.$page.'&numOfRows=100&'.$searchNm.'='.$searchVal.'&ServiceKey='.$sKey;

//http://apis.data.go.kr/1230000/UsrInfoService/getDminsttInfo?inqryDiv=1&inqryBgnDt=201605010000&inqryEndDt=201605052359&pageNo=1&numOfRows=10&ServiceKey=인증키
// ex ) http://apis.data.go.kr/1230000/UsrInfoService/getDminsttInfo?type=json&inqryDiv=2&inqryBgnDt=201001010000&inqryEndDt=202111292359&pageNo=1&numOfRows=100&dminsttNm=123&ServiceKey=DjQ9uho4QJ1Ne3TDB4N34CYCFE0RBCxcZodrGj1Y0qRUeO6RPvvbOjEVIP9We6bzg6BYrSFuQFjXWmoIZB4iHw%3D%3D

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

//$response = curl_exec($ch);

//curl_close($ch);​

$exe  = curl_exec($ch);
$getInfo = curl_getinfo($ch);

if ($exe === false) {
    $output = "Error in sending";
    if (curl_error($ch)){
        $output .= "\n". curl_error($ch);
    }
} else if($getInfo['http_code'] != 777){
    $output = "No data returned. Error: " . $getInfo['http_code'];
    if (curl_error($ch)){
        $output .= "\n". curl_error($ch);
    }
}
curl_close($ch);

//	echo $output."<br/>";
/*
$xml = simplexml_load_string($exe, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$array = json_decode($json,TRUE);
*/
//$arrObj = json_decode($exe);

//var_dump($arrObj);​

print_r($exe); //출력

?>