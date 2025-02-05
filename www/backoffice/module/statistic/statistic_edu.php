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
$statistics = getEducationStatistics($year, $categories);

// DB 해제
//SetDisConn($dblink);
?>

    <div class="container">
        <div class="title">연도별 교육 통계</div>
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
                        <a href="/backoffice/module/board/board_statistic_edu_xls.php?year=<?=$year?>" class="excel" download>엑셀파일로 저장<span class="pc_vw"></span></a>
                    </div>
                </div>
            </div>

            <div class="over_tbl mo_break_tbl">
                <div class="bdr_list tac">
                    <table >
                        <colgroup class="pc_vw">
                            <col class="w10p">
                            <col class="w10p">
                            <col class="w10p">
                            <col class="w15p">
                            <col class="w15p">
                            <col class="w15p">
                            <col class="w15p">
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="pc_vw">월</th>
                            <th class="pc_vw" colspan="2">구분</th>
                            <th class="pc_vw">건수</th>
                            <th class="pc_vw">교육인원수</th>
                            <th class="pc_vw">수료인원</th>
                            <th class="pc_vw">수강율(%)</th>
                            <th class="pc_vw">금액</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $currentMonth = null;
                        $currentCategory1 = null;
                        for ($month = 12; $month >= 1; $month--):
	                        if (isset($statistics[$month])):
		                        $rowCount = 0;
		                        foreach ($statistics[$month] as $subcategories) {
			                        $rowCount += count($subcategories);
		                        }
		                        $firstRow = true;
		                        foreach ($statistics[$month] as $category1 => $subcategories):
			                        $firstCategoryRow = true;
			                        foreach ($subcategories as $category2 => $data): ?>
                                        <tr>
					                        <?php if ($firstRow): ?>
                                                <td rowspan="<?= $rowCount ?>"><?= $month ?>월</td>
						                        <?php $firstRow = false; ?>
					                        <?php endif; ?>
					                        <?php if ($firstCategoryRow): ?>
                                                <td rowspan="<?= count($subcategories) ?>"><?= getCategoryName($category1); ?></td>
						                        <?php $firstCategoryRow = false; ?>
					                        <?php endif; ?>
                                            <td><?= getCategoryName($category2); ?></td>
                                            <td><?= $data['edu_count'] ?></td>
                                            <td><?= $data['applicants_count'] ?></td>
                                            <td><?= $data['certificates_count'] ?></td>
                                            <td><?= number_format($data['completion_rate']) ?>%</td>
                                            <td><?= number_format($data['total_amount']) ?>원</td>
                                        </tr>
			                        <?php endforeach;
		                        endforeach;
	                        endif;
                        endfor; ?>
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