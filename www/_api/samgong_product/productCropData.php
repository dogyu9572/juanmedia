<?
ini_set('memory_limit','-1');
session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
//	include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

function get_product_api( $api_url ) {
	
	$return = array();
	if( $api_url != "" ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);

		if( $response == false ) {
			//$info = array();
		} else {
			$object = simplexml_load_string($response);
			$return = get_object_vars($object);
		}
	}
	return $return;
}

$p_idx = $_REQUEST['idx'];

if(!$p_idx){	
	echo "idx";
	exit;
}

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$subQuery  = "select subject, category from tbl_board_product where idx='".$p_idx."' ";
$arrProductInfo = getFreeQueryR($subQuery);

if($arrProductInfo['total']){
	$pestiBrandName		= $arrProductInfo['list'][0]['subject'];
	$product_type_str	= $arrProductInfo['list'][0]['category'];
}

if(!$product_type_str){	
	echo "category";
	exit;
}



if( trim($pestiBrandName) != "" ) {
	$apiKey			= "20198abc529cd2acc15dec1ea29cb442ad5c";		// 실서버 인증키
	$base_url		= "http://psis.rda.go.kr/openApi/service.do";
	$serviceCode	= "SVC01"; // [SVC01: 농약 검색 목록 서비스코드, SVC02 : 농약등록정보 상세]
	$serviceType	= "AA001"; // 서비스 타입 코드 [AA001:XML,AA002:Ajax]
	$displayCount	= 5000; // 검색결과 출력건수(최대 50건) Integer:기본값 10, 최대 50
	$startPoint		= 1; // 검색 시작 위치 Integer:기본값 10, 최대 50

	$useName		= ""; // 용도 검색어
	$cropName		= ""; // 작물명 검색어
	$cropName2		= ""; // 작물명2 검색어
	$diseaseWeedName= ""; // 적용병해충 검색어

	$pestiKorName	= ""; // 품목명 검색어
	//$pestiBrandName	= ""; // 상표명 검색어
	$compName		= "한국삼공(주)"; // 회사명 검색어

	//$pestiKorName	= "노발루론 액상수화제"; // 품목명 검색어
	//$pestiBrandName	= "라이몬"; // 상표명 검색어
	//$compName		= "한국삼공(주)"; // 회사명 검색어

	$product_data_list = array();
	
	$serviceCode	= "SVC01"; // [SVC01: 농약 검색 목록 서비스코드, SVC02 : 농약등록정보 상세]
	$api_url = "";
	$api_url .= $base_url;
	$api_url .= "?apiKey=".$apiKey;
	$api_url .= "&serviceCode=".$serviceCode;
	$api_url .= "&serviceType=".$serviceType;
	$api_url .= "&displayCount=".$displayCount;
	$api_url .= "&startPoint=".$startPoint;
	$api_url .= "&compName=".$compName; // 회사명
	//$api_url .= "&pestiBrandName=".$pestiBrandName; // 상표명
	//$api_url .= "&pestiKorName=".$pestiKorName; // 품목명
	$api_url .= "&pestiBrandName=".$pestiBrandName; // 품목명

	$response = get_product_api($api_url);
	//	echo $api_url;
	
	
	// object를 한번에 array로 변환하기위해 json으로 변환해서 다시 array로 변환.
	$response = json_decode(json_encode($response), true);
	//	echo $response['totalCount'];
	if( $response['totalCount'] > 0 ) {		
		$detail_list = $response['list']['item'];
		$detail_search_list = array();
		if( isset($detail_list['pestiCode']) ) {
			$detail_search_list[0] = $detail_list;
		} else {
			$detail_search_list = $detail_list;
		}

		if( is_array($detail_search_list) && count($detail_search_list) > 0) {
			$deleteQuery = "DELETE FROM tbl_product_cropdata WHERE product_idx = '".$p_idx."'";
			getFreeQueryCud($deleteQuery);

			foreach( $detail_search_list AS $detailKey => $detail_info ) {				
				######################################################## 해당부분은 사용안함 추후에 사용가능함으로 주석처리함
				/****************************
				$serviceCode	= "SVC02"; // [SVC01: 농약 검색 목록 서비스코드, SVC02 : 농약등록정보 상세]
				$pestiCode = $detail_info['pestiCode'];
				$diseaseUseSeq = $detail_info['diseaseUseSeq'];

				$api_url2 = "";
				$api_url2 .= $base_url;
				$api_url2 .= "?apiKey=".$apiKey;
				$api_url2 .= "&serviceCode=".$serviceCode;
				$api_url2 .= "&pestiCode=".$pestiCode;
				$api_url2 .= "&diseaseUseSeq=".$diseaseUseSeq;
				
				$response_detail = get_product_api($api_url2);
				*****************************/

				$insertQuery = "INSERT INTO tbl_product_cropdata set
					product_idx			= '".mysqli_real_escape_string($GLOBALS['dblink'], $p_idx)."', 
					crop_code			= '000000000',
					pestiCode			= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['pestiCode'])."',
					diseaseUseSeq		= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['diseaseUseSeq'])."',
					cropName			= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['cropName'])."',
					diseaseWeedName		= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['diseaseWeedName'])."',
					pestiUse			= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['pestiUse'])."',
					dilutUnit			= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['dilutUnit'])."',
					useSuittime			= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['useSuittime'])."',
					useNum				= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['useNum'])."',
					applyFirstRegDate	= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['applyFirstRegDate'])."',
					useName				= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['useName'])."',
					pestiKorName		= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['pestiKorName'])."',
					pestiBrandName		= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['pestiBrandName'])."',
					pestiEngName		= '',
					regCpntQnty			= '',
					toxicGubun			= '',
					toxicName			= '',
					fishToxicGubun		= '',
					engName				= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['engName'])."',
					cmpaItmNm			= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['cmpaItmNm'])."',
					indictSymbl			= '".mysqli_real_escape_string($GLOBALS['dblink'], $detail_info['indictSymbl'])."',
					reg_date			= NOW()
				";
				getFreeQueryCud($insertQuery);
				//	echo $insertQuery."<br/><br/><br/>";
			}
			echo "OK";
		}
	}else{
		echo "NULL";
	}	
}
SetDisConn($dblink);
?>