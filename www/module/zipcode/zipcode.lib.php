<?
//우편번호 가져오기
function getZipCode($dong){
	$tbl = $GLOBALS["_conf_tbl"]["zipcode"];

	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl." ";
	$sql .= "WHERE dong like '%$dong%' ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;
}

function getZipCodeGugun($tname){
	$tbl = $GLOBALS["_conf_tbl"]["zipcode"] . $tname;

	$sql  = "SELECT distinct gugun FROM ".$tbl." ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;
}

function getZipCodeRoad($tname, $gugun, $road, $building="") {
	$tbl = $GLOBALS["_conf_tbl"]["zipcode"] . "_". $tname;

	$build = explode("-", $building);

	$sql  = "SELECT * FROM ".$tbl." ";
	$sql  .= "WHERE gugun='".$gugun."' AND road='".$road."' ";
	if($build[0]) {
		$sql  .= "AND building1='".$build[0]."' ";
	}
	if($build[1]) {
		$sql  .= "AND building2='".$build[1]."' ";
	}
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;

}

function getZipCodeDong($tname, $gugun, $dong, $gibun="") {
	$tbl = $GLOBALS["_conf_tbl"]["zipcode"] . "_". $tname;

	$jibun = explode("-", $gibun);

	$sql  = "SELECT * FROM ".$tbl." ";
	$sql  .= "WHERE gugun='".$gugun."' AND (dong='".$dong."' or ri='".$dong."') ";
	if($jibun[0]) {
		$sql  .= "AND gibun1='".$jibun[0]."' ";
	}
	if($jibun[1]) {
		$sql  .= "AND gibun2='".$jibun[1]."' ";
	}
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;

}

function getZipCodeBuild($tname, $gugun, $buildingname) {
	$tbl = $GLOBALS["_conf_tbl"]["zipcode"] . "_". $tname;

	$sql  = "SELECT * FROM ".$tbl." ";
	$sql  .= "WHERE gugun='".$gugun."' AND buildingname like '%".$buildingname."%' ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}
	return $list;

}
?>