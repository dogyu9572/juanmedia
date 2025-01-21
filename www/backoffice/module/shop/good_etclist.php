<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

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
if($_GET['rdnm'] && $_GET['rdsc']){
	$order_by = " A.".$_GET['rdnm']." ".$_GET['rdsc']." ";
}

#################################### 행사상품구분을 $_GET['eventMd'] = Y 
//상품 리스트
$subQuery = " AND A.model in ('D','C') ";
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
$arrBrand = getCategoryList(3);//1차카테고리
$arrCategory1 = getCategoryList(2);
if($arrCatCode[1]){	$arrCategory2 = getCategoryList($arrCatCode[1]); }

$subQuery = " AND model in ('D','C') AND stock_type in ('1') AND stock>0 ";
$arrGoodList01 = getFreeView("tbl_shop_good", $subQuery, "idx", 0, 0, " order by idx desc ");

$subQuery = " AND model in ('D','C') AND stock_type in ('4') ";
$arrGoodList02 = getFreeView("tbl_shop_good", $subQuery, "idx", 0, 0, " order by idx desc ");

$subQuery = " AND model in ('D','C') AND stock_type in ('2') ";
$arrGoodList03 = getFreeView("tbl_shop_good", $subQuery, "idx", 0, 0, " order by idx desc ");

$subQuery = " AND model in ('D','C') AND stock_type in ('1') AND stock<1 ";
$arrGoodList04 = getFreeView("tbl_shop_good", $subQuery, "idx", 0, 0, " order by idx desc ");

//DB해제
SetDisConn($dblink);

############### 페이지 수정용 ############### ST
$queryString = explode("&",$_SERVER['QUERY_STRING']);
$reQueryString = "";
$comma = "";
for($i=0;$i<count($queryString);$i++){
	if(strpos($queryString[$i],"idx=")===false){
		$reQueryString .= $comma.$queryString[$i];							
		$comma = "&";
	}
}
############### 페이지 수정용 ############### ED

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
	//if(confirm("선택한 상품을 복사하시겠습니까?")){
		document.location = "good_evn.php?evnMode=copy&idx="+idx;
	//}
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
				//	$("#cat_03").html(defHtml);
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
function fnOrderby(rdnm, rdsc){
	var frm = document.form1;
	frm.rdnm.value = rdnm;
	frm.rdsc.value = rdsc;
	frm.submit();
}
//-->
</script>
<div class="container">

	<div class="title">위탁/매입 상품 목록</div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
		<input type="hidden" id="cat_no" name="cat_no" value="<?=$_GET['cat_no']?>">
		<input type="hidden" name="eventMd" value="<?=$_GET['eventMd']?>">
		<input type="hidden" name="rdnm" value="<?=$_GET['rdnm']?>">
		<input type="hidden" name="rdsc" value="<?=$_GET['rdsc']?>">
	<div class="inbox top_search">
		<dl>
			<dt>상품분류</dt>
			<dd>
				<select name="model" id="model" onchange="document.form1.submit();" style="width:100px;">
					<option value="">전체</option>
					<option value="D" <?=$_GET['model']=="D"?" selected":""?>>위탁</option>
					<option value="C" <?=$_GET['model']=="C"?" selected":""?>>매입</option>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>카테고리 1</dt>
			<dd id="cat_01">
				<select name="cat1" id="cat1" onchange="fnCat1(this.value);">
					<option value="">======1차분류======　　</option>
					<?
					for($i=0;$i<$arrCategory1["total"];$i++){						
					?>
					<option value="<?=$arrCategory1["list"][$i]['cat_no']?>"<?=$arrCatCode[1]==$arrCategory1["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory1["list"][$i]['cat_name']?></option>
					<?}?>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>카테고리 2</dt>
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
					<option value="<?=$arrCategory2["list"][$i]['cat_no']?>"<?=$arrCatCode[2]==$arrCategory2["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory2["list"][$i]['cat_name']?></option>
					<?}}?>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>브랜드</dt>
			<dd>
				<select name="brand" id="brand">
					<option value="">전체</option>
					<?
					for($i=0;$i<$arrBrand["total"];$i++){						
					?>
					<option value="<?=$arrBrand["list"][$i]['cat_no']?>"<?=$_GET['brand']==$arrBrand["list"][$i]['cat_no']?" selected":""?>><?=$arrBrand["list"][$i]['cat_name']?></option>
					<?}?>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>판매상태</dt>
			<dd>
				<select name="stock_type" id="stock_type" onchange="document.form1.submit();" style="width:120px;">
					<option value="">전체</option>
					<option value="1" <?=$_GET['stock_type']=="1"?" selected":""?>>판매중</option>
					<option value="2" <?=$_GET['stock_type']=="2"?" selected":""?>>판매완료</option>
					<option value="3" <?=$_GET['stock_type']=="3"?" selected":""?>>위탁 정산 완료</option>
					<option value="4" <?=$_GET['stock_type']=="4"?" selected":""?>>미노출</option>
					<option value="5" <?=$_GET['stock_type']=="5"?" selected":""?>>입고 전</option>
				</select>
			</dd>
		</dl>
		<dl class="w2">
			<dt>등록일</dt>
			<dd><input type="text" class="datepicker" name="s_date" value="<?=$_REQUEST['s_date']?>"  /><em>~</em><input type="text" class="datepicker" name="e_date" value="<?=$_REQUEST['e_date']?>" /></dd>
		</dl>
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:100px">
					<option value='all'<?=$_GET['sw']=="all"?" selected='selected'":""?>>전체</option>
					<option value='gcode'<?=$_GET['sw']=="gcode"?" selected='selected'":""?>>상품코드</option>
					<option value='name'<?=$_GET['sw']=="name"?" selected='selected'":""?>>상품명</option>
				</select>	
				<input type="text" name="sk" value="<?=$_GET['sk']?>" onkeypress="if( event.keyCode == 13 ){fnSearch(document.form1);}" />
				<button type="button" class="search" onclick="fnSearch(document.form1)">검색</button>
			</dd>
		</dl>
	</div>
	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
					<span class="infotxt wd90">판매 중 : <span class="redtxt wetxt"><?=$arrGoodList01['total']?>건</span></span>
					<span class="infotxt wd90">미노출 : <span class="redtxt wetxt"><?=$arrGoodList02['total']?>건</span></span>
					<span class="infotxt wd90">판매완료 : <span class="redtxt wetxt"><?=$arrGoodList03['total']+$arrGoodList04['total']?>건</span></span>
			</div>
			<div class="bdr_right">
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
				<div class="btns">	
					<a href="" class="btn">x라벨지 출력</a>
					<a href="./good_info.php?eventMd=<?=$_GET['eventMd']?>" class="btn">신규등록</a>
				</div>
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
						<col class="w6p">
						<col class="w6p">
						<col class="w8p">
						<col class="w8p">
						<col class="*">
						<col class="w6p">
						<col class="w6p">
						<col class="w10p">
						<col class="w8p">
						<col class="w5p">
						<col class="w4p">						
						<col class="w12p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">등록일
							<a href="javascript:void(0);" onclick="fnOrderby('published_date','desc')">▼</a><a href="javascript:void(0);" onclick="fnOrderby('published_date','asc')">▲</a></th>
							<th class="pc_vw">이미지</th>
							<th class="pc_vw">카테고리</th>
							<th class="pc_vw">브랜드</th>
							<th class="pc_vw">상품명</th>
							<th class="pc_vw">상품코드</th>
<!-- 							<th class="pc_vw">소비자가</th> -->
							<th class="pc_vw">고객명</th>
							<th class="pc_vw">고객연락처</th>
							<th class="pc_vw">판매가
							<a href="javascript:void(0);" onclick="fnOrderby('price','desc')">▼</a><a href="javascript:void(0);" onclick="fnOrderby('price','asc')">▲</a></th>
							<th class="pc_vw">판매상태</th>
							<th class="pc_vw">URL</th>
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
							############################################ 판매상태 ######################################## ST
							if($arrList['list'][$i]['stock_type']=="1"){								
								if($arrList['list'][$i]['stock']<1){
									$saleState = "saled";
									$arrList['list'][$i]['stock_type_txt'] = "판매완료";									
								}else{
									$arrList['list'][$i]['stock_type_txt'] = "판매중";
									$saleState = "";
								}
							}else if($arrList['list'][$i]['stock_type']=="2"){
								$arrList['list'][$i]['stock_type_txt'] = "판매완료";
								$saleState = "saled";
							}else if($arrList['list'][$i]['stock_type']=="4"){
								$arrList['list'][$i]['stock_type_txt'] = "미노출";
								$saleState = "";
							}else if($arrList['list'][$i]['stock_type']=="5"){
								$arrList['list'][$i]['stock_type_txt'] = "입고 전";
								$saleState = "before";
							}else if($arrList['list'][$i]['stock_type']=="3"){
								$arrList['list'][$i]['stock_type_txt'] = "위탁 정산 완료";
								$saleState = "";
							}
							############################################ 판매상태 ######################################## ED
							if($arrList['list'][$i]['model']=="D"){
								$g_name	= $arrList['list'][$i]['s_cal01'];
								$g_tel	= $arrList['list'][$i]['s_cal02'];
							}else if($arrList['list'][$i]['model']=="C"){							
								$g_name	= $arrList['list'][$i]['buy_name'];
								$g_tel	= $arrList['list'][$i]['buy_tel'];
							}
					?>
						<tr>
							<td><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>
							<td><i class="mo_vw">등록일</i><?=substr($arrList['list'][$i]['published_date'],0,10)?></td>
							<td><?=$simg?></td>
							<td><i class="mo_vw">상품카테고리</i><?
							for($j=1; $j <count($arrThisCatCode)-1;$j++){
									echo $arrAllCategory[$arrThisCatCode[$j]];
									if($j < count($arrThisCatCode)-2){ echo " &gt;<br/> "; }								
								}
							?></td>
							<td><i class="mo_vw">브랜드</i><?=$arrAllCategory[$arrList['list'][$i]['brand']]?></td>
							<td><i class="mo_vw">상품명</i><a href="good_info.php?idx=<?=$arrList['list'][$i]['idx']?>&<?=$_SERVER['QUERY_STRING']?>" class="linktxt"><?=stripslashes($arrList['list'][$i]['g_name'])?></a></td>
							<td><i class="mo_vw">상품코드</i><?=stripslashes($arrList['list'][$i]['g_code'])?></td>
							<td><i class="mo_vw">고객명</i><?=stripslashes($g_name)?></td>
							<td><i class="mo_vw">고객연락처</i><?=stripslashes($g_tel)?></td>
							<td><i class="mo_vw">판매가</i><?=number_format($arrList['list'][$i]['price'])?></td>
							<td  class="<?=$saleState?>" ><i class="mo_vw">판매상태</i><?=$arrList['list'][$i]['stock_type_txt']?></td>
							<td class="mono_btm"><i class="mo_vw">URL</i>
								<div class="btns">	
								<?
								if($arrList['list'][$i]['stock_type_txt']=="판매중" || $arrList['list'][$i]['stock_type_txt']=="미노출"){
								?>
									<a href="good_url.php?idx=<?=$arrList['list'][$i]['idx']?>" class="btn perf">URL</a>
								<?}?>
								</div>
							</td>
				
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<?if($arrList['list'][$i]['a_id']!="homepage"){?>
									<a href="javascript:void(0);" onclick="fnGoodCopy('<?=$arrList['list'][$i]['idx']?>')" class="btn perf" style="background:#636363;">복사</a>
									<a href="good_info.php?idx=<?=$arrList['list'][$i]['idx']?>&rtpg=<?=$_SERVER['PHP_SELF']?>&<?=$reQueryString?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="delGood('<?=$arrList['list'][$i]['idx']?>')">삭제</button>
									<?}?>
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
<!-- 			<div class="btns">	
				<a href="./good_info.php?eventMd=<?=$_GET['eventMd']?>" class="btn">신규등록</a>
			</div> -->
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
<?php include("pub/inc/footer.php") ?>