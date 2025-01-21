<?
	/* ============================================================================== */
	/* =   환경 설정 (업체에 맞게 수정)                                             		  	= */
	/* ============================================================================== */
	/* = ※ 주의 ※                                                                 	= */
	/* = * g_conf_site_cd 설정                                                    	= */
	/* =                                                                            = */
	/* = 실제 인증 시 : 반드시 KCP에서 발급한 사이트코드(site_cd)                    				= */
	/* =            로 설정해 주십시오.                                             		= */
	/* =                                                                            = */
	/* = * g_conf_cert_info 설정                                                    	= */
	/* =                                                                            = */
	/* = 실제 인증 시 : 반드시 KCP에서 발급한 서비스 인증서(직렬화)                    				= */
	/* =            로 설정해 주십시오.                                             		= */
	/* =                                                                            = */
	/* = * g_conf_web_siteid 설정                                                  	= */
	/* =                                                                            = */
	/* = 실제인증 시 : 기존에 DI 관리를 위해 설정하여 사용하시는 값이 있는 경우에만 						= */
	/* =           설정 바랍니다. 값이 없으면 KCP에서 지정한 값으로 설정됩니다.     					= */
	/* =                                                                            = */                                                       
	/* = * g_conf_Ret_URL  설정                                                    	= */
	/* =                                                                            = */
	/* = 반드시 업체 리턴페이지 주소를 FULL URL로 설정하십시요                      				= */
	/* =                                                                            = */
	/* = * g_conf_cert_url 설정                                                    	= */
	/* = 테스트 시 : src="https://stg-spl.kcp.co.kr/std/certpass"        				= */
	/* = 실인증 시 : src="https://spl.kcp.co.kr/std/certpass"            				= */
	/* ============================================================================== */
    
	$g_conf_site_cd    = "AJW7A";	// test : AO0QE / AJW7A
	$g_conf_Ret_URL    = "http://goldenpg.hk-test.co.kr/_api/_nhnkcp/kcpcert_api/sample/kcp_cert_req.php";
	$g_conf_cert_info  = "-----BEGIN CERTIFICATE-----MIIDgTCCAmmgAwIBAgIHBy4lYNG7ojANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yMTA2MjkwMDM0MzdaFw0yNjA2MjgwMDM0MzdaMHAxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTERMA8GA1UECgwITG9jYWxXZWIxETAPBgNVBAsMCERFVlBHV0VCMRkwFwYDVQQDDBAyMDIxMDYyOTEwMDAwMDI0MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAppkVQkU4SwNTYbIUaNDVhu2w1uvG4qip0U7h9n90cLfKymIRKDiebLhLIVFctuhTmgY7tkE7yQTNkD+jXHYufQ/qj06ukwf1BtqUVru9mqa7ysU298B6l9v0Fv8h3ztTYvfHEBmpB6AoZDBChMEua7Or/L3C2vYtU/6lWLjBT1xwXVLvNN/7XpQokuWq0rnjSRThcXrDpWMbqYYUt/CL7YHosfBazAXLoN5JvTd1O9C3FPxLxwcIAI9H8SbWIQKhap7JeA/IUP1Vk4K/o3Yiytl6Aqh3U1egHfEdWNqwpaiHPuM/jsDkVzuS9FV4RCdcBEsRPnAWHz10w8CX7e7zdwIDAQABox0wGzAOBgNVHQ8BAf8EBAMCB4AwCQYDVR0TBAIwADANBgkqhkiG9w0BAQsFAAOCAQEAg9lYy+dM/8Dnz4COc+XIjEwr4FeC9ExnWaaxH6GlWjJbB94O2L26arrjT2hGl9jUzwd+BdvTGdNCpEjOz3KEq8yJhcu5mFxMskLnHNo1lg5qtydIID6eSgew3vm6d7b3O6pYd+NHdHQsuMw5S5z1m+0TbBQkb6A9RKE1md5/Yw+NymDy+c4NaKsbxepw+HtSOnma/R7TErQ/8qVioIthEpwbqyjgIoGzgOdEFsF9mfkt/5k6rR0WX8xzcro5XSB3T+oecMS54j0+nHyoS96/llRLqFDBUfWn5Cay7pJNWXCnw4jIiBsTBa3q95RVRyMEcDgPwugMXPXGBwNoMOOpuQ==-----END CERTIFICATE-----";
	$g_conf_cert_info  = "-----BEGIN CERTIFICATE-----MIIDjDCCAnSgAwIBAgIHBzDWNSlRMDANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQGEwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoMDE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3BsLmtjcC5jby5rcjAeFw0yNDAyMTQwNjExMTJaFw0yOTAyMTIwNjExMTJaMHsxCzAJBgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTEWMBQGA1UECgwNTkhOIEtDUCBDb3JwLjEXMBUGA1UECwwOUEdXRUJERVYgVGVhbS4xGTAXBgNVBAMMEDIwMjQwMjE0MTAwMDY5MTAwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCfnnNBKnudKbBiKMEZkkIB1wFyUoYA2JvHrmuE2/8+y2hARdFg0Id9pm2D13npKN0QxWbr5t0fqXMdAeKcunaoctVg4Zk/NSrvoGHDHsIXLP0dGiTu/gpfmAE4HA1wcxVsn32YO9n1K+j+SBwVcibwkliJZwyq5jb9mRH8OlLw+vgbcqriCKwPqcy/8elRYhb5xcY4+Ua1u1ZQt+LiHmsLF8CYGUZPP0EGeWi/oVm21Y8aHRXYwGSV+G/TrsSQRCowm5PswkGCXOo9GpAB4GWV1qK1zmhgnk7E1k4raAfv8G6OxA5g6YsBZBPIa9/PwC/2CeB6xSqCoWahIMHT0HstAgMBAAGjHTAbMA4GA1UdDwEB/wQEAwIHgDAJBgNVHRMEAjAAMA0GCSqGSIb3DQEBCwUAA4IBAQANnNVLi/fEg/MpecY4t0b718eJrZBi077UEcf3TTLFa1A2/eYB2yNtkQV8MXy5g+oPyLdiOxe+kmWQk2gQMw5hD/fvmIhD3crDfzB+mAPs7U1OWlEmY9JFprwsul0qvSw/l8+n1kZYpZk3lAYacLOb4TkL5SIVfwaFbN/pPVQw5FsIfI8zdnit5zvesE9/Ul6wlMe0iI9OOj/2jfhK0YA1pBAiwlwpt3HlKfia/eENCZhbVaO0vAlar9C+6tLEXPT2xHR/A3xZwazcfvmNERek5Au4Q8KzTDesEJZgo1Vurks88HdQMVxDx1B2dm/b0dvth7Cgy6KYScqRVeb+dGGr-----END CERTIFICATE-----";
	$g_conf_cert_url   = "https://stg-spl.kcp.co.kr/std/certpass";
	$g_conf_cert_url   = "https://spl.kcp.co.kr/std/certpass";
	$g_conf_web_siteid_hashYN = "";
	$g_conf_web_siteid = "";
?>
