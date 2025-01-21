<?
/*********************************** 배너관리 *************************************/
//배너파일 등록
function insertSubBanner(){
	$tbl = "tbl_sub_banner";
	//이미지파일 처리

	if ($_FILES['image_file']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['image_file']['name'];
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$filerename = md5(mktime()) . "." . $extension;
		$filesize = $_FILES['image_file']['size'];
		$filetype = $_FILES['image_file']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {	
			move_uploaded_file ($_FILES['image_file']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/sub_banner/".$filerename);
		}
	}


	$sql = "insert into ".$tbl." SET 
		b_subject= '".$_POST['b_subject']."',
		b_image= '".$filerename."',
		b_url= '".$_POST['b_url']."',
		b_lang= ".$_POST['b_lang'].",
		b_target = '".$_POST['b_target']."',
		b_show = '".$_POST['b_show']."',
		b_sort = '".$_POST['b_sort']."',
		b_type = '".$_POST['b_type']."',
		b_date = now()
	";


	$rsf = mysqli_query($GLOBALS['dblink'], $sql);
	
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//배너파일 수정
function updateSubBanner($idx){
	$tbl = "tbl_sub_banner";

	//이미지파일 처리
	$arrInfo = getArticleInfo($tbl, $idx);

	if ($_FILES['image_file']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['image_file']['name'];
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$filerename = md5(mktime()) . "." . $extension;
		$filesize = $_FILES['image_file']['size'];
		$filetype = $_FILES['image_file']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['image_file']['tmp_name'])) {	
			@unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/sub_banner/".$arrInfo["list"][0]['b_image']);
			move_uploaded_file ($_FILES['image_file']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/sub_banner/".$filerename);
		}
	}else{
		$filerename = $arrInfo["list"][0]['b_image'];
	}


	$sql = "UPDATE ".$tbl." SET 
		b_subject= '".$_POST['b_subject']."',
		b_image= '".$filerename."',
		b_url= '".$_POST['b_url']."',
		b_lang= ".$_POST['b_lang'].",
		b_target = '".$_POST['b_target']."',
		b_show = '".$_POST['b_show']."',
		b_sort = '".$_POST['b_sort']."',	
		b_type = '".$_POST['b_type']."'
		WHERE idx = '$idx'
	";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);

	if($rsf){
		return true;
	}else{
		return false;
	}
}


//배너파일 삭제
function deleteSubBanner($idx){
	$tbl = "tbl_sub_banner";

	//이미지파일 처리
	$arrInfo = getArticleInfo($tbl, $idx);

	@unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/sub_banner/".$arrInfo["list"][0]['b_image']);
	

	$sql = "DELETE FROM ".$tbl." WHERE idx = '$idx' ";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);

	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

function getSubBannerList($scale, $offset=0){
	$tbl = "tbl_sub_banner";

    $sql = "SELECT * FROM ".$tbl." WHERE 1=1 ";
	
	if($_REQUEST['b_type']!="") {
		$sql .= " and b_type='".$_REQUEST['b_type']."' ";
	}
	if(!isset($_REQUEST['st'])) {
		$_REQUEST['st'] = "2";
	}
	if($_REQUEST['st'] == "1") {
		$sql .= " order by b_sort desc, idx desc ";
	}	else {
		$sql .= " order by idx desc ";
	}
	//echo $sql;

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

	//echo $sql;

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

				//scale 0 으로 지정시에는 전체 가져옴
				if($scale > 0){
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
		$list['list']['total'] = 0;
    }
    
    return $list;
}

function getSubBannerListNShow($scale, $offset=0,$b_lang=0){
	$tbl = "tbl_sub_banner";

    $sql = "SELECT * FROM ".$tbl." WHERE 1=1 and b_lang=$b_lang ";
	
	if($_REQUEST['b_type']!="") {
		$sql .= " and b_type='".$_REQUEST['b_type']."' ";
	}
	if(!isset($_REQUEST['st'])) {
		$_REQUEST['st'] = "2";
	}
	if($_REQUEST['st'] == "1") {
		$sql .= " order by b_sort desc, idx desc ";
	}	else {
		$sql .= " order by idx desc ";
	}
	//echo $sql;

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

	//echo $sql;

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

				//scale 0 으로 지정시에는 전체 가져옴
				if($scale > 0){
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
		$list['list']['total'] = 0;
    }
    
    return $list;
}

//배너카운트 수정
function setSubBannerCount($idx){
	$tbl = "tbl_sub_banner";

	$sql = "UPDATE ".$tbl." SET 
		b_hit = b_hit + 1
		WHERE idx = '$idx'
	";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);

	if($rsf){
		return true;
	}else{
		return false;
	}
}
?>