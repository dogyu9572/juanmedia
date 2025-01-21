<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);


	$arrGidx = explode("|",$_REQUEST['gidx']);
	$arrCnt = count($arrGidx);
	for($i=0;$i<$arrCnt;$i++){		
		$updateQuery = "update tbl_banner set b_sort='".($arrCnt-$i-1)."' where idx='".$arrGidx[$i]."' ";
		//	echo $updateQuery;
		getFreeQueryCud($updateQuery);
	}


SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>true