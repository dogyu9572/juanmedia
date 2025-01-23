<?
$thisPHPname = basename($_SERVER['PHP_SELF']);
switch($thisPHPname){
	case 'member.php' :
	case 'member_info.php' : 
		$leftMenuClass[0] = "on";
	break;
	case 'member_standby.php' :
	case 'member_standby_info.php' : 
		$leftMenuClass[1] = "on";
	break;
	case 'member_add.php' : 
		$leftMenuClass[1] = "on";
	break;
	case 'member_outlist.php' : 
		$leftMenuClass[2] = "on";
	break;
}
?>
<div class="aside">
	<a href="javascript:void(0);" class="btn_aside"></a>
	<div class="in_scroll">
		<div class="menu">			
			<dl class="on">
				<dt>회원 관리 <i></i></dt>
				<dd style="display:block;"><!-- 열려있는 페이지에는 dd에 display:block 해주세요. -->
					<?if(in_array("member_manage_01", $arrayMyMenuSub) && (in_array("member_manage_01",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[0]?>" href="/backoffice/module/member/member.php">· 회원 리스트</a><?}?>
					<?if(in_array("member_manage_02", $arrayMyMenuSub) && (in_array("member_manage_02",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTHSUB"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
					<a class="<?=$leftMenuClass[2]?>" href="/backoffice/module/member/member_outlist.php">· 탈퇴 회원</a><?}?>
				</dd>
			</dl>			
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>        
