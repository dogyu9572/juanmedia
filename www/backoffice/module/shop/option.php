<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//옵션 리스트
$arrList = getOptionList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 0, 0);

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu_good.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">상품옵션 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 상품 관리 &nbsp;&gt;&nbsp; 상품옵션 목록</div>
	</div>

<script language="javascript">
function delOption(code){
	var cfm;
	cfm =false;
	cfm = confirm("해당 옵션을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmListHidden.code.value = code;
		document.frmListHidden.submit();
	}
}
</script>

<!-- <form name="frmSort" method="get" action="<?=$_SERVER['PHP_SELF']?>">
<h3 class="admin-title-middle">옵션검색</h3>
<input type="hidden" name="cat_no" id="cat_no">
<table class="admin-table-type1">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
	<tr>
	  <th>옵션명</th>
	  <td class="space-left"><input type="text" name="sk" value="<?=$_REQUEST['sk']?>" class="input" style="width:300px;" /> <input type="image" src="/backoffice/images/btn_search.gif" alt="검색"/></td>
	</tr>
  </tbody>
</table>
</form>

<br /> -->

<div class="mgb5">
	<div class="fl">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
</div>

<table class="admin-table-type1">
  <thead>
	<tr>
	  <th width="50">No.</th>
	  <th width="120">옵션코드</th>
	  <th width="200">옵션명</th>
	  <th width="*">옵션값</th>
	  <th width="100">관리</th>
	</tr>
  </thead>
  <tbody>
<?
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($arrList['list']['total'] > 0):?>
<?for ($i=0;$i<$arrList['list']['total'];$i++) {
	$arrVal = getArticleList($GLOBALS["_conf_tbl"]["shop_opt_val"], 0, 0, "WHERE opt_code='".$arrList['list'][$i]['opt_code']."' order by idx");
?>
<tr>
	<td><?=$arrList["total"]-$i-$_GET['offset']?></td>
	<td><?=$arrList['list'][$i]['opt_code']?></td>
	<td class="space-left"><?=stripslashes($arrList['list'][$i]['opt_name'])?></td>
	<td class="space-left">
	<? for ($j=0;$j<$arrVal['list']['total'];$j++) {
		echo $arrVal['list'][$j]['opt_value'];
		if($arrVal['list'][$j]['opt_price']>0) {
			echo " (+".number_format($arrVal['list'][$j]['opt_price']).")";
		}
		if($j!=$arrVal['list']['total']-1) {
			echo ", ";
		}
	} ?>
	</td>
	<td><a href="option_info.php?opt_code=<?=$arrList['list'][$i]['opt_code']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><img src="/backoffice/images/k_modify.gif" alt="수정" /></a> <a href="javascript:delOption('<?=$arrList['list'][$i]['opt_code']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
</tr>
<?}?>

<?else:?>
<tr height="100">
  <td colspan="12" >등록된 옵션이 없습니다.</td>
</tr>

<?endif;
//DB해제
SetDisConn($dblink);
?>
  </tbody>
</table>

<!-- <div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"cat_no=".$_REQUEST['cat_no']."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk'])?>
</div>
 -->
<form name="frmListHidden" method="post" action="good_evn.php">
<input type="hidden" name="evnMode" value="deleteOption">
<input type="hidden" name="code">
</form>

</div>
</div>

<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>
