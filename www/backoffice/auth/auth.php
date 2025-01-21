<?
if (!isset($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"])) {
	echo "
		<meta http-equiv=refresh content='0; URL=/backoffice/auth/admin_login.php?Prev_URL=".urlencode($_SERVER['REQUEST_URI'])."'>
		";
	
	exit;
}else{
	if(!$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
	
	echo "
		<meta http-equiv=refresh content='0; URL=/backoffice/auth/admin_login.php?Prev_URL=".urlencode($_SERVER['REQUEST_URI'])."'>
		";
	
	exit;
	}
}
?>