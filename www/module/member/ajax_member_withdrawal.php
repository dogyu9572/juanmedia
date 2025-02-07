<?php
@session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php");

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = loginMember($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $_GET["pw"]);

if ($arrList["total"] == 0) {
    $arrList = loginMemberBefore($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $_GET["pw"]);
}


if($arrList["total"] > 0){
    $rs = deleteMember($arrList["list"][0]["user_id"]);
    if($rs){
        unset($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]);
        echo "1";
    }else{
        echo "0";
    }
}else{
    echo "0";
}


//DB해제
SetDisConn($dblink);
?>