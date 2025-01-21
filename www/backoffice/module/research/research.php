<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/research/research.lib.php";
if(!in_array("research_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$scale = 20;

//제품 리스트
$arrList = getResearchList($scale, $_REQUEST[offset]);

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<h2 class="admin-title">투표 관리</h2>

<script language="javascript">
function delResearch(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 설문을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmListHidden.idx.value = idx;
		document.frmListHidden.submit();
	}
}
</script>
<div class="mgb5">
	&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong>
</div>

<table class="admin-table-type1">
  <thead>
	<tr>
	  <th width="5%">No.</th>
	  <th width="30%">설문제목</th>
	  <th width="8%">참여자목록</th>
	  <th width="8%">로그인사용</th>
	  <th width="8%">보이기</th>
	  <th width="8%">시작일</th>
	  <th width="8%">종료일</th>
	  <th width="15%">등록일</th>
	  <th width="10%">관리</th>
	</tr>
  </thead>
  <tbody>
	<?if($arrList['list']['total'] > 0):?>

	<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
	<tr>
		<td><?=$arrList['list'][$i]['idx']?></td>
		<td><?=$arrList['list'][$i]['subject']?></td>
		<td><span class="btn_pack small"><a href="research_join_list.php?idx=<?=$arrList['list'][$i]['idx']?>">목록보기</a></span></td>
		<td><?=$arrList['list'][$i]['use_login']?></td>
		<td><?=$arrList['list'][$i]['is_show']?></td>
		<td><?=$arrList['list'][$i]['sdate']?></td>
		<td><?=$arrList['list'][$i]['edate']?></td>
		<td><?=$arrList['list'][$i]['wdate']?></td>
		<td><a href="research_info.php?idx=<?=$arrList['list'][$i]['idx']?>"><img src="/backoffice/images/k_modify.gif" alt="수정" /></a> <a href="javascript:delResearch('<?=$arrList['list'][$i]['idx']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
	</tr>
	<?}?>

	<?else:?>
	<tr height="100">
	  <td width="100%" colspan="12" >등록된 설문이 없습니다.</td>
	</tr>
	<?endif;?>
  </tbody>
</table>

<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"")?>
</div>

<form name="frmListHidden" method="post" action="research_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
</form>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>