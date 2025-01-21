<?
############################ 저장후 리턴페이지 설정 ############################ ST
if($_GET['rlist']=="T"){
	session_start();
	header("location: ./good.php?".$_SESSION['searchParam']);
	exit();
}
############################ 저장후 리턴페이지 설정 ############################ ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/pop_top.php";

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

#################################### 행사상품구분을 $_GET['eventMd'] = Y 
//상품 리스트
$subQuery = " AND A.stock_type in (2,1) ";
$arrList = getGoodListBaseNFileFromCat(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"", $_GET['eventMd'], $subQuery);

	//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

//상품분류 리스트
$arrCategory = getCategoryList(0);//1차카테고리

$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

//카테고리 정보
$arrCatCode = explode("/", $arrCategoryInfo["list"][0]['cat_code']);

//분류 리스트
$arrCategory1 = getCategoryList(0);//1차카테고리
if($arrCatCode[0]){	$arrCategory2 = getCategoryList($arrCatCode[0]); }
if($arrCatCode[1]){	$arrCategory3 = getCategoryList($arrCatCode[1]); }

//DB해제
SetDisConn($dblink);

$priceType['KRW'] = "원화(KRW)";
$priceType['USD'] = "달러(USD)"; 
$priceType['EUR'] = "유로(EUR)"; 
$priceType['CHF'] = "프랑(CHF)"; 
$priceType['JPY'] = "엔화(JPY)";  
$priceType['ETC'] = "가격대체문구"; 
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

function fnGoodCopy(idx){
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
	var ss = "";
	var comma = "";
	var rows = $('input:checkbox[name=chk_list]:checked');
	
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		//ss.push(row.idx);
		ss += comma+row.value;
		comma = ",";
	}
	if(rows.length>0){
		//alert(ss);
		fnAddMsds(ss);
	}else{
		alert('선택된 항목이 없습니다.');
	}	
}
function fnAddMsds(ss){
	parent.fnGoodSelect(ss,'<?=$_GET['fname']?>');
}
$(function(){
    $(".check_all").click(function(){		
        var chk = $(this).is(":checked");//.attr('checked');
        if(chk) $(".chk_list").prop('checked', true);
        else  $(".chk_list").prop('checked', false);
    });
});
//-->
</script>
<div class="container">

	<div class="title">전체 상품 목록</div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
		<input type="hidden" id="cat_no" name="cat_no" value="<?=$_GET['cat_no']?>">
		<input type="hidden" name="eventMd" value="<?=$_GET['eventMd']?>">
		<input type="hidden" name="fname" value="<?=$_GET['fname']?>">
	<div class="inbox top_search">
		<dl>
			<dt>분류 1</dt>
			<dd id="cat_01">
				<select name="cat1" id="cat1" onchange="fnCat1(this.value);">
					<option value="">======1차분류======　　</option>
					<?
					for($i=0;$i<$arrCategory1["total"];$i++){
						if($arrCategory1["list"][$i]['cat_no']=="2" || $arrCategory1["list"][$i]['cat_no']=="3"){
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
		<!--<dl class="w2">
			<dt>등록일</dt>
			<dd><input type="text" id="datepicker1" class="w2 datepicker hasDatepicker"/><em>~</em><input type="text" id="datepicker2" /></dd>
		</dl>-->
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:100px">
					<option value='gcode'<?=$_GET['sw']=="gcode"?" selected='selected'":""?>>상품코드</option>
					<option value='name'<?=$_GET['sw']=="name"?" selected='selected'":""?>>상품명</option>
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
				</select>
				개씩 보기
			</div>
		</div>
		</form>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w10p">
						<col class="w6p">
						<col class="*">
						<col class="w8p">
						<col class="w8p">
						<col class="w12p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">상품코드</th>
							<th class="pc_vw">이미지</th>
							<th class="pc_vw">상품명</th>
							<th class="pc_vw">소비자가</th>
							<th class="pc_vw">판매가</th>
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

							
						
							$p_price = $arrList['list'][$i]['p_price'];	//할인전
							$price = $arrList['list'][$i]['price'];	// 할인후
					?>
						<tr>
							<td><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>
							<!--<td><i class="mo_vw">상품분류</i><?
							for($j=0; $j <count($arrThisCatCode)-1;$j++){
								echo $arrAllCategory[$arrThisCatCode[$j]];
								if($j < count($arrThisCatCode)-2){ echo " &gt; "; }								
							}
							?></td>-->
							<td><i class="mo_vw">상품코드</i><?=stripslashes($arrList['list'][$i]['g_code'])?></td>
							<td><?=$simg?></td>
							<td style="text-align:left;"><i class="mo_vw">상품명</i><strong><?=stripslashes($arrList['list'][$i]['g_name'])?></strong></td>
							<td><i class="mo_vw">소비자가</i><?=number_format($p_price)?></td>

							<td><i class="mo_vw">판매가</i><?=number_format($price)?></td>														
		
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="javascript:void(0);" onclick="fnAddMsds('<?=$arrList['list'][$i]['idx']?>')" class="btn perf">등록</a>
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
				<a href="./good_info.php?eventMd=<?=$_GET['eventMd']?>" class="btn">신규등록</a>
			</div>
		</div>
	</div>
</div>

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