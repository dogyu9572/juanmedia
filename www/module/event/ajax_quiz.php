<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_REQUEST['num']=="1"){
	if($_REQUEST['answer']=="변재락"){
		echo "OK";
	}else{
		echo "NO";
	}
}

if($_REQUEST['num']=="2"){
	if($_REQUEST['answer']=="기분 좋은" || $_REQUEST['answer']=="기분좋은"){
		echo "OK";
	}else{
		echo "NO";
	}
}

if($_REQUEST['num']=="3"){
	if($_REQUEST['answer']=="애착"){
		echo "OK";
	}else{
		echo "NO";
	}
}

if($_REQUEST['num']=="4"){
	if($_REQUEST['qfg01']=="1" && $_REQUEST['qfg02']=="1" && $_REQUEST['qfg03']=="1"){
		$eventID = "54";
		$eUserID = $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"];
		$arrUserCnt = getCouponUserEvent($eventID, $eUserID);
		if($arrUserCnt['total'] > 279){	## 오늘 등록된 쿠폰이 100 이상이면 - 280건으로 변경함 Jeejin 0806
			echo "totalover";
			exit();
		}else{
			if($arrUserCnt['userCnt'] > 0){	## 발행한 쿠폰이 있다면
				echo "userover";
				exit();
			}else{	## 쿠폰 발행
				if($eUserID){	## 로그인상태이면 즉시발행
					getUserCoupon($eventID, $eUserID);
					echo "coupon";
				}else{			## 비로그인상태이면 세션값 저장
					$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["COUPONID"]= $eventID;
					echo "OK";
				}
			}
		}
	}else{
		echo "NO";
	}
}

//DB해제
SetDisConn($dblink);
?>