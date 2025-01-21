<?php
	// Parameter 수신
	$rec_cert       = $_REQUEST['rec_cert'];
	$cookieCertNum  = $_REQUEST['certNum']; // certNum값을 쿠키 또는 Session을 생성하지 않았을때 certNum 수신처리

	$iv = $cookieCertNum;  // certNum값을 쿠키 또는 Session을 생성하지 않았을때 수신한 certNum을 복호화키에 세팅

		
		//암호화모듈 호출
		if (extension_loaded('ICERTSecu')) {

			//01.인증결과 1차 복호화
			$rec_cert = ICertSeed(2,0,$iv,$rec_cert);

			//02.복호화 데이터 Split (rec_cert 1차암호화데이터 / 위변조 검증값 / 암복화확장변수)
			$decStr_Split = explode("/", $rec_cert);

			$encPara  = $decStr_Split[0];		//rec_cert 1차 암호화데이터
			$encMsg   = $decStr_Split[1];		//위변조 검증값

			//03.인증결과 2차 복호화
			$rec_cert = ICertSeed(2,0,$iv,$encPara);

			//04. 복호화 된 결과자료 "/"로 Split 하기
			$decStr_Split = explode("/", $rec_cert);

			$certNum    = $decStr_Split[0];
			$date       = $decStr_Split[1];
			$CI         = $decStr_Split[2];
			$phoneNo    = $decStr_Split[3];
			$phoneCorp  = $decStr_Split[4];
			$birthDay   = $decStr_Split[5];
			$gender     = $decStr_Split[6];
			$nation     = $decStr_Split[7];
			$name       = $decStr_Split[8];
			$result     = $decStr_Split[9];
			$certMet    = $decStr_Split[10];
			$ip         = $decStr_Split[11];
			$M_name     = $decStr_Split[12];
			$M_birthDay = $decStr_Split[13];
			$M_Gender   = $decStr_Split[14];
			$M_nation   = $decStr_Split[15];
			$plusInfo   = $decStr_Split[16];
			$DI         = $decStr_Split[17];

			//05. CI,DI 복호화
			if(strlen($CI) > 0){
				$CI = ICertSeed(2,0,$iv,$CI);
			}
			if(strlen($DI) > 0){
				$DI = ICertSeed(2,0,$iv,$DI);
			}		

		}else{
		   echo("암호화모듈 호출 실패!!!");
		   return;
		}

/** 수신내역 유효성 검증 ******************************************************************/
		//	1-1-1) date 값 검증

		//	현재 서버 시각 구하기
		 $end_date = date("YmdHis");
		 $start_date = $date;
 
		 //mktime()을 만들기 위해 각 시간 단위로 분할
		 $yy = substr($end_date, 0, 4);
		 $mm = substr($end_date, 4, 2);
		 $dd = substr($end_date, 6, 2);
		 $hh = substr($end_date, 8, 2);
		 $ii = substr($end_date, 10, 2);
		 $ss = substr($end_date, 12, 2);
	 
		 //mktime()을 만들기 위해 DB에서 불러온 datetime 값을 시간 단위로 분할
		 $yy_start = substr($start_date, 0, 4);   
		 $mm_start = substr($start_date, 4, 2);   
		 $dd_start = substr($start_date, 6, 2);   
		 $hh_start = substr($start_date, 8, 2);   
		 $ii_start = substr($start_date, 10, 2);  
		 $ss_start = substr($start_date, 12, 2);  
     
		 $toDate = mktime($hh, $ii, $ss, $mm, $dd, $yy);
	     $fromDate = mktime($hh_start, $ii_start, $ss_start, $mm_start, $dd_start, $yy_start);
		 $timediff = intval(($toDate - $fromDate) / 60);		// 분
		
		if ( $timediff < -30 || 30 < $timediff  ){		?>
			비정상적인 접근입니다. (요청시간경과)
<?php			return;
		}

	//	1-1-2) ip 값 검증
		// 사용자IP 구하기
		$client_ip = "";
		if (isset($_SERVER)) {

			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
				$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			
			if (isset($_SERVER["HTTP_CLIENT_IP"]))
				$client_ip = $_SERVER["HTTP_CLIENT_IP"];

			$client_ip = $_SERVER["REMOTE_ADDR"];
		}

		if (getenv('HTTP_X_FORWARDED_FOR'))
			$client_ip = getenv('HTTP_X_FORWARDED_FOR');

		if (getenv('HTTP_CLIENT_IP'))
			$client_ip = getenv('HTTP_CLIENT_IP');
		
		if( $client_ip == "" ) 
			$client_ip = getenv('REMOTE_ADDR');
		
		$client_ip_list = explode(",",$client_ip);
		$client_ip = $client_ip_list[0];
		
		if( $client_ip != $ip ){		?>
		비정상적인 접근입니다. (IP불일치(<?php echo $client_ip ?>)(<?php echo $ip ?>))
<?php			return;
		}
/******************************************************************************************/

$param = urlencode($name."|".$phoneNo."|".$birthDay."|".$gender);
?>
            <!-- <table cellpadding=1 cellspacing=1>
                <tr>
                    <td align=left>위변조데이터</td>
                    <td align=left><?php echo $encMsg ?></td>
                </tr>
                <tr>
                    <td align=left>요청번호</td>
                    <td align=left><?php echo $certNum ?></td>
                </tr>
                <tr>
                    <td align=left>요청일시</td>
                    <td align=left><?php echo $date ?></td>
                </tr>
                <tr>
                    <td align=left>연계정보(CI)</td>
                    <td align=left><?php echo $CI ?></td>
                </tr>
                <tr>
                    <td align=left>중복가입확인정보(DI)</td>
                    <td align=left><?php echo $DI ?></td>
                </tr>
                <tr>
                    <td align=left>휴대폰번호</td>
                    <td align=left><?php echo $phoneNo ?></td>
                </tr>
                <tr>
                    <td align=left>이동통신사</td>
                    <td align=left><?php echo $phoneCorp ?></td>
                </tr>
                <tr>
                    <td align=left>생년월일</td>
                    <td align=left><?php echo $birthDay ?></td>
                </tr>
                <tr>
                    <td align=left>내국인</td>
                    <td align=left><?php echo $nation ?></td>
                </tr>
                <tr>
                    <td align=left>성별</td>
                    <td align=left><?php echo $gender ?></td>
                </tr>
                <tr>
                    <td align=left>성명</td>
                    <td align=left><?php echo $name ?></td>
                </tr>
                <tr>
                    <td align=left>결과값</td>
                    <td align=left><?php echo $result ?></td>
                </tr>
                <tr>
                    <td align=left>인증방법</td>
                    <td align=left><?php echo $certMet ?></td>
                    </td>
                </tr>
                <tr>
                    <td align=left>ip주소</td>
                    <td align=left><?php echo $ip ?></td>
                </tr>
                <tr>
                    <td align=left>미성년자 성명</td>
                    <td align=left><?php echo $M_name ?></td>
                </tr>
                <tr>
                    <td align=left>미성년자 생년월일</td>
                    <td align=left><?php echo $M_birthDay ?></td>
                </tr>
                <tr>
                    <td align=left>미성년자 성별</td>
                    <td align=left><?php echo $M_Gender ?></td>
                </tr>
                <tr>
                    <td align=left>미성년자 내외국인</td>
                    <td align=left><?php echo $M_nation ?></td>
                </tr>
                <tr>
                    <td align=left>추가DATA정보</td>
                    <td align=left><?php echo $plusInfo ?></td>
                </tr>
            </table>
 -->
<html>
<head>
<script type="text/javascript">
	function end() {
		window.opener.location.href = '/member.php?goPage=Join&param=<?=$param?>';
 		self.close();
	}
</script>
</head>
<body onload="end();">
</body>
</html>

