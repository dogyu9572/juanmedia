<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/research/research.lib.php";
if(!in_array("research_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<h2 class="admin-title">설문 등록</h2>

<script language="javascript">
function CheckForm(frm){
	if (frm.subject.value==""){
		alert("제목을 입력해 주십시요.");
		frm.subject.focus();
		return false;
	}
	if (frm.sdate.value==""){
		alert("시작일을 입력해 주십시요.");
		frm.sdate.focus();
		return false;
	}
	if (frm.edate.value==""){
		alert("종료일을 입력해 주십시요.");
		frm.edate.focus();
		return false;
	}
}

//문항 추가정보 열 추가
var rowcount = 0;
function append() {   
 var tbl = document.getElementById("research_question").getElementsByTagName("TBODY")[0];  
 var html1 = "<div class='space-left'><input type='text' name='question[]' style='width:500px;' maxlength='100' class='input' /></div>";  
 var row = document.createElement("tr"); 
 var col1 = document.createElement("td");   
 row.appendChild(col1);  
 col1.innerHTML = html1;  
 tbl.appendChild(row);  
 rowcount++;
}

function remove() {  
	if(rowcount > 0){
		var tbl = document.getElementById("research_question").getElementsByTagName("TBODY")[0];  
		if (tbl.hasChildNodes()) {      
			tbl.removeChild(tbl.lastChild);     // 마지막 로우   //tbl.removeChild(tbl.firstChild);  // 첫번째 로우  
		}
		rowcount--;
	}
}
//문항 추가정보 열 추가
</script>
<form name="frmInfo" method="post" action="research_evn.php" ENCTYPE="multipart/form-data" onSubmit="return CheckForm(this)">
<input type="hidden" name="evnMode" value="create">


		<!-- 기본정보 -->
		<h3 class="admin-title-middle">기본정보</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>설문제목</th>
				<td class="space-left"><input type="text" name="subject" style="width:99%" maxlength="200" class="input" /></td>
			</tr>
			<tr>
				<th>로그인사용</th>
				<td class="space-left">
				<input type="radio" name="use_login" id="loginY" value="Y" checked><label for="loginY">로그인후 설문가능</label>
				</td>
			</tr>
			<tr>
				<th>보이기</th>
				<td class="space-left">
				<input type="radio" name="is_show" id="showY" value="Y"><label for="showY">설문보이기</label> &nbsp;&nbsp;
				<input type="radio" name="is_show" id="showN" value="N" checked><label for="showN">설문숨김</label>
				</td>
			</tr>
			<tr>
				<th>시작일</th>
				<td class="space-left"><input type="text" name="sdate" readonly onclick="popUpCalendar(this, sdate, 'yyyy-mm-dd')" value='' class="input"></td>
			</tr>
			<tr>
				<th>종료일</th>
				<td class="space-left"><input type="text" name="edate" readonly onclick="popUpCalendar(this, edate, 'yyyy-mm-dd')" value='' class="input"></td>
			</tr>
		  </tbody>
		</table>
		<br />

		<!-- 설문항목 -->
		<h3 class="admin-title-middle">설문항목 &nbsp; <a href="javascript:append();"><img src="/backoffice/images/k_add.gif" alt="추가" align="top" /></a> <a href="javascript:remove();"><img src="/backoffice/images/k_delete.gif" alt="삭제" align="top" /></a></h3>
		<table id="research_question" class="admin-table-type1">
		  <tbody>
			<tr>
				<th>설문항목</th>
			</tr>
		  </tbody>
		</table>

		<div class="admin-buttons">
			<div class="cen">
				<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="설문등록" style="font-weight:bold" /></span>
			</div>
		</div>

</form>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>