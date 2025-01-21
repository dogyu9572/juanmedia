<?
if($_POST['chkAgree']!="Y"){
	echo "<script>
	document.location.href = '/member.php?goPage=Agree';
	</script>";
}
if($_POST['chkAgree2']!="Y"){
	echo "<script>
	document.location.href = '/member.php?goPage=Agree';
	</script>";
}


$CurTime = date('YmdHis');
$RandNo = rand(100000, 999999);

//요청 번호 생성
$reqNum = $CurTime.$RandNo;

$cpId       = "KBOM1001";        			// 회원사ID
if($_SERVER['HTTP_HOST']=="www.frienpi.com" || $_SERVER['HTTP_HOST']=="frienpi.com") {
	$urlCode    = "006001";  
} else {
	$urlCode    = "005001";  
}
//$urlCode    = "005001";     					// URL코드 www.frienpi.co.kr, www.frienpi.com 006001 
$certNum    = $reqNum;     // 요청번호
$date       = $CurTime;        // 요청일시
$certMet    = "M";     // 본인인증방법
$birthDay   = "";	// 생년월일
$gender     = "";		// 성별
$name       = "";        // 성명
$phoneNo    = "";		// 휴대폰번호
$phoneCorp 	= "";	// 이동통신사
$nation     = "";      // 내외국인 구분
$plusInfo   = "";	// 추가DATA정보
$tr_url     = "http://".$_SERVER['HTTP_HOST']."/member.php?goPage=CertOk&certNum=".$certNum;     // 본인인증 결과수신 POPUP URL
$extendVar  = "0000000000000000";       // 확장변수


// [ certNum 주의사항 ]--------------------------------------------------------------------------------------
// 1. 본인인증 결과값 복호화를 위한 키로 활용되므로 중요함.
// 2. 본인인증 요청시 중복되지 않게 생성해야함. (예-시퀀스번호)
// 3. certNum값 생성 후 쿠키 또는 Session에 저장한 후 본인인증 결과값 수신 후 복호화키로 사용함.
// 4. 아래 샘플은 쿠키를 사용하지 않았음.
//----------------------------------------------------------------------------------------------------------

$name = str_replace(" ", "+", $name) ;  //성명에 space가 들어가는 경우 "+"로 치환하여 암호화 처리

//02. certNum 쿠키 생성
//setcookie("certNum", $certNum, time()+600);

//03. tr_cert 데이터변수 조합 (서버로 전송할 데이터 "/"로 조합)
$tr_cert	= $cpId . "/" . $urlCode . "/" . $certNum . "/" . $date . "/" . $certMet . "/" . $birthDay . "/" . $gender . "/" . $name . "/" . $phoneNo . "/" . $phoneCorp . "/" . $nation . "/" . $plusInfo . "/" . $extendVar;

//암호화모듈 호출
if (extension_loaded('ICERTSecu')) {

	//04. 1차암호화
	$enc_tr_cert = ICertSeed(1,0,'',$tr_cert);

	//05. 변조검증값 생성
	$enc_tr_cert_hash = ICertHMac($enc_tr_cert);
	
	//06. 2차암호화
	$enc_tr_cert = $enc_tr_cert . "/" . $enc_tr_cert_hash . "/" . "0000000000000000";

	$enc_tr_cert = ICertSeed(1,0,'',$enc_tr_cert);

}else{
   echo("암호화모듈 호출 실패!!!");
   return;
}
?>
<script language=javascript>
<!--
  var KMCIS_window;

  function openKMCISWindow(){    

    var UserAgent = navigator.userAgent;
    /* 모바일 접근 체크*/
    // 모바일일 경우 (변동사항 있을경우 추가 필요)
    if (UserAgent.match(/iPhone|iPod|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i) != null || UserAgent.match(/LG|SAMSUNG|Samsung/) != null) {
      document.reqKMCISForm.target = '';
		} 
		
		// 모바일이 아닐 경우
		else {
    	KMCIS_window = window.open('', 'KMCISWindow', 'width=425, height=550, resizable=0, scrollbars=no, status=0, titlebar=0, toolbar=0, left=435, top=250' );

     	if(KMCIS_window == null){
    	  alert(" ※ 윈도우 XP SP2 또는 인터넷 익스플로러 7 사용자일 경우에는 \n    화면 상단에 있는 팝업 차단 알림줄을 클릭하여 팝업을 허용해 주시기 바랍니다. \n\n※ MSN,야후,구글 팝업 차단 툴바가 설치된 경우 팝업허용을 해주시기 바랍니다.");
      }
     	
     	document.reqKMCISForm.target = 'KMCISWindow';
		}
		  
		document.reqKMCISForm.action = 'https://www.kmcert.com/kmcis/web/kmcisReq.jsp';
		document.reqKMCISForm.submit();
  }

//-->
</script>

	<div id="sub_container">
		<div class="content">
			<div class="location">
				<p class="local"><span class="home"></span><span class="current">Member Join</span></p>
			</div>
			<!-- //location -->
			
			<div class="con">
			<!-- 내용 : s -->
				
				<h2>Member Join</h2>
				<div class="member">
					<p class="mb30"><img src="/img/join_step02.jpg" alt="02본인확인" /></p>
					<div class="joinStep02">
						<p class="font20 colBlcok mb10">본인인증</p>
						<p class="mb10">안전한 회원 가입을 위해 본인인증을 진행해주시기 비랍니다.</p>
						<div class="box">
							<p class="t01">휴대폰을 이용한 본인인증은 이용자의 개인정보를 보호하기 위해 <br />
								frienpi에 주민등록번호를 제공하지 않고 본인임을 확인 할 수 있는 인증 수단입니다.</p>	
							<p class="t02"><span>반드시 실명으로 가입해 주세요!</span> <br />
								타인 명의의 휴대폰을 도용하여 사용하는 경우 관련법률에 의거하여 처벌을 받을 수 있습니다.</p>
						</div>
						<!-- //box -->
					</div>
					<!-- //joinStep02 -->
					<form name="reqKMCISForm" method="post" action="#">
					<input type="hidden" name="tr_cert"     value = "<?php echo $enc_tr_cert ?>">
					<input type="hidden" name="tr_url"      value = "<?php echo $tr_url ?>">
					<div class="btnC">
						<a href="/member.php?goPage=Agree" class="btn_gray3">이전단계</a>
						<a href="javascript:openKMCISWindow();" class="btn_gray4">인증하기</a>
					</div>
					</form>
				</div>
				<!-- //member -->
			<!-- 내용 : e -->	
			</div>
			<!-- //con -->
		</div>
		<!--//content --> 
	</div>
