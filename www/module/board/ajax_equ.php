<?php


include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrUserList = getBoardListBase("equ", "", "idx", $_REQUEST['mid'], 0, 0, $subQuery = "", $orderby = "");

SetDisConn($dblink);

echo  $arrUserList['list'][0]['subject'] . "|" . $arrUserList['list'][0]['fee'] . "|" . $arrUserList['list'][0]['idx'] . "|" . $arrUserList['list'][0]['category1'] . "|" . $arrUserList['list'][0]['category2'];