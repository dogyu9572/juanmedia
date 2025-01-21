<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/include/headHtml.php'; 
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory = getCategoryAll();	// 전체카테고리

################################ 상품 리스트 ( GATE C ) ST
$scale = 8;
$order_by = " D.orderNum ASC";	## 분류에 정의된 순서	
//	$order_by = " sort_num ASC";	## 상품에 정의된 순서	

if($_POST['type']=="C"){
	$cat_no = "2";
}else{
	$cat_no = "3";
}

$arrGoodList = getGoodListBaseNFileFromCat($cat_no, $order_by, "allsearch", mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sk']), $scale, $_POST['offset'], "Y");
################################ 상품 리스트 ( GATE C ) ED

for($i=0;$i<$arrGoodList['list']["total"];$i++){
	################################ 브랜드 ################################ ST
	$arrExtCat[$i] = getGoodExtCat($arrGoodList["list"][$i]["idx"]);
	for($j=0;$j<$arrExtCat[$i]["total"];$j++){
		$arrExtCatCode = explode("/", $arrExtCat[$i]["list"][$j]["cat_code"]);
		if(in_array("6",$arrExtCatCode)){
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
						$goodImageUrl[$i] = "/pub/images/default/noimg_good_".$_POST['type'].".jpg";
					}
					if(!file_exists($_SERVER['DOCUMENT_ROOT'].$goodImageUrl[$i])){
						$goodImageUrl[$i] = "/pub/images/default/noimg_good_".$_POST['type'].".jpg";
					}
					if($arrGoodList["list"][$i]["special_show"]=="Y"){
						$stickerSMN[$i] = '<i class="semina">세미나</i>';
					}
					if($arrGoodList["list"][$i]["best_show"]=="Y"){
						$stickerBST[$i] = '<span class="tag"><i></i>BEST</span>';
					}
					############################################## 금액 ############################################## ST
					$percentageGood[$i] = $arrGoodList['list'][$i]['sale_price'];
						
					if($arrGoodList["list"][$i]["price_type"]=="USD"){
						$viewWon[$i] = "＄";
						$WON = (double)str_replace(",","",$_SITE["USDWON"]);
					}else if($arrGoodList["list"][$i]["price_type"]=="EUR"){
						$viewWon[$i] = "€";
						$WON = (double)str_replace(",","",$_SITE["EURWON"]);
					}else if($arrGoodList["list"][$i]["price_type"]=="CHF"){
						$viewWon[$i] = "Ｆ";
						$WON = (double)str_replace(",","",$_SITE["CHFWON"]);
					}else if($arrGoodList["list"][$i]["price_type"]=="JPY"){
						$viewWon[$i] = "￥";
						$WON = (double)str_replace(",","",$_SITE["JPYWON"]);
					}else if($arrGoodList["list"][$i]["price_type"]=="KRW"){
						$viewWon[$i] = "￦";
						$WON = 1;
					}else if($arrGoodList["list"][$i]["price_type"]=="ETC"){
						$viewWon[$i] = "";
						$WON = 0;
						$percentageGood[$i] = 0;
					}		
					
					if($arrGoodList["list"][$i]["price_type"]=="KRW"){																
						$p_price[$i] = number_format($arrGoodList["list"][$i]["vip_price"]);
						$s_price[$i] = $arrGoodList["list"][$i]["vip_price"] * $arrGoodList['list'][$i]['sale_price']/100;
						$w_price[$i] = number_format(floor(($arrGoodList["list"][$i]["vip_price"]-$s_price[$i])/100)*100);	## 원화는 100미만 절삭
					}else if($arrGoodList["list"][$i]["price_type"]=="ETC"){								
						$w_price[$i] = $arrGoodList["list"][$i]["price_txt"];
					}else{
						$p_price[$i] = number_format($arrGoodList["list"][$i]["vip_price"],2);
						$s_price[$i] = $arrGoodList["list"][$i]["vip_price"] * $arrGoodList['list'][$i]['sale_price']/100;
						$w_price[$i] = number_format(round(($arrGoodList["list"][$i]["vip_price"]-$s_price[$i])*100)/100,2);	## 반올림 소수점 2자리
					}
					############################################## 금액 ############################################## ED
					$listNum[$i] = sprintf('%02d', ((int)$_GET['offset']+$i+1)) ;
				?>
					<li>
						<a href="/GATE_<?=$_POST['type']?>/best/view.php?idx=<?=$arrGoodList["list"][$i]["idx"]?>">
							<span class="img"><img src="<?=$goodImageUrl[$i]?>" alt="image"> <?=$stickerSMN[$i]?></span>
							<span class="txt">
								<span class="name"><?=$strExtCat[$i]?></span>
								<p><?=$arrGoodList["list"][$i]["g_name"]?></p>
								<span class="pay">
								<?if($percentageGood[$i] > 0){?>
									<del><?=$viewWon[$i]?> <?=$p_price[$i]?></del>
									<span class="sale"><?=$percentageGood[$i]."%"?></span>
								<?}?>
									<strong><?=$viewWon[$i]?> <?=$w_price[$i]?></strong>
								</span>
								<?=$stickerBST[$i]?>
							</span>
						</a>
						<div class="btns">
							<label class="heart"><input type="checkbox" onclick="fnAddWish('<?=$arrGoodList["list"][$i]["idx"]?>', this, '')" value="Y" <?=$arrWishList[$i]['total']>0?"checked":""?>><i></i>좋아요</label>
							<?if($arrGoodList["list"][$i]["price_type"]=="ETC"){?>
							<a href="javascript:void(0);" onclick="alert('구매를 원하실 경우 카카오 상담톡으로 문의 부탁드립니다.')" class="cart">장바구니에 담기</a>	
							<?}else{?>
							<a href="/GATE_C/event_product/pop_cartoption.php?idx=<?=$arrGoodList["list"][$i]["idx"]?>" class="cart fancybox fancybox.ajax">장바구니에 담기</a>	
							<?}?>									
						</div>
					</li>
				<?}?>	