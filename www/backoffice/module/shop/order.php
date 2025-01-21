<?
############################ 저장후 리턴페이지 설정 ############################ ST
if($_GET['rlist']=="T"){
	session_start();
	header("location: ".$_SERVER['PHP_SELF']."?".$_SESSION['searchParam']);
	exit();
}
############################ 저장후 리턴페이지 설정 ############################ ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu_order.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/_api/ecount_api/ecount_zone.php";	//	erp 키값
if(!in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
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
$scale = 20;
if($_GET['page_size']){
	$scale = $_GET['page_size'];	
}
$pagescale = 10;

if(!$_REQUEST['sh_date']) $_REQUEST['sh_date']="order_date";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if(!$_REQUEST['sk']){
	$suqQuery  = " AND (A.shipping_order_no IS NULL OR A.shipping_order_no='') ";		## shipping_order_no : 묶음배송 설정값
	$suqQuery .= " AND A.reserve_state not in ('12') ";		## reserve_state : 12 = 예약주문(2차)값
	if($_GET['mode'] == "1") {				## 전체주문조회 
		$suqQuery .= " AND A.idx Not in ( SELECT idx FROM tbl_shop_order_info WHERE reserve_state='11' AND order_state IN (10,1) )";	// 예약 1차중 입금전인부분은 미표출
	}
}
	
$arrList = getOrderListAll(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date']), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['order_state']), 
	$scale, $_REQUEST['offset'], $suqQuery);

$arrGoodList01 = getFreeView("tbl_shop_order_info", " AND order_state='1' ", "idx", 0, 0, " order by idx desc ");
$arrGoodList02 = getFreeView("tbl_shop_order_info", " AND order_state='11' ", "idx", 0, 0, " order by idx desc ");
$arrGoodList03 = getFreeView("tbl_shop_order_info", " AND order_state='6' ", "idx", 0, 0, " order by idx desc ");

$arrAllCategory = getCategoryAll();
$arrBrandList = getCategoryList(3,'Y');			//회원등급

//DB해제
//SetDisConn($dblink);

if($_REQUEST['pay_type']) {
	for($oo=0; $oo < count($_REQUEST['pay_type']); $oo++){
		$paytype .= $_REQUEST['pay_type'][$oo].",";
	}
}
if($_REQUEST['order_states']) {
	for($os=0; $os < count($_REQUEST['order_states']); $os++){
		$orderstate .= "/".$_REQUEST['order_states'][$os]."/,";
	}
}

$arrOrderState = explode("/",$orderstate);
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
function fnERPSend(sid){
	var ss = "";
	var rows = $('input:checkbox[name=chk_list]:checked');
	var comma = "";
	
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		//ss.push(row.idx);
		ss += comma+row.value;
		comma = ",";
	}
	if(rows.length>0){
		//alert(ss);
		fnERPAPIAjax(ss, sid);
	}else{
		alert('선택된 항목이 없습니다.');
	}	
}
function fnERPAPIAjax(val, sid){		
	var conTxt = "ERP 전송을 하시겠습니까?";
	if(confirm(conTxt)) {		
		$.post("/_api/ecount_api/ecount.php", { g_idx: val, sid : sid},
		function(data){		
			alert(data);
			if(data=="OK"){
				location.reload();		
			}
		});
	}
}
/*
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
*/
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
	//	alert(ss);
		fnOrderState(ss);
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
function fnOrderState(ridx){	
	var rstate = $("#rstate").val();
//	alert(ridx+"/"+rstate);
	if(confirm("일괄변경을 진행하시겠습니까?")) {
		var apiUrl = "/module/shop/ajax_order_state_all.php";
		$.post(apiUrl, { "rval":rstate, "ridx":ridx }, function(data){						
			alert("변경되었습니다.");	
			location.reload();
		});	
	}
}
function fnOrderby(rdnm, rdsc){
	var frm = document.form1;
	frm.rdnm.value = rdnm;
	frm.rdsc.value = rdsc;
	frm.submit();
}
function fnOrderStateGo(str){
	location.href='order.php?mode=1&order_states%5B%5D='+str;
}

</script>
<style type="text/css">
	.color1{color:#50CE19;}
</style>
<div class="container">

	<div class="title">
		<? if($_GET['mode']=="1") {?>전체 주문 조회
		<?}else if($_GET['mode']=="4") {?>예약 주문 조회
		<?}else if($_GET['mode']=="2") {?>취소/환불/교환
		<?}?>
	</div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
		<input type="hidden" name="mode" value="<?=$_GET['mode']?>">
		<input type="hidden" name="rdnm" value="<?=$_GET['rdnm']?>">
		<input type="hidden" name="rdsc" value="<?=$_GET['rdsc']?>">
	<div class="inbox top_search">	
		<dl>
			<dt>등급별</dt>
			<dd>
				<select name="a_class" id="a_class" style="width:120px;">
					<option value="">전체</option>
					<?
					for($i=0;$i<$arrBrandList["total"];$i++){									
					?>
					<option value="<?=$arrBrandList["list"][$i]['cat_no']?>"<?=$_GET['a_class']==$arrBrandList["list"][$i]['cat_no']?" selected":""?>><?=$arrBrandList["list"][$i]['cat_name']?></option>
					<?}?>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>결제방법</dt>
			<dd>
				<select name="pay_type" id="pay_type" style="width:120px;">
					<option value="">전체</option>					
					<option value="card" <?=$_GET['pay_type']=="card"?" selected":""?>>신용카드</option>
					<option value="cash" <?=$_GET['pay_type']=="cash"?" selected":""?>>무통장</option>						
				</select>
			</dd>
		</dl>
		<?	if($_GET['mode']=="1"){			##################################################### 전체 주문 조회	?>
		<dl>
			<dt>주문일시</dt>
			<dd>
				<input type="text" class="w1 datepicker" name="s_date" value="<?=$_REQUEST['s_date']?>" maxlength="10" /><em>&nbsp;~&nbsp;</em>
				<input type="text" class="w1 datepicker" name="e_date" value="<?=$_REQUEST['e_date']?>" maxlength="10" />
			</dd>
		</dl>	
		<dl>
			<dt>배송희망일</dt>
			<dd>
				<input type="text" class="w1 datepicker" name="shipping_date" value="<?=$_REQUEST['shipping_date']?>" maxlength="10" />
			</dd>
		</dl>		
		<?	}else if($_GET['mode']=="3"){	##################################################### 배송 관리			?>
		<dl>
			<dt>발송일시</dt>
			<dd>
				<input type="text" class="w1 datepicker" name="s_sdate" value="<?=$_REQUEST['s_sdate']?>" maxlength="10" /><em>&nbsp;~&nbsp;</em>
				<input type="text" class="w1 datepicker" name="e_sdate" value="<?=$_REQUEST['e_sdate']?>" maxlength="10" />
			</dd>
		</dl>		
		<?	}else if($_GET['mode']=="2"){	##################################################### 취소/교환/반품	?>
		<dl>
			<dt>처리일시</dt>
			<dd>
				<input type="text" class="w1 datepicker" name="s_hdate" value="<?=$_REQUEST['s_hdate']?>" maxlength="10" /><em>&nbsp;~&nbsp;</em>
				<input type="text" class="w1 datepicker" name="e_hdate" value="<?=$_REQUEST['e_hdate']?>" maxlength="10" />
			</dd>
		</dl>		
		<?	}?>
		<dl>
			<dt>진행상태</dt>
			<dd><div class="inputs">
				<? if($_GET['mode']=="1" || $_GET['mode']=="4") {?>
				<label><input type="checkbox" name="order_states[]" value="1" <? if (in_array("1", $arrOrderState)) echo "checked"; ?>/>입금전</label>
				<label><input type="checkbox" name="order_states[]" value="6" <? if (in_array("6", $arrOrderState)) echo "checked"; ?> />결제완료</label>				
				<label><input type="checkbox" name="order_states[]" value="11" <? if (in_array("11", $arrOrderState)) echo "checked"; ?> />주문확인</label>
				<label><input type="checkbox" name="order_states[]" value="7" <? if (in_array("7", $arrOrderState)) echo "checked"; ?> />배송준비중</label>
				<label><input type="checkbox" name="order_states[]" value="8" <? if (in_array("8", $arrOrderState)) echo "checked"; ?> />배송중</label>
				<label><input type="checkbox" name="order_states[]" value="9" <? if (in_array("9", $arrOrderState)) echo "checked"; ?> />배송완료</label>

				<label><input type="checkbox" name="order_states[]" value="3" <? if (in_array("3", $arrOrderState)) echo "checked"; ?>/>전체주문취소</label>
				<label><input type="checkbox" name="order_states[]" value="4" <? if (in_array("4", $arrOrderState)) echo "checked"; ?>/>환불완료</label>
				<label><input type="checkbox" name="order_states[]" value="5" <? if (in_array("5", $arrOrderState)) echo "checked"; ?>/>부분환불/교환</label>
				<?}else if($_GET['mode']=="2") {?>
				<label><input type="checkbox" name="order_states[]" value="3" <? if (in_array("3", $arrOrderState)) echo "checked"; ?>/>전체주문취소</label>
				<label><input type="checkbox" name="order_states[]" value="4" <? if (in_array("4", $arrOrderState)) echo "checked"; ?>/>환불완료</label>
				<label><input type="checkbox" name="order_states[]" value="5" <? if (in_array("5", $arrOrderState)) echo "checked"; ?>/>부분환불/교환</label>
				<?}else if($_GET['mode']=="3") {?>
				<label><input type="checkbox" name="order_states[]" value="6" <? if (in_array("6", $arrOrderState)) echo "checked"; ?> />배송준비중</label>
				<label><input type="checkbox" name="order_states[]" value="9" <? if (in_array("9", $arrOrderState)) echo "checked"; ?> />배송완료</label>
				<?}?>
				</div>
			</dd>
		</dl>	
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:100px;">
					<option value="all" <?=$_GET['sw']=="all"?" selected":""?>>전체</option>
					<option value='name'<?=$_GET['sw']=="name"?" selected='selected'":""?>>이름</option>
					<option value='id'<?=$_GET['sw']=="id"?" selected='selected'":""?>>아이디</option>
					<option value='ono'<?=$_GET['sw']=="ono"?" selected='selected'":""?>>주문번호</option>
					<option value='tel'<?=$_GET['sw']=="tel"?" selected='selected'":""?>>휴대폰번호</option>
					<option value='gcode'<?=$_GET['sw']=="gcode"?" selected='selected'":""?>>상품코드</option>
					<option value='gname'<?=$_GET['sw']=="gname"?" selected='selected'":""?>>주문상품</option>
- 					<option value='order_cname'<?=$_GET['sw']=="order_cname"?" selected='selected'":""?>>상호명</option> 
- 					<option value='order_cust'<?=$_GET['sw']=="order_cust"?" selected='selected'":""?>>거래처등록번호</option> 
				</select>	
				<input type="text" name="sk" value="<?=$_GET['sk']?>" onkeypress="if( event.keyCode == 13 ){fnSearch(document.form1);}" />
				<button type="button" class="search" onclick="fnSearch(document.form1)">검색</button>
			</dd>
		</dl>
	</div>
	<?php if($_GET['mode']=="1"){ ?>
	<div class="inbox top_search" style="display:none;">	
		<dl>
			<dt>운송장 엑셀파일</dt>
			<dd>
				<iframe style="height:25px; width:500px;" src="/backoffice/module/shop/order_xlsx_input.php" frameborder="0"></iframe>
			</dd>
		</dl>
		<dl>
			<dt>엑셀양식다운로드</dt>
			<dd>
				<a href="/backoffice/module/shop/order_ex_xls.php?<?=$_SERVER["QUERY_STRING"]?>">양식다운로드.xls</a>
				<span style="color:#b72427;padding-left:20px;">※ 출고일자(shipping_date)는 서식 변경(텍스트)후 업로드하세요.</span>
				<br><span style="color:#b72427;">※ 「엑셀다운로드」로 다운로드한 파일을 바로 운송장으로 업로드 시 파일 형식을 「Excel 통합문서」로 저장 후 업로드하시면 됩니다.</span>
			</dd>
		</dl>
	</div>
	<?php } ?>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>	
				
				
				
				<div class="down">
					<a href="/backoffice/module/shop/order_to_xls.php?<?=$_SERVER["QUERY_STRING"]?>" class="excel">엑셀다운<span class="pc_vw">로드</span></a>
					<a href="/backoffice/module/shop/order_shipping_to_xls.php?<?=$_SERVER["QUERY_STRING"]?>" class="excel">배송사<span class="pc_vw">엑셀자료</span></a>
				</div>				
				<span class="infotxt wd120" style="margin-left:20px;cursor:pointer;" onclick="fnOrderStateGo('1')">입금전 : <span class="redtxt wetxt"><?=number_format($arrGoodList01['total'])?>건</span></span>
				<span class="infotxt wd120" style="margin-left:20px;cursor:pointer;" onclick="fnOrderStateGo('11')">주문확인 : <span class="redtxt wetxt"><?=number_format($arrGoodList02['total'])?>건</span></span>
				<span class="infotxt wd120" style="margin-left:20px;cursor:pointer;" onclick="fnOrderStateGo('6')">결제완료 : <span class="redtxt wetxt"><?=number_format($arrGoodList03['total'])?>건</span></span>
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
				</form>

				<div class="btns">	
					<select name="rstate" id="rstate">
					<?
					foreach ($_SITE["SHOP"]["ORDER_STATE"] AS $key => $val){
						if($key<20){
					?>
					<option value="<?=$key?>"<?=$arrInfo["list"][0]["order_state"]==$key?" selected":""?>><?=$val?></option>
					<?}}?>
					</select>	
					<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택변경</a>
				</div>
			</div>
		</div>

<!-- 		<div>		
			<div class="btns">	
				<select name="rstate" id="rstate">
				<?
				foreach ($_SITE["SHOP"]["ORDER_STATE"] AS $key => $val){
					if($key<20){
				?>
				<option value="<?=$key?>"<?=$arrInfo["list"][0]["order_state"]==$key?" selected":""?>><?=$val?></option>
				<?}}?>
				</select>	
				<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택변경</a>
			</div>
		</div> -->
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
				<? if($_GET['mode']=="1" || $_GET['mode']=="4"){	##################################################### 전체 주문 조회 ?>
					<colgroup class="pc_vw">
						<col class="w2p">
						<col class="w3p">
						<col class="w5p">
						<col class="w4p">
						<col class="w3p">
						<col class="w7p">
						<col class="w8p">
						<col class="w12p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w5p">
						<col class="w9p">
					</colgroup>
					<thead>
						<tr>	
							<th><label class="check notxt"><input type="checkbox" name="" id="allCheck"><i></i></label></th>
							<th class="pc_vw">No.</th>
							<th class="pc_vw">주문일시
							<!--<a href="javascript:void(0);" onclick="fnOrderby('order_date','desc')">▼</a><a href="javascript:void(0);" onclick="fnOrderby('order_date','asc')">▲</a>-->
							</th>
							<th class="pc_vw">주문번호</th>
							<th class="pc_vw">등급</th>
							<th class="pc_vw">주문자(ID)</th>
							<th class="pc_vw">상호명(거래처등록번호)</th>
							<th class="pc_vw">주문상품</th>							
							<th class="pc_vw">실결제금액
							<!--<a href="javascript:void(0);" onclick="fnOrderby('pay_amount','desc')">▼</a><a href="javascript:void(0);" onclick="fnOrderby('pay_amount','asc')">▲</a></th>-->
							<th class="pc_vw">입금주(상호명)</th>
							<th class="pc_vw">결제방법</th>
							<th class="pc_vw">배송희망일</th>
							<th class="pc_vw">진행상태</th>
							<th class="pc_vw">관리</th>
						</tr>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							//합계금액 계산
							$totalPrice = $arrList["list"][$i]['total_amount']+$arrList["list"][$i]['ship_amount'];
							$arrQtyList = getOrderGoodList($arrList["list"][$i]['order_no']);							
							$trColor="#fffff";
							
							// 결제방법 색상 지정
							if($arrList["list"][$i]['pay_type']=="card"){
								$pay_type = "card";
							}else{
								$pay_type = "account";
							}							

							if($arrList["list"][$i]['order_state']=="2" || $arrList["list"][$i]['order_state']=="3" || $arrList["list"][$i]['order_state']=="4" || $arrList["list"][$i]['order_state']=="5"){
								$trColor="#f1dede";
							}							

							$dis_amount[$i] = $arrList['list'][$i]['coupon_amount'] + $arrList['list'][$i]['using_point'];
							####################################### 상품이미지 ####################################### ST
							$imgQuery = "SELECT image_m FROM tbl_shop_good WHERE idx='".$arrList["list"][$i]['g_idx']."'";
							$arrImg = getFreeQueryR($imgQuery);
							if($arrImg['list'][0]['image_m']){
								$imageUrl = "<img src='/uploaded/shop_good/".$arrList["list"][$i]['g_idx']."/".$arrImg['list'][0]['image_m']."'>";
							}else{
								$imageUrl = "";
							}
							####################################### 상품이미지 ####################################### ED
							$arrUserInfo = getUserInfo($arrList["list"][$i]['order_id']);
					?>
						<tr <?=$styletr[$i]?>>
							<td><label class="check notxt"><input type="checkbox" value="<?=$arrList["list"][$i]['idx']?>" name="chk_list"><i></i></label></td>
							<td <?=$rowspan[$i]?>><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>

							<td <?=$rowspan[$i]?>><i class="mo_vw">주문일시</i><?=str_replace(" ","<br/>",$arrList['list'][$i]['order_date'])?></td>
							<td><i class="mo_vw">주문번호</i><a href="order_detail.php?order_no=<?=$arrList["list"][$i]['order_no']?>" class="linktxt"><?=$arrList["list"][$i]['order_no']?></a></td>
							<td><i class="mo_vw">등급</i><?=$arrAllCategory[$arrList["list"][$i]['order_class']]?></td>

							<td <?=$rowspan[$i]?>><i class="mo_vw">주문자(ID)</i><a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList["list"][$i]['order_id']?>" class="linktxt"><?=$arrUserInfo["list"][0]['user_name']?><br/>(<?=$arrList["list"][$i]['order_id']?>)</a></td>

							<td><i class="mo_vw">상호명</i><?=$arrList["list"][$i]['order_cname']?><?=$arrList["list"][$i]['order_cust']?"<br>(".$arrList["list"][$i]['order_cust'].")":""?></td>
							<!--<td><i class="mo_vw">상품이미지</i><?=$imageUrl?></td>-->
							<td><i class="mo_vw">주문상품</i><?=$arrList['list'][$i]['order_summary']?></td>
							<td><i class="mo_vw">실결제금액</i><?=number_format($arrList['list'][$i]['pay_amount'])?></td>
							<td><i class="mo_vw">입금주(상호명)</i><?=$arrList['list'][$i]['pay_type']=="cash"?$arrList['list'][$i]['bank_name']:""?></td>
							<td class="<?=$pay_type?>"><i class="mo_vw">결제방법</i><?=$_SITE["SHOP"]["PAY_TYPE"][$arrList["list"][$i]['pay_type']]?></td>
							<td><i class="mo_vw">배송희망일</i><?=$arrList["list"][$i]['shipping_date']?></td>
							<td><i class="mo_vw">진행상태</i><?=$_SITE["SHOP"]["RESERVE_STATE"][$arrList["list"][$i]['reserve_state']]?>
								<div  class="color<?=$arrList["list"][$i]['order_state']?>"><?=$_SITE["SHOP"]["ORDER_STATE"][$arrList["list"][$i]['order_state']]?></div>
							</td>

							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">									
									<a href="order_detail.php?order_no=<?=$arrList['list'][$i]['order_no']?>&<?=$_SERVER['QUERY_STRING']?>" class="btn modi">수정</a>
 									<button type="button" class="btn del" onclick="delOrder('<?=$arrList['list'][$i]['order_no']?>')">삭제</button>
								</div>
							</td>
						</tr>
									
					<?						
						}
					}else{
						echo '<tr height="100"><td width="100%" colspan="14" >검색된 데이터가 없습니다.</td></tr>';
					}
					?>
					</tbody>				
				<?}else if($_GET['mode']=="3"){	##################################################### 배송 관리?>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w8p">
						<col class="w10p">
						<col class="w8p">
						<col class="w10p">
						<col class="w14p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w8p">
					</colgroup>
					<thead>
						<tr>
							<th class="pc_vw">No.</th>
							<th class="pc_vw">주문일시</th>
							<th class="pc_vw">주문번호</th>
							<th class="pc_vw">주문자(ID)</th>
							<th class="pc_vw">상호명(거래처등록번호)</th>
							<th class="pc_vw">주문상품</th>
							<th class="pc_vw">수취인</th>
							<th class="pc_vw">운송장번호</th>
							<th class="pc_vw">배송희망일</th>						
							<th class="pc_vw">진행상태</th>
							<th class="pc_vw">관리</th>
						</tr>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							//합계금액 계산
							$totalPrice = $arrList["list"][$i]['total_amount']+$arrList["list"][$i]['ship_amount'];
							$arrQtyList = getOrderGoodList($arrList["list"][$i]['order_no']);							
							$trColor="#fffff";
							if($arrList["list"][$i]['order_state']=="2" || $arrList["list"][$i]['order_state']=="3" || $arrList["list"][$i]['order_state']=="4" || $arrList["list"][$i]['order_state']=="5"){
								$trColor="#f1dede";
							}
							$dis_amount[$i] = $arrList['list'][$i]['coupon_amount'] + $arrList['list'][$i]['using_point'];
					?>
						<tr>
							<td><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>
							<td><i class="mo_vw">주문일시</i><?=$arrList['list'][$i]['order_date']?></td>
							<td><i class="mo_vw">주문번호</i><a href="order_detail.php?order_no=<?=$arrList["list"][$i]['order_no']?>"><?=$arrList["list"][$i]['order_no']?></a></td>
							<td><i class="mo_vw">주문자(ID)</i><a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList["list"][$i]['order_id']?>"><?=$arrList["list"][$i]['order_name']?>(<?=$arrList["list"][$i]['order_id']?>)</a></td>
							<td><i class="mo_vw">상호명</i><?=$arrList["list"][$i]['order_cname']?><?=$arrList["list"][$i]['order_cust']?"<br>(".$arrList["list"][$i]['order_cust'].")":""?></td>
							<td><i class="mo_vw">주문상품</i><?=$arrList['list'][$i]['order_summary']?></td>
							<td><i class="mo_vw">수취인</i><?=$arrList['list'][$i]['ship_name']?></td>
							<td><i class="mo_vw">운송장번호</i><?=$arrList['list'][$i]['shipping_no']?></td>
							<td><i class="mo_vw">발송일시</i><?=$arrList['list'][$i]['shipping_date']?></td>
							<td><i class="mo_vw">진행상태</i><?=$_SITE["SHOP"]["ORDER_STATE"][$arrList["list"][$i]['order_state']]?></td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="order_detail.php?order_no=<?=$arrList['list'][$i]['order_no']?>&<?=$_SERVER['QUERY_STRING']?>" class="btn modi">수정</a>
<!-- 									<button type="button" class="btn del" onclick="delOrder('<?=$arrList['list'][$i]['order_no']?>')">삭제</button> -->
								</div>
							</td>
						</tr>
					<?
						}
					}else{
						echo '<tr height="100"><td width="100%" colspan="14" >검색된 데이터가 없습니다.</td></tr>';
					}
					?>
					</tbody>
				<?}else if($_GET['mode']=="2"){ ##################################################### 취소/교환/반품?>
					<colgroup class="pc_vw">
						<col class="w2p">
						<col class="w4p">
						<col class="w8p">
						<col class="w12p">
						<col class="w6p">
						<col class="w12p">
						<col class="w12p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w6p">
						<col class="w8p">
					</colgroup>
					<thead>
						<tr>		
							<th><label class="check notxt"><input type="checkbox" name="" id="allCheck"><i></i></label></th>
							<th class="pc_vw">No.</th>
							<th class="pc_vw">주문일시</th>
							<th class="pc_vw">주문번호</th>
							<th class="pc_vw">주문자(ID)</th>
							<th class="pc_vw">상호명(거래처등록번호)</th>

							<th class="pc_vw">주문상품</th>
							<th class="pc_vw">결제방법</th>
							<th class="pc_vw">배송희망일</th>
							<th class="pc_vw">처리일시</th>						
							<th class="pc_vw">진행상태</th>
							<th class="pc_vw">관리</th>
						</tr>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							//합계금액 계산
							$totalPrice = $arrList["list"][$i]['total_amount']+$arrList["list"][$i]['ship_amount'];
							$arrQtyList = getOrderGoodList($arrList["list"][$i]['order_no']);							
							$trColor="#fffff";
							if($arrList["list"][$i]['order_state']=="2" || $arrList["list"][$i]['order_state']=="3" || $arrList["list"][$i]['order_state']=="4" || $arrList["list"][$i]['order_state']=="5"){
								$trColor="#f1dede";
							}
							$dis_amount[$i] = $arrList['list'][$i]['coupon_amount'] + $arrList['list'][$i]['using_point'];
					?>
						<tr>
							<td><label class="check notxt"><input type="checkbox" value="<?=$arrList["list"][$i]['idx']?>" name="chk_list"><i></i></label></td>
							<td><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>
							<td><i class="mo_vw">주문일시</i><?=$arrList['list'][$i]['order_date']?></td>
							<td><i class="mo_vw">주문번호</i><a href="order_detail.php?order_no=<?=$arrList["list"][$i]['order_no']?>" class="linktxt"><?=$arrList["list"][$i]['order_no']?></a></td>
							<td><i class="mo_vw">주문자(ID)</i><a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList["list"][$i]['order_id']?>" class="linktxt"><?=$arrList["list"][$i]['order_name']?>(<?=$arrList["list"][$i]['order_id']?>)</a></td>
							<td><i class="mo_vw">상호명</i><?=$arrList["list"][$i]['order_cname']?><?=$arrList["list"][$i]['order_cust']?"<br>(".$arrList["list"][$i]['order_cust'].")":""?></td>
							<td><i class="mo_vw">주문상품</i><?=$arrList['list'][$i]['order_summary']?></td>
							<td><i class="mo_vw">결제방법</i><?=$_SITE["SHOP"]["PAY_TYPE"][$arrList["list"][$i]['pay_type']]?></td>
							<td><i class="mo_vw">발송일시</i><?=$arrList['list'][$i]['shipping_date']?></td>
							<td><i class="mo_vw">처리일시</i><?=substr($arrList['list'][$i]['claim_date'],0,10)?></td>
							<td><i class="mo_vw">진행상태</i><?=$_SITE["SHOP"]["ORDER_STATE"][$arrList["list"][$i]['order_state']]?></td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="order_detail.php?order_no=<?=$arrList['list'][$i]['order_no']?>&<?=$_SERVER['QUERY_STRING']?>" class="btn modi">수정</a>
<!-- 									<button type="button" class="btn del" onclick="delOrder('<?=$arrList['list'][$i]['order_no']?>')">삭제</button> -->
								</div>
							</td>
						</tr>
					<?
						}
					}else{
						echo '<tr height="100"><td width="100%" colspan="10" >검색된 데이터가 없습니다.</td></tr>';
					}
					?>
					</tbody>
				<?}?>
				</table>
			</div>
		</div>

		<div class="bdr_btm">
			<span class="infotxt wd120" style="margin-left:20px;">총 선택 수 : <span class="redtxt wetxt" id="checkNum">0개</span></span>
			<div class="btns">
				<?
				
				?>
				<a href="javascript:void(0);" onclick="fnERPSend('<?=ecount_session_id()?>')" class="btn btn">ERP 전송</a>			
			</div>
			
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
				<!--<select name="rstate" id="rstate">
						  <?
						  foreach ($_SITE["SHOP"]["ORDER_STATE"] AS $key => $val){
							  if($key<20){
						  ?>
						  <option value="<?=$key?>"<?=$arrInfo["list"][0]["order_state"]==$key?" selected":""?>><?=$val?></option>
						  <?}}?>
					  </select>	
				<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택변경</a>
			-->
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
		//체크박스
	var $allCheck = $('#allCheck');
	$allCheck.change(function () {
		var $this = $(this);
		var checked = $this.prop('checked');
		$('input[name="chk_list"]').prop('checked', checked);
		var checkedLength = $('input[name="chk_list"]:checked').length;
		$("#checkNum").text(checkedLength+"개");
	});
	var boxes = $('input[name="chk_list"]');
	boxes.change(function () {		
		var boxLength = boxes.length;
		var checkedLength = $('input[name="chk_list"]:checked').length;
		var selectallCheck = (boxLength == checkedLength);
		$allCheck.prop('checked', selectallCheck);
		$("#checkNum").text(checkedLength+"개");
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