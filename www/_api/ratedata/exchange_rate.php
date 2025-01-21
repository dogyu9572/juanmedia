<?
header( "Content-Type:application/json;charset=UTF-8" );
########################################################### 환률정보 ########################################################
/*
$ch = curl_init();

$apiKey	= "1ua1O7yhd4j0eGGDD8fWgNUNtF9U4EgW";
//$yDate	= date("Ymd", strtotime(date("Y-m-d")." -1 day"));
$yDate	= date("Ymd");
$apiUrl = "https://www.koreaexim.go.kr/site/program/financial/exchangeJSON?authkey=".$apiKey."&searchdate=".$yDate."&data=AP01";	## AP01 : 환율, AP02 : 대출금리, AP03 : 국제금리

//	https://www.koreaexim.go.kr/site/program/financial/exchangeJSON?authkey=1ua1O7yhd4j0eGGDD8fWgNUNtF9U4EgW&searchdate=20230727&data=AP01

curl_setopt($ch, CURLOPT_URL, $apiUrl);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

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

//	$xml = simplexml_load_string($exe, "SimpleXMLElement", LIBXML_NOCDATA);
//	$json = json_encode($xml);
//	$array = json_decode($json,TRUE);

//	$arrObj = json_decode($exe);

//	var_dump($arrObj);​

print_r($exe);


$xml = '[{"result":1,"cur_unit":"AED","ttb":"355.19","tts":"362.36","deal_bas_r":"358.78","bkpr":"358","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"358","kftc_deal_bas_r":"358.78","cur_nm":"아랍에미리트 디르함"},{"result":1,"cur_unit":"AUD","ttb":"853.48","tts":"870.73","deal_bas_r":"862.11","bkpr":"862","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"862","kftc_deal_bas_r":"862.11","cur_nm":"호주 달러"},{"result":1,"cur_unit":"BHD","ttb":"3,459.47","tts":"3,529.36","deal_bas_r":"3,494.42","bkpr":"3,494","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"3,494","kftc_deal_bas_r":"3,494.42","cur_nm":"바레인 디나르"},{"result":1,"cur_unit":"BND","ttb":"966.84","tts":"986.37","deal_bas_r":"976.61","bkpr":"976","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"976","kftc_deal_bas_r":"976.61","cur_nm":"브루나이 달러"},{"result":1,"cur_unit":"CAD","ttb":"959.66","tts":"979.05","deal_bas_r":"969.36","bkpr":"969","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"969","kftc_deal_bas_r":"969.36","cur_nm":"캐나다 달러"},{"result":1,"cur_unit":"CHF","ttb":"1,442.12","tts":"1,471.25","deal_bas_r":"1,456.69","bkpr":"1,456","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"1,456","kftc_deal_bas_r":"1,456.69","cur_nm":"스위스 프랑"},{"result":1,"cur_unit":"CNH","ttb":"184.45","tts":"188.18","deal_bas_r":"186.32","bkpr":"186","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"186","kftc_deal_bas_r":"186.32","cur_nm":"위안화"},{"result":1,"cur_unit":"DKK","ttb":"188.3","tts":"192.11","deal_bas_r":"190.21","bkpr":"190","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"190","kftc_deal_bas_r":"190.21","cur_nm":"덴마아크 크로네"},{"result":1,"cur_unit":"EUR","ttb":"1,402.8","tts":"1,431.13","deal_bas_r":"1,416.97","bkpr":"1,416","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"1,416","kftc_deal_bas_r":"1,416.97","cur_nm":"유로"},{"result":1,"cur_unit":"GBP","ttb":"1,613.18","tts":"1,645.77","deal_bas_r":"1,629.48","bkpr":"1,629","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"1,629","kftc_deal_bas_r":"1,629.48","cur_nm":"영국 파운드"},{"result":1,"cur_unit":"HKD","ttb":"166.58","tts":"169.95","deal_bas_r":"168.27","bkpr":"168","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"168","kftc_deal_bas_r":"168.27","cur_nm":"홍콩 달러"},{"result":1,"cur_unit":"IDR(100)","ttb":"8.75","tts":"8.92","deal_bas_r":"8.84","bkpr":"8","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"8","kftc_deal_bas_r":"8.84","cur_nm":"인도네시아 루피아"},{"result":1,"cur_unit":"JPY(100)","ttb":"936.84","tts":"955.77","deal_bas_r":"946.31","bkpr":"946","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"946","kftc_deal_bas_r":"946.31","cur_nm":"일본 옌"},{"result":1,"cur_unit":"KRW","ttb":"0","tts":"0","deal_bas_r":"1","bkpr":"1","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"1","kftc_deal_bas_r":"1","cur_nm":"한국 원"},{"result":1,"cur_unit":"KWD","ttb":"4,242.67","tts":"4,328.38","deal_bas_r":"4,285.53","bkpr":"4,285","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"4,285","kftc_deal_bas_r":"4,285.53","cur_nm":"쿠웨이트 디나르"},{"result":1,"cur_unit":"MYR","ttb":"284.07","tts":"289.8","deal_bas_r":"286.94","bkpr":"286","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"286","kftc_deal_bas_r":"286.94","cur_nm":"말레이지아 링기트"},{"result":1,"cur_unit":"NOK","ttb":"119.01","tts":"121.42","deal_bas_r":"120.22","bkpr":"120","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"120","kftc_deal_bas_r":"120.22","cur_nm":"노르웨이 크로네"},{"result":1,"cur_unit":"NZD","ttb":"796.94","tts":"813.03","deal_bas_r":"804.99","bkpr":"804","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"804","kftc_deal_bas_r":"804.99","cur_nm":"뉴질랜드 달러"},{"result":1,"cur_unit":"SAR","ttb":"347.79","tts":"354.82","deal_bas_r":"351.31","bkpr":"351","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"351","kftc_deal_bas_r":"351.31","cur_nm":"사우디 리얄"},{"result":1,"cur_unit":"SEK","ttb":"121.68","tts":"124.13","deal_bas_r":"122.91","bkpr":"122","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"122","kftc_deal_bas_r":"122.91","cur_nm":"스웨덴 크로나"},{"result":1,"cur_unit":"SGD","ttb":"966.84","tts":"986.37","deal_bas_r":"976.61","bkpr":"976","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"976","kftc_deal_bas_r":"976.61","cur_nm":"싱가포르 달러"},{"result":1,"cur_unit":"THB","ttb":"37.66","tts":"38.43","deal_bas_r":"38.05","bkpr":"38","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"38","kftc_deal_bas_r":"38.05","cur_nm":"태국 바트"},{"result":1,"cur_unit":"USD","ttb":"1,304.32","tts":"1,330.67","deal_bas_r":"1,317.5","bkpr":"1,317","yy_efee_r":"0","ten_dd_efee_r":"0","kftc_bkpr":"1,317","kftc_deal_bas_r":"1,317.5","cur_nm":"미국 달러"}]';
$array = json_decode($xml,TRUE);
for($i=0;$i < count($array);$i++){
	if($array[$i]['cur_unit']=="USD"){	## 달러
		$usd = $array[$i]['tts'];
	}
	if($array[$i]['cur_unit']=="EUR"){	## 유로
		$eur = $array[$i]['tts'];
	}
	if($array[$i]['cur_unit']=="CHF"){	## 프랑
		$chf = $array[$i]['tts'];
	}
}
*/

################################################### index.php iframe 적용해야함
## 10시 이후 > 같은날짜에 데이터가 없으면 > api curl 연결후 검색한 데이터가 있으면 > 환율 입력 끝
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
if( date("H") > 9 ){ ## 10시 이후로 업데이트		
	include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$arrList = getArticleList("tbl_board_hidt_rate",1,0," where schedule_date = '".date("Y-m-d")."' ");

	if($arrList['total'] < 1){	################# 데이터가 없으면 입력		
		################################################################ curl ################################################################ ST
		$ch = curl_init();

		$apiKey	= "M0jAh57X1h2Z1t6mZ21Ta3Osx6holAVL";		## 지현수 키( 1ua1O7yhd4j0eGGDD8fWgNUNtF9U4EgW ) / 고객사 키( M0jAh57X1h2Z1t6mZ21Ta3Osx6holAVL )
		//$yDate	= date("Ymd", strtotime(date("Y-m-d")." -1 day"));
		$yDate	= date("Ymd");
		$apiUrl = "https://www.koreaexim.go.kr/site/program/financial/exchangeJSON?authkey=".$apiKey."&searchdate=".$yDate."&data=AP01";	## AP01 : 환율, AP02 : 대출금리, AP03 : 국제금리

		curl_setopt($ch, CURLOPT_URL, $apiUrl);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		$exe  = curl_exec($ch);
		curl_close($ch);

		$array = json_decode($exe,TRUE);
		for($i=0;$i < count($array);$i++){
			if($array[$i]['cur_unit']=="USD"){	## 달러
				$usd = $array[$i]['tts'];
			}
			if($array[$i]['cur_unit']=="EUR"){	## 유로
				$eur = $array[$i]['tts'];
			}
			if($array[$i]['cur_unit']=="CHF"){	## 프랑
				$chf = $array[$i]['tts'];
			}
			if($array[$i]['cur_unit']=="JPY(100)"){	## 엔화
				$jpy = $array[$i]['tts'];
			}
		}
		################################################################ curl ################################################################ ED

		if($usd && $eur && $chf){
			$sql = "
				INSERT INTO tbl_board_hidt_rate set 
					schedule_date = '".date("Y-m-d")."',
					usd_amount	= '".$usd."',
					eur_amount	= '".$eur."',
					chf_amount	= '".$chf."',
					jpy_amount	= '".$jpy."',
					wdate		= now()
			";
			//	echo $sql;
			mysqli_query($GLOBALS['dblink'], $sql);
		}
	}
	//DB해제
	SetDisConn($dblink);
}
################################################################# 오픈 알림 ################################################################# ST
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$ndate		= strtotime(date("Y-m-d H:i:s"));
$subQuery	= " AND sendflag='N' AND sdate LIKE '".date("Y-m-d")."%' AND stime > ".$ndate;
//$subQuery	= " AND sendflag='N' "; ## 테스트
$arrAlarmList = getFreeView("tbl_shop_alarm", $subQuery, "*", 0, 0, "");

if($arrAlarmList["list"]["total"] > 0){
	for($i=0; $i < $arrAlarmList["list"]["total"]; $i++){
		################################### 회원이름 ################################### ST
		$arrMemberInfo[$i] = getFreeView("tbl_member", " AND user_id='".$arrAlarmList['list'][$i]['user_id']."' ", "user_name, mobile", 0, 0, "");
		################################### 회원이름 ################################### ED
		################################### 알림대상 ################################### ST
		$arrLiveInfo[$i] = getFreeView("tbl_shop_good", " AND idx='".$arrAlarmList['list'][$i]['g_idx']."' ", "*", 0, 0, "");
		################################### 알림대상 ################################### ED

		$user_name		= $arrMemberInfo[$i]['list'][0]['user_name'];		## 회원이름
		$user_mobile	= $arrMemberInfo[$i]['list'][0]['mobile'];			## 회원전번
		$good_name		= $arrLiveInfo[$i]['list'][0]['g_name'];			## 상품이름

		//	echo $user_name.$user_mobile.$good_name."<br/>";	## 테스트
		kakaoAllim("28", $user_name, $user_mobile, $good_name, "", "", "", "");

		$sql = "update tbl_shop_alarm set sendflag='Y' where g_idx='".$arrAlarmList["list"][$i]["g_idx"]."' ";
		getFreeQueryCud($sql);
	}
}

//DB해제
SetDisConn($dblink);
################################################################# 오픈 알림 ################################################################# ED
?>