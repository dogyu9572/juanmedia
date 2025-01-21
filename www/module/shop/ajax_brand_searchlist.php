<?
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$catName = $_POST['searchValue'];

$subQuery .= " AND cat_name like '%".$catName."%' ";

$arrTopBrand = getCategoryList(3, "Y", "", $subQuery);		## 브랜드

SetDisConn($dblink);

$arrAlphabet = explode(",","A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z");

for($a=0;$a<count($arrAlphabet);$a++){
	for($i=0;$i<$arrTopBrand['total'];$i++){
		if(substr($arrTopBrand['list'][$i]['cat_name'],0,1)==$arrAlphabet[$a]){
			$viewFlag[$a] = true;
		}
	}
}

for($a=0;$a<count($arrAlphabet);$a++){
	if($viewFlag[$a]){
?>
	<li>
		<strong><?=$arrAlphabet[$a]?></strong>
		<?
		for($i=0;$i<$arrTopBrand['total'];$i++){
			if(substr($arrTopBrand['list'][$i]['cat_name'],0,1)==$arrAlphabet[$a]){
				echo "<a href=\"javascript:void(0);\" onclick=\"fnSearchBrand('".$arrTopBrand['list'][$i]['cat_no']."')\" class=n>".$arrTopBrand['list'][$i]['cat_name']."</a>";
			}
		}	
		?>
	</li>
<?
	}
}	?>														