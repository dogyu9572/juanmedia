<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

// 해당폴더의 모든 파일을 복사
function recursive_copy($src,$dst){
	$oldumask = umask(0);
	$dir = opendir($src);
	@mkdir($dst, 0777, true);
	
	while(false !== ($file = readdir($dir)) ){
		if(( $file != '.' ) && ( $file != '..' )){
			if( is_dir($src . '/' . $file) ){
				recursive_copy($src.'/'.$file,$dst.'/'.$file);
			}else{
				copy($src.'/'.$file,$dst.'/'.$file);
				chmod($src.'/'.$file,$dst.'/'.$file, 0777);
				//	chown($src.'/'.$file,$dst.'/'.$file, "root");
			}
		}
	}
	closedir($dir);
	umask($oldumask);
	return true;
}

if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

if($_POST['evnMode']=="createBBS"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$boardid = str_replace(".","",str_replace("/","", trim($_REQUEST['id'])));
	$makeRS = makeBoard($boardid);

	if($makeRS==true){
		//디렉토리 생성
		if(rmkdir($_SITE["BOARD_DATA"] . "/" . $boardid, 0777)){
			jsGo("board.php","","게시판을 생성 하였습니다.");
		}else{
			jsGo("board.php","","게시판 DB정보는 생성하였으나 첨부파일 업로드 디렉토리 작성 실패");
		}
	}else{
		jsMsg("게시판 생성에 실패 하였습니다.");
		//jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}else if($_POST['evnMode']=="createForder"){
	
	$src= $_SERVER ['DOCUMENT_ROOT']."/module/board/skin/def/".$_POST['f_skin'];
	$dst= $_SERVER ['DOCUMENT_ROOT']."/module/board/skin/".$_POST['nfName'];
	$editRS = recursive_copy($src,$dst);
	
	if($editRS==true){
		jsGo("board.php","","스킨을 생성 하였습니다.");
	}else{
		jsMsg("스킨 생성에 실패 하였습니다.");
//		jsHistory("-1") ;
	}


}else if($_POST['evnMode']=="editBBS"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$editRS = editBoard($_POST);
	
	if($editRS==true){
		jsGo("board.php","","게시판 정보를 수정 하였습니다.");
	}else{
		jsMsg("게시판 수정에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);

}else if($_POST['evnMode']=="deleteBBS"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);
	
	//게시판 정보 가져오기
	$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["board_info"], $_POST['idx']);

	$editRS = deleteBoard($_POST['idx']);
	
	if($editRS==true && trim($arrInfo["list"][0]['boardid']) !=""){
		//파일삭제
		rrmdir ($_SITE["BOARD_DATA"] . "/" . trim($arrInfo["list"][0]['boardid']));
		//위 함수가 하위에 파일이 없으면 디렉토리를 삭제하지 못하는 버그로 아래줄 추가함
		@rmdir ($_SITE["BOARD_DATA"] . "/" . trim($arrInfo["list"][0]['boardid']));
		jsGo("board.php","","게시판을 삭제 하였습니다.");
	}else{
		jsMsg("게시판 삭제에 실패 하였습니다.");
		jsHistory("-1") ;
	}

	//DB해제
	SetDisConn($dblink);
}else if($_POST['evnMode']=="setHeader"){
	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	$arrData = array();

	$editRS = editBoardHeader($_POST["boardid"]);
	
	if($editRS==true){
		$arrData["success"] = true;
	}else{
		$arrData["success"] = false;
	}

	//DB해제
	SetDisConn($dblink);

	echo json_encode($arrData);

}
?>