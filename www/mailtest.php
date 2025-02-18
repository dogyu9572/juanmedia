<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/PHPMailer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/Exception.php";

$mail = new PHPMailer(true);

try {
	// Postfix를 통한 메일 발송 (SMTP 사용 안 함)
	$mail->isMail();

	// 이메일 기본 설정
	$mail->CharSet = "UTF-8";
	$mail->setFrom('admin@juanmedia.or.kr', '주안영상미디어센터');
	$mail->addAddress('cdg9572@gmail.com');

	$mail->Subject = 'Postfix 메일 테스트';
	$mail->msgHTML("Postfix를 이용한 메일 발송 테스트입니다.222");

	$mail->send();
	echo "Message Sent OK<p></p>\n";
} catch (Exception $e) {
	echo "메일 전송 실패: " . $e->getMessage();
}
?>
