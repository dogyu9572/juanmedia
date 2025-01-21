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
                    <div style="min-height:100px;"><?=stripslashes($arrBoardArticle["list"][0]['contents'])?></div>
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
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php");
    $dayTypeMap = [
        'weekly' => '매주',
        'biweekly' => '격주',
        'other' => '기타'
    ];

    $imgsrc = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][0]['re_name'];

    ?>
    <!-- pageTitle -->
    <div class="pageTitle inner">수강 신청</div>
    <!-- //pageTitle -->

    <!-- subSec -->
    <div class="subSec last pt0	">
        <div class="inner">
            <!-- orderSide -->
            <div class="orderSide">
                <!-- detailWrap -->
                <div class="detailWrap">
                    <div class="detailTit">수강 정보</div>
                    <div class="simpleView">
                        <div class="simpleBox">
                            <div class="img"><img src="<?=$imgsrc?>" alt="섬네일"></div>
                            <div class="textWrap">
                                <div class="title"><?=stripslashes($arrBoardArticle["list"][0]['subject'])?></div>
                                <div class="info">
                                    <div class="tit">교육기간</div>
                                    <div class="txt"><?=$arrBoardArticle["list"][0]['e_start_date']?> ~ <?=$arrBoardArticle["list"][0]['e_end_date']?></div>
                                </div>
                                <div class="info">
                                    <div class="tit">구분</div>
                                    <div class="txt"><?=getCategoryName($arrBoardArticle["list"][0]['category1'])?> / <?=getCategoryName($arrBoardArticle["list"][0]['category2'])?></div>
                                </div>
                                <div class="info">
                                    <div class="tit">수강료</div>
                                    <div class="txt"><?=number_format($arrBoardArticle["list"][0]['fee'])?>원</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="detailTit">예약자 정보</div>

                    <!-- formBox -->
                    <form id="enrollmentForm" name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
                        <input type="hidden" name="boardid" value="edu_applicants">
<!--                        <input type="hidden" name="returnURL" value="--><?php //=$_SERVER["PHP_SELF"]?><!--?boardid=--><?php //=$arrBoardInfo["list"][0]["boardid"]?><!--&mode=list&category=--><?php //=$_GET['category']?><!--&offset=--><?php //=$_GET['offset']??""?><!--">-->
                        <input type="hidden" name="returnURL" value="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&cat_no=<?=$arrBoardArticle["list"][0]['category1']?>&offset=<?=$_GET['offset']??""?>">
                        <input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
                        <input type="hidden" id="totalamountInput" name="totalamount" value="<?=$arrBoardArticle["list"][0]['fee']?>">
                        <input type="hidden" id="discountamountInput" name="discountamount" value="0">
                        <input type="hidden" id="finalamountInput" name="finalamount" value="<?=$arrBoardArticle["list"][0]['fee']?>">
                        <input type="hidden" id="edu_no" name="edu_no" value="<?=$arrBoardArticle["list"][0]["edu_no"]?>">
                        <input type="hidden" id="category1" name="category1" value="<?=$arrBoardArticle["list"][0]['category1']?>">
                        <input type="hidden" id="category2" name="category2" value="<?=$arrBoardArticle["list"][0]['category2']?>">
                        <input type="hidden" id="subject" name="subject" value="<?=$arrBoardArticle["list"][0]['subject']?>">
                        <input type="hidden" id="capacity" name="capacity" value="<?=$arrBoardArticle["list"][0]['capacity']?>">
                        <input type="hidden" id="user_level" name="user_level" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"]?>">
                        <input type="hidden" id="w_user" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
                        <input type="hidden" id="edu_idx" name="edu_idx" value="<?=$arrBoardArticle["list"][0]['idx']?>">
                        <input type="hidden" name="usehtml" value="Y">
                        <?php if($_REQUEST['mode']=="reply"):?>
                            <input type="hidden" name="evnMode" value="reply">
                        <?php elseif($_REQUEST['mode']=="modify"):?>
                            <input type="hidden" name="evnMode" value="modify">
                        <?php else:?>
                            <input type="hidden" name="evnMode" value="write">
                        <?php endif;?>
                    <div class="formBox mb1">
                        <div class="row">
                            <div class="formTit">이름</div>
                            <div class="right">
                                <div class="baseInput">
                                    <input type="text" name="name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>" disabled>
                                    <input type="hidden" name="name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">휴대폰번호</div>
                            <div class="right">
                                <div class="baseInput">
                                    <input type="text" name="tel" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"]?>" disabled>
                                    <input type="hidden" name="tel" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"]?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">이메일주소</div>
                            <div class="right">
                                <div class="baseInput">
                                    <input type="text" name="email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["EMAIL"]?>" disabled>
                                    <input type="hidden" name="email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["EMAIL"]?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">생년월일</div>
                            <div class="right">
                                <div class="baseInput">
                                    <input type="text" name="birthdate" class="datepicker">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">할인적용</div>
                            <div class="right">
                                <div class="baseSel">
                                    <select id="discountSelect" name="discount" onchange="calculateDiscount()">
                                        <option value="">선택</option>
                                        <?php
                                        $discounts = [
                                            '일반' => 'edu_discount1',
                                            '경로우대' => 'edu_discount2',
                                            '국가유공자' => 'edu_discount3',
                                            '기초생활수급자' => 'edu_discount4',
                                            '장애인' => 'edu_discount5',
                                            '청소년' => 'edu_discount6',
                                            '한부모가족' => 'edu_discount7',
                                            '다문화가족' => 'edu_discount8',
                                            '다자녀가정' => 'edu_discount9',
                                            '새터민' => 'edu_discount10',
                                            '사회복지시설수용자' => 'edu_discount11',
                                            '정회원 지원' => 'edu_discount12',
                                            '동아리 지원' => 'edu_discount13',
                                            '교육 지원' => 'edu_discount14',
                                            '구청 지원' => 'edu_discount15',
                                            '공익 지원(공문)' => 'edu_discount16',
                                            '미추홀구민 할인' => 'edu_discount17',
                                        ];

                                        if (strpos($arrDiscountInfo['list'][0]["items"], '교육') !== false) {
                                            foreach ($discounts as $label => $name) {
                                                if ($arrDiscountInfo['list'][0][$name] == 'Y') {
                                                    $value = $arrDiscountInfo['list'][0][$name . '_value'];
                                                    echo "<option value='{$value}' data-label='{$label}'>{$label}</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" id="discountText" name="discount_text" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">증명서 제출</div>
                            <div class="right">
                                <div class="baseSel">
                                    <select name="certificate">
                                        <option value="">선택</option>
                                        <option value="온라인 제출">온라인 제출</option>
                                        <option value="현장 제출">현장 제출</option>
                                    </select>
                                </div>
                                <div class="fileAddWrap" id="fileAddWrap" style="display: none;">
                                    <div class="inputFile">
                                        <div class="fileInput">
                                            <button class="fileInputButton">파일 선택</button>
                                            <input type="file" name="upfiles[]" class="fileInputHidden" onchange="javascript: document.getElementById('fileName').value = this.value">
                                        </div>
                                        <input type="text" id="fileName" class="fileInputTextbox" readonly="readonly" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">비고</div>
                            <div class="right">
                                <div class="baseInput">
                                    <textarea name="contents" id="" cols="30" rows="10" class="text w100p" placeholder="내용을 입력해주세요."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">유입경로</div>
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
                </div>
                <!-- //detailWrap -->

                <!-- payWrap -->
                <div class="payWrap">
                    <div class="detailTit">결제 정보</div>
                    <div class="info">
                        <div class="tit">총 결제금액</div>
                        <div class="txt" id="totalamount" name="totalamount"><?=number_format($arrBoardArticle["list"][0]['fee'])?>원</div>
                    </div>
                    <div class="info">
                        <div class="tit">할인금액</div>
                        <div class="txt" id="discountamount" name="discountamount">0원</div>
                    </div>
                    <div class="info last">
                        <div class="tit">최종금액</div>
                        <div class="txt" id="finalamount" name="finalamount"><?=number_format($arrBoardArticle["list"][0]['fee'])?>원</div>
                    </div>
                    <a href="javascript:void(0);" class="btnType1" onclick="document.getElementById('enrollmentForm').submit();">수강 신청</a>
                </div>
                <!-- //payWrap -->
            </div>
            <!-- //orderSide -->
            </form>

        </div>
    </div>
    <!-- //subSec -->
    <script type="text/javascript">
        function toggleReferralOther(checkbox) {
            var referralOther = document.getElementById('referral_other');
            referralOther.disabled = !checkbox.checked;
        }

        document.querySelector('select[name="certificate"]').addEventListener('change', function() {
            var fileAddWrap = document.getElementById('fileAddWrap');
            if (this.value === '온라인 제출') {
                fileAddWrap.style.display = 'block';
            } else {
                fileAddWrap.style.display = 'none';
            }
        });

        document.getElementById('discountSelect').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var discountText = selectedOption.getAttribute('data-label');
            document.getElementById('discountText').value = discountText;
        });

        function calculateDiscount() {
            var totalamount = <?= $arrBoardArticle["list"][0]['fee'] ?>;
            var discountSelect = document.getElementById('discountSelect');
            var discountValue = discountSelect.value;
            var discountamount = 0;

            if (discountValue) {
                discountamount = totalamount * (parseFloat(discountValue) / 100);
            }

            var finalamount = totalamount - discountamount;

            document.getElementById('discountamount').innerText = discountamount.toLocaleString() + '원';
            document.getElementById('finalamount').innerText = finalamount.toLocaleString() + '원';

            // Update hidden input fields
            document.getElementById('totalamountInput').value = totalamount;
            document.getElementById('discountamountInput').value = discountamount;
            document.getElementById('finalamountInput').value = finalamount;
        }

        document.getElementById('discountSelect').addEventListener('change', calculateDiscount);

        document.getElementById('discountSelect').addEventListener('change', calculateDiscount);
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

<?}###################################################### 사용자 페이지 ###################################################### END ?>