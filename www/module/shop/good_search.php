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

if($_GET['scale']) {
	$scale = $_GET['scale'];
} else {
	$scale = "9";
}

//상품 리스트
$arrList = getGoodListBaseNFileFromSearch(
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"Y");

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

SetDisConn($dblink);
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
<input type="hidden" name="goPage" value="Search">
<input type="hidden" name="sk" value="<?=$_REQUEST["sk"]?>">
<input type="hidden" name="st" value="<?=$_REQUEST["st"]?>">
<input type="hidden" name="scale" value="<?=$_REQUEST["scale"]?>">
</form>

	<div id="sub_container">
		<div class="content">

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/include/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="con">
					<div class="location">
					<p class="local"><span class="home"></span><span class="current">search</span></p>
				</div>
				<!-- //location -->
				<h2>SEARCH</h2>
				<!-- 내용 : s -->

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
								<a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&idx=<?=$arrList['list'][$i]['idx']?>">
									<div class="pic"><img src="/uploaded/shop_good/<?=$arrList['list'][$i]['idx']?>/<?=$arrList['list'][$i]['image_l']?>" width="294" /></div>
									<p class="tit"><?=$arrAllCategory[$arrThisCatCode[1]]?></p>
									<p class="name"><?=stripslashes($arrList['list'][$i]['g_name'])?></p>
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
						<?=pageNavigation($arrList['total'],$scale,$pagescale,$_GET['offset'],"cat_no=".$_REQUEST["cat_no"]."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&st=".$_REQUEST['st']."&goPage=".$_REQUEST['goPage'])?>
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
