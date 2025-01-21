<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/fckeditor/fckeditor.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["board_info"], $_REQUEST['idx']);
$arrLevelInfo = getArticleList($GLOBALS["_conf_tbl"]["member_level"], 0, 0, " order by level_no desc ");
//_DEBUG($arrInfo);

//DB해제
SetDisConn($dblink);
?>
<?
$dirhandle = opendir($_SITE["BOARD_PATH"]."/skin");
$arrSkinData = array();
while($filename = readdir($dirhandle)){
	if($filename == '.' || $filename == '..' || $filename == 'def' || $filename == 'ex'){
	}else{
		$arrSkinData[] = $filename;
	}
}
sort($arrSkinData);

?>

<script>
function CheckForm(f) {
	try{ f_header.outputBodyHTML(); } catch(e){ }
	try{ f_footer.outputBodyHTML(); } catch(e){ }
}
</script>

<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">게시판 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 게시판 관리 &nbsp;&gt;&nbsp; 게시판 수정</div>
	</div>

<script language="javascript" src="/common/util.js"></script>

<form name="frmBBS" method="post" action="board_evn.php" onSubmit="return CheckForm(this)">
<input type="hidden" name="evnMode" value="editBBS">
<input type="hidden" name="idx" value="<?=$_REQUEST['idx']?>">

<div class="clfix mgb5">
  <div class="fl">&nbsp;<strong><font color="red"><?=$arrInfo["list"][0]['boardid']?> 수정</font></strong></div>
  <div class="fr"><a href="board.php"><img src="/backoffice/images/k_list.gif" alt="목록" /></a></div>
</div>
<table class="admin-table-type1">
  <colgroup>
  <col width="15%" />
  <col width="35%" />
  <col width="15%" />
  <col width="35%" />
  </colgroup>
  <tbody>
	<tr>
		<th>게시판ID</td>
		<td class="space-left"><?=$arrInfo["list"][0]['boardid']?></td>
		<th>게시판스킨</td>
		<td class="space-left">
			<select name="f_skin">
			<?php for($i=0;$i<count($arrSkinData);$i++){?>
			<option value="<?=$arrSkinData[$i]?>" <?=$arrSkinData[$i] == $arrInfo["list"][0]['skin']?"selected":""?>><?=$arrSkinData[$i]?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
	  <th>게시판명</td>
	  <td class="space-left"><input type="text" name="f_boardname" value="<?=$arrInfo["list"][0]['boardname']?>" style="width:200px;" class="input" /></td>
	  <th>제목 글자수</td>
	  <td class="space-left"><input type="text" name="f_subjectcut" value="<?=$arrInfo["list"][0]['subjectcut']?>" style="width:30px" class="input" /> (목록에서 글자수 많을때 컷팅)</td>
	</tr>
	<tr>
	  <th>페이지 표시갯수</td>
	  <td class="space-left"><input type="text" name="f_scale" value="<?=$arrInfo["list"][0]['scale']?>" style="width:30px" class="input" /></td>
	  <th>페이지 나눔갯수</td>
	  <td class="space-left"><input type="text" name="f_pagescale" value="<?=$arrInfo["list"][0]['pagescale']?>" style="width:30px" class="input" /></td>
	</tr>
	<tr>
	  <th>가로 나눔갯수</td>
	  <td class="space-left"><input type="text" name="f_widthscale" value="<?=$arrInfo["list"][0]['widthscale']?>" style="width:30px" class="input" /> (사진 갤러리형 게시판에 사용)</td>
	  <th>썸네일 가로크기</td>
	  <td class="space-left"><input type="text" name="f_thumwidth" value="<?=$arrInfo["list"][0]['thumwidth']?>" style="width:30px" class="input" /> (사진 갤러리형 썸네일 크기)</td>
	</tr>
	<tr>
	  <th>새글 표시일수</td>
	  <td class="space-left"><input type="text" name="f_newmark" value="<?=$arrInfo["list"][0]['newmark']?>" style="width:30px" class="input" /></td>
	  <th>베스트 히트 횟수</td>
	  <td class="space-left"><input type="text" name="f_besthit" value="<?=$arrInfo["list"][0]['besthit']?>" style="width:30px" class="input" /></td>
	</tr>
	<tr>
	  <th>관리자만글쓰기</td>
	  <td class="space-left"><select name="f_useadminonly" style="width:160px;">
	  <option value="N"<?=$arrInfo["list"][0]['useadminonly']=="N"?" selected":""?> style="color:blue">아무나 쓸 수 있음</option>
		<option value="Y"<?=$arrInfo["list"][0]['useadminonly']=="Y"?" selected":""?> style="color:red">관리자만 쓸 수 있음</option>
	  </select>(쓰기등급보다 우선)</td>
	  <th>자료실 사용</td>
	  <td class="space-left"><select name="f_usepds" style="width:160px;">
	  <option value="N"<?=$arrInfo["list"][0]['usepds']=="N"?" selected":""?> style="color:red">자료실 사용안함</option>
		<option value="Y"<?=$arrInfo["list"][0]['usepds']=="Y"?" selected":""?> style="color:blue">자료실 사용</option>
	  </select></td>
	</tr>
	<tr>
	  <th>답글쓰기 사용</td>
	  <td class="space-left"><select name="f_usereply" style="width:160px;">
	  <option value="N"<?=$arrInfo["list"][0]['usereply']=="N"?" selected":""?> style="color:red">답글쓰기 불가</option>
		<option value="Y"<?=$arrInfo["list"][0]['usereply']=="Y"?" selected":""?> style="color:blue">답글쓰기 가능</option>
	  </select></td>
	  <th>게시물 댓글 사용</td>
	  <td class="space-left"><select name="f_usememo" style="width:160px;">
	  <option value="N"<?=$arrInfo["list"][0]['usememo']=="N"?" selected":""?> style="color:red">댓글(메모) 사용안함</option>
		<option value="Y"<?=$arrInfo["list"][0]['usememo']=="Y"?" selected":""?> style="color:blue">댓글(메모) 기능사용</option>
	  </select></td>
	</tr>
	<tr>
	  <th>글 읽기 등급</td>
	  <td class="space-left"><select name="f_readlevel" style="width:160px;">
		<option value="0">글 읽기 등급</option>
		<?for($i=0;$i<$arrLevelInfo["total"];$i++){?>
	  <option value="<?=$arrLevelInfo["list"][$i]["level_no"]?>"<?=$arrLevelInfo["list"][$i]["level_no"]==$arrInfo["list"][0]['readlevel']?" selected":""?>><?=$arrLevelInfo["list"][$i]["level_name"]?> 이상</option>
		<?}?>
	  </select></td>
	  <th>글 쓰기(수정,삭제) 등급</td>
	  <td class="space-left"><select name="f_writelevel" style="width:160px;">
		<option value="0">글 쓰기 등급</option>
		<?for($i=0;$i<$arrLevelInfo["total"];$i++){?>
	  <option value="<?=$arrLevelInfo["list"][$i]["level_no"]?>"<?=$arrLevelInfo["list"][$i]["level_no"]==$arrInfo["list"][0]['writelevel']?" selected":""?>><?=$arrLevelInfo["list"][$i]["level_name"]?> 이상</option>
		<?}?>
	  </select></td>
	</tr>
	<tr>
	  <th>답글쓰기 등급</td>
	  <td class="space-left"><select name="f_replylevel" style="width:160px;">
		<option value="0">답글쓰기 등급</option>
		<?for($i=0;$i<$arrLevelInfo["total"];$i++){?>
	  <option value="<?=$arrLevelInfo["list"][$i]["level_no"]?>"<?=$arrLevelInfo["list"][$i]["level_no"]==$arrInfo["list"][0]['replylevel']?" selected":""?>><?=$arrLevelInfo["list"][$i]["level_name"]?> 이상</option>
		<?}?>
	  </select></td>
	  <th>목록보기 등급</td>
	  <td class="space-left"><select name="f_listlevel" style="width:160px;">
		<option value="0">목록보기 등급</option>
		<?for($i=0;$i<$arrLevelInfo["total"];$i++){?>
	  <option value="<?=$arrLevelInfo["list"][$i]["level_no"]?>"<?=$arrLevelInfo["list"][$i]["level_no"]==$arrInfo["list"][0]['listlevel']?" selected":""?>><?=$arrLevelInfo["list"][$i]["level_name"]?> 이상</option>
		<?}?>
	  </select></td>
	</tr>
	<tr>
	  <th>글잠금 기능 사용</td>
	  <td class="space-left"><select name="f_uselock" style="width:160px;">
	  <option value="N"<?=$arrInfo["list"][0]['uselock']=="N"?" selected":""?> style="color:red">글잠금 불가</option>
		<option value="Y"<?=$arrInfo["list"][0]['uselock']=="Y"?" selected":""?> style="color:blue">글잠금 가능</option>
	  </select></td>
	  <th>인트라넷으로 사용</td>
	  <td class="space-left"><select name="f_intranet" style="width:160px;">
	  <option value="N"<?=$arrInfo["list"][0]['useintranet']=="N"?" selected":""?> style="color:red">일반게시판</option>
		<option value="Y"<?=$arrInfo["list"][0]['useintranet']=="Y"?" selected":""?> style="color:blue">인트라넷 게시판</option>
	  </select></td>
	</tr>
	<tr>
	  <th>카테고리 사용</td>
	  <td colspan="3" class="space-left"><input name="f_category" style="width:400px;" value="<?=$arrInfo["list"][0]['category']?>" class="input" /> 콤마 , 로 구분 (예: 공지,답글,국문,영문)</td>
	</tr>
	<tr>
	  <th>게시판 헤더</td>
	  <td colspan="3" class="space-left">
	  <input name="f_header" style="width:100%;height:40px;" value=" <?=stripslashes($arrInfo["list"][0]["header"])?>" class="input" />		
	  </td>
	</tr>
	<tr>
	  <th>게시판 푸터</td>
	  <td colspan="3" class="space-left">
	  <input name="f_footer" style="width:100%;height:40px;" value=" <?=stripslashes($arrInfo["list"][0]["footer"])?>" class="input" />	
	  </td>
	</tr>
  </tbody>
</table>

<div class="admin-buttons">
	<div class="cen">
		<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="정보수정" style="font-weight:bold" /></span>
		<span class="btn_pack xlarge"><input type="reset" value="수정취소" style="font-weight:bold;color:#888" /></span>
	</div>
</div>	
</form>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>