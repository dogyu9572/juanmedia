<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

// 최종마감일에 결제완료되지 않은 인원에게 전송

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
	// 등록안내
	// 매일 자정 마다 실행시킬 것

	$now_date = date("Y-m-d");

	// 1순위 최종마감일이거나 2순위 최종마감일 마지막 시일인 경우
	$Query = "select * from tbl_board_evaluation where (pay1_l_edate = '".$now_date."' OR pay2_l_edate = '".$now_date."')";
	$arrEvaluationList = getFreeQueryR($Query); // 해당 산업평가 목록을 불러옴


	// LEFT JOIN을 써도 되지만 가시성 과 대용량 처리가 필요해질 경우를 대비하여 반복문으로 수정함
	
	if($arrEvaluationList["total"] > 0){
		for($i=0;$i<$arrEvaluationList["total"];$i++){
			if($arrEvaluationList["list"][$i]["pay1_l_edate"] == $now_date){ // 1순위 발표일이라면
				$Query = "select * from tbl_board_accept where homepage = '".$arrEvaluationList["list"][$i]["idx"]."' and accept_state = '5' and accept_cate = '1' and accept_flag = 'Y' and order_state != '6' "; //1순위 이고 접수완료이며 최종선정이며 등록완료가 아닌 신청서를 불러옴
				
			}else if($arrEvaluationList["list"][$i]["pay2_l_edate"] == $now_date){ // 2순위 발표일이라면
				$Query = "select * from tbl_board_accept where homepage = '".$arrEvaluationList["list"][$i]["idx"]."' and accept_state = '5' and accept_cate = '2' and accept_flag = 'Y' and order_state != '6' "; //2순위 이고 접수완료이며 최종선정이며 등록완료가 아닌 신청서를 불러옴
			}else{ // 에러
				continue;
			}
			$arrAcceptList = getFreeQueryR($Query); // 해당 평가 접수 관리 내역을 불러옴
			for($j=0;$j<$arrAcceptList["total"];$j++){
				$arrVarData = array(
					"#{평가안내페이지}" => $_SITE["DOMAIN"]."qualified_candidates/evaluation_guide.php?user_code=".base64_encode($arrAcceptList["list"][$j]["w_user"])."&idx=".$arrAcceptList["list"][$j]["idx"]
				);
				if($arrAcceptList["list"][$j]["accept_cate"] == "1"){
					$arrVarData["#{최종선정일}"] = $arrEvaluationInfo["list"][0]["pay1_f_edate"];
				}else if($arrAcceptList["list"][$j]["accept_cate"] == "2"){
					$arrVarData["#{최종선정일}"] = $arrEvaluationInfo["list"][0]["pay2_f_edate"];
				}

				if($_SITE["SMS_USE"] && $arrAcceptList["list"][$j]["sms_accept"] == "Y"){ // 문자 전송
					smsLmsSureApi("kca_05", $arrVarData, $arrAcceptList["list"][$j]["tel"]);

					$sql = "
						insert into tbl_sms_log set 
						type = 'sms',
						templete_code = 'kca_05',
						tel = '".$arrAcceptList["list"][$j]["tel"]."',
						wdate = now()
					";
					$rs = mysqli_query($dblink,$sql);
				}
				if($_SITE["ARLIMTALK_USE"] && $arrAcceptList["list"][$j]["kakao_accept"] == "Y"){ // 카카오 알림톡 전송
					kakaoApiTalk("kca_05", $arrVarData, $arrAcceptList["list"][$j]["tel"]);

					$sql = "
						insert into tbl_sms_log set 
						type = 'kakao',
						templete_code = 'kca_05',
						tel = '".$arrAcceptList["list"][$j]["tel"]."',
						wdate = now()
					";
					$rs = mysqli_query($dblink,$sql);
				}
			}
		}
	}


SetDisConn($dblink);
?>