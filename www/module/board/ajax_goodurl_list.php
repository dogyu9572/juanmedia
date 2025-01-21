<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_REQUEST['bid']=="tbl_member"){		## 주소록 > 회원리스트
	$subQuery = " AND idx in (".$_REQUEST['idx'].") ";
	$arrBoardList = getMemberList ("", "", "", 0, 0, $subQuery);

	for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
		echo "<input type=\"hidden\" name=\"".$_REQUEST['fname']."[]\" value=\"".$arrBoardList["list"][$i]['idx']."\">";
		echo "<tr><td>".($i+1)."</td>";
		echo "<td>".$arrBoardList["list"][$i]['user_name']."(".$arrBoardList["list"][$i]['user_id'].")</td>";
		echo "<td>".$arrBoardList["list"][$i]['mobile']."</td>";
		echo "<td>".$arrBoardList["list"][$i]['email'].$arrBoardList["list"]["total"]."</td>";
		echo "<td><a href=\"javascript:void(0);\" onclick=\"fnAddDel('".$arrBoardList["list"][$i]['idx']."','".$_REQUEST['fname']."')\" class=\"btn del\" style=\"display: inline-block;\">삭제</a></td></tr>";
	}
}else if($_REQUEST['bid']=="contact"){	## 쿠폰 > 주소록
	$subQuery = " AND A.idx in (".$_REQUEST['idx'].") ";
	$arrBoardList = getBoardListBaseNFile($_REQUEST['bid'], "", "", "", 0, 0, $subQuery);

	for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
		$arrCnt = explode(",",$arrBoardList["list"][$i]['joinidxs']);
		
		$imgsrc[$i] = "/uploaded/board/".$_REQUEST['bid']."/".$arrBoardList["list"][$i]['re_name'];
		echo "<input type=\"hidden\" name=\"".$_REQUEST['fname']."[]\" value=\"".$arrBoardList["list"][$i]['idx']."\">";
		echo "<tr><td>".($i+1)."</td>";
		echo "<td>".$arrBoardList["list"][$i]['subject']."</td>";
		echo "<td>".count($arrCnt)."</td>";
		echo "<td>".$arrBoardList["list"][$i]['wdate']."</td>";
		echo "<td><a href=\"javascript:void(0);\" onclick=\"fnAddDel('".$arrBoardList["list"][$i]['idx']."','".$_REQUEST['fname']."')\" class=\"btn del\" style=\"display: inline-block;\">삭제</a></td></tr>";
	}
}else if($_REQUEST['bid']=="tbl_category"){	## 쿠폰 > 카테고리
	$subQuery = " AND cat_no in (".$_REQUEST['idx'].") ";
	$arrBoardList = getFreeView("tbl_category", $subQuery, "*", 0, 0, "");

	for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
		echo "<input type=\"hidden\" name=\"".$_REQUEST['fname']."[]\" value=\"".$arrBoardList["list"][$i]['cat_no']."\">";
		echo "<tr><td>".($i+1)."</td>";
		echo "<td>".$arrBoardList["list"][$i]['cat_name']."</td>";
		echo "<td><a href=\"javascript:void(0);\" onclick=\"fnAddDel('".$arrBoardList["list"][$i]['cat_no']."','".$_REQUEST['fname']."')\" class=\"btn del\" style=\"display: inline-block;\">삭제</a></td></tr>";
	}
}




//DB해제
SetDisConn($dblink);
?>