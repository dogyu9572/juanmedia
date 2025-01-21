<?session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/module/memo/memo.lib.php");

$dblink = SetConn($_conf_db["main_db"]);
if($_POST['evnMode']=="insert"){
	$RS = insertMemo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[to_id]),$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);//�޴»�����̵�,�����»�����̵�	

	if($RS==true){
		jsGo("memo_insert.php","","������ �����Ͽ����ϴ�.");
	}else{
		jsGo("memo_insert.php","","������ �����µ� �����Ͽ����ϴ�.");
	}
}
if($_GET[evnMode]=="delete"){	
	$RS = deleteMemo($idx,$type,$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);	

	if($RS==true){
		if($type!="save"){	jsGo("memo_list.php","","������ �����Ͽ����ϴ�.");	}else{	jsGo("memo_savelist.php","","������ �����Ͽ����ϴ�.");	}
	}else{
		jsGo("memo_list.php","","���� ������ �����Ͽ����ϴ�.");
	}
}
if($_POST['evnMode']=="save"){
	$RS = savereceivememoList($idx,$type);

	if($RS==true){
		jsGo("memo_savelist.php","","������ �����Կ� ��ҽ��ϴ�.");
	}else{
		jsGo("memo_view.php?idx=$idx&type=$type","","������ �����Կ� ���� ���Ͽ����ϴ�.");
	}
}
//DB����
SetDisConn($dblink);
?>