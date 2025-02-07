<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/banner/banner.lib.php";
if(!in_array("banner_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
	jsMsg("권한이 없습니다.");
	jsHistory("-1");
endif;

$b_type = $_REQUEST['b_type'] ?? "";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$scale = 20;

if(!isset($_REQUEST['offset'])){
	$_REQUEST['offset']=0;
}
if(!isset($_REQUEST['b_type'])){
	$_REQUEST['b_type']="";
}

//제품 리스트
$arrList = getBannerList($scale, $_REQUEST['offset']);

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function delBanner(idx){
	var cfm;
	cfm =false;
	cfm = confirm("이 배너를 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmListHidden.idx.value = idx;
		document.frmListHidden.submit();
	}
}
</script>
    <style>
        .tab_div {
            display:flex;flex-direction: row;align-items: center;justify-content: flex-start; gap:8px; margin-bottom:15px;
        }
        .tab_div .tab_menu {
            cursor:pointer;display:flex;align-items: center;justify-content: center;width: 150px;border: 1px solid #628dc7;border-radius: 5px;text-align: center;height: 30px;
        }
        .tab_div .tab_menu:hover,
        .tab_div .tab_menu.on {
            background-color:#628dc7;
            color:#ffffff;
        }
        .tab_div .tab_menu.cal {
            background-color: #305587;
            color:#ffffff;
        }
    </style>
<?php
$arrCategory = array(
    "1" => "메인 상단 배너",
    "2" => "메인 하단 배너",
);
?>
<div class="container">

	<div class="title">배너 목록</div>

	<div class="inbox">
        <div class="tab_div">
            <div class='tab_menu <?=$_GET["b_type"] == ""?"on":""?>' onclick="location.href='<?=$_SERVER["PHP_SELF"]?>'">전체</div>
            <?php foreach($arrCategory as $key => $val){?>
                <div class='tab_menu <?=$_GET["b_type"] == $key?"on":""?>' onclick="location.href='<?=$_SERVER["PHP_SELF"]?>?b_type=<?=$key?>'"><?=$val?></div>
            <?php } ?>
        </div>
		<div class="bdr_top">
			<div class="left">
				<div class="total">Total : <strong><?=number_format($arrList['total'])?></strong></div>				
			</div>
			<form name="frm" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
			<div class="count">
				<select name="b_device" onchange="document.frm.submit()">
                    <option value="">　　디바이스 타입　　　　　　　　　　　　　　▼</option>
					<option value="1"<?=$b_type=="1"?" selected":""?>>1. 웹 슬라이드 배너 (1920px * 420px)</option>
					<option value="2"<?=$b_type=="2"?" selected":""?>>2. 모바일 슬라이드 배너 (750px * 1332px)</option>
                    <option value="3"<?=$b_type=="3"?" selected":""?>>2. 공통</option>
				</select>
			</div>	
			</form>
            <!--<select name="st" onchange="document.frm.submit()">
					<option value="1"<?php /*=$_REQUEST['st']=="1"?" selected":""*/?>>정렬역순</option>
					<option value="2"<?php /*=$_REQUEST['st']=="2"?" selected":""*/?>>등록순</option>
				</select>-->
		</div>
<!-- over_tbl : 테이블을 좌우로 스크롤 할 때 사용합니다. -->
<!-- mo_break_tbl : 767px 이하에서 테이블 구조를 깰 때 사용합니다. -->
		<div class="over_tbl mo_break_tbl">
			<div class="bdr_list tac">
				<table>
					<colgroup class="pc_vw">
						<col class="w4p">
						<col class="w8p">
                        <col class="w8p">
                        <col class="w10p">
						<col class="*">
                        <col class="w8p">
						<col class="w10p">
						<col class="w10p">
						<col class="w8p">
						<col class="w10p">
					</colgroup>
					<thead>
						<tr>							
							<th class="pc_vw">No.</th>
                            <th class="pc_vw">디바이스</th>
							<th class="pc_vw">구분</th>
                            <th class="pc_vw">이미지</th>
							<th class="pc_vw">제목</th>
							<th class="pc_vw">타겟</th>
							<th class="pc_vw">노출여부</th>
							<th class="pc_vw">정렬</th>
							<th class="pc_vw">등록일</th>
							<th class="pc_vw">관리</th>
						</tr>
					</thead>
					<tbody>
					<?
					if($arrList['list']['total'] > 0){
						for ($i=0;$i<$arrList['list']['total'];$i++){
                            if ($arrList['list'][$i]['b_type'] == 1) {
                                $b_type = "메인 상단 배너";
                            } else if ($arrList['list'][$i]['b_type'] == 2) {
                                $b_type = "메인 하단 배너";
                            }

                            if ($arrList['list'][$i]['b_device'] == 1) {
                                $b_device = "PC";
                            } else if ($arrList['list'][$i]['b_device'] == 2) {
                                $b_device = "모바일";
                            } else if ($arrList['list'][$i]['b_device'] == 3) {
                                $b_device = "공통";
                            }
					?>
						<tr>
							<td><i class="mo_vw">No.</i><?=$arrList['total']-$i-(int)$_REQUEST['offset']?></td>
                            <td><i class="mo_vw">파일타입</i><?=$b_device?></td>
                            <td><i class="mo_vw">파일타입</i><?=$b_type?></td>
							<td><i class="mo_vw">배너</i><img src="/uploaded/banner/<?=$arrList['list'][$i]['b_image']?>" style="max-width:100px;max-height:100px;"></td>
							<td><i class="mo_vw">제목</i><?=$arrList['list'][$i]['b_subject']?></td>
<!--							<td><i class="mo_vw">타입</i>--><?php //=$arrList['list'][$i]['b_type']?><!--</td>-->
							<td><i class="mo_vw">타겟</i><?=$arrList['list'][$i]['b_target']?></td>
							<td><i class="mo_vw">보임</i><?=$arrList['list'][$i]['b_show']?></td>
							<td><i class="mo_vw">정렬</i><?=$arrList['list'][$i]['b_sort']?></td>
							<td><i class="mo_vw">등록일</i><?=$arrList['list'][$i]['b_date']?></td>							
							<td class="mono_btm"><i class="mo_vw">관리</i>
								<div class="btns">
									<a href="banner_info.php?idx=<?=$arrList['list'][$i]['idx']?>" class="btn modi">수정</a>
									<button type="button" class="btn del" onclick="delBanner('<?=$arrList['list'][$i]['idx']?>');">삭제</button>
								</div>
							</td>
						</tr>
					<?
						}
					}else{
					?>
					<tr height="100">
						<td colspan="9">등록된 배너가 없습니다.</td>
					</tr>
					<?}?>
					</tbody>
				</table>
			</div>
		</div>

		<div class="bdr_btm">
			<div class="paging">			
			<?=pageNavigationBackoffice($arrList['total'],$scale,$pagescale,$_REQUEST['offset'],"")?>
			</div>
			<div class="btns">
				<a href="./banner_add.php" class="btn">신규등록</a>
			</div>			
		</div>
	</div>

</div>

<form name="frmListHidden" method="post" action="banner_evn.php">
<input type="hidden" name="evnMode" value="delete">
<input type="hidden" name="idx">
</form>

<?php include("pub/inc/footer.php") ?>