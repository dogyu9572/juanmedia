<?
$thPHPname = basename($_SERVER['PHP_SELF']);
switch($thPHPname){
	case 'good.php' :	case 'good_info.php' : 
		$leftMenuClass[1] = "on"; break;	
	case 'good_outlist.php' : 
		$leftMenuClass[3] = "on"; break;
	case 'good_etclist.php' : 
		$leftMenuClass[4] = "on"; break;
}
unset($thPHPname);

switch($_GET['cat_no11111111111']){
	case '2' : 
		$leftMenuClass[0] = "on";
	break;
	case '3' : 
		$leftMenuClass[1] = "on";
	break;
}
?>
<div class="aside">
	<a href="javascript:void(0);" class="btn_aside"></a>
	<div class="in_scroll">
		<div class="menu">			
			<dl class="on">
				<dt>제품 관리<i></i></dt>
				<dd style="display:block;"><!-- 열려있는 페이지에는 dd에 display:block 해주세요. -->
					<a class="<?=$leftMenuClass[0]?>" href="/backoffice/module/category/category.php?cat_no=2">· 카테고리 관리</a>
					<a class="<?=$leftMenuClass[1]?>" href="/backoffice/module/shop/good.php">· 제품관리</a>
					<a class="<?=$leftMenuClass[2]?>" href="/backoffice/module/board/board_view.php?boardid=trend">· 트랜드 관리</a>
					<a class="<?=$leftMenuClass[3]?>" href="/backoffice/module/board/board_view.php?boardid=notify&mode=modify&idx=1">· 구매정보</a>
				</dd>
			</dl>			
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>