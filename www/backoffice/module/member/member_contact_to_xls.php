<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER ['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";

if (! in_array ( "member_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );

endif;

$scale = 10000;

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$subQuery = " AND idx in (".$_REQUEST['idx'].") ";
$arrList = getMemberList("", "", "", 0, 0, $subQuery);

$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","회원정보")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel; charset=euc-kr"); 
header( "Content-Description: PHP4 Generated Data" ); 
header( "Content-Disposition: attachment; filename=".$filename );
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=euc-kr\">");

$EXCEL_TXT = "
<table border='1'>
<tr style='background-color:#ffff00;'>
   <td>No</td>  
   <td>".iconv("UTF-8","EUC-KR","가입구분")."</td>
   <td>".iconv("UTF-8","EUC-KR","회원등급")."</td>
   <td>".iconv("UTF-8","EUC-KR","아이디")."</td>
   <td>".iconv("UTF-8","EUC-KR","이름")."</td>
   <td>".iconv("UTF-8","EUC-KR","이메일")."</td>
   <td>".iconv("UTF-8","EUC-KR","연락처")."</td>
   <td>".iconv("UTF-8","EUC-KR","휴대폰번호")."</td>
   <td>".iconv("UTF-8","EUC-KR","주소")."</td>
   <td>".iconv("UTF-8","EUC-KR","성별")."</td>
   <td>".iconv("UTF-8","EUC-KR","생년월일")."</td>
   <td>".iconv("UTF-8","EUC-KR","Email수신동의")."</td>
   <td>".iconv("UTF-8","EUC-KR","SMS수신동의")."</td>
   <td>".iconv("UTF-8","EUC-KR","가입일시")."</td>
</tr>
";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {
	if($arrList['list'][$i]['join_type']=="kakao"){
		$joinType = "카카오";
	}else if($arrList['list'][$i]['join_type']=="naver"){
		$joinType = "네이버";
	}else{
		$joinType = "일반";
	}

	if($arrList["list"][$i]['etc_1']=="m"){
		$arrList["list"][$i]['sex'] = "남자";
	}else if($arrList["list"][$i]['etc_1']=="f"){
		$arrList["list"][$i]['sex'] = "여자";	
	}else if($arrList["list"][$i]['etc_1']=="n"){
		$arrList["list"][$i]['sex'] = "선택안함";	
	}
	
	$EXCEL_TXT .= "
	<tr>
		<td>".($i+1)."</td>
		<td>".iconv("UTF-8","EUC-KR",$joinType)."</td>
		<td>".iconv("UTF-8","EUC-KR",$_SITE["MEMBER_LEVEL"][$arrList['list'][$i]['user_level']])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['user_id'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['user_name'])."</td>	
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['email'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['phone'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['mobile'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['address']." ".$arrList["list"][$i]['address_ext'])."</td>		
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['sex'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['birth'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['email_accept'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['sms_accept'])."</td>
		<td>".iconv("UTF-8","EUC-KR",$arrList["list"][$i]['wdate'])."</td>
	</tr>
	";		
}
$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>