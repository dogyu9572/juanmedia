<?
session_start();
setlocale(LC_CTYPE, 'ko_KR.eucKR');  
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>레이블 젯 관리자</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?
	
	if ($_FILES[upload][error] == 0){
		    //확장자 검사후 파일이름 생성
		    $filename = $_FILES[upload][name];
		    $attach_ext = explode(".",$filename);
		    $extension = $attach_ext[sizeof($attach_ext)-1];
		    $extension = strtolower($extension);		    
		    $filerename = md5(mktime()) . $i . ".txt";
	  		$filesize = $_FILES[upload][size];
	  		$filetype = $_FILES[upload][type];
			
		    // 파일 확장자 검사
		    if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
				echo "<script>
				alert('업로드 불가능한 파일입니다.');
				self.close();
				</script>";
				exit;
		    }
			
			if (is_uploaded_file($_FILES[upload][tmp_name])) {	
				move_uploaded_file ($_FILES[upload][tmp_name], "./csv/".$filerename);
			}
	}
	

	// 6.에러가 존재하는지 체크
	if ($_FILES['upload']['error'] > 0) {
		echo '<p>파일 업로드 실패 이유: <strong>';
	
		// 실패 내용을 출력
		switch ($_FILES['upload']['error']) {
			case 1:
				echo 'php.ini 파일의 upload_max_filesize 설정값을 초과함(업로드 최대용량 초과)';
				break;
			case 2:
				echo 'Form에서 설정된 MAX_FILE_SIZE 설정값을 초과함(업로드 최대용량 초과)';
				break;
			case 3:
				echo '파일 일부만 업로드 됨';
				break;
			case 4:
				echo '업로드된 파일이 없음';
				break;
			case 6:
				echo '사용가능한 임시폴더가 없음';
				break;
			case 7:
				echo '디스크에 저장할수 없음';
				break;
			case 8:
				echo '파일 업로드가 중지됨';
				break;
			default:
				echo '시스템 오류가 발생';
				break;
		} // switch
		
		echo '</strong></p>';
		
	} // if
	
	// 7.임시파일이 존재하는 경우 삭제
	if (file_exists ($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name']) ) {
		unlink ($_FILES['upload']['tmp_name']);
	}
	
	//업로드가 완료되면 서버에서 파일을 읽음
	$fp = fopen("./csv/".$filerename,"r");

	$goods = array();

	while ($row = fgetcsv($fp)){
		$goods[] = $row;
	}

	//DB연결
	$dblink = SetConn($_conf_db["main_db"]);

	//전체 카테고리 가져오기
	$arrAllCategory = getCategoryNameAll();

	for ($i=0; $i<sizeof($goods);$i++) {

			if($goods[$i]['4']) {
				$catcode[$i] = "1/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['2'])]."/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['3'])]."/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['4'])]."/";
				$catno[$i] = $arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['4'])];
			} else if($goods[$i]['3']) {
				$catcode[$i] = "1/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['2'])]."/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['3'])]."/";
				$catno[$i] = $arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['3'])];
			} else if($goods[$i]['2']) {
				$catcode[$i] = "1/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['2'])]."/";
				$catno[$i] = $arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['2'])];
			}
			
			$sql = "INSERT INTO tbl_shop_good SET
				cat_no='".$catno[$i]."', 
				cat_code='".$catcode[$i]."', 
				g_code='".iconv("euc-kr","utf-8",$goods[$i]['0'])."', 
				g_name='".iconv("euc-kr","utf-8",$goods[$i]['5'])."', 
				pan_color='".iconv("euc-kr","utf-8",$goods[$i]['11'])."', 
				pages='".iconv("euc-kr","utf-8",$goods[$i]['12'])."', 
				mokcha='".iconv("euc-kr","utf-8",$goods[$i]['13'])."', 
				author_text='".iconv("euc-kr","utf-8",$goods[$i]['14'])."', 
				movie_url='".iconv("euc-kr","utf-8",$goods[$i]['15'])."', 
				author_name='".iconv("euc-kr","utf-8",$goods[$i]['16'])."', 
				published_text='".iconv("euc-kr","utf-8",$goods[$i]['17'])."', 
				brand='".iconv("euc-kr","utf-8",$goods[$i]['18'])."', 
				model='".iconv("euc-kr","utf-8",$goods[$i]['19'])."', 
				isbn='".iconv("euc-kr","utf-8",$goods[$i]['20'])."', 
				memo='".iconv("euc-kr","utf-8",$goods[$i]['21'])."', 
				madein='".iconv("euc-kr","utf-8",$goods[$i]['22'])."', 
				vendor='".iconv("euc-kr","utf-8",$goods[$i]['23'])."', 
				movie='".iconv("euc-kr","utf-8",$goods[$i]['24'])."', 
				cdrom='".iconv("euc-kr","utf-8",$goods[$i]['25'])."', 
				sale_price='".$goods[$i]['26']."', 
				price='".$goods[$i]['27']."', 
				point='".$goods[$i]['28']."', 
				is_show='".$goods[$i]['29']."', 
				contents='".iconv("euc-kr","utf-8",$goods[$i]['30'])."', 
				wdate=now()
			";
			//echo $sql."<br>";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
			
			$sql = "INSERT INTO tbl_shop_good_cat set 
				g_idx='".$insert_idx."',
				cat_no='".$catno[$i]."',
				cat_code='".$catcode[$i]."'
			";
			mysqli_query($GLOBALS['dblink'], $sql);

			if($goods[$i]['6']) {	//BABY CARE
				$sql = "INSERT INTO tbl_shop_good_cat set 
					g_idx='".$insert_idx."',
					cat_no='".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['6'])]."',
					cat_code='2/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['6'])]."/'
				";
				mysqli_query($GLOBALS['dblink'], $sql);
			}
			if($goods[$i]['7']) {	//LIVING
				$sql = "INSERT INTO tbl_shop_good_cat set 
					g_idx='".$insert_idx."',
					cat_no='".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['7'])]."',
					cat_code='3/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['7'])]."/'
				";
				mysqli_query($GLOBALS['dblink'], $sql);
			}
			if($goods[$i]['8']) {	//FASHION
				$sql = "INSERT INTO tbl_shop_good_cat set 
					g_idx='".$insert_idx."',
					cat_no='".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['8'])]."',
					cat_code='4/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['8'])]."/'
				";
				mysqli_query($GLOBALS['dblink'], $sql);
			}
			if($goods[$i]['9']) {	//VEHICLE
				$sql = "INSERT INTO tbl_shop_good_cat set 
					g_idx='".$insert_idx."',
					cat_no='".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['9'])]."',
					cat_code='5/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['9'])]."/'
				";
				mysqli_query($GLOBALS['dblink'], $sql);
			}
			if($goods[$i]['10']) {	//TOY
				$sql = "INSERT INTO tbl_shop_good_cat set 
					g_idx='".$insert_idx."',
					cat_no='".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['10'])]."',
					cat_code='6/".$arrAllCategory[iconv("euc-kr","utf-8",$goods[$i]['10'])]."/'
				";
				mysqli_query($GLOBALS['dblink'], $sql);
			}
	}
	//DB해제
	SetDisConn($dblink);
	unlink ("./csv/".$filerename);
	
	echo"<script type='text/javascript'>alert('처리 되었습니다');opener.location.href='good.php';self.close();</script>";
?>
</body>
</html>