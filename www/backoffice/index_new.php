<?	include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/_header.php";		?>
<?	include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/_leftMenu.php";	?>
<div id="admin-content">
	<h2 class="admin-title">관리자관리</h2>
	<p class="btn_r">
		<a href="#" class="btn_box act_ins">신규등록</a>
	</p>
	<!--Search Form ST-->
	<fieldset class="search_box">
		<form id="frm_search" name="frm_search" method="get" action="" >
		<span class="select_wrap">
			<label>권한</label>
			<select name="auth_cd" class="select">
			<option value="">전체</option>
					<option value="MEM020" >특별회원</option>
					<option value="MEM030" >명예회원</option>
					</select>
			<label>검색어</label>
			<select class="select" name="search_fld">
				<option value="id"  >아이디</option>
				<option value="name"  >성명</option>
			</select>
			<input type="text" class="txt" name="search" value="" />
		</span>
		<button class="btn_search">검색</button>
		</form>
	</fieldset>
	<!--Search Form ED-->
	<!--List ST-->
	<table class="listTable">
		<colgroup>
			<col width="50" />
			<col width="100" />
			<col width="150" />
			<col width="150" />
			<col width="*" />
			<col width="150" />
			<col width="150" />
			<col width="140" />
		</colgroup>
		<thead>
		<tr>
			<th>번호</th>
			<th>등급</th>
			<th>아이디</th>
			<th>성명</th>
			<th>메뉴 권한</th>
			<th>가입일시</th>
			<th>최종접속일시</th>
			<th>관리</th>
		</tr>
		</thead>
		<tbody>
		<tr data-idno="1">
			<td>1</td>
			<td>명예회원</td>
			<td><a href="#" class="act_view">admin</a></td>
			<td><a href="#" class="act_view">최고관리자</a></td>
			<td class="al">전체권한</td>
			<td>2018-01-01 (00:00)</td>
			<td>2019-09-20 (11:10)</td>
			<td class="al"><a href="#" class="btn_icon modify act_view">수정</a></td>
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
</div>
<?	include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/_footer.php";	?>