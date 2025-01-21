<?
//로그인확인
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//내 게시물 인지 확인
$arrList = getOneToOneInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[idx]));

//DB해제
SetDisConn($dblink);

if($arrList["total"] < 1){
		jsMsg("존재하지 않는 글 입니다.");
		jsHistory("-1") ;
}

if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] != $arrList["list"][0]["user_id"]){
		jsMsg("고객님의 문의 글이 아닙니다.");
		jsHistory("-1") ;
}
?>

<script language="javascript">
function deleteOneToOne(idx){
	var cfm = false;
	cfm = confirm("이 상담내역을 삭제 하시겠습니까?");
	if(cfm==true){
		$.post("/module/one_to_one/one_to_one_evn.php", {idx: idx, evnMode: 'deleteAjax'},
		function(data){
			if(data=="true"){
				alert("삭제 되었습니다.");
				document.location.href="/shop.php?goPage=MyQna";
			}else{
				alert("삭제에 실패 하였습니다.");
			}
		});
	}
}
</script>


<div id="sub_container">
		<div class="content">

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/mypage/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="con">
				<!-- 내용 : s -->
					<div class="location">
						<p class="local"><span class="home"></span><span class="route">마이페이지</span><span class="route">문의사항</span><span class="current">1:1문의</span></p>
					</div>
					<!-- //location -->
					<h2>1:1문의</h2>
					<!-- <div class="searchArea">
						<p class="total">상품평 총 <span>173</span> 건</p>
					</div> -->
					<div class="bread">
						<table>
							<colgroup>
								<col width="65px" />
								<col width="*" />
							</colgroup>
							<thead>
								<tr>
									<th scope="col" colspan="2"><!-- <span class="completeAnswer">답변완료</span> --><?=stripslashes($arrList["list"][0][subject])?></th>
								</tr>
								<tr>
									<td class="day" colspan="2"><?=$arrList["list"][0][wdate]?></td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td  colspan="2">
										<div class="rcon">
										<?=nl2br(stripslashes($arrList["list"][0][contents]))?>
										</div>
									</td>
								</tr>
								<?if($arrList["list"][0][status]=="Y"){?>
								<tr>
									<td class="answeZone"><span class="completeAnswer">답변</span></td>
									<td class="answeZone">
										<?=nl2br(stripslashes($arrList["list"][0][re_contents]))?>
									</td>
								</tr>
								<?}?>
							</tbody>
						</table>
					</div>
					<!-- //bread --> 
					<!-- //breadBeAf --> 
					<br>
					<div class="btnZone btnR">
						<a class="btn_gray5" href="javascript:deleteOneToOne('<?=$arrList["list"][0][idx]?>');"><span>삭제</span></a>
						<a class="btn_gray5" href="/shop.php?goPage=MyQna"><span>목록</span></a>
					</div>

				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
