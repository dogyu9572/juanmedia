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
    session_start();

	include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php");
    $dayTypeMap = [
        'weekly' => '매주',
        'biweekly' => '격주',
        'other' => '기타'
    ];

    ?>

    <!-- pageTitle -->
    <div class="pageTitle inner only mob1">장비대여 장바구니</div>
    <!-- //pageTitle -->

    <!-- subSec -->
    <div class="subSec pt0 last">
        <div class="inner">

            <div class="btnRight fixed">
                <a href="javascript:getSelections();" class="btnType2">선택 삭제</a>
            </div>

            <div class="formTable">
                <ul class="thead">
                    <li class="no1">
                        <div class="baseCheck">
                            <input type="checkbox" id="agreeAll" />
                            <label for="agreeAll"></label>
                        </div>
                    </li>
                    <li class="no2">장비정보</li>
                    <li class="no3">장비대여 금액</li>
                    <li class="no4">대여일수</li>
                    <li class="no5">총 대여금액</li>
                    <li class="no6">선택</li>
                </ul>
                <div class="tbody agreeCheck">
    <?
    $totalAmount = 0;
    if($arrBoardList["list"]["total"] > 0){
        for($i=0; $i < $arrBoardList["list"]["total"]; $i++){


            $imgsrc[$i] = "/uploaded/board/equ/".$arrBoardList["list"][$i]['re_name'];
            ############################ 파일 확인 #############################
            $arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], "", $arrBoardList["list"][$i]['idx'],"list");
            for($j=0;$j<$arrBoardArticle["total_files"];$j++){
                if(substr($arrBoardArticle["files"][$j]['re_name'],0,2) != "l_"){
                    $fileImg[$i] = '<img src="/backoffice/pub_old/images/file.png">';
                }
            }

            $totalAmount += $arrBoardList["list"][$i]['totalamount'];


            ?>
                    <ul>
                        <li class="no1">
                            <div class="baseCheck">
                                <input type="checkbox" id="chk_list_<?=$i?>" name="chk_list" value="<?=$arrBoardList["list"][$i]['idx']?>"/>
                                <label for="chk_list_<?=$i?>"></label>
                            </div>
                        </li>
                        <li class="li no2">
                            <div class="boxImgDetail">
                                <div class="img"><img src="<?=$imgsrc[$i]?>" alt="썸네일"></div>
                                <div class="boxDetail">
                                    <div class="name"><?=$arrBoardList["list"][$i]['subject']?></div>
                                    <div class="info">
                                        <span class="tit">장비번호</span>
                                        <span class="txt"><?=$arrBoardList["list"][$i]['equ_number']?></span>
                                    </div>
                                    <div class="info">
                                        <span class="tit">대여일/반납일</span>
                                        <span class="txt"><?=$arrBoardList["list"][$i]['rental_start_date']?> ~ <br class="mob" /><?=$arrBoardList["list"][$i]['rental_end_date']?></span>
                                    </div>
                                    <div class="info">
                                        <span class="tit">대여시간/반납시간</span>
                                        <span class="txt"><?=$arrBoardList["list"][$i]['rental_start_time']?> ~ <br class="mob" /><?=$arrBoardList["list"][$i]['rental_end_time']?></span>
                                    </div>
                                    <div class="info mob">
                                        <span class="tit">장비대여 금액</span>
                                        <span class="txt"><?=number_format($arrBoardList["list"][$i]['fee'])?>원(1일)</span>
                                    </div>
                                    <div class="info mob">
                                        <span class="tit">대여일수</span>
                                        <span class="txt"><?=$arrBoardList["list"][$i]['usage_day']?>일</span>
                                    </div>
                                    <div class="info mob">
                                        <span class="tit">총 대여금액</span>
                                        <span class="txt"><?=number_format($arrBoardList["list"][$i]['totalamount'])?>원</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="no3"><?=number_format($arrBoardList["list"][$i]['fee'])?>원(1일)</li>
                        <li class="no4"><?=$arrBoardList["list"][$i]['usage_day']?>일</li>
                        <li class="no5"><?=number_format($arrBoardList["list"][$i]['totalamount'])?>원</li>
                        <li class="no6"><a href="javascript:boardDel(<?=$arrBoardList["list"][$i]['idx']?>)" class="btnType2">삭제</a></li>
                    </ul>
            <?
						}
					}?>
                </div>
            </div>

            <div class="totalPriceBox">
                <span>총 결제금액</span>
                <strong>0원</strong>
            </div>

            <div class="btnCenter mobFix">
                <a href="/equ/list.php"  class="btnType1 gray w1">목록으로</a>
                <a href="javascript:void(0);" id="submitOrderButton" class="btnType1 w1">대여 신청</a>

            </div>

            <div class="cartInfo">
                <div class="box">
                    <div class="ico no1"><img src="/images/ico_eq10.svg" alt="장바구니 안내"></div>
                    <div class="text">
                        <div class="tit">장바구니 안내</div>
                        <div class="txt">· 장바구니에는 100개까지 상품을 담을 수 있고 최대 90일까지 보관됩니다.</div>
                        <div class="txt">· 품절 상품은 별도 표기 됩니다.</div>
                    </div>
                </div>
                <div class="box">
                    <div class="ico no2"><img src="/images/ico_eq11.svg" alt="할인 안내"></div>
                    <div class="text">
                        <div class="tit">할인 안내</div>
                        <div class="txt">· 할인 혜택은 대여 신청 시 선택할 수 있습니다.</div>
                        <div class="txt">· 대여 신청을 클릭 후 예약자 정보에서 할인 옵션을 확인하고 적용할 수 있습니다.</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- //subSec -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to calculate the total amount based on selected checkboxes
        function calculateTotalAmount() {
            var totalAmount = 0;
            var selectedCheckboxes = document.querySelectorAll('input[name="chk_list"]:checked');

            selectedCheckboxes.forEach(function(checkbox) {
                var amount = parseInt(checkbox.closest('ul').querySelector('.no5').textContent.replace(/[^0-9]/g, ''), 10);
                totalAmount += amount;
            });

            document.querySelector('.totalPriceBox strong').textContent = totalAmount.toLocaleString() + '원';
        }

        // Add event listeners to all checkboxes
        var checkboxes = document.querySelectorAll('input[name="chk_list"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', calculateTotalAmount);
        });

        // Add event listener to the "Select All" checkbox
        var selectAllCheckbox = document.getElementById('agreeAll');
        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
            calculateTotalAmount();
        });

        // Initial calculation
        calculateTotalAmount();

        function submitOrder() {
            var selectedIdx = [];

            // Select all checked checkboxes with name 'chk_list'
            var checkboxes = document.querySelectorAll('input[name="chk_list"]:checked');

            // Collect the values of the selected checkboxes
            checkboxes.forEach(function(checkbox) {
                selectedIdx.push(checkbox.value);
            });

            if (selectedIdx.length > 0) {
                // Create a form dynamically
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/equ/list.php?boardid=equ&mode=order';

                // Add selected Idx as hidden inputs
                selectedIdx.forEach(function(id) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'idx[]';
                    input.value = id;
                    form.appendChild(input);
                });

                // Append the form to the body and submit it
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('선택된 항목이 없습니다.');
            }
        }

        // Add event listener to the "대여 신청" button
        var orderButton = document.getElementById('submitOrderButton');
        orderButton.addEventListener('click', submitOrder);
    });

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
        location.reload();
    }
    function getSelections(){
        var selectedIdx = [];

        // Select all checked checkboxes with name 'chk_list'
        var rows = $('input:checkbox[name=chk_list]:checked');

        // Collect the values of the selected checkboxes
        for(var i=0; i<rows.length; i++){
            selectedIdx.push(rows[i].value);
        }

        if(selectedIdx.length > 0){
            // Join the selected Idx into a comma-separated string
            var ss = selectedIdx.join(",");
            boardDel(ss);
        } else {
            alert('선택된 항목이 없습니다.');
        }
    }


</script>
<?}###################################################### 사용자 페이지 ###################################################### END ?>