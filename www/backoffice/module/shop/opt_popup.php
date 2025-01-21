<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//옵션 리스트
$arrList = getOptionList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 0, 0);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="imagetoolbar" content="no" />
<title><?=$_SITE["NAME"]?> 관리자</title>
<link href="/backoffice/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/common.js" ></script>
<script src="/common/js/shop.js" type="text/javascript"></script>
<script language="javascript">

function chkOpt(){
	//1개이상 체크했는지 검사
	var obj = document.getElementsByName('items[]'); 
	var objlength = obj.length;
	var objchecked = 0;
	var objstring = new Array();
	var arr = new Array();
	//패런트의 관련상품이 현재 몇개있나
	for(i=0; i<objlength; i++){
		if(obj[i].checked==true){
			objstring[objchecked] = obj[i].value;
			objchecked++;
		}
	}

	if(objchecked < 1){
		alert("선택하신 옵션이 없습니다.");
		return;
	}

	var tbl = opener.document.getElementById("product_opt").getElementsByTagName("TBODY")[0];  
	
	var optcount = <?=$_GET['rowcount_opt']?>;
	<?
	if($arrList['list']['total'] > 0):
	for ($i=0;$i<$arrList['list']['total'];$i++) {
		$arrVal = getArticleList($GLOBALS["_conf_tbl"]["shop_opt_val"], 0, 0, "WHERE opt_code='".$arrList['list'][$i]['opt_code']."' order by idx");
	?>	
	for(i=0; i<objlength; i++){
		if(obj[i].checked==true && obj[i].value=="<?=stripslashes($arrList['list'][$i]['opt_code'])?>"){
			var html1 = "<input type='hidden' id='opt_hidden_value_"+optcount+"' name='opt_hidden_value_"+optcount+"'><input type='text' id='opt_subject_"+optcount+"' name='opt_subject_"+optcount+"' style='width:100%' maxlength='250' class='input' value='<?=stripslashes($arrList['list'][$i]['opt_name'])?>' />";  
			var html2 = "<select id='opt_contents_"+optcount+"' name='opt_contents_"+optcount+"' style='width:100%'>";
			<? for ($j=0;$j<$arrVal['list']['total'];$j++) {?>
				var html2 = html2 + "<option value='<?=$arrVal['list'][$j]['opt_value']?>|<?=$arrVal['list'][$j]['opt_price']?>'><?=$arrVal['list'][$j]['opt_value']?>|<?=$arrVal['list'][$j]['opt_price']?></option>";
			<? } ?>
			var html2 = html2 + "</select>";   
			var html3 = "<a href='javascript:void(0);' onclick='LayerShowProductOpt("+optcount+", event)'><img src='/backoffice/images/k_add.gif' alt='추가' /></a>  <a href='javascript:void(0);' onclick='getProductOpt("+optcount+", document.getElementById(\"opt_contents_"+optcount+"\").selectedIndex, document.getElementById(\"opt_contents_"+optcount+"\").value, event)'><img src='/backoffice/images/k_modify.gif' alt='수정' /></a> <a href='javascript:void(0);' onclick='delProductOpt("+optcount+", document.getElementById(\"opt_contents_"+optcount+"\").selectedIndex)'><img src='/backoffice/images/k_delete.gif' alt='삭제' /></a>";   
			var row = opener.document.createElement("tr"); 
			var col1 = opener.document.createElement("td");   
			var col2 = opener.document.createElement("td"); 
			var col3 = opener.document.createElement("td"); 
			row.appendChild(col1);  
			row.appendChild(col2);
			row.appendChild(col3);
			col1.innerHTML = html1;  
			col2.innerHTML = html2;  
			col3.innerHTML = html3;  
			tbl.appendChild(row);
			optcount++;
		}
	}
	<?} endif; ?>
	
	opener.rowcount_opt=objchecked+<?=$_GET['rowcount_opt']?>; 

	self.close();
}
</script>

<body>

<div id="admin-content">
	<h2 class="admin-title">상품옵션 관리 </h2>
	<div class="mgb5">
		<div class="fl">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
	</div>

	<form id="frmOptList" name="frmOptList" method="POST">
	<table class="admin-table-type1">
	  <thead>
		<tr>
		  <th width="30"><input type="checkbox" onclick="checkboxCheckAll(checked);"></th>
		  <th width="70">옵션코드</th>
		  <th width="120">옵션명</th>
		  <th width="*">옵션값</th>
		</tr>
	  </thead>
	  <tbody>
	<?

	if($arrList['list']['total'] > 0):?>
	<?for ($i=0;$i<$arrList['list']['total'];$i++) {
		$arrVal = getArticleList($GLOBALS["_conf_tbl"]["shop_opt_val"], 0, 0, "WHERE opt_code='".$arrList['list'][$i]['opt_code']."' order by idx");
	?>
	<tr>
		<td><input type="checkbox" id="items[]" name="items[]" value="<?=$arrList["list"][$i]['opt_code']?>"></td>
		<td><?=$arrList['list'][$i]['opt_code']?></td>
		<td class="space-left"><?=stripslashes($arrList['list'][$i]['opt_name'])?></td>
		<td class="space-left">
		<? for ($j=0;$j<$arrVal['list']['total'];$j++) {
			echo $arrVal['list'][$j]['opt_value'];
			if($arrVal['list'][$j]['opt_price']>0) {
				echo " (+".number_format($arrVal['list'][$j]['opt_price']).")";
			}
			if($j!=$arrVal['list']['total']-1) {
				echo ", ";
			}
		} ?>
		</td>
	</tr>
	<?}?>

	<?else:?>
	<tr height="100">
	  <td colspan="12" >등록된 옵션이 없습니다.</td>
	</tr>

	<?endif;
	//DB해제
	SetDisConn($dblink);
	?>
	  </tbody>
	</table>
	<br>
	<table class="admin-table-type1">
	  <thead>
		<!-- <tr>
		  <th class="space-left">- 동일한 옵션은 한개만 추가됩니다.
		  <br>- 동일한 옵션명의 옵션을 1개 이상 선택한 경우 모두 추가되지 않으니, 옵션명을 변경해 주세요.
		  <br>- 이미 추가된 옵션명과 신규로 추가하려는 옵션의 옵션명이 동일할 때도 신규로 추가할 옵션이 추가되지 않습니다.</th>
		</tr> -->
	  </thead>
  	  <tbody>
		<tr>
			<td>
			<div class="admin-buttons">
				<div class="cen">
					<span class="btn_pack xlarge icon"><span class="check"></span><input type="button" value="선택등록" style="font-weight:bold" onclick="chkOpt()" /></span>
					<span class="btn_pack xlarge icon"><span class="delete"></span><input type="button" value="닫 기" style="font-weight:bold" onclick="javascript:self.close();" /></span>
				</div>
			</div>	
			</td>
		</tr>
	  </tbody>
	</table>
</div>

</body>
</html>