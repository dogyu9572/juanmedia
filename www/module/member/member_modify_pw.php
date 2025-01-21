<?
//로그인확인
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
?>
<script language="javascript">
function checkForm(frm){
	if (frm.user_pw.value==""){
		alert("비밀번호를 입력해 주세요.");
		frm.user_pw.focus();
		return false;
	}
}
</script>
	
		<div class="sub-maptree">
			<div class="container">
				<ul>
					<li><img src="/images/common/icon-home.png" alt="home"></li>
					<li>마이페이지</li>
					<li class="last">내 정보수정</li>
				</ul>
			</div>
		</div>
		<!-- //sub-maptree -->

		<!-- sub-contents -->
		<div class="sub-contents" id="sub2">
			<div class="container">
				<!-- lnb -->
				<?include $_SERVER['DOCUMENT_ROOT']."/include/lnb-mypage.php" ?>
				<!-- //lnb -->

				<!-- contents-box -->
				<div class="contents-box">
					<h3><img src="/images/common/sub-tit21.png" alt="내 정보수정"></h3>
					<div class="subTxt">
					※ 본인 확인을 위하여 회원님의 비밀번호를 다시 확인합니다.
					</div>
					
					<form name="memberForm" method="post" action="/member.php?goPage=Edit" onsubmit="return checkForm(this)">
					<div class="joinType01 mt20">
						<table>
							<colgroup>
								<col width="176px">
								<col width="auto">
							</colgroup>
							<tbody>
								<tr>
									<th>
										비밀번호
									</th>
									<td>
										<input type="password" name="user_pw" id="user_pw" class="type05">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="BtnWrap taC">
						<button type="button" class="blackType03" onclick="javascript:history.go(-1)">
							취소
						</button>
						<button type="submit" class="blueType03" >
							확인
						</button>
					</div>
					</form>
				</div>
				<!-- //contents-box -->
			</div>
		</div>
