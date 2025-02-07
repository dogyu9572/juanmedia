<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="Generator" content="EditPlus®">
<meta name="Author" content="">
<meta name="Keywords" content="">
<meta name="Description" content="">
<title>인쇄</title>
</head>
<body>

<div class="print_arae">
	<div class="tit">
		<span>Certificate of Completion</span>
		<strong>교육이수증</strong>
	</div>
	<div class="info_area">
		<div class="info">
			<table>
				<tbody>
					<tr>
						<th>성 &nbsp; &nbsp; &nbsp; &nbsp;명</th>
						<td>홍길동</td>
					</tr>
					<tr>
						<th>생년월일</th>
						<td>2025.01.01</td>
					</tr>
					<tr>
						<th>교 &nbsp;육 &nbsp;명</th>
						<td>캡컷으로 숏폼 영상제작(10월)</td>
					</tr>
					<tr>
						<th>교육일자</th>
						<td>2024.10.22 ~ 2024.11.05 (3일, 총 9시간)</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="cont">위 사람은 주안영상미디어센터에서 진행한 <br><span>「캡컷으로 숏폼 영상제작(10월)」</span><br>교육을 이수하였으므로 이 증서를 수여합니다.</div>
	</div>
	<div class="btm">
		<div class="box">
			<div class="date">2025년 02월 05일</div>
			<div class="copy">주안영상미디어센터</div>
			<img src="/images/img_stamp.svg" alt="">
		</div>
	</div>
</div>

<style>
@import url('//fonts.googleapis.com/earlyaccess/nanummyeongjo.css');
.print_arae {width:794px; height:1023px; padding:180px 80px; background:url('/images/bg_print.svg') no-repeat 50% 50% / contain; font-size:0;}
.print_arae .tit {text-align:center; margin-bottom:100px;}
.print_arae .tit * {display:block; font-family:'Nanum Myeongjo'; line-height:1.8;}
.print_arae .tit span {font-size:22px; color:#b1b1b1; font-weight:700; }
.print_arae .tit strong {font-size:52px; font-weight:800; letter-spacing:19.2px;}
.print_arae .info {width:440px; margin:0 auto; border-top:#ddd 1px solid; border-bottom:#ddd 1px solid; padding:20px 0;}
.print_arae .info th,
.print_arae .info td {font-size:18px; padding:10px 0; vertical-align:top; letter-spacing:-0.384px;}
.print_arae .info th {width:100px; font-weight:600;}
.print_arae .info td {width:calc(100% - 100px);}
.print_arae .cont {font-size:30px; color:#222; font-weight:500; line-height:1.6; margin:120px 0 200px; text-align:center;}
.print_arae .cont span {color:#1A75BC;}
.print_arae .btm {text-align:center; line-height:1.6; border:0; display:flex; justify-content:center; align-items:center;}
.print_arae .btm .box {position:relative;}
.print_arae .btm .box img {position:absolute; top:50%; right:-58px; width:48px; height:48px; margin-top:-24px;}
.print_arae .date {font-size:16px; color:#666;}
.print_arae .copy {font-size:24px; color:#222; font-weight:500;}
</style>

<script type="text/javascript" src="/js/jquery-3.7.1.min.js" ></script>
<script>
$(document).ready(function () {
    // 인쇄 이벤트 실행
    window.print();

    // 인쇄 후 창 닫기 처리
    $(window).on('afterprint', function () {
        window.close();
    });

    // 일부 브라우저에서 'afterprint' 이벤트가 동작하지 않을 경우 대체
    setTimeout(function () {
        if (!window.closed) {
            window.close();
        }
    }, 500); // 인쇄가 완료될 시간을 대략적으로 기다림
});
</script>

</body>
</html>
