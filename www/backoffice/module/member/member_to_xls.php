<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER ['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
function yoilHan($strYoil=""){
	$strYoil = str_replace("1","월요일",$strYoil);
	$strYoil = str_replace("2","화요일",$strYoil);
	$strYoil = str_replace("3","수요일",$strYoil);
	$strYoil = str_replace("4","목요일",$strYoil);
	$strYoil = str_replace("5","금요일",$strYoil);
	$strYoil = str_replace("6","토요일",$strYoil);
	$strYoil = str_replace("7","일요일",$strYoil);
	$strYoil = str_replace("all","전체",$strYoil);
	return $strYoil;
}

if (! in_array ( "member_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );

endif;

$scale = 10000;

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

if($_GET['join_type']){
	$subQuery .= " AND join_type='".$_GET['join_type']."' ";
}
if($_GET['job']){
	$subQuery .= " AND job='".$_GET['job']."' ";
}
if($_GET['email_accept']){
	$subQuery .= " AND email_accept='".$_GET['email_accept']."' ";
}
if($_GET['sms_accept']){
	$subQuery .= " AND sms_accept='".$_GET['sms_accept']."' ";
}


$arrList = getMemberList( "",  mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['sw'] ), mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['sk'] ), $scale, $_REQUEST ['offset'] , $subQuery);
// _DEBUG($arrList);

$arrAllCategory = getCategoryAll();

$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","회원정보")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel; charset=euc-kr"); 
header( "Content-Description: PHP4 Generated Data" ); 
header( "Content-Disposition: attachment; filename=".$filename );
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=euc-kr\">");

$EXCEL_TXT = "
<table border='1'>
<tr style='background-color:#ffff00;'>
   <td>No</td>  
   <td>".iconv("UTF-8","EUC-KR","회원상태")."</td>
   <td>".iconv("UTF-8","EUC-KR","가입구분")."</td>
   <td>".iconv("UTF-8","EUC-KR","이름")."</td>
   <td>".iconv("UTF-8","EUC-KR","이메일(아이디)")."</td>
   <td>".iconv("UTF-8","EUC-KR","연락처")."</td>
   <td>".iconv("UTF-8","EUC-KR","메일 수신")."</td>
   <td>".iconv("UTF-8","EUC-KR","SMS 수신")."</td>
   <td>".iconv("UTF-8","EUC-KR","가입날짜")."</td>
   <td>".iconv("UTF-8","EUC-KR","최종로그인")."</td>
   <td>".iconv("UTF-8","EUC-KR","비고")."</td>
</tr>
";

for ( $i=0 ; $i < $arrList["total"] ; $i++ ) {

    $EXCEL_TXT .= "
    <tr>
        <td>" . ($i + 1) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $_SITE["MEMBER_LEVEL"][$arrList['list'][$i]['user_level']]) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $_SITE["MEMBER_TYPE"][$arrList['list'][$i]['join_type']]) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['user_name']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['email']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['mobile']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['email_accept']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['sms_accept']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['wdate']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['login_last']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['user_memo']) . "</td>
    </tr>
	";		
}
$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>