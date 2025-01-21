<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";


if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("������ �����ϴ�.");
	jsHistory("-1");
endif;

//DB����
$dblink = SetConn($_conf_db["main_db"]);

$scale=0;


// �Ⱓ ������ ���� ��� ���� ��¥ �����͸� �����´�
if (!$_REQUEST['s_date'] && !$_REQUEST['e_date']){
	$_REQUEST['s_date']=date("Y-m-d");
	$_REQUEST['e_date']=date("Y-m-d");;
}



$arrList = getOrderListCSV(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_status']), 
	$scale, $_REQUEST['offset']);	


//��ü ī�װ� ��������
$arrAllCategory = getCategoryAll();


//Header("Content-type: file/unknown");
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename=".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_�ֹ����_".date(m)."��".date(d)."��".date(h)."��".date(i)."��.csv" );
header( "Content-Description: PHP4 Generated Data" );
header("Pragma: no-cache");
header("Expires: 0");

	echo "��ȣ,";
	echo "�ֹ���ȣ,";
	echo "�ֹ��ڸ�,";
	echo "�ֹ���ID,";
	echo "������,";
	echo "�귣��,";
	echo "��ǰ������ȣ,";
	echo "��ǰ�ڵ�,";
	echo "��ǰ��,";
	echo "�ɼ�,";
	echo "����,";
	echo "�����ο����ȣ,";
	echo "�������ּ�,";
	echo "��������ȭ��ȣ,";
	echo "�޴���,";	
	echo "����,";
	echo "�޸�,";
	echo "�����ݾ�\n";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	
	if ($i==0){
		$before_date=$arrList["list"][$i]['order_no'];
	}
	$temp_addr=stripslashes($arrList["list"][$i]['join_address'])." ".stripslashes($arrList["list"][$i]['join_address_ext']);

	$arrInfo = getOrderInfoAdmin(mysqli_real_escape_string($GLOBALS['dblink'], $arrList["list"][$i]['order_no']));

	if($arrInfo["good_total"]>0){
	for($j=0;$j<$arrInfo["good_total"];$j++){
		$option[$i] .= $arrInfo["good_list"][$j][g_opt_1]."//";
	}
	}


	if ($before_date!=$arrList["list"][$i]['order_no']){
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo ",";		
		echo ",";		
		echo ",";		
		echo ",";
		echo ",";
		echo ",";
		echo ",";
		echo "\n";	
		$before_date=$arrList["list"][$i]['order_no'];
		$j=0;		
	}	

	echo $i+1 . ",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['order_no'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['join_name'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['join_id'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", iconv("UTF-8","EUC-KR",stripslashes($arrList["list"][$i]['ship_name'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['cat_name'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['g_idx'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['g_code'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['g_name'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",substr($option[$i],0,-2))))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['g_qty'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", iconv("UTF-8","EUC-KR",$arrList["list"][$i]['join_zip']))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", iconv("UTF-8","EUC-KR",$temp_addr))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['ship_phone'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['order_phone'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['pay_type'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", stripslashes(iconv("UTF-8","EUC-KR",$arrList["list"][$i]['order_comment'])))) . "\",";
	echo "\"".strip_tags(str_replace(",",".", $arrList["list"][$i]['pay_amount']))  . "\"\n";
		
}

//DB����
SetDisConn($dblink);
?>