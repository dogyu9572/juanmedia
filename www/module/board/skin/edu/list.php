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
	$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

    //카테고리 정보
	$arrCatCode = explode("/", $arrCategoryInfo["list"][0]['cat_code']);

    //분류 리스트
	$arrCategory1 = getCategoryList(62);
    if($arrCatCode[2]){	$arrCategory2 = getCategoryList($arrCatCode[2]); }

    updateReceptionStatusToEdu();
###################################################### 관리자 페이지 ######################################################
?>
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
function fnCat1(tval){
    $.post("/module/shop/ajax_selectbox_category.php", { snum : '2',cat_no: tval, eventMd : '<?=$_GET['eventMd']?>'},
        function(data){
            if(data){
                $("#cat_02").html(data);
            }else{
                alert("실패.");
            }
        }
    );
    fnCatNo();
}
function fnCat2(tval){
    $.post("/module/shop/ajax_selectbox_category.php", { snum : '3',cat_no: tval },
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
    if(cat_no3 && cat_no2 && cat_no1){
        cat_no = cat_no3;
    }else if(cat_no2 && cat_no1){
        cat_no = cat_no2;
    }else{
        cat_no = cat_no1;
    }
    $("#cat_no").val(cat_no);
}
// 복사
function fnCopyEst(eidx){
    if(confirm("복사 하시겠습니까?")) {
        location.href = "/module/board/edu_copy.php?idx="+eidx;
    }
}
//-->
</script>
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
    .tab_div .tab_menu.cal {
        background-color: #305587;
        color:#ffffff;
    }
</style>
<div class="container">

	<div class="title"><?=$arrBoardInfo["list"][0]["boardname"]?></div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
    <input type="hidden" id="cat_no" name="cat_no" value="<?=$_GET['cat_no']?>">
	<input type="hidden" name="boardid" value="<?=$arrBoardInfo["list"][0]["boardid"]?>">
	<input type="hidden" name="category" value="<?=$_GET["category"]?>">

	<div class="inbox top_search">
        <dl class="search_wrap">
            <dt>상태</dt>
            <dd>
                <select name="reception_status" class="text" onchange="document.form1.submit()" style="width:120px;">
                    <option value="">전체</option>
                    <option value="접수중" <?= $_GET['reception_status'] == "접수중" ? "selected" : "" ?>>접수중</option>
                    <option value="대기접수" <?= $_GET['reception_status'] == "대기접수" ? "selected" : "" ?>>대기접수</option>
                    <option value="교육중" <?= $_GET['reception_status'] == "교육중" ? "selected" : "" ?>>교육중</option>
                    <option value="종료" <?= $_GET['reception_status'] == "종료" ? "selected" : "" ?>>종료</option>
                </select>
            </dd>
        </dl>
		<dl>
			<dt>교육구분</dt>
            <dd id="cat_01">
                <select name="cat1" id="cat1" class="text" onchange="fnCat1(this.value);" style="width:120px;">
					<option value="">전체</option>
					<?
					for($i=0;$i<$arrCategory1["total"];$i++){
                    ?>
                        <option value="<?=$arrCategory1["list"][$i]['cat_no']?>"<?=$arrCatCode[2]==$arrCategory1["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory1["list"][$i]['cat_name']?></option>
					<?}?>
				</select>
			</dd>
		</dl>
        <dl>
            <dt>&nbsp;</dt>
            <dd id="cat_02">
                <select name="cat2" id="cat2" class="text" onchange="fnCat2(this.value);" style="width:120px;">
                    <option value="">전체</option>
                    <?
                        for($i=0;$i<$arrCategory2["total"];$i++){

                    ?>
                    <option value="<?=$arrCategory2["list"][$i]['cat_no']?>"<?=$arrCatCode[3]==$arrCategory2["list"][$i]['cat_no']?" selected":""?>><?=$arrCategory2["list"][$i]['cat_name']?></option>
                    <?}?>
                </select>
            </dd>
        </dl>
        <dl>
            <dt>등록일</dt>
            <dd>
                <input type="text" class="datepicker" name="sdate" value="<?=$_GET['sdate']?>" autocomplete="off"/><em>~</em><input type="text" class="datepicker" name="edate" value="<?=$_GET['edate']?>" autocomplete="off"/>

            </dd>
        </dl>
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:120px;">
					<option value='all'<?=$_GET['sw']=="all"?" selected='selected'":""?>>전체</option>
					<option value='s'<?=$_GET['sw']=="s"?" selected='selected'":""?>>교육명</option>
				</select>	
				<input type="text" name="sk" value="<?=$_GET['sk']?>" onkeypress="if( event.keyCode == 13 ){document.form1.submit()}" />
                <button type="button" class="search" onclick="document.form1.submit()">검색</button>
			</dd>
		</dl>
	</div>
	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrBoardList["total"])?></strong></div>
				<div class="down">
				</div>
			</div>
			<div class="bdr_right">
                <div class="btns">
                    <a href="./board_<?=$arrBoardInfo["list"][0]["boardid"]?>_xls.php?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>" class="excel" download>엑셀파일로 저장<span class="pc_vw"></span></a>
                </div>
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
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w10p">
                        <col class="w17p">
                    </colgroup>
					<thead>
						<tr>	
							<th><label class="check notxt"><input type="checkbox" name="" id="allCheck"><i></i></label></th>
                            <th class="pc_vw">No.</th>
                            <th class="pc_vw">상태</th>
                            <th class="pc_vw">교육번호</th>
                            <th class="pc_vw">교육구분1</th>
                            <th class="pc_vw">교육구분2</th>
                            <th class="pc_vw">교육명</th>
                            <th class="pc_vw">교육기간</th>
                            <th class="pc_vw">요일</th>
                            <th class="pc_vw">시간</th>
                            <th class="pc_vw">정원</th>
                            <th class="pc_vw">대기</th>
                            <th class="pc_vw">수강료</th>
                            <th class="pc_vw">등록일</th>
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

                            $dayTypeMap = [
                                'weekly' => '매주',
                                'biweekly' => '격주',
                                'other' => '기타'
                            ];
                            $dayType = $dayTypeMap[$arrBoardList["list"][$i]['day_type']];

                            $days = str_replace('|', '/', $arrBoardList["list"][$i]['days']);

                            $subject_title = mb_strlen($arrBoardList["list"][$i]['subject']) > 20 ? $arrBoardList["list"][$i]['subject'] : '';
                            $category1_title = mb_strlen($arrBoardList["list"][$i]['category1']) > 20 ? $arrBoardList["list"][$i]['category1'] : '';
                            $category2_title = mb_strlen($arrBoardList["list"][$i]['category2']) > 20 ? $arrBoardList["list"][$i]['category2'] : '';
					?>
                        <tr data-order="<?=$arrBoardList['list'][$i]['idx']?>">
                            <td style="width:5%;"><label class="check notxt"><input type="checkbox" value="<?=$arrBoardList["list"][$i]['idx']?>" name="chk_list"><i></i></label></td>
                            <td style="width:5%;"><?=$arrBoardList["list"][$i]['no']=="0"?"공지":$categoryTitle?></td>
                            <td><?=$arrBoardList["list"][$i]['reception_status']?></td>
                            <td><?=str_pad($arrBoardList["list"][$i]['edu_no'], 2, "0", STR_PAD_LEFT)?></td>
                            <td>
                                <div title="<?=$category1_title?>">
                                <?php
                                $category1_name = getCategoryName($arrBoardList["list"][$i]['category1']);
                                echo $category1_name ? $category1_name : '';
                                ?>
                                </div>
                            </td>
                            <td>
                                <div title="<?=$category2_title?>">
                                <?php
                                $category2_name = getCategoryName($arrBoardList["list"][$i]['category2']);
                                echo $category2_name ? $category2_name : '';
                                ?>
                                </div>
                            </td>
                            <td>
                                <div title="<?=$subject_title?>">
                                    <?=mb_strimwidth($arrBoardList["list"][$i]['subject'], 0, 20, '...')?>
                                </div>
                            </td>
                            <td><?=$arrBoardList["list"][$i]['e_start_date']?> ~ <?=$arrBoardList["list"][$i]['e_end_date']?></td>
                            <td><?=$dayType?><br/><?=$days?></td>
                            <td><?=$arrBoardList["list"][$i]['start_hour']?>:<?=$arrBoardList["list"][$i]['start_minute']?> ~ <?=$arrBoardList["list"][$i]['end_hour']?>:<?=$arrBoardList["list"][$i]['end_minute']?></td>
                            <td><?=$arrBoardList["list"][$i]['capacity']?>명</td>
                            <td><?=$arrBoardList["list"][$i]['waitlist']?>명</td>
                            <td><?=number_format($arrBoardList["list"][$i]['fee'])?>원</td>
                            <td><?=date("Y-m-d", strtotime($arrBoardList["list"][$i]['wdate']))?></td>
                            <td>
                                <div class="btns">
                                    <a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=modify&idx=<?=$arrBoardList["list"][$i]['idx']?>&category=<?=$_GET['category']?>" class="btn modi">수정</a>
                                    <button type="button" class="btn del" onclick="boardDel(<?=$arrBoardList["list"][$i]['idx']?>)">삭제</button>
                                    <a href="javascript:void(0);" onclick="fnCopyEst('<?=$arrBoardList["list"][$i]['idx']?>')" class="btn perf">복사</a>
                                </div>
                            </td>
                        </tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td colspan="13">등록된 데이터가 없습니다.</td>
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
            <div class="btns">
                <a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택삭제</a>
                <a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=write&category=<?=$_GET['category']?>" class="btn">신규등록</a>
            </div>
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

    $arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_GET["cat_no"]));

    //카테고리 정보
    $arrCatCode = explode("/", $arrCategoryInfo["list"][0]['cat_code']);

    //분류 리스트
    $arrCategory1 = getCategoryList(62);
    if($arrCatCode[2]){	$arrCategory2 = getCategoryList($arrCatCode[2]); }

    //상태 업데이트
    updateReceptionStatusToEdu();

    ?>
    <!-- pageTitle -->
    <div class="pageTitle inner">상설교육</div>
    <!-- //pageTitle -->

    <!-- tabType1 -->
    <div class="tabType1">
        <ul>
            <li class="<?= $_GET['cat_no'] == '63' ? 'active' : '' ?>"><a href="/edu/list.php?cat_no=63">전체</a></li>
            <?php
            for($i=0; $i<$arrCategory2["total"]; $i++):
                $activeClass = ($_GET['cat_no'] == $arrCategory2["list"][$i]['cat_no']) ? 'class="active"' : '';
                ?>
                <li <?=$activeClass?>><a href="/edu/list.php?cat_no=<?=$arrCategory2["list"][$i]['cat_no']?>"><?=$arrCategory2["list"][$i]['cat_name']?></a></li>
            <?php endfor; ?>
        </ul>
    </div>
    <!-- //tabType1 -->

    <!-- subSec -->
    <div class="subSec last">
    <div class="inner">

        <!-- searchForm -->
        <div class="searchForm">
            <div class="count">
                전체 <span><?=number_format($arrBoardList["total"])?>건</span>
            </div>
            <form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
            <div class="rightForm">
                <div class="baseSel">
                    <select name="sw" id="sw" class="text">
                    <option value="">선택</option>
                    <option value="s" <?=$_GET["sw"] == "s"?"selected":""?>>교육명</option>
                    <option value="c" <?=$_GET["sw"] == "c"?"selected":""?>>교육내용</option>
                    <option value="all" <?=$_GET["sw"] == "all"?"selected":""?>>교육명/교육내용</option>
                    </select>
                </div>
                <div class="search">
                    <div class="baseInput">
                        <input type="text" name="sk" id="sk" value="<?=$_GET["sk"]?>" class="text" placeholder="검색어를 입력하세요.">
                    </div>
                    <a href="javascript:void(0);" onclick="document.form1.submit();"><img src="/images/ico_search.svg" alt="검색"></a>
                </div>
            </div>
            </form>
        </div>
        <!-- //searchForm -->

        <!-- listType1 -->
        <div class="listType1">
            <ul>
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
                        $listNum = number_format($arrBoardList["total"]-$i-$offset);
                        //신규글 표시
                        if(strtotime($arrBoardList["list"][$i]['wdate'])+($arrBoardInfo["list"][0]["newmark"]*86400) > mktime()){
                            $categoryTitle ='class="new"';	// new 이미지
                        }
                        $noticeTxt = "";
                        //공지
                        if($arrBoardList["list"][$i]['no']=="0"){
                            $listNum = '<span>공지</span>';
                            $noticeTxt = '<span class="notice">공지</span>';
                        }
                        //파일
                        $imgsrc[$i] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardList["list"][$i]['re_name'];
                        if(!$arrBoardList["list"][$i]['re_name']){$imgsrc[$i] = "/pub/images/img_gall_list_sample.png";}
                        ############################ 파일 확인 #############################
                        $arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], "", $arrBoardList["list"][$i]['idx'],"list");
                        for($j=0;$j<$arrBoardArticle["total_files"];$j++){
                            if(substr($arrBoardArticle["files"][$j]['re_name'],0,2) != "l_"){
                                $fileImg[$i] = '첨부파일';
                            }

                        }

                        // Get the current state
                        $currentStatus = $arrBoardList["list"][$i]['reception_status'];

                        // Set the state class based on the current status using if statements
                        if ($currentStatus == '접수중') {
                            $stateClass = 'ing';
                        } elseif ($currentStatus == '대기접수') {
                            $stateClass = 'etc';
                        } elseif ($currentStatus == '종료') {
                            $stateClass = 'end';
                        } elseif ($currentStatus == '교육중') {
                            $stateClass = '';
                        }

                        ?>
                        <li>
                            <a href="<?=$_SERVER["PHP_SELF"] . "?boardid=" . $arrBoardInfo["list"][0]["boardid"] . "&mode=view&idx=" . $arrBoardList["list"][$i]['idx']?>">
                                <div class="stateBox <?=$stateClass?>">
                                    <span><?=$arrBoardList["list"][$i]['reception_status']?></span>
                                </div>
                                <div class="img">
                                    <img src="<?=$imgsrc[$i]?>" alt="썸네일">
                                </div>
                                <div class="textWrap">
                                    <div class="title"><?=$arrBoardList["list"][$i]['subject']?></div>
                                    <div class="info">
                                        <span class="left">교육기간</span>
                                        <span class="right"><?=$arrBoardList["list"][$i]['e_start_date']?> ~ <?=$arrBoardList["list"][$i]['e_end_date']?></span>
                                    </div>
                                    <div class="info">
                                        <span class="left">교육시간</span>
                                        <span class="right"><?=$dayType?> <?=$days?> <?=$arrBoardList["list"][$i]['start_hour']?>:<?=$arrBoardList["list"][$i]['start_minute']?> ~ <?=$arrBoardList["list"][$i]['end_hour']?>:<?=$arrBoardList["list"][$i]['end_minute']?></span>
                                    </div>
                                    <div class="info">
                                        <span class="left">회차</span>
                                        <span class="right"><?=$arrBoardList["list"][$i]['etc_3']?>차시</span>
                                    </div>
                                    <div class="info">
                                        <span class="left">수강료</span>
                                        <span class="right"><?=number_format($arrBoardList["list"][$i]['fee'])?>원</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <!-- //listType1 -->

        <!-- pagingWrap -->
        <div class="pagingWrap">
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
        <!-- //pagingWrap -->

    </div>
    </div>
    <!-- //subSec -->

<?
}
?>