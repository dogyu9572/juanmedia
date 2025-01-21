<?
$thisPHPname = basename($_SERVER['PHP_SELF']);
switch($thisPHPname){
	case 'banner.php' :
	case 'banner_info.php' : 
	case 'banner_add.php' : 
		$leftMenuClass[0] = "on";
	break;
	case 'popup_list.php' : 
	case 'popup_info.php' : 
	case 'popup_add.php' : 
		$leftMenuClass[7] = "on";
	break;
}
?>
<div class="aside">
	<a href="javascript:void(0);" class="btn_aside"></a>
	<div class="in_scroll">
		<div class="menu">			
			<dl class="on">
				<dt>기본 설정 <i></i></dt>
				<dd style="display:block;"><!-- 열려있는 페이지에는 dd에 display:block 해주세요. -->
					<?if(in_array("admin_manage_01", $arrayMyMenuSub) && (in_array("admin_manage_01",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[0]?>" href="/backoffice/module/admin/admin_set.php">· 기본설정</a><?}?>
					<?if(in_array("admin_manage_02", $arrayMyMenuSub) && (in_array("admin_manage_02",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[1]?>" href="/backoffice/module/admin/admin.php">· 관리자 관리</a><?}?>
					<?if(in_array("admin_manage_03", $arrayMyMenuSub) && (in_array("admin_manage_03",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[2]?>" href="/backoffice/module/category/category.php?cat_no=3">· 메뉴 관리</a><?}?>
					<?if(in_array("admin_manage_04", $arrayMyMenuSub) && (in_array("admin_manage_04",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[3]?>" href="/backoffice/module/category/category.php?cat_no=4">· 소비자뉴스 관리</a><?}?>
					<?if(in_array("admin_manage_05", $arrayMyMenuSub) && (in_array("admin_manage_05",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[4]?>" href="/backoffice/module/category/category.php?cat_no=5">· 한소평 콘텐츠 관리</a><?}?>
					<?if(in_array("admin_manage_06", $arrayMyMenuSub) && (in_array("admin_manage_06",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[5]?>" href="/backoffice/module/category/category.php?cat_no=6">· 자주 묻는 질문 관리</a><?}?>
					<?if(in_array("admin_manage_07", $arrayMyMenuSub) && (in_array("admin_manage_07",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[6]?>" href="/backoffice/module/banner/banner.php">· 배너 관리</a><?}?>
					<?if(in_array("admin_manage_08", $arrayMyMenuSub) && (in_array("admin_manage_08",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[7]?>" href="/backoffice/module/popup/popup_list.php">· 팝업 관리</a><?}?>
					<?if(in_array("admin_manage_09", $arrayMyMenuSub) && (in_array("admin_manage_09",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[8]?>" href="/backoffice/module/board/board_view.php?boardid=mailsms">· 메일&문자 관리</a><?}?>
					<?if(in_array("admin_manage_10", $arrayMyMenuSub) && (in_array("admin_manage_10",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[9]?>" href="/backoffice/module/board/board_view.php?boardid=terms&category=1">· 개인정보처리방침 관리</a><?}?>
					<?if(in_array("admin_manage_11", $arrayMyMenuSub) && (in_array("admin_manage_11",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[10]?>" href="/backoffice/module/board/board_view.php?boardid=terms&category=2">· 이용약관 관리</a><?}?>
					<?if(in_array("admin_manage_12", $arrayMyMenuSub) && (in_array("admin_manage_12",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[11]?>" href="/backoffice/module/board/board_view.php?boardid=terms&category=3">· 위치정보이용약관 관리</a><?}?>
					<?if(in_array("admin_manage_13", $arrayMyMenuSub) && (in_array("admin_manage_13",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[12]?>" href="/backoffice/module/board/board_view.php?boardid=terms&category=4">· 결제약관 관리</a><?}?>
					<?if(in_array("admin_manage_14", $arrayMyMenuSub) && (in_array("admin_manage_14",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[13]?>" href="/backoffice/module/board/board_view.php?boardid=terms&category=5">· 결제동의문구 관리</a><?}?>
					<?if(in_array("admin_manage_15", $arrayMyMenuSub) && (in_array("admin_manage_15",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[14]?>" href="/backoffice/module/board/board_view.php?boardid=often">· 자주쓰는 메뉴 관리</a><?}?>
				</dd>
			</dl>			
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>