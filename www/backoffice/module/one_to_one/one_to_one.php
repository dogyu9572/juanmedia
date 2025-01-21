<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/one_to_one/one_to_one.lib.php";
if(!in_array("one_to_one_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$sclae="20";

$arrList = getOneToOneListAll(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sw]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sk]), $scale, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[offset]));
//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function delOneToOne(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 문의건을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmOneToOneHidden.idx.value = idx;
		document.frmOneToOneHidden.submit();
	}
}
</script>
<div id="admin-container">
	<? include "../board/menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">1:1 문의관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 게시판 관리 &nbsp;&gt;&nbsp; 1:1 문의 목록</div>
	</div>
		
		<div class="admin-search">
			<div class="total">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
		</div>

		<table class="admin-table-type1">
		<thead>
		  <tr>
			<th width="5%">No.</th>
			<th width="10%">이름</th>
			<th width="30%">제목</th>
			<th width="10%">처리</th>
			<th width="15%">신청일</th>
			<th width="10%">수정 | 삭제</th>
		</tr>
		</thead>
		<tbody>
		<?
		//DB연결
		$dblink = SetConn($_conf_db["main_db"]);

		if($arrList['list']['total'] > 0):?>
		<?for ($i=0;$i<$arrList['list']['total'];$i++) {
		?>
		<tr height="25" align="center">
		  <td><?=number_format($arrList['total']-$i-$offset)?></td>
		  <td><?=$arrList['list'][$i]['user_name']?><?if($arrList['list'][$i]['user_id']):?> (<a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>"><?=$arrList['list'][$i]['user_id']?></a>)<?endif;?></td>
		  <td align="left"><a href="one_to_one_info.php?o_type=<?=$arrList['list'][$i]['o_type']?>&idx=<?=$arrList['list'][$i]['idx']?>"><?=stripslashes($arrList['list'][$i]['subject'])?></a></td>
		  <td><img src="<?=$arrList['list'][$i]['status']=="Y"?"/common/images/icon_y.gif":"/common/images/icon_n.gif"?>"></td>
		  <td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
		  <td class="b02"><a href="one_to_one_info.php?o_type=<?=$arrList['list'][$i]['o_type']?>&idx=<?=$arrList['list'][$i]['idx']?>&listURL=<?=urlencode($_SERVER[REQUEST_URI])?>">수정</a> | <a href="javascript:delOneToOne('<?=$arrList['list'][$i]['idx']?>');">삭제</a></td>
		</tr>
		<?}?>

		<?else:?>
		<tr height="100" align="center">
		  <td width="100%" colspan="8" >등록된 문의건이 없습니다.</td>
		</tr>
		<?endif;
		//DB해제
		SetDisConn($dblink);
		?>
		</tbody>
		</table>

		<div class="paginate">
			<?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"o_type=".$_REQUEST[o_type])?>
		</div>

<form name="frmOneToOneHidden" method="post" action="one_to_one_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
<input type="hidden" name="rt_url" value="<?=$_SERVER[REQUEST_URI]?>">
</form>

	</div>
</div>

<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>
