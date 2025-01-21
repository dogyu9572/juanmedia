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
if(!isset($_REQUEST['s_date'])){
	$s_date = date("Y-m-d");
}else{
	$s_date = $_REQUEST['s_date'];
}

if(!isset($_REQUEST['e_date'])){
	$e_date = date("Y-m-d");
}else{
	$e_date = $_REQUEST['e_date'];
}

if(!isset($_REQUEST['offset'])){
	$_REQUEST['offset']=0;
}

$arrList = getAccessCounterLog($s_date, $e_date, $scale, $_REQUEST['offset']);

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
	<div class="admin-title-top">
		<h2 class="admin-title">접속통계</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 접속통계 &nbsp;&gt;&nbsp; 방문자 로그</div>
	</div>

<script language="javascript" src="calendar.js"></script>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
	<form method="get" action="<?=$_SERVER["PHP_SELF"]?>" name="logViewFrm">
	<tr height="25" align="left">
		<td width="100%">
		<input type="submit" value="조회" style="width:40px;height:22px;"> <input type="text" name="s_date" size="12" class="input datePicker" value="<?=$s_date?>"> ~ <input type="text" name="e_date" size="12" class="input datePicker" value="<?=$e_date?>">
		<b><?=number_format($arrList["total"])?>개의 기록</b>
		</td>
	</tr>
	</form>
</table>
<table border="0" cellpadding="0" cellspacing="1" width="100%">
	<tr>
		<td valign="top">
		<table border="0" cellpadding="3" cellspacing="1" width="100%" style="border:1px solid #dedede;">
			<tr align="center" bgcolor="#EEEEEE">
				<td width="5%"><b>No</b></td>
				<td width="40%"><b>접속주소/접속페이지</b></td>
				<td width="10%"><b>OS</b></td>
				<td width="10%"><b>브라우저</b></td>
				<td width="10%"><b>ip</b></td>
				<td width="10%"><b>검색/키워드</b></td>
				<td width="15%"><b>방문일</b></td>
			</tr>
			<?
			if($arrList["total"] > 0){
				for($i=0;$i<$arrList["list"]["total"];$i++){
			?>
				<tr align="center"<?=$i%2==1?" bgcolor='#f8f8f8'":""?>>
					<td bgcolor="#EEEEEE"><?=number_format($arrList["total"]-$_REQUEST[offset]-$i)?></td>
					<td align="left">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr><td><?=text_cut($arrList["list"][$i]["referer"],75)?></td></tr>
						<tr><td><?=text_cut($arrList["list"][$i]["page"],75)?></td></tr>
					</table></td>
					<td><?=$arrList["list"][$i]["os"]?></td>
					<td><?=$arrList["list"][$i]["browser"]?></td>
					<td><?=$arrList["list"][$i]["ip"]?></td>
					<td align="left">
						<?=$arrList["list"][$i]["searchengin"]?>
						<?if($arrList["list"][$i]["keyword"]):?> | <?=$arrList["list"][$i]["keyword"]?><?endif;?>
					</td>
					<td><?=$arrList["list"][$i]["wdate"]?></td>
				</tr>
				<?}?>
			<?}else{?>
				<tr height="100"><td colspan="7" align="center">검색된 자료가 없습니다.</td></tr>
				<tr height="1" bgcolor="#DDDDDD"><td colspan="7"></td></tr>
			<?}?>
		</table>
		</td>
	</tr>
</table>
<div class="paginate">
	<?=pageNavigation($arrList['total'],$scale,$pagescale,$_REQUEST['offset'],"s_date=".$s_date."&e_date=".$e_date)?>
</div>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>