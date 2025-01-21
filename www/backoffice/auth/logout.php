<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/lib/html.inc.php";

session_destroy();

jsGo("/backoffice/index.php","top","로그아웃 되었습니다.");
?>