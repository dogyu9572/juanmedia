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

인쇄 될 내용


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
