<?php include("../inc/header.php"); ?>
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

		<!-- Container -->
		<div class="container sub" id="container">

			<!-- pageTitle -->
			<div class="pageTitle inner only mb1">아이디 찾기 완료</div>
			<!-- //pageTitle -->

			<div class="memberText">
				개인정보 도용 피해방지를 위해 일부 정보는 *로 표기됩니다.<br />비밀번호가 기억나지 않으실 경우 재설정이 가능합니다.
			</div>

			<!-- memberWrap -->
			<div class="memberWrap">

				<div class="shadowDiv">
					<div class="memberBox">
						<div class="title">
							<div class="tit titBig"><?=$user_id?></div>
							<div class="text">가입일 : <?=$arrList["list"][0]["wdate"]?></div>
						</div>
						<div class="btnDouble">
							<a href="login.php" class="btnType1">로그인</a>
							<a href="pwSet.php" class="btnType1 lineBlue">비밀번호 재설정</a>
						</div>
					</div>
				</div>
	
			</div>
			<!-- //memberWrap -->
			

		</div>
		<!-- //Container -->


<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

	</div>
	<!-- //Wrap -->


</body>
</html>



