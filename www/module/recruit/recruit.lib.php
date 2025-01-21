<?

//인재DB 리스트 - 관리자
function getRecruitListAdmin($scale, $offset=0){

	$sw = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']);
	$sk = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']);
	$s_date = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']);
	$e_date = mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']);
	
	if($sw=="all"){
		$que_where .= "AND (A.ur_name like '%$sk%' OR A.ur_name like '%$sk%') ";
	}else if($sw=="name"){
		$que_where .= "AND A.ur_name like '%$sk%' ";
	}
	
	if($s_date){
		$que_where .= "AND A.wdate >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND A.wdate <='$e_date 23:59:59' ";
	}
	
	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM tbl_recruit A ";
    $sql .= "WHERE 1=1  $que_where ORDER BY A.ur_idx DESC ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    // offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

			if($scale != "0"){
				$sql .= " limit $offset,$scale ";
			}
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);

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
	//echo $sql;

    return $list;
}


//기본정보 가져오기
function getRecruit01Info($id){

	$sql  = "SELECT * ";
	$sql .= "FROM tbl_recruit a ";
	$sql .= "WHERE ur_idx = '$id' ";
	
	//echo $sql;
	//exit;

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


//기본정보 입력
function insertInfo01(){

	$birth = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_birth']);
	$birth_year = substr($birth,0,4);
	$birth = AES_encrypt($birth);
	$tel = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_tel1'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_tel2'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_tel3']);
	$tel4 = substr($tel,-4);
	$tel = AES_encrypt($tel);
	$email = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_id']) . "@" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_domain']);
	$email = AES_encrypt($email);
	$addr1 = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_addr1']);
	$addr1 = AES_encrypt($addr1);
	$addr2 = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_addr2']);
	$addr2 = AES_encrypt($addr2);
	
    if (!$_SESSION["RECRUITIDX"]) {
        $sql = "INSERT INTO tbl_recruit set 
		ur_sta = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_sta'])."',
		ur_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_name'])."',
		ur_gen = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_gen'])."',
		ur_birth = '$birth',
		ur_birthy = '$birth_year',
		ur_nat = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_nat'])."',
		ur_tel = '$tel',
		ur_tel4 = '$tel4',
		ur_email = '$email',
		ur_zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_zip'])."',
		ur_addr1 = '$addr1',
		ur_addr2 = '$addr2',
		ur_veteran = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_veteran'])."',
		ur_handi = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_handi'])."',
		ur_milit = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit'])."',
		ur_milit_gb = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_gb'])."',
		ur_milit_sdt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_sy']).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_sm'])."',
		ur_milit_edt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_ey']).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_em'])."',
		ur_high = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_high'])."',
		ur_high_sdt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_high_sdt'])."',
		ur_high_edt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_high_edt'])."',
		ur_ip = '".$_SERVER['REMOTE_ADDR']."',
		wdate = now()
	";

        $rs = mysqli_query($GLOBALS['dblink'], $sql);
        $insert_idx = mysqli_insert_id($GLOBALS['dblink']);
        $total = mysqli_affected_rows($GLOBALS['dblink']);

        if ($total > 0) {
            $_SESSION["RECRUITIDX"] = $insert_idx;
            return true;
        } else {
            return false;
        }
    }else{
		$sql = "UPDATE tbl_recruit SET 
		ur_sta = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_sta'])."',
		ur_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_name'])."',
		ur_gen = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_gen'])."',
		ur_birth = '$birth',
		ur_birthy = '$birth_year',
		ur_nat = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_nat'])."',
		ur_tel = '$tel',
		ur_tel4 = '$tel4',
		ur_email = '$email',
		ur_zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_zip'])."',
		ur_addr1 = '$addr1',
		ur_addr2 = '$addr2',
		ur_veteran = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_veteran'])."',
		ur_handi = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_handi'])."',
		ur_milit = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit'])."',
		ur_milit_gb = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_gb'])."',
		ur_milit_sdt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_sy']).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_sm'])."',
		ur_milit_edt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_ey']).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_milit_em'])."',
		ur_ip = '".$_SERVER['REMOTE_ADDR']."',
		wdate = now()
		WHERE ur_idx = ".$_SESSION['RECRUITIDX'];
		$rsf = mysqli_query($GLOBALS['dblink'], $sql);

		if($rsf){
			return true;
		}else{
			return false;
		}
	}
}

//추가정보 리스트
function getRecruitAddList($urIdx,$op){

	$sql = "SELECT * FROM tbl_recruit_add WHERE ur_idx = ".$urIdx." AND ad_type='$op'";
	$sql .= " order by ad_seq ";
	//echo $sql;
	//exit;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
	for($i=0; $i < $total_rs; $i++){
		$list['list'][$i] = mysqli_fetch_assoc($rs);
	}
	$list['list']['total'] = $total_rs;

	if($op=="LIC"){
		//파일정보 가져오기
		$sql  = "SELECT * ";
		$sql .= "FROM tbl_recruit_files ";
		$sql .= "WHERE ur_idx = '".$urIdx."'";
		$sql .= " order by idx";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		
		if($total_rs > 0){
			$list['total_files'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
				$list['files'][$i] = mysqli_fetch_assoc($rs);
			}
		}else{
			$list['total_files'] = 0;
		}
	}

	return $list;
}

//학력정보 입력
function insertInfo02(){

	// 선 삭제, 후 신규 입력
	$sql = "DELETE FROM tbl_recruit_add WHERE ur_idx = ".$_SESSION["RECRUITIDX"]." AND ad_type='EDU' ";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;
	//exit;

	//고교 학력 정보 입력
	$sql = "UPDATE tbl_recruit SET 
		ur_high = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_high'])."',
		ur_high_sdt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_high_sy']).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_high_sm'])."',
		ur_high_edt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_high_sy']).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_high_sm'])."'
		WHERE ur_idx = ".$_SESSION['RECRUITIDX'];
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);

	//학력정보 입력
	$addText = $_POST['ad_name'];
    for ($i=0;$i<count($addText);$i++) {
        $sql = "INSERT INTO tbl_recruit_add set 
			ur_idx = '".$_SESSION['RECRUITIDX']."',
			ad_type = 'EDU',
			ad_seq = $i+1,
			ad_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_name'][$i])."',
			ad_ext1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_ext1'][$i])."',
			ad_ext2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_ext2'][$i])."',
			ad_ext3 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_jum1'][$i])."/".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_jum2'][$i])."',
			ad_yn = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_yn'][$i])."',
			ad_sdt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_sy'][$i]).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_sm'][$i])."',
			ad_edt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_ey'][$i]).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ad_em'][$i])."'
		";
        $rs = mysqli_query($GLOBALS['dblink'], $sql);
    }
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if ($total > 0) {
		return true;
	} else {
		return false;
	}

}

//경력정보 리스트
function getRecruitCareerList($urIdx){

	$sql = "SELECT * FROM tbl_recruit_career WHERE ur_idx = ".$urIdx;
	$sql .= " order by cr_seq ";
	//echo $sql;
	//exit;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
	for($i=0; $i < $total_rs; $i++){
		$list['list'][$i] = mysqli_fetch_assoc($rs);
	}
	$list['list']['total'] = $total_rs;
	return $list;
}

//경력정보 입력
function insertInfo03(){

	// 선 삭제, 후 신규 입력
	$sql = "DELETE FROM tbl_recruit_add WHERE ur_idx = ".$_SESSION["RECRUITIDX"]." AND ad_type IN('LNG') ";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;
	//exit;

	//기타정보 입력
	$addArr = ['LNG'];
    for ($arr=0;$arr<count($addArr);$arr++) {
        $addText = $_POST[$addArr[$arr].'_ad_name'];
        for ($i=0;$i<count($addText);$i++) {
            $sql = "INSERT INTO tbl_recruit_add set 
			ur_idx = '".$_SESSION['RECRUITIDX']."',
			ad_type = '$addArr[$arr]',
			ad_seq = $i+1,
			ad_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_name'][$i])."',
			ad_ext1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_ext1'][$i])."',
			ad_ext2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_ext2'][$i])."',
			ad_ext3 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_ext3'][$i])."',
			ad_sdt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_sy'][$i]).mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_sm'][$i])."',
			ad_edt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_ey'][$i]).mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_em'][$i])."'
		";
            $rs = mysqli_query($GLOBALS['dblink'], $sql);
        }
	}
	
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if ($total > 0) {
		return true;
	} else {
		return false;
	}

}

//기타정보 입력
function insertInfo04(){

	// 선 삭제, 후 신규 입력
	$sql = "DELETE FROM tbl_recruit_career WHERE ur_idx = ".$_SESSION["RECRUITIDX"];
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;
	//exit;

	//경력정보 입력
	$addText = $_POST['cr_name'];
    for ($i=0;$i<count($addText);$i++) {
        $sql = "INSERT INTO tbl_recruit_career set 
			ur_idx = '".$_SESSION['RECRUITIDX']."',
			cr_seq = $i+1,
			cr_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_name'][$i])."',
			cr_yn = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_yn'][$i])."',
			cr_sdt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_sy'][$i]).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_sm'][$i])."',
			cr_edt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_ey'][$i]).mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_em'][$i])."',
			cr_dept = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_dept'][$i])."',
			cr_job = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_job'][$i])."',
			cr_pos = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_pos'][$i])."',
			cr_salary = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_salary'][$i])."',
			cr_etc1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_etc1'][$i])."',
			cr_etc2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cr_etc2'][$i])."'
		";
        $rs = mysqli_query($GLOBALS['dblink'], $sql);
    }

	// 선 삭제, 후 신규 입력
	$sql = "DELETE FROM tbl_recruit_add WHERE ur_idx = ".$_SESSION["RECRUITIDX"]." AND ad_type IN('AWD','LIC','FLE') ";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;
	//exit;

	//기타정보 입력
	$addArr = ['AWD','LIC'];
    for ($arr=0;$arr<count($addArr);$arr++) {
        $addText = $_POST[$addArr[$arr].'_ad_name'];
        for ($i=0;$i<count($addText);$i++) {
            $sql = "INSERT INTO tbl_recruit_add set 
			ur_idx = '".$_SESSION['RECRUITIDX']."',
			ad_type = '$addArr[$arr]',
			ad_seq = $i+1,
			ad_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_name'][$i])."',
			ad_ext1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_ext1'][$i])."',
			ad_ext2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_ext2'][$i])."',
			ad_ext3 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_ext3'][$i])."',
			ad_sdt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_sy'][$i]).mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_sm'][$i])."',
			ad_edt = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_ey'][$i]).mysqli_real_escape_string($GLOBALS['dblink'], $_POST[$addArr[$arr].'_ad_em'][$i])."'
		";
            $rs = mysqli_query($GLOBALS['dblink'], $sql);
        }
	}


	
	//파일삭제 코딩 시작 - 삭제체크 한것만 처리
	if(isset($_POST['filedel'])){
		for($i=0;$i<count($_POST['filedel']);$i++){
			if($_POST['filedel'][$i]>0){
				$fileinfo = getRecruitFileInfo($_SESSION['RECRUITIDX'], $_POST['filedel'][$i]);
				//디비에서 파일정보 삭제
				mysqli_query($GLOBALS['dblink'], "DELETE FROM tbl_recruit_files WHERE ur_idx='".$_SESSION['RECRUITIDX']."' AND idx='".$fileinfo["list"][0]['idx']."' ");
				//디스크에서 파일 삭제
				@unlink($GLOBALS["_SITE"]["UPLOADED_DATA"] . "/"."recruit"."/".$fileinfo["list"][0]['re_name']);
			}
		}
	}
	//파일삭제 코딩 종료

	inputRecruitFiles(); //첨부파일 처리

	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if ($total > 0) {
		return true;
	} else {
		return false;
	}

}

//자기소개서 입력
function insertInfo05(){

	//고교 학력 정보 입력
	$sql = "UPDATE tbl_recruit SET 
		ur_etc1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_etc1'])."',
		ur_etc2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_etc2'])."',
		ur_etc3 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_etc3'])."',
		ur_etc4 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ur_etc4'])."'
		WHERE ur_idx = ".$_SESSION['RECRUITIDX'];
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);

	if ($_POST['evnMode']=="intOdkwd") {
        //임시저장 상태 변경
        $sql = "UPDATE tbl_recruit SET 
		ur_sta = '2'
		WHERE ur_idx = ".$_SESSION['RECRUITIDX'];
        $rsf = mysqli_query($GLOBALS['dblink'], $sql);
    }

	$total = mysqli_affected_rows($GLOBALS['dblink']);

	return true;

}

//첨부 파일처리
function inputRecruitFiles(){
	for($i=0;$i<count($_FILES['upfiles']['error']);$i++){
		if ($_FILES['upfiles']['error'][$i] == 0){
			$filename = $_FILES['upfiles']['name'][$i];
		    $attach_ext = explode(".",$filename);
		    $extension = $attach_ext[sizeof($attach_ext)-1];
		    $extension = strtolower($extension);		    
		    $filerename = $memo."_".md5(mktime()) . $i . "." . $extension;
	  		$filesize = $_FILES['upfiles']['size'][$i];
	  		$filetype = $_FILES['upfiles']['type'][$i];
			
		    // 파일 확장자 검사
		    if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
				jsMsg("not allowed file extension");
		        jsHistory("-1");
		    }
			
			if (is_uploaded_file($_FILES['upfiles']['tmp_name'][$i])) {	
				move_uploaded_file ($_FILES['upfiles']['tmp_name'][$i], $GLOBALS["_SITE"]["UPLOADED_DATA"] . "/"."recruit"."/".$filerename);
			}
			
			$sql = "insert into tbl_recruit_files set 
				ur_idx='".$_SESSION['RECRUITIDX']."',/* 글 번호 id*/
				ori_name='".$filename."',/*파일원본이름*/
				re_name='".$filerename."',/*md5로 변환된 파일이름*/
				type='".$filetype."',/*파일타입*/
				ext ='".$extension."',/*파일확장자*/
				size='".$filesize."',/*첨부파일 용량*/
				memo = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['FLE_add_name'][$i])."',
				ur_ip = '".$_SERVER['REMOTE_ADDR']."',
				wdate=now()
			";
			$rsf = mysqli_query($GLOBALS['dblink'], $sql);
		}
	}
}

//파일정보 가져오기
function getRecruitFileInfo($b_idx, $idx){
    $sql  = "SELECT * ";
    $sql .= "FROM tbl_recruit_files ";
    $sql .= "WHERE ur_idx = '$b_idx' ";
    $sql .= "AND idx = '$idx' ";
//	echo $sql;
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


//삭제
function deleteRecruit($idx){
	$sql = "DELETE FROM tbl_recruit WHERE ur_idx = '$idx' ";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);
	$sql = "DELETE FROM tbl_recruit_add WHERE ur_idx = '$idx' ";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);
	$sql = "DELETE FROM tbl_recruit_career WHERE ur_idx = '$idx' ";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);
	
	if($rsf){
		return true;
	}else{
		return false;
	}
}


?>
