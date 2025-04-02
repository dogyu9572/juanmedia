<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);
$emails = explode(',', $_REQUEST["emails"]);
$emailCount = count($emails);
$results = [];

$arrBoardMailArticle = getBoardArticleView("mailsms", "email", $_REQUEST["idx"], "read", "and etc_3='" . $_REQUEST["etc_3"] . "'");

$title = $arrBoardMailArticle["list"][0]['etc_4'];
$subject = $arrBoardMailArticle["list"][0]['subject'];
$contents = $arrBoardMailArticle["list"][0]['contents'];

// 이미지 경로를 상대 경로에서 절대 URL로 변환
$domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
$pattern = '/<img[^>]*src=["\']([^"\']+)["\'][^>]*>/i';

$contents = preg_replace_callback($pattern, function($matches) use ($domain) {
    $src = $matches[1];
    // 이미 절대 URL인 경우 그대로 반환
    if (strpos($src, 'http://') === 0 || strpos($src, 'https://') === 0) {
        return $matches[0];
    }

    // 상대 경로를 절대 URL로 변환
    $absoluteSrc = rtrim($domain, '/') . '/' . ltrim($src, '/');
    return str_replace($src, $absoluteSrc, $matches[0]);
}, $contents);

// Read the HTML template
$htmlTemplate = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/_mailform/mailform_member.html");
$htmlTemplate = str_replace("{{subject}}", $subject, $htmlTemplate);
$htmlTemplate = str_replace("{{contents}}", $contents, $htmlTemplate);

if ($emailCount >= 10) {
    // 대량메일 발송 설정
    $sendmail_url = "https://camf2622.sendmail.cafe24.com/sendmail_api.php";
    $secureKey = "453496af7637e8db2eb490a4c16554e9";
    $userId = "camf2622";

    // 수신자 리스트 생성 (email,email 형식)
    $receiverlist = '';
    foreach ($emails as $email) {
        $receiverlist .= trim($email) . "," . trim($email) . "\n";
    }

//    $receiverlist = "cdg9572@gmail.com,cdg9572@gmail.com\n";
//    $receiverlist .= "dogyupower@naver.com,dogyupower@naver.com\n";
//    $receiverlist .= "dogyupower@hanmail.net,dogyupower@hanmail.net\n";
//    $receiverlist .= "songsongssong@hotmail.com,songsongssong@hotmail.com\n";
//    $receiverlist .= "cdg9572@gmail.com,cdg9572@gmail.com\n";
//    $receiverlist .= "dogyupower@naver.com,dogyupower@naver.com\n";
//    $receiverlist .= "dogyupower@hanmail.net,dogyupower@hanmail.net\n";
//    $receiverlist .= "songsongssong@hotmail.com,songsongssong@hotmail.com\n";
//    $receiverlist .= "dogyupower@hanmail.net,dogyupower@hanmail.net\n";
//    $receiverlist .= "songsongssong@hotmail.com,songsongssong@hotmail.com\n";
//    $receiverlist .= "songsongssong@hotmail.com,songsongssong@hotmail.com\n";

    // 대량메일 요청 데이터 설정
    $mail = array(
        'secureKey' => $secureKey,
        'userId' => $userId,
        'sender' => base64_encode('주안영상미디어센터'),
        'email' => base64_encode('admin@juanmedia.or.kr'),
        'receiverlist' => base64_encode($receiverlist),
        'subject' => base64_encode($title),
        'content' => base64_encode($htmlTemplate),
        'rejectType' => 2,
        'overlapType' => 2,
        'sendType' => 0,
        'useRejectMemo' => 0,
        'testFlag' => 0
    );

    // API 요청 처리
    $host_info = explode("/", $sendmail_url);
    $host = $host_info[2];
    $path = $host_info[3]."/".$host_info[4];

    $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
    $header = "POST /".$path ." HTTP/1.0\r\n";
    $header .= "Host: ".$host."\r\n";
    $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

    $data = '';
    foreach($mail as $index => $value){
        $data .="--$boundary\r\n";
        $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
        $data .= "\r\n".$value."\r\n";
        $data .="--$boundary\r\n";
    }

    $header .= "Content-length: " . strlen($data) . "\r\n\r\n";

    $fp = fsockopen($host, 80);
    if ($fp) {
        fputs($fp, $header.$data);
        $rsp = '';
        while(!feof($fp)) {
            $rsp .= fgets($fp,8192);
        }
        fclose($fp);
        $msg = explode("\r\n\r\n",trim($rsp));
        $success = (strpos($msg[1], 'SUCCESS') !== false);

        echo $success ? "true" : "false";
    } else {
        echo "false";
    }
} else {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/PHPMailer.php";
    require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/Exception.php";

    foreach ($emails as $email) {
        $mail = new PHPMailer(true);

        try {
            $mail->IsMail();
            $mail->CharSet = "UTF-8";
            $mail->setFrom('admin@juanmedia.or.kr', '주안영상미디어센터');
            $mail->AddAddress($email);
            $mail->Subject = $title;
            $mail->MsgHTML($htmlTemplate);
            $mail->Send();
            $results[] = true;
        } catch (Exception $e) {
            $results[] = false;
        }
    }

    echo (in_array(false, $results) === false) ? "true" : "false";
}

SetDisConn($dblink);
?>
