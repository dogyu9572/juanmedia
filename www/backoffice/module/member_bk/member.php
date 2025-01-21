<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
if(!in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale = 20;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getMemberList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), $scale, $_REQUEST['offset']);
//_DEBUG($arrList);

$arrLevel = getArticleList($_conf_tbl["member_level"], 0, 0, "order by level_no desc ");

for($i=0;$i<$arrLevel["total"];$i++){
	$arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}
//DB해제
SetDisConn($dblink);
?>
<script src="/backoffice/js/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/datePicker/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/js/datePicker/jquery-ui.css" />
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
</script>

<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">회원 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 회원 관리 &nbsp;&gt;&nbsp; 회원 목록</div>
	</div>

<script language="javascript" src="/common/util.js"></script>
<script language="javascript">
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

<h3 class="admin-title-middle">회원검색</h3>
<table class="admin-table-type1">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
	<form name="frmSort" method="get" action="member.php">
	<tr>
		<th>회원등급</th>
		<td class="space-left">
			<select name="user_level">
			<option value="">전체</option>
			<?for ($i=0;$i<$arrLevel['total'];$i++) {?>
			<option value="<?=$arrLevel['list'][$i]['level_no']?>"<?=$arrLevel['list'][$i]['level_no']==$_GET["user_level"]?" selected":""?>><?=$arrLevel['list'][$i]['level_name']?></option>
			<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>가입일</th>
		<td class="space-left">
		  <input type="text" name="s_date" style="width:80px;" value="<?=$_REQUEST['s_date']?>" class="input datePicker" /> ~ <input type="text" name="e_date" style="width:80px;" value="<?=$_REQUEST['e_date']?>" class="input datePicker" />
		</td>
	</tr>
	<tr>
		<th>검색</th>
		<td class="space-left">
	  		<select name="sw">
			<option value="all"<?=$_REQUEST['sw']=="all"?" selected":""?>>전체</option>
			<option value="id"<?=$_REQUEST['sw']=="id"?" selected":""?>>아이디</option>
			<option value="name"<?=$_REQUEST['sw']=="name"?" selected":""?>>성명</option>
			<option value="mobile"<?=$_REQUEST['sw']=="mobile"?" selected":""?>>핸드폰</option>
			</select>

		  <input type="text" name="sk" value="<?=$_REQUEST['sk']?>" class="input" /> <input type="image" src="/backoffice/images/btn_search.gif" alt="검색" />
		</td>
	</tr>
	</form>
  </tbody>
</table>
<br />
<div class="clfix mgb5">
    <div class="fl" style="padding-top:4px;">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 명</strong></div>
    <div class="fr"><span class="btn_pack medium icon"><span class="download"></span><a href="/backoffice/module/member/member_to_csv.php?sw=<?=$_REQUEST['sw']?>&sk=<?=$_REQUEST['sk']?>&user_level=<?=$_REQUEST['user_level']?>">CSV 파일로 다운로드</a></span> 
	</div>
</div>
<table class="admin-table-type1">
  <thead>
	<tr>
	  <th width="5%">No.</th>
	  <th width="10%">등급</th>
	  <th width="12%">ID</th>
	  <th width="10%">성명</th>
	  <th width="10%">휴대전화</th>
	  <th width="12%">이메일</th>
	  <th width="8%">로그인횟수</th>
	  <th width="10%">등록일</th>
	  <th width="10%">관리</th>
	</tr>
  </thead>
  <tbody>
	<?if($arrList['list']['total'] > 0):?>

	<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
	<tr>
	  <td><?=number_format($arrList['total']-$i-$_REQUEST['offset'])?></td>
	  <td><?=$arrayLevel[$arrList['list'][$i]['user_level']]?></td>
	  <td><a href="member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><?=$arrList['list'][$i]['user_id']?></a></td>
	  <td><a href="member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><?=$arrList['list'][$i]['user_name']?></a></td>
	  <td><?=$arrList['list'][$i]['mobile']?></td>	  
	  <td><?=$arrList['list'][$i]['email']?></td>
	  <td><?=number_format($arrList['list'][$i]['login_count'])?></td>
	  <td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
	  <td class="b02">		
		<a href="member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>&listURL=<?=urlencode($_SERVER['REQUEST_URI'])?>"><img src="/backoffice/images/k_modify.gif" alt="수정" /></a> 
		<a href="javascript:delMember('<?=$arrList['list'][$i]['user_id']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
	</tr>
	<?}?>

	<?else:?>
	<tr height="100">
	  <td width="100%" colspan="10" >등록된 회원이 없습니다.</td>
	</tr>
	<?endif;?>
  </tbody>
</table>

<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$_REQUEST['offset'],"&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&user_level=".$_REQUEST['user_level'])?>
</div>

<form name="frmContentsHidden" method="post" action="member_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="user_id">
<input type="hidden" name="returnURL" value="<?=$_SERVER['REQUEST_URI']?>">
</form>
</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>