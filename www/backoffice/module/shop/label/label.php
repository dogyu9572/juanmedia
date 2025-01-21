<?
session_start();
header("Content-Type: text/html; charset=utf-8");
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$subQuery = " AND idx in (".$_REQUEST['idxs'].") ";
$arrList = getFreeView("tbl_shop_good", $subQuery, $col="*", $scale=0, $offset=0, $orderBy="");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Document</title>
<link rel="stylesheet" href="style.css" />
<script  src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
<!--
$( document ).ready(function() {
	window.print();
});	
//-->
</script>
</head>
<body>
<?
for($i=0;$i<$arrList['total'];$i++){
	if(strpos($arrList['list'][$i]['cat_code'],"/5/")){	############### 악세사리
?>
<div class="label acc">
	<div class="left">
		<div>
			<span>상품코드 : </span>
			<span><?=$arrList['list'][$i]['g_code']?></span>
		</div>
		<div>
			<span>상품명 : </span>
			<span><?=$arrList['list'][$i]['g_name']?></span>
		</div>
		<div class="price">
			<span>판매가 : </span>
			<span><?=number_format($arrList['list'][$i]['price'])?></span>
		</div>
	</div>
	<div class="qr">
		<img src="/_qrcode/qrcode_image.php?txt=<?=$arrList['list'][$i]['idx']?>" />
	</div>
</div>
<?	}else{												############### 일반	?>
<div class="label page">
	<div class="left">
		<div>
			<span>상품코드 : </span>
			<span><?=$arrList['list'][$i]['g_code']?></span>
		</div>
		<div>
			<span>상품명 : </span>
			<span><?=$arrList['list'][$i]['g_name']?></span>
		</div>
		<div class="price">
			<span>판매가 : </span>
			<span><?=number_format($arrList['list'][$i]['price'])?></span>
		</div>
	</div>
	<div class="qr">
		<img src="/_qrcode/qrcode_image.php?txt=<?=$arrList['list'][$i]['idx']?>" />
	</div>
</div>
<?
	}
}
?>
</body>
</html>