<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getCommentList("", "", $scale, $_REQUEST[offset]);
//_DEBUG($arrList);

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
<script language="javascript" src="/common/util.js"></script>
<script language="javascript">
function delComment(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 코멘트를 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmContentsHidden.idx.value = idx;
		document.frmContentsHidden.submit();
	}
}
</script>

<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">댓글 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 게시판 관리 &nbsp;&gt;&nbsp; 댓글</div>
	</div>

<h3 class="admin-title-middle">댓글검색</h3>
<table class="admin-table-type1">
  <colgroup>
  <col width="140" />
  <col width="*" />
  </colgroup>
  <tbody>
	<form name="frmSort" method="get" action="comment_list.php">
	<tr>
		<th>등록일</th>
		<td class="space-left">
		  <input type="text" name="s_date" style="width:80px;" value="<?=$_REQUEST[s_date]?>" class="input datePicker" /> ~ <input type="text" name="e_date" style="width:80px;" value="<?=$_REQUEST[e_date]?>" class="input datePicker" />
		 <input type="image" src="/backoffice/images/btn_search.gif" alt="검색" />
		</td>
	</tr>
	</form>
  </tbody>
</table>
<br />
<div class="clfix mgb5">
    <div class="fl" style="padding-top:4px;">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
    <div class="fr"><span class="btn_pack medium icon"><span class="download"></span><a href="/backoffice/module/board/comment_to_csv.php?s_date=<?=$_REQUEST[s_date]?>&e_date=<?=$_REQUEST[e_date]?>">CSV 파일로 다운로드</a></span> 
	</div>
</div>
<table class="admin-table-type1">
<colgroup>
<col width="70" />
<col width="100" />
<col width="100" />
<col width="*" />
<col width="120" />
<col width="100" />
<col width="80" />
</colgroup>
<thead>
<tr>
  <th>No.</th>
  <th>ID</th>
  <th>작성자</th>
  <th>내용</th>
  <th>IP주소</th>
  <th>등록일</th>
  <th>삭제</th>
</tr>
</thead>
<tbody>
<?if($arrList['list']['total'] > 0):?>

<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
<tr>
  <td><?=number_format($arrList['total']-$i-$_GET[offset])?></td>
  <td><?=$arrList['list'][$i]['user_id']?></td>
  <td><?=$arrList['list'][$i]['user_name']?></td>
  <td class="space-left"><a href="board_view.php?boardid=<?=$arrList['list'][$i]['boardid']?>&mode=view&idx=<?=$arrList['list'][$i]['board_idx']?>&c_idx=<?=$arrList['list'][$i]['idx']?>"> <?=stripslashes($arrList['list'][$i]['comment'])?></a></td>
  <td><?=$arrList['list'][$i]['ip']?></td>
  <td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
  <td><a href="javascript:delComment('<?=$arrList['list'][$i]['idx']?>');"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
</tr>
<?}?>

<?else:?>
<tr height="100">
  <td width="100%" colspan="8" >등록된 댓글이 없습니다.</td>
</tr>
<?endif;?>
</tbody>
</table>

<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$_GET[offset],"s_date=".$_GET[s_date]."&e_date=".$_GET[e_date])?>
</div>

<form name="frmContentsHidden" method="post" action="/module/board/board_evn.php">
<input type="hidden" name="evnMode" value="comment_delete">
<input type="hidden" name="idx">
<input type="hidden" name="returnURL" value="<?=$_SERVER[REQUEST_URI]?>">
</form>
	</div>
</div>
<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/footer.php";
?>