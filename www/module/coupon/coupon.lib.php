<?
function insertCouponGood($idx) {
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//my쿠폰 테이블

	$arrInfo = getGoodInfo($idx);

	$sql = "INSERT INTO ".$tbl." set 
		user_id='".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]."',
		g_idx='$idx',
		coupon_name='".stripslashes($arrInfo["list"][0]['g_name'])."',
		coupon_dis='".$arrInfo["list"][0]['coupon_dis']."',
		coupon_unit='".$arrInfo["list"][0]['coupon_unit']."',
		coupon_sdate='".$arrInfo["list"][0]['coupon_sdate']."',
		coupon_edate='".$arrInfo["list"][0]['coupon_edate']."',
		coupon_use='N',
		wdate = now()
	";
	//echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs > 0){
		
		if($arrInfo["list"][0]['coupon_limit'] != "N"){
			$sql2 = "update tbl_shop_good set coupon_qty = coupon_qty - 1 where idx='$idx'";
			$rs2 = mysqli_query($GLOBALS['dblink'], $sql2);
		}

		return true;
	}else{
		return false;
	}
	
}


function insertCodeCouponGood($no) {
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//my쿠폰 테이블

	$sql = "UPDATE ".$tbl." set 
			user_id='".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]."',
			wdate = now()
			WHERE coupon_no='$no' and user_id=''
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS[dblink]);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

function getCouponGoodList($user_id, $idx, $use) {
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//my쿠폰 테이블
	
	if($idx!="") {
		$arr_idx = explode("|",$idx);
		for($i=0;$i<count($arr_idx);$i++){
			$str_idx .= "'".$arr_idx[$i]."'";
			if($i != count($arr_idx)-1){
				$str_idx .= ",";
			}
		}

		$que_where = "AND A.g_idx in (".$str_idx.")";
	} else {
		$que_where = "AND A.e_idx!='0' ";
	}

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM $tbl A ";
    $sql .= "WHERE 1=1 $que_where AND A.coupon_use='$use' ";
	$sql .= "AND A.coupon_sdate <= curdate() AND A.coupon_edate >= curdate() ORDER BY A.idx DESC ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS[dblink]);

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


//쿠폰 등록
function insertCoupon(){
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];//쿠폰 테이블
	$tbl_my = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블

	//	$arrCateInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cat_no']));

	if(!$_POST['coupon_sdate']){
		$_POST['coupon_sdate'] = "0000-00-00";
	}
	if(!$_POST['coupon_edate']){
		$_POST['coupon_edate'] = "0000-00-00";
	}
	if(!$_POST['over_price']){
		$_POST['over_price'] = "0";
	}
	if(!$_POST['under_price']){
		$_POST['under_price'] = "0";
	}

	$sql = "INSERT INTO ".$tbl." set 
		coupon_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_name'])."',
		coupon_content = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_content'])."',
		coupon_sdate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_sdate'])."',
		coupon_edate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_edate'])."',
		coupon_dis = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_dis'])."',
		coupon_unit = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_unit'])."',
		coupon_qty = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_qty'])."',
		over_price = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['over_price'])."',
		under_price = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['under_price'])."',
		member_coupon = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['member_coupon'])."',
		coupon_type = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_type'])."',
		wdate = now()
	";
	// echo $sql;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	$total = mysqli_affected_rows($GLOBALS['dblink']);
	
	//회원발행
	if($_POST["member"]=="Y") {
		$arrList = getMemberList("", "", 0, 0);
		if($arrList['list']['total'] > 0):
		for ($i=0;$i<$arrList['list']['total'];$i++) {
			$serial = substr(strtoupper(md5($_POST['coupon_name'].$i.microtime(true))),0,16);
			
			$sql = "INSERT INTO ".$tbl_my." set 
				user_id = '".$arrList['list'][$i]['user_id']."',
				e_idx = '".$insert_idx."',
				coupon_no = '".$serial."',
				coupon_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_name'])."',
				coupon_content = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_content'])."',
				coupon_sdate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_sdate'])."',
				coupon_edate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_edate'])."',
				coupon_dis = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_dis'])."',
				coupon_unit = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_unit'])."',
				over_price = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['over_price'])."',
				under_price = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['under_price'])."',
				coupon_use = 'N',
				wdate = now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

		} endif;

		$sql_up = "UPDATE  ".$tbl." set 
				coupon_qty = '".$arrList['list']['total']."'
			WHERE idx='".$insert_idx."'
		";
		$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);

	} else {

		//발행
		for($i=0; $i<$_POST['coupon_qty']; $i++) {

			$serial = substr(strtoupper(md5($_POST['coupon_name'].$i.microtime(true))),0,16);

			$sql = "INSERT INTO ".$tbl_my." set 
				e_idx = '".$insert_idx."',
				coupon_no = '".$serial."',
				coupon_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_name'])."',
				coupon_content = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_content'])."',
				coupon_sdate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_sdate'])."',
				coupon_edate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_edate'])."',
				coupon_dis = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_dis'])."',
				coupon_unit = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_unit'])."',
				over_price = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['over_price'])."',
				under_price = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['under_price'])."',
				coupon_use = 'N'
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
		}
	}

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

function updateCoupon($idx){	
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];//쿠폰 테이블
	$tbl_my = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블

	//$arrCateInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cat_no']));
	
	if(!$_POST['coupon_sdate']){
		$_POST['coupon_sdate'] = "0000-00-00";
	}
	if(!$_POST['coupon_edate']){
		$_POST['coupon_edate'] = "0000-00-00";
	}
	if(!$_POST['over_price']){
		$_POST['over_price'] = "0";
	}
	if(!$_POST['under_price']){
		$_POST['under_price'] = "0";
	}

	########################################## 주소록
	$comma = "";
	for($i=0;$i<count($_POST['addidxs']);$i++){			
		$addidxs	.= $comma.$_POST['addidxs'][$i];
		$comma = ",";
	}	
	$sub_sql= "				
		addidxs		='".mysqli_real_escape_string($GLOBALS['dblink'], $addidxs)."',	
	";
	########################################## 주소록 ED
	########################################## 카테고리
	$comma = "";
	for($i=0;$i<count($_POST['cateidxs']);$i++){			
		$cateidxs	.= $comma.$_POST['cateidxs'][$i];
		$comma = ",";
	}	
	$sub_sql.= "				
		cateidxs		='".mysqli_real_escape_string($GLOBALS['dblink'], $cateidxs)."',	
	";
	########################################## 카테고리 ED
	########################################## 브랜드
	$comma = "";
	for($i=0;$i<count($_POST['brandidxs']);$i++){			
		$brandidxs	.= $comma.$_POST['brandidxs'][$i];
		$comma = ",";
	}	
	$sub_sql.= "				
		brandidxs		='".mysqli_real_escape_string($GLOBALS['dblink'], $brandidxs)."',	
	";
	########################################## 카테고리 ED


	$sql = "UPDATE ".$tbl." SET 
		coupon_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_name'])."',
		coupon_content = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_content'])."',
		coupon_sdate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_sdate'])."',
		coupon_edate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_edate'])."',
		coupon_dis = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_dis'])."',
		over_price = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['over_price'])."',
		under_price = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['under_price'])."',
		coupon_qty = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_qty'])."',
		coupon_type = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_type'])."',	
		member_level = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['member_level'])."',	
		$sub_sql
		coupon_unit = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_unit'])."'
		WHERE idx = '".$idx."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//추가발행
	if($_POST['coupon_qty']) {
	for($i=0; $i<$_POST['coupon_qty']; $i++) {

		$serial = strtoupper(md5($_POST['coupon_name'].$i.microtime(true)));

		$sql1 = "INSERT INTO ".$tbl_my." SET 
			e_idx = '".$idx."',
			coupon_no = '".substr($serial,0,16)."',
			coupon_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_name'])."',
			coupon_content = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_content'])."',
			coupon_sdate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_sdate'])."',
			coupon_edate = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_edate'])."',
			coupon_dis = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_dis'])."',
			coupon_unit = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_unit'])."',
			coupon_use = 'N'
		";
		$rs1 = mysqli_query($GLOBALS['dblink'], $sql1); 
	}
	}

	if($rs > 0){
		return true;
	}else{
		return false;
	}

}

//발급된 쿠폰에 회원등록
function getUserCoupon($idx, $userID){
	
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블

	$arrInfo = getCouponInfo($idx);
	
	$coupon_name = $arrInfo["list"][0][coupon_name];
	$serial = substr(strtoupper(md5($coupon_name.$userID.microtime(true))),0,16);
	$edate =  date("Y-m-d", mktime(0,0,0,date("m")+$arrInfo["list"][0]['coupon_content'],date("d"),date("Y")));

	$sql = "
	INSERT INTO ".$tbl." set 
		user_id='".mysqli_real_escape_string($GLOBALS['dblink'], $userID)."',
		e_idx='$idx',
		coupon_no='".$serial."',
		coupon_name='".$coupon_name."',
		coupon_dis='".$arrInfo["list"][0][coupon_dis]."',
		coupon_unit='".$arrInfo["list"][0][coupon_unit]."',
		coupon_sdate=now(),
		coupon_edate='".$edate."',
		coupon_use='N',
		over_price='".$arrInfo["list"][0][over_price]."',
		under_price='".$arrInfo["list"][0][under_price]."',
		wdate=now()
	";
	//echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs > 0){
		return true;
	}else{
		return false;
	}
}

//발급된 쿠폰에 회원등록
function getUserCouponReturn($idx, $userID){
	
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블

	$arrInfo = getCouponInfo($idx);
	
	$coupon_name = $arrInfo["list"][0][coupon_name];
	$serial = substr(strtoupper(md5($coupon_name.$userID.microtime(true))),0,16);
	//	$edate =  date("Y-m-d", mktime(0,0,0,date("m")+$arrInfo["list"][0]['coupon_content'],date("d"),date("Y")));
	$sdate = $arrInfo["list"][0]['coupon_sdate'];
	$edate = $arrInfo["list"][0]['coupon_edate'];
	
	$sql = "
	INSERT INTO ".$tbl." set 
		user_id='".mysqli_real_escape_string($GLOBALS['dblink'], $userID)."',
		e_idx='$idx',
		coupon_no='".$serial."',
		coupon_name='".$coupon_name."',
		coupon_dis='".$arrInfo["list"][0][coupon_dis]."',
		coupon_unit='".$arrInfo["list"][0][coupon_unit]."',
		coupon_sdate='".$sdate."',
		coupon_edate='".$edate."',
		coupon_use='N',
		over_price='".$arrInfo["list"][0][over_price]."',
		under_price='".$arrInfo["list"][0][under_price]."',
		wdate=now(), 
		udate=now()
	";
	//echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	
	if($rs > 0){
		return $insert_idx;
	}else{
		return false;
	}

}

//발급된 쿠폰에 회원입력
function updateUserCoupon($idx){
	
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블

	$sql = "UPDATE ".$tbl." SET 
		user_id = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_id'])."',
		wdate = now()
		WHERE idx = '".$idx."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs > 0){
		return true;
	}else{
		return false;
	}

}

//쿠폰리스트 가져오기
function getCouponList($user_id, $gb="", $scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];//쿠폰 테이블
	$tbl_set = $GLOBALS["_conf_tbl"]["coupon_set"];//쿠폰 테이블

	$que_where = " AND A.user_id='$user_id' ";
	
	if($gb) {
		$que_where .= " AND A.use_gb='$gb' ";
	}

	//카운트
	$sql = "select count(A.idx) from $tbl A LEFT JOIN ".$tbl_set." B ON A.c_idx=B.idx WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysql_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT B.*, A.idx as cidx ";
    $sql .= "FROM ".$tbl." A ";
	$sql .= "LEFT JOIN ".$tbl_set." B ON A.c_idx=B.idx ";
    $sql .= "WHERE 1=1 $que_where  ORDER BY A.idx DESC ";

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

//상품쿠폰리스트 가져오기
function getGoodCouponList($scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품 테이블

	//카운트
	$sql = "select count(A.idx) from $tbl A WHERE coupon_use='Y' ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl." A WHERE coupon_use='Y' ";

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

function getMycouponList($g_idx, $scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블
	$tbl_member = $GLOBALS["_conf_tbl"]["member"];

	//카운트
	$sql = "select count(A.idx) from $tbl A WHERE g_idx='$g_idx' ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysql_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.*, B.user_name ";
    $sql .= "FROM ".$tbl." A ";
	$sql .= "LEFT JOIN ".$tbl_member." B ON A.user_id=B.user_id ";
	$sql .= "WHERE g_idx='$g_idx' ";

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


//쿠폰리스트 가져오기
function getCouponListOrder($scale, $offset=0, $gb="", $searchDate="", $subQuery=""){
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];//쿠폰 테이블

	if($gb=="Y") {
		$que_where = " AND member_coupon='Y' ";
	} else {
		$que_where = " AND member_coupon='N' ";
	}
	if($searchDate){
		$que_where .= " AND (( coupon_sdate <= '".$searchDate."' AND coupon_edate >= '".$searchDate."') OR ( coupon_sdate = '0000-00-00' AND coupon_edate = '0000-00-00' ))";
	}
	$que_where .= $subQuery;

	//카운트
	$sql = "select count(idx) from $tbl WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT * ";
    $sql .= "FROM ".$tbl." ";
    $sql .= "WHERE 1=1 $que_where  ORDER BY idx DESC ";
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

//쿠폰세팅 가져오기
function getCouponListAdmin($scale, $offset=0, $gb=""){
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];//쿠폰 테이블

	/*
	if($user_id){
		$que_where = " AND user_id='$user_id' ";
	}

	if($s_date){
		$que_where .= " AND wdate >= '$s_date 00:00:00' ";
	}

	if($e_date){
		$que_where .= " AND wdate <= '$e_date 23:59:59' ";
	}
	*/
	if($gb=="Y") {
		$que_where = " AND member_coupon='Y' ";
	} else {
		$que_where = " AND member_coupon='N' ";
	}
	if($_GET['coupon_unit']){
		$que_where .= "AND coupon_unit ='".$_GET['coupon_unit']."' ";
	}
	if($_GET['sk']){
		$que_where .= "AND coupon_name like '%".$_GET['sk']."%' ";
	}
	if($_REQUEST['s_date']){
		$que_where .= " AND wdate >= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date'])." 00:00:00' ";
	}
	if($_REQUEST['e_date']){
		$que_where .= " AND wdate <= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date'])." 23:59:59' ";
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

//쿠폰 리스트
function getCouponMemberList($userID, $scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블

	$que_where = " AND A.user_id='$userID' ";

	//카운트
	$sql = "select count(A.idx) from $tbl A WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.*, B.user_name ";
    $sql .= "FROM ".$tbl." A ";
	$sql .= "LEFT JOIN tbl_member B ON A.user_id=B.user_id ";
    $sql .= "WHERE 1=1 $que_where  ORDER BY A.idx DESC ";

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

//이벤트 쿠폰 사용여부
function getCouponUserEvent($idx, $userID){
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블

	//오늘 전체 카운트
	$sql = "select count(A.idx) from $tbl A WHERE A.e_idx='".$idx."' AND wdate like '%".date("Y-m-d")."%' ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $list['total'] = $row['0'];

	// 쿠폰카운트
	if($userID){
		$sql = "select count(A.idx) from $tbl A WHERE A.e_idx='".$idx."' AND user_id='". $userID ."' ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$row = mysqli_fetch_row($rs);
		$list['userCnt'] = $row['0'];
	}else{
		$list['userCnt'] = 0;
	}
    return $list;
}

//쿠폰 사용여부
function getCouponUserListAdmin($idx, $scale, $offset=0){
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블

	$que_where = " AND A.e_idx='$idx' ";

	//카운트
	$sql = "select count(A.idx) from $tbl A WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.*, B.user_name ";
    $sql .= "FROM ".$tbl." A ";
	$sql .= "LEFT JOIN tbl_member B ON A.user_id=B.user_id ";
    $sql .= "WHERE 1=1 $que_where  ORDER BY A.idx DESC ";

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

//쿠폰정보 가져오기
function getMyCouponCount($idx){
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];

    $sql  = "SELECT count(*) FROM " .$tbl." WHERE e_idx = '$idx' ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $list['total'] = $row['0'];
    
	return $list;
}

//쿠폰정보 가져오기
function getMyCouponCountDay($idx, $nowdate){
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];

    $sql  = "SELECT count(*) FROM " .$tbl." WHERE e_idx = '$idx' and coupon_sdate = '$nowdate' ";
	//echo $sql;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $list['total'] = $row['0'];
    
	return $list;
}

//쿠폰정보 가져오기
function getCouponInfo($idx){
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];

    $sql  = "SELECT * ";
    $sql .= "FROM " .$tbl." ";
    $sql .= "WHERE idx = '$idx' ";
	//echo $sql;

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

//쿠폰정보 가져오기
function getMyCouponInfo($idx){
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];

    $sql  = "SELECT * ";
    $sql .= "FROM " .$tbl." ";
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


//쿠폰시리얼 가져오기
function checkNumber($cert){
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];

    $sql  = "SELECT * ";
    $sql .= "FROM " .$tbl." ";
    $sql .= "WHERE coupon = '$cert' and use_gb='N' and wdate='0000-00-00' ";

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

function deleteMyCoupon($idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰정보

	//상품 정보 삭제
	$sql = "DELETE FROM ".$tbl." WHERE idx='".$idx."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	
	if($rs){
		return true;
	}else{
		return false;
	}
}

function deleteUserCoupon($e_idx, $idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];//쿠폰정보
	$tbl_my = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰정보

	//상품 정보 삭제
	$sql = "DELETE FROM ".$tbl_my." WHERE idx='".$idx."'	";
	$rs1 = mysqli_query($GLOBALS['dblink'], $sql);
	
	if($rs1){
		$sql = "UPDATE $tbl SET
			coupon_qty = coupon_qty-1
			WHERE idx='".$e_idx."'	
		";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		return true;
	}else{
		return false;
	}
}


function deleteCoupon($idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["coupon"];//쿠폰정보
	$tbl_my = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰정보

	//상품 정보 삭제
	$sql1 = "DELETE FROM ".$tbl." WHERE idx='".$idx."'	";
	$rs1 = mysqli_query($GLOBALS['dblink'], $sql1);

	$sql = "DELETE FROM ".$tbl_my." WHERE e_idx='".$idx."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	
	if($rs1){
		return true;
	}else{
		return false;
	}
}

//쿠폰정보 가져오기
function getMyCouponCheckInfo($user_id, $idx){
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];

    $sql  = "SELECT * ";
    $sql .= "FROM " .$tbl." ";
    $sql .= "WHERE e_idx = '$idx' AND user_id='$user_id' ";

	//echo $sql ;

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

function getMypageCouponList($user_id, $gb, $scale, $offset=0, $payprice="", $cat_yn="", $subQuery="", $orderBy="") {
	
	$tbl = $GLOBALS["_conf_tbl"]["mycoupon"];//쿠폰 테이블
	$tbl_coupon = $GLOBALS["_conf_tbl"]["coupon"];//쿠폰 테이블
	
	$que_where = " AND A.user_id='$user_id' ";
	
	if($cat_yn == "Y") {
		$que_where .= " AND B.cat_no != 0 ";
	}
	if($cat_yn == "N") {
		$que_where .= " AND B.cat_no = 0 ";
	}
	if($payprice!="") {		//결제금액이상
		$que_where .= " AND A.under_price <= '$payprice' ";
	}
	
	if($gb == "Y") {
		$que_where .= " AND A.coupon_use='N' AND A.coupon_sdate <= curdate() AND A.coupon_edate >= curdate() ";
	} else if($gb == "Y1") {
		$que_where .= " AND A.e_idx!='0' AND A.coupon_use='N' AND A.coupon_sdate <= curdate() AND A.coupon_edate >= curdate() ";		
	} else	if($gb == "U") {
		$que_where .= " AND A.coupon_use='Y' ";
	} else	if($gb == "E") {
		$que_where .= " AND A.coupon_edate < curdate() ";
	} else	if($gb == "UE") {
		$que_where .= " AND (A.coupon_use='Y' OR A.coupon_edate < curdate() ) ";
	}

	if($_REQUEST["s_date"] && $_REQUEST["e_date"]) {
		$que_where .= " AND (A.coupon_sdate BETWEEN '".$_REQUEST["s_date"]."' AND '".$_REQUEST["e_date"]."' OR A.coupon_edate BETWEEN '".$_REQUEST["s_date"]."' AND '".$_REQUEST["e_date"]."') ";
	}

	$que_where .= $subQuery;

	//카운트
	$sql = "select count(A.idx) from $tbl A LEFT JOIN  ".$tbl_coupon." B ON A.e_idx=B.idx WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.*, B.cat_no, B.cat_code, B.shopGoodIdx, B.cateidxs, B.brandidxs ";
    $sql .= "FROM ".$tbl." A ";
	$sql .= "LEFT JOIN  ".$tbl_coupon." B ON A.e_idx=B.idx ";
    $sql .= "WHERE 1=1 $que_where ";
	if($orderBy){
		$sql .= $orderBy;
	}else{
		$sql .= " ORDER BY A.idx DESC ";
	}
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

?>