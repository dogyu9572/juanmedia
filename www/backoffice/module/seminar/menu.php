<?
$thisPHPname = basename($_SERVER['PHP_SELF']);
switch($thisPHPname){
	case 'order_seminar.php' : case 'order_seminar_detail.php' : 
		$leftMenuClass[2] = "on"; break;
	case 'seminar_list.php' : case 'seminar_info.php' : 
		$leftMenuClass[0] = "on"; break;
	case 'order_academic.php' : case 'order_academic_detail.php' : 
		$leftMenuClass[3] = "on"; break;
	case 'product_list.php' : 
		$leftMenuClass[4] = "on"; break;
}
unset($thisPHPname);
?>
<div class="aside">
	<a href="javascript:void(0);" class="btn_aside"></a>
	<div class="in_scroll">
		<div class="menu">			
			<dl class="on">
				<dt>세미나 관리 <i></i></dt>
				<dd style="display:block;"><!-- 열려있는 페이지에는 dd에 display:block 해주세요. -->
					<a class="<?=$leftMenuClass[0]?>" href="/backoffice/module/seminar/seminar_list.php">· 세미나 목록</a>
					<a class="<?=$leftMenuClass[1]?>" href="/backoffice/module/board/board_view.php?boardid=academic">· 강의 관리</a>
					<a class="<?=$leftMenuClass[2]?>" href="/backoffice/module/seminar/order_seminar.php">· 결제 관리</a>
					<a class="<?=$leftMenuClass[3]?>" href="/backoffice/module/seminar/order_academic.php">· 수강 관리</a>
					<a class="<?=$leftMenuClass[4]?>" href="/backoffice/module/seminar/product_list.php">· 관련상품 관리</a>
					<a class="<?=$leftMenuClass[5]?>" href="/backoffice/module/board/board_view.php?boardid=placeusage">· 대관신청 관리</a>
				</dd>
			</dl>			
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>