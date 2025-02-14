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

    if (empty($_POST) && !isset($_GET['applicants_idx'])) { // 장비 대여 신청 페이지로 직접 접근한 경우
        jsGo("/equ/list.php?boardid=equ&mode=view&idx=".$_GET['idx']);
    }

    $subQuery = "AND equ_idx={$_GET["idx"]} AND (rental_start_date <= '{$_POST["rental_end_date"]}' AND rental_end_date >= '{$_POST["rental_start_date"]}')";
    $arrBoardEquList = getBoardListBase("equ_applicants", "", "", "", 100, 0, $subQuery, "", "");

    if($arrBoardEquList["total"] >= $arrSetInfo["list"][0]["equ_max_rental_count"]){
        jsMsg("대여 가능한 장비의 수량을 초과하였습니다.");
        jsHistory("-1");
    }

    $referralArray = explode('|', $arrBoardApplicantsArticle["list"][0]['referral']);
    $imgsrc = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][0]['re_name'];

    ?>
    <!-- pageTitle -->
    <div class="pageTitle inner only">장비대여 신청</div>
    <!-- //pageTitle -->

    <!-- subSec -->
    <div class="subSec last pt0">
        <div class="inner">

            <!-- orderSide -->
            <div class="orderSide">
                <!-- detailWrap -->
                <div class="detailWrap">
                    <div class="detailTit">장비 정보</div>
                    <div class="simpleView">
	                    <?php if (isset($_GET["idx"])): ?>
                        <div class="simpleBox">
                            <div class="img"><img src="<?=$imgsrc?>" alt="섬네일"></div>
                            <div class="textWrap">
                                <div class="title"><?=stripslashes($arrBoardArticle["list"][0]['subject'])?></div>
                                <div class="info">
                                    <div class="tit">장비번호</div>
                                    <div class="txt"><?=stripslashes($arrBoardArticle["list"][0]['equ_number'])?></div>
                                </div>
                                <div class="info">
                                    <div class="tit">대여일/반납일</div>
                                    <div class="txt">
                                        <?= isset($arrBoardApplicantsArticle["list"][0]["rental_start_date"]) ? $arrBoardApplicantsArticle["list"][0]["rental_start_date"] : $_POST["rental_start_date"] ?> ~
                                        <?= isset($arrBoardApplicantsArticle["list"][0]["rental_end_date"]) ? $arrBoardApplicantsArticle["list"][0]["rental_end_date"] : $_POST["rental_end_date"] ?>
                                        (<?= isset($arrBoardApplicantsArticle["list"][0]["usage_day"]) ? $arrBoardApplicantsArticle["list"][0]["usage_day"] : $_POST["usage_day"] ?>일)
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="tit">대여금액</div>
                                    <div class="txt">
                                        <?= isset($arrBoardApplicantsArticle["list"][0]["totalamount"]) ? number_format($arrBoardApplicantsArticle["list"][0]["totalamount"]) : number_format($_POST["totalamount"]) ?>원
                                    </div>
                                </div>
                            </div>
                        </div>
	                        <?php else:
	                             $totalAmount = 0;
                                if($arrBoardList["list"]["total"] > 0){
                                    for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
                                        $subQuery = "AND equ_idx={$arrBoardList["list"][$i]['equ_idx']} AND (rental_start_date <= '{$arrBoardList["list"][$i]['rental_start_date']}' AND rental_end_date >= '{$arrBoardList["list"][$i]['rental_end_date']}')";
                                        $arrBoardEquList = getBoardListBase("equ_applicants", "", "", "", 100, 0, $subQuery, "", "");

                                        if($arrBoardEquList["total"] >= $arrSetInfo["list"][0]["equ_max_rental_count"]){
                                            jsMsg($arrBoardList["list"][$i]['subject']. " 장비가 대여 가능한 장비의 수량을 초과하였습니다.");
                                            jsHistory("-1");
                                        }

                                    $imgsrc_order[$i] = "/uploaded/board/equ/".$arrBoardList["list"][$i]['re_name'];
                                    ############################ 파일 확인 #############################

                                    $arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], "", $arrBoardList["list"][$i]['idx'],"list");
                                    for($j=0;$j<$arrBoardArticle["total_files"];$j++){
                                        if(substr($arrBoardArticle["files"][$j]['re_name'],0,2) != "l_"){
                                            $fileImg[$i] = '<img src="/backoffice/pub_old/images/file.png">';
                                        }
                                    }
                                    $totalAmount += $arrBoardList["list"][$i]['totalamount'];
	                        ?>
                        <div class="simpleBox">
                            <div class="img"><img src="<?=$imgsrc_order[$i]?>" alt="섬네일"></div>
                            <div class="textWrap">
                                <div class="title"><?=stripslashes($arrBoardList["list"][$i]['subject'])?></div>
                                <div class="info">
                                    <div class="tit">장비번호</div>
                                    <div class="txt"><?=stripslashes($arrBoardList["list"][$i]['equ_number'])?></div>
                                </div>
                                <div class="info">
                                    <div class="tit">대여일/반납일</div>
                                    <div class="txt"><?=$arrBoardList["list"][$i]['rental_start_date']?> ~ <?=$arrBoardList["list"][$i]['rental_end_date']?> (<?=$arrBoardList["list"][$i]['usage_day']?>일)</div>
                                </div>
                                <div class="info">
                                    <div class="tit">대여금액</div>
                                    <div class="txt"><?=number_format($arrBoardList["list"][$i]['totalamount'])?>원</div>
                                </div>
                            </div>
                        </div>

	                        <?php
                                }
                            }
                                endif; ?>
                    </div>
                    <form id="enrollmentForm" name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
                        <input type="hidden" name="boardid" value="equ_applicants">
                        <input type="hidden" name="returnURL" value="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&cat_no=<?=$arrBoardArticle["list"][0]['category1']?>&offset=<?=$_GET['offset']??""?>">
                        <input type="hidden" id="user_level" name="user_level" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"]?>">
                        <input type="hidden" id="w_user" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
                        <input type="hidden" name="birthdate" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["BIRTH"]?>">
                        <input type="hidden" name="usehtml" value="Y">

		                <?php if (isset($_GET["idx"])): ?>
                            <!-- 단일 제품 대여일 경우 -->
                            <input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
                            <input type="hidden" id="subject" name="subject" value="<?=$arrBoardArticle["list"][0]['subject']?>">
                            <input type="hidden" id="category1" name="category1" value="<?=$arrBoardArticle["list"][0]['category1']?>">
                            <input type="hidden" id="category2" name="category2" value="<?=$arrBoardArticle["list"][0]['category2']?>">
                            <input type="hidden" id="rental_start_date" name="rental_start_date" value="<?= isset($_POST["rental_start_date"]) ? $_POST["rental_start_date"] : (isset($arrBoardApplicantsArticle["list"][0]["rental_start_date"]) ? $arrBoardApplicantsArticle["list"][0]["rental_start_date"] : '') ?>">
                            <input type="hidden" id="rental_end_date" name="rental_end_date" value="<?= isset($_POST["rental_end_date"]) ? $_POST["rental_end_date"] : (isset($arrBoardApplicantsArticle["list"][0]["rental_end_date"]) ? $arrBoardApplicantsArticle["list"][0]["rental_end_date"] : '') ?>">
                            <input type="hidden" id="usage_time" name="usage_time" value="<?= isset($_POST["usage_time"]) ? $_POST["usage_time"] : (isset($arrBoardApplicantsArticle["list"][0]["usage_time"]) ? $arrBoardApplicantsArticle["list"][0]["usage_time"] : '') ?>">
                            <input type="hidden" id="usage_day" name="usage_day" value="<?= isset($_POST["usage_day"]) ? $_POST["usage_day"] : (isset($arrBoardApplicantsArticle["list"][0]["usage_day"]) ? $arrBoardApplicantsArticle["list"][0]["usage_day"] : '') ?>">
                            <input type="hidden" id="totalamountInput" name="totalamount" value="<?= isset($arrBoardApplicantsArticle["list"][0]['totalamount']) ? $arrBoardApplicantsArticle["list"][0]['totalamount'] : $_POST["totalamount"] ?>">
                            <input type="hidden" id="discountamountInput" name="discountamount" value="<?= isset($arrBoardApplicantsArticle["list"][0]['discountamount']) ? $arrBoardApplicantsArticle["list"][0]['discountamount'] : '0' ?>">
                            <input type="hidden" id="finalamountInput" name="finalamount" value="<?= isset($arrBoardApplicantsArticle["list"][0]['finalamount']) ? $arrBoardApplicantsArticle["list"][0]['finalamount'] : $_POST["totalamount"] ?>">
                            <input type="hidden" id="equ_idx" name="equ_idx" value="<?=$_GET["idx"]?>">

                            <!-- 기타 필요한 제품 정보들 -->
		                <?php else: ?>
                            <!-- 장바구니에서 여러 제품 대여일 경우 -->
			                <?php for($i=0; $i < $arrBoardList["list"]["total"]; $i++): ?>
                                <input type="hidden" name="items[<?=$i?>][idx]" value="<?=$arrBoardList["list"][$i]['idx']?>">
                                <input type="hidden" name="items[<?=$i?>][subject]" value="<?=$arrBoardList["list"][$i]['subject']?>">
                                <input type="hidden" name="items[<?=$i?>][category1]" value="<?=$arrBoardList["list"][$i]['category1']?>">
                                <input type="hidden" name="items[<?=$i?>][category2]" value="<?=$arrBoardList["list"][$i]['category2']?>">
                                <input type="hidden" name="items[<?=$i?>][rental_start_date]" value="<?=$arrBoardList["list"][$i]['rental_start_date']?>">
                                <input type="hidden" name="items[<?=$i?>][rental_end_date]" value="<?=$arrBoardList["list"][$i]['rental_end_date']?>">
                                <input type="hidden" id="usage_day" name="items[<?=$i?>][usage_day]" value="<?=$arrBoardList["list"][$i]["usage_day"]?>">
                                <input type="hidden" id="totalamount" name="items[<?=$i?>][totalamount]" value="<?=$arrBoardList["list"][$i]['totalamount']?>">
                                <input type="hidden" id="totalamountInput" name="totalamountInput" value="<?=$arrBoardList["list"][$i]['totalamount']?>">
                                <input type="hidden" id="finalamountInput" name="finalamountInput" value="<?=$arrBoardList["list"][$i]['totalamount']?>>">
                                <input type="hidden" id="discountamountInput" name="discountamountInput" value="0">
                                <input type="hidden" id="gender" name="gender" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["GENDER"]?>">
                                <!-- 기타 필요한 제품 정보들 -->
			                <?php endfor; ?>
		                <?php endif; ?>

		                <?php if($_REQUEST['mode']=="reply"):?>
                            <input type="hidden" name="evnMode" value="reply">
		                <?php elseif($_REQUEST['mode']=="modify"):?>
                            <input type="hidden" name="evnMode" value="modify">
                        <?php elseif($_GET['applicants_idx']):?>
                            <input type="hidden" name="evnMode" value="modify">
                            <input type="hidden" name="idx" value="<?=$_GET['applicants_idx']?>">
                            <input type="hidden" name="status" value="<?=$arrBoardApplicantsArticle["list"][0]["status"]?>">
		                <?php else:?>
                            <input type="hidden" name="evnMode" value="write">
		                <?php endif;?>

                    <div class="detailTit">대여 정보</div>

                    <!-- formBox -->
                    <div class="formBox mb1">
                        <div class="row">
                            <div class="formTit">대여 방문시간</div>
                            <div class="right">
                                <div class="baseSel">
                                    <select name="rental_start_time" id="rental_start_time">
                                        <option value="">선택</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">반납 방문시간</div>
                            <div class="right">
                                <div class="baseSel">
                                    <select name="rental_end_time" id="rental_end_time">
                                        <option value="">선택</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //formBox -->


                    <div class="detailTit">예약자 정보</div>

                    <!-- formBox -->
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
                                    <input type="text" name="email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>" disabled>
                                    <input type="hidden" name="email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
                                </div>
                            </div>
                        </div>
                        <!--<div class="row">
                            <div class="formTit">생년월일</div>
                            <div class="right">
                                <div class="baseInput">
                                    <input type="text" name="birthdate"  value="<?php /*=$arrBoardApplicantsArticle["list"][0]["birthdate"]*/?>" class="datepicker">
                                </div>
                            </div>
                        </div>-->
                        <div class="row">
                            <div class="formTit">할인적용</div>
                            <div class="right">
                                <div class="baseSel">
                                    <select id="discountSelect" name="discount" onchange="calculateDiscount()">
                                        <option value="">선택</option>
                                        <option value="0%" data-label='해당없음' <?= $arrBoardApplicantsArticle["list"][0]['discount_text'] == '해당없음' ? 'selected' : '' ?>>해당없음</option>
                                        <?php
                                        $discounts = [
                                            '장기대여' => 'equ_discount1',
                                            '휴관일' => 'equ_discount2',
                                            '토요일' => 'equ_discount3',
                                            '일요일' => 'equ_discount4',
                                            '일반' => 'equ_discount5',
                                            '경로우대' => 'equ_discount6',
                                            '국가유공자' => 'equ_discount7',
                                            '기초생활수급자' => 'equ_discount8',
                                            '장애인' => 'equ_discount9',
                                            '청소년' => 'equ_discount10',
                                            '한부모가족' => 'equ_discount11',
                                            '다문화가족' => 'equ_discount12',
                                            '다자녀가정' => 'equ_discount13',
                                            '새터민' => 'equ_discount14',
                                            '사회복지시설거주자' => 'equ_discount15',
                                            '정회원 지원' => 'equ_discount16',
                                            '동아리 지원' => 'equ_discount17',
                                            '교육 지원' => 'equ_discount18',
                                            '구청 지원' => 'equ_discount19',
                                            '공익 지원(공문)' => 'equ_discount20',
                                            '미추홀구민 할인' => 'equ_discount21',
                                        '미추홀구민 시민리포터' => 'equ_discount22',
                                        ];

                                        foreach ($discounts as $label => $name) {
                                            if ($arrDiscountInfo['list'][0][$name] == 'Y') {
                                                $value = $arrDiscountInfo['list'][0][$name . '_value'];
                                                $selected = ($arrBoardApplicantsArticle["list"][0]['discount_text'] == $label) ? 'selected' : '';
                                                echo "<option value='{$value}' data-label='{$label}' {$selected}>{$label}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" id="discountText" name="discount_text" value="<?=$arrBoardApplicantsArticle["list"][0]['discount_text']?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">증명서 제출</div>
                            <div class="right">
                                <div class="baseSel">
                                    <select name="certificate">
                                        <option value="">선택</option>
                                        <option value="해당 사항 없음" <?= $arrBoardApplicantsArticle["list"][0]['certificate'] == '해당 사항 없음' ? 'selected' : '' ?>>해당 사항 없음</option>
                                        <option value="온라인 제출" <?= $arrBoardApplicantsArticle["list"][0]['certificate'] == '온라인 제출' ? 'selected' : '' ?>>온라인 제출</option>
                                        <option value="현장 제출" <?= $arrBoardApplicantsArticle["list"][0]['certificate'] == '현장 제출' ? 'selected' : '' ?>>현장 제출</option>
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
                                <?
                                if($arrBoardApplicantsArticle["total_files"]>0 ){
                                    ?>
                                    <table id="files_list" border="0" cellpadding="3" cellspacing="1" width="100%" style="padding:1%">
                                        <tbody>
                                        <?
                                        for($i=0;$i<$arrBoardApplicantsArticle["total_files"];$i++){
                                            if(substr($arrBoardApplicantsArticle["files"][$i]['re_name'],0,2) != "l_" && substr($arrBoardApplicantsArticle["files"][$i]['re_name'],0,2) != "v_") {
                                                ?>
                                                <tr>
                                                    <td><label class="check"><input type="checkbox" name="filedel[]" value="<?=$arrBoardApplicantsArticle["files"][$i]['idx']?>"><i></i>삭제</label>
                                                        file :  <a href="javascript:void(0);" onclick="fileDownload('<?=$arrBoardApplicantsArticle["files"][$i]['boardid']?>','<?=$arrBoardApplicantsArticle["files"][$i]['b_idx']?>','<?=$arrBoardApplicantsArticle["files"][$i]['idx']?>');"><?=$arrBoardApplicantsArticle["files"][$i]['ori_name']?></a>
                                                    </td>
                                                </tr>
                                                <?
                                            }
                                        }?>
                                        </tbody>
                                    </table>
                                <?}?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">사용인원</div>
                            <div class="right">
                                <div class="baseSel">
                                    <select name="usage_people">
                                        <option value="">선택</option>
                                        <?php for ($i = 1; $i <= 50; $i++): ?>
                                            <option value="<?= $i ?>" <?= $arrBoardApplicantsArticle["list"][0]["usage_people"] == $i ? 'selected' : '' ?>><?= $i ?>명</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">사용목적</div>
                            <div class="right">
                                <div class="baseInput">
                                    <input type="text" name="usage_purpose"  value="<?=$arrBoardApplicantsArticle["list"][0]["usage_purpose"]?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="formTit">비고</div>
                            <div class="right">
                                <div class="baseInput">
                                    <textarea name="contents" id="" cols="30" rows="10" class="text w100p" placeholder="내용을 입력해주세요."><?=$arrBoardApplicantsArticle["list"][0]["contents"]?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //formBox -->

                    <!-- //formBox -->
                </div>
                <!-- //detailWrap -->

                <!-- payWrap -->
                <div class="payWrap">
                    <div class="detailTit">결제 정보</div>
                    <div class="info">
                        <div class="tit">총 대여금액</div>
                        <div class="txt" id="totalamount" name="totalamount">
                            <?= isset($arrBoardApplicantsArticle["list"][0]['totalamount']) ? number_format($arrBoardApplicantsArticle["list"][0]['totalamount']) : number_format($_POST["totalamount"]) ?>원
                        </div>
                    </div>
                    <div class="info">
                        <div class="tit">할인금액</div>
                        <div class="txt" id="discountamount" name="discountamount">
                            <?= isset($arrBoardApplicantsArticle["list"][0]['discountamount']) ? number_format($arrBoardApplicantsArticle["list"][0]['discountamount']) : '0' ?>원
                        </div>
                    </div>
                    <div class="info last">
                        <div class="tit">최종금액</div>
                        <div class="txt" id="finalamount" name="finalamount">
                            <?= isset($arrBoardApplicantsArticle["list"][0]['finalamount']) ? number_format($arrBoardApplicantsArticle["list"][0]['finalamount']) : number_format($_POST["totalamount"]) ?>원
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="btnType1" onclick="if (validateForm()) { document.getElementById('enrollmentForm').submit(); }">대여 신청</a>

                </div>
                <!-- //payWrap -->
            </div>
            <!-- //orderSide -->
            </form>

        </div>
    </div>
    <!-- //subSec -->
    <script type="text/javascript">
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
            var totalamount = <?= isset($_POST["totalamount"]) ? $_POST["totalamount"] : (isset($arrBoardApplicantsArticle["list"][0]["totalamount"]) ? $arrBoardApplicantsArticle["list"][0]["totalamount"] : $totalAmount) ?>;
            var discountSelect = document.getElementById('discountSelect');
            var discountValue = discountSelect.value;
            var discountamount = 0;

            if (discountValue && discountValue !== '0%') {
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
        document.addEventListener('DOMContentLoaded', function() {
            const rentalUse = '<?=$arrSetInfo["list"][0]["equ_rental_use"]?>';
            const returnUse = '<?=$arrSetInfo["list"][0]["equ_return_use"]?>';
            const lunchUse = '<?=$arrSetInfo["list"][0]["equ_lunch_use"]?>';
            const dinnerUse = '<?=$arrSetInfo["list"][0]["equ_dinner_use"]?>';

            const lunchStartTime = '<?=$arrSetInfo["list"][0]["equ_lunch_start_time"]?>';
            const lunchEndTime = '<?=$arrSetInfo["list"][0]["equ_lunch_end_time"]?>';
            const dinnerStartTime = '<?=$arrSetInfo["list"][0]["equ_dinner_start_time"]?>';
            const dinnerEndTime = '<?=$arrSetInfo["list"][0]["equ_dinner_end_time"]?>';

            const rentalStartTimeValue = '<?=$arrBoardApplicantsArticle["list"][0]["rental_start_time"]?>';
            const rentalEndTimeValue = '<?=$arrBoardApplicantsArticle["list"][0]["rental_end_time"]?>';

            if (rentalUse === 'Y') {
                const rentalStartTime = '<?=$arrSetInfo["list"][0]["equ_rental_start_time"]?>';
                const rentalEndTime = '<?=$arrSetInfo["list"][0]["equ_rental_end_time"]?>';
                setTimeRange('rental_start_time', rentalStartTime, rentalEndTime, lunchUse, lunchStartTime, lunchEndTime, dinnerUse, dinnerStartTime, dinnerEndTime, rentalStartTimeValue);
            } else {
                setTimeRange('rental_start_time', '09:00', '22:00', lunchUse, lunchStartTime, lunchEndTime, dinnerUse, dinnerStartTime, dinnerEndTime, rentalStartTimeValue);
            }

            if (returnUse === 'Y') {
                const returnStartTime = '<?=$arrSetInfo["list"][0]["equ_return_start_time"]?>';
                const returnEndTime = '<?=$arrSetInfo["list"][0]["equ_return_end_time"]?>';
                setTimeRange('rental_end_time', returnStartTime, returnEndTime, lunchUse, lunchStartTime, lunchEndTime, dinnerUse, dinnerStartTime, dinnerEndTime, rentalEndTimeValue);
            } else {
                setTimeRange('rental_end_time', '09:00', '22:00', lunchUse, lunchStartTime, lunchEndTime, dinnerUse, dinnerStartTime, dinnerEndTime, rentalEndTimeValue);
            }

            function setTimeRange(elementId, startTime, endTime, lunchUse, lunchStartTime, lunchEndTime, dinnerUse, dinnerStartTime, dinnerEndTime, selectedValue) {
                const $select = document.getElementById(elementId);
                if (!$select) {
                    console.error(`Element with id ${elementId} not found.`);
                    return;
                }
                const startHour = parseInt(startTime.split(':')[0]);
                const endHour = parseInt(endTime.split(':')[0]);
                const lunchStartHour = lunchUse === 'Y' ? parseInt(lunchStartTime.split(':')[0]) : null;
                const lunchEndHour = lunchUse === 'Y' ? parseInt(lunchEndTime.split(':')[0]) : null;
                const dinnerStartHour = dinnerUse === 'Y' ? parseInt(dinnerStartTime.split(':')[0]) : null;
                const dinnerEndHour = dinnerUse === 'Y' ? parseInt(dinnerEndTime.split(':')[0]) : null;

                for (let hour = startHour; hour <= endHour; hour++) {
                    if ((lunchUse === 'Y' && hour >= lunchStartHour && hour < lunchEndHour) ||
                        (dinnerUse === 'Y' && hour >= dinnerStartHour && hour < dinnerEndHour)) {
                        continue;
                    }
                    const timeText = `${hour}:00`;
                    const option = document.createElement('option');
                    option.value = timeText;
                    option.textContent = timeText;
                    if (timeText === selectedValue) {
                        option.selected = true;
                    }
                    $select.appendChild(option);
                }
            }
        });

        function validateForm() {
            const rentalStartTime = document.getElementById('rental_start_time');
            const rentalEndTime = document.getElementById('rental_end_time');
            const birthdate = document.querySelector('input[name="birthdate"]');
            const usagePeople = document.querySelector('select[name="usage_people"]');
            const usagePurpose = document.querySelector('input[name="usage_purpose"]');
            const certificateSelect = document.querySelector('select[name="certificate"]');
            const discountSelect = document.getElementById('discountSelect');
         
            if (!rentalStartTime.value) {
                alert('대여 방문시간을 선택하지 않았습니다.');
                rentalStartTime.focus();
                return false;
            }

            if (!rentalEndTime.value) {
                alert('반납 방문시간을 선택하지 않았습니다.');
                rentalEndTime.focus();
                return false;
            }

            if (!discountSelect.value) {
                alert('할인적용을 선택하지 않았습니다.');
                discountSelect.focus();
                return false;
            }

            if (!certificateSelect.value) {
                alert('증명서 제출 방법을 선택하지 않았습니다.');
                certificateSelect.focus();
                return false;
            }

            if (!usagePeople.value) {
                alert('사용인원을 선택하지 않았습니다.');
                usagePeople.focus();
                return false;
            }

            if (!usagePurpose.value) {
                alert('사용목적을 입력하지 않았습니다.');
                usagePurpose.focus();
                return false;
            }

            return true;
        }


    </script>

<?}###################################################### 사용자 페이지 ###################################################### END ?>