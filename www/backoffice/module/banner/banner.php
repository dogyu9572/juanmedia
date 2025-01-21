<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/banner/banner.lib.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$b_type = $_REQUEST['b_type'] ?? "";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$scale = 100;
if($_GET['page_size']){
	$scale = $_GET['page_size'];	
}
$pagescale = 10;

if(!isset($_REQUEST['offset'])){
	$_REQUEST['offset']=0;
}
if(!isset($_REQUEST['b_type'])){
	$_REQUEST['b_type']="";
}

//제품 리스트
$arrList = getBannerList($scale, $_REQUEST['offset']);

//DB해제
SetDisConn($dblink);

$bannerType[1] = "KCA메인비주얼";
$bannerType[2] = "KCA메인배너";
$bannerType[3] = "KCIA";


$bannerDevice[1] = "PC";
$bannerDevice[2] = "MO";
?>
<script language="javascript">
function delBanner(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 이미지를 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmListHidden.idx.value = idx;
		document.frmListHidden.submit();
	}
}
function tSel(vstr){
	document.frm.submit();
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
	fnGoodOrderby();
}
// 상품 순서 변경
function fnGoodOrderby(){	
	var idxs = "";
	var comma = "";
	for(var i=0;i<arrIdx.length;i++){
		idxs += comma+arrIdx[i];
		comma = "|";
	}	
	//alert(idxs)
	if(idxs){
		
		$.post("/module/banner/ajax_orderby_banner.php", { gidx: idxs },
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

function fnAjaxYN(objt, sf){
	var apiUrl = "/module/shop/ajax_edit_def_yn.php";
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
		"gidx":gidx,"yn":yn,"sf":sf,"tn":"tbl_banner"
	}, function(data){
	//	alert(data);		
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

	<div class="title">이미지 목록</div>

	<form class="banner_flex" name="admin_form" method="post" action="/backoffice/module/admin/admin_evn.php">
		<input type="hidden" name="evnMode" value="setBanner">
		<div class="form_flex">
			<div class="inbox top_search">
				<dl class="search_wrap">
					<dt>문화연예시상식</dt>
					<dd>
						<div class="inputs">
							<input type="text" name="culture_banner_url" value="<?=$arrSetInfo["list"][0]['culture_banner_url']?>">
						</div>
					</dd>
				</dl>
				<dl class="search_wrap">
					<dt>KIMA</dt>
					<dd>
						<div class="inputs">
							<input type="text" name="kima_banner_url" value="<?=$arrSetInfo["list"][0]['kima_banner_url']?>">
						</div>
						<input type="submit" class="btn submit" onclick="document.admin_form.submit()" value="저장">
					</dd>
				</dl>
			</div>
		</div>
	</form>

	<form class="banner_flex" name="frm" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
	<div class="form_flex">
		<div class="inbox top_search">
			<dl>
				<dt>구분</dt>
				<dd>
					<select name="b_type" onchange="document.frm.submit();" style="width:160px;">
						<option value="">전체</option>
						<?php foreach($bannerType as $key => $val){?>
						<option value="<?=$key?>" <?=$key == $_GET["b_type"]?" selected":""?>><?=$val?></option>
						<?php } ?>				
					</select>
				</dd>
			</dl>
			<dl>
				<dt>PC/MO</dt>
				<dd>
					<select name="b_device" onchange="document.frm.submit();" style="width:160px;">
						<option value="">전체</option>
						<?php foreach($bannerDevice as $key => $val){?>
						<option value="<?=$key?>" <?=$key == $_GET["b_device"]?" selected":""?>><?=$val?></option>
						<?php } ?>				
					</select>
				</dd>
			</dl>
			<dl class="search_wrap">
				<dt>검색어</dt>
				<dd>
					<select name="sw" style="width:120px;">
						<option value='s'<?=$_GET['sw']=="s"?" selected='selected'":""?>>제목</option>
					</select>	
					<input type="text" name="sk" value="<?=$_GET['sk']?>" />
					<button type="button" class="search" onclick="document.frm.submit()">검색</button>
				</dd>
			</dl>
			
		</div>
		
	</div>

	<div class="inbox" style="">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
			</div>
			<div class="bdr_right">
				<div class="count">
					
					<!--<select name="st" onchange="document.frm.submit()">
						<option value="1"<?=$_REQUEST['st']=="1"?" selected":""?>>정렬역순</option>
						<option value="2"<?=$_REQUEST['st']=="2"?" selected":""?>>등록순</option>
					</select>				
					-->
					<select name="page_size" onchange="document.frm.submit()" style="width:60px;">
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
				</form>
				<div class="btns">
					<a href="./banner_add.php" class="btn">신규등록</a>
				</div>
			</div>
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w8p">
						<col class="w8p">
						<col class="w16p">
						<col class="*">
						<col class="w8p">
						<col class="w8p">
						<col class="w8p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">구분</th>
							<th class="pc_vw">PC/MO</th>
							<th class="pc_vw">이미지</th>
							<th class="pc_vw">제목</th>
							<th class="pc_vw">노출</th>
							<th class="pc_vw">정렬</th>
							<th class="pc_vw">등록일</th>
							<th class="pc_vw">관리</th>
						</tr>
					</thead>
					<tbody id="sortWrap">
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
					?>
						<tr data-order=<?=$arrList['list'][$i]['idx']?> style="width:100%;">
							<td><?=$arrList['total']-$i-(int)$_REQUEST['offset']?></td>
							<td><?=$bannerType[$arrList['list'][$i]['b_type']]?></td>
							<td><?=$bannerDevice[$arrList['list'][$i]['b_device']]?></td>
							<td><img src="/uploaded/banner/<?=$arrList['list'][$i]['b_image']?>" style="max-width:100px;max-height:100px;"></td>
							<td ><a href="banner_info.php?idx=<?=$arrList['list'][$i]['idx']?>" class="linktxt"><?=$arrList['list'][$i]['b_subject']?></a></td>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" value="<?=$arrList['list'][$i]['idx']?>" name="chk_list" <?=$arrList['list'][$i]['b_show']=="Y"?"checked":""?> onclick="fnAjaxYN(this,'b_show')"><i></i></label></td>
							<td><?=$arrList['list'][$i]['b_sort']?></td>
							<td><?=$arrList['list'][$i]['b_date']?></td>							
							<td class="mono_btm">
								<div class="btns">
									<a href="banner_info.php?idx=<?=$arrList['list'][$i]['idx']?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="delBanner('<?=$arrList['list'][$i]['idx']?>');">삭제</button>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td colspan="9">등록된 이미지가 없습니다.</td>
					</tr>
					<?}?>
					<tr style="height:10px;">
						<th colspan="9"></th>
					</tr>
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
				<a href="./banner_add.php" class="btn">신규등록</a>
			</div> -->
		</div>
	</div>

</div>

<form name="frmListHidden" method="post" action="banner_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
</form>

<?php include("pub/inc/footer.php") ?>