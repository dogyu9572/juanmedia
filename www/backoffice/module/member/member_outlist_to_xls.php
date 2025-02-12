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

$arrList = getMemberList( "",  mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['sw'] ), mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['sk'] ), $scale, $_REQUEST ['offset'] , $subQuery);
// _DEBUG($arrList);

$arrAllCategory = getCategoryAll();

$arrLevel = getArticleList ( $_conf_tbl ["member_level"], 0, 0, "order by level_no desc " );

for($i = 0; $i < $arrLevel["total"]; $i ++) {
    $arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}


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
   <td>".iconv("UTF-8","EUC-KR","이름")."</td>
   <td>".iconv("UTF-8","EUC-KR","이메일(아이디)")."</td>
   <td>".iconv("UTF-8","EUC-KR","연락처")."</td>
   <td>".iconv("UTF-8","EUC-KR","메일 수신")."</td>
   <td>".iconv("UTF-8","EUC-KR","SMS 수신")."</td>
   <td>".iconv("UTF-8","EUC-KR","탈퇴날짜")."</td>
   <td>".iconv("UTF-8","EUC-KR","비고")."</td>
</tr>
";

for ($i = 0; $i < $arrList["total"]; $i++) {
    if ($arrList['list'][$i]['before'] == 'Y') {
        $user_name = base64_decode($arrList['list'][$i]['user_name']);
        $mobile = '="' . base64_decode($arrList['list'][$i]['mobile']) . '"';
        $email = base64_decode($arrList['list'][$i]['email']);
    } else {
        $user_name = $arrList['list'][$i]['user_name'];
        $mobile = '="' . $arrList['list'][$i]['mobile'] . '"';
        $email = $arrList['list'][$i]['email'];
    }

    $mobile = str_replace('-', '', $mobile);

    $EXCEL_TXT .= "
    <tr>
        <td>" . ($i + 1) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $_SITE["MEMBER_TYPE"][$arrList['list'][$i]['join_type']]) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $user_name) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['user_id']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $mobile) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['email_accept']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['sms_accept']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['outdt']) . "</td>
        <td>" . iconv("UTF-8", "EUC-KR", $arrList["list"][$i]['user_memo']) . "</td>
    </tr>
    ";
}
$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>