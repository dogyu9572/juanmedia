<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//현재 상품정보 가져오기
$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx']));

//DB해제
SetDisConn($dblink);

//_DEBUG($arrInfo);
//존재여부 검사
if($arrInfo["total"]==0){
	jsMsg("존재하지 않는 상품입니다.");
	selfClose();
}

//진열여부 검사
if($arrInfo["list"][0]['is_show']=="N"){
	jsMsg("진열중인 상품이 아닙니다.");
	selfClose();
}

//선택한 이미지 번호가 없으면 첫번째 것 선택
if(!$_REQUEST['seq']){
	$_REQUEST['seq'] = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title><?=mysqli_real_escape_string($GLOBALS['dblink'], $arrInfo["list"][0]['g_name'])?></title>
</head>
<style>
body {margin:0; padding:0;}
</style>
<script language=javascript> 
	moveTo(0,0);
	<?if($arrInfo["total_files"] > 1){?>
	resizeTo(<?=($arrInfo["files"][$_REQUEST['seq']][width]+12)?>,<?=($arrInfo["files"][$_REQUEST['seq']][height]+183)?>);
	<?}else{?>
	resizeTo(<?=($arrInfo["files"][$_REQUEST['seq']][width]+12)?>,<?=$arrInfo["files"][$_REQUEST['seq']][height]+75?>);
	<?}?>
</script>
<body>
<table cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top">
	<a href="javascript:self.close();"><img src="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["files"][$_REQUEST['seq']][re_name]?>" border="0" alt="<?=mysqli_real_escape_string($GLOBALS['dblink'], $arrInfo["list"][0]['g_name'])?>"></a></td>
  </tr>
  <!-- 이미지 목록 -->
  <?if($arrInfo["total_files"] > 1){?>
  <tr height="10"><td></td></tr>
  <tr>
    <td valign="top" width="100" align="center">
	<?for($i=0; $i<$arrInfo["total_files"]; $i++){?>
	<a href="<?=$_SERVER['PHP_SELF']?>?g_idx=<?=$arrInfo["list"][0]['idx']?>&seq=<?=$i?>"><img src="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/s_<?=$arrInfo["files"][$i]['re_name']?>" border="0"></a>&nbsp;
	<?}?>
	</td>
  </tr>
  <?}?>
</table>

</body>
</html>
