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

$scale = "80";

if($_REQUEST["cat_no"]=="3") {
	$arrList[0] = getGoodListBaseNFileFromCat(
		"3", 
		$order_by, 
		mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
		mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
		$scale, $_REQUEST['offset'],"Y");
} else {
	$arrCategory = getCategoryList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));

	for($i=0;$i<$arrCategory["total"];$i++){

		$arrList[$i] = getGoodListBaseNFileFromCat(
			$arrCategory["list"][$i]['cat_no'], 
			$order_by, 
			mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
			mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
			$scale, $_REQUEST['offset'],"Y");
	}
}

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();


//현재 카테고리 정보
$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));
$arrCatCode = explode("/", $arrCategoryInfo["list"][0]["cat_code"]);

//서브 카테고리 목록
$arrSubCategoryList = getCategoryList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));


if($_REQUEST["cat_no"]=="1") {
	$arrSubBannerList = getMainBannerList("7"); //롤링배너
} else if($_REQUEST["cat_no"]=="2") {
	$arrSubBannerList = getMainBannerList("8"); //롤링배너
}


SetDisConn($dblink);
?>

	<? if($_REQUEST["cat_no"]!="3") {?>
	<div class="sub_visual">
		<div id="slideshow" class="slideshow">			
			<div id="slider" class="slider">
				<ul>
					<? if($arrSubBannerList['list']['total'] > 0){
					for($i=0; $i < $arrSubBannerList['list']['total']; $i++){
					?>
					<li><a href="<?=$arrSubBannerList["list"][$i]["b_url"]?>"  target="<?=$arrSubBannerList["list"][$i]["b_target"]?>"><img src="/uploaded/banner/<?=$arrSubBannerList["list"][$i]["b_image"]?>" width="1000" height="336" alt="<?=stripslashes($arrSubBannerList["list"][$i]["b_subject"])?>" /></a></li>
					<?}}?>
				</ul>
			</div>
			<? if($arrSubBannerList['list']['total'] > 1) {?>
			<ul id="pagination" class="pagination">
				<? if($arrSubBannerList['list']['total'] > 0){
				for($i=0; $i < $arrSubBannerList['list']['total']; $i++){
				?>
				<li onclick="slideshow.pos(<?=$i?>)"></li>
				<?}}?>
			</ul>
			<?}?>
		</div>			
		<? if($arrSubBannerList['list']['total'] > 1) {?>
		<script type="text/javascript" src="../js/slideshow.js"></script>
		<script type="text/javascript">
			var slideshow=new TINY.slider.slide('slideshow',{
				id:'slider', // slider아이디값
				auto:4,  
				resume:true,
				position:0,
				rewind:false,			
				navid:'pagination',
				activeclass:'current'
			});
		</script>	
		<?}?>
	</div>
	<?}?>
	<div id="content2">	
		<div id="con_area">			
		
		<? if($_REQUEST["cat_no"]=="1") {?>
			
			<div class="mono_list">
				<h2 class="tt_package"><img src="../images/sub/tt_business.gif" alt="영업용, Business package" /></h2>
				<ul>
					<?if($arrList[0]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[0]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[0]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[0]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[0]['list'][$i]['idx']?>/<?=$arrList[0]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[0]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[0]['list'][$i]['isbn']?stripslashes($arrList[0]['list'][$i]['isbn'])."</strong>":number_format($arrList[0]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>
			<div class="mono_list mono_list_v2">
				<h2 class="tt_package"><img src="../images/sub/tt_indiv.gif" alt="개인/업무용, Individual package" /></h2>
				<ul>
					<?if($arrList[1]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[1]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[1]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[1]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[1]['list'][$i]['idx']?>/<?=$arrList[1]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[1]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[1]['list'][$i]['isbn']?stripslashes($arrList[1]['list'][$i]['isbn'])."</strong>":number_format($arrList[1]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>

		<? } else if($_REQUEST["cat_no"]=="2") {?>

			<!--// content -->
			<div class="mono_tab">
				<ul>
					<?
					if($arrSubCategoryList["total"]>0){
					for($i=0; $i<$arrSubCategoryList["total"]; $i++){	
					?>
					<li<?=$i==0?" class='on'":""?> id="column<?=$i+1?>"><a href="#"><?=$arrSubCategoryList["list"][$i]['cat_name']?></a></li>
					<?}}?>
				</ul>				
			</div>
			<div class="mono_list mono_list_v4 column1">
				<h2 class="tt_machine"><img src="../images/shop/tt_machine1.gif" alt="커피머신" /></h2>
				<ul>
					<?if($arrList[0]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[0]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[0]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[0]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[0]['list'][$i]['idx']?>/<?=$arrList[0]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[0]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[0]['list'][$i]['isbn']?stripslashes($arrList[0]['list'][$i]['isbn'])."</strong>":number_format($arrList[0]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>
			<div class="mono_list mono_list_v3 column2">
				<h2 class="tt_machine"><img src="../images/shop/tt_grinder.gif" alt="그라인더" /></h2>
				<ul>
					<?if($arrList[1]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[1]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[1]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[1]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[1]['list'][$i]['idx']?>/<?=$arrList[1]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[1]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[1]['list'][$i]['isbn']?stripslashes($arrList[1]['list'][$i]['isbn'])."</strong>":number_format($arrList[1]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>
			<div class="mono_list mono_list_v3 column3">
				<h2 class="tt_machine"><img src="../images/shop/tt_ice.gif" alt="제빙기" /></h2>
				<ul>
					<?if($arrList[2]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[2]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[2]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[2]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[2]['list'][$i]['idx']?>/<?=$arrList[2]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[2]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[2]['list'][$i]['isbn']?stripslashes($arrList[2]['list'][$i]['isbn'])."</strong>":number_format($arrList[2]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>	
				</ul>
			</div>
			<div class="mono_list mono_list_v3 column4">
				<h2 class="tt_machine"><img src="../images/shop/tt_blender.gif" alt="블랜더" /></h2>
				<ul>
					<?if($arrList[3]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[3]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[3]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[3]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[3]['list'][$i]['idx']?>/<?=$arrList[3]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[3]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[3]['list'][$i]['isbn']?stripslashes($arrList[3]['list'][$i]['isbn'])."</strong>":number_format($arrList[3]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>
			<div class="mono_list mono_list_v3 column5">
				<h2 class="tt_machine"><img src="../images/shop/tt_shav.gif" alt="빙삭기" /></h2>
				<ul>
					<?if($arrList[4]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[4]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[4]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[4]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[4]['list'][$i]['idx']?>/<?=$arrList[4]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[4]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[4]['list'][$i]['isbn']?stripslashes($arrList[4]['list'][$i]['isbn'])."</strong>":number_format($arrList[4]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>
			<div class="mono_list mono_list_v3 column6">
				<h2 class="tt_machine"><img src="../images/shop/tt_freezing.gif" alt="냉장냉동고" /></h2>
				<ul>
					<?if($arrList[5]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[5]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[5]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[5]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[5]['list'][$i]['idx']?>/<?=$arrList[5]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[5]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[5]['list'][$i]['isbn']?stripslashes($arrList[5]['list'][$i]['isbn'])."</strong>":number_format($arrList[5]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>
			<div class="mono_list mono_list_v3 column7">
				<h2 class="tt_machine"><img src="../images/shop/tt_heater.gif" alt="온수기" /></h2>
				<ul>
					<?if($arrList[6]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[6]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[6]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[6]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[6]['list'][$i]['idx']?>/<?=$arrList[6]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[6]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[6]['list'][$i]['isbn']?stripslashes($arrList[6]['list'][$i]['isbn'])."</strong>":number_format($arrList[6]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>
			<div class="mono_list mono_list_v3 column8">
				<h2 class="tt_machine"><img src="../images/shop/tt_showcase.gif" alt="쇼케이스" /></h2>
				<ul>
					<?if($arrList[7]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[7]['list']['total'];$i++) {
						// 아이콘표시
						$array_part_icons=explode("|",$arrList[7]['list'][$i]['icons']);
						// 상품아이콘(히트,뉴,추천....)		
						$temp_part_icons="";
						if (count($array_part_icons)>0){				
							for($k=0;$k<count($array_part_icons);$k++){
								if ($array_part_icons[$k]){
									$temp_part_icons.="<img src='/uploaded/shop_icons/".$array_part_icons[$k]."'> ";				
								}
							}
						}
					?>
					<li>
						<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList[7]['list'][$i]['g_code']?>"><span class="thumb"><img src="/uploaded/shop_good/<?=$arrList[7]['list'][$i]['idx']?>/<?=$arrList[7]['list'][$i]['image_l']?>" alt="" width="235" height="253"/></span>
							<?=stripslashes($arrList[7]['list'][$i]['g_name'])?>
						</a>
						<? if($temp_part_icons) {?><?=$temp_part_icons?><br /><?}?>
						<span class="price"><strong><?=$arrList[7]['list'][$i]['isbn']?stripslashes($arrList[7]['list'][$i]['isbn'])."</strong>":number_format($arrList[7]['list'][$i]['price'])."</strong>원"?></span>
					</li>
					<?} else: ?>
					<li>등록된 상품이 없습니다.</li>
					<?endif;?>
				</ul>
			</div>

			<script type="text/javascript" 	src="../js/jquery.smint.js"></script>
			<script type="text/javascript">
				
			jQuery(document).ready( function() {
				jQuery('.mono_tab').smint({
					'scrollSpeed' : 1000
				});
			});

			</script>
			
			<? } else if($_REQUEST["cat_no"]=="3") {?>

			<div class="individual">
				<h2 class="tt_individual"><img src="../images/shop/tt_individual.gif" alt="개인결제창" /></h2>
				<ul>
					<?if($arrList[0]['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrList[0]['list']['total'];$i++) {?>
					<form id="frmGoodDetail<?=$i?>" name="frmGoodDetail<?=$i?>">
					<input type="hidden" id="basicPrice" name="basicPrice" value="<?=$arrList[0]['list'][$i]['price']?>">
					<li>
						<? if($arrList[0]['list'][$i]['price'] > 0) {?>
						<a href="javascript:buyDirect('<?=$arrList[0]['list'][$i]['idx']?>', 1)">
						<?}?>
						<span class="thumb"><? if($arrList[0]['list'][$i]['image_l']) {?><img src="/uploaded/shop_good/<?=$arrList[0]['list'][$i]['idx']?>/<?=$arrList[0]['list'][$i]['image_l']?>" alt="" width="235" height="195" />
						<?}else{?><img src="../images/shop/mono.gif" alt="" /><?}?>
						</span>
						<span class="name">[<?=stripslashes($arrList[0]['list'][$i]['g_name'])?>님]</span>	
						개인결제입니다.
						<? if($arrList[0]['list'][$i]['price'] > 0) {?>
						</a>
						<?}?>
						<span class="price"><strong><?=number_format($arrList[0]['list'][$i]['price'])?></strong>원</span>						
					</li>
					</form>
					<?} else: ?>
					<li>등록된 결제정보가 없습니다.</li>
					<?endif;?>
				</ul>
			</div>
			<? } ?>
		</div>
	</div>
