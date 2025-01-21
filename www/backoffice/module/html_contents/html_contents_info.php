<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/html_contents/html_contents.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/fckeditor/fckeditor.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["html_contents"], $_REQUEST[idx]);
//_DEBUG($arrInfo);

//DB해제
SetDisConn($dblink);
?>
<script>
function CheckForm(frm){
	try{ f_contents.outputBodyHTML(); } catch(e){ }
}
</script>
<script language="javascript" src="/common/util.js"></script>

<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<div class="admin-title-top">
			<h2 class="admin-title">컨텐츠 관리</h2>
			<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 컨텐츠 관리 &nbsp;&gt;&nbsp; 컨텐츠 수정</div>
		</div>
		
		<form name="frmBBS" method="post" action="html_contents_evn.php" onSubmit="return CheckForm(this)">
			<input type="hidden" name="evnMode" value="editContents">
			<input type="hidden" name="idx" value="<?=$_REQUEST[idx]?>">
			<div class="clfix mgb5">
				<div class="fl"><strong><font color="red"><?=$arrInfo["list"][0][code]?> 수정</font></strong></div>
				<div class="fr"><a href="html_contents.php"><img src="/backoffice/images/k_list.gif" alt="목록" /></a></div>
			</div>
			
			<table class="admin-table-type1">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				<tbody>
					<tr>
						<th>컨텐츠 Code</th>
						<td class="space-left"><?=$arrInfo["list"][0][code]?></td>
						<th>생성일</th>
						<td class="space-left"><?=$arrInfo["list"][0][wdate]?></td>
					</tr>
					<tr>
						<th>컨텐츠 제목</th>
						<td class="space-left"><input type="text" name="f_subject" value="<?=$arrInfo["list"][0][subject]?>" style="width:99%;" class="input" /></td>
						<th>HTML 사용</th>
						<td class="space-left"><select name="f_usehtml" style="width:160px;">
						<option value="Y"<?=$arrInfo["list"][0][usehtml]=="Y"?" selected":""?> style="color:blue">HTML 모드</option>
						<option value="N"<?=$arrInfo["list"][0][usehtml]=="N"?" selected":""?> style="color:red">TEXT 모드</option>
						</select></td>
					</tr>
					<tr>
						<th>컨텐츠 내용(국문)</th>
						<td class="space-left" colspan="3">	
						<textarea id="f_contents" name="f_contents" style="width:100%;height:400px;"><?=stripslashes($arrInfo["list"][0]["contents"])?></textarea>
						<?#	$CKContent = "f_contents";	include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";	?>
						</td>
					</tr>
					<tr>
						<th>컨텐츠 내용(영문)</th>
						<td class="space-left" colspan="3">	
						<textarea id="e_contents" name="e_contents" style="width:100%;height:400px;"><?=stripslashes($arrInfo["list"][0]["e_contents"])?></textarea>						
						</td>
					</tr>
					<tr>
						<th>컨텐츠 내용(중문)</th>
						<td class="space-left" colspan="3">	
						<textarea id="c_contents" name="c_contents" style="width:100%;height:400px;"><?=stripslashes($arrInfo["list"][0]["c_contents"])?></textarea>						
						</td>
					</tr>
				</tbody>
			</table>
			<div class="admin-buttons">
				<div class="cen">
					<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="정보수정" style="font-weight:bold" /></span>
					<span class="btn_pack xlarge"><input type="reset" value="수정취소" style="font-weight:bold;color:#888;" /></span>
				</div>
			</div>
		</form>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>