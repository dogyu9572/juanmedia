<?
//���� ��� ��������
function getReviewListAll($scale, $offset=0){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["shop_review"];

    $sql = "SELECT * FROM $tbl ";
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

//���� ��� ��������
function getReviewList($g_idx, $scale, $offset=0){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["shop_review"];

    $sql = "SELECT * FROM $tbl WHERE 1=1 AND g_idx='$g_idx' ";
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

//���� ��� ��������
function getMyReviewList($user_id, $scale, $offset=0){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["shop_review"];

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

//���� ����ϱ�
function insertReview($g_idx, $user_id, $user_name){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["shop_review"];


	//���� ���̺� �Է�
	$sql = "INSERT INTO ".$tbl." set 
		g_idx='$g_idx',
		user_id='".$user_id."',
		user_name='".$user_name."',
		review_point='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['review_point'])."',
		subject='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['subject'])."',
		contents='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])."',
		ip='".$_SERVER[REMOTE_ADDR]."',
		wdate=now()
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}


//���� �����ϱ�
function updateReview($user_id, $idx){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["shop_review"];

	//�������� ����
	$updatePerm = false;

	//�����ڴ� �׳� ���
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"]){
		$updatePerm = true;
	}

	//��������
	$arrArticleInfo = getReviewInfo($idx);
	
	if($arrArticleInfo["list"][0]["user_id"]==$user_id){
		$updatePerm = true;
	}

	if($updatePerm==true){
		//���� ���̺� ������Ʈ
		$sql = "UPDATE ".$tbl." set 
			review_point='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['review_point'])."',
			subject='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['subject'])."',
			contents='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])."',
			ip='".$_SERVER[REMOTE_ADDR]."'
			WHERE idx = '$idx'
		";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		jsMsg("������ ������ �����ϴ�.");
		return false;
	}
}


//���� �������� - id
function getReviewInfo($idx){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["shop_review"];

	$sql  = "SELECT * ";
	$sql .= "FROM $tbl ";
	$sql .= "WHERE idx = '$idx' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;

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

//���� �����ϱ�
function deleteReview($user_id, $idx){
	// ���̺� ����
	$tbl = $GLOBALS["_conf_tbl"]["shop_review"];

	//�������� ����
	$deletePerm = false;

	//�����ڴ� �׳� ���
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"]){
		$deletePerm = true;
	}

	//��������
	$arrArticleInfo = getReviewInfo($idx);
	
	if($arrArticleInfo["list"][0]["user_id"]==$user_id){
		$deletePerm = true;
	}

	
	if($deletePerm==true){
		//���� ���̺��� ����
		$sql = "DELETE FROM ".$tbl." 
			WHERE idx='$idx'
		";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		jsMsg("������ ������ �����ϴ�.");
		return false;
	}
}
?>