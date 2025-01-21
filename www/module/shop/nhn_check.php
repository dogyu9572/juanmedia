<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

if($_REQUEST['gb'] == "direct") { //상품정보에서 바로구매시

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	$tp = "1";
}else{
	$tp = "2";
}	
$arrList = getPreOrderList($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$tp);

//DB해제
SetDisConn($dblink);



//item data를 생성한다. 
class ItemStack { 
	var $id; 
	var $mapid; 
	var $name; 
	var $tprice; 
	var $uprice; 
	var $option; 
	var $count; 
	
	//option이 여러 종류라면, 선택된 옵션을 슬래시(/)로 구분해서 표시하는 것을 권장한다. 
	function ItemStack($_id, $_mapid, $_name, $_tprice, $_uprice, $_option, $_count) { 
		$this->id = $_id; 
		$this->mapid = $_mapid; 
		$this->name = $_name; 
		$this->tprice = $_tprice; 
		$this->uprice = $_uprice; 
		$this->option = $_option; 
		$this->count = $_count; 
	} 
	
	function makeQueryString() { 
		$ret .= 'ITEM_ID=' . urlencode($this->id); 
		$ret .= '&EC_MALL_PID=' . urlencode($this->mapid); 
		$ret .= '&ITEM_NAME=' . urlencode($this->name); 
		$ret .= '&ITEM_COUNT=' . $this->count; 
		$ret .= '&ITEM_OPTION=' . urlencode($this->option); 
		$ret .= '&ITEM_TPRICE=' . $this->tprice; 
		$ret .= '&ITEM_UPRICE=' . $this->uprice; return $ret; 
	} 
};

$shopId = 'minimono'; 
$certiKey = '69408115-F93C-4BC9-8B92-CE40A2CE6B0C'; 

if($arrList["total"]>0){
for($i=0;$i<$arrList["total"];$i++){
	$arrOpt1[$i] = explode("|",$arrList["list"][$i]['opt_1']);
	$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
	$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
	$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
	$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
	$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);
	$arrOptRel2[$i] = explode("|",$arrList["list"][$i]['opt_rel_2']);
	
	$price[$i] = $arrList["list"][$i]['price'];
	//$point[$i] = $arrList["list"][$i]['point'];
	
	$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'] + $arrOptRel1[$i]['1'] + $arrOptRel2[$i]['1'];
	
	$totalPrice += ($price[$i]*$arrList["list"][$i]['qty'])+($optionPrice * $arrList["list"][$i]['qty']);
}}

if($totalPrice < $_SITE["SHOP"]["SHIP"]["FREE_PRICE"]){
	$shippingPrice = $_SITE["SHOP"]["SHIP"]["SHIP_PRICE"];
	$shippingType = 'PAYED'; 
}else{
	$shippingPrice = 0;
	$shippingType = 'FREE'; 
}

$backUrl = "http://www.minimonorail.co.kr/shop.php?goPage=GoodDetail&g_code=".$arrList["list"][0]['g_code']; 

$queryString = 'SHOP_ID='.urlencode($shopId); 
$queryString .= '&CERTI_KEY='.urlencode($certiKey); 
$queryString .= '&SHIPPING_TYPE='.$shippingType; 
$queryString .= '&SHIPPING_PRICE='.$shippingPrice; 
$queryString .= '&RESERVE1=&RESERVE2=&RESERVE3=&RESERVE4=&RESERVE5='; 
$queryString .= '&BACK_URL='.urlencode($backUrl); 
$queryString .= '&SA_CLICK_ID='.$_COOKIE["NVADID"]; //CTS 
// CPA 스크립트 가이드 설치 업체는 해당 값 전달 
$queryString .= '&CPA_INFLOW_CODE='.urlencode($_COOKIE["CPAValidator"]); 
$queryString .= '&NAVER_INFLOW_CODE='.urlencode($_COOKIE["NA_CO"]); 
$totalMoney = 0; 

//DB와 장바구니에서 상품 정보를 얻어 온다. 
if($arrList["total"]>0){
for($i=0;$i<$arrList["total"];$i++){
	$arrOpt1[$i] = explode("|",$arrList["list"][$i]['opt_1']);
	$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
	$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
	$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
	$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
	$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);

	$price[$i] = $arrList["list"][$i]['price'];
	$point[$i] = $arrList["list"][$i]['point'];
		
	$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'] + $arrOptRel1[$i]['1'] + $arrOptRel2[$i]['1'];

	if($arrOpt1[$i]['0']) {
		$opt[$i] .= $arrOpt1[$i]['0']."/";
	}
	if($arrOpt2[$i]['0']) {
		$opt[$i] .= $arrOpt2[$i]['0']."/";
	}
	if($arrOpt3[$i]['0']) {
		$opt[$i] .= $arrOpt3[$i]['0']."/";
	}
	if($arrOpt4[$i]['0']) {
		$opt[$i] .= $arrOpt4[$i]['0']."/";
	}
	if($arrOpt5[$i]['0']) {
		$opt[$i] .= $arrOpt5[$i]['0']."/";
	}
	if($arrOptRel1[$i]['0']) {
		$opt[$i] .= $arrOptRel1[$i]['0']."/";
	}

	$id = $arrList["list"][$i]['idx']; 
	$mapid = $arrList["list"][$i]['g_code']; 
	$name = stripslashes($arrList["list"][$i]['g_name']); 
	$uprice = $price[$i]+$optionPrice; 
	$count = $arrList["list"][$i]['qty']; 
	$tprice = $uprice * $count; 
	$option = substr($opt[$i],0,-1); 
	$item = new ItemStack($id, $mapid, $name, $tprice, $uprice, $option, $count); 
	$totalMoney += $tprice; 
	$queryString .= '&'.$item->makeQueryString(); 
}}

$totalPrice = (int)$totalMoney + (int)$shippingPrice; 
$queryString .= '&TOTAL_PRICE='.$totalPrice;

//echo($queryString."<br>\n"); 

$req_addr = 'ssl://checkout.naver.com'; 
$req_url = 'POST /customer/api/order.nhn HTTP/1.1'; // utf-8 
// $req_url = 'POST /customer/api/CP949/order.nhn HTTP/1.1'; // euc-kr 
$req_host = 'checkout.naver.com'; 
$req_port = 443; 
$nc_sock = @fsockopen($req_addr, $req_port, $errno, $errstr); 
if ($nc_sock) { 
	fwrite($nc_sock, $req_url."\r\n" ); 
	fwrite($nc_sock, "Host: ".$req_host.":".$req_port."\r\n" ); 
	fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n"); 
	//fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=CP949\r\n"); 
	fwrite($nc_sock, "Content-length: ".strlen($queryString)."\r\n"); 
	fwrite($nc_sock, "Accept: */*\r\n"); 
	fwrite($nc_sock, "\r\n"); 
	fwrite($nc_sock, $queryString."\r\n"); 
	fwrite($nc_sock, "\r\n"); 
	
	// get header
	while(!feof($nc_sock)){ 
		$header=fgets($nc_sock,4096); 
		if($header=="\r\n"){ 
			break; 
		} else { 
			$headers .= $header; 
		} 
	}
	
	// get body 
	while(!feof($nc_sock)){ 
		$bodys.=fgets($nc_sock,4096); 
	} 
	
	fclose($nc_sock); 
	
	$resultCode = substr($headers,9,3); 
	
	if ($resultCode == 200) { 
		// success 
		$orderId = $bodys; 
	} else { 
		// fail 
		echo $bodys; 
	} 

} else { 

	echo "$errstr ($errno)<br>\n"; 
	exit(-1); 
	//에러처리 
}

//리턴받은 order_id로 주문서 page를 호출한다. 
//echo ($orderId."<br>\n"); 

$orderUrl = "https://checkout.naver.com/customer/order.nhn"; 
?>

<html>
<body>
<form name="frm" method="get" action="<?=$orderUrl?>">
<input type="hidden" name="ORDER_ID" value="<?=$orderId?>">
<input type="hidden" name="SHOP_ID" value="<?=$shopId?>">
<input type="hidden" name="TOTAL_PRICE" value="<?=$totalPrice?>">
</form>
</body>
<script>
<? if ($resultCode == 200) { ?>
document.frm.target = "_top"; 
document.frm.submit(); 
<? } ?> 
</script>
</html>

<?} else if($_REQUEST['gb'] == "cart") { //장바구니에서 구입

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

//회원의 경우 회원아이디로 로그인 전이라면 세션 아이디로
if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){
	$tp = "1";
}else{
	$tp = "2";
}
	
$arrList = getCartList($_SESSION[$_SITE["DOMAIN"]]["SESSIONID"], $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"],$tp);

//DB해제
SetDisConn($dblink);



//item data를 생성한다. 
class ItemStack { 
	var $id; 
	var $mapid; 
	var $name; 
	var $tprice; 
	var $uprice; 
	var $option; 
	var $count; 
	
	//option이 여러 종류라면, 선택된 옵션을 슬래시(/)로 구분해서 표시하는 것을 권장한다. 
	function ItemStack($_id, $_mapid, $_name, $_tprice, $_uprice, $_option, $_count) { 
		$this->id = $_id; 
		$this->mapid = $_mapid; 
		$this->name = $_name; 
		$this->tprice = $_tprice; 
		$this->uprice = $_uprice; 
		$this->option = $_option; 
		$this->count = $_count; 
	} 
	
	function makeQueryString() { 
		$ret .= 'ITEM_ID=' . urlencode($this->id); 
		$ret .= '&EC_MALL_PID=' . urlencode($this->mapid); 
		$ret .= '&ITEM_NAME=' . urlencode($this->name); 
		$ret .= '&ITEM_COUNT=' . $this->count; 
		$ret .= '&ITEM_OPTION=' . urlencode($this->option); 
		$ret .= '&ITEM_TPRICE=' . $this->tprice; 
		$ret .= '&ITEM_UPRICE=' . $this->uprice; return $ret; 
	} 
};

$shopId = 'minimono'; 
$certiKey = '69408115-F93C-4BC9-8B92-CE40A2CE6B0C'; 

if($arrList["total"]>0){
for($i=0;$i<$arrList["total"];$i++){
	$arrOpt1[$i] = explode("|",$arrList["list"][$i]['opt_1']);
	$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
	$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
	$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
	$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
	$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);
	$arrOptRel2[$i] = explode("|",$arrList["list"][$i]['opt_rel_2']);

	$price[$i] = $arrList["list"][$i]['price'];
	$point[$i] = $arrList["list"][$i]['point'];
	
	$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'] + $arrOptRel1[$i]['1'] + $arrOptRel2[$i]['1'];
	
	$totalPrice += ($price[$i]*$arrList["list"][$i]['qty'])+($optionPrice * $arrList["list"][$i]['qty']);
}}

if($totalPrice < $_SITE["SHOP"]["SHIP"]["FREE_PRICE"]){
	$shippingPrice = $_SITE["SHOP"]["SHIP"]["SHIP_PRICE"];
	$shippingType = 'PAYED'; 
}else{
	$shippingPrice = 0;
	$shippingType = 'FREE'; 
}


$backUrl = "http://www.minimonorail.co.kr/shop.php?goPage=Cart"; 

$queryString = 'SHOP_ID='.urlencode($shopId); 
$queryString .= '&CERTI_KEY='.urlencode($certiKey); 
$queryString .= '&SHIPPING_TYPE='.$shippingType; 
$queryString .= '&SHIPPING_PRICE='.$shippingPrice; 
$queryString .= '&RESERVE1=&RESERVE2=&RESERVE3=&RESERVE4=&RESERVE5='; 
$queryString .= '&BACK_URL='.urlencode($backUrl); 
$queryString .= '&SA_CLICK_ID='.$_COOKIE["NVADID"]; //CTS 
// CPA 스크립트 가이드 설치 업체는 해당 값 전달 
$queryString .= '&CPA_INFLOW_CODE='.urlencode($_COOKIE["CPAValidator"]); 
$queryString .= '&NAVER_INFLOW_CODE='.urlencode($_COOKIE["NA_CO"]); 
$totalMoney = 0; 

//DB와 장바구니에서 상품 정보를 얻어 온다. 
if($arrList["total"]>0){
for($i=0;$i<$arrList["total"];$i++){
	$arrOpt1[$i] = explode("|",$arrList["list"][$i]['opt_1']);
	$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
	$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
	$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
	$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
	$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);

	$price[$i] = $arrList["list"][$i]['price'];
	$point[$i] = $arrList["list"][$i]['point'];
	
	$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'] + $arrOptRel1[$i]['1'] + $arrOptRel2[$i]['1'];

	if($arrOpt1[$i]['0']) {
		$opt[$i] .= $arrOpt1[$i]['0']."/";
	}
	if($arrOpt2[$i]['0']) {
		$opt[$i] .= $arrOpt2[$i]['0']."/";
	}
	if($arrOpt3[$i]['0']) {
		$opt[$i] .= $arrOpt3[$i]['0']."/";
	}
	if($arrOpt4[$i]['0']) {
		$opt[$i] .= $arrOpt4[$i]['0']."/";
	}
	if($arrOpt5[$i]['0']) {
		$opt[$i] .= $arrOpt5[$i]['0']."/";
	}
	if($arrOptRel1[$i]['0']) {
		$opt[$i] .= $arrOptRel1[$i]['0']."/";
	}

	$id = $arrList["list"][$i]['idx']; 
	$mapid = $arrList["list"][$i]['g_code']; 
	$name = stripslashes($arrList["list"][$i]['g_name']); 
	$uprice = $price[$i]+$optionPrice; 
	$count = $arrList["list"][$i]['qty']; 
	$tprice = $uprice * $count; 
	$option = substr($opt[$i],0,-1); 
	$item = new ItemStack($id, $mapid, $name, $tprice, $uprice, $option, $count); 
	$totalMoney += $tprice; 
	$queryString .= '&'.$item->makeQueryString(); 
}}

$totalPrice = (int)$totalMoney + (int)$shippingPrice; 
$queryString .= '&TOTAL_PRICE='.$totalPrice;

//echo($queryString."<br>\n"); 

$req_addr = 'ssl://checkout.naver.com'; 
$req_url = 'POST /customer/api/order.nhn HTTP/1.1'; // utf-8 
// $req_url = 'POST /customer/api/CP949/order.nhn HTTP/1.1'; // euc-kr 
$req_host = 'checkout.naver.com'; 
$req_port = 443; 
$nc_sock = @fsockopen($req_addr, $req_port, $errno, $errstr); 
if ($nc_sock) { 
	fwrite($nc_sock, $req_url."\r\n" ); 
	fwrite($nc_sock, "Host: ".$req_host.":".$req_port."\r\n" ); 
	fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n"); 
	//fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=CP949\r\n"); 
	fwrite($nc_sock, "Content-length: ".strlen($queryString)."\r\n"); 
	fwrite($nc_sock, "Accept: */*\r\n"); 
	fwrite($nc_sock, "\r\n"); 
	fwrite($nc_sock, $queryString."\r\n"); 
	fwrite($nc_sock, "\r\n"); 
	
	// get header
	while(!feof($nc_sock)){ 
		$header=fgets($nc_sock,4096); 
		if($header=="\r\n"){ 
			break; 
		} else { 
			$headers .= $header; 
		} 
	}
	
	// get body 
	while(!feof($nc_sock)){ 
		$bodys.=fgets($nc_sock,4096); 
	} 
	
	fclose($nc_sock); 
	
	$resultCode = substr($headers,9,3); 
	
	if ($resultCode == 200) { 
		// success 
		$orderId = $bodys; 
	} else { 
		// fail 
		echo $bodys; 
	} 

} else { 

	echo "$errstr ($errno)<br>\n"; 
	exit(-1); 
	//에러처리 
}

//리턴받은 order_id로 주문서 page를 호출한다. 
//echo ($orderId."<br>\n"); 

$orderUrl = "https://checkout.naver.com/customer/order.nhn"; 
?>

<html>
<body>
<form name="frm" method="get" action="<?=$orderUrl?>">
<input type="hidden" name="ORDER_ID" value="<?=$orderId?>">
<input type="hidden" name="SHOP_ID" value="<?=$shopId?>">
<input type="hidden" name="TOTAL_PRICE" value="<?=$totalPrice?>">
</form>
</body>
<script>
<? if ($resultCode == 200) { ?>
document.frm.target = "_top"; 
document.frm.submit(); 
<? } ?> 
</script>
</html>

<?} else if($_REQUEST['gb'] == "wish") { //찜


//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrInfo = getGoodInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['idx']));

//DB해제
SetDisConn($dblink);


//item data를 생성한다. 
class ItemStack { 
	var $id; 
	var $mapid;
	var $name; 
	var $uprice; 
	var $image; 
	var $thumb; 
	var $url;
	
	function ItemStack($_id, $_mapid, $_name, $_uprice, $_image, $_thumb, $_url) { 
		$this->id = $_id; 
		$this->mapid = $_mapid; 
		$this->name = $_name; 
		$this->uprice = $_uprice; 
		$this->image = $_image; 
		$this->thumb = $_thumb; 
		$this->url = $_url; 
	} 
	
	function makeQueryString() { 
		$ret .= 'ITEM_ID=' . urlencode($this->id); 
		$ret .= '&EC_MALL_PID=' . urlencode($this->mapid); 
		$ret .= '&ITEM_NAME=' . urlencode($this->name); 
		$ret .= '&ITEM_UPRICE=' . $this->uprice; 
		$ret .= '&ITEM_IMAGE=' . urlencode($this->image); 
		$ret .= '&ITEM_THUMB=' . urlencode($this->thumb); 
		$ret .= '&ITEM_URL=' . urlencode($this->url); return $ret; 
	} 
}; 

$shopId = 'minimono'; 
$certiKey = '69408115-F93C-4BC9-8B92-CE40A2CE6B0C'; 

$queryString = 'SHOP_ID='.urlencode($shopId); 
$queryString .= '&CERTI_KEY='.urlencode($certiKey); 
$queryString .= '&RESERVE1=&RESERVE2=&RESERVE3=&RESERVE4=&RESERVE5='; 

//DB 에서 상품 정보를 얻어온다. 
//while(...) { 
	$uprice = $arrInfo["list"][0]['price']; 

	$uid = $arrInfo["list"][0]["idx"]; 
	$mapid = $arrInfo["list"][0]["g_code"]; 
	$name = stripslashes($arrInfo["list"][0]['g_name']); 
	
	$image = "http://minimonorail.co.kr/uploaded/shop_good/".$arrInfo["list"][0]['idx']."/".$arrInfo["list"][0]['p_image']; 
	$thumb = "http://minimonorail.co.kr/uploaded/shop_good/".$arrInfo["list"][0]['idx']."/".$arrInfo["list"][0]['image_s']; 
	$url = "http://minimonorail.co.kr/shop.php?goPage=GoodDetail&g_code=".$arrInfo["list"][0]['g_code']; 
	$item = new ItemStack($uid,  $mapid, $name, $uprice, $image, $thumb, $url); 
	$queryString .= '&'.$item->makeQueryString();
//}

//echo($queryString."<br>\n"); 

$req_addr = 'ssl://checkout.naver.com'; 
$req_url = 'POST /customer/api/wishlist.nhn HTTP/1.1'; // utf-8 
// $req_url = 'POST /customer/api/CP949/wishlist.nhn HTTP/1.1'; // euc-kr 
$req_host = 'checkout.naver.com'; 
$req_port = 443; 
$nc_sock = @fsockopen($req_addr, $req_port, $errno, $errstr);
if ($nc_sock) { 
	fwrite($nc_sock, $req_url."\r\n" ); 
	fwrite($nc_sock, "Host: ".$req_host.":".$req_port."\r\n" ); 
	fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n"); // utf-8 
	//fwrite($nc_sock, "Content-type: application/x-www-form-urlencoded; charset=CP949\r\n"); // euc-kr 
	fwrite($nc_sock, "Content-length: ".strlen($queryString)."\r\n"); 
	fwrite($nc_sock, "Accept: */*\r\n"); 
	fwrite($nc_sock, "\r\n"); 
	fwrite($nc_sock, $queryString."\r\n"); 
	fwrite($nc_sock, "\r\n"); 
	
	// get header 
	while(!feof($nc_sock)){ 
		$header=fgets($nc_sock,4096); 
		if($header=="\r\n"){
			break; 
		} else { 
			$headers .= $header; 
		} 
	}
	
	// get body 
	while(!feof($nc_sock)){ 
		$bodys.=fgets($nc_sock,4096);
	}
	
	fclose($nc_sock); 
	
	$resultCode = substr($headers,9,3);
	
	if ($resultCode == 200) { 
		// success 
		
		// 한개일경우 
		$itemId = $bodys; 
		
		// 여러개일경우 
		//$itemIds = trim($bodys); 
		//$itemIdList = split(",",$itemIds); 
	} else { 
		// fail 
		echo $bodys; 
	} 
} else { 
	echo "$errstr ($errno)<br>\n"; 
	exit(-1); 
	//에러처리 
}

//리턴받은itemId로 주문서 page를 호출한다.
//echo ($itemId."<br>\n"); 

$wishlistPopupUrl = "https://checkout.naver.com/customer/wishlistPopup.nhn"; 
?>
<html>
<body>
<form name="frm" method="get" action="<?=$wishlistPopupUrl?>"> 
<input type="hidden" name="SHOP_ID" value="<?=$shopId?>">

<!-- 한 개일 경우 --> 
<input type="hidden" name="ITEM_ID" value="<?=$itemId?>"> 

<!-- 여러 개일 경우
<? for($i=0; $i < count($itemIdList); $i++) { ?>
<input type="hidden" name="ITEM_ID" value="<?=$itemIdList[$i]?>">
<? } ?>
--> 

</form>
</body>
<script>

<? if ($resultCode == 200) { ?> 
document.frm.target = "_top"; 
document.frm.submit(); 
<? } ?>
</script>
</html>
<?
}
?>