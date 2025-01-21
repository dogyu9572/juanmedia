<?
switch($_GET['mode']){
	case '1' : 
		$leftMenuClass[0] = "on";
	break;
	case '2' : 
		$leftMenuClass[1] = "on";
	break;
	case '4' : 
		$leftMenuClass[2] = "on";
	break;
}
switch($_GET['cm']){
	case '1' : 
		$leftMenuClass[5] = "on";
	break;
	case '2' : 
		$leftMenuClass[6] = "on";
	break;
	case '3' : 
		$leftMenuClass[7] = "on";
	break;
}
?>
<div class="aside">
	<a href="javascript:void(0);" class="btn_aside"></a>
	<div class="in_scroll">
		<div class="menu">			
			<dl class="on">
				<dt>주문 관리 <i></i></dt>
				<dd style="display:block;"><!-- 열려있는 페이지에는 dd에 display:block 해주세요. -->
					<a class="<?=$leftMenuClass[0]?>" href="/backoffice/module/shop/order.php?mode=1">· 전체 주문 조회</a>
					<a class="<?=$leftMenuClass[1]?>" href="/backoffice/module/shop/order.php?mode=2">· 취소/환불/교환</a>
					<!--
					<a class="<?=$leftMenuClass[5]?>" href="/backoffice/module/shop/order_list2.php?cm=1">· 취소 주문 조회</a>
					<a class="<?=$leftMenuClass[6]?>" href="/backoffice/module/shop/order_list2.php?cm=2">· 교환 주문 조회</a>
					<a class="<?=$leftMenuClass[7]?>" href="/backoffice/module/shop/order_list2.php?cm=3">· 환불 주문 조회</a>
					-->
				</dd>
			</dl>			
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>