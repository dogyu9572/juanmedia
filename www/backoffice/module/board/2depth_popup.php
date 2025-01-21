<?PHP
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/iframe_admin_top.php";
include_once $_SERVER ['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER ['DOCUMENT_ROOT'] . "/module/board/board.lib.php";

if (! in_array ( "board_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );

endif;

$scale = 20;

$categoryid = $_POST["categoryid"];

$cat_parent_no = $_POST["cat_parent_no"];

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$categoryInfo = getCategoryInfoView($categoryid);

$arrTotalList = getCategoryList_id($categoryid,$cat_parent_no);

if($cat_parent_no != ""){
	$arrParentInfo = getCategoryInfo_id($categoryid,$cat_parent_no);
}

$arrData = $_POST["cat_no"];


// DB해제
SetDisConn ( $dblink );
?>
<script> 
 // 선택 삭제시 singleSelect=true 값 변경 false
function getSelections(){
	var ss = "0";

	var rows = $('input:checkbox[name=chk_list]:checked');
	if(rows.length>0){
		for(var i=0; i<rows.length; i++){
			window.parent.addSpecificCat("<?=$categoryid?>",rows[i].value,$(rows[i]).attr("data-name"));
		}
	}else{
		alert('선택된 항목이 없습니다.');
	}
	
}
</script>
<div class="iframe_container pop_iframe_cont">

	<div class="title"><a href="javascript:" onclick="window.parent.popup2Depth('<?=$categoryid?>','')"><?=$categoryInfo["list"][0]["categoryname"]?></a><?php if($cat_parent_no != ""){ ?> > <?=$arrParentInfo['list'][0]['cat_name']?><?php } ?> 추가/삭제</div>

	<div class="inbox">
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrTotalList['total'])?></strong></div>
			</div>
			<div class="right">
				<div class="btns">
					<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택 추가</a>
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
						<col class="w6p">
						<col class="*">
					</colgroup>
					<thead>
						<tr>	
							<th><label class="check notxt"><input type="checkbox" name="" id="allCheck"><i></i></label></th>
							<th class="pc_vw">카테고리 명</th>
						</tr>
					</thead>
					<tbody>
					<?
					
					if($arrTotalList['total'] > 0){
						for($i=0;$i<$arrTotalList["total"];$i++){
					?>
						<tr>
							<?php
								if($arrTotalList["list"][$i]['cat_depth'] != 0){
							?>
							<td class="chkbox"><label class="check notxt"><input type="checkbox" value="<?=$arrTotalList["list"][$i]['cat_no']?>" data-name="<?php if($cat_parent_no != ""){ ?><?=$arrParentInfo['list'][0]['cat_name']?> > <?php } ?><?=$arrTotalList['list'][$i]['cat_name']?>" name="chk_list" <?=in_array($arrTotalList["list"][$i]['cat_no'],$arrData)?"checked":""?>><i></i></label></td>
							<?php }else{ ?>
							<td><a href="javascript:" onclick="window.parent.popup2Depth('<?=$categoryid?>','<?=$arrTotalList['list'][$i]['cat_no']?>')"><img src="/backoffice/module/category/images/btn_view.gif" border=0 alt="하위보기"></a></td>
							<?php } ?>
							<td><i class="mo_vw">카테고리 명</i><?=$arrTotalList['list'][$i]['cat_name']?></td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td colspan="2">등록된 데이터가 없습니다.</td>
					</tr>
					<?}
					
					?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="bdr_btm">
			<div class="btns">
				<a href="javascript:void(0);" onclick="getSelections()" class="btn btn_del">선택 추가</a>
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

<?php include("pub/inc/footer.php") ?>