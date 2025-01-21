<?
session_start();
header("Content-Type: text/html; charset=euc-kr");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/memo/memo.lib.php");

//DB����
$dblink = SetConn($_conf_db["main_db"]);

$id = $_REQUEST[id];

//�Խ��� ����
$memoInfo = getmemoInfo($id);


if($id && $memoInfo["total"] > 0){
	//�˸����� �ش� ���� ������Ʈ
	setMemoNotifiy($id);
?>
<table width=200 height=200 border=1 align=center>

<tr><td><?=$id?> �Կ��� ������ �����Ͽ����ϴ�.</td></tr>
<tr><td><a href="javascript:LayerHideMemo();">�ݱ�</a></td></tr>
</table>
<?
}else{
	echo "0";
}
//DB����
SetDisConn($dblink);
?>
