<form name="frm" method="post" action="mailtest_action.php" enctype="multipart/form-data">
	<table>
		<tr>
			<td>발신자</td>
			<td><input type="text" name="sender" /></td>
		</tr>
		<tr>
			<td>발신자 메일</td>
			<td><input type="text" name='email' /></td>
		</tr>
		<tr>
			<td>수신자</td>
			<td><textarea name="receiverlist" rows="5" cols="40"></textarea></td>
		</tr>
		<tr>
			<td>수신자 URL</td>
			<td><input type="text" name='receiverlistUrl' /></td>
		</tr>
		<tr>
			<td>수신거부자 발송</td>
			<td><input type="radio" name='rejectType ' value="2" checked /> 포함
				<input type="radio" name='rejectType ' value="3"/> 포함</td>
		</tr>
		<tr>
			<td>제목</td>
			<td><input type="text" name='subject' /></td>
		</tr>
		<tr>
			<td>내용</td>
			<td><textarea name="content" rows="15" cols="60"></textarea></td>
		</tr>
		<tr>
			<td>파일첨부</td>
			<td><input type="file" name='addfile' />(2MB 미만)</td>
		</tr>
		<tr>
			<td>예약발송</td>
			<td><input type="checkbox" name='sendType' value="1"/> 사용
				<input type="text" name="sendDate" />(년-월-일 시:분:초)
			</td>
		</tr>
		<tr>
			<td>수신거부기능</td>
			<td><input type="checkbox" name='useRejectMemo' value="1"/> 사용 <a href="http://help.cafe24.com/notice/notice_view.php?idx=1914" target=_blank><b><font color="red"> ※주의하세요</font></b> </a></td>
		</tr>
		<tr>
			<td>메일 중복발송</td>
			<td><input type="radio" name='overlapType' value="2" checked/> 중복발송허용 <input type="radio" name='overlapType' value="1" /> 중복제외 </td>
		</tr>
		<tr>
			<td>요청테스트</td>
			<td><input type="checkbox" name='testFlag' value="1"/> 사용</td>
		</tr>
	</table>
	<input type="submit" value="발송요청" />
</form>

<?php
/*use PHPMailer\PHPMailer\PHPMailer;
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
*/?>
