<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
	// 발급품 배송내역 
	// 매일 1시간 마다 실행시킬 것

	$now_date = date("Y-m-d");
	$now_hour = date("H");

	// 언론보도 전송일 인 경우
	
	$Query = "
		select * from tbl_board_accept where accept_state = '5' and accept_flag = 'Y' 
		and ship_link1_date = '".$now_date."' and ship_link1_time = '".$now_hour."'
	";
	
	$arrAcceptList = getFreeQueryR($Query); // 해당 평가 접수 관리 내역을 불러옴
	for($j=0;$j<$arrAcceptList["total"];$j++){
		$arrVarData = array(
			"#{기사링크}" => $arrAcceptList["list"][$j]["ship_link1"]
		);

		if($_SITE["SMS_USE"] && $arrAcceptList["list"][$j]["sms_accept"] == "Y"){ // 문자 전송
			smsLmsSureApi("kca_09", $arrVarData, $arrAcceptList["list"][$j]["tel"]);

			$sql = "
				insert into tbl_sms_log set 
				type = 'sms',
				templete_code = 'kca_09',
				tel = '".$arrAcceptList["list"][$j]["tel"]."',
				wdate = now()
			";
			$rs = mysqli_query($dblink,$sql);

		}
		if($_SITE["ARLIMTALK_USE"] && $arrAcceptList["list"][$j]["kakao_accept"] == "Y"){ // 카카오 알림톡 전송
			kakaoApiTalk("kca_09", $arrVarData, $arrAcceptList["list"][$j]["tel"]);

			$sql = "
				insert into tbl_sms_log set 
				type = 'kakao',
				templete_code = 'kca_09',
				tel = '".$arrAcceptList["list"][$j]["tel"]."',
				wdate = now()
			";
			$rs = mysqli_query($dblink,$sql);
		}
	}

	// 온라인 발급품 전송일 인 경우
	
	$Query = "
		select * from tbl_board_accept where accept_state = '5' and accept_flag = 'Y' 
		and ship_link2_date = '".$now_date."' and ship_link2_time = '".$now_hour."'
	";
	
	$arrAcceptList = getFreeQueryR($Query); // 해당 평가 접수 관리 내역을 불러옴
	for($j=0;$j<$arrAcceptList["total"];$j++){
		$arrVarData = array(
			"#{다운로드기한}" => $arrAcceptList["list"][$j]["ship_link2_show_date"]
		);

		if($_SITE["SMS_USE"] && $arrAcceptList["list"][$j]["sms_accept"] == "Y"){ // 문자 전송
			smsLmsSureApi("kca_10", $arrVarData, $arrAcceptList["list"][$j]["tel"]);

			$sql = "
				insert into tbl_sms_log set 
				type = 'sms',
				templete_code = 'kca_10',
				tel = '".$arrAcceptList["list"][$j]["tel"]."',
				wdate = now()
			";
			$rs = mysqli_query($dblink,$sql);

		}
		if($_SITE["ARLIMTALK_USE"] && $arrAcceptList["list"][$j]["kakao_accept"] == "Y"){ // 카카오 알림톡 전송
			kakaoApiTalk("kca_10", $arrVarData, $arrAcceptList["list"][$j]["tel"]);

			$sql = "
				insert into tbl_sms_log set 
				type = 'kakao',
				templete_code = 'kca_10',
				tel = '".$arrAcceptList["list"][$j]["tel"]."',
				wdate = now()
			";
			$rs = mysqli_query($dblink,$sql);
		}
	}

	// 오프라인 발급품 전송일 인 경우
	
	$Query = "
		select * from tbl_board_accept where accept_state = '5' and accept_flag = 'Y' 
		and ship_number_date = '".$now_date."' and ship_number_time = '".$now_hour."'
	";
	
	$arrAcceptList = getFreeQueryR($Query); // 해당 평가 접수 관리 내역을 불러옴
	for($j=0;$j<$arrAcceptList["total"];$j++){
		$arrVarData = array(
			"#{송장번호}" => $arrAcceptList["list"][$j]["ship_number"]
		);

		if($_SITE["SMS_USE"] && $arrAcceptList["list"][$j]["sms_accept"] == "Y"){ // 문자 전송
			smsLmsSureApi("kca_11", $arrVarData, $arrAcceptList["list"][$j]["tel"]);

			$sql = "
				insert into tbl_sms_log set 
				type = 'sms',
				templete_code = 'kca_11',
				tel = '".$arrAcceptList["list"][$j]["tel"]."',
				wdate = now()
			";
			$rs = mysqli_query($dblink,$sql);
		}
		if($_SITE["ARLIMTALK_USE"] && $arrAcceptList["list"][$j]["kakao_accept"] == "Y"){ // 카카오 알림톡 전송
			kakaoApiTalk("kca_11", $arrVarData, $arrAcceptList["list"][$j]["tel"]);

			$sql = "
				insert into tbl_sms_log set 
				type = 'kakao',
				templete_code = 'kca_11',
				tel = '".$arrAcceptList["list"][$j]["tel"]."',
				wdate = now()
			";
			$rs = mysqli_query($dblink,$sql);
		}
	}

	

SetDisConn($dblink);
?>