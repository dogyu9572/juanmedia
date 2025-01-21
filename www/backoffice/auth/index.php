<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/module/admin/admin.lib.php";

if(isset($_POST['evnMode'])=='Login'){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);
	$arrInfo = getAdminInfo($_POST["ID"]);

	if($arrInfo["total"] < 1){
		//로그인정보 기록
		setAdminLoginLog(mysqli_real_escape_string($GLOBALS['dblink'], $_POST["ID"]),"N");

		jsMsg("해당하는 아이디가 없습니다.");
		jsHistory("-1");
	}
	//$arrInfoPW = getAdminPass($_POST['Password']);

	//echo $arrInfoPW['list'][0]['pw'];

	//if($arrInfo["list"][0]["a_pw"] == $arrInfoPW['list'][0]['pw']) {
	if($arrInfo["list"][0]["a_pw"] == $_POST['Password']) {
		//로그인정보 기록
		setAdminLoginLog($arrInfo["list"][0]["a_id"],"Y");

		// 로그인 정보로 세션을 생성
		$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] = $arrInfo["list"][0]["a_id"];
		$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["NAME"] = $arrInfo["list"][0]["a_name"];
		$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["CLASS"] = $arrInfo["list"][0]["a_class"];
		$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"] = $arrInfo["list"][0]["a_grade"];
		$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"] = explode(",",$arrInfo["list"][0]["a_auth"]);

		metaGo("/backoffice/");

	}else{
		//로그인정보 기록
		//setAdminLoginLog(mysqli_real_escape_string($_POST["ID"]),"N");

		jsMsg("비밀번호가 일치하지 않습니다.");
		jsHistory("-1");
	}

	//DB해제
	SetDisConn($dblink);
}
######################################################## 디자인 ST
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/dtd.php";
?>
<script language='JavaScript'>
<!--
function CheckForm(Frm){
	if (Frm.ID.value==''){
		alert('ID를 입력하여 주십시요.');
		Frm.ID.focus();
		return false;
	}
	if (Frm.Password.value==''){
		alert('비밀번호를 입력하여 주십시요.');
		Frm.Password.focus();
		return false;
	}
	Frm.submit();
}
//-->
</script>
<div class="login_wrap">
	<form action="<?=$_SERVER["PHP_SELF"]?>" method="post" name="login">
		<input type="hidden" name="evnMode" value="Login">
		<input type='hidden' name='Prev_URL' value='<?=$Prev_URL?>'>
		<div class="login_inbox">
			<div class="title noto">ADMIN LOGIN<div class="kor">관리자로그인</div></div>
			<div class="inputs">
				<input type="text" class="text" placeholder="아이디" id="id" name="ID" maxlength="15">
				<input type="password" class="text" placeholder="패스워드" id="pw" name="Password" maxlength="15">
				<button type="button" class="btn" onclick="CheckForm(document.login)">로그인</button>
			</div>
			<div class="btm_txt">※ 관리자 페이지로 접속합니다.<br/>※ 공공장소에서의 로그인시 정보 유출에 주의하시기 바랍니다.</div>
		</div>
	</form>
</div>
<script language="javascript">
document.login.id.focus();
</script>
</body>
</html>