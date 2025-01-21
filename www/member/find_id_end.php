<?php $gNum="09"; $sNum="03"; $gName="회원"; $sName="아이디 찾기"; ?>
<?php include("../pub/inc/_dtd.php") ?>
<?php include("../pub/inc/_header.php") ?>
<?php include("../pub/inc/_aside.php") ?>
<?php
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$nick_name = $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["USERCODE"];

$arrList = getUserInfoNnickname($nick_name);

//DB해제
SetDisConn($dblink);

if($arrList["total"] > 0){
	if($arrList["list"][0]["join_type"] != "homepage"){ // 소셜회원일 경우	
		$succcess = false;
	}else{
		$succcess = true;

		$user_id = substr($arrList["list"][0]["user_id"],0,3);
		for($i=3;$i<strlen($arrList["list"][0]["user_id"]);$i++){
			$user_id .="*";
		}
	}
}else{
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]		="";	## 이름
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"]		= "";	## 핸드폰
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["USERCODE"]	= "";				## 접수용 아이디
	jsGo("/member/login.php",'',"회원정보가 없습니다.");
}

	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]		="";	## 이름
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"]		= "";	## 핸드폰
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["USERCODE"]	= "";				## 접수용 아이디

	$arrSocial = array("kakao" => "카카오","naver" => "네이버");
?>
<div id="mainContent" class="container g<?=$gNum?> s<?=$sNum?>">
	
	<div class="inner inner_in">
		<div class="ctit"><?=$sName?></div>
		<?php if($succcess){?>
		<div class="mem_area bg_gray mem_end find_end">
			<p>아이디 찾기가 완료되었습니다.<br>회원님의 아이디는 <strong><?=$user_id?></strong> 입니다.</p>
			<a href="/member/login.php" class="btn">로그인</a>
			<a href="/member/find_pw.php" class="btn btn_l">비밀번호 찾기</a>
		</div>
		<?php }else{ ?>
		<div class="mem_area bg_gray mem_end find_sns">
			<p><strong><?=$arrList["list"][0]["user_name"]?></strong> 회원님은 <strong><?=$arrSocial[$arrList["list"][0]["join_type"]]?> 로그인</strong>을 통해 가입하셨습니다.<br><strong><?=$arrSocial[$arrList["list"][0]["join_type"]]?></strong> 로그인을 이용해주세요.</p>
			<a href="/member/login.php" class="btn">로그인</a>
		</div>
		<?php } ?>
	</div>

</div> <!-- //container -->
<?php include("../pub/inc/_footer.php") ?>