<?
session_start();
include_once $_SERVER[DOCUMENT_ROOT] . "/common/conf/config.inc.php";
include_once $_SERVER[DOCUMENT_ROOT] . "/common/conf/dbconfig.inc.php";
include_once $_SERVER[DOCUMENT_ROOT] . "/module/member/member.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";


$dblink = SetConn($_conf_db["main_db"]);
$code		= $_GET["code"];

$now_url	= "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
$url		= "https://kauth.kakao.com/oauth/token?";
$data[0]	= "grant_type=authorization_code";
$data[1]	= "client_id=3ab50760bb4e5594d889022823c143da";
$data[2]	= "redirect_uri=".urlencode($now_url);
$data[3]    = "code=".$code;

$url .= implode("&",$data); 
$ch		= curl_init();

//echo $url;

curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
curl_setopt($ch, CURLOPT_HTTPHEADER, 'Content-Type: application/x-www-form-urlencoded');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);

curl_close($ch);

$obj = json_decode($response);

if($obj->error != ""){
?>
	<script>
		alert("토큰이 만료되었습니다.");
		history.back();
	</script>
<?
}else{
	$accessToken = $obj->access_token;
	$userInfoUrl = "https://kapi.kakao.com/v2/user/me";

	$auth[0] = "Content-type: application/x-www-form-urlencoded";
	$auth[1] = "charset=utf-8";
	$auth[2] = "Authorization: Bearer ".$accessToken;

	$ch		= curl_init();

	curl_setopt($ch, CURLOPT_URL, $userInfoUrl);               //URL 지정하기
	curl_setopt($ch, CURLOPT_HTTPHEADER, $auth);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$res_info = curl_exec($ch);

	curl_close($ch);

	$obj_info = json_decode($res_info);

	//var_dump($obj_info);

	if($obj_info->id ==""){
		?>
			<script>
				alert("사용자 정보를 들고 올 수 없습니다.");
				history.back();
			</script>
		<?
	}else{ // 성공시
		
		$id = "kakao_".$obj_info->id; // 카카오 아이디 생성
		//$pw = "kakao_".$obj_info->id."@pwd"; // 카카오 비밀번호 생성

		$user_info = getUserInfo($id); // 아이디 확인

		//var_dump($user_info);

		if($user_info["total"] > 0){
			$user_id = mysqli_real_escape_string($GLOBALS['dblink'], trim($id));

			$arrInfo = getUserInfo($user_id, "Y");

			if($arrInfo["total"] < 1){
				jsMsg("해당하는 로그인 정보가 없습니다.");
				jsHistory("-1");
			}
			
			
			$RS = socialLoginMember($user_id);
			if($RS["total"] > 0){

				if($RS["list"][0]["user_level"]=="0"){	// 기본값 변경 1
					jsMsg("관리자 인증후 로그인이 가능합니다.");
					jsHistory("-1") ;
				}else{

					$arrLevelInfo  = getMemberLevelInfo($RS["list"][0]["user_level"]); //등급정보

					$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] = $RS["list"][0]["user_id"];
					$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"] = $RS["list"][0]["user_name"];
					$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["EMAIL"] = $RS["list"][0]["email"];
					$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"] = $RS["list"][0]["mobile"];
					$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["SOCIAL"] = true;
					$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] = $RS["list"][0]["user_level"];
					$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVELPOINT"] = $arrLevelInfo["list"][0]["level_point"];

					if($_REQUEST[save_id]=="1"){
						setcookie("login_id", $RS["list"][0]["user_id"], time()+(3600*24*30), "/", $_SERVER['SERVER_NAME']);
					}else{
						setcookie("login_id", "", time()+(3600*24*30), "/", $_SERVER['SERVER_NAME']);
					}

					//장바구니의 해당 세션을 로그인한 회원의 장바구니로 옮김
					@updateCartSession($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

					if($_REQUEST['rt_url']){
						jsGo($_REQUEST['rt_url'],"","");
					}else{
						jsGo("/","","");
					}
				}
			}else{
				jsMsg("오류가 발생했습니다.다시 시도해주세요.");
				jsHistory("-1") ;
			}
		}else{

			$phone = $obj_info->kakao_account->phone_number;

			$phone = str_replace("+82 ","0",$phone);

			//$phone = str_replace("-","",$phone); // 본인인증과 같은 형태 유지 // 현재는 - 를 붙여야함

			$name = $obj_info->kakao_account->name;

			$email = $obj_info->kakao_account->email;

			//$birth = $obj_info->kakao_account->birthyear.$obj_info->kakao_account->birthday;

			$birth = $obj_info->kakao_account->birthyear."-".substr($obj_info->kakao_account->birthday,0,2)."-".substr($obj_info->kakao_account->birthday,2,2);

			if($obj_info->kakao_account->gender == "male"){
				$sex = "M";
			}else if($obj_info->kakao_account->gender == "female"){
				$sex = "F";
			}

			//print_r($obj_info);
			$_SESSION[$_SITE["DOMAIN"]]["SOCIAL"]["ID"]  = $id;
			?>
				<form name="form1" action="/member/join2_social.php" method="post">
					<input type="hidden" name="phone" id="phone" value="<?=$phone?>">
					<input type="hidden" name="name" id="name" value="<?=$name?>">
					<input type="hidden" name="email" id="email" value="<?=$email?>">
					<input type="hidden" name="birth" id="birth" value="<?=$birth?>">
					<input type="hidden" name="sex" id="sex" value="<?=$sex?>">
				</form>
				<script>
					document.form1.submit();
				</script>
			<?
		}
	}


}
//DB해제
SetDisConn($dblink);
?>