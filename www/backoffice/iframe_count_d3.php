<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/log/log.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

/* 접속통계그래프*/
$arrLogGraph[0] = getAccessCounterMonthly(date("Y-01-01"), date("Y-01-31"));
$arrLogGraph[1] = getAccessCounterMonthly(date("Y-02-01"), date("Y-02-29"));
$arrLogGraph[2] = getAccessCounterMonthly(date("Y-03-01"), date("Y-03-31"));
$arrLogGraph[3] = getAccessCounterMonthly(date("Y-04-01"), date("Y-04-30"));
$arrLogGraph[4] = getAccessCounterMonthly(date("Y-05-01"), date("Y-05-31"));
$arrLogGraph[5] = getAccessCounterMonthly(date("Y-06-01"), date("Y-06-30"));
$arrLogGraph[6] = getAccessCounterMonthly(date("Y-07-01"), date("Y-07-31"));
$arrLogGraph[7] = getAccessCounterMonthly(date("Y-08-01"), date("Y-08-31"));
$arrLogGraph[8] = getAccessCounterMonthly(date("Y-09-01"), date("Y-09-30"));
$arrLogGraph[9] = getAccessCounterMonthly(date("Y-10-01"), date("Y-10-31"));
$arrLogGraph[10] = getAccessCounterMonthly(date("Y-11-01"), date("Y-11-30"));
$arrLogGraph[11] = getAccessCounterMonthly(date("Y-12-01"), date("Y-12-31"));

/* 접속통계그래프*/
for($i=0; $i < 12; $i++){
	$arrLogGraph[$i]["list"][0]["sum_hit"] = $arrLogGraph[$i]["list"][0]["sum_hit"]??0;
}
//DB해제
SetDisConn($dblink);


?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="/_d3/c3.css">
<style type="text/css">
	text > tspan{font-size:12px; font-family: '나눔고딕',NanumGothic,'맑은고딕',Malgun Gothic,'돋움',Dotum,helvetica,sans-serif;font-weight:800;color:blue !important;}
</style>
</head>
<body>
<div id="chart"></div>

<script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script>
<script src="/_d3/c3.js"></script>
<script>
var data, axis_x_localtime;

var data1 = {
	x : 'date',
	columns: [
		['date', '2020-01-01', '2020-02-01', '2020-03-01', '2020-04-01', '2020-05-01', '2020-06-01', '2020-07-01', '2020-08-01', '2020-09-01', '2020-10-01', '2020-11-01', '2020-12-01'],
		['접속수', <? for($i=0; $i < 12; $i++){?><?=str_replace(",","",number_format($arrLogGraph[$i]["list"][0]["sum_hit"]))?><? if($i!=11){?>,<?}?><?}?>]
	],
	type: 'bar'
};

var generate = function () { return c3.generate({
		bindto: '#chart',
		data: data,
		legend: {
			show: false
		},
		axis : {
			x : {
				type : 'timeseries',
				tick : {
					format : "%m 월" // https://github.com/mbostock/d3/wiki/Time-Formatting#wiki-format
				},
				localtime: axis_x_localtime
			}
		}
	}); 
};

setTimeout(function () {
	data = data1;
	axis_x_localtime = true;
	chart = generate();
}, 1);

</script>
</body>
</html>