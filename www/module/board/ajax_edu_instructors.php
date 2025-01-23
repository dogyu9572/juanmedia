<?php

include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrUserList = getBoardListBase("edu_instructors", "", "idx", $_REQUEST['mid'], 0, 0, $subQuery = "", $orderby = "");

SetDisConn($dblink);

echo  $arrUserList['list'][0]['name'] . "|" . $arrUserList['list'][0]['tel'] . "|" . $arrUserList['list'][0]['email']. "|" . $arrUserList['list'][0]['idx'];