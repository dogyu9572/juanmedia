<?
include ("./header.php");
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);


if(!$_REQUEST[cal_date]){
    $cal_date = date("Y-m-d");
}else{
    $cal_date = $_REQUEST[cal_date];
}
//날짜를 - 구분자로 배열로 만듬
$arrDate = explode("-",$cal_date);

$arrSolarCalendar = getDiarySet(intval($arrDate[0]), intval($arrDate[1]), intval($arrDate[2]));
$arrBoardList = getBoardListSchedule("schedule", $arrSolarCalendar[first_before], $arrSolarCalendar[last_after]);

//DB해제
SetDisConn($dblink);
?>
    <link href="/css/board.css" rel="stylesheet" type="text/css" />


    <div id="admin-container">
        <div class="admin-snb">
            <div class="admin-snb-group">
                <div class="snb-title"><h2>기본설정</h2></div>
                <ul class="snb-menu">
                    <li><a href="/backoffice/module/admin/admin_set.php">기본정보 설정</a></li>
                </ul>
            </div>
            <div class="admin-snb-group">
                <div class="snb-title"><h2>게시판</h2></div>
                <ul class="snb-menu">
                    <li><a href="/backoffice/module/board/board.php">게시판 관리</a></li>
                    <!-- <li><a href="/backoffice/module/board/comment_list.php">댓글 등록</a></li> -->
                </ul>
            </div>
            <? include "admin_info.php"; ?>
        </div>
        <script>
            function viewCount(gb) {
                if(gb == "1") {
                    document.getElementById("tcounter").src="iframe_count1.php";
                } else if(gb == "2") {
                    document.getElementById("tcounter").src="iframe_count2.php";
                } else if(gb == "3") {
                    document.getElementById("tcounter").src="iframe_count3.php";
                }

            }
        </script>
        <div id="admin-content">
            <!--// content -->
            <div class="admin-title-top">
                <h2 class="admin-title">관리자 메인</h2>
                <div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 관리자 메인</div>
            </div>

            <div class="admin-top">
                <!-- 그래프 -->
                <div id="tabs" class="admin-graph">
                    <div class="admin-tab">
                        <ul class="tabe">
                            <li><a href="#tab1" onclick="viewCount(1)">접속현황</a></li>
                        </ul>
                    </div>

                    <iframe name="tcounter" id="tcounter" src="iframe_count1.php" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="1050" height="200"></iframe>
                </div>

                <!-- 일정달력 -->
                <script type="text/javascript">
                    jQuery(function(){
                        <?
                        //DB연결
                        $dblink = SetConn($_conf_db["main_db"]);

                        for($i=1; $i<32; $i++){
                        if($i<10) $i="0".$i;

                        $cdate = $arrDate[0]."-".$arrDate[1]."-".$i;
                        $week = date("w", mktime(0, 0, 0, $arrDate[1], $i, $arrDate[0]));
                        $arrInfo[$i] = getBoardArticleSchedule("schedule", $cdate);
                        ?>
                        jQuery('.calendar-table .date<?=$i?>').click(function(){
                            jQuery('.calendar-layer').show();
                            document.getElementById('ndateID').innerHTML = "<?=$arrDate[0]?>년 <?=$arrDate[1]?>월 <?=$i?>일 (<?=$_SITE["SHOP"]["WEEK"][$week]?>)";
                            document.getElementById('schedule_date').value = "<?=$cdate?>";
                            document.getElementById('idx').value = "<?=$arrInfo[$i]["list"][0][idx]?>";
                            document.getElementById('idx2').value = "<?=$arrInfo[$i]["list"][0][idx]?>";
                            document.getElementById('subject').value = "<?=$arrInfo[$i]["list"][0][subject]?>";
                            document.getElementById('contents').value = "<?=str_replace("\r\n", "<br>",$arrInfo[$i]["list"][0][contents])?>";
                            document.getElementById('contents').value = document.getElementById('contents').value.replace(/<br>/g, "\r\n");
                            <?if($arrInfo[$i]["list"][0][idx]) {?>
                            document.getElementById('evnMode').value = "modify";
                            <?}else{?>
                            document.getElementById('evnMode').value = "write";
                            <?}?>
                        });
                        <?}?>
                        jQuery('.calendar-layer .close').click(function(){
                            jQuery('.calendar-layer').hide();
                        });
                    });

                    function delSchedule() {
                        var cfm;
                        cfm =false;
                        cfm = confirm("해당 삭제 하시겠습니까?");
                        if(cfm==true){
                            document.sfrm2.submit();
                        }
                    }
                </script>
                <div class="admin-calendar">
                    <!-- 스케쥴등록 -->
                    <div class="calendar-layer">
                        <form name="sfrm"  method="post" action="/module/board/board_evn.php">
                            <input type="hidden" name="boardid" value="schedule">
                            <input type="hidden" name="idx" id="idx" value="">
                            <input type="hidden" name="schedule_date" id="schedule_date" value="">
                            <input type="hidden" name="evnMode" id="evnMode" value="write">
                            <input type="hidden" name="usehtml" value="N">
                            <input type="hidden" name="returnURL" value="/backoffice">
                            <div class="contents">
                                <p>
                                    <strong>일정등록</strong>
                                    <span class="ndateID" id="ndateID">2014년 07월 10일 (목)</span>
                                </p>
                                <fieldset>
                                    <legend>일정 등록 폼</legend>
                                    <table>
                                        <colgroup>
                                            <col width="18%" />
                                            <col />
                                        </colgroup>
                                        <tbody>
                                        <tr>
                                            <th scope="row">제목</th>
                                            <td><input type="text" name="subject" id="subject" class="input" style="width:250px;" /></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">내용</th>
                                            <td><textarea name="contents1" id="contents" cols="18" rows="5" class="textarea" style="width:248px;height:84px;"></textarea></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </fieldset>
                                <div class="admin-buttons">
                                    <div class="cen">
                                        <span class="btn_pack medium"><input type="submit" value="등록" /></span>
                                        <span class="btn_pack medium"><a href="javascript:delSchedule();">삭제</a></span>
                                    </div>
                                </div>
                                <button title="닫기" class="close" type="button">닫기</button>
                            </div>
                        </form>
                        <form name="sfrm2"  method="post" action="/module/board/board_evn.php">
                            <input type="hidden" name="boardid" value="schedule">
                            <input type="hidden" name="evnMode" value="delete">
                            <input type="hidden" name="idx" id="idx2" value="">
                            <input type="hidden" name="returnURL" value="/backoffice">
                        </form>
                    </div>
                    <!-- 달력 -->
                    <div class="calendar-table">
                        <div class="year">
                            <!-- <button title="이전년도" class="year prev" type="button">이전년도</button> -->
                            <strong><?=$arrDate[0]?></strong>
                            <!-- <button title="다음년도" class="year next" type="button">다음년도</button> -->
                        </div>
                        <div class="month">
                            <a href="<?=$_SERVER["PHP_SELF"]?>?cal_date=<?=$arrSolarCalendar[prev_month]?>"><button title="이전달" class="month prev" type="button">이전달</button></a>
                            <strong><?=$arrDate[1]?></strong>
                            <a href="<?=$_SERVER["PHP_SELF"]?>?cal_date=<?=$arrSolarCalendar[next_month]?>"><button title="다음달" class="month next" type="button">다음달</button></a>
                        </div>
                        <table summary="달력에서 일정 등록 및 확인이 가능합니다.">
                            <thead>
                            <tr>
                                <th class="sun" scope="col">SUN</th>
                                <th scope="col">MON</th>
                                <th scope="col">TUE</th>
                                <th scope="col">WED</th>
                                <th scope="col">THU</th>
                                <th scope="col">FRI</th>
                                <th class="sat" scope="col">SAT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?for($i=0;$i<count($arrSolarCalendar["box"]);$i++){?>
                                <tr>
                                    <?for($j=0;$j<7;$j++){
                                        //국경일, 법정공휴일, 일요일의 경우
                                        if($arrLunarCalendar[$arrSolarCalendar["box"][$i][$j]][holiday]=="1" || $j==0){
                                            $bgcolor = "sun";
                                            //토요일의 경우
                                        }else if($j==6){
                                            $bgcolor = "sat";
                                        }else{
                                            $bgcolor = "weekdays";
                                        }

                                        if($arrSolarCalendar["box"][$i][$j]==date("Y-m-d")) {
                                            $tdclass[$i][$j] .= "today";
                                        } else {
                                            $tdclass[$i][$j] .= "";
                                        }

                                        if(is_array($arrBoardList["list"][$arrSolarCalendar["box"][$i][$j]])){
                                            foreach($arrBoardList["list"][$arrSolarCalendar["box"][$i][$j]] AS $key => $val){
                                                $tdclass[$i][$j] .=" schedule-on";
                                            }
                                        } else {
                                            $tdclass[$i][$j] .="";
                                        }
                                        ?>
                                        <td class="<?=$tdclass[$i][$j]?>"><? if($arrDate[1]==substr($arrSolarCalendar["box"][$i][$j],5,2)) {?><a class="date<?=substr($arrSolarCalendar["box"][$i][$j],-2)?> <?=$bgcolor?>" style="cursor:pointer;"><?=substr($arrSolarCalendar["box"][$i][$j],-2)?></a><?}?></td>
                                    <?}?>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br />

        </div>
    </div>

<?
include ("./footer.php"); ?>