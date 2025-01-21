<?
//컨텐츠 등록하기
function createContents($code){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["html_contents"];

	// 테이블에 입력
	$sql = "INSERT INTO ".$tbl." set 
		code='$code',
		subject='새 콘텐츠',
		usehtml='Y',
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



//컨텐츠 수정하기
function editContents($idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["html_contents"];

	// 테이블에 입력
	$sql = "UPDATE ".$tbl." set 
		subject='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['f_subject'])."',
		usehtml='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['f_usehtml'])."',
		contents='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['f_contents'])."',
		e_contents='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['e_contents'])."',
		c_contents='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['c_contents'])."'
		WHERE idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['idx'])."'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($rs){
		return true;
	}else{
		return false;
	}

}

//컨텐츠 삭제하기
function deleteContents($idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["html_contents"];

	//게시판 테이블에서 삭제
	$sql = "DELETE FROM ".$tbl." 
		WHERE idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['idx'])."'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//컨텐츠 가져오기
function getContents($code){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["html_contents"];

    $sql  = "SELECT * ";
    $sql .= "FROM $tbl ";
    $sql .= "WHERE code = '$code' ";
//	echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){
        $row = mysqli_fetch_assoc($rs);
		$row[contents] = stripslashes($row[contents]);
		if($row[usehtml]=="N"){
			$row[contents] = htmlspecialchars($row[contents]);
			$row[contents] = nl2br($row[contents]);
		}
		$rtn = $row[contents];
    }else{
        $rtn = "";
    }
    return $rtn;
}
?>