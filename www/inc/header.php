
<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/include/headHtml.php";

?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<title>주안영상미디어센터</title>
	 <meta name="title" content="주안영상미디어센터"/>
    <meta name="Keywords" content="주안영상미디어센터, 공유형 영상미디어센터, 함께하는 미디어센터, 미디어센터, 인천, 주안 , 미디어센터 ,미디어교육, 장비, 공간,영상미디어센터 "/>
    <meta name="description" content="주안미디어센터는 인천시민 누구나 뉴미디어를 활용할 수 있도록 다양한 미디어 제작교육 프로그램을 운영합니다."/>
    <meta name="format-detection" content="telephone=no"/>
    <meta property="og:title" content="주안영상미디어센터"/>
    <meta property="og:type" content="website"/>
    <meta property="og:description" content="주안미디어센터는 인천시민 누구나 뉴미디어를 활용할 수 있도록 다양한 미디어 제작교육 프로그램을 운영합니다."/>
	<meta property="og:image" content="/images/share.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="user-scalable=yes, initial-scale=1.0, maximum-scale=2.0, minimum-scale=1.0, width=device-width">
	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/fonts.css"  media="all" />
	<link rel="stylesheet" type="text/css" href="/css/swiper.min.css"  media="all" />
	<link rel="stylesheet" type="text/css" href="/css/common.css?v8"  media="all" />
	<script type="text/javascript" src="/js/jquery-3.7.1.min.js" ></script>
	<script type="text/javascript" src="/js/swiper.min.js" ></script>
	<script type="text/javascript" src="/js/jquery-ui.min.js" ></script>
	<script type="text/javascript" src="/js/jquery.mCustomScrollbar.min.js" ></script>
	<script type="text/javascript" src="/js/common.js?v5" ></script>
</head>
<body>
	<!-- Skip Nav -->
	<div id="skipnavigation">
		<a href="#container">본문내용 바로가기</a>
	</div>
	<!-- //Skip Nav -->

	<!-- Wrap -->
	<div class="wrap">
		<!-- Head -->
		<div class="head">

			<!-- member -->
			<div class="member">
				<div class="inner">
					<a href="/equ/list.php?boardid=equ_applicants_cart&mode=cart" class="pc_vw">장바구니</a>
				<?php if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] != ""){?>
					<a href="/module/member/logout.php">로그아웃</a>
					<a href="/mypage/orderList.php" class="pc_vw">마이페이지</a> <!-- 임시로 모바일에서는 히든처리 -->
				<?php }else{ ?>
					<a href="/member/login.php">로그인</a>
					<a href="/member/agree.php" class="pc_vw">회원가입</a>
				<?php } ?>
				</div>
			</div>
			<!-- //member -->

			<!-- headCont -->
			<div class="headCont">
				<div class="inner">
					<div class="h1Logo"><a href="/"><img src="/images/ico_logo.svg" alt="주안영상미디어센터"></a></div>

					<?php include("gnb.php"); ?>

					<div class="btnMenu">
						<img src="/images/ico_ham.svg" alt="메뉴">
					</div>
				</div>
			</div>
			<!-- //headCont -->

		</div>
		<!-- //Head -->
 

		<!-- siteMap -->
		<div class="siteMap">
			<div class="mobTop">
				<div class="h1Logo"><a href="/"><img src="/images/ico_logo_w.svg" alt="주안영상미디어센터"></a></div>
				<div class="closeSite">
					<img src="/images/ico_closeSite.svg" alt="닫기">
				</div>
			</div>
			<div class="inner">
				<div class="siteTop">
					<div class="bigTit">SITE MAP</div>
					<div class="closeSite">
						<img src="/images/ico_closeSite.svg" alt="닫기">
					</div>
				</div>

				<?php include("gnb.php"); ?>

			</div>

		</div>
		<!-- //siteMap -->