<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

$fp = fopen($_SERVER['DOCUMENT_ROOT']."/uploaded/sumep.txt", "a");
ob_start();

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//상품 리스트
$arrInfo = getGoodInfo($_REQUEST['idx']);

//DB해제
SetDisConn($dblink);
?>
<<<begin>>>
<<<mapid>>><?=$arrInfo['list'][0]['g_code']?><? echo "\n";?>
<<<pname>>><?=stripslashes(iconv("utf-8","euc-kr",$arrInfo['list'][0]['g_name']))?><? echo "\n";?>
<<<price>>><?=$arrInfo['list'][0]['price']?><? echo "\n";?>
<<<class>>>U
<<<utime>>><?=date("Y-m-d H:s:i")?><? echo "\n";?>
<<<ftend>>>
<?
//echo "\n";

$msg = ob_get_contents();
ob_end_clean();
fwrite($fp, $msg);
fclose($fp);
?>
