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

<div class="img_wrap">
	<button type="button" class="btn_trans">이미지로 저장</button>
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
	                    <td><?= htmlspecialchars($_GET['name']) ?></td>
	                </tr>
	                <tr>
	                    <th>생년월일</th>
	                    <td><?= htmlspecialchars($_GET['birthdate']) ?></td>
	                </tr>
	                <tr>
	                    <th>교 &nbsp;육 &nbsp;명</th>
	                    <td><?= htmlspecialchars($_GET['subject']) ?></td>
	                </tr>
	                <tr>
	                    <th>교육일자</th>
	                    <td><?= htmlspecialchars($_GET['e_start_date']) ?> ~ <?= htmlspecialchars($_GET['e_end_date']) ?> (<?= htmlspecialchars($_GET['total_days']) ?>일, 총 <?= htmlspecialchars($_GET['total_hours']) ?>시간)</td>
	                </tr>
	                </tbody>
	            </table>
	        </div>
	        <div class="cont">위 사람은 주안영상미디어센터에서 진행한 <br><span>「<?= htmlspecialchars($_GET['subject']) ?>」</span><br>교육을 이수하였으므로 이 증서를 수여합니다.</div>
	    </div>
	    <div class="btm">
	        <div class="box">
	            <div class="date"><?= htmlspecialchars($_GET['wdate']) ?></div>
	            <div class="copy">주안영상미디어센터</div>
	            <img src="/images/img_stamp.svg" alt="">
	        </div>
	    </div>
	</div>
</div>

<style>
    @import url('//fonts.googleapis.com/earlyaccess/nanummyeongjo.css');
	.img_wrap {position:relative;}
	.img_wrap .btn_trans {display:block; font-size:4vw; color:#000; font-weight:500; padding:3vw; width:100%; border:#ddd 1px solid; margin:0 auto 3vw; border-radius:3vw;}
    .print_arae {width:794px; height:1023px; padding:180px 80px; background:url('/images/bg_print.svg') no-repeat 50% 50% / contain; font-size:0; pointer-events:none; user-select:none;}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    $('.btn_trans').on('click', function () {
        html2canvas($('.print_arae')[0], {
            scale: 2, // 고해상도 출력을 위해 스케일 조정
            useCORS: true // 외부 이미지 로딩 가능하도록 설정
        }).then(canvas => {
            let imgData = canvas.toDataURL("image/png"); // PNG 형식으로 변환
            let link = document.createElement('a');
            link.href = imgData;
            link.download = 'certificate.png'; // 저장될 파일명 지정
            link.click();
        });
    });
</script>

</body>
</html>