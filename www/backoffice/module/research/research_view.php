<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/research/research.lib.php";
if(!in_array("research_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//설문정보
$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["research_info"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[r_idx]));

//설문 결과정보
$arrResearchResult = getResearchJoinView($arrInfo["list"][0][idx], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<h2 class="admin-title">설문 결과</h2>


		<!-- 기본정보 -->
		<h3 class="admin-title-middle">설문정보</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="0" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>설문제목</th>
				<td class="space-left"><?=stripslashes($arrInfo["list"][0][subject])?></td>
			</tr>
			<tr>
				<th>로그인사용</th>
				<td class="space-left"><?=$arrInfo["list"][0][use_login]=="Y"?"로그인후 설문가능":"아무나 설문가능"?></td>
			</tr>
			<tr>
				<th>보이기</th>
				<td class="space-left"><?=$arrInfo["list"][0][is_show]=="Y"?"설문보이기":"설문숨김"?></td>
			</tr>
			<tr>
				<th>시작일</th>
				<td class="space-left"><?=$arrInfo["list"][0][sdate]?></td>
			</tr>
			<tr>
				<th>종료일</th>
				<td class="space-left"><?=$arrInfo["list"][0][edate]?></td>
			</tr>
			<tr>
				<th>등록일</th>
				<td class="space-left"><?=$arrInfo["list"][0][wdate]?></td>
			</tr>
		  </tbody>
		</table>
		<br />

		<!-- 설문항목 -->
		<h3 class="admin-title-middle">설문결과</h3>
		<div class="border-box">
		<table id="research_answer" border="0" cellpadding="3" cellspacing="1" width="100%">
			<?for($i=0;$i<$arrResearchResult["total"];$i++){?>
			<tr>
				<td class="d_icon" width="10"><img src="/backoffice/images/arrow.gif" align="absmiddle"></td>
				<td class="join_td" align="left"><b><?=$arrResearchResult["list"][$i][question]?></b></td>
			</tr>
			<tr>
				<td class="d_icon" width="10"></td>
				<td class="join_td" align="left"><?=$arrResearchResult["list"][$i][answer]?></td>
			</tr>
			<?}?>
		</table>
		</div>

	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>