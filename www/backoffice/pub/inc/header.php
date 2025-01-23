<?PHP
$thisPHPname = basename($_SERVER['PHP_SELF']);

switch($thisPHPname){
	case 'admin_set.php' :	case 'admin.php' : case 'admin_info.php' : case 'admin_set_point.php' : case 'admin_set_search.php' :
		$topMenuClass[1] = "on"; break;	
	case 'member.php' : case 'member_info.php' : case 'member_add.php' : case 'member_outlist.php' : case 'member_standby.php' : case 'member_standby_info.php' : 
		$topMenuClass[2] = "on"; break;
	case 'log_hourly_view.php' : case 'log_daily_view.php' : case 'log_monthly_view.php' : case 'log_os_view.php' : case 'log_browser_view.php' : case 'log_ip_view.php' : case 'log_domain_view.php' : 
	case 'log_referer_view.php' : case 'log_page_view.php' :
		$topMenuClass[9] = "on"; break;
	case 'banner.php' : case 'banner_add.php' : case 'banner_info.php' : 
		$topMenuClass[1] = "on"; break;
	case 'popup_list.php' : case 'popup_info.php' : case 'popup_add.php' : 
		$topMenuClass[1] = "on"; break;
	case 'category.php' : case 'category_info.php' : 
		$topMenuClass[1] = "on"; break;

	case 'good.php' : case 'good_info.php' : case 'good_outlist.php' :
		$topMenuClass[6] = "on"; break;	
	case 'order.php' : case 'order_detail.php' : case 'order_list2.php' : 
		$topMenuClass[7] = "on"; break;
}

if($thisPHPname=="category.php" && $_REQUEST['cat_no']=="2"){
	$topMenuClass[4] = "on";
	$topMenuClass[1] = ""; 
}
unset($thisPHPname);

if($_REQUEST['boardid']){
	switch ($_REQUEST['boardid']){
		case 'edu_applicants' :	case 'edu' :	case 'edu_certificates' :
            $topMenuClass[3] = "on"; break;
        case 'equ_applicants' :	case 'equ' :
             $topMenuClass[4] = "on"; break;
        case 'place_applicants' :	case 'place' :
             $topMenuClass[5] = "on"; break;
		case 'mailsms' :	case 'terms' :	case "often": case 'edu_instructors' :
			$topMenuClass[1] = "on"; break;		
		case 'evaluation' :	case 'accept' :	case 'sub_evaluation':
			$topMenuClass[4] = "on"; break;


	}
}

?>
<div class="header">
	<a href="/backoffice/" class="logo">관리자모드</a>
	<a href="javascript:void(0);" class="btn_menu">
		<p class="t"></p>
		<p class="m"></p>
		<p class="b"></p>
	</a>
	<div class="gnb">
		<div class="black"></div>
		<ul>
		<?
		for($i=0;$i<$arrMenuList["total"];$i++){
			if($arrMenuList["list"][$i]['m_code']!="board_manage"){
				if( in_array($arrMenuList["list"][$i]['m_code'],$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT" ){
					if($arrMenuList["list"][$i]['m_code']=="log_manage"){
						$menuNum = "09";
					}else{
						$menuNum = sprintf('%02d', $i);
					}
					echo '<li class="'.$topMenuClass[$i].'"><a href="'.$arrMenuList["list"][$i]['purl'].'"><img src="/backoffice/pub/images/icon_gnb'.$menuNum.'.svg" alt="icon"><p>'.$arrMenuList["list"][$i]['m_name'].'</p></a></li>';			
				}
			}
		}
		?>		
		</ul>
	</div>
	<div class="mem_set">
		<div class="name"><i><img src="/backoffice/pub/images/icon_mem.svg" alt=""></i><?=$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]?>(<?=$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["NAME"]?>)님 로그인</div>
		<a href="/backoffice/">HOME</a>
		<a href="/" target="_blank">내 홈페이지</a>
		<a href="/backoffice/auth/logout.php">로그아웃</a>
	</div>
</div>