<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/PHPMailer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/Exception.php";

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);
$emails = explode(',', $_REQUEST["emails"]);
$results = [];

$arrBoardMailArticle = getBoardArticleView("mailsms", "email", $_REQUEST["idx"], "read", "and etc_3='" . $_REQUEST["etc_3"] . "'");

$title = $arrBoardMailArticle["list"][0]['etc_4'];
$subject = $arrBoardMailArticle["list"][0]['subject'];
$contents = $arrBoardMailArticle["list"][0]['contents'];

// Read the HTML template
$htmlTemplate = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/_mailform/mailform_member.html");

// Replace placeholders with actual content
$htmlTemplate = str_replace("{{subject}}", $subject, $htmlTemplate);
$htmlTemplate = str_replace("{{contents}}", $contents, $htmlTemplate);

foreach ($emails as $email) {
    $mail = new PHPMailer(true);

    try {
        $mail->IsMail();  // Postfix의 sendmail을 사용
        $mail->CharSet = "UTF-8";  // 한글 깨짐 방지

        $mail->SetFrom('cdg9572@gmail.com', '주안영상미디어센터');  // 보내는 사람 메일 주소
        $mail->AddAddress($email);  // 받는 사람 메일 주소

        $mail->Subject = $title;  // 메일 제목
        $mail->MsgHTML($htmlTemplate);  // 메일 내용

        $mail->Send();
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); // PHPMailer 에러 메시지
    } catch (Exception $e) {
        echo $e->getMessage(); // 일반 예외 메시지
    }

    $results[] = $mail;
}

if ($mail == true) {
    echo "true";
} else {
    echo "false" . $_REQUEST["emails"] . "//" . $emails;
}

// DB 해제
SetDisConn($dblink);
?>