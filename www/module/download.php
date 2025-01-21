<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
	$src_file = $_SERVER['DOCUMENT_ROOT'] . "/pub/doc/";

	$filepath = $_GET['filepath'];
	$filename = $_GET['filename'];
	$src_file = $src_file.$filepath;

	fileDownload($src_file, iconv("UTF-8","EUC-KR",$filename));	
	
?>