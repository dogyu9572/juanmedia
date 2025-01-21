<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("homepage_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrLevel = getArticleList($_conf_tbl["member_level"], $scale, $_REQUEST['offset'], "order by level_no desc ");

if($_REQUEST["idx"]) {
	$arrInfo = getCouponInfo($_REQUEST["idx"]);
	$mode = "modify";
} else {
	$mode = "add";
}

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
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

	$("input:radio[name='coupon_type']").click(function(){ // 주소록리스트 hide/show
		if(this.value=="auto"){
			$(".addresslist").hide();		
		}else{
			$(".addresslist").show();
		}		
	});

	<?if($mode=="modify"){?>
	fnGoodPrint("addidxs","<?=$arrBoardArticle["list"][0]["addidxs"]?>");
	fnGoodPrint("cateidxs","<?=$arrBoardArticle["list"][0]["cateidxs"]?>");
	fnGoodPrint("brandidxs","<?=$arrBoardArticle["list"][0]["brandidxs"]?>");
	<?}?>
});
function frmCheck(f){
	if(f.coupon_name.value.length < 1){
		alert("혜택명을 입력하세요.");
		f.coupon_name.focus();
		return false;
	}
	if(f.coupon_dis.value.length < 1){
		alert("할인금액(할인율)을 입력하세요.");
		f.coupon_dis.focus();
		return false;
	}
	/*
	if(f.coupon_sdate.value.length < 1){
		alert("사용기한을 입력하세요.");
		f.coupon_sdate.focus();
		return false;
	}
	if(f.coupon_edate.value.length < 1){
		alert("사용기한을 입력하세요.");
		f.coupon_edate.focus();
		return false;
	}
	*/
	f.submit();
}
/////////////////////////////////// 팝업
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
<div class="container">

	<div class="title">할인혜택 <?=$arrInfo["list"][0]['idx']?"수정":"등록"?></div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="frmCoupon" method="post" action="/backoffice/module/coupon/coupon_evn.php">
			<input type="hidden" name="evnMode" value="<?=$mode?>">
			<input type="hidden" name="idx" value="<?=$arrInfo["list"][0]["idx"]?>">
			<input type="hidden" name="returnURL" value="/backoffice/module/coupon/member_coupon.php?rlist=T">
			<input type="hidden" name="coupon_qty" value="0">
			<input type="hidden" name="member_coupon" value="Y">

			<input type="hidden" name="addidxs" value="<?=$arrInfo["list"][0]["addidxs"]?>">
			<input type="hidden" name="cateidxs" value="<?=$arrInfo["list"][0]["cateidxs"]?>">
			<input type="hidden" name="brandidxs" value="<?=$arrInfo["list"][0]["brandidxs"]?>">

			<div class="tit">기본정보 <i>*</i></div>
			<table>
				<tr style="display:none;">
					<th>발급 구분</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="coupon_type" value="auto"<?=$arrInfo["list"][0]['coupon_type']!="manual"?" checked":""?>><i></i>자동 발급</label>
						<label class="radio"><input type="radio" name="coupon_type" value="manual"<?=$arrInfo["list"][0]['coupon_type']=="manual"?" checked":""?>><i></i>수동 발급</label> 					
					</div></td>
				</tr>
				<tr>
					<th>할인구분</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="coupon_unit" value="P"<?=$arrInfo["list"][0]['coupon_unit']!="F"?" checked":""?>><i></i>%할인</label>
						<label class="radio"><input type="radio" name="coupon_unit" value="F"<?=$arrInfo["list"][0]['coupon_unit']=="F"?" checked":""?>><i></i>금액할인</label> 						
					</div></td>
				</tr>				
				<tr>
					<th>혜택명</th>
					<td><div class="inputs"><input type="text" class="w4" name="coupon_name" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['coupon_name'])?>"></div></td>
				</tr>
				<tr>
					<th>할인금액(할인율)</th>
					<td><div class="inputs"><input type="text" class="w2 numberOnly" name="coupon_dis" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['coupon_dis'])?>"><em>&nbsp;원(%)</em></div></td>
				</tr>			
				<tr>
					<th>사용기한</th>
					<td><div class="inputs">		
						<input type="text" class="w2 datepicker" name="coupon_sdate" value="<?=stripslashes($arrInfo["list"][0]['coupon_sdate'])?>" maxlength="10" /><em>&nbsp;~&nbsp;</em>
						<input type="text" class="w2 datepicker" name="coupon_edate" value="<?=stripslashes($arrInfo["list"][0]['coupon_edate'])?>" maxlength="10" />
					</div></td>
				</tr>
				<tr>
					<th>최소주문금액</th>
					<td><div class="inputs"><input type="text" class="w2 numberOnly" name="under_price" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['under_price'])?>"><em>&nbsp;이상 주문 시</em></div></td>
				</tr>
				<tr>
					<th>최대할인금액</th>
					<td><div class="inputs"><input type="text" class="w2 numberOnly" name="over_price" maxlength="100" value="<?=stripslashes($arrInfo["list"][0]['over_price'])?>"><em>&nbsp;원</em></div></td>
				</tr>

				<tr style="display:none;">
					<th>카테고리</th>
					<td>
						<div class="btns" style="height:30px;margin-top:0;margin-bottom:10px; justify-content: left">
							<a href="javascript:void(0);" class="btn" onclick="OpenApplyView('cate','cateidxs')">추가</a>
						</div>
						<div class="bdr_list tac" style="width:600px;board:1px">
							<table>
								<colgroup>									
									<col width="20%">
									<col width="60%">
									<col width="20%">
								</colgroup>
								<thead>
									<tr>							
										<th style="text-align:center;padding:20px 0;">번호</th>
										<th style="text-align:center;padding:20px 0;">카테고리명</th>
										<th style="text-align:center;padding:20px 0;">관리</th>
									</tr>
								</thead>
								<tbody id="categorylist">
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr style="display:none;">
					<th>브랜드</th>
					<td>
						<div class="btns" style="height:30px;margin-top:0;margin-bottom:10px; justify-content: left">
							<a href="javascript:void(0);" class="btn" onclick="OpenApplyView('brand','brandidxs')">추가</a>
						</div>
						<div class="bdr_list tac" style="width:600px;board:1px">
							<table>
								<colgroup>									
									<col width="20%">
									<col width="60%">
									<col width="20%">
								</colgroup>
								<thead>
									<tr>							
										<th style="text-align:center;padding:20px 0;">번호</th>
										<th style="text-align:center;padding:20px 0;">브랜드명</th>
										<th style="text-align:center;padding:20px 0;">관리</th>
									</tr>
								</thead>
								<tbody id="brandlist">
								</tbody>
							</table>
						</div>
					</td>
				</tr>	
			</table>
			<div class="tit addresslist" <?=$arrInfo["list"][0]['coupon_type']!="manual"?" style=\"display:none;\" ":""?>>주소록<i>*</i></div>		
			<table>	
				<tr class="addresslist" <?=$arrInfo["list"][0]['coupon_type']!="manual"?" style=\"display:none;\" ":""?>>
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
				<tr class="addresslist" <?=$arrInfo["list"][0]['coupon_type']!="manual"?" style=\"display:none;\" ":""?>>
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
				<a href="/backoffice/module/coupon/member_coupon.php?<?=$reQueryString?>" class="btn btn_list">목록보기</a>
				<button class="btn btn_save" type="button" onclick="frmCheck(document.frmCoupon)">저장</button>
			</div>
		</form>
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