<?php

	header("Content-type: text/html; charset=utf-8");
	
	/* ============================================================================== */
    /* =   환경 설정 파일 Include                                                   		= */
    /* = -------------------------------------------------------------------------- = */
    /* =   ※ 필수                                                                  	= */
    /* =   테스트 및 실결제 연동시 site_conf_inc.php파일을 수정하시기 바랍니다.     					= */
    /* = -------------------------------------------------------------------------- = */

    include "./cfg/site_conf_inc.php";

    /* = -------------------------------------------------------------------------- = */
    /* =   환경 설정 파일 Include END                                               		= */
    /* ============================================================================== */
	
	/* 
	====================================================================================
		요청정보                                                                
	------------------------------------------------------------------------------------
	*/

	$ct_type = $_POST["ct_type"];
	$ordr_idxx = $_POST["ordr_idxx"];
	$make_req_dt = $_POST["make_req_dt"];

	$hash_data = $g_conf_site_cd . "^" .  $ct_type . "^" . $make_req_dt; //up_hash 생성 서명 데이터

	// privatekey 파일 read
	$key_data = file_get_contents($_SERVER['DOCUMENT_ROOT']."/_api/_nhnkcp/kcpcert_api/certificate/splPrikeyPKCS8.pem");
	// privatekey 추출,  'changeit' 은 테스트용 개인키비밀번호
	$pri_key = openssl_pkey_get_private($key_data, "anybake0923@");		//	test : changeit / anybake0923@
	//서명 데이터(무결성 검증)
	openssl_sign($hash_data, $signature, $pri_key, "sha256WithRSAEncryption");
	$kcp_sign_data = base64_encode($signature);

	$data = array(
		"site_cd"       => $g_conf_site_cd,
		"kcp_cert_info" => $g_conf_cert_info,
		"ct_type"       => $ct_type,
		"ordr_idxx"     => $ordr_idxx,
		"web_siteid"    => $g_conf_web_siteid,
		"make_req_dt"   => $make_req_dt,
		"kcp_sign_data" => $kcp_sign_data
	);

	$req_data = json_encode($data);

	$header_data = ["Content-Type: application/json", "charset=utf-8"];

	// up_hash 생성 REQ DATA
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

	if($res_cd == "0000"){
		
		$res_en_msg = $json_res["res_en_msg"];
		$up_hash = $json_res["up_hash"];
		$kcp_merchant_time = $json_res["kcp_merchant_time"];
		$kcp_cert_lib_ver = $json_res["kcp_cert_lib_ver"]; 
	}
	curl_close($ch); 
?>
<!DOCTYPE>
<html>
<head>
    <title>*** NHN KCP API SAMPLE ***</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">  
    <script type="text/javascript">
    function goReq()
        {
            if ('<?=$res_cd ?>' == "0000")
            {
                //alert("up_hash 생성 성공");
				document.form_hash.action = "./sample/kcp_cert_start_frame.php";			    
                document.form_hash.submit();
            }
			else 
			{
                alert("에러 코드 : "+ '<?=$res_cd ?>' +", 에러 메세지 : "+ '<?=$res_msg ?>');
                location.href = "sample/make_hash.php";
            }
        }
    </script>
</head>
<body onload="goReq();">
    <div class="wrap">
        <!-- up_hash 응답 파라미터를 form 데이터로 전송 -->
        <form name="form_hash" method="post">
        <input type="hidden" name="site_cd" value="<?=$g_conf_site_cd ?>"/>
        <input type="hidden" name="ordr_idxx" value="<?=$ordr_idxx ?>"/>
        <input type="hidden" name="up_hash" value="<?=$up_hash ?>"/>
        <input type="hidden" name="web_siteid" value="<?=$web_siteid ?>"/>
        <input type="hidden" name="kcp_merchant_time" value="<?=$kcp_merchant_time ?>"/>
        <input type="hidden" name="kcp_cert_lib_ver" value="<?=$kcp_cert_lib_ver ?>"/>
        <input type="hidden" name="web_siteid_hashYN" value="<?=$web_siteid_hashYN ?>"/> 
        </form>
    </div>
<!--//wrap-->
</body>
</html>
