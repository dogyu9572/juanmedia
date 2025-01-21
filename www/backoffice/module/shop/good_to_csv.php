<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";


if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("������ �����ϴ�.");
	jsHistory("-1");
endif;

//DB����
$dblink = SetConn($_conf_db["main_db"]);

$scale=0;

if($_REQUEST['st']=="1"){
	$order_by = " A.sort_num DESC, A.idx DESC ";
}else{
	$order_by = " A.idx DESC ";
}

//��ǰ ����Ʈ
$arrList = getGoodListBaseNFile(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"");


//��ü ī�װ� ��������
$arrAllCategory = getCategoryAll();

//��ǰ�з� ����Ʈ
$arrCategory = getCategoryList(0);//1��ī�װ�

$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

//ī�װ� ����
$arrCatCode = explode("/", $arrCategoryInfo["list"][0]['cat_code']);


//Header("Content-type: file/unknown");
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename=".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_��ǰ���_".date(m)."��".date(d)."��".date(h)."��".date(i)."��.csv" );
header( "Content-Description: PHP4 Generated Data" );
header("Pragma: no-cache");
header("Expires: 0");

	echo "��ȣ,";
	echo "��ǰ�ڵ�,";
	echo "1���з�,";
	echo "2���з�,";
	echo "3���з�,";
	echo "4���з�,";
	echo "BABY CARE,";
	echo "LIVING,";
	echo "FASHION,";
	echo "VEHICLE,";
	echo "TOY,";
	echo "��ǰ��,";
	echo "����,";
	echo "ũ��,";
	echo "���/��ġ���,";
	echo "�ֿ����,";
	echo "ǰ����������,";
	echo "A/Så���ڿ� ��ȭ��ȣ,";
	echo "KC���� �� ����,";
	echo "����ǰ,";
	echo "ǰ��,";
	echo "��۱Ⱓ,";
	echo "��ȯ/��ǰ����,";
	echo "������,";
	echo "������,";
	echo "������,";
	echo "����,";
	echo "�Һ��ڰ�,";
	echo "�ǸŰ�,";
	echo "������,";
	echo "��ǰ��������,";
	echo "�󼼼���,";
	echo "�������\n";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {

		$arrThisCatCode = explode("/", $arrList["list"][$i]['cat_code']);

		$arrExtCat = getGoodExtCat($arrList["list"][$i]['idx']);
		for($j=0;$j<$arrExtCat["total"];$j++){
			$arrExtCatCode[$i] = explode("/", $arrExtCat["list"][$j]["cat_code"]);
			if($arrExtCatCode[$i]['0']=="2") {
				$cate1[$i] = $arrAllCategory[$arrExtCatCode[$i]['1']];
			} else if($arrExtCatCode[$i]['0']=="3") {
				$cate2[$i] = $arrAllCategory[$arrExtCatCode[$i]['1']];
			} else if($arrExtCatCode[$i]['0']=="4") {
				$cate3[$i] = $arrAllCategory[$arrExtCatCode[$i]['1']];
			} else if($arrExtCatCode[$i]['0']=="5") {
				$cate4[$i] = $arrAllCategory[$arrExtCatCode[$i]['1']];
			} else if($arrExtCatCode[$i]['0']=="6") {
				$cate5[$i] = $arrAllCategory[$arrExtCatCode[$i]['1']];
			}
		}
		/*
		for($j=0; $j <count($arrThisCatCode)-1;$j++){
		 $cate[$i] .= $arrAllCategory[$arrThisCatCode[$j]];
		 if($j < count($arrThisCatCode)-2) {
			 $cate[$i] .= " > ";
		 } else {
		 }
		}
		*/

	echo $i+1 . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['g_code']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrAllCategory[$arrThisCatCode[0]]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrAllCategory[$arrThisCatCode[1]]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrAllCategory[$arrThisCatCode[2]]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrAllCategory[$arrThisCatCode[3]]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$cate1[$i]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$cate2[$i]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$cate3[$i]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$cate4[$i]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$cate5[$i]))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['g_name']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['pan_color']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['pages']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['mokcha']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['author_text']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['movie_url']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['author_name']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['published_text']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['brand']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['model']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['isbn']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['memo']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['madein']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['vendor']))))  . ",";	
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['movie']))))  . ",";	
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['cdrom']))))  . ",";	
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['sale_price']))))  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['price']))))  . ",";
	echo $arrList['list'][$i]['point_unit']=="P"?number_format(($arrList['list'][$i]['point']*$arrList['list'][$i]['price'])/100):number_format($arrList['list'][$i]['point'])  . ",";
	echo strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['is_show']))))  . ",";
	echo "\"".str_replace(",",".", str_replace("\n","",str_replace("\r","", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['contents']))))). "\",";
	echo strip_tags(str_replace(",",".", stripslashes(substr($arrList["list"][$i]['wdate'],0,10))))  . "\n";
		
}

//DB����
SetDisConn($dblink);
?>