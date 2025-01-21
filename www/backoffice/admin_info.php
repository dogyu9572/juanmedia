<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/log/log.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$yesterday	= date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
$monthFirst	= date("Y-m")."-01";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrListLogToday = getAccessCounterDaily(date("Y-m-d"), date("Y-m-d"));	//오늘방문
$arrListLogYesterday = getAccessCounterDaily($yesterday, $yesterday);
$arrListLogMonth = getAccessCounterDaily($monthFirst, date("Y-m-d"));
$arrListLogAll = getAccessCounterDaily("2016-08-01", date("Y-m-d"));

/*
$sql = "select * from tbl_board_info";
$result = mysqli_query($GLOBALS['dblink'], $sql);

$i=0;
while($row=mysqli_fetch_array($result)){
	$arrLeftBoard[$i] = getBoardListBase($row['boardid'], "", "", "", "", "");
	$boardTotal += $arrLeftBoard[$i]["total"];
	$i++;
}
*/
//DB해제
SetDisConn($dblink);
?>
<div class="visit">
	<div class="time" id="clock"><?=date("Y.m.d H:i:s")?></div>
	<ul>
		<li>
			<i class="i1"></i>
			<div class="txt">
				오늘방문<br>
				<strong><?=number_format($arrListLogToday["unisum"])?></strong> 명
			</div>
		</li>
		<li>
			<i class="i2"></i>
			<div class="txt">
				어제방문<br>
				<strong><?=number_format($arrListLogYesterday["unisum"])?></strong> 명
			</div>
		</li>
		<li>
			<i class="i3"></i>
			<div class="txt">
				이달방문<br>
				<strong><?=number_format($arrListLogMonth["unisum"])?></strong> 명
			</div>
		</li>
		<li class="bar"></li>
		<li>
			<i class="i4"></i>
			<div class="txt">
				총 방문객<br>
				<strong><?=number_format($arrListLogAll["unisum"])?></strong> 명
			</div>
		</li>
		<!--
		<li class="bar"></li>
		<li>
			<i class="i5"></i>
			<div class="txt">
				총 게시물<br>
				<strong><?=number_format($boardTotal)?></strong> 개
			</div>
		</li>
		-->
	</ul>
</div>
<script type="text/javascript">
<!--
const clock = document.querySelector('.h1_clock');

function getTime(){
    const time = new Date();
    const hour = time.getHours();
    const minutes = time.getMinutes();
    const seconds = time.getSeconds();
	//alert( hour +":" + minutes + ":"+seconds);
    //clock.innerHTML = hour +":" + minutes + ":"+seconds;
    clock.innerHTML = `${hour<10 ? `0${hour}`:hour}:${minutes<10 ? `0${minutes}`:minutes}:${seconds<10 ? `0${seconds}`:seconds}`
}
function init(){	
	setInterval(getTime, 1000);
}
//init();	
//-->
</script>

<script language="JavaScript"> 
function printTime(){ 
	var clock = document.getElementById("clock"); 
	var now = new Date(); 

	clock.innerHTML = now.getFullYear() + "." + fillZero(now.getMonth()+1) + "." + fillZero(now.getDate()) + " " + fillZero(now.getHours()) + ":" + fillZero(now.getMinutes()) + ":" + fillZero(now.getSeconds()); 
	
	setTimeout("printTime()", 1000); 
}

window.onload = function() { 
	printTime(); 
}; 

function fillZero(num){
	var width = 2;
	var str	= String(num);
    return str.length >= width ? str:new Array(width-str.length+1).join('0')+str;//남는 길이만큼 0으로 채움
}
</script> 