<?
//*********************************** 접속로그 관련 ******************************************//
//N@LOG 프로그램에 있는 함수 차용 및 수정함. (os정보, browser정보 os+brower체크)
####################################################################################
//					os정보
####################################################################################
function getOSversion($os,$array){
	for($i=0;$i<sizeof($array);$i++){
		$j=$i+1;
		if(eregi("$os",$array[$i]) && eregi("^[0-9]{1,2}([\.]{1}[0-9]{1,2})*[a-z]{0,1}$",$array[$j])){
			$os_version=$array[$j];
		}	
	}
	return $os_version;
}

####################################################################################
//					browser정보
####################################################################################
function getBRversion($br,$array){
	for($i=0;$i<sizeof($array);$i++){
		$j=$i+1;
		if(eregi("$br",$array[$i]) && eregi("^[0-9]{1,2}([\.]{1}[0-9]{1,2})*[a-z]{0,1}$",$array[$j])){
			$br_version=$array[$j];
		}	
	}
	return $br_version;
}
####################################################################################
//					os+browser체크
####################################################################################
function getUserAgent(){
	## New 2020-05-18

	$broswerList = array('MSIE', 'Chrome', 'Firefox', 'iPhone', 'iPad', 'Android', 'PPC', 'Safari', 'Trident', 'none');
    $browserName = 'none';
    
    foreach ($broswerList as $userBrowser){
        if($userBrowser === 'none') break;
        if(strpos($_SERVER['HTTP_USER_AGENT'], $userBrowser)) {
            $browserName = $userBrowser;
            break;
        }
    }
    $br_name	= $browserName;
	$br_version = "";
	/////////////////////////////////////////////////////////////////////
	$userAgent = $_SERVER["HTTP_USER_AGENT"]; 
	if (preg_match('/linux/i', $userAgent)){ 
		$os = 'linux';}
	elseif(preg_match('/macintosh|mac os x/i', $userAgent)){
		$os = 'mac';}
	elseif (preg_match('/windows|win32/i', $userAgent)){
		$os = 'windows';}
	else {
		$os = 'Other';
	}
	$os_name	= $os;
	$os_version = "";

	return array($os_name . $os_version, $br_name . $br_version);
}

//레퍼러에서 도메인 정보 가져오기
function getRefererDomain(){
	$httpReferer = $_SERVER["HTTP_REFERER"]??"";
	$arr = explode("/",$httpReferer);
	if($httpReferer){
		return strtolower($arr[2]);
	}else{
		return "";
	}
}

//쿼리스트링에서 검색엔진과 검색엔진 키워드 알아내기
function getSearchKeyword($str_domain){
	//$str="http://search.cyworld.com/search/all.html?qn=&s=&f=&bd=&bw=&tq=&z=A&q=%B3%B2%B1%D4%B8%AE&premiumText=&s=";
	//$str_domain = "search.cyworld.com";
	$query		= "";
	$keyword	= "";
	$engin		= "";
	//검색엔진별 쿼리 키워드
	if(strpos(".naver.com$",$str_domain)){
			$engin = "naver.com";
			$query = "query";
	}else if(strpos(".daum.net$",$str_domain)){
			$engin = "daum.net";
			$query = "q";
	}else if(strpos(".cyworld.com$",$str_domain)){
			$engin = "cyworld.com";
			$query = "q";
	}else if(strpos(".yahoo.com$",$str_domain)){
			$engin = "yahoo.com";
			$query = "p";
	}else if(strpos(".nate.com$",$str_domain)){
			$engin = "nate.com";
			$query = "q";
	}else if(strpos(".paran.com$",$str_domain)){
			$engin = "paran.com";
			$query = "Query";
	}

	//검색엔진 추가시 위의 주석과 함께 테스트 해본다~
	//if($str){
	//	$arr = explode("&",$str);
	if($_SERVER["QUERY_STRING"]){
		$arr = explode("&",$_SERVER["QUERY_STRING"]);
		for($i=0;$i<count($arr);$i++){
			$arr2 = explode("=",$arr[$i]);
			if($arr2[0]==$query){
				$keyword = $arr2[1];
			}
		}
	}

	return array($engin, $keyword);
}

function insertLog(){
	// 테이블 지정
	$tbl_log = $GLOBALS["_conf_tbl"]["log"]["log"];
	$tbl_os = $GLOBALS["_conf_tbl"]["log"]["os"];
	$tbl_browser = $GLOBALS["_conf_tbl"]["log"]["browser"];
	$tbl_referer = $GLOBALS["_conf_tbl"]["log"]["referer"];
	$tbl_domain = $GLOBALS["_conf_tbl"]["log"]["domain"];
	$tbl_ip = $GLOBALS["_conf_tbl"]["log"]["ip"];
	$tbl_page = $GLOBALS["_conf_tbl"]["log"]["page"];
	$tbl_searchengin = $GLOBALS["_conf_tbl"]["log"]["searchengin"];
	$tbl_keyword = $GLOBALS["_conf_tbl"]["log"]["keyword"];
	$tbl_counter = $GLOBALS["_conf_tbl"]["log"]["counter"];

	//데이터 할당
	$arrAgent = getUserAgent();
	$str_os = $arrAgent[0];
	$str_browser = $arrAgent[1];
	$httpReferer = $_SERVER["HTTP_REFERER"]??"";
	$str_referer = urldecode($httpReferer);
	if($str_referer==""){
		$str_referer = "직접입력";
	}

	$str_domain = getRefererDomain();
	if($str_domain==""){
		$str_domain = "직접입력";
	}

	$str_ip = $_SERVER["REMOTE_ADDR"];
	//$str_page = $_SERVER["SCRIPT_NAME"];
	$str_page = $_SERVER["REQUEST_URI"];
	$arrQuery = getSearchKeyword($str_domain);
	$str_searchengin = $arrQuery[0];
	$str_keyword = urldecode($arrQuery[1]);

	//os 정보 입력
	$rs = mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_os WHERE os='$str_os'");
	$total = mysqli_num_rows($rs);
	if($total > 0){
		$os_idx = mysqli_result($rs,0);
		mysqli_query($GLOBALS['dblink'], "UPDATE $tbl_os set hit=hit+1 WHERE idx='$os_idx'");
	}else{
		mysqli_query($GLOBALS['dblink'], "INSERT INTO $tbl_os SET os='$str_os', hit='1'");
		$os_idx = mysqli_insert_id($GLOBALS['dblink']);
	}

	//browser 정보 입력
	$rs = mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_browser WHERE browser='$str_browser'");
	$total = mysqli_num_rows($rs);
	if($total > 0){
		$browser_idx = mysqli_result($rs,0);
		mysqli_query($GLOBALS['dblink'], "UPDATE $tbl_browser set hit=hit+1 WHERE idx='$browser_idx'");
	}else{
		mysqli_query($GLOBALS['dblink'], "INSERT INTO $tbl_browser SET browser='$str_browser', hit='1'");
		$browser_idx = mysqli_insert_id($GLOBALS['dblink']);
	}


	//referer 정보 입력
	$rs = mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_referer WHERE referer='$str_referer'");
	$total = @mysqli_num_rows($rs);
	if($total > 0){
		$referer_idx = mysqli_result($rs,0);
		mysqli_query($GLOBALS['dblink'], "UPDATE $tbl_referer set hit=hit+1 WHERE idx='$referer_idx'");
	}else{
		mysqli_query($GLOBALS['dblink'], "INSERT INTO $tbl_referer SET referer='$str_referer', hit='1'");
		$referer_idx = mysqli_insert_id($GLOBALS['dblink']);
	}


	//domain 정보 입력
	$rs = mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_domain WHERE domain='$str_domain'");
	$total = mysqli_num_rows($rs);
	if($total > 0){
		$domain_idx = mysqli_result($rs,0);
		mysqli_query($GLOBALS['dblink'], "UPDATE $tbl_domain set hit=hit+1 WHERE idx='$domain_idx'");
	}else{
		mysqli_query($GLOBALS['dblink'], "INSERT INTO $tbl_domain SET domain='$str_domain', hit='1'");
		$domain_idx = mysqli_insert_id($GLOBALS['dblink']);
	}

	//ip 정보 입력
	$rs = mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_ip WHERE ip='$str_ip'");
	$total = mysqli_num_rows($rs);
	if($total > 0){
		$ip_idx = mysqli_result($rs,0);
		mysqli_query($GLOBALS['dblink'], "UPDATE $tbl_ip set hit=hit+1 WHERE idx='$ip_idx'");
	}else{
		mysqli_query($GLOBALS['dblink'], "INSERT INTO $tbl_ip SET ip='$str_ip', hit='1'");
		$ip_idx = mysqli_insert_id($GLOBALS['dblink']);
	}

	//page 정보 입력
	$rs = @mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_page WHERE page='$str_page'");
	$total = @mysqli_num_rows($rs);
	if($total > 0){
		$page_idx = mysqli_result($rs,0);
		mysqli_query($GLOBALS['dblink'], "UPDATE $tbl_page set hit=hit+1 WHERE idx='$page_idx'");
	}else{
		mysqli_query($GLOBALS['dblink'], "INSERT INTO $tbl_page SET page='$str_page', hit='1'");
		$page_idx = mysqli_insert_id($GLOBALS['dblink']);
	}

	//searchengin 정보 입력
	$searchengin_idx	= "";
	if($str_searchengin){
		$rs = mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_searchengin WHERE searchengin='$str_searchengin'");
		$total = mysqli_num_rows($rs);
		if($total > 0){
			$searchengin_idx = mysqli_result($rs,0);
			mysqli_query($GLOBALS['dblink'], "UPDATE $tbl_searchengin set hit=hit+1 WHERE idx='$searchengin_idx'");
		}else{
			mysqli_query($GLOBALS['dblink'], "INSERT INTO $tbl_searchengin SET searchengin='$str_searchengin', hit='1'");
			$searchengin_idx = mysqli_insert_id($GLOBALS['dblink']);
		}
	}else{
		$searchengin_idx = "0";
	}


	//keyword 정보 입력
	$keyword_idx	= "";
	if($str_keyword){
		$rs = mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_keyword WHERE keyword='$str_keyword'");
		$total = mysqli_num_rows($rs);
		if($total > 0){
			$keyword_idx = mysqli_result($rs,0);
			mysqli_query($GLOBALS['dblink'], "UPDATE $tbl_keyword set hit=hit+1 WHERE idx='$keyword_idx'");
		}else{
			mysqli_query($GLOBALS['dblink'], "INSERT INTO $tbl_keyword SET keyword='$str_keyword', hit='1'");
			$keyword_idx = mysqli_insert_id($GLOBALS['dblink']);
		}
	}else{
		$keyword_idx	= "0";
	}

	//log 정보 입력
	$sql = "INSERT INTO $tbl_log SET
		browser='$browser_idx',
		domain='$domain_idx',
		referer='$referer_idx',
		ip='$ip_idx',
		searchengin='$searchengin_idx',
		keyword='$keyword_idx',
		os='$os_idx',
		page='$page_idx'
	";
	//echo $sql;
	mysqli_query($GLOBALS['dblink'], $sql);

	//날짜설정
	$yyyy = date('Y');
	$mm = date('m');
	$dd = date('d');
	$week = date('w');
	$hh = date('G');
	//count 정보 입력
	$rs = mysqli_query($GLOBALS['dblink'], "SELECT idx FROM $tbl_counter WHERE yyyy='$yyyy' AND mm='$mm' AND dd='$dd' ");
	$total = mysqli_num_rows($rs);
	if($total > 0){
		$counter_idx = mysqli_result($rs,0);
		$hh_sql = "h".$hh."=h".$hh."+1, ";
		$sql = "UPDATE $tbl_counter SET 
			$hh_sql
			hit=hit+1
			WHERE idx='$counter_idx'
		";
		mysqli_query($GLOBALS['dblink'], $sql);
	}else{
		$hh_sql = "h".$hh."=h".$hh."+1, ";
		$sql = "INSERT INTO $tbl_counter SET 
			yyyy='$yyyy', 
			mm='$mm', 
			dd='$dd',
			week='$week',
			$hh_sql
			hit='1'
		";
		mysqli_query($GLOBALS['dblink'], $sql);
	}
}

//시간대별 접속자수 표시
function getAccessCounterHourly($s_date, $e_date){
	$tbl = $GLOBALS["_conf_tbl"]["log"]["counter"];

	$sql  = "SELECT ";
	for($i=0;$i<24;$i++){
		$sql .= "SUM(h".$i.") AS h".$i.", ";
	}
    $sql .= "SUM(hit) AS hit ";
    $sql .= "FROM $tbl ";
	$sql .=" WHERE concat(yyyy,'-',mm,'-',dd) >= '$s_date' AND concat(yyyy,'-',mm,'-',dd) <= '$e_date'";


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

//일별 접속자수 표시
function getAccessCounterDaily($s_date, $e_date){
	$tbl = $GLOBALS["_conf_tbl"]["log"]["counter"];

	//전체합계
	$sql  = "SELECT sum(hit) ";
    $sql .= "FROM $tbl ";
	$sql .=" WHERE concat(yyyy,'-',mm,'-',dd) >= '$s_date' AND concat(yyyy,'-',mm,'-',dd) <= '$e_date'";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $sum = mysqli_result($rs,0,0);

	$sql = "
		SELECT COUNT(ip) FROM (
			SELECT ip FROM tbl_websight_log
			WHERE wdate >= '$s_date 00:00:00' AND wdate <= '$e_date 23:59:59'
			GROUP BY ip ) AS b
	";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $unisum = mysqli_result($rs,0,0);

	$sql  = "SELECT yyyy, mm, dd, week, hit ";
    $sql .= "FROM $tbl ";
	$sql .=" WHERE concat(yyyy,'-',mm,'-',dd) >= '$s_date' AND concat(yyyy,'-',mm,'-',dd) <= '$e_date'";

	//echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){
        $list['total'] = $total_rs;
        $list['sum'] = $sum;
        $list['unisum'] = $unisum;
        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
        $list['sum'] = 0;
    }
    return $list;
}

//월별 접속자수 표시
function getAccessCounterMonthly($s_date, $e_date){
	$tbl = $GLOBALS["_conf_tbl"]["log"]["counter"];

	$s_date = substr($s_date,0,7);
	$e_date = substr($e_date,0,7);

	//전체합계
	$sql  = "SELECT sum(hit) ";
    $sql .= "FROM $tbl ";
	$sql .=" WHERE concat(yyyy,'-',mm) >= '$s_date' AND concat(yyyy,'-',mm) <= '$e_date'";

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $sum = mysqli_result($rs,0,0);
	//echo $sql;


	$sql  = "SELECT yyyy, mm, sum(hit) as sum_hit ";
    $sql .= "FROM $tbl ";
	$sql .=" WHERE concat(yyyy,'-',mm) >= '$s_date' AND concat(yyyy,'-',mm) <= '$e_date' GROUP BY mm";

	//echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){
        $list['total'] = $total_rs;
        $list['sum'] = $sum;
        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
        $list['sum'] = 0;
    }
    return $list;
}

//접속통계 일반 쿼리
function getAccessCounterTable($tbl_type, $s_date, $e_date, $scale, $offset=0){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["log"]["log"];
	$tbl_browser = $GLOBALS["_conf_tbl"]["log"]["browser"];
	$tbl_domain = $GLOBALS["_conf_tbl"]["log"]["domain"];
	$tbl_referer = $GLOBALS["_conf_tbl"]["log"]["referer"];
	$tbl_ip = $GLOBALS["_conf_tbl"]["log"]["ip"];
	$tbl_searchengin = $GLOBALS["_conf_tbl"]["log"]["searchengin"];
	$tbl_keyword = $GLOBALS["_conf_tbl"]["log"]["keyword"];
	$tbl_os = $GLOBALS["_conf_tbl"]["log"]["os"];
	$tbl_page = $GLOBALS["_conf_tbl"]["log"]["page"];

	$sql  = "SELECT count(idx) ";
    $sql .= "FROM $tbl  ";
	$sql .=" WHERE wdate >= '$s_date 00:00:00' AND wdate <= '$e_date 23:59:59'";

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_sum = mysqli_result($rs,0,0);


	if($tbl_type=="os"){
		$sql = " SELECT B.os, count(A.idx) AS hit FROM $tbl A, $tbl_os B ";
		$sql .=" WHERE A.os=B.idx ";
		$sql .=" AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'";
		$sql .=" group by A.os ";
		$sql .=" order by hit desc ";
	}else if($tbl_type=="browser"){
		$sql = " SELECT B.browser, count(A.idx) AS hit FROM $tbl A, $tbl_browser B ";
		$sql .=" WHERE A.browser=B.idx ";
		$sql .=" AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'";
		$sql .=" group by A.browser ";
		$sql .=" order by hit desc ";
	}else if($tbl_type=="ip"){
		$sql = " SELECT B.ip, count(A.idx) AS hit FROM $tbl A, $tbl_ip B ";
		$sql .=" WHERE A.ip=B.idx ";
		$sql .=" AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'";
		$sql .=" group by A.ip ";
		$sql .=" order by hit desc ";
	}else if($tbl_type=="domain"){
		$sql = " SELECT B.domain, count(A.idx) AS hit FROM $tbl A, $tbl_domain B ";
		$sql .=" WHERE A.domain=B.idx ";
		$sql .=" AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'";
		$sql .=" group by A.domain ";
		$sql .=" order by hit desc ";
	}else if($tbl_type=="referer"){
		$sql = " SELECT B.referer, count(A.idx) AS hit FROM $tbl A, $tbl_referer B ";
		$sql .=" WHERE A.referer=B.idx ";
		$sql .=" AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'";
		$sql .=" group by A.referer ";
		$sql .=" order by hit desc ";
	}else if($tbl_type=="page"){
		$sql = " SELECT B.page, count(A.idx) AS hit FROM $tbl A, $tbl_page B ";
		$sql .=" WHERE A.page=B.idx ";
		$sql .=" AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'";
		$sql .=" group by A.page ";
		$sql .=" order by hit desc ";
	}else if($tbl_type=="searchengin"){
		$sql = " SELECT B.searchengin, count(A.idx) AS hit FROM $tbl A, $tbl_searchengin B ";
		$sql .=" WHERE A.searchengin=B.idx ";
		$sql .=" AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'";
		$sql .=" group by A.searchengin ";
		$sql .=" order by hit desc ";
	}else if($tbl_type=="keyword"){
		$sql = " SELECT B.keyword, count(A.idx) AS hit FROM $tbl A, $tbl_keyword B ";
		$sql .=" WHERE A.keyword=B.idx ";
		$sql .=" AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'";
		$sql .=" group by A.keyword ";
		$sql .=" order by hit desc ";
	}

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

	//echo $sql;

    if($total_rs > 0){
        $list['total'] = $total_rs;
		$list['total_sum'] = $total_sum;
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
		$list['total_sum'] = 0;
    }
    
    return $list;
}


//접속로그 보기
function getAccessCounterLog($s_date, $e_date, $scale, $offset=0){
	// 테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["log"]["log"];
	$tbl_browser = $GLOBALS["_conf_tbl"]["log"]["browser"];
	$tbl_domain = $GLOBALS["_conf_tbl"]["log"]["domain"];
	$tbl_referer = $GLOBALS["_conf_tbl"]["log"]["referer"];
	$tbl_ip = $GLOBALS["_conf_tbl"]["log"]["ip"];
	$tbl_searchengin = $GLOBALS["_conf_tbl"]["log"]["searchengin"];
	$tbl_keyword = $GLOBALS["_conf_tbl"]["log"]["keyword"];
	$tbl_os = $GLOBALS["_conf_tbl"]["log"]["os"];
	$tbl_page = $GLOBALS["_conf_tbl"]["log"]["page"];


	$sql = "SELECT A.idx, A.wdate, B.browser, C.domain, D.referer, E.ip, F.searchengin, G.keyword, H.os, I.page FROM $tbl A
	LEFT JOIN $tbl_browser B ON A.browser=B.idx
	LEFT JOIN $tbl_domain C ON A.domain=C.idx
	LEFT JOIN $tbl_referer D ON A.referer=D.idx
	LEFT JOIN $tbl_ip E ON A.ip=E.idx
	LEFT JOIN $tbl_searchengin F ON A.searchengin=F.idx
	LEFT JOIN $tbl_keyword G ON A.keyword=G.idx
	LEFT JOIN $tbl_os H ON A.os=H.idx
	LEFT JOIN $tbl_page I ON A.page=I.idx
	WHERE 1=1 
	AND A.wdate >= '$s_date 00:00:00' AND A.wdate <= '$e_date 23:59:59'
	";
	$sql .= " order by idx desc ";

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
		$rs = mysqli_query($sql,$GLOBALS['dblink']);
	
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
function updateLogCount($category){
	mysqli_query($GLOBALS['dblink'], "UPDATE tbl_down_log set hit=hit+1 WHERE category='$category'");
	return true;
}
//일별 접속자수 표시
function getAccessDownload(){
	$tbl = "tbl_down_log";


	//전체합계
	$sql  = "SELECT sum(hit) ";
    $sql .= "FROM $tbl ";
	$sql .=" WHERE 1=1 ";

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $sum = mysqli_result($rs,0,0);

	$sql  = "SELECT * FROM $tbl ";
	$sql .=" WHERE 1=1 ";

	//echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){
        $list['total'] = $total_rs;
        $list['sum'] = $sum;
        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
        $list['sum'] = 0;
    }
    return $list;
}
?>