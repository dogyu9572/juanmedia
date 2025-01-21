<?php  
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/giftcard/giftcard.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

	$PayMethod			= $_GET['PayMethod']; 
	$MID				= $_REQUEST['MID'];
	$Amt				= $_REQUEST['Amt'];
	$BuyerName			= $_REQUEST['BuyerName'];
	$GoodsName			= $_REQUEST['GoodsName'];
	$OID				= $_REQUEST['OID'];
	$mallUserID			= $_REQUEST['mallUserID'];
	$AuthDate			= $_REQUEST['AuthDate'];
	$AuthCode			= $_REQUEST['AuthCode'];
	$ResultCode			= $_REQUEST['ResultCode'];
	$ResultMsg			= $_REQUEST['ResultMsg'];
	$VbankNum			= $_REQUEST['VbankNum'];
	$VbankName			= $_REQUEST['VbankName'];
	                                 
	$MallReserved	    = $_REQUEST['MallReserved'];
	$TID				= $_REQUEST['TID'];
	$AcquCardCode		= $_REQUEST['AcquCardCode'];	
	$AcquCardName		= $_REQUEST['AcquCardName'];	
	$CardUsePoint		= $_REQUEST['CardUsePoint'];	
	$SignValue			= $_REQUEST['SignValue'];	
	$merchantKey		= "ew/2rFGhMl9ZyFutE+Jy+mHt5auYB2+7d4uZl9shUTfnmgTheJq/Lr8bKoAxplsw+X+XT95B6L0jFOSMXZs1xg==";
	//$merchantKey		= "0/4GFsSd7ERVRGX9WHOzJ96GyeMTwvIaKSWUCKmN3fDklNRGw3CualCFoMPZaS99YiFGOuwtzTkrLo4bR4V+Ow==";
	$VerifySignValue	= base64_encode(md5(substr($TID,0,10).$ResultCode.substr($TID,10,5).$merchantKey.substr($TID,15,15)));

	
	$ipkum_date = date("Y-m-d H:i:s");

	$dblink = SetConn($_conf_db["main_db"]);

	  if("3001" == $ResultCode){ //CARD
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT

		 $sql = "update tbl_shop_order_info set order_state='6',ipkum_date='$ipkum_date', tid='".$TID."' where order_no='".$OID."'";
		 $rs = mysql_query($sql);

		//상품권구매시
		if($MallReserved=="G" || $MallReserved=="A") {
			if($Amt=="50000") {
				$arrGiftCardInfo = getGiftcardInfo(5); //상품권정보
			} else if($Amt=="100000") {
				$arrGiftCardInfo = getGiftcardInfo(6); //상품권정보
			} else if($Amt=="300000") {
				$arrGiftCardInfo = getGiftcardInfo(7); //상품권정보
			} 

			$serial = substr(strtoupper(md5($arrGiftCardInfo["list"][0]['giftcard_name'].microtime(true))),0,16);
			$edate =  date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")+1));

			$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["mygiftcard"]." set 
				user_id='".$mallUserID."',
				e_idx = '".$arrGiftCardInfo["list"][0]['idx']."',
				giftcard_no = '".$serial."',
				giftcard_name = '".$arrGiftCardInfo["list"][0]['giftcard_name']."',
				giftcard_content = '".$arrGiftCardInfo["list"][0]['giftcard_content']."',
				giftcard_sdate = now(),
				giftcard_edate = '".$edate."',
				giftcard_dis = '".$arrGiftCardInfo["list"][0]['giftcard_dis']."',
				giftcard_unit = '".$arrGiftCardInfo["list"][0]['giftcard_unit']."',
				over_price = '".$arrGiftCardInfo["list"][0]['over_price']."',
				under_price = '".$arrGiftCardInfo["list"][0]['under_price']."',
				giftcard_use = 'N',
				order_no = '".$OID."'
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$insert_idx = mysqli_insert_id($GLOBALS['dblink']);

			$sql_up = "UPDATE ".$GLOBALS["_conf_tbl"]["shop_order_good"]." set
					g_vendor='".$serial."'
				WHERE order_no = '".$OID."'
			";
			$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);
		}
		if($MallReserved=="G") { //바로발송
			$arrShip = getOrderInfoAdmin($OID);

			$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["giftcard_send"]." set 
				mgf_idx = '".$insert_idx."',
				user_id = '".$mallUserID."',
				send_name = '".$arrShip["list"][0]["ship_name"]."',
				mobile = '".$arrShip["list"][0]["ship_mobile"]."',
				email = '".$arrShip["list"][0]["ship_email"]."',
				memo = '".$arrShip["list"][0]["order_comment"]."',
				mail_sms = '".$arrShip["list"][0]["mail_sms"]."',
				wdate = now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			$sql_up = "update ".$GLOBALS["_conf_tbl"]["mygiftcard"]." set user_id='' where idx='$insert_idx'";
			$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);

			
			$arrMailInfo = getMailConfig("giftcard");

			if($arrShip["list"][0]["mail_sms"]=="MS" || $arrShip["list"][0]["mail_sms"]=="S") {
				$sms_Contents = stripslashes($arrMailInfo["list"][0]["m_subject"]);
				$sms_Contents = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $sms_Contents);
				$sms_Contents = str_replace("{SERIAL}", $serial, $sms_Contents);
				
				$sms_to = $arrShip["list"][0]["ship_mobile"];
				$sms_from = "02-577-7180";
				$sms_date = "";
				$sms_msg = $sms_Contents;
				$sms_type = "L";    // 설정 하지 않는다면 80byte 넘는 메시지는 쪼개져서 sms로 발송, L 로 설정하면 80byte 넘으면 자동으로 lms 변환

				$sms = new EmmaSMS();
				$sms->login("toyskool93501", "kibos7180");
				$ret = $sms->send($sms_to, $sms_from, $sms_msg, $sms_date, $sms_type);
			}
			
			if($arrShip["list"][0]["mail_sms"]=="MS" || $arrShip["list"][0]["mail_sms"]=="M") {
		
				if($Amt=="300000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8941/m_3e317c8d3f1a3bbaff51f532691dd0e30.jpg";
				} else if($Amt=="100000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8940/m_185feeeba3457aa50eee0e3b864933270.jpg";
				} else if($Amt=="50000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8939/m_e60ae9244f83ab89449de040a103d20d0.jpg";
				} 

				//변수치환
				$Mail_Subject = stripslashes($arrMailInfo["list"][0]["subject"]);
				$Mail_Subject = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $Mail_Subject);
				$Mail_Subject = str_replace("{SHIPNAME}", $arrShip["list"][0]["ship_name"], $Mail_Subject);
				
				$Mail_Contents = stripslashes($arrMailInfo["list"][0]["contents"]);
				$Mail_Contents = str_replace("{SERIAL}", $serial, $Mail_Contents);
				$Mail_Contents = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $Mail_Contents);
				$Mail_Contents = str_replace("{SHIPNAME}", $arrShip["list"][0]["ship_name"], $Mail_Contents);
				$Mail_Contents = str_replace("{MOBILE}", $arrShip["list"][0]["ship_mobile"], $Mail_Contents);
				$Mail_Contents = str_replace("{IMG}", $img, $Mail_Contents);
				$Mail_Contents = str_replace("{COMMENT}", nl2br(stripslashes($arrShip["list"][0]["order_comment"])), $Mail_Contents);
				
				sendMail($GLOBALS["_SITE"]["EMAIL"], $GLOBALS["_SITE"]["NAME"], $arrShip["list"][0]["ship_email"], $arrShip["list"][0]["ship_name"], $Mail_Subject , $Mail_Contents);
			}
			
		}

	 }

	  if("4000" == $ResultCode){ //BANK
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT

		 $sql = "update tbl_shop_order_info set order_state='6',ipkum_date='$ipkum_date', tid='".$TID."' where order_no='".$OID."'";
		 $rs = mysql_query($sql);

		 //상품권구매시
		if($MallReserved=="G" || $MallReserved=="A") {
			if($Amt=="50000") {
				$arrGiftCardInfo = getGiftcardInfo(5); //상품권정보
			} else if($Amt=="100000") {
				$arrGiftCardInfo = getGiftcardInfo(6); //상품권정보
			} else if($Amt=="300000") {
				$arrGiftCardInfo = getGiftcardInfo(7); //상품권정보
			} 

			$serial = substr(strtoupper(md5($arrGiftCardInfo["list"][0]['giftcard_name'].microtime(true))),0,16);
			$edate =  date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")+1));

			$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["mygiftcard"]." set 
				user_id='".$mallUserID."',
				e_idx = '".$arrGiftCardInfo["list"][0]['idx']."',
				giftcard_no = '".$serial."',
				giftcard_name = '".$arrGiftCardInfo["list"][0]['giftcard_name']."',
				giftcard_content = '".$arrGiftCardInfo["list"][0]['giftcard_content']."',
				giftcard_sdate = now(),
				giftcard_edate = '".$edate."',
				giftcard_dis = '".$arrGiftCardInfo["list"][0]['giftcard_dis']."',
				giftcard_unit = '".$arrGiftCardInfo["list"][0]['giftcard_unit']."',
				over_price = '".$arrGiftCardInfo["list"][0]['over_price']."',
				under_price = '".$arrGiftCardInfo["list"][0]['under_price']."',
				giftcard_use = 'N',
				order_no = '".$OID."'
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$insert_idx = mysqli_insert_id($GLOBALS['dblink']);

			$sql_up = "UPDATE ".$GLOBALS["_conf_tbl"]["shop_order_good"]." set
					g_vendor='".$serial."'
				WHERE order_no = '".$OID."'
			";
			$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);
		}
		if($MallReserved=="G") { //바로발송
			$arrShip = getOrderInfoAdmin($OID);

			$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["giftcard_send"]." set 
				mgf_idx = '".$insert_idx."',
				user_id = '".$mallUserID."',
				send_name = '".$arrShip["list"][0]["ship_name"]."',
				mobile = '".$arrShip["list"][0]["ship_mobile"]."',
				email = '".$arrShip["list"][0]["ship_email"]."',
				memo = '".$arrShip["list"][0]["order_comment"]."',
				mail_sms = '".$arrShip["list"][0]["mail_sms"]."',
				wdate = now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			$sql_up = "update ".$GLOBALS["_conf_tbl"]["mygiftcard"]." set user_id='' where idx='$insert_idx'";
			$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);

			$arrMailInfo = getMailConfig("giftcard");

			if($arrShip["list"][0]["mail_sms"]=="MS" || $arrShip["list"][0]["mail_sms"]=="S") {
				$sms_Contents = stripslashes($arrMailInfo["list"][0]["m_subject"]);
				$sms_Contents = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $sms_Contents);
				$sms_Contents = str_replace("{SERIAL}", $serial, $sms_Contents);
				
				$sms_to = $arrShip["list"][0]["ship_mobile"];
				$sms_from = "02-577-7180";
				$sms_date = "";
				$sms_msg = $sms_Contents;
				$sms_type = "L";    // 설정 하지 않는다면 80byte 넘는 메시지는 쪼개져서 sms로 발송, L 로 설정하면 80byte 넘으면 자동으로 lms 변환

				$sms = new EmmaSMS();
				$sms->login("toyskool93501", "kibos7180");
				$ret = $sms->send($sms_to, $sms_from, $sms_msg, $sms_date, $sms_type);
			}
			
			if($arrShip["list"][0]["mail_sms"]=="MS" || $arrShip["list"][0]["mail_sms"]=="M") {
		
				if($Amt=="300000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8941/m_3e317c8d3f1a3bbaff51f532691dd0e30.jpg";
				} else if($Amt=="100000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8940/m_185feeeba3457aa50eee0e3b864933270.jpg";
				} else if($Amt=="50000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8939/m_e60ae9244f83ab89449de040a103d20d0.jpg";
				} 

				//변수치환
				$Mail_Subject = stripslashes($arrMailInfo["list"][0]["subject"]);
				$Mail_Subject = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $Mail_Subject);
				$Mail_Subject = str_replace("{SHIPNAME}", $arrShip["list"][0]["ship_name"], $Mail_Subject);
				
				$Mail_Contents = stripslashes($arrMailInfo["list"][0]["contents"]);
				$Mail_Contents = str_replace("{SERIAL}", $serial, $Mail_Contents);
				$Mail_Contents = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $Mail_Contents);
				$Mail_Contents = str_replace("{SHIPNAME}", $arrShip["list"][0]["ship_name"], $Mail_Contents);
				$Mail_Contents = str_replace("{MOBILE}", $arrShip["list"][0]["ship_mobile"], $Mail_Contents);
				$Mail_Contents = str_replace("{IMG}", $img, $Mail_Contents);
				$Mail_Contents = str_replace("{COMMENT}", nl2br(stripslashes($arrShip["list"][0]["order_comment"])), $Mail_Contents);
				
				sendMail($GLOBALS["_SITE"]["EMAIL"], $GLOBALS["_SITE"]["NAME"], $arrShip["list"][0]["ship_email"], $arrShip["list"][0]["ship_name"], $Mail_Subject , $Mail_Contents);
			}

		}


	  }
	  if("4100" == $ResultCode){ //VBANK 체번완료
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
		 $sql = "update tbl_shop_order_info set order_state='1',ipkum_date='$ipkum_date', tid='".$TID."' where order_no='".$OID."'";
		 $rs = mysql_query($sql);
	  }
	  if("4110" == $ResultCode){ //VBANK 입금완료
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
		 $sql = "update tbl_shop_order_info set order_state='6',ipkum_date='$ipkum_date', tid='".$TID."' where order_no='".$OID."'";
		 $rs = mysql_query($sql);

		 //상품권구매시
		if($MallReserved=="G" || $MallReserved=="A") {
			if($Amt=="50000") {
				$arrGiftCardInfo = getGiftcardInfo(5); //상품권정보
			} else if($Amt=="100000") {
				$arrGiftCardInfo = getGiftcardInfo(6); //상품권정보
			} else if($Amt=="300000") {
				$arrGiftCardInfo = getGiftcardInfo(7); //상품권정보
			} 

			$serial = substr(strtoupper(md5($arrGiftCardInfo["list"][0]['giftcard_name'].microtime(true))),0,16);
			$edate =  date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")+1));

			$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["mygiftcard"]." set 
				user_id='".$mallUserID."',
				e_idx = '".$arrGiftCardInfo["list"][0]['idx']."',
				giftcard_no = '".$serial."',
				giftcard_name = '".$arrGiftCardInfo["list"][0]['giftcard_name']."',
				giftcard_content = '".$arrGiftCardInfo["list"][0]['giftcard_content']."',
				giftcard_sdate = now(),
				giftcard_edate = '".$edate."',
				giftcard_dis = '".$arrGiftCardInfo["list"][0]['giftcard_dis']."',
				giftcard_unit = '".$arrGiftCardInfo["list"][0]['giftcard_unit']."',
				over_price = '".$arrGiftCardInfo["list"][0]['over_price']."',
				under_price = '".$arrGiftCardInfo["list"][0]['under_price']."',
				giftcard_use = 'N',
				order_no = '".$OID."'
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$insert_idx = mysqli_insert_id($GLOBALS['dblink']);

			$sql_up = "UPDATE ".$GLOBALS["_conf_tbl"]["shop_order_good"]." set
					g_vendor='".$serial."'
				WHERE order_no = '".$OID."'
			";
			$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);
		}
		if($MallReserved=="G") { //바로발송
			$arrShip = getOrderInfoAdmin($OID);

			$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["giftcard_send"]." set 
				mgf_idx = '".$insert_idx."',
				user_id = '".$mallUserID."',
				send_name = '".$arrShip["list"][0]["ship_name"]."',
				mobile = '".$arrShip["list"][0]["ship_mobile"]."',
				email = '".$arrShip["list"][0]["ship_email"]."',
				memo = '".$arrShip["list"][0]["order_comment"]."',
				mail_sms = '".$arrShip["list"][0]["mail_sms"]."',
				wdate = now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			$sql_up = "update ".$GLOBALS["_conf_tbl"]["mygiftcard"]." set user_id='' where idx='$insert_idx'";
			$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);

			$arrMailInfo = getMailConfig("giftcard");

			if($arrShip["list"][0]["mail_sms"]=="MS" || $arrShip["list"][0]["mail_sms"]=="S") {
				$sms_Contents = stripslashes($arrMailInfo["list"][0]["m_subject"]);
				$sms_Contents = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $sms_Contents);
				$sms_Contents = str_replace("{SERIAL}", $serial, $sms_Contents);
				
				$sms_to = $arrShip["list"][0]["ship_mobile"];
				$sms_from = "02-577-7180";
				$sms_date = "";
				$sms_msg = $sms_Contents;
				$sms_type = "L";    // 설정 하지 않는다면 80byte 넘는 메시지는 쪼개져서 sms로 발송, L 로 설정하면 80byte 넘으면 자동으로 lms 변환

				$sms = new EmmaSMS();
				$sms->login("toyskool93501", "kibos7180");
				$ret = $sms->send($sms_to, $sms_from, $sms_msg, $sms_date, $sms_type);
			}
			
			if($arrShip["list"][0]["mail_sms"]=="MS" || $arrShip["list"][0]["mail_sms"]=="M") {
		
				if($Amt=="300000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8941/m_3e317c8d3f1a3bbaff51f532691dd0e30.jpg";
				} else if($Amt=="100000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8940/m_185feeeba3457aa50eee0e3b864933270.jpg";
				} else if($Amt=="50000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8939/m_e60ae9244f83ab89449de040a103d20d0.jpg";
				} 

				//변수치환
				$Mail_Subject = stripslashes($arrMailInfo["list"][0]["subject"]);
				$Mail_Subject = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $Mail_Subject);
				$Mail_Subject = str_replace("{SHIPNAME}", $arrShip["list"][0]["ship_name"], $Mail_Subject);
				
				$Mail_Contents = stripslashes($arrMailInfo["list"][0]["contents"]);
				$Mail_Contents = str_replace("{SERIAL}", $serial, $Mail_Contents);
				$Mail_Contents = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $Mail_Contents);
				$Mail_Contents = str_replace("{SHIPNAME}", $arrShip["list"][0]["ship_name"], $Mail_Contents);
				$Mail_Contents = str_replace("{MOBILE}", $arrShip["list"][0]["ship_mobile"], $Mail_Contents);
				$Mail_Contents = str_replace("{IMG}", $img, $Mail_Contents);
				$Mail_Contents = str_replace("{COMMENT}", nl2br(stripslashes($arrShip["list"][0]["order_comment"])), $Mail_Contents);
				
				sendMail($GLOBALS["_SITE"]["EMAIL"], $GLOBALS["_SITE"]["NAME"], $arrShip["list"][0]["ship_email"], $arrShip["list"][0]["ship_name"], $Mail_Subject , $Mail_Contents);
			}

		}

	  }
	  if("A000" == $ResultCode){ //cellphone
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
		 $sql = "update tbl_shop_order_info set order_state='6',ipkum_date='$ipkum_date', tid='".$TID."' where order_no='".$OID."'";
		 $rs = mysql_query($sql);

		 //상품권구매시
		if($MallReserved=="G" || $MallReserved=="A") {
			if($Amt=="50000") {
				$arrGiftCardInfo = getGiftcardInfo(5); //상품권정보
			} else if($Amt=="100000") {
				$arrGiftCardInfo = getGiftcardInfo(6); //상품권정보
			} else if($Amt=="300000") {
				$arrGiftCardInfo = getGiftcardInfo(7); //상품권정보
			} 

			$serial = substr(strtoupper(md5($arrGiftCardInfo["list"][0]['giftcard_name'].microtime(true))),0,16);
			$edate =  date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")+1));

			$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["mygiftcard"]." set 
				user_id='".$mallUserID."',
				e_idx = '".$arrGiftCardInfo["list"][0]['idx']."',
				giftcard_no = '".$serial."',
				giftcard_name = '".$arrGiftCardInfo["list"][0]['giftcard_name']."',
				giftcard_content = '".$arrGiftCardInfo["list"][0]['giftcard_content']."',
				giftcard_sdate = now(),
				giftcard_edate = '".$edate."',
				giftcard_dis = '".$arrGiftCardInfo["list"][0]['giftcard_dis']."',
				giftcard_unit = '".$arrGiftCardInfo["list"][0]['giftcard_unit']."',
				over_price = '".$arrGiftCardInfo["list"][0]['over_price']."',
				under_price = '".$arrGiftCardInfo["list"][0]['under_price']."',
				giftcard_use = 'N',
				order_no = '".$OID."'
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$insert_idx = mysqli_insert_id($GLOBALS['dblink']);

			$sql_up = "UPDATE ".$GLOBALS["_conf_tbl"]["shop_order_good"]." set
					g_vendor='".$serial."'
				WHERE order_no = '".$OID."'
			";
			$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);
		}
		if($MallReserved=="G") { //바로발송
			$arrShip = getOrderInfoAdmin($OID);

			$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["giftcard_send"]." set 
				mgf_idx = '".$insert_idx."',
				user_id = '".$mallUserID."',
				send_name = '".$arrShip["list"][0]["ship_name"]."',
				mobile = '".$arrShip["list"][0]["ship_mobile"]."',
				email = '".$arrShip["list"][0]["ship_email"]."',
				memo = '".$arrShip["list"][0]["order_comment"]."',
				mail_sms = '".$arrShip["list"][0]["mail_sms"]."',
				wdate = now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			$sql_up = "update ".$GLOBALS["_conf_tbl"]["mygiftcard"]." set user_id='' where idx='$insert_idx'";
			$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);

			$arrMailInfo = getMailConfig("giftcard");

			if($arrShip["list"][0]["mail_sms"]=="MS" || $arrShip["list"][0]["mail_sms"]=="S") {
				$sms_Contents = stripslashes($arrMailInfo["list"][0]["m_subject"]);
				$sms_Contents = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $sms_Contents);
				$sms_Contents = str_replace("{SERIAL}", $serial, $sms_Contents);
				
				$sms_to = $arrShip["list"][0]["ship_mobile"];
				$sms_from = "02-577-7180";
				$sms_date = "";
				$sms_msg = $sms_Contents;
				$sms_type = "L";    // 설정 하지 않는다면 80byte 넘는 메시지는 쪼개져서 sms로 발송, L 로 설정하면 80byte 넘으면 자동으로 lms 변환

				$sms = new EmmaSMS();
				$sms->login("toyskool93501", "kibos7180");
				$ret = $sms->send($sms_to, $sms_from, $sms_msg, $sms_date, $sms_type);
			}
			
			if($arrShip["list"][0]["mail_sms"]=="MS" || $arrShip["list"][0]["mail_sms"]=="M") {
		
				if($Amt=="300000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8941/m_3e317c8d3f1a3bbaff51f532691dd0e30.jpg";
				} else if($Amt=="100000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8940/m_185feeeba3457aa50eee0e3b864933270.jpg";
				} else if($Amt=="50000") {
					$img = "http://frienpi.co.kr/uploaded/shop_good/8939/m_e60ae9244f83ab89449de040a103d20d0.jpg";
				} 

				//변수치환
				$Mail_Subject = stripslashes($arrMailInfo["list"][0]["subject"]);
				$Mail_Subject = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $Mail_Subject);
				$Mail_Subject = str_replace("{SHIPNAME}", $arrShip["list"][0]["ship_name"], $Mail_Subject);
				
				$Mail_Contents = stripslashes($arrMailInfo["list"][0]["contents"]);
				$Mail_Contents = str_replace("{SERIAL}", $serial, $Mail_Contents);
				$Mail_Contents = str_replace("{ORDERNAME}", $arrShip["list"][0]["order_name"], $Mail_Contents);
				$Mail_Contents = str_replace("{SHIPNAME}", $arrShip["list"][0]["ship_name"], $Mail_Contents);
				$Mail_Contents = str_replace("{MOBILE}", $arrShip["list"][0]["ship_mobile"], $Mail_Contents);
				$Mail_Contents = str_replace("{IMG}", $img, $Mail_Contents);
				$Mail_Contents = str_replace("{COMMENT}", nl2br(stripslashes($arrShip["list"][0]["order_comment"])), $Mail_Contents);
				
				sendMail($GLOBALS["_SITE"]["EMAIL"], $GLOBALS["_SITE"]["NAME"], $arrShip["list"][0]["ship_email"], $arrShip["list"][0]["ship_name"], $Mail_Subject , $Mail_Contents);
			}

		}

	  }
	  if("7001" == $ResultCode){ //현금영수증
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	
	SetDisConn($dblink);
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>스마트로::인터넷결제</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>

<link href="./css/Confirmation.css" rel="stylesheet" type="text/css" />
<link href="./css/ims.css" rel="stylesheet" type="text/css" />
<link href="./css/mms.css" rel="stylesheet" type="text/css" />
<link href="./css/payment.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
<script>
function winClose() {
	opener.document.location.href="/shop.php?goPage=Thanks&order_no=<?=$OID?>";
	window.close();
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="window.focus(); window.resizeTo(670, 810)"   onbeforeunload="" oncontextmenu='return false' ondragstart='return false' onselectstart='return false'>
<table width="650" border="0" cellpadding="0" cellspacing="0" bgcolor="e5e5e5">
  <tr>
    <td width="153" rowspan="2" valign="top"><img src="./images/logo_c.gif" width="153" height="88" /></td>
    <td width="477" height="31" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" background="./images/bottom01.jpg">
      <tr>
        <td><img src="./images/no.gif" width="5" height="3" /></td>
      </tr>
    </table></td>
    <td width="20" rowspan="2" valign="top"><img src="./images/no.gif" width="20" height="20" /></td>
  </tr>
  <tr>
    <td valign="bottom"><table width="314" height="73" border="0" align="right" cellpadding="0" cellspacing="0">
      <tr>
        <td width="314" valign="bottom"><div align="right"><img src="./images/title01_.gif" /></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20"><img src="./images/no.gif" width="20" height="20" /></td>
    <td width="610">&nbsp;</td>
    <td width="20"><img src="./images/no.gif" width="20" height="20" /></td>
  </tr>
  <tr>
    <td rowspan="5">&nbsp;</td>
    <td height="50"><div align="center"><span class="style1">[ 결제 완료 결과입니다 ]</span></div></td>
    <td rowspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="1" background="./images/tb_patt03.jpg" bgcolor="#E2E2E2">
      <tr>
        <td width="151" height="30" bgcolor="#D9CDBF"><div align="center">지불수단</div></td>
        <td width="12" height="30" valign="middle">&nbsp;</td>
        <td width="423" height="30" valign="middle"><strong><?php echo $PayMethod ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상점ID</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $MID ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">금액</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $Amt ?>원</strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">구매자명</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $BuyerName ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">상품명</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $GoodsName ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">주문번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $OID ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">구매자 ID</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $mallUserID ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">승인번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><?php echo $AuthCode ?></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">결과코드</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $ResultCode ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">결과메시지</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $ResultMsg ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">거래고유번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $TID ?></strong></td>
      </tr>
      <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">가상계좌번호</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $VbankNum ?></strong></td>
      </tr>
	   <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">가상계좌은행</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $VbankName ?></strong></td>
      </tr>
	   <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">응답 사인값</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $SignValue ?></strong></td>
      </tr>
	   <tr>
        <td height="30" bgcolor="#D9CDBF"><div align="center">검증 사인값</div></td>
        <td height="30" valign="middle">&nbsp;</td>
        <td height="30" valign="middle"><strong><?php echo $VerifySignValue ?></strong></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center"><a href="javascript:winClose()"><img src="./images/btn_close.gif" width="70" height="23" /></a></div></td>
  </tr>
</table>
<table width="650" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="20"><img src="./images/no.gif" width="20" height="50" /></td>
    <td width="610" valign="bottom"><table width="100%" border="0" cellpadding="0" cellspacing="0" background="./images/bottom01.jpg">
      <tr>
        <td><img src="./images/no.gif" width="5" height="5" /></td>
      </tr>
    </table></td>
    <td width="20"><img src="./images/no.gif" width="20" height="50" /></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
