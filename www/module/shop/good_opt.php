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

$scale = "16";

//상품 리스트
if($_REQUEST['sw']=="top"){

$arrList1 = getGoodListBaseNFileFromCat("1", 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	5, $_REQUEST['offset'],"Y");

$arrList2 = getGoodListBaseNFileFromCat("2", 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	5, $_REQUEST['offset'],"Y");

$arrList3 = getGoodListBaseNFileFromCat("3", 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	5, $_REQUEST['offset'],"Y");

$arrList4 = getGoodListBaseNFileFromCat("4", 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	5, $_REQUEST['offset'],"Y");

$arrList5 = getGoodListBaseNFileFromCat("5", 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	5, $_REQUEST['offset'],"Y");

} else {

$arrList = getGoodListBaseNFileFromCat(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"Y");

}

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

SetDisConn($dblink);

switch($_REQUEST['sw']) {
	case("best"): $title = "BEST PRODUCT"; break;
	case("new"): $title = "NEW PRODUCT"; break;
	case("event"): $title = "EVENT"; break;
	case("top"): $title = "바비걸 TOP5"; break;
	case("recom"): $title = "바비추천상품"; break;
	default : $title = "개인결제";
}
?>

    <div id="content">
		<div id="subTitle">
			<h3><?=$title?></h3>
			<span class="location"> HOME &gt; <strong><?=$title?></strong></span>
		</div>

		<? if($_REQUEST['sw']=="top"){?>
		<div id="subTitle"><strong>- <?=$arrAllCategory[1]?></strong></div>

		<div class="BestList mgb40">
		<ul>
			<?if($arrList1['list']['total'] > 0):?>
			<?for ($i=0;$i<$arrList1['list']['total'];$i++) {
				$arrCat1[$i] = explode("/", $arrList1["list"][$i]["cat_code"]);
			?>
			<li>
				<span class="photo"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList1['list'][$i]['g_code']?>"><img src="/uploaded/shop_good/<?=$arrList1['list'][$i]['idx']?>/<?=$arrList1['list'][$i]['image_m']?>" width="195" alt="" /></a></span>
				<span class="p_cate">[<?=$arrAllCategory[$arrCat1[$i]['0']]?> <?=$arrAllCategory[$arrCat1[$i]['1']]?>] <!--<a href="javascript:popWinCenter('../shop/zoom.php',650,570);"><img src="../images/btn_zoom.gif" alt="확대보기" /></a>--></span><br />
				<span class="p_name"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList1['list'][$i]['g_code']?>"><?=stripslashes($arrList1['list'][$i]['g_name'])?></a></span><br />
				<span class="info01"><?=stripslashes($arrList1['list'][$i]['madein'])?></span><br />
				<span class="info02"><?=stripslashes($arrList1['list'][$i]['vendor'])?></span><br />
				<span class="p_price"><?=number_format($arrList1['list'][$i]['price'])?>원</span>
			</li>
			<?} else: ?>
			<li>등록된 상품이 없습니다.</li>
			<?endif;?>
		</ul>    	
		</div><br /><br />

		<div id="subTitle"><strong>- <?=$arrAllCategory[2]?></strong></div>

		<div class="BestList mgb40">
		<ul>
			<?if($arrList2['list']['total'] > 0):?>
			<?for ($i=0;$i<$arrList2['list']['total'];$i++) {
				$arrCat2[$i] = explode("/", $arrList2["list"][$i]["cat_code"]);
			?>
			<li>
				<span class="photo"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList2['list'][$i]['g_code']?>"><img src="/uploaded/shop_good/<?=$arrList2['list'][$i]['idx']?>/<?=$arrList2['list'][$i]['image_m']?>" width="195" alt="" /></a></span>
				<span class="p_cate">[<?=$arrAllCategory[$arrCat2[$i]['0']]?> <?=$arrAllCategory[$arrCat2[$i]['1']]?>] <!--<a href="javascript:popWinCenter('../shop/zoom.php',650,570);"><img src="../images/btn_zoom.gif" alt="확대보기" /></a>--></span><br />
				<span class="p_name"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList2['list'][$i]['g_code']?>"><?=stripslashes($arrList2['list'][$i]['g_name'])?></a></span><br />
				<span class="info01"><?=stripslashes($arrList2['list'][$i]['madein'])?></span><br />
				<span class="info02"><?=stripslashes($arrList2['list'][$i]['vendor'])?></span><br />
				<span class="p_price"><?=number_format($arrList2['list'][$i]['price'])?>원</span>
			</li>
			<?} else: ?>
			<li>등록된 상품이 없습니다.</li>
			<?endif;?>
		</ul>    	
		</div><br /><br />

		<div id="subTitle"><strong>- <?=$arrAllCategory[3]?></strong></div>

		<div class="BestList mgb40">
		<ul>
			<?if($arrList3['list']['total'] > 0):?>
			<?for ($i=0;$i<$arrList3['list']['total'];$i++) {
				$arrCat3[$i] = explode("/", $arrList3["list"][$i]["cat_code"]);
			?>
			<li>
				<span class="photo"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList3['list'][$i]['g_code']?>"><img src="/uploaded/shop_good/<?=$arrList3['list'][$i]['idx']?>/<?=$arrList3['list'][$i]['image_m']?>" width="195" alt="" /></a></span>
				<span class="p_cate">[<?=$arrAllCategory[$arrCat3[$i]['0']]?> <?=$arrAllCategory[$arrCat3[$i]['1']]?>] <!--<a href="javascript:popWinCenter('../shop/zoom.php',650,570);"><img src="../images/btn_zoom.gif" alt="확대보기" /></a>--></span><br />
				<span class="p_name"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList3['list'][$i]['g_code']?>"><?=stripslashes($arrList3['list'][$i]['g_name'])?></a></span><br />
				<span class="info01"><?=stripslashes($arrList3['list'][$i]['madein'])?></span><br />
				<span class="info02"><?=stripslashes($arrList3['list'][$i]['vendor'])?></span><br />
				<span class="p_price"><?=number_format($arrList3['list'][$i]['price'])?>원</span>
			</li>
			<?} else: ?>
			<li>등록된 상품이 없습니다.</li>
			<?endif;?>
		</ul>    	
		</div><br /><br />

		<div id="subTitle"><strong>- <?=$arrAllCategory[4]?></strong></div>

		<div class="BestList mgb40">
		<ul>
			<?if($arrList4['list']['total'] > 0):?>
			<?for ($i=0;$i<$arrList4['list']['total'];$i++) {
				$arrCat4[$i] = explode("/", $arrList4["list"][$i]["cat_code"]);
			?>
			<li>
				<span class="photo"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList4['list'][$i]['g_code']?>"><img src="/uploaded/shop_good/<?=$arrList4['list'][$i]['idx']?>/<?=$arrList4['list'][$i]['image_m']?>" width="195" alt="" /></a></span>
				<span class="p_cate">[<?=$arrAllCategory[$arrCat4[$i]['0']]?> <?=$arrAllCategory[$arrCat4[$i]['1']]?>] <!--<a href="javascript:popWinCenter('../shop/zoom.php',650,570);"><img src="../images/btn_zoom.gif" alt="확대보기" /></a>--></span><br />
				<span class="p_name"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList4['list'][$i]['g_code']?>"><?=stripslashes($arrList4['list'][$i]['g_name'])?></a></span><br />
				<span class="info01"><?=stripslashes($arrList4['list'][$i]['madein'])?></span><br />
				<span class="info02"><?=stripslashes($arrList4['list'][$i]['vendor'])?></span><br />
				<span class="p_price"><?=number_format($arrList4['list'][$i]['price'])?>원</span>
			</li>
			<?} else: ?>
			<li>등록된 상품이 없습니다.</li>
			<?endif;?>
		</ul>    	
		</div><br /><br />

		<div id="subTitle"><strong>- <?=$arrAllCategory[5]?></strong></div>

		<div class="BestList mgb40">
		<ul>
			<?if($arrList5['list']['total'] > 0):?>
			<?for ($i=0;$i<$arrList5['list']['total'];$i++) {
				$arrCat5[$i] = explode("/", $arrList5["list"][$i]["cat_code"]);
			?>
			<li>
				<span class="photo"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList5['list'][$i]['g_code']?>"><img src="/uploaded/shop_good/<?=$arrList5['list'][$i]['idx']?>/<?=$arrList5['list'][$i]['image_m']?>" width="195" alt="" /></a></span>
				<span class="p_cate">[<?=$arrAllCategory[$arrCat5[$i]['0']]?> <?=$arrAllCategory[$arrCat5[$i]['1']]?>] <!--<a href="javascript:popWinCenter('../shop/zoom.php',650,570);"><img src="../images/btn_zoom.gif" alt="확대보기" /></a>--></span><br />
				<span class="p_name"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList5['list'][$i]['g_code']?>"><?=stripslashes($arrList5['list'][$i]['g_name'])?></a></span><br />
				<span class="info01"><?=stripslashes($arrList5['list'][$i]['madein'])?></span><br />
				<span class="info02"><?=stripslashes($arrList5['list'][$i]['vendor'])?></span><br />
				<span class="p_price"><?=number_format($arrList5['list'][$i]['price'])?>원</span>
			</li>
			<?} else: ?>
			<li>등록된 상품이 없습니다.</li>
			<?endif;?>
		</ul>    	
		</div>

		<?} else {?>
		
		<div id="productCount">
            <ul class="prod_sort">
                <li><a href="/shop.php?goPage=Search&sk=<?=$_REQUEST["sk"]?>&st=new"><img src="../images/shop/sort1.gif" alt="신상품순" /></a></li>
                <li><a href="/shop.php?goPage=Search&sk=<?=$_REQUEST["sk"]?>&st=lprice"><img src="../images/shop/sort2.gif" alt="가격순" /></a></li>
                <li><a href="/shop.php?goPage=Search&sk=<?=$_REQUEST["sk"]?>&st=order"><img src="../images/shop/sort3.gif" alt="판매순" /></a></li>
                <li><a href="/shop.php?goPage=Search&sk=<?=$_REQUEST["sk"]?>&st=hit"><img src="../images/shop/sort4.gif" alt="인기순" /></a></li>
            </ul>
        </div>

        <div class="productList">
		<ul>
			<?if($arrList['list']['total'] > 0):?>
			<?for ($i=0;$i<$arrList['list']['total'];$i++) {
				$arrCat[$i] = explode("/", $arrList["list"][$i]["cat_code"]);

				// 아이콘표시
				$array_part_icons=explode("|",$arrList['list'][$i]['icons']);
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
				<span class="photo"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList['list'][$i]['g_code']?>"><img src="/uploaded/shop_good/<?=$arrList['list'][$i]['idx']?>/<?=$arrList['list'][$i]['image_m']?>" width="240" alt="" /></a></span>
				<span class="p_cate">[<?=$arrAllCategory[$arrCat[$i]['0']]?> <?=$arrAllCategory[$arrCat[$i]['1']]?>] <!--<a href="javascript:popWinCenter('../shop/zoom.php',650,570);"><img src="../images/btn_zoom.gif" alt="확대보기" /></a>--></span><br />
				<span class="p_name"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList['list'][$i]['g_code']?>"><?=stripslashes($arrList['list'][$i]['g_name'])?></a></span><br />
				<? if($arrList['list'][$i]['madein']) {?>
				<span class="info01"><?=stripslashes($arrList['list'][$i]['madein'])?></span><br />
				<?}?>
				<? if($arrList['list'][$i]['vendor']) {?>
				<span class="info02"><?=stripslashes($arrList['list'][$i]['vendor'])?></span><br />
				<?}?>
				<? if($temp_part_icons) {?>
				<span class="info02"><?=$temp_part_icons?></span><br />
				<?}?>
				<? if($arrList['list'][$i]['sale_price']) {?>
				<span class="info01"><s><?=number_format($arrList['list'][$i]['sale_price'])?>원</s></span><br />
				<?}?>
				<span class="p_price"><?=number_format($arrList['list'][$i]['price'])?>원</span>
			</li>
			<?} else: ?>
			<li>등록된 상품이 없습니다.</li>
			<?endif;?>
		</ul>    	
		</div>

		<?}?>

	<div class="paginate">  
	<?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"cat_no=".$catno."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&st=".$_REQUEST['st']."&goPage=".$_REQUEST['goPage'])?>
	</div>
</div>