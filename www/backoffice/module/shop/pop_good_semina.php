<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/pop_top.php";


include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

// init
$scale = 500;
$pagescale = 10;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$order_by = " D.orderNum ASC, A.idx DESC";

#################################### 행사상품구분을 $_GET['eventMd'] = Y 
//상품 리스트
$arrList = getGoodListBaseNFileFromCat(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']), 
	$order_by, 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	$scale, $_REQUEST['offset'],"", "SMN");

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
$(function() {
	$("#sortWrap").sortable({
		axis: "y",
		containment: "parent",
		update: function (event, ui) {
			var order = $(this).sortable('toArray', {
				attribute: 'data-order'
			});
			console.log(order);
			fnOrderSave(order);
		}
	});
});
var arrIdx=[];
function fnOrderSave(order){
	arrIdx = order;	
}
// 상품 순서 변경
function fnGoodOrderby(){	
	var idxs = "";
	var comma = "";
	for(var i=0;i<arrIdx.length;i++){
		idxs += comma+arrIdx[i];
		comma = "|";
	}	
	if(idxs){
		$.post("/module/shop/ajax_orderby_good.php", { evnMode : 'best', gidx: idxs, catNo:"<?=$_REQUEST['cat_no']?>" },
			function(data){
				if(data){
				//	alert(data);
					location.reload();
				}
			}
		);
	}else{
		alert('변경된 순서가 없습니다.');
	}
}
function fnWritePage(){
	top.location.href = '/backoffice/module/seminar/seminar_info.php';
}
//-->
</script>
<style type="text/css">
	.list_image{max-width:120px;max-height:40px;}
</style>
<div class="container">

	<div class="title">세미나 목록</div>

	<div class="bdr_btm">
		<div class="btns">	
			<a href="javascript:void();" onclick="fnWritePage();" class="btn" style="margin: 10px;">세미나등록</a>
			<a href="javascript:void();" onclick="fnGoodOrderby();" class="btn" style="margin: 10px;">저장</a>
		</div>	
	</div>
	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
			</div>			
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w6p">
						<col class="w12p">
						<col class="*">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw" colspan="2">세미나명</th>
							<th class="pc_vw">순서</th>
						</tr>
					<tbody id="sortWrap">
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							$arrThisCatCode = explode("/", $arrList["list"][$i]['cat_code']);

							if($arrList['list'][$i]['image_s']) {
								$simg = "<img src=\"/uploaded/shop_good/".$arrList['list'][$i]['idx']."/".$arrList['list'][$i]['image_s']."\" class='list_image'>";
							} else {
								$simg = "";
							}

							$arrOption[$i] = str_replace("|",", ", $arrList["list"][$i]['option_title']);
					?>
						<tr data-order=<?=$arrList['list'][$i]['idx']?>>
							<td style="width:60px"><?=$arrList["total"]-$i-$_GET['offset']?></td>			
							<td style="width:100px"><?=$simg?></td>
							<td style="text-align:left;width:620px"><strong><?=stripslashes($arrList['list'][$i]['g_name'])?></strong><br/><?=$arrOption[$i]?></td>							
							<td style="width:100px"><input type="text" class="w1" name="orderby" readonly maxlength="5" value="<?=($i+1)?>" style="width:60px;text-align:center;"></td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td width="100%" colspan="4"  style="width:1000px">검색된 데이터가 없습니다.</td>
					</tr>
					<?}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php include("pub/inc/footer.php") ?>