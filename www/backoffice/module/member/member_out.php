<?
include $_SERVER ['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER ['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if (! in_array ( "member_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );

endif;

$scale = 20;

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$_GET ['level'] = "90";

$arrList = getMemberList ( mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['jb'] ),  mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['sw'] ), mysqli_real_escape_string ( $GLOBALS ['dblink'], $_REQUEST ['sk'] ), $scale, $_REQUEST ['offset'] );
// _DEBUG($arrList);

for($i = 0; $i < $arrLevel ["total"]; $i ++) {
	$arrayLevel [$arrLevel ["list"] [$i] ['level_no']] = $arrLevel ["list"] [$i] ['level_name'];
}
// DB해제
SetDisConn ( $dblink );
?>
<script src="/backoffice/js/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/common/js/datePicker/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/common/js/datePicker/jquery-ui.css" />
<script>
$(function() {
// $.datepicker.setDefaults($.datepicker.regional["ko"]);
    $(".datePicker").datepicker({ 
     dateFormat: 'yy-mm-dd',
     monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
     dayNamesMin: ['일','월','화','수','목','금','토'],
	 weekHeader: 'Wk',
     changeMonth: true, //월변경가능
     changeYear: true, //년변경가능
     showMonthAfterYear: true //년 뒤에 월 표시
  });
 });
 
 $(function(){
    $(".check_all").click(function(){		
        var chk = $(this).is(":checked");//.attr('checked');
        if(chk) $(".chk_list").prop('checked', true);
        else  $(".chk_list").prop('checked', false);
    });
});
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
	if(confirm("삭제 처리 하시겠습니까?")) {
		$.post("/backoffice/module/member/ajax_member_out.php", {g_idx: val },
		function(data){		
			//alert(data);
			location.reload();
		});
	}
}

</script>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<div class="admin-title-top">
			<h2 class="admin-title">탈퇴회원 관리</h2>
			<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 회원 관리 &nbsp;&gt;&nbsp; 탈퇴회원 리스트</div>
		</div>
		<script language="javascript" src="/common/util.js"></script>
		<script language="javascript">
function delMember(id){
	var cfm;
	cfm =false;
	cfm = confirm(id + "을 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmContentsHidden.user_id.value = id;
		document.frmContentsHidden.submit();
	}
}
</script>
		<h3 class="admin-title-middle">탈퇴회원검색</h3>
		<table class="admin-table-type1">
			<colgroup>
				<col width="140" />
				<col width="*" />
			</colgroup>
			<tbody>
				<form name="frmSort" method="get" action="member_out.php">
					<tr>
						<th>검색</th>
							<td class="space-left">							
							<select name="sw">
								<option value="all" <?=$_REQUEST['sw']=="all"?" selected":""?>>전체</option>
								<option value="id" <?=$_REQUEST['sw']=="id"?" selected":""?>>아이디</option>
								<option value="name" <?=$_REQUEST['sw']=="name"?" selected":""?>>성명</option>
							</select>
							<input type="text" name="sk" value="<?=$_REQUEST['sk']?>" class="input" /> <input type="image" src="/backoffice/images/btn_search.gif" alt="검색" />
						</td>
					</tr>
				</form>
			</tbody>
		</table>
		<br />
		<div class="mgb5">
			<div class="fl" style="padding-top: 4px;">
				&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 명</strong>
			</div>
			<div class="fr"></div>
		</div>
		<table class="admin-table-type1">
			<thead>
				<tr>
					<th width="2%"><input type="checkbox" class="check_all" value="Y" /></th>
					<th width="4%">No.</th>
					<th width="8%">ID</th>
					<th width="8%">이름</th>
					<th width="50%">탈퇴사유</th>
					<th width="6%">탈퇴일</th>
					<th width="5%">관리</th>
				</tr>
			</thead>
			<tbody>
			<?if($arrList['list']['total'] > 0):?>

				<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
				<tr>
					<td><input type="checkbox" class="chk_list" value="<?=$arrList["list"][$i]['idx']?>" name="chk_list" /></td>
					<td><?=number_format($arrList['total']-$i-$_REQUEST['offset'])?></td>
					<td><a href="member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><?=$arrList['list'][$i]['user_id']?></a></td>
					<td><a href="member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><?=$arrList['list'][$i]['user_name']?></a></td>

					<td><?=$arrList['list'][$i]['etc_10']?></td>
					
					<td><?=substr($arrList['list'][$i]['outdt'],0,10)?></td>
					<td class="b02"><a href="member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"> <img src="/backoffice/images/k_modify.gif" alt="수정" /></a> <a href="javascript:delMember('<?=$arrList['list'][$i]['user_id']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
				</tr>
				<?}?>

			<?else:?>
				<tr height="100">
					<td width="100%" colspan="9">탈퇴 회원이 없습니다.</td>
				</tr>
			<?endif;?>
			</tbody>
		</table>
		<span class="btn_pack medium delete" style="margin-top:10px;"><input type="button" value=" 선택탈퇴 " onclick="getSelections();" style="font-weight: bold" /></span>
		<div class="paginate">
		  <?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"&jb=".$_REQUEST['jb']."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk'])?>
		</div>
		<form name="frmContentsHidden" method="post" action="member_evn.php">
			<input type="hidden" name="evnMode" value="out">
			<input type="hidden" name="user_id">
			<input type="hidden" name="returnURL" value="<?=$_SERVER['REQUEST_URI']?>">
		</form>
	</div>
</div>
<?
include $_SERVER ['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>