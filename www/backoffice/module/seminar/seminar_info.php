<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
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

for($j=0;$j<$arrExtCat["total"];$j++){
	$arrExtCatCode = explode("/", $arrExtCat["list"][$j]["cat_code"]);	
	if(in_array("187",$arrExtCatCode)){	## 국비지원 GATE A : 187
		//	$arrInfo["list"][0]["price"] = 0;
		$supportFlag = "Y";		## 국비지원
	}
}

if( !$arrInfo["list"][0]['g_code'] ){
	$arrInfo["list"][0]['g_code']="SMN".date("YmdHis").rand(100,999);
}
if( !$arrInfo["list"][0]['price'] ){
	$arrInfo["list"][0]['price']="0";
}

//카테고리 정보
$arrCatCode = explode("/", $arrInfo["list"][0]["cat_code"]);

//분류 리스트
$arrCategory1 = getCategoryList(0);//1차카테고리
if($_REQUEST['idx']){
	$arrCategory2 = getCategoryList($arrCatCode[0]);//2차
	$arrCategory3 = getCategoryList($arrCatCode[1]);//3차
}

################################ 대기자 판별 ################################ ST
$Query = "SELECT * FROM tbl_shop_order_good AS a
LEFT JOIN tbl_shop_order_info AS b ON a.order_no=b.order_no
WHERE a.g_idx = '".$arrInfo["list"][0]["idx"]."' AND b.order_state='11' ";
$arrOrderList = getFreeQueryR($Query);
$sCount1 = 0;
$sCount2 = 0;
$sCount3 = 0;
$sCount4 = 0;
$sCount5 = 0;
for($i=0;$i<$arrOrderList['list']['total'];$i++){
	if($arrOrderList['list'][$i]['g_opt_1']=="1"){ $sCount1++; }
	if($arrOrderList['list'][$i]['g_opt_1']=="2"){ $sCount2++; }
	if($arrOrderList['list'][$i]['g_opt_1']=="3"){ $sCount3++; }
	if($arrOrderList['list'][$i]['g_opt_1']=="4"){ $sCount4++; }
	if($arrOrderList['list'][$i]['g_opt_1']=="5"){ $sCount5++; }
}
if($arrInfo['list'][0]['s_inwon']<$sCount1){ $sCount1 = $arrInfo['list'][0]['s_inwon']; }	## 신청인원수가 모집정원보다 많으면 모집정원수로 변경
if($arrInfo['list'][0]['s_inwon']<$sCount2){ $sCount2 = $arrInfo['list'][0]['s_inwon']; }
if($arrInfo['list'][0]['s_inwon']<$sCount3){ $sCount3 = $arrInfo['list'][0]['s_inwon']; }
if($arrInfo['list'][0]['s_inwon']<$sCount4){ $sCount4 = $arrInfo['list'][0]['s_inwon']; }
if($arrInfo['list'][0]['s_inwon']<$sCount5){ $sCount5 = $arrInfo['list'][0]['s_inwon']; }
################################ 대기자 판별 ################################ ED

################################ 대기자 인원수 확인 ################################ ST
$Query = "SELECT * FROM tbl_shop_order_good AS a
LEFT JOIN tbl_shop_order_info AS b ON a.order_no=b.order_no
WHERE a.g_idx = '".$arrInfo["list"][0]["idx"]."' AND b.order_state='12' ";
$arrWaitList = getFreeQueryR($Query);
$wCount1 = 0;
$wCount2 = 0;
$wCount3 = 0;
$wCount4 = 0;
$wCount5 = 0;
for($i=0;$i<$arrWaitList['list']['total'];$i++){
	if($arrWaitList['list'][$i]['g_opt_1']=="1"){ $wCount1++; }
	if($arrWaitList['list'][$i]['g_opt_1']=="2"){ $wCount2++; }
	if($arrWaitList['list'][$i]['g_opt_1']=="3"){ $wCount3++; }
	if($arrWaitList['list'][$i]['g_opt_1']=="4"){ $wCount4++; }
	if($arrWaitList['list'][$i]['g_opt_1']=="5"){ $wCount5++; }
}
################################ 대기자 인원수 확인 ################################ ED
?>
<script type="text/javascript" src="/common/js/layer.js"></script><?#### 이미지 레이어 관련 팝업 설정?>
<script type="text/javascript">
<!--
//카테고리 이름 자바스크립트 변수 생성
var arrayAllCategory = new Array();
<?
foreach ($arrAllCategory AS $key => $val){
?>
arrayAllCategory[<?=$key?>] = "<?=$val?>";
<?
}
?>

function fnCat1(tval){	
	var defHtml = '<select name="cat3" id="cat3" onchange="fnCat3(this.value);"><option value="">======3차분류======　　</option></select>';
	$.post("/module/shop/ajax_selectbox_category.php", { snum : '2',cat_no: tval, eventMd : '<?=$_GET['eventMd']?>' },
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
	$.post("/module/shop/ajax_selectbox_category_info.php", { snum : '3',cat_no: tval },
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
	if(cat_no3){
		cat_no = cat_no3;
	}else if(cat_no2){
		cat_no = cat_no2;
	}else{
		cat_no = cat_no1;
	}
	$("#cat_no").val(cat_no);
}
//상품분류 항목삭제
function delGoodCat(idx){
	var div_id = "#ext_cat";
	if(idx=="-1"){
		alert("삭제할 항목을 선택하세요.");
	}else{
		$("#ext_cat option:eq("+idx+")").remove();
		fnCatExt();
	}
}
//상품분류 항목추가
function addGoodCat(){
	var cat_no1 = $("#cat1 option:selected").val();
	var cat_no2 = $("#cat2 option:selected").val();
	var cat_no3 = $("#cat3 option:selected").val();
	var cat_no = "";
	if(cat_no3){
		cat_no = cat_no3;
	}else if(cat_no2){
		cat_no = cat_no2;
	}else{
		cat_no = cat_no1;
	}

	if(cat_no !=""){
		var option = $('<option value="'+cat_no+'">'+arrayAllCategory[cat_no]+'</option>');
		$('#ext_cat').append(option);
		fnCatExt();
	}else{
		alert("분류를 선택하세요.");
	}
}
//추가 상품분류 
function fnCatExt(){
	var comma = "";
	var frm = document.frmInfo;
	frm.ext_cat_hidden.value = "";
	for(j=0;j<$("#ext_cat option").length;j++){		
		frm.ext_cat_hidden.value += comma+$("#ext_cat option:eq("+j+")").val();
		comma= "|:|";		
	}
}
//이미지 상세보기 레이어
function LayerShowImage(img, e){
	layerPositionSet('layerImageShow', e);
	$('#layerImageShow').html("<a href='javascript:;' onclick='LayerHideImage();'><img src='/uploaded/shop_good/"+img+"' border=0></a>");
}
//레이어 닫기
function LayerHideImage() {
	$('#layerImageShow').hide();
}
// 입력확인
function frmCheck(frm){
	if(!frm.g_code.value){
		alert('코드를 입력하세요.');
		frm.g_code.focus();
		return;
	}
	if(!frm.g_name.value){
		alert('세미나명을 입력하세요.');
		frm.g_name.focus();
		return;
	}
	if(!frm.price.value){
		alert('가격을 입력하세요.');
		frm.price.focus();
		return;
	}
	frm.submit();
}
//-->
</script>
<script type="text/javascript">
<!--
var USDWON = Number("<?=str_replace(",","",$_SITE["USDWON"])?>");
var EURWON = Number("<?=str_replace(",","",$_SITE["EURWON"])?>");
var CHFWON = Number("<?=str_replace(",","",$_SITE["CHFWON"])?>");
var DEFWON = Number("<?=str_replace(",","",$arrInfo["list"][0]['price'])?>");
var DEFSALE = Number("<?=str_replace(",","",$_SITE["SHOP_SALE_DEF"])?>");		//기본할인율

$(document).ready(function(){
	// 숫자만 입력
	$(".numberOnly").on("keyup", function() {
		if(this.value){
			$(this).val($(this).val().replace(/[^0-9]/g,""));
		}else{
			$(this).val(0);
		}
	});
	// 전화번호 입력
	$(".phoneOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9\-]/g,""));
	});
	// 영문,숫자,특수문자만 입력
	$(".pwdCheck").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9a-zA-Z._@\-]/g,""));										  
	});
	// 영문만 입력
	$(".engOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9a-zA-Z._@\-]/g,""));
	});
	// 영문 띄어쓰기 만 입력
	$(".engName").on("keyup", function() {
		$(this).val($(this).val().replace(/[^a-zA-Z0-9\s]/gi,""));	
		$(this).val($(this).val().toUpperCase());	
	});
	// 한글만 입력
	$(".hanOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[0-9a-zA-Z!@#$%^&*()_+|\-]/g,""));										  
	});	
	// 환율계산
	fnRateAuto(DEFWON);
	$("#price").on("keyup", function() {
		var KRWWON = Number(this.value);
		fnRateAuto(KRWWON);
	});		
	// 관련상품
	fnGoodSelect('', '');	
	fnAcademicSelect('', '');	
});
// 판매가격 계산
function fnPriceAuto(){
	var p_price = Number($("#p_price").val());
	var sale	= Number($("#sale_price").val());
	var price	= 0;
	if(sale > 0){
		price = p_price*(100-sale)/100;
	}else{
		price = p_price;
	}
	$("#price").val(price);
	fnRateAuto(price);
}
// 환율계산
function fnRateAuto(KRWWON){
	var USDTXT = Math.round(KRWWON / USDWON * 100) / 100;
	$("#USD_WON").text(USDTXT.toLocaleString());

	var EURTXT = Math.round(KRWWON / EURWON * 100) / 100;
	$("#EUR_WON").text(EURTXT.toLocaleString());

	var CHFTXT = Math.round(KRWWON / CHFWON * 100) / 100;
	$("#CHF_WON").text(CHFTXT.toLocaleString());
}
// 옵션 추가 삭제
function fnOption(flag){
	var optCnt = Number($("#option_cnt").val());
	if(flag=="p"){
		if(optCnt < 100){
			optCnt++;
		}
	}else{
		if(optCnt > 1){
			optCnt--;
		}
	}
	$("#option_cnt").val(optCnt);
	for(var i=1;i<101;i++){
		if(i<=optCnt){
			$("#opt_"+i).show();
		}else{
			$("#opt_"+i).hide();
		}
	}
}
// 관련상품
function fnPlusGood(){
	OpenApplyView();
}
function fnGoodSelect(stridx, mpflag){	
	if(stridx){ $(".is-close-btn").click();	}
	var gdidx = "<?=$arrInfo["list"][0]['idx']?>";
	if(gdidx){
		$.post("/module/shop/ajax_rel_good.php", { goodidx : gdidx,relidx: stridx, evnMode: mpflag},
			function(data){
			//	alert(data);
				if(data){
					$("#relGoodList").html(data);				
				}
			}
		);
	}
}
function fnAcademicSelect(stridx, mpflag){		
	if(stridx){ $(".is-close-btn").click();	}
	var gdidx = "<?=$arrInfo["list"][0]['idx']?>";
	if(gdidx){
		$.post("/module/shop/ajax_rel_academic.php", { goodidx : gdidx,relidx: stridx, evnMode: mpflag},
			function(data){
			//	alert(data);
				if(data){
					$("#relAcademicList").html(data);				
				}
			}
		);
	}
}
function fnSave(){
	var frm = document.frmInfo;
	frm.rt_url.value = '<?=$_SERVER['REQUEST_URI']?>';
	frm.altYN.value = 'Y';
	frm.submit();
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
function OpenApplyView()
{
	Fancybox.show([
	{
		src: "/backoffice/module/shop/pop_good_seminar.php",
		type: "iframe",
		preload: false,
		width: 1100,
		height: 700
	},
	]);
}	
function fnPlusAcademic(){
	Fancybox.show([
	{
		src: "/backoffice/module/board/pop_board_view.php?boardid=academic",
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
<?######################################### color picker######################################### ST?>
<script src="/_api/_minicolors/js/jquery.minicolors.js"></script>
<link rel="stylesheet" href="/_api/_minicolors/js/jquery.minicolors.css">
<script>
$(document).ready( function() {
	$('.picker').each( function() {
		$(this).minicolors({
			control: $(this).attr('data-control') || 'hue',
			defaultValue: $(this).attr('data-defaultValue') || '',
			format: $(this).attr('data-format') || 'hex',
			keywords: $(this).attr('data-keywords') || '',
			inline: $(this).attr('data-inline') === 'true',
			letterCase: $(this).attr('data-letterCase') || 'lowercase',
			opacity: $(this).attr('data-opacity'),
			position: $(this).attr('data-position') || 'bottom',
			swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
			change: function(value, opacity) {
				if( !value ) return;
				if( opacity ) value += ', ' + opacity;
				if( typeof console === 'object' ) {
					console.log(value);
				}
			},
			theme: 'bootstrap'
		});
	});
});
</script>
<?######################################### color picker######################################### ED?>
<div class="container">

	<div class="title">세미나 <?=$arrInfo["list"][0]['idx']?"수정":"등록"?></div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmInfo" method="post" action="good_evn.php" ENCTYPE="multipart/form-data" onSubmit="return goodCheckForm(this)">
			<input type="hidden" name="evnMode" value="<?=$arrInfo["list"][0]['idx']?"edit":"insert"?>">
			<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]['idx']?>">
			<input type="hidden" name="altYN" value="N">
			<input type="hidden" name="rt_url" value="/backoffice/module/seminar/seminar_list.php?rlist=T">
			<input type="hidden" name="vip_price" value="0"><?## 사용안함 ##?>			
			<input type="hidden" name="image_type" value="1"><?## 이미지타입 사용안함 ##?>
			<input type="hidden" name="shipping_charge" value="0"><?## 배송료 사용안함 ##?>
			<input type="hidden" name="percent_point" value="0"><?## 적립금 사용안함 ##?>

			<div class="tit">기본정보 <i>*</i></div>
			<table>
				<tr>
					<th>분류(<span style="color:red;">연자 *</span>, <span style="color:red;">세미나 분류 *</span>, 키워드, 분기별)</th>
					<td>
						<div class="inputs">
							<input type="hidden" id="cat_no" name="cat_no" value="<?=$arrInfo["list"][0]['cat_no']?>">
							<div id="cat_01">
								<select name="cat1" id="cat1" onchange="fnCat1(this.value);" style="height:100px;" size="3">
									<option value="">======1차분류======　　</option>
									<?
									for($i=0;$i<$arrCategory1["total"];$i++){
										if($arrCategory1["list"][$i]['cat_no']=="4"){
									?>
									<option value="<?=$arrCategory1["list"][$i]['cat_no']?>"<?=$arrCatCode[0]==$arrCategory1["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory1["list"][$i]['cat_name']?></option>
									<?}}?>
								</select>
							</div>

							<div id="cat_02" style="padding-left:5px;">
								<select name="cat2" id="cat2" onchange="fnCat2(this.value);" style="height:100px;" size="3">
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
									<?
										}
									}
									?>
								</select>
							</div>

							<div id="cat_03" style="padding-left:5px;">
								<select name="cat3" id="cat3" onchange="fnCat3(this.value);" style="height:100px;" size="3">
									<option value="">======3차분류======　　</option>
									<?for($i=0;$i<$arrCategory3["total"];$i++){?>
									<option value="<?=$arrCategory3["list"][$i]['cat_no']?>"<?=$arrCatCode[2]==$arrCategory3["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory3["list"][$i]['cat_name']?></option>
									<?}?>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>추가 분류</th>
					<td><div class="inputs">
						<select id='ext_cat' name='ext_cat' style='width:550px;height:200px;' size="8">
						<?
						$arrExtCatHide = "";
						$comma = "";
						for($i=0;$i<$arrExtCat["total"];$i++){
							$arrExtCatCode = explode("/", $arrExtCat["list"][$i]["cat_code"]);
							$strExtCat = "";
							$arrExtCatHide .= $comma.$arrExtCat["list"][$i]["cat_no"];
							$comma = "|:|";
							for($j=0;$j<count($arrExtCatCode)-1;$j++){
								$strExtCat .= $arrAllCategory[$arrExtCatCode[$j]];								
								if($j != count($arrExtCatCode)-2){
									$strExtCat .= " > ";
								}
							}
						?>
						<option value="<?=$arrExtCat["list"][$i]["cat_no"]?>"><?=$strExtCat?></option>
						<?}?>
						</select>
						&nbsp;<a href="javascript:void(0);" onclick='addGoodCat()'><img src="/backoffice/images/k_add.gif" alt="추가" /></a>
						&nbsp;<a href="javascript:void(0);" onclick='delGoodCat(document.frmInfo.ext_cat.selectedIndex)'><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a>
						<input type='hidden' id="ext_cat_hidden" name="ext_cat_hidden" value="<?=$arrExtCatHide?>">
					</div></td>
				</tr>
				<tr style="display:none;">
					<th>코드</th>
					<td><div class="inputs"><input type="text" class="w3" name="g_code" id="g_code" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['g_code'])?>" readonly style="background:#cccccc;"></div></td>
				</tr>
				<tr>
					<th>사용기능</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="special_show" onclick="fnShow('Y')" value="Y"<?=$arrInfo["list"][0]['special_show']!="N"?" checked":""?>><i></i>참가신청</label>
						<label class="radio"><input type="radio" name="special_show" onclick="fnShow('N')" value="N"<?=$arrInfo["list"][0]['special_show']=="N"?" checked":""?>><i></i>영상구매</label> 					
					</div></td>
				</tr>
<script type="text/javascript">
<!--
function fnShow(flag){
	if(flag=="Y"){
		$(".smn").show();
		$(".mov").hide();
	}else{
		$(".smn").hide();
		$(".mov").show();	
	}
	$("input:radio[name='brand_show']").prop('checked', false);
}
//-->
</script>
				<tr>
					<th>상태</th>
					<td>
						<div class="inputs smn" <?=$arrInfo["list"][0]['special_show']!="N"?"":" style=\"display:none;\" "?>>
							<label class="radio"><input type="radio" name="brand_show" value="Y"<?=$arrInfo["list"][0]['brand_show']=="Y"?" checked":""?>><i></i>접수중</label>
							<label class="radio"><input type="radio" name="brand_show" value="N"<?=$arrInfo["list"][0]['brand_show']=="N"?" checked":""?>><i></i>접수마감</label> 					
						</div>
						<div class="inputs mov" <?=$arrInfo["list"][0]['special_show']=="N"?"":" style=\"display:none;\" "?>>
							<label class="radio"><input type="radio" name="brand_show" value="F"<?=$arrInfo["list"][0]['brand_show']=="F"?" checked":""?>><i></i>무료영상</label>
							<label class="radio"><input type="radio" name="brand_show" value="T"<?=$arrInfo["list"][0]['brand_show']=="T"?" checked":""?>><i></i>유료영상</label> 					
						</div>
					</td>
				</tr>
				<tr>
					<th>세미나명</th>
					<td><div class="inputs"><input type="text" class="w4" name="g_name" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['g_name'])?>"></div></td>
				</tr>
				<tr>
					<th>참가신청금액(영상구매금액)</th>
					<td><div class="inputs"><input type="text" class="w2 numberOnly" style="text-align:right;" id="price" name="price" maxlength="10" value="<?=stripslashes($arrInfo["list"][0]['price'])?>"><em>&nbsp;원</em>
					&nbsp;&nbsp;&nbsp;<label class="check"><input type="checkbox" id="default_sale" name="default_sale" value="Y"<?=$arrInfo["list"][0]['default_sale']!="N"?" checked":""?>><i></i>무료</label>
					</div></td>
				</tr>
				<tr>
					<th>레벨</th>
					<td><div class="inputs"><input type="text" class="w4" name="s_level" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['s_level'])?>"></div></td>
				</tr>
				<tr>
					<th>세미나시간/강의분량</th>
					<td><div class="inputs"><input type="text" class="w4" name="s_time" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['s_time'])?>"></div></td>
				</tr>
				<tr style="display:none;">
					<th>일정/수강기간</th>
					<td><div class="inputs"><input type="text" class="w4" name="s_schedule" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['s_schedule'])?>"></div></td>
				</tr>
				<tr>
					<th>장소</th>
					<td><div class="inputs"><input type="text" class="w4" name="vendor" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['vendor'])?>"></div></td>
				</tr>
				<tr style="display:none;">
					<th>정원</th>
					<td><div class="inputs"><input type="text" class="w4" name="brand" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['brand'])?>"></div></td>
				</tr>
				<tr>
					<th>코멘트</th>
					<td><textarea id="memo" name="memo" style="width:100%;height:100px;padding:10px;"><?=stripslashes($arrInfo["list"][0]['memo'])?></textarea></td>
				</tr>
				<tr>
					<th>세미나 소개</th>
					<td><textarea id="contents" name="contents"><?=stripslashes($arrInfo["list"][0]['contents'])?></textarea>
					<?
					$CKContent = "contents";
					include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
					?></td>
				</tr>
				
				<?
				$_SITE["PRODUCT"]["IMAGE_COUNT"] = 1;
				//업로드 한것
				if($arrInfo["total_files"]>0){
				for($i=0; $i < $arrInfo["total_files"]; $i++){
				?>
				<tr>
					<th>업로드된 이미지 <?=$i+1?> </th>
					<td>			
					<span onClick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["files"][$i]['re_name']?>', event);" style="cursor:pointer;">[보기]</span> &nbsp;
					<label class="check"><input type="checkbox" name="delPhoto[]" id="delPhoto1<?=$i?>" value="<?=$arrInfo["files"][$i]['idx']?>" onclick="fnSave()"><i></i>삭제</label>  &nbsp;

					<font color=blue><?//=$arrInfo["files"][$i]['re_name']==$arrInfo["list"][0]['p_image']?"대표이미지":""?></font>
				  </td>
				</tr>
				<?}?>
				<?}?>
				<?
				for($i=0; $i < intval($_SITE["PRODUCT"]["IMAGE_COUNT"] - $arrInfo["total_files"]); $i++){
				?>
				<tr>
					<th>이미지 (740*460)</th>
					<td><input type="file" name="photo_file[]" style="width:400px;" class="input" /> <label class="radio" style="display:none;"><input type="radio" name="p_image" value="<?=$i?>" id="idPhoto<?=$i?>"
					checked><i></i>대표이미지</label></td>
				</tr>
				<?
				}
				?>
				<tr>
					<th>게시여부</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="is_show" value="Y"<?=$arrInfo["list"][0]['is_show']!="N"?" checked":""?>><i></i>Y</label>
						<label class="radio"><input type="radio" name="is_show" value="N"<?=$arrInfo["list"][0]['is_show']=="N"?" checked":""?>><i></i>N</label> 
						<!--<em>(노출여부)</em>-->						
					</div></td>
				</tr>
				<tr class="mov" <?=$arrInfo["list"][0]['special_show']=="N"?"":" style=\"display:none;\" "?>>
					<th>메인노출(내게 필요한 세미나)<br/>*영상구매만 노출</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="main_show" value="Y"<?=$arrInfo["list"][0]['main_show']=="Y"?" checked":""?>><i></i>Y</label>
						<label class="radio"><input type="radio" name="main_show" value="N"<?=$arrInfo["list"][0]['main_show']!="Y"?" checked":""?>><i></i>N</label> 					
					</div></td>
				</tr>
				<tr>
					<th>메인노출(추천상품)</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="best_show" value="Y"<?=$arrInfo["list"][0]['best_show']=="Y"?" checked":""?>><i></i>Y</label>
						<label class="radio"><input type="radio" name="best_show" value="N"<?=$arrInfo["list"][0]['best_show']!="Y"?" checked":""?>><i></i>N</label> 					
					</div></td>
				</tr>
				
				<tr style="display:none">
					<th>정렬순서</th>
					<td><div class="inputs"><input type="text" class="w2 numberOnly" name="sort_num" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['sort_num'])?>"></div></td>
				</tr>
			</table>			
			<div class="tit">참가신청 정보 <i>*</i></div>
			<table>				
				<tr style="display:none;">
					<th>상품가격</th>
					<td><div class="inputs"><input type="text" class="w2 numberOnly" style="text-align:right;" id="p_price" name="p_price" maxlength="10" value="<?=stripslashes($arrInfo["list"][0]['p_price'])?>"><em>&nbsp;원</em></div></td>
				</tr>
				<tr>
					<th>신청 가능 정원</th>
					<td>
						<div class="inputs">						
							<select name="s_inwon" id="s_inwon" style="width:150px;">
								<option value="free">무제한</option>
								<?
								for($i=1;$i<16;$i++){
								?>
								<option value="<?=$i?>"<?=$i==$arrInfo["list"][0]['s_inwon']?" selected":""?>><?=$i?>명</option>
								<?}?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th>일정 01</th>
					<td><div class="inputs"><input type="text" class="w4" name="s_cal01" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['s_cal01'])?>">
					<?
					if($arrInfo["list"][0]['s_cal01']){
						if($arrInfo['list'][0]['s_inwon']>$sCount1 || $arrInfo['list'][0]['s_inwon']=="free"){	## 신청가능 
					?>
					&nbsp;<em>(신청인원 : <?=$sCount1?>명 / 정원:<?=$arrInfo['list'][0]['s_inwon']=="free"?"제한없음":$arrInfo['list'][0]['s_inwon']."명"?>)</em>
					<?
						}else{
							if($supportFlag == "Y"){	########## 국비지원인 경우 대기자 접수
								echo '&nbsp;<em>대기자 접수중 ('.$wCount1.'명)</em>';
							}else{
								echo '&nbsp;<em>신청마감</em>';
							}
						}
					}
					?>
					</div></td>
				</tr>
				<tr>
					<th>일정 02</th>
					<td><div class="inputs"><input type="text" class="w4" name="s_cal02" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['s_cal02'])?>">
					<?
					if($arrInfo["list"][0]['s_cal02']){
						if($arrInfo['list'][0]['s_inwon']>$sCount2 || $arrInfo['list'][0]['s_inwon']=="free"){	## 신청가능 
					?>
					&nbsp;<em>(신청인원 : <?=$sCount2?>명 / 정원:<?=$arrInfo['list'][0]['s_inwon']=="free"?"제한없음":$arrInfo['list'][0]['s_inwon']."명"?>)</em>
					<?
						}else{
							if($supportFlag == "Y"){	########## 국비지원인 경우 대기자 접수
								echo '&nbsp;<em>대기자 접수중 ('.$wCount2.'명)</em>';
							}else{
								echo '&nbsp;<em>신청마감</em>';
							}
						}
					}
					?>
					</div></td>
				</tr>
				<tr>
					<th>일정 03</th>
					<td><div class="inputs"><input type="text" class="w4" name="s_cal03" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['s_cal03'])?>">
					<?
					if($arrInfo["list"][0]['s_cal03']){
						if($arrInfo['list'][0]['s_inwon']>$sCount3 || $arrInfo['list'][0]['s_inwon']=="free"){	## 신청가능 
					?>
					&nbsp;<em>(신청인원 : <?=$sCount3?>명 / 정원:<?=$arrInfo['list'][0]['s_inwon']=="free"?"제한없음":$arrInfo['list'][0]['s_inwon']."명"?>)</em>
					<?
						}else{
							if($supportFlag == "Y"){	########## 국비지원인 경우 대기자 접수
								echo '&nbsp;<em>대기자 접수중 ('.$wCount3.'명)</em>';
							}else{
								echo '&nbsp;<em>신청마감</em>';
							}
						}
					}
					?>
					</div></td>
				</tr>
				<tr>
					<th>일정 04</th>
					<td><div class="inputs"><input type="text" class="w4" name="s_cal04" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['s_cal04'])?>">
					<?
					if($arrInfo["list"][0]['s_cal04']){
						if($arrInfo['list'][0]['s_inwon']>$sCount4 || $arrInfo['list'][0]['s_inwon']=="free"){	## 신청가능 
					?>
					&nbsp;<em>(신청인원 : <?=$sCount4?>명 / 정원:<?=$arrInfo['list'][0]['s_inwon']=="free"?"제한없음":$arrInfo['list'][0]['s_inwon']."명"?>)</em>
					<?
						}else{
							if($supportFlag == "Y"){	########## 국비지원인 경우 대기자 접수
								echo '&nbsp;<em>대기자 접수중 ('.$wCount4.'명)</em>';
							}else{
								echo '&nbsp;<em>신청마감</em>';
							}
						}
					}
					?>
					</div></td>
				</tr>
				<tr>
					<th>일정 05</th>
					<td><div class="inputs"><input type="text" class="w4" name="s_cal05" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['s_cal05'])?>">
					<?
					if($arrInfo["list"][0]['s_cal05']){
						if($arrInfo['list'][0]['s_inwon']>$sCount5 || $arrInfo['list'][0]['s_inwon']=="free"){	## 신청가능 
					?>
					&nbsp;<em>(신청인원 : <?=$sCount5?>명 / 정원:<?=$arrInfo['list'][0]['s_inwon']=="free"?"제한없음":$arrInfo['list'][0]['s_inwon']."명"?>)</em>
					<?
						}else{
							if($supportFlag == "Y"){	########## 국비지원인 경우 대기자 접수
								echo '&nbsp;<em>대기자 접수중 ('.$wCount5.'명)</em>';
							}else{
								echo '&nbsp;<em>신청마감</em>';
							}
						}
					}
					?>
					</div></td>
				</tr>
			</table>			
			<div class="tit">영상구매 정보 <i>*</i></div>
			<table>	
				<tr>
					<th>수강기간</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="s_time_radio" value="평생"<?=$arrInfo["list"][0]['s_time_radio']!="30일"?" checked":""?>><i></i>제한없음</label>
						<label class="radio"><input type="radio" name="s_time_radio" value="30일"<?=$arrInfo["list"][0]['s_time_radio']=="30일"?" checked":""?>><i></i>30일</label> 	
						<?######################### s_inwon 을 같이 사용 영상구매일 경우는 s_inwon_radio로 값선택 ?>
					</div></td>
				</tr>
				<tr>
					<th>세미나 자료</th>
					<?if($arrInfo["list"][0]['etc_file_1']){?>
					<td><a href="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['etc_file_1']?>" target="_blank"><?=$arrInfo["list"][0]['etc_file_fn_1']?></a>
					&nbsp;&nbsp;<label class="check"><input type="checkbox" name="etc_file_1_del" value="Y" onclick="fnSave()"><i></i>삭제</label><input type="file" name="etc_file_1" style="display:none;" class="input" />
					</td>
					<?}else{?>
					<td><input type="file" name="etc_file_1" style="width:400px;" class="input" /></td>
					<?}?>
				</tr>
				<tr style="<?=$arrInfo["list"][0]['idx']?"display:none;":""?>">					
					<th>강의등록</th>
					<td>※ 강의등록은 세미나 등록 후 수정 화면에서 등록하실 수 있습니다.</td>
				</tr>
				<tr style="<?=$arrInfo["list"][0]['idx']?"":"display:none;"?>">
					<input type="hidden" name="rel_a_idx" value="">
					<th>강의등록</th>
					<td>
						<div class="btns" style="height:30px;margin-top:0;margin-bottom:10px;">
							<a href="javascript:void(0);" class="btn" onclick="fnPlusAcademic()">+추가</a>
						</div>
						<div class="bdr_list tac" style="width:812px;board:1px">
							<table>
								<colgroup>									
									<col width="15%">
									<col width="40%">
									<col width="20%">
									<col width="15%">
									<col width="10%">
								</colgroup>
								<thead>
								<tr>							
									<th style="text-align:center;padding:14px 0;">연자명</th>
									<th style="text-align:center;padding:14px 0;">강의명</th>
									<th style="text-align:center;padding:14px 0;">영상시간</th>
									<th style="text-align:center;padding:14px 0;">정렬</th>
									<th style="text-align:center;padding:14px 0;">관리</th>
								</tr>
								<tbody id="relAcademicList">		
								<?
								##	$arrPlusGood = explode("|",$arrInfo["list"][0]['rel_a_idx']);
								?>
								<tr>							
									<td colspan="5">연결된 강의가 없습니다.</td>	
								</tr>								
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				
				
			</table>
			
			<div class="tit">관련상품 등록 <i>*</i></div>
			<table>
				<tr style="<?=$arrInfo["list"][0]['idx']?"display:none;":""?>">
					<th>※ 관련상품 등록은 세미나 등록 후 수정 화면에서 등록하실 수 있습니다.</th>
				</tr>
				<tr style="<?=$arrInfo["list"][0]['idx']?"":"display:none;"?>">
					<input type="hidden" name="rel_g_idx" value="">
					<th>상품등록</th>
					<td>
						<div class="btns" style="height:30px;margin-top:0;margin-bottom:10px;">
							<a href="javascript:void(0);" class="btn" onclick="fnPlusGood()">+추가</a>
						</div>
						<div class="bdr_list tac" style="width:812px;board:1px">
							<table>
								<colgroup>									
									<col width="18%">
									<col width="12%">
									<col width="60%">
									<col width="10%">
								</colgroup>
								<thead>
								<tr>							
									<th style="text-align:center;padding:14px 0;">상품코드</th>
									<th style="text-align:center;padding:14px 0;" colspan="2">상품명/옵션명</th>
									<th style="text-align:center;padding:14px 0;">관리</th>
								</tr>
								<tbody id="relGoodList">		
								<?
								$arrPlusGood = explode("|",$arrInfo["list"][0]['rel_g_idx']);
								?>
								<tr>							
									<td colspan="4">연결된 상품이 없습니다.</td>	
								</tr>								
								</tbody>
							</table>
						</div>
					</td>
				</tr>
			</table>		
			<?
			################################ 목록보기
			$queryString = explode("&",$_SERVER['QUERY_STRING']);
			$reQueryString = "";
			$comma = "";
			for($i=0;$i<count($queryString);$i++){
				if(strpos($queryString[$i],"idx=")===false){
					$reQueryString .= $comma.$queryString[$i];
					$comma = "&";
				}
			}
			?>
			<div class="btns">
				<a href="/backoffice/module/seminar/seminar_list.php?<?=$reQueryString?>" class="btn btn_list">목록보기</a>
				<button class="btn btn_save" type="button" onclick="frmCheck(document.frmInfo)">저장</button>
			</div>
		</form>
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
});
//]]>
</script>