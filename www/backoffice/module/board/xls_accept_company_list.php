<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

if (! in_array ( "board_manage", $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["AUTH"] ) && $_SESSION [$_SITE ["DOMAIN"]] ["ADMIN"] ["GRADE"] != "ROOT") :
	jsMsg ( "권한이 없습니다." );
	jsHistory ( "-1" );
endif;

	$subQuery = " AND length(A.order_no)>1 AND A.order_state in (6) ";
	$file_name = "_상호정보_";

$filename = $_SITE['NAME'].$file_name.date(m).date(d).date(h).date(i).".xls";
header( "Content-type: application/vnd.ms-excel; charset=utf-8"); 
header( "Content-Description: PHP4 Generated Data" ); 
header( "Content-Disposition: attachment; filename=".$filename );
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");

$scale = 10000;

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$arrAllCategory = getCategoryAll();	// 전체카테고리

$boardid = "accept";

$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], $boardid);

$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET['sw'], $_GET['sk'], 0, 0, $subQuery, 'admin');
?>
<style type="text/css">
body{overflow-x:scroll;width: max-content;}
.mainTable{width:auto;}
th{font-size:12px; background-color:#7cacdc;}	
td{font-size:12px; /*width:300px;*/ padding: 0 10px; text-align:center;}
a{font-size:12px;}	
</style>
<table border=1 class="mainTable" style="overflow-x:scroll;">
	<tr>
		<th style="background-color:#7cacdc;">No.</th>
		<th style="background-color:#7cacdc;">이름</th>
		<th style="background-color:#7cacdc;">아아디</th>
		<th style="background-color:#7cacdc;">업종</th>
		<th style="background-color:#7cacdc;">부문1</th>
		<th style="background-color:#7cacdc;">부문2</th>
		<th style="background-color:#7cacdc;">최종부문1</th>
		<th style="background-color:#7cacdc;">최종부문2</th>

		<th style="background-color:#7cacdc;">상호명</th>
		<th style="background-color:#7cacdc;">전화번호</th>
		<th style="background-color:#7cacdc;">사업장주소</th>
		<th style="background-color:#7cacdc;">사용자페이지 노출여부</th>
		<th style="background-color:#7cacdc;">등록일</th>				
	</tr>
	<?php
	for($i=0; $i < $arrBoardList["list"]["total"]; $i++){			
		$categoryTitle = $arrBoardList["total"]-$i-(int)$_GET['offset'];
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
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['name']?></td>
		<td style="mso-number-format:'\@'"><?=$user_id?></td>
		<td style="mso-number-format:'\@'"><?=$arrAllCategory[$arrBoardList["list"][$i]['category']]?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_1']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_2']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_3']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_4']?></td>

		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['company_name']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['company_phone']?></td>
		<td style="mso-number-format:'\@'">[<?=$arrBoardList["list"][$i]['company_zip']?>] <?=$arrBoardList["list"][$i]['company_address']?> <?=$arrBoardList["list"][$i]['company_address_ext']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['company_yn']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['company_date']?></td>
	</tr>
	<?php
	}
	?>
</table>
<?php
SetDisConn($dblink);
?>