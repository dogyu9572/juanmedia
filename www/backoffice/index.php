<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT"){
	header("location: /backoffice/module/admin/admin_set.php");
	exit();
}else{
	if(in_array("admin_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"])){			// 
		header("location: /backoffice/module/admin/admin_set.php");
		exit();
	}
	if(in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"])){			// 
		header("location: /backoffice/module/member/member.php");
		exit();
	}
	if(in_array("banner_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"])){			// 
		header("location: /backoffice/module/banner/banner.php");
		exit();
	}
	if(in_array("popup_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"])){			// 
		header("location: /backoffice/module/popup/popup_list.php");
		exit();
	}
	if(in_array("log_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"])){			// 
		header("location: /backoffice/module/log/log_hourly_view.php");
		exit();
	}
}
?>