<?//로그인확인
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/giftcard/giftcard.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrOrder1 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "1", 0, 0);	//주문접수,입금
$arrOrder6 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "6", 0, 0);	//결제완료
$arrOrder7 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "7", 0, 0);	//출고준비
$arrOrder8 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "8", 0, 0);	//배송중
$arrOrder9 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "9", 0, 0);	//배송완료

$arrOrder2 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "2,3", 0, 0);	//취소
$arrOrder4 = getOrderList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "", "", "4,5", 0, 0);	//교환/반품

$arrCoupon = getMypageCouponList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "Y", 0, 0);
$arrGiftcard = getMypageGiftcardList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], "Y", 0, 0);

$arrBoard1 = getOneToOneList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], 6, 0);
$arrBoard2 = getBoardListBase("qna", "", "u_id", $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], 6, 0);
$arrBoard3 = getBoardListBaseNFile("after", "", "userid", $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], 1, 0);

$nowPoint = getNowPoint($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

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
					<p class="local"><span class="home"></span><span class="current">마이페이지</span></p>
				</div>
				<!-- //location -->
				<h2>마이페이지</h2>
				<div class="mypageCon">
					<h3>진행 중인 주문</h3>
					<div class="orderIng">
						<ul class="orderly">
							<li class="li01">입금대기 <span><?=number_format($arrOrder1["total"])?></span></li> 
							<li class="li02">결제완료  <span><?=number_format($arrOrder6["total"])?></span></li> 
							<li class="li03">상품준비중 <span><?=number_format($arrOrder7["total"])?></span></li>  
							<li class="li04">배송  <span><?=number_format($arrOrder8["total"])?></span></li>    
							<li class="li05">배송완료<span><?=number_format($arrOrder9["total"])?></span></li> 
						</ul>
						<ul class="deal">
							<li>취소 <span><?=number_format($arrOrder2["total"])?>건</span></li>
							<li>교환/반품 <span><?=number_format($arrOrder4["total"])?>건</span></li>
						</ul>
					</div>
					<!-- //orderIng -->
					<p class="helpEx mb40">* 구매확정이 완료된 주문은 진행중인 주문에 포함되지 않으며 진행상태에 따라 배송지 변경, 취소, 교환, 반품신청이 가능합니다.</p>

					<div class="details">
						<div class="areaL">
							<a href="/shop.php?goPage=WishList">
								<div class="interest">
									<p class="tit">관심상품</p>
									<p class="t01">나의 관심 품목만을 <br />
									모아 보다 쉽게 확인이<br />
									가능합니다.</p>
								</div>
							</a>
							<a href="/shop.php?goPage=OrderList">
								<div class="orderCheck">
									<p class="tit">전체 주문내역확인</p>
								</div>
							</a>
						</div>
						<!-- //areaL -->
						<div class="areaC">
							<a href="/shop.php?goPage=Cart">
								<div class="basket">
									<p class="tit">장바구니</p>
									<p class="t01">상품 구매를 쉽고 <br />
									빠르게 도와 드립니다.</p>
								</div>
							</a>
							<div class="info">
								<ul>
									<li class="modify"><a href="/member.php?goPage=Edit">회원정보수정</a></li>
									<li class="addr"><a href="/shop.php?goPage=Address">배송주소록</a></li>
								</ul>
							</div>
						</div>
						<!-- //areaC -->
						<div class="areaR">
							<p class="tit">쿠폰/상품권/적립금 현황</p>
							<p>상품을 보다 싸고, 저렴하게 구입하는 기회를 <br />잡으세요!</p>
							<ul>
								<li>쿠폰현황<span><?=number_format($arrCoupon["total"])?>개</span></li>
								<li>상품권현황<span><?=number_format($arrGiftcard["total"])?>개</span></li>
								<li>적립금<span><?=number_format($nowPoint[nowpoint])?></span></li>
							</ul>
						</div>
						<!-- //areaR -->
					</div>
					<!-- //details -->

					<h3>최근 문의사항</h3>
					<div class="inquire">
						<div class="box">
							<p class="tit">1:1문의</p>
							<ul class="list">
								<?
								if($arrBoard1["total"]>0){
								for($i=0;$i<$arrBoard1["list"]["total"];$i++){
								?>
								<li><a href="/shop.php?goPage=MyQnaView&idx=<?=$arrBoard1["list"][$i]['idx']?>"><?=$arrBoard1["list"][$i]['status']=="Y"?"<span class='answer completeAnswer'>답변완료":"<span class='answer waitingAnswer'>답변대기"?></span><span class="writing"><?=stripslashes($arrBoard1["list"][$i]['subject'])?></span></a></li>
								<?}}?>
							</ul>
							<a href="/shop.php?goPage=MyQna" class="more"><img src="/img/icon_more.gif" alt="더보기" /></a>
						</div>
						<!-- //box -->
						<div class="box boxC">
							<p class="tit">나의 상품문의</p>
							<ul class="list">
								<?
								//DB연결
								$dblink = SetConn($_conf_db["main_db"]);

								if($arrBoard2["total"]>0){
								for($i=0;$i<$arrBoard2["list"]["total"];$i++){
									$arrAnser = getArticleList("tbl_board_qna", 0, 0, "WHERE main='".$arrBoard2["list"][$i]['main']."' ");
								?>
								<li><a href="/mypage/inquiry/productQna.php?boardid=qna&mode=view&idx=<?=$arrAnser["total"]>"1"?$arrAnser["list"][1][idx]:$arrBoard2["list"][$i]['idx']?>"><?=$arrAnser["total"]>"1"?"<span class='answer completeAnswer'>답변완료":"<span class='answer waitingAnswer'>답변대기"?></span><span class="writing"><?=stripslashes($arrBoard2["list"][$i]['subject'])?></span></a></li>
								<?}}
								//DB해제
								SetDisConn($dblink);
								?>
							</ul>
							<a href="/mypage/inquiry/productQna.php" class="more"><img src="/img/icon_more.gif" alt="더보기" /></a>
						</div>
						<!-- //box -->
						<div class="box reviews">
							<p class="tit">나의 상품후기</p>
							<?
							//DB연결
							$dblink = SetConn($_conf_db["main_db"]);

							if($arrBoard3["total"]>0){
							for($i=0;$i<$arrBoard3["list"]["total"];$i++){
								$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $arrBoard3["list"][$i]['etc_1']));
							?>
							<a href="/mypage/inquiry/post.php?boardid=after&mode=view&idx=<?=$arrBoard3["list"][$i]['idx']?>">
								<div class="in">
									<div class="pic"><img src="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo['list'][0]['image_s']?>" width="78" /></div>
									<div class="txt">
										<dl>
											<dt><?=stripslashes($arrInfo["list"][0]['g_name'])?></dt>
											<dd></dd>
										</dl>
										<p><?=stripslashes($arrBoard3["list"][$i]['contents'])?></p>
									</div>
									<!-- //txt -->
								</div>
								<!-- //in -->
								<p class="day"><span class="star"><? for($j=0; $j<$arrBoard3["list"][$i]['etc_3'];$j++) { echo "★"; }
								for($k=0; $k<5-$arrBoard3["list"][$i]['etc_3'];$k++) { echo "☆"; }?></span><?=str_replace("-",".",substr($arrBoard3["list"][$i]['wdate'],0,10))?></p>
							</a>
							<?}}
							//DB해제
							SetDisConn($dblink);
							?>
							<a href="/mypage/inquiry/post.php" class="more"><img src="/img/icon_more.gif" alt="더보기" /></a>
						</div>
						<!-- //box -->
					</div>
					<!-- //inquire -->
				</div>
				<!-- //mypageCon -->
					
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
