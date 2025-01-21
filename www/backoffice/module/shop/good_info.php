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

$tmpCount = getFreeQueryR("select max(idx) as cnt from tbl_shop_good");

$arrBusinessList = getBoardListBase("business", "", "", "", 0, 0, "");

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
$arrBrandList = getCategoryList(3,'Y');			//회원등급
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
	if(!tval){ return; }
	if(tval=="2" && "<?=$_GET['eventMd']?>"=="Y"){
		// 중복검사
		var inFlag = true;
		for(j=0;j<$("#ext_cat option").length;j++){		
			if($("#ext_cat option:eq("+j+")").val()=="11"){
				inFlag = false;
			}
		}
		if(inFlag){
			var option = $('<option value="11">GATE C > 행사상품</option>');
			$('#ext_cat').append(option);		
		}
	}
	if(tval=="3" && "<?=$_GET['eventMd']?>"=="Y"){
		// 중복검사
		var inFlag = true;
		for(j=0;j<$("#ext_cat option").length;j++){		
			if($("#ext_cat option:eq("+j+")").val()=="17"){
				inFlag = false;
			}
		}
		if(inFlag){
			var option = $('<option value="17">GATE L > 행사상품</option>');
			$('#ext_cat').append(option);		
		}
	}


	$("#cat_02").html(' ');
	//var defHtml = '<select name="cat3" id="cat3" onchange="fnCat3(this.value);" style="height:100px;" size="3"><option value="">======3차분류======　　</option></select>';
	$.post("/module/shop/ajax_selectbox_category_info.php", { snum : '2',cat_no: tval, eventMd : '<?=$_GET['eventMd']?>' },
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
	if(!tval){ return; }
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
	//alert(cat_no);
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
	// 중복검사
	for(j=0;j<$("#ext_cat option").length;j++){		
		if($("#ext_cat option:eq("+j+")").val()==cat_no){
			alert('해당 분류는 이미 추가되어 있습니다.');
			return;
		}
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
	if(!frm.g_name.value){
		alert('상품명을 입력하세요.');
		frm.g_name.focus();
		return;
	}
	if(!frm.g_code.value){
		alert('상품코드를 입력하세요.');
		frm.g_code.focus();
		return;
	}
	if(!frm.p_price.value){
		alert('단가를 입력하세요.');
		frm.p_price.focus();
		return;
	}	
	frm.submit();
}
function fnSave(){
	var frm = document.frmInfo;
	frm.rt_url.value = '<?=$_SERVER['REQUEST_URI']?>';
	frm.altYN.value = 'Y';
	frm.submit();
}
//-->
</script>
<script type="text/javascript">
<!--
var USDWON = Number("<?=str_replace(",","",$_SITE["USDWON"])?>");
var EURWON = Number("<?=str_replace(",","",$_SITE["EURWON"])?>");
var CHFWON = Number("<?=str_replace(",","",$_SITE["CHFWON"])?>");
var JPYWON = Number("<?=str_replace(",","",$_SITE["JPYWON"])?>");

var DEFWON = Number("<?=str_replace(",","",$arrInfo["list"][0]['price'])?>");
var DEFSALE = Number("<?=str_replace(",","",$_SITE["SHOP_SALE_DEF"])?>");		//기본할인율

var ptype = "KRW";	// 기초 단위 (원화)

$(document).ready(function(){
	// 숫자만 입력
	$(".numberOnly").on("keyup", function() {
		if(this.value){
			var num = $(this).val().replace(/[^0-9.]/g,"");
			$(this).val(Number(num));
		}else{
			$(this).val(0);
		}
	});
	// 숫자 마이너스까지 입력
	$(".numberOnly2").on("keyup", function() {
		if(this.value){
			$(this).val($(this).val().replace(/[^0-9.\-]/g,""));
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

	// 판매가격 계산
	$("#p_price").on("keyup", function() {
		fnPriceAuto();
	});
	// 판매가격 계산
	$(".member_price").on("keyup", function() {
		fnPriceAuto();
	});
});
// 판매가격 계산
function fnPriceAuto(){
	var p_price = Number($("#p_price").val());	// 소비자가	
	for(i=0;i<$(".member_price").length;i++){
		var price	= Number($(".member_price").eq(i).val());	// 등급별 판매가
		var sale_price = 0;
		var c_price = p_price - price;

		if(p_price > 0 && price > 0){
			sale_price = Math.floor(c_price/p_price*100);
		}
//		alert(sale_price);
		if(sale_price>0){
			$(".member_sale").eq(i).val(sale_price.toLocaleString());
		}else{
			$(".member_sale").eq(i).val(0);
		}		
	}
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
		src: "/backoffice/module/shop/pop_good.php?eventMd=<?=$_GET['eventMd']?>",
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
<style type="text/css">
	.numberOnly{text-align:right;}
	.bggray{background:#eeeeee;}
</style>
<div class="container">
<?
############### 페이지 수정용 ############### ST
$queryString = explode("&",$_SERVER['QUERY_STRING']);
$reEditString = "";
$comma = "";
for($i=0;$i<count($queryString);$i++){
	if(strpos($queryString[$i],"rtpg=")===false && strpos($queryString[$i],"idx=")===false){
		$reEditString .= $comma.$queryString[$i];							
		$comma = "&";
	}
}
############### 페이지 수정용 ############### ED
?>
	<div class="title">상품 <?=$arrInfo["list"][0]['idx']?"수정":"등록"?></div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmInfo" method="post" action="good_evn.php" ENCTYPE="multipart/form-data" onSubmit="return goodCheckForm(this)">
			<input type="hidden" name="evnMode" value="<?=$arrInfo["list"][0]['idx']?"edit":"insert"?>">
			<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]['idx']?>">
			<input type="hidden" name="altYN" value="N">
			<input type="hidden" name="rt_url" value="<?=$_GET['rtpg']?$_GET['rtpg']:"good.php"?>?<?=$reEditString?>">
			<input type="hidden" name="image_type" value="1"><?## 이미지타입 사용안함 ##?>
			<input type="hidden" name="shipping_charge" value="0"><?## 배송료 사용안함 ##?>
			<input type="hidden" name="percent_point" value="0"><?## 적립금 사용안함 ##?>
			<input type="hidden" name="tmp_idx" value="<?=$arrInfo["list"][0]['idx']?$arrInfo["list"][0]['idx']:($tmpCount['list'][0]['cnt']+1)?>"><?## 적립금 사용안함 ##?>

			<input type="hidden" name="is_show" value="Y">

			<div class="tit">제품분류<i>*</i></div>
			<table>
				<tr>
					<th>제품 구분 <i>*</i></th>
					<td>
						<div class="inputs">
							<select name="model" style="width:180px;">								
								<option value="A" <?=$arrInfo["list"][0]['model']=="A"?"selected":""?>>일반</option>									
								<option value="S" <?=$arrInfo["list"][0]['model']=="S"?"selected":""?>>샘플</option>													
							</select>							
						</div>
					</td>
				</tr>
				
				<tr>
					<th>판매상태</th>
					<td>
						<div class="inputs">	
							<label class="radio"><input type="radio" name="stock_type" value="1"<?=$arrInfo["list"][0]['stock_type']=="1"?" checked":""?>><i></i>판매중</label>							
							
							<label class="radio"><input type="radio" name="stock_type" value="2"<?=$arrInfo["list"][0]['stock_type']=="2"?" checked":""?>><i></i>품절</label>
							
							<label class="radio"><input type="radio" name="stock_type" value="3"<?=$arrInfo["list"][0]['stock_type']=="3"?" checked":""?> <?=$arrInfo["list"][0]['stock_type']?"":"checked"?>><i></i>숨김</label>							
						</div>
					</td>
				</tr>
				<tr>
					<th>뱃지 표기</th>
					<td>
						<div class="inputs">
							<label class="check"><input type="checkbox" name="special_show" value="Y"<?=$arrInfo["list"][0]['special_show']!="N"?" checked":""?>><i></i>NEW</label>
							<label class="check"><input type="checkbox" name="best_show" value="Y"<?=$arrInfo["list"][0]['best_show']!="N"?" checked":""?>><i></i>BEST</label>
						</div>
					</td>
				</tr>
				<tr>
					<th>메인 노출 New</th>
					<td>
						<div class="inputs">
							<label class="check"><input type="checkbox" name="main_show" value="Y"<?=$arrInfo["list"][0]['main_show']!="N"?" checked":""?>><i></i>메인노출</label>
						</div>
					</td>
				</tr>
				<tr>
					<th>메인 노출 Product</th>
					<td>
						<div class="inputs">
							<label class="check"><input type="checkbox" name="brand_show" value="Y"<?=$arrInfo["list"][0]['brand_show']!="N"?" checked":""?>><i></i>메인노출</label>
						</div>
					</td>
				</tr>				
				<tr>
					<th>품목코드</th>
					<td><div class="inputs"><input type="text" class="w3" name="g_code" maxlength="50" value="<?=stripslashes($arrInfo["list"][0]['g_code'])?>"></div></td>
				</tr>
				<tr>
					<th>재고</th>
					<td>
						<div class="inputs"><input type="text" class="w2 numberOnly" name="stock" maxlength="10" value="<?=$arrInfo["list"][0]['idx']?$arrInfo["list"][0]['stock']:"99999999"?>">
						</div>
					</td>
				</tr>
				<tr>
					<th>노출순서</th>
					<td>
						<div class="inputs"><input type="text" class="w2 numberOnly" name="sort_num" maxlength="10" value="<?=$arrInfo["list"][0]['idx']?$arrInfo["list"][0]['sort_num']:"0"?>">
						<em>&nbsp;(숫자가 높을수록 위쪽에 나타남)</em>
						</div>
					</td>
				</tr>
			</table>			
			<div class="tit">기본정보 <i>*</i></div>
			<table>
				<tr>
					<th>카테고리 선택 <i>*</i></th>
					<td>
						<div class="inputs">
							<input type="hidden" id="cat_no" name="cat_no" value="<?=$arrInfo["list"][0]['cat_no']?>">
							<div id="cat_01">
								<select name="cat1" id="cat1" onchange="fnCat1(this.value);" style="height:100px;" size="3">
									<option value="">======1차분류======　　</option>
									<?
									for($i=0;$i<$arrCategory1["total"];$i++){									
									?>
									<option value="<?=$arrCategory1["list"][$i]['cat_no']?>"<?=$arrCatCode[1]==$arrCategory1["list"][$i]['cat_no']?" selected":""?> datacode="<?=$arrCategory1["list"][$i]['cat_value']?>"><?=$arrCategory1["list"][$i]['cat_name']?></option>
									<?}?>
								</select>
							</div>
							<div id="cat_02" style="padding-left:5px;">
								<select name="cat2" id="cat2" style="height:100px;" onchange="fnCat2(this.value);" size="3">
									<option value="">======2차분류======　　</option>
									<?
									for($i=0;$i<$arrCategory2["total"];$i++){
									?>
									<option value="<?=$arrCategory2["list"][$i]['cat_no']?>"<?=$arrCatCode[2]==$arrCategory2["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory2["list"][$i]['cat_name']?></option>
									<?
									}
									?>
								</select>
							</div>
						</div>
						<div><span style="color:red;">* 카테고리 선택 후 저장된 항목이 메인 페이지 카테고리로 저장됩니다.(1건만 가능)</span></div>
					</td>
				</tr>
				<tr>
					<th>추가 상품분류</th>
					<td><div class="inputs">
						<select id='ext_cat' name='ext_cat' style='width:550px;height:100px;' size="8">
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
						&nbsp;<a href="javascript:void(0);" onclick='addGoodCat()'><img src="/backoffice/images/k_add.gif" alt="추가" class="width100"/></a>
						&nbsp;<a href="javascript:void(0);" onclick='delGoodCat(document.frmInfo.ext_cat.selectedIndex)'><img src="/backoffice/images/k_delete.gif" alt="삭제" / class="width100"></a>
						<input type='hidden' id="ext_cat_hidden" name="ext_cat_hidden" value="<?=$arrExtCatHide?>">
					</div></td>
				</tr>
				<tr>
					<th>제품명</th>
					<td><div class="inputs"><input type="text" class="w4" name="g_name" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['g_name'])?>"></div></td>
				</tr>
				<tr>
					<th>단가(부가세 미포함)</th>
					<td>
						<div class="inputs">
							<input type="text" class="w2 numberOnly" style="text-align:right;" id="p_price" name="p_price" maxlength="10" value="<?= isset($arrInfo["list"][0]['p_price']) ? stripslashes($arrInfo["list"][0]['p_price']) : 0 ?>"><em>&nbsp;원</em>
						</div>
					</td>
				</tr>
				<tr>
					<th>회원 등급별<br/>
					노출 및 공급가액<br/>
					체크박스를 선택한 회원에게 노출
					</th>
					<td>
					<?
					$arrChoice	= explode("|",$arrInfo["list"][0]['member_choice']);
					$arrPrice	= explode("|",$arrInfo["list"][0]['member_price']);
					$arrSale	= explode("|",$arrInfo["list"][0]['member_sale']);

					$j = 0;
					for($i=0;$i<$arrBrandList["total"];$i++){									
						if(in_array($arrBrandList["list"][$i]['cat_no'], $arrChoice)){
							$arrBrandList["list"][$i]['member_price'] = $arrPrice[$j];
							$arrBrandList["list"][$i]['member_sale'] = $arrSale[$j];
							$j++;
						}else{
							$arrBrandList["list"][$i]['member_price'] = "0";
							$arrBrandList["list"][$i]['member_sale'] = "0";
						}
					?>	
						<div class="inputs" style="padding:3px 0;">
							<label class="check"><input type="checkbox" name="member_choice[]" value="<?=$arrBrandList["list"][$i]['cat_no']?>"
							<?=in_array($arrBrandList["list"][$i]['cat_no'], $arrChoice)?"checked":""?>><i></i><?=$arrBrandList["list"][$i]['cat_name']?></label>
							<input type="text" class="w2 numberOnly member_price" name="member_price_<?=$arrBrandList["list"][$i]['cat_no']?>" maxlength="100" value="<?=$arrBrandList["list"][$i]['member_price']?>">
							<em>&nbsp; 할인율 &nbsp;</em><input type="text" class="w1 numberOnly member_sale bggray" name="member_sale_<?=$arrBrandList["list"][$i]['cat_no']?>" maxlength="100" value="<?=$arrBrandList["list"][$i]['member_sale']?>" readonly><em>&nbsp;%</em>
						</div>
					<?}?>
					</td>
				</tr>
			</table>			
			<div class="tit">제품정보 <i>*</i></div>
			<table>
				<tr>
					<th>한 줄 소개</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_1" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_1'])?>"></div></td>
				</tr>
				<tr>
					<th>중량 및 포장</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_2" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_2'])?>"></div></td>
				</tr>
				<tr>
					<th>소비기한</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_3" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_3'])?>"></div></td>
				</tr>
				<tr>
					<th>보관방법</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_4" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_4'])?>"></div></td>
				</tr>
				<tr>
					<th>조리방법</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_5" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_5'])?>"></div></td>
				</tr>
				<tr>
					<th>알레르기 정보</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_6" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_6'])?>"></div></td>
				</tr>
				<tr>
					<th>포장재질</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_7" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_7'])?>"></div></td>
				</tr>
				<tr>
					<th>관련 검색어</th>
					<td><textarea id="search_keyword" name="search_keyword" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['search_keyword'])?></textarea></td>
				</tr>
			</table>			
			<div class="tit">상품정보 <i>*</i></div>
			<span class="redtxt">* 이미지 업로드 방법 : 에디터 그림 아이콘 선택 - 업로드 탭 선택 - 파일 선택 후 '서버로 전송 버튼 선택' - 확인</span><br>			
			<a href="/backoffice/pub/images/product_upload_sample.docx" download><span class="blue">* 상품정보 - 에디터에 스타일 넣는 방법</span></a>
			<table>
				<tr>
					<th>상품정보</th>
					<td><textarea id="contents" name="contents"><?=stripslashes($arrInfo["list"][0]['contents'])?></textarea>
					<?
					$CKContent = "contents";
					include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
					?></td>
				</tr>
				<tr>
					<th>TIP's</th>
					<td><textarea id="memo" name="memo"><?=stripslashes($arrInfo["list"][0]['memo'])?></textarea>
					<?
					$CKContent = "memo";
					include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
					?></td>
				</tr>
				<tr>
					<th>Special</th>
					<td><textarea id="text_special" name="text_special" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_special'])?></textarea></td>
				</tr>
				<tr>
					<th>Safety</th>
					<td><textarea id="text_safety" name="text_safety" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_safety'])?></textarea></td>
				</tr>
				<tr>
					<th>Pairing</th>
					<td><textarea id="text_pairing" name="text_pairing" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_pairing'])?></textarea></td>
				</tr>
				<tr>
					<th>Store</th>
					<td><textarea id="text_store" name="text_store" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_store'])?></textarea></td>
				</tr>
			</table>			
			<div class="tit">상세정보 <i>*</i></div>
				<span class="redtxt" style="font-size:12px; display:block;">* 제품구분 = 샘플 시 미노출</span>
			<table>
				<tr>
					<th>PRODUCT & SPEC</th>
					<td><textarea id="mokcha" name="mokcha"><?=stripslashes($arrInfo["list"][0]['mokcha'])?></textarea>
					<?
					$CKContent = "mokcha";
					include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
					?></td>
				</tr>
			</table>			
			<div class="tit">상품고시정보 <i>*</i></div>
				<span class="redtxt" style="font-size:12px; display:block;">* 제품구분 = 샘플 시 미노출</span>
				<span class="redtxt" style="font-size:12px; display:block;">* 텍스트 강조 시 <span>텍스트</span> 형태로 입력</span>
			<table>
				<tr>
					<th>제품명</th>
					<td><textarea id="text_etc1" name="text_etc1" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_etc1'])?></textarea></td>
				</tr>
				<tr>
					<th>식품유형</th>
					<td><textarea id="text_etc2" name="text_etc2" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_etc2'])?></textarea></td>
				</tr>
				<tr>
					<th>품목보고번호</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_8" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_8'])?>"></div></td>
				</tr>
				<tr>
					<th>원산지</th>
					<td><div class="inputs"><input type="text" class="w4" name="madein" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['madein'])?>"></div></td>
				</tr>
				<tr>
					<th>포장재질</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_9" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_9'])?>"></div></td>
				</tr>
				<tr>
					<th>소비기한</th>
					<td><div class="inputs"><input type="text" class="w4" name="etc_10" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_10'])?>"></div></td>
				</tr>				
				<tr>
					<th>원재료명</th>
					<td><textarea id="text_etc3" name="text_etc3" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_etc3'])?></textarea></td>
				</tr>				
				<tr>
					<th>알레르기정보</th>
					<td><textarea id="text_etc4" name="text_etc4" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_etc4'])?></textarea></td>
				</tr>
			</table>			
			<div class="tit">주의사항 <i>*</i></div>
				<span class="redtxt" style="font-size:12px; display:block;">* 제품구분 = 샘플 시 미노출</span>
				<span class="redtxt" style="font-size:12px; display:block;">* ‘-’ 입력 시 ‘ㆍ’ 로 대치</span>
			<table>
				<tr>
					<th>주의사항</th>
					<td><textarea id="text_etc5" name="text_etc5" style="width:70%;height:60px;"><?=stripslashes($arrInfo["list"][0]['text_etc5'])?></textarea></td>
				</tr>
			</table>			
			<div class="tit">영양 ㆍ 기능정보 <i>*</i></div>
				<span class="redtxt" style="font-size:12px; display:block;">* 제품구분 = 샘플 시 미노출</span>
			<table>
				<tr>
					<th>총 내용량</th>
					<td colspan="2"><div class="inputs"><input type="text" class="w4" name="author_name" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['author_name'])?>"></div></td>
				</tr>
				<tr>
					<td></td>
					<th style="background:#cccccc;text-align:center;">함량 </th>
					<th style="background:#cccccc;text-align:center;">영상성분 기준치(%)</th>					
				</tr>
				<tr>
					<th>열량</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_11" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_11'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_12" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_12'])?>"></td>
				</tr>
				<tr>
					<th>나트륨</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_13" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_13'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_14" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_14'])?>"></td>
				</tr>
				<tr>
					<th>탄수화물</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_15" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_15'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_16" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_16'])?>"></td>
				</tr>
				<tr>
					<th>당류</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_17" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_17'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_18" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_18'])?>"></td>
				</tr>
				<tr>
					<th>지방</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_19" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_19'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_20" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_20'])?>"></td>
				</tr>
				<tr>
					<th>트랜스지방</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_21" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_21'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_22" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_22'])?>"></td>
				</tr>
				<tr>
					<th>포화지방</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_23" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_23'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_24" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_24'])?>"></td>
				</tr>
				<tr>
					<th>콜레스테롤</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_25" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_25'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_26" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_26'])?>"></td>
				</tr>
				<tr>
					<th>단백질</th>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_27" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_27'])?>"></td>
					<td style="text-align:center;"><input type="text" class="" style="width:90%;" name="etc_28" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['etc_28'])?>"></td>
				</tr>
			</table>
			<div class="tit">다운로드 <i>*</i>
				<span class="redtxt" style="font-size:12px; display:block;">* 전체 파일은 20MB 이상 업로드할 수 없습니다.</span>
				</div>
			<table>			
				<tr>
					<th>누끼 이미지</th>
					<?if($arrInfo["list"][0]['etc_file_1']){?>
					<td><a href="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['etc_file_1']?>" target="_blank"><?=$arrInfo["list"][0]['etc_file_fn_1']?></a>
					&nbsp;&nbsp;<label class="check"><input type="checkbox" name="etc_file_1_del" value="Y"><i></i>삭제</label><input type="file" name="etc_file_1"  class="input" />
					</td>
					<?}else{?>
					<td><input type="file" name="etc_file_1" style="width:400px;" class="input" /></td>
					<?}?>
				</tr>
				<tr>
					<th>홍보물</th>
					<?if($arrInfo["list"][0]['etc_file_2']){?>
					<td><a href="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['etc_file_2']?>" target="_blank"><?=$arrInfo["list"][0]['etc_file_fn_2']?></a>
					&nbsp;&nbsp;<label class="check"><input type="checkbox" name="etc_file_2_del" value="Y"><i></i>삭제</label><input type="file" name="etc_file_2" class="input" />
					</td>
					<?}else{?>
					<td><input type="file" name="etc_file_2" style="width:400px;" class="input" /></td>
					<?}?>
				</tr>

				<tr style="display:none;">
					<th>규격서</th>
					<?if($arrInfo["list"][0]['etc_file_3']){?>
					<td><a href="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['etc_file_3']?>" target="_blank"><?=$arrInfo["list"][0]['etc_file_fn_3']?></a>
					&nbsp;&nbsp;<label class="check"><input type="checkbox" name="etc_file_3_del" value="Y" onclick="fnSave()"><i></i>삭제</label><input type="file" name="etc_file_3" style="display:none;" class="input" />
					</td>
					<?}else{?>
					<td><input type="file" name="etc_file_3" style="width:400px;" class="input" /></td>
					<?}?>
				</tr>
				<tr style="display:none;">
					<th>기타자료</th>
					<?if($arrInfo["list"][0]['etc_file_4']){?>
					<td><a href="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['etc_file_4']?>" target="_blank"><?=$arrInfo["list"][0]['etc_file_fn_4']?></a>
					&nbsp;&nbsp;<label class="check"><input type="checkbox" name="etc_file_4_del" value="Y" onclick="fnSave()"><i></i>삭제</label><input type="file" name="etc_file_4" style="display:none;" class="input" />
					</td>
					<?}else{?>
					<td><input type="file" name="etc_file_4" style="width:400px;" class="input" /></td>
					<?}?>
				</tr>
				<tr style="display:none;">
					<th>MSDS</th>
					<?if($arrInfo["list"][0]['etc_file_5']){?>
					<td><a href="/uploaded/shop_good/<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['etc_file_5']?>" target="_blank"><?=$arrInfo["list"][0]['etc_file_fn_5']?></a>
					&nbsp;&nbsp;<label class="check"><input type="checkbox" name="etc_file_5_del" value="Y" onclick="fnSave()"><i></i>삭제</label><input type="file" name="etc_file_5" style="display:none;" class="input" />
					</td>
					<?}else{?>
					<td><input type="file" name="etc_file_5" style="width:400px;" class="input" /></td>
					<?}?>
				</tr>

			</table>
			<div class="tit">이미지 <i>*</i>
				<span class="redtxt" style="font-size:12px; display:block;">* 이미지는 20mb 이하 / jpeg는 업로드 되지 않을 수 있습니다.</span>
				</div>
			<table>			
				<script type="text/javascript">
				<!--
				function fnDePyuImage(imgUrl, delid){
					if(imgUrl){
						$('#'+delid).prop('checked',true);
					}else{
						$('#'+delid).prop('checked',false);
					}
				}
				//-->
				</script>
				<tr>
					<th>대표이미지</th>
					<td>		
					<input type="file" name="photo_file[]" style="width:400px;" class="input imageFile" onchange="fnDePyuImage(this.value,'imgDelCK0')"/> 
					
					<input type="hidden" name="p_image" value="0"><?#대표이미지 고정#?>
					<input type="hidden" name="photo_file_name[]" value="<?=$arrInfo["files"][0]['re_name']?>" />	
					<?if($arrInfo["files"][0]['idx']){?>
					<span onClick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["files"][0]['re_name']?>', event);" style="cursor:pointer;">[보기]</span> &nbsp;
					<label class="check"><input type="checkbox" name="delPhoto[]" value="<?=$arrInfo["files"][0]['idx']?>" onclick="fnSave()" id="imgDelCK0"><i></i>삭제</label>  &nbsp;
					<?}?>
					<img src="#" onerror="this.style.display='none'" >
				  </td>
				</tr>
				<?
				//업로드 한것
				if($arrInfo["total_files"]>0){
					for($i=1; $i < $arrInfo["total_files"]; $i++){
				?>
				<tr>
					<th>이미지 <?=$i?></th>
					<td>		
					<input type="file" name="photo_file[]" style="width:400px;" class="input imageFile" onchange="fnDePyuImage(this.value,'imgDelCK<?=$i?>')"/> 

					<input type="hidden" name="photo_file_name[]" value="<?=$arrInfo["files"][$i]['re_name']?>" />

					<span onClick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["files"][$i]['re_name']?>', event);" style="cursor:pointer;">[보기]</span> &nbsp;
					<label class="check"><input type="checkbox" name="delPhoto[]" value="<?=$arrInfo["files"][$i]['idx']?>" onclick="fnSave()" id="imgDelCK<?=$i?>"><i></i>삭제</label>  &nbsp;
					<img src="#" onerror="this.style.display='none'" >
				  </td>
				</tr>
				<?
					}
				}
				if($arrInfo["total_files"] < 1){$arrInfo["total_files"]=1;}
				for($i=0; $i < intval($_SITE["PRODUCT"]["IMAGE_COUNT"] - $arrInfo["total_files"]); $i++){
				?>
				<tr>
					<th>이미지 <?=$i+$arrInfo["total_files"]?></th>
					<td><input type="file" name="photo_file[]" style="width:400px;" class="input imageFile" />
					<img src="#" onerror="this.style.display='none'" ></td>
				</tr>
				<?
				}
				?>
				
				
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
				<a href="<?=$_GET['rtpg']?>?<?=$reEditString?>" class="btn btn_list">목록보기</a>
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
//
	var $allCheck = $('#allCheck');
	$allCheck.change(function () {
		var $this = $(this);
		var checked = $this.prop('checked');
		$('.opt_checkbox').prop('checked', checked);
	});

	// 이미지 미리보기
	$(".imageFile").on("change", function(event) {
		var file = event.target.files[0];
		var reader = new FileReader(); 
		var timg = $(this).parent('td').children('img');
		reader.onload = function(e) {
			timg.attr("src", e.target.result);
			timg.show();
		}
		reader.readAsDataURL(file);
	});
});
//]]>
</script>