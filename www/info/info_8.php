<?php include_once ('../_head.php'); ?>
	<div class="sub-top sub1">
		<div class="visual">
			<h2>비급여수가안내</h2>
		</div>
		<div class="breadcrumb">
			<a href="/" class="home"><span class="blind">홈으로</span></a>
			<div class="loca">
				<button type="button">진료안내</button>
				<ul>
					<li class="active"><a href="/info/info_11.php">진료안내</a></li>
					<li><a href="/depart/depart_1.php">진료과</a></li>
					<li><a href="/use/use_1.php">이용안내</a></li>
					<li><a href="/sercive/sercive_1.php">고객서비스</a></li>
					<li><a href="/about/about_1.php">병원소개</a></li>
				</ul>
			</div>
			<div class="loca sec">
				<button type="button">비급여수가안내</button>
				<ul>
					<li><a href="/info/info_11.php">외래진료안내</a></li>
					<li><a href="/info/info_21.php">입원진료안내</a></li>
					<li><a href="/info/info_3.php">응급진료 안내</a></li>
					<li><a href="/info/info_4.php">진료비 하이패스</a></li>
					<li><a href="/info/info_51.php">가정간호안내</a></li>
					<li><a href="/info/info_6.php">간호간병통합서비스 안내</a></li>
					<li><a href="/info/info_7.php">대리처방안내</a></li>
					<li class="active"><a href="/info/info_8.php">비급여수가안내</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="contents inner">
		<div class="tit-box">
			<h3 class="tit-bullet heading1">비급여 수가 안내</h3>
			<p class="sub">의료법 제45조 및 의료법 시행규칙 제42조의 2에 의하여 비급여 진료비용을 고지합니다. 비용은 수가가 변경되는 경우 달라질 수 있습니다.
				<br>기준일 : 24.09.30
			</p>
		</div>
		<div class="tab-wrap">
			<div class="tab">
				<button type="button" class="btn active">행위</button>
				<button type="button" class="btn">치료재료</button>
				<button type="button" class="btn">약제</button>
				<button type="button" class="btn">제증명수수료</button>
			</div>
			<div class="tab-container">
				<div class="tab-content">1</div>
				<div class="tab-content">2</div>
				<div class="tab-content">3</div>
				<div class="tab-content">3</div>
			</div>
		</div>
	</div>
	<script>
	$(document).ready(function () {
		$(".tab-wrap .tab .btn").click(function () {
			var index = $(this).index();
			$(".tab-wrap .tab .btn").removeClass("active");
			$(this).addClass("active");
			$(".tab-wrap .tab-container .tab-content").hide();
			$(".tab-wrap .tab-container .tab-content").eq(index).show();
		});
	});
	</script>
<?php include_once ('../_tail.php'); ?>