<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/log/log.lib.php";
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

$arrList = getAccessCounterTable("os", $s_date, $e_date, $scale, $_REQUEST['offset']);

//_DEBUG($arrInfo);
//DB해제
SetDisConn($dblink);
?>

<div class="container">

	<div class="title">OS별 접속통계</div>

	<div class="inbox top_search">
	<form method="get" action="<?=$_SERVER["PHP_SELF"]?>" name="logViewFrm">
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
				<div class="total">Total : <strong><?=number_format($arrList["total_sum"])?></strong></div>				
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
							<th class="pc_vw">OS</th>
							<th class="pc_vw">방문수</th>
							<th class="pc_vw">점유율</th>
							<th class="pc_vw">그래프</th>
						</tr>
					</thead>
					<tbody>					
					<?
					if($arrList["total"] > 0){
						for($i=0;$i<$arrList["total"];$i++){
					?>					
						<tr>
							<td><i class="mo_vw">OS</i><?=$arrList["list"][$i]["os"]?></td>
							<td><i class="mo_vw">방문수</i><?=$arrList["list"][$i]["hit"]?></td>
							<td><i class="mo_vw">점유율</i><?=$arrList["total_sum"]!=0?number_format(($arrList["list"][$i]["hit"]/$arrList["total_sum"])*100,2):"0"?> %</td>
							<td><i class="mo_vw">그래프</i>
							<div style="width:<?=$arrList["total_sum"]!=0?number_format(($arrList["list"][$i]["hit"]/$arrList["total_sum"])*100,0):"1"?>%;height:20px;background-color:#CCCCCC"></div>
							</td>
						</tr>
					<?	
						}
					}else{
					?>
						<tr height="100"><td colspan="4" align="center">검색된 자료가 없습니다.</td></tr>
					<?}?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="bdr_btm">
			<div class="paging">	
			<?=pageNavigationBackoffice($arrList['total'],$scale,$pagescale,$_REQUEST['offset'],"s_date=".$s_date."&e_date=".$e_date)?>		
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