<?php
	$year = $_POST["year"];
	$month = $_POST["month"];

	$date = $year."-".$month."-01"; // 현재 날짜
	$time = strtotime($date); // 현재 날짜의 타임스탬프
	$start_week = date('w', $time); // 1. 시작 요일
	$total_day = date('t', $time); // 2. 현재 달의 총 날짜
	$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차

	$prev_date = date("Y-m",strtotime($date." -1 months"));
	$arrPrev = explode("-",$prev_date);

	$next_date = date("Y-m",strtotime($date." +1 months"));
	$arrNext = explode("-",$next_date);
?>
<div class="month_tit"><?=$year?>.<?=$month?>
	<div class="buttons">
		<button class="prev" type="button" onclick="getCalender('<?=$arrPrev[0]?>','<?=$arrPrev[1]?>')"></button>
		<button class="next" type="button" onclick="getCalender('<?=$arrNext[0]?>','<?=$arrNext[1]?>')"></button>
	</div>
</div>
<div class="tbl_month">
	<table>
		<thead>
			<tr>
				<th>일</th>
				<th>월</th>
				<th>화</th>
				<th>수</th>
				<th>목</th>
				<th>금</th>
				<th>토</th>
			</tr>
		</thead>
		<tbody>
			<?php for ($n = 1, $i = 0; $i < $total_week; $i++){ ?> 
				<tr> 
					<?php for ($k = 0; $k < 7; $k++){ ?> 
						<?php 
							if ( ($n > 1 || $k >= $start_week) && ($total_day >= $n) ){ 
								$view_n = $n < 10?"0".$n:$n;
								if(strtotime($year."-".$month."-".$view_n) - strtotime(date("Y-m-d")) > -1){
									$class = "";
									$onclick= "onclick=\"setCalender(this.value)\"";
								}else{
									$class = "class='before'";
									$onclick= "onclick=\"alert('과거의 날짜는 희망날짜로 정할 수 없습니다.')\"";
								}
						?>
							<td <?=$class?>> 
								<label class="month"><input type="radio" name="calender_date" value="<?php echo $year."-".$month."-".$view_n ?>" <?=$onclick?>><span><?php echo $n++ ?></span></label>
							</td> 
						<?php }else{ ?>
							<td> 
							</td>
						<?php } ?>
					<?php } ?> 
				</tr> 
			<?php } ?>
		</tbody>
	</table>
</div>
<?php
	$date = date("Y-m-d",strtotime($date." +1 months "));
	$time = strtotime($date); // 현재 날짜의 타임스탬프
	$start_week = date('w', $time); // 1. 시작 요일
	$total_day = date('t', $time); // 2. 현재 달의 총 날짜
	$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차

	$year = date("Y",strtotime($date));
	$month = date("m",strtotime($date));
?>
<div class="month_tit"><?=$year?>.<?=$month?>
</div>
<div class="tbl_month">
	<table>
		<thead>
			<tr>
				<th>일</th>
				<th>월</th>
				<th>화</th>
				<th>수</th>
				<th>목</th>
				<th>금</th>
				<th>토</th>
			</tr>
		</thead>
		<tbody>
			<?php for ($n = 1, $i = 0; $i < $total_week; $i++){ ?> 
				<tr> 
					<?php for ($k = 0; $k < 7; $k++){ ?> 
						<?php 
							if ( ($n > 1 || $k >= $start_week) && ($total_day >= $n) ){ 
								$view_n = $n < 10?"0".$n:$n;
								if(strtotime($year."-".$month."-".$view_n) - strtotime(date("Y-m-d")) > -1){
									$class = "";
									$onclick= "onclick=\"setCalender(this.value)\"";
								}else{
									$class = "class='before'";
									$onclick= "onclick=\"alert('과거의 날짜는 희망날짜로 정할 수 없습니다.')\"";
								}
						?>
							<td <?=$class?>> 
								<label class="month"><input type="radio" name="calender_date" value="<?php echo $year."-".$month."-".$view_n ?>" <?=$onclick?>><span><?php echo $n++ ?></span></label>
							</td>
						<?php }else{ ?>
							<td> 
							</td>
						<?php } ?>
					<?php } ?> 
				</tr> 
			<?php } ?>
		</tbody>
	</table>
</div>
<?php
	$date = date("Y-m-d",strtotime($date." +1 months "));
	$time = strtotime($date); // 현재 날짜의 타임스탬프
	$start_week = date('w', $time); // 1. 시작 요일
	$total_day = date('t', $time); // 2. 현재 달의 총 날짜
	$total_week = ceil(($total_day + $start_week) / 7);  // 3. 현재 달의 총 주차

	$year = date("Y",strtotime($date));
	$month = date("m",strtotime($date));
?>
<div class="month_tit"><?=$year?>.<?=$month?>
</div>
<div class="tbl_month">
	<table>
		<thead>
			<tr>
				<th>일</th>
				<th>월</th>
				<th>화</th>
				<th>수</th>
				<th>목</th>
				<th>금</th>
				<th>토</th>
			</tr>
		</thead>
		<tbody>
			<?php for ($n = 1, $i = 0; $i < $total_week; $i++){ ?> 
				<tr> 
					<?php for ($k = 0; $k < 7; $k++){ ?>
						<?php 
								if ( ($n > 1 || $k >= $start_week) && ($total_day >= $n) ){ 
									$view_n = $n < 10?"0".$n:$n;
									if(strtotime($year."-".$month."-".$view_n) - strtotime(date("Y-m-d")) > -1){
										$class = "";
										$onclick= "onclick=\"setCalender(this.value)\"";
									}else{
										$class = "class='before'";
										$onclick= "onclick=\"alert('과거의 날짜는 희망날짜로 정할 수 없습니다.')\"";
									}
							?>
						<td <?=$class?>>
							<label class="month"><input type="radio" name="calender_date" value="<?php echo $year."-".$month."-".$view_n ?>" <?=$onclick?>><span><?php echo $n++ ?></span></label>
						</td> 
						<?php }else{ ?>
							<td> 
							</td>
						<?php } ?>
					<?php } ?> 
				</tr> 
			<?php } ?>
		</tbody>
	</table>
</div>
<script>
	$(document).ready(function(){
		let schedule_date = $("#schedule_date").val();
		$("input[name='calender_date'][value='"+schedule_date+"']").prop("checked",true);
	});
</script>