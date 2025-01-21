<?
session_start();
//header("Content-Type: text/html; charset=euc-kr");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";

$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["order_name"]			= $_POST['order_name'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["order_mobile"]		= $_POST['order_mobile'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["order_email"]			= $_POST['order_email'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["order_zip"]			= $_POST['order_zip'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["order_address"]		= $_POST['order_address'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["order_address_ext"]	= $_POST['order_address_ext'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["order_phone"]			= $_POST['order_phone'];

$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["ship_name"]			= $_POST['ship_name'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["ship_mobile"]			= $_POST['ship_mobile01']."-".$_POST['ship_mobile02']."-".$_POST['ship_mobile03'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["ship_email"]			= $_POST['ship_email'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["ship_zip"]			= $_POST['ship_zip'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["ship_address"]		= $_POST['ship_address'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["ship_address_ext"]	= $_POST['ship_address_ext'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["ship_phone"]			= $_POST['ship_phone'];

$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["delivery"]			= $_POST['delivery'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["ship_title"]			= $_POST['ship_title'];
$_SESSION[$_SITE["DOMAIN"]]["ORDER"]["order_comment"]		= $_POST['order_comment'];
?>true