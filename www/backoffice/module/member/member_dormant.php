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

$_GET[level] = "80";

$arrList = getMemberList(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sw']), mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['sk']), $scale, $_REQUEST['offset']);
//_DEBUG($arrList);


for($i=0;$i<$arrLevel["total"];$i++){
	$arrayLevel[$arrLevel["list"][$i]['level_no']] = $arrLevel["list"][$i]['level_name'];
}
//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">휴면회원 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 회원 관리 &nbsp;&gt;&nbsp; 휴면회원 리스트</div>
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

<h3 class="admin-title-middle">휴면회원검색</h3>
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
    <div class="fr"></div>
</div>
<table class="admin-table-type1">
  <thead>
	<tr>
	  <th width="5%">No.</th>
	  <th width="15%">ID</th>
	  <th width="10%">성명</th>
	  <th width="10%">휴대전화</th>
	  <th width="20%">마지막로그인</th>
	  <th width="10%">등록일</th>
	  <th width="5%">관리</th>
	</tr>
  </thead>
  <tbody>
	<?if($arrList['list']['total'] > 0):?>

	<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
	<tr>
	  <td><?=number_format($arrList['total']-$i-$offset)?></td>
	  <td><?=$arrList['list'][$i]['user_id']?></td>
	  <td><?=$arrList['list'][$i]['user_name']?></td>
	  <td><?=$arrList['list'][$i]['mobile']?></td>
	  <td><?=$arrList['list'][$i]['login_last']?></td>
	  <td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
	  <td class="b02"><a href="javascript:delMember('<?=$arrList['list'][$i]['user_id']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
	</tr>
	<?}?>

	<?else:?>
	<tr height="100">
	  <td width="100%" colspan="10" >휴면회원이 없습니다.</td>
	</tr>
	<?endif;?>
  </tbody>
</table>

<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk'])?>
</div>

<form name="frmContentsHidden" method="post" action="member_evn.php">
<input type="hidden" name="evnMode" value="out">
<input type="hidden" name="user_id">
<input type="hidden" name="returnURL" value="<?=$_SERVER['REQUEST_URI']?>">
</form>
</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>