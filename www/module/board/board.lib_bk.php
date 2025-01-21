<?
/*********************************** 게시판 관리 *************************************/

//게시판 디비 만들기
function makeBoard($boardid){
	// 테이블 중복검사(게시판용 테이블이 아닌 일반용에서)
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	## $Table_exist = mysql_list_tables($GLOBALS["_conf_db"]["main_db"]["db"]); ## PHP 7 사용불가
	$sql = "SHOW TABLES like '".$tblid."'";
	//echo $sql;
	$Table_exist = mysqli_query($GLOBALS['dblink'], $sql);

	if (!$Table_exist) {
		jsMsg("테이블 선택 실패");
		//jsHistory("-1") ;
	}
	$Table_Num = mysqli_num_rows($Table_exist);

	if($Table_Num>0){
		jsMsg("이미 사용중인 테이블 입니다.");
		jsHistory("-1") ;
		exit();
	}
	/*
	for ($i=0;$i<$Table_Num;$i++) {
		for ($j=0;$j<$Table_Etc;$j++) {
			$Table_Name = mysqli_result($Table_exist,$i,$j);			
			if ($tblid==$Table_Name) {
				jsMsg("이미 사용중인 테이블 입니다.");
				jsHistory("-1") ;
			}
		}
	}
	*/

	//게시판 정보 테이블에 입력
	$sql = "INSERT INTO ".$GLOBALS["_conf_tbl"]["board_info"]." set 
		boardid='$boardid',
		wdate=now()
	";

	//echo $sql;

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		//게시판 테이블 생성
		$sql = "CREATE TABLE $tblid (
			idx int(10) unsigned NOT NULL auto_increment COMMENT '일련번호',
			no tinyint(1) unsigned DEFAULT '1' NOT NULL COMMENT '정렬용 번호',
			main int(10) unsigned DEFAULT '99999999' NOT NULL COMMENT '원글번호',
			sub tinyint(3) unsigned DEFAULT '0' NOT NULL COMMENT '답글위치',
			depth tinyint(3) unsigned DEFAULT '0' NOT NULL COMMENT '답글깊이',
			w_user varchar(200) COMMENT '글쓴사람',
			r_user varchar(200) COMMENT '답글쓴사람',
			name varchar(20) COMMENT '작성자명',
			pass varchar(255) COMMENT '비밀번호',
			homepage varchar(100) COMMENT '홈페이지',
			tel varchar(255) COMMENT '연락처',
			email varchar(255) COMMENT '이메일',
			subject varchar(100) COMMENT '제목',
			contents mediumtext COMMENT '내용',
			usereplyemail enum('Y','N') NOT NULL default 'N' COMMENT '답변시 메일받음',
			usehtml enum('Y','N') NOT NULL default 'N' COMMENT 'HTML 사용',
			category varchar(50) COMMENT '게시판 카테고리',
			uselock enum('Y','N') NOT NULL default 'N' COMMENT '글잠금',
			hit int(10) COMMENT '조회수',
			etc_1 varchar(255) COMMENT '여분필드1',
			etc_2 varchar(255) COMMENT '여분필드2',
			etc_3 varchar(255) COMMENT '여분필드3',
			etc_4 varchar(255) COMMENT '여분필드4',
			etc_5 TEXT COMMENT '여분필드5',
			ip varchar(24) COMMENT 'IP주소',
			wdate DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '작성일',
			PRIMARY KEY (idx),
			KEY no (no, main, sub)
		)";

		//echo $sql;
		//exit;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		if($rs){
			return true;
		}else{

			//위에서 입력한 게시판 정보 삭제
			$sql = "delete from ".$GLOBALS["_conf_tbl"]["board_info"]." where boardid='$boardid' ";
			mysqli_query($GLOBALS['dblink'], $sql);
			return false;
		}
	}else{
		jsMsg("게시판 정보 테이블 입력실패");
		return false;
	}
}

function editBoard($arrData){
	//게시판 데이터 수정
	$sql = "UPDATE ".$GLOBALS["_conf_tbl"]["board_info"]." SET
		boardname='".$arrData['f_boardname']."', 
		skin='".$arrData['f_skin']."', 
		scale='".$arrData['f_scale']."', 
		pagescale='".$arrData['f_pagescale']."', 
		widthscale='".$arrData['f_widthscale']."', 
		thumwidth='".$arrData['f_thumwidth']."', 
		newmark='".$arrData['f_newmark']."', 
		besthit='".$arrData['f_besthit']."', 
		subjectcut='".$arrData['f_subjectcut']."', 
		useadminonly='".$arrData['f_useadminonly']."', 
		useintranet='".$arrData['f_intranet']."', 
		usepds='".$arrData['f_usepds']."', 
		usereply='".$arrData['f_usereply']."', 
		usememo='".$arrData['f_usememo']."', 
		uselock='".$arrData['f_uselock']."', 
		readlevel='".$arrData['f_readlevel']."', 
		writelevel='".$arrData['f_writelevel']."', 
		replylevel='".$arrData['f_replylevel']."', 
		listlevel='".$arrData['f_listlevel']."', 
		category='".str_replace(" ","",$arrData['f_category'])."', 
		header='".$arrData['f_header']."', 
		footer='".$arrData['f_footer']."' 
		WHERE idx='".$arrData['idx']."' 
	";
	
	// echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

function deleteBoard($idx){
	//게시판 정보 가져오기
	$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["board_info"], $idx);

	if($arrInfo["total"] > 0){
		$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $arrInfo["list"][0]["boardid"];

		//게시판 정보 삭제
		$sql = "DELETE FROM ".$GLOBALS["_conf_tbl"]["board_info"]." WHERE idx='".$idx."'	";
		//echo $sql;
		$rs1 = mysqli_query($GLOBALS['dblink'], $sql);

		//게시판 테이블 삭제
		$sql = "DROP TABLE ".$tblid;
		$rs2 = mysqli_query($GLOBALS['dblink'], $sql);

		//파일 테이블 정보 삭제
		$sql = "DELETE FROM ".$GLOBALS["_conf_tbl"]["board_files"]." WHERE boardid='".$arrInfo["list"][0]["boardid"]."'	";
		$rs3 = mysqli_query($GLOBALS['dblink'], $sql);

		//댓글 삭제
		mysqli_query($GLOBALS['dblink'], "DELETE FROM ".$GLOBALS["_conf_tbl"]["comment"]." WHERE boardid='".$arrInfo["list"][0]["boardid"]."'");


		if($rs1 && $rs2 &&$rs3){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
/*********************************** 게시판 관리 *************************************/

/*********************************** 게시물관련 *************************************/
//게시판 설정정보 가져오기
function getBoardInfo($tbl, $boardid){
    $sql  = "SELECT * ";
    $sql .= "FROM ".$GLOBALS["_conf_tbl"]["board_info"]." ";
    $sql .= "WHERE boardid = '$boardid' ";
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


//게시물 가져오기 - 파일 제외
function getBoardListBase($boardid, $category, $sw="", $sk="", $scale, $offset=0){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	//카테고리가 있을경우
	if($category !=""){
		$que_category = " and category='$category' ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("n") :
			$que_where = "and name like '%$sk%'";
		break;
		case("s") :
			$que_where = "and subject like '%$sk%'";
		break;
		case("e1") :
			$que_where = "and etc_1 = '$sk'";
		break;
		case("e2") :
			$que_where = "and etc_2 = '$sk'";
		break;
		case("e3") :
			$que_where = "and etc_3 like '%$sk%'";
		break;
		case("e4") :
			$que_where = "and etc_4 = '$sk'";
		break;
		case("c") :
			$que_where = "and contents like '%$sk%'";
		break;
		case("u_id") :
			$que_where = "and w_user = '$sk'";
		break;
		case("a") :
		default :
			$que_where = "and (name like '%$sk%' or subject like '%$sk%' or contents like '%$sk%' or w_user like '%$sk%' or etc_3 like '%$sk%')";
		}

		// 검색시 영역을 분할하여 검색=> 속도향상용
		$sql = "select count(idx) as cnt from $tblid ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		$row = mysqli_fetch_assoc($rs);
		$q_total = $row[cnt];
		$q_start = $q_total - 10000; // 최근 10000건만 검색
		
		if($q_total>10000){
			$q_limit = " idx between " . $q_start . " and " . $q_total . " ";
		}
		// 검색시 영역을 분할하여 검색=> 속도향상용
	}
	//카운트
//	$sql = "select count(idx) from $tblid WHERE no='1' $q_limit $que_where $que_category ";
	$sql = "select count(idx) from $tblid WHERE 1=1 $q_limit $que_where $que_category ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row[0];

	//목록
    $sql  = "SELECT * ";
    $sql .= "FROM $tblid ";
    //$sql .= "WHERE no='1' $q_limit $que_where $que_category ";
	$sql .= "WHERE 1=1 $q_limit $que_where $que_category ";

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

				if($boardid == "trmneon") {	# 진료대기
					if($scale > 0){
						$sql .= " order by idx limit $offset,$scale ";
					}else{
						$sql .= " order by idx ";						
					}
				}else{
					//scale 0 으로 지정시에는 전체 가져옴
					if($scale > 0){
					 $sql .= " order by hit desc, no, main, sub limit $offset,$scale ";
					}else{
					  $sql .= " order by no, main, sub ";
					}
				}

	    	
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);
		//echo $sql;
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

//게시물 가져오기 - 스케줄 형식
function getBoardListSchedule($boardid, $s_date, $e_date){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
	
	//목록
    $sql  = "SELECT * ";
    $sql .= "FROM $tblid ";
	$sql .= "WHERE schedule_date >= '$s_date' AND  schedule_date <= '$e_date' ";
    $sql .= " order by no, main, sub ";       	
	//echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_num_rows($rs);
	$list['list']['total'] = $total;
		
	for($i=0; $i < $total; $i++){
		$row = mysqli_fetch_assoc($rs);
		$list['list'][$row['schedule_date']][] = $row;
	}
    
    return $list;
}

//게시물 가져오기 - 스케줄 형식
function getBoardScheduleCnt($boardid, $s_date, $e_date, $subject, $idx){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
	
	//목록
    $sql  = "SELECT * ";
    $sql .= "FROM $tblid ";
	$sql .= "WHERE schedule_date >= '$s_date' AND  schedule_date <= '$e_date' AND subject = '$subject' ";
	if($idx){
		$sql .= " AND idx != $idx ";       	
	}
    $sql .= " order by no, main, sub ";       	
	//echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_num_rows($rs);
	$list['list']['total'] = $total;
		
	for($i=0; $i < $total; $i++){
		$row = mysqli_fetch_assoc($rs);
		$list['list'][$row[schedule_date]][] = $row;
	}
    
    return $list;
}

//게시물 가져오기 - 파일 포함
function getBoardListBaseNFile($boardid, $category, $sw="", $sk="", $scale, $offset=0, $page="", $reply=""){

	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
	$q_limit	= "";
	$que_where	= "";
	$que_category	= "";
	$que_etc	= "";
	
	//카테고리가 있을경우
	if($category !=""){
		$que_category = " and A.category='$category' ";
	}
	if(isset($_GET['etc_1'])){
		$que_etc .= " and A.etc_1='".$_GET['etc_1']."' ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("n") :
			$que_where = "and A.name like '%$sk%'";
		break;
		case("order") :
			$que_where = "and (A.etc_1 like '%$sk%' or A.subject like '%$sk%' ) ";
		break;
		case("s") :
			$que_where = "and A.subject like '%$sk%'";
		break;
		case("idx") :
			$que_where = "and A.idx = '$sk'";
		break;
		case("sd") :
			$que_where = "and A.schedule_date like '%$sk%'";
		break;
		case("h") :
			$que_where = "and A.homepage like '%$sk%'";
		break;
		case("e") :
			$que_where = "and A.etc_1 = '$sk'";
		break;
		case("e1l") :
			$que_where = "and A.etc_1 like '%$sk%'";
		break;
		case("e2l") :
			$que_where = "and A.etc_2 like '%$sk%'";
		break;
		case("e3l") :
			$que_where = "and A.etc_3 like '%$sk%'";
		break;
		case("e5l") :
			$que_where = "and A.etc_5 like '%$sk%'";
		break;
		case("cate") :
			$que_where = "and A.category = '$sk'";
		break;
		case("e4") :
			$que_where = "and A.etc_4 = '$sk'";
		break;
		case("userid") :
			$que_where = "and A.w_user = '$sk'";
		break;
		case("search") :
			$que_where = "and A.tel = '". $_SESSION["MEMBER"]["HP"] ."' and A.email = '". $_SESSION["MEMBER"]["EMAIL"] ."' ";
		break;
		case("c") :
			$que_where = "and A.contents like '%$sk%'";
		break;
		case("a") :
		default :
			$que_where = "and (A.name like '%$sk%' or A.subject like '%$sk%' or A.contents like '%$sk%')";
		}

		// 검색시 영역을 분할하여 검색=> 속도향상용
		$sql = "select count(A.idx) as cnt from $tblid A";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		$row = mysqli_fetch_assoc($rs);
		$q_total = $row['cnt'];
		$q_start = $q_total - 10000; // 최근 10000건만 검색
		
		if($q_total>10000){
			$q_limit = " A.idx between " . $q_start . " and " . $q_total . " ";
		}
		// 검색시 영역을 분할하여 검색=> 속도향상용
	}

	//echo $reply;
	//exit();
	//카운트
	//$sql = "select count(A.idx) from $tblid A WHERE A.no='1' $q_limit $que_where $que_category ";
	$sql = "select count(A.idx) from ".$tblid." A WHERE 1=1 ".$q_limit." ".$que_where." ".$que_category." ".$que_etc;
	//echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row[0];

	//=========================================================  
	// mysql 4.1 부터 적용되는 쿼리
	//=========================================================  
	// 20100624
	// 그림의 정렬순서를 입력된 순서로 하고자 할경우 실행
	// 4.1 부터 서브쿼리가 적용되므로 이하일경우 
	// 일반 group by 쿼리를 그냥 사용한다
	//=========================================================  
	//if (mysqli_get_server_info()>4.1){
	//	$sub_query=" ( SELECT *  FROM ".$GLOBALS["_conf_tbl"]["board_files"]." ORDER BY idx ASC ) ";
	//}else{
		$sub_query= $GLOBALS["_conf_tbl"]["board_files"];
	//}

	//목록
	$sql  = " SELECT A.*, B.idx AS f_idx, B.boardid, B.b_idx, B.ori_name, B.re_name, B.type, B.size ";
	$sql .= " FROM $tblid A LEFT JOIN ".$sub_query." B ON B.boardid='$boardid' AND A.idx=B.b_idx ";
	$sql .= " WHERE 1=1 $q_limit $que_where $que_category $que_etc group by A.idx";
	//echo $sql;
	//echo "//".$offset."//".$scale;
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
						$sql .= " order by A.no, A.main, A.sub, A.wdate desc limit $offset,$scale ";
					}else{
						$sql .= " order by A.no, A.main, A.sub, A.wdate desc ";
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


//게시물 가져오기 - 댓글카운트 포함
function getBoardListBaseNMemoCnt($boardid, $category, $sw="", $sk="", $scale, $offset=0){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	$tbl_comment = $GLOBALS["_conf_tbl"]["comment"];

	if($boardid == "after" && $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]) {
	//	$que_where .= " or (w_user='".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]."') ";
	}

	//카테고리가 있을경우
	if($category !=""){
		$que_category = " and category='$category' ";
	}

	if($_GET[user_id]) {
		$que_where .= "and (w_user = '".$_GET[user_id]."' or schedule_date='0000-00-00') ";
	} else if($boardid == "after" && $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT") {
		$que_where .= " and schedule_date!='0000-00-00'";
	}


	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("n") :
			$que_where .= "and name like '%$sk%'";
		break;
		case("s") :
			$que_where .= "and subject like '%$sk%'";
		break;
		case("c") :
			$que_where .= "and contents like '%$sk%'";
		break;
		case("e") :
			$que_where .= "and etc_1 = '$sk'";
		break;
		case("sp") :
			$que_where = "and schedule_date = '0000-00-00'";
		break;
		case("a") :
		default :
			$que_where .= "and (name like '%$sk%' or subject like '%$sk%' or contents like '%$sk%')";
		}

		// 검색시 영역을 분할하여 검색=> 속도향상용
		$sql = "select count(idx) as cnt from $tblid ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		$row = mysqli_fetch_assoc($rs);
		$q_total = $row[cnt];
		$q_start = $q_total - 10000; // 최근 10000건만 검색
		
		if($q_total>10000){
			$q_limit = " idx between " . $q_start . " and " . $q_total . " ";
		}
		// 검색시 영역을 분할하여 검색=> 속도향상용
	}
	//카운트
	//$sql = "select count(idx) from $tblid WHERE no='1' $q_limit $que_where $que_category $que_catcode ";
	$sql = "select count(idx) from $tblid WHERE 1=1 $q_limit $que_where $que_category $que_catcode ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row[0];

	//목록
    $sql  = "SELECT * ";
    $sql .= "FROM $tblid ";
    //$sql .= "WHERE no='1' $q_limit $que_where $que_category $que_catcode ";
	$sql .= "WHERE 1=1 $q_limit $que_where $que_category $que_catcode ";

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
			$sql .= " order by no, main, sub limit $offset,$scale ";
		}else{
		  $sql .= " order by no, main, sub ";
		}

		
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		//echo $sql;
		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
			$m_cnt_row = mysqli_fetch_row(mysqli_query("select count(idx) from $tbl_comment WHERE boardid='$boardid' AND board_idx='".$list['list'][$i][idx]."' "));
            $list['list'][$i][cmt_count] = $m_cnt_row[0];
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}

//게시물 가져오기 - 파일 포함
function getBoardListBaseNImage($boardid, $category, $sw="", $sk="", $scale, $offset=0){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	//카테고리가 있을경우
	if($category !=""){
		$que_category = " and A.category='$category' ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("n") :
			$que_where = "and A.name like '%$sk%'";
		break;
		case("s") :
			$que_where = "and A.subject like '%$sk%'";
		break;
		case("c") :
			$que_where = "and A.contents like '%$sk%'";
		break;
		case("e") :
			$que_where = "and etc_1 = '$sk'";
		break;
		case("a") :
		default :
			$que_where = "and (A.name like '%$sk%' or A.subject like '%$sk%' or A.contents like '%$sk%')";
		}

		// 검색시 영역을 분할하여 검색=> 속도향상용
		$sql = "select count(A.idx) as cnt from $tblid A";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		$row = mysqli_fetch_assoc($rs);
		$q_total = $row[cnt];
		$q_start = $q_total - 10000; // 최근 10000건만 검색
		
		if($q_total>10000){
			$q_limit = " A.idx between " . $q_start . " and " . $q_total . " ";
		}
		// 검색시 영역을 분할하여 검색=> 속도향상용
	}

	//카운트
//	$sql = "select count(A.idx) from $tblid A WHERE A.no='1' $q_limit $que_where $que_category ";
	$sql = "select count(A.idx) from $tblid A WHERE 1=1 $q_limit $que_where $que_category ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row[0];

	//목록
    $sql  = "SELECT A.*, B.idx AS f_idx, B.boardid, B.b_idx, B.ori_name, B.re_name, B.type, B.size ";
    $sql .= "FROM $tblid A LEFT JOIN ".$GLOBALS["_conf_tbl"]["board_files"]." B ON B.boardid='$boardid' AND A.idx=B.b_idx AND B.ext IN('jpg','gif','png')";
//    $sql .= "WHERE A.no='1' $q_limit $que_where $que_category group by A.idx";
    $sql .= "WHERE 1=1 $q_limit $que_where $que_category group by A.idx";

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
					$sql .= " order by A.no, A.main, A.sub limit $offset,$scale ";
				}else{
					$sql .= " order by A.no, A.main, A.sub ";
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

//게시물 등록하기
function insertBoardArticle($boardid, $thumwidth){
	$sub_sql= "";
	$hit = $_POST['hit']??0;
	$contents = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])??"";
	$contents = str_replace("'","’",$contents);
	$subject = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['subject'])??"";
	$subject = str_replace("'","’",$subject);

	if(!isset($_POST["is_notice"])){	$_POST["is_notice"]=""; }

	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	//main 번호 가져오기
	$q_main = mysqli_query($GLOBALS['dblink'],"select min(main) from $tblid ");	
	$c_main = @mysqli_result($q_main,0,0);
		
	if(!$c_main){	
		$main='99999999';
	}else{	
		$main=$c_main-1;
	}

	//게시판 공지 설정
	if(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['is_notice'])=="Y"){
		$setNo = "0";
	}else{
		$setNo = "1";
	}
	

	if(isset($_POST['wdate'])) {
		$sql_add = " wdate='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['wdate'])."' ";
	} else {
		$sql_add = " wdate=now() ";
	}
	
	//게시판 테이블에 입력

	if(!isset($_POST['usereplyemail'])){	$_POST['usereplyemail'] = "N"; }
	if(!isset($_POST['usehtml'])){	$_POST['usehtml'] = "N"; }
	if(!isset($_POST['uselock'])){	$_POST['uselock'] = "N"; }

	$etc_3 = mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_3'));
	$etc_4 = mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_4'));

	if( $boardid=="mindconsult" ){
		$sub_sql = "
			tel='".AES_encrypt(mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('tel')))."',
		";
	}
	
	$pass = "";
	if(isset($_POST['pass'])){
		$email = password_hash(mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('pass')), PASSWORD_DEFAULT) ;	## 비밀번호 있을 경우 단방향 암호화
	}
	$tel = "";
	if(isset($_POST['tel'])){
		$tel = AES_encrypt(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['tel'])) ;	## 연락처
	}
	$email = "";
	if(isset($_POST['email_1'])){
		$email = AES_encrypt(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_1']) ."@". mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_2'])) ;	## 이메일
	}
	
	$sql = "INSERT INTO ".$tblid." set 
		no='$setNo',
		main='$main',
		sub='0',
		depth='0',
		w_user='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('w_user'))."',
		r_user='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('r_user'))."',
		name='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('name'))."',
		pass='".$pass."',
		homepage='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('homepage'))."',
		tel='".$tel."',
		email='".$email."',
		subject='".$subject."',
		contents='".$contents."',
		usereplyemail='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['usereplyemail']) ."',
		usehtml='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['usehtml'])."',
		category='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('category'))."',
		uselock='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['uselock'])."',
		hit='".$hit."',
		etc_1='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_1'))."',
		etc_2='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_2'))."',
		etc_3='".$etc_3."',
		etc_4='".$etc_4 ."',
		etc_5='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_5'))."',
		ip='".$_SERVER['REMOTE_ADDR']."',
		". $sub_sql ." ". $sql_add ." ";

	//echo $sql;
	//exit;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	//파일처리
	if($boardid=="notice" || $boardid=="data" || $boardid=="edu"){
		inputBoardFiles($boardid, $insert_idx, $_FILES, $thumwidth);	
	}
	
	if($total > 0){
		return true;
	}else{
		return false;
	}

}


//게시물 수정하기
function modifyBoardArticle($boardid, $idx, $thumwidth){
	// PHP 7
	if(!isset($_POST["is_notice"])){	$_POST["is_notice"]=""; }
	$sub_sql	= "";
	$sql_add	= "";
	$contents = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])??"";
	$contents = str_replace("'","’",$contents);
	$subject = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['subject'])??"";
	$subject = str_replace("'","’",$subject);
	
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	//수정권한 설정
	$modifyPerm = false;

	//관리자는 그냥 통과
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT" || @in_array("board_manage",$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["AUTH"])){
		$modifyPerm = true;
	}

	//기존정보와 비밀번호를 비교
	$arrArticleInfo = getArticleInfo($tblid, $idx);
	
	if($arrArticleInfo["list"][0]["pass"] && ($arrArticleInfo["list"][0]["pass"]==trim($_POST['pass']) || password_verify(trim($_POST['pass']), $arrArticleInfo["list"][0]["pass"]))){
		$modifyPerm = true;
	}
	
	// 본인아이디 확인
	// 로그인 상태이고 로그인 아이디와 글 쓴 아이디가 같을 경우	
	//if(isset($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"]) && $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"]==$arrArticleInfo["list"][0]["w_user"]){
		//$modifyPerm = true;
	//}


	//권한받은자만 젒근
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]=="ACCEL" && @in_array("biz_manage",$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["AUTH"])){
		$modifyPerm = true;
	}

	//게시판 공지 설정
	if($_POST['is_notice']=="Y"){
		$setNo = "0";
	}else{
		$setNo = "1";
	}	

	if($modifyPerm==true){

		//게시판 테이블 지정
		$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

		if(isset($_POST['hit'])) {
			$sql_add = " hit=".$_POST['hit'].", ";
		}
		if(isset($_POST['wdate'])) {
			$sql_add .= " wdate='".$_POST['wdate']."' ";
		} else {
			$sql_add .= " wdate=now() ";
		}	
			
		$etc_3 = mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_3'));
		$etc_4 = mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_4'));
	
		$etc_3 = mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_3'));
		$etc_4 = mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_4'));
	
		if( $boardid=="mindconsult" ){
			$sub_sql = "
				tel='".AES_encrypt(mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('tel')))."',
			";
		}
			
		$tel = "";
		if(isset($_POST['tel'])){
			$tel = AES_encrypt(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['tel'])) ;	## 연락처
		}
		$email = "";
		if(isset($_POST['email_1'])){
			$email = AES_encrypt(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_1']) ."@". mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email_2'])) ;	## 이메일
		}
			
		$sql = "UPDATE ".$tblid." set 
			no='".$setNo."',
			r_user='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('r_user'))."',
			name='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('name'))."',
			homepage='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('homepage'))."',
			tel='".$tel."',
			email='".$email."',
			subject='".$subject."',
			contents='".$contents."',
			category='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('category'))."',
			usehtml='Y',
			etc_1='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_1'))."',
			etc_2='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_2'))."',
			etc_3='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_3'))."',
			etc_4='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_4'))."',
			etc_5='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('etc_5'))."',
			". $sub_sql . $sql_add ." 
			WHERE idx='".mysqli_real_escape_string($GLOBALS['dblink'], postNullCheck('idx'))."'";

		
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);


		//파일삭제 코딩 시작 - 삭제체크 한것만 처리
		if(isset($_POST['filedel'])){
			for($i=0;$i<count($_POST['filedel']);$i++){
				if($_POST['filedel'][$i]>0){
					$fileinfo = getArticleFileInfo($boardid, $_POST['idx'], $_POST['filedel'][$i]);
					//디비에서 파일정보 삭제
					mysqli_query($GLOBALS['dblink'], "DELETE FROM ".$GLOBALS["_conf_tbl"]["board_files"]." WHERE boardid='".$boardid."' AND idx='".$fileinfo["list"][0]['idx']."' ");
					//디스크에서 파일 삭제
					@unlink($GLOBALS["_SITE"]["BOARD_DATA"] . "/".$boardid."/".$fileinfo["list"][0]['re_name']);
				}
			}
		}
		//파일삭제 코딩 종료

		//파일처리
		if($boardid=="notice" || $boardid=="data" || $boardid=="edu"){
			inputBoardFiles($boardid, $idx, $_FILES, $thumwidth);
		}

		if($rs){
			return true;
		}else{
			return false;
		}
	}else{
		jsMsg("비밀번호가 일치하지 않습니다.");
		return false;
	}

}

//답글 등록하기
function insertBoardArticleReply($boardid, $idx, $thumwidth){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	//main 번호 가져오기
	$q_main = mysqli_query("select main,sub,depth,email,usereplyemail,pass,uselock,no from ".$tblid." where idx = '".$idx."'", $GLOBALS['dblink']);	
	$row = mysqli_fetch_array($q_main);

	$c_main =					$row[0];
	$c_sub =						$row[1];
	$c_depth =					$row[2];
	$c_email =					$row[3];
	$c_usereplyemail =		$row[4];
	$c_pass =						$row[5];
	$c_lock =						$row[6];
	$c_no =						$row[7];

	if($c_no=="0"){
		jsMsg("공지글에는 답글을 달 수 없습니다.");
		return false;
	}

	//잠긴글에 답글을 달 경우 원 사용자가 볼수 있게 비밀번호를 원 글의 비밀번호로 입력
	if($c_lock =="Y"){
		$pass = $c_pass;
		$uselock = "Y";
	}else{
		$pass = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['pass']);
		$uselock = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['uselock']=="Y"?"Y":"N");
	}

	$main = $c_main;
	$sub = $c_sub + 1;
	$depth = $c_depth + 1;

	mysqli_query("UPDATE ".$tblid." set sub=sub+1 where no='1' and main='$main' and sub>'$c_sub'", $GLOBALS['dblink']);	


	if($_POST['wdate']) {
		$sql_add = " wdate='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['wdate'])."' ";
	} else {
		$sql_add = " wdate=now() ";
	}

	//게시판 테이블에 입력
	$sql = "INSERT INTO ".$tblid." set 
		no='1',
		main='$main',
		sub='$sub',
		depth='$depth',
		w_user='".$_POST['w_user_id']."',
		r_user='".$_POST['r_user_id']."',
		name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['name'])."',
		pass='".$pass."',
		homepage='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['homepage'])."',
		email='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['email'])."',
		subject='".mysqli_real_escape_string($GLOBALS['dblink'], str_replace("\"","'",$_POST['subject']))."',
		contents='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])."',
		usereplyemail='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['usereplyemail']=="Y"?"Y":"N")."',
		usehtml='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['usehtml']=="Y"?"Y":"N")."',
		category='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['category'])."',
		uselock='".$uselock."',
		hit='0',
		etc_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',
		etc_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_2'])."',
		etc_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_3'])."',
		etc_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',
		etc_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',
		ip='".$_SERVER['REMOTE_ADDR']."',
		$sql_add
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	//파일처리
	inputBoardFiles($boardid, $insert_idx, $_FILES, $thumwidth);

	if($total > 0){
		// 글 등록시 메일링여부
		if ($c_usereplyemail=='Y'){
			if($_POST['usehtml'] !='Y') $contents = nl2br($_POST['contents']);
			mailing($GLOBALS["_SITE"]["NAME"],$GLOBALS["_SITE"]["EMAIL"],$c_email,$_POST['subject'],$contents);
		}

		return true;
	}else{
		return false;
	}
}

//게시물 삭제하기
function deleteBoardArticle($boardid, $idx, $pass){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
	$tbl_comment = $GLOBALS["_conf_tbl"]["comment"];
	$tbl_board_product = $GLOBALS["_conf_tbl"]["board_product"];
	//삭제권한 설정
	$deletePerm = false;

	//관리자는 그냥 통과
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT" || @in_array("board_manage",$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["AUTH"])){
		$deletePerm = true;
	}

	//기존정보와 비밀번호를 비교 - 수정할때와 다른 함수를 씀 (파일 삭제 때문에)
	$arrArticleInfo = getBoardArticleView($boardid, $category, $idx, "delete");
	
	if($arrArticleInfo["list"][0]["pass"]==trim($pass) || password_verify(trim($pass), $arrArticleInfo["list"][0]["pass"])){
		$deletePerm = true;
	}

	
	if($deletePerm==true){
		//게시판 테이블 지정
		$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

		//게시판 테이블에서 삭제
		$sql = "DELETE FROM ".$tblid." 
			WHERE idx='".$idx."'
		";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

	

		//파일삭제 코딩 시작
		for($i=0;$i<$arrArticleInfo["total_files"];$i++){
			//디비에서 파일정보 삭제
			mysqli_query("DELETE FROM ".$GLOBALS["_conf_tbl"]["board_files"]." WHERE boardid='".$boardid."' AND idx='".$arrArticleInfo["files"][$i]['idx']."' ", $GLOBALS['dblink']);
			//디스크에서 파일 삭제
			@unlink($GLOBALS["_SITE"]["BOARD_DATA"] . "/".$boardid."/".$arrArticleInfo["files"][$i]['re_name']);
			//썸네일 삭제
			if($arrArticleInfo["files"][$i]["type"]=="image/pjpeg" || $arrArticleInfo["files"][$i]["type"]=="image/x-png" || $arrArticleInfo["files"][$i]["type"]=="image/jpeg" || $arrArticleInfo["files"][$i]["type"]=="image/png" || $arrArticleInfo["files"][$i]["type"]=="image/gif"){
				@unlink($GLOBALS["_SITE"]["BOARD_DATA"] . "/".$boardid."/t_".$arrArticleInfo["files"][$i]['re_name']);
			}
		}
		//파일삭제 코딩 종료

		//댓글 삭제
		//mysqli_query("DELETE FROM ".$tbl_comment." WHERE boardid='".$boardid."' AND board_idx='".$idx."' ", $GLOBALS['dblink']);

		//메인 event
		if($boardid == "event1" || $boardid == "event2") { 
			mysqli_query("DELETE FROM tbl_board_tmp WHERE boardid='".$boardid."' AND b_idx='".$idx."' ", $GLOBALS['dblink']);
		}


		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		jsMsg("비밀번호가 일치하지 않습니다.");
		return false;
	}
}

//관리자 게시물 삭제시
function deleteBoardAdmin($boardid, $idx){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
	$tbl_comment = $GLOBALS["_conf_tbl"]["comment"];
	$tbl_board_product = $GLOBALS["_conf_tbl"]["board_product"];
	
	//삭제권한 설정
	$deletePerm = false;

	//관리자는 그냥 통과
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT" || @in_array("board_manage",$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["AUTH"])){
		$deletePerm = true;
	}	

	$arrArticleInfo = getBoardArticleView($boardid, $category, $idx, "delete");
	
	if($arrArticleInfo["list"][0]["w_user"]==trim($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"])){
		$deletePerm = true;
	}

	
	if($deletePerm==true){
		//게시판 테이블 지정
		$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

		//게시판 테이블에서 삭제
		$sql = "DELETE FROM ".$tblid." WHERE idx in (".$idx.")";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);
		
		if( $boardid=="ktoabiz" ){
			$sql_ktoaresults = "";
			$sql_ktoaresults = "DELETE FROM tbl_board_ktoaresults WHERE biz_idx in (".$idx.")";

	 		$rs = mysqli_query($GLOBALS['dblink'], $sql_ktoaresults);
		}
		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{		
		return false;
	}
}


//관리자 게시물 삭제시
function updownBoardAdmin($boardid, $main, $updown){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	$sql  = "SELECT * ";
    $sql .= "FROM $tblid ";
    $sql .= "WHERE main = '$main' ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){       
        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);			
        }
    }

	if($updown=="up"){
		$sql  = "SELECT * FROM $tblid WHERE main > '$main' order by main asc limit 0,1 ";		
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		
		if($total_rs > 0){       
			$row = mysqli_fetch_assoc($rs);			
			$sql = "update ".$tblid." set main='$main' WHERE idx in (".$row["idx"].")";
			//echo $sql;
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			$sql = "update ".$tblid." set main='".$row["main"]."' WHERE idx in (".$list['list'][0]["idx"].")";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
		}
	}else{
		$sql  = "SELECT * FROM $tblid WHERE main < '$main' order by main desc limit 0,1 ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		
		if($total_rs > 0){       
			$row = mysqli_fetch_assoc($rs);			
			$sql = "update ".$tblid." set main='$main' WHERE idx in (".$row["idx"].")";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			$sql = "update ".$tblid." set main='".$row["main"]."' WHERE idx in (".$list['list'][0]["idx"].")";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
		}	
	}
	
	if($total_rs > 0){
		return true;
	}else{
		return false;
	}

}

//3년 지난 게시물 삭제하기
function deleteBoardArticle3YearsAgo($boardid){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
	$tbl_comment = $GLOBALS["_conf_tbl"]["comment"];
	$tbl_board_product = $GLOBALS["_conf_tbl"]["board_product"];
	//삭제권한 설정
	$deletePerm = true;

	//관리자는 그냥 통과
	//if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT" || @in_array("board_manage",$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["AUTH"])){
		//$deletePerm = true;
	//}

	if($deletePerm==true){
		//게시판 테이블 지정
		$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
		
		$sqlWhere = " WHERE DATE_FORMAT(wdate,'%Y-%m-%d') < DATE_FORMAT(DATE_ADD(NOW(), INTERVAL -3 YEAR),'%Y-%m-%d');";

		//게시판 테이블에서 삭제
		$sql = "DELETE FROM tbl_board_mindconsult".$sqlWhere;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);
		
		$sql = "DELETE FROM tbl_board_mindqna".$sqlWhere;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = $total+mysqli_affected_rows($GLOBALS['dblink']);
		
		$sql = "DELETE FROM tbl_board_mindmtapp".$sqlWhere;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = $total+mysqli_affected_rows($GLOBALS['dblink']);
		
		$sql = "DELETE FROM tbl_board_mindvisit".$sqlWhere;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = $total+mysqli_affected_rows($GLOBALS['dblink']);
		
		$sql = "DELETE FROM tbl_board_mindvolunteer".$sqlWhere;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = $total+mysqli_affected_rows($GLOBALS['dblink']);
		
		$sql = "DELETE FROM tbl_board_mindcheck".$sqlWhere;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = $total+mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			return $total;
		}else{
			return "0";
		}
	}else{
		jsMsg("권한이 없습니다.");
		return false;
	}
}

//게시물 가져오기 - id
function getBoardArticleView($boardid, $category, $idx, $mode="read"){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
	$que_category = "";
	//카테고리가 있을경우
	if($category !=""){
		$que_category = " and category='$category' ";
	}

	//조회수 먼저 업데이트
	if($mode=="read"){
		$sql  = "UPDATE $tblid SET ";
		$sql .= " hit = hit + 1 ";
		$sql .= "WHERE idx = '".$idx."' $que_category ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
	}
	
    $sql  = "SELECT * ";
    $sql .= "FROM $tblid ";
    $sql .= "WHERE idx = '".$idx."' ".$que_category;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){
        $list['total'] = $total_rs;
        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
			//html 사용여부 체크-> 읽기 페이지에서만
			if($mode=="read" && $list['list'][$i]['usehtml']!='Y'){
				$list['list'][$i]['contents'] = nl2br(htmlspecialchars($list['list'][$i]['contents']));
			}
        }
    }else{
        $list['total'] = 0;
    }


	//이전글, 다음글은 읽기 모드일때만
	if($mode=="read"){
		//이전글 정보 가져오기
		$sql  = "SELECT max(idx) ";
		$sql .= "FROM $tblid ";
		$sql .= "WHERE idx < '$idx' $que_category ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$prev = mysqli_result($rs,0,0);
		if($prev > 0){
			$list["prev"]["idx"] = $prev;

			$sql  = "SELECT idx, name, subject, hit, wdate  ";
			$sql .= "FROM $tblid ";
			$sql .= "WHERE idx = '$prev' $que_category ";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			$list["prev"] = mysqli_fetch_assoc($rs);
		}else{
			$list["prev"]["idx"] = 0;
		}


		//다음글 정보 가져오기
		$sql  = "SELECT min(idx) ";
		$sql .= "FROM $tblid ";
		$sql .= "WHERE idx > '$idx' $que_category ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$next = mysqli_result($rs,0,0);
		if($next > 0){
			$list["next"]["idx"] = $next;

			$sql  = "SELECT idx, name, subject, hit, wdate  ";
			$sql .= "FROM $tblid ";
			$sql .= "WHERE idx = '$next' $que_category ";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);

			$list["next"] = mysqli_fetch_assoc($rs);
		}else{
			$list["next"]["idx"] = 0;
		}
	}


	//파일정보 가져오기
    $sql  = "SELECT * ";
    $sql .= "FROM ".$GLOBALS["_conf_tbl"]["board_files"]." ";
    $sql .= "WHERE boardid = '$boardid' ";
    $sql .= "AND b_idx = '$idx' order by idx";
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

//글잠금 해제
function unlockBoardArticle($boardid, $idx, $pass){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	$sql  = "SELECT * ";
	$sql .= "FROM $tblid ";
	$sql .= "WHERE idx = '$idx'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	
	$list['list'][0] = mysqli_fetch_assoc($rs);
	
	if(password_verify($pass, $list["list"][0]["pass"]) || $pass == $list["list"][0]["pass"]){
		return true;
	}else{
		return false;
	}

}


//파일정보 가져오기
function getArticleFileListInfo($boardid, $b_idx){
    $sql  = "SELECT * ";
    $sql .= "FROM " .$GLOBALS["_conf_tbl"]["board_files"]." ";
    $sql .= "WHERE boardid = '$boardid' ";
    $sql .= "AND b_idx = '$b_idx' ";
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


//파일정보 가져오기
function getArticleFileInfo($boardid, $b_idx, $idx){
    $sql  = "SELECT * ";
    $sql .= "FROM " .$GLOBALS["_conf_tbl"]["board_files"]." ";
    $sql .= "WHERE boardid = '$boardid' ";
    $sql .= "AND b_idx = '$b_idx' ";
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

//파일정보 가져오기
function getArticleFileInfoImage($boardid, $idx){
    $sql  = "SELECT * ";
    $sql .= "FROM " .$GLOBALS["_conf_tbl"]["board_files"]." ";
    $sql .= "WHERE boardid = '$boardid' ";
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

//최근게시물 목록 가져오기
function getBoardMember($boardid){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

    $sql = "SELECT distinct subject FROM $tblid order by subject desc";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total = mysqli_num_rows($rs);
    
    if($total > 0){
        $list['total'] = $total;
	    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
    
    return $list;
}

//게시판 파일처리
## function inputBoardFiles($boardid, $idx, $_FILES, $thumwidth){
function inputBoardFiles($boardid, $idx, $thumwidth){
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
		    }
			
			if (is_uploaded_file($_FILES['upfiles']['tmp_name'][$i])) {	
				move_uploaded_file ($_FILES['upfiles']['tmp_name'][$i], $GLOBALS["_SITE"]["BOARD_DATA"] . "/".$boardid."/".$filerename);
				//썸네일 만들기
				if($filetype=="image/pjpeg" || $filetype=="image/x-png" || $filetype=="image/jpeg" || $filetype=="image/png" || $filetype=="image/gif"){
					@MakeThum($GLOBALS["_SITE"]["BOARD_DATA"] . "/".$boardid."/".$filerename, $GLOBALS["_SITE"]["BOARD_DATA"] . "/".$boardid."/t_".$filerename, $thumwidth);
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

//댓글 목록 가져오기
function getCommentList($boardid, $board_idx, $scale, $offset2=0, $page=""){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["comment"];

    $sql = "SELECT * FROM $tbl WHERE 1=1 ";

		if($boardid !=""){
			$sql .= " AND boardid='$boardid' "; 
		}
		if($board_idx !=""){
			$sql .= " AND board_idx='$board_idx' ";
		}		

		if($page == "admin") {
			$sql .= " order by idx desc ";
		} else {
			$sql .= " order by prino desc, depno ";
		}

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

	//echo $sql;

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		    if(!$offset2){
		        $offset2=0;
		    }else{
		        $offset2=$offset2;
		    }

		    // offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		    if($total_rs<=$offset2){
		        $offset2 = $total_rs - $scale;
		    }

				//scale 0 으로 지정시에는 전체 가져옴
				if($scale > 0){
		    	$sql .= " limit $offset2, $scale ";
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

//댓글 등록하기
function insertComment($boardid, $board_idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["comment"];

	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"]) {
		$user_id = $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"];
		$user_name = $GLOBALS["_SITE"]["NAME"];
	} else {
		$user_id = $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]; 
		$user_name = $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["NAME"];
	}
	
	$arrInfo = getBoardArticleView($boardid, "", $board_idx, "comment");

	$sql = "select max(prino) as prino from ".$tbl." where boardid='$boardid' and board_idx='$board_idx' ";
	$result = mysqli_query($sql) or error(mysqli_error());
	if($row = mysqli_fetch_array($result)){
		$prino = $row[prino] + 1;
	}
	$grpno = $prino;

	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]) {
		//댓글 테이블에 입력
		$sql = "INSERT INTO ".$tbl." set 
			boardid='$boardid',
			board_idx='$board_idx',
			prino='".$grpno."',
			user_id='".$user_id."',
			user_name='".$user_name."',
			comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['comment'])."',
			ip='".$_SERVER[REMOTE_ADDR]."',
			wdate=now()
		";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	}
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//댓글 수정하기
function updateComment($boardid, $board_idx, $idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["comment"];

	//댓글 테이블에 입력
	$sql = "UPDATE ".$tbl." set 
		comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['comment'])."',
		ip='".$_SERVER[REMOTE_ADDR]."'
		Where boardid='$boardid' and board_idx='$board_idx' and idx='".$idx."'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//댓글에 댓글 등록하기
function replyComment($boardid, $board_idx, $idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["comment"];

	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"]) {
		$user_id = $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["ID"];
		$user_name = $GLOBALS["_SITE"]["NAME"];
	} else {
		$user_id = $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]; 
		$user_name = $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["NICK"]?$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["NICK"]:$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["NAME"];
	}
	
	$sql = "select prino,depno from ".$tbl." where idx='$idx'";
	$result = mysqli_query($sql) or error(mysqli_error());
	$row = mysqli_fetch_array($result);
	$prino = $row[prino];
	$depno = ++$row[depno];

	$sql = "update ".$tbl." set prino = prino+1 where boardid='$boardid' and board_idx='$board_idx' and prino >= '$prino'";
	$result = mysqli_query($GLOBALS['dblink'], $sql);

	//댓글 테이블에 입력
	$sql = "INSERT INTO ".$tbl." set 
		boardid='$boardid',
		board_idx='$board_idx',
		prino='".$prino."',
		depno='".$depno."',
		user_id='".$user_id."',
		user_name='".$user_name."',
		comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['comment'])."',
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


//댓글 가져오기 - id
function getCommentInfo($idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["comment"];

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

//댓글 삭제하기
function deleteComment($idx){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["comment"];

	//삭제권한 설정
	$deletePerm = false;

	//관리자는 그냥 통과
	if($_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT" || @in_array("board_manage",$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["ADMIN"]["AUTH"])){
		$deletePerm = true;
	}

	//기존정보
	$arrArticleInfo = getCommentInfo($idx);
	
	if($arrArticleInfo["list"][0]["user_id"]==$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]){
		$deletePerm = true;
	}

	
	if($deletePerm==true){
		//댓글 테이블에서 삭제
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
		jsMsg("삭제할 권한이 없습니다.");
		return false;
	}
}

//==================================================
// 첫화면 인텍스 메인화면에 리스트와 이미지 부르기
// 200900604
//===================================================

function getBoardLastNImage($boardid, $limit,$category=""){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	//카테고리가 있을경우
	if($category !=""){
			$que_category = " and A.category='$category' ";
	}

    //목록
    $sql  = "SELECT A.*, B.idx AS f_idx, B.boardid, B.b_idx, B.ori_name, B.re_name, B.type, B.size ";
    $sql .= "FROM $tblid A LEFT JOIN ".$GLOBALS["_conf_tbl"]["board_files"]." B ON B.boardid='$boardid' AND A.idx=B.b_idx AND B.ext IN('jpg','gif','png')";
    $sql .= "WHERE A.no='1' $que_where $que_category group by A.idx DESC LIMIT $limit";


	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	// offset 을 이용한 limit 가 적용된 갯수
	$total = mysqli_num_rows($rs);
	$list['list']['total'] = $total;
	// 페이지 네비게이션 오프셋 지정.

    if($total > 0){
        $list['total'] = $total;
        // 페이지 네비게이션 오프셋 지정.
			
		for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
    	}	
    }else{
        $list['total'] = 0;
    }
	
    return $list;
}


//==============================================
// 게시물 첨부파일,메모카운트
// 20090507
// 첨부파일조인후 메모카운트는 배열에 추가저장
// 피노갤러리에서 가져옴
//==============================================
function getBoardListBaseNFileNMemoCnt($boardid, $category, $sw="", $sk="", $scale, $offset=0){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;
	
	// 추가부분
	// 코멘트 테이블
	$tbl_comment = $GLOBALS["_conf_tbl"]["comment"];

	//카테고리가 있을경우
	if($category !=""){
		$que_category = " and A.category='$category' ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("n") :
			$que_where = "and A.name like '%$sk%'";
		break;
		case("s") :
			$que_where = "and A.subject like '%$sk%'";
		break;
		case("c") :
			$que_where = "and A.contents like '%$sk%'";
		break;
		case("ltdNm") :
			$que_where = "and A.etc_7 like '%$sk%'";
		break;
		case("a") :
		default :
			$que_where = "and (A.name like '%$sk%' or A.subject like '%$sk%' or A.contents like '%$sk%')";
		}

		// 검색시 영역을 분할하여 검색=> 속도향상용
		$sql = "select count(A.idx) as cnt from $tblid A";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		$row = mysqli_fetch_assoc($rs);
		$q_total = $row[cnt];
		$q_start = $q_total - 10000; // 최근 10000건만 검색
		
		if($q_total>10000){
			$q_limit = " A.idx between " . $q_start . " and " . $q_total . " ";
		}
		// 검색시 영역을 분할하여 검색=> 속도향상용
	}


		
	//카운트
	$sql = "select count(A.idx) from $tblid A WHERE A.no='1' $q_limit $que_where $que_category ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row[0];


	//목록
    $sql  = "SELECT A.*, B.idx AS f_idx, B.boardid, B.b_idx, B.ori_name, B.re_name, B.type, B.size ";
    $sql .= "FROM $tblid A LEFT JOIN ".$GLOBALS["_conf_tbl"]["board_files"]." B ON B.boardid='$boardid' AND A.idx=B.b_idx ";
    $sql .= "WHERE A.no='1' $q_limit $que_where $que_category group by A.idx";

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
				$sql .= " order by A.main limit $offset,$scale ";
			}else{
				$sql .= " order by A.main ";
			}
			
			
		
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);

		    // offset 을 이용한 limit 가 적용된 갯수
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.

			for($i=0; $i < $total; $i++){
				$list['list'][$i] = mysqli_fetch_assoc($rs);
			
				// 댓글 카운트 추가 부분
				$m_cnt_row = mysqli_fetch_row(mysqli_query("select count(idx) from $tbl_comment WHERE boardid='$boardid' AND board_idx='".$list['list'][$i][idx]."' "));			
				$list['list'][$i][cmt_count] = $m_cnt_row[0];
			}		
		
		
    }else{
        $list['total'] = 0;
    }
    return $list;
}
//easyUi json List
function getJsonList($boardid, $scale, $offset=0, $orderby="", $wheresql=""){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	if($orderby){
		$ordersql = $orderby; 
	}else{
		$ordersql = "order by main desc" ; 
	}

    $sql = "SELECT * FROM $tblid $wheresql $ordersql";
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
//			echo $sql;
		    //$list['row']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['rows'][$i] = mysqli_fetch_assoc($rs);
			$list['rows'][$i]["rownum"] = $total_rs-$i-$offset;
        }
    }else{
        $list['total'] = 0;
    }
    
    return $list;
}
//easyUi json List
function getJsonListFile($boardid, $scale, $offset=0, $orderby="", $wheresql=""){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	if($orderby){
		$ordersql = $orderby; 
	}else{
		$ordersql = "order by A.idx desc" ; 
	}

    $sql = "SELECT * FROM $tblid AS A $wheresql $ordersql";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

	$sub_query = $GLOBALS["_conf_tbl"]["board_files"];

	$sql  = " SELECT A.*, B.idx AS f_idx, B.boardid, B.b_idx, B.ori_name, B.re_name, B.type, B.size ";
	$sql .= " FROM $tblid A LEFT JOIN ".$sub_query." B ON B.boardid='$boardid' AND A.idx=B.b_idx ";
	$sql .= " $wheresql group by A.idx";

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
				$sql .= " order by A.no, A.main, A.sub limit $offset,$scale ";
			}else{
				$sql .= " order by A.no, A.main, A.sub ";
			}

		    $rs = mysqli_query($GLOBALS['dblink'], $sql);
		
		    // offset 을 이용한 limit 가 적용된 갯수
		    $total = mysqli_num_rows($rs);
//			echo $sql;
		    //$list['row']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['rows'][$i] = mysqli_fetch_assoc($rs);
			$list['rows'][$i]["rownum"] = $total_rs-$i-$offset;
        }
    }else{
        $list['total'] = 0;
    }
    
    return $list;
}
//easyUi json List - 온라인 문의
function getJsonListOnline($boardid, $scale, $offset=0, $orderby="", $wheresql=""){
	//게시판 테이블 지정
	$tblid = $GLOBALS["_SITE"]["BOARD_PREWORD"] . $boardid;

	if($orderby){
		$ordersql = $orderby; 
	}else{
		$ordersql = "order by idx desc" ; 
	}

    $sql = "SELECT * FROM $tblid $wheresql $ordersql";
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
//			echo $sql;
		    //$list['row']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['rows'][$i] = mysqli_fetch_assoc($rs);
			$list['rows'][$i]["rownum"] = $total_rs-$i-$offset;
			if($list['rows'][$i]["etc_1"]=="Y"){
				$list['rows'][$i]["etc_1"]="상담완료";
			}else{
				$list['rows'][$i]["etc_1"]="상담대기";
			}
			$list['rows'][$i]["etc_2"]=str_replace("||",", &nbsp;",$list['rows'][$i]["etc_2"]);
			$list['rows'][$i]["etc_2"]=str_replace("|","",$list['rows'][$i]["etc_2"]);
        }
    }else{
        $list['total'] = 0;
    }
    
    return $list;
}

?>
