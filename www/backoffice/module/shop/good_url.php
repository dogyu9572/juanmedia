<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
if(!in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//전체 카테고리 가져오기
$arrAllCategory = getCategoryAll();

if($_REQUEST['idx']){
	$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));
	$arrExtCat = getGoodExtCat(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));
	$arrExtSearch = getGoodExtSearch(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));
}

//카테고리 정보
$arrCatCode = explode("/", $arrInfo["list"][0]["cat_code"]);

//분류 리스트
$arrCategory1 = getCategoryList(2,'Y');			//1차카테고리
if($_REQUEST['idx']){
	$arrCategory2 = getCategoryList($arrCatCode[1]);//2차
}
$arrBrandList = getCategoryList(3,'Y');			//브랜드

######################################################### 등록된 예약정보 ######################################################### ST
$subQuery = " AND reserve_state in ('11','12','13') AND order_no IN (SELECT order_no FROM tbl_shop_order_good WHERE g_idx='".$_REQUEST['idx']."') order by idx asc";
$arrOrderList = getFreeView("tbl_shop_order_info", $subQuery, $col="*", $scale=0, $offset=0, $orderBy="");
######################################################### 등록된 예약정보 ######################################################### ED
?>
<!--<script type="text/javascript" src="/common/js/fancybox/source/jquery.fancybox.js"></script>
<script type="text/javascript" src="/backoffice/js/myjs.js"></script>
<script type="text/javascript" src="/common/js/prototype-1.6.0.3-euc-kr.js"></script>
<script type="text/javascript" src="/common/js/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="/common/js/scriptaculous/effects.js"></script>
<script type="text/javascript" src="/common/js/layer.js"></script>
<script type="text/javascript" src="/common/js/shop.js"></script>-->
<script type="text/javascript" src="/common/js/layer.js"></script><?#### 이미지 레이어 관련 팝업 설정?>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$.each($('input.calendar'), function() {
		set_datepicker($(this));
	});	
	// 숫자만 입력
	$(".numberOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9]/g,""));
	});

	
});	
function fnGoodSelect(stridx, inputName){			
	if(stridx){ $(".is-close-btn").click();	}	
	$.post("/module/shop/ajax_order_goodurl.php", { memberidx : stridx, gidx: "<?=$_REQUEST['idx']?>"},
		function(data){
			if(data){
			//	alert(data);
				location.reload();
			}else{
				alert("실패.");
			}
		}
	);
}
////////////////////////////////////////////////////  아래 사용확인
function fnGoodPrint(inputName, orderby){
	var msds = $("input[name='"+inputName+"']").val();
	var listName = "#msdslist1";

	if(msds<1){
		$(listName).html('<tr><td colspan="5" style="text-align:center;padding:14px 0;">등록된 데이터가 없습니다.</td></tr>');				
	}else{
		$.post("/module/board/ajax_goodurl_list.php", { bid: 'tbl_member', idx : msds, fname: inputName, orderby: orderby},
			function(data){
				if(data){
				//	alert(data);
					$(listName).html(data);				
				}else{
					alert("실패.");
				}
			}
		);
	}
}
function fnAddDel(stridx, inputName){
	var msds = $("input[name='"+inputName+"']").val();
	var arrMsds = msds.split(',');
	var reArrMsds = "";
	var comma = "";
	for(var i=0; i<arrMsds.length;i++){
		if(arrMsds[i]!=stridx){
			reArrMsds += comma+arrMsds[i]
			comma = ",";
		}
	}
	$("input[name='"+inputName+"']").val(reArrMsds);
	fnGoodPrint(inputName);
}
//-->
</script>
<?######################################### iframe fancybox ######################################### ST?>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
<style type="text/css">
.fancybox__content { padding: 5px 0;border-radius: 4px; }
.fancybox__slide {padding-bottom:20px;}
</style>
<script type="text/javascript">
<!--
function OpenApplyView(fname)
{
	//	var requestUrl = "/backoffice/module/board/pop_board_view.php?boardid=tbl_member&fname="+fname;
	var requestUrl = "/backoffice/module/member/pop_member.php?fname="+fname;
	Fancybox.show([
	{
		src: requestUrl,
		type: "iframe",
		preload: false,
		width: 1100,
		height: 700
	},
	]);
}	
//-->
</script>
<?######################################### iframe fancybox ######################################### ED?>
<script type="text/javascript">
<!--
function fnOrderState(th, ostt, orderNo){
	var thval = $(th).val();
	if(thval=="11" || thval=="13"){
		// ajax orderNo/
		if(confirm("해당 주문의 결제방식을 변경합니다. 계속하시겠습니까?")){
			$.post("/module/board/ajax_edit_yn.php", { tblid: 'tbl_shop_order_info', rval : thval, rname: 'reserve_state', ridx: orderNo, editr :'order_no'},
				function(data){
					if(data){
						//alert(data);	
						location.reload();
					}else{
						alert("실패.");
					}
				}
			);
		}
	}else{
		alert('해당 주문의 결제방식은 변경할 수 없습니다.');
		$(th).val(ostt).prop("selected",true);
		return;
	}
}
function fnOrderDel(orderNo){
	if(confirm("해당 주문을 삭제합니다. 계속하시겠습니까?")){		
		$.post("/module/shop/ajax_order_evn.php", { evnMode: 'delete', order_no: orderNo},
			function(data){
				if(data){
					//alert(data);	
					location.reload();
				}else{
					alert("실패.");
				}
			}
		);
	}
}
function fnOrderCopy(orderNo){
	//alert(orderNo)
	var url = '';
	var textarea = document.createElement("textarea");
	document.body.appendChild(textarea);
	url = 'http://<?=$_SERVER['SERVER_NAME']?>/member/payment_solo.php?orderno='+orderNo;
	textarea.value = url;
	textarea.select();
	document.execCommand("copy");
	document.body.removeChild(textarea);
	alert("URL이 복사되었습니다.")
}
function fnOrderSave(orderNo, frm){
	if(frm.total_amount.value < 1){
		alert('결제금액을 입력하세요.');
		frm.total_amount.focus();
		return;
	}
	if(!frm.re_amount.value){
		alert('다음 결제금액을 입력하세요.');
		frm.re_amount.focus();
		return;
	}
	//alert(frm.pay_amount.value);
	frm.submit();
}
//-->
</script>
<div class="container">

	<div class="title">구매 URL</div>
	
	<div class="inbox write_tbl mo_break_write">
		<div class="tit">상품정보<i>*</i></div>
		<table>
			<tr>					
				<td>					
					<div class="bdr_list tac" style="width:100%;board:1px">
						<table>
							<colgroup>									
								<col width="10%">
								<col width="15%">
								<col width="10%">
								<col width="20%">
								<col width="10%">
								<col width="10%">
								<col width="10%">
								<col width="10%">
							</colgroup>
							<thead>
								<tr>							
									<th style="text-align:center;padding:20px 0;">이미지</th>
									<th style="text-align:center;padding:20px 0;">카테고리</th>
									<th style="text-align:center;padding:20px 0;">브랜드</th>
									<th style="text-align:center;padding:20px 0;">상품명</th>
									<th style="text-align:center;padding:20px 0;">상품코드</th>
									<th style="text-align:center;padding:20px 0;">소비자가</th>
									<th style="text-align:center;padding:20px 0;">판매가</th>
									<th style="text-align:center;padding:20px 0;">판매상태</th>
								</tr>
							</thead>
							<tbody>
							<?
							if($arrInfo["files"][0]['re_name']) {
								$simg = "<img src=\"/uploaded/shop_good/".$arrInfo["list"][0]['idx']."/".$arrInfo["files"][0]['re_name']."\" style='width:100px;'>";
							} else {
								$simg = "";
							}
							$arrThisCatCode = explode("/", $arrInfo["list"][0]['cat_code']);
							############################################ 판매상태 ######################################## ST
							if($arrInfo['list'][0]['stock_type']=="1"){								
								if($arrInfo['list'][0]['stock']<1){
									$arrInfo['list'][0]['stock_type_txt'] = "판매완료";									
								}else{
									$arrInfo['list'][0]['stock_type_txt'] = "판매중";
								}
							}else if($arrInfo['list'][0]['stock_type']=="2"){
								$arrInfo['list'][0]['stock_type_txt'] = "판매완료";
							}else if($arrInfo['list'][0]['stock_type']=="4"){
								$arrInfo['list'][0]['stock_type_txt'] = "미노출";
							}
							############################################ 판매상태 ######################################## ED
							?>
								<tr>							
									<td><?=$simg?></td>
									<td><?for($j=1; $j <count($arrThisCatCode)-1;$j++){	echo $arrAllCategory[$arrThisCatCode[$j]]; if($j < count($arrThisCatCode)-2){ echo " &gt; "; } }?></td>
									<td><?=$arrAllCategory[$arrInfo['list'][0]['brand']]?></td>
									<td><?=$arrInfo['list'][0]['g_name']?></td>
									<td><?=$arrInfo['list'][0]['g_code']?></td>
									<td><?=number_format($arrInfo['list'][0]['p_price'])?></td>
									<td><?=number_format($arrInfo['list'][0]['price'])?></td>
									<td><?=$arrInfo['list'][0]['stock_type_txt']?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</table>
		<div class="tit">기본정보<?=date("Y-m-d",strtotime("+30 day", time()));?> <i>*</i></div>
		<table>
			<tr>
				<td>
					<div class="btns" style="height:30px;margin-top:0;margin-bottom:10px; justify-content: left">
						<a href="javascript:void(0);" class="btn" onclick="OpenApplyView('joinidxs')">회원 검색</a>
					</div>
					<div class="bdr_list tac" style="width:100%;board:1px">
						<table>
							<colgroup>									
								<col width="10%">
								<col width="10%">
								<col width="10%">
								<col width="15%">
								<col width="10%">
								<col width="10%">
								<col width="8%">
								<col width="6%">
								<col width="10%">
								<col width="6%">
								<col width="6%">
								<col width="13%">
							</colgroup>
							<thead>
								<tr>							
									<th style="text-align:center;padding:20px 0;">결제방식</th>
									<th style="text-align:center;padding:20px 0;">이름(ID)</th>
									<th style="text-align:center;padding:20px 0;">연락처</th>
									<th style="text-align:center;padding:20px 0;">이메일</th>
									<th style="text-align:center;padding:20px 0;">예약금</th>
									<th style="text-align:center;padding:20px 0;">잔금</th>
									<th style="text-align:center;padding:20px 0;">가격(회원특가)</th>
									<th style="text-align:center;padding:20px 0;">등록일</th>
									<th style="text-align:center;padding:20px 0;">만료(예정)</th>
									<th style="text-align:center;padding:20px 0;">진행</th>
									<th style="text-align:center;padding:20px 0;">알림톡</th>
									<th style="text-align:center;padding:20px 0;">관리</th>
								</tr>
							</thead>
							<tbody id="msdslist1">
							<?
							if($arrOrderList['total']>0){
								for($i=0; $i < $arrOrderList["total"]; $i++){
							?>
								<form name="frmInfo<?=$i?>" method="post" action="good_evn.php" ENCTYPE="multipart/form-data">
								<input type="hidden" name="evnMode" value="good_update">
								<input type="hidden" name="order_no" value="<?=$arrOrderList['list'][$i]['order_no']?>">
								<input type="hidden" name="rt_url" value="/backoffice/module/shop/good_url.php?idx=<?=$_REQUEST['idx']?>">
								<tr>
									<td><select name="reserve_state" style="width:100px;" onchange="fnOrderState(this,'<?=$arrOrderList['list'][$i]['reserve_state']?>','<?=$arrOrderList['list'][$i]['order_no']?>')">
										<option value="11" <?=$arrOrderList['list'][$i]['reserve_state']=="11"?"selected":""?>><?=$_SITE["SHOP"]["RESERVE_STATE"]["11"]?></option>
										<option value="12" <?=$arrOrderList['list'][$i]['reserve_state']=="12"?"selected":""?>><?=$_SITE["SHOP"]["RESERVE_STATE"]["12"]?></option>
										<option value="13" <?=$arrOrderList['list'][$i]['reserve_state']=="13"?"selected":""?>><?=$_SITE["SHOP"]["RESERVE_STATE"]["13"]?></option>
									</select></td>
									<td><?=$arrOrderList['list'][$i]['order_name']?>(<?=$arrOrderList['list'][$i]['order_id']?>)
									<br/><?=$arrOrderList['list'][$i]['order_no_parent']?$arrOrderList['list'][$i]['order_no_parent']:$arrOrderList['list'][$i]['order_no']?>
									</td>
									<td><?=$arrOrderList['list'][$i]['order_mobile']?></td>
									<td><?=$arrOrderList['list'][$i]['order_email']?></td>
									<td><input type="text" class="w2 numberOnly" name="total_amount" maxlength="20" value="<?=$arrOrderList['list'][$i]['total_amount']?>" style="text-align:right;"></td>
									<?
									if($arrOrderList['list'][$i]['reserve_state']=="13"){
										$arrOrderList['list'][$i]['re_amount']="0";
									?>
									<td><input type="text" class="w2 numberOnly" name="re_amount" maxlength="20" value="<?=$arrOrderList['list'][$i]['re_amount']?>" style="text-align:right;background:#eeeeee" readonly></td>
									<?}else{?>
									<td><input type="text" class="w2 numberOnly" name="re_amount" maxlength="20" value="<?=$arrOrderList['list'][$i]['re_amount']?>" style="text-align:right;"></td>
									<?}?>
									<td><?=number_format($arrOrderList['list'][$i]['total_amount']+$arrOrderList['list'][$i]['re_amount'])?></td>
									<td><?=substr($arrOrderList['list'][$i]['order_date'],0,10)?></td>
									<td><input type="text" class="w2 <?=$arrOrderList['list'][$i]['reserve_state']=="12"?"":"datepicker"?>" name="ipkum_date" maxlength="10" value="<?=$arrOrderList['list'][$i]['ipkum_date']?>" <?=$arrOrderList['list'][$i]['reserve_state']=="12"?"readonly style='background:#eeeeee;'":""?>></td>
									<td><?=$_SITE["SHOP"]["ORDER_STATE"][$arrOrderList['list'][$i]['order_state']]?></td>
									<td><label class="check"><input type="checkbox" name="mail_sms" value="Y"><i></i></label></td>
									<td>
										<div class="btns">
										<a href="javascript:void(0);" class="btn modi" onclick="fnOrderSave('<?=$arrOrderList['list'][$i]['order_no']?>',document.frmInfo<?=$i?>)">저장</a>
										<a href="javascript:void(0);" class="btn perf" onclick="fnOrderCopy('<?=$arrOrderList['list'][$i]['order_no']?>')">복사</a>
										<a href="javascript:void(0);" class="btn del" onclick="fnOrderDel('<?=$arrOrderList['list'][$i]['order_no']?>')">삭제</a>
										</div>
									</td>
								</tr>
								<?if($arrOrderList['list'][$i]['reserve_state']!="14"){?>
								<tr>
									<td style="font-weight: bold;">비고</td>
									<td colspan="11">
										<textarea id="admin_comment" name="admin_comment" style="width:90%;height:40px;padding:10px;"><?=$arrOrderList['list'][$i]['admin_comment']?></textarea>
									</td>
								</tr>
								<?}?>
								</form>
							<?
								}
							}else{
								echo "<tr><td colspan='12'>등록된 데이터가 없습니다.</td></tr>";
							}
							?>
							</tbody>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div> <!-- //inbox -->
</div>
<div id="layerImageShow" style="position:absolute; display:none; background-color:#FFFFFF; border-size:3px;bordercolor:#CCCCCC"></div>
<iframe id="iframeRelGood" name="iframeRelGood" border="0" width="0" height="0"></iframe>
<iframe id="iframeHidden" name="iframeHidden" border="0" width="0" height="0"></iframe>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>
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
//파일선택
	$(".searchfile").on('change',function(){
		val = $(this).val().split("\\");
		f_name = val[val.length-1]; 
		s_name = f_name.substring(f_name.length-4, f_name.length);
		$(this).parent().siblings('.filebox').html(f_name);
	});
//
	var $allCheck = $('#allCheck');
	$allCheck.change(function () {
		var $this = $(this);
		var checked = $this.prop('checked');
		$('.opt_checkbox').prop('checked', checked);
	});
});
//]]>
</script>