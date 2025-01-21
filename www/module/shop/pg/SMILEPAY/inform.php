<?
	$PayMethod		= $_REQUEST['PayMethod'];
	$MID			    = $_REQUEST['MID'];
	$Amt			    = $_REQUEST['Amt'];
	$GoodsName		= $_REQUEST['GoodsName'];
	$OID			    = $_REQUEST['OID'];
	$AuthDate		  = $_REQUEST['AuthDate'];
	$AuthCode		  = $_REQUEST['AuthCode'];
	$ResultCode		= $_REQUEST['ResultCode'];
	$ResultMsg		= $_REQUEST['ResultMsg'];
	$stateCd        = $_REQUEST['state_cd'];   // 0: 결제승인, 1:전취소, 2:후취소
	$CardUsePoint   = $_REQUEST['CardUsePoint'];
	
	// 결제 승인 
	if("0" == $stateCd){
	  if("3001" == $ResultCode){ //CARD
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }

	  if("4000" == $ResultCode){ //BANK
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("4100" == $ResultCode){ //VBANK 체번완료
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("4110" == $ResultCode){ //VBANK 입금완료
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("A000" == $ResultCode){ //cellphone
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("7001" == $ResultCode){ //현금영수증
	     // 결제 성공시 DB처리 하세요.
	     // TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	}else{
	// 결제 취소
	  if("2001" == $ResultCode){
	     // 취소 성공시 DB처리 하세요.
	     //TID 결제 취소한 데이터 존재시 UPDATE, 존재하지 않을 경우 INSERT
	  }
	  if("2211" == $ResultCode){
	     // 환불
	  }
	
	
	} 
	
	
?>
<?=$PayMethod . '<br>'?>
<?=$MID . '<br>'?>
<?=$Amt . '<br>'?>
<?=$GoodsName . '<br>'?>
<?=$OID . '<br>'?>
<?=$AuthDate . '<br>'?>
<?=$AuthCode . '<br>'?>
<?=$ResultCode . '<br>'?>
<?=$ResultMsg . '<br>'?>
