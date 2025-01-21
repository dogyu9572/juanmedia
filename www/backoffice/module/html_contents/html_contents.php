<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/html_contents/html_contents.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$scale="20";

$arrList = getArticleList($_conf_tbl["html_contents"], $scale, $_REQUEST[offset]);
//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">컨텐츠 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 컨텐츠 관리 &nbsp;&gt;&nbsp; 컨텐츠 목록</div>
	</div>

<script language="javascript" src="/common/util.js"></script>
<script language="javascript">
function delContents(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 컨텐츠를 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmContentsHidden.idx.value = idx;
		document.frmContentsHidden.submit();
	}
}

function CheckForm(frm){ 
	if (frm.id.value==""){
		alert("ID 를 입력하여 주십시요.");
		frm.id.focus();
		return false;
	}
	if (frm.id.value.length < 2 || frm.id.value.length > 20) {
		alert("ID는 2~20자리입니다.");
		frm.id.focus();
		return false;
	}
	if (hangul_chk(frm.id.value) != true ){
		alert("ID에 한글이나 여백은 사용할 수 없습니다.");
		frm.id.focus();
	 	return false;
	}

}
</script>

<div class="admin-search">
  <form name="frmContents" method="post" action="html_contents_evn.php" onSubmit="return CheckForm(this)">
  <input type="hidden" name="evnMode" value="createContents">
	<div class="total">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>	
	<!-- <div class="keyword">신규 컨텐츠 ID : <input type="text" name="id" size="30" maxlength="20" class="input" /> <span class="btn_pack medium icon"><span class="add"></span><input type="submit" value="컨텐츠 생성"></span></div> -->
  </form>
</div>

<table class="admin-table-type1">
  <thead>
	<tr>
	  <th width="5%">No.</th>
	  <th width="15%">컨텐츠 코드</th>
	  <th width="20%">컨텐츠 제목</th>
	  <th width="10%">HTML</th>
	  <th width="10%">생성일</th>
	  <th width="10%">관리</th>
	</tr>
  </thead>
  <tbody>
	<?if($arrList['list']['total'] > 0):?>

	<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
	<tr>
	  <td><?=number_format($arrList['total']-$i-$offset)?></td>
	  <td><?=$arrList['list'][$i]['code']?></td>
	  <td class="space-left"><a href="html_contents_info.php?idx=<?=$arrList['list'][$i]['idx']?>"> <?=$arrList['list'][$i]['subject']?></a></td>
	  <td><?=$arrList['list'][$i]['usehtml']?></td>
	  <td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
	  <td><a href="html_contents_info.php?idx=<?=$arrList['list'][$i]['idx']?>"><img src="/backoffice/images/k_modify.gif" alt="수정" /></a>
	  <!-- <?if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT"):?><a href="javascript:delContents('<?=$arrList['list'][$i]['idx']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a><?endif;?>--></td>
	</tr>
	<?}?>

	<?else:?>
	<tr height="100">
	  <td width="100%" colspan="8" >생성된 컨텐츠가 없습니다.</td>
	</tr>
	<?endif;?>
  </tbody>
</table>

<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"")?>
</div>

<form name="frmContentsHidden" method="post" action="html_contents_evn.php">
<input type="hidden" name="evnMode" value="deleteContents">
<input type="hidden" name="idx">
</form>
  </div>
</div>

<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>