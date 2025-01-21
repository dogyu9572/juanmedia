<?
//1:1 ������ �亯 �Է�
function insertOneToOne(){
	$tbl = $GLOBALS["_conf_tbl"]["one_to_one"];

	$sql = "INSERT INTO ".$tbl." set 
		user_id = '".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]."',
		user_name = '".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["NAME"]."',
		status = 'N',
		subject = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['subject'])."',
		contents = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])."',
		ip='".$_SERVER[REMOTE_ADDR]."',
		wdate = now()
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);


	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//1:1 ������ �亯 ����(�亯)
function editOneToOne($idx){
	$tbl = $GLOBALS["_conf_tbl"]["one_to_one"];

	$sql = "UPDATE ".$tbl." SET
		status='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['status'])."',
		re_contents = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['re_contents'])."'
		WHERE idx='$idx'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//1:1 ������ �亯 ����
function deleteOneToOne($idx){
	$tbl = $GLOBALS["_conf_tbl"]["one_to_one"];

	$sql = "DELETE FROM ".$tbl." WHERE idx='$idx' ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//1:1 ������ �亯 ���� - �����
function deleteOneToOneUser($user_id, $idx){
	$tbl = $GLOBALS["_conf_tbl"]["one_to_one"];

	$sql = "DELETE FROM ".$tbl." WHERE user_id='$user_id' AND idx='$idx' ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//1:1 ������ �亯 ���
function getOneToOneListAll($sw, $sk, $scale, $offset=0){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["one_to_one"];

    $sql = "SELECT * FROM $tbl WHERE 1=1 ";

	if($sw == "id"){
		$sql .= " AND user_id like '%$sk%' "; 
	}
	if($sw == "name"){
		$sql .= " AND user_name like '%$sk%' ";
	}
	if($sw == "st"){
		$sql .= " AND status = '$sk' ";
	}
	$sql .= " order by idx desc ";

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

//1:1 ������ �亯 ���
function getOneToOneList($user_id, $scale, $offset=0){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["one_to_one"];

    $sql = "SELECT * FROM $tbl WHERE 1=1 AND user_id='$user_id' ";

	$sql .= " order by idx desc ";

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

//1:1 ������ �亯 ��������
function getOneToOneInfo($idx){
	$tbl = $GLOBALS["_conf_tbl"]["one_to_one"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE idx = '$idx' ";
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
?>