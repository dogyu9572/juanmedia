<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getArticleList($_conf_tbl["member_level"], $scale, $_REQUEST['offset'], "order by level_no desc ");
//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">회원등급 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 회원 관리 &nbsp;&gt;&nbsp; 회원등급 목록</div>
	</div>

<script language="javascript">
function delMemberLevel(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 회원등급을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmMemberLevelHidden.idx.value = idx;
		document.frmMemberLevelHidden.submit();
	}
}

function CheckForm(frm){ 
	if (frm.level_no.value==""){
		alert("등급을 입력하여 주십시요.");
		frm.level_no.focus();
		return false;
	}
	if (frm.level_name.value==""){
		alert("이름을 입력하여 주십시요.");
		frm.level_name.focus();
		return false;
	}
}
</script>
<div class="admin-search">
  <form name="frmMemberLevel" method="post" action="member_level_evn.php" onSubmit="return CheckForm(this)">
  <input type="hidden" name="evnMode" value="createMemberLevel">
    <div class="total">&nbsp;<strong>전체 : <?=number_format($arrList['list']['total'])?> 개</strong></div>
	<div class="keyword">
      신규 회원등급분야 등급: <input type="text" name="level_no" size="2" maxlength="2" style="width:35px;" class="input" /> &nbsp;&nbsp; 이름: <input type="text" name="level_name" size="20" maxlength="20" class="input" /> <span class="btn_pack medium icon"><span class="add"></span><input type="submit" value="회원등급 생성" /></span>
    </div>
  </form>
</div>
<table class="admin-table-type1">
  <thead>
	<tr>
	  <th width="5%">No.</th>
	  <th width="10%">회원등급</th>
	  <th width="15%">등급이름</th>
	  <th width="10%">등록일</th>
	  <th width="10%">관리</th>
	</tr>
  </thead>
  <tbody>
	<?if($arrList['list']['total'] > 0):?>

	<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
	<tr>
	  <td><?=number_format($arrList['total']-$i-$offset)?></td>
	  <td><?=$arrList['list'][$i]['level_no']?></td>
	  <td><?=$arrList['list'][$i]['level_name']?></td>
	  <td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
	  <td><a href="member_level_info.php?idx=<?=$arrList['list'][$i]['idx']?>"><img src="/backoffice/images/k_modify.gif" alt="수정" /></a> <a href="javascript:delMemberLevel('<?=$arrList['list'][$i]['idx']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
	</tr>
	<?}?>

	<?else:?>
	<tr height="100">
	  <td width="100%" colspan="8" >생성된 회원등급이 없습니다.</td>
	</tr>
	<?endif;?>
  </tbody>
</table>

<br />
<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"")?>
</div>

<form name="frmMemberLevelHidden" method="post" action="member_level_evn.php">
<input type="hidden" name="evnMode" value="deleteMemberLevel">
<input type="hidden" name="idx">
</form>
</div>
</div>

<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>