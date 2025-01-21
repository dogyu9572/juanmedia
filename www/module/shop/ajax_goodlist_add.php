<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

$cat_no = $_GET['cat_no']??"2";	## 기본카테고리 SHOP

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

################################ 상품 리스트 ST
if(!$_GET['scale']){ $_GET['scale'] = 20; }
$scale = $_GET['scale'];
if($scale==20){
	$scaleTxt = "20개씩 보기";
}else if($scale==40){
	$scaleTxt = "40개씩 보기";
}else if($scale==60){
	$scaleTxt = "60개씩 보기";
}
#################################### 검색 ST (순서정의)
$moSearchTxt = "";
if(!$_GET['odby']){ 	
	$_GET['odby'] = "nw"; 
}
if($_GET['odby']=="hg"){			// 인기상품
	$order_by = " A.hit DESC, A.idx DESC";	
	$moSearchTxt = "인기순";
	$background['engName']	= "Best";
}else if($_GET['odby']=="nw"){		// 상품명순
	$order_by = " A.idx DESC";			
	$moSearchTxt = "최신순";
	$background['engName']	= "New";
}else if($_GET['odby']=="sp"){		// 낮은 가격순
	$order_by = " A.stock_type ASC, stock_num DESC, A.price ASC, A.idx DESC";	
	$moSearchTxt = "낮은 가격순";	
	//	$subQuery .= " AND A.stock_type = '1' AND A.stock > 0 ";
}else if($_GET['odby']=="mp"){		// 높은 가격순
	$order_by = " A.stock_type ASC, stock_num DESC, A.price DESC, A.idx DESC";		
	$moSearchTxt = "높은 가격순";	
	//	$subQuery .= " AND A.stock_type = '1' AND A.stock > 0 ";
}else if($_GET['odby']=="sl"){		// 할인상품 보기
	$order_by = " A.stock_type ASC, stock_num DESC,A.idx DESC";
	$moSearchTxt = "할인상품 보기";
	$subQuery .= " AND A.sale_price > 0 ";
	//	$subQuery .= " AND A.stock_type = '1' AND A.stock > 0 ";
}
//echo $order_by;
#################################### 검색(카테고리)
$comma = "";
if(count($_GET['ssc'])>0){
	for($i=0;$i<count($_GET['ssc']);$i++){	
		$subCatno .= $comma.$_GET['ssc'][$i];
		$comma = ",";		
	}	
	$subQuery .= " AND D.cat_no in (".$subCatno.") ";
}
#################################### 검색(할인율)
$comma = "";
if(count($_GET['sale'])>0){	
	for($i=0;$i<count($_GET['sale']);$i++){	
		if($_GET['sale'][$i]=="30"){
			$subQuery2_1 = $comma." A.sale_price<=30 ";
		}else if($_GET['sale'][$i]=="50"){
			$subQuery2_2 = $comma." (A.sale_price>=30 AND A.sale_price<=50) ";
		}else if($_GET['sale'][$i]=="70"){
			$subQuery2_3 = $comma." (A.sale_price>=50 AND A.sale_price<=70) ";
		}else if($_GET['sale'][$i]=="100"){
			$subQuery2_4 = $comma." A.sale_price>=70 ";
		}	
		$comma = " OR ";
	}	
	$subQuery .= " AND (".$subQuery2_1.$subQuery2_2.$subQuery2_3.$subQuery2_4.")";
	//	echo $subQuery;
}
#################################### 검색(브랜드)
if($_GET['brand']){
	$subQuery .= " AND A.brand = '".$_GET['brand']."' ";
}
#################################### 검색(가격)
if($_GET['smpay']){
	$subQuery .= " AND A.price >= ".$_GET['smpay']." ";
}
if($_GET['mxpay']){
	$subQuery .= " AND A.price <= ".$_GET['mxpay']." ";
}
$subQuery .= " AND A.stock_type in (1,2) ";		## 상품관리 1: 판매중/ 2:판매중단 / 3: 숨김
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ACLASS"]){	## 등급별 상품 표시 중요 ##
	$subQuery .= " AND CONCAT('|',A.member_choice,'|') like '%|".$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ACLASS"]."|%'";
}
#################################### 검색 ED
$arrGoodList = getGoodListBaseNFileFromCat(
	mysqli_real_escape_string($GLOBALS['dblink'], $_GET['cat_no']), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_GET['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_GET['sk']), 
	$scale, $_GET['offset'], "Y", "", $subQuery);
################################ 상품 리스트 ED

for($i=0;$i<$arrGoodList['list']["total"];$i++){
	################################ 브랜드 ################################ ST
	$arrExtCat[$i] = getGoodExtCat($arrGoodList["list"][$i]["idx"]);
	for($j=0;$j<$arrExtCat[$i]["total"];$j++){
		$arrExtCatCode = explode("/", $arrExtCat[$i]["list"][$j]["cat_code"]);
		if(in_array($initBland, $arrExtCatCode)){
			$strExtCat[$i] = $arrAllCategory[$arrExtCatCode[2]];
		}		
	}
	################################ 브랜드 ################################ ED
	################################ 찜목록 ################################ ST
	$arrWishList[$i] = getWishListGood($arrGoodList["list"][$i]["idx"], $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]);
	################################ 찜목록 ################################ ED
}

################################ 검색메뉴
if($_GET['cat_no']){
	$arrCateList	= getCategoryList($cat_no, "Y");		## 검색 카테고리
}

//DB해제
//SetDisConn($dblink);
?>
<script type="text/javascript">
<!--
//fancybox
	$(".fancybox").fancybox();	
//-->
</script>
		<?
		for($i=0;$i<$arrGoodList['list']["total"];$i++){
			if($arrGoodList["list"][$i]["p_image"]){
				$goodImageUrl[$i] = "/uploaded/shop_good/".$arrGoodList["list"][$i]["idx"]."/".$arrGoodList["list"][$i]["p_image"];
			}else{
				$goodImageUrl[$i] = "/pub/images/no_image.svg";
			}
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].$goodImageUrl[$i])){
				$goodImageUrl[$i] = "/pub/images/no_image.svg";
			}
			if($arrGoodList["list"][$i]['stock']<1){			## 재고 수량이 0이면
				$arrGoodList["list"][$i]['stock_type'] = "2";	## 품절처리
			}
			if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ACLASS"]){	## 금액 차등표시
				$arrChoice	= explode("|",$arrGoodList["list"][$i]['member_choice']);
				$arrPrice	= explode("|",$arrGoodList["list"][$i]['member_price']);
				$arrSale	= explode("|",$arrGoodList["list"][$i]['member_sale']);	
				for($j=0;$j<count($arrChoice);$j++){
					if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ACLASS"]==$arrChoice[$j]){						
						$arrGoodList["list"][$i]["show_price"]		= "<strong>".number_format($arrPrice[$j]*1.1)."</strong>";
						$arrGoodList["list"][$i]["show_sale"]		= "<span class=\"dis\">".$arrSale[$j]."%</span>";
						$arrGoodList["list"][$i]["show_won_price"]	= "<del>".number_format($arrGoodList["list"][$i]["p_price"]*1.1)."</del>";
						if($arrSale[$j]<1){
							$arrGoodList["list"][$i]["show_sale"]		= "";
							$arrGoodList["list"][$i]["show_won_price"]	= "";						
						}
					}
				}
				if(!$arrGoodList["list"][$i]["show_won_price"]){
				//	$arrGoodList["list"][$i]["show_won_price"]	= "<strong>".number_format($arrGoodList["list"][$i]["p_price"])."</strong>";
				}
			}else{
				$arrGoodList["list"][$i]["show_won_price"]	= "<strong>회원사 공개</strong><span class=\"dis\">로그인 필요</span>";
			}
			if($arrGoodList["list"][$i]["special_show"]=="Y"){
				$arrGoodList["list"][$i]["special_show_txt"] = "<span class=\"n\">N</span>";			
			}
			if($arrGoodList["list"][$i]["best_show"]=="Y"){
				$arrGoodList["list"][$i]["best_show_txt"] = "<span class=\"b\">B</span>";			
			}
		?>	
			<div class="mv">
				<a href="/product/product_view.php?idx=<?=$arrGoodList["list"][$i]["idx"]?>" class="imgfit"><img src="<?=$goodImageUrl[$i]?>" alt="image">
					<div class="state">
						<?=$arrGoodList["list"][$i]["special_show_txt"]?>
						<?=$arrGoodList["list"][$i]["best_show_txt"]?>
					</div>
				</a>
				<span class="txt">
					<span class="name"><?=$arrGoodList["list"][$i]["g_name"]?></span>
					<span class="box"><?=$arrGoodList["list"][$i]["etc_2"]?></span>
					<span class="money">
						<?=$arrGoodList["list"][$i]["show_price"]?>
						<?=$arrGoodList["list"][$i]["show_won_price"]?>
						<?=$arrGoodList["list"][$i]["show_sale"]?>
					</span>
					<label class="heart"><input type="checkbox" onclick="fnAddWish('<?=$arrGoodList["list"][$i]["idx"]?>', this, '')" value="Y" <?=$arrWishList[$i]['total']>0?"checked":""?>><i></i></label>
					<?if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]){?>
					<a href="/product/pop_cart.php?idx=<?=$arrGoodList["list"][$i]["idx"]?>" class="cart fancybox fancybox.iframe"><span></span>장바구니</a>
					<?}else{?>
					<a href="/member/login.php?rt_url=<?=str_replace("&","||",$_SERVER['REQUEST_URI'])?>" class="cart"><span></span>장바구니</a>
					<?}?>
				</span>
			</div>
		<?
		}
		?>		