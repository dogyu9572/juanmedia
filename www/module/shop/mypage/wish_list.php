<?
//로그인확인
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getWishList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$scale,$_REQUEST['offset']);

//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);
?>

<div id="sub_container">
		<div class="content">

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/mypage/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="con">
				<!-- 내용 : s -->
				<div class="location">
					<p class="local"><span class="home"></span><span class="route">마이페이지</span><span class="current">관심상품</span></p>
				</div>
				<!-- //location -->
				<h2>관심상품</h2>
				<div class=" favoriteCon">
					<h3>관심상품 (<?=number_format($arrList["total"])?>)</h3>
					
					<div class="blist">
						<table>
							<colgroup>
								<col width="50px" />
								<col width="*" />
								<col width="150px" />
								<col width="150px" />
							</colgroup>
							<thead>
								<tr>
									<th scope="col" colspan="2">상품정보</th>
									<th scope="col">상품금액</th>
									<th scope="col">관리</th>
								</tr>
							</thead>
							<tbody>
								<?
								if($arrList["total"]>0){
									for($i=0;$i<$arrList["total"];$i++){
										//적립금계산
										if($arrList["list"][$i]['point_unit']=="P"){
											$thisPoint = ($arrList["list"][$i]['point']*$arrList["list"][$i]['price'])/100;
										}else{
											$thisPoint = $arrList["list"][$i]['point'];
										}

										//추가금액 계산
										$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'];

										//합계금액 계산
										$totalPrice += $arrList["list"][$i]['price'];
								?>
								<tr>
									<td><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&idx=<?=$arrList["list"][$i]['idx']?>"><img src="/uploaded/shop_good/<?=$arrList["list"][$i]['idx']?>/<?=$arrList["list"][$i]['image_m']?>" width="80" alt="" /></a></td>
									<td class="tl">
										<p class="name"><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&idx=<?=$arrList["list"][$i]['idx']?>">[<?=$arrList["list"][$i]['g_code']?>] <br /><?=stripslashes($arrList["list"][$i]['g_name'])?></a></p>
									</td>
									<td><?=number_format($arrList["list"][$i]['price'])?>원</td>
									 <td>
										<!-- <p><a href="javascript:addCart('<?=$arrList['list'][$i]['g_idx']?>', '1');" class="btn_brown3">장바구니</a></p> -->
										<p><a href="javascript:deleteWish('<?=$arrList["list"][$i]['c_idx']?>');" class="btn_white_diagonal2">삭제하기</a></p>
									</td>
								</tr>
								<?
									}
								}else{
								?>
								<tr height="100">
									<td colspan="9" align="center">관심 상품이 없습니다.</td>
								</tr>
								<?}?>	
							</tbody>
						</table>
					</div>
					<!-- //blist -->
					<div class="btnAll">
						<div class="paging">
							<?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"")?>
						</div>
						<!--//paging --> 
						<!-- //btnR -->
					</div>
					<!-- //btnAll -->
				</div>
				<!-- //favoriteCon -->
					
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
