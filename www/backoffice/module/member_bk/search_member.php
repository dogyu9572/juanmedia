<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/module/admin/admin.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="imagetoolbar" content="no" />
<title><?=$_SITE["NAME"]?> 관리자</title>
<link href="/backoffice/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale = 20;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getMemberList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), $scale, $_REQUEST['offset']);
//_DEBUG($arrList);

$arrLevel = getArticleList($_conf_tbl["member_level"], 0, 0, "order by level_no desc ");

for($i=0;$i<$arrLevel["total"];$i++){
	$arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}
//DB해제
SetDisConn($dblink);
?>
<script>
function addCoupon(id, name) {
	var cfm;
	cfm =false;
	cfm = confirm(name + " 님에게 <?=$_GET[gb]=="G"?"상품권":"쿠폰"?>을 등록 하시겠습니까?");
	if(cfm==true){
		document.frmContentsHidden.user_id.value = id;
		document.frmContentsHidden.submit();
	}
}
</script>

<table border="0" width="100%" cellpadding="3" cellspacing="0" align="center">
  <tr>
    <td><b>전체 : <?=number_format($arrList['total'])?> 명</b></td>
  </tr>
</table>
<table border="0" width="400" cellpadding="2" cellspacing="1">
        <form name="frmSort" method="get" action="search_member.php">
        <input type="hidden" name="idx" value="<?=$_GET[idx]?>">
		<input type="hidden" name="gb" value="<?=$_GET[gb]?>">
		<tr>
                <td width="80" bgcolor="#646464"><font color="white">검색범위</font></td>
                <td width="320">
                <select name="sw">
                <option value="all"<?=$_REQUEST['sw']=="all"?" selected":""?>>전체</option>
                <option value="name"<?=$_REQUEST['sw']=="name"?" selected":""?>>성명</option>
                <option value="id"<?=$_REQUEST['sw']=="id"?" selected":""?>>아이디</option>
				<option value="mobile"<?=$_REQUEST['sw']=="mobile"?" selected":""?>>핸드폰</option>
                </select>
                </td>
        </tr>
        <tr>
                <td bgcolor="#646464"><font color="white">키워드</font></td>
                <td>
                <input type="text" name="sk" value="<?=$_REQUEST['sk']?>"> <input type="submit" value="조회">
                </td>
        </tr>

        </form>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
<tr height="25" align="center" bgcolor="#646464">
  <td width="5%"><font color="#ffffff">No.</font></td>
  <td width="10%"><font color="#ffffff">등급</font></td>
  <td width="20%"><font color="#ffffff">ID(이메일)</font></td>
  <td width="10%"><font color="#ffffff">성명</font></td>
  <td width="5%"><font color="#ffffff">생년</font></td>
  <td width="5%"><font color="#ffffff">성별</font></td>
  <td width="5%"><font color="#ffffff">로그인횟수</font></td>
  <td width="10%"><font color="#ffffff">등록일</font></td>
</tr>
<?if($arrList['list']['total'] > 0):?>

<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
<tr height="25" align="center" onclick="javascript:addCoupon('<?=$arrList['list'][$i]['user_id']?>','<?=$arrList['list'][$i]['user_name']?>')" style="cursor:pointer">
  <td><?=number_format($arrList['total']-$i-$offset)?></td>
  <td><?=$arrayLevel[$arrList['list'][$i]['user_level']]?></td>
  <td><?=$arrList['list'][$i]['user_id']?></td>
  <td><?=$arrList['list'][$i]['user_name']?></td>
  <td><?=substr($arrList['list'][$i]['birth'],0,4)?></td>
  <td><?=$arrList['list'][$i]['sex']=="M"?"<font color=blue>남</font>":"<font color=red>여</font>"?></td>
  <td><?=number_format($arrList['list'][$i]['login_count'])?></td>
  <td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
</tr>
<tr>
  <td colspan="10" height="1" bgcolor="646464"></td>
</tr>
<?}?>

<?else:?>
<tr height="100" align="center">
  <td width="100%" colspan="10" >등록된 회원이 없습니다.</td>
</tr>
<tr>
  <td colspan="10" height="1" bgcolor="646464"></td>
</tr>
<?endif;?>
</table>

<br />
<table border="0" cellpadding="0" cellspacing="1" width="100%">
<tr height="25" align="center">
  <td><?=pageNavigation($arrList['total'],$scale,$pagescale,$_REQUEST['offset'],"&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&s_date=".$_REQUEST['s_date']."&e_date=".$_REQUEST['e_date']."&idx=".$_REQUEST[idx])?></td>
</tr>
</table>

<? if($_GET[gb]=="G") {?>
<form name="frmContentsHidden" method="post" action="/backoffice/module/giftcard/giftcard_evn.php">
<input type="hidden" name="evnMode" value="add_giftcard">
<?} else {?>
<form name="frmContentsHidden" method="post" action="/backoffice/module/coupon/coupon_evn.php">
<input type="hidden" name="evnMode" value="add_coupon">
<?}?>
<input type="hidden" name="user_id">
<input type="hidden" name="idx" value="<?=$_GET[idx]?>">
</form>

</body>
</html>