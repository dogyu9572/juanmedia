<?PHP
######################################################## 디자인 ST
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/dtd.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/header.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/aside.php";
?>
<div class="container">

	<div class="title">업체 관리 등록</div>

	<div class="inbox write_tbl mo_break_write">
		<table>
			<tr>
				<th>구분</th>
				<td>
					<div class="inputs">
						<label class="check"><input type="checkbox"><i></i>개발자</label>
						<label class="check"><input type="checkbox"><i></i>사용자</label>
						<label class="check"><input type="checkbox"><i></i>협약자</label>
						<label class="check"><input type="checkbox"><i></i>회원사</label>
					</div>
				</td>
			</tr>
		</table>

		<div class="tit">기본정보 <i>*</i></div>
		<table>
			<tr>
				<th>업체구분 *</th>
				<td>
					<div class="inputs">
						<label class="radio"><input type="radio" name="radio1"><i></i>업체(중소기업)</label>
						<label class="radio"><input type="radio" name="radio1"><i></i>업체(대기업)</label>
						<label class="radio"><input type="radio" name="radio1"><i></i>연구소</label>
						<label class="radio"><input type="radio" name="radio1"><i></i>개인</label>
						<label class="radio"><input type="radio" name="radio1"><i></i>공공</label>
						<label class="radio"><input type="radio" name="radio1"><i></i>학계</label>
						<label class="radio"><input type="radio" name="radio1"><i></i>기타</label>
					</div>
				</td>
			</tr>
			<tr>
				<th>주민번호(법인번호)</th>
				<td>
					<div class="inputs">
						<input type="text" class="w2">
						<em class="w2">-</em>
						<input type="text" class="w2">
					</div>
				</td>
			</tr>
			<tr>
				<th>사업자등록번호</th>
				<td>
					<div class="inputs">
						<input type="text" class="w1">
						<em class="w1">-</em>
						<input type="text" class="w1">
						<em class="w1">-</em>
						<input type="text" class="w1">
					</div>
				</td>
			</tr>
			<tr>
				<th>상호 *</th>
				<td>
					<ul class="half">
						<li><input type="text" class="w3"></li>
						<li>구)&nbsp;<input type="text" class="w3"></li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>대표자 *</th>
				<td>
					<ul class="plus_minus plus_minus_boss">
						<li><input type="text" name="txt" class="w3"><button type="button" class="btnAdd_boss">+ 추가</button></li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>업태 *</th>
				<td>
					<div class="inputs">
						<select name="" id="" class="w3">
							<option value="">선택</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<th>주소 *</th>
				<td>
					<ul class="address">
						<li>
							<button class="zip">우편번호</button>
							<input type="text" class="w1">
							<input type="text" class="w3">
							<input type="text" class="w3">
						</li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>우편물수령지 *</th>
				<td>
					<ul class="address">
						<li>
							<button class="zip">우편번호</button>
							<input type="text" class="w1">
							<input type="text" class="w3">
							<input type="text" class="w3">
						</li>
						<li><label class="check"><input type="checkbox"><i></i>사업자등록증 주소와 동일</label></li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>전화번호 *</th>
				<td>
					<div class="inputs">
						<input type="text" class="w1">
						<em class="w1">-</em>
						<input type="text" class="w1">
						<em class="w1">-</em>
						<input type="text" class="w1">
					</div>
				</td>
			</tr>
			<tr>
				<th>팩스번호 *</th>
				<td>
					<div class="inputs">
						<input type="text" class="w1">
						<em class="w1">-</em>
						<input type="text" class="w1">
						<em class="w1">-</em>
						<input type="text" class="w1">
					</div>
				</td>
			</tr>
			<tr>
				<th>이메일 *</th>
				<td>
					<div class="inputs email">
						<input type="text" class="w3">
						<em class="w2">@</em>
						<input type="text" name="" id="str_email02" class="w3">
						<select name="" id="selectEmail" class="w3">
							<option value="1">직접입력</option>
							<option value="naver.com">naver.com</option>
							<option value="daum.net">daum.net</option>
							<option value="nate.com">nate.com</option>
							<option value="dreamwiz.com">dreamwiz.com</option>
							<option value="freechal.com">freechal.com</option>
							<option value="lycos.co.kr">lycos.co.kr</option>
							<option value="korea.com">korea.com</option>
							<option value="gmail.com">gmail.com</option>
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<th>홈페이지</th>
				<td><div class="inputs"><input type="text" class="w4"></div></td>
			</tr>
			<tr>
				<th>회사 창립기념일</th>
				<td><input type="text" id="datepicker1" class="w3" /></td>
			</tr>
			<tr>
				<th>보유 신기술 *</th>
				<td>
					<div class="inputs">
						<label class="check"><input type="checkbox"><i></i>건설신기술</label>
						<label class="check"><input type="checkbox"><i></i>교통신기술</label>
						<label class="check"><input type="checkbox"><i></i>환경신기술</label>
						<label class="check"><input type="checkbox"><i></i>방재신기술</label>
						<label class="check"><input type="checkbox"><i></i>자연재해저감신기술</label>
						<label class="check"><input type="checkbox"><i></i>보건신기술</label>
						<label class="check"><input type="checkbox"><i></i>물류신기술</label>
						<label class="check"><input type="checkbox"><i></i>농림식품신기술</label>
						<label class="check"><input type="checkbox"><i></i>목재제품신기술</label>
						<label class="check"><input type="checkbox"><i></i>전력신기술</label>
						<label class="check"><input type="checkbox"><i></i>신기술(NET)</label>
						<label class="check"><input type="checkbox"><i></i>신제품(NEP)</label>
						<label class="check"><input type="checkbox"><i></i>보유신기술 없음</label>
					</div>
				</td>
			</tr>
			<tr>
				<th>사업자 등록증 *</th>
				<td>
					<div class="inputs">
						<div class="filebutton">
							<span>파일 선택</span>
							<input type="file" name="egovComFileUploader" class="searchfile" title="파일 찾기">
						</div>
						<div class="filebox">선택된 파일 없음</div>
					</div>
				</td>
			</tr>
		</table>

		<div class="tit">개발기술 정보 <button class="btn btnAdd_devel">+ 추가</button></div>
		<table>
			<tbody class="plus_minus plus_minus_devel">
				<tr>
					<th>신기술구분</th>
					<td>
						<div class="inputs">
							<label class="radio"><input type="radio" name="radio2"><i></i>건설</label>
							<label class="radio"><input type="radio" name="radio2"><i></i>교통</label>
						</div>
					</td>
				</tr>
				<tr>
					<th>지정번호</th>
					<td>
						<div class="inputs">
							<input type="text" class="w4">
						</div>
					</td>
				</tr>
				<tr>
					<th>주관사(출력순서)</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
			</tbody>
		</table>

		<div class="tit">사용기술 정보 <button class="btn btnAdd_tech">+ 추가</button></div>
		<table>
			<tbody>
				<tr>
					<th>실적신고담당자</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>전화번호 *</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<th>팩스번호 *</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<th>이메일 *</th>
					<td>
						<div class="inputs email">
							<input type="text" class="w3">
							<em class="w2">@</em>
							<input type="text" name="" id="str_email02" class="w3">
							<select name="" id="selectEmail" class="w3">
								<option value="1">직접입력</option>
								<option value="naver.com">naver.com</option>
								<option value="daum.net">daum.net</option>
								<option value="nate.com">nate.com</option>
								<option value="dreamwiz.com">dreamwiz.com</option>
								<option value="freechal.com">freechal.com</option>
								<option value="lycos.co.kr">lycos.co.kr</option>
								<option value="korea.com">korea.com</option>
								<option value="gmail.com">gmail.com</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th>신기술구분</th>
					<td>
						<div class="inputs">
							<label class="radio"><input type="radio" name="radio2"><i></i>건설</label>
							<label class="radio"><input type="radio" name="radio2"><i></i>교통</label>
						</div>
					</td>
				</tr>
				<tr>
					<th>지정번호</th>
					<td>
						<ul class="plus_minus plus_minus_tech">
							<li><input type="text" class="w4"></li>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="tit">협약기술 정보 <button class="btn btnAdd_conv">+ 추가</button></div>
		<table>
			<tbody>
				<tr>
					<th>협약담당자</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>전화번호 *</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<th>팩스번호 *</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<th>이메일 *</th>
					<td>
						<div class="inputs email">
							<input type="text" class="w3">
							<em class="w2">@</em>
							<input type="text" name="" id="str_email02" class="w3">
							<select name="" id="selectEmail" class="w3">
								<option value="1">직접입력</option>
								<option value="naver.com">naver.com</option>
								<option value="daum.net">daum.net</option>
								<option value="nate.com">nate.com</option>
								<option value="dreamwiz.com">dreamwiz.com</option>
								<option value="freechal.com">freechal.com</option>
								<option value="lycos.co.kr">lycos.co.kr</option>
								<option value="korea.com">korea.com</option>
								<option value="gmail.com">gmail.com</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th>등록번호</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>신기술구분</th>
					<td>
						<div class="inputs">
							<label class="radio"><input type="radio" name="radio2"><i></i>건설</label>
							<label class="radio"><input type="radio" name="radio2"><i></i>교통</label>
						</div>
					</td>
				</tr>
				<tr>
					<th>지정번호</th>
					<td>
						<ul class="plus_minus plus_minus_conv_a">
							<li><input type="text" class="w4"></li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>협약기간</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>활용건수</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>활용금액</th>
					<td>
						<ul class="plus_minus plus_minus_conv_b">
							<li><input type="text" class="w4"></li>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="tit">회원사 정보</div>
		<table>
			<tbody>
				<tr>
					<th>회비담당자</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>전화번호 *</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<th>팩스번호 *</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<th>이메일 *</th>
					<td>
						<div class="inputs email">
							<input type="text" class="w3">
							<em class="w2">@</em>
							<input type="text" name="" id="str_email02" class="w3">
							<select name="" id="selectEmail" class="w3">
								<option value="1">직접입력</option>
								<option value="naver.com">naver.com</option>
								<option value="daum.net">daum.net</option>
								<option value="nate.com">nate.com</option>
								<option value="dreamwiz.com">dreamwiz.com</option>
								<option value="freechal.com">freechal.com</option>
								<option value="lycos.co.kr">lycos.co.kr</option>
								<option value="korea.com">korea.com</option>
								<option value="gmail.com">gmail.com</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th>가입 날짜</th>
					<td><div class="inputs"><input type="text" id="datepicker2" class="w3" /></div></td>
				</tr>
				<tr>
					<th>유지현황</th>
					<td>
						<div class="inputs">
							<label class="radio"><input type="radio"><i></i>유효</label>
							<label class="radio"><input type="radio"><i></i>미납</label>
							<label class="radio"><input type="radio"><i></i>탈퇴</label>
							<label class="radio"><input type="radio"><i></i>부도</label>
							<label class="radio"><input type="radio"><i></i>합병</label>
							<label class="radio"><input type="radio"><i></i>취소</label>
						</div>
					</td>
				</tr>
				<tr>
					<th>특이사항</th>
					<td><div class="inputs"><textarea name="" id="" cols="30" rows="7" class="w100p"></textarea></div></td>
				</tr>
			</tbody>
		</table>

		<div class="tit">담당자 <i>*</i><input type="text" class="w1"><button class="btn">+ 추가</button></div>
		<table>
			<tbody>
				<tr>
					<th>담당자명</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>직책</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>이메일</th>
					<td>
						<div class="inputs email">
							<input type="text" class="w3">
							<em class="w2">@</em>
							<input type="text" name="" id="str_email02" class="w3">
							<select name="" id="selectEmail" class="w3">
								<option value="1">직접입력</option>
								<option value="naver.com">naver.com</option>
								<option value="daum.net">daum.net</option>
								<option value="nate.com">nate.com</option>
								<option value="dreamwiz.com">dreamwiz.com</option>
								<option value="freechal.com">freechal.com</option>
								<option value="lycos.co.kr">lycos.co.kr</option>
								<option value="korea.com">korea.com</option>
								<option value="gmail.com">gmail.com</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th>부서</th>
					<td><div class="inputs"><input type="text" class="w4"></div></td>
				</tr>
				<tr>
					<th>회사 연락처</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<th>직통 FAX</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<th>휴대폰번호</th>
					<td>
						<div class="inputs">
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
							<em class="w1">-</em>
							<input type="text" class="w1">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<img src="/backoffice/pub/images/img_edit_sample.gif" alt="에디터" class="pc_vw">
						<textarea name="" id="" cols="30" rows="10" class="mo_vw"></textarea>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="btns">
			<a href="list.php" class="btn btn_list">목록보기</a>
			<a href="javascript:void(0);" class="btn btn_cancel">취소</a>
			<button class="btn btn_save">저장</button>
		</div>
	</div> <!-- //inbox -->

</div>

<script type="text/javascript">
//<![CDATA[
$(window).load(function(){
//달력
	$("#datepicker1,#datepicker2").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.gif", 
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});
//이메일 직접입력
	$('#selectEmail').change(function(){
		$("#selectEmail option:selected").each(function () {
			if($(this).val()== '1'){
				$("#str_email02").val('');
				$("#str_email02").attr("disabled",false);
			}else{
				$("#str_email02").val($(this).text());
				$("#str_email02").attr("disabled",true);
			}
		});
	});
//파일선택
	$(".searchfile").on('change',function(){
		val = $(this).val().split("\\");
		f_name = val[val.length-1]; 
		s_name = f_name.substring(f_name.length-4, f_name.length);
		$(this).parent().siblings('.filebox').html(f_name);
	});
//추가삭제 - 대표자
	$('.btnAdd_boss').click(function () {
		$('.plus_minus_boss').append (
			'<li><input type="text" name="txt" class="w3"> <button type="button" class="btnRemove_boss">- 삭제</button></li>'
		);
		$('.btnRemove_boss').on('click', function () {
			$(this).parent().remove();
		});
	});
//추가삭제 - 개발기술 정보
	$('.btnAdd_devel').click(function () {
		$('.plus_minus_devel').append (
			'<tr class="tline"><th>신기술구분</th><td><div class="inputs"><label class="radio"><input type="radio" name="radio2"><i></i>건설</label><label class="radio"><input type="radio" name="radio2"><i></i>교통</label></div></td></tr><tr><th>지정번호</th><td><div class="inputs"><input type="text" class="w4"><button type="button" class="btnRemove_devel">- 삭제</button></div></td></tr><tr><th>주관사(출력순서)</th><td><div class="inputs"><input type="text" class="w4"></div></td></tr>'
		);
		$('.btnRemove_devel').on('click', function () {
			$(this).parent().parent().parent().prev().remove();
			$(this).parent().parent().parent().next().remove();
			$(this).parent().parent().parent().remove();
		});
	});
//추가삭제 - 사용기술 정보
	$('.btnAdd_tech').on('click', function() {
		$('.plus_minus_tech').append (
			'<li><input type="text" class="w4"><button type="button" class="btnRemove_tech">- 삭제</button></li>'
		);
		$('.btnRemove_tech').on('click', function () {
			$(this).parent().remove();
		});
		$('select').niceSelect();
	});
//추가삭제 - 협약기술 정보
	$('.btnAdd_conv').on('click', function() {
		$('.plus_minus_conv_a').append (
			'<li><input type="text" class="w4"><button type="button" class="btnRemove_conv">- 삭제</button></li>'
		);
		$('.plus_minus_conv_b').append (
			'<li><input type="text" class="w4"><button type="button" class="btnRemove_conv">- 삭제</button></li>'
		);
		$('.btnRemove_conv').on('click', function () {
			$(this).parent().remove();
		});
		$('select').niceSelect();
	});
});
//]]>
</script>

<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>