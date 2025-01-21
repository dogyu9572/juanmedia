<?
############################ 저장후 리턴페이지 설정 ############################ ST
if($_GET['rlist']=="T"){
	session_start();
	header("location: ".$_SERVER['PHP_SELF']."?".$_SESSION['searchParam']);
	exit();
}
############################ 저장후 리턴페이지 설정 ############################ ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;
############################ 저장후 리턴페이지 설정 ############################ ST
if($_GET['rlist']!="T"){
	if($_SERVER['QUERY_STRING']){
		$_SESSION['searchParam'] = $_SERVER['QUERY_STRING'];
	}
}
############################ 저장후 리턴페이지 설정 ############################ ED
// init
$scale = 10;
if($_GET['page_size']){
	$scale = $_GET['page_size'];	
}
$pagescale = 10;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($_REQUEST['st']=="1"){
	$order_by = " A.sort_num DESC, A.idx DESC ";
}else{
	$order_by = " A.idx DESC ";
}

$subQuery = "";
if($_GET['sshow']){
	$subQuery .= " AND special_show='".$_GET['sshow']."' ";
}
if($_GET['bshow']){
	$subQuery .= " AND brand_show='".$_GET['bshow']."' ";
}

#################################### 세미나 구분값 'SMN'
//상품 리스트
$arrList = getGoodListBaseNFileFromCat(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"", "SMN", $subQuery);

	//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

//상품분류 리스트
$arrCategory = getCategoryList(0);//1차카테고리

$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

//카테고리 정보
$arrCatCode = explode("/", $arrCategoryInfo["list"][0]['cat_code']);

//분류 리스트
$arrCategory1 = getCategoryList(0);//1차카테고리
$arrCatCode[0] = 4;	## 분류 1 고정
if($arrCatCode[0]){	$arrCategory2 = getCategoryList($arrCatCode[0]); }
if($arrCatCode[1]){	$arrCategory3 = getCategoryList($arrCatCode[1]); }

for($i=0;$i<$arrList['list']["total"];$i++){
	################################ 연자 ################################ ST
	$arrExtCat[$i] = getGoodExtCat($arrList["list"][$i]["idx"]);
	$comma = "";
	for($j=0;$j<$arrExtCat[$i]["total"];$j++){
		$arrExtCatCode = explode("/", $arrExtCat[$i]["list"][$j]["cat_code"]);
		if(in_array("18",$arrExtCatCode)){
			$arrList["list"][$i]["yunja"] = $arrAllCategory[$arrExtCatCode[2]];
		}else{
			//$arrList["list"][$i]["category_txt"] .= $comma.$arrAllCategory[$arrExtCatCode[2]];
			//$comma = "<br/>";
		}	
		if(in_array("183",$arrExtCatCode)){
			$arrList["list"][$i]["category_txt"] .= $comma.$arrAllCategory[$arrExtCatCode[2]];
			$comma = "<br/>";
		}
	}
	################################ 연자 ################################ ED
}
//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function delGood(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 상품을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmListHidden.idx.value = idx;
		document.frmListHidden.submit();
	}
}

function copyGood(idx){
	if(confirm("선택한 상품을 복사하시겠습니까?")){
		document.location = "good_evn.php?evnMode=copy&idx="+idx;
	}
}

function changeShow(idx,gb) {
	document.frmListHidden.evnMode.value = "changeshow";
	document.frmListHidden.idx.value = idx;
	document.frmListHidden.gb.value = gb;
	document.frmListHidden.submit();
}
</script>

<script type="text/javascript">
<!--
function fnCat1(tval){	
	var defHtml = '<select name="cat3" id="cat3" onchange="fnCat3(this.value);"><option value="" selected>======3차분류======　　</option></select>';
	$.post("/module/shop/ajax_selectbox_category.php", { snum : '2',cat_no: tval, eventMd : '<?=$_GET['eventMd']?>'},
		function(data){
			if(data){
				$("#cat_02").html(data);
				if('<?=$_GET['eventMd']?>'=='Y'){
					if(tval=="2"){
						fnCat2('11')
					}else{
						fnCat2('17')
					}					
				}else{
					$("#cat_03").html(defHtml);
				}
			}else{
				alert("실패.");
			}
		}
	);
	fnCatNo();
}
function fnCat2(tval){		
	$.post("/module/shop/ajax_selectbox_category.php", { snum : '3',cat_no: tval },
		function(data){
			if(data){
				$("#cat_03").html(data);	
			}else{
				alert("실패.");
			}
		}
	);
	fnCatNo();
}
function fnCat3(tval){		
	fnCatNo();
}
function fnCatNo(){	
	var cat_no1 = $("#cat1 option:selected").val();
	var cat_no2 = $("#cat2 option:selected").val();
	var cat_no3 = $("#cat3 option:selected").val();
	var cat_no = "";
	if(cat_no3 && cat_no2 && cat_no1){
		cat_no = cat_no3;
	}else if(cat_no2 && cat_no1){
		cat_no = cat_no2;
	}else{
		cat_no = cat_no1;
	}	
	$("#cat_no").val(cat_no);
}	
function fnSearch(frm){
	fnCatNo();
	frm.submit();	
}

// 선택 삭제시 singleSelect=true 값 변경 false
function getSelections(){
	var ss = "0";

	var rows = $('input:checkbox[name=chk_list]:checked');
	
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		//ss.push(row.idx);
		ss += ","+row.value;
	}
	if(rows.length>0){
		//alert(ss);
		boardDel(ss);
	}else{
		alert('선택된 항목이 없습니다.');
	}	
}
$(function(){
    $(".check_all").click(function(){		
        var chk = $(this).is(":checked");//.attr('checked');
        if(chk) $(".chk_list").prop('checked', true);
        else  $(".chk_list").prop('checked', false);
    });
});

function fnAjaxYN(objt, sf){
	var apiUrl = "/module/shop/ajax_edit_seminar_yn.php";
	var gidx = $(objt).val();
	var chk = $(objt).is(":checked");//.attr('checked');
	var yn = "";
	if(chk){
		yn = "Y";
	}else{
		yn = "N";
	}
	//	alert(yn)
	
	$.post(apiUrl, {
		"gidx":gidx,"yn":yn,"sf":sf
	}, function(data){
		//alert(data);		
		if(data=="true"){
			location.reload();
		}else{
			alert(data);	
		}
	});		
}
//-->
</script>
<div class="container">

	<div class="title">세미나 목록</div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
		<input type="hidden" id="cat_no" name="cat_no" value="<?=$_GET['cat_no']?>">
		<input type="hidden" name="eventMd" value="<?=$_GET['eventMd']?>">
	<div class="inbox top_search">		
		<dl>
			<dt>분류 1</dt>
			<dd id="cat_01">
				<select name="cat1" id="cat1" onchange="fnCat1(this.value);" style="width:120px;">
					<?
					for($i=0;$i<$arrCategory1["total"];$i++){
						if($arrCategory1["list"][$i]['cat_no']=="4"){
					?>
					<option value="<?=$arrCategory1["list"][$i]['cat_no']?>"<?=$arrCatCode[0]==$arrCategory1["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory1["list"][$i]['cat_name']?></option>
					<?}}?>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>분류 2</dt>
			<dd id="cat_02">
				<select name="cat2" id="cat2" onchange="fnCat2(this.value);">
					<option value="">======2차분류======　　</option>
					<?
					for($i=0;$i<$arrCategory2["total"];$i++){
						if($_GET['eventMd']=="Y"){
							if($arrCategory2["list"][$i]['cat_no']=="11" || $arrCategory2["list"][$i]['cat_no']=="17"){
								$viewflag[$i] = true;
								$arrCatCode[1] =$arrCategory2["list"][$i]['cat_no'];	## 검수필요
							}
						}else{
							if($arrCategory2["list"][$i]['cat_no']=="11" || $arrCategory2["list"][$i]['cat_no']=="17"){}else{
								$viewflag[$i] = true;
							}
						}
						if($viewflag[$i]){
					?>
					<option value="<?=$arrCategory2["list"][$i]['cat_no']?>"<?=$arrCatCode[1]==$arrCategory2["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory2["list"][$i]['cat_name']?></option>
					<?}}?>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>분류 3</dt>
			<dd id="cat_03">
				<select name="cat3" id="cat3" onchange="fnCat3(this.value);">
					<option value="">======3차분류======　　</option>
					<?for($i=0;$i<$arrCategory3["total"];$i++){?>
					<option value="<?=$arrCategory3["list"][$i]['cat_no']?>"<?=$arrCatCode[2]==$arrCategory3["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory3["list"][$i]['cat_name']?></option>
					<?}?>
				</select>
			</dd>
		</dl>	
		<dl>
			<dt>사용기능</dt>
			<dd id="cat_01">
				<select name="sshow" id="special_show" style="width:120px;">				
					<option value="">전체</option>			
					<option value="Y"<?=$_GET['sshow']=="Y"?" selected":""?>>참가신청</option>
					<option value="N"<?=$_GET['sshow']=="N"?" selected":""?>>영상구매</option>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>상태</dt>
			<dd id="cat_01">
				<select name="bshow" id="brand_show" style="width:120px;">				
					<option value="">전체</option>			
					<option value="Y"<?=$_GET['bshow']=="Y"?" selected":""?>>접수중</option>
					<option value="N"<?=$_GET['bshow']=="N"?" selected":""?>>접수마감</option>
					<option value="F"<?=$_GET['bshow']=="F"?" selected":""?>>무료영상</option>
					<option value="T"<?=$_GET['bshow']=="T"?" selected":""?>>유료영상</option>
				</select>
			</dd>
		</dl>
		<!--<dl class="w2">
			<dt>등록일</dt>
			<dd><input type="text" id="datepicker1" class="w2 datepicker hasDatepicker"/><em>~</em><input type="text" id="datepicker2" /></dd>
		</dl>-->
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:120px;">
				<!--	<option value="">전체</option>-->
					<option value='n'<?=$_GET['sw']=="n"?" selected='selected'":""?>>세미나명</option>
				</select>	
				<input type="text" name="sk" value="<?=$_GET['sk']?>" />
				<button type="button" class="search" onclick="fnSearch(document.form1)">검색</button>
			</dd>
		</dl>
	</div>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
			</div>	
			<div class="count">
				<select name="page_size" onchange="document.form1.submit()" style="width:60px;">
					<option value="100"	<?=$scale=="100"?'selected':""?>>100</option>
					<option value="50"	<?=$scale=="50"?'selected':""?>>50</option>
					<option value="40"	<?=$scale=="40"?'selected':""?>>40</option>
					<option value="30"	<?=$scale=="30"?'selected':""?>>30</option>
					<option value="20"	<?=$scale=="20"?'selected':""?>>20</option>
					<option value="15"	<?=$scale=="15"?'selected':""?>>15</option>
					<option value="10"	<?=$scale=="10"?'selected':""?>>10</option>
					<!--<option value="5"	<?=$scale=="5"?'selected':""?>>5</option>-->
				</select>
				개씩 보기
			</div>
			</form>
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="*">
						<col class="w6p">
						<col class="w6p">
						<col class="w8p">
						<col class="w4p">
						<col class="w5p">
						<col class="w4p">
						<col class="w6p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>	
							<th class="pc_vw">No.</th>
							<th class="pc_vw">분류</th>
							<th class="pc_vw">사용기능</th>
							<th class="pc_vw">상태</th>
							<th class="pc_vw" colspan="2">세미나명</th>							
							<th class="pc_vw">연자</th>
							<th class="pc_vw">참가신청금액<br/>(영상구매금액)</th>
							<th class="pc_vw">일정/수강기간</th>							
							<th class="pc_vw">게시여부</th>
							<th class="pc_vw">메인노출<br/>(내게 필요한)</th>
							<th class="pc_vw">메인노출<br/>(추천상품)</th>
							<th class="pc_vw">등록일</th>
							<th class="pc_vw">관리</th>
						</tr>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							$arrThisCatCode = explode("/", $arrList["list"][$i]['cat_code']);

							if($arrList['list'][$i]['image_s']) {
								$simg = "<img src=\"/uploaded/shop_good/".$arrList['list'][$i]['idx']."/".$arrList['list'][$i]['image_s']."\">";
							} else {
								$simg = "";
							}

							$arrOption[$i] = str_replace("|",", ", $arrList["list"][$i]['option_title']);
							if($arrList['list'][$i]['special_show']=="N"){	## 영상구매
								if($arrList['list'][$i]['s_time_radio']=="평생"){
									$arrList['list'][$i]['s_time_txt'] = "제한없음";
								}else{
									$arrList['list'][$i]['s_time_txt'] = $arrList['list'][$i]['s_time_radio'];
								}
							}else{	## 참가신청
								$arrList['list'][$i]['s_time_txt'] = $arrList['list'][$i]['s_cal01'];
								if($arrList['list'][$i]['s_cal02']){ $arrList['list'][$i]['s_time_txt'] .= "<br/>".$arrList['list'][$i]['s_cal02']; }
								if($arrList['list'][$i]['s_cal03']){ $arrList['list'][$i]['s_time_txt'] .= "<br/>".$arrList['list'][$i]['s_cal03']; }
								if($arrList['list'][$i]['s_cal04']){ $arrList['list'][$i]['s_time_txt'] .= "<br/>".$arrList['list'][$i]['s_cal04']; }
								if($arrList['list'][$i]['s_cal05']){ $arrList['list'][$i]['s_time_txt'] .= "<br/>".$arrList['list'][$i]['s_cal05']; }
							}
					?>
						<tr>
							
							<td><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>
							<td><i class="mo_vw">분류</i><?=$arrList["list"][$i]["category_txt"]?></td>
							<td><i class="mo_vw">사용가능</i><?=$arrList['list'][$i]['special_show']=="N"?"영상구매":"참가신청"?></td>
							<td><i class="mo_vw">상태</i><?=$SMNSTAT[$arrList['list'][$i]['brand_show']]?></td>
							<td><?=$simg?></td>
							<td style="text-align:left;"><i class="mo_vw">세미나명</i><strong><?=stripslashes($arrList['list'][$i]['g_name'])?></strong><br/><?=$arrOption[$i]?></td>
							<td><i class="mo_vw">연자</i><?=$arrList["list"][$i]["yunja"]?></td>
							<td><i class="mo_vw">참가신청금액<br/>(영상구매금액)</i><?=number_format($arrList['list'][$i]['price'])?></td>
							<td><i class="mo_vw">일정/수강기간</i><?=$arrList['list'][$i]['s_time_txt']?></td>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" value="<?=$arrList['list'][$i]['idx']?>" name="chk_list" <?=$arrList['list'][$i]['is_show']=="Y"?"checked":""?> onclick="fnAjaxYN(this,'is_show')"><i></i></label></td>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" value="<?=$arrList['list'][$i]['idx']?>" name="chk_list" <?=$arrList['list'][$i]['main_show']=="Y"?"checked":""?> onclick="fnAjaxYN(this,'main_show')"><i></i></label></td>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" value="<?=$arrList['list'][$i]['idx']?>" name="chk_list" <?=$arrList['list'][$i]['best_show']=="Y"?"checked":""?> onclick="fnAjaxYN(this,'best_show')"><i></i></label></td>
							<td><i class="mo_vw">등록일</i><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns"><?if($arrList['list'][$i]['a_id']!="homepage"){?>
									<a href="seminar_info.php?idx=<?=$arrList['list'][$i]['idx']?>&<?=$_SERVER['QUERY_STRING']?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="delGood('<?=$arrList['list'][$i]['idx']?>')">삭제</button><?}?>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td width="100%" colspan="11" >검색된 데이터가 없습니다.</td>
					</tr>
					<?}?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="bdr_btm">
			<div class="paging">			
			<?
			############### paging ############### ST
			$queryString = explode("&",$_SERVER['QUERY_STRING']);
			$reQueryString = "";
			$comma = "";
			for($i=0;$i<count($queryString);$i++){
				if(strpos($queryString[$i],"offset=")===false){
					$reQueryString .= $comma.$queryString[$i];
					$comma = "&";
				}
			}
			//	echo pageNavigationUser($arrBoardList["total"],$arrBoardInfo["list"][0]["scale"],$arrBoardInfo["list"][0]["pagescale"],$_GET['offset'],$reQueryString);
			echo pageNavigationBackoffice($arrList['total'], $scale, $pagescale, $_GET['offset'], $reQueryString);
			############### paging ############### ED
			?>
			</div>
			<div class="btns">				
				<a href="./seminar_info.php" class="btn">신규등록</a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
//달력
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.gif", 
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});
//체크박스
	var $allCheck = $('#allCheck');
	$allCheck.change(function () {
		var $this = $(this);
		var checked = $this.prop('checked');
		$('input[name="chk_list"]').prop('checked', checked);
	});
	var boxes = $('input[name="chk_list"]');
	boxes.change(function () {
		var boxLength = boxes.length;
		var checkedLength = $('input[name="chk_list"]:checked').length;
		var selectallCheck = (boxLength == checkedLength);
		$allCheck.prop('checked', selectallCheck);
	});
});
//]]>
</script>
<form name="frmListHidden" method="post" action="good_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
<input type="hidden" name="gb">
<input type="hidden" name="rt_url" value="<?=$_SERVER['REQUEST_URI']?>">
</form>
<script language="javascript">
<?
for($i=0;$i<count($arrCatCode)-1;$i++){
?>
getCat<?=$i+1?>('<?=$arrCatCode[$i]?>','<?=$arrCatCode[$i+1]?>');
<?
}
?>
</script>
<?php include("pub/inc/footer.php") ?>