<?
//로그인확인
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getPointList(
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], 
	mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['type']), 
	$scale, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['offset'])
	);

$nowPoint = getNowPoint($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

//DB해제
SetDisConn($dblink);
?>

	<div id="sub_container">
		<div class="content">

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/mypage/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="con">
				<!-- 내용 : s -->
				<div class="location">
					<p class="local"><span class="home"></span><span class="route">마이페이지</span><span class="route">쿠폰/상품권/적립금현황</span><span class="current">적립금현황</span></p>
				</div>
				<!-- //location -->
				<h2>적립금현황</h2>
				<div class="ticketCon">
					<div class="searchArea">
						<h3>적립금현황 (<?=number_format($nowPoint[nowpoint])?>원)</h3>
					</div>
					<!--//searchArea -->
					<div class="blist">
						<table>
							<colgroup>
								<col width="15%" />
								<col width="15%" />
								<col width="15%" />
								<col width="*"  />
								<col width="15%" />
							</colgroup>
							<thead>
								<tr>
									<th scope="col">사용액</th>
									<th scope="col">적립액</th>
									<th scope="col">잔여 적립금</th>
									<th scope="col">적립/사용 내용</th>
									<th scope="col">날짜</th>
								</tr>
							</thead>
							<tbody>
								<?if($arrList['list']['total'] > 0):?>
								<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
								<tr>
									<td><?=number_format($arrList['list'][$i]['minus'])?></td>
									<td><?=number_format($arrList['list'][$i]['plus'])?></td>
									<td><?=number_format($arrList['list'][$i]['nowpoint'])?></td>
									<td><?=stripslashes($arrList['list'][$i]['contents'])?></td>
									<td><?=$arrList['list'][$i]['wdate']?></td>
								</tr>
								<?}?>
								<?else:?>
								<tr height="100" align="center">
								  <td width="100%" colspan="8" >적립금 기록이 없습니다.</td>
								</tr>
								<?endif;?>
							</tbody>
						</table>
					</div>
					<!-- //blist -->

					<div class="paging">
						<?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"")?>
					</div>
					<!--//paging --> 

				</div>
				<!-- //ticketCon -->
					
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
