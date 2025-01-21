<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/include/headHtml.php'; 
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

########################## /GATE_C/event_product/list.php  44Line 부터 소스 동일

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory = getCategoryAll();	// 전체카테고리

$arrTopCate = getCategoryList(11, "Y");
################################ 상품 리스트 ST
$scale = 20;
$order_by = " D.orderNum ASC";	## 분류에 정의된 순서	
//	$order_by = " sort_num ASC";	## 상품에 정의된 순서	
$subQuery = " AND ( ( A.soon_yn='Y' AND A.published_date < '".date("Y-m-d H:i:s")."') OR A.soon_yn='N' )";	## 커밍순 제외
$arrGoodList = getGoodListBaseNFileFromCat("11", $order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sk']), 
	$scale, $_POST['offset'],"Y", "Y", $subQuery);
################################ 상품 리스트 ED

for($i=0;$i<$arrGoodList['list']["total"];$i++){
	################################ 브랜드 ################################ ST
	$arrExtCat[$i] = getGoodExtCat($arrGoodList["list"][$i]["idx"]);
	for($j=0;$j<$arrExtCat[$i]["total"];$j++){
		$arrExtCatCode = explode("/", $arrExtCat[$i]["list"][$j]["cat_code"]);
		if(in_array("11",$arrExtCatCode)){
			$strExtCat[$i] = $arrAllCategory[$arrExtCatCode[2]];
		}		
	}
	################################ 브랜드 ################################ ED
	################################ 찜목록 ################################ ST
	$arrWishList[$i] = getWishListGood($arrGoodList["list"][$i]["idx"], $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]);
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
		?>
			<li>
				<a href="../best/view.php?idx=<?=$arrGoodList["list"][$i]["idx"]?>">
					<span class="img"><img src="<?=$goodImageUrl[$i]?>" alt="image">
						<span class="ev">
							<strong style="font-size:44px;"><?=$strExtCat[$i]?></strong>
							<p><?=text_cut($arrGoodList['list'][$i]['g_name'],30)?></p>
						</span>
					</span>
					<span class="txt">
						<p><?=$arrGoodList['list'][$i]['g_name']?></p>
					</span>
				</a>
			</li>
		<?
		}
		?>