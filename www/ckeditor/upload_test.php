
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="imagetoolbar" content="no" />
<title>밥스누 관리자</title>
<link href="/backoffice/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>

	
    
<form name="frmInfo" method="post" action="upload.php" ENCTYPE="multipart/form-data">


<!-- 기본정보 -->
<table class="admin-table-type1">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
	<tr>
		<th>제목</th>
		<td class="space-left"><input type="text" name="CKEditorFuncNum" style="width:50%" maxlength="200" value="11" class="input" /></td>
	</tr>
	<tr>
		<th>배너</th>
		<td class="space-left"><input type="file" name="upload" style="width:50%" /> </td>
	</tr>
  </tbody>
</table>

<div class="admin-buttons">
	<div class="cen">
		<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="배너수정" style="font-weight:bold" /></span>
	</div>
</div>

</form>
</body>
</html>
