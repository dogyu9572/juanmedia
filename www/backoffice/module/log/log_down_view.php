<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/log/log.lib.php";
if(!in_array("log_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getAccessDownload();


//_DEBUG($arrInfo);
//DB해제
SetDisConn($dblink);
?>
<script src="/backoffice/js/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/datePicker/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/datePicker/jquery-ui.css" />


<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">다운로드통계</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 다운로드통계</div>
	</div>

<table border="0" cellpadding="0" cellspacing="1" width="100%">
	<tr>
		<td valign="top">
		<table border="0" cellpadding="3" cellspacing="1" width="100%" style="border:1px solid #dedede;">
			<tr align="center" bgcolor="#EEEEEE">
				<td width="10%"><b>항목</b></td>
				<td width="10%"><b>다운로드수</b></td>
				<td width="10%"><b>%</b></td>
				<td width="70%"><b>그래프</b></td>
			</tr>
			<?
			if($arrList["total"] > 0){
				for($i=0;$i<$arrList["total"];$i++){
			?>
				<tr align="right">
					<td width="10%" bgcolor="#EEEEEE" align="center">
						<?=$arrList["list"][$i]["category"]=="all"?"통합 다운로드":""?>
						<?=$arrList["list"][$i]["category"]=="today"?"오늘체 다운로드":""?>
						<?=$arrList["list"][$i]["category"]=="oneday"?"하루체 다운로드":""?>
						<?=$arrList["list"][$i]["category"]=="image"?"이미지 다운로드":""?>
					</td>
					<td width="10%"><?=$arrList["list"][$i]["hit"]?></td>
					<td width="10%"><?=$arrList["sum"]!=0?number_format(($arrList["list"][$i]["hit"]/$arrList["sum"])*100,2):"0"?> %</td>
					<td width="70%" align="left"><table border="0" title=""><tr><td bgcolor="#CCCCCC" width="<?=$arrList["sum"]!=0?number_format(($arrList["list"][$i]["hit"]/$arrList["sum"])*800,0):"0"?>" height="10"></td></tr></table></td>
				</tr>	

			<?
				}
			}else{?>
				<tr height="100"><td colspan="4" align="center">검색된 자료가 없습니다.</td></tr>
				<tr height="1" bgcolor="#DDDDDD"><td colspan="4"></td></tr>
			<?}?>
		</table>
		</td>
	</tr>
</table>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>