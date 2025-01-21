<?
################################################### PHP 7 Set ST
if(!isset($_GET["category"])){	$_GET["category"]=""; }
if(!isset($_GET["sw"])){		$_GET['sw']="";	}
if(!isset($_GET["sk"])){		$_GET['sk']="";	}
if(!isset($_GET["offset"])){	$_GET['offset']=0;	}
if(!isset($_GET["page_size"])){	$_GET['page_size']=""; }
if(!isset($arrBoardList["list"]["total"])){			$arrBoardList["list"]["total"]=0; }
if(!isset($arrBoardList["total"])){					$arrBoardList["total"]=0; }
if(!isset($arrBoardInfo["list"][0]["scale"])){		$arrBoardInfo["list"][0]["scale"]=0; }
if(!isset($arrBoardInfo["list"][0]["pagescale"])){	$arrBoardInfo["list"][0]["pagescale"]=0; }
if(!isset($arrBoardInfo["list"][0]["boardid"])){	$arrBoardInfo["list"][0]["boardid"]=""; }
################################################### PHP 7 Set ED

if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] && $_SERVER["PHP_SELF"]=="/backoffice/module/board/board_view.php"){
	if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;
###################################################### 관리자 페이지 ######################################################?>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$.each($('input.calendar'), function() {
		set_datepicker($(this));
	});	
});
function set_datepicker($cont) {
	$cont.prop('readonly', true).datepicker({
		closeText: '닫기',
		prevText: '',
		nextText: '',
		currentText: '오늘',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)','7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		defaultDate: '+1w',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '년 ',
		changeMonth: true,
		changeYear: true,
		yearRange: '1921:c+5'
	});
}

function boardDel(val){	
	if(confirm("삭제 하시겠습니까?")) {
		$.post("/module/board/ajax_board_del.php", { evnMode: "delete", g_idx: val, boardid: "<?=$arrBoardInfo["list"][0]["boardid"]?>" },
		function(data){		
			//alert(data);
			doLoad();
		});
	}
}
function doLoad(){	
	location.reload();		
}
// 선택 삭제시 singleSelect=true 값 변경 false
function getSelections(){
	var ss = "0";

	var rows = $('input:checkbox[name=chk_list]:checked');
	
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		//ss.push(row.idx);
		ss += ","+row.value;
	}
	if(rows.length>0){
		//alert(ss);
		boardDel(ss);
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

// 순서 변경
$(function() {
	/*
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
	*/
});
var arrIdx=[];
function fnOrderSave(order){
	arrIdx = order;	
	fnGoodOrderby();
}
function fnGoodOrderby(){	
	var idxs = "";
	var comma = "";
	for(var i=0;i<arrIdx.length;i++){
		idxs += comma+arrIdx[i];
		comma = "|";
	}	
	//alert(idxs)
	if(idxs){
		
		$.post("/module/board/ajax_orderby_board.php", { "gidx": idxs, "tn":"tbl_board_<?=$arrBoardInfo["list"][0]["boardid"]?>" },
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
// 메인노출
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
		"gidx":gidx,"yn":yn,"sf":sf,"tn":"tbl_board_ourstory"
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
<?php
$arrCategory = array(
	"1" => "1:1문의",
	"2" => "고객Q&A",
	"3" => "전화문의",
	"4" => "카카오문의",
	"5" => "이메일문의"
);
?>
<style>
	.tab_div {
		display:flex;flex-direction: row;align-items: center;justify-content: flex-start; gap:8px; margin-bottom:15px;
	}
	.tab_div .tab_menu {
		cursor:pointer;display:flex;align-items: center;justify-content: center;width: 150px;border: 1px solid #628dc7;border-radius: 5px;text-align: center;height: 30px;
	}
	.tab_div .tab_menu:hover,
	.tab_div .tab_menu.on {
		background-color:#628dc7;
		color:#ffffff;
	}


</style>
<div class="container">

	<div class="title"><?=$arrBoardInfo["list"][0]["boardname"]?></div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
	<input type="hidden" name="boardid" value="<?=$arrBoardInfo["list"][0]["boardid"]?>">
	<input type="hidden" name="category" value="<?=$_GET["category"]?>">

	<div class="inbox top_search">
		<dl>
			<dt>답변여부</dt>
			<dd>
				<select name="etc_1" class="text" onchange="document.form1.submit()" style="width:120px;">
					<option value="">전체</option>			
					<option value="Y" <?=$_GET['etc_1'] == "Y"?"selected":""?>>Y</option>
					<option value="N" <?=$_GET['etc_1'] == "N"?"selected":""?>>N</option>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>업종</dt>
			<dd>
				<select name="etc_2" class="text" onchange="document.form1.submit()" style="width:120px;">
					<option value="">전체</option>
					<?php 
					for($i=0;$i < $arrEtc2List["total"];$i++){
						$arrEtc2[$arrEtc2List["list"][$i]["cat_no"]] = $arrEtc2List["list"][$i]["cat_name"];
					?>
					<option value="<?=$arrEtc2List["list"][$i]["cat_no"]?>" <?=$arrEtc2List["list"][$i]["cat_no"] == $_GET['etc_2']?"selected":""?>><?=$arrEtc2List["list"][$i]["cat_name"]?></option>
					<?php } 
					$arrEtc2["etc"] = "기타/ 해당사항 없음";
					?>
					<option value="etc" <?=$_GET['etc_2'] == "etc"?"selected":""?>>기타/ 해당사항 없음</option>
				</select>
			</dd>
		</dl>
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:120px;">
					<option value='all'<?=$_GET['sw']=="all"?" selected='selected'":""?>>선택</option>
					<option value='s'<?=$_GET['sw']=="s"?" selected='selected'":""?>>제목</option>
					<option value='c'<?=$_GET['sw']=="c"?" selected='selected'":""?>>내용</option>
					<option value='n'<?=$_GET['sw']=="n"?" selected='selected'":""?>>이름</option>
					<option value='userid'<?=$_GET['sw']=="userid"?" selected='selected'":""?>>아이디</option>
					<option value='tel'<?=$_GET['sw']=="tel"?" selected='selected'":""?>>연락처</option>
					<option value='e5l'<?=$_GET['sw']=="e5l"?" selected='selected'":""?>>답변</option>
				</select>	
				<input type="text" name="sk" value="<?=$_GET['sk']?>" onkeypress="if( event.keyCode == 13 ){document.form1.submit()}" />
			</dd>
		</dl>
		<dl class="search_wrap">
			<dt>등록일/답변일</dt>
			<dd>
				<select name="date_type" style="width:120px;">
					<option value='wdate' <?=$_GET['date_type']=="wdate"?" selected='selected'":""?>>등록일</option>
					<option value='schedule_date' <?=$_GET['date_type']=="schedule_date"?" selected='selected'":""?>>답변일</option>
				</select>	
				<input type="text" class="datepicker" name="sdate" value="<?=$_GET['sdate']?>" autocomplete="off"/><em>~</em><input type="text" class="datepicker" name="edate" value="<?=$_GET['edate']?>" autocomplete="off"/>
				<button type="button" class="search" onclick="document.form1.submit()">검색</button>
			</dd>
		</dl>
	</div>
	<div class="inbox">
		<div class="tab_div">
			<div class='tab_menu <?=$_GET["category"] == ""?"on":""?>' onclick="location.href='<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>'">전체</div>
			<?php foreach($arrCategory as $key => $val){?>
			<div class='tab_menu <?=$_GET["category"] == $key?"on":""?>' onclick="location.href='<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&category=<?=$key?>'"><?=$val?></div>
			<?php } ?>
		</div>
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrBoardList["total"])?></strong></div>
				<div class="down">
				<!--	<a href="#this" class="excel" download>전체다운<span class="pc_vw">로드</span></a>
					<a href="#this" class="excel" download>선택다운<span class="pc_vw">로드</span></a>
				-->
				</div>
			</div>
			<div class="bdr_right">
				<div class="count">
					<select name="page_size" onchange="document.form1.submit()"  style="width:60px;">
						<option value="100" <?if($arrBoardInfo["list"][0]["scale"]=="100"){echo 'selected="selected"';}?>>100</option>
						<option value="50" <?if($arrBoardInfo["list"][0]["scale"]=="50"){echo 'selected="selected"';}?>>50</option>
						<option value="40" <?if($arrBoardInfo["list"][0]["scale"]=="40"){echo 'selected="selected"';}?>>40</option>
						<option value="30" <?if($arrBoardInfo["list"][0]["scale"]=="30"){echo 'selected="selected"';}?>>30</option>
						<option value="20" <?if($arrBoardInfo["list"][0]["scale"]=="20"){echo 'selected="selected"';}?>>20</option>
						<option value="15" <?if($arrBoardInfo["list"][0]["scale"]=="15"){echo 'selected="selected"';}?>>15</option>
						<option value="10" <?if($arrBoardInfo["list"][0]["scale"]=="10"){echo 'selected="selected"';}?>>10</option>
						<option value="9" <?if($arrBoardInfo["list"][0]["scale"]=="9"){echo 'selected="selected"';}?>>9</option>
					</select>
					개씩 보기
				</div>
				<div class="btns">
					<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택삭제</a>
					<a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=write&category=<?=$_GET['category']?>" class="btn">신규등록</a>
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
						<col class="check">
						<col class="w4p">
						<col class="w6p">
						<col class="w10p">
						<col class="w15p">
						<col class="w10p">
						<col class="*">
						<col class="w10p">
						<col class="w4p">
						<col class="w10p">
						<col class="w10p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>	
							<th><label class="check notxt"><input type="checkbox" name="" id="allCheck"><i></i></label></th>
							<th class="pc_vw">No.</th>
							<th class="pc_vw">문의구분</th>
							<th class="pc_vw">업종</th>
							<th class="pc_vw">아이디</th>
							<th class="pc_vw">이름</th>
							<th class="pc_vw">제목</th>
							<th class="pc_vw">등록일</th>
							<th class="pc_vw">답변여부</th>
							<th class="pc_vw">답변자</th>
							<th class="pc_vw">답변일</th>
							<th class="pc_vw">관리</th>
						</tr>
					</thead>
					<tbody id="sortWrap">
					<?
					if($arrBoardList["list"]["total"] > 0){
						for($i=0; $i < $arrBoardList["list"]["total"]; $i++){
							//신규글 표시
							if(strtotime($arrBoardList["list"][$i]['wdate'])+($arrBoardInfo["list"][0]["newmark"]*86400) > mktime()){
								$newImage ='<span class="icoNew">new</span>';	// new 이미지
							}else{
								$newImage ='';
							}
							//글잠금 표시
							if($arrBoardList["list"][$i]['uselock'] == "Y"){
								$lockImage ="";	// 글잠금표시
							}else{
								$lockImage ="";
							}
							//댓글수 표시
							if(isset($arrBoardList["list"][$i]['cmt_count']) > 0){
								$cmt_count = "[".number_format($arrBoardList["list"][$i]['cmt_count'])."]";
							}else{
								$cmt_count = "";
							}
							//공지				
							$categoryTitle = $arrBoardList["total"]-$i-(int)$_GET['offset'];					
							$TrClass="";
							$noticeMo="";
							if($arrBoardList["list"][$i]['no']=="0"){
								$TrClass="class=\"notice\"";	// 공지글 표시
								$categoryTitle = '<span class="notiTit">공지</span>';
								$noticeMo = '<span class="notiTit">공지</span>';
							}

							$imgsrc[$i] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardList["list"][$i]['re_name'];
							############################ 파일 확인 #############################
							$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], "", $arrBoardList["list"][$i]['idx'],"list");
							for($j=0;$j<$arrBoardArticle["total_files"];$j++){
								if(substr($arrBoardArticle["files"][$j]['re_name'],0,2) != "l_"){
									$fileImg[$i] = '<img src="/backoffice/pub_old/images/file.png">';
								}
							}
					?>
						<tr data-order="<?=$arrBoardList['list'][$i]['idx']?>">
							<td style="width:5%;"><label class="check notxt"><input type="checkbox" value="<?=$arrBoardList["list"][$i]['idx']?>" name="chk_list"><i></i></label></td>
							<td style="width:5%;"><?=$arrBoardList["list"][$i]['no']=="0"?"공지":$categoryTitle?></td>								
							<td style="width:5%;"><?=$arrCategory[$arrBoardList["list"][$i]['category']]?></td>									
							<td style="width:5%;"><?=$arrEtc2[$arrBoardList["list"][$i]['etc_2']]?></td>	
							<td style="width:40%;"><?=$arrBoardList["list"][$i]['w_user']?></td>	
							<td style="width:40%;"><?=$arrBoardList["list"][$i]['name']?></td>	
							<td style="width:5%;"><?=$arrBoardList["list"][$i]['subject']?></td>	
							<td style="width:40%;"><?=date("Y-m-d",strtotime($arrBoardList["list"][$i]['wdate']))?></td>	
							<td style="width:40%;"><?=$arrBoardList["list"][$i]['etc_1']?></td>	
							<td style="width:15%;"><?=$arrBoardList["list"][$i]['homepage']?></td>	
							<td style="width:15%;"><?=$arrBoardList["list"][$i]['schedule_date']?></td>					
							<td style="width:10%;">
								<div class="btns">
									<a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=modify&idx=<?=$arrBoardList["list"][$i]['idx']?>&category=<?=$_GET['category']?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="boardDel(<?=$arrBoardList["list"][$i]['idx']?>)">삭제</button>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td colspan="11">등록된 데이터가 없습니다.</td>
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
			echo pageNavigationBackoffice($arrBoardList["total"],$arrBoardInfo["list"][0]["scale"],$arrBoardInfo["list"][0]["pagescale"],$_GET['offset'],$reQueryString);
			############### paging ############### ED
			?>			
			</div>	
<!-- 			<div class="btns">
				<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택삭제</a>
				<a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=write&category=<?=$_GET['category']?>" class="btn">신규등록</a>
			</div> -->
		</div>
	</div>
</div>
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
<?
}else{###################################################### 사용자 페이지 ######################################################
	$offset = 0;
	if(isset($_GET["offset"])){
		$offset = (int)$_GET["offset"];
	}
?>
<div class="my_con">
	<div class="stit s mo_mt0">1:1문의 <div class="btns"><a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=write&category=<?=$_GET['category']?>" class="btn">문의하기</a></div></div>
	
	<div class="board_list list_fz14 board_inquiry">
		<table>
			<colgroup>
				<col class="w1">
				<col class="w220">
				<col width="*">
				<col class="w220">
			</colgroup>
			<thead>
				<tr>
					<th>답변상태</th>
					<th>문의유형</th>
					<th>제목</th>
					<th>문의일</th>
				</tr>
			</thead>
			<tbody>
			<?
			if($arrBoardList["list"]["total"] > 0){
				for($i=0; $i < $arrBoardList["list"]["total"]; $i++){				
					//글잠금 표시
					if($arrBoardList["list"][$i]['uselock'] == "Y"){
						$lockImage ="";	// 글잠금표시
					}else{
						$lockImage ="";
					}				
					//순번 & 공지 & 신규표시
					$listNum = $arrBoardList["total"]-$i-$offset;					
					//신규글 표시
					if(strtotime($arrBoardList["list"][$i]['wdate'])+($arrBoardInfo["list"][0]["newmark"]*86400) > mktime()){
						$categoryTitle ='class="new"';	// new 이미지				
					}
					//공지
					if($arrBoardList["list"][$i]['no']=="0"){
						$listNum = '<span class="notice">공지</span>';
					}					
					//파일
					$imgsrc[$i] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardList["list"][$i]['re_name'];
					if(!$arrBoardList["list"][$i]['re_name']){$imgsrc[$i] = "/GATE/pub/images/img_story00.png";}
					############################ 파일 확인 #############################
					$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], "", $arrBoardList["list"][$i]['idx'],"list");
					for($j=0;$j<$arrBoardArticle["total_files"];$j++){
						if(substr($arrBoardArticle["files"][$j]['re_name'],0,2) != "l_"){
							$fileImg[$i] = '<i class="file"></i>';
						}
					}

					if($arrBoardList["list"][$i]['etc_1']=="Y"){
						$arrBoardList["list"][$i]['etc_txt'] = '<i class="end">답변완료</i>';
					}else{
						$arrBoardList["list"][$i]['etc_txt'] = '<i class="ing">답변대기</i>';					
					}
			?>		
				<tr>
					<td class="state"><?=$arrBoardList["list"][$i]['etc_txt']?></td>
					<td><?=$arrBoardList["list"][$i]['category']?></td>
					<td class="tal"><a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=view&idx=<?=$arrBoardList["list"][$i]['idx']?>&sk=<?=$_GET['sk']?>&sw=<?=$_GET['sw']?>&offset=<?=$_GET['offset']?>&category=<?=$_GET['category']?>"><?=text_cut($arrBoardList["list"][$i]['subject'],80)?></a></td>
					<td><?=str_replace("-",".",substr($arrBoardList["list"][$i]['wdate'],0,10))?></td>
				</tr>
			<?
				}
			}else{
				echo "<tr><td colspan='4'><a href='javascript:void(0);' class='no_data'>등록된 데이터가 없습니다.</a></td></tr>";
			}
			?>	
			</tbody>
		</table>

		<div class="board_bottom">
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
			echo pageNavigationUser($arrBoardList["total"],$arrBoardInfo["list"][0]["scale"],$arrBoardInfo["list"][0]["pagescale"],$_GET['offset'],$reQueryString);
			############### paging ############### ED
			?>
			</div>
		</div> <!-- //board_bottom -->
	</div>
</div>
<?
}
?>