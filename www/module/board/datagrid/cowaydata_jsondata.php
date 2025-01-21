<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

$boardid	= isset($_POST['boardid']) ? strval($_POST['boardid']) : 'cowaydata';
$page		= isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows		= isset($_POST['rows']) ? intval($_POST['rows']) : 10;
$sort		= isset($_POST['sort']) ? strval($_POST['sort']) : 'idx';
$order		= isset($_POST['order']) ? strval($_POST['order']) : 'desc';
$searchid	= isset($_POST['searchid']) ? $_POST['searchid'] : '';
$searchtext	= isset($_POST['searchtext']) ? $_POST['searchtext'] : '';
$searchsdate	= isset($_POST['searchsdate']) ? $_POST['searchsdate'] : '';
$searchedate	= isset($_POST['searchedate']) ? $_POST['searchedate'] : '';

$wheresql =" where 1=1 ";
if($searchtext){
	if($searchid=="s"){$searchCol="A.subject";}
	if($searchid=="c"){$searchCol="A.contents";}
	if($searchid=="w"){$searchCol="A.name";}	
	if($searchid=="all"){
		$wheresql .=" and (A.subject like '%".$searchtext."%' or A.contents like '%".$searchtext."%' or A.name like '%".$searchtext."%') ";
	}else{
		$wheresql .=" and ".$searchCol." like '%".$searchtext."%' ";
	}
}
################################ 날짜 검색 ############################//ST
if($searchsdate){
	$sDate = $searchsdate." 00:00:00";
}
if($searchedate){
	#$arrEdate = explode("/",$searchedate);
	#$eDate = $arrEdate[2]."-".$arrEdate[0]."-".$arrEdate[1]." 59:59:59";
	$eDate = $searchedate." 23:59:59";
}
if($searchsdate){	
	if($searchedate){
		$wheresql .=" and A.wdate >= '".$sDate."' and A.wdate <= '".$eDate."' ";
	}else{
		$wheresql .=" and A.wdate >= '".$sDate."' ";
	}
}else if($searchedate){
	$wheresql .=" and A.wdate <= '".$eDate."' ";
}
################################ 날짜 검색 ############################//ED

$offset = ($page-1)*$rows;
if($sort=="idx"){
	$orderby = " order by A.".$sort." ".$order;	
}else{
	$orderby = " order by A.".$sort." ".$order.", A.idx desc";
}

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrBoardList = getJsonListFile($boardid, $rows, $offset, $orderby, $wheresql);

//DB해제
SetDisConn($dblink);

echo json_encode($arrBoardList);

?>