<?
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_REQUEST['st']=="new"){
	$order_by = " A.idx DESC ";
}else if($_REQUEST['st']=="hit"){
	$order_by = " A.hit DESC ";
}else if($_REQUEST['st']=="name"){
	$order_by = " A.g_name ";
}else if($_REQUEST['st']=="hprice"){
	$order_by = " length(A.price) DESC, A.price DESC ";
}else if($_REQUEST['st']=="lprice"){
	$order_by = " length(A.price) ASC, A.price ASC ";
}else{
	$order_by = " A.sort_num DESC, A.idx DESC ";
}

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();
$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));
$arrCatCode = explode("/", $arrCategoryInfo["list"][0]["cat_code"]);

$arrTopCategory = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $arrCatCode[1]));


if($_GET['scale']) {
	$scale = $_GET['scale'];
} else {
	$scale = "9";
}

//상품 리스트
$arrList = getGoodListBaseNFileFromCat(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"Y");

$arrSpecialList = getGoodListMain(mysqli_real_escape_string($GLOBALS['dblink'], $arrCatCode[1]), 0, 0, "special_show");
if($arrCatCode[0]=="1") {
	$arrBestList = getGoodListMain(mysqli_real_escape_string($GLOBALS['dblink'], $arrCatCode[1]), 0, 0, "best_show");
} else {
	$arrBestList = getGoodListMain(mysqli_real_escape_string($GLOBALS['dblink'], $arrCatCode[0]), 0, 0, "best_show");
}

$arrCategoryList = getCategoryList(122); //sns 카테고리

$arrBannerList1 = getCategoryBannerList(mysqli_real_escape_string($GLOBALS['dblink'], $arrCatCode[1]), "1"); //브랜드 배너관리
$arrBannerList2 = getCategoryBannerList(mysqli_real_escape_string($GLOBALS['dblink'], $arrCatCode[1]), "2"); //레이어관리

$arrBannerList3 = getCategoryBannerList(mysqli_real_escape_string($GLOBALS['dblink'], $arrCatCode[1]), "1"); //브랜드외 배너관리
if($arrBannerList3["list"]["total"]<=0) {
	$arrBannerList3 = getCategoryBannerList(mysqli_real_escape_string($GLOBALS['dblink'], $arrCatCode[0]), "1"); //브랜드외 배너관리
}

SetDisConn($dblink);


if($arrTopCategory["total_catalog_files"]>0){
for($i=0;$i<$arrTopCategory["total_catalog_files"];$i++){
	if(substr($arrTopCategory["catalog_files"][$i]['re_name'],0,2) == "b_") {
		$num2 = $i;
	}
}
}

$chkSns=array();
if($arrTopCategory["total_sns"]>0){
for($i=0; $i<$arrTopCategory["total_sns"]; $i++){	
	array_push($chkSns, $arrTopCategory["sns"][$i]["s_cat_no"]);

	$arraySns[$arrTopCategory["sns"][$i]["s_cat_no"]] = $arrTopCategory["sns"][$i]["sns_url"];
}
}
?>

<script>
function orderGood(txt) {
	var f = document.sfrm;
	f.st.value = txt;
	f.submit();
}
function viewGood(txt) {
	var f = document.sfrm;
	f.scale.value = txt;
	f.submit();
}
</script>

<form name="sfrm" method="get" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="goPage" value="GoodList">
<input type="hidden" name="cat_no" value="<?=$_REQUEST["cat_no"]?>">
<input type="hidden" name="st" value="<?=$_REQUEST["st"]?>">
<input type="hidden" name="scale" value="<?=$_REQUEST["scale"]?>">
</form>

	<div id="sub_container">
		<div class="content">
			<div class="location">
				<p class="local"><span class="home"></span><span class="route"><?=$arrAllCategory[$arrCatCode[0]]?></span><span class="current"><?=$arrAllCategory[$arrCatCode[1]]?></span></p>
			</div>

			<? if($arrCatCode[0]=="1") {?>
			<div class="brandVisual">
				<div class="areaL">
					<div class="label"><img src="/uploaded/category/<?=$arrTopCategory["catalog_files"][$num2]["re_name"]?>" height="57" /></div>
					<dl>
						<dt><?=$arrTopCategory["list"][0]['cat_name']?> 스토리</dt>
						<dd>
							<?=stripslashes(nl2br($arrTopCategory["list"][0]['cat_content']))?>
						</dd>
					</dl>
					<ul class="sns">
						<?
						//DB연결
						$dblink = SetConn($_conf_db["main_db"]);

						if($arrCategoryList["total"]>0){
						for($i=0; $i<$arrCategoryList["total"]; $i++){
							if(in_array($arrCategoryList["list"][$i]['cat_no'], $chkSns)){

							$arrCateInfo = getCategoryInfo($arrCategoryList["list"][$i]['cat_no']);
						?>
						<li<?=$i==0?" class='first'":""?>><a href="<?=$arraySns[$arrCategoryList["list"][$i]['cat_no']]?>" target="_balnk"><img src="/uploaded/category/<?=$arrCateInfo["catalog_files"][0]['re_name']?>" alt="<?=stripslashes($arrCategoryList["list"][$i]['cat_name'])?>" /></a></li>
						<?}
						}}
						SetDisConn($dblink);
						?>
					</ul>
				</div>
				<!-- //areaL -->

				<div class="areaR">
					<ul class="brandVisual_slides">
						<?
						if($arrBannerList1["list"]["total"]>0){
						for($i=0; $i<$arrBannerList1["list"]["total"]; $i++){
						?>
						<li><a href="<?=$arrBannerList1['list'][$i]['c_url']?>" target="<?=$arrBannerList1['list'][$i]['c_target']?>"><img src="/uploaded/categorybanner/<?=$arrBannerList1['list'][$i]['c_image']?>" /></a></li>
						<?}}?>
					</ul>
					<ul class="btn">
						<?
						if($arrBannerList2["list"]["total"]>0){
						for($i=0; $i<$arrBannerList2["list"]["total"]; $i++){
						?>
						<li><a href="<?=$arrBannerList2['list'][$i]['c_url']?>" target="<?=$arrBannerList2['list'][$i]['c_target']?>"><img src="/uploaded/categorybanner/<?=$arrBannerList2['list'][$i]['c_image']?>" /></a></li>
						<?}}?>
					</ul>
				</div>
				<!-- //areaR -->
			</div>
			<!-- //brandVisual -->
			<?} else {?>
			<?	if($arrBannerList3["list"]["total"]>0){?>
			<div class="productVisual">
				<ul class="productVisual_slides">
					<? for($i=0; $i<$arrBannerList3["list"]["total"]; $i++){?>
					<li><a href="<?=$arrBannerList3['list'][$i]['c_url']?>" target="<?=$arrBannerList3['list'][$i]['c_target']?>"><img src="/uploaded/categorybanner/<?=$arrBannerList3['list'][$i]['c_image']?>" width="1207" /></a></li>
					<?}?>
				</ul>	
			</div>
			<?}?>
			<?} ?>

			<div class="productItem">
				<? if($arrCatCode[0]=="1") {?>
				<p class="tit"><img src="/img/brandCon_tit1.jpg" alt="SPECIAL  Pick" /></p>
				<ul class="productItem_slides">
					<?if($arrSpecialList['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrSpecialList['list']['total'];$i++) {
						$arrSpecialCatCode = explode("/", $arrSpecialList["list"][$i]['cat_code']);
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&idx=<?=$arrSpecialList['list'][$i]['idx']?>">
							<div class="pic"><img src="/uploaded/shop_good/<?=$arrSpecialList['list'][$i]['idx']?>/<?=$arrSpecialList['list'][$i]['image_l']?>" width="270" /></div>
							<div class="txt">
								<p class="brand"><?=$arrAllCategory[$arrSpecialCatCode[1]]?></p>
								<p class="name"><?=text_cut(stripslashes($arrSpecialList['list'][$i]['g_name']),42)?></p>
								<p class="price"><span class="sale"><span class="through"><?=number_format($arrSpecialList['list'][$i]['sale_price'])?></span></span>    <?=number_format($arrSpecialList['list'][$i]['price'])?></p>
							</div>
						</a>	
					</li>
					<?} endif;?>
				</ul>
				<?} else {?>
				<p class="tit"><img src="/img/productItem_tit.jpg" alt="product" /></p>
				<ul class="productItem_slides">
					<?if($arrBestList['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrBestList['list']['total'];$i++) {
						$arrBestCatCode = explode("/", $arrBestList["list"][$i]['cat_code']);
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&subcat=<?=$arrCatCode[0]=="1"?$arrCatCode[1]:$arrCatCode[0]?>&idx=<?=$arrBestList['list'][$i]['idx']?>">
							<div class="pic"><img src="/uploaded/shop_good/<?=$arrBestList['list'][$i]['idx']?>/<?=$arrBestList['list'][$i]['image_l']?>" width="270" /></div>
							<div class="txt">
								<p class="brand"><?=$arrAllCategory[$arrBestCatCode[1]]?></p>
								<p class="name"><?=text_cut(stripslashes($arrBestList['list'][$i]['g_name']),42)?></p>
								<p class="price"><span class="sale"><span class="through"><?=number_format($arrBestList['list'][$i]['sale_price'])?></span></span>    <?=number_format($arrBestList['list'][$i]['price'])?></p>
							</div>
						</a>	
					</li>
					<?} endif;?>
				</ul>
				<?} ?>
			</div>

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/include/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="con">
				<!-- 내용 : s -->
					<a name="listAll">
					<div class="searchArea">
						<p class="total">상품 <span><?=number_format($arrList['total'])?></span> 개</p>
						<div class="selectBox">
							<select name="st" onChange="orderGood(this.value);">
								<option value="new"<?=$_REQUEST['st']=="new"?" selected":""?>>신상품</option>
								<option value="lprice"<?=$_REQUEST['st']=="lprice"?" selected":""?>>낮은가격순</option>
								<option value="hprice"<?=$_REQUEST['st']=="hprice"?" selected":""?>>높은가격순</option>
							</select>
							<select name="scale" onChange="viewGood(this.value);">
								<option value="9"<?=$_GET['scale']=="9"?" selected":""?>>9개씩 정렬</option>
								<option value="18"<?=$_GET['scale']=="18"?" selected":""?>>18개씩 정렬</option>
								<option value="36"<?=$_GET['scale']=="36"?" selected":""?>>36개씩 정렬</option>
								<option value="54"<?=$_GET['scale']=="54"?" selected":""?>>54개씩 정렬</option>
							</select>
						</div>
						<!--//selectBox --> 
						<div class="paging pagingTop">
							<?=pageNavigation($arrList['total'],$scale,$pagescale,$_GET['offset'],"cat_no=".$_REQUEST["cat_no"]."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&st=".$_REQUEST['st']."&scale=".$_REQUEST['scale']."&goPage=".$_REQUEST['goPage'])?>
						</div>
						<!--//paging --> 
					</div>
					<!--//searchArea -->

					
					<div class="productList">
						<!-- 3번째 li마다 class="last"삽입-->
						<ul>
							<?if($arrList['list']['total'] > 0):?>
							<?for ($i=0;$i<$arrList['list']['total'];$i++) {
								$arrThisCatCode = explode("/", $arrList["list"][$i]['cat_code']);
							?>
							<li<?=$i%3==2?" class='last'":""?>>
								<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&cat_no=<?=$_GET['cat_no']?>&idx=<?=$arrList['list'][$i]['idx']?>">
									<div class="pic"><img src="/uploaded/shop_good/<?=$arrList['list'][$i]['idx']?>/<?=$arrList['list'][$i]['image_l']?>" width="294" /></div>
									<p class="tit"><?=$arrAllCategory[$arrThisCatCode[1]]?></p>
									<p class="name"><?=text_cut(stripslashes($arrList['list'][$i]['g_name']),42)?></p>
									<p class="price"><?=number_format($arrList['list'][$i]['price'])?></p>
								</a>
							</li>
							<?} else: ?>
							<li>등록된 상품이 없습니다.</li>
							<?endif;?>
						</ul>
					</div>
					<!--//productList -->

					<div class="paging">
						<?=pageNavigation($arrList['total'],$scale,$pagescale,$_GET['offset'],"cat_no=".$_REQUEST["cat_no"]."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&st=".$_REQUEST['st']."&scale=".$_REQUEST['scale']."&goPage=".$_REQUEST['goPage'])?>
					</div>
					<!--//paging --> 

				
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
