<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

if (! in_array ( "board_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );
endif;

$filename = iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","참가신청_TOT")."_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel; charset=euc-kr"); 
header( "Content-Description: PHP4 Generated Data" ); 
header( "Content-Disposition: attachment; filename=".$filename );
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=euc-kr\">");

$scale = 10000;

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );


$boardid = "tot";

$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], $boardid);

$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET['sw'], $_GET['sk'], 0, 0, '', '');

?>
<style type="text/css">
body{overflow-x:scroll;width: max-content;}
.mainTable{width:auto;}
th{font-size:12px; background-color:#7cacdc;}	
td{font-size:12px; /*width:300px;*/ padding: 0 10px; text-align:center;}
a{font-size:12px;}	
</style>
<table border=1 class="mainTable" style="overflow-x:scroll;">
	<tr>
		<th style="background-color:#d7e6f4;" rowspan="2">No.</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Name</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Gender</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Date of Birth</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Mobile Phone</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Email</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Nationality</th>	
		<th style="background-color:#d7e6f4;" rowspan="2">Occupation</th>	
		<th style="background-color:#d7e6f4;" rowspan="2">Address</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Academic Backgrounds</th>	
		<th style="background-color:#d7e6f4;" colspan="4">Coaching Experiences</th>	
		<th style="background-color:#d7e6f4;" colspan="3">Business Experiences</th>
		<th style="background-color:#d7e6f4;" >Language Skill</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Your Motivation For Participating TOT</th>
	</tr>
	<tr>					
		<th style="background-color:#7cacdc;">Have you completed the ASEAN Sustainable Business Coach Training Course (TOT) 2021-2022?</th>
		<th style="background-color:#7cacdc;">Do you have experience in business coaching?</th>
		<th style="background-color:#7cacdc;">If Yes, where?</th>
		<th style="background-color:#7cacdc;">What areas have you coached?</th>

		<th style="background-color:#7cacdc;">Do you have any business experience?</th>
		<th style="background-color:#7cacdc;">If Yes, what kind of business?</th>
		<th style="background-color:#7cacdc;">How long have you been in business?</th>	
		<th style="background-color:#7cacdc;">English</th>	
	</tr>
<?	
for($i=0; $i < $arrBoardList["list"]["total"]; $i++){			
	$categoryTitle = $arrBoardList["total"]-$i-(int)$_GET['offset'];
?>
	<tr>
		<td><?=$categoryTitle?></td>
		<td><?=$arrBoardList["list"][$i]['name']?> <?=$arrBoardList["list"][$i]['etc_2']?> <?=$arrBoardList["list"][$i]['etc_3']?></td>
		<td><?=$arrBoardList["list"][$i]['etc_4']?></td>
		<td><?=$arrBoardList["list"][$i]['birth']?></td>
		<td><?=$arrBoardList["list"][$i]['tel']?></td>
		<td><?=$arrBoardList["list"][$i]['email']?></td>
		<td><?=$arrBoardList["list"][$i]['Nationality']?></td>
		<td><?=$arrBoardList["list"][$i]['Occupation']?></td>
		<td><?=$arrBoardList["list"][$i]['homepage']?></td>			
		<?############################ Academic Backgrounds ############################ ED?>
		<td style="vertical-align: top;">
			<table class="" style="width:300px;" border=1>
			<tr>
				<th style="background-color:#6d87b6;color:#ffffff;">No</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Name of University</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Degree</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Date</th>
			</tr>
			<tr>
				<td>01</td>
				<td><?=$arrBoardList["list"][$i]['university1']?></td>
				<td><?=$arrBoardList["list"][$i]['degree1']?></td>
				<td><?=$arrBoardList["list"][$i]['udate1']?></td>
			</tr>
			<tr>
				<td>02</td>
				<td><?=$arrBoardList["list"][$i]['university2']?></td>
				<td><?=$arrBoardList["list"][$i]['degree2']?></td>
				<td><?=$arrBoardList["list"][$i]['udate2']?></td>
			</tr>
			<tr>
				<td>03</td>
				<td><?=$arrBoardList["list"][$i]['university3']?></td>
				<td><?=$arrBoardList["list"][$i]['degree3']?></td>
				<td><?=$arrBoardList["list"][$i]['udate3']?></td>
			</tr>
			</table>
		</td>
		<td><?=$arrBoardList["list"][$i]['training']?></td>	
		<td><?=$arrBoardList["list"][$i]['coaching']?></td>	
		<td><?=nl2br($arrBoardList["list"][$i]['etc_5'])?></td>	
		<td><?=$arrBoardList["list"][$i]['areas']?></td>	
		<td><?=$arrBoardList["list"][$i]['experience']?></td>	
		<td><?=$arrBoardList["list"][$i]['business']?></td>		
		<td><?=$arrBoardList["list"][$i]['btime']?></td>		
		<td><?=$arrBoardList["list"][$i]['engskill']?></td>		
		<td><?=nl2br($arrBoardList["list"][$i]['contents'])?></td>		
	</tr>
<?
}
echo "</table>";

SetDisConn($dblink);
?>