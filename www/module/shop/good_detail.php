<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

//현재 상품정보 가져오기
if($_REQUEST['idx']){
	$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));
}else{
	$arrInfo = getGoodInfoGcode(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_code']));
}

//카테고리 정보
if($_REQUEST['cat_no']) {
	$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));
	$arrCatCode = explode("/", $arrCategoryInfo["list"][0]["cat_code"]);
} else {
	$arrCatCode = explode("/", $arrInfo["list"][0]["cat_code"]);
}

//존재여부 검사
if($arrInfo["total"]==0){
	jsMsg("존재하지 않는 상품입니다.");
	jsHistory();
}

//진열여부 검사
if($arrInfo["list"][0]['is_show']=="N"){
	jsMsg("진열중인 상품이 아닙니다.");
	jsHistory();
}

$arrQna = getBoardListBase("qna", mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']), "", "", 0, 0);
$arrAfter = getBoardListBase("after", "", "e1", mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']), 0, 0);

//상품 조회수 증가
setGoodHitsUpdate($arrInfo["list"][0]["idx"]);

//DB해제
SetDisConn($dblink);

//오늘본 상품 세션에 추가
if(is_array($_SESSION[$_SITE["DOMAIN"]]["SHOP"]["VIEW"])){
	if(!in_array(array($arrInfo["list"][0]['idx'],$arrInfo["list"][0]['image_s']),$_SESSION[$_SITE["DOMAIN"]]["SHOP"]["VIEW"])){
		$_SESSION[$_SITE["DOMAIN"]]["SHOP"]["VIEW"][] = array($arrInfo["list"][0]['idx'],$arrInfo["list"][0]['image_s']);
	}
	if(sizeof($_SESSION[$_SITE["DOMAIN"]]["SHOP"]["VIEW"]) > 60){
		array_shift($_SESSION[$_SITE["DOMAIN"]]["SHOP"]["VIEW"]);
	}
}else{
	$_SESSION[$_SITE["DOMAIN"]]["SHOP"]["VIEW"][] = array($arrInfo["list"][0]['idx'],$arrInfo["list"][0]['image_s']);
}
?>
<script>
function incAmount(){
	var qty = document.frmGoodDetail.qty.value;
	document.frmGoodDetail.qty.value = ++qty;

	setOptPrice();
}

// 수량 감소
function decAmount(){
   var qty = document.frmGoodDetail.qty.value;
	if(qty > 1)
		document.frmGoodDetail.qty.value = --qty;

	setOptPrice();
}
</script>


	<div id="sub_container">
		<div class="content">

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/include/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="con">
				<!-- 내용 : s -->
					<div class="location">
						<p class="local"><span class="home"></span><span class="route"><?=$arrAllCategory[$arrCatCode[0]]?></span><span class="current"><?=$arrAllCategory[$arrCatCode[1]]?></span></p>
					</div>
					<!-- //location -->
					
					<div class="productRead">
						<div class="info">
							<!-- 이미지 -->
							<div class="thumbnail">
								<div id="productRead_thumbnail" class="sliderkit photosgallery-std">
									<!-- 작은썸네일 -->
									<div class="sliderkit-nav">
										<div class="sliderkit-nav-clip">
											<ul>
												<?
												if($arrInfo["total_files"]>0){
												for($i=0; $i < $arrInfo["total_files"]; $i++){
												?>
												<li><a href="#"><img src="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/s_<?=$arrInfo["files"][$i]['re_name']?>" alt="" /></a></li>
												<?}}?>
											</ul>
										</div>
									</div>
									<!-- //sliderkit-nav -->
									<!-- 큰썸네일 -->
									<div class="sliderkit-panels">
										<div class="sliderkit-btn sliderkit-go-btn sliderkit-go-prev"><a rel="nofollow" href="#" title="Previous"><span>Previous</span></a></div>
										<div class="sliderkit-btn sliderkit-go-btn sliderkit-go-next"><a rel="nofollow" href="#" title="Next"><span>Next</span></a></div>
										<?
										if($arrInfo["total_files"]>0){
										for($i=0; $i < $arrInfo["total_files"]; $i++){
										?>
										<div class="sliderkit-panel"><img src="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["files"][$i]['re_name']?>" width="380" height="380" alt="" /></div>
										<?}}?>
									</div>
									<!-- //sliderkit-panels -->
								</div>
								<!-- //productRead_thumbnail -->    
							</div>
							<!-- //thumbnail -->
								
							<!-- 설명 -->
							<div class="detail">
								<form id="frmGoodDetail" name="frmGoodDetail">
								<input type="hidden" id="basicPrice" name="basicPrice" value="<?=$arrInfo["list"][0]['price']?>">
								<input type="hidden" id="basicPoint" name="basicPoint" value="<?=$arrInfo["list"][0]['point_unit']=="P"?($arrInfo["list"][0]['point']*$arrInfo["list"][0]['price']/100):$arrInfo["list"][0]['point']?>">
								<p class="tit"><span><?=stripslashes($arrInfo["list"][0]['g_code'])?></span><?=stripslashes($arrInfo["list"][0]['g_name'])?></p>
								<table>					
									<colgroup>
										<col width="130px" />
										<col width="*" />
									</colgroup>
									<tbody>
										<tr>
											<th scope="row">판매가</th>
											<td><p class="sellingPrice"><span class="sale"><span class="through"><?=number_format($arrInfo["list"][0]['sale_price'])?></span></span><?=number_format($arrInfo["list"][0]['price'])?>원</p></td>
										</tr>
										<!--tr>
											<th scope="row">적립금</th>
											<td><?=$arrInfo["list"][0]['point_unit']=="P"?number_format(($arrInfo["list"][0]['point']*$arrInfo["list"][0]['price'])/100):number_format($arrInfo["list"][0]['point'])?> 원</td>
										</tr>
										<tr>
											<th scope="row" class="lineTop">배송비</th>
											<td class="lineTop"><?=$arrInfo["list"][0]['price']>$_SITE["SHOP"]["SHIP"]["FREE_PRICE"]?"무료배송":number_format($_SITE["SHOP"]["SHIP"]["SHIP_PRICE"])."원"?></td>
										</tr>
										<tr>
											<th scope="row">배송정보</th>
											<td>
												<p><?=substr($_SITE["SHOP"]["SHIP"]["FREE_PRICE"],0,1)?>만원이상 / 무료배송</p>
												<p><?=substr($_SITE["SHOP"]["SHIP"]["FREE_PRICE"],0,1)?>만원미만 / 배송비 : <?=number_format($_SITE["SHOP"]["SHIP"]["SHIP_PRICE"])?>원</p>
												<p>* 쿠폰등을 적용한 기준이 아닌 최초 명시된 금액 기준</p>
											</td>
										</tr>
										<tr>
											<th scope="row">배송기간</th>
											<td><?=stripslashes($arrInfo["list"][0]['isbn'])?></td>
										</tr>
										<tr>
											<th scope="row">교환/반품정보</th>
											<td>
												<?=nl2br(stripslashes($arrInfo["list"][0]['memo']))?>
											</td>
										</tr>
										<tr>
											<th scope="row" class="lineTop">제조사</th>
											<td class="lineTop"><?=stripslashes($arrInfo["list"][0]['madein'])?></td>
										</tr>
										<tr>
											<th scope="row">제조국</th>
											<td><?=stripslashes($arrInfo["list"][0]['vendor'])?></td>
										</tr-->
										<? if($arrInfo["total_opt"] > 0){?>
										<? for($i=0;$i<$arrInfo["total_opt"];$i++){?>
										<tr>
											<th scope="row" class="lineTop"><span class="pt5 disInb"><?=stripslashes($arrInfo["opt"][$i]["opt_1"])?></span></th>
											<td class="lineTop">
												<input type="hidden" id="pre_opt_1" name="pre_opt_1">
												<input type="hidden" id="pre_opt_2" name="pre_opt_2" value="0">
												<? if($arrInfo["total_opt"] > 1 && $i==0) {?>
												<select id="opt_contents_<?=$i?>" name="opt_contents_<?=$i?>" onchange="preOpt(<?=$arrInfo["list"][0]['price']?>, this.value);" style="width:300px">
												<?} else {?>
												<select id="opt_contents_<?=$i?>" name="opt_contents_<?=$i?>" onchange="setAddOpt(<?=$arrInfo["list"][0]['price']?>, this.value, '<? if($arrInfo["total_opt"] >1) { echo "Y"; } ?>');"  style="width:300px">
												<?}?>
													<option value="">선택</option>
													<?
													for($j=0;$j<$arrInfo["total_opt_info"];$j++){
														if($arrInfo["opt"][$i]["opt_1"]==$arrInfo["opt_info"][$j]["opt_1"]){
													?>
														<option value="<?=$arrInfo["opt_info"][$j]["opt_1_value"]?>|<?=$arrInfo["opt_info"][$j]["price"]?>"><?=$arrInfo["opt_info"][$j]["opt_1_value"]?> <?if($arrInfo["opt_info"][$j]["price"]>0){?>(+<?=number_format($arrInfo["opt_info"][$j]["price"])?>)<?}?>
														<?if($arrInfo["opt_info"][$j]["price"]<0){?>(<?=number_format($arrInfo["opt_info"][$j]["price"])?>)<?}?></option>
													<?
														}
													}
													?>
												</select>	
											</td>
										</tr>
										<?}}?>
										<? if($arrInfo["total_opt"]> 0){?>
										<tr>
											<th scope="row" colspan="2" class="lineTop">선택사항</th>
										</tr>
										<tr>
											<td colspan="2">
												<table id="addOptDivBody">
													<TBODY>
													</TBODY>
												</table>
											</td>
										</tr>
										<?} else {?>
										<tr>
											<td colspan="2" class="lineTop">
												<div class="choose">
													<p class="name">수량</p>
													<div class="quantity">
														<p class="num"><input type="text" id="qty" name="qty" value="1" readonly onfocus="blur()" /></p>
														<div class="control">
															<a href="javascript:incAmount();" class="btn_white">+</a>
															<a href="javascript:decAmount();" class="btn_white">-</a>
														</div>
														<!-- //control -->
													</div>
												</div>
												<!-- //choose -->
											</td>
										</tr>
										<?} ?>
									</tbody>
								</table>
								<div class="total">
									<!-- <span class="amount">총 수량 1개</span> -->  
									<span class="sum">총 상품금액<span class="price" id="divPrice"><?=number_format($arrInfo["list"][0]['price'])?>원</span></span>
								</div>
								<!-- //total -->
								<div class="btnR">
									<? if($arrInfo["total_opt"]> 0){?>
									<a href="javascript:buyOptDirect('<?=$arrInfo["list"][0]['idx']?>')" class="btn_brown"><span class="icon_buyNow">바로구매</span></a>
									<a href="javascript:addOptCart('<?=$arrInfo["list"][0]['idx']?>')" class="btn_black2"><span class="icon_basket">장바구니</span></a>
									<?}else{?>
									<a href="javascript:buyDirect('<?=$arrInfo["list"][0]['idx']?>', document.frmGoodDetail.qty.value)" class="btn_brown"><span class="icon_buyNow">바로구매</span></a>
									<a href="javascript:addCart('<?=$arrInfo["list"][0]['idx']?>', document.frmGoodDetail.qty.value)" class="btn_black2"><span class="icon_basket">장바구니</span></a>
									<?}?>
									<a href="javascript:addWish('<?=$arrInfo["list"][0]['idx']?>','<?=$_SERVER['REQUEST_URI']?>')" class="btn_white_diagonal"><span class="icon_interest">관심상품등록</span></a>
								</div>
								<!-- //btnR -->
								</form>
							</div>
							<!-- //detail -->					
						</div>
						<!-- //info -->

						<div class="productTabs" id="detail01">
							<ul>
								<li class="first on"><a href="#detail01">상세정보</a></li>
								<li><a href="#detail02">상품평 (<span class="num"><?=number_format($arrAfter["list"]["total"])?></span>)</a></li>
								<li><a href="#detail03">상품 Q&A (<span class="num"><?=number_format($arrQna["list"]["total"])?></span>)</a></li>
								<li><a href="#detail04">배송ㆍ반품 / 상품고시</a></li>
							</ul>
						</div>
						<!-- //tabs --> 
						
						<div class="productTabsDetail">
							<!-- 상세정보 -->
							<div class="detail">
								<?=stripslashes($arrInfo["list"][0]["contents"])?>
							</div>
							<!-- //상세정보 -->

							<!-- ================================================================ -->
							<div class="productTabs" id="detail02">
								<ul>
									<li class="first"><a href="#detail01">상세정보</a></li>
									<li class="on"><a href="#detail02">상품평 (<span class="num"><?=number_format($arrAfter["list"]["total"])?></span>)</a></li>
									<li><a href="#detail03">상품 Q&A (<span class="num"><?=number_format($arrQna["list"]["total"])?></span>)</a></li>
									<li><a href="#detail04">배송ㆍ반품 / 상품고시</a></li>
								</ul>
							</div>
							<!-- //tabs --> 
							<!-- 상품평 -->
							<div class="detail" id="ListAfter">
							</div>
							<!-- //상품평 -->
			
							<!-- ================================================================ -->
							<div class="productTabs" id="detail03">
								<ul>
									<li class="first"><a href="#detail01">상세정보</a></li>
									<li><a href="#detail02">상품평 (<span class="num"><?=number_format($arrAfter["list"]["total"])?></span>)</a></li>
									<li class="on"><a href="#detail03">상품 Q&A (<span class="num"><?=number_format($arrQna["list"]["total"])?></span>)</a></li>
									<li><a href="#detail04">배송ㆍ반품 / 상품고시</a></li>
								</ul>
							</div>
							<!-- //tabs --> 
							<!-- 상품 Q&A -->
							<div class="detail" id="ListQna">	
							</div>
							<!-- //상품 Q&A -->

							<!-- ================================================================ -->
							<div class="productTabs" id="detail04">
								<ul>
									<li class="first"><a href="#detail01">상세정보</a></li>
									<li><a href="#detail02">상품평 (<span class="num"><?=number_format($arrAfter["list"]["total"])?></span>)</a></li>
									<li><a href="#detail03">상품 Q&A (<span class="num"><?=number_format($arrQna["list"]["total"])?></span>)</a></li>
									<li class="on"><a href="#detail04">배송ㆍ반품 / 상품고시</a></li>
								</ul>
							</div>
							<!-- //tabs --> 
							<!-- 배송ㆍ반품 / 상품고시 -->
							<div class="detail delivery">
								<p class="tit">전자상거래 등에서의 상품정보제공고시</p>
								<div class="bread">
									<table>
										<colgroup>
											<col width="25%" />
											<col width="*" />
										</colgroup>
										<tbody>
											<tr>
												<th scope="row">색  상</th>
												<td><?=stripslashes($arrInfo["list"][0]['pan_color'])?></td>
											</tr>
											<tr>
												<th scope="row">제조자</th>
												<td><?=stripslashes($arrInfo["list"][0]['madein'])?></td>
											</tr>
											<tr>
												<th scope="row">크기</th>
												<td><?=stripslashes($arrInfo["list"][0]['pages'])?></td>
											</tr>
											<tr>
												<th scope="row">배송/설치비용</th>
												<td><?=stripslashes($arrInfo["list"][0]['mokcha'])?></td>
											</tr>
											<tr>
												<th scope="row">제조국</th>
												<td><?=stripslashes($arrInfo["list"][0]['vendor'])?></td>
											</tr>
											<tr>
												<th scope="row">주요 소재</th>
												<td><?=stripslashes($arrInfo["list"][0]['author_text'])?></td>
											</tr>
											<tr>
												<th scope="row">품질보증기준</th>
												<td><?=stripslashes($arrInfo["list"][0]['movie_url'])?></td>
											</tr>
											<tr>
												<th scope="row">A/S책임자와 전화번호</th>
												<td><?=stripslashes($arrInfo["list"][0]['author_name'])?></td>
											</tr>
											<tr>
												<th scope="row">KC 인증 필 유무</th>
												<td><?=stripslashes($arrInfo["list"][0]['published_text'])?></td>
											</tr>
											<tr>
												<th scope="row">구성품 </th>
												<td><?=stripslashes($arrInfo["list"][0]['brand'])?></td>
											</tr>
											<tr>
												<th scope="row">품명</th>
												<td><?=stripslashes($arrInfo["list"][0]['model'])?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<!-- //bread --> 
							</div>
							<!-- //배송ㆍ반품 / 상품고시 -->
						</div>
						<!-- //tabsView --> 
					</div>
					<!-- //productRead -->

				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
	

<script language="javascript" type="text/javascript">
<!--
var loadingString = "<div style='text-align:center; padding:200px 0;'><img src='/img/loadings.gif' /></div>";

function listLoad(option){
	$("#ListAfter").html(loadingString);
	var data = "&sw=e&sk=<?=$arrInfo["list"][0]["idx"]?>&"+option;
	$.ajax({
	  type: "GET",
	  url: "/module/shop/ajax_review.php",
			data: data,
			success: function(msg){
				$("#ListAfter").html(msg);
	  },
	  error:function(request,status,error){
				alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	  }
   });
}

var loadingString2 = "<div style='text-align:center; padding:200px 0;'><img src='/img/loadings.gif' /></div>";

function listLoad2(option){
	$("#ListQna").html(loadingString);
	var data = "&category=<?=$arrInfo["list"][0]["idx"]?>&"+option;
	$.ajax({
	  type: "GET",
	  url: "/module/shop/ajax_qna.php",
			data: data,
			success: function(msg){
				$("#ListQna").html(msg);
	  },
	  error:function(request,status,error){
				alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	  }
   });
}

$(document).ready(function(){
		listLoad('');
		listLoad2('');
});

  //-->
</script>
