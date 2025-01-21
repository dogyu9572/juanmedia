<?//로그인확인
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
?>
<script language="javascript">
function checkForm(frm){
	if (frm.now_pw.value==""){
		alert("현재 비밀번호를 입력해 주세요.");
		frm.now_pw.focus();
		return false;
	}
	if (frm.user_pw.value==""){
		alert("비밀번호를 입력해 주세요.");
		frm.user_pw.focus();
		return false;
	}
	if (frm.user_pw2.value==""){
		alert("비밀번호 확인을 입력해 주세요.");
		frm.user_pw2.focus();
		return false;
	}
	if (frm.user_pw.value != frm.user_pw2.value){
		alert("비밀번호가 일치하지 않습니다.");
		frm.user_pw2.focus();
		return false;
	}
}
</script>

		<div class="sub-maptree">
			<div class="container">
				<ul>
					<li><img src="/images/common/icon-home.png" alt="home"></li>
					<li>마이페이지</li>
					<li class="last">비밀번호 변경</li>
				</ul>
			</div>
		</div>
		<!-- //sub-maptree -->

		<!-- sub-contents -->
		<div class="sub-contents" id="sub3">
			<div class="container">
				<!-- lnb -->
				<?include $_SERVER['DOCUMENT_ROOT']."/include/lnb-mypage.php" ?>
				<!-- //lnb -->

				<!-- contents-box -->
				<div class="contents-box">
					<h3><img src="/images/common/sub-tit22.png" alt="비밀번호 변경"></h3>
					<form name="memberForm" method="post" action="/module/member/member_evn.php" onsubmit="return checkForm(this)">
					<input type="hidden" name="evnMode" value="editPw">
					<div class="joinType01">
						<table>
							<colgroup>
								<col width="176px">
								<col width="auto">
							</colgroup>
							<tbody>
								<tr>
									<th>
										기존 비밀번호
									</th>
									<td>
										<input type="password" name="now_pw" id="now_pw" class="type05">
									</td>
								</tr>
								<tr>
									<th>
										새 비밀번호
									</th>
									<td>
										<input type="password" name="user_pw" id="user_pw" class="type05">
									</td>
								</tr>
								<tr>
									<th>
										새 비밀번호 확인
									</th>
									<td>
										<input type="password" name="user_pw2" id="user_pw2" class="type05">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="BtnWrap taC">
						<button type="button" class="blackType03" onclick="history.go(-1)">
							취소
						</button>
						<button type="submit" class="blueType03">
							확인
						</button>
					</div>
					</form>
				</div>
				<!-- //contents-box -->
			</div>
		</div>
