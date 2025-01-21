<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

if(!in_array("homepage_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
//	jsMsg("권한이 없습니다.");
//	jsHistory("-1");
endif;
?>
<script type="text/javascript">
<!--
function fnCheck(){	
	var frm = document.frmInfo;	
	if(!frm.shop_point_member.value){
		frm.shop_point_member.value = "0";
	}	
	if(!frm.shop_point_min.value){
		frm.shop_point_min.value = "0";
	}
	if(!frm.shop_point_max.value){
		frm.shop_point_max.value = "0";	
	}
	frm.submit();

}
//-->
</script>
<div class="container">

	<div class="title">최소주문비용 관리</div>
	
	<div class="inbox write_tbl mo_break_write">
		<form name="frmInfo" method="post" action="admin_evn.php">
		<input type="hidden" name="evnMode" value="setDiscount">
		<input type="hidden" name="returl" value="/backoffice/module/admin/admin_set_point.php">

		<table style="display:none;">
			<tr>
				<th>홈페이지 Title</th>
				<td><div class="inputs"><input type="text" class="w4" name="shop_name" value="<?=$arrSetInfo["list"][0]['shop_name']?>"></div></td>
			</tr>
			<tr>
				<th>홈페이지 URL</th>
				<td><div class="inputs"><input type="text" class="w3" name="shop_url" value="<?=$arrSetInfo["list"][0]['shop_url']?$arrSetInfo["list"][0]['shop_url']:"https://"?>"><em> ※ 예) https://도메인주소</em></div></td>
			</tr>
			<tr>
				<th>관리자 Email</th>
				<td><div class="inputs"><input type="text" class="w4" name="admin_email" value="<?=$arrSetInfo["list"][0]['admin_email']?>"></div></td>
			</tr>
			<tr>
				<th>홈페이지 이름</th>
				<td><div class="inputs"><input type="text" class="w4" name="shop_title" value="<?=$arrSetInfo["list"][0]['shop_title']?>"></div></td>
			</tr>
			<tr>
				<th>검색키워드</th>
				<td><div class="inputs"><input type="text" class="w4" name="shop_keyword" value="<?=$arrSetInfo["list"][0]['shop_keyword']?>"></div></td>
			</tr>
			<tr>
				<th>소개글</th>
				<td><div class="inputs"><input type="text" class="w4" name="shop_content" value="<?=$arrSetInfo["list"][0]['shop_content']?>"></div></td>
			</tr>
			<tr style="display:none;">
				<th>추천 검색어</th>
				<td><div class="inputs">
					<textarea name="shop_search" style="width:100%;height:100px;padding:10px;"><?=$arrSetInfo["list"][0]['shop_search']?></textarea>
					</div>
				</td>
			</tr>		
		</table>

		<div class="tit">VAT 포함 가격을 입력해 주세요 <i>*</i></div>
		<table>
			<tbody class="plus_minus plus_minus_devel">
				<tr>
					<th>서울</th>
					<td><div class="inputs">
							<input type="text" class="w3" style="text-align:right;" name="shop_point_default" value="<?=$arrSetInfo["list"][0]['shop_point_default']?>" maxlength="20">
						</div>
					</td>
				</tr>
				<tr>
					<th>경기</th>
					<td><div class="inputs">
							<input type="text" class="w3" style="text-align:right;" name="shop_point_min" value="<?=$arrSetInfo["list"][0]['shop_point_min']?>" maxlength="20">
						</div>
					</td>
				</tr>
				<tr>
					<th>그 외</th>
					<td><div class="inputs">						
							<input type="text" class="w3" style="text-align:right;" name="shop_point_max" value="<?=$arrSetInfo["list"][0]['shop_point_max']?>" maxlength="20">
						</div>
					</td>
				</tr>			
			</tbody>
		</table>

		<table style="display:none;">
			<tbody class="plus_minus plus_minus_devel">
				<tr>
					<th>구매 확정 시</th>
					<td><div class="inputs">
							<label class="radio"><input type="radio" name="shop_point_yn" value="N"<?=$arrSetInfo["list"][0]['shop_point_yn']=="N"?" checked":""?>><i></i>사용 안함</label>
							<label class="radio"><input type="radio" name="shop_point_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_point_yn']=="Y"?" checked":""?>><i></i>총 상품금액의</label>
							<!--<input type="text" class="w1" style="text-align:right;" name="shop_point_default" value="<?=$arrSetInfo["list"][0]['shop_point_default']?>" maxlength="5"> <em>% 적립  <span style="color:red">(* 100이상 입력 불가)</span></em>-->
						</div>
					</td>
				</tr>
				<tr style="display:none;">
					<th>리뷰 작성 시 적립금</th>
					<td><div class="inputs">
							<label class="radio"><input type="radio" name="shop_point_review_yn" value="N"<?=$arrSetInfo["list"][0]['shop_point_review_yn']=="N"?" checked":""?>><i></i>사용 안함</label>
							<label class="radio"><input type="radio" name="shop_point_review_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_point_review_yn']=="Y"?" checked":""?>><i></i></label>
							<input type="text" class="w1" style="text-align:right;" name="shop_point_review" value="<?=$arrSetInfo["list"][0]['shop_point_review']?>" maxlength="5"> <em>자동 적립</em>
						</div>
					</td>
				</tr>
				<tr>
					<th>회원가입 시</th>
					<td><div class="inputs">
							<label class="radio"><input type="radio" name="shop_point_member_yn" value="N"<?=$arrSetInfo["list"][0]['shop_point_member_yn']=="N"?" checked":""?>><i></i>사용 안함</label>
							<label class="radio"><input type="radio" name="shop_point_member_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_point_member_yn']=="Y"?" checked":""?>><i></i></label>
							<input type="text" class="w1" style="text-align:right;" name="shop_point_member" value="<?=$arrSetInfo["list"][0]['shop_point_member']?>" maxlength="10"> <em>자동 적립</em>
						</div>
					</td>
				</tr>
				<tr>
					<th>최소 사용 제한</th>
					<td><div class="inputs">
							<label class="radio"><input type="radio" name="shop_point_min_yn" value="N"<?=$arrSetInfo["list"][0]['shop_point_min_yn']=="N"?" checked":""?>><i></i>제한 없음</label>
							<label class="radio"><input type="radio" name="shop_point_min_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_point_min_yn']=="Y"?" checked":""?>><i></i>최소</label>
							<!--<input type="text" class="w1" style="text-align:right;" name="shop_point_min" value="<?=$arrSetInfo["list"][0]['shop_point_min']?>" maxlength="10"> <em>부터 사용 가능</em>-->
						</div>
					</td>
				</tr>
				<tr>
					<th>최대 사용 제한</th>
					<td><div class="inputs">
							<label class="radio"><input type="radio" name="shop_point_max_yn" value="N"<?=$arrSetInfo["list"][0]['shop_point_max_yn']=="N"?" checked":""?>><i></i>제한 없음</label>
							<label class="radio"><input type="radio" name="shop_point_max_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_point_max_yn']=="Y"?" checked":""?>><i></i>최대</label>
							<!--<input type="text" class="w1" style="text-align:right;" name="shop_point_max" value="<?=$arrSetInfo["list"][0]['shop_point_max']?>" maxlength="10"> <em>까지 사용 가능</em>-->
						</div>
					</td>
				</tr>			
			</tbody>
		</table>
		<table style="display:none;">
			<tbody class="plus_minus plus_minus_devel">
				<tr>
					<th>배송비 설정</th>
					<td><div class="inputs">
						<em>주문금액&nbsp;</em><input type="text" class="w1" name="shop_delivery_price" value="<?=$arrSetInfo["list"][0]['shop_delivery_price']?>" style="text-align:right;"><em>&nbsp;미만 배송비&nbsp;</em>
						<input type="text" class="w1" name="shop_delivery_default" value="<?=$arrSetInfo["list"][0]['shop_delivery_default']?>" style="text-align:right;"><em>&nbsp;원</em>					
					</div></td>
				</tr>
				<tr>
					<th>도서산간 배송비</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="shop_shipout_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_shipout_yn']!="N"?" checked":""?>><i></i>사용</label>
						<input type="text" class="w1" name="shop_shipout_default" value="<?=$arrSetInfo["list"][0]['shop_shipout_default']?>"><em>&nbsp;원&nbsp;</em>
						<label class="radio" style="margin-left:30px;"><input type="radio" name="shop_shipout_yn" value="N"<?=$arrSetInfo["list"][0]['shop_shipout_yn']=="N"?" checked":""?>><i></i>사용안함</label>
					</div></td>
				</tr>
				<tr>
					<th>기본 할인</th>
					<td><div class="inputs">
						<label class="radio"><input type="radio" name="shop_sale_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_sale_yn']!="N"?" checked":""?>><i></i>사용</label>
						<input type="text" class="w1" name="shop_sale_default" value="<?=$arrSetInfo["list"][0]['shop_sale_default']?>"><em>&nbsp;%&nbsp;</em>
						<label class="radio" style="margin-left:30px;"><input type="radio" name="shop_sale_yn" value="N"<?=$arrSetInfo["list"][0]['shop_sale_yn']=="N"?" checked":""?>><i></i>사용안함</label>
					</div></td>
				</tr>
			</tbody>
		</table>		

		<div class="btns">
			<button class="btn btn_save" type="button" onclick="fnCheck()">저장</button>
		</div>
		</form>
	</div> <!-- //inbox -->

</div>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>