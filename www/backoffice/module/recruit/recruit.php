<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/recruit/recruit.lib.php";
if(!in_array("recruit_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale = 20;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getRecruitListAdmin($scale, $_REQUEST['offset']);

//DB해제
SetDisConn($dblink);

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

 // 기간설정
function setPeriod(pdate){
	document.frmSort.s_date.value = pdate;
	document.frmSort.e_date.value = "<?=date("Y-m-d")?>";
}

</script>

<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<div class="admin-title-top">
			<h2 class="admin-title">인재DB</h2>
			<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 인재DB</div>
		</div>

		<h3 class="admin-title-middle">검색</h3>
			<form name="frmSort" method="get" action="<?=$_SERVER['PHP_SELF']?>">
			<table  class="admin-table-type1">
				<colgroup>
					<col width="140" />
					<col width="*" />
					<col width="140" />
				</colgroup>
				<tbody>
				<tr>
					<th>통합검색</th>
					<td class="space-left">
						<select name="sw">
						<option value="name"<?=$_REQUEST['sw']=="name"?" selected":""?>>이름</option>
						</select>
						<input type="text" name="sk" value="<?=$_REQUEST['sk']?>" class="input" />&nbsp;&nbsp;&nbsp;
					</td>
					<td rowspan="2"><span class="btn_pack xlarge"><input type="submit" style="width:100px;font-weight:bold" value=" 검 색 " /></span></td>
				</tr>
				<tr>
					<th>등록일자</th>
					<td class="space-left">
						<input type="text" name="s_date" id="s_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['s_date']?>" /> ~ <input type="text" name="e_date" id="e_date" style="width:80px;"  class="datePicker input" value="<?=$_REQUEST['e_date']?>" />
						&nbsp;
						<?
						$yes_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*1));
						$yes3_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*3));
						$to_day = date('Y-m-d');
						$week_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*7));
						$month_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*30));
						?>
						<span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$to_day?>')" style="color:#660000;">오늘</a></span>
						<span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$yes_day?>')" style="color:#660000;">어제</a></span>
						<span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$yes3_day?>')" style="color:#660000;">3일전</a></span>
						<span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$week_day?>')" style="color:#660000;">1주일</a></span>
						<span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('<?=$month_day?>')" style="color:#660000;">1개월</a></span>
						<span class="btn_pack small" style="margin-top:1px;"><a href="javascript:setPeriod('')" style="color:#660000;">전체</a></span>
					</td>
				</tr>
			</table>
		</form>

		<br />

		<div class="clfix mgb5">
			<div class="fl" style="padding-top:5px;">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
			<!-- <div class="fr"><span class="btn_pack medium icon"><span class="download"></span><a href="/backoffice/module/shop/order_to_csv.php?s_date=<?=$_REQUEST['s_date']?>&e_date=<?=$_REQUEST['e_date']?>&s_price=<?=$_REQUEST['s_price']?>&e_price=<?=$_REQUEST['e_price']?>&sw=<?=$_REQUEST['sw']?>&sk=<?=$_REQUEST['sk']?>&sk2=<?=$_REQUEST['sk2']?>&order_state=<?=$_REQUEST['order_state']?>&sh_date=<?=$_REQUEST['sh_date']?>&orderstate=<?=$orderstate?>&paytype=<?=$paytype?>&mode=<?=$_REQUEST['mode']?>" target="_blank">주문목록 CSV로 받기</a></span></div> -->
		</div>

		<table class="admin-table-type1">
			<thead>
				<tr>
					<th width="5%">No.</th>
					<th width="10%">이름</th>
					<th width="5%">성별</th>
					<th width="10%">생년</th>
					<th width="10%">국적</th>
					<th width="10%">등록일</th>
				</tr>
			</thead>
			<tbody>
			<?if($arrList['list']['total'] > 0):?>

				<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
				<tr>
					<td><?=number_format($arrList['total']-$i-$_REQUEST['offset'])?></td>
					<td><a href="recruit_info.php?uridx=<?=$arrList['list'][$i]['ur_idx']?>"><?=$arrList['list'][$i]['ur_name']?></a></td>
					<td><?=$arrList['list'][$i]['ur_gen']?></td>
					<td><?=$arrList['list'][$i]['ur_birthy']?></td>
					<td>
							<?
								if($arrList['list'][$i]['ur_nat']=='2'){
									echo '외국인';
								}else{
									echo '내국인';
								}
							?>
					</td>
					<td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
				</tr>
				<?}?>

			<?else:?>
				<tr height="100">
					<td width="100%" colspan="6">등록된 정보가 없습니다.</td>
				</tr>
			<?endif;?>
			</tbody>
		</table>
		<div class="paginate">
		  <?=pageNavigation($arrList['total'],$scale,$pagescale,$_REQUEST['offset'],'')?>
		</div>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php" ;
?>