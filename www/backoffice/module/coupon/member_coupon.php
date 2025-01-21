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

include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";

if(!in_array("homepage_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
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

$scale = 10;
if($_GET['page_size']){
	$scale = $_GET['page_size'];	
}
$pagescale = 10;
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getCouponListAdmin(0, 0, "Y");

$arrList2 = getGoodCouponList($scale, mysqli_escape_string($_REQUEST[offset]));

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function couponDel(id){
	var cfm;
	cfm =false;
	cfm = confirm("등록된 정보을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmContentsHidden.idx.value = id;
		document.frmContentsHidden.submit();
	}
}

// 상품별쿠폰 발급회원
function popMycoupon(idx){
	var url = "../shop/shop_mycoupon.php?idx=" + idx;
	var str = window.open(url,"MyCouponList","height=400, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	str.focus();
}

function fnSearch(frm){
	frm.submit();	
}
</script>
<div class="container">

	<div class="title">할인혜택 목록</div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
		<input type="hidden" id="cat_no" name="cat_no" value="<?=$_GET['cat_no']?>">
		<input type="hidden" name="eventMd" value="<?=$_GET['eventMd']?>">
	<div class="inbox top_search">
		<!--<dl>
			<dt>적용범위</dt>
			<dd>
				<select name="coupon_type" id="coupon_type">
					<option value="">전체　　</option>					
					<option value="order" <?=$_GET['coupon_type']=="order"?" selected":""?>>주문별</option>
					<option value="goods" <?=$_GET['coupon_type']=="goods"?" selected":""?>>상품별</option>					
				</select>
			</dd>
		</dl>-->
		<dl>
			<dt>등록일</dt>
			<dd><input type="text" class="datepicker" name="s_date" value="<?=$_GET['s_date']?>"/><em>~</em><input type="text" class="datepicker" name="e_date" value="<?=$_GET['e_date']?>" /></dd>
		</dl>
		<dl>
			<dt>할인구분</dt>
			<dd>
				<select name="coupon_unit" id="coupon_unit" style="width:120px;">
					<option value="">전체　　</option>					
					<option value="P" <?=$_GET['coupon_unit']=="P"?" selected":""?>>%할인</option>
					<option value="F" <?=$_GET['coupon_unit']=="F"?" selected":""?>>금액할인</option>					
				</select>
			</dd>
		</dl>		
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw">
					<!--<option value="">전체　　</option>-->
					<option value='n'<?=$_GET['sw']=="n"?" selected='selected'":""?>>혜택명　　</option>
				</select>	
				<input type="text" name="sk" value="<?=$_GET['sk']?>" onkeypress="if( event.keyCode == 13 ){fnSearch(document.form1);}"/>
				<button type="button" class="search" onclick="fnSearch(document.form1)">검색</button>
			</dd>
		</dl>
	</div>
	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
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
					<a href="./member_coupon_info.php" class="btn">신규등록</a>
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
						<col class="w3p">
						<col class="w7p">
						<col class="*">
						<col class="w7p">
						<col class="w7p">
						<col class="w7p">
						<col class="w7p">
						<col class="w12p">
						<col class="w7p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">할인구분</th>
							<th class="pc_vw">혜택명</th>
							<th class="pc_vw">사용여부</th>
							<th class="pc_vw">할인금액/할인율</th>
							<th class="pc_vw">최소주문금액</th>
							<th class="pc_vw">최대할인금액</th>
							<th class="pc_vw">사용기한</th>
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

							if($arrList['list'][$i]['coupon_sdate']!="0000-00-00" && $arrList['list'][$i]['coupon_edate']!="0000-00-00"){
								$coupon_playdate = $arrList['list'][$i]['coupon_sdate']." ~ ".$arrList['list'][$i]['coupon_edate'];
								if($arrList['list'][$i]['coupon_sdate'] <= date("Y-m-d") && $arrList['list'][$i]['coupon_edate'] >= date("Y-m-d")){
									$playTxt = "사용중";
								}else{
									$playTxt = "기간만료";
								}
							}else{
								$coupon_playdate = "제한없음";
								$playTxt = "사용중";
							}
							if($arrList['list'][$i]['under_price']>0){
								$under_price = number_format($arrList['list'][$i]['under_price']);
							}else{
								$under_price = "제한없음";
							}
							if($arrList['list'][$i]['over_price']>0){
								$over_price = number_format($arrList['list'][$i]['over_price']);
							}else{
								$over_price = "제한없음";								
							}
					?>
						<tr>
							<td><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>							
							<!--<td><i class="mo_vw">적용범위</i><?=$arrList['list'][$i]['coupon_type']=="order"?"주문별":"상품별"?></td>-->
							<td><i class="mo_vw">할인구분</i><?=$arrList['list'][$i]['coupon_unit']=="P"?"%할인":"금액할인"?></td>
							<td><i class="mo_vw">혜택명</i><a href="member_coupon_info.php?idx=<?=$arrList['list'][$i]['idx']?>&<?=$_SERVER['QUERY_STRING']?>" class="linktxt"><?=$arrList['list'][$i]['coupon_name']?></a></td>
							<td><i class="mo_vw">사용여부</i><?=$playTxt?></td>
							<td><i class="mo_vw">할인금액/할인율</i><?=$arrList['list'][$i]['coupon_unit']=="P"?number_format($arrList['list'][$i]['coupon_dis'])."%":number_format($arrList['list'][$i]['coupon_dis'])."원"?></td>
							<td><i class="mo_vw">최소주문금액</i><?=$under_price?></td>
							<td><i class="mo_vw">최대할인금액</i><?=$over_price?></td>
							<td><i class="mo_vw">사용기한</i><?=$coupon_playdate?></td>
							<td><i class="mo_vw">등록일</i><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="member_coupon_info.php?idx=<?=$arrList['list'][$i]['idx']?>&<?=$_SERVER['QUERY_STRING']?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="couponDel('<?=$arrList['list'][$i]['idx']?>')">삭제</button>
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
				<a href="./member_coupon_info.php" class="btn">신규등록</a>
			</div> -->
		</div>
	</div>
</div>
<form name="frmContentsHidden" method="post" action="coupon_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
<input type="hidden" name="returnURL" value="<?=$_SERVER[REQUEST_URI]?>">
</form>
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