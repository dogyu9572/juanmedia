<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/zipcode/zipcode.lib.php";

if($_REQUEST[dong]!=""){
	//DB연결
	$dblink = SetConn($_conf_db["zipcode"]);

	$arrList = getZipCode(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[dong]));

	//DB해제
	SetDisConn($dblink);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />
<title>주소찾기</title>
<link rel="stylesheet" type="text/css" href="zipcode.css" />
<script type="text/javascript">
var previousString = "";
  function clearForm(obj, defaultvalue) {
	if (obj.value == defaultvalue) {
	  previousString = obj.value;
	  obj.value = "";
	}
  }

  function escapeForm(obj, defaultValue) {
	if (obj.value == "")
	  obj.value = defaultValue;
  }
</script>
<script language="javascript">
function setCode(tp, zip1, zip2, addr){
	if(tp==1){
		//회원가입
		frm = opener.document.memberForm;
		frm.zip1.value = zip1;
		frm.zip2.value = zip2;
		frm.address.value = addr;
		frm.address_ext.focus();
	}else if(tp==2){
		//쇼핑몰 주문서
		frm = opener.document.frmOrderForm;
		frm.order_zip1.value = zip1;
		frm.order_zip2.value = zip2;
		frm.order_address.value = addr;
		frm.order_address_ext.focus();
	}else if(tp==3){
		//쇼핑몰 주문서
		frm = opener.document.frmOrderForm;
		frm.ship_zip1.value = zip1;
		frm.ship_zip2.value = zip2;
		frm.ship_address.value = addr;
		frm.ship_address_ext.focus();
	}else if(tp==4){
		//온라인견적서
		frm = opener.document.frmOnlineForm;
		frm.zip1.value = zip1;
		frm.zip2.value = zip2;
		frm.address.value = addr;
		frm.address_ext.focus();
	}else if(tp==5){
		//회원가입
		frm = opener.document.memberForm;
		frm.com_zip1.value = zip1;
		frm.com_zip2.value = zip2;
		frm.com_address.value = addr;
		frm.com_address_ext.focus();
	}else if(tp==6){
		//세금계산서
		frm = opener.document.frmOrderEndForm;
		frm.zip1.value = zip1;
		frm.zip2.value = zip2;
		frm.address.value = addr;
		frm.address_ext.focus();
	}
	self.close();

}
</script>
<script language="javascript">
function checkForm(){
	var frm = document.zipFrm;
	if(frm.dong.value=="동(읍/면/리/가)"){
		alert("찾으실 '동' 을 입력하세요.");
		frm.dong.focus();
		return ;
	}
	frm.submit();
}
</script>
</head>
<body>
<div class="zipcode_wrap">
	<div class="title">
		<h1>주소찾기</h1>
	</div>
	<div class="container">
		<div class="tab">
			<ul>
				<li class="on"><a href="zipcode.php?tp=<?=$_REQUEST[tp]?>">우편번호로 찾기</a></li>
				<li><a href="zipcode2.php?tp=<?=$_REQUEST[tp]?>">도로명 새주소로 찾기</a></li>
			</ul>
		</div>
		<div class="content">
			<div class="zip-code">
				<p class="type">찾고자 하는 주소명(동/읍/면/리/가)을 입력하세요.<span>&nbsp;(예:역삼1동)</span></p>
				<div class="search-area">
					<form name="zipFrm" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
					<input type="hidden" name="tp" value="<?=$_REQUEST[tp]?>">
					<div class="word">
						<label for="htxtDongName"><span>검색어</span></label>
						<input type="text"  name="dong" id="htxtDongName" value="<?=$_REQUEST[dong]?$_REQUEST[dong]:"동(읍/면/리/가)"?>" style="width:165px;" onfocus="clearForm(this,'동(읍/면/리/가)');" onblur="escapeForm(this,'동(읍/면/리/가)');" class="input" />
						<a class="search" href="javascript:checkForm()"><img src="images/btn_search.gif" alt="검색" /></a>
					</div>
					</form>
				</div>
				<?if($_REQUEST[dong] !="" && $arrList["total"] > 0){?>
				<p><span>※ 검색 후 우편번호를 클릭해주세요.</span></p>
				
				<div id="hdivResult" class="postresult">
					<ul>
						<?for($i=0;$i<$arrList["total"];$i++){?>
						<li><a href="javascript:setCode('<?=$_REQUEST[tp]?>','<?=substr($arrList["list"][$i][zip],0,3)?>','<?=substr($arrList["list"][$i][zip],4,3)?>','<?=$arrList["list"][$i][sido]?> <?=$arrList["list"][$i][gugun]?> <?=$arrList["list"][$i][dong]?>');"> <?=$arrList["list"][$i][sido]?> <?=$arrList["list"][$i][gugun]?> <?=$arrList["list"][$i][dong]?> <?=$arrList["list"][$i][bunji]?> <span><?=$arrList["list"][$i][zip]?></span></a></li>
						<?}?>
					</ul>
				</div>
				<?}else if($_REQUEST[dong] !=""){?>
				<p style="text-align:center;height:40px"><strong>검색결과값이 없습니다.</strong></p>
				<?}?>
			</div>
		</div>
	</div>
	<div class="button-area">
		<a href="javascript:self.close();"><img src="images/btn_close.gif" alt="닫기" /></a>
	</div>
</div>
</body>
</html>