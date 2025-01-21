<?
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$order_by = " A.sort_num DESC, A.idx DESC ";

$scale = "500";

//상품 리스트
$arrList = getGoodListBaseNFileFromCat(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"Y");

//Best 상품 리스트
$arrBestList = getGoodListMain(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']),  5, 0, "main_show");

$nowPoint = getNowPoint($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

//DB해제
SetDisConn($dblink);
?>
	<div class="location">
    	<p><img src="../images/home.gif" alt="" /> HOME &gt; 포인트몰</p>
    </div>

	<? include $_SERVER['DOCUMENT_ROOT'] . "/include/menu_pointmall.php"; ?>
    
    <div id="contents">
    	
        <form method="post" name="frmPointForm">
		<input type="hidden" name="curr_poiont" value="<?=$nowPoint[nowpoint]?>">
		<input type="hidden" name="app_qty" value="">
		<input type="hidden" name="app_point" value="">
		<input type="hidden" name="rest_point" value="">
		<input type="hidden" name="idx" value="">

		<!-- // 나의 포인트 내역 -->
        <div id="pointmallArea">
            <div class="myPoint">
                <p><strong><?=number_format($nowPoint[nowpoint])?></strong> <img src="../images/shop/point.gif" alt="POINT" /></p>
            </div>
        </div>
        <!-- // 나의 포인트 내역 -->
        
        
        
        <!-- // BEST 포인트몰 상품 -->
        <div id="pointmallBest">
            <div class="pointmallBestTitle">
                <p><img src="../images/shop/pointmallbest.gif" alt="BEST 포인트몰 상품" /></p>
            </div>
            <div id="pointmallBestList">
                <ul>
                    <?if($arrBestList['list']['total'] > 0):?>
					<?for ($i=0;$i<$arrBestList['list']['total'];$i++) {?>
					<li>
                        <p class="image"><img src="/uploaded/shop_good/<?=$arrBestList['list'][$i]['idx']?>/<?=$arrBestList['list'][$i]['image_m']?>" width="100" height="100" alt="" /></p>
                        <p><?=text_cut(stripslashes($arrBestList['list'][$i]['g_name']),20)?></p>
						<p class="prd_price"><?=number_format($arrBestList['list'][$i]['price'])?>원</p>
						<p class="mgt10">
							<select name="b_qty<?=$i?>" onchange="selectQty('<?=$arrBestList['list'][$i]['price']?>',this)">
								<option value="">선택</option>
								<? for($j=1;$j<11;$j++){?>
								<option value="<?=$j?>"><?=$j?></option>
								<?}?>
							</select>
							
							<a href="javascript:confirmApp('<?=$arrBestList['list'][$i]['idx']?>','<?=$arrBestList['list'][$i]['price']?>',frmPointForm.b_qty<?=$i?>)"><img src="/images/shop/btn_app.gif" alt="신청" style='vertical-align: middle;'/></a>
						</p>
                    </li>
					<?} endif; ?>
                </ul>
            </div>
        </div>
        <!-- BEST 포인트몰 상품 // -->
        

       
        

		<!-- // BEST 포인트몰 상품 -->
        <ul class="pointmallTab">
            <li><a href="/shop.php?goPage=PointList&cat_no=88"><img src="../images/shop/pointmall_cate1<?=$_REQUEST['cat_no']=="88"?"_on":""?>.gif" alt="전체" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=89"><img src="../images/shop/pointmall_cate2<?=$_REQUEST['cat_no']=="89"?"_on":""?>.gif" alt="백화점/마트" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=90"><img src="../images/shop/pointmall_cate3<?=$_REQUEST['cat_no']=="90"?"_on":""?>.gif" alt="도서" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=91"><img src="../images/shop/pointmall_cate4<?=$_REQUEST['cat_no']=="91"?"_on":""?>.gif" alt="영화" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=92"><img src="../images/shop/pointmall_cate5<?=$_REQUEST['cat_no']=="92"?"_on":""?>.gif" alt="주유권" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=93"><img src="../images/shop/pointmall_cate6<?=$_REQUEST['cat_no']=="93"?"_on":""?>.gif" alt="베이커리" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=94"><img src="../images/shop/pointmall_cate7<?=$_REQUEST['cat_no']=="94"?"_on":""?>.gif" alt="커피/아이스크림" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=95"><img src="../images/shop/pointmall_cate8<?=$_REQUEST['cat_no']=="95"?"_on":""?>.gif" alt="패스트푸드" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=96"><img src="../images/shop/pointmall_cate9<?=$_REQUEST['cat_no']=="96"?"_on":""?>.gif" alt="화장품" /></a></li>
            <li><a href="/shop.php?goPage=PointList&cat_no=97"><img src="../images/shop/pointmall_cate10<?=$_REQUEST['cat_no']=="97"?"_on":""?>.gif" alt="패밀리레스토랑" /></a></li>
        </ul>
        <!-- BEST 포인트몰 상품 // -->

        
        <!-- // 리스트 -->
        <div id="pointmallList">
            <ul>
                <?if($arrList['list']['total'] > 0):?>
				<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
				<li>
                    <p class="image"><img src="/uploaded/shop_good/<?=$arrList['list'][$i]['idx']?>/<?=$arrList['list'][$i]['image_s']?>" width="100" height="100" alt="" /></p>
                    <p><?=text_cut(stripslashes($arrList['list'][$i]['g_name']),24)?></p>
                    <p class="prd_price"><?=number_format($arrList['list'][$i]['price'])?>원</p>
                    <p class="mgt10">
                    	<select name="qty<?=$i?>" onchange="selectQty('<?=$arrList['list'][$i]['price']?>',this)">
                        	<option value="">선택</option>
							<? for($j=1;$j<11;$j++){?>
							<option value="<?=$j?>"><?=$j?></option>
							<?}?>
                        </select>
                    	
                        <a href="javascript:confirmApp('<?=$arrList['list'][$i]['idx']?>','<?=$arrList['list'][$i]['price']?>',frmPointForm.qty<?=$i?>)"><img src="/images/shop/btn_app.gif" alt="신청" /></a>
                    </p>
                </li>
				<?}?>

				<?else:?>
				<li>등록된 상품이 없습니다.</li>
				<?endif;?>
			</ul>
        </div> 
        <!-- 리스트 // -->
        </form>

        <p class="pointmallGuide"><img src="../images/shop/pointmall_guide.jpg" alt="" /></p>

	</div>

<script>
 var M_point = "<?=$nowPoint[nowpoint]?>";
 var frm = document.frmPointForm;

function selectQty(price, selectObj) {
	var tPrice = calcPoint(price, selectObj.value);
	 if(tPrice != 0){
	 if (tPrice > M_point) {
		alert("보유하고 계신 포인트보다 금액을 초과 했습니다.");
		selectObj.selectedIndex = 0;
		return;   
	 }
   }
}

function calcPoint(price, qty){
	if (price==Infinity || isNaN(price)){
		price = 0;
	}
	if (qty==Infinity || isNaN(qty)){
		qty = 0;
	}
	if(price == 0){
		alert('상품권에 대한 금액이 존재하지 않습니다.\n금액 확인 바랍니다.');
		return 0;
	}
	if(qty == 0){
	  alert('수량을 선택하시기 바랍니다.');
	  return 0;
	}
	return eval(price * qty); 
}

function calcRestPoint(currPoint, usePoint){
	if (currPoint==Infinity || isNaN(currPoint)){
		price = 0;
	}
	if (usePoint==Infinity || isNaN(usePoint)){
		usePoint = 0;
	}
	return eval(currPoint - usePoint); 
}  


function confirmApp(idx, price, selectObj){
	var tPrice = calcPoint(price, selectObj.value);
	if(tPrice != 0){
		if (tPrice > M_point) {
			alert("보유하고 계신 포인트보다 금액을 초과 했습니다.");
			selectObj.selectedIndex = 0;
			return;   
		}else{
			frm.app_qty.value = selectObj.value;
			frm.app_point.value = tPrice;
			frm.rest_point.value = calcRestPoint(M_point, tPrice);
			frm.idx.value = idx;
			newwindow = window.open("","popup","scrollbars=yes, toolbar=no, directories=no, menuar=no, resizable=no, status=yes, width=647, height=530");
			frm.action = "/shop/pointService_pop_register.php";
			frm.target = "popup";
			frm.submit();
		 }
	}
}

</script>