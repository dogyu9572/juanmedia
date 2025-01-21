<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

/* 매출통계그래프*/
$arrAccGraph[0] = getAccountStatus(date("Y-01-01"), date("Y-01-31"));
$arrAccGraph[1] = getAccountStatus(date("Y-02-01"), date("Y-02-29"));
$arrAccGraph[2] = getAccountStatus(date("Y-03-01"), date("Y-03-31"));
$arrAccGraph[3] = getAccountStatus(date("Y-04-01"), date("Y-04-30"));
$arrAccGraph[4] = getAccountStatus(date("Y-05-01"), date("Y-05-31"));
$arrAccGraph[5] = getAccountStatus(date("Y-06-01"), date("Y-06-30"));
$arrAccGraph[6] = getAccountStatus(date("Y-07-01"), date("Y-07-31"));
$arrAccGraph[7] = getAccountStatus(date("Y-08-01"), date("Y-08-31"));
$arrAccGraph[8] = getAccountStatus(date("Y-09-01"), date("Y-09-30"));
$arrAccGraph[9] = getAccountStatus(date("Y-10-01"), date("Y-10-31"));
$arrAccGraph[10] = getAccountStatus(date("Y-11-01"), date("Y-11-30"));
$arrAccGraph[11] = getAccountStatus(date("Y-12-01"), date("Y-12-31"));

//DB해제
SetDisConn($dblink);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="imagetoolbar" content="no" />
<title><?=$_SITE["NAME"]?> 관리자</title>
<link href="/backoffice/css/style.css" rel="stylesheet" type="text/css" />
<script src="/backoffice/js/jquery-1.8.2.min.js" type="text/javascript"></script>
</head>

<body>

<div id="chart1" style="margin-left:10px; width:1050px; height:200px;"></div>

<script class="code" type="text/javascript">
$(document).ready(function(){
	$.jqplot.config.enablePlugins = true;
	var s1 = [<? for($i=0; $i < 12; $i++){?><?=str_replace(",","",number_format($arrAccGraph[$i]['list_sum'][pay_amount]))?><? if($i!=11){?>,<?}?><?}?>];
	var ticks = ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'];
	
	plot1 = $.jqplot('chart1', [s1], {
		// Only animate if we're not using excanvas (not in IE 7 or IE 8)..
		animate: !$.jqplot.use_excanvas,
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			}
		},
		highlighter: { show: false }
	});
});
</script>
<script class="include" type="text/javascript" src="/common/chart/jquery.jqplot.min.js"></script>

<script class="include" type="text/javascript" src="/common/chart/plugins/jqplot.barRenderer.min.js"></script>
<script class="include" type="text/javascript" src="/common/chart/plugins/jqplot.pieRenderer.min.js"></script>
<script class="include" type="text/javascript" src="/common/chart/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script class="include" type="text/javascript" src="/common/chart/plugins/jqplot.pointLabels.min.js"></script>
			
</body>
</html>
