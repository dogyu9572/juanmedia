<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";


$arrIdx = $_REQUEST["arrIdx"];

if($arrIdx == ""){
	$arrIdx = '0';
}

$boardid = "product";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrspecification = getCategoryList_id("specification","");

$arrList = getBoardListBaseNFile($boardid, "", "arridx", $arrIdx, 0, 0, "user");

$filename = "The4thware_Product_Specification_Download"."_".date("Ymd").".xls";
/*
header( "Content-type: application/vnd.ms-excel" ); 
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename =".iconv("UTF-8","EUC-KR",$_SITE['NAME'])."_".iconv("UTF-8","EUC-KR","연수생")."_".date(m).date(d).date(h).date(i).".xls" ); 
header( "Content-Description: PHP4 Generated Data" );
*/
if($_GET["test"] != "test"){
header( "Content-type: application/vnd.ms-excel; charset=UTF-8"); 
header( "Content-Description: PHP4 Generated Data" ); 
header( "Content-Disposition: attachment; filename=".$filename );
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=UTF-8\">");
}

function transposeArray($array) {
    $transposedArray = array();
    
    foreach ($array as $rowKey => $row) {
        foreach ($row as $colKey => $value) {
            $transposedArray[$colKey][$rowKey] = $value;
        }
    }
    
    return $transposedArray;
}

$arrData = array();
$arrData[0] = array();
$arrData[0][] = "<img src='".$_SITE["DOMAIN"]."/pub/images/logo.png'>";
$arrData[0][] = "제품명";
$arrData[0][] = "설명1";
$arrData[0][] = "설명2";
for($k=0;$k<$arrspecification["total"];$k++){
	$arrData[0][] = $arrspecification["list"][$k]["cat_name"];
}

for($i=0;$i<$arrList["total"];$i++){
	$arrSpecificationList = getBoardCategoryList($boardid,$arrList["list"][$i]['idx'],"specification");
	$arrData[$i+1][] = " ";
	$arrData[$i+1][] = $arrList["list"][$i]['subject'];
	$arrData[$i+1][] = $arrList["list"][$i]['etc_1'];
	$arrData[$i+1][] = $arrList["list"][$i]['etc_2'];
	for($k=0;$k<$arrspecification["total"];$k++){
		$data = '';
		for($j=0;$j<$arrSpecificationList["total"];$j++){
				$arrTmpInfo = getCategoryInfo_id('specification', $arrSpecificationList["list"][$j]["cat_no"]);
				if($arrTmpInfo["list"][0]["cat_parent_no"] == $arrspecification["list"][$k]["cat_no"]){
					$data .= $arrTmpInfo["list"][0]["cat_name"]."<br/>";
				}
			}
		$arrData[$i+1][] = $data;
	}	
}

$arrReWorkData = transposeArray($arrData);



$EXCEL_TXT = "
<table border='1'>
<tbody>";

for($i=0;$i<count($arrReWorkData);$i++){
	$is_null = true;
	for($k=1;$k<count($arrReWorkData[$i]);$k++){
		if($arrReWorkData[$i][$k] != ""){
			$is_null = false;
			break;
		}
	}
	if(!$is_null){
		$tr_style = "";
		if($i == 0){
			$tr_style = "style='height:50px;'";
		}
		$EXCEL_TXT .= "<tr ".$tr_style.">";
		for($j=0;$j<count($arrReWorkData[$i]);$j++){
			$style = '';
			if($j == 0){
				$style = "style='background-color: #9ed6c0;width:250px;'";
			}
			$EXCEL_TXT .= "<td ".$style.">".$arrReWorkData[$i][$j]."</td>";
		}
		$EXCEL_TXT .= "</tr>";
	}
}
$EXCEL_TXT .= "</tbody>";
$EXCEL_TXT .= "</table>";
echo $EXCEL_TXT;

SetDisConn($dblink);
?>