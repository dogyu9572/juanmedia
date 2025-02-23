<?
/*********************************** 회원관련 *************************************/
//회원등급 등록
function createMemberLevel($level_no, $level_name){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["member_level"];

	// 테이블에 입력
	$sql = "INSERT INTO ".$tbl." set 
		level_no='$level_no',
		level_name='$level_name',
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



//회원등급 수정하기
function editMemberLevel($idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["member_level"];

	// 테이블에 입력
	$sql = "UPDATE ".$tbl." set 
		level_no='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['level_no'])."',
		level_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['level_name'])."',
		level_point='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['level_point'])."',
		level_price='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['level_price'])."',
		coupon1='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon1'])."',
		coupon2='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon2'])."',
		favor1='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['favor1'])."',
		favor2='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['favor2'])."',
		favor1_ea='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['favor1_ea'])."'
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

//회원등급정보
function getMemberLevelInfo($level){
	$tbl = $GLOBALS["_conf_tbl"]["member_level"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE level_no = '$level' ";

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


function getMemberListOrderJoin($jb, $sw, $sk, $scale, $offset=0, $subQuery=""){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["member"];

    $sql = "SELECT a.*
	, (SELECT SUM(pay_amount) FROM tbl_shop_order_info WHERE order_id=a.user_id AND order_state IN (9,19) ) AS sum_amount
	, (SELECT count(pay_amount) FROM tbl_shop_order_info WHERE order_id=a.user_id AND order_state IN (9,19) ) AS cnt_amount
	, (SELECT count(pay_amount) FROM tbl_shop_order_info WHERE order_id=a.user_id AND order_state IN (5) ) AS cnt_cag
	, (SELECT count(pay_amount) FROM tbl_shop_order_info WHERE order_id=a.user_id AND order_state IN (3) ) AS cnt_cet	
	FROM $tbl as a WHERE 1=1 ";

	if($_GET['level'] == "90") {
		$sql .= " AND user_level = '90' ";
	} else if($_GET['level'] == "80") {
		$sql .= " AND user_level = '80' ";
	} else {
		$sql .= " AND user_level != '80' AND user_level != '90' ";
	}

	if($_GET['user_level'] != "") {
		$sql .= " AND user_level = '$_GET[user_level]' ";
	}
	if($jb){
		$sql .= " AND job = '$jb' ";
	}
	if($sw == "id"){
		$sql .= " AND user_id like '%$sk%' ";
	}else if($sw == "name"){
		$sql .= " AND user_name like '%$sk%' ";
	}else if($sw == "email"){
		$sql .= " AND email like '%$sk%' ";
	}else if($sw == "hp"){
		$sql .= " AND mobile like '%$sk' ";
	}else if($sw == "company"){
		$sql .= " AND company like '%$sk%' ";
	}else if($sw == "ll2"){
		$sql .= " AND login_last <= '$sk 23:59:59' AND etc_2!='Y' ";
	}else if($sw == "ll"){
		$sql .= " AND login_last <= '$sk 23:59:59' ";
	}else if($sw == "all"){
		$sql .= " AND ( (user_name like '%$sk%') OR (user_id like '%$sk%') OR (email like '%$sk%') OR (mobile like '%$sk') )";
	}

	if($subQuery){
		$sql .= $subQuery;
	}
	if($_REQUEST['s_date']){
		$sql .= " AND wdate >= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date'])." 00:00:00' ";
	}
	if($_REQUEST['e_date']){
		$sql .= " AND wdate <= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date'])." 23:59:59' ";
	}
	if($_REQUEST['ds_date']){
		$sql .= " AND outdt >= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['ds_date'])." 00:00:00' ";
	}
	if($_REQUEST['de_date']){
		$sql .= " AND outdt <= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['de_date'])." 23:59:59' ";
	}
	if($_GET['orderby']){
		$sql .= " order by ".$_GET['orderby']." desc,idx desc ";
	}else{
		$sql .= " order by idx desc ";
	}
	//echo $sql;
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

				//scale 0 으로 지정시에는 전체 가져옴
				if($scale > 0){
		    	$sql .= " limit $offset,$scale ";
				}
		    $rs = mysqli_query($GLOBALS['dblink'],$sql);

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


//회원목록
function getMemberList($jb, $sw, $sk, $scale, $offset=0, $subQuery="", $orderBy=""){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["member"];

    $sql = "SELECT * FROM $tbl WHERE 1=1 ";

	if($_GET['level'] == "90" || $_GET['user_level'] == "90") {
		$sql .= " AND user_level = '90' ";
	} else if($_GET['level'] == "80") {
		$sql .= " AND user_level = '80' ";
	} else {
		$sql .= " AND user_level != '80' AND user_level != '90' ";
	}

	if($_GET['user_level'] != "") {
		$sql .= " AND user_level = '$_GET[user_level]' ";
	}
	if($jb){
		$sql .= " AND job = '$jb' ";
	}

	if($sw == "id"){
		$encoded_sk = base64_encode($sk);
		$sql .= " AND (user_id like '%$sk%' OR user_id like '%$encoded_sk%') ";
	}else if($sw == "name"){
		$encoded_sk = base64_encode($sk);
		$sql .= " AND (user_name like '%$sk%' OR user_name like '%$encoded_sk%') ";
	}else if($sw == "mobile"){
		$encoded_sk = base64_encode($sk);
		$sql .= " AND (mobile like '%$sk%' OR mobile like '%$encoded_sk%') ";
	}else if($sw == "all"){
		$encoded_sk = base64_encode($sk);
		$sql .= " AND ( (user_name like '%$sk%' OR user_name like '%$encoded_sk%') OR (user_id like '%$sk%' OR user_id like '%$encoded_sk%') OR (company like '%$sk%' OR company like '%$encoded_sk%') )";
	}else if($sw == "company"){
		$encoded_sk = base64_encode($sk);
		$sql .= " AND (company like '%$sk%' OR company like '%$encoded_sk%') ";
	}else if($sw == "company"){
		$sql .= " AND company like '%$sk%' ";
	}else if($sw == "ll2"){
		$sql .= " AND login_last <= '$sk 23:59:59' AND etc_2!='Y' ";
	}else if($sw == "ll"){
		$sql .= " AND login_last <= '$sk 23:59:59' ";
	}else if($sw == "etc_1"){
		$sql .= " AND etc_1 like '%$sk%' ";
	}else if($sw == "etc_10"){
		$sql .= " AND etc_10 like '%$sk%' ";
	}else if($sw == "tel"){
		$sql .= " AND tel like '%$sk%' ";
	}


	if($subQuery){
		$sql .= $subQuery;
	}
	if($_REQUEST['s_date']){
		$sql .= " AND wdate >= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date'])." 00:00:00' ";
	}
	if($_REQUEST['e_date']){
		$sql .= " AND wdate <= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date'])." 23:59:59' ";
	}

	if($_GET["login_last"] != ""){
		$sql .= " AND DATE_FORMAT(login_last, '%Y-%m-%d') >= '".mysqli_real_escape_string($GLOBALS['dblink'], $_GET['login_last'])."' ";
	}

	if($_GET["email_accept"] != ""){
		$sql .= " AND email_accept = '".mysqli_real_escape_string($GLOBALS['dblink'], $_GET['email_accept'])."' ";
	}
	if($_GET["sms_accept"] != ""){
		$sql .= " AND sms_accept = '".mysqli_real_escape_string($GLOBALS['dblink'], $_GET['sms_accept'])."' ";
	}
	if($_GET["kakao_accept"] != ""){
		$sql .= " AND kakao_accept = '".mysqli_real_escape_string($GLOBALS['dblink'], $_GET['kakao_accept'])."' ";
	}
	if($_GET['join_type']){
		$sql .= " AND join_type='".$_GET['join_type']."' ";
	}
	if($_GET['job']){
		$sql .= " AND job='".$_GET['job']."' ";
	}
	if($_GET['etc1']){
		$sql .= " AND etc_1='".$_GET['etc1']."' ";
	}

	/*if($_GET['email_accept']){
		$subQuery .= " AND email_accept='".$_GET['email_accept']."' ";
	}
	if($_GET['sms_accept']){
		$subQuery .= " AND sms_accept='".$_GET['sms_accept']."' ";
	}*/

	if($orderBy){
		$sql .= $orderBy;
	}else{
		$sql .= " order by idx desc ";
	}

	// 전체 레코드 수 먼저 조회
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	// 결과 배열 초기화
	$list = array('total' => $total_rs);

	if($total_rs > 0) {
		// offset 유효성 검사
		if(!isset($offset) || $offset < 0 || $offset === '' || !is_numeric($offset)) {
			$offset = 0;
		}

		// offset이 전체 레코드 수보다 크면 조정
		if($total_rs <= $offset) {
			$offset = max(0, $total_rs - ($scale > 0 ? $scale : $total_rs));
		}

		// scale이 0이면 전체 데이터 조회, 아니면 LIMIT 적용
		if($scale > 0) {
			$sql .= " LIMIT $offset, $scale";
		}

		// 결과 조회
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$list['list']['total'] = mysqli_num_rows($rs);

		// 결과 데이터 처리
		$i = 0;
		while($row = mysqli_fetch_assoc($rs)) {
			$list['list'][$i] = $row;
			$i++;
		}
	} else {
		$list['list']['total'] = 0;
	}

	return $list;
}


//회원가입
function joinMember(){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$birth			= $_POST['birthY']."-".$_POST['birthM']."-".$_POST['birthD'];
	$user_id		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_id']);
	$user_pw		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw']);
	$address_type	= "직장";
	//$mobile			= $_POST['mobile_01']."-".$_POST['mobile_02']."-".$_POST['mobile_03'];
	//$phone			= $_POST['phone_01']."-".$_POST['phone_02']."-".$_POST['phone_03'];

	$mobile			= $_POST['mobile'];
	$user_email		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_id']);

	$email_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="on"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$sms_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sms_accept'])=="on"?"Y":"N";
	if($sms_accept=="Y"){
		$sms_accept_date = date("Y-m-d H:i:s");
	}
	$kakao_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['kakao_accept'])=="Y"?"Y":"N";
	if($kakao_accept=="Y"){
		$kakao_accept_date = date("Y-m-d H:i:s");
	}

	$arrCheck = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $user_id));

	$comma = "";
	for($i=0;$i<count($_POST['holiday']);$i++){
		$holiday	.= $comma.$_POST['holiday'][$i];
		$comma = ",";
	}

	if($arrCheck["total"] > 0){
		return false;
	}else{
		$sql = "INSERT INTO ".$tbl." set 
			user_id = '".$user_id."',
			user_pw = password('".$user_pw."'),
			user_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
			nick_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['nick_name'])."',
			user_status = '0',
			user_level = '1',
			a_class = '4',
			birth = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['birth'])."',
			email = '".$user_email."',
			email_accept = '$email_accept',
			email_accept_date = '$email_accept_date',
			sms_accept = '$sms_accept',
			sms_accept_date = '$sms_accept_date',
			kakao_accept = '$kakao_accept',
			kakao_accept_date = '$kakao_accept_date',
			zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip'])."',
			address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
			address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
			address_type = '".$address_type."',
			etc_1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
			etc_2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_2'])."',
			etc_3 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_3'])."',
			etc_4 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',
			etc_5 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
			etc_6 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',
			etc_7 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',
			etc_8 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',
			etc_9 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',
			etc_10 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',
			holiday = '".mysqli_real_escape_string($GLOBALS['dblink'], $holiday)."',
			company = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['company'])."',
			department = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['department'])."',
			job = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['job'])."',
			duty = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['duty'])."',
			join_type = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['join_type'])."',
			gender = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['gender'])."',
			phone = '".$phone."',
			mobile = '".$mobile."',
			login_last = now(),
			wdate = now(),
			udate = now()
		";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($_POST['address'] && $_POST['address_ext']){	####### 주소가 있으면 배송지 최초 입력
			$sql = "
				INSERT INTO tbl_member_address set 
				user_id = '".$user_id."', d_addr='Y', shipping='기본배송지',
				name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
				zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip'])."',
				address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
				address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
				phone = '".$phone."',
				mobile = '".$mobile."',
				wdate = now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
		}

		//파일처리	
		inputMemberFiles("member", $insert_idx, $_FILES, $thumwidth);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}
}

//회원가입 > 통합회원
function getUserUnite($user_type, $user_id, $user_pw, $user_uniteid){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$userID		= mysqli_real_escape_string($GLOBALS['dblink'], $user_id);
	$userPW		= mysqli_real_escape_string($GLOBALS['dblink'], $user_pw);

	if($user_type=="naver"){
		$sql = "UPDATE ".$tbl." set naver_id='".$userID."', naver_pw=password('".$userPW."') where user_id='".$user_uniteid."'";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

	}else if($user_type=="kakao"){
		$sql = "UPDATE ".$tbl." set kakao_id='".$userID."', kakao_pw=password('".$userPW."') where user_id='".$user_uniteid."'";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);
	}
	//	echo $sql;
	if($total > 0){
		return "Y";
	}else{
		return "N";
	}
}

//회원가입 /소셜 회원가입
function joinSocialMember(){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$user_id		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['join_type']."_".$_SESSION['social']['id']);
	$user_pw		= mysqli_real_escape_string($GLOBALS['dblink'], $_SESSION['social']['id']."@pwd");
	$address_type	= "직장";

	if ($_POST['join_type'] == 'naver') {
		$naver_id = $user_id;

	} elseif ($_POST['join_type'] == 'kakao') {
		$kakao_id = $user_id;
	}
	$nick_name =  $_POST['nick_name'] . "_". $_POST['join_type'];


	//$mobile			= $_POST['mobile_01']."-".$_POST['mobile_02']."-".$_POST['mobile_03'];
	//$phone			= $_POST['phone_01']."-".$_POST['phone_02']."-".$_POST['phone_03'];

	$mobile			= $_POST['mobile'];
	$user_email		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email']);

	$email_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="on"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$sms_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sms_accept'])=="on"?"Y":"N";
	if($sms_accept=="Y"){
		$sms_accept_date = date("Y-m-d H:i:s");
	}
	$kakao_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['kakao_accept'])=="Y"?"Y":"N";
	if($kakao_accept=="Y"){
		$kakao_accept_date = date("Y-m-d H:i:s");
	}

	$arrCheck = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $user_id));

	$comma = "";
	for($i=0;$i<count($_POST['holiday']);$i++){
		$holiday	.= $comma.$_POST['holiday'][$i];
		$comma = ",";
	}

	if($arrCheck["total"] > 0){
		return false;
	}else{
		$sql = "INSERT INTO ".$tbl." set 
			user_id = '".$user_id."',
			naver_id = '".$naver_id."',
			kakao_id = '".$kakao_id."',
			user_pw = password('".$user_pw."'),
			user_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
			nick_name = '".$nick_name."',
			user_status = '0',
			user_level = '1',
			a_class = '4',
			birth = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['birth'])."',
			email = '".$user_email."',
			email_accept = '$email_accept',
			email_accept_date = '$email_accept_date',
			sms_accept = '$sms_accept',
			sms_accept_date = '$sms_accept_date',
			kakao_accept = '$kakao_accept',
			kakao_accept_date = '$kakao_accept_date',
			zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip'])."',
			address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
			address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
			address_type = '".$address_type."',
			etc_1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
			etc_2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_2'])."',
			etc_3 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_3'])."',
			etc_4 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',
			etc_5 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
			etc_6 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',
			etc_7 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',
			etc_8 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',
			etc_9 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',
			etc_10 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',
			holiday = '".mysqli_real_escape_string($GLOBALS['dblink'], $holiday)."',
			company = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['company'])."',
			department = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['department'])."',
			job = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['job'])."',
			duty = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['duty'])."',
			join_type = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['join_type'])."',
			gender = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['gender'])."',
			phone = '".$phone."',
			mobile = '".$mobile."',
			login_last = now(),
			wdate = now(),
			udate = now()
		";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($_POST['address'] && $_POST['address_ext']){	####### 주소가 있으면 배송지 최초 입력
			$sql = "
				INSERT INTO tbl_member_address set 
				user_id = '".$user_id."', d_addr='Y', shipping='기본배송지',
				name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
				zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip'])."',
				address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
				address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
				phone = '".$phone."',
				mobile = '".$mobile."',
				wdate = now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
		}

		//파일처리	
		inputMemberFiles("member", $insert_idx, $_FILES, $thumwidth);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}
}

//회원가입 - 기존회원 / 골든브라운
function joinMemberVIP(){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$birth			= $_POST['birthY']."-".$_POST['birthM']."-".$_POST['birthD'];
	$user_id		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_id']);
	$user_pw		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw']);
	$address_type	= "직장";
	//$mobile			= $_POST['mobile_01']."-".$_POST['mobile_02']."-".$_POST['mobile_03'];
	//$phone			= $_POST['phone_01']."-".$_POST['phone_02']."-".$_POST['phone_03'];

	$mobile			= $_POST['mobile'];
	$user_email		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email']);
	$email_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$sms_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sms_accept'])=="Y"?"Y":"N";
	if($sms_accept=="Y"){
		$sms_accept_date = date("Y-m-d H:i:s");
	}

	$arrCheck = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $user_id));

	$comma = "";
	for($i=0;$i<count($_POST['holiday']);$i++){
		$holiday	.= $comma.$_POST['holiday'][$i];
		$comma = ",";
	}

	if($arrCheck["total"] > 0){
		return false;
	}else{
		$sql = "INSERT INTO ".$tbl." set 
			user_id = '".$user_id."',
			user_pw = password('".$user_pw."'),
			user_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
			user_status = '0',
			user_level = '3',
			birth = '$birth',
			email = '".$user_email."',
			email_accept = '$email_accept',
			email_accept_date = '$email_accept_date',
			sms_accept = '$sms_accept',
			sms_accept_date = '$sms_accept_date',
			zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip'])."',
			address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
			address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
			address_type = '".$address_type."',
			etc_1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
			etc_2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_2'])."',
			etc_3 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_3'])."',
			etc_4 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',
			etc_5 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
			etc_6 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',
			etc_7 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',
			etc_8 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',
			etc_9 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',
			etc_10 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',
			holiday = '".mysqli_real_escape_string($GLOBALS['dblink'], $holiday)."',
			company = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['company'])."',
			department = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['department'])."',
			job = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['job'])."',
			duty = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['duty'])."',
			join_type = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['join_type'])."',
			a_class = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['a_class'])."',
			orderday = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['orderday'])."',
			phone = '".$phone."',
			mobile = '".$mobile."',
			login_last = now(),
			wdate = now(),
			udate = now()
		";

		//echo $sql;
		//exit;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($_POST['address'] && $_POST['address_ext']){	####### 주소가 있으면 배송지 최초 입력
			$sql = "
				INSERT INTO tbl_member_address set 
				user_id = '".$user_id."', d_addr='Y', shipping='기본배송지',
				name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
				zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip'])."',
				address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
				address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
				phone = '".$phone."',
				mobile = '".$mobile."',
				wdate = now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
		}

		//파일처리	
		inputMemberFiles("member", $insert_idx, $_FILES, $thumwidth);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}
}

function inputMemberFiles($boardid, $idx, $tmp, $thumwidth="200"){
	for($i=0;$i<count($_FILES['upfiles']['error']);$i++){
		if ($_FILES['upfiles']['error'][$i] == 0){
		    //확장자 검사후 파일이름 생성
			if(isset($_POST['memo_name'][$i])){
				$memo = $_POST['memo_name'][$i];
			}else{
				$memo = "";
			}
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
				exit();
		    }

			if(!strcmp($extension,"pdf") || !strcmp($extension,"png") || !strcmp($extension,"jpg") || !strcmp($extension,"jpeg")){
				#### (첨부 가능 파일: pdf, png, jpg, jpeg)
		    }else{
				jsMsg("not allowed file extension");
		        jsHistory("-1");
				exit();
			}

			if (is_uploaded_file($_FILES['upfiles']['tmp_name'][$i])) {
				move_uploaded_file ($_FILES['upfiles']['tmp_name'][$i], $GLOBALS["_SITE"]["UPLOADED_DATA"] . "/".$boardid."/".$filerename);
				//썸네일 만들기
				if($filetype=="image/pjpeg" || $filetype=="image/x-png" || $filetype=="image/jpeg" || $filetype=="image/png" || $filetype=="image/gif"){
					@MakeThum($GLOBALS["_SITE"]["UPLOADED_DATA"] . "/".$boardid."/".$filerename, $GLOBALS["_SITE"]["UPLOADED_DATA"] . "/".$boardid."/t_".$filerename, $thumwidth);
				}
			}

			$sql = "insert into ".$GLOBALS["_conf_tbl"]["board_files"]." set 
				boardid='".$boardid."',/*게시판 아이디*/
				b_idx='".$idx."',/* 글 번호 id*/
				ori_name='".$filename."',/*파일원본이름*/
				re_name='".$filerename."',/*md5로 변환된 파일이름*/
				type='".$filetype."',/*파일타입*/
				ext ='".$extension."',/*파일확장자*/
				size='".$filesize."',/*첨부파일 용량*/
				wdate=now()
			";
			$rsf = mysqli_query($GLOBALS['dblink'], $sql);
		}
	}
}

//회원가입
function joinMember_(){
	$tbl = $GLOBALS["_conf_tbl"]["member"];
	$tbl_baby = $GLOBALS["_conf_tbl"]["member_baby"];

	$birth = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['birth']);
	$solar = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['solar'])!=""?mysqli_real_escape_string($GLOBALS['dblink'], $_POST['solar']):"E";
	$member_id = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_id']);
	$email_accept = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$zip = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip']);
	$address_type = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_type'])=="자택"?"자택":"직장";
	$phone = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_3']);
	$mobile = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_3']);
	$fax = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_3']);

	$user_email = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_1']) . "@" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_2']);
	$_POST['etc_1'] =  mysqli_real_escape_string($GLOBALS['dblink'], $_POST['licensenum_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['licensenum_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['licensenum_3']);

	$marriage = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage'])!=""?mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage']):"E";
	$marriage_date = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage_date']);
	$sms_accept = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sms_accept'])=="Y"?"Y":"N";
	if($sms_accept=="Y"){
		$sms_accept_date = date("Y-m-d H:i:s");
	}
	$arrCheck = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $member_id));

	if($arrCheck["total"] > 0){
		return false;
	}else{
		$sql = "INSERT INTO ".$tbl." set 
			user_id = '".$member_id."',
			user_pw = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw'])."',
			regnum1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['regnum1'])."',
			regnum2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['regnum2'])."',
			user_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
			user_status = '0',
			user_level = '1',
			user_memo = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_memo'])."',
			company = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['company'])."',
			department = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['department'])."',
			duty = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['duty'])."',
			birth = '$birth',
			solar = '$solar',
			sex = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sex'])."',
			email = '".$user_email."',
			zip = '".$zip."',
			address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
			address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
			address_type = '".$address_type."',
			phone = '".$phone."',
			mobile = '".$mobile."',
			fax = '".$fax."',
			f_cat = '$implode_f_cat',
			f_product = '$implode_f_product',
			email_accept = '$email_accept',
			email_accept_date = '$email_accept_date',
			sms_accept = '$sms_accept',
			sms_accept_date = '$sms_accept_date',
			marriage = '$marriage',
			marriage_date = '$marriage_date',
			job = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['job'])."',
			etc_1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
			etc_4 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',
			etc_5 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
			etc_6 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',
			etc_7 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',
			etc_8 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',
			etc_9 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',
			etc_10 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',
			login_last = now(),
			wdate = now(),
			udate = now()
		";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){

			//자녀가 있는경우
			if($_POST['babychk']=="Y") {
				for($i=0; $i<$_POST["count"]; $i++) {
					$baby_brith = mysqli_real_escape_string($GLOBALS['dblink'], $_POST["babybirth_".$i]);

					if($_POST["children_value"]=="c_".$i) {
						$children_value = "Y";
					} else {
						$children_value = "N";
					}

					$sql = "INSERT INTO ".$tbl_baby." set 
						user_id = '".$email."',
						babyname = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["babyname_".$i])."',
						prenatal = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["prenatal_".$i])."',
						sex = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["sex_".$i])."',
						birth = '".$baby_brith."',
						children = '".$children_value."',
						wdate = now()
					";
					$rs = mysqli_query($GLOBALS['dblink'], $sql);
				}
			}

			if($_SITE["SHOP"]["POINT"]["JOIN"]>0) {
			//	setPlusPoint($email, $_SITE["SHOP"]["POINT"]["JOIN"], "회원가입 포인트");
			}

			$arrInfo = getCouponInfo("8");

			$coupon_name="회원가입쿠폰";
			$serial = substr(strtoupper(md5($coupon_name.$email.microtime(true))),0,16);
			$edate =  date("Y-m-d", mktime(0,0,0,date("m")+$arrInfo["list"][0]['coupon_content'],date("d"),date("Y")));

			//쿠폰발행
			$sql = "INSERT INTO tbl_mycoupon SET
				user_id='".$email."',
				e_idx='8',
				coupon_no='".$serial."',
				coupon_name='".$coupon_name."',
				coupon_dis='".$arrInfo["list"][0]['coupon_dis']."',
				coupon_unit='".$arrInfo["list"][0]['coupon_unit']."',
				coupon_sdate=now(),
				coupon_edate='".$edate."',
				coupon_use='N',
				over_price='".$arrInfo["list"][0]['over_price']."',
				under_price='".$arrInfo["list"][0]['under_price']."',
				wdate=now()
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			return true;
		}else{
			return false;
		}
	}
}

//회원가입 - 관리자
function joinMemberAdmin(){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$birth			= $_POST['birthY']."-".$_POST['birthM']."-".$_POST['birthD'];
	$user_id		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_id']);
	$user_pw		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw']);
	$address_type	= "직장";
	//$mobile			= $_POST['mobile_01']."-".$_POST['mobile_02']."-".$_POST['mobile_03'];
	//$phone			= $_POST['phone_01']."-".$_POST['phone_02']."-".$_POST['phone_03'];

	$mobile			= $_POST['mobile'];
	$user_email		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email']);
	$email_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$sms_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sms_accept'])=="Y"?"Y":"N";
	if($sms_accept=="Y"){
		$sms_accept_date = date("Y-m-d H:i:s");
	}

	$arrCheck = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $user_id));

	$comma = "";
	for($i=0;$i<count($_POST['holiday']);$i++){
		$holiday	.= $comma.$_POST['holiday'][$i];
		$comma = ",";
	}
	$comma = "";
	for($i=0;$i<count($_POST['orderday']);$i++){
		$orderday	.= $comma.$_POST['orderday'][$i];
		$comma = ",";
	}

	$comma = "";
	for($i=0;$i<count($_POST['child_admin']);$i++){
		$child_admin		.= $comma.$_POST['child_admin'][$i];
		$child_wdate		.= $comma.$_POST['child_wdate'][$i];
		$comma = "||";
	}

	if($arrCheck["total"] > 0){
		return false;
	}else{
		$sql = "INSERT INTO ".$tbl." set 
			user_id = '".$user_id."',
			user_pw = password('".$user_pw."'),
			user_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
			user_status = '0',
			user_level = '1',
			birth = '$birth',
			email = '".$user_email."',
			email_accept = '$email_accept',
			email_accept_date = '$email_accept_date',
			sms_accept = '$sms_accept',
			sms_accept_date = '$sms_accept_date',
			zip = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip'])."',
			address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
			address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
			address_type = '".$address_type."',
			etc_1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
			etc_2 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_2'])."',
			etc_3 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_3'])."',
			etc_4 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',
			etc_5 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
			etc_6 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',
			etc_7 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',
			etc_8 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',
			etc_9 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',
			etc_10 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',
			holiday = '".mysqli_real_escape_string($GLOBALS['dblink'], $holiday)."',
			company = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['company'])."',
			department = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['department'])."',
			job = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['job'])."',
			duty = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['duty'])."',
			join_type = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['join_type'])."',
			a_class = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['a_class'])."',
			orderday = '".$orderday."',
			phone = '".$phone."',
			mobile = '".$mobile."',
			`before` = 'N',
			gender = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['gender']). "',
			child_admin = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_admin) . "',
			child_wdate = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_wdate) . "',		      
			child_violation = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation) . "',
			child_category = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_category) . "',
			child_violation_wdate = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_wdate) . "',
			child_violation_start_date = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_start_date) . "',
			child_violation_end_date = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_end_date) . "',
			login_last = now(),
			wdate = now(),
			udate = now()
		";
//		echo $sql;
//		exit;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		//파일처리	
		inputMemberFiles("member", $insert_idx, $_FILES, $thumwidth);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}
}

//회원정보 수정
function editMember($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$mobile			= $_POST['mobile'];
	$phone			= $_POST['mobile'];
	$user_email		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email']);

	$email_accept = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$sms_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sms_accept'])=="Y"?"Y":"N";
	if($sms_accept=="Y"){
		$sms_accept_date = date("Y-m-d H:i:s");
	}
	$kakao_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['kakao_accept'])=="Y"?"Y":"N";
	if($kakao_accept=="Y"){
		$kakao_accept_date = date("Y-m-d H:i:s");
	}
	$sql_add = "";
	if($_POST['user_pw'] && $_POST['user_pw1'] && $_POST['user_pw'] == $_POST['user_pw1']){
		$sql_add .= " user_pw = password('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw'])."'), ";
	}

	if($_POST['user_pw'] !=""){
		$sql_pw = " user_pw = password('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw'])."'), ";
	}

	$sql = "UPDATE ".$tbl." SET
		$sql_pw
		$sql_add
		email = '".$user_email."',
		email_accept = '$email_accept',
		email_accept_date = '$email_accept_date',
		sms_accept = '$sms_accept',
		sms_accept_date = '$sms_accept_date',
		kakao_accept = '$kakao_accept',
		kakao_accept_date = '$kakao_accept_date',
		user_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
		nick_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['nick_name'])."',
		birth = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['birth'])."',
		phone = '".$phone."',
		mobile = '".$mobile."',
		`before` = 'N',
		address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
		address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
		etc_1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
		gender = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['gender'])."',
		udate = now()
		WHERE user_id='$id'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//파일처리	
	inputMemberFiles("member", $_POST['idx'], $_FILES, $thumwidth);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원정보 수정
function editMember_golden($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql_add = "";
	if($_POST['user_pw'] && $_POST['user_pw2'] && $_POST['user_pw'] == $_POST['user_pw2']){
		$sql_add .= " user_pw = password('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw'])."'), ";
	}
	if($_POST['mobile']!=$_POST['mobile_old']){
		$sql_add .= " user_editdt = now(), ";
	}


	$comma = "";
	for($i=0;$i<count($_POST['holiday']);$i++){
		$holiday	.= $comma.$_POST['holiday'][$i];
		$comma = ",";
	}
	$sql = "UPDATE ".$tbl." SET
		$sql_add
		etc_5 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
		etc_6 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',
		holiday = '".mysqli_real_escape_string($GLOBALS['dblink'], $holiday)."',
		user_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',	
		mobile = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile'])."',	
		udate = now()
		WHERE user_id='$id'
	";

	//echo $sql;
	//exit;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원정보 수정
function editMemberEngName($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql = "UPDATE ".$tbl." SET
		engname01 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['engname01'])."',
		engname02 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['engname02'])."'
		WHERE user_id='$id'
	";
	//echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원정보 수정 - 관리자용
function editMemberAdmin($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	if($_POST['user_pw'] !="" && $_POST['user_pw'] !="" && $_POST['user_pw'] == $_POST['user_pw2']){
		$sql_pw = " user_pw = password('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw'])."'), ";
	}

	$email_accept = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$sms_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sms_accept'])=="Y"?"Y":"N";
	if($sms_accept=="Y"){
		$sms_accept_date = date("Y-m-d H:i:s");
	}
	$kakao_accept	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['kakao_accept'])=="Y"?"Y":"N";
	if($kakao_accept=="Y"){
		$kakao_accept_date = date("Y-m-d H:i:s");
	}

	$comma = "";
	for($i=0;$i<count($_POST['child_admin']);$i++){
		$child_admin		.= $comma.$_POST['child_admin'][$i];
		$child_wdate		.= $comma.$_POST['child_wdate'][$i];
		$comma = "||";
	}

	$comma = "";
	for($i=0;$i<count($_POST['child_violation']);$i++){
		$child_violation    .= $comma.$_POST['child_violation'][$i];
		$child_category     .= $comma.$_POST['child_category'][$i];
		$child_violation_wdate .= $comma.$_POST['child_violation_wdate'][$i];
		$child_violation_start_date .= $comma.$_POST['child_violation_start_date'][$i];
		$child_violation_end_date .= $comma.$_POST['child_violation_end_date'][$i];
		$comma = "||";
	}

	$sql = "UPDATE ".$tbl." SET
		$sql_pw
		user_level = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_level'])."',
		address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
		address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
		etc_1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
		user_memo = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_memo'])."',
		user_name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_name'])."',
		email = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email'])."',
		email_accept = '$email_accept',
		sms_accept = '$sms_accept',
		kakao_accept = '$kakao_accept',
		gender = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['gender'])."',
		mobile = '".(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile']))."',
		birth = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['birth'])."',
		`before` = 'N',
		child_admin = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_admin) . "',
		child_wdate = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_wdate) . "',		      
		child_violation = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation) . "',
		child_category = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_category) . "',
		child_violation_wdate = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_wdate) . "',
		child_violation_start_date = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_start_date) . "',
		child_violation_end_date = '" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_end_date) . "',
		udate = now()
		WHERE user_id='$id'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);


	//파일처리	
	inputMemberFiles("member", $_POST['idx'], $_FILES, $thumwidth);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원정보 수정 - 관리자용
function editMemberAdmin_($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	if($_POST['user_pw'] !="" && $_POST['user_pw'] !="" && $_POST['user_pw'] == $_POST['user_pw2']){
		$sql_pw = " user_pw = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw'])."', ";
	}

	$birth = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['birth']);
	$solar = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['solar'])=="S"?"S":"L";
	$sex = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sex'])=="M"?"M":"F";
	$email_accept = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$user_status = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_status'])=="1"?"1":"0";
	$user_level = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_level']);
	$zip = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip']);
	$address_type = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_type'])=="자택"?"자택":"직장";
	$phone = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_3']);
	$mobile = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_3']);
	$fax = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_3']);

	$marriage = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage'])!=""?mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage']):"E";
	$marriage_date = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage_date']);
	$email_accept = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}

	$sql = "UPDATE ".$tbl." SET
		$sql_pw
		user_status = '$user_status',
		user_level = '$user_level',
		user_memo = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_memo'])."',
		company = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['company'])."',
		department = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['department'])."',
		duty = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['duty'])."',
		birth = '$birth',
		solar = '$solar',
		sex = '$sex',
		zip = '".$zip."',
		address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
		address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
		address_type = '".$address_type."',
		phone = '".$phone."',
		mobile = '".$mobile."',
		fax = '".$fax."',
		f_cat = '$implode_f_cat',
		f_product = '$implode_f_product',
		email_accept = '$email_accept',
		email_accept_date = '$email_accept_date',
		sms_accept = '$sms_accept',
		sms_accept_date = '$sms_accept_date',
		marriage = '$marriage',
		marriage_date = '$marriage_date',
		job = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['job'])."',
		etc_4 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',
		etc_5 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
		etc_6 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',
		etc_7 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',
		etc_8 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',
		etc_9 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',
		etc_10 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',
		gender = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['gender'])."',
		birth = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['birth'])."',
		udate = now()
		WHERE user_id='$id'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원정보 수정
function editMember_($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];
	$tbl_baby = $GLOBALS["_conf_tbl"]["member_baby"];

	if($_POST['user_pw'] !="" && $_POST['user_pw'] !="" && $_POST['user_pw'] == $_POST['user_pw2']){
		$sql_pw = " user_pw = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw'])."', ";
	}

	$birth = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['byear'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['bmonth'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['bday']);
	$solar = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['solar'])=="S"?"S":"L";
	//$sex = substr($_POST['regnum2'],0,1)%2==1?"M":"F";
	$email = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_id']) . "@" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_domain']);
	$email_accept = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}
	$zip = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip']);
	$address_type = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_type'])=="자택"?"자택":"직장";
	$phone = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone_3']);
	$mobile = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile_3']);
	$fax = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['fax_3']);

	$user_email = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_1']) . "@" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_2']);
	$_POST['etc_1'] =  mysqli_real_escape_string($GLOBALS['dblink'], $_POST['licensenum_1']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['licensenum_2']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['licensenum_3']);

	$marriage = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage'])!=""?mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage']):"E";
	$marriage_date = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['marriage_date']);
	$email_accept = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_accept'])=="Y"?"Y":"N";
	if($email_accept=="Y"){
		$email_accept_date = date("Y-m-d H:i:s");
	}

	$sql = "UPDATE ".$tbl." SET
		$sql_pw						
		regnum1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['regnum1'])."',
		email = '".$user_email."',
		company = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['company'])."',
		department = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['department'])."',
		zip = '".$zip."',
		address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
		address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
		phone = '".$phone."',
		mobile = '".$mobile."',
		fax = '".$fax."',
		sms_accept = '$sms_accept',
		sms_accept_date = '$sms_accept_date',
		job = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['job'])."',
		etc_1 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
		etc_4 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',
		etc_5 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
		etc_6 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',
		etc_7 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',
		etc_8 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',
		etc_9 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',
		etc_10 = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',		
		udate = now()
		WHERE user_id='$id'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//자녀 삭제
	$sql = "DELETE FROM ".$tbl_baby." WHERE user_id='$id'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//자녀가 있는경우
	if($_POST['babychk']=="Y") {
		for($i=0; $i<$_POST["count"]; $i++) {
			$baby_brith = mysqli_real_escape_string($GLOBALS['dblink'], $_POST["byear_".$i])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["bmonth_".$i])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["bday_".$i]);

			if($_POST["babyname_".$i]) {

				if($_POST["children_value"]=="c_".$i) {
					$children_value = "Y";
				} else {
					$children_value = "N";
				}

				$sql = "INSERT INTO ".$tbl_baby." set 
					user_id = '".$id."',
					babyname = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["babyname_".$i])."',
					prenatal = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["prenatal_".$i])."',
					sex = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["sex_".$i])."',
					birth = '".$baby_brith."',
					children = '".$children_value."',
					wdate = now()
				";
				$rs = mysqli_query($GLOBALS['dblink'], $sql);
			}
		}
	}
	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원등급 수정
function getMemberLevelUpdate($idx, $lval, $child_violation, $child_category, $child_violation_wdate, $child_violation_start_date, $child_violation_end_date){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	if ($child_violation_wdate) {
		// Retrieve existing data
		$sql = "SELECT child_violation, child_category, child_violation_wdate FROM " . $tbl . " WHERE idx='$idx'";
		$result = mysqli_query($GLOBALS['dblink'], $sql);
		$row = mysqli_fetch_assoc($result);

		$existing_child_violation = $row['child_violation'];
		$existing_child_category = $row['child_category'];
		$existing_child_violation_wdate = $row['child_violation_wdate'];
		$existing_child_violation_start_date = $row['child_violation_start_date'];
		$existing_child_violation_end_date = $row['child_violation_end_date'];

		// Append new data
		// Append new data only if existing data is not empty
		$new_child_violation = $existing_child_violation ? $existing_child_violation . "||" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation) : mysqli_real_escape_string($GLOBALS['dblink'], $child_violation);
		$new_child_category = $existing_child_category ? $existing_child_category . "||" . mysqli_real_escape_string($GLOBALS['dblink'], $child_category) : mysqli_real_escape_string($GLOBALS['dblink'], $child_category);
		$new_child_violation_wdate = $existing_child_violation_wdate ? $existing_child_violation_wdate . "||" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_wdate) : mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_wdate);
		$new_child_violation_start_date = $existing_child_violation_start_date ? $existing_child_violation_start_date . "||" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_start_date) : mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_start_date);
		$new_child_violation_end_date = $existing_child_violation_end_date ? $existing_child_violation_end_date . "||" . mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_end_date) : mysqli_real_escape_string($GLOBALS['dblink'], $child_violation_end_date);

		$sub_sql = "child_violation = '" . $new_child_violation . "',
             child_category = '" . $new_child_category . "',
             child_violation_wdate = '" . $new_child_violation_wdate . "',
             child_violation_start_date = '" . $new_child_violation_start_date . "',
             child_violation_end_date = '" . $new_child_violation_end_date . "',";
	}

	$sql = "UPDATE ".$tbl." SET
		$sub_sql
		user_level = '". $lval ."'
		WHERE idx='$idx'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원비밀번호 수정
function editPasswd($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	/*$arrCheck = getUserInfo($id);

	if($arrCheck["list"][0]["user_pw"]!=mysqli_real_escape_string($GLOBALS['dblink'], $_POST['now_pw'])) {
		return false;
	}*/

	$user_pw		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw']);

	$sql = "UPDATE ".$tbl." SET
		user_pw = password('".$user_pw."')
		WHERE user_id='$id'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

// 닉네임으로 비밀번호 변경
function editPasswdNnickname($nick_name){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql = "UPDATE ".$tbl." SET
		user_pw = password('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw'])."')
		WHERE nick_name='$nick_name'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원정보 가져오기 - 사업자번호 중복체크용
function getUserFindCompanyNumber($etc_1){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE etc_1='$etc_1' ";
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


//회원정보 가져오기
function getUserInfo($id, $level=""){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * FROM ".$tbl." WHERE user_id = '$id' ";
	if($level != "") {
		$sql .= " AND user_level !='80' AND user_level !='90' ";
	}
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


	$idx = $list['list'][0]['idx'];
	//파일정보 가져오기
    $sql  = "SELECT * ";
    $sql .= "FROM ".$GLOBALS["_conf_tbl"]["board_files"]." ";
    $sql .= "WHERE boardid = 'member' ";
    $sql .= "AND b_idx = '$idx' order by idx desc";
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

	return $list;
}

//통합회원정보 가져오기
function getUserInfoUnite($id, $level="", $unitetype){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * FROM ".$tbl;
	if($unitetype=="naver"){
		$sql  .= " WHERE naver_id = '$id' ";
	}else if($unitetype=="kakao"){
		$sql  .= " WHERE kakao_id = '$id' ";
	}else{
		$sql  .= " WHERE user_id = '$id' ";
	}


	if($level != "") {
		$sql .= " AND user_level !='80' AND user_level !='90' ";
	}
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

//회원정보 가져오기
function getUserInfoNnickname($nick_name){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$nick_name_naver = $nick_name . "_naver";
	$nick_name_kakao = $nick_name . "_kakao";

	$sql  = "SELECT * FROM ".$tbl." WHERE nick_name = '$nick_name' OR nick_name = '$nick_name_naver' OR nick_name = '$nick_name_kakao'";

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

function getMemberInfoByNameAndMobile($name, $mobile) {
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$name = mysqli_real_escape_string($GLOBALS['dblink'], $name);
	$mobile = mysqli_real_escape_string($GLOBALS['dblink'], $mobile);

	// Encode the name and mobile values using Base64
	$encoded_name = base64_encode($name);
	$encoded_mobile = base64_encode($mobile);

	// Format the mobile number with dashes
	$formatted_mobile = substr($mobile, 0, 3) . '-' . substr($mobile, 3, 4) . '-' . substr($mobile, 7);
	$encoded_formatted_mobile = base64_encode($formatted_mobile);

	$sql = "SELECT * FROM $tbl 
            WHERE (user_name = '$encoded_name' OR user_name = '$name') 
            AND (mobile = '$encoded_mobile' OR mobile = '$encoded_formatted_mobile' OR mobile = '$mobile' OR mobile = '$formatted_mobile')";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if ($total_rs > 0) {
		$list['total'] = $total_rs;
		for ($i = 0; $i < $total_rs; $i++) {
			$list['list'][$i] = mysqli_fetch_assoc($rs);
		}
	} else {
		$list['total'] = 0;
	}

	return $list;
}



//기간별회원
function getMemberInfo($sdate, $edate) {
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT count(*) as num ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE 1=1 ";
	if($sdate){
		$sql .= " AND wdate >= '".mysqli_real_escape_string($GLOBALS['dblink'], $sdate)." 00:00:00' ";
	}
	if($edate){
		$sql .= " AND wdate <= '".mysqli_real_escape_string($GLOBALS['dblink'], $edate)." 23:59:59' ";
	}
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

//회원정보 가져오기 - 핸드폰 중복체크용
function getUserFindMobile($mobile){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	// 하이픈 제거
	$mobile = str_replace("-", "", $mobile);

	// 하이픈 포함 형식 생성
	$formatted_mobile = substr($mobile, 0, 3).'-'.substr($mobile, 3, 4).'-'.substr($mobile, 7);

	// base64 인코딩 버전 생성
	$encoded_mobile = base64_encode($mobile);
	$encoded_formatted_mobile = base64_encode($formatted_mobile);

	$sql = "SELECT * FROM ".$tbl." 
            WHERE REPLACE(mobile,'-','') = '".$mobile."' 
            OR mobile = '".$formatted_mobile."'
            OR mobile = '".$encoded_mobile."'
            OR mobile = '".$encoded_formatted_mobile."'";

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


//회원정보 가져오기 - 아이디 찾기용
function getUserFindEmail($name, $email){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_name = '$name' AND email='$email'";
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

//회원정보 가져오기 - 아이디 찾기용
function getUserFindID($name, $mobile,$email=""){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$mobile = str_replace("-","",$mobile);

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_name = '$name' AND REPLACE(mobile,'-','')='$mobile' ";
	if($email){
		$sql .= "  AND email='$email' ";
	}

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	//echo $sql;

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

//회원정보 가져오기 - 아이디 찾기용
function getUserPassSearch($uid, $name){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_id = '$uid' AND user_name = '$name' ";

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

//회원정보 가져오기 - 아이디 찾기용
function getUserFindPassW($uid, $email, $mobile){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$mobile = str_replace("-","",$mobile);

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_id = '$uid' AND email = '$email' AND REPLACE(mobile,'-','')='$mobile'";

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

//비밀번호 변경 - 비밀번호 찾기용
function getUserFindPWedit($uid, $name, $mobile){
	$tbl = $GLOBALS["_conf_tbl"]["member"];
	$mobile = str_replace("-","",$mobile);

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_id = '$uid' AND REPLACE(mobile,'-','')='$mobile'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	//echo $sql;

	if($total_rs > 0){

		$user_pw = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['user_pw']);

		$sql = "UPDATE ".$tbl." SET
			user_pw = password('".$user_pw."')
			WHERE user_id='$uid' and  REPLACE(mobile,'-','')='$mobile'
		";
		//echo $sql;
		//exit;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
	}else{
		return false;
	}

	if($rs){
		return true;
	}else{
		return false;
	}
}

//회원정보 가져오기 - 비밀번호 찾기용
function getUserFindPW($name, $email, $uid){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_name = '$name' AND  email='$email' AND user_id='$uid' ";

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

//회원정보 가져오기 - 로그인용
function loginMember($id, $pw){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_id = '$id' AND user_pw = password('$pw') ";
	//$sql .= " AND  user_level != '90' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if($total_rs > 0){
			//로그인정보 기록
			mysqli_query($GLOBALS['dblink'], "update ".$GLOBALS["_conf_tbl"]["member"]." set login_count = login_count + 1, login_last = now() WHERE user_id='$id' ");
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;
}
function loginMemberBefore($id, $inputPassword) {
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	// Java의 MessageDigest.getInstance("SHA-256") 방식과 동일하게 구현
	$md = hash_init('sha256');

	// 먼저 userId(salt)로 업데이트
	hash_update($md, $id);

	// 그 다음 비밀번호로 업데이트
	hash_update($md, $inputPassword);

	// 최종 해시 생성 및 base64 인코딩
	$hashedPassword = base64_encode(hash_final($md, true));

	// Prepare the SQL query to find the user with the given ID and hashed password
	$sql = "SELECT * FROM $tbl WHERE user_id = '$id' AND user_pw = '$hashedPassword'";

	// Execute the query
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if ($total_rs > 0) {
		// Login successful
		// Update login information
		mysqli_query($GLOBALS['dblink'], "UPDATE $tbl SET login_count = login_count + 1, login_last = now() WHERE user_id = '$id'");

		$list['total'] = $total_rs;
		for ($i = 0; $i < $total_rs; $i++) {
			$list['list'][$i] = mysqli_fetch_assoc($rs);
		}
	} else {
		// Login failed
		$list['total'] = 0;
	}

	return $list;
}

//회원정보 가져오기 - 통합회원 로그인용
function loginMemberUnite($id, $pw, $snstype){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	if($snstype=="naver"){
		$sql .= "WHERE naver_id = '$id' AND naver_pw = password('$pw') ";
	}else if($snstype=="kakao"){
		$sql .= "WHERE kakao_id = '$id' AND kakao_pw = password('$pw') ";
	}else{
		$sql .= "WHERE user_id = '$id' AND user_pw = password('$pw') ";
	}
//	$sql .= "WHERE user_id = '$id' AND idx = '$pw' ";
	//$sql .= " AND  user_level != '90' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	// echo $sql;

	if($total_rs > 0){
		//로그인정보 기록
		mysqli_query($GLOBALS['dblink'], "update ".$GLOBALS["_conf_tbl"]["member"]." set login_count = login_count + 1, login_last = now() WHERE user_id='$id' ");
		$list['total'] = $total_rs;
		for($i=0; $i < $total_rs; $i++){
				$list['list'][$i] = mysqli_fetch_assoc($rs);
		}
		if($snstype=="naver" || $snstype=="kakao"){
			$list['list'][0]["join_type"] = "unite";
		}
	}else{
		if($snstype=="naver" || $snstype=="kakao"){		## 통합회원이 아닌 sns 가입 회원인경우
			$sql  = "SELECT * FROM ".$tbl." WHERE user_id = '$id' AND user_pw = password('$pw') ";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$total_rs = mysqli_num_rows($rs);

			if($total_rs > 0){
				//로그인정보 기록
				mysqli_query($GLOBALS['dblink'], "update ".$GLOBALS["_conf_tbl"]["member"]." set login_count = login_count + 1, login_last = now() WHERE user_id='$id' ");
				$list['total'] = $total_rs;
				for($i=0; $i < $total_rs; $i++){
						$list['list'][$i] = mysqli_fetch_assoc($rs);
				}
			}else{
				$list['total'] = 0;
			}
		}else{
			$list['total'] = 0;
		}
	}

	return $list;
}
// 회원탈퇴 검색용
function loginMemberSearch($id, $pw, $sns){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	if($sns=='kakao' || $sns=='naver'){
		$sql .= "WHERE user_id = '$id' ";
	}else{
		$sql .= "WHERE user_id = '$id' AND user_pw = password('$pw') ";
	}
//	$sql .= "WHERE user_id = '$id' AND idx = '$pw' ";
	//$sql .= " AND  user_level != '90' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	// echo $sql;

	if($total_rs > 0){
			//로그인정보 기록
			mysqli_query($GLOBALS['dblink'], "update ".$GLOBALS["_conf_tbl"]["member"]." set login_count = login_count + 1, login_last = now() WHERE user_id='$id' ");
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;
}

//회원정보 가져오기 - 로그인용 / 암호화
function loginMemberPassword($id, $pw){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_id = '$id' AND user_pw = password('$pw') ";
	//$sql .= " AND  user_level != '90' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if($total_rs > 0){
			//로그인정보 기록
			mysqli_query($GLOBALS['dblink'], "update ".$GLOBALS["_conf_tbl"]["member"]." set login_count = login_count + 1, login_last = now() WHERE user_id='$id' ");
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;
}

//회원정보 가져오기 - 로그인용 / 암호화 / 관리자 임시로그인용
function loginMemberPasswordAdmin($id, $pw){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_id = '$id' ";
	//$sql .= " AND  user_level != '90' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);

	if($total_rs > 0){
			//로그인정보 기록
			mysqli_query($GLOBALS['dblink'], "update ".$GLOBALS["_conf_tbl"]["member"]." set login_count = login_count + 1, login_last = now() WHERE user_id='$id' ");
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;
}

//회원정보 가져오기 - 회원탈퇴용
function withdrawalMember($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_id = '$id' and user_pw = PASSWORD('".$_POST['user_pw']."') ";
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

//회원정보 가져오기 - 회원탈퇴용 카카오/네이버용
function withdrawalMemberSNS($id){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE user_id = '$id' ";
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

//회원삭제
function outMemberAdmin($idx){
	//회원정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["member"];
	//삭제권한 설정
	$deletePerm = false;
	//관리자는 그냥 통과
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT" || @in_array("member_manage",$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["AUTH"])){
		$deletePerm = true;
	}
	if($deletePerm==true){
		//회원 정보 
		$sql = "DELETE FROM ".$tbl." WHERE idx in (".$idx.")";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_affected_rows($GLOBALS['dblink']);
		if($total_rs > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}


//회원탈퇴
function deleteMemberAdmin($idx){
	//회원정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["member"];
	//삭제권한 설정
	$deletePerm = false;
	//관리자는 그냥 통과
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT" || @in_array("member_manage",$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["AUTH"])){
		$deletePerm = true;
	}
	if($deletePerm==true){
		//회원 정보 
		$sql = "UPDATE ".$tbl." SET user_level = '90', outdt = now() WHERE idx in (".$idx.")";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_affected_rows($GLOBALS['dblink']);
		if($total_rs > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}


//회원탈퇴
function deleteMember($id){
	//회원정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["member"];
	$tbl_point = $GLOBALS["_conf_tbl"]["point"];
	$tbl_order = $GLOBALS["_conf_tbl"]["shop_order_info"];
	$tbl_mycoupon = $GLOBALS["_conf_tbl"]["mycoupon"];
	$tbl_mygiftcard = $GLOBALS["_conf_tbl"]["mygiftcard"];

	//회원 정보 
	$sql = "UPDATE ".$tbl." SET user_level = '90' , outdt = now() WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);
	/*
	//적립금 테이블 탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_point." SET user_id='deleted_user' WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//주문정보 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_order." SET order_id='deleted_user' WHERE order_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//쿠폰 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_mycoupon." SET user_id='deleted_user' WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//상품권 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_mygiftcard." SET user_id='deleted_user' WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	*/
	if($total_rs > 0){
		return true;
	}else{
		return false;
	}
}

//회원삭제
function outMember($id){
	$tbl			= $GLOBALS["_conf_tbl"]["member"];					//회원정보 테이블
	$tbl_point		= $GLOBALS["_conf_tbl"]["point"];					//포인트
	$tbl_order		= $GLOBALS["_conf_tbl"]["shop_order_info"];			//주문정보
	$tbl_mycoupon	= $GLOBALS["_conf_tbl"]["mycoupon"];				//쿠폰
	$tbl_mygiftcard = $GLOBALS["_conf_tbl"]["mygiftcard"];				//상품권
	$tbl_shop_wish	= $GLOBALS["_conf_tbl"]["shop_wish"];				//관심상품
	$tbl_board_qna	= "tbl_board_qna";									//문의내역
	$tbl_member_add	= "tbl_member_address";								//배송지

	//회원 정보 
	$sql = "DELETE FROM ".$tbl." WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

	################################# 모든 회원관련 정보의 아이디값 변경 ################################# ST
	//적립금 테이블 탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_point." SET user_id='deleted_user' WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//주문정보 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_order." SET order_id='deleted_user' WHERE order_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//쿠폰 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_mycoupon." SET user_id='deleted_user' WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//상품권 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_mygiftcard." SET user_id='deleted_user' WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//관심상품 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_shop_wish." SET user_id='deleted_user' WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//문의내역 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_board_qna." SET w_user='deleted_user' WHERE w_user='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//배송지 테이블  탈퇴회원아이디로 업데이트
	$sql = "UPDATE ".$tbl_member_add." SET user_id='deleted_user' WHERE user_id='".$id."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	################################# 모든 회원관련 정보의 아이디값 변경 ################################# ED

	if($total_rs > 0){
		return true;
	}else{
		return false;
	}
}

//배송지 목록
function getAddressList($user_id, $scale, $offset=0){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["member_address"];

    $sql = "SELECT * FROM $tbl WHERE user_id='$user_id' ";
	$sql .= " order by d_addr, idx desc ";

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
    }

    return $list;
}

//회원정보 가져오기
function getAddressInfo($idx){
	$tbl = $GLOBALS["_conf_tbl"]["member_address"];

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

//배송지입력
function insertAddress($user_id){
	$tbl = $GLOBALS["_conf_tbl"]["member_address"];

	$d_addr = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['d_addr'])=="Y"?"Y":"N";
	$zip = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip']);
	$phone = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone01']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone02']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone03']);
	$mobile = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile01']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile02']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile03']);
	if($_POST['msg']=="기타"){
		$msg = $_POST['msg_text'];
	}else{
		$msg = $_POST['msg'];
	}

	if($d_addr=="Y") { //다른곳 기본주소초기화
		$sql_up = "UPDATE ".$tbl." set 
			d_addr = 'N'
			where user_id = '".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]."'
		";
		$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);
	}

	$sql = "INSERT INTO ".$tbl." set 
		shipping = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['shipping'])."',
		d_addr = '".$d_addr."',
		user_id = '".$user_id."',
		name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['name'])."',
		zip = '".$zip."',
		address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
		address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
		phone = '".mysqli_real_escape_string($GLOBALS['dblink'], $phone)."',
		mobile = '".mysqli_real_escape_string($GLOBALS['dblink'], $mobile)."',
		email = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email'])."',
		msg = '".mysqli_real_escape_string($GLOBALS['dblink'], $msg)."',
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

//기본배송지
function defaultAddress($idx){
	$tbl = $GLOBALS["_conf_tbl"]["member_address"];

	$sql_up = "UPDATE ".$tbl." set 
		d_addr = 'N'
		where user_id = '".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]."'
	";
	$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);


	$sql = "UPDATE ".$tbl." set 
		d_addr = 'Y'
		where idx = '".$idx."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//배송지수정
function editAddress($idx){
	$tbl = $GLOBALS["_conf_tbl"]["member_address"];

	$d_addr = $_POST['d_addr']=="Y"?"Y":"N";
	$zip = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['zip']);
	$phone = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone01']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone02']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['phone03']);
	$mobile = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile01']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile02']) . "-" . mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mobile03']);
	if($_POST['msg']=="기타"){
		$msg = $_POST['msg_text'];
	}else{
		$msg = $_POST['msg'];
	}

	if($_POST['d_addr']=="Y") { //다른곳 기본주소초기화
		$sql_up = "UPDATE ".$tbl." set 
			d_addr = 'N'
			where user_id = '".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]."'
		";
		$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);
	}

	$sql = "UPDATE ".$tbl." set 
		shipping = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['shipping'])."',
		d_addr = '".$d_addr."',
		name = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['name'])."',
		zip = '".$zip."',
		address = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address'])."',
		address_ext = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['address_ext'])."',
		phone = '".mysqli_real_escape_string($GLOBALS['dblink'], $phone)."',
		mobile = '".mysqli_real_escape_string($GLOBALS['dblink'], $mobile)."',
		email = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email'])."',
		msg = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['msg'])."'
		where idx = '".$idx."'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//배송지 삭제
function deleteAddress($idx){
	//회원정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["member_address"];

	//회원 정보 삭제
	$sql = "DELETE FROM ".$tbl." WHERE idx='".$idx."'	";
	//echo $sql;
	//exit;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

	if($total_rs > 0){
		return true;
	}else{
		return false;
	}
}

############################################################ 로보월드 전용
//회원정보 가져오기
function getUserInfoBoard($id){
	$tbl = "tbl_board_apply";

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE registration = '$id' order by category desc, idx desc";

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
//회원정보 가져오기
function getUserInfoBoard2($id){
	$tbl = "tbl_board_apply_user";

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE email = '$id' order by category desc, idx desc ";

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
//회원정보 가져오기 - 로그인용
function loginMemberBoard2($id, $pw){
	$tbl = "tbl_board_apply_user";

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE email = '$id' AND pass = '$pw' order by category desc, idx desc ";
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
//회원정보 가져오기 - 로그인용
function loginMemberBoard($id, $pw){
	$tbl = "tbl_board_apply";

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE registration = '$id' AND pass = '$pw' order by category desc, idx desc ";
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
//회원정보 가져오기 - 이메일
function getUserCheckInfo($etc2){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * FROM ".$tbl." ";
	$sql .= "WHERE etc_2 = '$etc2' ";

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

function getDecryptPw($pw){
    $sql  = "SELECT PASSWORD('". $pw ."') as pw ";
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


#################################################### CS 관련 ################################################

function getCsList($m_idx,$scale, $offset=0){
	$tbl = "tbl_member_cs";

    $sql = "SELECT * FROM $tbl WHERE 1=1 and m_idx = '$m_idx' ";
	//echo $sql;
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
			$sql .= " order by idx desc ";
				//scale 0 으로 지정시에는 전체 가져옴
			if($scale > 0){
		    	$sql .= " limit $offset,$scale ";
			}
		    $rs = mysqli_query($GLOBALS['dblink'],$sql);

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

function insertCs(){
	$tbl = "tbl_member_cs";

	$m_idx = mysqli_real_escape_string($GLOBALS["dblink"],$_POST["m_idx"]);
	$category = mysqli_real_escape_string($GLOBALS["dblink"],$_POST["category"]);
	$contents = mysqli_real_escape_string($GLOBALS["dblink"],$_POST["contents"]);
	$schdule_date = $_POST["schdule_date"] == ""?"0000-00-00":mysqli_real_escape_string($GLOBALS["dblink"],$_POST["schdule_date"]);
	$name = mysqli_real_escape_string($GLOBALS["dblink"],$_POST["name"]);

	$sql = "
	insert into
		$tbl
	SET
	  `m_idx` = '$m_idx',
	  `category` = '$category',
	  `contents` = '$contents',
	  `schdule_date` = '$schdule_date',
	  `name` = '$name',
	  `wdate` = now(),
	  `udate` = now()
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

function updateCs($idx){
	$tbl = "tbl_member_cs";

	$category = mysqli_real_escape_string($GLOBALS["dblink"],$_POST["category"]);
	$contents = mysqli_real_escape_string($GLOBALS["dblink"],$_POST["contents"]);
	$schdule_date = $_POST["schdule_date"] == ""?"0000-00-00":mysqli_real_escape_string($GLOBALS["dblink"],$_POST["schdule_date"]);
	$name = mysqli_real_escape_string($GLOBALS["dblink"],$_POST["name"]);

	$sql = "
	update
		$tbl
	SET
	  `category` = '$category',
	  `contents` = '$contents',
	  `schdule_date` = '$schdule_date',
	  `name` = '$name',
	  `udate` = now()
	where idx = '$idx'
	";


	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

function deleteCs($idx){
	$tbl = "tbl_member_cs";

	$sql = "delete from $tbl where idx = '$idx'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

function updateMemberLevelByViolation() {
	$tbl = $GLOBALS["_conf_tbl"]["member"];
	$today = date('Y-m-d');

	$sql = "UPDATE $tbl SET user_level = '1' 
            WHERE (
                SELECT MAX(date_val) <= '$today'
                FROM (
                    SELECT IF(
                        value REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$',
                        value,
                        '0000-00-00'
                    ) as date_val
                    FROM (
                        SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(t.value, '||', n.n), '||', -1) value
                        FROM (
                            SELECT child_violation_end_date as value
                            FROM $tbl
                            WHERE child_violation_end_date IS NOT NULL 
                            AND child_violation_end_date != ''
                        ) t CROSS JOIN (
                            SELECT a.N + b.N * 10 + 1 n
                            FROM (
                                SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL 
                                SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL 
                                SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                            ) a,
                            (
                                SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL 
                                SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL 
                                SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                            ) b
                            ORDER BY n
                        ) n
                        WHERE n.n <= 1 + (LENGTH(t.value) - LENGTH(REPLACE(t.value, '||', '')))
                    ) as dates
                    WHERE value != ''
                ) as valid_dates
                WHERE date_val != '0000-00-00'
            ) 
            AND (user_level = '3' OR user_level = '6')
            AND child_violation_end_date IS NOT NULL 
            AND child_violation_end_date != ''";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	return $rs ? true : false;
}

?>
