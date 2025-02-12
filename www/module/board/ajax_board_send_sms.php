<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$phones = explode(',', $_REQUEST["phones"]);
$messages = $_REQUEST["messages"];
$results = [];

foreach ($phones as $phone) {
    $smsRS = munja_send("sms", "Recipient Name", $phone, $messages, "010-2740-4458", "", "", "", "", "");
    $results[] = $smsRS;
}

if($smsRS==true){
    echo "true";
}else{
    echo "false".$_REQUEST["phones"]."//".$smsRS;
}

//DB해제
SetDisConn($dblink);
?>