<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/log/log.lib.php";
if(!in_array("log_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;
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


$arrInfo = getAccessCounterHourly($s_date,$e_date);

//_DEBUG($arrInfo);
//DB해제
SetDisConn($dblink);
?>

<div class="container">

	<div class="title">시간대별 접속통계</div>

	<div class="inbox top_search">
	<form method="get" action="<?=$_SERVER["PHP_SELF"]?>" name="logViewFrm">
		<!--	<dl>
			<dt>구분</dt>
			<dd><select name="" id=""><option value="">전체</option></select></dd>
		</dl>
		<dl>
			<dt>업체구분</dt>
			<dd><select name="" id=""><option value="">전체</option></select></dd>
		</dl>
		<dl>
			<dt>회비현황</dt>
			<dd><select name="" id=""><option value="">전체</option></select></dd>
		</dl>
		<dl>
			<dt>보유신기술</dt>
			<dd><select name="" id=""><option value="">전체</option></select></dd>
		</dl>
		<dl class="w2">
			<dt>업태</dt>
			<dd><select name="" id=""><option value="">전체</option></select></dd>
		</dl>	-->
		<dl class="w2">
			<dt>검색</dt>
			<dd><input type="text"  name="s_date" class="datepicker" value="<?=$s_date?>"/><em>~</em><input type="text" name="e_date" class="datepicker" value="<?=$e_date?>"/></dd>
		</dl>
		<dl class="search_wrap">
			<dd>
				<button type="button" class="search" onclick="document.logViewFrm.submit()">검색</button>
			</dd>
		</dl>
	</form>
	</div>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrInfo["list"][0]["hit"])?></strong></div>				
			</div>
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w10p">
						<col class="w10p">
						<col class="w10p">
						<col class="*">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">시간</th>
							<th class="pc_vw">방문수</th>
							<th class="pc_vw">시/일</th>
							<th class="pc_vw">그래프</th>
						</tr>
					</thead>
					<tbody>					
					<?for($i=0;$i<12;$i++){?>					
						<tr>
							<td><i class="mo_vw">시간</i><?=$i?> 시</td>
							<td><i class="mo_vw">방문수</i><?=number_format($arrInfo["list"][0]["h".$i])?></td>
							<td><i class="mo_vw">시/일</i><?=$arrInfo["list"][0]["hit"]!=0?number_format(($arrInfo["list"][0]["h".$i]/$arrInfo["list"][0]["hit"])*100,2):"0"?> %</td>
							<td><i class="mo_vw">그래프</i>
							<div style="width:<?=$arrInfo["list"][0]["h".$i]==0?"1":number_format(($arrInfo["list"][0]["h".$i]/$arrInfo["list"][0]["hit"])*100,0)?>%;height:20px;background-color:#CCCCCC"></div>
							</td>
						</tr>
					<?}?>
					<?for($i=12;$i<24;$i++){?>				
						<tr>
							<td><i class="mo_vw">시간</i><?=$i?> 시</td>
							<td><i class="mo_vw">방문수</i><?=number_format($arrInfo["list"][0]["h".$i])?></td>
							<td><i class="mo_vw">시/일</i><?=$arrInfo["list"][0]["hit"]!=0?number_format(($arrInfo["list"][0]["h".$i]/$arrInfo["list"][0]["hit"])*100,2):"0"?> %</td>
							<td><i class="mo_vw">그래프</i>
							<div style="width:<?=$arrInfo["list"][0]["h".$i]==0?"1":number_format(($arrInfo["list"][0]["h".$i]/$arrInfo["list"][0]["hit"])*100,0)?>%;height:20px;background-color:#CCCCCC"></div>
							</td>
						</tr>
					<?}?>	
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
//달력
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.gif", 
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});

});
//]]>
</script>

<?php include("pub/inc/footer.php") ?>