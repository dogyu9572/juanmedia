<?php
    header("Content-type: text/html; charset=euc-kr");
	

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

    $req_tx            = "";
    $site_cd           = "";
    $ordr_idxx         = "";
    $user_name         = "";
    $sex_code          = "";

    $web_siteid        = "";
    $web_siteid_hashYN = "";
    
    $up_hash           = "";
    $Ret_URL           = "";
    $kcp_merchant_time = "";
    $kcp_cert_lib_ver  = "";
	/*------------------------------------------------------------------------*/
    /*  :: 전체 파라미터 남기기                                                      */
    /*------------------------------------------------------------------------*/

    // request 로 넘어온 데이터 처리
    foreach($_POST as $nmParam => $valParam)
    {

        if ( $nmParam == "site_cd" )
        {
            $site_cd = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "req_tx" )
        {
            $req_tx = f_get_parm_str ( $valParam );
        }

        if ( $nmParam == "ordr_idxx" )
        {
            $ordr_idxx = f_get_parm_str ( $valParam );
        }

            $sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . f_get_parm_str( $valParam ) . "'/>";
	}
	
			$sbParam .= "<input type='hidden' name='" . $up_hash . "' value='" . f_get_parm_str( "up_hash" ) . "'/>";
			
    if( $web_siteid_hashYN =="Y" ) 
    {
        $sbParam .= "<input type='hidden' name='" . $web_siteid_hashYN . "' value='" . f_get_parm_str( $web_siteid_hashYN ) . "'/>";
        $sbParam .= "<input type='hidden' name='" . $web_siteid . "' value='" . f_get_parm_str( $web_siteid ) . "'/>";
    }   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html">
        <link href="../static/css/style.css" rel="stylesheet" type="text/css" id="cssLink"/>    
        <title>*** NHN KCP Online Payment System [php Version] ***</title>
        <script type="text/javascript">
            window.onload=function()
            {
                cert_page();
            }

			// 인증 요청 시 호출 함수
            function cert_page()
            {
                var frm = document.form_auth;

				if ( ( frm.req_tx.value == "auth" || frm.req_tx.value == "otp_auth" ) )
                {
                    frm.action="./kcp_cert_res.php";
                    
                    frm.submit();
                }
				
				else if ( frm.req_tx.value == "cert" )
                {
                    //frm.action = "https://testcert.kcp.co.kr/kcp_cert/cert_view.jsp";
                    frm.action = "https://cert.kcp.co.kr/kcp_cert/cert_view.jsp";  // 운영계
					frm.submit();
                }
			}

        </script>
    </head>
    <body oncontextmenu="return false;" ondragstart="return false;" onselectstart="return false;">
        <form name="form_auth" method="post">
		    <?=$sbParam ?>
        </form>
    </body>
</html>
