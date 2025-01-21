<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/log/log.lib.php";
if(!in_array("log_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;
$scale = 20;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//검색날짜 설정
if(!$_REQUEST[s_date]){
	$s_date = date("Y-m-d");
}else{
	$s_date = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[s_date]);
}

if(!$_REQUEST[e_date]){
	$e_date = date("Y-m-d");
}else{
	$e_date = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[e_date]);
}

$arrList = getAccessCounterTable("searchengin", $s_date, $e_date, $scale, $_REQUEST[offset]);

//_DEBUG($arrInfo);
//DB해제
SetDisConn($dblink);
?>
<script src="/backoffice/js/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/datePicker/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/datePicker/jquery-ui.css" />
<script>
$(function() {
    $(".datePicker").datepicker({ 
     dateFormat: 'yy-mm-dd',
     monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
     dayNamesMin: ['일','월','화','수','목','금','토'],
	 weekHeader: 'Wk',
     changeMonth: true, //월변경가능
     changeYear: true, //년변경가능
     showMonthAfterYear: true //년 뒤에 월 표시
  });
 });
</script>

<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<h2 class="admin-title">접속통계</h2>

<script language="javascript" src="calendar.js"></script>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
	<form method="get" action="<?=$_SERVER["PHP_SELF"]?>" name="logViewFrm">
	<tr height="25" align="left">
		<td width="100%">
		<input type="submit" value="조회" style="width:40px;height:22px;"> <input type="text" name="s_date" size="12" class="input datePicker" value="<?=$s_date?>"> ~ <input type="text" name="e_date" size="12" class="input datePicker" value="<?=$e_date?>">
		<b><?=number_format($arrList["total"])?>개의 검색엔진 : <?=number_format($arrList["total_sum"])?>회</b>
		(방문자가 검색엔진을 통해서 왔을경우에만 표시되므로 전체 방문자수와 검색엔진별 방문자수 합계가 동일하지 않음)
		</td>
	</tr>
	</form>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
	<tr>
		<td valign="top">
		<table border="0" cellpadding="3" cellspacing="1" width="100%" style="border:1px solid #dedede;">
			<tr align="center" bgcolor="#EEEEEE">
				<td width="10%"><b>검색엔진</b></td>
				<td width="10%"><b>방문수</b></td>
				<td width="10%"><b>점유율</b></td>
				<td width="70%"><b>그래프</b></td>
			</tr>
			<?
			if($arrList["total"] > 0){
				for($i=0;$i<$arrList["list"]["total"];$i++){
			?>
				<tr align="right">
					<td width="10%" bgcolor="#EEEEEE" align="center"><a href="http://<?=$arrList["list"][$i]["searchengin"]?>" target="_blank"><?=$arrList["list"][$i]["searchengin"]?></a> </td>
					<td width="10%" align="center"><?=$arrList["list"][$i]["hit"]?> </td>
					<td width="10%"><?=$arrList["total_sum"]!=0?number_format(($arrList["list"][$i]["hit"]/$arrList["total_sum"])*100,2):"0"?> %</td>
					<td width="70%" align="left"><table border="0" title=""><tr><td bgcolor="#CCCCCC" width="<?=$arrList["total_sum"]!=0?number_format(($arrList["list"][$i]["hit"]/$arrList["total_sum"])*200,0):"0"?>" height="10"></td></tr></table></td>
				</tr>
				<?}?>
			<?}else{?>
				<tr height="100"><td colspan="4" align="center">검색된 자료가 없습니다.</td></tr>
				<tr height="1" bgcolor="#DDDDDD"><td colspan="4"></td></tr>
			<?}?>
		</table>
		</td>
	</tr>
</table>
<div class="paginate">
	<?=pageNavigation($arrList['total'],$scale,$pagescale,$_REQUEST[offset],"s_date=".$s_date."&e_date=".$e_date)?>
</div>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>