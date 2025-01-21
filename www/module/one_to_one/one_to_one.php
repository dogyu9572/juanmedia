<?
//로그인확인
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getOneToOneList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $scale, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[offset]));

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function checkForm(frm){
	if (frm.subject.value.length < 2){
		alert("제목을 입력해 주세요.");
		frm.subject.focus();
		return ;
	}
	frm.submit();
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

					<div class="searchArea">
						<p class="total">1:1문의 총 <span><?=number_format($arrList["total"])?></span> 건</p>
					</div>
					<div class="blist">
						<table>
							<colgroup>
								<col width="80px" />
								<col width="80px" />
								<col width="*" />
								<col width="150px" />
							</colgroup>
							<thead>
								<tr>
									<th scope="col">번호</th>
									<th scope="col"></th>
									<th scope="col">문의/답변</th>
									<th scope="col">등록일</th>
								</tr>
							</thead>
							<tbody>
								<?
								if($arrList["total"]>0){
								for($i=0;$i<$arrList["list"]["total"];$i++){
								?>
								<tr>
									<td><?=$arrList["total"]-$i-$_GET[offset]?></td>
									<td><?=$arrList["list"][$i][status]=="Y"?"<div class='completeAnswer'>답변완료":"<div class='waitingAnswer'>답변대기"?></div></td>
									<td class="tl"><a href="/shop.php?goPage=MyQnaView&idx=<?=$arrList["list"][$i][idx]?>"><?=stripslashes($arrList["list"][$i][subject])?></a></td>
									<td><?=substr($arrList["list"][$i][wdate],0,10)?></td>
								</tr>
								<?	
									}
								?>
								<?
								}else{
								?>
								<tr height="100">
									<td colspan="4" align="center">문의내역이 없습니다.</td>
								</tr>
								<?}?>
							</tbody>
						</table>
					</div>
					<!-- //blist --> 

					<div class="btnAll">
						<div class="paging">
							<?=pageNavigation($arrList["total"],$scale,$pagescale,$_GET[offset],"sw=".$_REQUEST[sw]."&sk=".$_REQUEST[sk]."&s_date=".$_REQUEST[s_date]."&e_date=".$_REQUEST[e_date]."&order_state=".$_REQUEST[order_state])?>
						</div>
						<!--//paging --> 
						<div class="btnR">
							<a href="#write" class="btn_gray5 fancybox">글쓰기</a>
						</div>
						<!-- //btnR -->
					</div>
					<!-- //btnAll -->

					<!-- 글쓰기 레이아팝업-->
					<div id="write" class="popupWrite">
						<p class="tit">1:1문의 작성</p>
						<form name="oneToOneForm" method="post" action="/module/one_to_one/one_to_one_evn.php">
						<input type="hidden" name="evnMode" value="write">
						<table>
							<colgroup>
								<col width="120px" />
								<col width="*" />
							</colgroup>
							<tbody>
								<tr>
									<th scope="row">등록자</th>
									<td><?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>(<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>)</td>
								</tr>
								<tr>
									<th scope="row">제<span class="nbsp10"></span>목</th>
									<td><input type="text" id="subject" name="subject" value=""  /></td>
								</tr>
								<tr>
									<th scope="row">내<span class="nbsp10"></span>용</th>
									<td><textarea name="contents"></textarea></td>
								</tr>
							</tbody>
						</table>
						<div class="btnC">
							<a href="javascript:checkForm(document.oneToOneForm);" class="btn_gray4">등록하기</a>
						</div>
						</form>
						<!-- //btnR -->
					</div>
					<!-- //popupWrite -->
					<!-- //글쓰기 레이아팝업-->

					
				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>
