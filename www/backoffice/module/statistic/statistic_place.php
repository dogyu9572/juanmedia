<?php
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include "./menu.php";

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);

// 연도 설정
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// 카테고리 목록 가져오기
$arrCategory = getCategoryList(36);
$categories = [];
foreach ($arrCategory["list"] as $item) {
	$categories[intval($item['cat_no'])] = $item['cat_name'];
}

// 통계 데이터 가져오기
$statistics = getPlaceStatistics($year);
?>

	<div class="container">
		<div class="title">공간대여 통계</div>
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
						<a href="/backoffice/module/board/board_statistic_place_xls.php?year=<?=$year?>" class="excel" download>엑셀파일로 저장<span class="pc_vw"></span></a>
					</div>
				</div>
			</div>

			<div class="over_tbl mo_break_tbl">
				<div class="bdr_list tac">
					<table>
						<colgroup>
							<col width="5%">
							<col width="10%">
							<col width="15%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
							<col width="6%">
						</colgroup>
						<thead>
						<tr>
							<th rowspan="2">월</th>
							<th rowspan="2">구분</th>
							<th rowspan="2">공간명</th>
							<th colspan="5">건수</th>
							<th colspan="5">대여시간</th>
							<th colspan="5">이용 인원(명)</th>
							<th colspan="5">금액</th>
						</tr>
						<tr>
							<th>유료</th>
							<th>교육</th>
							<th>지원</th>
							<th>할인</th>
							<th>유료+지원</th>
							<th>유료</th>
							<th>교육</th>
							<th>지원</th>
							<th>할인</th>
							<th>유료+지원</th>
							<th>유료</th>
							<th>교육</th>
							<th>지원</th>
							<th>할인</th>
							<th>유료+지원</th>
							<th>유료</th>
							<th>교육</th>
							<th>지원</th>
							<th>할인</th>
							<th>유료+지원</th>
						</tr>
						</thead>
						<tbody>
						<?php
						for ($month = 12; $month >= 1; $month--):
							$monthData = isset($statistics[$month]) ? $statistics[$month] : [];

							if (!empty($monthData)): // 월 데이터가 있는 경우만 표시
								foreach ($monthData as $cat_no => $categoryData):
									if ($cat_no !== '소계'): // 소계는 나중에 처리
										foreach ($categoryData as $subject => $data):
											?>
											<tr>
												<?php if ($cat_no === array_key_first(array_filter($monthData, function($k) { return $k !== '소계'; }, ARRAY_FILTER_USE_KEY)) && $subject === array_key_first($categoryData)): ?>
													<td rowspan="<?= count_monthly_rows($monthData, array_keys($monthData)) ?>"><?= $month ?>월</td>
												<?php endif; ?>

												<?php if ($subject === array_key_first($categoryData)): ?>
													<td rowspan="<?= count($categoryData) ?>"><?= isset($categories[$cat_no]) ? $categories[$cat_no] : $cat_no ?></td>
												<?php endif; ?>

												<td><?= $subject ?></td>

												<!-- 건수 -->
												<td><?= isset($data['counts']['유료']) ? $data['counts']['유료'] : 0 ?></td>
												<td><?= isset($data['counts']['교육']) ? $data['counts']['교육'] : 0 ?></td>
												<td><?= isset($data['counts']['지원']) ? $data['counts']['지원'] : 0 ?></td>
												<td><?= isset($data['counts']['할인']) ? $data['counts']['할인'] : 0 ?></td>
												<td><?= isset($data['counts']['유료+지원']) ? $data['counts']['유료+지원'] : 0 ?></td>

												<!-- 대여시간 -->
												<td><?= isset($data['times']['유료']) ? $data['times']['유료'] : 0 ?></td>
												<td><?= isset($data['times']['교육']) ? $data['times']['교육'] : 0 ?></td>
												<td><?= isset($data['times']['지원']) ? $data['times']['지원'] : 0 ?></td>
												<td><?= isset($data['times']['할인']) ? $data['times']['할인'] : 0 ?></td>
												<td><?= isset($data['times']['유료+지원']) ? $data['times']['유료+지원'] : 0 ?></td>

												<!-- 이용인원 -->
												<td><?= isset($data['people']['유료']) ? $data['people']['유료'] : 0 ?></td>
												<td><?= isset($data['people']['교육']) ? $data['people']['교육'] : 0 ?></td>
												<td><?= isset($data['people']['지원']) ? $data['people']['지원'] : 0 ?></td>
												<td><?= isset($data['people']['할인']) ? $data['people']['할인'] : 0 ?></td>
												<td><?= isset($data['people']['유료+지원']) ? $data['people']['유료+지원'] : 0 ?></td>

												<!-- 금액 -->
												<td><?= isset($data['amounts']['유료']) ? number_format($data['amounts']['유료']) : 0 ?></td>
												<td><?= isset($data['amounts']['교육']) ? number_format($data['amounts']['교육']) : 0 ?></td>
												<td><?= isset($data['amounts']['지원']) ? number_format($data['amounts']['지원']) : 0 ?></td>
												<td><?= isset($data['amounts']['할인']) ? number_format($data['amounts']['할인']) : 0 ?></td>
												<td><?= isset($data['amounts']['유료+지원']) ? number_format($data['amounts']['유료+지원']) : 0 ?></td>
											</tr>
										<?php
										endforeach;
									endif;
								endforeach;

								if (isset($monthData['소계'])): ?>
									<tr class="total">
										<td colspan="2">소계</td>
										<!-- 건수 소계 -->
										<td><?= $monthData['소계']['counts']['유료'] ?></td>
										<td><?= $monthData['소계']['counts']['교육'] ?></td>
										<td><?= $monthData['소계']['counts']['지원'] ?></td>
										<td><?= $monthData['소계']['counts']['할인'] ?></td>
										<td><?= $monthData['소계']['counts']['유료+지원'] ?></td>
										<!-- 시간 소계 -->
										<td><?= $monthData['소계']['times']['유료'] ?></td>
										<td><?= $monthData['소계']['times']['교육'] ?></td>
										<td><?= $monthData['소계']['times']['지원'] ?></td>
										<td><?= $monthData['소계']['times']['할인'] ?></td>
										<td><?= $monthData['소계']['times']['유료+지원'] ?></td>
										<!-- 인원 소계 -->
										<td><?= $monthData['소계']['people']['유료'] ?></td>
										<td><?= $monthData['소계']['people']['교육'] ?></td>
										<td><?= $monthData['소계']['people']['지원'] ?></td>
										<td><?= $monthData['소계']['people']['할인'] ?></td>
										<td><?= $monthData['소계']['people']['유료+지원'] ?></td>
										<!-- 금액 소계 -->
										<td><?= number_format($monthData['소계']['amounts']['유료']) ?></td>
										<td><?= number_format($monthData['소계']['amounts']['교육']) ?></td>
										<td><?= number_format($monthData['소계']['amounts']['지원']) ?></td>
										<td><?= number_format($monthData['소계']['amounts']['할인']) ?></td>
										<td><?= number_format($monthData['소계']['amounts']['유료+지원']) ?></td>
									</tr>
									<tr class="month-total">
										<td colspan="2">합</td>
										<td colspan="5"><?= array_sum($monthData['소계']['counts']) ?>건</td>
										<td colspan="5"><?= array_sum($monthData['소계']['times']) ?>시간</td>
										<td colspan="5"><?= array_sum($monthData['소계']['people']) ?>명</td>
										<td colspan="5"><?= number_format(array_sum($monthData['소계']['amounts'])) ?>원</td>
									</tr>
								<?php endif;
							endif;
						endfor;
						?>
						</tbody>
					</table>
				</div>

				<?php if (isset($statistics['연간총계'])): ?>
					<div class="bdr_list tac mt-20">
						<table>
							<tr class="year-total">
								<td colspan="4"><?= $year ?>년 총합</td>
								<td colspan="5"><?= array_sum($statistics['연간총계']['counts']) ?>건</td>
								<td colspan="5"><?= array_sum($statistics['연간총계']['times']) ?>시간</td>
								<td colspan="5"><?= array_sum($statistics['연간총계']['people']) ?>명</td>
								<td colspan="5"><?= number_format(array_sum($statistics['연간총계']['amounts'])) ?>원</td>
							</tr>
						</table>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<style>
        .bdr_list tbody td, .bdr_list thead th {
            border-right: #ddd 1px solid;
            border-bottom: #ddd 1px solid;
        }
        .bdr_list thead th {
            border-top: #ddd 1px solid;
        }
        .total td {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .month-total td {
            background-color: #eee;
            font-weight: bold;
        }
        .year-total td {
            background-color: #e0e0e0;
            font-weight: bold;
        }
	</style>

<?php include("pub/inc/footer.php") ?>

<?php
// 월별 행 수를 계산하는 헬퍼 함수
function count_monthly_rows($monthData, $categories) {
	$count = 0;
	foreach ($categories as $category) {
		if ($category !== '소계') {
			if (isset($monthData[$category])) {
				$count += count($monthData[$category]);
			}
		}
	}
	// 소계와 합계 행 추가
	return $count + 2;
}
?>