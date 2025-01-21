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
    $dayTypeMap = [
        'weekly' => '매주',
        'biweekly' => '격주',
        'other' => '기타'
    ];

    $imgsrc = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][0]['re_name'];
    $capacity = $arrBoardArticle["list"][0]['capacity'];
    ?>
    <!-- pageTitle -->
    <div class="pageTitle inner">공간대관 신청</div>
    <!-- //pageTitle -->

    <!-- subSec -->
    <div class="subSec pt0 last">
        <div class="inner">
            <!-- orderSide -->
            <div class="orderSide">
                <!-- detailWrap -->
                <div class="detailWrap">
                    <div class="detailTit">공간 정보</div>
                    <div class="simpleView">
                        <div class="simpleBox">
                            <div class="img"><img src="<?=$imgsrc?>" alt="섬네일"></div>
                            <div class="textWrap">
                                <div class="title"><?=stripslashes($arrBoardArticle["list"][0]['subject'])?></div>
                                <div class="info">
                                    <div class="tit">대관일</div>
                                    <div class="txt"><?=$_POST["rental_date"]?></div>
                                </div>
                                <div class="info">
                                    <div class="tit">대관 시간</div>
                                    <div class="txt"><?=$_POST["rental_start_time"]?> ~ <?=$_POST["rental_end_time"]?></div>
                                </div>
                                <div class="info">
                                    <div class="tit">대관금액</div>
                                    <div class="txt"><?=number_format($_POST["totalamount"])?>원</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="detailTit">예약자 정보</div>

                    <!-- formBox -->
                    <form id="enrollmentForm" name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
                        <input type="hidden" name="boardid" value="place_applicants">
                        <!--                        <input type="hidden" name="returnURL" value="--><?php //=$_SERVER["PHP_SELF"]?><!--?boardid=--><?php //=$arrBoardInfo["list"][0]["boardid"]?><!--&mode=list&category=--><?php //=$_GET['category']?><!--&offset=--><?php //=$_GET['offset']??""?><!--">-->
                        <input type="hidden" name="returnURL" value="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&cat_no=<?=$arrBoardArticle["list"][0]['category1']?>&offset=<?=$_GET['offset']??""?>">
                        <input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
                        <input type="hidden" id="totalamountInput" name="totalamount" value="<?=$_POST["totalamount"]?>">
                        <input type="hidden" id="discountamountInput" name="discountamount" value="0">
                        <input type="hidden" id="finalamountInput" name="finalamount" value="<?=$_POST["totalamount"]?>">
                        <input type="hidden" id="subject" name="subject" value="<?=$arrBoardArticle["list"][0]['subject']?>">
                        <input type="hidden" id="rental_date" name="rental_date" value="<?=$_POST["rental_date"]?>">
                        <input type="hidden" id="rental_start_time" name="rental_start_time" value="<?=$_POST["rental_start_time"]?>">
                        <input type="hidden" id="rental_end_time" name="rental_end_time" value="<?=$_POST["rental_end_time"]?>">
                        <input type="hidden" id="usage_time" name="usage_time" value="<?=$_POST["usage_time"]?>">
                        <input type="hidden" id="capacity" name="capacity" value="<?=$arrBoardArticle["list"][0]['capacity']?>">
                        <input type="hidden" id="place_idx" name="place_idx" value="<?=$arrBoardArticle["list"][0]['idx']?>">
                        <input type="hidden" id="user_level" name="user_level" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"]?>">
                        <input type="hidden" id="category1" name="category1" value="<?=$arrBoardArticle["list"][0]['category1']?>">
                        <input type="hidden" id="category2" name="category2" value="<?=$arrBoardArticle["list"][0]['category2']?>">
                        <input type="hidden" id="w_user" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
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
                                                '토요일' => 'place_discount1',
                                                '일요일' => 'place_discount2',
                                                '일반' => 'place_discount3',
                                                '경로우대' => 'place_discount4',
                                                '국가유공자' => 'place_discount5',
                                                '기초생활수급자' => 'place_discount6',
                                                '장애인' => 'place_discount7',
                                                '청소년' => 'place_discount8',
                                                '한부모가족' => 'place_discount9',
                                                '다문화가족' => 'place_discount10',
                                                '다자녀가정' => 'place_discount11',
                                                '새터민' => 'place_discount12',
                                                '사회복지시설수용자' => 'place_discount13',
                                                '정회원 지원' => 'place_discount14',
                                                '동아리 지원' => 'place_discount15',
                                                '교육 지원' => 'place_discount16',
                                                '구청 지원' => 'place_discount17',
                                                '공익 지원(공문)' => 'place_discount18',
                                                '미추홀구민 할인' => 'place_discount19',
                                            ];
                                            
                                            if (strpos($arrDiscountInfo['list'][0]["items"], '공간') !== false) {
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
                                <div class="formTit">사용인원</div>
                                <div class="right">
                                    <div class="baseSel">
                                        <select name="usage_people">
                                            <option value="">선택</option>
                                            <?php for ($i = 1; $i <= $capacity; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?>명</option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="formTit">사용목적</div>
                            <div class="right">
                                <div class="baseInput">
                                    <input type="text" name="usage_purpose">
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
                    </div>
                    <!-- //formBox -->

                    <!-- <div class="detailTit">결제수단 선택</div> -->
                    <!-- formBox -->
                    <!-- <div class="formBox">
                        <div class="row">
                            <div class="formTit">결제수단</div>
                            <div class="right">
                                <div class="radioList">
                                    <div class="baseRadio">
                                        <input type="radio" name="pay" id="radio1" />
                                        <label for="radio1">무통장 입금</label>
                                    </div>
                                    <div class="baseRadio">
                                        <input type="radio" name="pay" id="radio2" />
                                        <label for="radio2">실시간 계좌이체</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- //formBox -->
                </div>
                <!-- //detailWrap -->


                <!-- payWrap -->
                <div class="payWrap">
                    <div class="detailTit">결제 정보</div>
                    <div class="info">
                        <div class="tit">총 대관금액</div>
                        <div class="txt" id="totalamount" name="totalamount"><?=number_format($_POST["totalamount"])?>원</div>
                    </div>
                    <div class="info">
                        <div class="tit">할인금액</div>
                        <div class="txt" id="discountamount" name="discountamount">0원</div>
                    </div>
                    <div class="info last">
                        <div class="tit">최종금액</div>
                        <div class="txt" id="finalamount" name="finalamount"><?=number_format($_POST["totalamount"])?>원</div>
                    </div>
                    <a href="javascript:void(0);" class="btnType1" onclick="document.getElementById('enrollmentForm').submit();">대관 신청</a>
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
            var totalamount = <?=$_POST["totalamount"]?>;
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