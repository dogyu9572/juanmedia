<?	include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/_header.php";		?>
<?	include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/_leftMenu.php";	?>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$.each($('input.calendar'), function() {
		set_datepicker($(this));
	});	
});
function set_datepicker($cont) {
	$cont.prop('readonly', true).datepicker({
		closeText: '닫기',
		prevText: '',
		nextText: '',
		currentText: '오늘',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)','7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		defaultDate: '+1w',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '년 ',
		changeMonth: true,
		changeYear: true,
		yearRange: '1921:c+5'
	});
}
//-->
</script>

<div id="admin-content">
	<h2 class="admin-title">국내외 행사</h2>
	<!--Search Form ST-->
	<form id="frm_search" name="frm_search" method="get" action="" >
		<input type="hidden" name="category_idno" value="" />
		<input type="hidden" name="ord" value="" />
		<fieldset class="search_box">
			<div>
				<label>구분</label>
				<input type="radio" name="board_code" id="board_code_1" value="003" checked /> <label for="board_code_1">국문</label>&nbsp;&nbsp;&nbsp;
				<input type="radio" name="board_code" id="board_code_2" value="017"  /> <label for="board_code_2">영문</label>
			</div>
			<div>
				<label>기간</label>
				<input type="text" name="s_date" class="txt calendar" value="" /> ~ 
				<input type="text" name="e_date" class="txt calendar" value="" />
			</div>
			<div>
				<span class="select_wrap">
					<select class="select" name="search_fld">
						<option value="subject"  >제목</option>
						<option value="contents"  >내용</option>
					</select>
				</span>
				<input type="text" class="txt" name="search" value="" />
				<button class="btn_search">검색</button>
			</div>
		</fieldset>
		<div class="page-size">
			<select name="page_size">
				<option value="100" >100</option>
				<option value="50" >50</option>
				<option value="40" >40</option>
				<option value="30" >30</option>
				<option value="20" >20</option>
				<option value="15"  selected="selected">15</option>
				<option value="10" >10</option>
			</select> 개씩 보기
		</div>
	</form>
	<!--Search Form ED-->
	<!--List ST-->
	<table class="listTable">
		<colgroup>
			<col width="3%" />
			<col width="6%" />
					<col width="100" />
			<col width="12%" />
			<col width="12%" />
			<col width="*" />
			<col width="8%" />		<col width="10%" />
			<col width="10%" />
			<col width="10%" />
		</colgroup>
		<thead>
			<tr>
				<th><input type="checkbox" class="check_all" data-check="chk_list" value="Y" /></th>
				<th>번호</th>
				<th>이미지</th>
				<th>시작일</th>
				<th>종료일</th>
				<th>제목</th>
				<th>첨부파일</th>
				<th>작성자</th>
				<th>작성일</th>
				<th>조회수</th>
			</tr>
		</thead>
		<tbody>
		<tr data-idno="4249" class="open">
			<td><input type="checkbox" class="chk_list" value="Y" /></td>
			<td>61</td>				<td><a href="#" class="act_view"><img src="/files/board_003/201811151557539346.png" style="max-width:100%;max-height:80px" /></a></td>
			<td>2019년 11월 24일(일)</td>
			<td>2019년 11월 27일(수)</td>
			<td class="al"><a href="#" class="act_view">15th Asian Pacific Congress of Hypertension (APCH2019)</a> 
			</td>
			<td>
			<a href="#" class="file-download" data-part="003" data-encname="" data-filename=""><img src="../css/icon/file.png" /></a> 		</td>		<td>관리자</td>
			<td>2018-11-15</td>
			<td>399</td>
		</tr>
		<tr data-idno="4238" class="open">
			<td><input type="checkbox" class="chk_list" value="Y" /></td>
			<td>60</td>				<td></td>
			<td>2019년 07월 22일(월)</td>
			<td>2019년 07월 26일(금)</td>
			<td class="al"><a href="#" class="act_view">2019 APSH-ISH Summer School</a> 
			</td>
			<td>
			<a href="#" class="file-download" data-part="003" data-encname="" data-filename=""><img src="../css/icon/file.png" /></a> 		</td>		<td>관리자</td>
			<td>2018-10-19</td>
			<td>278</td>
		</tr>
		</tbody>
	</table>
	<!--List ED-->
	<!--Page ST-->
	<p class="paging">PAGE : <b>1</b> / 1</p>
	<p class="pagination">
		<a href="#" class="pn_first"><img src="/backoffice/pub/images/paging1.png" alt="처음" /></a>
		<a href="#" class="pn_prev"><img src="/backoffice/pub/images/paging2.png" alt="이전" /></a>
		<span>
			<a href="#" class="on">1</a>
			<a href="#" class="">2</a>
			<a href="#" class="">3</a>
			<a href="#" class="">4</a>
			<a href="#" class="">5</a>
			<a href="#" class="">6</a>
			<a href="#" class="">7</a>
			<a href="#" class="">8</a>
			<a href="#" class="">9</a>
			<a href="#" class="">10</a>
		</span>
		<a href="#" class="pn_next"><img src="/backoffice/pub/images/paging3.png" alt="다음" /></a>
		<a href="#" class="pn_last"><img src="/backoffice/pub/images/paging4.png" alt="마지막" /></a>
	</p>
	<!--Page ED-->
	<p class="btn_r">
		<a href="#" class="btn_box act_del">선택삭제</a>
		<a href="#" class="btn_box act_ins">신규등록</a>
	</p>

</div>

<script>
$(function() {
	$(document).on('click', '.btn_ord', function() {
		var ord = $(this).hasClass('init') ? '' : $(this).data('fld')+($(this).hasClass('down') ? ' DESC' : '');
		$('#frm_search input[name=ord]').val(ord).closest('form').submit();
		return false;
	});
	$(document).on('click', '.category', function() {
		location.href = '?board_code=003&category_idno='+$(this).data('idno');
		return false;
	});
	$(document).on('click', '.act_ins', function() {
		location.href = '?board_code=003&mode=write';
		return false;
	});
	$(document).on('click', '.act_view', function() {
		var idno = $(this).closest('tr').length > 0 ? $(this).closest('tr').data('idno') : $(this).closest('li').data('idno');
		go_view('', idno);
		return false;
	});
	$(document).on('click', '.act_upt', function() {
		go_modify('', $(this).closest('tr').data('idno'));
		return false;
	});
	$(document).on('click', '.act_slide_view', function() {
		$(this).closest('.faq_q').trigger('click');
		return false;
	});
	$(document).on('click', '.act_del', function() {
		var idno = '';
		$.each($('.chk_list:checked'), function() {
			idno += (idno == '' ? '' : ',')+$(this).closest('tr').data('idno');
		});
		if (idno == '') {
			alert('선택된 게시물이 없습니다.');
			return false;
		}
		if (confirm('게시물을 삭제하시겠습니까?')) {
			$.ajax({
				type: 'post',
				dataType: 'json',
				data: {act:'delete', board_code:'003', idno:idno},
				url: '../common/board_proc.php',
				success: function(resp) {
					alert(resp.message);
					if (resp.result == 'success') {
						go_list('');
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert(errorThrown);
				}
			});
		}
		return false;
	});
	$(document).ready(function() {
		set_page_navi($('.pagination'), 61, 15, 1, 0);
	});
	function get_selected_idno() {
		var idno_str = '';
		$.each($('.chk_list:checked'), function() {
			idno_str += (idno_str == '' ? '' : ',')+($(this).closest('tr').length > 0 ? $(this).closest('tr').data('idno') : $(this).closest('li').data('idno'));
		});
		if (idno_str == '') alert('선택된 게시물이 없습니다.');
		return idno_str;
	}
});
</script>

<?	include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/_footer.php";	?>