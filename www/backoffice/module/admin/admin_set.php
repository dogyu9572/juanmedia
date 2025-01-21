<?PHP
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/admin_top.php";
include "./menu.php";

if(!in_array("homepage_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
//	jsMsg("권한이 없습니다.");
//	jsHistory("-1");
endif;

?>
<div class="container">

	<div class="title">기본정보설정</div>
	
	<div class="inbox write_tbl mo_break_write">
		<form name="frmInfo" method="post" action="admin_evn.php">
		<input type="hidden" name="evnMode" value="setAdmin">
        <input type="hidden" name="culture_year" value="<?= date('Y') ?>">

            <div class="tit">장비정보 설정 <i>*</i></div>
            <table>
                <tr>
                    <th>최대 대여 개수</th>
                    <td>
                        <div class="inputs">
                            <select name="equ_max_rental_count">
                                <?php for ($i = 1; $i <= 20; $i++): ?>
                                    <option value="<?= $i ?>" <?= $arrSetInfo["list"][0]['equ_max_rental_count'] == $i ? 'selected' : '' ?>><?= $i ?>개</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>최대 대여 일</th>
                    <td>
                        <div class="inputs">
                            <select name="equ_max_rental_days">
                                <?php for ($i = 1; $i <= 60; $i++): ?>
                                    <option value="<?= $i ?>" <?= $arrSetInfo["list"][0]['equ_max_rental_days'] == $i ? 'selected' : '' ?>><?= $i ?>일</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th rowspan="2">예외시간</th>
                    <td>
                        <div class="inputs">
                            <em>점심&nbsp;</em>
                            <select name="equ_lunch_start_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['equ_lunch_start_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            <em>&nbsp;~&nbsp;</em>
                            <select name="equ_lunch_end_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['equ_lunch_end_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;<label class="radio"><input type="radio" name="equ_lunch_use" value="Y" <?= $arrSetInfo["list"][0]['equ_lunch_use'] == 'Y' ? 'checked' : '' ?>><i></i>Y</label>
                            <label class="radio"><input type="radio" name="equ_lunch_use" value="N" <?= $arrSetInfo["list"][0]['equ_lunch_use'] == 'N' ? 'checked' : '' ?>><i></i>N</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="inputs">
                            <em>저녁&nbsp;</em>
                            <select name="equ_dinner_start_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['equ_dinner_start_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            <em>&nbsp;~&nbsp;</em>
                            <select name="equ_dinner_end_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['equ_dinner_end_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;<label class="radio"><input type="radio" name="equ_dinner_use" value="Y" <?= $arrSetInfo["list"][0]['equ_dinner_use'] == 'Y' ? 'checked' : '' ?>><i></i>Y</label>
                            <label class="radio"><input type="radio" name="equ_dinner_use" value="N" <?= $arrSetInfo["list"][0]['equ_dinner_use'] == 'N' ? 'checked' : '' ?>><i></i>N</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th rowspan="2">대여시간/반납시간</th>
                    <td>
                        <div class="inputs">
                            <em>대여&nbsp;</em>
                            <select name="equ_rental_start_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['equ_rental_start_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            <em>&nbsp;~&nbsp;</em>
                            <select name="equ_rental_end_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['equ_rental_end_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;<label class="radio"><input type="radio" name="equ_rental_use" value="Y" <?= $arrSetInfo["list"][0]['equ_rental_use'] == 'Y' ? 'checked' : '' ?>><i></i>Y</label>
                            <label class="radio"><input type="radio" name="equ_rental_use" value="N" <?= $arrSetInfo["list"][0]['equ_rental_use'] == 'N' ? 'checked' : '' ?>><i></i>N</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="inputs">
                            <em>반납&nbsp;</em>
                            <select name="equ_return_start_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['equ_return_start_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            <em>&nbsp;~&nbsp;</em>
                            <select name="equ_return_end_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['equ_return_end_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;<label class="radio"><input type="radio" name="equ_return_use" value="Y" <?= $arrSetInfo["list"][0]['equ_return_use'] == 'Y' ? 'checked' : '' ?>><i></i>Y</label>
                            <label class="radio"><input type="radio" name="equ_return_use" value="N" <?= $arrSetInfo["list"][0]['equ_return_use'] == 'N' ? 'checked' : '' ?>><i></i>N</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>신청 가능일</th>
                    <td>
                        <div class="inputs">
                            <select name="equ_application_days">
                                <?php for ($i = 10; $i <= 90; $i += 10): ?>
                                    <option value="<?= $i ?>" <?= $arrSetInfo["list"][0]['equ_application_days'] == $i ? 'selected' : '' ?>><?= $i ?>일</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="tit">공간정보 설정 <i>*</i></div>
            <table>
                <tr>
                    <th>최대 가능 시간</th>
                    <td>
                        <div class="inputs">
                            <select name="place_max_available_hours">
                                <?php for ($i = 1; $i <= 11; $i++): ?>
                                    <option value="<?= $i ?>" <?= $arrSetInfo["list"][0]['place_max_available_hours'] == $i ? 'selected' : '' ?>><?= $i ?>시간</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th rowspan="2">예외시간</th>
                    <td>
                        <div class="inputs">
                            <em>점심&nbsp;</em>
                            <select name="place_lunch_start_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['place_lunch_start_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            <em>&nbsp;~&nbsp;</em>
                            <select name="place_lunch_end_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['place_lunch_end_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;<label class="radio"><input type="radio" name="place_lunch_use" value="Y" <?= $arrSetInfo["list"][0]['place_lunch_use'] == 'Y' ? 'checked' : '' ?>><i></i>Y</label>
                            <label class="radio"><input type="radio" name="place_lunch_use" value="N" <?= $arrSetInfo["list"][0]['place_lunch_use'] == 'N' ? 'checked' : '' ?>><i></i>N</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="inputs">
                            <em>저녁&nbsp;</em>
                            <select name="place_dinner_start_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['place_dinner_start_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            <em>&nbsp;~&nbsp;</em>
                            <select name="place_dinner_end_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['place_dinner_end_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;<label class="radio"><input type="radio" name="place_dinner_use" value="Y" <?= $arrSetInfo["list"][0]['place_dinner_use'] == 'Y' ? 'checked' : '' ?>><i></i>Y</label>
                            <label class="radio"><input type="radio" name="place_dinner_use" value="N" <?= $arrSetInfo["list"][0]['place_dinner_use'] == 'N' ? 'checked' : '' ?>><i></i>N</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th rowspan="2">대관시간/반납시간</th>
                    <td>
                        <div class="inputs">
                            <em>대관&nbsp;</em>
                            <select name="place_rental_start_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['place_rental_start_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            <em>&nbsp;~&nbsp;</em>
                            <select name="place_rental_end_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['place_rental_end_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;<label class="radio"><input type="radio" name="place_rental_use" value="Y" <?= $arrSetInfo["list"][0]['place_rental_use'] == 'Y' ? 'checked' : '' ?>><i></i>Y</label>
                            <label class="radio"><input type="radio" name="place_rental_use" value="N" <?= $arrSetInfo["list"][0]['place_rental_use'] == 'N' ? 'checked' : '' ?>><i></i>N</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="inputs">
                            <em>반납&nbsp;</em>
                            <select name="place_return_start_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['place_return_start_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            <em>&nbsp;~&nbsp;</em>
                            <select name="place_return_end_time">
                               <?php for ($i = 9; $i <= 22; $i++): ?>
                                    <option value="<?= sprintf('%02d:00', $i) ?>" <?= $arrSetInfo["list"][0]['place_return_end_time'] == sprintf('%02d:00', $i) ? 'selected' : '' ?>><?= sprintf('%02d:00', $i) ?></option>
                                <?php endfor; ?>
                            </select>
                            &nbsp;<label class="radio"><input type="radio" name="place_return_use" value="Y" <?= $arrSetInfo["list"][0]['place_return_use'] == 'Y' ? 'checked' : '' ?>><i></i>Y</label>
                            <label class="radio"><input type="radio" name="place_return_use" value="N" <?= $arrSetInfo["list"][0]['place_return_use'] == 'N' ? 'checked' : '' ?>><i></i>N</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>신청 가능일</th>
                    <td>
                        <div class="inputs">
                            <select name="place_application_days">
                                <?php for ($i = 10; $i <= 90; $i += 10): ?>
                                    <option value="<?= $i ?>" <?= $arrSetInfo["list"][0]['place_application_days'] == $i ? 'selected' : '' ?>><?= $i ?>일</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>
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

		<div class="tit" style="display:none;">적립금 설정 <i>*</i></div>
		<table style="display:none;">
			<tbody class="plus_minus plus_minus_devel">
				<tr>
					<th>구매 확정 시</th>
					<td><div class="inputs">
							<label class="radio"><input type="radio" name="shop_point_yn" value="N"<?=$arrSetInfo["list"][0]['shop_point_yn']=="N"?" checked":""?>><i></i>사용 안함</label>
							<label class="radio"><input type="radio" name="shop_point_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_point_yn']=="Y"?" checked":""?>><i></i>총 상품금액의</label>
							<input type="text" class="w1" style="text-align:right;" name="shop_point_default" value="<?=$arrSetInfo["list"][0]['shop_point_default']?>" maxlength="5"> <em>% 적립  <span style="color:red">(* 100이상 입력 불가)</span></em>
						</div>
					</td>
				</tr>
				<tr>
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
							<input type="text" class="w1" style="text-align:right;" name="shop_point_min" value="<?=$arrSetInfo["list"][0]['shop_point_min']?>" maxlength="10"> <em>부터 사용 가능</em>
						</div>
					</td>
				</tr>
				<tr>
					<th>최대 사용 제한</th>
					<td><div class="inputs">
							<label class="radio"><input type="radio" name="shop_point_max_yn" value="N"<?=$arrSetInfo["list"][0]['shop_point_max_yn']=="N"?" checked":""?>><i></i>제한 없음</label>
							<label class="radio"><input type="radio" name="shop_point_max_yn" value="Y"<?=$arrSetInfo["list"][0]['shop_point_max_yn']=="Y"?" checked":""?>><i></i>최대</label>
							<input type="text" class="w1" style="text-align:right;" name="shop_point_max" value="<?=$arrSetInfo["list"][0]['shop_point_max']?>" maxlength="10"> <em>까지 사용 가능</em>
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
			<button class="btn btn_save" type="submit">저장</button>
		</div>
		</form>
	</div> <!-- //inbox -->

</div>
<?php
######################################################## 디자인 ED
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/footer.php";
?>