<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$order_state	= $_REQUEST['rval'];
$gidx			= $_REQUEST['ridx'];

if($order_state=="8"){		//	배송중으로 변경시
	$Query = "SELECT * from tbl_shop_order_info WHERE idx in (".$gidx.") ";
	$arrOrderList = getFreeQueryR($Query);
	
	for($i=0;$i<$arrOrderList['list']['total'];$i++){
		/////////	$arrOrderList["list"][$i]["mail_sms"]	= "";	## 테스트
		if($arrOrderList["list"][$i]["mail_sms"]=="send"){
			## 알림톡 발송됨
			//	echo "발송됨";
		}else{
			$sql = "update tbl_shop_order_info set mail_sms='send',send_date=now()  where order_no='".$arrOrderList["list"][$i]["order_no"]."' ";
			getFreeQueryCud($sql);	## 완료후 주석 삭제

			//$note1 = weekday($arrOrderList["list"][$i]['shipping_date'])."요일";
			$note1 = $arrOrderList["list"][$i]['shipping_date'].weekday($arrOrderList["list"][$i]['shipping_date'])."요일";
			$note2 = $arrOrderList["list"][$i]['order_summary'];
			//echo  $arrOrderList["list"][$i]['order_name']. $arrOrderList["list"][$i]['order_mobile']. $arrOrderList["list"][$i]['order_id']. $note1. $note2;

			kakaoApiTalk("G08", $arrOrderList["list"][$i]['order_cname'], $arrOrderList["list"][$i]['order_mobile'], $arrOrderList["list"][$i]['order_id'], $note1, $note2, "", "", "");
		}
	}	
}

$updateQuery = "update tbl_shop_order_info set order_state='".$order_state."' where idx in (".$gidx.") ";
//echo $updateQuery;
getFreeQueryCud($updateQuery);

SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>