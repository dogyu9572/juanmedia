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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="imagetoolbar" content="no" />
<title><?=$_SITE["NAME"]?> 관리자</title>
<link rel="stylesheet" type="text/css" href="/backoffice/css/style.css" />
<link rel="stylesheet" type="text/css" href="/common/js/datePicker/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="/common/js/fancybox/source/jquery.fancybox.css" media="screen" />
<link rel="stylesheet" type="text/css" href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" />
<script type="text/javascript" src="/backoffice/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="/common/js/datePicker/jquery-ui.min.js"></script>
<!--
<script type="text/javascript" src="/common/js/fancybox/source/jquery.fancybox.js"></script>
<script type="text/javascript" src="/backoffice/js/myjs.js"></script>
<script type="text/javascript" src="/common/js/prototype-1.6.0.3-euc-kr.js"></script>
<script type="text/javascript" src="/common/js/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="/common/js/scriptaculous/effects.js"></script>
-->
<script type="text/javascript" src="/common/js/layer.js"></script>
<script type="text/javascript" src="/common/js/shop.js"></script>

<script>
$(function() {
// $.datepicker.setDefaults($.datepicker.regional["ko"]);
    $(".datePicker").datepicker({ 
     dateFormat: 'yy-mm-dd',
	 minDate: '-90y',
	 maxDate: '1y',
     yearRange: 'c-80:c+80',
     monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
     dayNamesMin: ['일','월','화','수','목','금','토'],
	 weekHeader: 'Wk',
     changeMonth: true, //월변경가능
     changeYear: true, //년변경가능
     showMonthAfterYear: true //년 뒤에 월 표시
  });
 });
</script>

<? if(isset($maingb) != "main") {?>
<script src="/common/js/common.js" type="text/javascript"></script>
<?}?>
<style type="text/css">
	/* https://ionicons.com/ */
	.newtop ul li {float:left;width:95px;text-align:center;padding-top:9px;}
	.newtop ul li a{text-decoration:none;color:#e4e4e4;}
	.newtop ul li a:hover,  
	.newtop ul li a:active,  
	.newtop ul li a:focus{color:#999999;}
	.newtop ul li span{font-size:12px;font-weight:bold;}
	.newtop ul li i{font-size:32px;}
	
	.newhomevi li {float:right;padding-left:20px;padding-top:5px;text-align:right;vertical-align:middle;}
	.newhomevi li a{text-decoration:none;color:#e4e4e4;}
	.newhomevi li a i{font-size:13px;}
</style>
</head>

<body>
<div id="admin-wrapper">
	<div id="admin-header">
    	<div class="admin-top-content">
            <h1 class="top-logo"><a href="/backoffice"><img src="/backoffice/images/logo_admin.gif" alt="관리자모드" /></a></h1>
            <div class="top-util">
                <div class="visitor-name"><strong><?=$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]?>(<?=$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["NAME"]?><?=$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["CLASS"]?>)님 로그인</strong></div>
				<!--ul class="util-menu">
					<li><a href="/backoffice/index.php" title="관리자메인"><img src="/backoffice/images/top_m1.gif" alt="관리자메인" /></a></li>
					<li><a href="http://<?=$_SITE["DOMAIN"]?>" target="_blank" title="내 홈페이지-새창열림"><i class="icon ion-md-home"></i><img src="/backoffice/images/top_m2.gif" alt="내 홈페이지" /></a></li>
					<li><a href="/backoffice/auth/logout.php" title="로그아웃"><img src="/backoffice/images/top_m3.gif" alt="로그아웃" /></a></li>
				</ul-->
				<ul class="newhomevi">
					
					
					<li><a href="/backoffice/auth/logout.php" title="로그아웃"><i class="icon ion-md-unlock"></i> 로그아웃</a></li>					
					<li><a href="http://<?=$_SITE["DOMAIN"]?>" target="_blank" title="내 홈페이지-새창열림"><i class="icon ion-md-tv"></i> 내 홈페이지</a></li>
					<li><a href="/backoffice/index.php" title="관리자메인"><i class="icon ion-md-home"></i> HOME</a></li>
				</ul>
			</div>
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header_gnb.php";?>		
    </div>
    	