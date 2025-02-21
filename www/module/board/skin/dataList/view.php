<?php
if ($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] && $_SERVER["PHP_SELF"] == "/backoffice/module/board/board_view.php") {
    if (!in_array("biz_manage", $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"] != "ROOT") {
        jsMsg("권한이 없습니다.");
        jsHistory("-1");
    }
    ###################################################### 관리자 페이지 ######################################################
    ?>
    <script language="javascript">
        function fileDownload(boardid, b_idx, idx) {
            obj = window.open("/module/board/download.php?boardid=" + boardid + "&b_idx=" + b_idx + "&idx=" + idx, "urlCheck", "width=100, height=100, menubars=0, toolbars=0");
        }
        <?php
        // 댓글 사용시
        if ($arrBoardInfo["list"][0]["usememo"] == "Y") {
        ?>
        function checkComment(frm) {
            <?php if (!$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]) { ?>
            alert("로그인을 하셔야 댓글입력이 가능합니다.");
            return false;
            <?php } else if ($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["replylevel"]) { ?>
            if (frm.comment.value == "") {
                alert("댓글 내용을 입력해 주세요.");
                frm.comment.focus();
                return false;
            }
            <?php } else { ?>
            alert("<?=$arrLevelInfo[$arrBoardInfo["list"][0]["replylevel"]]?> 이상 댓글입력이 가능합니다.");
            return false;
            <?php } ?>
        }
        <?php
        }
        // 댓글 사용시
        ?>
    </script>
    <script type="text/javascript">
        <!--
        function boardDel(val) {
            if (confirm("삭제 하시겠습니까?")) {
                $.post("/module/board/ajax_board_del.php", { evnMode: "delete", g_idx: val, boardid: "<?=$arrBoardInfo["list"][0]["boardid"]?>" },
                    function(data) {
                        //alert(data);
                        doLoad();
                    });
            }
        }
        function doLoad() {
            location.href = "<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&sk=<?=$_GET['sk']?>&sw=<?=$_GET['sw']?>&offset=<?=$_GET['offset']?>&category=<?=$_GET['category']?>";
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
                    <?php for ($i = 0; $i < $arrBoardArticle["total_files"]; $i++) { ?>
                        <a href="javascript:void(0);" onclick="fileDownload('<?=$arrBoardArticle["files"][$i]['boardid']?>', '<?=$arrBoardArticle["files"][$i]['b_idx']?>', '<?=$arrBoardArticle["files"][$i]['idx']?>');"><?=$arrBoardArticle["files"][$i]['ori_name']?></a>
                    <?php } ?>
                    <?php if ($i < 1) { ?>
                        첨부파일이 없습니다.
                    <?php } ?>
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
            <dt>이전글</dt><dd><?php if ($arrBoardArticle["prev"]["idx"] != 0) { ?><a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=view&idx=<?=$arrBoardArticle["prev"]["idx"]?>&category=<?=$_GET['category']?>" title="<?=$arrBoardArticle["prev"]["subject"]?>" class="act_view"><?=text_cut($arrBoardArticle["prev"]["subject"], $arrBoardInfo["list"][0]['subjectcut'])?></a><?php } else { ?><a href="javascript:void(0);">이전글이 없습니다.</a><?php } ?></dd>
            <dt>다음글</dt><dd><?php if ($arrBoardArticle["next"]["idx"] != 0) { ?><a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=view&idx=<?=$arrBoardArticle["next"]["idx"]?>&category=<?=$_GET['category']?>" title="<?=$arrBoardArticle["next"]["subject"]?>" class="act_view"><?=text_cut($arrBoardArticle["next"]["subject"], $arrBoardInfo["list"][0]['subjectcut'])?></a><?php } else { ?><a href="javascript:void(0);">다음글이 없습니다.</a><?php } ?></dd>
        </dl>
    </div>
    <?php
} else {
    ###################################################### 사용자 페이지 ######################################################
    ?>
    <!-- subSec -->
    <div class="subSec last">
        <div class="inner">
            <div class="btnBack">
                <a href="javascript:history.back()">뒤로</a>
            </div>
            <!-- noticeDetail -->
            <div class="noticeDetail">
                <div class="top">
                    <div class="title"><?=stripslashes($arrBoardArticle["list"][0]['subject'])?></div>
                    <div class="info">
                        <div class="box">
                            <div class="tit">등록일</div>
                            <?=date('Y.m.d', strtotime($arrBoardArticle["list"][0]['wdate']))?>
                        </div>
                        <div class="box">
                            <div class="tit">조회수</div>
                            <div class="txt"><?=number_format($arrBoardArticle["list"][0]['hit'])?></div>
                        </div>
                        <div class="box">
                            <div class="tit">작성자</div>
                            <div class="txt"><?=$arrBoardArticle["list"][0]['name']?></div>
                        </div>
                    </div>
                </div>
                <div class="cont">
                    <?=$arrBoardArticle["list"][0]['contents']?>
                </div>
                <div class="fileAdd">
                    <?php
                    $upfile = true;
                    if ($arrBoardArticle["total_files"] > 0) {
                    for ($i = 0; $i < $arrBoardArticle["total_files"]; $i++) {
                    if (substr($arrBoardArticle["files"][$i]['re_name'], 0, 2) != "l_") {
                    $upfile = false;
                    ?>
                    <div class="tit">첨부파일</div>
                    <div class="file"><a href="/uploaded/board/<?=$arrBoardInfo["list"][0]["boardid"]?>/<?=$arrBoardArticle["files"][$i]['re_name']?>" download="<?=$arrBoardArticle["files"][$i]['ori_name']?>"><?=$arrBoardArticle["files"][$i]['ori_name']?></a></div>
                <?php
                }
                }
                }
                if ($upfile) {
                    ?>
                    <div class="file">첨부파일이 없습니다.</div>
                    <?php
                }
                ?>
                </div>
            </div>
            <!-- listPaging -->
            <div class="listPaging">
                <div class="line">
                    <?php if ($arrBoardArticle["prev"]["idx"] != 0) { ?>
                        <a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=view&idx=<?=$arrBoardArticle["prev"]["idx"]?>&category=<?=$_GET['category']?>">
                            <div class="tit">이전 글</div>
                            <div class="text"><?=text_cut($arrBoardArticle["prev"]["subject"], $arrBoardInfo["list"][0]['subjectcut'])?></div>
                        </a>
                    <?php } else { ?>
                        <a href="javascript:void(0);">
                        <div class="tit">이전 글</div>
                        <div class="text">이전글이 없습니다.</div>
                    </a>
                    <?php } ?>
                </div>
                <div class="line">
                    <?php if ($arrBoardArticle["next"]["idx"] != 0) { ?>
                        <a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=view&idx=<?=$arrBoardArticle["next"]["idx"]?>&category=<?=$_GET['category']?>">
                            <div class="tit next">다음 글</div>
                            <div class="text"><?=text_cut($arrBoardArticle["next"]["subject"], $arrBoardInfo["list"][0]['subjectcut'])?></div>
                        </a>
                    <?php } else { ?>
                    <a href="javascript:void(0);">
                        <div class="tit next">다음 글</div>
                        <div class="text">다음글이 없습니다.</div>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <!-- //listPaging -->
            <div class="btnCenter">
                <a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&sk=<?=$_GET['sk']?>&sw=<?=$_GET['sw']?>&offset=<?=$_GET['offset']?>&category=<?=$_GET['category']?>" class="btnType1 black list">목록</a>
            </div>
        </div>
    </div>
    <!-- //subSec -->
    <?php
}
###################################################### 사용자 페이지 ###################################################### END
?>