<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";
if(!in_array("point_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="add"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	if($_POST['type']=="plus"){
		$RS = setPlusPoint(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[user_id]),mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[point]),mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[contents]));
	}else if($_POST['type']=="minus"){
		$RS = setMinusPoint(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[user_id]),mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[point]),mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[contents]));
	}
	
	//DB해제
	SetDisConn($dblink);

	if($RS > 0){
		jsGo("point_list.php","","정보를 저장하였습니다.");
	}else{
		jsMsg("정보 저장에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="addall"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	if($_POST['type']=="plus"){
		if($_POST['point_type']=="auto"){	## 전체회원 포인트 지급
			$arrMemberList = getArticleList("tbl_member", 0, 0, " where user_level < 80 ");
			for($i=0; $i < $arrMemberList['total']; $i++){
				$RS = setPlusPoint($arrMemberList['list'][$i]['user_id'], $_POST['point'], mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents']));
			}
		}else if($_POST['point_type']=="level"){	## 회원등급별 포인트 지급
			$arrMemberList = getArticleList("tbl_member", 0, 0, " where user_level = '".$_POST['member_level']."' ");
			for($i=0; $i < $arrMemberList['total']; $i++){
				$RS = setPlusPoint($arrMemberList['list'][$i]['user_id'], $_POST['point'], mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents']));
			}
		}else{	## 주소록
			$subQuery = " where idx in (".$_POST['addidxs'].") ";
			$arrBoardList = getArticleList("tbl_board_contact", 0, 0, $subQuery);
			for($i=0; $i < $arrBoardList['total']; $i++){
				$subQuery2 = " where idx in (".$arrBoardList["list"][$i]['joinidxs'].") ";
				$arrMemberList = getArticleList("tbl_member", 0, 0, $subQuery2);

				for($m=0; $m < $arrMemberList["list"]["total"]; $m++){									
					$RS = setPlusPoint($arrMemberList['list'][$m]['user_id'], $_POST['point'], mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents']));								
				}
			}
		}
	}else if($_POST['type']=="minus"){
		$RS = setMinusPoint(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[user_id]),mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[point]),mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[contents]));
	}

	//DB해제
	SetDisConn($dblink);

	if($RS > 0){
		jsGo("point_list.php","","포인트가 지급되었습니다.");
	}else{
		jsMsg("실패 하였습니다.");
		//jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="delete"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$RS2 = deletePoint(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[idx]));
	if($RS2 == true){
		if($_POST['returnURL']){
			jsGo($_POST['returnURL'],"","정상적으로 삭제 되었습니다.");
		}else{
			jsGo("point_list.php","","정상적으로 삭제 되었습니다.");
		}
	}else{
		jsGo("point_list.php","","삭제중 오류가 발생하였습니다.");
	}


	//DB해제
	SetDisConn($dblink);

}
?>