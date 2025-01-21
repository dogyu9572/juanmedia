<script language="JavaScript">
$(document).ready(function(){
	$("#id").keyup(function(event) {
		if (event.which == 13) {
			checkLogin(document.loginForm);
		}
	});
	$("#pw").keyup(function(event) {
		if (event.which == 13) {
			checkLogin(document.loginForm);
		}
	});
});

function checkLogin(f) { //입력값 검사
	if (!f.id.value) {
		alert("아이디를 입력하세요."); f.id.focus(); return ;
	}
	if (!f.pw.value) {
		alert("비밀번호를 입력하세요."); f.pw.focus(); return ;
	}
	f.submit();
}
function checkLoginGuest(f) { //입력값 검사
	if (!f.name.value) {
		alert("이름을 입력하세요."); f.name.focus(); return ;
	}
	f.submit();
}

function checkLoginGuest2(f) { //입력값 검사
	if (!f.order_name.value) {
		alert("주문자명을 입력하세요."); f.order_name.focus(); return ;
	}
	if (!f.order_pw.value) {
		alert("주문시 작성한 비밀번호를 입력하세요."); f.order_pw.focus(); return ;
	}
	f.submit();
}
</script>


	<div id="sub_container">
		<div class="content">
			<div class="location">
				<p class="local"><span class="home"></span><span class="current">Member Login</span></p>
			</div>
			<!-- //location -->
			
			<div class="con">
			<!-- 내용 : s -->
				
				<h2>Member Login</h2>
				<div class="member">
					<div class="login">
						<div class="areaL">
							<p class="t01">frienpi 사이트의 회원이 되시면 제품의 정품등록을 통한 <br /><span>A/S서비스 및 다양한 이벤트의 혜택</span>을 제공해 드립니다.</p>
							<form action='/module/member/member_evn.php' method='post' name='loginForm'>
							<input type="hidden" name="evnMode" value="login">
							<input type="hidden" name="rt_url" value="<?=$_REQUEST[rt_url]?>">
							<ul>
								<li><span>아이디</span><input type="text" id="id" name="id" value="<?=$_COOKIE[login_id]?>" placeholder="아이디@이메일주소" /></li>
								<li><span>비밀번호</span><input type="password" id="pw" name="pw" value=""  /></li>
							</ul>
							<a href="javascript:checkLogin(document.loginForm);" class="btnLogin">로그인</a>
							<p class="idSave"><input type="checkbox" name="save_id" value="1"<?=$_COOKIE[login_id]?" checked":""?> /> 아이디 저장</p>
							</form>

						</div>
						<!-- //areaL -->
						<div class="areaR">
							<div class="zone zoneTop">
								<div class="in">
									<p class="t01">아이디 또는 비밀번호를 잊으셨나요?</p>
									<div class="btn">
										<a href="/member.php?goPage=Find" class="btnFindID">아이디 찾기</a>
										<a href="/member.php?goPage=Find" class="btnFindPW">비밀번호 찾기</a>
									</div>
								</div>
								<!-- //in -->
							</div>
							<!-- //zone -->
							<div class="zone zoneBot">
								<div class="in">
									<p class="t01">아직 프렌피 회원이 아니신가요?</p>
									<div class="btn">
										<a href="/member.php?goPage=Agree" class="btnJoin">회원가입</a>
									</div>
								</div>
								<!-- //in -->
							</div>
							<!-- //zone -->
						</div>
						<!-- //areaR -->
					</div>
					<!-- //login -->

					<div class="nonmember_login">
						<? if($_REQUEST[rt_url]=="/shop.php?goPage=Order") {?>
						<dl class="dl_num">비회원으로 구매가능합니다. (단, 할인/쿠폰 등의 혜택이 제공되지 않을 수 있습니다.)</dl>
						<form action='/module/member/member_evn.php' method='post' name='loginForm2'>
						<input type="hidden" name="evnMode" value="login_guest">
						<input type="hidden" name="rt_url" value="<?=$_REQUEST[rt_url]?>">
						<input type="hidden" name="name" value="guest">
						<a href="javascript:checkLoginGuest(document.loginForm2);" class="btn_brown4">비회원 구매</a>
						</form>
						<?} else {?>
						<form action='/module/member/member_evn.php' method='post' name='loginForm3'>
						<input type="hidden" name="evnMode" value="login_guest2">
						<input type="hidden" name="rt_url" value="<?=$_REQUEST[rt_url]?>">
						<dl class="dl_num">
							<dt>주문자명</dt>
							<dd><input type="text" id="order_name" name="order_name" value=""  /></dd>
						</dl>
						<dl>
							<dt>주문비밀번호</dt>
							<dd><input type="password" id="order_pw" name="order_pw" value=""  /></dd>
						</dl>
						<a href="javascript:checkLoginGuest2(document.loginForm3);" class="btn_brown4">비회원 구매 확인</a>
						</form>
						<?} ?>
					</div>
				</div>
				<!-- //member -->
				
			<!-- 내용 : e -->	
			</div>
			<!-- //con -->
		</div>
		<!--//content --> 
	</div>
