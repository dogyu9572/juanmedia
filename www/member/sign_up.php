<?php $gNum="09"; $sNum="02"; $gName="회원"; $sName="회원가입"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?
@session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/_api/_NICE/niceapi_token_inc.php";
$_SESSION["SEAR_TYPE"] = "join";

$response_type = "code";
$client_id	= "O_l4hDsO2jNKdo8gr42b";
$redirect_uri = $_SITE["DOMAIN"].'_callback/naver_callback.php';
$state = "kca_auth";

$naver_login_url = "https://nid.naver.com/oauth2.0/authorize?response_type=".$response_type."&client_id=".$client_id."&redirect_uri=".$redirect_uri."&state=".urlencode($state);

$redirect_uri = $_SITE["DOMAIN"].'_callback/kakao_callback.php';
$client_id	= "39446b83eda31ee73b9d08771c8ed472";
$response_type = "code";

$kakao_login_url = "https://kauth.kakao.com/oauth/authorize?response_type=".$response_type."&redirect_uri=".$redirect_uri."&client_id=".$client_id."&state=".urlencode($state);

if($_GET["rt_url"]){
	$returnUrl = $_GET["rt_url"];	
}else{
	$tmpUrl = explode("://",$_SERVER["HTTP_REFERER"]);
	$retUrl = explode("/",$tmpUrl[1]);
	for($i=1;$i<count($retUrl);$i++){
		$setUrl .= "/". $retUrl[$i];
	}
	$returnUrl = $setUrl;
}
if($returnUrl){
	$_SESSION["RETURNURL"] = $returnUrl;
}else{
	$_SESSION["RETURNURL"] = "/";
}
?>
<script type="text/javascript">
<!--
function fnNicePopup(){
	fnPopup();	// 리얼
}
//-->
</script>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner">
		<div class="ctit"><?=$sName?></div>

		<ul class="mem_area join_step">
			<li class="i1 on"><i></i><div class="step">STEP 01</div><p>휴대폰 본인인증</p></li>
			<li class="i2"><i></i><div class="step">STEP 02</div><p>가입정보 입력 및 약관 동의</p></li>
			<li class="i3"><i></i><div class="step">STEP 03</div><p>회원가입 완료</p></li>
		</ul>

		<div class="mem_area bg_gray join_wrap">
			<p class="tac">회원가입을 위해 <strong>휴대폰 본인인증</strong>을 진행해주세요.</p>
			<button type="submit" class="btn" onclick="fnNicePopup()">휴대폰 본인인증</button>
		</div>

		<div class="mem_area sns_login">
			<div class="tt"><span>SNS 로그인</span></div>
			<div class="aset flex">
				<a href="<?=$naver_login_url?>" class="naver">네이버</a>
				<!-- <a href="javascript:void(0);" class="google">구글<div id="buttonDiv"><div class="S9gUrf-YoZ4jf" style="position: relative;"><div></div><iframe></iframe></div></div></a> -->
				<a href="<?=$kakao_login_url?>" class="kakao">카카오</a>
			</div>
		</div>
	</div>

</div> <!-- //container -->
<?php include("../pub/inc/_footer.php") ?>