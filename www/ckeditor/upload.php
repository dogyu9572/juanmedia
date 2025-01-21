<?
ini_set("session.gc_maxlifetime", 10800);
ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

session_cache_limiter("nocache, must_revalidate");
session_set_cookie_params(0, "/");
session_start();

header("Content-Type: text/html; charset=utf-8");
header("Expires: Mon, 26 Jul 1991 00:00:00 GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("P3P: CP='NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE'");

/////////////////////////////////////////////////////////////////////////////////////////////////본문

$file = $_FILES['upload'];
$owner = $_REQUEST['owner'];
$type = $_REQUEST['type'];
$editor = $_REQUEST['CKEditorFuncNum'];

if ($_FILES['upload']['error'] == 0){
	//확장자 검사후 파일이름 생성
	$filename = $_FILES['upload']['name'];
	$attach_ext = explode(".",$filename);
	$extension = $attach_ext[sizeof($attach_ext)-1];
	$extension = strtolower($extension);		    
	$filerename = md5(mktime()) . "." . $extension;
	$filesize = $_FILES['upload']['size'];
	$filetype = $_FILES['upload']['type'];
	$sitepath = "/uploaded/webedit/".$type."/". date("Ym");
	$upload_path = $_SERVER['DOCUMENT_ROOT'] . $sitepath;
	
		
	// 파일 확장자 검사
	if( strcmp($extension,"jpg") && strcmp($extension,"png") && strcmp($extension,"gif") ){
		echo "<script>window.parent.CKEDITOR.tools.callFunction(" . $editor . ", '', '이미지 파일만 업로드 가능합니다.');</script>";
		exit;
	}	

	umask(0);
	if (!is_dir($upload_path)) {
		mkdir($upload_path, 0777, true);
	}

	if (is_uploaded_file($_FILES['upload']['tmp_name'])) {	
		move_uploaded_file ($_FILES['upload']['tmp_name'],$upload_path."/".$filerename);		
	}		
//	echo "<script type=\"text/javascript\">window.parent.CKEDITOR.tools.callFunction('" . $editor . "', '" . $sitepath.$filerename . "', '');</script>";
	echo "{\"filename\" : \"[".$filerename."]\", \"uploaded\" : 1, \"url\":\"".$sitepath."/".$filerename."\"}";
}else{
	echo "<script>window.parent.CKEDITOR.tools.callFunction(" . $editor . ", '', '이미지 파일만 업로드 가능합니다.');</script>";
}

/*
$result = file_upload($file, $type);

if ($editor) {
    if (isset($result['error'])) {
        echo $result['error'];
    } else {
        echo "<script>window.parent.CKEDITOR.tools.callFunction(" . $editor . ", '" . $result['file_path'] . "');</script>";
    }
}else{
	$path = $type . "/" . date("Ym");
	$upload_path = $GLOBALS["_SITE"]["UPLOADED_DATA"]."/webedit/" . $path;
	echo "<script>window.parent.CKEDITOR.tools.callFunction(" . $editor . ", '', '이미지 파일만 업로드 가능합니다.');</script>";
}


function file_upload($file, $type) {
	if ($type == "images" || $type == "photo") {
		$allowed_ext = array("jpg", "jpeg", "png", "gif");
	} else {
		$allowed_ext = array("jpg", "jpeg", "png", "gif", "zip", "pdf", "ppt", "pptx", "doc", "docx", "xls", "xlsx", "hwp", "txt", "mp3", "wav", "avi", "mpg", "mpeg", "mpe", "flv", "rm", "mov");
	}

	$ext = strtolower(array_pop(explode(".", $file['name'])));
	$path = $type . "/" . date("Ym");
	$upload_path = $GLOBALS["_SITE"]["UPLOADED_DATA"]."/webedit/" . $path;
	$upload_file_name = mktime() . sprintf("%02d", mt_rand(1, 99)) . "." . $ext;
	$file_path = $GLOBALS["_SITE"]["UPLOADED_DATA"]."/webedit/" . $path . "/" . $upload_file_name;

	umask(0);
	if (!is_dir($upload_path)) {
		mkdir($upload_path, 0777, true);
	}

	if (20971520 < $file['size']) {
		$result['error'] = "업로드 파일 최대 사이즈는 20MByte 입니다";
	} else if(!in_array($ext, $allowed_ext)) {
		$result['error'] = "업로드 할 수 없는 파일형식 입니다";
	} else if (!@move_uploaded_file($file['tmp_name'], $upload_path . "/" . $upload_file_name)) {
		$result['error'] = "업로드에 실패하였습니다";
	} else {
		$result['file_path'] = $file_path;
	}
}
*/
?>