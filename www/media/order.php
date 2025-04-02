<?php include("../inc/header.php"); ?>
<?php $gNum = "04"; $sNum = "02"; $gName = "미디어 체험"; $sName = "체험신청";?>
<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

    function sanitizeInput($input) {
        global $dblink;

        if (is_array($input)) {
            return array_map('sanitizeInput', $input);
        }

        // SQL 인젝션 방지를 위한 이스케이프 처리
        if (isset($dblink) && $dblink) {
            return mysqli_real_escape_string($dblink, htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8'));
        }

        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    // POST 데이터 필터링
    $_POST = sanitizeInput($_POST);
    $dblink = SetConn($_conf_db["main_db"]);

    // GET 파라미터 필터링
    $_GET = sanitizeInput($_GET);
    // 또는 개별 처리
    $category = isset($_GET["category"]) ? sanitizeInput($_GET["category"]) : '';
    $sw = isset($_GET['sw']) ? sanitizeInput($_GET['sw']) : '';
    $sk = isset($_GET['sk']) ? sanitizeInput($_GET['sk']) : '';
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $reply = isset($_GET['reply']) ? sanitizeInput($_GET['reply']) : '';
    // 숫자 필드 정수형 변환 (integer로 확실하게 타입 변환)
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $total_members = isset($_POST['total_members']) ? intval($_POST['total_members']) : 0;

    // 이메일 필드 검증
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : '';
    if ($email === false) {
        // 오류 처리: 잘못된 이메일 형식
        die("올바른 이메일 형식이 아닙니다");
    }

    $arrBoardHolidayList = getBoardListBaseNFile("holiday", $category, $sw, $sk, $arrBoardInfo["list"][0]["scale"], $offset, $reply);
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

    $arrMediaApplicantsList = getBoardListBaseNFile("media_applicants", $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], $_GET['reply']);

    // 기존 예약 데이터를 JSON으로 변환
    $existingReservations = array_filter(array_map(function($item) {
        if (!empty($item['desired_date']) && $item['desired_date'] != '0000-00-00') {
            return [
                'desired_date' => $item['desired_date'],
                'start_time' => $item['start_hour'] . ':' . $item['start_minute'],
                'end_time' => $item['end_hour'] . ':' . $item['end_minute']
            ];
        }
        return null;
    }, $arrMediaApplicantsList['list']), function($item) {
        return $item !== null;
    });

    $reservationsJson = json_encode(array_values($existingReservations));

    //DB해제
    SetDisConn($dblink);
?>
<script>
    const holidayWeekdaysJson = <?= json_encode($holidayWeekdays) ?>;
    const specificHolidayDatesJson = <?= json_encode($specificHolidayDates) ?>;
    const existingReservations = <?= $reservationsJson ?>;
</script>
<script src="/js/calendar_sub.js"></script>
<!-- Container -->
<div class="container sub" id="container">

    <!-- subTopBg -->
    <div class="subTopBg media">
        <div class="inner">
            <div class="enName">MEDIA EXPERIENCE</div>
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
            <input type="hidden" name="birthdate" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["BIRTH"]?>">
            <input type="hidden" name="birthdate" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["BIRTH"]?>">
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
                                    <input name="desired_date" readonly type="text" title="시작날짜" value="" class="datepicker">
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
                    <div class="text">체험 신청은 아래와 같이 <br class="mob" />주안영상미디어센터 미디어 체험을 신청하고 있습니다.<br /> 미디어 체험 운영을 위한 개인 정보 활용에 동의합니다.</div>
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
        if (<?= $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] ?> == 6 ) {
            alert("정지 회원은 신청 불가능 합니다.");
            return;
        }
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

        // 시간 선택 유효성 검사 추가
        if (!validateTimeSelection()) {
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
        const checkboxes = document.querySelectorAll('input[name="experience[]"]');
        const minMembers = {
            exp1: parseInt('<?= $arrSetInfo["list"][0]["tv_broadcasting"] ?>'), // TV방송제작
            exp2: parseInt('<?= $arrSetInfo["list"][0]["radio_broadcasting"] ?>'), // 라디오 방송제작
            exp3: parseInt('<?= $arrSetInfo["list"][0]["weather_forecaster"] ?>'), // 기상캐스터
            exp4: parseInt('<?= $arrSetInfo["list"][0]["drone"] ?>'), // 드론
            exp5: parseInt('<?= $arrSetInfo["list"][0]["vr"] ?>') // VR
        };

        // 인원수 제한 체크
        if (totalMembers > 30) {
            alert('최대 인원은 30명 입니다.');
            totalMembersInput.value = 0;
            return;
        }

        // 모든 체크박스 초기화
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
            checkbox.disabled = true;
        });

       /* // 인원수가 최소 인원보다 적으면 체크박스 비활성화
        if (totalMembers >= 6 && totalMembers <= 8) {
            // VR만 활성화 (최소 인원 체크)
            if (totalMembers >= minMembers.exp5) {
                document.getElementById('exp5').disabled = false;
            }
        } else if (totalMembers >= 10 && totalMembers <= 19) {
            // TV방송제작, 라디오 방송제작, 기상캐스터, 드론 활성화 (최소 인원 체크)
            ['exp1', 'exp2', 'exp3', 'exp4'].forEach((id, index) => {
                const expMinMembers = minMembers[id];
                if (totalMembers >= expMinMembers) {
                    document.getElementById(id).disabled = false;
                }
            });
        } else if (totalMembers >= 20 && totalMembers <= 30) {
            // TV방송제작, 라디오 방송제작, 기상캐스터, 드론 활성화 (최소 인원 체크)
            ['exp1', 'exp2', 'exp3', 'exp4'].forEach((id, index) => {
                const expMinMembers = minMembers[id];
                if (totalMembers >= expMinMembers) {
                    document.getElementById(id).disabled = false;
                }
            });
        }*/

        // $arrSetInfo의 최소 인원 기준으로만 체크박스 활성화
        Object.keys(minMembers).forEach(expId => {
            if (totalMembers >= minMembers[expId]) {
                document.getElementById(expId).disabled = false;
            }
        });

        if (totalMembers >= 10) {
            document.getElementById('exp5').disabled = true;
        }

        // 체크박스 이벤트 설정
        checkboxes.forEach(checkbox => {
            checkbox.onclick = function() {
                const checked = document.querySelectorAll('input[name="experience[]"]:checked');
                const maxChecks = totalMembers >= 20 ? 3 : (totalMembers >= 10 ? 2 : 1);

                if (checked.length > maxChecks) {
                    this.checked = false;
                    alert(`최대 ${maxChecks}개까지 선택 가능합니다.`);
                }
            };
        });
    }

    function isTimeOverlap(date, startHour, startMinute, endHour, endMinute) {
        const selectedStart = startHour * 60 + parseInt(startMinute);
        const selectedEnd = endHour * 60 + parseInt(endMinute);

        // existingReservations가 객체인 경우 유효한 예약만 필터링
        for (let key in existingReservations) {
            const reservation = existingReservations[key];

            // total과 빈 데이터 건너뛰기
            if (key === 'total' ||
                !reservation.desired_date ||
                reservation.desired_date === '0000-00-00' ||
                !reservation.start_time ||
                reservation.start_time === ':') {
                continue;
            }

            // 같은 날짜인 경우에만 체크
            if (reservation.desired_date === date) {
                const [resStartHour, resStartMinute] = reservation.start_time.split(':');
                const [resEndHour, resEndMinute] = reservation.end_time.split(':');

                const existingStart = parseInt(resStartHour) * 60 + parseInt(resStartMinute);
                const existingEnd = parseInt(resEndHour) * 60 + parseInt(resEndMinute);

                // 시간 범위가 겹치는지 확인
                if (selectedStart < existingEnd && selectedEnd > existingStart) {
                    return true; // 중복 있음
                }
            }
        }
        return false; // 중복 없음
    }

    function validateTimeSelection() {
        const startHour = parseInt(document.querySelector('[name="start_hour"]').value);
        const startMinute = parseInt(document.querySelector('[name="start_minute"]').value);
        const endHour = parseInt(document.querySelector('[name="end_hour"]').value);
        const endMinute = parseInt(document.querySelector('[name="end_minute"]').value);
        const desiredDate = document.querySelector('[name="desired_date"]').value;
        const totalMembers = parseInt(document.getElementById('total_members').value) || 0;

        // 시간 중복 체크
        if (isTimeOverlap(desiredDate, startHour, startMinute, endHour, endMinute)) {
            alert('선택하신 날짜와 시간에 이미 예약이 있습니다. 다른 시간을 선택해주세요.');
            resetTimeSelections();
            return false;
        }

        // 시작 시간이나 종료 시간이 점심시간인 경우
        if ((startHour === 12) || (endHour === 12)) {
            alert('점심시간(12:00~13:00)은 선택할 수 없습니다.');
            resetTimeSelections();
            return false;
        }

        // 시작시간과 종료시간 사이에 점심시간이 포함되는 경우
        if (startHour < 12 && endHour > 12) {
            alert('점심시간(12:00~13:00)을 포함할 수 없습니다.');
            resetTimeSelections();
            return false;
        }

        // 시간 차이 계산 (분 단위)
        const startTime = startHour * 60 + (startMinute || 0);
        const endTime = endHour * 60 + (endMinute || 0);
        const timeDiff = endTime - startTime;

        // 인원수별 최대 이용시간 체크
        if (totalMembers >= 10 && totalMembers <= 19) {
            if (timeDiff > 120) { // 2시간 = 120분
                alert('10~19명은 최대 2시간까지만 이용 가능합니다.');
                resetTimeSelections();
                return false;
            }
        } else if (totalMembers >= 20 && totalMembers <= 30) {
            if (timeDiff > 180) { // 3시간 = 180분
                alert('20~30명은 최대 3시간까지만 이용 가능합니다.');
                resetTimeSelections();
                return false;
            }
        }

        return true;
    }

    function resetTimeSelections() {
        document.querySelector('[name="start_hour"]').value = "";
        document.querySelector('[name="start_minute"]').value = "";
        document.querySelector('[name="end_hour"]').value = "";
        document.querySelector('[name="end_minute"]').value = "";
    }

    // 시간 선택 이벤트 리스너 추가
    document.addEventListener('DOMContentLoaded', function() {
        const timeSelects = document.querySelectorAll('[name="start_hour"], [name="start_minute"], [name="end_hour"], [name="end_minute"]');
        timeSelects.forEach(select => {
            select.addEventListener('change', validateTimeSelection);
        });
    });

    // selectbox 옵션 수정
    const hourSelects = document.querySelectorAll('[name="start_hour"], [name="end_hour"]');
    hourSelects.forEach(select => {
        select.innerHTML = `
            <option value="">선택</option>
            <option value="09">09</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
        `;
    });

    $(document).ready(function(){
        $(document).ready(function(){
            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd',
                showMonthAfterYear: true,
                showOn: "both",
                buttonImage: "/images/icon_month.svg",
                buttonImageOnly: true,
                changeYear: true,
                changeMonth: true,
                yearRange: 'c-100:c+10',
                yearSuffix: "년 ",
                monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
                minDate: "+14d"
            });
        });
    });
</script>
</body>
</html>



