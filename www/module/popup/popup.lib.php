<?
/*********************************** 팝업관리 *************************************/
//팝업 등록하기
function insertPopup(){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["popup"];

	//이미지파일 처리
	if ($_FILES['photo_file']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['photo_file']['name'];
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$filerename = md5(mktime()) . "." . $extension;
		$filesize = $_FILES['photo_file']['size'];
		$filetype = $_FILES['photo_file']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['photo_file']['tmp_name'])) {	
			move_uploaded_file ($_FILES['photo_file']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"] . "/popup/".$filerename);
		}
	}

	//이미지파일 처리
	if ($_FILES['photo_file_mo']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['photo_file_mo']['name'];
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$filerenameMo = "m_".md5(mktime()) . "." . $extension;
		$filesize = $_FILES['photo_file_mo']['size'];
		$filetype = $_FILES['photo_file_mo']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['photo_file_mo']['tmp_name'])) {	
			move_uploaded_file ($_FILES['photo_file_mo']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"] . "/popup/".$filerenameMo);
		}
	}

	if($_POST['p_mode']!="P"){		$_POST['p_mode'] = "L";	}
	if($_POST['p_type']!="IMG"){	$_POST['p_type'] = "HTML";	}
	if($_POST['p_target']!="O"){	$_POST['p_target'] = "B";	}

	// 테이블에 입력
	$sql = "INSERT INTO ".$tbl." set 
		category='".$_POST['category']."',
		device='".$_POST['device']."',
		subject='".$_POST['subject']."',
		contents='".$_POST['contents']."',
		width='".$_POST['width']."',
		height='".$_POST['height']."',
		p_mode='".$_POST['p_mode']."',
		p_type='".$_POST['p_type']."',
		p_image='".$filerename."',
		m_image='".$filerenameMo."',
		p_url='".$_POST['p_url']."',
		p_target='".$_POST['p_target']."',
		s_date='".$_POST['s_date']."',
		e_date='".$_POST['e_date']."',
		pop_top='".$_POST['pop_top']."',
		pop_left='".$_POST['pop_left']."',				
		w_date=now()
	";
//echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}



//팝업 수정하기
function editPopup($idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["popup"];

	//이미지파일 처리
	$arrInfo = getArticleInfo($tbl, $idx);



	if ($_FILES['photo_file']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['photo_file']['name'];
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$filerename = md5(mktime()) . "." . $extension;
		$filesize = $_FILES['photo_file']['size'];
		$filetype = $_FILES['photo_file']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['photo_file']['tmp_name'])) {	
			unlink($GLOBALS["_SITE"]["UPLOADED_DATA"] . "/popup/".$arrInfo["list"][0]['p_image']);
			move_uploaded_file ($_FILES['photo_file']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"] . "/popup/".$filerename);
		}
	}else{
		$filerename = $arrInfo["list"][0]['p_image'];
	}

	//이미지파일 처리
	if ($_FILES['photo_file_mo']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['photo_file_mo']['name'];
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$filerenameMo = "m_".md5(mktime()) . "." . $extension;
		$filesize = $_FILES['photo_file_mo']['size'];
		$filetype = $_FILES['photo_file_mo']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['photo_file_mo']['tmp_name'])) {	
			move_uploaded_file ($_FILES['photo_file_mo']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"] . "/popup/".$filerenameMo);
		}
	}else{
		$filerenameMo = $arrInfo["list"][0]['m_image'];
	}

	if($_POST['p_mode']!="P"){		$_POST['p_mode'] = "L";	}
	if($_POST['p_type']!="IMG"){	$_POST['p_type'] = "HTML";	}
	if($_POST['p_target']!="O"){	$_POST['p_target'] = "B";	}
	
	// 테이블에 입력
	$sql = "UPDATE ".$tbl." set 
		category='".$_POST['category']."',
		device='".$_POST['device']."',
		subject='".$_POST['subject']."',
		contents='".$_POST['contents']."',
		width='".$_POST['width']."',
		height='".$_POST['height']."',
		p_mode='".$_POST['p_mode']."',
		p_type='".$_POST['p_type']."',
		p_image='".$filerename."',
		m_image='".$filerenameMo."',
		p_url='".$_POST['p_url']."',
		p_target='".$_POST['p_target']."',
		s_date='".$_POST['s_date']."',
		e_date='".$_POST['e_date']."',
		pop_top='".$_POST['pop_top']."',
		pop_left='".$_POST['pop_left']."'		
		WHERE idx='".$_POST['idx']."'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($rs){
		return true;
	}else{
		return false;
	}

}

//팝업 삭제하기
function deletePopup($idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["popup"];

	//이미지파일 처리
	$arrInfo = getArticleInfo($tbl, $idx);

	//게시판 테이블에서 삭제
	$sql = "DELETE FROM ".$tbl." 
		WHERE idx='".$_POST['idx']."'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		@unlink($GLOBALS["_SITE"]["UPLOADED_DATA"] . "/popup/".$arrInfo["list"][0]['p_image']);
		return true;
	}else{
		return false;
	}
}

//팝업 가져오기
function getActivePopup(){
	$tbl = $GLOBALS["_conf_tbl"]["popup"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE s_date <= curdate() ";
	$sql .= "AND e_date >= curdate() ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;
}

//팝업 가져오기
function getActivePopupList($category,$device = 'pc'){
	$tbl = $GLOBALS["_conf_tbl"]["popup"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE s_date <= curdate() ";
	$sql .= "AND e_date >= curdate() ";
	//$sql .= "AND category = '$category' ";
	//$sql .= "AND device = '$device' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;
}

// 팝업 관리자 리스트
function getAdminPopupList($sw, $sk, $scale, $offset){
	$tbl = $GLOBALS["_conf_tbl"]["popup"];

	$sql_add = "";

	if($sk !=""){
		switch($sw){
			case "s":
				$sql_add .= "and A.subject like '%$sk%' ";
			break;
			case "c":
				$sql_add .= "and A.contents like '%$sk%' ";
			break;
			default :
				$sql_add .= "and (A.subject like '%$sk%' or A.contents like '%$sk%')";
			break;
		}
	}

	if($_GET["sdate"] != ""){
		$sql_add .= "and date_format(A.w_date,'%Y-%m-%d') >= '".mysqli_real_escape_string($GLOBALS["dblink"],$_GET["sdate"])."' ";
	}

	if($_GET["edate"] != ""){
		$sql_add .= "and date_format(A.w_date,'%Y-%m-%d') <= '".mysqli_real_escape_string($GLOBALS["dblink"],$_GET["edate"])."' ";
	}

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." as A ";
	$sql .= "WHERE 1=1 ".$sql_add;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
		$list['total'] = $total_rs;
		if($scale > 0){
			$sql .= " order by idx desc limit $offset,$scale ";
		}else{
			$sql .= " order by idx desc";						
		}
						
		//echo $sql;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		
		//echo $sql;
		//exit();

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
	}else{
		$list['total'] = 0;
	}
	return $list;
}
?>