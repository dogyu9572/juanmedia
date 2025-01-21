<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");

$src_file = $_SERVER['DOCUMENT_ROOT'] . "/uploaded/member/" . $_GET['file'];

if($_GET['file']) {
	fileDownload($src_file, iconv("UTF-8","EUC-KR",$_GET['rename']));	

}else{
	jsMsg('�ش� ������ �����ϴ�.');
	selfClose();
}
?>