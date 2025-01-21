<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<?php
/****
[{
"usercode" : "test001",
"deptcode" : "XX-XXX-XX",
"biz_type": "at",
"yellowid_key" : "1234567890123456789012345678901234567890",
"message_id":"123",
"to" : "82-1000000000",
"text" : "홍길동님, 안녕하세요. 슈어엠주식회사입니다.",
"reqphone" : "15884640",
"template_code" : "A_1000",
"re_send" : "R",
"re_text" : "대체 메시지입니다.",
"template_title" : "강조형메시지 제목",
"attachment": {"button":[{"name":"Web바로가기","type":"WL","url_pc":"https://surebiz.co.kr/",
"url_mobile":"https://surebiz.co.kr/", "target": "out"}]},
"supplement": {"quick_reply":[{"name":"Web바로가기","type":"WL","url_pc":"https://surebiz.co.kr/",
"url_mobile":"https://surebiz.co.kr/", "target": "out"}]}
}]

403에러시 ip 등록 > 전화해야함
*********/
$usercode		= "nyfood2012";		// nyfood2012				
$deptcode		= "XZ-EDZ-ZU";		// 회사코드
$biz_type		= "at";				// 타입 : at/알림톡
$yellowid_key	= "851ee8737693ed296f9d5a2e6d03bc99eda56b27";	// 발신프로필키 : 851ee8737693ed296f9d5a2e6d03bc99eda56b27
$template_code	= "neworder_3";		// 템플릿 코드
$to				= "82-1066440212";	// 국가번호 포함 전화번호
$reqphone		= "15660798";		// 발신번호
$text			= "골든브라운 등록을 환영합니다!

아래와 같이 주문 아이디 및 비번 안내를 드립니다.

주문사이트 : https://c-portal.ecount.com/ECERP/Login/CSLogin

코드번호: goldenbrown
ID: 사업자등록번호
PW: abcd1234

1. 로그인 후 좌측 주문서 입력 클릭
2. 품목코드 더블클릭하면 원하시는 품목선정 후 수량기입
- 핸드폰으로 주문시 품목코드란에 제품명으로 검색
3. 수량입력후 아래 저장버튼 클릭

입금: 기업은행 025-505173-04-011 (엔와이푸드)
*꼭 상호명으로 입금
*상세 주문안내와 단가표는 이메일 참조해주세요.";

$template_code	= "ordercheck_2";		// 템플릿 코드
$text			= "홈코님 입금이 확인되었습니다.

수요일 에 배송될 예정입니다.

상품은 오전8시~오후6시 냉동차량 직배송됩니다.
상품 수령시 바로 검수하셔야 하자로 인한 교환/환불이 가능합니다.

고객센터 : 1566-0798

감사합니다";


//수신자 정보
$api_server = "https://rest.surem.com";		// 서버		https://rest.surem.com/messages/alimtalk
$api_url = "/messages/alimtalk";					// url

$jsonVars  = '"usercode":"'.$usercode.'"';			
$jsonVars .= ', "deptcode":"'.$deptcode.'"';		
$jsonVars .= ', "biz_type":"'.$biz_type.'"';		
$jsonVars .= ', "yellowid_key":"'.$yellowid_key.'"';
$jsonVars .= ', "template_code":"'.$template_code.'"';
$jsonVars .= ', "to":"'.$to.'"';
$jsonVars .= ', "reqphone":"'.$reqphone.'"';
$jsonVars .= ', "text":"'.$text.'"';

//	$jsonVars .= ', "attach":{"button":[{"name":"홈페이지 바로가기","type":"WL","url_mobile":"http://www.ellaboutique.com/","url_pc":"http://www.ellaboutique.com/"}]}';		// 버튼처리/웹링크

$postvars = '[{'.$jsonVars.'}]';      //JSON 데이터

//echo $postvars;

$headers = array("content-type: application/json; charset=utf-8");	// header

// api 호출
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $api_server.$api_url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
curl_setopt($ch,CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$response = curl_exec($ch);

curl_close($ch);
print_r($response);
?>
</body>
</html>
