<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include_once $_SERVER ['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include_once $_SERVER ['DOCUMENT_ROOT'] . "/module/coupon/coupon.lib.php";

if (! in_array ( "member_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );

endif;

if($_GET['page_size']){
	$scale = $_GET['page_size'];
}else{
	$scale = 20;
}

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$subQuery = " AND user_level < 3 ";
if($_GET['join_type']){
	$subQuery .= " AND join_type='".$_GET['join_type']."' ";
}
if($_GET['job']){
	$subQuery .= " AND job='".$_GET['job']."' ";
}
if($_GET['email_accept']){
	$subQuery .= " AND email_accept='".$_GET['email_accept']."' ";
}
if($_GET['sms_accept']){
	$subQuery .= " AND sms_accept='".$_GET['sms_accept']."' ";
}

$arrList = getMemberList( mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['jb'] ),  mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['sw'] ), mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['sk'] ), $scale, $_REQUEST ['offset'] , $subQuery, " order by user_level asc, idx desc");
// _DEBUG($arrList);

$arrLevel = getArticleList ( $_conf_tbl ["member_level"], 0, 0, "order by level_no desc " );

for($i = 0; $i < $arrLevel["total"]; $i ++) {
	$arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}

$arrCouponList = getCouponListAdmin(0, 0, "Y");

$arrCount = getArticleList("tbl_member", 0, 0, " where user_level=0 ");

// DB해제
SetDisConn ( $dblink );
?>
<script> 
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
function boardDel(val){
	//location.href="/backoffice/module/member/ajax_member_del.php?g_idx="+val;
	//return;
	//alert(val);
	//return;
	if(confirm("탈퇴 처리 하시겠습니까?")) {
		$.post("/backoffice/module/member/ajax_member_out.php", {g_idx: val },
		function(data){		
			//alert(data);
			location.reload();
		});
	}
}
function memberLevel(idx, lval){
	//if(confirm("해당 회원의 등급을 변경 하시겠습니까?")) {
		$.post("/backoffice/module/member/ajax_member_level.php", {g_idx: idx,levelval: lval},
		function(data){		
			//alert(data);
			location.reload();
		});
	//}
}

function userCoupon(uid, idx){
	if(confirm("해당 회원에게 쿠폰을 발행합니다. 계속 하시겠습니까?")) {
		$.post("/backoffice/module/coupon/coupon_evn.php", {evnMode:"user_coupon", idx: idx,user_id: uid},
		function(data){		
			if(data=="OK"){
				alert("발행되었습니다.");
			}
			//location.reload();
		});
	}
}
function delMember(id){
	var cfm;
	cfm =false;
	cfm = confirm(id + " 이 회원을 탈퇴처리 하시겠습니까?\n\n탈퇴처리시 복구 불가능합니다.");
	if(cfm==true){
		document.frmContentsHidden.user_id.value = id;
		document.frmContentsHidden.submit();
	}
}
</script>
<div class="container">

	<div class="title">회원가입 신청</div>

	<form name="frmSort" method="get" action="member_standby.php">

	<div class="inbox top_search">
		<!--<dl>
			<dt>회원정렬</dt>
			<dd>
				<select name="orderby" id="" style="width:120px;" onchange="document.frmSort.submit()" >
					<option value="">회원가입날짜</option>
					<option value="sum_amount" <?=$_GET['orderby']=="sum_amount"?"selected":""?>>총 구매금액 순</option>
					<option value="cnt_amount" <?=$_GET['orderby']=="cnt_amount"?"selected":""?>>총 구매 건수</option>
					<option value="cnt_cag"  <?=$_GET['orderby']=="cnt_cag"?"selected":""?>>교환 완료</option>
					<option value="cnt_cet"  <?=$_GET['orderby']=="cnt_cet"?"selected":""?>>환불 완료</option>
				</select>
			</dd>
		</dl>-->
		<!--<dl>
			<dt>가입구분</dt>
			<dd>
				<select name="join_type" id="" style="width:100px;">
					<option value="">전체</option>
					<option value="homepage" <?=$_GET['join_type']=="homepage"?"selected":""?>>일반</option>
					<option value="naver"  <?=$_GET['join_type']=="naver"?"selected":""?>>네이버</option>
					<option value="kakao"  <?=$_GET['join_type']=="kakao"?"selected":""?>>카카오</option>
					<option value="google"  <?=$_GET['join_type']=="google"?"selected":""?>>구글</option>
				</select>
			</dd>
		</dl>-->
		<dl>
			<dt>처리상태</dt>
			<dd>
				<select name="user_level" id="" style="width:100px;">
					<option value="">전체</option>
					<?
					for ($i=0;$i<$arrLevel['total'];$i++){
						if($arrLevel['list'][$i]['level_name']!="승인"){
					?>
					<option value="<?=$arrLevel['list'][$i]['level_no']?>"<?=$arrLevel['list'][$i]['level_no']==$_GET["user_level"]?" selected":""?>><?=$arrLevel['list'][$i]['level_name']?></option>
					<?	}
					}	?>
					
				</select>
			</dd>
		</dl>
		<dl class="w2">
			<dt>가입신청 날짜</dt>
			<dd><input type="text" class="datepicker" name="s_date" value="<?=$_REQUEST['s_date']?>"  /><em>~</em><input type="text" class="datepicker" name="e_date" value="<?=$_REQUEST['e_date']?>" /></dd>
		</dl>
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:100px;">
					<option value="all" <?=$_REQUEST['sw']=="all"?" selected":""?>>전체</option>
					<option value="name" <?=$_REQUEST['sw']=="name"?" selected":""?>>이름</option>
					<option value="id" <?=$_REQUEST['sw']=="id"?" selected":""?>>아이디</option>
					<option value="hp" <?=$_REQUEST['sw']=="hp"?" selected":""?>>휴대폰 번호 뒷 4자리</option>
					<option value="email" <?=$_REQUEST['sw']=="email"?" selected":""?>>이메일</option>
					<option value='etc_1' <?=$_REQUEST['sw']=="etc_1"?" selected":""?>>상호명</option> 
- 					<option value='etc_10' <?=$_REQUEST['sw']=="etc_10"?" selected":""?>>거래처등록번호</option> 
				</select>
				<input type="text" name="sk" value="<?=$_GET['sk']?>" onkeypress="if( event.keyCode == 13 ){document.frmSort.submit();}"/>
				<button type="button" class="search" onclick="document.frmSort.submit()">검색</button>
			</dd>
		</dl>
	</div>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<!--<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>-->
				<div class="total">신규 회원가입 신청 : <strong><?=number_format($arrCount['total'])?>건</strong></div>
				
				<div class="down">
					<a href="/backoffice/module/member/member_to_xls.php?stby=y&sw=<?=$_REQUEST['sw']?>&sk=<?=$_REQUEST['sk']?>&user_level=<?=$_REQUEST['user_level']?>&s_date=<?=$_REQUEST['s_date']?>&e_date=<?=$_REQUEST['e_date']?>" class="excel">엑셀다운<span class="pc_vw">로드</span></a>		
				</div>
			</div>
			
			<div class="count">
				<select name="page_size" onchange="document.frmSort.submit()" style="width:100px;">
					<option value="100" <?if($scale=="100"){echo 'selected="selected"';}?>>100</option>
					<option value="50" <?if($scale=="50"){echo 'selected="selected"';}?>>50</option>
					<option value="40" <?if($scale=="40"){echo 'selected="selected"';}?>>40</option>
					<option value="30" <?if($scale=="30"){echo 'selected="selected"';}?>>30</option>
					<option value="20" <?if($scale=="20"){echo 'selected="selected"';}?>>20</option>
					<option value="15" <?if($scale=="15"){echo 'selected="selected"';}?>>15</option>
					<option value="10" <?if($scale=="10"){echo 'selected="selected"';}?>>10</option>
				</select>
				개씩 보기
			</div>
		</div>
		</form>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<!--<col class="check">-->
						<col class="w4p">
						<col class="w8p">
						<col class="w8p">
						<col class="w20p">
						<col class="w8p">
						<col class="w6p">
						<col class="w8p">
						<col class="w10p">
						<col class="w6p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>	
							<!--<th><label class="check notxt"><input type="checkbox" name="" id="allCheck"><i></i></label></th>-->
							<th class="pc_vw">No.</th>
							<th class="pc_vw">상호명<br/>(거래처등록코드)</th>
							<th class="pc_vw">사업자등록번호</th>
							<th class="pc_vw">주소</th>
							<th class="pc_vw">ID(아이디)</th>
							<th class="pc_vw">담당자이름</th>
							<th class="pc_vw">담당자연락처</th>
							<th class="pc_vw">가입날짜</th>
							<th class="pc_vw">처리상태</th>
							<th class="pc_vw">관리</th>
						</tr>
					</thead>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							
					?>
						<tr>
							<!--<td class="chkbox"><label class="check notxt"><input type="checkbox" value="<?=$arrList["list"][$i]['idx']?>" name="chk_list"><i></i></label></td>-->
							<td><i class="mo_vw">No.</i><?=number_format($arrList['total']-$i-$_REQUEST['offset'])?></td>
							<td><i class="mo_vw">상호명</i><?=$arrList['list'][$i]['etc_1']?><br/><?=$arrList['list'][$i]['etc_10']?"(".$arrList['list'][$i]['etc_10'].")":""?></td>
							<td><i class="mo_vw">사업자등록번호</i><?=$arrList['list'][$i]['etc_2']?></td>
							<td><i class="mo_vw">주소</i>[<?=$arrList['list'][$i]['zip']?>] <?=$arrList['list'][$i]['address']?> <?=$arrList['list'][$i]['address_ext']?></td>							
							<td><i class="mo_vw"><a href="member_standby_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="linktxt">ID(아이디)</i><?=$arrList['list'][$i]['user_id']?></a></td>
							<td><i class="mo_vw">담당자이름</i><?=$arrList['list'][$i]['user_name']?></td>
							<td><i class="mo_vw">담당자연락처</i><?=$arrList['list'][$i]['mobile']?></td>							
							<td><i class="mo_vw">가입날짜</i><?=$arrList['list'][$i]['wdate']?></td>	
							<td><i class="mo_vw">처리상태</i><?=$_SITE["MEMBER_LEVEL"][$arrList['list'][$i]['user_level']]?></td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="member_standby_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>" class="btn perf">확인</a>
									<button type="button" class="btn del" onclick="delMember('<?=$arrList['list'][$i]['user_id']?>');">삭제</button>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td colspan="10">등록된 데이터가 없습니다.</td>
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
			echo pageNavigationBackoffice($arrList["total"],$scale,10,$_GET['offset'],$reQueryString);
			############### paging ############### ED
			?>			
			</div>	
			<div class="btns">
			<!--	<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택탈퇴</a>-->
			<a href="/backoffice/module/member/member_info.php"  class="btn btn">회원가입</a>
			</div>
		</div>
	</div>
</div>
<form name="frmContentsHidden" method="post" action="member_evn.php">
	<input type="hidden" name="evnMode" value="delete">
	<input type="hidden" name="user_id">
	<input type="hidden" name="returnURL" value="<?=$_SERVER['REQUEST_URI']?>">
</form>
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

<?php include("pub/inc/footer.php") ?>