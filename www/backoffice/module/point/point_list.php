<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "../coupon/menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";

if(!in_array("homepage_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale = 10;
if($_GET['page_size']){
	$scale = $_GET['page_size'];	
}
$pagescale = 10;
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getPointListAdmin(
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sw]), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[sk]), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[type]), 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[s_date]),
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[e_date]),
	$scale, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[offset])
	);
//_DEBUG($arrList);

//DB해제
SetDisConn($dblink);
?>
<script type="text/javascript">
<!--
function couponDel(id){
	var cfm;
	cfm =false;
	cfm = confirm("등록된 정보을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmContentsHidden.idx.value = id;
		document.frmContentsHidden.submit();
	}
}	

function fnSearch(frm){
	frm.submit();	
}
//-->
</script>
<div class="container">

	<div class="title">적립금 목록</div>

	<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">		
	<div class="inbox top_search">
		<dl>
			<dt>구분</dt>
			<dd>
				<select name="type">
					<option value="all"<?=$_REQUEST['type']=="all"?" selected":""?>>전체　　</option>
					<option value="plus"<?=$_REQUEST['type']=="plus"?" selected":""?>>적립</option>
					<option value="minus"<?=$_REQUEST['type']=="minus"?" selected":""?>>사용</option>
				</select>
			</dd>
		</dl>
		<dl>
			<dt>조회기간</dt>
			<dd>
				<input type="text" class="w1 datepicker" name="s_date" value="<?=$_REQUEST['s_date']?>" maxlength="10" /><em>&nbsp;~&nbsp;</em>
				<input type="text" class="w1 datepicker" name="e_date" value="<?=$_REQUEST['e_date']?>" maxlength="10" />
			</dd>
		</dl>		
		<dl class="search_wrap">
			<dt>검색어</dt>
			<dd>
				<select name="sw" style="width:90px;">
					<option value='all'<?=$_GET['sw']=="all"?" selected='selected'":""?>>전체</option>
					<option value='name'<?=$_GET['sw']=="name"?" selected='selected'":""?>>이름</option>
					<option value='id'<?=$_GET['sw']=="id"?" selected='selected'":""?>>아이디</option>
				</select>	
				<input type="text" name="sk" value="<?=$_GET['sk']?>" onkeypress="if( event.keyCode == 13 ){fnSearch(document.form1);}"/>
				<button type="button" class="search" onclick="fnSearch(document.form1)">검색</button>
			</dd>
		</dl>
	</div>
	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
			</div>
			<div class="bdr_right">
				<div class="count">
					<select name="page_size" onchange="document.form1.submit()" style="width:60px;">
						<option value="100"	<?=$scale=="100"?'selected':""?>>100</option>
						<option value="50"	<?=$scale=="50"?'selected':""?>>50</option>
						<option value="40"	<?=$scale=="40"?'selected':""?>>40</option>
						<option value="30"	<?=$scale=="30"?'selected':""?>>30</option>
						<option value="20"	<?=$scale=="20"?'selected':""?>>20</option>
						<option value="15"	<?=$scale=="15"?'selected':""?>>15</option>
						<option value="10"	<?=$scale=="10"?'selected':""?>>10</option>
					</select>
					개씩 보기
				</div>
				</form>
				<div class="btns">	
					<a href="/backoffice/module/point/point_add.php" class="btn">신규등록</a>
				</div>
			</div>
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w6p">
						<col class="w10p">
						<col class="w6p">
						<col class="w6p">
						<col class="w8p">
						<col class="*">
						<col class="w8p">
						<col class="w8p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
							<th class="pc_vw">구분</th>
							<th class="pc_vw">회원아이디</th>
							<th class="pc_vw">사용</th>
							<th class="pc_vw">적립</th>
							<th class="pc_vw">잔액</th>
							<th class="pc_vw">내용</th>
							<th class="pc_vw">IP</th>
							<th class="pc_vw">사용/지급일</th>
							<th class="pc_vw">관리</th>
						</tr>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
							if($arrList['list'][$i]['minus']>0){
								$typeTxt = "사용";
								$typeClass = "used";
							}else{
								$typeTxt = "적립";
								$typeClass = "saved";								
							}
					?>
						<tr>
							<td><i class="mo_vw">No.</i><?=$arrList["total"]-$i-$_GET['offset']?></td>							
							<td class="<?=$typeClass?>"><i class="mo_vw">구분</i><?=$typeTxt?></td>						
							<td><i class="mo_vw">회원아이디</i><a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>" class="linktxt"><?=$arrList['list'][$i]['user_name']?> (<?=$arrList['list'][$i]['user_id']?>)</a></td>						
							<td><i class="mo_vw">사용</i><?=number_format($arrList['list'][$i]['minus'])?></td>						
							<td><i class="mo_vw">적립</i><?=number_format($arrList['list'][$i]['plus'])?></td>						
							<td><i class="mo_vw">잔액</i><?=number_format($arrList['list'][$i]['nowpoint'])?></td>						
							<td><i class="mo_vw">내용</i><?=stripslashes($arrList['list'][$i]['contents'])?></td>						
							<td><i class="mo_vw">IP</i><?=$arrList['list'][$i]['ip']?></td>				
							<td><i class="mo_vw">사용/지급일</i><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="/backoffice/module/point/point_add.php?user_id=<?=$arrList['list'][$i]['user_id']?>" class="btn modi">추가</a>
									<button type="button" class="btn del" onclick="couponDel('<?=$arrList['list'][$i]['idx']?>')">삭제</button>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td width="100%" colspan="10" >검색된 데이터가 없습니다.</td>
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
			//	echo pageNavigationUser($arrBoardList["total"],$arrBoardInfo["list"][0]["scale"],$arrBoardInfo["list"][0]["pagescale"],$_GET['offset'],$reQueryString);
			echo pageNavigationBackoffice($arrList['total'], $scale, $pagescale, $_GET['offset'], $reQueryString);
			############### paging ############### ED
			?>
			</div>
<!-- 			<div class="btns">	
				<a href="/backoffice/module/point/point_alladd.php" class="btn">신규등록</a>
			</div> -->
		</div>
	</div>
</div>
<form name="frmContentsHidden" method="post" action="point_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
<input type="hidden" name="returnURL" value="<?=$_SERVER[REQUEST_URI]?>">
</form>
<?php include("pub/inc/footer.php") ?>
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
});
//]]>
</script>