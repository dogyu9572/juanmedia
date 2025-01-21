<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/include/headHtml.php'; 
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory = getCategoryAll();	// 전체카테고리

$scale = 6;
$order_by = " sort_num ASC";	## 상품에 정의된 순서
$subQuery = "";
$arrGoodList = getGoodListBaseNFileFromCat(4, $order_by, "name", mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sk']), $scale, $_POST['offset'],"Y", "SMN", $subQuery);

for($i=0;$i<$arrGoodList['list']["total"];$i++){
	################################ 연자 ################################ ST
	$arrExtCat[$i] = getGoodExtCat($arrGoodList["list"][$i]["idx"]);
	for($j=0;$j<$arrExtCat[$i]["total"];$j++){
		$arrExtCatCode = explode("/", $arrExtCat[$i]["list"][$j]["cat_code"]);
		if(in_array("18",$arrExtCatCode)){
			$arrGoodList["list"][$i]["yunja"] = $arrAllCategory[$arrExtCatCode[2]];
		}		
	}
	################################ 연자 ################################ ED
	################################ 찜목록 ################################ ST
	//	$arrWishList[$i] = getWishListGood($arrGoodList["list"][$i]["idx"], $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]);
	################################ 찜목록 ################################ ED	
}

//DB해제
SetDisConn($dblink);
?>
<?
					for($i=0;$i<$arrGoodList['list']["total"];$i++){
						if($arrGoodList["list"][$i]["p_image"]){
							$goodImageUrl[$i] = "/uploaded/shop_good/".$arrGoodList["list"][$i]["idx"]."/".$arrGoodList["list"][$i]["p_image"];
						}else{
							$goodImageUrl[$i] = "/GATE_C/pub/images/best_product_img02.png";
						}

						if($arrGoodList['list'][$i]['special_show']=="N"){	## 영상구매
							if($arrGoodList['list'][$i]['s_time_radio']=="평생"){
								$arrGoodList['list'][$i]['s_time_txt'] = "제한없음";
							}else{
								$arrGoodList['list'][$i]['s_time_txt'] = $arrGoodList['list'][$i]['s_time_radio'];
							}
						}else{	## 참가신청
							$arrGoodList['list'][$i]['s_time_txt'] = $arrGoodList['list'][$i]['s_cal01'];
							if($arrGoodList['list'][$i]['s_cal02']){ $arrGoodList['list'][$i]['s_time_txt'] .= " / ".$arrGoodList['list'][$i]['s_cal02']; }
							if($arrGoodList['list'][$i]['s_cal03']){ $arrGoodList['list'][$i]['s_time_txt'] .= " / ".$arrGoodList['list'][$i]['s_cal03']; }
							if($arrGoodList['list'][$i]['s_cal04']){ $arrGoodList['list'][$i]['s_time_txt'] .= " / ".$arrGoodList['list'][$i]['s_cal04']; }
							if($arrGoodList['list'][$i]['s_cal05']){ $arrGoodList['list'][$i]['s_time_txt'] .= " / ".$arrGoodList['list'][$i]['s_cal05']; }
						}
					?>
					<li class="info_con" onclick="location.href='/GATE_A/semina/semina_view.php?idx=<?=$arrGoodList["list"][$i]["idx"]?>'" style="cursor: pointer">
						<i><img src="<?=$goodImageUrl[$i]?>" alt="image"></i>
						<span class="ing"><?=$SMNSTAT[$arrGoodList["list"][$i]["brand_show"]]?></span>
						<div class="txt_con">
							<P class="tlt"><?=$arrGoodList["list"][$i]["g_name"]?></p>
							<ul class="list">
								<li>
									<div class="th">연자</div>
									<div class="td"><?=$arrGoodList["list"][$i]["yunja"]?></div>
								</li>
								<li>
									<div class="th">일정</div>
									<div class="td"><?=$arrGoodList["list"][$i]["s_time_txt"]?></div>
								</li>
								<li>
									<div class="dpf">
										<div class="th">장소</div>
										<div class="td"><?=$arrGoodList["list"][$i]["vendor"]?></div>
									</div>
									<!--<label class="heart"><input type="checkbox"><i></i></label>-->
								</li>
							</ul>
						</div>
					</li>
					<?
					}
					?>