<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$idx = $_GET["idx"];
$type = $_GET["type"];


//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo("tbl_member_cs", $idx);


//DB해제
SetDisConn($dblink);

$arrCsCategory = array(
	"1" => "새 문의", 
	"2" => "확인 중", 
	"3" => "추가 논의 필요", 
	"4" => "재연락 필요", 
	"5" => "처리 완료"
);

if($type == "modify"){
?>
<td>
	<select name="category" id="input_cs_category">
		<?php foreach($arrCsCategory as $key => $val){?>
		<option value="<?=$key?>" <?=$key == $arrInfo["list"][0]["category"]?>><?=$val?></option>
		<?php } ?>
	</select>
</td>
<td><textarea name="contents" id="input_cs_contents" cols="80" rows="10"><?=$arrInfo["list"][0]["contents"]?></textarea></td>
<td><div class="inputs"><input type="date" class="w2" name="schdule_date" id="input_cs_schdule_date" min="1000-01-01" max="9999-12-31" value="<?=date("Y-m-d",strtotime($arrInfo["list"][0]["schdule_date"]))?>"></div></td>
<td><div class="inputs"><input type="text" class="w2" name="name" id="input_cs_name" value="<?=$arrInfo["list"][0]["name"]?>"></div></td>
<td>
	<div class="btns">
		<a href="javascript:frmCsSubmit('<?=$arrInfo["list"][0]["idx"]?>')" class="btn">수정</a>
	</div>
</td>
<td>
	<div class="btns">
		<a href="javascript:changeCsModifyForm('<?=$arrInfo["list"][0]["idx"]?>')" class="btn">취소</a>
	</div>
</td>
<?php
}else{
?>
<td><?=$arrCsCategory[$arrInfo["list"][0]["category"]]?></td>
<td><?=nl2br($arrInfo["list"][0]["contents"])?></td>
<td><?=date("Y-m-d",strtotime($arrInfo["list"][0]["schdule_date"]))?></td>
<td><?=$arrInfo["list"][0]["name"]?></td>
<td>
	<div class="btns">
		<a href="javascript:changeCsModifyForm('<?=$arrInfo["list"][0]["idx"]?>','modify')" class="btn modi">수정</a>
	</div>
</td>
<td>
	<div class="btns">
		<a href="javascript:delCs('<?=$arrInfo["list"][0]["idx"]?>')" class="btn del">삭제</a>
	</div>
</td>
<?php
}


?>