<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrFile = getArticleFileInfoImage(mysqli_real_escape_string($GLOBALS['dblink'], $_GET[boardid]), mysqli_real_escape_string($GLOBALS['dblink'], $_GET[idx]));

if($arrFile["total"] > 0){
	$src_file = $_SITE["BOARD_DATA"] . "/" . $arrFile["list"][0][boardid] . "/" . $arrFile["list"][0][re_name];

	if(!$_REQUEST[mode]){	

		//다운로드 수 업데이트
		$sql  = "UPDATE " .$GLOBALS["_conf_tbl"]["board_files"]." SET ";
		$sql .= " download = download + 1 ";
		$sql .= "WHERE idx = '".$arrFile["list"][0][idx]."' ";
		mysqli_query($GLOBALS['dblink'], $sql);

		if($arrFile["list"][0][ext]=="swf"){
			$size = GetImageSize($src_file);
			$width = $size[0]+10;
			$height = $size[1]+28;
			echo"
			<script language=javascript> 
			  resizeTo($width,$height)
			  moveTo(0,0)
			</script>
			<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
			<div align=center>	
			<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,2,0' $size[3]>
			  <param name=movie value='$src_file'>
			  <param name=quality value=high>
			  <param name=BGCOLOR value=''>
			  <param name='SCALE' value='exactfit'>
			  <embed src='$src_file' quality=high pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash' type='application/x-shockwave-flash' scale='exactfit' bgcolor='' $size[3]>
			  </embed> 
			</object>
			</div>
			";
			exit;
		}elseif($arrFile["list"][0][ext]=="gif" || $arrFile["list"][0][ext]=="jpg" || $arrFile["list"][0][ext]=="png" || $arrFile["list"][0][ext]=="bmp")
		{
			$size = GetImageSize($src_file);
			$width = $size[0]+30;
			$height = $size[1]+55;
			echo"
			<script language=javascript> 
			  resizeTo($width,$height)
			  moveTo(0,0)
			</script>
			";		
			
			$go_url = $PHP_SELF . "?mode=img_view&boardid=" . $boardid . "&no=" . $no;
			jsGo($_SERVER["PHP_SELF"]."?mode=img_view&boardid=".$_GET[boardid]."&b_idx=".$_GET[b_idx]."&idx=".$_GET[idx],"","");
			exit;
		}else{//일반파일일때
			// utf-8로 된 싸이트 일경우
			fileDownload($src_file, iconv("UTF-8","EUC-KR",$arrFile["list"][0][ori_name]));	

		}
	}elseif($_REQUEST[mode]=='img_view'){
		$src_file = "/uploaded/board/" . $arrFile["list"][0][boardid] . "/" . $arrFile["list"][0][re_name];
		echo"<img src='$src_file' border='0' style='max-width:100px;max-height:80px;'>";
		
		

	}
}else{
	//jsMsg('해당 파일이 없습니다.');
	selfClose();
}
//DB해제
SetDisConn($dblink);
?>
