<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/zipcode/zipcode.lib.php";

if($_REQUEST[searchMode]!=""){
	//DB연결
	$dblink = SetConn($_conf_db["zipcode"]);

	if($_REQUEST[searchMode]=="1") {
		$arrList = getZipCodeRoad(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sido]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[gugun]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[road]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[building]));
	} else if($_REQUEST[searchMode]=="2") {
		$arrList = getZipCodeDong(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sido]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[gugun]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[dong]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[gibun]));
	} else if($_REQUEST[searchMode]=="3") {
		$arrList = getZipCodeBuild(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sido]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[gugun]), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[buildingname]));
	} 
	//DB해제
	SetDisConn($dblink);
}

if($_REQUEST[searchMode]==""){
	$_REQUEST[searchMode]="1";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="imagetoolbar" content="no" />
<title>주소찾기</title>
<link rel="stylesheet" type="text/css" href="zipcode.css" />
<script type="text/JavaScript" language="javascript" src="/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript">
//resizeTo('500','520');

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

function checkMode(no) {
	if(no == "3") {
		$(".mode01").hide();
		$(".mode02").hide();
		$(".mode03").show();
	} else if(no == "2") {
		$(".mode01").hide();
		$(".mode02").show();
		$(".mode03").hide();
	} else {
		$(".mode01").show();
		$(".mode02").hide();
		$(".mode03").hide();
	}
	$(".notice").show();
	$(".no_list").hide();
	$(".roadname_list").hide();
}

//카테고리 초기화
function initCat(){
	for(i=document.zipFrm.gugun.length; i >= 0; i--){
		document.zipFrm.gugun.options[i] = null;
	}
	document.zipFrm.gugun.options[0] = new Option("","");
}

function getCat(cat,selected_idx){
	try{ initCat(); }catch(e){}
	
	//순서대로 가져와야되기 때문에 이곳은 async = false
	$.ajax({
		type: "GET",
		url: "/module/zipcode/ajax_get_gugun.php",
		data: {sido: cat},
		cache: false,
		async: false,
		dataType: "html",
		success: function(html){
			setCat(html,selected_idx);
			//document.zipFrm.cat_no.value = cat;
		}

	});
}

function setCat(txt,selected_idx){
	if(txt !=""){
		var opt = new Array();
		var opt2 = new Array();
		opt = txt.split("||");
		for(i=0; i<opt.length; i++){
			opt2 = opt[i].split("**");
			//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
			try{
				if(selected_idx==opt2[0]){
					document.zipFrm.gugun.options[i+1] = new Option(opt2[1],opt2[0],true,true);
				}else{
					document.zipFrm.gugun.options[i+1] = new Option(opt2[1],opt2[0]);
				}
			}catch(e){}
		}
	}
}

function checkForm(){
	var frm = document.zipFrm;
	if(frm.sido.value==""){
		alert("시도를 선택하세요.");
		frm.sido.focus();
		return ;
	}
	if(frm.gugun.value=="" && frm.sido.value!="sejong"){
		alert("시군구를 선택하세요.");
		frm.gugun.focus();
		return ;
	}

	if(frm.searchMode[0].checked==true) {
		if(frm.road.value=="도로명"){
			alert("도로명을 입력하세요.");
			frm.road.focus();
			return ;
		}
		if(frm.building.value=="건물번호"){
			alert("건물번호를 입력하세요.");
			frm.building.focus();
			return ;
		}
	} else if(frm.searchMode[1].checked==true) {
		if(frm.dong.value=="동(읍/면/리)"){
			alert("동(읍/면/리)을 입력하세요.");
			frm.dong.focus();
			return ;
		}
		if(frm.gibun.value=="지번"){
			alert("지번을 입력하세요.");
			frm.gibun.focus();
			return ;
		}
	} else if(frm.searchMode[2].checked==true) {
		if(frm.buildingname.value=="건물명(아파트명 등)"){
			alert("건물명(아파트명 등)을 입력하세요.");
			frm.buildingname.focus();
			return ;
		}
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
				<li><a href="zipcode.php?tp=<?=$_REQUEST[tp]?>">우편번호로 찾기</a></li>
				<li class="on"><a href="zipcode2.php?tp=<?=$_REQUEST[tp]?>">도로명 새주소로 찾기</a></li>
			</ul>
		</div>
		<div class="content">
			<div class="street-name">
				<form name="zipFrm" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
				<input type="hidden" name="tp" value="<?=$_REQUEST[tp]?>">
				<div class="type">
					<label><input type="radio" id="cb01" value="1" name="searchMode" onclick="checkMode(1);"<?=$_REQUEST[searchMode]=="1"?" checked='checked'":""?> />도로명+건물번호</label>
					<label><input type="radio" id="cb02" value="2" name="searchMode" onclick="checkMode(2);"<?=$_REQUEST[searchMode]=="2"?" checked='checked'":""?> />동(읍/면)+지번</label>
					<label><input type="radio" id="cb03" value="3" name="searchMode" onclick="checkMode(3);"<?=$_REQUEST[searchMode]=="3"?" checked='checked'":""?> />건물명(아파트명)</label>
				</div>				
				<div class="search-area">
					<div class="selectors">
						<label for="ddlCityNState"><span>시도</span></label>
						<select name="sido" id="sido" class="styled" style="width:110px;" onchange="getCat(this.value);">
							<option selected="selected" value="">전체</option>
							<option value="seoul"<?=$_GET[sido]=="seoul"?" selected":""?>>서울특별시</option>
							<option value="busan"<?=$_GET[sido]=="busan"?" selected":""?>>부산광역시</option>
							<option value="daegu"<?=$_GET[sido]=="daegu"?" selected":""?>>대구광역시</option>
							<option value="incheon"<?=$_GET[sido]=="incheon"?" selected":""?>>인천광역시</option>
							<option value="gwangju"<?=$_GET[sido]=="gwangju"?" selected":""?>>광주광역시</option>
							<option value="daejeon"<?=$_GET[sido]=="daejeon"?" selected":""?>>대전광역시</option>
							<option value="ulsan"<?=$_GET[sido]=="ulsan"?" selected":""?>>울산광역시</option>
							<option value="sejong"<?=$_GET[sido]=="sejong"?" selected":""?>>세종특별자치시</option>
							<option value="gangwon"<?=$_GET[sido]=="gangwon"?" selected":""?>>강원도</option>
							<option value="gyeonggi"<?=$_GET[sido]=="gyeonggi"?" selected":""?>>경기도</option>
							<option value="gyeongnam"<?=$_GET[sido]=="gyeongnam"?" selected":""?>>경상남도</option>
							<option value="gyeongbuk"<?=$_GET[sido]=="gyeongbuk"?" selected":""?>>경상북도</option>
							<option value="jeonnam"<?=$_GET[sido]=="jeonnam"?" selected":""?>>전라남도</option>
							<option value="jeonbuk"<?=$_GET[sido]=="jeonbuk"?" selected":""?>>전라북도</option>
							<option value="jeju"<?=$_GET[sido]=="jeju"?" selected":""?>>제주특별자치도</option>
							<option value="chungnam"<?=$_GET[sido]=="chungnam"?" selected":""?>>충청남도</option>
							<option value="chungbuk"<?=$_GET[sido]=="chungbuk"?" selected":""?>>충청북도</option>
						</select>
						<label for="ddlCityRegions"><span>시군구</span></label>
						<select name="gugun" id="gugun" class="styled" style="width:110px;">
							<option value=""></option>
						</select>
					</div>
					<div class="word mode01">
						<label class="first"><span>검색어</span>
						<input name="road" type="text" value="<?=$_REQUEST[road]?$_REQUEST[road]:"도로명"?>" id="txtRoadName" onfocus="clearForm(this,'도로명');" onblur="escapeForm(this,'도로명');" value="도로명" onkeydown="if(event.keyCode==13){clickSearchButton(); return false;}" style="width:98px;" class="input" /></label>
						<input name="building" type="text" value="<?=$_REQUEST[building]?$_REQUEST[building]:"건물번호"?>" id="txtBuildingNo" onfocus="clearForm(this,'건물번호');" onblur="escapeForm(this,'건물번호');" value="건물번호" onkeydown="if(event.keyCode==13){clickSearchButton(); return false;}" style="width:98px;" class="input" />
						<a class="search" href="javascript:checkForm()"><img src="images/btn_search.gif" alt="검색" /></a>
					</div>
					<div class="word mode02" style="display:none">
						<label class="first"><span>검색어</span>
						<input name="dong" type="text" value="<?=$_REQUEST[dong]?$_REQUEST[dong]:"동(읍/면/리)"?>" id="txtDongName" onfocus="clearForm(this,'동(읍/면/리)');" onblur="escapeForm(this,'동(읍/면/리)');" value="동(읍/면/리)" onkeydown="if(event.keyCode==13){clickSearchButton(); return false;}" style="width:98px;" class="input" /></label>
						<input name="gibun" type="text" value="<?=$_REQUEST[gibun]?$_REQUEST[gibun]:"지번"?>" id="txtJibun" onfocus="clearForm(this,'지번');" onblur="escapeForm(this,'지번');" value="지번" onkeydown="if(event.keyCode==13){clickSearchButton(); return false;}" style="width:98px;" class="input" />
						<a class="search" href="javascript:checkForm()"><img src="images/btn_search.gif" alt="검색" /></a>
					</div>
					<div class="word mode03" style="display:none">
						<label><span>검색어</span>
						<input name="buildingname" type="text" value="<?=$_REQUEST[buildingname]?$_REQUEST[buildingname]:"건물명(아파트명 등)"?>" id="txtBuildingName" onfocus="clearForm(this,'건물명(아파트명 등)');" onblur="escapeForm(this,'건물명(아파트명 등)');" value="건물명(아파트명 등)" onkeydown="if(event.keyCode==13){clickSearchButton(); return false;}" style="width:167px;" class="input" /></label>
						<a class="search" href="javascript:checkForm()"><img src="images/btn_search.gif" alt="검색" /></a>
					</div>
				</div>
				<p class="ex mode01">예) 테헤란로 152 → ‘서울시’‘강남구’ 선택 후 테헤란로(도로명) + 152(건물번호) </p>
				<p class="ex mode02" style="display:none">예) 잠실동 27 → ‘서울시’’송파구’ 선택 후 잠실동(동명) + 27(지번) </p>
				<p class="ex mode03" style="display:none">예) ‘서울시’ ’강남구’ 선택 후 강남파이낸스센터 (건물명)</p>
				<p class="notice" >※ 도로명 새주소가 검색되지 않는 경우는 행정안전부 새주소안내시스템 <a href="https://www.juso.go.kr" target="_blank">(http://www.juso.go.kr)</a>에서 확인하시기 바랍니다.</p>
				</form>
			</div>
			<div class="result roadname_list" style="display:none">
			  <p>아래 주소 중 해당되는 주소를 선택해 주세요.</p>
			  <div class="list">
				<ul>
					<?for($i=0;$i<$arrList["total"];$i++){?>
					<li>
						<span>
							<em><?=$arrList["list"][$i][sido]?> <?=$arrList["list"][$i][gugun]?> <?=$arrList["list"][$i][upmyon]?> <?=$arrList["list"][$i][road]?> <?=$arrList["list"][$i][building1]?><?=$arrList["list"][$i][building2]?"-":""?><?=$arrList["list"][$i][building2]?> <? if($arrList["list"][$i][dong]){?>(<?=$arrList["list"][$i][dong]?><?=$arrList["list"][$i][buildingname]?", ":""?><?=$arrList["list"][$i][buildingname]?>)<?}?><? if($arrList["list"][$i][dong]=="" && $arrList["list"][$i][buildingname]){?>(<?=$arrList["list"][$i][buildingname]?>)<?}?></em>
							[<?=substr($arrList["list"][$i][zip],0,3)?>-<?=substr($arrList["list"][$i][zip],3,3)?>] <?=$arrList["list"][$i][sido]?> <?=$arrList["list"][$i][gugun]?> <?=$arrList["list"][$i][dong]?><?=$arrList["list"][$i][ri]?> <?=$arrList["list"][$i][san]=="1"?"산":""?> <?=$arrList["list"][$i][gibun1]?><?=$arrList["list"][$i][gibun2]?"-":""?><?=$arrList["list"][$i][gibun2]?> <?=$arrList["list"][$i][buildingname]?>
						</span>
						<a href="javascript:setCode('<?=$_REQUEST[tp]?>','<?=substr($arrList["list"][$i][zip],0,3)?>','<?=substr($arrList["list"][$i][zip],3,3)?>','<?=$arrList["list"][$i][sido]?> <?=$arrList["list"][$i][gugun]?>  <?=$arrList["list"][$i][road]?> <?=$arrList["list"][$i][building1]?><?=$arrList["list"][$i][building2]?"-":""?><?=$arrList["list"][$i][building2]==0?"":$arrList["list"][$i][building2]?> <? if($arrList["list"][$i][dong]){?>(<?=$arrList["list"][$i][dong]?><?=$arrList["list"][$i][buildingname]?", ":""?><?=$arrList["list"][$i][buildingname]?>)<?}?><? if($arrList["list"][$i][dong]=="" && $arrList["list"][$i][buildingname]){?>(<?=$arrList["list"][$i][buildingname]?>)<?}?>');"><img src="images/btn_select.gif" alt="선택" /></a>
					</li>
					 <?}?>
				</ul>
			  </div>
			</div>

			<div class="result no_list" style="display:none">
			  <p class="none"><strong>검색결과가 없습니다.</strong>행안부 도로명주소에 동록되지 않은 주소이거나 잘못 입력한 경우입니다.</p>
			</div>
			
		</div>
	</div>
	<div class="button-area">
		<a href="javascript:self.close();"><img src="images/btn_close.gif" alt="닫기" /></a>
	</div>
</div>
<script language="javascript">
getCat('<?=$_GET[sido]?>','<?=$_GET[gugun]?>');
checkMode(<?=$_REQUEST[searchMode]?>);

<? if($arrList["total"]>0 && ($_REQUEST[dong] || $_REQUEST[road] || $_REQUEST[buildingname]) ) {?>
$(".notice").hide();
$(".roadname_list").show();
<?}?>

 <? if($arrList["total"]==0 && ($_REQUEST[dong] || $_REQUEST[road] || $_REQUEST[buildingname])){?>
$(".no_list").show();
 <?}?>
</script>
</body>
</html>