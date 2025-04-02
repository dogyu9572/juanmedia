<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);
$phones = explode(',', $_REQUEST["phones"]);
$results = [];

$arrBoardSmsArticle = getBoardArticleView("mailsms", "sms", $_REQUEST["idx"], "read", "and etc_3='" . $_REQUEST["etc_3"] . "'");

$subject = $arrBoardSmsArticle["list"][0]['subject'];
$title = $arrBoardSmsArticle["list"][0]['etc_4'];
$contents = $arrBoardSmsArticle["list"][0]['contents'];

//$messages = "[" . $title . "]\n" . $contents;

$clean_title = str_replace('&nbsp;', ' ', $title);
$clean_contents = str_replace('&nbsp;', ' ', $contents);

$messages = "[" . $clean_title . "]\n" . $clean_contents;

$mtype = strlen($messages) > 90?"lms":"sms";

foreach ($phones as $phone) {
    $smsRS = munja_send($mtype, "Recipient Name", $phone, $messages, "032-872-2622", "", "", "", "", "");
    $results[] = $smsRS;
}

if($smsRS==true){
    echo "true";
}else{
    echo "false".$_REQUEST["phones"]."//".$arrBoardSmsArticle;
}

//DB해제
SetDisConn($dblink);
?>