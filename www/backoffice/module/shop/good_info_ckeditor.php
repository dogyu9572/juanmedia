<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
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

//분류 리스트
$arrCategory = getCategoryList(0);//1차카테고리
$arrCategory1 = getCategoryList(67);//제조사
$arrCategory2 = getCategoryList(68);//제조국
$arrCategory3 = getCategoryList(69);//개월수

$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));

$arrExtCat = getGoodExtCat(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));
$arrExtSearch = getGoodExtSearch(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));

//카테고리 정보
$arrCatCode = explode("/", $arrInfo["list"][0]["cat_code"]);
?>
<div id="admin-container">
	<? include "menu_good.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">상품 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 상품 관리 &nbsp;&gt;&nbsp; 상품 수정</div>
	</div>

<script language="javascript">
//카테고리 이름 자바스크립트 변수 생성
var arrayAllCategory = new Array();
<?
foreach ($arrAllCategory AS $key => $val){
?>
arrayAllCategory[<?=$key?>] = "<?=$val?>";
<?
}
?>


<?
for($j=1;$j<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$j++){ //카테고리 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차까지 만듬
?>
//카테고리 초기화
function initCat<?=$j?>(){
	for(i=$("cat<?=$j?>").length; i >= 0; i--){
		$("cat<?=$j?>").options[i] = null;
	}
	$("cat<?=$j?>").options[0] = new Option("==========<?=$j?>차분류==========","");
}

//카테고리 가져오기
function getCat<?=$j?>(cat,selected_idx){
	//선택된 값 이후 카테고리는 초기화
	//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
	<?for($k=$j;$k<$_SITE["PRODUCT"]["CATEGORY_DEPTH"];$k++){?>
	try{ initCat<?=$k+1?>(); }catch(e){}
	<?}?>

	new Ajax.Request('/module/category/ajax_get_cat.php',
	{
		method: 'get',
		asynchronous: false,
		contentType: 'application/x-www-form-urlencoded',
		encoding: 'euc-kr',
		parameters: {cat_no: cat},

		onSuccess: function(transport){
			var response = transport.responseText; 
			setCat<?=$j?>(response,selected_idx);
			//카테고리 번호 설정
			$("cat_no").value = cat;
		},
		
		onFailure: function(){ 
			alert('Something went wrong...') 
		}   
	});

}

//카테고리 설정하기
function setCat<?=$j?>(txt,selected_idx){
	if(txt !=""){
		var opt = new Array();
		var opt2 = new Array();
		opt = txt.split("||");
		for(i=0; i<opt.length; i++){
			opt2 = opt[i].split("**");
			//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
			try{
				if(selected_idx==opt2[0]){
					$("cat<?=$j+1?>").options[i+1] = new Option(opt2[1],opt2[0],true,true);
				}else{
					$("cat<?=$j+1?>").options[i+1] = new Option(opt2[1],opt2[0]);
				}
			}catch(e){}
		}
	}
}
<?
} //카테고리 js 끝
?>


//추가 상품분류를 위한~~
<?
for($j=1;$j<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$j++){ //카테고리 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차까지 만듬
?>
//카테고리 초기화
function initExtCat<?=$j?>(){
	for(i=$("t_ext_cat<?=$j?>").length; i >= 0; i--){
		$("t_ext_cat<?=$j?>").options[i] = null;
	}
	$("t_ext_cat<?=$j?>").options[0] = new Option("==========<?=$j?>차분류==========","");
}

//카테고리 가져오기
function getExtCat<?=$j?>(cat,selected_idx){
	//선택된 값 이후 카테고리는 초기화
	//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
	<?for($k=$j;$k<$_SITE["PRODUCT"]["CATEGORY_DEPTH"];$k++){?>
	try{ initExtCat<?=$k+1?>(); }catch(e){}
	<?}?>

	new Ajax.Request('/module/category/ajax_get_cat.php',
	{
		method: 'get',
		asynchronous: false,
		contentType: 'application/x-www-form-urlencoded',
		encoding: 'euc-kr',
		parameters: {cat_no: cat},

		onSuccess: function(transport){
			var response = transport.responseText; 
			setExtCat<?=$j?>(response,selected_idx);
			//카테고리 번호 설정
			$("t_ext_cat_no").value = cat;
		},
		
		onFailure: function(){ 
			alert('Something went wrong...') 
		}   
	});

}

//카테고리 설정하기
function setExtCat<?=$j?>(txt,selected_idx){
	if(txt !=""){
		var opt = new Array();
		var opt2 = new Array();
		opt = txt.split("||");
		for(i=0; i<opt.length; i++){
			opt2 = opt[i].split("**");
			//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
			try{
				if(selected_idx==opt2[0]){
					$("t_ext_cat<?=$j+1?>").options[i+1] = new Option(opt2[1],opt2[0],true,true);
				}else{
					$("t_ext_cat<?=$j+1?>").options[i+1] = new Option(opt2[1],opt2[0]);
				}
			}catch(e){}
		}
	}
}
<?
} //카테고리 js 끝
?>
</script>


<form name="frmInfo" method="post" action="good_evn.php" ENCTYPE="multipart/form-data" onSubmit="return goodCheckForm(this)">
<input type="hidden" name="evnMode" value="edit">
<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]['idx']?>">
<input type="hidden" name="rt_url" value="<?=$_SERVER['REQUEST_URI']?>">



		<!-- 기본정보 -->
		<h3 class="admin-title-middle">기본정보</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>상품코드</th>
				<td class="space-left"><input type="text" name="g_code" style="width:200" maxlength="30" value="<?=stripslashes($arrInfo["list"][0]['g_code'])?>" class="input" /></td>
			</tr>
			<tr>
				<th>상품분류</th>
				<td class="space-left">
					<input type="hidden" id="cat_no" name="cat_no" value="<?=$arrInfo["list"][0]['cat_no']?>">
					<select name="cat" id="cat" onchange="getCat1(this.value);">
					<option value="">==========1차분류==========</option>
					<?for($i=0;$i<$arrCategory["total"];$i++){?>
					<option value="<?=$arrCategory["list"][$i]['cat_no']?>"<?=$arrCatCode[0]==$arrCategory["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory["list"][$i]['cat_name']?></option>
					<?}?>
					</select>

					<?
					for($i=2;$i<$_SITE["PRODUCT"]["CATEGORY_DEPTH"]+1;$i++){ //카테고리 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차까지 만듬 => 1차 초기화는 따로위에서 함
					?>
					<select name="cat<?=$i?>" id="cat<?=$i?>" onchange="getCat<?=$i?>(this.value);">
					<option value="">==========<?=$i?>차분류==========</option>
					</select>
					<?
					}
					?>
					<!-- 해당 분류 선택하기 -->
					<script language="javascript">
					<?
					$arrCatCode = explode("/",$arrInfo["list"][0]["cat_code"]);
					for($i=0;$i<count($arrCatCode)-1;$i++){
					?>
					getCat<?=$i+1?>('<?=$arrCatCode[$i]?>','<?=$arrCatCode[$i+1]?>');
					<?
					}
					?>
					</script>
				</td>
			</tr>
			<tr>
				<th>추가 상품분류</th>
				<td class="space-left">
				<select id='ext_cat' name='ext_cat' style='width:550px;' size="8">
				<?
				for($i=0;$i<$arrExtCat["total"];$i++){
					$arrExtCatCode = explode("/", $arrExtCat["list"][$i]["cat_code"]);
					$strExtCat = "";
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
				<a href="javascript:void(0);" onclick='LayerShowGoodCat(event)'><img src="/backoffice/images/k_add.gif" alt="추가" /></a>
				<a href="javascript:void(0);" onclick='delGoodCat(document.frmInfo.ext_cat.selectedIndex)'><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a>
				<input type='hidden' id="ext_cat_hidden" name="ext_cat_hidden">
				</td>
			</tr>	
			<!-- <tr>
				<th>관련 상품</th>
				<td class="space-left">
				<a href="javascript:void(0);" onclick='LayerShowRelGood(event)'><img src="/backoffice/images/k_add.gif" alt="추가" /></a>
				<a href="javascript:void(0);" onclick='delRelGood(this.form.rel_good.selectedIndex)'><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a>
				<input type='hidden' id="rel_good_hidden" name="rel_good_hidden">
				<br>
				<select id='rel_good' name='rel_good' style='width:550px;' size="8">
				<?
				for($i=0;$i<$arrInfo["list_rel_good_total"];$i++){
				?>
				<option value="<?=$arrInfo["list_rel_good"][$i]["idx"]?>"><?=$arrInfo["list_rel_good"][$i]["g_name"]?></option>
				<?}?>
				</select>
				</td>
			</tr> -->
			<tr>
				<th>상품명</th>
				<td class="space-left"><input type="text" name="g_name" style="width:90%" class="input" maxlength="200" value="<?=stripslashes($arrInfo["list"][0]['g_name'])?>" /></td>
			</tr>
			<!-- <tr>
				<th>간략설명</th>
				<td class="space-left"><textarea name="memo" class="textarea" style="width:90%;height:60px"><?=stripslashes($arrInfo["list"][0]["memo"])?></textarea></td>
			</tr> -->
			<tr>
				<th>정렬순서</th>
				<td class="space-left"><input type="text" name="sort_num" size="4" maxlength="8" value="<?=$arrInfo["list"][0]['sort_num']?>" class="input" /> (숫자가 높을수록 위쪽에 표시됨)</td>
			</tr>
			<!-- <tr>
				<th>아이콘</th>
				<td class="space-left">
					<ul style="display:inline-block;width:90%;padding-top:10px;">
					<?
					$arrIcons = explode("|",$arrInfo["list"][0]['icons']);
					if ($handle = opendir($_SERVER['DOCUMENT_ROOT'] . "/uploaded/shop_icons/")) {
						$cc=0;
						while (false !== ($file = readdir($handle))) {
							if ($file != "." && $file != "..") {
					?>
						<li style="float:left;width:160px;height:40px;"><input type="checkbox" name="shop_icon[]" value="<?=$file?>"<?=in_array($file,$arrIcons)?" checked":""?>><img src="/uploaded/shop_icons/<?=$file?>"></li>
					<?
								//if($cc%8 == 7) echo "<br />"; 
								//$cc++;	
							}
						}
						closedir($handle);
					}
					?>
					</ul>
					<div style="color:#d33131;clar:both;">(FTP를 이용하여 /www/uploaded/shop_icons 폴더에 아이콘을 업로드 시키면 나타납니다.)</div>
				</td>
			</tr> -->
		  </tbody>
		</table>

		<br />
		<h3 class="admin-title-middle">전자상거래 등에서의 상품정보제공 고시</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="280" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>식품의 유형</th>
				<td class="space-left"><input type="text" name="pan_color" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['pan_color'])?>" /></td>
			</tr>
			<tr>
				<th>생산자 및 소재지</th>
				<td class="space-left"><input type="text" name="pages" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['pages'])?>" /></td>
			</tr>
			<tr>
				<th>제조연월일, 유통기한 또는 품질유지기한</th>
				<td class="space-left"><input type="text" name="mokcha" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['mokcha'])?>" /></td>
			</tr>
			<tr>
				<th>포장단위별 용량(중량), 수량</th>
				<td class="space-left"><input type="text" name="author_text" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['author_text'])?>" /></td>
			</tr>
			<tr>
				<th>원재료명 및 함량</th>
				<td class="space-left"><input type="text" name="movie_url" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['movie_url'])?>" /></td>
			</tr>
			<tr>
				<th>영양성분</th>
				<td class="space-left"><input type="text" name="author_name" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['author_name'])?>" /></td>
			</tr>
			<tr>
				<th>유전자변형식품에 해당하는 경우의 표시</th>
				<td class="space-left"><input type="text" name="published_text" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['published_text'])?>" /></td>
			</tr>
			<tr>
				<th>표시광고사전심의필 유무 및 부작용 발생 가능성</th>
				<td class="space-left"><input type="text" name="brand" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['brand'])?>" /></td>
			</tr>
			<tr>
				<th>수입식품 문구</th>
				<td class="space-left"><input type="text" name="model" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['model'])?>" /></td>
			</tr>
			<tr>
				<th>소비자상담관련 전화번호</th>
				<td class="space-left"><input type="text" name="movie" style="width:90%" class="input" value="<?=stripslashes($arrInfo["list"][0]['movie'])?>" /></td>
			</tr>
		  </tbody>
		</table>

		<br />
		<!-- 상품 추가정보 -->
		<h3 class="admin-title-middle">상품 추가정보</h3>
		<table class="admin-table-type1">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>배송기간</th>
				<td class="space-left"><input type="text" name="isbn" style="width:400px" maxlength="50" class="input" value="<?=stripslashes($arrInfo["list"][0]['isbn'])?>" /></td>
			</tr>
			<tr>
				<th>교환/반품정보</th>
				<td class="space-left"><textarea name="memo" cols="70" rows="4" style="height:36px" class="input" /><?=stripslashes($arrInfo["list"][0]['memo'])?></textarea></td>
			</tr>
			
			<!-- <tr>
				<th>추가 검색분류</th>
				<td class="space-left">
				<select id='ext_search' name='ext_search' style='width:550px;' size="8">
				<?
				for($i=0;$i<$arrExtSearch["total"];$i++){
					$arrExtSearchCode = explode("/", $arrExtSearch["list"][$i]["cat_code"]);
					$strExtSearch = "";
					for($j=0;$j<count($arrExtSearchCode)-1;$j++){
						$strExtSearch .= $arrAllCategory[$arrExtSearchCode[$j]];
						if($j != count($arrExtSearchCode)-2){
							$strExtSearch .= " > ";
						}
					}
				?>
				<option value="<?=$arrExtSearch["list"][$i]["cat_no"]?>"><?=$strExtSearch?></option>
				<?}?>
				</select>
				<a href="javascript:void(0);" onclick='LayerShowGoodSearch(event)'><img src="/backoffice/images/k_add.gif" alt="추가" /></a>
				<a href="javascript:void(0);" onclick='delGoodSearch(document.frmInfo.ext_search.selectedIndex)'><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a>
				<input type='hidden' id="ext_search_hidden" name="ext_search_hidden">
				</td>
			</tr>	 -->
			<tr>
				<th>매입가</th>
				<td class="space-left"><input type="text" name="p_price" style="width:200" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['p_price'])?>" class="input" /> (숫자만 입력)</td>
			</tr>
			<tr>
				<th>소비자가</th>
				<td class="space-left"><input type="text" name="sale_price" style="width:200" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['sale_price'])?>" class="input" /> (숫자만 입력)</td>
			</tr>
			<tr>
				<th>판매가</th>
				<td class="space-left"><input type="text" name="price" style="width:200" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['price'])?>" class="input" /> (숫자만 입력)</td>
			</tr>
			<!-- <tr>
				<th>적립금</th>
				<td class="space-left"><input type="text" name="point" style="width:100" maxlength="20" value="<?=stripslashes($arrInfo["list"][0]['point'])?>" class="input" />
				<select name="point_unit">
				<option value="F"<?=$arrInfo["list"][0]['point_unit']=="F"?" selected":""?>>고정금액</option>
				<option value="P"<?=$arrInfo["list"][0]['point_unit']=="P"?" selected":""?>>%</option>
				</select>
				</td>
			</tr> -->
			<tr>
				<th>상품진열</th>
				<td class="space-left"><input type="radio" id="is_show_y" name="is_show" value="Y"<?=$arrInfo["list"][0]['is_show']=="Y"?" checked":""?>><label for="is_show_y"><font color=blue>진열함</font></label> &nbsp;
				<input type="radio" id="is_show_n" name="is_show" value="N"<?=$arrInfo["list"][0]['is_show']=="N"?" checked":""?>><label for="is_show_n"><font color=red>진열안함</font></label>
				</td>
			</tr>
			<tr>
				<th>메인노출 여부</th>
				<td class="space-left">
				<input type="checkbox" id="main_show" name="main_show" value="Y"<?=$arrInfo["list"][0]['main_show']=="Y"?" checked":""?>><label for="main_show"><font color=blue>MAIN</font></label> &nbsp;
				<!-- input type="checkbox" id="brand_show" name="brand_show" value="Y"<?=$arrInfo["list"][0]['brand_show']=="Y"?" checked":""?>><label for="brand_show"><font color=green>Brand Pick</font></label> &nbsp;
				<input type="checkbox" id="special_show" name="special_show" value="Y"<?=$arrInfo["list"][0]['special_show']=="Y"?" checked":""?>><label for="special_show"><font color=red>SPECIAL Pick</font></label> &nbsp;
				<input type="checkbox" id="best_show" name="best_show" value="Y"<?=$arrInfo["list"][0]['best_show']=="Y"?" checked":""?>><label for="best_show">BEST PRODUCT</label--> &nbsp;
				</td>
			</tr>
			<tr>
				<th>재고관리</th>
				<td class="space-left">
				<input type="radio" id="stock_type_1" name="stock_type" value="1" onclick="checkStockManage(1);"<?=$arrInfo["list"][0]['stock_type']=="1"?" checked":""?>><label for="stock_type_1"><font color=blue>재고관리 안함</font></label> &nbsp;
				<input type="radio" id="stock_type_2" name="stock_type" value="2" onclick="checkStockManage(2);"<?=$arrInfo["list"][0]['stock_type']=="2"?" checked":""?>><label for="stock_type_2"><font color=green>일반 재고관리</font></label> &nbsp;
				<!--<input type="radio" id="stock_type_3" name="stock_type" value="3" onclick="checkStockManage(3);"<?=$arrInfo["list"][0]['stock_type']=="3"?" checked":""?>><label for="stock_type_3"><font color=red>연계 재고관리</font></label> -->
				<table class="admin-table-type1" id="layerStock" style="position:relative; display:none; width:200px !important;">
					<tr>
						<th>재고량</th>
						<td class="space-left"><input type="text" name="stock" style="width:100" maxlength="10" value="<?=$arrInfo["list"][0]['stock']?>" class="input" /></td>
					</tr>
				</table>
				</td>
			</tr>
		  </tbody>
		</table>

		<br />

		<!-- 옵션 추가정보 -->
		<h3 class="admin-title-middle">상품 옵션정보 &nbsp; | &nbsp; <span class="btn_pack medium icon"><span class="download"></span><a href="javascript:add_opt();">옵션불러오기</a></span>&nbsp; &nbsp; 
				<span class="btn_pack medium icon"><span class="check"></span><a href="javascript:append_opt();">옵션 추가</a></span>&nbsp; 
				<span class="btn_pack medium icon"><span class="delete"></span><a href="javascript:remove_opt();">옵션 삭제</a></span></h3>
		<!-- 옵션관련 변수 처리는 /common/js/shop.js 에서 처리합니다 -->
		<input type="hidden" id="opt_hidden_count" name="opt_hidden_count">
		<!-- 추가옵션 관리 -->
		<table class="admin-table-type1" id="layerOption1" style="position:relative; display:show;">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>추가옵션</th>
				<td class="space-left">
					<div style="width:480px;">
						<table class="admin-table-type1" id="product_opt">
						  <colgroup>
						  <col width="150" />
						  <col width="200" />
						  <col width="130" />
						  </colgroup>
						  <tbody>
							<tr>
								<td colspan="3">옵션수정후 아래의 수정을 클릭을 하시면 적용됩니다.</td>
							</tr>
							<tr>
								<th class="space-center">옵션명</th>
								<th class="space-center">옵션항목</th>
								<th class="space-center">항목설정</th>
							</tr>
							<?
							for($i=0;$i<$arrInfo["total_opt"];$i++){
							?>
							<tr>
								<td>
								<input type='hidden' id='opt_hidden_value_<?=$i?>' name='opt_hidden_value_<?=$i?>'>
								<input type='text' id='opt_subject_<?=$i?>' name='opt_subject_<?=$i?>' style='width:100%' maxlength='250' value="<?=stripslashes($arrInfo["opt"][$i]["opt_1"])?>" class="input" />  
								</td>
								<td>
								<select id='opt_contents_<?=$i?>' name='opt_contents_<?=$i?>' style='width:100%'>
								<?
								for($j=0;$j<$arrInfo["total_opt_info"];$j++){
									if($arrInfo["opt"][$i]["opt_1"]==$arrInfo["opt_info"][$j]["opt_1"]){
								?>
								<option value="<?=$arrInfo["opt_info"][$j]["opt_1_value"]?>|<?=$arrInfo["opt_info"][$j]["price"]?>"><?=$arrInfo["opt_info"][$j]["opt_1_value"]?>|<?=$arrInfo["opt_info"][$j]["price"]?></option>
								<?
									}
								}
								?>
								</select></td>
								<td>
								  <a href="javascript:void(0);" onclick='LayerShowProductOpt(<?=$i?>, event)'><img src="/backoffice/images/k_add.gif" alt="추가" /></a>
								  <a href="javascript:void(0);" onclick='getProductOpt(<?=$i?>, document.getElementById("opt_contents_<?=$i?>").selectedIndex, document.getElementById("opt_contents_<?=$i?>").value, event)'><img src="/backoffice/images/k_modify.gif" alt="수정" /></a>
								  <a href="javascript:void(0);" onclick='delProductOpt(<?=$i?>, document.getElementById("opt_contents_<?=$i?>").selectedIndex)'><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a>
								</td>
							</tr>		
							<?
							}
							?>
						  </tbody>
						</table>
					</div>
				</td>
			</tr>
		  </tbody>
		</table>		
		<!-- 추가옵션 관리 -->

		<!-- 연계 재고관리 -->
		<div id="layerOption2" style="position:relative; display:none;">
			<div class="mgb5">세로/가로,  재고,추가금 (세로, 가로의 항목명이 없는 것, 값이 없는 옵션은 포함되지 않습니다.)</div>
			<table class="admin-table-type1">
			  <tbody>
				<tr> 
				  <td width="10%"><table width="100%" cellspacing="0" cellpadding="0"></tr><td width="50%" bgcolor="#000000" align="center"><input type="text" name="relOptName2" style="width:50" class='input' maxlength="50" value="<?=stripslashes($arrInfo["opt_rel"][0]["opt_2"])?>"></td><td width="10"></td><td width="50%" bgcolor="#FFCC00" align="center"><input type="text" name="relOptName1" style="width:50" class='input' maxlength="50" value="<?=stripslashes($arrInfo["opt_rel"][0]["opt_1"])?>"></td></tr></table></td>
				  <?for($i=0;$i<9;$i++){?>
				  <td bgcolor="#FFCC00" width="10%"><input name="relOpt1_<?=$i?>" type="text" class='input' value="<?=$arrInfo["opt_rel_1"][$i]["opt_1_value"]?>" size="4"> <input name="" type="text" class='input' value="추가금" size="6" readonly></td>
				  <?}?>
				</tr>

				<?for($i=0;$i<9;$i++){?>
				<tr> 
				  <td bgcolor="#000000"><input name="relOpt2_<?=$i?>" type="text" class='input' value="<?=$arrInfo["opt_rel_2"][$i]["opt_2_value"]?>" size="10"></td>
				  <?for($j=0;$j<9;$j++){?>
				  <td>
				  <input name="rel_stock_<?=$i?>_<?=$j?>" type="text" class='input' size="4" value="<?=$arrInfo["opt_rel_info"][$arrInfo["opt_rel_1"][$j]["opt_1_value"]][$arrInfo["opt_rel_2"][$i]["opt_2_value"]]["stock"]?>">
				  <input name="rel_price_<?=$i?>_<?=$j?>" type="text" class='input' size="6" value="<?=$arrInfo["opt_rel_info"][$arrInfo["opt_rel_1"][$j]["opt_1_value"]][$arrInfo["opt_rel_2"][$i]["opt_2_value"]]["price"]?>">
				  </td>
				  <?}?>
				</tr>
				<?}?>
		  </tbody>
		</table>
		</div>
		<!-- 연계 재고관리 -->

		<!-- 옵션 추가정보 -->


		<!-- 상품 사진 -->
		<br />
		<h3 class="admin-title-middle">상품 사진 | <input type="radio" id="image_type_1" name="image_type" value="1" checked onclick="checkImageType(1);"<?=$arrInfo["list"][0]['image_type']=="1"?" checked":""?>><label for="image_type_1"><font color=blue>대표이미지</font></label> <!-- <input type="radio" id="image_type_2" name="image_type" value="2" onclick="checkImageType(2);"<?=$arrInfo["list"][0]['image_type']=="2"?" checked":""?>><label for="image_type_2"><font color=green>개별이미지</font></label> --></h3>
		<!-- 이미지 타입 1 -->
		<div id="layerImage1" style="position:relative; display:block;">
			<div class="mgb5" style="line-height:20px;">
				아래 이미지중 대표이미지로 설정된 이미지를 장바구니, 상품목록, 상품상세 이미지를 자동으로 생성(나머지는 확대이미지로 사용) <br />
				이미지크기는 600px내외 1:1 비율이 적당합니다. 이미지파일의 확장자는 RGB모드의 jpg, gif, png 입니다.
			</div>
			<table class="admin-table-type1">
			  <colgroup>
			  <col width="140" />
			  <col width="*" />
			  </colgroup>
			  <tbody>
				<?
				//업로드 한것
				if($arrInfo["total_files"]>0){
				for($i=0; $i < $arrInfo["total_files"]; $i++){
				?>
				<tr>
				  <th>업로드된 사진 <?=$i+1?> </th>
				  <td class="space-left">
				
					<span onClick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["files"][$i]['re_name']?>', event);" style="cursor:pointer;">[보기]</span> &nbsp;

					<!--
					<a href="javascript:;" onclick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["files"][$i]['re_name']?>');">보기</a>--> 
					<input type="checkbox" name="delPhoto[]" id="delPhoto1<?=$i?>" value="<?=$arrInfo["files"][$i]['idx']?>"><label for="delPhoto1<?=$i?>"><font color=red>삭제</font></label>  &nbsp;

					<font color=blue><?=$arrInfo["files"][$i]['re_name']==$arrInfo["list"][0]['p_image']?"대표이미지":""?></font>
				  </td>
				</tr>
				<?}?>
				<?}?>

				<?
				for($i=0; $i < intval($_SITE["PRODUCT"]["IMAGE_COUNT"] - $arrInfo["total_files"]); $i++){
				?>
				<tr>
				  <th>사진 <?=$i+1?></th>
				  <td class="space-left"><input type="file" name="photo_file[]" style="width:400px;" class="input" /> <input type="radio" name="p_image" value="<?=$i?>" id="idPhoto<?=$i?>"><label for="idPhoto<?=$i?>">대표이미지</label></td>
				</tr>
				<?
				}
				?>
			  </tbody>
			</table>
		</div>
		<!-- 이미지 타입 2 -->
		<div id="layerImage2" style="position:relative; display:none;">
			<div class="mgb5" style="line-height:20px;">
				아래 이미지중 대표이미지로 설정된 이미지를 장바구니, 상품목록, 상품상세 이미지를 자동으로 생성(나머지는 확대이미지로 사용)
			</div>
			<table class="admin-table-type1">
			  <colgroup>
			  <col width="140" />
			  <col width="*" />
			  </colgroup>
			  <tbody>
				<tr>
				  <th>장바구니 사진</th>
				  <td class="space-left"><a href="javascript:;" onclick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['image_s']?>');">[보기]</a> <input type="file" name="photo_file_s" style="width:400px;" class="input" /> </td>
				</tr>
				<tr>
				  <th>상품목록 사진</th>
				  <td class="space-left"><a href="javascript:;" onclick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['image_m']?>');">[보기]</a> <input type="file" name="photo_file_m" style="width:400px;" class="input" /> </td>
				</tr>
				<tr>
				  <th>상세정보 사진</th>
				  <td class="space-left"><a href="javascript:;" onclick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["list"][0]['image_l']?>');">[보기]</a> <input type="file" name="photo_file_l" style="width:400px;" class="input" /> </td>
				</tr>
				<?
				//업로드 한것
				if($arrInfo["total_files"]>0){
				for($i=0; $i < $arrInfo["total_files"]; $i++){
				?>
				<tr>
				  <th>업로드된 사진 <?=$i+1?> </th>
				  <td class="space-left"><a href="javascript:;" onclick="LayerShowImage('<?=$arrInfo["list"][0]['idx']?>/<?=$arrInfo["files"][$i]['re_name']?>');">[보기]</a> &nbsp;
				    <input type="checkbox" name="delPhoto[]" id="delPhoto2<?=$i?>" value="<?=$arrInfo["files"][$i]['idx']?>"><label for="delPhoto2<?=$i?>"><font color=red>[삭제]</font></label>
				  </td>
				</tr>
				<?}?>
				<?}?>

				<?
				for($i=0; $i < intval($_SITE["PRODUCT"]["IMAGE_COUNT"] - $arrInfo["total_files"]); $i++){
				?>
				<tr>
				  <th>사진 <?=$i+1?></th>
				  <td class="space-left"><input type="file" name="photo_file[]" style="width:400px;" class="input" /></td>
				</tr>
				<?
				}
				?>
			  </tbody>
			</table>
		</div>

		<br />
		
		<h3 class="admin-title-middle">상품 설명</h3>
		<table width="100%;" border="0" cellspacing="1" cellpadding="6" bgcolor="#dedede">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th bgcolor="#f8f8f8">상세설명</th>
				<td bgcolor="#ffffff">
				<textarea id="contents" name="contents"><?=stripslashes($arrInfo["list"][0]["contents"])?></textarea>
				<?
				$CKContent = "contents";
				include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
				?>
				</td>
			</tr>
		  </tbody>
		</table>


		<div class="admin-buttons">
			<div class="cen">
				<span class="btn_pack xlarge icon"><span class="refresh"></span><input type="submit" value="정보수정" style="font-weight:bold" /></span>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<span class="btn_pack xlarge"><input type="button" value="목록으로" style="font-weight:bold" onclick="document.location.href='<?=$_GET['listURL']?>'" /></span>
			</div>
		</div>	


		</form>
	</div>
</div>
<?
//DB해제
SetDisConn($dblink);
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>
<script language="javascript">
//상품 옵션정보 레이어 선택
checkStockManage('<?=$arrInfo["list"][0]['stock_type']?>');
//상품 옵션정보의 추가갯수 재 설정(현재 등록되어 있는 만큼)
rowcount_opt = <?=$arrInfo["total_opt"]?>;
//이미지 타입 레이어 선택
checkImageType(<?=$arrInfo["list"][0]['image_type']?>);
</script>
<div id="layerProductOpt" style="position:absolute; display:none; background-color:#FFCC00; border-size:3px;bordercolor:#CCCCCC"></div>
<div id="layerImageShow" style="position:absolute; display:none; background-color:#FFFFFF; border-size:3px;bordercolor:#CCCCCC"></div>
<div id="layerGoodCat" style="position:absolute; display:none; background-color:#FFCC00; border-size:3px;bordercolor:#CCCCCC"></div>
<div id="layerRelGood" style="position:absolute; display:none; background-color:#FF0000; border-size:3px;bordercolor:#CCCCCC">
<iframe id="iframeRelGood" name="iframeRelGood" border="1" width="800" height="400"></iframe>
</div>
<iframe id="iframeHidden" name="iframeHidden" border="0" width="0" height="0"></iframe>
