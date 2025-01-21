<?
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	$tp = "1";
	$pointunit = $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVELPOINT"];
}else{
	$tp = "2";
	$pointunit = "0";
}
	
$arrList = getCartList($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$tp);

//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);
?>
<script>
$(function(){
	//전체선택 체크박스 클릭
	$("#allCheck").click(function(){
		//만약 전체 선택 체크박스가 체크된상태일경우
		if($("#allCheck").prop("checked")) {
			//해당화면에 전체 checkbox들을 체크해준다
			$("input[type=checkbox]").prop("checked",true);
		// 전체선택 체크박스가 해제된 경우
		} else {
			//해당화면에 모든 checkbox들의 체크를해제시킨다.
			$("input[type=checkbox]").prop("checked",false);
		}
	})
})

function incAmount2(num){
	var qty = document.getElementById('qty_'+num).value;
	document.getElementById('qty_'+num).value = ++qty;
}

// 수량 감소
function decAmount2(num){
   var qty = document.getElementById('qty_'+num).value;

	if(qty > 1)
		document.getElementById('qty_'+num).value = --qty;
}
</script>
<div id="sub_container">
		<div class="content">

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/include/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="location">
					<p class="local"><span class="home"></span><span class="current">장바구니</span></p>
				</div>
				<!-- //location -->
				
				<div class="con">
				<!-- 내용 : s -->

					<div class="cartCon">
						<p class="mb40"><img src="/img/cartCon_img_step01.jpg" alt="01 장바구니" /></p>
						<form id="frmCartList" name="frmCartList" method="POST">
						<input type="hidden" name="evnMode">
						<div class="blist">
							<table>
								<colgroup>
									<col width="40px" />
									<col width="100px" />
									<col width="*" />
									<col width="80px" />
									<col width="120px" />
									<!-- <col width="100px" />
									<col width="100px" /> -->
									<col width="80px" />
									<col width="90px" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col"><input type="checkbox" id="allCheck" checked /></th>
										<th scope="col" colspan="2">상품정보</th>
										<th scope="col">수량</th>
										<th scope="col">판매금액</th>
										<!-- <th scope="col">쿠폰/상품권</th>
										<th scope="col">쿠폰적용금액</th> -->
										<th scope="col">적립금</th>
										<th scope="col">삭제</th>
									</tr>
								</thead>
								<tbody>
								<?
								if($arrList["total"]>0){
									for($i=0;$i<$arrList["total"];$i++){
										$arrOpt1[$i] = explode("|",$arrList["list"][$i]['opt_1']);
										$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
										$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
										$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
										$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
										$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);

										//추가금액 계산
										$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'];

										//적립금계산
										//if($arrList["list"][$i]['point_unit']=="P"){
											$thisPoint = (($pointunit *($arrList["list"][$i]['price']+$optionPrice))/100) * $arrList["list"][$i]['qty'];
										//}else{
										//	$thisPoint = $arrList["list"][$i]['point'] * $arrList["list"][$i]['qty'];
										//}

										//합계금액 계산
										$totalPrice += ($arrList["list"][$i]['price']*$arrList["list"][$i]['qty'])+($optionPrice * $arrList["list"][$i]['qty']);

										if($arrList['list'][$i]['image_s']) {
											$simg = "/uploaded/shop_good/".$arrList['list'][$i]['idx']."/".$arrList['list'][$i]['image_s'];
										} else {
											$simg = "/images/shop/no_img.jpg";
										}
								?>
									<tr>
										<td><input type="checkbox" id="items[]" name="items[]" value="<?=$arrList["list"][$i]['c_idx']?>" checked="checked"></td>
										<td><img src="<?=$simg?>" width="76" /></td>
										<td class="tl">
											<p class="name"><?=stripslashes($arrList["list"][$i]['g_name'])?></p>
											<p class="option"><?=$arrOpt1[$i]['0']?"| ".$arrOpt1[$i]['0']:""?><?=$arrOpt1[$i]['1']?" +".number_format($arrOpt1[$i]['1']):""?>
											<?=$arrOpt2[$i]['0']?"| ".$arrOpt2[$i]['0']:""?><?=$arrOpt2[$i]['1']?" +".number_format($arrOpt2[$i]['1']):""?>
											<?=$arrOpt3[$i]['0']?"| ".$arrOpt3[$i]['0']:""?><?=$arrOpt3[$i]['1']?" +".number_format($arrOpt3[$i]['1']):""?>
											<?=$arrOpt4[$i]['0']?"| ".$arrOpt4[$i]['0']:""?><?=$arrOpt4[$i]['1']?" +".number_format($arrOpt4[$i]['1']):""?>
											<?=$arrOpt5[$i]['0']?"| ".$arrOpt5[$i]['0']:""?><?=$arrOpt5[$i]['1']?" +".number_format($arrOpt5[$i]['1']):""?></p>
										</td>
										<td>
											<div class="quantity">
												<p class="num"><input type="text" id="qty_<?=$i?>" name="qty_<?=$i?>" value="<?=$arrList["list"][$i]['qty']?>" readonly onfocus="blur()" /></p>
												<div class="control">
													<a href="javascript:incAmount2(<?=$i?>)" class="btn_white">+</a>
													<a href="javascript:decAmount2(<?=$i?>)" class="btn_white">-</a>
												</div>
												<a href="javascript:updateCart('<?=$arrList["list"][$i]['c_idx']?>',frmCartList.qty_<?=$i?>.value);" class="btn_gray">수정</a>
											</div>
										</td>
										<td><?=number_format(($arrList["list"][$i]['price']*$arrList["list"][$i]['qty'])+($optionPrice * $arrList["list"][$i]['qty']))?>원</td>
										<!-- <td>
											<p class="mb5">할인세일쿠폰</p>
											<a href="#" class="btn_gray">쿠폰변경</a>
										</td>
										<td>70,000원</td> -->
										<td><?=number_format($thisPoint)?></td>
										<td>
											<!-- <a href="javascript:updateCart('<?=$arrList["list"][$i]['c_idx']?>',frmCartList.qty_<?=$i?>.value);" class="btn_gray2 mb2 disB">수정</a>  -->
											<a href="javascript:deleteCart('<?=$arrList["list"][$i]['c_idx']?>');" class="btn_black disB">삭제</a>
										</td>
									</tr>
									<?	
										}
										//배송비 계산
										if($totalPrice < $_SITE["SHOP"]["SHIP"]["FREE_PRICE"]){
											$shipPrice = $_SITE["SHOP"]["SHIP"]["SHIP_PRICE"];
										}else{
											$shipPrice = 0;
										}
									}else{
									?>
									<tr height="100">
										<td colspan="5" align="center">장바구니가 비었습니다.</td>
									</tr>
									<?}?>	
								</tbody>
							</table>
						</div>
						<!-- //blist --> 

						<div class="orderBox" >
							<div class="box">
								<p class="tit1"></p>
								
							</div>
							<div class="box">
								<p class="tit">상품금액</p>
								<div class="price"><?=number_format($totalPrice)?>원</div>
							</div>
							<!-- //box -->
							
							<div class="box">
								<p class="tit">배송비</p>
								<div class="price"><span>+</span><?=number_format($shipPrice)?>원</div>
							</div>
							<!-- //box -->

							<!--<div class="box">
								<p class="tit">쿠폰/상품권</p>
								<div class="price"><span>-</span>4,000원</div>
							</div>
							 //box -->

							<div class="box payment">
								<p class="tit">최종결제금액</p>
								<div class="price"><?=number_format($totalPrice+$shipPrice)?>원</div>
							</div>
							<!-- //box -->
						</div>
						<!-- //orderBox -->
					</div>	
					<!-- //cartCon -->

					<div class="btnR">
						<a href="javascript:orderCartChecked(document.frmCartList);" class="btn_brown_diagonal">선택상품 구매</a>
						<a href="javascript:orderCartAll(document.frmCartList);" class="btn_brown">전체상품 구매</a>
						<a href="/shop.php?goPage=GoodList&cat_no=18" class="btn_black2">쇼핑 계속하기</a>
						<a href="javascript:deleteCartChecked(document.frmCartList);" class="btn_white_diagonal">장바구니 비우기</a>
					</div>
					</form>
					
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
