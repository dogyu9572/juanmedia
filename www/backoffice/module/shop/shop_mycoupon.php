<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));

$arrList = getMycouponList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']), 0, 0);

//DB해제
SetDisConn($dblink);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="imagetoolbar" content="no" />
<title><?=$_SITE["NAME"]?> 관리자</title>
<link href="/backoffice/css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript">
<!--
function deleteMycoupon(idx){
	if(confirm('해당 쿠폰을 삭제하시겠습니까?')){
		document.location = "../coupon/coupon_evn.php?evnMode=deletemycoupon&idx=<?=$_REQUEST['idx']?>&c_idx=" + idx;
	}
}
//-->
</script>
</head>
<body>
<table width="100%"cellpadding=6 cellspacing=0>
<tr>
<td>
	
<h3 class="subTitle">상품명: <?=stripslashes($arrInfo["list"][0]['g_name'])?></h3>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
  <tr height="25" align="center" bgcolor="#646464">
	<th width="5%" height="25"><font color="#ffffff">번호</font></th>
    <th width="12%"><font color="#ffffff">회원이름</font></th>
    <th width="15%"><font color="#ffffff">회원아이디</font></th>
    <th width="25%"><font color="#ffffff">기간</font></th>
    <th width="20%"><font color="#ffffff">발급시간</font></th>
    <th width="8%"><font color="#ffffff">사용여부</font></th>
    <th width="8%"><font color="#ffffff">기능</font></th>
  </tr>
  <?if($arrList['list']['total'] > 0):?>
  <?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
  <tr height="25" align="center">
	  <td><?=$arrList['total']-$offset-$i?></td>
	  <td><?=stripslashes($arrList['list'][$i]['user_name'])?></td>
	  <td><?=$arrList['list'][$i]['user_id']?></td>
	  <td><?=$arrList['list'][$i]['coupon_sdate']?> ~ <?=$arrList['list'][$i]['coupon_edate']?></td>
	  <td><?=$arrList['list'][$i]['wdate']?></td>
  	  <td><?=$arrList['list'][$i]['coupon_use']?></td>
	  <td align="center"><a href="javascript:deleteMycoupon('<?=$arrList['list'][$i]['idx']?>')">삭제</a></td>
	</tr>
	<tr>
	  <td colspan="10" height="1" bgcolor="646464"></td>
	</tr>
	<?}?>

	<?else:?>
	<tr height="100" align="center">
	  <td width="100%" colspan="8" >발급회원이 없습니다.</td>
	</tr>
	<tr>
	  <td colspan="10" height="1" bgcolor="646464"></td>
	</tr>
	<?endif;?>
</table>


<table border="0" cellpadding="0" cellspacing="1" width="100%">
<tr height="25" align="center">
  <td><?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"cat_no=".$_REQUEST['cat_no']."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&st=".$_REQUEST['st'])?></td>
</tr>
</table>

</td>
</tr>
</table>
</body>
</html>