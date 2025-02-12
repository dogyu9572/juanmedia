<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/PHPMailer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/Exception.php";

$mail = new PHPMailer(true);

try {
    $mail->IsMail();  // Postfix의 sendmail을 사용
    $mail->CharSet = "UTF-8";  // 한글 깨짐 방지

    $mail->SetFrom('mail@juanmedia.or.kr', '주안영상미디어센터');  // 보내는 사람 메일 주소
    $mail->AddAddress('dogyupower@naver.com');  // 받는 사람 메일 주소

    $mail->Subject = 'Postfix 메일 테스트';  // 메일 제목
    $mail->MsgHTML("Postfix를 이용한 메일 발송 테스트입니다.");  // 메일 내용

    $mail->Send();
    echo "Message Sent OK<p></p>\n";
} catch (phpmailerException $e) {
    echo $e->errorMessage(); // PHPMailer 에러 메시지
} catch (Exception $e) {
    echo $e->getMessage(); // 일반 예외 메시지
}
?>
