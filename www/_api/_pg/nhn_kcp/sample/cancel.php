<?php
session_start();
header("Content-type: text/html; charset=utf-8");

$site_cd	= "AJW14";		## test : T0000 / real : AJW14
$tno		= $_POST['tno'];
$mod_type	= $_POST['mod_type'];	## STSC : 전체취소 / STPC : 부분취소

$mod_mny	= $_POST['mod_mny'];
$rem_mny	= $_POST['rem_mny'];

$cancelPay	= $_POST['cancelPay'];


$kcp_cert_info      = "-----BEGIN CERTIFICATE-----MIIDgTCCAmmgAwIBAgIHBy4lYNG7ojANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yMTA2MjkwMDM0MzdaFw0yNjA2MjgwMDM0MzdaMHAxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTERMA8GA1UECgwITG9jYWxXZWIxETAPBgNVBAsMCERFVlBHV0VCMRkwFwYDVQQDDBAyMDIxMDYyOTEwMDAwMDI0MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAppkVQkU4SwNTYbIUaNDVhu2w1uvG4qip0U7h9n90cLfKymIRKDiebLhLIVFctuhTmgY7tkE7yQTNkD+jXHYufQ/qj06ukwf1BtqUVru9mqa7ysU298B6l9v0Fv8h3ztTYvfHEBmpB6AoZDBChMEua7Or/L3C2vYtU/6lWLjBT1xwXVLvNN/7XpQokuWq0rnjSRThcXrDpWMbqYYUt/CL7YHosfBazAXLoN5JvTd1O9C3FPxLxwcIAI9H8SbWIQKhap7JeA/IUP1Vk4K/o3Yiytl6Aqh3U1egHfEdWNqwpaiHPuM/jsDkVzuS9FV4RCdcBEsRPnAWHz10w8CX7e7zdwIDAQABox0wGzAOBgNVHQ8BAf8EBAMCB4AwCQYDVR0TBAIwADANBgkqhkiG9w0BAQsFAAOCAQEAg9lYy+dM/8Dnz4COc+XIjEwr4FeC9ExnWaaxH6GlWjJbB94O2L26arrjT2hGl9jUzwd+BdvTGdNCpEjOz3KEq8yJhcu5mFxMskLnHNo1lg5qtydIID6eSgew3vm6d7b3O6pYd+NHdHQsuMw5S5z1m+0TbBQkb6A9RKE1md5/Yw+NymDy+c4NaKsbxepw+HtSOnma/R7TErQ/8qVioIthEpwbqyjgIoGzgOdEFsF9mfkt/5k6rR0WX8xzcro5XSB3T+oecMS54j0+nHyoS96/llRLqFDBUfWn5Cay7pJNWXCnw4jIiBsTBa3q95RVRyMEcDgPwugMXPXGBwNoMOOpuQ==-----END CERTIFICATE-----";
$kcp_cert_info      = "-----BEGIN CERTIFICATE-----MIIDjDCCAnSgAwIBAgIHBzDYTZprrDANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yNDAzMDQwNjMyMjhaFw0yOTAzMDMwNjMyMjhaMHsxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTEWMBQGA1UECgwNTkhOIEtDUCBDb3JwLjEXMBUGA1UECwwOUEdXRUJERVYgVGVhbS4xGTAXBgNVBAMMEDIwMjQwMzA0MTAwMDY5OTQwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC2+2LlKDVYS3L1LXS4daJ91rUuMZYFkBPcv+knY4k5CsagZ8M8uvbXK+Cy8VJxhXPe8zusDQmpBepIkJaAnQPVBGpXgZQqB/P2bebdMJK6jOB1lYB8z9x6zRkMS3VyfQ1qogoNFFmo8bTro/oqeMDj5RwUsnoxxYip8cGtX39hd7HK7RoxQJquIyGzs4/HzqKLLPAnqssVZEnLe89i+4xu8HMQQypU16Tyxm4G9CGVoRVIjEQegqGrS2LMaRDiFHdKk1Iq5/k9CgeuSZT9zDj6GqVG+CGJ0BrXXutFP5mxc+HFf4t2YoFG19nov72Rv/pNUc9qPJmWFmYOTKd56W49AgMBAAGjHTAbMA4GA1UdDwEB/wQEAwIHgDAJBgNVHRMEAjAAMA0GCSqGSIb3DQEBCwUAA4IBAQCDh3tMuz/htPZnrmlzM/f5pR56pST8TNxNSFjUF5q2ATjjmhidMvLkGnc9ruwCbYihEECqQEbKoDoJ6Hm1lJujdXRWj+mb66GEVqyCebFveDBrg+uHB+7dZzTU/viMu0t1EB7jQMfGcXv+AuN30Ury6rKMYhnwib2pVJxiWWesPx1wVsxESAsPpnSieRq8XKES5qRnNaC92VXkZPTP7L9S/VDUtOPEM2+oTtFMNZKgOhHgCNR/+flW8sO4Emta8ndYjAXrfWvm3nPE94kXNKx5nLdAZLi8k31w9UmPkZ+6kTz+MyGlxXgGA5TBNXie+MKWoyH9Ph6SM7Fl/EwtDppR-----END CERTIFICATE-----";

/* 
==========================================================================
취소 API URL                                                           
--------------------------------------------------------------------------
*/
//$target_URL = "https://stg-spl.kcp.co.kr/gw/mod/v1/cancel"; // 개발서버
$target_URL = "https://spl.kcp.co.kr/gw/mod/v1/cancel"; // 운영서버

// 서명데이터생성에시
// site_cd(사이트코드) + "^" + tno(거래번호) + "^" + mod_type(취소유형)
// NHN KCP로부터 발급받은 개인키(PRIVATE KEY)로 SHA256withRSA 알고리즘을 사용한 문자열 인코딩 값
$cancel_target_data = $site_cd . "^" . $tno . "^" . $mod_type;
//	echo $cancel_target_data;
/*
 ==========================================================================
 privatekey 파일 read
 --------------------------------------------------------------------------
 */
//$pemUrl = '\home\ellapg\www\_api\_pg\nhn_kcp\certificate\splPrikeyPKCS8.pem';
//$key_data = file_get_contents($pemUrl);
//	echo $_SERVER['DOCUMENT_ROOT'].'\_api\_pg\nhn_kcp\certificate\splPrikeyPKCS8.pem';
$key_data = "-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIE9jAoBgoqhkiG9w0BDAEDMBoEFH7zQRSTb6EbAyfdugpVqM1frRNoAgIIAASC
BMhe0D5h4CidCjHjyi6pdqz+SwJOtBQLnQdgfvoTboV5zl4AmqKgmd2eTbMoKMA5
q49qcKFRzf92BGHY34dzJYaRKBSlRXjv3yKNDbTKZHT1Pc7abRS3hvHsoPhRhZwv
ut6Kpazjx2RVZsZPUZQXU27NnLKFDI+V2LTQ5FRIoWYrNE5DGl4NCwfD5jsqK/RR
h+CTWGs/Ci+7GgSbTHrpfmENfv/F/SAHZAyi8yKRbMssU0mdjwc5s8K+ZFdQDNfC
+stUA2bLfTGfOu/ONgIX+A8QwdtINm/ubpNZKMsK6ar76goJ5hLfyxs8xZ9GFnnp
j5F0Rf6DdMiRj1WD9kLeylei895IdYz8GniUd9PA1LC6KBkt1bViRlorpGb+GfLW
/p5Minryaq6PbMNGKAJGpyuDAUVRu6F6FvmYIRgnV2bciRXObnm5uQUN5cFJwaC5
gIlNg5l9xUkeZvvt0uVWRyXQ/6ZoBzmry6fbulENLeADRwUiYowZp/Eo2AiGycKw
z1Cb8W8vGkTC4q+ekMsb9N8u6Q+mIpYCoWlK1kpOn67YSBJaEmJ3m5NFVoQBGFWa
Lyu/FLfaVCEME5kdKffG5Te5ui9WTMtPSw2apIy8btDkZfk8UnVoAoiwFNIBBgcf
TMpFD37LuSszGrj4dCfkzJE+eeM9k+IHe2+IUjxNiVPvFLBLELnd+NnzEtRJiCLr
/+aP3ySUbmV4Mpss+OJ2Zl+4Gj7DONg2NFa7CYwsMWGAvOwNLQIem0vsl6RZIUor
pDFWu5/Mo/fdFvFYejuiLUtYZovB8nm+Fr4jxD5X8orHg+WQZJWkJK0vPTnKT9sJ
VZCKxYrRTFa4ZrETadGwWvOOB7MAS3H7Fgrv0R2urtIi8EjuA2vv3reye12ktOjR
vv3NNMvYV3+f40HsF+QgRROo8Yt/eokEOVPqU2gfeTeavd/oogpOEgdHQxce2OgQ
haI1cbyQzFwgo4cwderM/+c6Zah6q/6+vVke3AR1hcGBX3bpKfST3qUOzSbx7wVR
Z0J42PcHVjqoE7O0TXDYTelB55gxp0rv/mq2vgwIWWYGeDre3rW9HKD9CwHveBTX
HBJZ9jUewHlLiKHcLjslPkDKFj+I8u9IuEKlW+1Tm4PqcDgTGecRQRJJozNlY2+V
lnAvCOtens7o7uhUGxAYuHVda3CFwDk4Glqy0bQDH1GxgN+IGx7I8OONil4G+UVr
RokQe4GaQ+MDBgkDK39YGw6LtgOhBycfUQLeSahgJ9U5f+sOxQlkGfxAZCmRqhzW
EVCq5IqX8v3NwkdaaUXqwEhm0gzmUTPMky9RpiDtA8XFbQCsZUlBGVuRB/zuL+iS
54E99lQFG1QpCnFp3+3fkYfMwKBb4Df0+qI1GC9XBGBHh58nr4qcXC8WrMpPCCFH
XruFJOvVbxsQYvVlJt8/2xYcABYtkL+Gy9+t7dBf+Lc1gcUP4i+SVomsmekOuP4j
/aOwDynRkUfGLi59pmlc/xEqqWt7JuzpU+rDGxRGn0RK/IzmuR3Tr9UcRQIS9TTg
dfrucLbTlKT/yZwdYd2KJ6ewLssWFln/tFi4pOI+2S362+XKdPJzyY5wKmIPifgH
G4B68+gmeJulARqtEoa7pZHCQ3KwSU2hBJQ=
-----END ENCRYPTED PRIVATE KEY-----";
/*
 ==========================================================================
 privatekey 추출
 'changeit' 은 테스트용 개인키비밀번호 ************************중요** 꼭 수정해야함 실결제시
 --------------------------------------------------------------------------
 */

$pri_key = openssl_pkey_get_private($key_data,'!cubecake4');

/*
 ==========================================================================
 sign data 생성
 --------------------------------------------------------------------------
 */
// 결제 취소 signature 생성
openssl_sign($cancel_target_data, $signature, $pri_key, 'sha256WithRSAEncryption');
//	echo "cancel_signature :".base64_encode($signature)."<br><br>";

$kcp_sign_data = base64_encode($signature);

//	$target_URL = "https://spl.kcp.co.kr/gw/mod/v1/cancel"; // 취소 개발서버
$target_URL = "https://spl.kcp.co.kr/gw/mod/v1/cancel"; // 운영서버

if( $mod_type == "STSC"){
	$data = [
		'site_cd'        => $site_cd,
		'kcp_cert_info'  => $kcp_cert_info,
		'kcp_sign_data'  => $kcp_sign_data,
		'tno'            => $tno,
		'mod_type'       => $mod_type,
		'mod_desc'       => '취소',
	];    
}else if( $mod_type == "STPC"){
	$data = [
		'site_cd'        => $site_cd,
		'kcp_cert_info'  => $kcp_cert_info,
		'kcp_sign_data'  => $kcp_sign_data,
		'tno'            => $tno,
		'mod_type'       => $mod_type,
		'mod_desc'       => '부분취소',
		'mod_mny'        => $mod_mny,		
		'rem_mny'        => $rem_mny		
	];    
}

$req_data = json_encode($data);

$header_data = array( "Content-Type: application/json", "charset=utf-8" );

// API REQ
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $target_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// API RES
$res_data  = curl_exec($ch);

// RES JSON DATA Parsing
$json_res = json_decode($res_data, true);

//print_r($res_data);
$res_cd = $json_res["res_cd"];
$res_msg = $json_res["res_msg"];

curl_close($ch);

if($res_cd=="0000"){	## 정상처리
	echo "true";
	include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
	
	$dblink = SetConn($_conf_db["main_db"]);
	if( $mod_type == "STPC"){	// 부분취소/교환/환불
		$sql = "update tbl_shop_order_info set order_state='5',claim_amount='".($mod_mny+$cancelPay)."', claim_date=now()  where tid='".$tno."'";			
	}else{						// 교환/환불완료
		$sql = "update tbl_shop_order_info set order_state='4',claim_amount='".($mod_mny+$cancelPay)."', claim_date=now()  where tid='".$tno."'";			
	}
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	SetDisConn($dblink);
}else{
	echo "error:".$res_msg." | ".$tno;
}
?>