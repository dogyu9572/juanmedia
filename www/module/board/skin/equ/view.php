<?if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] && $_SERVER["PHP_SELF"]=="/backoffice/module/board/board_view.php"){
if(!in_array("biz_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;
###################################################### 관리자 페이지 ######################################################?>
<script language="javascript">
function fileDownload(boardid,b_idx,idx){
	obj = window.open("/module/board/download.php?boardid="+boardid+"&b_idx="+b_idx+"&idx="+idx,"urlCheck","width=100,height=100,menubars=0, toolbars=0");
}
<?
//댓글 사용시
if($arrBoardInfo["list"][0]["usememo"]=="Y"){
?>
function checkComment(frm){
	<?if(!$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){?>
	alert("로그인을 하셔야 댓글입력이 가능합니다.");
	return false;

	<?}else if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["replylevel"]){?>
	if (frm.comment.value==""){
		alert("댓글 내용을 입력해 주세요.");
		frm.comment.focus();
		return false;
	}
	<?}else{?>

	alert("<?=$arrLevelInfo[$arrBoardInfo["list"][0]["replylevel"]]?> 이상 댓글입력이 가능합니다.");
	return false;
	<?}?>
}
<?
}
//댓글 사용시
?>
</script>
<script type="text/javascript">
<!--
function boardDel(val){
	if(confirm("삭제 하시겠습니까?")) {
		$.post("/module/board/ajax_board_del.php", { evnMode: "delete", g_idx: val, boardid: "<?=$arrBoardInfo["list"][0]["boardid"]?>" },
		function(data){
			//alert(data);
			doLoad();
		});
	}
}
function doLoad(){
	location.href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&sk=<?=$_GET['sk']?>&sw=<?=$_GET['sw']?>&offset=<?=$_GET['offset']?>&category=<?=$_GET['category']?>";
}
//-->
</script>
<div id="admin-content">
	<h2 class="admin-title"><?=$arrBoardInfo["list"][0]["boardname"]?> - View</h2>
	<table class="viewTable">
		<colgroup><col width="110px" /><col width="*" /><col width="110px" /><col width="20%" /><col width="110px" /><col width="20%" /></colgroup>
		<thead>
		<tr>
			<th colspan="6"><?=stripslashes($arrBoardArticle["list"][0]['subject'])?></th>
		</tr>
		</thead>
		<tbody>
			<tr>
			<th>작성자</th>
			<td><?=stripslashes($arrBoardArticle["list"][0]['name'])?></td>
			<th>조회수</th>
			<td colspan="3"><?=number_format($arrBoardArticle["list"][0]['hit'])?></td>
		</tr>
		<tr>
			<td class="ct" colspan="6">
                <div style="min-height:100px;"><?=htmlspecialchars_decode($arrBoardArticle["list"][0]['contents'])?></div>
			</td>
		</tr>
		<tr>
			<th>키워드</th>
			<td colspan="5">
			<?=stripslashes($arrBoardArticle["list"][0]['etc_1'])?>
			</td>
		</tr>
			<tr>
			<th>첨부파일</th>
			<td colspan="5" class="file">
			<?for($i=0;$i<$arrBoardArticle["total_files"];$i++){?>
			<a href="javascript:void(0);" onclick="fileDownload('<?=$arrBoardArticle["files"][$i]['boardid']?>','<?=$arrBoardArticle["files"][$i]['b_idx']?>','<?=$arrBoardArticle["files"][$i]['idx']?>');"><?=$arrBoardArticle["files"][$i]['ori_name']?></a>
			<?}?>
			<?if($i<1){?>
			첨부파일이 없습니다.
			<?}?>
			</td>
		</tr>
			<tr>
			<th>등록일시</th>
			<td><?=$arrBoardArticle["list"][0]['wdate']?></td>
			<th>등록IP</th>
			<td colspan="3"><?=stripslashes($arrBoardArticle["list"][0]['ip'])?></td>
		</tr>
		</tbody>
	</table>
	<p class="btn_l">
		<a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&sk=<?=$_GET['sk']?>&sw=<?=$_GET['sw']?>&offset=<?=$_GET['offset']?>&category=<?=$_GET['category']?>" class="btn_box act_list">목록보기</a>
	</p>
	<p class="btn_r">
		<a href="javascript:void(0);" onclick="boardDel(<?=$arrBoardArticle["list"][0]['idx']?>)" class="btn_box black act_del">삭제</a>
		<a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=modify&idx=<?=$arrBoardArticle["list"][0]['idx']?>&category=<?=$_GET['category']?>" class="btn_box act_upt">수정</a>
	</p>
	<dl class="more_list">
		<dt>이전글</dt><dd><?if($arrBoardArticle["prev"]["idx"] !=0):?><a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=view&idx=<?=$arrBoardArticle["prev"]["idx"]?>&category=<?=$_GET['category']?>" title="<?=$arrBoardArticle["prev"]["subject"]?>" class="act_view"><?=text_cut($arrBoardArticle["prev"]["subject"],$arrBoardInfo["list"][0]['subjectcut'])?></a><?else:?><a href="javascript:void(0);">이전글이 없습니다.</a><?endif;?></dd>
		<dt>다음글</dt><dd><?if($arrBoardArticle["next"]["idx"] !=0):?><a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=view&idx=<?=$arrBoardArticle["next"]["idx"]?>&category=<?=$_GET['category']?>" title="<?=$arrBoardArticle["next"]["subject"]?>" class="act_view"><?=text_cut($arrBoardArticle["next"]["subject"],$arrBoardInfo["list"][0]['subjectcut'])?></a><?else:?><a href="javascript:void(0);">다음글이 없습니다.</a><?endif;?></dd>
	</dl>
</div>
<?}else{###################################################### 사용자 페이지 ######################################################?>
	<?php
	$dayTypeMap = [
		'weekly' => '매주',
		'biweekly' => '격주',
		'other' => '기타'
	];

	$arrEquUser = getBoardArticleView("equ_applicants", "", "", "", "  equ_idx = " . $arrBoardArticle["list"][0]['idx']);

	$imgsrc = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][0]['re_name'];

	$arrBoardHolidayList = getBoardListBaseNFile("holiday", $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], $_GET['reply']);

	$holidayWeekdays = [];
	$specificHolidayDates = [];

	foreach ($arrBoardHolidayList['list'] as $holiday) {
		// 요일 정보 처리
		if (!empty($holiday['weekdays'])) {
			$weekdays = explode('|', $holiday['weekdays']);
			$holidayWeekdays = array_merge($holidayWeekdays, $weekdays);
		}

		// 특정 날짜 범위 처리
		if (!empty($holiday['holly_start_date']) && !empty($holiday['holly_end_date'])) {
			$startDate = strtotime($holiday['holly_start_date']);
			$endDate = strtotime($holiday['holly_end_date']);

			for ($date = $startDate; $date <= $endDate; $date = strtotime('+1 day', $date)) {
				$specificHolidayDates[] = date('Y-m-d', $date);
			}
		}
	}

	// 중복 제거
	$holidayWeekdays = array_unique($holidayWeekdays);
	$specificHolidayDates = array_unique($specificHolidayDates);

	// JavaScript 배열로 변환
	$holidayWeekdaysJson = json_encode($holidayWeekdays);
	$specificHolidayDatesJson = json_encode($specificHolidayDates);

	// 대여 정보를 JSON으로 변환
	$rentalData = [];
	foreach ($arrEquUser["list"] as $rental) {
		$rentalData[] = [
			'start_date' => $rental['rental_start_date'],
			'end_date' => $rental['rental_end_date'],
			'start_time' => $rental['rental_start_time'],
			'end_time' => $rental['rental_end_time']
		];
	}
	$rentalDataJson = json_encode($rentalData);
	$stockQuantity = $arrBoardArticle["list"][0]["stock_quantity"];
	?>
    <form id="rentalForm"  method="POST">
        <input type="hidden" name="rental_date" id="rental_date">
        <input type="hidden" name="totalamount" id="total_price_hidden">
        <input type="hidden" name="fee" id="fee" value="<?=$arrBoardArticle["list"][0]['fee']?>">
        <input type="hidden" name="subject" id="subject" value="<?=$arrBoardArticle["list"][0]['subject']?>">
        <input type="hidden" name="category1" id="category1" value="<?=$arrBoardArticle["list"][0]['category1']?>">
        <input type="hidden" name="category2" id="category2" value="<?=$arrBoardArticle["list"][0]['category2']?>">
        <input type="hidden" name="usage_day" id="usage_time_day">
        <input  type="hidden" name="equ_number" value="<?=$arrBoardArticle["list"][0]['equ_number']?>">
        <input  type="hidden" name="equ_idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
        <!--        <input type="hidden" name="boardid" value="equ_applicants_cart">-->
        <input type="hidden" name="user_id" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
        <input type="hidden" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
        <!-- subSec -->
        <div class="subSec ">
            <div class="inner">
                <div class="btnBack">
                    <a href="javascript:history.back();">뒤로</a>
                </div>
                <!-- eqDetail -->
                <div class="eqDetail detailInfo">
                    <div class="img">
                        <div class="swiper-wrapper">
							<?php
							foreach ($arrBoardArticle["files"] as $file) {
								$imgsrc = "/uploaded/board/" . $arrBoardInfo["list"][0]["boardid"] . "/" . $file['re_name'];
								echo '<div class="swiper-slide"><img src="' . $imgsrc . '" alt="섬네일"></div>';
							}
							?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <div class="textCont">
                        <div class="pointBox">
							<?php if (!empty(getCategoryName($arrBoardArticle["list"][0]['category1']))): ?>
                                <div class="tit"><?=getCategoryName($arrBoardArticle["list"][0]['category1'])?></div>
							<?php endif; ?>
							<?php if (!empty(getCategoryName($arrBoardArticle["list"][0]['category2']))): ?>
                                <div class="tit"><?=getCategoryName($arrBoardArticle["list"][0]['category2'])?></div>
							<?php endif; ?>
                            <div class="tit green"><?=stripslashes($arrBoardArticle["list"][0]['usage_level'])?></div>
                        </div>
                        <div class="title"><?=stripslashes($arrBoardArticle["list"][0]['subject'])?></div>
                        <div class="item_num"><span>장비번호</span><em><?=stripslashes($arrBoardArticle["list"][0]['equ_number'])?></em></div>
                        <div class="price">
                            <div class="name">대여료</div>
                            <div class="money"><?=number_format(stripslashes($arrBoardArticle["list"][0]['fee']))?>원 (1일)</div>
                        </div>
                        <div class="info">
                            <ul>
                                <li>
                                    <div class="tit">대여 / 반납시간</div>
                                    <div class="txt">
										<?= $arrSetInfo["list"][0]["equ_rental_use"] == 'Y' ? $arrSetInfo["list"][0]["equ_rental_start_time"] : '00:00' ?>
                                        ~
										<?= $arrSetInfo["list"][0]["equ_rental_use"] == 'Y' ? $arrSetInfo["list"][0]["equ_rental_end_time"] : '00:00' ?>
                                        /
										<?= $arrSetInfo["list"][0]["equ_return_use"] == 'Y' ? $arrSetInfo["list"][0]["equ_return_start_time"] : '00:00' ?>
                                        ~
										<?= $arrSetInfo["list"][0]["equ_return_use"] == 'Y' ? $arrSetInfo["list"][0]["equ_return_end_time"] : '00:00' ?>
                                    </div>
                                </li>
                                <li>
                                    <div class="tit">점심 / 저녁시간</div>
                                    <div class="txt">
										<?= $arrSetInfo["list"][0]["equ_lunch_use"] == 'Y' ? $arrSetInfo["list"][0]["equ_lunch_start_time"] : '00:00' ?>
                                        ~
										<?= $arrSetInfo["list"][0]["equ_lunch_use"] == 'Y' ? $arrSetInfo["list"][0]["equ_lunch_end_time"] : '00:00' ?>
                                        /
										<?= $arrSetInfo["list"][0]["equ_dinner_use"] == 'Y' ? $arrSetInfo["list"][0]["equ_dinner_start_time"] : '00:00' ?>
                                        ~
										<?= $arrSetInfo["list"][0]["equ_dinner_use"] == 'Y' ? $arrSetInfo["list"][0]["equ_dinner_end_time"] : '00:00' ?>
                                    </div>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <div class="tit">대여일/반납일</div>
                                    <div class="txt">
                                        <div class="cmsDate">
                                            <div class="baseInput">
                                                <input id="st1" name ="rental_start_date" readonly type="text" title="시작날짜" value="" >
                                            </div>
                                            <div class="line">-</div>
                                            <div class="baseInput">
                                                <input id="ed" name ="rental_end_date" readonly type="text" title="마지막날짜" value=""" >
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tit">대여 방문시간</div>
                                    <div class="txt">
                                        <div class="baseSel">
                                            <select name="rental_start_time" id="rental_start_time">
                                                <option value="">선택</option>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="tit">반납 방문시간</div>
                                    <div class="txt">
                                        <div class="baseSel">
                                            <select name="rental_end_time" id="rental_end_time">
                                                <option value="">선택</option>
                                            </select>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="totalPrice">
                            <div class="nameDate">
								<?=stripslashes($arrBoardArticle["list"][0]['subject'])?> / <?=stripslashes($arrBoardArticle["list"][0]['equ_number'])?>  / 0일
                            </div>
                            <div class="price">0원</div>
                            <a href="javascript:void(0)" class="close"><img src="/images/ico_smClose.svg" alt="닫기"></a>
                        </div>
                        <div class="btnOrder mobFix two">
                            <a href="javascript:void(0);" class="btnType1 lineBlue" onclick="submitRentalForm('cart_write')">장바구니</a>
                            <a href="javascript:void(0);" class="btnType1" onclick="submitRentalForm('order')">대여 신청</a>
                        </div>
                    </div>
                </div>
                <!-- //eqDetail -->
    </form>
    </div>
    </div>
    <!-- //subSec -->

    <!-- subSec -->
    <div class="subSec blue last">
        <div class="inner">
            <div class="whiteBox">
                <div class="wTit">대여 신청 안내</div>
                <div class="iconList">
                    <ul>
                        <li>
                            <div class="img"><img src="/images/ico_eq6.svg" alt="아이콘"></div>
                            <div class="text">
                                <div class="step">STEP 01</div>
                                <div class="tit">장비번호 선택</div>
                            </div>
                        </li>
                        <li>
                            <div class="img"><img src="/images/ico_eq7.svg" alt="아이콘"></div>
                            <div class="text">
                                <div class="step">STEP 02</div>
                                <div class="tit">대여 일자 선택</div>
                            </div>
                        </li>
                        <li>
                            <div class="img"><img src="/images/ico_eq8.svg" alt="아이콘"></div>
                            <div class="text">
                                <div class="step">STEP 03</div>
                                <div class="tit">방문 시간 조정</div>
                            </div>
                        </li>
                        <li>
                            <div class="img"><img src="/images/ico_eq9.svg" alt="아이콘"></div>
                            <div class="text">
                                <div class="step">STEP 04</div>
                                <div class="tit">대여신청</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="whiteBox">
                <div class="wTit">유의사항</div>
                <ul class="textUl">
                    <li>· 기자재는 일반회원으로 가입하셔야 대여하실 수 있습니다.</li>
                    <li>· 장비의 대여와 반납은 신청자(회원) 본인이 직접 하셔야 합니다.</li>
                </ul>
            </div>
            <div class="whiteBox">
                <div class="wTit"> 장비대여 주의사항</div>
                <ul class="textUl">
                    <li>· 홈페이지를 통해서 최소 2일전에 예약 신청 후, 담당자와 통화 후 예약이 확정됩니다.</li>
                    <li> * 담당자와 통화를 하지 않고 방문 시 장비대여 불가 (070-4607-1214, 070-4607-1215)</li>
                </ul>
                <br />
                <ul class="textUl">
                    <li>· 장비 대여료 결제는 계좌이체를 통해서만 가능합니다.<br />(주안영상미디어센터 계좌번호 : 신한은행 100-035-698102)</li>
                </ul>
            </div>
            <div class="whiteBox">
                <div class="wTit">신청서 작성 <a href="/download/juanmedia.hwp" download="주안영상미디어센터 시설 및 장비 사용(감면) 신청서(구청양식).hwp" class="btnDown"><span>신청서 다운로드</span></a></div>
                <ul class="textUl">
                    <li>· 대여 신청 완료 후 별도의 신청서를 작성하여 파일 첨부 또는 해당 이메일로 보내 주시기를 바랍니다.</li>
                    <li>· Email : juanmedia@daum.net</li>
                </ul>
            </div>

            <div class="btnCenter">
                <a href="list.php" class="btnType1 black list">목록</a>
            </div>

        </div>
    </div>
    <!-- //subSec -->

    </div>
    <!-- //Container -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 슬라이더 초기화
            const swiper = new Swiper(".eqDetail .img", {
                pagination: { el: ".swiper-pagination" }
            });

            document.querySelector('.totalPrice .close').addEventListener('click', function() {
                // Reset the date fields
                $("#st1").datepicker("setDate", null);
                $("#ed").datepicker("setDate", null);

                // Update the total price and name date
                document.querySelector('.nameDate').textContent = `<?=stripslashes($arrBoardArticle["list"][0]['subject'])?> / <?=stripslashes($arrBoardArticle["list"][0]['equ_number'])?> / 0일`;
                document.querySelector('.totalPrice .price').textContent = `0원`;

                // Reset hidden fields
                document.getElementById('total_price_hidden').value = 0;
                document.getElementById('usage_time_day').value = 0;
            });

            const today = new Date();
            const minStartDate = new Date();
            minStartDate.setDate(today.getDate() + 3);

            const maxRentalDays = <?= $arrSetInfo["list"][0]["equ_application_days"] ?> - 1;
            const applicationDays = <?= $arrSetInfo["list"][0]["equ_max_rental_days"] ?> - 1;
            const feePerDay = <?= $arrBoardArticle["list"][0]['fee'] ?>;
            const maxRentalEndDate = new Date();
            maxRentalEndDate.setDate(today.getDate() + 3 + maxRentalDays);

            //휴관일 데이터
            const holidayWeekdays = <?= $holidayWeekdaysJson ?>;
            const specificHolidayDates = <?= $specificHolidayDatesJson ?>;

            // 요일 맵핑 (PHP의 요일명을 JavaScript의 숫자로 변환)
            const weekdayMap = {
                '일': 0, '월': 1, '화': 2, '수': 3, '목': 4, '금': 5, '토': 6
            };

            // 날짜가 휴관일인지 확인하는 함수
            function isHoliday(date) {
                // 요일 체크
                const dayOfWeek = date.getDay();
                const koreanDayName = Object.keys(weekdayMap).find(key => weekdayMap[key] === dayOfWeek);
                if (holidayWeekdays.includes(koreanDayName)) {
                    return [false, 'holiday', '휴관일'];
                }

                // 특정 날짜 체크
                const dateString = date.toLocaleDateString('en-CA'); // 'en-CA' 형식은 'YYYY-MM-DD' 형식을 반환합니다.
                if (specificHolidayDates.includes(dateString)) {
                    return [false, 'holiday', '휴관일'];
                }

                return [true, '', ''];
            }

            // Initialize the start date picker
            $("#st1").datepicker({
                showOn: "both",
                buttonText: "날짜 선택",
                prevText: '이전 달',
                nextText: '다음 달',
                monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                dayNames: ['일', '월', '화', '수', '목', '금', '토'],
                dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
                dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
                dateFormat: 'yy-mm-dd',
                showMonthAfterYear: true,
                showAnim: false,
                yearSuffix: '년',
                minDate: minStartDate,
                maxDate: maxRentalEndDate,
                beforeShowDay: isHoliday,
                onSelect: function(selectedDate) {
                    const startDate = new Date(selectedDate);
                    const newEndDate = new Date(startDate);
                    newEndDate.setDate(startDate.getDate() + applicationDays);
                    $("#ed").datepicker("option", "minDate", startDate);
                    $("#ed").datepicker("option", "maxDate", newEndDate);
                    updateTotalPrice();
                }
            });

            // Initialize the end date picker
            $("#ed").datepicker({
                showOn: "both",
                buttonText: "날짜 선택",
                prevText: '이전 달',
                nextText: '다음 달',
                monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                dayNames: ['일', '월', '화', '수', '목', '금', '토'],
                dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
                dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
                dateFormat: 'yy-mm-dd',
                showMonthAfterYear: true,
                showAnim: false,
                yearSuffix: '년',
                minDate: minStartDate,
                maxDate: maxRentalEndDate,
                beforeShowDay: isHoliday,
                onSelect: function(selectedDate) {
                    const rentalStartDate = $("#st1").datepicker("getDate");
                    if (!rentalStartDate) {
                        alert('대여일을 먼저 선택해 주세요.');
                        $("#ed").datepicker("setDate", null);
                    } else {
                        updateTotalPrice();
                    }
                }
            });

            // 대여 정보와 재고 수량
            const rentalData = <?= $rentalDataJson ?>;
            const stockQuantity = <?= $stockQuantity ?>;

            // 날짜 범위가 겹치는지 확인하는 함수
            function isDateOverlap(start1, end1, start2, end2) {
                return start1 <= end2 && end1 >= start2;
            }

            // 특정 기간 동안의 대여 수량을 확인하는 함수
            function checkAvailability(startDate, endDate, startTime, endTime) {
                let maxOverlap = 0;

                // 선택한 날짜들을 배열로 생성
                const selectedDates = [];
                let currentDate = new Date(startDate);
                while (currentDate <= new Date(endDate)) {
                    // 휴관일이 아닌 경우만 포함
                    if (!isHoliday(currentDate)[0]) {
                        selectedDates.push(new Date(currentDate));
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                // 각 날짜별로 겹치는 대여 건수 확인
                selectedDates.forEach(date => {
                    let overlapCount = 0;
                    const dateStr = date.toLocaleDateString('en-CA');

                    rentalData.forEach(rental => {
                        if (isDateOverlap(
                            dateStr,
                            dateStr,
                            rental.start_date,
                            rental.end_date
                        )) {
                            // 시간이 겹치는지 확인
                            if (
                                (startTime <= rental.end_time && endTime >= rental.start_time) ||
                                (rental.start_time <= endTime && rental.end_time >= startTime)
                            ) {
                                overlapCount++;
                            }
                        }
                    });
                    maxOverlap = Math.max(maxOverlap, overlapCount);
                });

                return maxOverlap < stockQuantity;
            }

            function updateTotalPrice() {
                const rentalStartDate = $("#st1").datepicker("getDate");
                const rentalEndDate = $("#ed").datepicker("getDate");
                const rentalStartTime = $("#rental_start_time").val();
                const rentalEndTime = $("#rental_end_time").val();

                if (rentalStartDate && rentalEndDate && rentalStartTime && rentalEndTime) {
                    // 재고 수량 체크
                    const isAvailable = checkAvailability(
                        rentalStartDate.toLocaleDateString('en-CA'),
                        rentalEndDate.toLocaleDateString('en-CA'),
                        rentalStartTime,
                        rentalEndTime
                    );

                    if (!isAvailable) {
                        alert("선택하신 기간에 대여 가능한 수량을 초과하였습니다.");
                        $("#ed").datepicker("setDate", null);
                        $("#rental_start_time").val("");
                        $("#rental_end_time").val("");
                        return;
                    }

                    // 시작 날짜와 시간을 결합
                    const startDateTime = new Date(rentalStartDate);
                    const [startHour] = rentalStartTime.split(':').map(Number);
                    startDateTime.setHours(startHour, 0, 0);

                    // 종료 날짜와 시간을 결합
                    const endDateTime = new Date(rentalEndDate);
                    const [endHour] = rentalEndTime.split(':').map(Number);
                    endDateTime.setHours(endHour, 0, 0);

                    // 실제 대여 가능 일수 계산
                    let rentalDays = 0;
                    const currentDate = new Date(startDateTime);

                    while (currentDate <= endDateTime) {
                        // 해당 날짜가 휴관일인지 확인
                        const dayOfWeek = currentDate.getDay();
                        const koreanDayName = Object.keys(weekdayMap).find(key => weekdayMap[key] === dayOfWeek);
                        const dateString = currentDate.toLocaleDateString('en-CA');

                        // 휴관일이 아닌 경우만 카운트
                        if (!holidayWeekdays.includes(koreanDayName) && !specificHolidayDates.includes(dateString)) {
                            rentalDays++;
                        }

                        // 다음 날짜로 이동
                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    // 24시간 기준으로 올림 계산
                    const timeDiff = Math.abs(endDateTime.getTime() - startDateTime.getTime());
                    const totalDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                    // 실제 대여 가능 일수가 0이면 경고
                    if (rentalDays === 0) {
                        alert("선택한 기간에 대여 가능한 날짜가 없습니다.");
                        $("#ed").datepicker("setDate", null);
                        return;
                    }

                    const feePerDay = <?= $arrBoardArticle["list"][0]['fee'] ?>;
                    const totalPrice = rentalDays * feePerDay;

                    // UI 업데이트
                    document.querySelector('.nameDate').textContent =
                        `<?=stripslashes($arrBoardArticle["list"][0]['subject'])?> / <?=stripslashes($arrBoardArticle["list"][0]['equ_number'])?> / ${rentalDays}일`;
                    document.querySelector('.totalPrice .price').textContent =
                        `${totalPrice.toLocaleString()}원`;

                    // 히든 필드 업데이트
                    document.getElementById('total_price_hidden').value = totalPrice;
                    document.getElementById('usage_time_day').value = rentalDays;
                }
            }

            // 대여 가능 여부를 확인하는 함수
            function checkRentalAvailability(newStartDate, newEndDate, newStartTime, newEndTime, existingRentals, stockQuantity) {
                // 새로운 대여 시작/종료 시간 생성
                const newStart = new Date(`${newStartDate}T${newStartTime}`);
                const newEnd = new Date(`${newEndDate}T${newEndTime}`);

                // 각 날짜별 대여 수량을 추적하는 맵 생성
                const rentalCountByDate = new Map();

                // 새로운 대여 기간의 각 날짜에 대해
                const currentDate = new Date(newStart);
                while (currentDate <= newEnd) {
                    const dateStr = currentDate.toISOString().split('T')[0];
                    rentalCountByDate.set(dateStr, 0);
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                // 기존 대여 정보를 확인하여 각 날짜별 대여 수량 계산
                for (const rental of existingRentals) {
                    const rentalStart = new Date(`${rental.rental_start_date}T${rental.rental_start_time}`);
                    const rentalEnd = new Date(`${rental.rental_end_date}T${rental.rental_end_time}`);

                    // 기존 대여가 새로운 대여 기간과 겹치는지 확인
                    if (!(rentalEnd < newStart || rentalStart > newEnd)) {
                        const current = new Date(rentalStart);
                        while (current <= rentalEnd) {
                            const dateStr = current.toISOString().split('T')[0];
                            if (rentalCountByDate.has(dateStr)) {
                                rentalCountByDate.set(dateStr, rentalCountByDate.get(dateStr) + 1);
                            }
                            current.setDate(current.getDate() + 1);
                        }
                    }
                }

                // 각 날짜에 대해 재고 수량을 초과하는지 확인
                for (const [date, count] of rentalCountByDate) {
                    if (count >= stockQuantity) {
                        return {
                            available: false,
                            message: `${date} 날짜에 대여 가능한 수량(${stockQuantity}개)을 초과했습니다.`
                        };
                    }
                }

                return {
                    available: true,
                    message: "대여 가능합니다."
                };
            }

// 폼 제출 전 유효성 검사 함수
            function validateRentalForm() {
                const startDate = document.getElementById('st1').value;
                const endDate = document.getElementById('ed').value;
                const startTime = document.getElementById('rental_start_time').value;
                const endTime = document.getElementById('rental_end_time').value;

                // PHP에서 전달받은 기존 대여 정보와 재고 수량을 JavaScript 변수로 변환
                const existingRentals = <?= json_encode($arrEquUser['list'] ?? []) ?>;
                const stockQuantity = <?= $arrBoardArticle["list"][0]["stock_quantity"] ?>;

                const availability = checkRentalAvailability(
                    startDate,
                    endDate,
                    startTime,
                    endTime,
                    existingRentals,
                    stockQuantity
                );

                if (!availability.available) {
                    alert(availability.message);
                    return false;
                }

                return true;
            }

            // 폼 제출 관련 함수
            window.submitRentalForm = function(mode) {
                // 대여/반납 시간 체크
                const startTime = document.getElementById('rental_start_time').value;
                const endTime = document.getElementById('rental_end_time').value;
                const startDate = document.getElementById('st1').value;
                const endDate = document.getElementById('ed').value;

                if (!validateRentalForm()) {
                    return;
                }
                /* if (<?= $arrEduUser["total"] ?> >= <?= $arrSetInfo["list"][0]["equ_max_rental_count"] ?>) {
                    alert("최대 대여 개수를 초과 하였습니다.");
                    return;
                }*/
                if (<?= $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] ?> == 6 ) {
                    alert("정지 회원은 신청 불가능 합니다.");
                    return;
                }
                if (!document.getElementById('st1').value) {
                    alert('대여일을 선택해 주세요.');
                    return;
                }
                if (!document.getElementById('ed').value) {
                    alert('반납일을 선택해 주세요.');
                    return;
                }

                if (!startTime) {
                    alert('대여 방문시간을 선택해 주세요.');
                    document.getElementById('rental_start_time').focus();
                    return;
                }

                if (!endTime) {
                    alert('반납 방문시간을 선택해 주세요.');
                    document.getElementById('rental_end_time').focus();
                    return;
                }


                if (!checkAvailability(startDate, endDate, startTime, endTime)) {
                    alert("선택하신 기간에 대여 가능한 수량을 초과하였습니다.");
                    return;
                }

                const form = document.getElementById('rentalForm');
                if (mode === 'cart_write') {
                    actionUrl = `<?=$_SERVER["PHP_SELF"]?>?boardid=equ_applicants_cart&mode=${mode}`;
                }
                else {
                    actionUrl = `<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=${mode}&idx=<?=$arrBoardArticle["list"][0]['idx']?>`;
                }
                form.action = actionUrl;
                form.submit();
            };

            // 운영 시간 설정
            const rentalStartSelect = document.getElementById('rental_start_time');
            const rentalEndSelect = document.getElementById('rental_end_time');

            // PHP에서 설정된 시간 가져오기
            const rentalStartTime = '<?= $arrSetInfo["list"][0]["equ_rental_start_time"] ?>';
            const rentalEndTime = '<?= $arrSetInfo["list"][0]["equ_rental_end_time"] ?>';
            const returnStartTime = '<?= $arrSetInfo["list"][0]["equ_return_start_time"] ?>';
            const returnEndTime = '<?= $arrSetInfo["list"][0]["equ_return_end_time"] ?>';

            // 점심, 저녁 시간 설정
            const lunchStartTime = '<?= $arrSetInfo["list"][0]["equ_lunch_start_time"] ?>';
            const lunchEndTime = '<?= $arrSetInfo["list"][0]["equ_lunch_end_time"] ?>';
            const dinnerStartTime = '<?= $arrSetInfo["list"][0]["equ_dinner_start_time"] ?>';
            const dinnerEndTime = '<?= $arrSetInfo["list"][0]["equ_dinner_end_time"] ?>';

            // 시간을 분으로 변환하는 함수
            function timeToMinutes(time) {
                const [hours, minutes] = time.split(':').map(Number);
                return hours * 60 + minutes;
            }

            // 제외할 시간대인지 확인하는 함수
            function isExcludedTime(time) {
                const minutes = timeToMinutes(time);
                const isLunchTime = minutes >= timeToMinutes(lunchStartTime) && minutes < timeToMinutes(lunchEndTime);
                const isDinnerTime = minutes >= timeToMinutes(dinnerStartTime) && minutes < timeToMinutes(dinnerEndTime);
                return isLunchTime || isDinnerTime;
            }

            // 시간 옵션 생성 함수
            function generateTimeOptions(startTime, endTime, selectElement) {
                selectElement.innerHTML = '<option value="">선택</option>';

                let start = startTime.split(':');
                let end = endTime.split(':');

                let startHour = parseInt(start[0]);
                let endHour = parseInt(end[0]);

                for (let hour = startHour; hour <= endHour; hour++) {
                    const timeString = `${hour.toString().padStart(2, '0')}:00`;
                    // 점심시간과 저녁시간 제외
                    if (!isExcludedTime(timeString)) {
                        const option = new Option(timeString, timeString);
                        selectElement.appendChild(option);
                    }
                }
            }

            // 초기 시간 옵션 설정
            generateTimeOptions(rentalStartTime, rentalEndTime, rentalStartSelect);
            generateTimeOptions(returnStartTime, returnEndTime, rentalEndSelect);

            // 시간 선택 시 유효성 검사
            rentalStartSelect.addEventListener('change', function() {
                if (!document.getElementById('st1').value) {
                    alert('대여일을 먼저 선택해주세요.');
                    this.value = '';
                    return;
                }
                if (this.value && isExcludedTime(this.value)) {
                    alert('점심 시간 또는 저녁 시간에는 선택할 수 없습니다.');
                    this.value = '';
                    return;
                }
                updateTotalPrice();
            });

            rentalEndSelect.addEventListener('change', function() {
                if (!document.getElementById('ed').value) {
                    alert('반납일을 먼저 선택해주세요.');
                    this.value = '';
                    return;
                }
                if (this.value && isExcludedTime(this.value)) {
                    alert('점심 시간 또는 저녁 시간에는 선택할 수 없습니다.');
                    this.value = '';
                    return;
                }
                updateTotalPrice();
            });
        });
    </script>
<?}###################################################### 사용자 페이지 ###################################################### END ?>