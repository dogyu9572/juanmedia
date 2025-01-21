<html>
<head>
    <title></title>
</head>    
<body>
<?php
include_once $_SERVER ['DOCUMENT_ROOT']."/_qrcode/qrlib.php";
ob_start("colback");
##############################################
$txt = "http://ellapg.hk-test.co.kr/shop/shop_view.php?idx=".str_replace("||","&",$_GET['txt']);

$sOrignText=$txt;
##############################################
$debugLog=ob_get_contents();
ob_end_clean();
QRcode::png($sOrignText);
?>  
</body>
</html>