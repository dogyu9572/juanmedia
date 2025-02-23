<?php
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include "./menu.php";

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);

// 연도 설정
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// 카테고리 목록 가져오기
$arrCategory = getCategoryList(62);
$categories = [];
foreach ($arrCategory["list"] as $item) {
    $categories[] = $item['cat_no'];
}
// 통계 데이터 가져오기
$statistics = getVideoStatistics($year);

// DB 해제
//SetDisConn($dblink);
?>

    <div class="container">
        <div class="title">상영회통계</div>
        <div class="inbox">
            <div class="block-area">
                <form method="get" action="<?= $_SERVER["PHP_SELF"] ?>" name="logViewFrm">
                    <dl class="w2">
                        <dd>
                            <button type="button" onclick="location.href='?year=<?= $year - 1 ?>'" class="btn">◀</button>
                            <span style="margin: 0 20px; font-size: 20px;"><strong><?= $year ?>년</strong></span>
                            <button type="button" onclick="location.href='?year=<?= $year + 1 ?>'" class="btn">▶</button>
                        </dd>
                    </dl>
                </form>
            </div>

            <div class="bdr_top">
                <div class="left"></div>
                <div class="bdr_right">
                    <div class="btns">
                        <a href="/backoffice/module/board/board_statistic_video_xls.php?year=<?=$year?>" class="excel" download>엑셀파일로 저장<span class="pc_vw"></span></a>
                    </div>
                </div>
            </div>

            <div class="over_tbl mo_break_tbl">
                <div class="bdr_list tac">
                    <table>
                        <colgroup>
                            <col class="w30p">
                            <col class="w35p">
                            <col class="w35p">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>월</th>
                            <th>건수</th>
                            <th>상영회인원수</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($statistics as $month => $data): ?>
                            <tr>
                                <td><?= $month ?></td>
                                <td><?= number_format($data['count']) ?></td>
                                <td><?= number_format($data['people']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        .bdr_list tbody td {
            border-right: #ddd 1px solid;
        }
    </style>
<?php include("pub/inc/footer.php") ?>