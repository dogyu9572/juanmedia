<?
//로그인확인
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
?>
<script language="JavaScript">
function checkit2(f) {
	if (!f.contents.value) {
		alert("불편하거나 부족한 부분을 입력하세요.");
		f.contents.focus();
		return ;
	}
	f.submit();
}
</script>


<div id="sub_container">
	<div class="content">
		<div class="location">
			<p class="local"><span class="home"></span><span class="current">Secession</span></p>
		</div>
		<!-- //location -->
		
		<div class="con">
		<!-- 내용 : s -->
			
			<h2>Secession</h2>
			<div class="member">
				<form action='/module/member/member_evn.php' method='post' name='leaveform'>
				<input type="hidden" name="evnMode" value="withdrawal">
				<input type="hidden" name="rt_url" value="<?=$_REQUEST[rt_url]?>">
				<div class="secede">
					<p class="t01">홈페이지를 이용해 주셔서 감사드립니다.</p>
					<p class="t02">보다 나은 서비스 제공을 위해 사이트 이용시의 불편했거나 부족했던 부분을 기록해 주시면, 적극 반영 하겠습니다.</p>
					<div class="box">
						<textarea name="contents" class="box"></textarea>
					</div>
				</div>
				<!-- //secede -->

				<div class="btnC">
					<a href="javascript:checkit2(document.leaveform)" class="btn_gray4">회원탈퇴</a>
					<a href="javascript:history.go(-1)" class="btn_gray3">취소</a>
				</div>
				</form>
			</div>
			<!-- //member -->
			
		<!-- 내용 : e -->	
		</div>
		<!-- //con -->
	</div>
	<!--//content --> 
</div>
