<?
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/PHPMailer.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/Exception.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/_PHPMailer/src/SMTP.php";

$mail = new PHPMailer(true);

$mail->IsSMTP();
try {
	$mail->Host = "smtp.gmail.com";								// email 보낼때 사용할 서버를 지정
	$mail->SMTPAuth = true;										// SMTP 인증을 사용함
	$mail->Port = 465;											// email 보낼때 사용할 포트를 지정
	$mail->SMTPSecure = "ssl";									// SSL을 사용함
	$mail->Username   = "jeejin@homepagekorea.com";				// Gmail 계정
	$mail->Password   = "[slswk0212]";							// 패스워드
	
	$mail->SetFrom('jeejin1122@naver.com', 'ECOTHON');			// 보내는 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
	$mail->AddAddress('jeejin@homepagekorea.com', 'jeejin');	// 받을 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)

	$mail->Subject = 'Email Subject';							// 메일 제목
	$mail->MsgHTML("Email Content");							// 메일 내용 (HTML 형식도 되고 그냥 일반 텍스트도 사용 가능함)
	
	$mail->Send();												// 실제로 메일을 보냄

	echo "Message Sent OK<p></p>\n";
} catch (phpmailerException $e) {
	echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
	echo $e->getMessage(); //Boring error messages from anything else!
}

?>