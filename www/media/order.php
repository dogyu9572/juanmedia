<?php include("../inc/header.php"); ?>
<?php $gNum = "04"; $sNum = "02"; $gName = "미디어체험"; $sName = "체험신청";?>
<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
    $dblink = SetConn($_conf_db["main_db"]);

    $arrBoardHolidayList = getBoardListBaseNFile("holiday", $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], $_GET['reply']); // 휴관일 리스트 가져오기
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

    //DB해제
    SetDisConn($dblink);
?>
<script>
    const holidayWeekdaysJson = <?= json_encode($holidayWeekdays) ?>;
    const specificHolidayDatesJson = <?= json_encode($specificHolidayDates) ?>;
</script>
<script src="/js/calendar_sub.js"></script>
<!-- Container -->
<div class="container sub" id="container">

    <!-- subTopBg -->
    <div class="subTopBg media">
        <div class="inner">
            <div class="enName">MEDIA EXPERIENCE</div>
            <div class="korName">미디어체험</div>
            <?php include("../inc/sub_navi.php"); ?>
        </div>
    </div>
    <!-- //subTopBg -->

    <!-- pageTitle -->
    <div class="pageTitle inner">체험신청</div>
    <!-- //pageTitle -->

    <!-- subSec -->
    <div class="subSec last pt0">
        <div class="expCal">
            <div class="inner">
                <div class="top">
                    <div class="inner">
                        <div class="state">
                            <span>가능</span>
                            <span class="not">불가능</span>
                        </div>
                        <div class="year">
                            <a href="#;"><img src="/images/ico_calPrev.svg" alt="이전"></a>
                            <span class="num">2024.10</span>
                            <a href="#;"><img src="/images/ico_calNext.svg" alt="다음"></a>
                        </div>
                    </div>
                </div>

                <div id="scrollBar">
                    <div class="contScroll">
                        <ul>
                            <li>
                                <a href="#;">
                                    <span class="week">휴일</span>
                                    <span class="day">1</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">수</span>
                                    <span class="day">2</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">휴일</span>
                                    <span class="day">3</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">금</span>
                                    <span class="day">4</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">토</span>
                                    <span class="day">5</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">일</span>
                                    <span class="day">6</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">월</span>
                                    <span class="day">7</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">화</span>
                                    <span class="day">8</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">휴일</span>
                                    <span class="day">9</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;" class="able">
                                    <span class="week">목</span>
                                    <span class="day">10</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;" class="able">
                                    <span class="week">금</span>
                                    <span class="day">11</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;" class="able">
                                    <span class="week">토</span>
                                    <span class="day">12</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;" class="disable">
                                    <span class="week">일</span>
                                    <span class="day">13</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">월</span>
                                    <span class="day">14</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">화</span>
                                    <span class="day">15</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">수</span>
                                    <span class="day">16</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">목</span>
                                    <span class="day">17</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">금</span>
                                    <span class="day">18</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">토</span>
                                    <span class="day">19</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">일</span>
                                    <span class="day">20</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">월</span>
                                    <span class="day">21</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">화</span>
                                    <span class="day">22</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">수</span>
                                    <span class="day">23</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">목</span>
                                    <span class="day">24</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">금</span>
                                    <span class="day">25</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">토</span>
                                    <span class="day">26</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">일</span>
                                    <span class="day">27</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">월</span>
                                    <span class="day">28</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">화</span>
                                    <span class="day">29</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">수</span>
                                    <span class="day">30</span>
                                </a>
                            </li>
                            <li>
                                <a href="#;">
                                    <span class="week">목</span>
                                    <span class="day">31</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form id="enrollmentForm" name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
            <input type="hidden" name="boardid" value="media_applicants">
            <input type="hidden" name="returnURL" value="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&cat_no=<?=$arrBoardArticle["list"][0]['category1']?>&offset=<?=$_GET['offset']??""?>">
            <input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
            <input type="hidden" id="media_no" name="media_no" value="<?=$arrBoardArticle["list"][0]["media_no"]?>">
            <input type="hidden" id="subject" name="subject" value="<?=$arrBoardArticle["list"][0]['subject']?>">
            <input type="hidden" id="capacity" name="capacity" value="<?=$arrBoardArticle["list"][0]['capacity']?>">
            <input type="hidden" id="user_level" name="user_level" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"]?>">
            <input type="hidden" id="w_user" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
            <input type="hidden" id="media_idx" name="media_idx" value="<?=$arrBoardArticle["list"][0]['idx']?>">
            <input type="hidden" name="usehtml" value="Y">
            <?php if($_REQUEST['mode']=="reply"):?>
                <input type="hidden" name="evnMode" value="reply">
            <?php elseif($_REQUEST['mode']=="modify"):?>
                <input type="hidden" name="evnMode" value="modify">
            <?php else:?>
                <input type="hidden" name="evnMode" value="write">
            <?php endif;?>

            <div class="expFrom">
                <div class="title">
                    <div class="tit">정보입력</div>
                    <div class="eq"><span>*</span> 는 필수 입력 사항입니다.</div>
                </div>
                <!-- formBox -->
                <div class="formBox">
                    <div class="row">
                        <div class="formTit">신청인(이름)<span>*</span></div>
                        <div class="right">
                            <div class="baseInput">
                                <input type="text" name="name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>" disabled>
                                <input type="hidden" name="name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">연락처<span>*</span></div>
                        <div class="right">
                            <div class="baseInput">
                                <input type="text" name="tel" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"]?>" disabled>
                                <input type="hidden" name="tel" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"]?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">이메일<span>*</span></div>
                        <div class="right">
                            <div class="baseInput">
                                <input type="text" name="email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["EMAIL"]?>" disabled>
                                <input type="hidden" name="email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["EMAIL"]?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">단체명(학교명)</div>
                        <div class="right">
                            <div class="baseInput">
                                <input type="text" name="group_name" placeholder="단체명(학교명)을 입력해주세요.">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">주소</div>
                        <div class="right">
                            <div class="baseInput">
                                <input type="text" name="address" placeholder="주소를 입력해주세요.">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">인원 수<span>*</span></div>
                        <div class="right">
                            <div class="members">
                                <div class="box">
                                    <div class="mText">총인원</div>
                                    <div class="baseInput">
                                        <input type="tel" name="total_members" id="total_members" placeholder="0" maxlength="4" oninput="checkMembers()">
                                    </div>
                                    <div class="mText">명</div>
                                </div>
                                <div class="slash">/</div>
                                <div class="box">
                                    <div class="mText">남</div>
                                    <div class="baseInput">
                                        <input type="tel" name="male_members" placeholder="0" maxlength="4" >
                                    </div>
                                    <div class="mText">명</div>
                                </div>
                                <div class="slash">/</div>
                                <div class="box">
                                    <div class="mText">여</div>
                                    <div class="baseInput">
                                        <input type="tel" name="female_members" placeholder="0" maxlength="4" >
                                    </div>
                                    <div class="mText">명</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit pt1">체험분야<br class="pc" />(중복가능)<span>*</span></div>
                        <div class="right">
                            <div class="expCk">
                                <div class="baseCheck2">
                                    <input type="checkbox" name="experience[]" value="TV방송제작" id="exp1" disabled />
                                    <label for="exp1">TV방송제작</label>
                                </div>
                                <div class="baseCheck2">
                                    <input type="checkbox" name="experience[]" value="라디오 방송제작" id="exp2" disabled />
                                    <label for="exp2">라디오 방송제작</label>
                                </div>
                                <div class="baseCheck2">
                                    <input type="checkbox" name="experience[]" value="기상캐스터" id="exp3" disabled />
                                    <label for="exp3">기상캐스터</label>
                                </div>
                                <div class="baseCheck2">
                                    <input type="checkbox" name="experience[]" value="드론" id="exp4" disabled />
                                    <label for="exp4">드론</label>
                                </div>
                                <div class="baseCheck2">
                                    <input type="checkbox" name="experience[]" value="VR(가상현실)" id="exp5" disabled />
                                    <label for="exp5">VR(가상현실)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">연령대</div>
                        <div class="right">
                            <div class="baseInput">
                                <input type="text" name="age_group" placeholder="연령대를 입력해주세요.">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">희망 날짜<span>*</span></div>
                        <div class="right">
                            <div class="cmsDate solo">
                                <div class="baseInput">
                                    <input id="st1" name="desired_date" readonly type="text" title="시작날짜" value="" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">희망 이용시간<span>*</span></div>
                        <div class="right">
                            <div class="timeSel">
                                <div class="box">
                                    <div class="baseSel">
                                        <select name="start_hour">
                                            <option value="">선택</option>
                                            <?php for($i=9; $i<=18; $i++): ?>
                                                <option value="<?=str_pad($i, 2, '0', STR_PAD_LEFT)?>"><?=str_pad($i, 2, '0', STR_PAD_LEFT)?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="selTit">시</div>
                                    <div class="baseSel">
                                        <select name="start_minute">
                                            <option value="">선택</option>
                                            <option value="00">00</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                    <div class="selTit">분</div>
                                </div>
                                <div class="slash">~</div>
                                <div class="box">
                                    <div class="baseSel">
                                        <select name="end_hour">
                                            <option value="">선택</option>
                                            <?php for($i=9; $i<=18; $i++): ?>
                                                <option value="<?=str_pad($i, 2, '0', STR_PAD_LEFT)?>"><?=str_pad($i, 2, '0', STR_PAD_LEFT)?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="selTit">시</div>
                                    <div class="baseSel">
                                        <select name="end_minute">
                                            <option value="">선택</option>
                                            <option value="00">00</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                    <div class="selTit">분</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit">비고</div>
                        <div class="right">
                            <textarea name="contents" placeholder="내용을 입력해주세요." class="baseTextarea"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="formTit vt">신청경로</div>
                        <div class="right">
                            <div class="ckList">
                                <div class="baseCheck">
                                    <input type="checkbox" id="ck1" name="referral[]" value="홈페이지"/>
                                    <label for="ck1">홈페이지</label>
                                </div>
                                <div class="baseCheck long">
                                    <input type="checkbox" id="ck2" name="referral[]" value="블로그"/>
                                    <label for="ck2">블로그</label>
                                </div>
                                <div class="baseCheck">
                                    <input type="checkbox" id="ck3" name="referral[]" value="페이스북"/>
                                    <label for="ck3">페이스북</label>
                                </div>
                                <div class="baseCheck last">
                                    <input type="checkbox" id="ck4" name="referral[]" value="인스타그램"/>
                                    <label for="ck4">인스타그램</label>
                                </div>
                                <div class="baseCheck">
                                    <input type="checkbox" id="ck5" name="referral[]" value="당근마켓"/>
                                    <label for="ck5">당근마켓</label>
                                </div>
                                <div class="baseCheck long">
                                    <input type="checkbox" id="ck6" name="referral[]" value="카카오톡 채널"/>
                                    <label for="ck6">카카오톡 채널</label>
                                </div>
                                <div class="baseCheck">
                                    <input type="checkbox" id="ck7" name="referral[]" value="문자"/>
                                    <label for="ck7">문자</label>
                                </div>
                                <div class="baseCheck">
                                    <input type="checkbox" id="ck8" name="referral[]" value="이메일"/>
                                    <label for="ck8">이메일</label>
                                </div>
                                <div class="etcWrap">
                                    <div class="baseCheck">
                                        <input type="checkbox" id="ck9" name="referral[]" value="기타" onchange="toggleReferralOther(this)"/>
                                        <label for="ck9">기타</label>
                                    </div>
                                    <div class="baseInput">
                                        <input type="text" name="referral_other" id="referral_other" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- //formBox -->

                <div class="expAgree">
                    <div class="text">체험 신청은 아래와 같이 <br class="mob" />주안영상미디어센터 미디어체험을 신청하고 있습니다.<br /> 미디어 체험 운영을 위한 개인 정보 활용에 동의합니다.</div>
                    <div class="baseCheck">
                        <input type="checkbox" id="agree" />
                        <label for="agree">동의합니다</label>
                    </div>
                </div>
                <div class="btnOrder two">
                    <a href="javascript:void(0);" class="btnType1 gray" onclick="history.back();">취소하기</a>
                    <a href="#;" class="btnType1" onclick="if (validateForm()) { document.getElementById('enrollmentForm').submit(); }">신청하기</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- //Container -->


<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

</div>
<!-- //Wrap -->
<script>

    function toggleReferralOther(checkbox) {
        var referralOther = document.getElementById('referral_other');
        referralOther.disabled = !checkbox.checked;
    }

    function validateForm() {
        const name = document.querySelector('[name="name"]');
        if (!name.value) {
            alert('이름을 입력하지 않았습니다.');
            name.focus();
            return false;
        }

        const tel = document.querySelector('[name="tel"]');
        if (!tel.value) {
            alert('연락처를 입력하지 않았습니다.');
            tel.focus();
            return false;
        }

      /*  const email = document.querySelector('[name="email"]');
        if (!email.value) {
            alert('이메일을 입력하지 않았습니다.');
            email.focus();
            return false;
        }*/

        const totalMembers = document.querySelector('[name="total_members"]');
        if (!totalMembers.value) {
            alert('인원수를 입력하지 않았습니다.');
            totalMembers.focus();
            return false;
        }

        const experienceCheckboxes = document.querySelectorAll('input[name="experience[]"]');
        let experienceChecked = false;
        for (let checkbox of experienceCheckboxes) {
            if (checkbox.checked) {
                experienceChecked = true;
                break;
            }
        }

        if (!experienceChecked) {
            alert('체험분야를 선택하지 않았습니다.');
            experienceCheckboxes[0].focus();
            return false;
        }

        const desiredDate = document.querySelector('[name="desired_date"]');
        if (!desiredDate.value) {
            alert('희망 날짜를 입력하지 않았습니다.');
            desiredDate.focus();
            return false;
        }

        const startHour = document.querySelector('[name="start_hour"]');
        if (!startHour.value) {
            alert('희망 이용시간을 입력하지 않았습니다.');
            startHour.focus();
            return false;
        }

        const startMinute = document.querySelector('[name="start_minute"]');
        if (!startMinute.value) {
            alert('희망 이용시간을 입력하지 않았습니다.');
            startMinute.focus();
            return false;
        }

        const endHour = document.querySelector('[name="end_hour"]');
        if (!endHour.value) {
            alert('희망 이용시간을 입력하지 않았습니다.');
            endHour.focus();
            return false;
        }

        const agreeCheckbox = document.getElementById('agree');
        if (!agreeCheckbox.checked) {
            alert('개인 정보 활용에 동의하지 않았습니다.');
            agreeCheckbox.focus();
            return false;
        }

        return true;
    }

    function checkMembers() {
        const totalMembersInput = document.getElementById('total_members');
        const totalMembers = parseInt(totalMembersInput.value, 10) || 0;

        if (totalMembers > 30) {
            alert('최대 인원은 30명 입니다.');
            totalMembersInput.value = 0;
        }

        const tvBroadcasting = <?= $arrSetInfo["list"][0]["tv_broadcasting"] ?>;
        const radioBroadcasting = <?= $arrSetInfo["list"][0]["radio_broadcasting"] ?>;
        const weatherForecaster = <?= $arrSetInfo["list"][0]["weather_forecaster"] ?>;
        const drone = <?= $arrSetInfo["list"][0]["drone"] ?>;
        const vr = <?= $arrSetInfo["list"][0]["vr"] ?>;

        const totalRequired = tvBroadcasting + radioBroadcasting + weatherForecaster + drone + vr;

        document.getElementById('exp1').disabled = totalMembers < tvBroadcasting;
        document.getElementById('exp2').disabled = totalMembers < radioBroadcasting;
        document.getElementById('exp3').disabled = totalMembers < weatherForecaster;
        document.getElementById('exp4').disabled = totalMembers < drone;
        document.getElementById('exp5').disabled = totalMembers < 8 || totalMembers > 9;

        const allowedChecks = Math.floor(totalMembers / totalRequired * 5);

        const checkboxes = document.querySelectorAll('input[name="experience[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checkedCount = document.querySelectorAll('input[name="experience[]"]:checked').length;
                if (checkedCount >= allowedChecks) {
                    checkboxes.forEach(box => {
                        if (!box.checked) {
                            box.disabled = true;
                        }
                    });
                } else {
                    checkboxes.forEach(box => {
                        if (!box.checked) {
                            box.disabled = false;
                        }
                    });
                }
            });
        });
    }
</script>
</body>
</html>



