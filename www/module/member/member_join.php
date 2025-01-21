<?
//회원약관 동의를 하지 않았으면 약관동의 페이지로 이동시킴
if($_GET[param]==""){
	echo "<script>
	document.location.href = '/member.php?goPage=Agree';
	</script>";
}

$arrJoin = explode("|", $_GET[param]);
$arrBirth1 = substr($arrJoin[2],0,4);
$arrBirth2 = substr($arrJoin[2],4,2);
$arrBirth3 = substr($arrJoin[2],6,2);
$arrMobile1 = substr($arrJoin[1],0,3);
$arrMobile2 = substr($arrJoin[1],3,4);
$arrMobile3 = substr($arrJoin[1],7,4);

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserFindMobile($arrMobile1."-".$arrMobile2."-".$arrMobile3);

//DB해제
SetDisConn($dblink);

if($arrInfo["total"]>0) {
	echo "<script>
	alert('이미 회원가입이 된 상태거나 탈퇴회원입니다.\\n\\n확인후 다시 가입하십시요.');
	history.go(-2);
	</script>";
}
?>
<script language="javascript">
//아이디 중복체크
function check_id(email1, email2){
	if(email1=="" && email2==""){
		alert('이메일을 입력하신후 클릭해 주세요.');
		document.memberForm.email_id.focus();
	} else {
		$.get("/module/member/ajax_check_id.php", {email_id: email1, email_domain: email2},
		function(data){
			if(data=="0"){
				alert('사용가능한 이메일 입니다.');
				document.memberForm.dupcheck.value = email1;
			}else if(data=="1"){
				alert('이미 사용중인 이메일 입니다.');
			}else{
				alert('오류가 발생하였습니다. 다시 시도해 주세요.');
			}
			document.memberForm.email_id.focus();
		});
	}
}

function checkForm(frm){
	if (frm.email_id.value.length < 2){
		alert("이메일을 입력해 주세요.");
		frm.email_id.focus();
		return ;
	}
	if (frm.email_domain.value.length < 2){
		alert("이메일을 입력해 주세요.");
		frm.email_domain.focus();
		return ;
	}
	if (frm.email_id.value != frm.dupcheck.value){
		alert("아이디 중복확인을 해주세요.");
		return ;
	}
	if (frm.user_name.value==""){
		alert("이름을 입력해 주세요.");
		return ;
	}
	if (frm.user_pw.value==""){
		alert("비밀번호를 입력해 주세요.");
		frm.user_pw.focus();
		return ;
	}
	if (frm.user_pw.value.length < 6){
		alert("비밀번호는 6~16자로 입력해 주세요.");
		frm.user_pw.focus();
		return ;
	}
	if (frm.user_pw2.value==""){
		alert("비밀번호 확인을 입력해 주세요.");
		frm.user_pw2.focus();
		return ;
	}
	if (frm.user_pw.value != frm.user_pw2.value){
		alert("비밀번호가 일치하지 않습니다.");
		frm.user_pw2.focus();
		return ;
	}
	if (frm.zip.value.length < 2){
		alert("우편번호를 입력해 주세요.");
		frm.zip.focus();
		return ;
	}
	if (frm.address.value.length < 2){
		alert("주소를 입력해 주세요.");
		frm.address.focus();
		return ;
	}
	if (frm.address_ext.value.length < 2){
		alert("상세주소를 입력해 주세요.");
		frm.address_ext.focus();
		return ;
	}
	if (frm.mobile.value==""){
		alert("핸드폰번호를 입력해 주세요.");
		return ;
	}
	if (frm.sex[0].checked==false && frm.sex[1].checked==false){
		alert("성별을 선택해주세요.");
		frm.sex[0].focus();
		return ;
	}
	if (frm.byear.value==""){
		alert("생년월일를 선택해 주세요.");
		frm.byear.focus();
		return ;
	}
	if (frm.bmonth.value==""){
		alert("생년월일를 선택해 주세요.");
		frm.bmonth.focus();
		return ;
	}
	if (frm.bday.value==""){
		alert("생년월일를 선택해 주세요.");
		frm.bday.focus();
		return ;
	}
	if (frm.solar[0].checked==false && frm.solar[1].checked==false){
		alert("생일구분을 선택해 주세요.");
		frm.solar[0].focus();
		return ;
	}
	if (frm.babychk[0].checked==false && frm.babychk[1].checked==false){
		alert("자녀여부를 선택해 주세요.");
		frm.babychk[0].focus();
		return ;
	}

	if (frm.babychk[0].checked==true) {	//자녀있을 선택시
		var k = $(".intbaby").length;
		for( i=0; i<k; i++){
			var birth = "prenatal_"+i;
			var gender = "sex_"+i;
			var year = "byear_"+i;
			var month = "bmonth_"+i;
			var day = "bday_"+i;

			if (document.getElementById("babyname_"+i).value==""){
				alert("이름(태명)을 입력해 주세요.");
				document.getElementById("babyname_"+i).focus();
				return ;
			}
			if (document.getElementById("prenatal1_"+i).checked==false && document.getElementById("prenatal2_"+i).checked==false){
				alert("출생여부를 선택해 주세요.");
				document.getElementById("prenatal1_"+i).focus();
				return ;
			}
			if (document.getElementById("sex1_"+i).checked==false && document.getElementById("sex2_"+i).checked==false && document.getElementById("sex3_"+i).checked==false){
				alert("성별을 선택해 주세요.");
				document.getElementById("sex1_"+i).focus();
				return ;
			}
			if (document.getElementById("byear_"+i).value==""){
				alert("아이 생년을 입력해 주세요.");
				document.getElementById("byear_"+i).focus();
				return ;
			}
			if (document.getElementById("bmonth_"+i).value==""){
				alert("아이 생월을 입력해 주세요.");
				document.getElementById("bmonth_"+i).focus();
				return ;
			}
			if (document.getElementById("bday_"+i).value==""){
				alert("아이 생일을 입력해 주세요.");
				document.getElementById("bday_"+i).focus();
				return ;
			}
		}
	}
	frm.children_value.value = $("input[name=children]:checked").val();

	frm.submit();
}

function num_filter(el, length) { 
    try { 
        el.value = el.value.replace(/[^0-9\*]/, ""); 

        var str = el.value.substr(0, length); 
        var len = el.value.length; 
        el.value = str; 
    } catch(e) { 
        alert(e.description); 
    } 
} 

function execDaumPostcode(gb) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			if(gb=="2") {
				document.getElementById('ship_postcode').value = data.zonecode; //5자리 새우편번호 사용
				document.getElementById('ship_address').value = fullAddr;

				// 커서를 상세주소 필드로 이동한다.
				document.getElementById('ship_address2').focus();
			} else {
				document.getElementById('postcode').value = data.zonecode; //5자리 새우편번호 사용
				document.getElementById('address').value = fullAddr;

				// 커서를 상세주소 필드로 이동한다.
				document.getElementById('address2').focus();
			}
		}
	}).open();
}



var count = 0;
function addForm(){
	var addedFormDiv = document.getElementById("addedFormDiv");
	
	var str = "";
	str+="<table>";
	str+="	<colgroup>";
	str+="		<col width='160px' />";
	str+="		<col width='*' />";
	str+="	</colgroup>";
	str+="	<tbody>";
	str+="<tr>";
	str+="		<th scope='row' class='first'>이름(태명)</th>";
	str+="		<td class='first'>";
	str+="			<input type='text' id='babyname_"+count+"' class='intbaby' name='babyname_"+count+"' value='' style='width:200px;' />";
	str+="			<input type='radio' id='prenatal1_"+count+"' name='prenatal_"+count+"' value='Y' /> 출생";
	str+="			<input type='radio' id='prenatal2_"+count+"' name='prenatal_"+count+"' value='N' /> 출생전";
	str+="		</td>";
	str+="	</tr>";
	str+="	<tr>";
	str+="		<th scope='row'>성별</th>";
	str+="		<td>";
	str+="			<input type='radio' id='sex1_"+count+"' name='sex_"+count+"' value='M' /> 남";
	str+="			<input type='radio' id='sex2_"+count+"' name='sex_"+count+"' value='F' /> 여";
	str+="			<input type='radio' id='sex3_"+count+"' name='sex_"+count+"' value='N' /> 모름";
	str+="		</td>";
	str+="	</tr>";
	str+="	<tr>";
	str+="		<th scope='row'>아이생일</th>";
	str+="		<td>";
	str+="			<select id='byear_"+count+"' name='byear_"+count+"'>";
	str+="				<option value=''>선택</option>";
	<? for($i=2000; $i<=date("Y")+1; $i++) {?>
	str+="				<option value='<?=$i?>'><?=$i?>년</option>";
	<?}?>
	str+="			</select>";
	str+="			<select id='bmonth_"+count+"' name='bmonth_"+count+"'>";
	str+="				<option value=''>선택</option>";
	<? for($i=1; $i<13; $i++) { if($i<10) $i="0".$i; ?>
	str+="				<option value='<?=$i?>'><?=$i?>월</option>";
	<?}?>
	str+="			</select>";
	str+="			<select id='bday_"+count+"' name='bday_"+count+"'>";
	str+="				<option value=''>선택</option>";
	<? for($i=1; $i<32; $i++) { if($i<10) $i="0".$i; ?>
	str+="				<option value='<?=$i?>'><?=$i?>일</option>";
	<?}?>
	str+="			</select>";
	str+="			<span class='colBrown font13'>(출생자녀 생일변경불가)</span>";
	str+="			<p class='fontB colBlack mt10'><input type='radio' id='children_"+count+"' name='children' value='c_"+count+"'>맞춤정보/서비스를 위한 대표자녀로 선택</p>";
	str+="		</td>";
	str+="	</tr>";
	str+="</tbody>";
	str+="</table>";
	// 추가할 폼(에 들어갈 HTML)

	 var addedDiv = document.createElement("div"); // 폼 생성
	 addedDiv.id = "added_"+count; // 폼 Div에 ID 부여 (삭제를 위해)
	 addedDiv.innerHTML  = str; // 폼 Div안에 HTML삽입
	 addedFormDiv.appendChild(addedDiv); // 삽입할 DIV에 생성한 폼 삽입

	 count++;
	 document.memberForm.count.value=count;
	 // 다음 페이지에 몇개의 폼을 넘기는지 전달하기 위해 히든 폼에 카운트 저장
}

function delForm(){
	var addedFormDiv = document.getElementById("addedFormDiv");
	
	if(count >1){ // 현재 폼이 두개 이상이면
		var addedDiv = document.getElementById("added_"+(--count));
		// 마지막으로 생성된 폼의 ID를 통해 Div객체를 가져옴
		addedFormDiv.removeChild(addedDiv); // 폼 삭제 
	 }else{ // 마지막 폼만 남아있다면
		document.memberForm.reset(); // 폼 내용 삭제
	 }
}

function enablepanel() {
	$("#addedFormDiv  :input").each(function () {
		$(this).prop("disabled",false);
	});

	$(".addDel").show();
}

function disablepanel() {
	$("#addedFormDiv  :input").each(function () {
		$(this).prop("disabled",true);
	});

	$(".addDel").hide();
}
</script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>


	<div id="sub_container">
		<div class="content">
			<div class="location">
				<p class="local"><span class="home"></span><span class="current">Member Join</span></p>
			</div>
			<!-- //location -->
			
			<div class="con">
			<!-- 내용 : s -->
				
				<h2>Member Join</h2>
				<div class="member">
					<p class="mb30"><img src="/img/join_step03.jpg" alt="03 회원정보입력" /></p>
					<div class="joinStep03">
						<p class="font20 colBlcok mb10">회원정보입력</p>
						<p class="font16 colBlcok">회원정보는 개인정보취급방침에 따라 안전하게 보호되며 회원님의 명백한 동의 없이 공개 또는 제3자에게 제공되지 않습니다.</p>
						<p class="colBrown mb10">* 표시 영역은 필수입력 항목입니다.</p>
						
						<form name="memberForm" method="post" action="/module/member/member_evn.php">
						<input type="hidden" name="evnMode" value="join">
						<input type="hidden" id="dupcheck" name="dupcheck">
						<input type="hidden" name="user_name" id="user_name" value="<?=iconv("euc-kr","utf-8",$arrJoin[0])?>">
						<input type="hidden" name="mobile" id="mobile" value="<?=$arrMobile1?>-<?=$arrMobile2?>-<?=$arrMobile3?>">
						<input type="hidden" name="count" id="count" value="0">
						<input type="hidden" name="children_value">

						<div class="joinWrite mb40">
							<table>
								<colgroup>
									<col width="160px" />
									<col width="*" />
								</colgroup>
								<thead>
									<th scope="col" colspan="2">기본정보</th>
								</thead>
								<tbody>
									<tr>
										<th scope="row" class="necessary first">아이디(이메일)</th>
										<td class="first">
											<input type="text" id="email_id" name="email_id" value="" style="width:220px;" style="ime-mode:disabled;" onkeyup="this.value=this.value.replace(' ','')" /> @
											<input type="text" id="email_domain" name="email_domain" value="" style="width:220px;" style="ime-mode:disabled;" onkeyup="this.value=this.value.replace(' ','')" />
											<select name="email_list" id="email_list" onchange="document.getElementById('email_domain').value=this.value;">
												<option value="">직접입력</option>
												<option value="naver.com">naver.com</option>
												<option value="chol.com">chol.com</option>
												<option value="dreamwiz.com">dreamwiz.com</option>
												<option value="empal.com">empal.com</option>
												<option value="freechal.com">freechal.com</option>
												<option value="gmail.com">gmail.com</option>
												<option value="hanafos.com">hanafos.com</option>
												<option value="hanmail.net">hanmail.net</option>
												<option value="hanmir.com">hanmir.com</option>
												<option value="hitel.net">hitel.net</option>
												<option value="hotmail.com">hotmail.com</option>
												<option value="korea.com">korea.com</option>
												<option value="lycos.co.kr">lycos.co.kr</option>
												<option value="nate.com">nate.com</option>
												<option value="netian.com">netian.com</option>
												<option value="paran.com">paran.com</option>
												<option value="yahoo.com">yahoo.com</option>
												<option value="yahoo.co.kr">yahoo.co.kr</option>
											</select>
											<a href="javascript:check_id(document.memberForm.email_id.value, document.memberForm.email_domain.value);" class="checkBtn">중복확인</a>
											<p class="mt10"><input type="checkbox" id="email_accept" name="email_accept" value="Y" />이벤트 및 소식을 이메일로 수신하겠습니다.</p>
										</td>
									</tr>
									<tr>
										<th scope="row" class="necessary">이름</th>
										<td><?=iconv("euc-kr","utf-8",$arrJoin[0])?></td>
									</tr>
									<tr>
										<th scope="row" class="necessary">비밀번호</th>
										<td><input type="password" id="user_pw" name="user_pw" value="" style="width:220px;" maxlength="16" /></td>
									</tr>
									<tr>
										<th scope="row" class="necessary">비밀번호 확인</th>
										<td><input type="password" id="user_pw2" name="user_pw2" value="" style="width:220px;" maxlength="16" /></td>
									</tr>
									<tr>
										<th scope="row" class="necessary">주소</th>
										<td>
											<span class="disB mb2">
												<input type="text" id="postcode" name="zip" value="" style="width:194px;" />
												<a href="javascript:execDaumPostcode();" class="checkBtn">우편번호 검색</a>
											</span>
											<span class="disB mb2"><input type="text" id="address" name="address" value="" class="w70" /></span>
											<span class="disB mb2"><input type="text" id="address2" name="address_ext" value="" class="w70" /></span>
										</td>
									</tr>
									<tr>
										<th scope="row">전화번호</th>
										<td>
											<input type="text" id="phone_1" name="phone_1" value="" style="width:75px;" maxlength="4" /> -
											<input type="text" id="phone_2" name="phone_2" value="" style="width:106px;" maxlength="4" /> -
											<input type="text" id="phone_3" name="phone_3" value="" style="width:106px;" maxlength="4" />
										</td>
									</tr>
									<tr>
										<th scope="row" class="necessary">핸드폰번호</th>
										<td><?=$arrMobile1?>-<?=$arrMobile2?>-<?=$arrMobile3?></td>
									</tr>
									<tr>
										<th scope="row" class="necessary">성별</th>
										<td>
											<input type="radio" id="sex" name="sex" value="M"<?=$arrJoin[3]=="0"?" checked":""?>/> 남성
											<input type="radio" id="sex" name="sex" value="F"<?=$arrJoin[3]=="1"?" checked":""?> /> 여성
										</td>
									</tr>
									<tr>
										<th scope="row" class="necessary">생년월일</th>
										<td>
											<select name="byear">
												<option value="">선택</option>
												<? for($i=1930;$i<date("Y")-12;$i++) {?>
												<option value="<?=$i?>" <?=$arrBirth1==$i?"selected":""?>><?=$i?></option>
												<?}?>
											</select>
											<select name="bmonth">
												<option value="">선택</option>
												<? for($i=1;$i<13;$i++) {
														if($i<10) $i = "0".$i;
												?>
												<option value="<?=$i?>" <?=$arrBirth2==$i?"selected":""?>><?=$i?></option>
												<?}?>
											</select>
											<select name="bday">
												<option value="">선택</option>
												<? for($i=1;$i<32;$i++) {
														if($i<10) $i = "0".$i;
												?>
												<option value="<?=$i?>" <?=$arrBirth3==$i?"selected":""?>><?=$i?></option>
												<?}?>
											</select>
											<input type="radio" id="solar" name="solar" value="S" /> 양력
											<input type="radio" id="solar" name="solar" value="L" /> 음력
										</td>
									</tr>
									<!--tr>
										<th scope="row" class="mallId">키즈몰 ID</th>
										<td class="mallId">
											<p class="mb10"><input type="text" id="etc_1" name="etc_1" value="" class="w20" /></p>
											<p class="font13 colBrown">- 적립금 증정 이벤트시 제공되는 아이디입니다.</p>
											<p class="font13 colBrown">- 필수사항이 아니므로 추후 회원페이지에서 수정 가능함.</p>
										</td>
									</tr-->
								</tbody>
							</table>
						</div>
						<!-- //joinWrite -->

						<div class="joinWrite">
							<table>
								<colgroup>
									<col width="160px" />
									<col width="*" />
								</colgroup>
								<thead>
									<th scope="col" colspan="2">자녀정보 (추가입력사항)</th>
								</thead>
								<tbody>
									<tr class="childCheck">
										<th scope="row">자녀여부</th>
										<td>
											<input type="radio" id="babychk" name="babychk" value="Y" onclick="enablepanel();" /> 자녀있음 / 출산예정
											<input type="radio" id="babychk" name="babychk" value="N" onclick="disablepanel();" /> 자녀 없음
											<div class="addDel">
												<a href="javascript:addForm()" class="add">+ 추가</a>
												<a href="javascript:delForm()" class="del">- 삭제</a>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
							<div id="addedFormDiv"></div>
						</div>
						<!-- //joinWrite -->
						
					</div>
					<!-- //joinStep03 -->

					<div class="btnC">
						<a href="/member.php?goPage=Cert" class="btn_gray3">이전단계</a>
						<a href="javascript:checkForm(document.memberForm)" class="btn_brown2">가입하기</a>
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

<script>
addForm();
</script>