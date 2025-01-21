<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";

################################################
## 퀵메뉴 최근본상품 삭제시
################################################
$arrQuickGood = explode(",",$_SESSION[$_SITE["DOMAIN"]]["GOODIDXS"]);
$reGoodIdxs = "";
$comma = ",";

if($_REQUEST['evnMode']=="del"){
	for($i=1;$i<count($arrQuickGood);$i++){
		if($_REQUEST['g_idx']==$arrQuickGood[$i]){
			## 삭제
		}else{
			$reGoodIdxs .= $comma.$arrQuickGood[$i];			
		}
	}
}else if($_REQUEST['evnMode']=="add"){
	$reGoodIdxs = ",".$_REQUEST['g_idx'].$_SESSION[$_SITE["DOMAIN"]]["GOODIDXS"];
}
$_SESSION[$_SITE["DOMAIN"]]["GOODIDXS"] = $reGoodIdxs;
//echo $reGoodIdxs;
?>true