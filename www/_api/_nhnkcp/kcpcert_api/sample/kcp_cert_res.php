<?php
	session_start();
    header("Content-type: text/html; charset=UTF-8");
	

    /* ============================================================================== */
    /* =   환경 설정 파일 Include                                                   		= */
    /* = -------------------------------------------------------------------------- = */
    /* =   ※ 필수                                                                  	= */
    /* =   테스트 및 실결제 연동시 site_conf_inc.php파일을 수정하시기 바랍니다.     					= */
    /* = -------------------------------------------------------------------------- = */

    include "../cfg/site_conf_inc.php";

    /* = -------------------------------------------------------------------------- = */
    /* =   환경 설정 파일 Include END                                               		= */
    /* ============================================================================== */
	
    /* ============================================================================== */
    /* =   null 값을 처리하는 메소드                                                = */
    /* = -------------------------------------------------------------------------- = */
    function f_get_parm_str( $val )
    {
        if ( $val == null ) $val = "";
        return  $val;
    }

	$res_cd = $_POST["res_cd"];
	$enc_cert_data2 = $_POST["enc_cert_data2"];

    // privatekey 파일 read
	$key_data = file_get_contents($_SERVER['DOCUMENT_ROOT']."/_api/_nhnkcp/kcpcert_api/certificate/splPrikeyPKCS8.pem");

    if( $res_cd =="0000" ) 
    {
		$up_hash = $_POST["up_hash"];
        $ct_type = "CHK";
        $site_cd = $_POST["site_cd"];
        $ordr_idxx = $_POST["ordr_idxx"]; // dn_hash 검증 요청 전 가맹점 DB상의 주문번호와 동일한지 검증 후 요청 바랍니다.
        $web_siteid = $_POST["web_siteid"];
        $cert_no = $_POST["cert_no"];
        $dn_hash = $_POST["dn_hash"];
    
        $hash_data = $site_cd . "^" .  $ct_type . "^" . $cert_no . "^" . $dn_hash; // dn_hash 검증  서명 데이터

        // privatekey 추출,  'changeit' 은 테스트용 개인키비밀번호
        $pri_key = openssl_pkey_get_private($key_data, "anybake0923@");		//	test : changeit / anybake0923@
        //서명 데이터(무결성 검증)
        openssl_sign($hash_data, $signature, $pri_key, "sha256WithRSAEncryption");
        $kcp_sign_data = base64_encode($signature);

        $data = [
            "site_cd" => $site_cd,
            "kcp_cert_info" => $g_conf_cert_info,
            "ct_type" => $ct_type,
            "ordr_idxx" => $ordr_idxx,
            "cert_no" => $cert_no,
            "dn_hash" => $dn_hash,
            "kcp_sign_data" => $kcp_sign_data,
        ];

        $req_data = json_encode($data);
        $header_data = ["Content-Type: application/json", "charset=UTF-8"];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $g_conf_cert_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // API RES
        $res_data = curl_exec($ch);

        // RES JSON DATA Parsing
        $json_res = json_decode($res_data, true);

        $dn_res_cd = $json_res["res_cd"];
		
        curl_close($ch); 

        if($dn_res_cd == "0000"){ //dn_hash 검증이 완료 일 때
            $ct_type = "DEC";
            $hash_data = $site_cd . "^" .  $ct_type . "^" . $cert_no; // dn_hash 검증  서명 데이터

            // privatekey 추출,  'changeit' 은 테스트용 개인키비밀번호
            $pri_key = openssl_pkey_get_private($key_data, "anybake0923@");
            //서명 데이터(무결성 검증)
            openssl_sign($hash_data, $signature, $pri_key, "sha256WithRSAEncryption");
            $kcp_sign_data = base64_encode($signature);
    
            $data = [
                "site_cd" => $site_cd,
                "kcp_cert_info" => $g_conf_cert_info,
                "ct_type" => $ct_type,
                "ordr_idxx" => $ordr_idxx,
                "cert_no" => $cert_no,
                "enc_cert_Data" => $enc_cert_data2,
                "kcp_sign_data" => $kcp_sign_data,
            ];
    
            $req_data = json_encode($data);
            $header_data = ["Content-Type: application/json", "charset=UTF-8"];
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $g_conf_cert_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
            // API RES
            $res_data = curl_exec($ch);
    
            // RES JSON DATA Parsing
            $json_res = json_decode($res_data, true);
    
            $res_cd = $json_res["res_cd"];
            $res_msg = $json_res["res_msg"];
            $comm_id = $json_res["comm_id"];
            $phone_no = $json_res["phone_no"];
            $user_name = $json_res["user_name"];
            $birth_day = $json_res["birth_day"];
            $sex_code = $json_res["sex_code"];
            $local_code = $json_res["local_code"];

            $ci = $json_res["ci"];
            $di = $json_res["di"];
            $ci_url = $json_res["ci_url"];
            $di_url = $json_res["di_url"];
            $web_siteid = $json_res["web_siteid"];

            echo "결과코드 : ".$res_cd."<br>";
            echo "결과메세지 : ".$res_msg."<br>";
            echo "이동통신사 코드 : ".$comm_id."<br>";
            echo "전화번호 : ".$phone_no."<br>";
            echo "이름 : ".$user_name."<br>";
            echo "생년월일 : ".$birth_day."<br>";
            echo "성별코드 : ".$sex_code."<br>";
            echo "내/외국인 정보 : ".$local_code."<br>";
            echo "CI : ".$ci."<br>";
            echo "DI : ".$di."<br>";
            echo "CI_URL : ".$ci_url."<br>";
            echo "DI_URL : ".$di_url."<br>";

            curl_close($ch); 

			###########################
			$_SESSION['CERT_MEMBER']['MOBILE'] = $phone_no;
			$_SESSION['CERT_MEMBER']['USERNM'] = $user_name;

        }
    }else/*if( res_cd.equals( "0000" ) != true )*/
    {
       // 인증실패
	   echo "인증실패";
	   $_SESSION['CERT_MEMBER']['MOBILE'] = "";
	   $_SESSION['CERT_MEMBER']['USERNM'] = "";
    }    
?>
<script type="text/javascript">
<!--
opener.parent.location.reload();
window.close();	
//-->
</script>
<body style="display:none;"></body>