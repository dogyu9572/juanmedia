<?
//로그인확인
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrList = getAddressList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $scale, mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['offset']));

//DB해제
SetDisConn($dblink);
?>
<script language="javascript">
function del(idx){
	var cfm;
	cfm =false;
	cfm = confirm("선택하신 배송지를 삭제 하시겠습니까?");
	if(cfm==true){
		document.frmContentsHidden.idx.value = idx;
		document.frmContentsHidden.submit();
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

function checkForm(frm) {
	if (frm.shipping.value.length < 2){
		alert("배송지명을 입력해 주세요.");
		frm.shipping.focus();
		return ;
	}

	frm.submit();
}
</script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

	<div id="sub_container">
		<div class="content">

			<!-- leftArea : s -->
			<!-- leftArea : e -->

			<div id="rightArea">
				<div class="con">
				<!-- 내용 : s -->
				<div class="location">
					<p class="local"><span class="home"></span><span class="route">마이페이지</span><span class="route">회원정보</span><span class="current">배송주소록</span></p>
				</div>
				<!-- //location -->
				<h2>배송주소록</h2>
				
				<div class="searchArea">
					<h3>나의 배송 주소록</h3>
					<!-- <select name="">
						<option value=""></option>
					</select>										
					<input type="text" name="" value=""><a href="#" class="sbtn">검색</a>  --><a href="javascript:editAddressInsert()" class="nbtn">신규등록</a>
				</div>
				<!--//searchArea -->
<script>
function editAddressInsert(){
	$.fancybox({href:"#addrNew",inline:true, open:true});

	document.getElementById('tit').innerHTML = "신규등록";
	document.getElementById('ment').innerHTML = "등록하기";
	document.getElementById('shipping').value = "";
	document.getElementById('d_addr').checked = false;
	document.getElementById('user_name').value = "";
	document.getElementById('phone_1').value = "";
	document.getElementById('phone_2').value = "";
	document.getElementById('phone_3').value = "";
	document.getElementById('mobile_1').value = "";
	document.getElementById('mobile_2').value = "";
	document.getElementById('mobile_3').value = "";
	document.getElementById('postcode').value = "";
	document.getElementById('address').value = "";
	document.getElementById('address2').value = "";
	document.getElementById('evnMode').value = "address_insert";
}
</script>
				<div class="blist">
						<table>
							<colgroup>
								<col width="120px" />	
								<col width="100px" />
								<col width="250px" />
								<col width="*" />
								<col width="85px" />
							</colgroup>
							<thead>
								<tr>
									<th scope="col">배송지명</th>
									<th scope="col">수령인</th>
									<th scope="col">전화번호/핸드폰</th>
									<th scope="col">주소</th>
									<th scope="col">관리</th>
								</tr>
							</thead>
							<tbody>
								<?if($arrList['list']['total'] > 0):?>
								<?for ($i=0;$i<$arrList['list']['total'];$i++) {
									$arrPhone = explode("-", $arrList['list'][$i]['phone']);
									$arrMobile = explode("-", $arrList['list'][$i]['mobile']);
								?>
								<script>
								function editAddress<?=$arrList['list'][$i]['idx']?>(){
									$.fancybox({href:"#addrNew",inline:true, open:true});

									document.getElementById('tit').innerHTML = "주소록 수정";
									document.getElementById('ment').innerHTML = "수정하기";
									<? if($arrList['list'][$i]['d_addr']=="Y") {?>
									document.getElementById('d_addr').checked = true;
									<? } else {?>
									document.getElementById('d_addr').checked = false;
									<? } ?>
									document.getElementById('shipping').value = "<?=$arrList['list'][$i]['shipping']?>";
									document.getElementById('user_name').value = "<?=$arrList['list'][$i]['name']?>";
									document.getElementById('phone_1').value = "<?=$arrPhone[0]?>";
									document.getElementById('phone_2').value = "<?=$arrPhone[1]?>";
									document.getElementById('phone_3').value = "<?=$arrPhone[2]?>";
									document.getElementById('mobile_1').value = "<?=$arrMobile[0]?>";
									document.getElementById('mobile_2').value = "<?=$arrMobile[1]?>";
									document.getElementById('mobile_3').value = "<?=$arrMobile[2]?>";
									document.getElementById('postcode').value = "<?=$arrList['list'][$i]['zip']?>";
									document.getElementById('address').value = "<?=$arrList['list'][$i]['address']?>";
									document.getElementById('address2').value = "<?=$arrList['list'][$i]['address_ext']?>";
									document.getElementById('evnMode').value = "address_edit";
									document.getElementById('idx').value = "<?=$arrList['list'][$i]['idx']?>";
								}
								</script>
								<tr>
									<td><?=$arrList['list'][$i]['shipping']?><br><?=$arrList['list'][$i]['d_addr']=="Y"?"<font color='red'>기본배송지</font>":""?></td>
									<td><?=$arrList['list'][$i]['name']?></td>
									<td><?=$arrList['list'][$i]['phone']?> / <?=$arrList['list'][$i]['mobile']?></td>
									<td>(<?=$arrList['list'][$i]['zip']?>) <?=stripslashes($arrList['list'][$i]['address'])?> <?=stripslashes($arrList['list'][$i]['address_ext'])?></td>
									<td>
										<span class="disB mb2"><a href="javascript:editAddress<?=$arrList['list'][$i]['idx']?>();" class="btn_gray2">수정</a></span>
										<span class="disB"><a href="javascript:del(<?=$arrList['list'][$i]['idx']?>)" class="btn_black">삭제</a></span>
									</td>
								</tr>
								<?} else:?>
								<tr height="100" align="center">
								  <td width="100%" colspan="8" >등록된 배송지가 없습니다.</td>
								</tr>
								<?endif;?>
							</tbody>
						</table>
					</div>
					<!-- //blist --> 

					<div class="paging">
						<?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"")?>
					</div>
					<!--//paging --> 

					<!-- 신규등록 레이아팝업-->
					<div id="addrNew" class="popupWrite">
						<form name="memberForm" method="post" action="/module/member/member_evn.php">
						<input type="hidden" name="evnMode" id="evnMode" value="address_insert">
						<input type="hidden" name="idx" id="idx" value="">
						<p class="tit" id="tit">신규등록</p>
						<table>
							<colgroup>
								<col width="120px" />
								<col width="*" />
							</colgroup>
							<tbody>
								<tr>
									<th scope="row">배송지명</th>
									<td>
										<input type="text" id="shipping" name="shipping" value="" /> 
										<span class="disB mt10"><input type="checkbox" id="d_addr" name="d_addr" value="Y" />이 주소를 기본배송지로 설정하시겠습니까?</span>
									</td>
								</tr>
								<tr>
									<th scope="row">수령인</th>
									<td>
										<input type="text" id="user_name" name="name" value="" class="w50" /> 
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
									<th scope="row">휴대번호</th>
									<td>
										<input type="text" id="mobile_1" name="mobile_1" value="" style="width:75px;" maxlength="4" /> -
										<input type="text" id="mobile_2" name="mobile_2" value="" style="width:106px;" maxlength="4" /> -
										<input type="text" id="mobile_3" name="mobile_3" value="" style="width:106px;" maxlength="4" /> 
									</td>
								</tr>
								<tr>
									<th scope="row">주소</th>
									<td>
										<span class="disB mb2">
											<input type="text" id="postcode" name="zip" value="" style="width:194px;" />
											<a href="javascript:execDaumPostcode();" class="checkBtn">우편번호 검색</a>
										</span>
										<span class="disB mb2"><input type="text" id="address" name="address" value="" class="w100" /></span>
										<span class="disB mb2"><input type="text" id="address2" name="address_ext" value="" class="w100" /></span>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="btnC">
							<a href="javascript:checkForm(document.memberForm)" class="btn_gray4" id="ment">등록하기</a>
						</div>
						</form>
					</div>
					<!-- //popupWrite -->
					<!-- //신규등록 레이아팝업-->

				<!-- 내용 : e -->	
				</div>
				<!-- //con -->
			</div>
			<!-- //rightArea -->
		</div>
		<!--//content --> 
	</div>

<form name="frmContentsHidden" method="post" action="/module/member/member_evn.php">
<input type="hidden" name="evnMode" value="address_delete">
<input type="hidden" name="idx">
<input type="hidden" name="returnURL" value="<?=$_SERVER['REQUEST_URI']?>">
</form>
