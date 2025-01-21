<?
include $_SERVER[DOCUMENT_ROOT] . "/backoffice/header.php";
include $_SERVER[DOCUMENT_ROOT] . "/module/coupon/coupon.lib.php";
if(!in_array("point_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$scale = "20";
//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getCouponListAdmin(0, 0, "N");

$arrList2 = getGoodCouponList($scale, mysql_escape_string($_REQUEST[offset]));

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function couponDel(id){
	var cfm;
	cfm =false;
	cfm = confirm("등록된 쿠폰정보을 삭제 하시겠습니까?\n\n발급된 쿠폰도 자동 삭제됩니다.");
	if(cfm==true){
		document.frmContentsHidden.idx.value = id;
		document.frmContentsHidden.submit();
	}
}

// 상품별쿠폰 발급회원
function popMycoupon(idx){
	var url = "../shop/shop_mycoupon.php?idx=" + idx;
	var str = window.open(url,"MyCouponList","height=400, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	str.focus();
}
</script>


<div id="admin-container">
	<? include "menu.php"; ?>
    <div id="admin-content">
	<div class="admin-title-top">
		<h2 class="admin-title">쿠폰 관리</h2>
		<div class="admin-title-right">HOME &nbsp;&gt;&nbsp; 프로모션 &nbsp;&gt;&nbsp; 쿠폰 목록</div>
	</div>


<!-- <h3 class="admin-title-middle">이벤트 쿠폰</h3> -->
<div class="clfix mgb5">
	<div class="fl" style="padding-top:5px;">&nbsp;<strong>전체 : <?=number_format($arrList['total'])?> 개</strong></div>
	<div class="fr"><span class="btn_pack medium icon"><span class="download"></span><a href="coupon_info.php">쿠폰등록</a></span></div>
</div>

<table class="admin-table-type1">
  <thead>
  <tr>
	  <th width="5%">No.</th>
	  <th width="15%">쿠폰명</th>
	  <th width="30%">쿠폰내용</th>
	  <th width="5%">할인금액</th>
	  <th width="15%">유효기간</th>
	  <th width="5%">발급수량</th>
	  <th width="8%">발급일</th>
	  <th width="15%">관리</th>
	</tr>
  </thead>
  <tbody>
<?if($arrList['list']['total'] > 0):?>
<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
<tr>
  <td><?=$arrList['total']-$offset-$i?></td>
  <td><?=stripslashes($arrList['list'][$i]['coupon_name'])?></td>
  <td><?=stripslashes($arrList['list'][$i]['coupon_content'])?></td>
  <td><?=$arrList['list'][$i][coupon_unit]=="P"?number_format($arrList['list'][$i][coupon_dis])."%":number_format($arrList['list'][$i][coupon_dis])."원"?></td>
  <td><?=$arrList['list'][$i]['coupon_sdate']?> ~ <?=$arrList['list'][$i]['coupon_edate']?></td>
  <td><?=number_format($arrList['list'][$i]['coupon_qty'])?></td>
  <td><?=substr($arrList['list'][$i]['wdate'],0,10)?></td>
  <td align="center"><a href="coupon_info.php?idx=<?=$arrList['list'][$i]['idx']?>">수정/발행된 쿠폰</a> | <a href="javascript:couponDel('<?=$arrList['list'][$i]['idx']?>')">삭제</a></td>
</tr>
<?}?>

<?else:?>
<tr height="100">
  <td colspan="12" >등록된 쿠폰이 없습니다.</td>
</tr>
<?endif;?>
  </tbody>
</table>
<br />
<br />

<!-- <h3 class="admin-title-middle">상품별 쿠폰</h3>

<table class="admin-table-type1">
  <thead>
	 <tr>
	  <th width="5%">No.</th>
	  <th width="36%">상품명</th>
	  <th width="16%">기간</th>
	  <th width="10%">할인</th>
	  <th width="8%">수량</th>
	  <th width="15%">관리</th>
	</tr>
  </thead>
 <tbody>
<?if($arrList2['list']['total'] > 0):?>
<?for ($i=0;$i<$arrList2['list']['total'];$i++) {?>
<tr height="25" align="center">
  <td><?=$arrList2['total']-$offset-$i?></td>
  <td align="left"><?=stripslashes($arrList2['list'][$i]['g_name'])?></td>
  <td><?=$arrList2['list'][$i]['coupon_sdate']?> ~ <?=$arrList2['list'][$i]['coupon_edate']?></td>
  <td><?=$arrList2['list'][$i][coupon_unit]=="P"?number_format($arrList2['list'][$i][coupon_dis])."%":number_format($arrList2['list'][$i][coupon_dis])."원"?></td>
  <td><?=$arrList2['list'][$i][coupon_limit]=="N"?"수량제한없음":number_format($arrList2['list'][$i][coupon_qty])?></td>
  <td align="center"><a href="../shop/good_info.php?idx=<?=$arrList2['list'][$i]['idx']?>">수정</a> | <a href="javascript:popMycoupon('<?=$arrList2['list'][$i]['idx']?>')">발급회원보기</a></td>
</tr>
<?}?>

<?else:?>
<tr height="100">
  <td colspan="12" >등록된 쿠폰이 없습니다.</td>
</tr>
<?endif;?>
 </tbody>
</table>
 -->
<div class="paginate">
  <?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"type=".$_REQUEST[type]."&s_date=".$_REQUEST[s_date]."&e_date=".$_REQUEST[e_date])?>
</div>

<form name="frmContentsHidden" method="post" action="coupon_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
<input type="hidden" name="returnURL" value="<?=$_SERVER[REQUEST_URI]?>">
</form>


</div>
</div>
<?
include $_SERVER[DOCUMENT_ROOT] . "/backoffice/footer.php";
?>