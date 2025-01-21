<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_REQUEST['evnMode']=="best"){		## best 통일 mdp,keyword 사용안함
	$arrGidx = explode("|",$_REQUEST['gidx']);
	for($i=0;$i<count($arrGidx);$i++){		
		$updateQuery = "update tbl_shop_good_cat set orderNum='".$i."' where g_idx='".$arrGidx[$i]."' and cat_no='".$_REQUEST['catNo']."' ";
		//	echo $updateQuery;
		getFreeQueryCud($updateQuery);
	}
}else if($_REQUEST['evnMode']=="mdp"){

}else if($_REQUEST['evnMode']=="keyword"){

}

SetDisConn($dblink);

//echo $_REQUEST['gidx'];
?>true