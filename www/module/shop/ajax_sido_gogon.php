<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$sido = mysqli_real_escape_string($dblink,$_POST['sido']);

$gogon = mysqli_real_escape_string($dblink,$_POST['gogon']);

$Query = "SELECT gogon FROM tbl_sido where sido = '".$sido."' GROUP BY gogon ORDER BY s_order ASC";
$arrSido = getFreeQueryR($Query);

SetDisConn($dblink);

for($i=0; $i < $arrSido['total']; $i++){
	$selected = "";
	if($gogon == $arrSido['list'][$i]['gogon']){
		$selected = "selected";
	}
	echo '<option value="'.$arrSido['list'][$i]['gogon'].'" '.$selected.'>'.$arrSido['list'][$i]['gogon'].'</option>';
}
?>