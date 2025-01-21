<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$updateQuery = "
	UPDATE tbl_shop_order_info AS a, (SELECT name,zip,address,address_ext,phone FROM tbl_member_address WHERE idx='".$_POST["shipidx"]."') AS b
	SET a.ship_name=b.name, a.ship_zip=b.zip, a.ship_address=b.address, a.ship_address_ext=b.address_ext, a.ship_mobile=b.phone
	WHERE a.order_no='".$_POST["order_no"]."'
";
getFreeQueryCud($updateQuery);

echo "Y";
//DB해제
SetDisConn($dblink);
