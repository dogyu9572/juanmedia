<?
/*********************************** ��ʰ��� *************************************/
//������� ���
function insertCourse(){
	$tbl = $GLOBALS["_conf_tbl"]["course"];
	//�̹������� ó��

	if ($_FILES[image_file][error] == 0){
		//Ȯ���� �˻��� �����̸� ����
		$filename = $_FILES[image_file][name];
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$filerename = md5(mktime()) . "." . $extension;
		$filesize = $_FILES[image_file][size];
		$filetype = $_FILES[image_file][type];
			
		// ���� Ȯ���� �˻�
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES[image_file][tmp_name])) {	
			move_uploaded_file ($_FILES[image_file][tmp_name],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/course/".$filerename);
		}
	}


	$sql = "insert into ".$tbl." SET 
		b_subject= '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_subject'])."',
		e_subject= '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['e_subject'])."',
		c_subject= '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['c_subject'])."',
		b_image= '".$filerename."',
		b_url= '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_url'])."',
		b_target = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_target'])."',
		b_show = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_show'])."',
		b_sort = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_sort'])."',
		b_type = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_type'])."',
		b_brand = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_brand'])."',
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

//������� ����
function updateCourse($idx){
	$tbl = $GLOBALS["_conf_tbl"]["course"];

	//�̹������� ó��
	$arrInfo = getArticleInfo($tbl, $idx);

	if ($_FILES[image_file][error] == 0){
		//Ȯ���� �˻��� �����̸� ����
		$filename = $_FILES[image_file][name];
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$filerename = md5(mktime()) . "." . $extension;
		$filesize = $_FILES[image_file][size];
		$filetype = $_FILES[image_file][type];
			
		// ���� Ȯ���� �˻�
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES[image_file][tmp_name])) {	
			@unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/course/".$arrInfo["list"][0][b_image]);
			move_uploaded_file ($_FILES[image_file][tmp_name],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/course/".$filerename);
		}
	}else{
		$filerename = $arrInfo["list"][0][b_image];
	}


	$sql = "UPDATE ".$tbl." SET 
		b_subject= '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_subject'])."',
		e_subject= '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['e_subject'])."',
		c_subject= '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['c_subject'])."',
		b_image= '".$filerename."',
		b_url= '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_url'])."',
		b_target = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_target'])."',
		b_show = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_show'])."',
		b_sort = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_sort'])."',
		b_brand = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_brand'])."',
		b_type = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['b_type'])."'
		WHERE idx = '$idx'
	";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);

	if($rsf){
		return true;
	}else{
		return false;
	}
}


//������� ����
function deleteCourse($idx){
	$tbl = $GLOBALS["_conf_tbl"]["course"];

	//�̹������� ó��
	$arrInfo = getArticleInfo($tbl, $idx);

	@unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/course/".$arrInfo["list"][0][b_image]);
	

	$sql = "DELETE FROM ".$tbl." WHERE idx = '$idx' ";
	$rsf = mysqli_query($GLOBALS['dblink'], $sql);

	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

function getMainCourseList($type){
	$tbl = $GLOBALS["_conf_tbl"]["course"];

    $sql = "SELECT * FROM ".$tbl." WHERE b_show='Y' AND b_type='$type' order by b_sort desc ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_num_rows($rs);

	$list['list']['total'] = $total;
		
	for($i=0; $i < $total; $i++){
		$list['list'][$i] = mysqli_fetch_assoc($rs);
	}

    return $list;
}

function getCourseList($scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["course"];

    $sql = "SELECT * FROM ".$tbl." WHERE 1=1 ";
	
	if($_REQUEST[b_type]) {
		$sql .= " and b_type='".$_REQUEST[b_type]."' ";
	}
	
	if($_REQUEST[st] == "1") {
		$sql .= " order by b_sort desc, idx desc ";
	}	else {
		$sql .= " order by idx desc ";
	}

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

	//echo $sql;

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // ������ �׺���̼� ������ ����.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    // offset �� ��ü �Խù������� ������ offset �� ��ü�Խù� - �������� ������ �� ���� offset ����
		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

				//scale 0 ���� �����ÿ��� ��ü ������
				if($scale > 0){
		    	$sql .= " limit $offset,$scale ";
				}
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);
		
		    // offset �� �̿��� limit �� ����� ����
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // ������ �׺���̼� ������ ����.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
    
    return $list;
}

//���ī��Ʈ ����
function setCourseCount($idx){
	$tbl = $GLOBALS["_conf_tbl"]["course"];

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