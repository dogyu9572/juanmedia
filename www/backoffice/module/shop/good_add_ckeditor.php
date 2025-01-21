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
?>
<div id="admin-container">
	<? include "menu_good.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">상품 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 상품 관리 &nbsp;&gt;&nbsp; 상품 등록</div>
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
function getCat<?=$j?>(cat){
	//선택된 값 이후 카테고리는 초기화
	//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
	<?for($k=$j;$k<$_SITE["PRODUCT"]["CATEGORY_DEPTH"];$k++){?>
	try{ initCat<?=$k+1?>(); }catch(e){}
	<?}?>

	new Ajax.Request('/module/category/ajax_get_cat.php',
	{
		method: 'get',
		//asynchronous: this.asynchronous,
		asynchronous: false,
		contentType: 'application/x-www-form-urlencoded',
		encoding: 'euc-kr',
		parameters: {cat_no: cat},

		onSuccess: function(transport){
			var response = transport.responseText; 
			setCat<?=$j?>(response);
			//카테고리 번호 설정
			$("cat_no").value = cat;
		},
		
		onFailure: function(){ 
			alert('Something went wrong...') 
		}   
	});

}

//카테고리 설정하기
function setCat<?=$j?>(txt){
	if(txt !=""){
		var opt = new Array();
		var opt2 = new Array();
		opt = txt.split("||");
		for(i=0; i<opt.length; i++){
			opt2 = opt[i].split("**");
			//마지막 $_SITE["PRODUCT"]["CATEGORY_DEPTH"]차 이후는 셀렉트 박스가 없으므로 try 로 씀
			try{
				$("cat<?=$j+1?>").options[i+1] = new Option(opt2[1],opt2[0]);
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
		//asynchronous: this.asynchronous,
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
<input type="hidden" name="evnMode" value="insert">

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
				<td class="space-left"><input type="text" name="g_code" style="width:200px" maxlength="30" value="<?=date("YmdHis").rand(100,999)?>" class="input" /></td>
			</tr>
			<tr>
				<th>상품분류</th>
				<td class="space-left">
					<input type="hidden" name="cat_no" id="cat_no">
					<select name="cat" id="cat" onchange="getCat1(this.value);">
					<option value="">==========1차분류==========</option>
					<?for($i=0;$i<$arrCategory["total"];$i++){?>
					<option value="<?=$arrCategory["list"][$i]['cat_no']?>"><?=$arrCategory["list"][$i]['cat_name']?></option>
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
				</td>
			</tr>
			<tr>
				<th>추가 상품분류</th>
				<td class="space-left">
				<select id='ext_cat' name='ext_cat' style='width:550px;' size="8">
				</select>
				<a href="javascript:void(0);" onclick='LayerShowGoodCat(event)'><img src="/backoffice/images/k_add.gif" alt="추가" /></a>
				<a href="javascript:void(0);" onclick='delGoodCat(document.getElementById("ext_cat").selectedIndex)'><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a>
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
				</select>
				</td>
			</tr> -->
			<tr>
				<th>상품명</th>
				<td class="space-left"><input type="text" name="g_name" style="width:90%" maxlength="200" class="input" /></td>
			</tr>
			<!-- <tr>
				<th>간략설명</th>
				<td class="space-left"><textarea name="memo" class="textarea" style="width:90%;height:60px"></textarea></td>
			</tr> -->
			<tr>
				<th>정렬순서</th>
				<td class="space-left"><input type="text" name="sort_num" size="4" maxlength="8" value="0" class="input" /> (숫자가 높을수록 위쪽에 표시됨)</td>
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
				<td class="space-left"><input type="text" name="pan_color" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>생산자 및 소재지</th>
				<td class="space-left"><input type="text" name="pages" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>제조연월일, 유통기한 또는 품질유지기한</th>
				<td class="space-left"><input type="text" name="mokcha" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>포장단위별 용량(중량), 수량</th>
				<td class="space-left"><input type="text" name="author_text" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>원재료명 및 함량</th>
				<td class="space-left"><input type="text" name="movie_url" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>영양성분</th>
				<td class="space-left"><input type="text" name="author_name" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>유전자변형식품에 해당하는 경우의 표시</th>
				<td class="space-left"><input type="text" name="published_text" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>표시광고사전심의필 유무 및 부작용 발생 가능성</th>
				<td class="space-left"><input type="text" name="brand" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>수입식품 문구</th>
				<td class="space-left"><input type="text" name="model" style="width:90%" class="input" /></td>
			</tr>
			<tr>
				<th>소비자상담관련 전화번호</th>
				<td class="space-left"><input type="text" name="movie" style="width:90%" class="input" /></td>
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
				<td class="space-left"><input type="text" name="isbn" style="width:400px" maxlength="50" class="input" /></td>
			</tr>
			<tr>
				<th>교환/반품정보</th>
				<td class="space-left"><textarea name="memo" cols="70" rows="4" style="height:36px" class="input" /></textarea></td>
			</tr>						
			<tr>
				<th>매입가</th>
				<td class="space-left"><input type="text" name="p_price" style="width:200" maxlength="20" class="input" /> (숫자만 입력)</td>
			</tr>
			<tr>
				<th>소비자가</th>
				<td class="space-left"><input type="text" name="sale_price" style="width:200" maxlength="20" class="input" /> (숫자만 입력)</td>
			</tr>
			<tr>
				<th>판매가</th>
				<td class="space-left"><input type="text" name="price" style="width:200" maxlength="20" class="input" /> (숫자만 입력)</td>
			</tr>
			<!-- <tr>
				<th>적립금</th>
				<td class="space-left"><input type="text" name="point" style="width:100" maxlength="20" value="0" class="input" />
				<select name="point_unit">
				<option value="F">고정금액</option>
				<option value="P">%</option>
				</select>
				</td>
			</tr> -->
			<tr>
				<th>상품진열</th>
				<td class="space-left"><input type="radio" id="is_show_y" name="is_show" value="Y" checked><label for="is_show_y"><font color=blue>진열함</font></label>
				<input type="radio" id="is_show_n" name="is_show" value="N"><label for="is_show_n"><font color=red>진열안함</font></label>
				</td>
			</tr>
			<tr>
				<th>메인노출 여부</th>
				<td class="space-left">
				<input type="checkbox" id="main_show" name="main_show" value="Y"><label for="main_show"><font color=blue>MAIN</font></label> &nbsp;
				<!--input type="checkbox" id="brand_show" name="brand_show" value="Y"><label for="brand_show"><font color=green>Brand Pick</font></label> &nbsp;
				<input type="checkbox" id="special_show" name="special_show" value="Y"><label for="special_show"><font color=red>SPECIAL Pick</font></label> &nbsp;
				<input type="checkbox" id="best_show" name="best_show" value="Y"><label for="best_show">BEST PRODUCT</label--> &nbsp;
				</td>
			</tr>
			<tr>
				<th>재고관리</th>
				<td class="space-left">
				<input type="radio" id="stock_type_1" name="stock_type" value="1" checked onclick="checkStockManage(1);"><label for="stock_type_1"><font color=blue>재고관리 안함</font></label> &nbsp;
				<input type="radio" id="stock_type_2" name="stock_type" value="2" onclick="checkStockManage(2);"><label for="stock_type_2"><font color=green>일반 재고관리</font></label> &nbsp;
				<!-- <input type="radio" id="stock_type_3" name="stock_type" value="3" onclick="checkStockManage(3);"><label for="stock_type_3"><font color=red>연계 재고관리</font></label> -->
				<table class="admin-table-type1" id="layerStock" style="position:relative; display:none; width:200px !important;">
					<tr>
						<th>재고량</th>
						<td class="space-left"><input type="text" name="stock" style="width:100" maxlength="10" class="input" /></td>
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
						<span class="btn_pack medium icon"><span class="delete"></span><a href="javascript:remove_opt();">옵션 삭제</a></span><!--  
				<input type="radio" id="option_show_n" name="option_show" value="N" checked onclick="javascript:optChkyn(2)"><label for="option_show_n">옵션 불러오기</label> &nbsp;
				<input type="radio" id="option_show_y" name="option_show" value="Y" onclick="javascript:optChkyn(1)"><label for="option_show_y">직접 입력하기</label> &nbsp;</h3>		 -->
		<!-- 옵션관련 변수 처리는 /common/js/shop.js 에서 처리합니다 -->
		<input type="hidden" id="opt_hidden_count" name="opt_hidden_count">
		<!-- 개별 재고관리 -->
		<table class="admin-table-type1" id="layerOption1" style="position:relative; display:show;">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>추가옵션</th>
				<td>
					<div style="width:480px;">		
						<table class="admin-table-type1" id="product_opt">
						  <colgroup>
						  <col width="150" />
						  <col width="200" />
						  <col width="130" />
						  </colgroup>
						  <tbody>
							<tr>
								<th class="space-center">옵션명</th>
								<th class="space-center">옵션항목</th>
								<th class="space-center">항목설정</th>
							</tr>
						  </tbody>
						</table>
					</div>
				</td>
			</tr>
		  </tbody>
		</table>
		<!-- 개별 재고관리 -->

		<table class="admin-table-type1" id="layerOption3" style="position:relative; display:none;">
		  <colgroup>
		  <col width="140" />
		  <col width="*" />
		  </colgroup>
		  <tbody>
			<tr>
				<th>옵션 불러오기</th>
				<td class="space-left"><span class="btn_pack medium icon"><span class="download"></span><a href="javascript:add_opt();">옵션추가</a></span></td>
			</tr>
			<tr>
				<th>사용된 옵션</th>
				<td class="space-left">
					<div style="width:480px;">		
						<table class="admin-table-type1" id="option_opt11">
						  <colgroup>
						  <col width="150" />
						  <col width="200" />
						  <col width="130" />
						  </colgroup>
						  <tbody>
							<tr>
								<th class="space-center">옵션명</th>
								<th class="space-center">옵션항목</th>
								<th class="space-center">항목설정</th>
							</tr>
						  </tbody>
						</table>
					</div>
				</td>
			</tr>
		  </tbody>
		</table>

		<!-- 연계 재고관리 -->
		<div id="layerOption2" style="position:relative; display:none;">
			<div class="mgb5">세로/가로,  재고,추가금 (세로, 가로의 항목명이 없는 것, 값이 없는 옵션은 포함되지 않습니다.)</div>
			<table class="admin-table-type1">
			  <tbody>
				<tr> 
				  <td width="10%"><table width="100%" cellspacing="0" cellpadding="0"></tr><td width="50%" bgcolor="#000000" align="center"><input type="text" name="relOptName2" style="width:50" class='input' maxlength="50" value="세로항목"></td><td width="10"></td><td width="50%" bgcolor="#FFCC00" align="center"><input type="text" name="relOptName1" style="width:50" class='input' maxlength="50" value="가로항목"></td></tr></table></td>
				  <?for($i=0;$i<9;$i++){?>
				  <?switch($i){
					  case(0):$val="옵션1";break;
					  case(1):$val="옵션2";break;
					  default:$val="";break;
				  }
				  ?>
				  <td bgcolor="#FFCC00" width="10%"><input name="relOpt1_<?=$i?>" type="text" class='input' value="<?=$val?>" size="4"> <input name="" type="text" class='input' value="추가금" size="6" readonly></td>
				  <?}?>
				</tr>

				<?for($i=0;$i<9;$i++){?>
				  <?switch($i){
					  case(0):$val="옵션A";break;
					  case(1):$val="옵션B";break;
					  default:$val="";break;
				  }
				  ?>
				<tr> 
				  <td bgcolor="#000000"><input name="relOpt2_<?=$i?>" type="text" class='input' value="<?=$val?>" size="10"></td>
				  <?for($j=0;$j<9;$j++){?>
				  <td>
				  <input name="rel_stock_<?=$i?>_<?=$j?>" type="text" class='input' size="4">
				  <input name="rel_price_<?=$i?>_<?=$j?>" type="text" class='input' size="6">
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
		<h3 class="admin-title-middle">상품 사진 | <input type="radio" id="image_type_1" name="image_type" value="1" checked onclick="checkImageType(1);"><label for="image_type_1"><font color=blue>대표이미지</font></label> <!-- <input type="radio" id="image_type_2" name="image_type" value="2" onclick="checkImageType(2);"><label for="image_type_2"><font color=green>개별이미지</font></label> --></h3>
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
				for($i=0;$i<$_SITE["PRODUCT"]["IMAGE_COUNT"];$i++){
				?>
				<tr>
				  <th>사진 <?=$i+1?></th>
				  <td class="space-left"><input type="file" name="photo_file[]" style="width:50%" class="input" /> <input type="radio" name="p_image" value="<?=$i?>" id="idPhoto<?=$i?>"<?=$i==0?" checked":""?>><label for="idPhoto<?=$i?>">대표이미지</label></td>
				</tr>
				<?
				}
				?>
			  </tboday>
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
				  <td class="space-left"><input type="file" name="photo_file_s" style="width:50%" class="input" /> </td>
				</tr>
				<tr>
				  <th>상품목록 사진</th>
				  <td class="space-left"><input type="file" name="photo_file_m" style="width:50%" class="input" /> </td>
				</tr>
				<tr>
				  <th>상세정보 사진</th>
				  <td class="space-left"><input type="file" name="photo_file_l" style="width:50%" class="input" /> </td>
				</tr>
				<?
				for($i=0;$i<$_SITE["PRODUCT"]["IMAGE_COUNT"];$i++){
				?>
				<tr>
				  <th>확대사진 <?=$i+1?></th>
				  <td class="space-left"><input type="file" name="photo_file[]" style="width:50%" class="input" /> </td>
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
				<textarea id="contents" name="contents"></textarea>
				<?
				$CKContent = "contents";
				include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
				?>
				</td>
			</tr>
		</table>

		<div class="admin-buttons">
			<div class="cen">
				<span class="btn_pack xlarge icon"><span class="check"></span><input type="submit" value="상품등록" style="font-weight:bold" /></span>
			</div>
		</div>	


		</form>
	</div>
</div>
<div id="layerProductOpt" style="position:absolute; display:none; background-color:#FFCC00; border-size:3px;bordercolor:#CCCCCC;"></div>
<div id="layerImageShow" style="position:absolute; display:none; background-color:#FFFFFF; border-size:3px;bordercolor:#EEEEEE"></div>
<div id="layerGoodCat" style="position:absolute; display:none; background-color:#FFCC00; border-size:3px;bordercolor:#CCCCCC"></div>
<div id="layerRelGood" style="position:absolute; display:none; background-color:#FF0000; border-size:3px;bordercolor:#CCCCCC">
<iframe id="iframeRelGood" name="iframeRelGood" border="1" width="800" height="400"></iframe>
</div>
<?
//DB해제
SetDisConn($dblink);
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>
