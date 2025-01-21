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

$subQuery = " AND length(A.order_no)>1 ";
$file_name = "_등록 내역_";

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
		<th style="background-color:#7cacdc;">등록번호</th>
		<th style="background-color:#7cacdc;">사업자등록번호</th>
		<th style="background-color:#7cacdc;">휴대폰번호</th>
		<th style="background-color:#7cacdc;">발행용도</th>
		<th style="background-color:#7cacdc;">이름</th>
		<th style="background-color:#7cacdc;">아아디</th>
		<th style="background-color:#7cacdc;">업종</th>
		<th style="background-color:#7cacdc;">부문1</th>
		<th style="background-color:#7cacdc;">부문2</th>
		<th style="background-color:#7cacdc;">최종부문1</th>
		<th style="background-color:#7cacdc;">최종부문2</th>
		<th style="background-color:#7cacdc;">등록수단</th>
		<th style="background-color:#7cacdc;">등록금액</th>
		<th style="background-color:#7cacdc;">등록상태</th>
		<th style="background-color:#7cacdc;">등록일</th>
		<th style="background-color:#7cacdc;">거래증빙 구분</th>
		<th style="background-color:#7cacdc;">거래증빙 발행여부</th>
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
		if($arrBoardList["list"][$i]['proof']=="tax"){	## 거래증빙
			$arrBoardList["list"][$i]['proofTxt'] = "세금계산서";
		}else if($arrBoardList["list"][$i]['proof']=="csh"){
			$arrBoardList["list"][$i]['proofTxt'] = "현금영수증";
		}else{
			$arrBoardList["list"][$i]['proofTxt'] = "";
			if($arrBoardList["list"][$i]['proof_yn'] != "Y"){
				$arrBoardList["list"][$i]['proof_yn'] = "N";
			}
		}
		if($arrBoardList["list"][$i]['csh_type']=="A"){	## 발행용도
			$arrBoardList["list"][$i]['csh_typeTxt'] = "소득공제용";
		}else if($arrBoardList["list"][$i]['csh_type']=="B"){
			$arrBoardList["list"][$i]['csh_typeTxt'] = "지출증빙용";
		}else if($arrBoardList["list"][$i]['csh_type']=="C"){
			$arrBoardList["list"][$i]['csh_typeTxt'] = "미발행";
		}else{
			$arrBoardList["list"][$i]['csh_typeTxt'] = "";
		}
		if($arrBoardList["list"][$i]['proof_yn'] != "N"){
			$arrBoardList["list"][$i]['proof_yn'] = "Y";
		}

		############################################################ 사업자등록번호 ############################################################ ST
		$arrParentArticle = getBoardArticleView("evaluation", "", $arrBoardList["list"][$i]["homepage"],"input");		## 접수한 산업평가

		$arr_item_name	= explode("|+|",$arrParentArticle['list'][0]['item_name']);
		$arr_item_val	= explode("|+|",$arrBoardList["list"][$i]['item_val']);

		for($e=0; $e < count($arr_item_name); $e++){
			if($arr_item_name[$e]=="사업자등록번호"){
				$arrBoardList["list"][$i]['item_valTxt'] = $arr_item_val[$e];
			}
		}
		############################################################ 사업자등록번호 ############################################################ ED
	?>
	<tr>
		<td style="mso-number-format:'\@'"><?=$categoryTitle?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['r_user']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['item_valTxt']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['tel']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['csh_typeTxt']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['name']?></td>
		<td style="mso-number-format:'\@'"><?=$user_id?></td>
		<td style="mso-number-format:'\@'"><?=$arrAllCategory[$arrBoardList["list"][$i]['category']]?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_1']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_2']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_3']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['etc_4']?></td>
		<td style="mso-number-format:'\@'"><?=$_SITE["SHOP"]["PAY_TYPE"][$arrBoardList["list"][$i]['pay_type']]?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['pay_amount']?></td>
		<td style="mso-number-format:'\@'"><?=$_SITE["SHOP"]["ORDER_STATE"][$arrBoardList["list"][$i]['order_state']]?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['order_date']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['proofTxt']?></td>
		<td style="mso-number-format:'\@'"><?=$arrBoardList["list"][$i]['proof_yn']?></td>
	</tr>
	<?php
	}
	?>
</table>
<?php
SetDisConn($dblink);
?>