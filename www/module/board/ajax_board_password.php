<?php
session_start();
header("Content-Type: text/plain; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

// DB 연결
$dblink = SetConn($_conf_db["main_db"]);

$checkRS = unlockBoardArticle($_POST['boardid'], $_POST['idx'], $_POST['password']);

// Plain text 형식으로 응답
if ($checkRS) {
    echo "true";
} else {
    echo "false";
}

// DB 해제
SetDisConn($dblink);
?>