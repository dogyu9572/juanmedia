<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php"; 
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";


//표시할 페이지
if($_REQUEST[goPage]){
	switch($_REQUEST[goPage]){
		case("Find"):	
			$incPage = "member/find.php";
			break;
		case("Login"):	
			$incPage = "member/login.php";
			break;
		case("Agree"):	
			$incPage = "member/member_agree.php";
			break;
		case("Cert"):	
			$incPage = "member/member_cert.php";
			break;
		case("CertOk"):	
			$incPage = "member/member_cert_ok.php";
			break;
		case("Join"):	
			$incPage = "member/member_join.php";
			break;
		case("Thanks"):	
			$incPage = "member/member_thanks.php";
			break;
		case("Edit"):	
			$incPage = "member/member_modify.php";
			break;
		case("EditPw"):	
			$incPage = "member/member_modify_pw.php";
			break;
		case("Pass"):	
			$incPage = "member/member_passwd.php";
			break;
		case("Leave"):	
			$incPage = "member/member_leave.php";
			break;
		default: 
			$incPage = "";
			break;
	}
?>
<?
	if($incPage !="" && file_exists($_SERVER['DOCUMENT_ROOT'] . "/module/".$incPage)){
		include($_SERVER['DOCUMENT_ROOT'] . "/module/".$incPage);
	}
}
?>
