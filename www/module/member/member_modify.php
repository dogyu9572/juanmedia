<?
//로그인확인
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getUserInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

//DB해제
SetDisConn($dblink);

$arrPhone = explode("-",$arrInfo["list"][0][phone]);
$arrBirth = explode("-",$arrInfo["list"][0][birth]);
?>
<script language="javascript">
function checkForm(frm){
	if(frm.user_pw.value.length > 0){
		if (frm.user_pw.value==""){
			alert("비밀번호를 입력해 주세요.");
			frm.user_pw.focus();
			return false;
		}
		if (frm.user_pw2.value==""){
			alert("비밀번호 확인을 입력해 주세요.");
			frm.user_pw2.focus();
			return ;
		}
		if (frm.user_pw.value.length < 6){
			alert("비밀번호는 6~16자로 입력해 주세요.");
			frm.user_pw.focus();
			return ;
		}
		if (frm.user_pw.value != frm.user_pw2.value){
			alert("비밀번호가 일치하지 않습니다.");
			frm.user_pw2.focus();
			return ;
		}
		if (frm.user_pw.value == "<?=$_SESSION[$_SITE['DOMAIN']]['MEMBER']['ID']?>"){
			alert("비밀번호가 회원아이디와 동일합니다.");
			frm.user_pw.focus();
			return ;
		}
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


var count = <?=$arrInfo['total_baby']?$arrInfo['total_baby']:"0"?>;
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
	
	//if(count >1){ // 현재 폼이 두개 이상이면
		var addedDiv = document.getElementById("added_"+(--count));
		// 마지막으로 생성된 폼의 ID를 통해 Div객체를 가져옴
		addedFormDiv.removeChild(addedDiv); // 폼 삭제 
	 //}else{ // 마지막 폼만 남아있다면
		document.memberForm.reset(); // 폼 내용 삭제
	// }
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

			<!-- leftArea : s -->
			<? include $_SERVER['DOCUMENT_ROOT'].'/mypage/left.php'; ?>
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="con">
				<!-- 내용 : s -->
				<div class="location">
					<p class="local"><span class="home"></span><span class="route">마이페이지</span><span class="route">회원정보</span><span class="current">회원정보수정</span></p>
				</div>
				<!-- //location -->
				<h2>회원정보수정</h2>
				<h3>회원정보수정</h3>
				<p class="font16 colBlcok">고객님의 회원정보를 수정하실 수 있습니다. 회원정보를 변경하시고 반드시 하단에 있는 확인 버튼을 클릭해 주셔야 합니다.</p>
				<p class="colBrown mb10">* 표시 영역은 필수입력 항목입니다.</p>
				<form name="memberForm" method="post" action="/module/member/member_evn.php">
				<input type="hidden" name="evnMode" value="edit">
				<input type="hidden" name="count" id="count" value="<?=$arrInfo['total_baby']?$arrInfo['total_baby']:"0"?>">
				<input type="hidden" name="children_value">
				<div class="joinWrite mb40">
					<a href="/member.php?goPage=Leave" class="btn_secede">회원탈퇴</a>
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
								<th scope="row" class="necessary first pt25">아이디(이메일)</th>
								<td class="first pt20">
									<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>
								</td>
							</tr>
							<tr>
								<th scope="row" class="necessary">이름</th>
								<td><?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?></td>
							</tr>
							<tr>
								<th scope="row" class="necessary">비밀번호</th>
								<td><input type="password" id="user_pw" name="user_pw" value="" style="width:220px;" /> <span class="font13">(6~16자의 영문대소문자, 숫자, 특수문자 사용가능)</span></td>
							</tr>
							<tr>
								<th scope="row" class="necessary">비밀번호 확인</th>
								<td><input type="password" id="user_pw2" name="user_pw2" value="" style="width:220px;" /></td>
							</tr>
							<tr>
								<th scope="row" class="necessary">주소</th>
								<td>
									<span class="disB mb10">
										<input type="text" id="postcode" name="zip" value="<?=$arrInfo["list"][0][zip]?>" style="width:194px;" />
										<a href="javascript:execDaumPostcode();" class="checkBtn">우편번호 검색</a>
									</span>
									<span class="disB mb10"><input type="text" id="address" name="address" value="<?=$arrInfo["list"][0][address]?>" class="w70" /></span>
									<span class="disB mb10"><input type="text" id="address2" name="address_ext" value="<?=$arrInfo["list"][0][address_ext]?>" class="w70" /></span>
								</td>
							</tr>
							<tr>
								<th scope="row">전화번호</th>
								<td>
									<input type="text" id="phone_1" name="phone_1" value="<?=$arrPhone[0]?>" style="width:75px;" maxlength="4" /> -
									<input type="text" id="phone_2" name="phone_2" value="<?=$arrPhone[1]?>" style="width:106px;" maxlength="4" /> -
									<input type="text" id="phone_3" name="phone_3" value="<?=$arrPhone[2]?>" style="width:106px;" maxlength="4" />
								</td>
							</tr>
							<tr>
								<th scope="row" class="necessary">핸드폰번호</th>
								<td><?=$arrInfo["list"][0][mobile]?></td>
							</tr>
							<tr>
								<th scope="row" class="necessary">성별</th>
								<td>
									<input type="radio" id="sex" name="sex" value="M"<?=$arrInfo["list"][0][sex]=="M"?" checked":""?> /> 남성
									<input type="radio" id="sex" name="sex" value="F"<?=$arrInfo["list"][0][sex]=="F"?" checked":""?> /> 여성
								</td>
							</tr>
							<tr>
								<th scope="row" class="necessary">생년월일</th>
								<td>
									<select name="byear">
										<option value="">선택</option>
										<? for($i=1930;$i<date("Y")-12;$i++) {?>
										<option value="<?=$i?>" <?=$arrBirth[0]==$i?"selected":""?>><?=$i?></option>
										<?}?>
									</select>
									<select name="bmonth">
										<option value="">선택</option>
										<? for($i=1;$i<13;$i++) {
												if($i<10) $i = "0".$i;
										?>
										<option value="<?=$i?>" <?=$arrBirth[1]==$i?"selected":""?>><?=$i?></option>
										<?}?>
									</select>
									<select name="bday">
										<option value="">선택</option>
										<? for($i=1;$i<32;$i++) {
												if($i<10) $i = "0".$i;
										?>
										<option value="<?=$i?>" <?=$arrBirth[2]==$i?"selected":""?>><?=$i?></option>
										<?}?>
									</select>
									<input type="radio" id="solar" name="solar" value="S"<?=$arrInfo["list"][0][solar]=="S"?" checked":""?> /> 양력
									<input type="radio" id="solar" name="solar" value="L"<?=$arrInfo["list"][0][solar]=="L"?" checked":""?> /> 음력
								</td>
							</tr>
							<!--tr>
								<th scope="row" class="pt50">키즈몰 ID</th>
								<td class="pt50">
									<p class="mb10"><input type="text" id="etc_1" name="etc_1" value="<?=$arrInfo["list"][0][etc_1]?>" class="w20" /></p>
									<p class="font13 colBrown">- 적립금 증정 이벤트시 제공되는 아이디입니다.</p>
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
									<input type="radio" id="babychk" name="babychk" value="Y" onclick="enablepanel();" <?=$arrInfo['total_baby']>0?"checked":""?> /> 자녀있음 / 출산예정
									<input type="radio" id="babychk" name="babychk" value="N" onclick="disablepanel();" <?=$arrInfo['total_baby']>0?"":"checked"?> /> 자녀 없음
									<div class="addDel">
										<a href="javascript:addForm()" class="add">+ 추가</a>
										<a href="javascript:delForm()" class="del">- 삭제</a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<div id="addedFormDiv"><?
					if($arrInfo['total_baby']>0) {
					for($j=0; $j<$arrInfo['total_baby']; $j++) {
						$arrBirthBaby[$j] = explode("-",$arrInfo["baby"][$j]["birth"]);

						echo "<div id='added_".$j."'><table>";
						echo "	<colgroup>";
						echo "		<col width='160px' />";
						echo "		<col width='*' />";
						echo "	</colgroup>";
						echo "	<tbody>";
						echo "<tr>";
						echo "		<th scope='row' class='first'>이름(태명)</th>";
						echo "		<td class='first'>";
						echo "			<input type='text' id='babyname_".$j."' class='intbaby' name='babyname_".$j."' value='".$arrInfo["baby"][$j]["babyname"]."' style='width:200px;' />";
						echo "			<input type='radio' id='prenatal1_".$j."' name='prenatal_".$j."' value='Y'"; if($arrInfo["baby"][$j]["prenatal"]=="Y") { echo " checked"; } else { echo ""; } echo "/> 출생";
						echo "			<input type='radio' id='prenatal2_".$j."' name='prenatal_".$j."' value='N'"; if($arrInfo["baby"][$j]["prenatal"]=="N") { echo " checked"; } else { echo ""; } echo " /> 출생전";
						echo "		</td>";
						echo "	</tr>";
						echo "	<tr>";
						echo "		<th scope='row'>성별</th>";
						echo "		<td>";
						echo "			<input type='radio' id='sex1_".$j."' name='sex_".$j."' value='M'"; if($arrInfo["baby"][$j]["sex"]=="M") { echo " checked"; } else { echo ""; } echo "/> 남";
						echo "			<input type='radio' id='sex2_".$j."' name='sex_".$j."' value='F'"; if($arrInfo["baby"][$j]["sex"]=="F") { echo " checked"; } else { echo ""; } echo "/> 여";
						echo "			<input type='radio' id='sex3_".$j."' name='sex_".$j."' value='N'"; if($arrInfo["baby"][$j]["sex"]=="N") { echo " checked"; } else { echo ""; } echo "/> 모름";
						echo "		</td>";
						echo "	</tr>";
						echo "	<tr>";
						echo "		<th scope='row'>아이생일</th>";
						echo "		<td>";
						echo "			<select id='byear_".$j."' name='byear_".$j."'>";
						echo "				<option value=''>선택</option>";
							for($i=2000; $i<=date("Y")+1; $i++) {
						echo "				<option value='".$i."'"; if($arrBirthBaby[$j][0]==$i) { echo " selected"; } else { echo ""; } echo ">".$i."년</option>";
							}
						echo "			</select>";
						echo "			<select id='bmonth_".$j."' name='bmonth_".$j."'>";
						echo "				<option value=''>선택</option>";
							for($i=1; $i<13; $i++) { if($i<10) $i="0".$i; 
						echo "				<option value='".$i."'"; if($arrBirthBaby[$j][1]==$i) { echo " selected"; } else { echo ""; } echo ">".$i."월</option>";
							}
						echo "			</select>";
						echo "			<select id='bday_".$j."' name='bday_".$j."'>";
						echo "				<option value=''>선택</option>";
							for($i=1; $i<32; $i++) { if($i<10) $i="0".$i; 
						echo "				<option value='".$i."'"; if($arrBirthBaby[$j][2]==$i) { echo " selected"; } else { echo ""; } echo ">".$i."일</option>";
							}
						echo "			</select>";
						echo "			<span class='colBrown font13'>(출생자녀 생일변경불가)</span>";
						echo "			<p class='fontB colBlack mt10'><input type='radio' id='children_".$j."' name='children' value='c_".$j."'"; if($arrInfo["baby"][$j]["children"]=="Y") { echo " checked"; } else { echo ""; } echo ">맞춤정보/서비스를 위한 대표자녀로 선택</p>";
						echo "		</td>";
						echo "	</tr>";
						echo "</tbody>";
						echo "</table></div>";
					} } else {
						echo "<script>	addForm();disablepanel();</script>";
					}
					?>
					</div>
				</div>
				<!-- //joinWrite -->

				<div class="btnC">
					<a href="javascript:checkForm(document.memberForm)" class="btn_brown2">확인</a>
					<a href="javascript:history.go(-1)" class="btn_gray3">취소</a>
				</div>	
				</form>

				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>

