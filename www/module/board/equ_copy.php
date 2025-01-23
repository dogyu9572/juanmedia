<?php

include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$estNum = $_REQUEST['idx'];

getEquCopy($estNum);

SetDisConn($dblink);

jsGo('/backoffice/module/board/board_view.php?boardid=equ', '', '');

