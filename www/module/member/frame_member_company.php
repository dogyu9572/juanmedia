<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php");

$scale = 8;
$dblink = SetConn($_conf_db["main_db"]);
$arrBoardList = getBoardListBase("company", "", $_GET[sw], $_GET[sk], $scale, $_GET[offset]);
SetDisConn($dblink);
?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link href="/css/common.css" type="text/css" rel="stylesheet">
	<title>KPU한국산업기술대학교</title>
	<script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="/js/jquery.bxslider.min.js"></script>
	<script type="text/javascript" src="/js/common.js"></script>
<script>
function closeLayer(str, addr1, addr2, addr3, ceo, num) {
	<? if($_GET[tp]=="1") {?>
	opener.document.getElementById('company').value = str;
	opener.document.getElementById('postcode').value = addr1;
	opener.document.getElementById('address').value = addr2;
	opener.document.getElementById('address2').value = addr3;
	self.close();
	<? } else {?>
	parent.document.getElementById('company').value = str;
	parent.document.getElementById('postcode').value = addr1;
	parent.document.getElementById('address').value = addr2;
	parent.document.getElementById('address2').value = addr3;
	try{
		parent.document.getElementById('ceo').value = ceo;
	} catch(e){ }
	try{
		parent.document.getElementById('cmp').value = num;
	} catch(e){ }
	try{
		parent.$('.layer').fadeOut();
	} catch(e){ }
	<?} ?>
}
</script>
</head>
<body>

					<div class="top">
			
						<div class="fix_cont">
							<div class="list_table02 cssC">
								<colgroup>
									<col width="47px">
									<col width="auto">
								</colgroup>
								<table>
									<tbody>
										<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>"> 
										<input type="hidden" name="boardid" value="<?=$arrBoardInfo["list"][0]["boardid"]?>">
										<tr>
											<th>
												검색
											</th>
											<td>
												<select name="sw" id="sw" class="selType01">
													<option value="s"<?=$_GET["sw"]=="s"?" selected":""?>>협약기업명</option>
													<option value="e3"<?=$_GET["sw"]=="e3"?" selected":""?>>사업자등록번호</option>
												</select>
												<input type="text" name="sk" id="sk" class="type01" value="<?=$_GET[sk]?>">
												<button type="submit" class="blueType01">
													검색
												</button>
											</td>
										</tr>
										</form>
									</tbody>
								</table>
							</div>
						</div>
			
					</div>
			
					<div class="cont">
			
						<div class="list_table01 mt40">
							<table>
								<colgroup>
									<col width="47px">
									<col width="200px">
									<col width="120px">
									<col width="420px">
								</colgroup>
								<tbody>
									<tr>
										<th>No</th>
										<th>협약기업명</th>
										<th>사업자등록번호</th>
										<th>주소</th>
									</tr>
									<?
									if($arrBoardList["list"]["total"] > 0){
									for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
										$arrAddr = explode("|", stripslashes($arrBoardList["list"][$i][etc_5]));
									?>
									<tr>
										<td>
											<?=$arrBoardList["list"][$i][no]=="0"?$noticeImage:$arrBoardList["total"]-$i-$_GET[offset]?>
										</td>
										<td><a href="javascript:closeLayer('<?=stripslashes($arrBoardList["list"][$i][subject])?>','<?=$arrAddr[0]?>','<?=$arrAddr[1]?>','<?=$arrAddr[2]?>','<?=stripslashes($arrBoardList["list"][$i][name])?>','<?=stripslashes($arrBoardList["list"][$i][etc_3])?>');"><?=stripslashes($arrBoardList["list"][$i][subject])?></a></td>
										<td><?=stripslashes($arrBoardList["list"][$i][etc_3])?></td>
										<td><?=$arrAddr[1]?> <?=$arrAddr[2]?></td>
									</tr>
									<?
										}
									}else{
									?>
									<tr>
										<td colspan="4" align="center">등록된 글이 없습니다</td>
									</tr>
									<?
										}
									?>
								</tbody>
							</table>
						</div>
			
						<div class="basePaging pagingType02">
							<?=pageNavigation($arrBoardList["total"],$scale,$pagescale,$_GET[offset],"boardid=company&sk=".$_GET[sk]."&sw=".$_GET[sw]."&category=".$_GET[category])?>
						</div>
			
					</div>

</body>
</html>