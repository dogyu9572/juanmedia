<?php
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");

$mode = $_POST["mode"];
$idx = $_POST["idx"];

try{
	$arrReturnData = array();

	if($mode == "add"){
		if($idx != ""){
			if(!in_array($idx,$_SESSION[$_SITE["DOMAIN"]]["CART"])){
				$_SESSION[$_SITE["DOMAIN"]]["CART"][] = $idx;

				$arrReturnData["success"] = true;
				$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["CART"]);
				$arrReturnData["msg"] = "등록되었습니다.";
			}else{
				$arrReturnData["success"] = false;
				$arrReturnData["msg"] = "이미 등록되어있습니다.";
			}
		}else{
			$arrReturnData["success"] = false;
			$arrReturnData["msg"] = "값이 없습니다.";
		}
	}else if($mode == "remove"){
		if(in_array($idx,$_SESSION[$_SITE["DOMAIN"]]["CART"])){
			$index = array_search($idx, $_SESSION[$_SITE["DOMAIN"]]["CART"]);
			if($index !== false){
				unset($_SESSION[$_SITE["DOMAIN"]]["CART"][$index]);
				$arrReturnData["success"] = true;
				$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["CART"]);
				$arrReturnData["msg"] = "삭제되었습니다.";
			}else{
				$arrReturnData["success"] = false;
				$arrReturnData["msg"] = "등록되어있지만 삭제에 실패했습니다.";
			}
		}else{
			$arrReturnData["success"] = false;
			$arrReturnData["msg"] = "등록되어있지않습니다.";
		}
	}else if($mode == "hw_add"){
		if($idx != ""){
			if(!in_array($idx,$_SESSION[$_SITE["DOMAIN"]]["HW_CART"])){
				$_SESSION[$_SITE["DOMAIN"]]["HW_CART"][] = $idx;

				$arrReturnData["success"] = true;
				$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["HW_CART"]);
				$arrReturnData["msg"] = "등록되었습니다.";
			}else{
				$arrReturnData["success"] = false;
				$arrReturnData["msg"] = "이미 등록되어있습니다.";
			}
		}else{
			$arrReturnData["success"] = false;
			$arrReturnData["msg"] = "값이 없습니다.";
		}
	}else if($mode == "hw_remove"){
		if(in_array($idx,$_SESSION[$_SITE["DOMAIN"]]["HW_CART"])){
			$index = array_search($idx, $_SESSION[$_SITE["DOMAIN"]]["HW_CART"]);
			if($index !== false){
				unset($_SESSION[$_SITE["DOMAIN"]]["HW_CART"][$index]);
				$arrReturnData["success"] = true;
				$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["HW_CART"]);
				$arrReturnData["msg"] = "삭제되었습니다.";
			}else{
				$arrReturnData["success"] = false;
				$arrReturnData["msg"] = "등록되어있지만 삭제에 실패했습니다.";
			}
		}else{
			$arrReturnData["success"] = false;
			$arrReturnData["msg"] = "등록되어있지않습니다.";
		}
	}else if($mode == "add_mul"){
		if($idx != ""){
			$arrIdx = explode(",",$idx);

			$count = 0;

			for($i=0;$i<count($arrIdx);$i++){
				if(!in_array($arrIdx[$i],$_SESSION[$_SITE["DOMAIN"]]["CART"])){
					$_SESSION[$_SITE["DOMAIN"]]["CART"][] = $arrIdx[$i];
					$count++;
				}
			}

			if($count == count($arrIdx)){
				$arrReturnData["success"] = true;
				$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["CART"]);
				$arrReturnData["msg"] = "선택한 제품이 모두 등록되었습니다.";
			}else{
				$arrReturnData["success"] = false;
				$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["CART"]);
				$arrReturnData["msg"] = "선택한 제품이 일부 등록되었습니다. (".$count."/".(count($arrIdx)).")";
			}
		}else{
			$arrReturnData["success"] = false;
			$arrReturnData["msg"] = "값이 없습니다.";
		}
	}else if($mode == "remove_mul"){

		$arrIdx = explode(",",$idx);

		$count = 0;

		for($i=0;$i<count($arrIdx);$i++){
			if(in_array($arrIdx[$i],$_SESSION[$_SITE["DOMAIN"]]["CART"])){
				$index = array_search($arrIdx[$i], $_SESSION[$_SITE["DOMAIN"]]["CART"]);
				if($index !== false){
					unset($_SESSION[$_SITE["DOMAIN"]]["CART"][$index]);
					$count++;
				}
			}
		}
		if($count == count($arrIdx)){
			$arrReturnData["success"] = true;
			$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["CART"]);
			$arrReturnData["msg"] = "선택한 제품이 모두 삭제되었습니다.";
		}else{
			$arrReturnData["success"] = false;
			$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["CART"]);
			$arrReturnData["msg"] = "선택한 제품이 일부 삭제되었습니다. (".$count."/".(count($arrIdx)).")";
		}
	}else if($mode == "remove_all_product"){
		$_SESSION[$_SITE["DOMAIN"]]["CART"] = array();
		$arrReturnData["success"] = true;
		$arrReturnData["count"] = count($_SESSION[$_SITE["DOMAIN"]]["CART"]);
		$arrReturnData["msg"] = "선택한 제품이 모두 삭제되었습니다.";
	}else{
		$arrReturnData["success"] = false;
		$arrReturnData["msg"] = "잘못된 요청입니다.";
	}
}catch(Exception $e){
	$arrReturnData["success"] = false;
	$arrReturnData["msg"] = "잘못된 요청입니다.";
}finally {
	$void_index = array_search("", $_SESSION[$_SITE["DOMAIN"]]["CART"]);
	if($void_index !== false){
		unset($_SESSION[$_SITE["DOMAIN"]]["CART"][$void_index]);
	}

	echo json_encode($arrReturnData);
}


?>