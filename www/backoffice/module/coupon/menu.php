<!--
		<li><a href="/backoffice/module/coupon/member_coupon.php">쿠폰 관리</a></li>
		<li><a href="/backoffice/module/point/point_list.php">포인트 관리</a></li>
		<li><a href="/backoffice/module/point/point_add.php">지급/사용 추가</a></li>
-->     
<?
$thisPHPname = basename($_SERVER['PHP_SELF']);
switch($thisPHPname){	
	case 'member_coupon.php' : case 'member_coupon_info.php' : 
		$leftMenuClass[4] = "on";
	break;
	case 'point_list.php' : case 'point_add.php' : case 'point_alladd.php' : 
		$leftMenuClass[3] = "on";
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
					<a class="<?=$leftMenuClass[1]?>" href="/backoffice/module/member/member_standby.php">· 회원가입 신청</a>
					<a class="<?=$leftMenuClass[0]?>" href="/backoffice/module/member/member.php">· 승인 회원</a>					
					<a class="<?=$leftMenuClass[2]?>" href="/backoffice/module/category/category.php?cat_no=3">· 등급 및 혜택 관리</a>					
					<!--<a class="<?=$leftMenuClass[3]?>" href="/backoffice/module/point/point_list.php">· 적립금 관리</a>-->
					<a class="<?=$leftMenuClass[4]?>" href="/backoffice/module/coupon/member_coupon.php">· 할인혜택 관리</a>
				</dd>
			</dl>
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>
<script type="text/javascript">
<!--
/*
$(document).ready(function(){
	$(".aside .btn_aside").click(function(){
		if($(".aside").hasClass("opcl") === true) {
			alert('class가 존재함.');
		} else {
			alert('class가 존재하지 않음');
		}
		//$(".aside").stop(false,true).toggleClass("opcl");
		//$(".container").stop(false,true).toggleClass("full");
	});
});
*/
//-->
</script>