<?php include("../inc/header.php"); ?>
<?php

$response_type = "code";
$client_id	= "piN0UzyFFMT6EkKmIePX";
$redirect_uri = $_SITE["DOMAIN"].'_callback/naver_callback.php';
$state = "juan_auth";

$naver_login_url = "https://nid.naver.com/oauth2.0/authorize?response_type=".$response_type."&client_id=".$client_id."&redirect_uri=".$redirect_uri."&state=".urlencode($state);


$redirect_uri = $_SITE["DOMAIN"].'_callback/kakao_callback.php';

$client_id	= "4f089e9c5169339339a31c205533c88b"; // 본인의 REST API KEY를 입력해주세요
$response_type = "code";

$kakao_login_url = "https://kauth.kakao.com/oauth/authorize?response_type=".$response_type."&redirect_uri=".$redirect_uri."&client_id=".$client_id."&state=".urlencode($state);

$_SESSION["RETURNURL"] = "/";
$_SESSION["CB_RT_URL"] = "/";

if($_GET["join_type"] == "kakao"){
    $snsNAme = "카카오";
}else if($_GET["join_type"] == "naver"){
    $snsNAme = "네이버";
}

?>
		<!-- Container -->
		<div class="container sub" id="container">

			<!-- pageTitle -->
			<div class="pageTitle inner only mb60">아이디 찾기 완료</div>
			<!-- //pageTitle -->

			<!-- memberWrap -->
			<div class="memberWrap">

				<div class="shadowDiv">
					<div class="memberBox sns">
						<div class="title">
							<div class="text"><?=$_GET["name"]?> 회원님은 카카오 로그인을 통해 가입하셨습니다.<br />카카오 로그인을 이용해주세요.</div>
						</div>
						<div class="btnDouble">
							<?php if ($_GET["join_type"] == "kakao"): ?>
                                <a href="<?=$kakao_login_url?>" class="btnType1 kakao">카카오 로그인하기</a>
							<?php elseif ($_GET["join_type"] == "naver"): ?>
                                <a href="<?=$naver_login_url?>" class="btnType1 naver">네이버로 로그인하기</a>
							<?php endif; ?>
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



