<?php
// KMC 본인인증 범용서비스 샘플소스 STEP03
// 최종작성일 2013.12.03 
//---------------------------------------------------------------------------------------------------------

    $rec_cert = $_REQUEST['rec_cert'];
	$certNum  = $_REQUEST['certNum']; // certNum값을 쿠키 또는 Session을 생성하지 않았을때 certNum 수신처리
?>
<html>
<head>
<script type="text/javascript">
	var move_page_url = "http://회원사별 경로/kmcis_web_sample_step04.php";
	

	function end() {
	   	// 결과 페이지 경로 셋팅
    	document.kmcis_form.action = move_page_url;

   		var UserAgent = navigator.userAgent;
    	/* 모바일 접근 체크*/
    	// 모바일일 경우 (변동사항 있을경우 추가 필요)
    	if (UserAgent.match(/iPhone|iPod|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i) != null || UserAgent.match(/LG|SAMSUNG|Samsung/) != null) {
		    document.kmcis_form.submit();
	  	} 
	  
	  	// 모바일이 아닐 경우
	  	else {
			document.kmcis_form.target = opener.window.name;
		  	document.kmcis_form.submit();
   		  	self.close();
	  	}
	}
</script>
</head>
<body onload="javascript:end()">
<form id="kmcis_form" name="kmcis_form" method="post">
	<input type="hidden"	name="rec_cert"		id="rec_cert"	value="<?php echo $rec_cert ?>"/>
	<input type="hidden"	name="certNum"		id="certNum"	value="<?php echo $certNum ?>"/>
</form>
</body>
</html>