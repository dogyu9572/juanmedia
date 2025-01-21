//옵션 추가정보 열 추가
var fixed_ea = 5;
var rowcount_opt = 0;
function append_opt() {
	if(rowcount_opt < fixed_ea){
		var tbl = document.getElementById("product_opt").getElementsByTagName("TBODY")[0];  
		var html1 = "<input type='hidden' id='opt_hidden_value_"+rowcount_opt+"' name='opt_hidden_value_"+rowcount_opt+"'><input type='text' id='opt_subject_"+rowcount_opt+"' name='opt_subject_"+rowcount_opt+"' style='width:100%' maxlength='250' class='input' />";  
		var html2 = "<select id='opt_contents_"+rowcount_opt+"' name='opt_contents_"+rowcount_opt+"' style='width:100%'></select>";   
		var html3 = "<a href='javascript:void(0);' onclick='LayerShowProductOpt("+rowcount_opt+", event)'><img src='/backoffice/images/k_add.gif' alt='추가' /></a>  <a href='javascript:void(0);' onclick='getProductOpt("+rowcount_opt+", document.getElementById(\"opt_contents_"+rowcount_opt+"\").selectedIndex, document.getElementById(\"opt_contents_"+rowcount_opt+"\").value, event)'><img src='/backoffice/images/k_modify.gif' alt='수정' /></a> <a href='javascript:void(0);' onclick='delProductOpt("+rowcount_opt+", document.getElementById(\"opt_contents_"+rowcount_opt+"\").selectedIndex)'><img src='/backoffice/images/k_delete.gif' alt='삭제' /></a>";   
		var row = document.createElement("tr"); 
		var col1 = document.createElement("td");   
		var col2 = document.createElement("td"); 
		var col3 = document.createElement("td"); 
		row.appendChild(col1);  
		row.appendChild(col2);
		row.appendChild(col3);
		col1.innerHTML = html1;  
		col2.innerHTML = html2;  
		col3.innerHTML = html3;  
		tbl.appendChild(row);  
		rowcount_opt++;
	}else{
		alert("옵션은 최대 "+fixed_ea+" 개까지 추가할 수 있습니다.");
	}
}

function remove_opt() {  
	if(rowcount_opt > -1){
		var tbl = document.getElementById("product_opt").getElementsByTagName("TBODY")[0];  
		if (tbl.hasChildNodes()) {      
			tbl.removeChild(tbl.lastChild);     // 마지막 로우   //tbl.removeChild(tbl.firstChild);  // 첫번째 로우  
		}
		rowcount_opt--;
	}
}
//옵션 추가정보 열 추가


var rowcount_opt2 = 0;
function append_opt2() {
	var tbl = document.getElementById("option_opt").getElementsByTagName("TBODY")[0];  
	var html1 = "<input type='text' id='o_name[]' name='o_name[]' style='width:200px' maxlength='250' class='input' placeholder='예) 블랙' />";  
	var html2 = "<input type='text' id='o_price[]' name='o_price[]' style='width:100px' maxlength='100' class='input' value='0' />";   
	var row = document.createElement("tr"); 
	var col1 = document.createElement("td");   
	var col2 = document.createElement("td"); 
	row.appendChild(col1);  
	row.appendChild(col2);
	col1.innerHTML = html1;  
	col2.innerHTML = html2;  
	tbl.appendChild(row);  
	rowcount_opt2++;
}

function remove_opt2() {  
	if(rowcount_opt2 > 0){
		var tbl = document.getElementById("option_opt").getElementsByTagName("TBODY")[0];  
		if (tbl.hasChildNodes()) {      
			tbl.removeChild(tbl.lastChild);     // 마지막 로우   //tbl.removeChild(tbl.firstChild);  // 첫번째 로우  
		}
		rowcount_opt2--;
	}
}
//옵션 추가정보 열 추가

function add_opt() {
	var obj = window.open("/backoffice/module/shop/opt_popup.php?rowcount_opt="+rowcount_opt,"opt_pop","width=900,height=500,scrollbars=yes");
	obj.focus();
}

//레이어 닫기
function LayerHideProductOpt() {
	$('layerProductOpt').hide();
}


// 세부 항목추가 레이어 보이기 =========================
function LayerShowProductOpt(o_no, e){
	layerPositionSet('layerProductOpt', e);
		$('layerProductOpt').innerHTML = "<table width='130'><tr><form name='optInsertFrm'><td>항목명</td><td><input type='text' name='newOpt' id='newOpt' size='10'></td></tr><tr><td>추가금</td><td><input type='text' name='newOptPrice' id='newOptPrice' size='10'></td></tr><tr><td colspan='2' align='center'><input type='button' value='입력' onClick='addProductOpt("+o_no+", document.getElementById(\"newOpt\").value, document.getElementById(\"newOptPrice\").value);'>&nbsp;&nbsp;&nbsp;<input type='button' value='취소' onclick='LayerHideProductOpt();'></td></tr></form></table>";
}

// 세부 항목수정 레이어 보이기 =========================
function LayerShowProductOptEdit(o_no, o_name, o_price, idx, e){
	if(idx=="-1"){
		alert("수정할 항목을 선택하세요.");
	}else{
		layerPositionSet('layerProductOpt', e);
		$('layerProductOpt').innerHTML = "<table width='130'><tr><form name='optInsertFrm'><td>항목명</td><td><input type='text' name='newOpt' id='newOpt' size='10'  value="+o_name+"></td></tr><tr><td>추가금</td><td><input type='text' name='newOptPrice' id='newOptPrice' size='10' value="+o_price+"></td></tr><tr><td colspan='2' align='center'><input type='button' value='수정' onclick='editProductOpt("+o_no+", document.getElementById(\"newOpt\").value, document.getElementById(\"newOptPrice\").value, "+idx+");'>&nbsp;&nbsp;&nbsp;<input type='button' value='취소' onclick='LayerHideProductOpt();'></td></tr></form></table>";
	}
}

//옵션 항목추가
function addProductOpt(o_no, o_name, o_price){
	var div_id = "opt_contents_" + o_no;
	$(div_id).options[$(div_id).length] = new Option(o_name+"|"+o_price,o_name+"|"+o_price);
	LayerHideProductOpt();
}

//옵션 항목수정
function editProductOpt(o_no, o_name, o_price, idx){
	var div_id = "opt_contents_" + o_no;
	$(div_id).options[idx] = new Option(o_name+"|"+o_price,o_name+"|"+o_price);
	LayerHideProductOpt();
}

//옵션 항목정보 가져오기
function getProductOpt(o_no, idx, val, e){
	var optArray = new Array();
	optArray = val.split("|");
	LayerShowProductOptEdit(o_no, optArray[0], optArray[1], idx, e);
}

//옵션 항목삭제
function delProductOpt(o_no, idx){
	var div_id = "opt_contents_" + o_no;
	if(idx=="-1"){
		alert("삭제할 항목을 선택하세요.");
	}else{
		$(div_id).options[idx] = null;
	}

}



// 상품분류 추가 레이어 보이기 =========================

//레이어 닫기
function LayerHideGoodCat() {
	$('layerGoodCat').hide();
}

function LayerShowGoodCat(e){
	layerPositionSet('layerGoodCat',e);
	new Ajax.Request('/module/shop/ajax_ext_cat.php',
	{
		method:'get',
		parameters: {gb: 'a'},
		asynchronous: this.asynchronous,
		encoding: 'euc-kr',
		contentType: 'application/x-www-form-urlencoded',

		onSuccess: function(transport){
			var response = transport.responseText || "응답된 내역이 없습니다."; 
			//alert(transport.responseText);
			$('layerGoodCat').innerHTML = response;
		},
		
		onFailure: function(){ 
			alert('AJAX 데이터 응답중 오류가 발생하였습니다.') 
		}   
	});
}

function LayerShowGoodSearch(e){
	layerPositionSet('layerGoodCat',e);
	new Ajax.Request('/module/shop/ajax_ext_cat.php',
	{
		method:'get',
		parameters: {gb: 'b'},
		asynchronous: this.asynchronous,
		encoding: 'euc-kr',
		contentType: 'application/x-www-form-urlencoded',

		onSuccess: function(transport){
			var response = transport.responseText || "응답된 내역이 없습니다."; 
			//alert(transport.responseText);
			$('layerGoodCat').innerHTML = response;
		},
		
		onFailure: function(){ 
			alert('AJAX 데이터 응답중 오류가 발생하였습니다.') 
		}   
	});
}

//상품분류 항목추가
function addGoodCat(cat_no){
	if(cat_no !=""){
		var div_id = "ext_cat";
		$(div_id).options[$(div_id).length] = new Option(arrayAllCategory[cat_no], cat_no);
		LayerHideGoodCat();
	}else{
		alert("분류를 선택하세요.");
		$("t_ext_cat1").focus();
	}
}

//상품분류 항목삭제
function delGoodCat(idx){
	var div_id = "ext_cat";
	if(idx=="-1"){
		alert("삭제할 항목을 선택하세요.");
	}else{
		$(div_id).options[idx] = null;
	}
}

//검색분류 항목추가
function addGoodSearch(cat_no){
	if(cat_no !=""){
		var div_id = "ext_search";
		$(div_id).options[$(div_id).length] = new Option(arrayAllCategory[cat_no], cat_no);
		LayerHideGoodCat();
	}else{
		alert("분류를 선택하세요.");
		$("t_ext_cat1").focus();
	}
}

//검색분류 항목삭제
function delGoodSearch(idx){
	var div_id = "ext_search";
	if(idx=="-1"){
		alert("삭제할 항목을 선택하세요.");
	}else{
		$(div_id).options[idx] = null;
	}
}



// 관련상품 추가 레이어 보이기 =========================

//레이어 닫기 - iframe 에서 부르기 때문에 parent
function LayerHideRelGood() {
	parent.$('#layerRelGood').hide("slow");
}

function LayerShowRelGood(e){
	layerPositionSet('layerRelGood', e);
	document.getElementById('iframeRelGood').src = "/backoffice/module/shop/rel_good.php";
}

//상품분류 항목삭제
function delRelGood(idx){
	var div_id = "rel_good";
	if(idx=="-1"){
		alert("삭제할 항목을 선택하세요.");
	}else{
		document.getElementById(div_id).options[idx] = null;
	}
}

//체크한 아이템 주문 - iframe 에서 부르기 때문에 parent
function addRelGoodChecked(f){
	//1개이상 체크했는지 검사
	var obj = document.getElementsByName('items[]'); 
	var objlength = obj.length;
	var objchecked = 0;
	var objstring = new Array();
	var arr = new Array();

	for(i=0; i<objlength; i++){
		if(obj[i].checked==true){
			objstring[objchecked] = obj[i].value;
			objchecked++;
		}
	}

	if(objchecked < 1){
		alert("선택하신 상품이 없습니다.");
		return;
	}

	for(i=0;i<objstring.length;i++){
		
		arr = objstring[i].split("|");
		parent.$("#rel_good").append("<li><input type='hidden' name='goods[]' value='"+arr[0]+"'><div class='pic'><img src='/uploaded/shop_good/"+arr[0]+"/"+arr[1]+"' width='60'></div>"+arr[2]+" <a href='#' id='btnDelete'><img src='/backoffice/images/k_delete.gif' alt='삭제' /></a></li>");
	}

	LayerHideRelGood();
}
// 관련상품 추가 레이어 보이기 =========================


function optChkyn(gb) {
	if(gb=="1"){
		$('layerOption1').show();
		$('layerOption3').hide();
	}else if(gb=="2"){
		$('layerOption1').hide();
		$('layerOption3').show();
	}
}


//재고관리 설정
function checkStockManage(st){
	if(st=="1"){
		$('layerStock').hide();
		$('layerOption2').hide();
	}else if(st=="2"){
		$('layerStock').show();
		$('layerOption2').hide();
	}else if(st=="3"){
		$('layerStock').hide();
		$('layerOption2').show();
	}
}

//이미지 타입 설정
function checkImageType(st){
	if(st=="2"){
		$('layerImage1').hide();
		$('layerImage2').show();
	}else{
		$('layerImage1').show();
		$('layerImage2').hide();
	}
}

function viewSendForm(gb) {
	if(gb == "1") {
		document.getElementById('ship_name').disabled = false;
		document.getElementById('ship_mobile1').disabled = false;
		document.getElementById('ship_mobile2').disabled = false;
		document.getElementById('ship_mobile3').disabled = false;
		document.getElementById('email_id').disabled = false;
		document.getElementById('email_domain').disabled = false;
		document.getElementById('order_comment').disabled = false;
		document.getElementById('mail_ms').disabled = false;
		document.getElementById('mail_m').disabled = false;
		document.getElementById('mail_s').disabled = false;
	} else {
		document.getElementById('ship_name').disabled = true;
		document.getElementById('ship_mobile1').disabled = true;
		document.getElementById('ship_mobile2').disabled = true;
		document.getElementById('ship_mobile3').disabled = true;
		document.getElementById('email_id').disabled = true;
		document.getElementById('email_domain').disabled = true;
		document.getElementById('order_comment').disabled = true;
		document.getElementById('mail_ms').disabled = true;
		document.getElementById('mail_m').disabled = true;
		document.getElementById('mail_s').disabled = true;
	}
}

//상품 입력폼 체크
function goodCheckForm(frm){
	if (frm.cat_no.value==""){
		alert("상품분류를 선택해 주십시요.");
		frm.cat.focus();
		return false;
	}
	if (frm.g_name.value==""){
		alert("상품명을 입력해 주십시요.");
		frm.g_name.focus();
		return false;
	}
	
	//상품 옵션정보 데이터 조합
	$("opt_hidden_count").value=0;
	for(i=0;i<fixed_ea;i++){
		try{
			var opt_subject_id = "opt_subject_" + i;
			var opt_contents_id = "opt_contents_" + i;
			var opt_value_id = "opt_hidden_value_" + i;
			for(j=0;j<$(opt_contents_id).length;j++){
				$(opt_value_id).value += $(opt_contents_id)[j].value;
				//마지막이 아니면 구분자 추가
				if(j+1 != $(opt_contents_id).length){
					$(opt_value_id).value += "|:|";
				}
			}
			$("opt_hidden_count").value++;
		}catch(e){}
	}


	//추가카테고리 조합
	try{
		for(j=0;j<$("ext_cat").length;j++){
			$("ext_cat_hidden").value += $("ext_cat")[j].value;
			//마지막이 아니면 구분자 추가
			if(j+1 != $("ext_cat").length){
				$("ext_cat_hidden").value += "|:|";
			}
		}
	}catch(e){}

	//추가검색카테고리 조합
	try{
		for(j=0;j<$("ext_search").length;j++){
			$("ext_search_hidden").value += $("ext_search")[j].value;
			//마지막이 아니면 구분자 추가
			if(j+1 != $("ext_search").length){
				$("ext_search_hidden").value += "|:|";
			}
		}
	}catch(e){}


	//관련상품 조합 : 상품의 idx 만 저장할 것이기 때문에 구분자를 | 하나만 함
	try{
		for(j=0;j<$("rel_good").length;j++){
			$("rel_good_hidden").value += $("rel_good")[j].value;
			//마지막이 아니면 구분자 추가
			if(j+1 != $("rel_good").length){
				$("rel_good_hidden").value += "|";
			}
		}
	}catch(e){}

	try{ contents.outputBodyHTML(); } catch(e){ }
	try{ mokcha.outputBodyHTML(); } catch(e){ }
	try{ author_text.outputBodyHTML(); } catch(e){ }
}

//이미지 상세보기 레이어
function LayerShowImage(img, e){
	layerPositionSet('layerImageShow', e);
	$('layerImageShow').innerHTML = "<a href='javascript:;' onclick='LayerHideImage();'><img src='/uploaded/shop_good/"+img+"' border=0></a>";
}

//레이어 닫기
function LayerHideImage() {
	$('layerImageShow').hide();
}

//연계재고 옵션 가져오기
function getRelOpt(idx, opt_1){
	if(idx != "" || opt_1){
		new Ajax.Request('/module/shop/ajax_get_rel_opt.php',
		{
			method:'get',
			parameters: {idx: idx, opt_1: opt_1},
			asynchronous: this.asynchronous,
			encoding: 'utf-8',
			contentType: 'application/x-www-form-urlencoded',

			onSuccess: function(transport){
				var response = transport.responseText || "응답된 내역이 없습니다."; 
				//alert(transport.responseText);
				setRelOpt(response);
			},
			
			onFailure: function(){ 
				alert('AJAX 데이터 응답중 오류가 발생하였습니다.') 
			}   
		});
	}

}

function setRelOpt(txt){
	//alert(txt);
	var opt = new Array();
	var arr = new Array();
	var stk;
	var prc;
	opt = txt.split("||");
	//초기화	
	for(i=1; i< $("opt_rel_contents_1").length; i++){
		$("opt_rel_contents_1").options[i] = null;
	}
	for(i=0; i<opt.length; i++){
		arr = opt[i].split("**");

		if(arr[1] > "0"){
			prc = " (+"+addComma(arr[1])+")";
		}else{
			prc = "";
		}

		//재고가 없을경우 값을 -1 로 설정
		if(arr[2]=="0"){
			$("opt_rel_contents_1").options[i+1] = new Option(arr[0]+" (품절)","-1");
		}else{
			$("opt_rel_contents_1").options[i+1] = new Option(arr[0]+prc,arr[0]+"|"+arr[1]);
		}
	}
}

function preOpt(price, str) {
	var opt_price = str.split("|");

	document.getElementById("pre_opt_1").value = opt_price[0];
	document.getElementById("pre_opt_2").value = opt_price[1];
}

var fixed_ea2 = 10;
var rowcount_opt3 = 0;
var vtxt;
function setAddOpt(price, str, pre)
{
	
	if(pre == "Y") {
		var pre_name = document.getElementById("pre_opt_1").value + "-";

		if(pre_name=="-") {
			alert("상위 옵션부터 선택해주세요.");			
			$("#opt_contents_1 option:eq(0)").attr("selected", "selected");
			return ;
		}
	} else {
		var pre_name = "";
	}
	var pre_price = document.getElementById("pre_opt_2").value;

	if(str) {
		var opt_price = str.split("|");

		if(pre == "Y") {
			var defaultName = pre_name+opt_price[0];
		} else {
			var defaultName = opt_price[0];
		}

		if(opt_price[1] > "0"){
			var sprice = parseInt(price) + parseInt(opt_price[1]) + parseInt(pre_price);
		} else if(opt_price[1] < "0"){
			var sprice = parseInt(price) + parseInt(opt_price[1]) + parseInt(pre_price);
		} else {
			var sprice = parseInt(price) + parseInt(pre_price);
		}

		try{
			var name_0 = document.getElementById("opt_name_0").value;
		}catch(e){}
		try{
			var name_1 = document.getElementById("opt_name_1").value;
		}catch(e){}
		try{
			var name_2 = document.getElementById("opt_name_2").value;
		}catch(e){}
		try{
			var name_3 = document.getElementById("opt_name_3").value;
		}catch(e){}
		try{
			var name_4 = document.getElementById("opt_name_4").value;
		}catch(e){}
		try{
			var name_5 = document.getElementById("opt_name_5").value;
		}catch(e){}
		try{
			var name_6 = document.getElementById("opt_name_6").value;
		}catch(e){}
		try{
			var name_7 = document.getElementById("opt_name_7").value;
		}catch(e){}
		try{
			var name_8 = document.getElementById("opt_name_8").value;
		}catch(e){}
		try{
			var name_9 = document.getElementById("opt_name_9").value;
		}catch(e){}

		setOptPrice();
		

		
		if( ( name_0 != defaultName) && (name_1 != defaultName) && (name_2 != defaultName) && (name_3 != defaultName) && (name_4 != defaultName) && ( name_5 != defaultName) && (name_6 != defaultName) && (name_7 != defaultName) && (name_8 != defaultName) && (name_9 != defaultName) ) {
			if (rowcount_opt3 < fixed_ea2) {
				
				var tbl = document.getElementById("addOptDivBody").getElementsByTagName("TBODY")[0]; 
				vtxt ="	<div>";
				vtxt +="	<input type='hidden' id='opt_price_"+rowcount_opt3+"' name='opt_price_"+rowcount_opt3+"' value='"+sprice+"'>";
				vtxt +="	<input type='hidden' id='opt_name_"+rowcount_opt3+"' name='opt_name_"+rowcount_opt3+"' value='"+pre_name+opt_price[0]+"'>";
				if(pre == "Y") {
					vtxt +="	<input type='hidden' id='opt_plus_price_"+rowcount_opt3+"' name='opt_plus_price_"+rowcount_opt3+"' value='"+pre_price+"'>";
				} else {
					vtxt +="	<input type='hidden' id='opt_plus_price_"+rowcount_opt3+"' name='opt_plus_price_"+rowcount_opt3+"' value='"+opt_price[1]+"'>";
				}				
				vtxt +="	<span>"+pre_name+opt_price[0]+"</span>";	
				
				vtxt +="	<span><div class=\"countInput\">";	
				vtxt +="		<a class='btnMin' onclick='decAmount2("+rowcount_opt3+")'></a>";	
				vtxt +="		<input type=\"text\" readonly value=\"1\" name='qty_"+rowcount_opt3+"' id='qty_"+rowcount_opt3+"' maxlength='4' value='1' readonly onfocus='blur()' >";	
				vtxt +="		<a class='btnPlus' onclick='incAmount2("+rowcount_opt3+")'></a></div>";	
				vtxt +="	</span>";	

				vtxt +="	<span style='padding:10px;'><strong id='opt_price_v_"+rowcount_opt3+"'>"+addComma(sprice)+"원</strong> <a href='javascript:;' onclick='remove_this(this)' style='padding-left:5px'><img src='/img/icon_delete.png' alt='삭제' /></a></span>";		
				vtxt +="</div>";

				var row = document.createElement("tr"); 
				var col1 = document.createElement("td");   
				row.appendChild(col1);  
				col1.innerHTML = vtxt;  
				tbl.appendChild(row);  
				rowcount_opt3++;

				totalAmount();
				
			}else{
				alert("옵션은 최대 "+fixed_ea2+" 개까지 추가할 수 있습니다.");
			}
		}
	
	}	
}

function remove_this(obj)
{
   var lo_table      = obj.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode  //버튼으로 부터 위로 4번째 객체는 테이블이지 
   var li_row_index  = obj.parentNode.parentNode.parentNode.parentNode.parentNode.rowIndex; // 버튼으로 부터 위로 2번째니깐 TR 이겠지 그 TR 의 INDEX 값
   lo_table.deleteRow(li_row_index); // 위에서 찾은 인덱스에 해당하는 TR 을 삭제하라...
   rowcount_opt3--;

	totalAmount();
}


function incAmount2_(num){
	var qty = document.getElementById('qty_'+num).value;
	document.getElementById('qty_'+num).value = ++qty;

	totalAmount();
}

// 수량 감소
function decAmount2_(num){
   var qty = document.getElementById('qty_'+num).value;
	if(qty > 1)
		document.getElementById('qty_'+num).value = --qty;
	
	totalAmount();
}


//총상품금액
function totalAmount_() {
	var t_price, t_price1, t_price2, t_price3, t_price4, t_price5, t_qty1, t_qty2, t_qty3, t_qty4, t_qty5;
	t_price1 = 0;
	t_price2 = 0;
	t_price3 = 0;
	t_price4 = 0;
	t_price5 = 0;
	t_qty1 = 0;
	t_qty2 = 0;
	t_qty3 = 0;
	t_qty4 = 0;
	t_qty5 = 0;
	t_price = t_price1 + t_price2 + t_price3 + t_price4 + t_price5;

	try{
		t_price1 = document.getElementById("opt_price_0").value;
		t_qty1 = document.getElementById("qty_0").value;
		if(t_price1 > 0){
			t_price = t_price + parseInt(t_price1 * t_qty1);
			document.getElementById('opt_price_v_0').innerHTML = addComma(t_price1 * t_qty1)+"원";
		}
	}catch(e){}

	try{
		t_price2 = document.getElementById("opt_price_1").value;
		t_qty2 = document.getElementById("qty_1").value;
		if(t_price2 > 0){
			t_price = t_price + parseInt(t_price2 * t_qty2);
			document.getElementById('opt_price_v_1').innerHTML = addComma(t_price2 * t_qty2)+"원";
		}
	}catch(e){}

	try{
		t_price3 = document.getElementById("opt_price_2").value;
		t_qty3 = document.getElementById("qty_2").value;
		if(t_price3 > 0){
			t_price = t_price + parseInt(t_price3 * t_qty3);
			document.getElementById('opt_price_v_2').innerHTML = addComma(t_price3 * t_qty3)+"원";
		}
	}catch(e){}

	try{
		t_price4 = document.getElementById("opt_price_3").value;
		t_qty4 = document.getElementById("qty_3").value;
		if(t_price4 > 0){
			t_price = t_price + parseInt(t_price4 * t_qty4);
			document.getElementById('opt_price_v_3').innerHTML = addComma(t_price4 * t_qty4)+"원";
		}
	}catch(e){}

	try{
		t_price5 = document.getElementById("opt_price_4").value;
		t_qty5 = document.getElementById("qty_4").value;
		if(t_price5 > 0){
			t_price = t_price + parseInt(t_price5 * t_qty5);
			document.getElementById('opt_price_v_4').innerHTML = addComma(t_price5 * t_qty5)+"원";
		}
	}catch(e){}

	total_price = parseInt(t_price);

	if(total_price == 0) {
		document.getElementById('divPrice').innerHTML = "";
	} else {
		document.getElementById('divPrice').innerHTML = addComma(total_price)+"원";
	}

}


//옵션가격 설정
function setOptPrice(){
	var price;
	var point;
	var total_price;
	var opt_price;	
	var cnt;
	price = parseInt(document.getElementById("basicPrice").value);
	point = parseInt(document.getElementById("basicPoint").value);
	cnt = parseInt(document.getElementById("qty").value);
	if(cnt > 0){
		cnt = cnt;
	}else{
		cnt = 0;
	}
 

	//옵션가격 초기화
	var option_price1, option_price2, option_price3, option_price4, option_price5, option_price_rel1, option_price_rel2;
	option_price1 = 0;
	option_price2 = 0;
	option_price3 = 0;
	option_price4 = 0;
	option_price5 = 0;
	option_price_rel1 = 0;
	option_price_rel2 = 0;
	opt_price = option_price1 + option_price2 + option_price3 + option_price4 + option_price5 + option_price_rel1 + option_price_rel2;

	try{
		option_price1 = document.getElementById("opt_contents_0").value.split("|")[1];
		if(option_price1 > 0){
			opt_price = opt_price + parseInt(option_price1);
		}
	}catch(e){}
	try{
		option_price2 = document.getElementById("opt_contents_1").value.split("|")[1];
		if(option_price2 > 0){
			opt_price = opt_price + parseInt(option_price2);
		}
	}catch(e){}
	try{
		option_price3 = document.getElementById("opt_contents_2").value.split("|")[1];
		if(option_price3 > 0){
			opt_price = opt_price + parseInt(option_price3);
		}
	}catch(e){}
	try{
		option_price4 = document.getElementById("opt_contents_3").value.split("|")[1];
		if(option_price4 > 0){
			opt_price = opt_price + parseInt(option_price4);
		}
	}catch(e){}
	try{
		option_price5 = document.getElementById("opt_contents_4").value.split("|")[1];
		if(option_price5 > 0){
			opt_price = opt_price + parseInt(option_price5);
		}
	}catch(e){}
 	try{
		option_price_rel1 = document.getElementById("opt_rel_contents_1").value.split("|")[1];
		if(option_price_rel1 > 0){
			opt_price = opt_price + parseInt(option_price_rel1);
		}
	}catch(e){}
	try{
		option_price_rel2 = document.getElementById("opt_rel_contents_2").value.split("|")[1];
		if(option_price_rel2 > 0){
			opt_price = opt_price + parseInt(option_price_rel2);
		}
	}catch(e){}
	total_price = (price+opt_price)*cnt;

	try{
	document.getElementById("divPrice").innerHTML= addComma(total_price)+"원";
	document.getElementById("divPrice2").innerHTML= addComma(total_price)+"원";
	}catch(e){}
	try{
	document.getElementById("divPoint").innerHTML= addComma(point*cnt);
	}catch(e){}
}

//옵션있는 상품 바로구매
function buyOptDirect(g_idx){
	var opt_1, opt_2, opt_3, opt_4, opt_5, qty_1, qty_2, qty_3, qty_4, qty_5;
	var topt=0;

	for(i=0;i<fixed_ea;i++){
		try{
			var opt_name_id = document.getElementById("opt_name_" + i).value;
			var opt_contents_id = document.getElementById("opt_plus_price_" + i).value;
			var qty_id = document.getElementById("qty_" + i).value;

			if(i==0){ opt_1 = opt_name_id+"|"+opt_contents_id; qty_1=qty_id; topt++; }
			if(i==1){ opt_2 = opt_name_id+"|"+opt_contents_id; qty_2=qty_id; topt++; }
			if(i==2){ opt_3 = opt_name_id+"|"+opt_contents_id; qty_3=qty_id; topt++; }
			if(i==3){ opt_4 = opt_name_id+"|"+opt_contents_id; qty_4=qty_id; topt++; }
			if(i==4){ opt_5 = opt_name_id+"|"+opt_contents_id; qty_5=qty_id; topt++; }

		}catch(e){}
	}
	
	if(document.getElementById("opt_name_0")==null) {
		alert("옵션을 선택해주세요.");
		return;
	} else {

		$.post("/module/shop/ajax_cart_process.php", { evnMode: "direct2", g_idx: g_idx, qty_1: qty_1, qty_2: qty_2, qty_3: qty_3, qty_4: qty_4, qty_5: qty_5, opt_1: opt_1, opt_2: opt_2, opt_3: opt_3, opt_4: opt_4, opt_5: opt_5, topt: topt },
		function(data){
			if(data=="true"){
				document.location.href="/shop.php?goPage=Order";
			}else{
				alert("바로구매 진행중 실패 하였습니다.");
			}
		});
				
	}
}


//옵션있는 상품 장바구니에 담기
function addOptCart(g_idx){
	var opt_1;
	
	for(i=0;i<fixed_ea;i++){
		try{
			
			var opt_name_id = document.getElementById("opt_name_" + i).value;
			var opt_contents_id = document.getElementById("opt_plus_price_" + i).value;
			var qty = document.getElementById("qty_" + i).value;

			opt_1 = opt_name_id+"|"+opt_contents_id;

			$.post("/module/shop/ajax_cart_process.php", { evnMode: "add", g_idx: g_idx, qty: qty, opt_1: opt_1 },
			function(data){
				if(data=="true"){
				}
			});

		}catch(e){}
	}
	
	if(document.getElementById("opt_name_0")==null) {
		alert("옵션을 선택해주세요.");
		return;
	} else {
		/*
		cfm = confirm("장바구니에 담았습니다.\n\n지금 확인 하시겠습니까?");
		if(cfm==true){
			document.location.href="/shop.php?goPage=Cart";
		}
		*/
		popOpen('.cartPop');
	}

}


//장바구니에 담기
function addCart(g_idx,qty){
	var opt_1="";
	var opt_2="";
	var opt_3="";
	var opt_4="";
	var opt_5="";
	var opt_rel_1="";
	var opt_rel_2="";

	if(qty < 1){
		alert("구매수량은 1개 이상입니다.");
		return;
	}

	for(i=0;i<fixed_ea;i++){
		try{
			var opt_name_id = document.getElementById("opt_name_" + i);
			var opt_contents_id = document.getElementById("opt_contents_" + i);

			if(opt_contents_id.value==""){
				alert(opt_name_id.value + "(을)를 선택하세요.");
				opt_contents_id.focus();
				return;
			}
			if(i==0){opt_1 = opt_contents_id.value;}
			if(i==1){opt_2 = opt_contents_id.value;}
			if(i==2){opt_3 = opt_contents_id.value;}
			if(i==3){opt_4 = opt_contents_id.value;}
			if(i==4){opt_5 = opt_contents_id.value;}

		}catch(e){}
	}

	//연계옵션 체크
	for(i=0;i<2;i++){
		try{
			var opt_name_id = document.getElementById("opt_rel_name_" + i);
			var opt_contents_id = document.getElementById("opt_rel_contents_" + i);

			if(opt_contents_id.value==""){
				alert(opt_name_id.value + "(을)를 선택하세요.");
				opt_contents_id.focus();
				return;
			}
			if(i==0){opt_rel_1 = opt_contents_id.value;}
			if(i==1){opt_rel_2 = opt_contents_id.value;}

		}catch(e){}
	}

	//location.href="/module/shop/ajax_cart_process.php?evnMode=add&qty=1&g_idx="+g_idx;
	$.post("/module/shop/ajax_cart_process.php", { evnMode: "add", g_idx: g_idx, qty: qty, opt_1: opt_1, opt_2: opt_2, opt_3: opt_3, opt_4: opt_4, opt_5: opt_5, opt_rel_1: opt_rel_1, opt_rel_2: opt_rel_2 },
	function(data){
		if(data=="true"){
			/*
			var cfm = false;
			cfm = confirm("장바구니에 담았습니다.\n\n지금 확인 하시겠습니까?");
			if(cfm==true){
				document.location.href="/shop.php?goPage=Cart";
			}
			*/
			popOpen('.pop_cart');
			$('.overlay').show();
		}else{
			alert("장바구니에 담기 실패!");
		}
	});

}

//바로구매
function buyDirect(g_idx,qty){
	var opt_1="";
	var opt_2="";
	var opt_3="";
	var opt_4="";
	var opt_5="";
	var opt_rel_1="";
	var opt_rel_2="";

	if(qty < 1){
		alert("구매수량은 1개 이상입니다.");
		return;
	}

	for(i=0;i<fixed_ea;i++){
		try{
			var opt_name_id = document.getElementById("opt_name_" + i);
			var opt_contents_id = document.getElementById("opt_contents_" + i);

			if(opt_contents_id.value==""){
				alert(opt_name_id.value + "(을)를 선택하세요.");
				opt_contents_id.focus();
				return;
			}
			if(opt_contents_id.value=="-1"){
				alert("품절된 " + opt_name_id.value + "입니다.");
				opt_contents_id.focus();
				return;
			}
			if(i==0){opt_1 = opt_contents_id.value;}
			if(i==1){opt_2 = opt_contents_id.value;}
			if(i==2){opt_3 = opt_contents_id.value;}
			if(i==3){opt_4 = opt_contents_id.value;}
			if(i==4){opt_5 = opt_contents_id.value;}

		}catch(e){}
	}

	//연계옵션 체크
	for(i=0;i<2;i++){
		try{
			var opt_name_id = document.getElementById("opt_rel_name_" + i);
			var opt_contents_id = document.getElementById("opt_rel_contents_" + i);

			if(opt_contents_id.value==""){
				alert(opt_name_id.value + "(을)를 선택하세요.");
				opt_contents_id.focus();
				return;
			}
			if(i==0){opt_rel_1 = opt_contents_id.value;}
			if(i==1){opt_rel_2 = opt_contents_id.value;}

		}catch(e){}
	}

	$.post("/module/shop/ajax_cart_process.php", { evnMode: "direct", g_idx: g_idx, qty: qty, opt_1: opt_1, opt_2: opt_2, opt_3: opt_3, opt_4: opt_4, opt_5: opt_5, opt_rel_1: opt_rel_1, opt_rel_2: opt_rel_2 },
	function(data){
		if(data=="true"){
			document.location.href="/shop.php?goPage=Order";
		}else{
			alert("바로구매 진행중 실패 하였습니다.");
		}
	});
}

function buyDirectGiftCard(g_idx,qty){
	if(qty < 1){
		alert("구매수량은 1개 이상입니다.");
		return;
	}

	$.post("/module/shop/ajax_cart_process.php", { evnMode: "direct", g_idx: g_idx, qty: qty },
	function(data){
		if(data=="true"){
			document.location.href="/shop.php?goPage=OrderGiftCard";
		}else{
			alert("바로구매 진행중 실패 하였습니다.");
		}
	});
}


//위시리스트에 담기
function addWish(g_idx, rt_url){
	$.post("/module/shop/ajax_wish_process.php", { evnMode: "add", g_idx: g_idx },
	function(data){
		var cfm = false;
		if(data=="true"){
			/*
			cfm = confirm("위시리스트에 담았습니다.\n\n지금 확인 하시겠습니까?");
			if(cfm==true){
				document.location.href="/shop.php?goPage=WishList";
			}
			*/
			popOpen('.getPop');
		}else if(data=="nologin"){
			cfm = confirm("회원전용입니다.\n\n지금 로그인 하시겠습니까?");
			if(cfm==true){
				document.location.href="/member/login.php?rt_url="+rt_url;
			}
		}else{
			alert("위시리스트에 담기 실패!");
		}
	});
}

//장바구시 수량수정
function updateCart(c_idx,qty){
	if(qty < 1){
		alert("구매수량은 1개 이상입니다.");
		return;
	}

	$.post("/module/shop/ajax_cart_process.php", { evnMode: "update", c_idx: c_idx, qty: qty },
	function(data){
		if(data=="true"){
			//alert("주문수량을 수정하였습니다.");
			document.location.reload();
		}else{
			alert("주문수량 수정 실패!");
		}
	});

}

//장바구니에서 아이템 삭제
function deleteCart(c_idx){
	$.post("/module/shop/ajax_cart_process.php", { evnMode: "delete", c_idx: c_idx },
	function(data){
		if(data=="true"){
			document.location.reload();
		}else{
			alert("장바구니 아이템 삭제 실패!");
		}
	});
}

//위시리스트에서 아이템 삭제
function deleteWish(c_idx){
	$.post("/module/shop/ajax_wish_process.php", { evnMode: "delete", c_idx: c_idx },
	function(data){
		if(data=="true"){
			document.location.reload();
		}else{
			alert("위시리스트 아이템 삭제 실패!");
		}
	});
}

//셀렉트 박스 전체체크
function checkboxCheckAll(status){
	var obj = document.getElementsByName('items[]'); 
	var objlength = obj.length;
	for(i=0; i<objlength; i++){
		if(status==1){
			obj[i].checked=true;
		}else{
			obj[i].checked=false;
		}
	}
}

//체크한 아이템 삭제
function deleteCartChecked(f){
	//1개이상 체크했는지 검사
	var obj = document.getElementsByName('items[]'); 
	var objlength = obj.length;
	var objchecked = 0;
	for(i=0; i<objlength; i++){
		if(obj[i].checked==true){
			objchecked++;
		}
	}

	if(objchecked < 1){
		alert("선택하신 상품이 없습니다.");
		return;
	}

	var cfm = false;
	cfm = confirm("선택하신 상품들을 장바구니에서 삭제 하시겠습니까?");
	if(cfm==true){
		f.evnMode.value = "deleteCartChecked";
		f.action = "/module/shop/cart_evn.php";
		f.submit();
	}
}

//체크한 아이템 주문
function orderCartChecked(f, nhn){
	//1개이상 체크했는지 검사
	var obj = document.getElementsByName('items[]'); 
	var objlength = obj.length;
	var objchecked = 0;
	for(i=0; i<objlength; i++){
		if(obj[i].checked==true){
			objchecked++;
		}
	}

	if(objchecked < 1){
		alert("선택하신 상품이 없습니다.");
		return;
	}

	//모바일 접근 확인용
	var mobile = false;
	var uAgent = navigator.userAgent.toLowerCase();
	var mobilePhones = new Array('iphone','ipod','android','blackberry','windows ce','nokia','webos','opera mini','sonyericsson','opera mobi','iemobile');
	/*
	for(var i=0;i<mobilePhones.length;i++)
	{
		if(uAgent.indexOf(mobilePhones[i]) != -1)
		{
			mobile = true;
			break;
		}
	}
	*/

	
	if(nhn == "1") {
		document.location.href="/module/shop/nhn_check.php?gb=cart";
	} else {
		if (mobile)
		{
			f.evnMode.value = "orderCartChecked";
		}
		else
		{
			f.evnMode.value = "orderCartChecked";
		}

		f.action = "/module/shop/cart_evn.php";
		f.submit();
	}
}

//전체체크후 주문
function orderCartAll(f, nhn){
	//전체체크후
	checkboxCheckAll("1");
	//주문
	orderCartChecked(f, nhn);
}

//장바구니에서 1개 상품 주문
function orderCartOne(c_idx){
	new Ajax.Request('/module/shop/ajax_cart_process.php',
	{
		method:'post',
		parameters: {evnMode: "orderOne", c_idx: c_idx},
		asynchronous: this.asynchronous,
		encoding: 'utf-8',
		contentType: 'application/x-www-form-urlencoded',

		onSuccess: function(transport){
			var response = transport.responseText || "응답된 내역이 없습니다."; 
			if(response=="true"){
				document.location.href="/shop.php?goPage=Order";
			}else{
				alert("잠시후 다시 시도해 주세요.");
			}
		},
		
		onFailure: function(){ 
			alert('AJAX 데이터 응답중 오류가 발생하였습니다.') 
		}   
	});

}

//위시리스트 체크한 아이템 삭제
function deleteWishChecked(f){
	//1개이상 체크했는지 검사
	var obj = document.getElementsByName('items[]'); 
	var objlength = obj.length;
	var objchecked = 0;
	for(i=0; i<objlength; i++){
		if(obj[i].checked==true){
			objchecked++;
		}
	}

	if(objchecked < 1){
		alert("선택하신 상품이 없습니다.");
		return;
	}

	var cfm = false;
	cfm = confirm("선택하신 상품들을 위시리스트에서 삭제 하시겠습니까?");
	if(cfm==true){
		f.evnMode.value = "deleteWishChecked";
		f.action = "/module/shop/wish_evn.php";
		f.submit();
	}
}

//위시리스트 체크한 아이템 카트에 담기
function addCartWishChecked(f){
	//1개이상 체크했는지 검사
	var obj = document.getElementsByName('items[]'); 
	var objlength = obj.length;
	var objchecked = 0;
	for(i=0; i<objlength; i++){
		if(obj[i].checked==true){
			objchecked++;
		}
	}

	if(objchecked < 1){
		alert("선택하신 상품이 없습니다.");
		return;
	}

	f.evnMode.value = "addCartWishChecked";
	f.action = "/module/shop/wish_evn.php";
	f.submit();
}


//주문서에서 주문자 정보와 수취인 정보가 같게 설정
function shipInfoAssign(st){
	f = document.frmOrderForm;
	if(st==true){
		f.ship_name.value = f.order_name.value;
		f.ship_zip1.value = f.order_zip1.value;
		f.ship_zip2.value = f.order_zip2.value;
		f.ship_address.value = f.order_address.value;
		f.ship_address_ext.value = f.order_address_ext.value;
		f.ship_phone1.value = f.order_phone1.value;
		f.ship_phone2.value = f.order_phone2.value;
		f.ship_phone3.value = f.order_phone3.value;
		f.ship_mobile1.value = f.order_mobile1.value;
		f.ship_mobile2.value = f.order_mobile2.value;
		f.ship_mobile3.value = f.order_mobile3.value;
	}else{
		f.ship_name.value = "";
		f.ship_zip1.value = "";
		f.ship_zip2.value = "";
		f.ship_address.value = "";
		f.ship_address_ext.value = "";
		f.ship_phone1.value = "";
		f.ship_phone2.value = "";
		f.ship_phone3.value = "";
		f.ship_mobile1.value = "";
		f.ship_mobile2.value = "";
		f.ship_mobile3.value = "";
	}
}


//주문서에서 무통장입금 선택시 결제정보 선택화면 보이기
function check_pay_type(st){
	if(st=="cash"){
		$('#tblPayInfo').show();
	}else{
		$('#tblPayInfo').hide();
	}
}

//주문서 입력정보 체크
// onsubmit 형태로 호출하지 않고 그냥 호출해야함.
function check_order_form(f, pg, mobile){
	if(f.order_name.value==""){
		alert("주문자명을 입력해 주세요.");
		f.order_name.focus();
		return;
	}
	
	/*
	if(f.order_phone1.value==""){
		alert("전화번호를 입력해 주세요");
		f.order_phone1.focus();
		return ;
	}
	if(f.order_phone2.value==""){
		alert("전화번호를 입력해 주세요");
		f.order_phone2.focus();
		return ;
	}
	if(f.order_phone3.value==""){
		alert("전화번호를 입력해 주세요");
		f.order_phone3.focus();
		return ;
	}
	*/

	if(f.order_email.value==""){
		alert("이메일 주소를 입력해 주세요");
		f.order_email.focus();
		return ;
	}	

	if(f.ship_name.value==""){
		alert("수령인을 입력해 주세요.");
		f.ship_name.focus();
		return ;
	}

	if(f.ship_phone1.value==""){
		alert("전화번호를 입력해 주세요");
		f.ship_phone1.focus();
		return ;
	}
	if(f.ship_phone2.value==""){
		alert("전화번호를 입력해 주세요");
		f.ship_phone2.focus();
		return ;
	}
	if(f.ship_phone3.value==""){
		alert("전화번호를 입력해 주세요");
		f.ship_phone3.focus();
		return ;
	}

	if(f.ship_zip.value==""){
		alert("우편번호를 입력해 주세요");
		f.ship_zip.focus();
		return ;
	}
	if(f.ship_address.value==""){
		alert("주소를 입력해 주세요");
		f.ship_address.focus();
		return ;
	}
	if(f.ship_address_ext.value==""){
		alert("상세주소를 입력해 주세요");
		f.ship_address_ext.focus();
		return ;
	}
	/*
	try{
		if(f.giftgb[0].checked==true) {
			if(f.sendgb[0].checked==false && f.sendgb[1].checked==false && f.sendgb[2].checked==false){
				alert("발송유형을 입력해 주세요");
				f.sendgb[0].focus();
				return ;
			}
		}
	}catch(e){}
	*/

	var objcheckedval = "";

	//결제방법을 체크했는지 검사
	
	var obj = document.getElementsByName('pay_type'); 
	var objlength = obj.length;
	var objchecked = 0;
	
	for(i=0; i<objlength; i++){
		if(obj[i].checked==true){
			objchecked++;
			objcheckedval = obj[i].value;
		}
	}

	if(objchecked < 1){
		alert("결제방법을 선택해 주세요.");
		return ;
	}
	/*
	f.order_phone1.value = f.ship_phone1.value;
	f.order_phone2.value = f.ship_phone2.value;
	f.order_phone3.value = f.ship_phone3.value;
	*/

	//무통장입금 결제
	if(objcheckedval=="cash"){
		if(f.bank_type.value==""){
			alert("입금계좌를 선택해 주세요");
			f.bank_type.focus();
			return ;
		}
		if(f.bank_name.value==""){
			alert("입금자명을 입력해 주세요.");
			f.bank_name.focus();
			return ;
		}

		//무통장입금은 바로 주문서 입력으로
		f.target="hiddenFrame";
		f.action="/module/shop/order_evn.php";
		f.submit();
	
	}else{
		
		//기타(카드,에스크로,핸드폰,계좌이체)는 결제창을 띄움
		if(pg=="agspay"){
			if (mobile)
			{
				f.action="http://www.allthegate.com/payment/mobile_utf8/pay_start.jsp";
			}
			else
			{
				f.action="/module/shop/pg/agspay/AGS_pay_ing.php";
			}
			Pay(f);
		} else if(pg=="dacom"){
			f.target="hiddenFrame";
			f.action="/module/shop/order_evn.php?order_state=10";
			f.submit();
		}

	}

}


//하위 카테고리 html 로 가져오기 - 좌측카테고리
function getSubCatHtml(cat_no){
	if(cat_no){
		new Ajax.Request('/module/category/ajax_get_cat_html.php',
		{
			method:'get',
			parameters: {cat_no: cat_no},
			asynchronous: this.asynchronous,
			encoding: 'utf-8',
			contentType: 'application/x-www-form-urlencoded',

			onSuccess: function(transport){
				var response = transport.responseText || "하위카테고리가 없습니다."; 
				//alert(transport.responseText);
				showSubCatHtml(response);
			},
			
			onFailure: function(){ 
				alert('AJAX 데이터 응답중 오류가 발생하였습니다.') 
			}   
		});
	}else{
		hideSubCatHtml();
	}
}

function showSubCatHtml(str){
	$("divSubCategory").innerHTML = str;
	$("divSubCategory").show();
}

function hideSubCatHtml(){
	$("divSubCategory").innerHTML = "";
	$("divSubCategory").hide();
}

function getCordinatesByEvent(event){
	var cordinates = {
		x:event.pointerX(),
		y:event.pointerY()
	};
	$("divSubCategory").hide();
	$("divSubCategory").style.left = cordinates.x+10;
	$("divSubCategory").style.top = cordinates.y-5;
}
/* 카테고리 관련 */

//적립금 사용
function calUsingPoint(f, val){
	//사용하려는 적립금이 가진 적립금보다 크면 내 적립금으로 강제조정
	if(parseInt(f.using_point.value) > parseInt(f.hiddenMyPoint.value)){
		f.using_point.value = f.hiddenMyPoint.value;
	}
	//0보다 작으면 0
	if(parseInt(f.using_point.value) < 0){
		f.using_point.value = 0;
	}
	//값이 없어도 0
	if(!f.using_point.value){
		f.using_point.value = 0;
	}
	
	var coupon = 0; 
	var giftcard = 0; 
	if(document.getElementById("disPrice").value=="") {
		var coupon = 0; 
	} else {
		var coupon = document.getElementById("disPrice").value; 
	}
	if(document.getElementById("disPrice2").value=="") {
		var giftcard = 0; 
	} else {
		var giftcard = document.getElementById("disPrice2").value; 
	}
	var payPrice = parseInt(f.hiddenPayAmount.value) - parseInt(f.using_point.value) - parseInt(coupon) - parseInt(giftcard);
	var dicPrice = parseInt(f.using_point.value) + parseInt(coupon) + parseInt(giftcard);

	document.getElementById("shopCouponPrice").innerHTML =  "<span>-</span>" + addComma(dicPrice); + "원";
	document.getElementById("showPriceTotal").innerHTML =  addComma(payPrice) + "원";
}

//상품이미지 크게보기
function viewImgPop(g_idx,seq){
	obj = window.open("/module/shop/viewImgPop.php?g_idx="+g_idx+"&seq="+seq,"viewImagePop","width=100,height=100,menubars=0, toolbars=0");
}


//상품 상세페이지에서의 리뷰보기
var review_list = '';
function reveiwClick( review) {
    if( review_list != review ) {
	    if( review_list !='' ) {
		    review_list.style.display = 'none';
		}
	    review.style.display = 'block';
		review_list = review;
    } else {
        review.style.display = 'none';
        review_list = '';
    }
}

function shipInfoNew(){
	f = document.frmOrderForm;
	f.ship_name.value = "";
	f.ship_zip.value = "";
	f.ship_address.value = "";
	f.ship_address_ext.value = "";
	f.ship_phone1.value = "";
	f.ship_phone2.value = "";
	f.ship_phone3.value = "";
	f.ship_email.value = "";
}

//쇼셜커머스
function sendTwitter(title,url) {
	var wp = window.open("http://twitter.com/home?status=" + encodeURIComponent(title) + " " + encodeURIComponent(url), 'twitter', '');
	if ( wp ) {
		wp.focus();
	}
}
function sendFaceBook(title,url) {
	var wp = window.open("http://www.facebook.com/sharer.php?u=" + encodeURIComponent(url) + "&t=" + encodeURIComponent(title), 'facebook', '');
	if ( wp ) {
		wp.focus();
	}
}
function sendCyWorld(url,title,thumbnail,summary) {
	var wp = window.open("http://csp.cyworld.com/bi/bi_recommend_pop.php?url="+encodeURIComponent(url)+"&title="+encodeURIComponent(title)+"&thumbnail="+encodeURIComponent(thumbnail)+"&summary="+encodeURIComponent(summary),"xu","width=400px,height=364px")
	if ( wp ) {
		wp.focus();
	}
}
