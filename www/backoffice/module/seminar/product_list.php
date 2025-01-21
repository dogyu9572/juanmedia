<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrAllCategory	= getCategoryAll();				## 전체카테고리
$arrCategory	= getCategoryList(18, "Y");		## 연자

########################### search param ########################### ST
$searchCatno = $_GET['cname'];
########################### search param ########################### ED


################################ 상품 리스트 ST
$scale = 20;
if($_GET['page_size']){
	$scale = $_GET['page_size'];	
}
$pagescale = 10;

$order_by = " sort_num ASC";	## 상품에 정의된 순서	

if($searchCatno){	## 대상/연자 검색이 있으면
	$subQuery = " and D.cat_no in (".$searchCatno.") ";
	$tmpsubQuery = " and D.cat_no in (".$searchCatno.") ";
}
if($srhBshow){		## 상태 검색이 있으면
	$subQuery .= " and A.brand_show in (".$srhBshow.") ";
}



$cat_no = "4";				## 세미나 : 4

$arrGoodList = getGoodListBaseNFileFromCat($cat_no, $order_by,
	mysqli_real_escape_string($GLOBALS['dblink'], $_GET['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_GET['sk']), 
	0, 0,"Y", "SMN", $subQuery);
################################ 상품 리스트 ED


for($i=0;$i<$arrGoodList['list']["total"];$i++){
	################################ 연자 ################################ ST
	$arrExtCat[$i] = getGoodExtCat($arrGoodList["list"][$i]["idx"]);
	for($j=0;$j<$arrExtCat[$i]["total"];$j++){
		$arrExtCatCode = explode("/", $arrExtCat[$i]["list"][$j]["cat_code"]);
		if(in_array("18",$arrExtCatCode)){
			$arrGoodList["list"][$i]["htm_yunja"] = $arrAllCategory[$arrExtCatCode[2]];
		}		
	}
	################################ 브랜드 ################################ ED
}

$arrAllGoodIdx = "0";
for($i=0;$i<$arrGoodList['list']["total"];$i++){
	if($arrGoodList["list"][$i]['rel_g_idx']){
		$arrAllGoodIdx .= ",".$arrGoodList["list"][$i]['rel_g_idx'];
		$arrGidx = explode(",",$arrGoodList["list"][$i]['rel_g_idx']);
		for($k=0;$k<count($arrGidx);$k++){
			if($arrGoodList["list"][$i]["p_image"]){
				$goodIdx[$arrGidx[$k]]['imgUrl'] = "/uploaded/shop_good/".$arrGoodList["list"][$i]["idx"]."/".$arrGoodList["list"][$i]["p_image"];
			}else{
				$goodIdx[$arrGidx[$k]]['imgUrl'] = "/GATE_C/pub/images/best_product_img02.png";
			}
			$goodIdx[$arrGidx[$k]]['seminaName'] = $arrGoodList["list"][$i]["g_name"];
			$goodIdx[$arrGidx[$k]]['yunjaName'] = $arrGoodList["list"][$i]["htm_yunja"];
		}
	}
}			

################################ 연결된 상품 ################################ ST
$subQuery = " AND idx IN (".$arrAllGoodIdx.") ";
$arrList = getFreeView("tbl_shop_good", $subQuery, $col="*", $scale, $_GET['offset'], " ORDER BY seminar_orderby desc,idx DESC ");	

//DB해제
//	SetDisConn($dblink);
?>
<script language="javascript">
function delOrder(order_no){
	var cfm;
	cfm =false;
	cfm = confirm(order_no + " 이 주문건을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmOrderListHidden.order_no.value = order_no;
		document.frmOrderListHidden.submit();
	}
}

function orderStateChange(order_no, currorderstatus, val) {
	document.frmOrderChangeHidden.order_no.value = order_no;
	document.frmOrderChangeHidden.currorderstatus.value = currorderstatus;
	document.frmOrderChangeHidden.state.value = val;
	document.frmOrderChangeHidden.submit();
}

// 기간설정
function setPeriod(pdate){
	document.frmSort.s_date.value = pdate;
	document.frmSort.e_date.value = "<?=date("Y-m-d")?>";
}
$(function(){
    $(".check_all").click(function(){		      
		var chk = $(this).is(":checked");//.attr('checked');
        if(chk) $(".chk_list").prop('checked', true);
        else  $(".chk_list").prop('checked', false);
    });
});
function getSelections(str){
	var ss = "0";

	var rows = $('input:checkbox[name=chk_list]:checked');
	
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		//ss.push(row.idx);
		ss += ","+row.value;
	}
	if(rows.length>0){
		//alert(ss);
		orderCheck(ss,str);
	}else{
		alert('선택된 항목이 없습니다.');
	}	
}
function orderCheck(val,stat){		
	var conTxt = "환불처리 하시겠습니까?";
	if(stat=="2"){conTxt = "환불취소처리 하시겠습니까?";}
	if(confirm(conTxt)) {
		$.post("/module/shop/ajax_order_admin.php", { evnMode: "state", g_idx: val, states: stat },
		function(data){		
//			alert(data);
			if(data=="OK"){
				location.reload();		
			}
		});
	}
}
function delOrder(order_no){
	var cfm;
	cfm =false;
	cfm = confirm(order_no + " 이 주문건을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmOrderListHidden.order_no.value = order_no;
		document.frmOrderListHidden.submit();
	}
}
function fnSearch(frm){
	frm.submit();	
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
	//	alert(idxs)
	if(idxs){
		$.post("/module/shop/ajax_orderby_product.php", { gidx: idxs },
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
//-->
</script>
<div class="container">

	<div class="title">
		관련상품 관리
	</div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
		<input type="hidden" name="mode" value="<?=$_GET['mode']?>">
	<div class="inbox top_search">
		<dl>
			<dt>연자</dt>
			<dd>
			<select name="cname" id="cname" style="width:120px;" onchange="document.form1.submit();">
			<option value="">전체</option>		
			<?
			for($i=0;$i<$arrCategory["total"];$i++){
				if($arrCategory["list"][$i]['cat_no']==$_GET['cname']){
					$selected[$i] = "selected";
				}					
			?>
			<option value="<?=$arrCategory["list"][$i]['cat_no']?>" <?=$selected[$i]?>><?=$arrCategory["list"][$i]['cat_name']?></option>
		
			<?}?>
			</select>
			</dd>
		</dl>
		<!--<dl>
			<dt>사용기능</dt>
			<dd>
				<select name="special_show" id="special_show" style="width:120px;">
					<option value="">전체</option>					
					<option value="Y" <?=$_GET['special_show']=="Y"?" selected":""?>>참가신청</option>
					<option value="N" <?=$_GET['special_show']=="N"?" selected":""?>>영상구매</option>					
				</select>
			</dd>
		</dl>
		<dl>
			<dt>결제일시</dt>
			<dd>
				<input type="text" class="w1 datepicker" name="s_date" value="<?=$_REQUEST['s_date']?>" maxlength="10" /><em>&nbsp;~&nbsp;</em>
				<input type="text" class="w1 datepicker" name="e_date" value="<?=$_REQUEST['e_date']?>" maxlength="10" />
			</dd>
		</dl>		
		<dl>
			<dt>진행상태</dt>
			<dd><div class="inputs">
				<label><input type="checkbox" name="order_states[]" value="11" <? if (preg_match("/11/", $orderstate)) echo "checked"; ?> />신청완료</label>
				<label><input type="checkbox" name="order_states[]" value="12" <? if (preg_match("/12/", $orderstate)) echo "checked"; ?> />대기예약</label>
				<label><input type="checkbox" name="order_states[]" value="13" <? if (preg_match("/13/", $orderstate)) echo "checked"; ?> />신청취소</label>				
				</div>
			</dd>
		</dl>	
		-->
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:90px;">
					<!--<option value="">전체　　</option>-->
					<option value='name'<?=$_GET['sw']=="name"?" selected='selected'":""?>>세미나명</option>
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
			</form>
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w10p">
						<col class="w14p">
						<col class="w10p">
						<col class="w10p">
						<col class="*">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">연자</th>
							<th class="pc_vw">세미나명</th>
							<th class="pc_vw">상품코드</th>
							<th class="pc_vw" colspan="2">상품명/옵션명</th>
							<th class="pc_vw">정렬</th>
						</tr>
					<tbody id="sortWrap">
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							$arrOption[$i] = str_replace("|",", ", $arrList["list"][$i]['option_title']);
							if($arrList['list'][$i]['image_s']) {
								$simg = "<img src=\"/uploaded/shop_good/".$arrList['list'][$i]['idx']."/".$arrList['list'][$i]['image_s']."\">";
							} else {
								$simg = "";
							}
							
					?>
						<tr data-order="<?=$arrList['list'][$i]['idx']?>" style="width:100%;">
							<td style="width:60px"><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>		
							<td style="width:150px"><i class="mo_vw">연자</i><?=$goodIdx[$arrList["list"][$i]["idx"]]['yunjaName']?></td>		
							<td style="width:240px"><i class="mo_vw">세미나명</i><?=$goodIdx[$arrList["list"][$i]["idx"]]['seminaName']?></td>
							<td style="width:140px"><i class="mo_vw">상품코드</i><?=stripslashes($arrList['list'][$i]['g_code'])?></td>
							<td style="width:140px"><?=$simg?></td>
							<td style="width:700px;text-align:left;"><i class="mo_vw">상품명/옵션명</i><strong><?=stripslashes($arrList['list'][$i]['g_name'])?></strong><br/><?=$arrOption[$i]?></td>
							<td style="width:140px"><i class="mo_vw">정렬번호</i><?=$arrList['list'][$i]['seminar_orderby']?></td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td width="100%" colspan="7" >검색된 데이터가 없습니다.</td>
					</tr>
					<?}?>
					<tr style="height:10px;">
						<th colspan="7"></th>
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
			<div class="btns">	
			<!--	<a href="./member_coupon_info.php" class="btn">신규등록</a>-->
			</div>
		</div>
	</div>
</div>

<form name="frmOrderListHidden" method="post" action="order_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="order_no">
<input type="hidden" name="mode" value="<?=$_REQUEST['mode']?>">
</form>

<form name="frmOrderChangeHidden" method="post" action="order_evn.php">
<input type="hidden" name="evnMode" value="order">
<input type="hidden" name="order_no">
<input type="hidden" name="currorderstatus">
<input type="hidden" name="state">
<input type="hidden" name="listURL" value="<?=$_SERVER['REQUEST_URI']?>">
</form>

<?php include("pub/inc/footer.php") ?>
<script type="text/javascript">
//<![CDATA[
$(window).load(function(){
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
});
//]]>
</script>


<?#####################################################################################################################################################?>
<!--
		<form name="frmSort" method="get" action="<?=$_SERVER['PHP_SELF']?>">
		<input type="hidden" name="mode" value="<?=$_GET['mode']?>">
		
			<tr>
			  <th>통합검색</th>
			  <td class="space-left">
				<select name="sw">
				<option value="all"<?=$_REQUEST['sw']=="all"?" selected":""?>>주문자명+회원ID</option>
				<option value="name"<?=$_REQUEST['sw']=="name"?" selected":""?>>주문자명</option>
				<option value="id"<?=$_REQUEST['sw']=="id"?" selected":""?>>회원ID</option>
				</select>
				<input type="text" name="sk" value="<?=$_REQUEST['sk']?>" class="input" />&nbsp;&nbsp;&nbsp;
		
				<select name="sw2">
				<option value="goodname"<?=$_REQUEST['sw2']=="goodname"?" selected":""?>>상품명</option>
				</select>
				<input type="text" name="sk2" value="<?=$_REQUEST['sk2']?>" class="input" />
			</td>
			<td rowspan="6"><span class="btn_pack xlarge"><input type="submit" style="width:100px;font-weight:bold" value=" 검 색 " /></span></td>
		  </tr>
		  <tr>
			 <th>주문상태</th>
			 <td class="space-left">
				<? if($_GET['mode']=="1") {?>
				<input type="checkbox" name="order_states[]" value="1" <? if (preg_match("/1/", $orderstate)) echo "checked"; ?>/>입금대기 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="6" <? if (preg_match("/6/", $orderstate)) echo "checked"; ?> />입금확인 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="7" <? if (preg_match("/7/", $orderstate)) echo "checked"; ?> />배송준비중 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="8" <? if (preg_match("/8/", $orderstate)) echo "checked"; ?> />배송중 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="9" <? if (preg_match("/9/", $orderstate)) echo "checked"; ?> />구매완료 &nbsp;&nbsp;

				<input type="checkbox" name="order_states[]" value="2" <? if (preg_match("/2/", $orderstate)) echo "checked"; ?>/>취소요청 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="3" <? if (preg_match("/3/", $orderstate)) echo "checked"; ?>/>취소완료 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="4" <? if (preg_match("/4/", $orderstate)) echo "checked"; ?>/>교환/반품요청 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="5" <? if (preg_match("/5/", $orderstate)) echo "checked"; ?>/>교환/반품완료 &nbsp;&nbsp;
				<?}else if($_GET['mode']=="2") {?>
				<input type="checkbox" name="order_states[]" value="2" <? if (preg_match("/2/", $orderstate)) echo "checked"; ?>/>취소요청 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="3" <? if (preg_match("/3/", $orderstate)) echo "checked"; ?>/>취소완료 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="4" <? if (preg_match("/4/", $orderstate)) echo "checked"; ?>/>교환/반품요청 &nbsp;&nbsp;
				<input type="checkbox" name="order_states[]" value="5" <? if (preg_match("/5/", $orderstate)) echo "checked"; ?>/>교환/반품완료 &nbsp;&nbsp;
				<?}else if($_GET['mode']=="3") {?>
				미주문 &nbsp;&nbsp;
				<?}?>
			 </td>
		  </tr>
		  <tr>
			 <th>처리일자</th>
			 <td class="space-left"><input type="radio" name="sh_date" value="order_date" <?=$_REQUEST["sh_date"]=="order_date"?"checked":""?>>주문일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="ipkum_date" <?=$_REQUEST["sh_date"]=="ipkum_date"?"checked":""?>>입금일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="shipping_date" <?=$_REQUEST["sh_date"]=="shipping_date"?"checked":""?>>배송일 &nbsp;&nbsp;
				  <input type="radio" name="sh_date" value="finish_date" <?=$_REQUEST["sh_date"]=="finish_date"?"checked":""?>>거래완료일 &nbsp;&nbsp;
				<input type="text" name="s_date" id="s_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['s_date']?>" /> ~ <input type="text" name="e_date" id="e_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['e_date']?>" />
				&nbsp;
				<?
				$yes_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*1));
				$yes3_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*3));
				$to_day = date('Y-m-d');
				$week_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*7));
				$month_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*30));
				?>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$to_day?>')" style="color:#660000;">오늘</a></span>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$yes_day?>')" style="color:#660000;">어제</a></span>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$yes3_day?>')" style="color:#660000;">3일전</a></span>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$week_day?>')" style="color:#660000;">1주일</a></span>
			  <span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$month_day?>')" style="color:#660000;">1개월</a></span>
			</td>
		  </tr>
		  <tr>
			<th>결제가격</th>
			<td class="space-left">
				<input type="text" name="s_price" style="width:80px;" value="<?=$_REQUEST['s_price']?>" style="text-align:right" class="input" />원 ~ <input type="text" name="e_price" style="width:80px;" value="<?=$_REQUEST['e_price']?>" style="text-align:right" class="input" />원
			</td>
		  </tr>
		  <tr>
			<th>배송형태</th>
			<td class="space-left">
				<label><input type="radio" name="shipping_type" value="" <?=!$_REQUEST["shipping_type"]?"checked":""?>>전체</label>&nbsp;&nbsp;
				<label><input type="radio" name="shipping_type" value="visit" <?=$_REQUEST["shipping_type"]=="visit"?"checked":""?> >방문출고</label>&nbsp;&nbsp;
				<label><input type="radio" name="shipping_type" value="delivery" <?=$_REQUEST["shipping_type"]=="delivery"?"checked":""?>>택배출고</label>
			</td>
		  </tr>
		  <tr>
			<th>쿠폰 & 포인트 확인</th>
			<td class="space-left">
				<label><input type="checkbox" name="coupon_amount" value="Y" <?=$_REQUEST['coupon_amount']=="Y"?"checked":""?>/>쿠폰사용</label> &nbsp;&nbsp;
				<label><input type="checkbox" name="using_point" value="Y" <?=$_REQUEST['using_point']=="Y"?"checked":""?>/>포인트사용</label> &nbsp;&nbsp;
			</td>
		  </tr>
		</form>
-->