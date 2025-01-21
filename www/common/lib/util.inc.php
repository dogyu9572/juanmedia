<?php
//글자 자르기(utf8)
function text_cut($str, $size) {
	$substr = substr( $str, 0, $size * 2 );
	$multi_size = preg_match_all( '/[\\x80-\\xff]/', $substr, $multi_chars );

	if ( $multi_size > 0 )
		$size = $size + intval( $multi_size / 3 ) - 1;

	if ( strlen( $str ) > $size )
	{
		$str = substr( $str, 0, $size );
		$str = preg_replace( '/(([\\x80-\\xff]{3})*?)([\\x80-\\xff]{0,2})$/', '$1', $str );
		$str .= '...';
	}

	return $str;
}

function weekday($day){	// 요일
	$week = array("일", "월", "화", "수", "목", "금", "토") ;
	$weekday = $week[ date('w', strtotime($day)) ] ;
	return $weekday;
}

// 금액을 한글로 변환
function number2hangul($number){
	$num = array('', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구');
	$unit4 = array('', '만', '억', '조', '경');
	$unit1 = array('', '십', '백', '천');

	$res = array();

	$number = str_replace(',','',$number);
	$split4 = str_split(strrev((string)$number),4);

	for($i=0;$i<count($split4);$i++){
			$temp = array();
			$split1 = str_split((string)$split4[$i], 1);
			for($j=0;$j<count($split1);$j++){
					$u = (int)$split1[$j];
					if($u > 0) $temp[] = $num[$u].$unit1[$j];
			}
			if(count($temp) > 0) $res[] = implode('', array_reverse($temp)).$unit4[$i];
	}
	return implode('', array_reverse($res));
}

function dateDiff($strDate1, $strDate2){
	$timeDiff = strtotime($strDate2) - strtotime($strDate1);	
	return number_format($timeDiff / 86400);
}

//캘린더 날짜 가져오기 - 월
function getDiarySet($year, $month, $day){
  $diary = array();

  //setlocale(LC_ALL, "ko_KR.UTF-8");
  $diary['to_weekday_str'] = strftime("%a", mktime(0,0,0, $month, $day, $year));    // 한글로 요일 출력.

  $diary['prev_month'] = date("Y-m-d",strtotime("-1 Month", mktime(0,0,0,$month,1,$year)));
  $diary['this_month'] = date("Y-m-d",mktime(0,0,0,$month,1,$year));
  $diary['next_month'] = date("Y-m-d",strtotime("+1 Month", mktime(0,0,0,$month,1,$year)));

  $diary['first'] = 1;                // 1일.
  $diary['last'] = date('t', mktime(0,0,0, $month, 1, $year));  // 말일.

  $diary['s_weekday'] = date('w', mktime(0,0,0, $month, 1, $year));             // 1일의 요일.숫자.
  $diary['e_weekday'] = date('w', mktime(0,0,0, $month, $diary['last'], $year));  // 말일의 요일.숫자.

  $diary['first_before'] = date('Y-m-d', mktime(0,0,0, $month, $diary['first'] - $diary['s_weekday'], $year));     // 1일 이전의 날짜.
  $diary['last_after'] = date('Y-m-d', mktime(0,0,0, $month, $diary['last'] + (6 - $diary['e_weekday']), $year));  // 말일 이후의 날짜.

	//이주시작(월) 끝(금), 다음주시작(월) 끝(금)
	$todayN = date('N', mktime(0,0,0, $month, $day, $year));
	$this_week_mon_time = mktime(0,0,0, $month, $day, $year) - (($todayN-1) * 24 * 3600);
	$diary['last_week_fri'] = date('Y-m-d',$this_week_mon_time - (3 * 24 * 3600));
	$diary['this_week_sun'] = date('Y-m-d',$this_week_mon_time - (24 * 3600));
	$diary['this_week_mon'] = date('Y-m-d',$this_week_mon_time);
	$diary['this_week_wed'] = date('Y-m-d',$this_week_mon_time + (3 * 24 * 3600));
	$diary['this_week_fri'] = date('Y-m-d',$this_week_mon_time + (4 * 24 * 3600));
	$diary['next_week_sun'] = date('Y-m-d',$this_week_mon_time + (6 * 24 * 3600));
	$diary['next_week_mon'] = date('Y-m-d',$this_week_mon_time + (7 * 24 * 3600));
	$diary['next_week_wed'] = date('Y-m-d',$this_week_mon_time + (10 * 24 * 3600));
	$diary['next_week_fri'] = date('Y-m-d',$this_week_mon_time + (11 * 24 * 3600));
	

	// 1일 이전의 날짜 부터, 말일 이후의 날짜 까지를 배열에 넣기.
	for($i=0;;$i++){
		for($j=0;$j<=6;$j++){
      $diary['box'][$i][$j] = date('Y-m-d', strtotime($diary['first_before']) + ($i*7+$j) * 24*3600);
		}

		if($diary['box'][$i][6]==$diary['last_after'])
			break;
	}

  return $diary;
}

//캘린더 날짜 가져오기 - 주
function getWeeklySet($year, $month, $day){
	$diary = array();

  	
	
	//이주시작(월) 끝(금), 다음주시작(월) 끝(금)
	$todayN = date('N', mktime(0,0,0, $month, $day, $year));	
	$this_week_mon_time = mktime(0,0,0, $month, $day, $year) - (($todayN-1) * 24 * 3600);

	$diary[prev_week_mon] = date('Y-m-d',$this_week_mon_time - (7 * 24 * 3600));

	$diary[this_week_sun] = date('Y-m-d',$this_week_mon_time - (24 * 3600));
	$diary[this_week_mon] = date('Y-m-d',$this_week_mon_time);
	$diary[this_week_tue] = date('Y-m-d',$this_week_mon_time + (1 * 24 * 3600));
	$diary[this_week_wed] = date('Y-m-d',$this_week_mon_time + (2 * 24 * 3600));
	$diary[this_week_thu] = date('Y-m-d',$this_week_mon_time + (3 * 24 * 3600));
	$diary[this_week_fri] = date('Y-m-d',$this_week_mon_time + (4 * 24 * 3600));
	$diary[this_week_sat] = date('Y-m-d',$this_week_mon_time + (5 * 24 * 3600));

	$diary[next_week_mon] = date('Y-m-d',$this_week_mon_time + (7 * 24 * 3600));

	$diary[first_before]	= $diary[this_week_sun];
	$diary[last_after]		= $diary[this_week_sat];
	

	// 1일 이전의 날짜 부터, 말일 이후의 날짜 까지를 배열에 넣기.
	for($i=0;;$i++){
		for($j=0;$j<=6;$j++){
      $diary[box][$i][$j] = date('Y-m-d', strtotime($diary[first_before]) + ($i*7+$j) * 24*3600);
		}

		if($diary[box][$i][6]==$diary[last_after])
			break;
	}

  return $diary;
}

/**
 * Makes directory and returns BOOL(TRUE) if exists OR made.
 *
 * @param  $path Path name
 * @return bool
 */
function rmkdir($path, $mode = 0755) {
    $path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
    $e = explode("/", ltrim($path, "/"));
    if(substr($path, 0, 1) == "/") {
        $e[0] = "/".$e[0];
    }
    $c = count($e);
    $cp = $e[0];
    for($i = 1; $i < $c; $i++) {
        if(!is_dir($cp) && !@mkdir($cp, $mode)) {
            return false;
        }
        $cp .= "/".$e[$i];
    }
    return @mkdir($path, $mode);
}

/* 지정된 디렉토리 및의 모든 데이터 삭제*/
function rrmdir($f) {
    if (is_dir($f)) {
        foreach(glob($f.'/*') as $sf) {
            if (is_dir($sf) && !is_link($sf)) {
                rrmdir($sf);
            } else {
                @unlink($sf);
            }
            @rmdir($f);
        }
    }
}

//파일 바이트수 개산
function getByte($intSize){
  if( strlen($intSize) < 7 ) {
	 $filesize = sprintf("%0.1f KB", $intSize/1024);
  }else{
	 $filesize = sprintf("%0.1f MB", $intSize/1024000);
  }

  return $filesize;
}

//썸네일 만들기
function MakeThum($src, $dst, $size){
	$quality = '100';    //-- jpg 퀄리티 
	//$size = '200';    //-- 줄일 크기 pixel (너비, 또는 높이에 적용) 
	//$ratio = '4:3';        //-- 이미지를 4:3 비율로 잘라냄 
	$ratio = 'false';        //-- 원본 이미지비율을 유지 

	$get_size = _getimagesize($src, $size, $ratio); 
	$result = resize_image($dst, $src, $get_size, $quality, $ratio); 

	if($result === TRUE) 
		return true;
	else 
		return false; 
}

// $destination : 이미지가 저장될 경로 
// $departure : 원본 이미지 경로 
// $size : _getimagesize() 의 return 값을 넣을 것 
// $quality : JPG 퀄리티 
// $ratio : 비율 강제설정 

function resize_image($destination, $departure, $size, $quality='80', $ratio='false'){ 

    if($size[2] == 1)    //-- GIF 
        $src = imageCreateFromGIF($departure); 
    elseif($size[2] == 2) //-- JPG 
        $src = imageCreateFromJPEG($departure); 
    else    //-- $size[2] == 3, PNG 
        $src = imageCreateFromPNG($departure); 

    $dst = imagecreatetruecolor($size['w'], $size['h']); 


    $dstX = 0; 
    $dstY = 0; 
    $dstW = $size['w']; 
    $dstH = $size['h']; 

    if($ratio != 'false' && $size['w']/$size['h'] <= $size[0]/$size[1]){ 
        $srcX = ceil(($size[0]-$size[1]*($size['w']/$size['h']))/2); 
        $srcY = 0; 
        $srcW = $size[1]*($size['w']/$size['h']); 
        $srcH = $size[1]; 
    }elseif($ratio != 'false'){ 
        $srcX = 0; 
        $srcY = ceil(($size[1]-$size[0]*($size['h']/$size['w']))/2); 
        $srcW = $size[0]; 
        $srcH = $size[0]*($size['h']/$size['w']); 
    }else{ 
        $srcX = 0; 
        $srcY = 0; 
        $srcW = $size[0]; 
        $srcH = $size[1]; 
    } 

    @imagecopyresampled($dst, $src, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH); 
    @imagejpeg($dst, $destination, $quality); 
    @imagedestroy($src); 
    @imagedestroy($dst); 

    return TRUE; 
} 

// $img : 원본이미지 
// $m : 목표크기 pixel 
// $ratio : 비율 강제설정 
function _getimagesize($img, $m, $ratio='false'){ 

    $v = @getImageSize($img); 

    if($v === FALSE || $v[2] < 1 || $v[2] > 3) 
        return FALSE; 

    $m = intval($m); 

    if($m > $v[0] && $m > $v[1]) 
        return array_merge($v, array("w"=>$v[0], "h"=>$v[1])); 

    if($ratio != 'false'){ 
        $xy = explode(':',$ratio); 
        return array_merge($v, array("w"=>$m, "h"=>ceil($m*intval(trim($xy[1]))/intval(trim($xy[0]))))); 
    }elseif($v[0] > $v[1]){ 
        $t = $v[0]/$m; 
        $s = floor($v[1]/$t); 
        $m = ($m > 0) ? $m : 1; 
        $s = ($s > 0) ? $s : 1; 
        return array_merge($v, array("w"=>$m, "h"=>$s)); 
    } else { 
        $t = $v[1]/intval($m); 
        $s = floor($v[0]/$t); 
        $m = ($m > 0) ? $m : 1; 
        $s = ($s > 0) ? $s : 1; 
        return array_merge($v, array("w"=>$s, "h"=>$m)); 
    } 
}

//  메일보내기
function mailing($Mail_Name,$Mail_From,$Mail_To,$Mail_Subject,$Mail_Contents){
    $Mail_Header = "from:$Mail_Name<$Mail_From>\nreply-to:$Mail_Name<$Mail_From>\n";
    $Mail_Header .= "Content-Type: text/html;charset=euc_kr";
    mail($Mail_To,$Mail_Subject,$Mail_Contents,$Mail_Header);
} // end func


/** 
 * 파일 다운로드 
 * @param $download_file 다운로드 할 파일경로 
 * @param $file_org_name 다운로드 파일 이름 
 */ 
function fileDownload($download_file, $file_org_name='') { 
	$copy_file_name = substr($download_file, strrpos($download_file, '/')+1); 
	$file_name = ($file_org_name) ? $file_org_name : $copy_file_name; 
	if(file_exists($download_file) == true) { 
		if (preg_match("(MSIE 5.5|MSIE 6.0)", $HTTP_USER_AGENT)) {
            Header("Content-type:application/octet-stream");
            Header("Content-Length:".filesize($download_file));
            Header("Content-Disposition:attachment;filename=".$file_name."");
            Header("Content-Transfer-Encoding:binary");
            Header("Pragma:no-cache");
            Header("Expires:0");
        } else {
            Header("Content-type:file/unknown");
            Header("Content-Length:".filesize($download_file));
            Header("Content-Disposition:attachment; filename=".$file_name."");
            Header("Content-Description:PHP3 Generated Data");
            Header("Pragma: no-cache");
            Header("Expires: 0");
        }

        if (is_file($download_file)) {
            $fp = fopen($download_file, "rb");
            if (!fpassthru($fp)) fclose($fp);
            clearstatcache();
        }
	} else { 

		jsMsg($download_file . '해당 파일이 없습니다.');
		selfClose();
	} 
}



function makeincFiles($boardid, $limit){
	if($boardid){
		if(!$limit){
			$limit = 5;
		}
		$arrList = getBoardLast($boardid, $limit);
		$makeinc_path = $_SERVER["DOCUMENT_ROOT"] . "/bbs/makeinc/";
		$nowfile = $makeinc_path . $boardid . ".inc.php";
		$newfile = $makeinc_path . $boardid . ".inc.php.new";

		if(!file_exists($nowfile)){
			$fp = fopen($nowfile, 'w');
			fclose($fp);
		}
		if(copy($nowfile, $newfile)){
			$fp = fopen($newfile, 'w');
			if($arrList["total"] > 0){
				fwrite($fp, "<?php $makeinc_".$boardid." = ");
				for($i=0; $i<$arrList["total"];$i++){
					fwrite($fp, $arrList["list"][$i][idx]);
				}
				fwrite($fp, "?>");
			}else{
				fwrite($fp, "<?php $makeinc_".$boardid." = NULL;?>");
			}
			fclose($fp);
		}

	}else{
		return false;
	}
}
// PHP 5.2 이하 적용 AES-256
function encrypt($value)
{                    
	$strAESKey128 = "encript.railpark";  // 16자리	
	#$strAESKeyIV = str_repeat(chr(0), 16); #Same as in JAVA  16자리
    #$padSize = 16 - (strlen ($value) % 16) ;
    #$value = $value . str_repeat (chr ($padSize), $padSize) ;
    $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $strAESKey128, $value, MCRYPT_MODE_CBC, str_repeat(chr(0),16)) ;                
    //return strtoupper(bin2hex ($output)) ;        
	return base64_encode($output);
}
function decrypt($value)        
{                     
	$strAESKey128 = "encript.railpark";  // 16자리	
	$strAESKeyIV = str_repeat(chr(0), 16); #Same as in JAVA  16자리
    $value = base64_encode($value) ;                
    $output = mcrypt_decrypt (MCRYPT_RIJNDAEL_128, $strAESKey128, $value, MCRYPT_MODE_CBC, str_repeat(chr(0),16)) ;                   
    return $output;        
}
// PHP 5.3 이상 적용 AES-256 ini => openssl 설정
function Encoder($value){
	$key = "homepagekoreakey";
	//	return base64_encode(openssl_encrypt($value, "aes-256-cbc", $key, true, str_repeat(chr(0), 16))); 
	return urlencode(base64_encode(openssl_encrypt($value, "aes-256-cbc", $key, true, str_repeat(chr(0), 16)))); 
} 
function Decoder($value){
	$key = "homepagekoreakey";
	return openssl_decrypt(base64_decode($value), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
}
?>