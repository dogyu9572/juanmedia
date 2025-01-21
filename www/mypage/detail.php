<?php include("../inc/header.php"); ?>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/module/member/auth.php";

include_once $_SERVER['DOCUMENT_ROOT']."/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], "edu_applicants");

$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"read");
$arrBoardEduArticle = getBoardArticleView("edu", $_GET["category"], $arrBoardArticle["list"][0]["edu_idx"],"read");
$imgsrc = "/uploaded/board/edu/".$arrBoardEduArticle["files"][0]['re_name'];

?>

		<!-- Container -->
		<div class="container sub" id="container">

			<!-- subTopBg -->
			<div class="subTopBg myPage">
				<div class="inner">
					<div class="enName">MY PAGE</div>
					<div class="korName">마이페이지</div>
					<div class="lnb">
						<a href="/"><img src="/images/ico_home.svg" alt="home"></a>
						<div class="lnbSub">
							<div class="tit">마이페이지</div>
							<ul>
								<li><a href="/edu/info.php">미디어교육</a></li>
								<li><a href="/equ/info.php">장비대여</a></li>
								<li><a href="/place/info.php">공간대관</a></li>
								<li><a href="/media/info.php">미디어체험</a></li>
								<li><a href="/center/intro.php">센터안내</a></li>
								<li><a href="/cm/notice.php">게시판</a></li>
							</ul>
						</div>
						<div class="lnbSub">
							<div class="tit">신청 내역</div>
							<ul>
								<li><a href="freeList.php">나의 활동 관리</a></li>
								<li><a href="edit.php">나의 정보 관리</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- //subTopBg -->

			<!-- subSec -->
			<div class="subSec p80 last">
				<div class="mySide inner">
					
					<div class="menu">
						<div class="inMenu">
							<div class="box">
								<div class="tit">신청 내역</div>
								<ul>
									<li><a href="orderList.php" class="active">교육신청</a></li>
									<li><a href="orderListEq.php">장비대여</a></li>
									<li><a href="orderListPlace.php">공간대여</a></li>
									<li><a href="orderListVideo.php">상영회</a></li>
								</ul>
							</div>
							<div class="box">
								<div class="tit">나의 활동 관리</div>
								<ul>
									<li><a href="freeList.php">자유게시판</a></li>
									<li><a href="stopList.php">자격 정지 내역</a></li>
								</ul>
							</div>
							<div class="box">
								<div class="tit">나의 정보 관리</div>
								<ul>
									<li><a href="edit.php">회원정보 수정</a></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="rightCont">
						<div class="bigTit">교육신청 상세</div>

						<div class="myDetail">
							<div class="detailTit">신청정보 <a href="/mypage/print.php" target="_blank" class="more">수료증 확인</a></div>
							<div class="detailTable">
								<div class="line">
									<div class="th">접수번호</div>
									<div class="td"><?=$arrBoardArticle["list"][0]["app_no"]?></div>
								</div>
								<div class="line">
									<div class="th">신청상태</div>
									<div class="td">
                                        <?=$arrBoardArticle["list"][0]["status"]?> <?=$arrBoardArticle["list"][0]["status"] == "신청완료" ?  ' <a href="#;" class="btnTypeSm" onclick="contentPop(\'.cancelPop\');">신청 취소</a>' : "" ?>
									</div>
								</div>
								<div class="line">
									<div class="th">결제금액</div>
									<div class="td"><?=number_format($arrBoardArticle["list"][0]['finalamount'])?>원 <button type="button" class="btnTypeSm" onclick="contentPop('.paymentPop');">입금 확인 요청</button></div>
								</div>
								<!-- <div class="line">
									<div class="th">결제방법</div>
									<div class="td">신용카드</div>
								</div> -->
							</div>
						</div>
						<div class="myDetail">
							<div class="detailTit">예약자 정보</div>
							<div class="detailTable">
								<div class="line">
									<div class="th">이름</div>
									<div class="td"><?=$arrBoardArticle["list"][0]["name"]?></div>
								</div>
								<div class="line">
									<div class="th">휴대폰번호</div>
									<div class="td"><?=$arrBoardArticle["list"][0]["tel"]?></div>
								</div>
								<div class="line">
									<div class="th">이메일주소</div>
									<div class="td"><?=$arrBoardArticle["list"][0]["email"]?></div>
								</div>
							</div>
						</div>

						<div class="myDetail">
							<div class="detailTit">교육 정보</div>
							
							<div class="detailWrap">
								<div class="simpleView">
									<div class="simpleBox">
										<div class="img"><img src="<?=$imgsrc?>" alt="섬네일"></div>
										<div class="textWrap">
											<div class="title"><?=$arrBoardEduArticle["list"][0]["subject"]?></div>
											<div class="info">
												<div class="tit">교육기간</div>
												<div class="txt"><?=$arrBoardEduArticle["list"][0]["e_start_date"]?> ~ <?=$arrBoardEduArticle["list"][0]["e_end_date"]?></div>
											</div>
											<div class="info">
												<div class="tit">구분</div>
												<div class="txt"><?=getCategoryName($arrBoardEduArticle["list"][0]['category1'])?> / <?=getCategoryName($arrBoardEduArticle["list"][0]['category2'])?></div>
											</div>
											<div class="info">
												<div class="tit">수강료</div>
												<div class="txt"><?=number_format($arrBoardEduArticle["list"][0]['fee'])?>원</div>
											</div>
											<div class="info">
												<div class="tit">교육장소</div>
												<div class="txt"><?=$arrBoardEduArticle["list"][0]["education_name"]?></div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>

						<div class="btnCenter">
							<a href="orderList.php" class="btnType1 black list">목록</a>
						</div>

					</div> 


				</div>
			</div>
			<!-- //subSec -->

		</div>
		<!-- //Container -->

<!-- 컨텐츠팝업 -->
<div class="contentPop cancelPop">
    <div class="bg"></div>
    <div class="popIn">
        <div class="content">
            <div class="popTit">취소사유</div>
            <form name="form1" method="post" action="/module/board/board_evn.php" >
                <input type="hidden" name="boardid" value="<?=$arrBoardInfo["list"][0]["boardid"]?>">
                <input type="hidden" name="returnURL" value="/mypage/detail.php?idx=<?=$arrBoardArticle["list"][0]["idx"]?>">
                <input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
                <input type="hidden" name="evnMode" value="cancel">
                <div class="cancelBox">
                    <textarea name="etc_1" class="baseTextarea" placeholder="취소 사유를 간단히 입력해주세요. (최대 100글자) "></textarea>
                </div>
                <div class="btnCenter">
                    <button type="submit" class="btnType1 black list">확인</button>
                </div>
            </form>
            <div class="closePop">
                <a href="javascript:;" onclick="contentClose()">팝업닫기</a>
            </div>
        </div>
    </div>
</div>
<!-- //컨텐츠팝업 -->

<!-- 결제금액팝업 -->
<div class="contentPop paymentPop">
	<div class="bg"></div>
	<div class="popIn">
		<div class="content">
			<div class="popTit">입금 확인 요청</div>
			<div class="cancelBox">
				<dl>
					<dt>입금금액</dt>
					<dd><input type="text"></dd>
				</dl>
				<dl>
					<dt>입금자</dt>
					<dd><input type="text"></dd>
				</dl>
				<dl>
					<dt>입금일자</dt>
					<dd><input type="text" class="datepicker"></dd>
				</dl>
			</div>
			<div class="btnCenter">
				<a href="#;" onclick="contentClose()" class="btnType1 black list">확인</a>
			</div>
			<div class="closePop">
				<a href="javascript:;" onclick="contentClose()">팝업닫기</a>
			</div>
		</div>
	</div>
</div>
<!-- //결제금액팝업 -->

<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

	</div>
	<!-- //Wrap -->

<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.svg",
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});
});
//]]>
</script>

</body>
</html>