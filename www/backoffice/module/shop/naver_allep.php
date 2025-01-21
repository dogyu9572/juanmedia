<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

$fp = fopen($_SERVER['DOCUMENT_ROOT']."/uploaded/allep.txt", "w");
ob_start();

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$order_by = " A.idx DESC ";

//상품 리스트
$arrList = getGoodListBaseNFile(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	"0", $_REQUEST['offset'],"Y");

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

//DB해제
SetDisConn($dblink);

if($arrList['list']['total'] > 0):
for ($i=0;$i<$arrList['list']['total'];$i++) {
	$arrThisCatCode = explode("/", $arrList["list"][$i]['cat_code']);
	
	if($arrThisCatCode[0]!="3") {
		if($arrList['list'][$i]['price'] < 50000) {
			$shipprice = "3000";
		} else {
			$shipprice = "0";
		}
?>
<<<begin>>>
<<<mapid>>><?=$arrList['list'][$i]['g_code']?><? echo "\n";?>
<<<pname>>><?=stripslashes(iconv("utf-8","euc-kr",$arrList['list'][$i]['g_name']))?><? echo "\n";?>
<<<price>>><?=$arrList['list'][$i]['price']?><? echo "\n";?>
<<<pgurl>>>http://www.minimonorail.co.kr/shop.php?goPage=GoodDetail&g_code=<?=$arrList['list'][$i]['g_code']?><? echo "\n";?>
<<<igurl>>>http://www.minimonorail.co.kr/uploaded/shop_good/<?=$arrList['list'][$i]['idx']?>/<?=$arrList['list'][$i]['p_image']?><? echo "\n";?>
<<<cate1>>><?=iconv("utf-8","euc-kr",$arrAllCategory[$arrThisCatCode[0]])?><? echo "\n";?>
<<<cate2>>><?=iconv("utf-8","euc-kr",$arrAllCategory[$arrThisCatCode[1]])?><? echo "\n";?>
<<<cate3>>>
<<<cate4>>>
<<<caid1>>><?=$arrThisCatCode[0]?><? echo "\n";?>
<<<caid2>>><?=$arrThisCatCode[1]?><? echo "\n";?>
<<<caid3>>>
<<<caid4>>>
<<<model>>><?=stripslashes(iconv("utf-8","euc-kr",$arrList['list'][$i]['model']))?><? echo "\n";?>
<<<brand>>><?=stripslashes(iconv("utf-8","euc-kr",$arrList['list'][$i]['brand']))?><? echo "\n";?>
<<<maker>>><?=stripslashes(iconv("utf-8","euc-kr",$arrList['list'][$i]['vendor']))?><? echo "\n";?>
<<<origi>>><?=stripslashes(iconv("utf-8","euc-kr",$arrList['list'][$i]['madein']))?><? echo "\n";?>
<<<pdate>>>
<<<deliv>>><?=$shipprice?><? echo "\n";?>
<<<event>>>
<<<coupo>>>
<<<pcard>>>
<<<point>>>
<<<mvurl>>>
<<<selid>>>
<<<barcode>>>
<<<cardn>>>
<<<cardp>>>
<<<mpric>>>
<<<revct>>>
<<<ecoyn>>>
<<<econm>>>
<<<gtype>>>
<<<branc>>>
<<<ftend>>>
<?
//echo "\n";
	}
} endif; 

$msg = ob_get_contents();
ob_end_clean();
fwrite($fp, $msg);
fclose($fp);
?>
<!-- 전체 EP 생성 / 갱신 완료<br>
상품 DB URL : http://www.minimonorail.co.kr/uploaded/allep.txt<br>
<br>
<input type="button" value=" 닫 기 " onclick="self.close()"> -->

