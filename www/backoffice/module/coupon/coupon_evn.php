<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("point_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST[evnMode]=="add"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = insertCoupon();
	
	//DB해제
	SetDisConn($dblink);

	if($RS > 0){
		jsGo("member_coupon.php","","추가되었습니다.");
	}else{
		jsMsg("실패하였습니다.");
		//jsHistory("-1") ;
	}

}else if($_POST[evnMode]=="modify"){

	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = updateCoupon($_REQUEST['idx']);
	
	//DB해제
	SetDisConn($dblink);

	if($RS > 0){
		jsGo($_POST[returnURL],"","수정하였습니다.");
	}else{
		jsMsg("실패 하였습니다.");
		jsHistory("-1") ;
	}

}else if($_REQUEST['evnMode']=="user_coupon"){

	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = getUserCoupon($_REQUEST['idx'], $_REQUEST['user_id']);
	
	//DB해제
	SetDisConn($dblink);

	if($RS > 0){
		echo "OK";
	}else{
		echo "ERROR";
	}

}else if($_POST[evnMode]=="add_coupon"){

	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS = updateUserCoupon(mysql_escape_string($_REQUEST[idx]));
	
	//DB해제
	SetDisConn($dblink);

	if($RS > 0){
?>
	<script>
	alert("쿠폰 회원등록 되었습니다.");
	opener.location.reload();
	self.close();
	</script>
<?
	}else{
		jsMsg("회원등록에 실패 하였습니다.");
		jsHistory("-1") ;
	}

}else if($_REQUEST[evnMode]=="sendCoupon"){
	
	$dblink = SetConn($_conf_db["main_db"]);

	$arrInfo = getMyCouponInfo(mysql_escape_string($_REQUEST[idx]));

	SetDisConn($dblink);
	

	$contents = "<div style=\"width:720px;height:453px;background:url(http://myluxuryline.co.kr/images/shop/coupon_bg.gif) 0 0 no-repeat; font-family:NanumGothic; font-weight:700; text-align:center;\">";
	$contents .= "	<h1 style=\"width:430px; height:82px; padding:110px 0 0 0; margin:0 auto; font-size:30px; color:#000;\">".stripslashes($arrInfo["list"][0]["coupon_name"])."</h1>";
	$contents .= "	<div style=\"width:430px; height:80px; margin:0 auto; font-size:17px; color:#000;\">".stripslashes($arrInfo["list"][0]["coupon_content"])."</div>";
	$contents .= "	<div style=\"width:430px; margin:0 auto; font-size:20px; color:#b80100; line-height:30px;\">";
	$contents .= "		쿠폰사용기간 : ".$arrInfo["list"][0]["coupon_edate"]." 까지";
		if($arrInfo["list"][0]["over_price"] > 0) {
			$saleprice = ($arrInfo["list"][0][coupon_dis]*$arrInfo["list"][0][over_price])/100;
			$contents .= "		<br />최대 ".number_format($saleprice)."원까지 할인";
		}
	$contents .= "	</div>";
	$contents .= "</div>";
?>
<html>
<body>
<form name="postmanForm" method="post" action="http://www.postman.co.kr/partner/auto_message_send.jsp" target="POSTMAN">
<input type="hidden" name="mail_code" value="E0954" />
<input type="hidden" name="cooperation_id" value="WS" />
<input type="hidden" name="send_type" value="M" />
<input type="hidden" name="user_id" value="MYLL" />
<input type="hidden" name="auth_key" value="63JDKM-M1FCJQ-LZFVYP-LE3M11" /> <!-- 인증키: 자동부여 -->
<input type="hidden" name="mem_id" value="<?=$_REQUEST[user_id]?>"/> <!-- 받는 사람 ID: 유니크한 유일 값 -->
<input type="hidden" name="mem_name" value="<?=$_REQUEST[user_name]?>"/> <!-- 받는 사람 이름: 실제 받는 사람 이름 -->
<input type="hidden" name="mem_email" value="<?=$_REQUEST[user_id]?>"/> <!-- 받을 사람 E-mail: 실제 발송되는 주소 -->
<input type="hidden" name="M1" value='<?=stripslashes($contents)?>'> <!-- 매핑값 : ${map_4} -->
</form>

<iframe name="POSTMAN" width="0" height="0" frameborder="0"></iframe>

<script type="text/javascript">
document.postmanForm.submit();
</script>
</body>
</html>
<?
	jsMsg("쿠폰내역이 메일로 발송되었습니다.");
	jsHistory("-1") ;

}else if($_POST[evnMode]=="user_delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS2 = deleteUserCoupon($_REQUEST['e_idx'], $_REQUEST['idx']);
	if($RS2 == true){
		if($_POST[returnURL]){
			jsGo($_POST[returnURL],"","정상적으로 삭제 되었습니다.");
		}
	}else{
		jsGo("coupon.php","","삭제중 오류가 발생하였습니다.");
	}


	//DB해제
	SetDisConn($dblink);


}else if($_POST[evnMode]=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS2 = deleteCoupon($_REQUEST['idx']);
	if($RS2 == true){
		if($_POST[returnURL]){
			jsGo($_POST[returnURL],"","정상적으로 삭제 되었습니다.");
		} else {
			jsGo("member_coupon.php","","정상적으로 삭제 되었습니다.");
		}
	}else{
		jsGo("member_coupon.php","","삭제중 오류가 발생하였습니다.");
	}

	//DB해제
	SetDisConn($dblink);

}else if($_GET[evnMode]=="deletemycoupon"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS2 = deleteMyCoupon(mysql_escape_string($_REQUEST[c_idx]));
	if($RS2 == true){
		jsGo("/backoffice/module/shop/shop_mycoupon.php?idx=".$_REQUEST[idx],"","정상적으로 삭제 되었습니다.");
	}else{
		jsGo("/backoffice/module/shop/shop_mycoupon.php?idx=".$_REQUEST[idx],"","삭제중 오류가 발생하였습니다.");
	}

	//DB해제
	SetDisConn($dblink);


}
?>