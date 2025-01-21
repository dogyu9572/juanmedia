<?php include("pub/inc/dtd.php") ?>
<?php include("pub/inc/header.php") ?>
<?php include("pub/inc/aside.php") ?>
<div class="container">

	<div class="title">업체 관리</div>

	<div class="inbox top_search">
		<dl>
			<dt>구분</dt>
			<dd>
				<select name="" id="">
					<option value="">전체</option>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>업체구분</dt>
			<dd>
				<select name="" id="">
					<option value="">전체</option>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>회비현황</dt>
			<dd>
				<select name="" id="">
					<option value="">전체</option>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>보유신기술</dt>
			<dd>
				<select name="" id="">
					<option value="">전체</option>
				</select>
			</dd>
		</dl>
		<dl class="w2">
			<dt>업태</dt>
			<dd>
				<select name="" id="">
					<option value="">전체</option>
				</select>
			</dd>
		</dl>
		<dl class="w2">
			<dt>창립기념일</dt>
			<dd>
				<input type="text" id="datepicker1" /><em>~</em>
				<input type="text" id="datepicker2" />
			</dd>
		</dl>
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="" id="">
					<option value="">전체</option>
				</select>
				<input type="text">
				<button type="button" class="search">검색</button>
			</dd>
		</dl>
	</div>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong>2,914</strong></div>
				<div class="down">
					<a href="#this" class="excel" download>전체다운<span class="pc_vw">로드</span></a>
					<a href="#this" class="excel" download>선택다운<span class="pc_vw">로드</span></a>
				</div>
			</div>
			<div class="count">
				<select name="" id="">
					<option value="">10</option>
					<option value="">20</option>
					<option value="">30</option>
					<option value="">50</option>
				</select>
				개씩 보기
			</div>
		</div>

<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="check">
						<col class="w4p">
						<col class="w9p">
						<col class="w10p">
						<col class="w8p">
						<col class="w8p">
						<col class="w7p">
						<col class="w7p">
						<col class="w8p">
						<col class="w10p">
						<col class="w10p">
						<col width="*">
					</colgroup>
					<thead>
						<tr>
							<th><label class="check notxt"><input type="checkbox" name="" id="allCheck"><i></i></label></th>
							<th class="pc_vw">고유번호</th>
							<th class="pc_vw">구 &nbsp;분</th>
							<th class="pc_vw">회사명</th>
							<th class="pc_vw">사업자등록번호</th>
							<th class="pc_vw">대표자</th>
							<th class="pc_vw">팩스번호</th>
							<th class="pc_vw">전화번호</th>
							<th class="pc_vw">업체구분</th>
							<th class="pc_vw">보유기술지정번호</th>
							<th class="pc_vw">등록일</th>
							<th class="pc_vw">관리</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
						<tr>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" name="check"><i></i></label></td>
							<td><i class="mo_vw">고유번호</i>9999</td>
							<td><i class="mo_vw">구  분</i>개발자 사용자 회원사</td>
							<td><i class="mo_vw">회사명</i><a href="#this" class="link">(주)한국시설안전연구원</a></td>
							<td><i class="mo_vw">사업자등록번호</i>000-00-00000</td>
							<td><i class="mo_vw">대표자</i>최윤호, 신순주</td>
							<td><i class="mo_vw">팩스번호</i>053-587-1278</td>
							<td><i class="mo_vw">전화번호</i>053-587-1278</td>
							<td><i class="mo_vw">업체구분</i>업체(중소기업)</td>
							<td><i class="mo_vw">보유기술지정번호</i>
								<select name="" id="">
									<option value="">▼개발기술</option>
									<option value="">▼개발기술1</option>
								</select>
							</td>
							<td><i class="mo_vw">등록일</i>2022-09-22 08:07:55</td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="#this" class="btn perf">실적</a>
									<a href="#this" class="btn modi">수정</a>
									<button class="btn del">삭제</button>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="bdr_btm">
			<div class="paging">
				<a href="#this" class="arrow first"></a>
				<a href="#this" class="arrow prev"></a>
				<a href="#this" class="on">1</a>
				<a href="#this">2</a>
				<a href="#this">3</a>
				<a href="#this">4</a>
				<a href="#this">5</a>
				<a href="#this">6</a>
				<a href="#this">7</a>
				<a href="#this">8</a>
				<a href="#this">9</a>
				<a href="#this">10</a>
				<a href="#this" class="arrow next"></a>
				<a href="#this" class="arrow last"></a>
			</div>
			<div class="btns">
				<a href="#this" class="btn btn_del">선택삭제</a>
				<a href="write.php" class="btn">신규등록</a>
			</div>
		</div>
	</div>

</div>

<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
//달력
	$("#datepicker1,#datepicker2").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
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
//체크박스
	var $allCheck = $('#allCheck');
	$allCheck.change(function () {
		var $this = $(this);
		var checked = $this.prop('checked');
		$('input[name="check"]').prop('checked', checked);
	});
	var boxes = $('input[name="check"]');
	boxes.change(function () {
		var boxLength = boxes.length;
		var checkedLength = $('input[name="check"]:checked').length;
		var selectallCheck = (boxLength == checkedLength);
		$allCheck.prop('checked', selectallCheck);
	});
});
//]]>
</script>

<?php include("pub/inc/footer.php") ?>