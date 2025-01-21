<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

if (! in_array ( "board_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );
endif;

$filename = $_SITE['NAME']."_접수 및 심사내역_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel; charset=utf-8"); 
header( "Content-Description: PHP4 Generated Data" ); 
header( "Content-Disposition: attachment; filename=".$filename );
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");

$scale = 10000;

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );


$boardid = "eco";

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
		<th style="background-color:#d7e6f4;" rowspan="2">Team Name</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Country of Origin</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Date of Establishment</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Number of Team Members</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Types of Establishment</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Stages of Establishment</th>	
		<th style="background-color:#d7e6f4;" rowspan="2">Official website/SNS</th>	
		<th style="background-color:#d7e6f4;" rowspan="2">Profit (USD/year)</th>	
		<th style="background-color:#d7e6f4;" colspan="3">Business brief</th>	
		<th style="background-color:#d7e6f4;" colspan="8">Sustainable Development Goals</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Personal Information</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Academic Backgrounds</th>
		<th style="background-color:#d7e6f4;" rowspan="2">Internet Connection</th>
	</tr>
	<tr>					
		<th style="background-color:#7cacdc;">Industry Sector</th>
		<th style="background-color:#7cacdc;">Title of your business</th>
		<th style="background-color:#7cacdc;">Business concept</th>
		<th style="background-color:#7cacdc;">Which SDG(s) is/are relevant to your business?</th>
		<th style="background-color:#7cacdc;">Sustainability : What aspects of sustainable consumption and production does your business have?</th>
		<th style="background-color:#7cacdc;">Marketability : What is your target market and their needs?</th>
		<th style="background-color:#7cacdc;">Feasibility : Please explain how feasible your product/service is.</th>	
		<th style="background-color:#7cacdc;">Innovation : How innovative is your product/service?</th>	
		<th style="background-color:#7cacdc;">Competitiveness : Describe your business growth potential.</th>	
		<th style="background-color:#7cacdc;">What other social impacts does your business have?</th>	
		<th style="background-color:#7cacdc;">Please upload any materials relevant to introducing your company (Optional)</th>		
	</tr>
<?	
for($i=0; $i < $arrBoardList["list"]["total"]; $i++){			
	$categoryTitle = $arrBoardList["total"]-$i-(int)$_GET['offset'];
?>
	<tr>
		<td><?=$categoryTitle?></td>
		<td><?=$arrBoardList["list"][$i]['name']?></td>
		<td><?=$arrBoardList["list"][$i]['etc_2']?></td>
		<td><?=$arrBoardList["list"][$i]['etc_3']?></td>
		<td><?=$arrBoardList["list"][$i]['etc_4']?></td>
		<td><?=$arrRepTxt[$arrBoardList["list"][$i]['etc_6']]?></td>
		<td><?=$arrRepTxt[$arrBoardList["list"][$i]['etc_7']]?></td>
		<td><?=$arrBoardList["list"][$i]['tel']?></td>
		<td><?=$arrBoardList["list"][$i]['etc_8']?></td>
		<td><?=$arrRepTxt[$arrBoardList["list"][$i]['homepage']]?></td>
		<td><?=$arrBoardList["list"][$i]['subject']?></td>
		<td><?=$arrBoardList["list"][$i]['etc_9']?></td>
		<td><?=$arrRepTxt[$arrBoardList["list"][$i]['etc_10']]?></td>
		<td><?=nl2br($arrBoardList["list"][$i]['etc_11'])?></td>
		<td><?=nl2br($arrBoardList["list"][$i]['etc_12'])?></td>
		<td><?=nl2br($arrBoardList["list"][$i]['etc_13'])?></td>
		<td><?=nl2br($arrBoardList["list"][$i]['etc_14'])?></td>
		<td><?=nl2br($arrBoardList["list"][$i]['etc_15'])?></td>
		<td><?=nl2br($arrBoardList["list"][$i]['etc_16'])?></td>
		<td><?=nl2br($arrBoardList["list"][$i]['etc_17'])?></td>
		<?############################ Personal Information ############################ ST?>
		<td style="vertical-align: top;">
			<table class="" style="width:300px;" border=1>
			<tr>
				<th style="background-color:#6d87b6;color:#ffffff;">Name</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Role and Responsibility</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Gender</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Date of Birth</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Mobile Phone</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Email</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Nationality</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Address of residence</th>
				<th style="background-color:#6d87b6;color:#ffffff;">Affiliation and position</th>
			</tr>
			<?
			$arrMember01 = explode("|",$arrBoardList["list"][$i]["member_1"]);
			$arrMember02 = explode("|",$arrBoardList["list"][$i]["member_2"]);
			$arrMember03 = explode("|",$arrBoardList["list"][$i]["member_3"]);
			$arrMember04 = explode("|",$arrBoardList["list"][$i]["member_4"]);
			$arrMember05 = explode("|",$arrBoardList["list"][$i]["member_5"]);
			$arrMember06 = explode("|",$arrBoardList["list"][$i]["member_6"]);
			$arrMember07 = explode("|",$arrBoardList["list"][$i]["member_7"]);
			$arrMember08 = explode("|",$arrBoardList["list"][$i]["member_8"]);
			$arrMember09 = explode("|",$arrBoardList["list"][$i]["member_9"]);
			$arrMember10 = explode("|",$arrBoardList["list"][$i]["member_10"]);
			$arrMember11 = explode("|",$arrBoardList["list"][$i]["member_11"]);

			for($j=1;$j<11;$j++){				
				if(str_replace("|","",$arrMember01[$j])){
			?>
				<tr>
					<td><?=$arrMember01[$j]?> <?=$arrMember02[$j]?> <?=$arrMember03[$j]?></td>
					<td><?=$arrMember04[$j]?></td>
					<td><?=$arrMember05[$j]?></td>
					<td><?=$arrMember06[$j]?></td>
					<td style=mso-number-format:'\@'><?=$arrMember07[$j]?></td>
					<td><?=$arrMember08[$j]?></td>
					<td><?=$arrMember09[$j]?></td>
					<td><?=$arrMember10[$j]?></td>
					<td><?=$arrMember11[$j]?></td>
				</tr>
			<?
				}
			}		
			?>
			</table>
		</td>
		<?############################ Personal Information ############################ ED?>	
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
		<td><?=$arrRepTxt[$arrBoardList["list"][$i]['etc_18']]?></td>
	</tr>
<?
}
echo "</table>";

SetDisConn($dblink);
?>