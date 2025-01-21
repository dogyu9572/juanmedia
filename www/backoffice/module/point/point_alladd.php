<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "../coupon/menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

if(!in_array("homepage_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrLevel = getArticleList($_conf_tbl["member_level"], $scale, $_REQUEST['offset'], "order by level_no desc ");

$arrInfo = getUserInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));

$arrPoint = getNowPoint(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["user_id"]));
//DB해제
SetDisConn($dblink);
?>
<script type="text/javascript">
<!--
function fnGoodSelect(stridx, inputName){			
	if(stridx){ $(".is-close-btn").click();	}
	var msds = $("input[name='"+inputName+"']").val();
	if(msds){
		$("input[name='"+inputName+"']").val(msds+","+stridx);
	}else{
		$("input[name='"+inputName+"']").val(stridx);
	}	
	fnGoodPrint(inputName);
}

function fnGoodPrint(inputName, orderby){
	var msds = $("input[name='"+inputName+"']").val();
	var listName = "#msdslist1";
	var boardID = "contact";
	if(inputName=="cateidxs"){
		listName = "#categorylist";
		boardID = "tbl_category";
	}else if(inputName=="brandidxs"){
		listName = "#brandlist";
		boardID = "tbl_category";
	}

	if(msds<1){
		$(listName).html('<tr><td colspan="5" style="text-align:center;padding:14px 0;">등록된 데이터가 없습니다.</td></tr>');				
	}else{
		//	alert("bid:"+boardID+"\n idx:"+msds+"\n fname:"+inputName);
		$.post("/module/board/ajax_info_sublist.php", { bid: boardID, idx : msds, fname: inputName, orderby: orderby},
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
function OpenApplyView(openUrl, fname)
{
	var requestUrl = "";
	if(openUrl=="board"){
		requestUrl = "/backoffice/module/board/pop_board_view.php?boardid=contact&fname="+fname;	// 일반게시판
	}else if(openUrl=="cate"){
		requestUrl = "/backoffice/module/category/pop_category.php?cat_no=2&fname="+fname;	// 카테고리
	}else if(openUrl=="brand"){
		requestUrl = "/backoffice/module/category/pop_category.php?cat_no=3&fname="+fname;	// 카테고리
	}
	//	var requestUrl = "/backoffice/module/member/pop_member.php?fname="+fname;		// 회원
	//	var requestUrl = "/backoffice/module/shop/pop_good.php?fname="+fname;			// 상품
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
<script language="javascript">
function frmCheck(f){
	if(f.point.value.length < 1){
		alert("적립금을 입력하세요.");
		f.point.focus();
		return false;
	}
	if(f.contents.value.length < 1){
		alert("내용을 입력하세요.");
		f.contents.focus();
		return false;
	}
	f.submit();
}

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

	$("input:radio[name='point_type']").click(function(){ // 주소록리스트 hide/show
		if(this.value=="auto"){
			$(".addresslist").hide();
			$(".levellist").hide();
		}else if(this.value=="manual"){
			$(".levellist").hide();
			$(".addresslist").show();
		}else{
			$(".addresslist").hide();
			$(".levellist").show();
		}		
	});
});
</script>
<div class="container">

	<div class="title">적립금 등록</div>
	
	<div class="inbox write_tbl mo_break_write">
		<div class="tit">기본정보 <i>*</i></div>
		<table>
			<form name="frmPoint" method="post" action="point_evn.php">
			<input type="hidden" name="evnMode" value="addall">
			<input type="hidden" name="addidxs" value="">
			
			
			<tr style="display:none;">
				<th>입력구분</th>
				<td>
					<div class="inputs">
						<select name="type" style="width:110px;">
							<option value="plus">적립</option>
							<option value="minus">사용</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<th>적립금</th>
				<td><input type="text" name="point" class="w2 numberOnly" maxlength="100"/></td>
			</tr>
			<tr>
				<th>내용</th>
				<td><input type="text" name="contents" class="w4" maxlength="250" /></td>
			</tr>
			<tr>
				<th>발송 대상</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="point_type" value="auto" checked><i></i>전체 회원</label>
					<label class="radio"><input type="radio" name="point_type" value="manual"><i></i>주소록</label> 
					<label class="radio"><input type="radio" name="point_type" value="level"><i></i>회원등급</label> 					
				</div></td>
			</tr>
			<tr class="addresslist" style="display:none;">
				<th>LIST</th>
				<td>
					<div class="btns" style="height:30px;margin-top:0;margin-bottom:10px; justify-content: left">
						<a href="javascript:void(0);" class="btn" onclick="OpenApplyView('board','addidxs')">추가</a>
					</div>
					<div class="bdr_list tac" style="width:100%;board:1px">
						<table>
							<colgroup>									
								<col width="10%">
								<col width="25%">
								<col width="20%">
								<col width="30%">
								<col width="15%">
							</colgroup>
							<thead>
								<tr>							
									<th style="text-align:center;padding:20px 0;">번호</th>
									<th style="text-align:center;padding:20px 0;">주소록명</th>
									<th style="text-align:center;padding:20px 0;">등록인원</th>
									<th style="text-align:center;padding:20px 0;">등록일</th>
									<th style="text-align:center;padding:20px 0;">관리</th>
								</tr>
							</thead>
							<tbody id="msdslist1">
							</tbody>
						</table>
					</div>
				</td>
			</tr>
			<tr class="levellist"  style="display:none;">
				<th>회원등급</th>
				<td><div class="inputs">
					<select name="member_level" style="width:120px;">
					<option value="0">회원등급선택</option>
					<?for ($i=0;$i<$arrLevel['total'];$i++) {?>
					<option value="<?=$arrLevel['list'][$i]['level_no']?>"<?=$arrLevel['list'][$i]['level_no']==$arrInfo["list"][0]["member_level"]?" selected":""?>><?=$arrLevel['list'][$i]['level_name']?></option>
					<?}?>
					</select>
				</div></td>
			</tr>
			</form>
		</table>		
		<?
		################################ 목록보기
		$queryString = explode("&",$_SERVER['QUERY_STRING']);
		$reQueryString = "";
		$comma = "";
		for($i=0;$i<count($queryString);$i++){
			if(strpos($queryString[$i],"user_id=")===false){
				$reQueryString .= $comma.$queryString[$i];
				$comma = "&";
			}
		}
		?>
		<div class="btns">
			<a href="/backoffice/module/point/point_list.php?<?=$reQueryString?>" class="btn btn_list">목록보기</a>
			<button class="btn btn_save" type="button" onclick="frmCheck(document.frmPoint)">저장</button>
		</div>		
	</div> <!-- //inbox -->
</div>

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