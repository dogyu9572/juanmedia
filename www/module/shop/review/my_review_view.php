<?
//�α���Ȯ��
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";

//DB����
$dblink = SetConn($_conf_db["main_db"]);

//�� �Խù� ���� Ȯ��
$arrList = getReviewInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));

if($arrList["total"] < 1){
		jsMsg("�������� �ʴ� �� �Դϴ�.");
		jsHistory("-1") ;
}

if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] != $arrList["list"][0]["user_id"]){
		jsMsg("������ ���� ���� �ƴմϴ�.");
		jsHistory("-1") ;
}

//��ǰ ����
$arrInfo = getGoodInfo($arrList["list"][0]['g_idx']);

//DB����
SetDisConn($dblink);
?>

<script language="javascript">
	function deleteReview(idx){
		var cfm = false;
		cfm = confirm("�� ���䳻���� ���� �Ͻðڽ��ϱ�?");
		if(cfm==true){
			new Ajax.Request('/module/shop/review/review_evn.php',
			{
				method:'post',
				parameters: {idx: idx, evnMode: 'deleteAjax'},
				asynchronous: this.asynchronous,
				encoding: 'utf-8',
				contentType: 'application/x-www-form-urlencoded',

				onSuccess: function(transport){
					var response = transport.responseText || "����� ������ �����ϴ�."; 
					if(response=="true"){
						alert("���� �Ǿ����ϴ�.");
						document.location.href="/shop.php?goPage=MyReview";
					}else{
						alert("������ ���� �Ͽ����ϴ�.");
					}
				},
				
				onFailure: function(){ 
					alert('AJAX ������ ������ ������ �߻��Ͽ����ϴ�.') 
				}   
			});
		}
	}
</script>
�̿��ı� ����
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
  <td>
  
	<div id="contents_tab">

	  <table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
		<tr>
		  <th><span class="spanbb"><?=stripslashes($arrList["list"][0]["subject"])?></span></th>
		</tr>
		<tr>
		  <td><p>
		  - ��ǰ�� : <a href="/shop.php?goPage=GoodDetail&g_code=<?=$arrInfo["list"][0]['g_code']?>"><?=stripslashes($arrInfo["list"][0]['g_name'])?></a><br />
		  - �ۼ��� : <?=$arrList["list"][0]['user_name']?><br />
		  - �ۼ��� : <?=$arrList["list"][0]['wdate']?></p></td>
		</tr>
		<tr>
		  <td style="padding:30px 0px 30px 0px"><?=stripslashes(nl2br($arrList["list"][0]['contents']))?></td>
		</tr>
	  </table>
	</div>
	
	
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	  <tr>
		<td><a href="/shop.php?goPage=MyReview"><img src="/common/images/btn_list.gif" alt="��Ϻ���" width="66" height="24" border="0" /></a></td>
		<td><div align="right"><a href="javascript:;" onclick="deleteReview('<?=$arrList["list"][0]['idx']?>');"><img src="/common/images/btn_delete.gif" border="0" align="absmiddle"></a></div></td>
	  </tr>	

	</table>
  
  </td>
</tr>
</table>