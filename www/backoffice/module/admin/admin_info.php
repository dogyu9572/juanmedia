<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

if(!in_array("admin_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["admin"], $_GET['idx']);

$arrListLevel = getArticleList($_conf_tbl["member_level"], $scale, postNullCheck('offset'), "order by level_no desc ");

$arrAuth = explode(",",str_replace(" ","",$arrInfo["list"][0]['a_auth']));
$arrBAuth = explode("|",str_replace(" ","",$arrInfo["list"][0]['b_auth']));

//DB해제
//SetDisConn($dblink);
?>
<div class="container">

	<div class="title">관리자 수정</div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmInfo" method="post" action="admin_evn.php">
		<input type="hidden" name="evnMode" value="updateAdmin">
		<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]['idx']?>">

		<div class="tit">관리자정보 <i>*</i></div>
		<table>
			<tr>
				<th>아이디</th>
				<td><em><?=$arrInfo["list"][0]['a_id']?></em></td>
			</tr>
			<tr>
				<th>비밀번호</th>
				<td><div class="inputs"><input type="password" class="w3" name="a_pw" maxlength="20"> <em>&nbsp;※ 비밀번호 변경시에만 입력하세요. (20자 이내)</em></div></td>
			</tr>
			<tr>
				<th>이름</th>
				<td><div class="inputs"><input type="text" class="w4" name="a_name" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['a_name'])?>"></div></td>
			</tr>
			<tr>
				<th>설명</th>
				<td><div class="inputs"><input type="text" class="w4" name="a_note" maxlength="200" value="<?=stripslashes($arrInfo["list"][0]['a_note'])?>"></div></td>
			</tr>
			<tr>
				<th>연락처</th>
				<td><div class="inputs"><input type="text" class="w4" name="a_phone" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['a_phone'])?>"></div></td>
			</tr>
			<tr>
				<th>이메일</th>
				<td><div class="inputs"><input type="text" class="w4" name="a_email" maxlength="50" value="<?=stripslashes($arrInfo["list"][0]['a_email'])?>"></div></td>
			</tr>
			<tr>
				<th>등급</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio"  id="radio1" name="a_grade" value="ROOT"<?=$arrInfo["list"][0]['a_grade']=="ROOT"?" checked":""?>><i></i>ROOT</label>
					<label class="radio"><input type="radio"  id="radio2" name="a_grade" value="ADMIN"<?=$arrInfo["list"][0]['a_grade']=="ADMIN"?" checked":""?>><i></i>ADMIN</label> 
					<em>(ROOT 일경우 아래권한에 관계없이 모든 메뉴 접근 가능)</em>
				</div></td>
			</tr>
			<tr>
				<th>관리권한</th>
				<td>
					<?					
					//var_dump($arrayMyMenuSub);					
					?>
					<?
					for($i=0;$i<$arrMenuList["total"];$i++){
						$arrMenuSubList = getAdminMenuSub($arrMenuList["list"][$i]['m_code']);
					?>
					<div style="margin-top:20px;<?=$arrMenuList["list"][$i]['m_code']=="board_manage"?"display:none;":""?>">
					
					<label class="check" style="font-weight: bold;color:#0075df;"><input type="checkbox" name="a_auth[]" class="auth_box" id="a_auth_<?=$i?>" <?=$arrMenuList["list"][$i]['m_code']=="board_manage"?" checked onclick='return false;'":""?> value="<?=$arrMenuList["list"][$i]['m_code']?>"<?=in_array($arrMenuList["list"][$i]['m_code'],$arrAuth)==true?" checked":""?> <?=$arrInfo["list"][0]['a_grade']=="ROOT"?" checked":""?>><i></i><?=$arrMenuList["list"][$i]['m_name']?><?=$arrMenuList["list"][$i]['m_code']=="board_manage"?"(Default)":""?></label>
					
					<?
					if($arrMenuSubList["total"]>0){
						echo "<div style='margin-left:60px;'>";
					}
						for($j=0;$j<$arrMenuSubList["total"];$j++){
							if($j==8){
								echo "<br/>";
							}
					?>
						<label class="check"><input type="checkbox" name="b_auth[]" class="auth_box" value="<?=$arrMenuSubList["list"][$j]['m_code']?>"<?=in_array($arrMenuSubList["list"][$j]['m_code'],$arrBAuth)==true?" checked":""?> ><i></i><?=$arrMenuSubList["list"][$j]['m_name']?></label>
					<?
						}
					if($arrMenuSubList["total"]>0){
						echo "</div>";
					}
					?>
					
					</div>
					<?}?>
				</td>
			</tr>

			<tr>
				<th>등록일</th>
				<td><em><?=$arrInfo["list"][0]['a_date']?></em></td>
			</tr>
		</table>		

		<div class="btns">
			<a href="javascript:void(0);" onclick="history.back()" class="btn btn_list">목록보기</a>
			<button class="btn btn_save" type="submit">저장</button>
		</div>
		</form>
	</div> <!-- //inbox -->

</div>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>