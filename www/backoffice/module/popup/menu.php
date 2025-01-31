<?
$thisPHPname = basename($_SERVER['PHP_SELF']);
switch($thisPHPname){
	case 'popup_list.php' : 
	case 'popup_info.php' : 
	case 'popup_add.php' : 
		$leftMenuClass[3] = "on";
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
                    <a class="<?=$leftMenuClass[1]?>" href="/backoffice/module/board/board_view.php?boardid=mailsms">· 문자&메일 관리</a>
                    <a class="<?=$leftMenuClass[2]?>" href="/backoffice/module/banner/banner.php">· 배너 관리</a>
                    <a class="<?=$leftMenuClass[3]?>" href="/backoffice/module/popup/popup_list.php">· 팝업 관리</a>
                    <a class="<?=$leftMenuClass[4]?>" href="/backoffice/module/category/category.php?cat_no=5">· 패밀리사이트 관리</a>
                    <a class="<?=$leftMenuClass[5]?>" href="/backoffice/module/board/board_view.php?boardid=payment_summary">· 매출내역 관리</a>
                    <a class="<?=$leftMenuClass[6]?>" href="/backoffice/module/board/board_view.php?boardid=payment_details">· 세부실적 내역</a>
                </dd>
			</dl>			
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>