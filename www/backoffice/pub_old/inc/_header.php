<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/module/admin/admin.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/whereis.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrMenuList = getAdminMenu();
for($i=0;$i<$arrMenuList["total"];$i++){
	$arrayMyMenu[] = $arrMenuList["list"][$i]['m_code'];
	$arrayMenuList[$arrMenuList["list"][$i]['m_code']] = $arrMenuList["list"][$i]['m_name'];
}

//DB해제
SetDisConn($dblink);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="imagetoolbar" content="no" />
<title><?=$_SITE["NAME"]?> 관리자</title>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/backoffice/pub/css/style.css" />
<link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
<style type="text/css">
	/* https://ionicons.com/ */
	.newtop ul li {float:left;width:95px;text-align:center;padding-top:9px;}
	.newtop ul li a{text-decoration:none;color:#e4e4e4;}
	.newtop ul li a:hover,  
	.newtop ul li a:active,  
	.newtop ul li a:focus{color:#999999;}
	.newtop ul li span{font-size:12px;font-weight:bold;}
	.newtop ul li i{font-size:32px;}
</style>

<script  src="https://code.jquery.com/jquery-latest.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<body>
<div id="admin-wrapper">
	<div id="admin-header">
    	<div class="admin-top-content">
            <h1 class="top-logo"><a href="/backoffice"><img src="/backoffice/images/logo_admin.gif" alt="관리자모드" /></a></h1>
            <div class="top-util">
                <div class="visitor-name"><strong><?=$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]?>(<?=$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["NAME"]?><?=$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["CLASS"]?>)님 로그인</strong></div>
				<ul class="util-menu">
					<li><a href="/backoffice/auth/logout.php" title="로그아웃"><i class="icon ion-md-unlock"></i> 로그아웃</a></li>					
					<li><a href="http://<?=$_SITE["DOMAIN"]?>" target="_blank" title="내 홈페이지-새창열림"><i class="icon ion-md-tv"></i> 내 홈페이지</a></li>
					<li><a href="/backoffice/index.php" title="관리자메인"><i class="icon ion-md-home"></i> HOME</a></li>
				</ul>
			</div>
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header_gnb.php";?>
    </div>
	<div id="admin-container">