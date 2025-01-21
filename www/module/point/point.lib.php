<?
//사용가능 적립금 가져오기
function getNowPoint($user_id){
	$tbl = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블
    
    $sql  = "SELECT nowpoint ";
    $sql .= "FROM $tbl ";
    $sql .= "WHERE user_id = '$user_id' order by idx desc limit 1";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
	if($total_rs > 0){
			$list = mysqli_fetch_assoc($rs);
	}

	return $list;
}

//적립금 사용
function setMinusPoint($user_id, $minus, $contents){
	$tbl = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블

	//현재 사용가능 적립금 체크
	$arrNowPoint = getNowPoint($user_id);

	//사용하려는 적립금이 사용가능한 현재 적립금보다 작으면 사용불가
	if($arrNowPoint[nowpoint] < $minus){
		return false;
	}

	$save_point = $arrNowPoint[nowpoint] - $minus;

	$sql = "INSERT INTO ".$tbl." set 
		user_id = '$user_id',
		user_name = '".$_POST['user_name']."',
		minus = '$minus',
		nowpoint = '$save_point',
		wdate = now(),
		ip = '".$_SERVER[REMOTE_ADDR]."',
		contents = '$contents'
	";

	if($minus > 0){
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$insert_idx = mysqli_insert_id($GLOBALS[dblink]);
		$total = mysqli_affected_rows($GLOBALS[dblink]);
	}
	if($total > 0){
		return $insert_idx;
	}else{
		return false;
	}
}

//적립금 지급
function setPlusPoint($user_id, $plus, $contents){
	$tbl = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블
	
	//현재 사용가능 적립금 체크
	$arrNowPoint = getNowPoint($user_id);

	$save_point = $arrNowPoint[nowpoint] + $plus;

	$sql = "INSERT INTO ".$tbl." set 
		user_id = '$user_id',
		user_name = '".$_POST['user_name']."',
		plus = '$plus',
		nowpoint = '$save_point',
		wdate = now(),
		ip = '".$_SERVER[REMOTE_ADDR]."',
		contents = '$contents'
	";
	// echo $sql;

	if($plus > 0){
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$insert_idx = mysqli_insert_id($GLOBALS[dblink]);
		$total = mysqli_affected_rows($GLOBALS[dblink]);
	}
	if($total > 0){
		return $insert_idx;
	}else{
		return false;
	}
}

//적립금 기록 가져오기
function getPointList($user_id, $type, $scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블

	$que_where = " AND user_id='$user_id' ";

	if($type=="minus"){
		$que_where .= " AND minus > 0 ";
	}

	if($type=="plus"){
		$que_where .= " AND plus > 0 ";
	}

	//카운트
	$sql = "select count(idx) from $tbl WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT * ";
    $sql .= "FROM ".$tbl." ";
    $sql .= "WHERE 1=1 $que_where  ORDER BY idx DESC ";

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

    return $list;
}
//상품후기용 리스트 가져오기
function getPointListAfter($user_id, $type, $order_no, $scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블

	$que_where = " AND user_id='$user_id' ";

	if($type=="minus"){
		$que_where .= " AND minus > 0 ";
	}

	if($type=="plus"){
		$que_where .= " AND plus > 0 ";
	}

	if($order_no){
		$que_where .= " AND order_no ='".$order_no."' ";
	}

	//카운트
	$sql = "select count(idx) from $tbl WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT * ";
    $sql .= "FROM ".$tbl." ";
    $sql .= "WHERE 1=1 $que_where  ORDER BY idx DESC ";	

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

    return $list;
}
//적립금 기록 가져오기
function getPointListAdmin($sw, $sk, $type, $s_date, $e_date, $scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블
	$tbl_member = $GLOBALS["_conf_tbl"]["member"];//회원 테이블

	if($sw == "id"){
		$que_where .= " AND B.user_id like '%$sk%' ";
	}else if($sw == "name"){
		$que_where .= " AND B.user_name like '%$sk%' ";
	}else if($sw == "all"){
		$que_where .= " AND ( (B.user_name like '%$sk%') OR (B.user_id like '%$sk%') )";
	}

	if($type=="minus"){
		$que_where .= " AND A.minus > 0 ";
	}

	if($type=="plus"){
		$que_where .= " AND A.plus > 0 ";
	}

	if($s_date){
		$que_where .= " AND A.wdate >= '$s_date 00:00:00' ";
	}

	if($e_date){
		$que_where .= " AND A.wdate <= '$e_date 23:59:59' ";
	}


	//카운트
	$sql = "select count(A.idx) from $tbl A LEFT JOIN ".$tbl_member." B ON A.user_id=B.user_id WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.*, B.user_name ";
    $sql .= "FROM ".$tbl." A ";
	$sql .= "LEFT JOIN ".$tbl_member." B ON A.user_id=B.user_id ";
    $sql .= "WHERE 1=1 $que_where  ORDER BY idx DESC ";
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
		//echo $sql;
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

    return $list;
}
//적립금 기록 가져오기 - 사용자용
function getPointListUser($sw, $sk, $type, $s_date, $e_date, $scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블
	$tbl_member = $GLOBALS["_conf_tbl"]["member"];//회원 테이블

	if($sw == "id"){
		$que_where .= " AND B.user_id like '%$sk%' ";
	}else if($sw == "name"){
		$que_where .= " AND B.user_name like '%$sk%' ";
	}else if($sw == "all"){
		$que_where .= " AND ( (B.user_name like '%$sk%') OR (B.user_id like '%$sk%') )";
	}

	if($type=="minus"){
		$que_where .= " AND A.minus > 0 ";
	}

	if($type=="plus"){
		$que_where .= " AND A.plus > 0 ";
	}

	if($s_date){
		$que_where .= " AND A.wdate >= '$s_date 00:00:00' ";
	}

	if($e_date){
		$que_where .= " AND A.wdate <= '$e_date 23:59:59' ";
	}


	//카운트
	$sql = "select count(A.idx) from $tbl A LEFT JOIN ".$tbl_member." B ON A.user_id=B.user_id WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.*, B.user_name ";
    $sql .= "FROM ".$tbl." A ";
	$sql .= "LEFT JOIN ".$tbl_member." B ON A.user_id=B.user_id ";
    $sql .= "WHERE 1=1 $que_where  ORDER BY idx DESC ";
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

    return $list;
}
function deletePoint($idx){
	$tbl = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블

	$sql = "DELETE FROM ".$tbl." WHERE idx='".$idx."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	
	if($rs){
		return true;
	}else{
		return false;
	}
}
?>