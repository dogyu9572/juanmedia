<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrBoardList = selectBoardPrint($_REQUEST["boardid"], $_REQUEST["sidx"]);

//	DB해제
//	SetDisConn($dblink);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>상세내용 프린트</title>    
<style>
    h1, h2, h3, h4, h5 {page-break-after: avoid;}
    table, figure, .sector {page-break-inside: avoid; }
    .table {max-width: 70%;margin-bottom: 1rem;border: 1px solid #000; margin: 0 auto;}
    tbody {display: table-row-group;vertical-align: middle;border-color: inherit;}
    .table td {padding: .75rem;vertical-align: top;border-top: 1px solid #000;font-size: 11px; }
    .table .first {border-top: none;}
    .table tr td:first-child {border-right: 1px solid #000; font-weight: bold; width: 40%; max-width:40%;background-color: #eee !important; -webkit-print-color-adjust:exact;}
    .table tr td:last-child {vertical-align: middle; font-size: 13px;max-width:100%;}
</style>
<script src="http://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
<!--
$( document ).ready(function() {
    print();
});
//-->
</script>
</head>
<body>
<?
if($arrBoardList["total"] > 0){
	for($i=0; $i < $arrBoardList["total"]; $i++){		
		$Query2 = mysqli_query($GLOBALS['dblink'],"select etc_1,etc_2 from tbl_board_timetable where idx='".$arrBoardList['list'][$i]['time']."'");	
		$row	= mysqli_fetch_array($Query2);
		$timeTxt[$i] = $row[0].":".$row[1];
?>
<div class="sector" style="padding-bottom:10px;">
	<table class="table">
		<tbody>
		<tr>
			<td class="first">
				코로나 검사 예약 날짜<br/>
				Date of Screening test<br/>
				新冠肺炎(核酸检测)检查日期
			</td>
			<td class="first"><?=substr($arrBoardList["list"][$i]["schedule_date"],0,4)?>년 <?=substr($arrBoardList["list"][$i]["schedule_date"],5,2)?>월 <?=substr($arrBoardList["list"][$i]["schedule_date"],8,2)?>일</td>
		</tr>
		<tr>
			<td>
				예약시간<br/>
				Reservation Time<br/>
				预定时间
			</td>
			<td><?=$timeTxt[$i]?></td>
		</tr>
		<tr>
			<td>
				검사항목<br/>
				Test Type<br/>
				请选择必要的检查项目
			</td>
			<td><?=$pcrName[$arrBoardList["list"][0]['arrpcr']]?></td>
		</tr>
		<tr>
			<td>
				한글이름<br/>
				Given Name<br/>
				英文名字
			</td>
			<td><?=$arrBoardList["list"][$i]["name"]?></td>
		</tr>
		<tr>
			<td>
				여권이름(영문)<br/>
				Surname
			</td>
			<td><?=$arrBoardList["list"][$i]["eng_name"]?></td>
		</tr>
		<tr>
			<td>
				연락처<br/>
				Phone Number<br/>
				联糸电话号码
			</td>
			<td>+<?=$arrBoardList["list"][0]['phone_country']?>)<?=$arrBoardList["list"][0]['phone_number']?></td>
		</tr>
		<tr>
			<td>
				이메일주소<br/>
				Email address<br/>
				电邮 领取                        
			</td>
			<td><?=$arrBoardList["list"][$i]["email"]?></td>
		</tr>
		<tr>
			<td>
				생년월일<br/>
				Date of Birth(YYYY-MM-DD)<br/>
				出生年月日
			</td>
			<td><?=$arrBoardList["list"][$i]["bday"]?></td>
		</tr>
		<tr>
			<td>
				성별<br/>
				Gender<br/>
				性别                                             
			</td>
			<td><?=$arrBoardList["list"][$i]["sex"]=="m"?"남자":"여자"?></td>
		</tr>
		<tr>
			<td>
				여권번호<br/>
				Passport Number<br/>
				护照号码                             
			</td>
			<td><?=$arrBoardList["list"][0]['passport']?></td>
		</tr>
		<tr>
			<td>
				국적<br/>
				Nationality<br/>
				国籍                                               
			</td>
			<td><?=$countryName[$arrBoardList["list"][0]['country']]?></td>
		</tr>
		<tr>
			<td>
				도착지<br/>
				Destination<br/>
				入境国家                     
			</td>
			<td><?=$countryName[$arrBoardList["list"][0]['arrival_country']]?></td>
		</tr>
		<tr>
			<td>
				출국일<br/>
				Date of Departure<br/>
				出发日期                                            
			</td>
			<td><?=$arrBoardList["list"][0]['out_date']?></td>
		</tr>
		<tr>
			<td>
				한국 내 거주지 주소<br/>
				Address of residence in Korea<br/>
				现地址         
			</td>
			<td><?=$arrBoardList["list"][0]['kor_addres']?></td>
		</tr>
		</tbody>
	</table>
</div>
<?
	}
}
?>
</body>
</html>