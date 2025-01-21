<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/research/research.lib.php";
if(!in_array("research_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$scale = 20;

$arrInfo = getArticleInfo($GLOBALS["_conf_tbl"]["research_info"], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[idx]));

//설문참여 리스트
$arrList = getResearchJoinList($arrInfo["list"][0][idx], $scale, $_REQUEST[offset]);

//DB해제
SetDisConn($dblink);
?>
<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
		<h2 class="admin-title">설문 참여자 목록</h2>

<div class="mgb5">
	<strong><font color="#b42c2c"><?=$arrInfo["list"][0][subject]?></font> 설문에 <?=number_format($arrList['total'])?> 명이 참여하셨습니다.</strong>
</div>

<table class="admin-table-type1">
  <thead>
	<tr>
	  <th width="10%">No.</th>
	  <th width="10%">결과보기</th>
	  <th width="20%">회원ID</th>
	  <th width="30%">IP Address</th>
	  <th width="30%">참여일자</th>
	</tr>
  </thead>
  <tbody>
	<?if($arrList['list']['total'] > 0):?>

	<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
	<tr>
		<td><?=$arrList['total']-$_REQUEST[offset]-$i?></td>
		<td><span class="btn_pack small"><a href="research_view.php?r_idx=<?=$arrInfo["list"][0][idx]?>&user_id=<?=$arrList['list'][$i]['user_id']?>">보기</a></span></td>
		<td><a href="/backoffice/module/member/member_info.php?user_id=<?=$arrList['list'][$i]['user_id']?>"><?=$arrList['list'][$i]['user_id']?></a></td>
		<td><?=$arrList['list'][$i]['ip']?></td>
		<td><?=$arrList['list'][$i]['wdate']?></td>
	</tr>
	<?}?>

	<?else:?>
	<tr height="100">
	  <td width="100%" colspan="12" >설문에 참여한분이 없습니다.</td>
	</tr>
	<?endif;?>
  </tbody>
</table>

<div class="paginate">
	<?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"")?>
</div>

	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>