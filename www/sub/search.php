<?php include_once ('../_head.php'); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
	$arrMenuList =  getCategoryNo1_id("menu");

	$arrSubMenu = array();
	$arrTotalSearchDataList = array();
	
	$total = 0;
	$arrMenuTotal = array();

	for($i=0;$i<$arrMenuList["total"];$i++){
		$arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]] = 0;
		$arrSubMenuList = getCategoryList_id('menu',$arrMenuList["list"][$i]["cat_no"],"Y"); // 메뉴리스트를 불러옴
		// 소메뉴를 불러옴
		for($j=0;$j<$arrSubMenuList["total"];$j++){
			$arrRowList = getCategoryList_id('menu',mysqli_real_escape_string($dblink, $arrSubMenuList["list"][$j]["cat_no"]),"Y");
			if($arrRowList["total"] > 0){
				for($k=0;$k<$arrRowList["total"];$k++){
					$arrSubMenu[$arrMenuList["list"][$i]["cat_no"]][$arrRowList["list"][$k]["cat_no"]] = $arrSubMenuList["list"][$j]["cat_name"]." > ".$arrRowList["list"][$k]["cat_name"];
				}
			}else{
				$arrSubMenu[$arrMenuList["list"][$i]["cat_no"]][$arrSubMenuList["list"][$j]["cat_no"]] = $arrSubMenuList["list"][$j]["cat_name"];
			}
		}
		// 소메뉴를 불러옴 / 끝
		foreach($arrSubMenu[$arrMenuList["list"][$i]["cat_no"]] as $key => $val){
			$arrTotalSearchList = getBoardListBaseNFile("totalsearch", $arrMenuList["list"][$i]["cat_no"], "e", $key, 0, 0,'user'); // 통합검색에 저장된 내용을 전부 불러옴
			for($j=0;$j<$arrTotalSearchList["total"];$j++){
				if($arrTotalSearchList["list"][$j]["etc_2"] == "1"){ // HTML
					$arrList = getTotalSearchListBaseNFile("totalsearch", $arrMenuList["list"][$i]["cat_no"], $key, "a", mysqli_real_escape_string($dblink, $_GET["sk"]), 0, 0,'user'); // html에서 저장한 제목, 내용을 불러옴
					$arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]] += $arrList["total"];
					$total += $arrList["total"];
				}else if($arrTotalSearchList["list"][$j]["etc_2"] == "2"){ // 게시판
					$arrList = getBoardListBaseNFile($arrTotalSearchList["list"][$j]["etc_3"], "", "a", mysqli_real_escape_string($dblink, $_GET["sk"]), 0, 0,'user'); // 조건에 맞는 게시판내용을를 불러옴
					$arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]] += $arrList["total"];
					$total += $arrList["total"];
				}else if($arrTotalSearchList["list"][$j]["etc_2"] == "3"){ // 카테고리 / 연구실만 포함
					$arrList = getTotalSearchCategoryList_id($arrTotalSearchList["list"][$j]["etc_4"],"","Y","s",$_GET["sk"]);
					$arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]] += $arrList["total"];
					$total += $arrList["total"];
				}
				for($k=0;$k<$arrList["total"];$k++){
					$arrList["list"][$k]["type"] = $arrTotalSearchList["list"][$j]["etc_2"];
					$arrList["list"][$k]["page"] = $arrTotalSearchList["list"][$j]["homepage"];
					$arrTotalSearchDataList[$arrMenuList["list"][$i]["cat_no"]][$key][] = $arrList["list"][$k];
				}
			}
		}

	}

SetDisConn($dblink);
?>
	<div class="search-top">
		<h2>통합검색</h2>
		<form action="<?=$_SERVER["PHP_SELF"]?>">
			<div class="search-primary">
				<input type="text" id="sk" name="sk" placeholder="검색어를 입력해 주세요" value="<?=$_GET["sk"]?>">
				<button type="submit" title="검색"></button>
			</div>
		</form>
	</div>
	<div class="contents inner">
		<div class="section">
			<p class="result-total"><span class="c-primaryDark">“<?=$_GET["sk"]?>”</span>에 대한 검색결과가 <span class="c-primaryDark">총 <?=number_format($total)?>건</span> 있습니다.</p>
			<div class="tab-wrap">
				<div class="tab">
					<button type="button" class="btn active">통합검색(<?=number_format($total)?>)</button>
					<?php for($i=0;$i<$arrMenuList["total"];$i++){ ?>
						<button type="button" class="btn"><?=$arrMenuList["list"][$i]["cat_name"]?>(<?=number_format($arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]])?>)</button>
					<?php } ?>
					<!-- <button type="button" class="btn">진료안내(20)</button>
					<button type="button" class="btn">진료과(0)</button>
					<button type="button" class="btn">이용안내(20)</button>
					<button type="button" class="btn">고객서비스(40)</button>
					<button type="button" class="btn">병원소개(20)</button> -->
				</div>
				<div class="tab-container">
					<div class="tab-content">
						<!-- s: 검색결과 -->
						<div class="result-wrap">
							<?php 
							for($i=0;$i<$arrMenuList["total"];$i++){
							?>
							<div class="result">
								<button type="button" class="toggle">
									<p class="sub-bullet stitle2"><?=$arrMenuList["list"][$i]["cat_name"]?>(<?=number_format($arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]])?>)</p>
								</button>
								<div class="result-content">
									<?php 
									if($arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]] > 0){
										$count = 0;
										foreach($arrTotalSearchDataList[$arrMenuList["list"][$i]["cat_no"]] as $key => $arrList){
											for($j=0;$j<count($arrList) && $count<3;$j++,$count++){
												if($arrList[$j]["type"] == "2"){// 보드
													$page = $arrList[$j]["page"]."?boardid=".$arrList[$j]["boardid"]."&mode=view&idx=".$arrList[$j]["idx"];
												}else{
													$page = $arrList[$j]["page"];
												}
									?>
												<a href="<?=$page?>" class="box">
													<p class="body1"><?=$arrList[$j]["subject"]?></p>
													<p class="t-secondary">
														<?=strip_tags($arrList[$j]["contents"])?>
													</p>
													<p class="c-secondary"><?=$arrSubMenu[$arrMenuList["list"][$i]["cat_no"]][$key]?></p>
												</a>
									<?php
											}
										}
									}else{
									?>
									<p class="no-result">검색결과가 없습니다.</p>
									<?php } ?>
									<!-- case 1: 검색결과 있음 -->
									<!-- <a href="#;" class="box">
										<p class="body1">간호간병통합서비스병동 입원생활 안내</p>
										<p class="t-secondary">대법관은 대법원장의 제청으로 국회의 동의를 얻어 대통령이 임명한다. 일반사면을 명하려면 국회의 동의를 얻어야 한다. 대통령은 국무총리·국무위원·행정각부의 장 기타 법률이 정하는 공사의 직을 겸할 수 없다. 제안된 헌법개정안은 대통령이 20일 이상의 기간 이를 공고하여야 한다. 누구든지 체포 또는 구속을 당한 때에는 적부의 심사를 법원에 청구할 권리를 가진다.
										모든 국민은 직업선택의 자유를 가진다. 모든 국민은 법 앞에 평등하다. 누구든지 성별·종교 또는 사회적 신분에 의하여 정치적·경제적·사회적·문화적 생활의 모든 영역에 있어서 차별을 받지 아니한다.</p>
										<p class="c-secondary">진료안내 > 간호간병통합서비스 안내</p>
									</a>
									<a href="#;" class="box">
										<p class="body1">간호간병통합서비스병동 입원생활 안내</p>
										<p class="t-secondary">대법관은 대법원장의 제청으로 국회의 동의를 얻어 대통령이 임명한다. 일반사면을 명하려면 국회의 동의를 얻어야 한다. 대통령은 국무총리·국무위원·행정각부의 장 기타 법률이 정하는 공사의 직을 겸할 수 없다. 제안된 헌법개정안은 대통령이 20일 이상의 기간 이를 공고하여야 한다. 누구든지 체포 또는 구속을 당한 때에는 적부의 심사를 법원에 청구할 권리를 가진다.
										모든 국민은 직업선택의 자유를 가진다. 모든 국민은 법 앞에 평등하다. 누구든지 성별·종교 또는 사회적 신분에 의하여 정치적·경제적·사회적·문화적 생활의 모든 영역에 있어서 차별을 받지 아니한다.</p>
										<p class="c-secondary">진료안내 > 간호간병통합서비스 안내</p>
									</a> -->
								</div>
							</div>
							<?php
							}			
							?>
						</div>
					</div>
						<!-- e: 검색결과 -->
					<?php 
					for($i=0;$i<$arrMenuList["total"];$i++){
					?>
					<div class="tab-content">
						
						<!-- s: 검색결과 -->
						<div class="result-wrap">
							
							<div class="result">
								<button type="button" class="toggle">
									<p class="sub-bullet stitle2"><?=$arrMenuList["list"][$i]["cat_name"]?>(<?=number_format($arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]])?>)</p>
								</button>
								<div class="result-content">
									<?php 
									if($arrMenuTotal[$arrMenuList["list"][$i]["cat_no"]] > 0){
										$count = 0;
										foreach($arrTotalSearchDataList[$arrMenuList["list"][$i]["cat_no"]] as $key => $arrList){
											for($j=0;$j<count($arrList) && $count<3;$j++,$count++){
												if($arrList[$j]["type"] == "2"){// 보드
													$page = $arrList[$j]["page"]."?boardid=".$arrList[$j]["boardid"]."&mode=view&idx=".$arrList[$j]["idx"];
												}else{
													$page = $arrList[$j]["page"];
												}
									?>
												<a href="<?=$page?>" class="box">
													<p class="body1"><?=$arrList[$j]["subject"]?></p>
													<p class="t-secondary">
														<?=strip_tags($arrList[$j]["contents"])?>
													</p>
													<p class="c-secondary"><?=$arrSubMenu[$arrMenuList["list"][$i]["cat_no"]][$key]?></p>
												</a>
									<?php
											}
										}
									}else{
									?>
									<p class="no-result">검색결과가 없습니다.</p>
									<?php } ?>
									<!-- case 1: 검색결과 있음 -->
									<!-- <a href="#;" class="box">
										<p class="body1">간호간병통합서비스병동 입원생활 안내</p>
										<p class="t-secondary">대법관은 대법원장의 제청으로 국회의 동의를 얻어 대통령이 임명한다. 일반사면을 명하려면 국회의 동의를 얻어야 한다. 대통령은 국무총리·국무위원·행정각부의 장 기타 법률이 정하는 공사의 직을 겸할 수 없다. 제안된 헌법개정안은 대통령이 20일 이상의 기간 이를 공고하여야 한다. 누구든지 체포 또는 구속을 당한 때에는 적부의 심사를 법원에 청구할 권리를 가진다.
										모든 국민은 직업선택의 자유를 가진다. 모든 국민은 법 앞에 평등하다. 누구든지 성별·종교 또는 사회적 신분에 의하여 정치적·경제적·사회적·문화적 생활의 모든 영역에 있어서 차별을 받지 아니한다.</p>
										<p class="c-secondary">진료안내 > 간호간병통합서비스 안내</p>
									</a>
									<a href="#;" class="box">
										<p class="body1">간호간병통합서비스병동 입원생활 안내</p>
										<p class="t-secondary">대법관은 대법원장의 제청으로 국회의 동의를 얻어 대통령이 임명한다. 일반사면을 명하려면 국회의 동의를 얻어야 한다. 대통령은 국무총리·국무위원·행정각부의 장 기타 법률이 정하는 공사의 직을 겸할 수 없다. 제안된 헌법개정안은 대통령이 20일 이상의 기간 이를 공고하여야 한다. 누구든지 체포 또는 구속을 당한 때에는 적부의 심사를 법원에 청구할 권리를 가진다.
										모든 국민은 직업선택의 자유를 가진다. 모든 국민은 법 앞에 평등하다. 누구든지 성별·종교 또는 사회적 신분에 의하여 정치적·경제적·사회적·문화적 생활의 모든 영역에 있어서 차별을 받지 아니한다.</p>
										<p class="c-secondary">진료안내 > 간호간병통합서비스 안내</p>
									</a> -->
								</div>
							</div>
						</div>
					</div>
					<!-- e: 검색결과 -->
					<?php
					}			
					?>
				</div>
			</div>
		</div>
	</div>
	<script>
	$(document).ready(function () {
		// tab
		$(".tab-wrap .tab .btn").click(function () {
			var index = $(this).index();
			$(".tab-wrap .tab .btn").removeClass("active");
			$(this).addClass("active");
			$(".tab-container .tab-content").hide();
			$(".tab-container .tab-content").eq(index).show();
		});

		// toggle
		$(".result-wrap .result .toggle").each(function (index) {
			$(this).on("click", function () {
				$(".result-wrap .result .result-content").eq(index).stop().slideToggle(200);
				$(".result-wrap .result").eq(index).toggleClass("close");
			});
  	});
	});
	</script>
<?php include_once ('../_tail.php'); ?>