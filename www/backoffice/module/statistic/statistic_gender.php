<?php
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);

// 통계 데이터 가져오기
$statistics = getAgeGenderStatistics();

// DB 해제
SetDisConn($dblink);
?>

    <div class="container">
        <div class="title">성별/나이통계</div>

        <div class="inbox">
            <div class="bdr_top">
                <div class="left"></div>
                <div class="bdr_right">
                    <div class="btns">
                        <a href="/backoffice/module//board/board_statistic_gender_xls.php" class="excel" download>엑셀파일로 저장<span class="pc_vw"></span></a>
                    </div>
                </div>
            </div>
            <div class="over_tbl mo_break_tbl">
                <div class="bdr_list tac">
                    <table>
                        <colgroup class="pc_vw">
                            <col class="w15p">  <!-- 구분 -->
                            <col class="w15p">  <!-- 연령 -->
                            <col class="w20p">  <!-- 전체 -->
                            <col class="w20p">  <!-- 남자 -->
                            <col class="w20p">  <!-- 여자 -->
                        </colgroup>
                        <thead>
                        <tr>
                            <th class="pc_vw" colspan="2">구분</th>
                            <th class="pc_vw">전체</th>
                            <th class="pc_vw">남자</th>
                            <th class="pc_vw">여자</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $ageRanges = [
	                        '0~4', '5~9', '10~14', '15~19', '20~24', '25~29', '30~34', '35~39', '40~44', '45~49', '50~54', '55~59', '60~64', '65~69', '70~74', '75~79', '80세 이상', '미집계'
                        ];

                        $firstRow = true;

                        foreach ($ageRanges as $range) {
	                        $total = 0;
	                        $male = 0;
	                        $female = 0;

	                        foreach ($statistics as $table => $data) {
		                        if (isset($data[$range])) {
			                        $total += array_sum($data[$range]);
			                        $male += isset($data[$range]['남자']) ? $data[$range]['남자'] : 0;
			                        $female += isset($data[$range]['여자']) ? $data[$range]['여자'] : 0;
		                        }
	                        }

	                        echo '<tr>';
	                        if ($firstRow) {
		                        echo '<td rowspan="' . count($ageRanges) . '">연령</td>';
		                        $firstRow = false;
	                        }
	                        echo '<td>' . $range . '</td>';
	                        echo '<td>' . number_format($total) . '</td>';
	                        echo '<td>' . number_format($male) . '</td>';
	                        echo '<td>' . number_format($female) . '</td>';
	                        echo '</tr>';
                        }
                        ?>
                        <tr class="total">
                            <td colspan="2">총합</td>
                            <td><?= number_format(array_sum(array_column($statistics, 'total'))) ?></td>
                            <td><?= number_format(array_sum(array_column($statistics, '남자'))) ?></td>
                            <td><?= number_format(array_sum(array_column($statistics, '여자'))) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd',
                showMonthAfterYear: true,
                showOn: "both",
                buttonImage: "/images/icon_month.gif",
                buttonImageOnly: true,
                changeYear: true,
                changeMonth: true,
                yearRange: 'c-100:c+10',
                yearSuffix: "년 ",
                monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
                dayNamesMin: ['일','월','화','수','목','금','토']
            });
        });
    </script>
    <style>
        .bdr_list tbody td {
            border-right: #ddd 1px solid;
        }
        .block-area {
            padding-bottom: 20px;
        }
    </style>

<?php include("pub/inc/footer.php") ?>