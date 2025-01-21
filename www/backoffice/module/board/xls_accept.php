<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/_api/_data_gokr/tourapi/sigugun.php";	


if (! in_array ( "board_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );
endif;

$filename = $_SITE['NAME']."_접수_및_심사내역_".date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel; charset=utf-8"); 
header( "Content-Description: PHP4 Generated Data" ); 
header( "Content-Disposition: attachment; filename=".$filename );
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$boardid = "accept";

$arrAllCategory = getCategoryAll();	// 전체카테고리

$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], $boardid);

$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET['sw'], $_GET['sk'], 0, 0, $subQuery, 'admin');

## 구/군
#################################### 시구군 ##############################################
$Query = "SELECT sido, sido_abbreviation FROM tbl_sido_official GROUP BY sido ORDER BY s_order ASC, idx ASC ";
$arrSido = getFreeQueryR($Query);

$arrGugunCategory = array();

for($i=0; $i < $arrSido['total']; $i++){
	$arrSidoData[$arrSido['list'][$i]['sido_abbreviation']] = $arrSido['list'][$i]['sido'];
}

for($i=0;$i<$arrGugun['body']['totalCount'];$i++){
	if($arrGugun['body']['totalCount'] < 2){
		$arrGugunCategory[$arrGugun['body']['items']['item']['code']] = $arrGugun['body']['items']['item']['name'];
	}else{
		$arrGugunCategory[$arrGugun['body']['items']['item'][$i]['code']] = $arrGugun['body']['items']['item'][$i]['name'];
	}
}

?>
<table border=1>
	<tr>
		<th style="background-color:#7cacdc;">No.</th>
		<th style="background-color:#7cacdc;">접수번호</th>
		<th style="background-color:#7cacdc;">이름</th>
		<th style="background-color:#7cacdc;">아아디</th>
		<th style="background-color:#7cacdc;">업종</th>
		<th style="background-color:#7cacdc;">부문1</th>
		<th style="background-color:#7cacdc;">부문2</th>
		<th style="background-color:#7cacdc;">최종부문1</th>
		<th style="background-color:#7cacdc;">최종부문2</th>
		<th style="background-color:#7cacdc;">접수지역</th>
		<th style="background-color:#7cacdc;">접수상태</th>
		<th style="background-color:#7cacdc;">접수일</th>
		<th style="background-color:#7cacdc;">심사상태</th>
		<th style="background-color:#7cacdc;">n년수상업체</th>
		<th style="background-color:#7cacdc;">n년수상업체_연도별</th>
		<th style="background-color:#7cacdc;">등록상태</th>
	</tr>
	<?php
	for($i=0; $i < $arrBoardList["list"]["total"]; $i++){			
		$categoryTitle = $arrBoardList["total"] - $i;
		$user_id = "비회원";
		if($arrBoardList["list"][$i]['fax'] == "Y" && $arrBoardList["list"][$i]['w_user'] != ""){
			############################################################ 회원ID추출 ############################################################ ST
			$Query = "SELECT user_id FROM tbl_member WHERE nick_name = '".$arrBoardList["list"][$i]['w_user']."'";
			$arrUserInfo = getFreeQueryR($Query);

			$user_id = $arrUserInfo['list'][0]['user_id'];
			############################################################ 회원ID추출 ############################################################ ED
		}
	?>
	<tr>
		<td style="mso-number-format:'\@'"><?=$categoryTitle?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['r_user']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['name']?></td>
		<td style="mso-number-format:'\@'"><?=$user_id?></td>
		<td style="mso-number-format:'\@'"><?=$arrAllCategory[$arrBoardList["list"][$i]['category']]?></td>	
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_1']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_2']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_3']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_4']?></td>
		<td style="mso-number-format:'\@'"><?=$arrSidoData[$arrBoardList["list"][$i]['sido']]?> <?=$arrGugunCategory[$arrBoardList["list"][$i]['gogon']]?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['accept_cate']?>차 <?=$arrBoardList["list"][$i]['accept_flag']=="Y"?"접수완료":"접수취소"?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['wdate']?></td>
		<td style="mso-number-format:'\@'"><?=$_SITE["SHOP"]["STATE"][$arrBoardList["list"][$i]['accept_state']]?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['subject']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['contents']?></td>
		<td style="mso-number-format:'\@'"><?=$_SITE["SHOP"]["ORDER_STATE"][$arrBoardList["list"][$i]['order_state']]?></td>		
		<?php if($i == 0){?>
		<td rowspan="<?=$arrBoardList["total"]?>" style="mso-number-format:'\@'">
			<?php foreach($_SITE["SHOP"]["STATE"] as $key => $val){?>
			<?=$val?> => <?=$key?><br/>
			<?php } ?>
			심사상태를 변경하고 싶을 경우 숫자로 입력 해주세요.
		</td>
		<?php } ?>
	</tr>
	<?php
	}
	?>
</table>
<?php
SetDisConn($dblink);
?>