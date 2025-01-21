<?
header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//카테고리 목록
$arrList = getCategoryList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));

//DB해제
SetDisConn($dblink);
if($arrList["total"] > 0){
?>
<table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="fa493f" bgcolor="#FFFFFF" onmouseover="$('divSubCategory').show()"  onmouseout="$('divSubCategory').hide()">
  <tr>
    <td style="background: url(/images/w.gif) bottom repeat-x;">
<?
for($i=0;$i<$arrList["total"];$i++){
?>
      <li class="none"><a href="/shop.php?goPage=GoodList&cat_no=<?=$arrList["list"][$i]['cat_no']?>&listmode=b" class="sn"><?=stripslashes($arrList["list"][$i]['cat_name'])?></a></li>
<?
}
?>
    </td>
  </tr>
</table>
<?}?>