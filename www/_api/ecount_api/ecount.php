<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";


/**
API테스트 인증키   아이디   인증키
   NYFOOD2024   4da818102cc24484b8932aca45cb565dd9
이카운트테스트계정   거래처코드   
   0123456789   
   1123456789   
   2123456789

   회사코드 : 22349
   ZONE : CC
   {"EXPIRE_DATE":null,"COM_CODE":"22349","DOMAIN":".ecount.com","STATUS":"E","ZONE":"CC","DB_SHARD_NO":null,"CS_COM_CODE":"goldenbrown","ACCESS_ALL":null,"APP_DT_FROM":null,"APP_DT_TO":null,"MSG":"","DB_CON_FLAG":"83","EMPTY_ZONE":null,"SIP":"59465444460a1603071a1e0a1a"},"Status":"200","Errors":null,"Error":null,"Timestamp":"2024-02-22 14:54:43.673","RequestKey":null,"IsEnableNoL4":false,"RefreshTimestamp":"0","AsyncActionKey":null}

   SESSION_ID : 32323334397c4e59464f4f4432303234:CC-AQyYcoET7tXlg
				32323334397c4e59464f4f4432303234:CC-AQyjGkMuOPW!r

   {"Data":{"TRACE_ID":"63c5671b8ce94cf9a7e221a573e8aba9","EXPIRE_DATE":"","QUANTITY_INFO":"시간당 연속 오류 제한 건수 : 0/30건, 1시간 허용량 : 3/30000건, 1일 허용량 : 3/100000건","SuccessCnt":1,"FailCnt":0,"ResultDetails":[{"Line":0,"IsSuccess":true,"TotalError":"[전표묶음0] OK","Errors":null,"Code":null}],"SlipNos":["20240222-467"]},"Status":"200","Errors":null,"Error":null,"Timestamp":"2024-02-22 15:13:28.746","RequestKey":null,"IsEnableNoL4":false,"RefreshTimestamp":"0","AsyncActionKey":null}
**/
//수신자 정보
function ecount_erp_api($SESSION_ID, $postvars){	
	//	$SESSION_ID="32323334397c4e59464f4f4432303234:CC-AQyjGkMuOPW!r";
	$api_server = "https://sboapiCC.ecount.com";				// Test서버 : https://sboapi{ZONE}.ecount.com / 운영서버 : https://oapi{ZONE}.ecount.com
	$api_url = "/OAPI/V2/Sale/SaveSale?SESSION_ID=".$SESSION_ID;		// url

	/***********************************************
	$UPLOAD_SER_NO = "1";			// 순번
	$IO_DATE	= date("Y-m-d");	// 배송일자
	$QTY		= "1";				// 수량
	$PROD_CD	= "00001";			// 품목코드
	$PROD_DES	= "test";			// 품목명
	$CUST		//	거래처코드
	$IO_TYPE	//	거래유형 11현금/13카드
	$PRICE		//	단가
	$SUPPLY_AMT	//	공급가
	$VAT_AMT	//	부가세
	$WH_CD		= "300";			// 출하창고[고정]
	$EMP_CD		= "";		//담당자	
	************************************************/
	/************************************************
	$postvars = '{
		"SaleList": [{
			"BulkDatas": {
				"IO_DATE": "'.$IO_DATE.'",
				"UPLOAD_SER_NO": "'.$UPLOAD_SER_NO.'",
				"CUST": "'.$CUST.'",
				"CUST_DES": "",
				"EMP_CD": "'.$EMP_CD.'",
				"WH_CD": "'.$WH_CD.'",
				"IO_TYPE": "'.$IO_TYPE.'",
				"EXCHANGE_TYPE": "",
				"EXCHANGE_RATE": "",
				"SITE": "",
				"PJT_CD": "",
				"DOC_NO": "",
				"TTL_CTT": "",
				"U_MEMO1": "",
				"U_MEMO2": "",
				"U_MEMO3": "",
				"U_MEMO4": "",
				"U_MEMO5": "",
				"ADD_TXT_01_T": "",
				"ADD_TXT_02_T": "",
				"ADD_TXT_03_T": "",
				"ADD_TXT_04_T": "",
				"ADD_TXT_05_T": "",
				"ADD_TXT_06_T": "",
				"ADD_TXT_07_T": "",
				"ADD_TXT_08_T": "",
				"ADD_TXT_09_T": "",
				"ADD_TXT_10_T": "",
				"ADD_NUM_01_T": "",
				"ADD_NUM_02_T": "",
				"ADD_NUM_03_T": "",
				"ADD_NUM_04_T": "",
				"ADD_NUM_05_T": "",
				"ADD_CD_01_T": "",
				"ADD_CD_02_T": "",
				"ADD_CD_03_T": "",
				"ADD_DATE_01_T": "",
				"ADD_DATE_02_T": "",
				"ADD_DATE_03_T": "",
				"U_TXT1": "",
				"ADD_LTXT_01_T": "",
				"ADD_LTXT_02_T": "",
				"ADD_LTXT_03_T": "",
				"PROD_CD": "'.$PROD_CD.'",
				"PROD_DES": "'.$PROD_DES.'",
				"SIZE_DES": "",
				"UQTY": "",
				"QTY": "'.$QTY.'",
				"PRICE": "'.$PRICE.'",
				"USER_PRICE_VAT": "",
				"SUPPLY_AMT": "'.$SUPPLY_AMT.'",
				"SUPPLY_AMT_F": "",
				"VAT_AMT": "'.$VAT_AMT.'",
				"REMARKS": "",
				"ITEM_CD": "",
				"P_REMARKS1": "",
				"P_REMARKS2": "",
				"P_REMARKS3": "",
				"ADD_TXT_01": "",
				"ADD_TXT_02": "",
				"ADD_TXT_03": "",
				"ADD_TXT_04": "",
				"ADD_TXT_05": "",
				"ADD_TXT_06": "",
				"REL_DATE": "",
				"REL_NO": "",
				"MAKE_FLAG": "",
				"CUST_AMT": "",
				"P_AMT1": "",
				"P_AMT2": "",
				"ADD_NUM_01": "",
				"ADD_NUM_02": "",
				"ADD_NUM_03": "",
				"ADD_CD_01": "",
				"ADD_CD_02": "",
				"ADD_CD_03": "",
				"ADD_CD_NM_01": "",
				"ADD_CD_NM_02": "",
				"ADD_CD_NM_03": "",
				"ADD_CDNM_01": "",
				"ADD_CDNM_02": "",
				"ADD_CDNM_03": "",
				"ADD_DATE_01": "",
				"ADD_DATE_02": "",
				"ADD_DATE_03": ""
		  }
	   }]
	}';
	************************************************/
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
	//	echo $jsonValue['Data']['SuccessCnt'];	// 1 : 성공
	return $jsonValue['Data']['SuccessCnt'];
}


//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$Query = "
SELECT a.order_no,a.shipping_date, a.pay_type, a.order_cust, a.order_class, a.coupon_amount from tbl_shop_order_info AS a
WHERE a.idx IN (".$_REQUEST['g_idx'].") ORDER BY a.idx DESC
";
$arrList = getFreeQueryR($Query);

//DB해제
//	SetDisConn($dblink);

$SESSION_ID = $_REQUEST['sid'];

for($i=0;$i<$arrList['list']['total'];$i++){
	$UPLOAD_SER_NO	= "";				// 순번
	$WH_CD			= "300";			// 출하창고[고정]
	$EMP_CD			= "";		//담당자
	$IO_DATE		= substr(str_replace("-","",$arrList['list'][$i]['shipping_date']),0,8);		// 배송일
	if($arrList['list'][$i]['pay_type']=="cash"){			//	결제타입
		$IO_TYPE = "11";
	}else{
		$IO_TYPE = "13";
	}
	$CUST		= $arrList['list'][$i]['order_cust'];		//	거래처번호
	
	
	
	$postvars = '{
		"SaleList": [';
	
	$Query = "
	SELECT b.g_vendor, b.g_qty, b.g_name, b.g_code, c.p_price, c.member_choice, c.member_price from tbl_shop_order_info AS a
	JOIN tbl_shop_order_good AS b ON a.order_no=b.order_no
	JOIN tbl_shop_good AS c ON  b.g_idx=c.idx
	WHERE a.order_no = '".$arrList['list'][$i]["order_no"]."' ORDER BY a.idx DESC
	";
	$arrGoodList = getFreeQueryR($Query);
	
	$comma = "";
	for($g=0;$g<$arrGoodList['list']['total'];$g++){
		$QTY		= $arrGoodList['list'][$g]['g_qty'];			//	수량
		$PROD_CD	= $arrGoodList['list'][$g]['g_code'];			//	품목코드
		$PROD_DES	= $arrGoodList['list'][$g]['g_name'];			//	품목명	
		//	$PRICE		= $arrGoodList['list'][$g]['p_price'];			//	상품단가

		$arrChoice	= explode("|",$arrGoodList['list'][$g]['member_choice']);
		$arrPrice	= explode("|",$arrGoodList['list'][$g]['member_price']);

		for($j=0;$j<count($arrChoice);$j++){
			if($arrList['list'][$i]['order_class']==$arrChoice[$j]){						
				$arrGoodList['list'][$g]["show_price"]		= $arrPrice[$j];			
				$arrGoodList['list'][$g]["show_tax"]		= $arrPrice[$j]*0.1;
			}
		}
		$PRICE		= $arrGoodList['list'][$g]["show_price"];			//	상품단가
		$SUPPLY_AMT = $arrGoodList['list'][$g]["show_price"]*$QTY;		//	공급금액
		$VAT_AMT	= $arrGoodList['list'][$g]["show_tax"]*$QTY;		//	부가세

		if($arrGoodList['list'][$g]["g_vendor"]=="admin"){	// 관리자가 직접 등록한 상품
			$PRICE		= 0;		//	상품단가
			$SUPPLY_AMT = 0;		//	공급금액
			$VAT_AMT	= 0;		//	부가세
		}
	
		$postvars .= $comma.'{
			"BulkDatas": {
					"IO_DATE": "'.$IO_DATE.'",
					"UPLOAD_SER_NO": "'.$UPLOAD_SER_NO.'",
					"CUST": "'.$CUST.'",
					"CUST_DES": "",
					"EMP_CD": "'.$EMP_CD.'",
					"WH_CD": "'.$WH_CD.'",
					"IO_TYPE": "'.$IO_TYPE.'",
					"EXCHANGE_TYPE": "",
					"EXCHANGE_RATE": "",
					"SITE": "",
					"PJT_CD": "",
					"DOC_NO": "",
					"TTL_CTT": "",
					"U_MEMO1": "",
					"U_MEMO2": "",
					"U_MEMO3": "",
					"U_MEMO4": "",
					"U_MEMO5": "",
					"ADD_TXT_01_T": "",
					"ADD_TXT_02_T": "",
					"ADD_TXT_03_T": "",
					"ADD_TXT_04_T": "",
					"ADD_TXT_05_T": "",
					"ADD_TXT_06_T": "",
					"ADD_TXT_07_T": "",
					"ADD_TXT_08_T": "",
					"ADD_TXT_09_T": "",
					"ADD_TXT_10_T": "",
					"ADD_NUM_01_T": "",
					"ADD_NUM_02_T": "",
					"ADD_NUM_03_T": "",
					"ADD_NUM_04_T": "",
					"ADD_NUM_05_T": "",
					"ADD_CD_01_T": "",
					"ADD_CD_02_T": "",
					"ADD_CD_03_T": "",
					"ADD_DATE_01_T": "",
					"ADD_DATE_02_T": "",
					"ADD_DATE_03_T": "",
					"U_TXT1": "",
					"ADD_LTXT_01_T": "",
					"ADD_LTXT_02_T": "",
					"ADD_LTXT_03_T": "",
					"PROD_CD": "'.$PROD_CD.'",
					"PROD_DES": "'.$PROD_DES.'",
					"SIZE_DES": "",
					"UQTY": "",
					"QTY": "'.$QTY.'",
					"PRICE": "'.$PRICE.'",
					"USER_PRICE_VAT": "",
					"SUPPLY_AMT": "'.$SUPPLY_AMT.'",
					"SUPPLY_AMT_F": "",
					"VAT_AMT": "'.$VAT_AMT.'",
					"REMARKS": "",
					"ITEM_CD": "",
					"P_REMARKS1": "",
					"P_REMARKS2": "",
					"P_REMARKS3": "",
					"ADD_TXT_01": "",
					"ADD_TXT_02": "",
					"ADD_TXT_03": "",
					"ADD_TXT_04": "",
					"ADD_TXT_05": "",
					"ADD_TXT_06": "",
					"REL_DATE": "",
					"REL_NO": "",
					"MAKE_FLAG": "",
					"CUST_AMT": "",
					"P_AMT1": "",
					"P_AMT2": "",
					"ADD_NUM_01": "",
					"ADD_NUM_02": "",
					"ADD_NUM_03": "",
					"ADD_CD_01": "",
					"ADD_CD_02": "",
					"ADD_CD_03": "",
					"ADD_CD_NM_01": "",
					"ADD_CD_NM_02": "",
					"ADD_CD_NM_03": "",
					"ADD_CDNM_01": "",
					"ADD_CDNM_02": "",
					"ADD_CDNM_03": "",
					"ADD_DATE_01": "",
					"ADD_DATE_02": "",
					"ADD_DATE_03": ""
			}
		}';
		$comma = ",";
	}
	################################################ 쿠폰사용이 있으면 ################################################ ST
	if($arrList['list'][$i]['coupon_amount']>1){
		$PROD_CD	= "D-0004";
		$PROD_DES	= "쿠폰할인";
		$QTY		= "-1";
		$PRICE		= $arrList['list'][$i]['coupon_amount'];			//	상품단가
		$SUPPLY_AMT = ceil($PRICE/1.1);		//	공급금액
		$VAT_AMT	= floor($SUPPLY_AMT*0.1);		//	부가세

		$postvars .= $comma.'{
			"BulkDatas": {
					"IO_DATE": "'.$IO_DATE.'",
					"UPLOAD_SER_NO": "'.$UPLOAD_SER_NO.'",
					"CUST": "'.$CUST.'",
					"CUST_DES": "",
					"EMP_CD": "'.$EMP_CD.'",
					"WH_CD": "'.$WH_CD.'",
					"IO_TYPE": "'.$IO_TYPE.'",
					"EXCHANGE_TYPE": "",
					"EXCHANGE_RATE": "",
					"SITE": "",
					"PJT_CD": "",
					"DOC_NO": "",
					"TTL_CTT": "",
					"U_MEMO1": "",
					"U_MEMO2": "",
					"U_MEMO3": "",
					"U_MEMO4": "",
					"U_MEMO5": "",
					"ADD_TXT_01_T": "",
					"ADD_TXT_02_T": "",
					"ADD_TXT_03_T": "",
					"ADD_TXT_04_T": "",
					"ADD_TXT_05_T": "",
					"ADD_TXT_06_T": "",
					"ADD_TXT_07_T": "",
					"ADD_TXT_08_T": "",
					"ADD_TXT_09_T": "",
					"ADD_TXT_10_T": "",
					"ADD_NUM_01_T": "",
					"ADD_NUM_02_T": "",
					"ADD_NUM_03_T": "",
					"ADD_NUM_04_T": "",
					"ADD_NUM_05_T": "",
					"ADD_CD_01_T": "",
					"ADD_CD_02_T": "",
					"ADD_CD_03_T": "",
					"ADD_DATE_01_T": "",
					"ADD_DATE_02_T": "",
					"ADD_DATE_03_T": "",
					"U_TXT1": "",
					"ADD_LTXT_01_T": "",
					"ADD_LTXT_02_T": "",
					"ADD_LTXT_03_T": "",
					"PROD_CD": "'.$PROD_CD.'",
					"PROD_DES": "'.$PROD_DES.'",
					"SIZE_DES": "",
					"UQTY": "",
					"QTY": "'.$QTY.'",
					"PRICE": "'.$PRICE.'",
					"USER_PRICE_VAT": "",
					"SUPPLY_AMT": "-'.$SUPPLY_AMT.'",
					"SUPPLY_AMT_F": "",
					"VAT_AMT": "-'.$VAT_AMT.'",
					"REMARKS": "",
					"ITEM_CD": "",
					"P_REMARKS1": "",
					"P_REMARKS2": "",
					"P_REMARKS3": "",
					"ADD_TXT_01": "",
					"ADD_TXT_02": "",
					"ADD_TXT_03": "",
					"ADD_TXT_04": "",
					"ADD_TXT_05": "",
					"ADD_TXT_06": "",
					"REL_DATE": "",
					"REL_NO": "",
					"MAKE_FLAG": "",
					"CUST_AMT": "",
					"P_AMT1": "",
					"P_AMT2": "",
					"ADD_NUM_01": "",
					"ADD_NUM_02": "",
					"ADD_NUM_03": "",
					"ADD_CD_01": "",
					"ADD_CD_02": "",
					"ADD_CD_03": "",
					"ADD_CD_NM_01": "",
					"ADD_CD_NM_02": "",
					"ADD_CD_NM_03": "",
					"ADD_CDNM_01": "",
					"ADD_CDNM_02": "",
					"ADD_CDNM_03": "",
					"ADD_DATE_01": "",
					"ADD_DATE_02": "",
					"ADD_DATE_03": ""
			}
		}';
	}
	################################################ 쿠폰사용이 있으면 ################################################ ED
	$postvars .= ']	}';
	//	echo $postvars."<br/><br/>";

	//echo "|".$IO_DATE."/".$QTY."/".$PROD_CD."/".$PROD_DES."/".$IO_TYPE."/".$CUST."/".$PRICE."/".$SUPPLY_AMT."/".$VAT_AMT."|";
	
	$successCnt = ecount_erp_api($SESSION_ID, $postvars);
	if($successCnt>0){
		//	echo "|품목코드:".$PROD_CD."/품목명:".$PROD_DES."/".$QTY."개|";
		echo "주문번호:".$arrList["list"][$i]["order_no"]."\n";
	}
}
?>전송완료