<?
############################ 저장후 리턴페이지 설정 ############################ ST
if($_GET['rlist']=="T"){
	session_start();
	header("location: ".$_SERVER['PHP_SELF']."?".$_SESSION['searchParam']);
	exit();
}
############################ 저장후 리턴페이지 설정 ############################ ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/pop_top.php";

include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
if(!in_array("product_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;
############################ 저장후 리턴페이지 설정 ############################ ST
if($_GET['rlist']!="T"){
	if($_SERVER['QUERY_STRING']){
		$_SESSION['searchParam'] = $_SERVER['QUERY_STRING'];
	}
}
############################ 저장후 리턴페이지 설정 ############################ ED
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//현재카테고리 정보
$arrInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));
//현재카테고리 패쓰
$arrPath = getCategoryPath(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));
//카테고리 목록
$arrList = getCategoryList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["cat_no"]));

//_DEBUG($arrList);
//DB해제
SetDisConn($dblink);

$arrCate = explode("/", $arrInfo["list"][0]['cat_code']);
?>
<style type="text/css">
	.cat_image{max-width:120px;max-height:40px;}
</style>
<script type="text/javascript">
<!--
// 선택 삭제시 singleSelect=true 값 변경 false
function getSelections(){
	var ss = "";
	var comma = "";
	var rows = $('input:checkbox[name=chk_list]:checked');
	
	for(var i=0; i<rows.length; i++){
		var row = rows[i];
		//ss.push(row.idx);
		ss += comma+row.value;
		comma = ",";
	}
	if(rows.length>0){
		//alert(ss);
		fnAddMsds(ss);
	}else{
		alert('선택된 항목이 없습니다.');
	}	
}
function fnAddMsds(ss){
	parent.fnGoodSelect(ss,'<?=$_GET['fname']?>');
}
$(function(){
    $(".check_all").click(function(){		
        var chk = $(this).is(":checked");//.attr('checked');
        if(chk) $(".chk_list").prop('checked', true);
        else  $(".chk_list").prop('checked', false);
    });

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
//-->
</script>
<div class="container">

	<div class="title">분류 목록</div>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">
				<a href="<?=$_SERVER['PHP_SELF']?>?cat_no=<?=$arrPath['list'][0]['cat_no']?>">ROOT</a>
				<?
				if($arrPath['total'] > 0){
					for($i=0; $i < $arrPath['total']; $i++){
				?>
				 > <a href="<?=$_SERVER['PHP_SELF']?>?cat_no=<?=$arrPath['list'][$i]['cat_no']?>"><?=$arrPath['list'][$i]['cat_name']?></a>
				<?
					}
				}
				?>
				<!-- Total : <strong><?=number_format($arrList['total'])?></strong>--></div>				
			</div>
		</div>
		
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w6p">
						<col class="w6p">
						<col class="w8p">
						<col class="w8p">
						<col class="*">
						<col class="w8p">
						<col class="w12p">
					</colgroup>
					<thead>
						<tr>						
							<th><label class="check notxt"><input type="checkbox" name="" id="allCheck"><i></i></label></th>
							<th class="pc_vw">No.</th>	
							<th class="pc_vw">하위</th>
							<th class="pc_vw">하위보기</th>
							<th class="pc_vw">분류명</th>
							<th class="pc_vw">사용</th>			
							<th class="pc_vw">관리</th>
						</tr>
					</thead>
					<tbody id="sortWrap">
					<?
					//DB연결
					$dblink = SetConn($_conf_db["main_db"]);

					if($arrList['total'] > 0){
						for ($i=0;$i<$arrList['total'];$i++){							
					?>
						<tr data-order="<?=$arrList["list"][$i]['cat_no']?>">
							<td class="chkbox"><label class="check notxt"><input type="checkbox" value="<?=$arrList["list"][$i]['cat_no']?>" name="chk_list"><i></i></label></td>		
							<td style="width:6%"><?=$arrList["list"][$i]['cat_no']?></td>	
							<td><i class="mo_vw">하위</i><?=number_format($arrList["list"][$i]['total_sub'])?></td>
							<td><i class="mo_vw">하위보기</i><a href="<?=$_SERVER['PHP_SELF']?>?cat_no=<?=$arrList["list"][$i]['cat_no']?>&fname=<?=$_GET['fname']?>"><img src="./images/btn_view.gif" border=0 alt="<?=$arrList["list"][$i]['cat_name']?>"></a></td>							
							<td><i class="mo_vw">명칭</i><?=stripslashes($arrList["list"][$i]['cat_name'])?>
												
							<td style="width:8%"><?=stripslashes($arrList["list"][$i]['cat_is_show'])?></td>		
							<td style="width:12%">
								<div class="btns">
									<a href="javascript:void(0);" onclick="fnAddMsds('<?=$arrList["list"][$i]['cat_no']?>')" class="btn perf">등록</a>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td width="100%" colspan="5" >생성된 데이터가 없습니다.</td>
					</tr>
					<?
					}
					//DB해제
					SetDisConn($dblink);
					?>
					</tbody>
				</table>
			</div>
		</div>		

		<div class="bdr_btm">
			<div class="btns">
				<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택등록</a>
			</div>
		</div>				
	</div>
</div>
<?php include("pub/inc/footer.php") ?>