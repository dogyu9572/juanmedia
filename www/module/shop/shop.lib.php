<?
function setOrderInfoStateBank($order_no, $order_state, $tid){	## 무통장입금 자동 예약확정

	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];	//상품 주문정보 테이블
	$sql = "UPDATE ".$tbl_order_info." SET
		order_state='". $order_state ."',
		TID='". $tid ."',
		ipkum_date='".date("Y-m-d")."'
		WHERE order_no='".$order_no."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	if($rs){
		return true;
    }else{
        return false;
    }
}
function setOrderInfoStateCh($order_no, $order_state){	## 계좌이체시 자동 예약확정

	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];	//상품 주문정보 테이블
	$sql = "UPDATE ".$tbl_order_info." SET
		order_state='". $order_state ."',
		ipkum_date='".date("Y-m-d")."'
		WHERE TID='".$order_no."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	if($rs){
		return true;
    }else{
        return false;
    }
}
## 주문정보 업데이트 / 주문단계 업데이트
function stateChangeAdmin($state, $idx){
	$sql = "UPDATE tbl_shop_order_info SET order_state = '". $state ."',shipping_date='".date("Y-m-d")."'  WHERE idx in (". $idx .")";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs > 0){
		return true;
	}else{
		return false;
	}
}
## 주문정보 업데이트 / 배송장 업데이트
function getUpdateOrderShopping($order_no, $shipping_no, $shipping_date=""){
	if(strlen($shipping_date)>9){
		$subquery = ",shipping_date='". $shipping_date ."'";
	}
	$sql = "UPDATE tbl_shop_order_info SET shipping_no= '". $shipping_no ."',order_state='8' ".$subquery." WHERE order_no='". $order_no ."'";	## 배송완료처리함 2021/08/12
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs > 0){
		return true;
	}else{
		//echo $sql;
		return false;
	}
}
//상품 등록하기
function insertGood(){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];
	$tbl_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];
	$tbl_good_search = $GLOBALS["_conf_tbl"]["shop_good_search"];

	$arrInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cat_no']));

	//아이콘등록
    if ($_POST['shop_icon']) {
        for ($i=0; $i < count($_POST['shop_icon']); $i++) {
            $str_icons .= $_POST['shop_icon'][$i];
            if ($i != count($_POST['shop_icon'])-1) {
                $str_icons .= "|";
            }
        }
    }

	if(!$_POST['stock_type']){
		$_POST['stock_type'] = "1";
	}
	if(!$_POST['default_sale']){
		$_POST['default_sale'] = "N";
	}
	if($_POST['published_date']){
		$published_date = $_POST['published_date'];
	}else{
		$published_date = "0000-00-00 00:00:00";
	}
	if($_POST['close_date']){
		$close_date = "'".$_POST['close_date']." ".$_POST['closeh'].":".$_POST['closem'].":00'";
	}else{
		//	$close_date = "NULL";
		$close_date = "'2041-01-01 00:00:00'";
	}

	$comma = "";
	for($i=0;$i<count($_POST['member_choice']);$i++){
		$catNo = $_POST['member_choice'][$i];
		$member_choice	.= $comma.$_POST['member_choice'][$i];
		$member_price	.= $comma.$_POST['member_price_'.$catNo];
		$member_sale	.= $comma.$_POST['member_sale_'.$catNo];		
		$comma = "|";
	}
	$addSql = "
		member_choice		='".$member_choice."',
		member_price		='".$member_price."',
		member_sale			='".$member_sale."',
		search_keyword		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['search_keyword'])."',
		text_special		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_special'])."',
		text_safety			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_safety'])."',
		text_pairing		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_pairing'])."',
		text_store			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_store'])."',
		text_etc1			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc1'])."',		
		text_etc2			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc2'])."',	
		text_etc3			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc3'])."',	
		text_etc4			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc4'])."',	
		text_etc5			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc5'])."',
		etc_1				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',	
		etc_2				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_2'])."',	
		etc_3				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_3'])."',	
		etc_4				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',	
		etc_5				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',	
		etc_6				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',	
		etc_7				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',	
		etc_8				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',	
		etc_9				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',	
		etc_10				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',	
		etc_11				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_11'])."',	
		etc_12				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_12'])."',	
		etc_13				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_13'])."',	
		etc_14				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_14'])."',	
		etc_15				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_15'])."',	
		etc_16				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_16'])."',	
		etc_17				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_17'])."',	
		etc_18				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_18'])."',	
		etc_19				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_19'])."',	
		etc_20				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_20'])."',	
		etc_21				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_21'])."',	
		etc_22				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_22'])."',	
		etc_23				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_23'])."',	
		etc_24				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_24'])."',	
		etc_25				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_25'])."',	
		etc_26				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_26'])."',	
		etc_27				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_27'])."',	
		etc_28				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_28'])."',	
	";	


	//상품정보 테이블에 입력
	$sql = "INSERT INTO ".$tbl." set 
		cat_no='".$arrInfo["list"][0]['cat_no']."',
		cat_code='".$arrInfo["list"][0]['cat_code']."',		
		g_code='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['g_code'])."',
		g_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['g_name'])."',
		memo='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['memo'])."',
		contents='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])."',
		sort_num=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sort_num'])."',''),'0'),
		madein='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['madein'])."',
		vendor='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['vendor'])."',
		brand='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['brand'])."',
		model='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['model'])."',
		icons='".mysqli_real_escape_string($GLOBALS['dblink'], $str_icons)."',
		p_price=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], str_replace(",","",$_POST['p_price']))."',''),'0'),
		sale_price=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sale_price'])."',''),'0'),
		vip_price=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['vip_price'])."',''),'0'),
		price=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['price'])."',''),'0'),
		stock=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['stock'])."',''),'0'),
		stock_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['stock_type'])."',
		point=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['point'])."',''),'0'),
		point_unit=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['point_unit'])."',''),'F'),
		image_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['image_type'])."',
		is_show='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['is_show'])."',
		main_show=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['main_show'])."',''),'N'),
		brand_show=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['brand_show'])."',''),'N'),
		special_show=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['special_show'])."',''),'N'),
		best_show=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['best_show'])."',''),'N'),
		mokcha='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mokcha'])."',
		author_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['author_name'])."',
		author_text='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['author_text'])."',
		pages='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['pages'])."',
		pan_color='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['pan_color'])."',
		movie='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['movie'])."',
		movie_url='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['movie_url'])."',
		shipping_charge='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['shipping_charge'])."',
		percent_point='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['percent_point'])."',		
		price_txt='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['price_txt'])."',
		published_date	='".$published_date."',
		close_date		=".$close_date.",
		default_sale='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['default_sale'])."',	
		".$addSql."
		wdate=now()
	";

	//	echo $sql."<br/>";
	//	exit;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	//추가 카테고리 정보 입력
	$ext_cat_value_arr=explode("|:|",$_POST["ext_cat_hidden"]);

	for($j=0;$j<count($ext_cat_value_arr);$j++){
		$arrCatInfo = getCategoryInfo($ext_cat_value_arr[$j]);
		if($arrCatInfo["list"][0]['cat_no'] > 0){
			$sql = "INSERT INTO ".$tbl_good_cat." set 
				g_idx='".$insert_idx."',
				cat_no='".$arrCatInfo["list"][0]['cat_no']."',
				cat_code='".$arrCatInfo["list"][0]['cat_code']."'
			";
			mysqli_query($GLOBALS['dblink'], $sql);
		}
	}

	//검색 카테고리 정보 입력
	$ext_search_value_arr=explode("|:|",$_POST["ext_search_hidden"]);

	for($j=0;$j<count($ext_search_value_arr);$j++){
		$arrSearchInfo = getCategoryInfo($ext_search_value_arr[$j]);
		if($arrSearchInfo["list"][0]['cat_no'] > 0){
			$sql = "INSERT INTO ".$tbl_good_search." set 
				g_idx='".$insert_idx."',
				cat_no='".$arrSearchInfo["list"][0]['cat_no']."',
				cat_code='".$arrSearchInfo["list"][0]['cat_code']."'
			";
			mysqli_query($GLOBALS['dblink'], $sql);
		}
	}

	//선택한 카테고리 정보 입력
	$sql = "INSERT INTO ".$tbl_good_cat." set 
		g_idx='".$insert_idx."',
		cat_no='".$arrInfo["list"][0]['cat_no']."',
		cat_code='".$arrInfo["list"][0]['cat_code']."'
	";
	mysqli_query($GLOBALS['dblink'], $sql);

	//옵션입력
	for($i=0; $i < $_POST['opt_hidden_count']; $i++){
		$opt_1=mysqli_real_escape_string($GLOBALS['dblink'], $_POST["opt_subject_".$i]);
		$opt_1_value_arr=explode("|:|",$_POST["opt_hidden_value_".$i]);

		for($j=0;$j<count($opt_1_value_arr);$j++){
			$arr_opt_value = explode("|",$opt_1_value_arr[$j]);
			$sql = "INSERT INTO ".$tbl_opt." set 
				g_idx='".$insert_idx."',
				opt_1='".$opt_1."',
				opt_1_value='".mysqli_real_escape_string($GLOBALS['dblink'], $arr_opt_value[0])."',
				price='".mysqli_real_escape_string($GLOBALS['dblink'], $arr_opt_value[1])."'
			";
			mysqli_query($GLOBALS['dblink'], $sql);
		}
		
		if($i >= 5){
			break;
		}
	}


	//연계 재고관리
	if($_POST['stock_type']=="3"){
		for($i=0; $i<9; $i++){
			if($_POST["relOpt1_".$i] != ""){
				for($j=0; $j<9; $j++){
					if($_POST["relOpt2_".$j] != ""){
						$sql = "INSERT INTO ".$tbl_opt_rel." set 
							g_idx='".$insert_idx."',
							opt_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['relOptName1'])."',
							opt_1_value='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["relOpt1_".$i])."',
							opt_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['relOptName2'])."',
							opt_2_value='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["relOpt2_".$j])."',
							price='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["rel_price_".$j."_".$i])."',
							stock='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["rel_stock_".$j."_".$i])."'
						";
						mysql_query($sql);
					}
				}
			}
		}
	}

	//파일 저장 디렉토리 생성
	rmkdir($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$insert_idx, 0777);

	//이미지 파일처리
	inputGoodFiles($insert_idx, mysqli_real_escape_string($GLOBALS['dblink'], $_POST['image_type']));

	//카탈로그 파일처리
	inputCatalogFilesShop($insert_idx);

	if($total > 0){
		//echo "<img src='/backoffice/module/shop/naver_allep.php' width='0' height='0'>";  //네이버 전체 ep 새로 생성
		return $insert_idx;
	}else{
		return false;
	}

}


//상품 복사하기
function copyGood($idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];
	$tbl_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];
	$tbl_good_search = $GLOBALS["_conf_tbl"]["shop_good_search"];

	$arrInfo = getGoodInfo($idx);

	//상품정보 테이블에 입력
	$sql = "INSERT INTO ".$tbl." set 
		cat_no			='".$arrInfo["list"][0]['cat_no']."',
		cat_code		='".$arrInfo["list"][0]['cat_code']."',
		g_code			='".$arrInfo["list"][0]['g_code']."',
		g_name			='".addslashes($arrInfo["list"][0]['g_name'])."',
		rel_g_idx		='".$arrInfo["list"][0]['rel_g_idx']."',
		rel_a_idx		='".$arrInfo["list"][0]['rel_a_idx']."',
		rel_a_orderby	='".$arrInfo["list"][0]['rel_a_orderby']."',
		memo			='".addslashes($arrInfo["list"][0]['memo'])."',
		contents		='".addslashes($arrInfo["list"][0]['contents'])."',
		sort_num		='".$arrInfo["list"][0]['sort_num']."',
		seminar_orderby	='".$arrInfo["list"][0]['seminar_orderby']."',
		madein			='".addslashes($arrInfo["list"][0]['madein'])."',
		vendor			='".addslashes($arrInfo["list"][0]['vendor'])."',
		brand			='".addslashes($arrInfo["list"][0]['brand'])."',
		model			='".addslashes($arrInfo["list"][0]['model'])."',
		s_level			='".addslashes($arrInfo["list"][0]['s_level'])."',
		s_time			='".addslashes($arrInfo["list"][0]['s_time'])."',
		s_schedule		='".addslashes($arrInfo["list"][0]['s_schedule'])."',
		s_cal01			='".addslashes($arrInfo["list"][0]['s_cal01'])."',
		s_cal02			='".addslashes($arrInfo["list"][0]['s_cal02'])."',
		s_cal03			='".addslashes($arrInfo["list"][0]['s_cal03'])."',
		s_cal04			='".addslashes($arrInfo["list"][0]['s_cal04'])."',		
		s_cal05			='".addslashes($arrInfo["list"][0]['s_cal05'])."',
		s_inwon			='".addslashes($arrInfo["list"][0]['s_inwon'])."',
		s_time_radio	='".addslashes($arrInfo["list"][0]['s_time_radio'])."',
		icons			='".$arrInfo["list"][0]['icons']."',
		price_type		='".$arrInfo["list"][0]['price_type']."',
		price_txt		='".addslashes($arrInfo["list"][0]['price_txt'])."',
		p_price			='".$arrInfo["list"][0]['p_price']."',
		sale_price		='".$arrInfo["list"][0]['sale_price']."',
		default_sale	='".$arrInfo["list"][0]['default_sale']."',
		price			='".$arrInfo["list"][0]['price']."',
		vip_price		='".$arrInfo["list"][0]['vip_price']."',
		stock			='".$arrInfo["list"][0]['stock']."',
		stock_type		='4',
		point			='".$arrInfo["list"][0]['point']."',
		point_unit		='".$arrInfo["list"][0]['point_unit']."',
		image_type		='".$arrInfo["list"][0]['image_type']."',
		image_s			='".$arrInfo["list"][0]['image_s']."',
		image_m			='".$arrInfo["list"][0]['image_m']."',
		image_l			='".$arrInfo["list"][0]['image_l']."',
		etc_file_1		='".$arrInfo["list"][0]['etc_file_1']."',
		etc_file_2		='".$arrInfo["list"][0]['etc_file_2']."',
		etc_file_3		='".$arrInfo["list"][0]['etc_file_3']."',
		etc_file_4		='".$arrInfo["list"][0]['etc_file_4']."',
		etc_file_5		='".$arrInfo["list"][0]['etc_file_5']."',
		etc_file_6		='".$arrInfo["list"][0]['etc_file_6']."',
		etc_file_fn_1	='".$arrInfo["list"][0]['etc_file_fn_1']."',
		etc_file_fn_2	='".$arrInfo["list"][0]['etc_file_fn_2']."',
		etc_file_fn_3	='".$arrInfo["list"][0]['etc_file_fn_3']."',
		etc_file_fn_4	='".$arrInfo["list"][0]['etc_file_fn_4']."',
		etc_file_fn_5	='".$arrInfo["list"][0]['etc_file_fn_5']."',
		etc_file_fn_6	='".$arrInfo["list"][0]['etc_file_fn_6']."',
		p_image			='".$arrInfo["list"][0]['p_image']."',		
		is_show			='".$arrInfo["list"][0]['is_show']."',
		main_show		='".$arrInfo["list"][0]['main_show']."',
		brand_show		='".$arrInfo["list"][0]['brand_show']."',
		special_show	='".$arrInfo["list"][0]['special_show']."',
		best_show		='".$arrInfo["list"][0]['best_show']."',
		soon_yn			='".$arrInfo["list"][0]['soon_yn']."',
		mokcha			='".addslashes($arrInfo["list"][0]['mokcha'])."',
		author_name		='".addslashes($arrInfo["list"][0]['author_name'])."',
		author_text		='".addslashes($arrInfo["list"][0]['author_text'])."',
		isbn			='".addslashes($arrInfo["list"][0]['isbn'])."',
		published_date	='".$arrInfo["list"][0]['published_date']."',
		close_date		='".$arrInfo["list"][0]['close_date']."',
		published_text	='".addslashes($arrInfo["list"][0]['published_text'])."',
		pages			='".addslashes($arrInfo["list"][0]['pages'])."',
		pan_color		='".addslashes($arrInfo["list"][0]['pan_color'])."',
		cdrom			='".addslashes($arrInfo["list"][0]['cdrom'])."',
		movie			='".addslashes($arrInfo["list"][0]['movie'])."',
		movie_url		='".addslashes($arrInfo["list"][0]['movie_url'])."',
		seminar_yn		='".$arrInfo["list"][0]['seminar_yn']."',
		seminar_free	='".$arrInfo["list"][0]['seminar_free']."',
		option_yn		='".$arrInfo["list"][0]['option_yn']."',
		option_type		='".$arrInfo["list"][0]['option_type']."',
		option_cnt		='".$arrInfo["list"][0]['option_cnt']."',
		option_title	='".$arrInfo["list"][0]['option_title']."',
		option_color	='".$arrInfo["list"][0]['option_color']."',
		option_orderby	='".$arrInfo["list"][0]['option_orderby']."',
		option_play		='".$arrInfo["list"][0]['option_play']."',
		option_price	='".$arrInfo["list"][0]['option_price']."',
		option_qty		='".$arrInfo["list"][0]['option_qty']."',
		wdate			=now()
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$insert_idx = mysqli_insert_id($GLOBALS['dblink']);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	//관련상품
	$sql  = "select * from ".$tbl_good_cat." where g_idx='".$idx."' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
		$list['total'] = $total_rs;
		for($i=0; $i < $total_rs; $i++){
			$row = mysqli_fetch_assoc($rs);
			
			$sql = "INSERT INTO ".$tbl_good_cat." set 
			g_idx='".$insert_idx."',
			cat_no='".$row['cat_no']."',
			cat_code='".$row['cat_code']."'
			";	
			mysqli_query($GLOBALS['dblink'], $sql);

		}
	}

	//옵션
	$sql  = "select * from ".$tbl_opt." where g_idx='".$idx."' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
		$list['total'] = $total_rs;
		for($i=0; $i < $total_rs; $i++){
			$row = mysqli_fetch_assoc($rs);
			
			$sql = "INSERT INTO ".$tbl_opt." set 
			g_idx='".$insert_idx."',
			opt_1='".$row['opt_1']."',
			opt_1_value='".$row['opt_1_value']."',
			price='".$row['price']."'
			";	
			mysqli_query($GLOBALS['dblink'], $sql);

		}
	}

	//파일 저장 디렉토리 생성
	rmkdir($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$insert_idx, 0777);
	
	if($arrInfo["total_files"]>0){
		for($i=0; $i < $arrInfo["total_files"]; $i++){
			if(@file($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$arrInfo["files"][$i]['re_name'])) copy($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$arrInfo["files"][$i]['re_name'], $GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$insert_idx."/".$arrInfo["files"][$i]['re_name']);
			if(@file($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/s_".$arrInfo["files"][$i]['re_name'])) copy($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/s_".$arrInfo["files"][$i]['re_name'], $GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$insert_idx."/s_".$arrInfo["files"][$i]['re_name']);
			if(@file($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/m_".$arrInfo["files"][$i]['re_name'])) copy($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/m_".$arrInfo["files"][$i]['re_name'], $GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$insert_idx."/m_".$arrInfo["files"][$i]['re_name']);
			if(@file($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/l_".$arrInfo["files"][$i]['re_name'])) copy($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/l_".$arrInfo["files"][$i]['re_name'], $GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$insert_idx."/l_".$arrInfo["files"][$i]['re_name']);

				$sql = "insert into ".$GLOBALS["_conf_tbl"]["shop_good_files"]." set 
					b_idx='".$insert_idx."',
					ori_name='".$arrInfo["files"][$i]['ori_name']."',
					re_name='".$arrInfo["files"][$i]['re_name']."',
					type='".$arrInfo["files"][$i]['type']."',
					ext ='".$arrInfo["files"][$i]['ext']."',
					size='".$arrInfo["files"][$i]['size']."',
					width='".$arrInfo["files"][$i]['width']."',
					height='".$arrInfo["files"][$i]['height']."',
					wdate=now()
				";
				mysqli_query($GLOBALS['dblink'], $sql);

		}
	}

	if($total > 0){
		return $insert_idx;
	}else{
		return false;
	}

}

//상품 수정하기
function editGood($idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];
	$tbl_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];
	$tbl_good_search = $GLOBALS["_conf_tbl"]["shop_good_search"];

	$arrInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cat_no']));

	//아이콘등록
    if ($_POST['shop_icon']) {
        for ($i=0; $i < count($_POST['shop_icon']); $i++) {
            $str_icons .= $_POST['shop_icon'][$i];
            if ($i != count($_POST['shop_icon'])-1) {
                $str_icons .= "|";
            }
        }
    }

	if(!$_POST['stock_type']){
		$_POST['stock_type'] = "1";
	}
	if(!$_POST['default_sale']){
		$_POST['default_sale'] = "N";
	}
	if($_POST['published_date']){
		$published_date = $_POST['published_date'];
	}else{
		$published_date = "0000-00-00 00:00:00";
	}
	if($_POST['close_date']){
		$close_date = "'".$_POST['close_date']." ".$_POST['closeh'].":".$_POST['closem'].":00'";
	}else{
		//	$close_date = "NULL";
		$close_date = "'2041-01-01 00:00:00'";
	}
	
	$comma = "";
	for($i=0;$i<count($_POST['member_choice']);$i++){
		$catNo = $_POST['member_choice'][$i];
		$member_choice	.= $comma.$_POST['member_choice'][$i];
		$member_price	.= $comma.$_POST['member_price_'.$catNo];
		$member_sale	.= $comma.$_POST['member_sale_'.$catNo];		
		$comma = "|";
	}
	$addSql = "
		member_choice		='".$member_choice."',
		member_price		='".$member_price."',
		member_sale			='".$member_sale."',
		search_keyword		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['search_keyword'])."',
		text_special		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_special'])."',
		text_safety			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_safety'])."',
		text_pairing		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_pairing'])."',
		text_store			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_store'])."',
		text_etc1			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc1'])."',		
		text_etc2			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc2'])."',	
		text_etc3			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc3'])."',	
		text_etc4			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc4'])."',	
		text_etc5			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['text_etc5'])."',
		etc_1				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_1'])."',	
		etc_2				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_2'])."',	
		etc_3				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_3'])."',	
		etc_4				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_4'])."',	
		etc_5				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_5'])."',	
		etc_6				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_6'])."',	
		etc_7				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_7'])."',	
		etc_8				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_8'])."',	
		etc_9				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_9'])."',	
		etc_10				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_10'])."',	
		etc_11				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_11'])."',	
		etc_12				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_12'])."',	
		etc_13				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_13'])."',	
		etc_14				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_14'])."',	
		etc_15				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_15'])."',	
		etc_16				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_16'])."',	
		etc_17				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_17'])."',	
		etc_18				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_18'])."',	
		etc_19				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_19'])."',	
		etc_20				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_20'])."',	
		etc_21				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_21'])."',	
		etc_22				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_22'])."',	
		etc_23				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_23'])."',	
		etc_24				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_24'])."',	
		etc_25				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_25'])."',	
		etc_26				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_26'])."',	
		etc_27				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_27'])."',	
		etc_28				='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['etc_28'])."',	
	";	
	
	//상품정보 테이블에 입력
	$sql = "UPDATE ".$tbl." set 
		cat_no='".$arrInfo["list"][0]['cat_no']."',
		cat_code='".$arrInfo["list"][0]['cat_code']."',
		g_code='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['g_code'])."',
		g_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['g_name'])."',
		memo='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['memo'])."',
		contents='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['contents'])."',		
		sort_num=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sort_num'])."',''),'0'),
		madein='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['madein'])."',
		vendor='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['vendor'])."',
		brand='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['brand'])."',
		model='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['model'])."',
		icons='".mysqli_real_escape_string($GLOBALS['dblink'], $str_icons)."',
		p_price=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], str_replace(",","",$_POST['p_price']))."',''),'0'),
		sale_price=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['sale_price'])."',''),'0'),
		vip_price=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['vip_price'])."',''),'0'),
		price=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['price'])."',''),'0'),
		stock=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['stock'])."',''),'0'),
		stock_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['stock_type'])."',
		point=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['point'])."',''),'0'),
		point_unit=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['point_unit'])."',''),'F'),
		image_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['image_type'])."',
		is_show='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['is_show'])."',
		main_show=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['main_show'])."',''),'N'),
		brand_show=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['brand_show'])."',''),'N'),
		special_show=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['special_show'])."',''),'N'),
		best_show=IFNULL(NULLIF('".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['best_show'])."',''),'N'),		
		mokcha='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['mokcha'])."',
		author_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['author_name'])."',
		author_text='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['author_text'])."',		
		pages='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['pages'])."',
		pan_color='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['pan_color'])."',
		movie='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['movie'])."',
		movie_url='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['movie_url'])."',
		shipping_charge='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['shipping_charge'])."',
		percent_point='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['percent_point'])."',		
		price_txt='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['price_txt'])."',		
		published_date	='".$published_date."',
		close_date		=".$close_date.",
		".$addSql."
		default_sale='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['default_sale'])."'
		WHERE idx = '".$idx."'
	";
	//echo $sql;
	//exit;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//기존 카테고리 정보 삭제
	$sql = "DELETE FROM ".$tbl_good_cat." 
			WHERE g_idx='".$idx."'
		";
		mysqli_query($GLOBALS['dblink'], $sql);

	//추가 카테고리 정보 입력
	$ext_cat_value_arr=explode("|:|",$_POST["ext_cat_hidden"]);

	for($j=0;$j<count($ext_cat_value_arr);$j++){
		$arrCatInfo = getCategoryInfo($ext_cat_value_arr[$j]);
		if($arrCatInfo["list"][0]['cat_no'] > 0){
			$sql = "INSERT INTO ".$tbl_good_cat." set 
				g_idx='".$idx."',
				cat_no='".$arrCatInfo["list"][0]['cat_no']."',
				cat_code='".$arrCatInfo["list"][0]['cat_code']."'
			";
			mysqli_query($GLOBALS['dblink'], $sql);
		}
	}

	//기존 검색 정보 삭제
	$sql = "DELETE FROM ".$tbl_good_search." 
			WHERE g_idx='".$idx."'
		";
		mysqli_query($GLOBALS['dblink'], $sql);

	//추가 카테고리 정보 입력
	$ext_search_value_arr=explode("|:|",$_POST["ext_search_hidden"]);

	for($j=0;$j<count($ext_search_value_arr);$j++){
		$arrSearchInfo = getCategoryInfo($ext_search_value_arr[$j]);
		if($arrSearchInfo["list"][0]['cat_no'] > 0){
			$sql = "INSERT INTO ".$tbl_good_search." set 
				g_idx='".$idx."',
				cat_no='".$arrSearchInfo["list"][0]['cat_no']."',
				cat_code='".$arrSearchInfo["list"][0]['cat_code']."'
			";
			mysqli_query($GLOBALS['dblink'], $sql);
		}
	}

	//선택한 카테고리 정보 입력
	$sql = "INSERT INTO ".$tbl_good_cat." set 
		g_idx='".$idx."',
		cat_no='".$arrInfo["list"][0]['cat_no']."',
		cat_code='".$arrInfo["list"][0]['cat_code']."'
	";
	mysqli_query($GLOBALS['dblink'], $sql);


	//기존 옵션 삭제
	$sql = "DELETE FROM ".$tbl_opt." 
			WHERE g_idx='".$idx."'
		";
		mysqli_query($GLOBALS['dblink'], $sql);

	//옵션입력
	for($i=0; $i < $_POST['opt_hidden_count']; $i++){
		$opt_1=mysqli_real_escape_string($GLOBALS['dblink'], $_POST["opt_subject_".$i]);
		$opt_1_value_arr=explode("|:|",$_POST["opt_hidden_value_".$i]);

		for($j=0;$j<count($opt_1_value_arr);$j++){
			$arr_opt_value = explode("|",$opt_1_value_arr[$j]);
			
			if($opt_1 || $arr_opt_value[0] || $arr_opt_value[1]) {
				$sql = "INSERT INTO ".$tbl_opt." set 
					g_idx='".$idx."',
					opt_1='".$opt_1."',
					opt_1_value='".mysqli_real_escape_string($GLOBALS['dblink'], $arr_opt_value[0])."',
					price='".mysqli_real_escape_string($GLOBALS['dblink'], $arr_opt_value[1])."'
				";
				mysqli_query($GLOBALS['dblink'], $sql);
			}
		}
		
		if($i >= 5){
			break;
		}
	}

	//연계 재고관리
	if($_POST['stock_type']=="3"){
		//기존 옵션 삭제
		$sql = "DELETE FROM ".$tbl_opt_rel." 
			WHERE g_idx='".$idx."'
		";
		mysqli_query($GLOBALS['dblink'], $sql);

		for($i=0; $i<9; $i++){
			if($_POST["relOpt1_".$i] != ""){
				for($j=0; $j<9; $j++){
					if($_POST["relOpt2_".$j] != ""){
						$sql = "INSERT INTO ".$tbl_opt_rel." set 
							g_idx='".$idx."',
							opt_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['relOptName1'])."',
							opt_1_value='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["relOpt1_".$i])."',
							opt_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['relOptName2'])."',
							opt_2_value='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["relOpt2_".$j])."',
							price='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["rel_price_".$j."_".$i])."',
							stock='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["rel_stock_".$j."_".$i])."'
						";
						mysql_query($sql);
					}
				}
			}
		}
	}

	//이미지 파일처리
	delGoodFiles($idx);
	//이미지 파일처리
	inputGoodFiles($idx, mysqli_real_escape_string($GLOBALS['dblink'], $_POST['image_type']));

	//카탈로그 파일처리
	delCatalogFilesShop($idx);
	inputCatalogFilesShop($idx);

	if($rs > 0){
		return true;
	}else{
		return false;
	}


}


//상품 노출여부 수정하기
function editGoodShow($idx, $gb){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];

	//상품정보 테이블에 입력
	$sql = "UPDATE ".$tbl." set 
		is_show='".$gb."'
		WHERE idx = '".$idx."'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs > 0){
		return true;
	}else{
		return false;
	}
}

//상품 파일처리
function inputGoodFiles($idx, $image_type){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];

	//이미지파일 처리

	//현재 정보 가져오기
	$arrCurInfo = getArticleInfo($tbl, $idx);

	//대표이미지로 썸네일 만드는 방식 일경우
	if($image_type=="1"){
		for($i=0;$i<count($_FILES['photo_file']['error']);$i++){
			if ($_FILES['photo_file']['error'][$i] == 0){
				//확장자 검사후 파일이름 생성
				$filename = $_FILES['photo_file']['name'][$i];
				$attach_ext = explode(".",$filename);
				$extension = $attach_ext[sizeof($attach_ext)-1];
				$extension = strtolower($extension);		    
				$filerename = md5(mktime()) . $i . "." . $extension;
				$filesize = $_FILES['photo_file']['size'][$i];
				$filetype = $_FILES['photo_file']['type'][$i];
					
				// 파일 확장자 검사
				if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
					jsMsg("not allowed file extension");
					jsHistory("-1");
				}
				
				if (is_uploaded_file($_FILES['photo_file']['tmp_name'][$i])) {	
					move_uploaded_file ($_FILES['photo_file']['tmp_name'][$i],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$filerename);
					//썸네일 만들기
					if($filetype=="image/pjpeg" || $filetype=="image/x-png" || $filetype=="image/jpeg" || $filetype=="image/png" || $filetype=="image/gif"){
						$tmpImageSize = getimagesize($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$filerename);
						
						MakeThum($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$filerename, $GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/s_".$filerename, $GLOBALS["_SITE"]["SHOP"]["IMAGE_S_WIDTH"]);
						
						MakeThum($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$filerename, $GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/m_".$filerename, $GLOBALS["_SITE"]["SHOP"]["IMAGE_M_WIDTH"]);
						
						MakeThum($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$filerename, $GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/l_".$filerename, $GLOBALS["_SITE"]["SHOP"]["IMAGE_L_WIDTH"]);
						
					}
				}

				$sql = "insert into ".$GLOBALS["_conf_tbl"]["shop_good_files"]." set 
					b_idx='".$idx."',/* 글 번호 id*/
					ori_name='".$filename."',/*파일원본이름*/
					re_name='".$filerename."',/*md5로 변환된 파일이름*/
					type='".$filetype."',/*파일타입*/
					ext ='".$extension."',/*파일확장자*/
					size='".$filesize."',/*첨부파일 용량*/
					width='".$tmpImageSize[0]."',/*첨부파일 가로길이*/
					height='".$tmpImageSize[1]."',/*첨부파일 세로길이*/
					wdate=now()
				";
				$rsf = mysqli_query($GLOBALS['dblink'], $sql);	
				if($_POST['p_image']==$i){
					$sql = "update ".$GLOBALS["_conf_tbl"]["shop_good"]." set 
					image_s='s_".$filerename."',
					image_m='m_".$filerename."',
					image_l='l_".$filerename."',
					p_image='$filerename' 
					WHERE idx='$idx' 
					";
					mysqli_query($GLOBALS['dblink'], $sql);
					//echo $sql;
				}
			}
			//대표이미지 업데이트
			if($_POST['photo_file_name'][$i]){
				$filerename = $_POST['photo_file_name'][$i];
			}
			
		}

	//장바구니, 목록, 상세이미지를 직접 올릴경우
	}else if($image_type=="2"){
		//장바구니 이미지 등록
		if ($_FILES['photo_file_s']['error'] == 0){
			//확장자 검사후 파일이름 생성
			$filename = $_FILES['photo_file_s']['name'];
			$attach_ext = explode(".",$filename);
			$extension = $attach_ext[sizeof($attach_ext)-1];
			$extension = strtolower($extension);		    
			$s_filerename = "s_" . md5(mktime()) . "." . $extension;
			$filesize = $_FILES['photo_file']['size'];
			$filetype = $_FILES['photo_file']['type'];
				
			// 파일 확장자 검사
			if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
				jsMsg("not allowed file extension");
				jsHistory("-1");
			}
			
			if (is_uploaded_file($_FILES['photo_file_s']['tmp_name'])) {	
				move_uploaded_file ($_FILES['photo_file_s']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$s_filerename);
			}
		}else{
			$s_filerename = $arrCurInfo["list"][0]['image_s'];
		}
		//목록 이미지 등록
		if ($_FILES['photo_file_m']['error'] == 0){
			//확장자 검사후 파일이름 생성
			$filename = $_FILES['photo_file_m']['name'];
			$attach_ext = explode(".",$filename);
			$extension = $attach_ext[sizeof($attach_ext)-1];
			$extension = strtolower($extension);		    
			$m_filerename = "m_" . md5(mktime()) .  "." . $extension;
			$filesize = $_FILES['photo_file']['size'];
			$filetype = $_FILES['photo_file']['type'];
				
			// 파일 확장자 검사
			if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
				jsMsg("not allowed file extension");
				jsHistory("-1");
			}
			
			if (is_uploaded_file($_FILES['photo_file_m']['tmp_name'])) {	
				move_uploaded_file ($_FILES['photo_file_m']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$m_filerename);
			}
		}else{
			$m_filerename = $arrCurInfo["list"][0]['image_m'];
		}
		//상세 이미지 등록
		if ($_FILES['photo_file_l']['error'] == 0){
			//확장자 검사후 파일이름 생성
			$filename = $_FILES['photo_file_l']['name'];
			$attach_ext = explode(".",$filename);
			$extension = $attach_ext[sizeof($attach_ext)-1];
			$extension = strtolower($extension);		    
			$l_filerename = "l_" . md5(mktime()) . "." . $extension;
			$filesize = $_FILES['photo_file']['size'];
			$filetype = $_FILES['photo_file']['type'];
				
			// 파일 확장자 검사
			if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
				jsMsg("not allowed file extension");
				jsHistory("-1");
			}
			
			if (is_uploaded_file($_FILES['photo_file_l']['tmp_name'])) {	
				move_uploaded_file ($_FILES['photo_file_l']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$l_filerename);
			}
		}else{
			$l_filerename = $arrCurInfo["list"][0]['image_l'];
		}

		//확대이미지 등록
		for($i=0;$i<count($_FILES['photo_file']['error']);$i++){
			if ($_FILES['photo_file']['error'][$i] == 0){
				//확장자 검사후 파일이름 생성
				$filename = $_FILES['photo_file']['name'][$i];
				$attach_ext = explode(".",$filename);
				$extension = $attach_ext[sizeof($attach_ext)-1];
				$extension = strtolower($extension);		    
				$filerename = md5(mktime()) . $i . "." . $extension;
				$filesize = $_FILES['photo_file']['size'][$i];
				$filetype = $_FILES['photo_file']['type'][$i];
					
				// 파일 확장자 검사
				if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
					jsMsg("not allowed file extension");
					jsHistory("-1");
				}
				
				if (is_uploaded_file($_FILES['photo_file']['tmp_name'][$i])) {	
					move_uploaded_file ($_FILES['photo_file']['tmp_name'][$i],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$filerename);
				}

				$sql = "insert into ".$GLOBALS["_conf_tbl"]["shop_good_files"]." set 
					b_idx='".$idx."',/* 글 번호 id*/
					ori_name='".$filename."',/*파일원본이름*/
					re_name='".$filerename."',/*md5로 변환된 파일이름*/
					type='".$filetype."',/*파일타입*/
					ext ='".$extension."',/*파일확장자*/
					size='".$filesize."',/*첨부파일 용량*/
					width='".$tmpImageSize[0]."',/*첨부파일 가로길이*/
					height='".$tmpImageSize[1]."',/*첨부파일 세로길이*/
					wdate=now()
				";
				$rsf = mysqli_query($GLOBALS['dblink'], $sql);
			}
		}

		//상품정보에 이미지 정보 업데이트
		$sql = "update ".$GLOBALS["_conf_tbl"]["shop_good"]." set 
		image_s='".$s_filerename."',
		image_m='".$m_filerename."',
		image_l='".$l_filerename."'
		WHERE idx='$idx' 
		";
		mysqli_query($GLOBALS['dblink'], $sql);
	}

	########################################################## 기타 파일 업로드 ########################################################## ST
	// 썸네일 이미지 등록
	if ($_FILES['etc_file_1']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['etc_file_1']['name'];
		$e1_orifn = $filename;
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$e1_filerename = "e1_" . md5(mktime()) . "." . $extension;
		$filesize = $_FILES['etc_file_1']['size'];
		$filetype = $_FILES['etc_file_1']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['etc_file_1']['tmp_name'])) {	
			move_uploaded_file ($_FILES['etc_file_1']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$e1_filerename);
		}
	}else{
		if($_POST['etc_file_1_del']=="Y"){
			$e1_orifn = "";
			$e1_filerename = "";
		}else{
			$e1_filerename	= $arrCurInfo["list"][0]['etc_file_1'];
			$e1_orifn		= $arrCurInfo["list"][0]['etc_file_fn_1'];
		}
	}
	// 매뉴얼 등록
	if ($_FILES['etc_file_2']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['etc_file_2']['name'];
		$e2_orifn = $filename;
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$e2_filerename = "e2_" . md5(mktime()) .  "." . $extension;
		$filesize = $_FILES['etc_file_2']['size'];
		$filetype = $_FILES['etc_file_2']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['etc_file_2']['tmp_name'])) {	
			move_uploaded_file ($_FILES['etc_file_2']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$e2_filerename);
		}
	}else{
		if($_POST['etc_file_2_del']=="Y"){
			$e2_orifn = "";
			$e2_filerename = "";
		}else{
			$e2_filerename = $arrCurInfo["list"][0]['etc_file_2'];
			$e2_orifn = $arrCurInfo["list"][0]['etc_file_fn_2'];
		}
	}
	//규격서 등록
	if ($_FILES['etc_file_3']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['etc_file_3']['name'];
		$e3_orifn = $filename;
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$e3_filerename = "e3_" . md5(mktime()) . "." . $extension;
		$filesize = $_FILES['etc_file_3']['size'];
		$filetype = $_FILES['etc_file_3']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['etc_file_3']['tmp_name'])) {	
			move_uploaded_file ($_FILES['etc_file_3']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$e3_filerename);
		}
	}else{
		if($_POST['etc_file_3_del']=="Y"){
			$e3_orifn = "";
			$e3_filerename = "";
		}else{
			$e3_filerename = $arrCurInfo["list"][0]['etc_file_3'];
			$e3_orifn = $arrCurInfo["list"][0]['etc_file_fn_3'];
		}
	}
	//기타자료 등록
	if ($_FILES['etc_file_4']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['etc_file_4']['name'];
		$e4_orifn = $filename;
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$e4_filerename = "e4_" . md5(mktime()) . "." . $extension;
		$filesize = $_FILES['etc_file_4']['size'];
		$filetype = $_FILES['etc_file_4']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['etc_file_4']['tmp_name'])) {	
			move_uploaded_file ($_FILES['etc_file_4']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$e4_filerename);
		}
	}else{
		if($_POST['etc_file_4_del']=="Y"){
			$e4_orifn = "";
			$e4_filerename = "";
		}else{
			$e4_filerename = $arrCurInfo["list"][0]['etc_file_4'];
			$e4_orifn = $arrCurInfo["list"][0]['etc_file_fn_4'];
		}
	}
	//MSDS 등록
	if ($_FILES['etc_file_5']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['etc_file_5']['name'];
		$e5_orifn = $filename;
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$e5_filerename = "e5_" . md5(mktime()) . "." . $extension;
		$filesize = $_FILES['etc_file_5']['size'];
		$filetype = $_FILES['etc_file_5']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['etc_file_5']['tmp_name'])) {	
			move_uploaded_file ($_FILES['etc_file_5']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$e5_filerename);
		}
	}else{
		if($_POST['etc_file_5_del']=="Y"){
			$e5_orifn = "";
			$e5_filerename = "";
		}else{
			$e5_filerename = $arrCurInfo["list"][0]['etc_file_5'];
			$e5_orifn = $arrCurInfo["list"][0]['etc_file_fn_5'];
		}
	}
	//세미나용이미지 등록 변경해야할 부분 ( _6 , e6 )
	if ($_FILES['etc_file_6']['error'] == 0){
		//확장자 검사후 파일이름 생성
		$filename = $_FILES['etc_file_6']['name'];
		$e6_orifn = $filename;
		$attach_ext = explode(".",$filename);
		$extension = $attach_ext[sizeof($attach_ext)-1];
		$extension = strtolower($extension);		    
		$e6_filerename = "e6_" . md5(mktime()) . "." . $extension;
		$filesize = $_FILES['etc_file_6']['size'];
		$filetype = $_FILES['etc_file_6']['type'];
			
		// 파일 확장자 검사
		if(!strcmp($extension,"htm") ||!strcmp($extension,"html") ||!strcmp($extension,"phtml") ||!strcmp($extension,"php") ||!strcmp($extension,"php3") ||!strcmp($extension,"php4") ||!strcmp($extension,"inc") ||!strcmp($extension,"pl") ||!strcmp($extension,"cgi")){
			jsMsg("not allowed file extension");
			jsHistory("-1");
		}
		
		if (is_uploaded_file($_FILES['etc_file_6']['tmp_name'])) {	
			move_uploaded_file ($_FILES['etc_file_6']['tmp_name'],$GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$e6_filerename);
		}
	}else{
		if($_POST['etc_file_6_del']=="Y"){
			$e6_orifn = "";
			$e6_filerename = "";
		}else{
			$e6_filerename = $arrCurInfo["list"][0]['etc_file_6'];
			$e6_orifn = $arrCurInfo["list"][0]['etc_file_fn_6'];
		}
	}	

	//상품정보에 이미지 정보 업데이트
	$sql = "update ".$GLOBALS["_conf_tbl"]["shop_good"]." set 
	etc_file_fn_1='".$e1_orifn."',
	etc_file_fn_2='".$e2_orifn."',
	etc_file_fn_3='".$e3_orifn."',
	etc_file_fn_4='".$e4_orifn."',
	etc_file_fn_5='".$e5_orifn."',
	etc_file_fn_6='".$e6_orifn."',
	etc_file_1='".$e1_filerename."',
	etc_file_2='".$e2_filerename."',
	etc_file_3='".$e3_filerename."',
	etc_file_4='".$e4_filerename."',
	etc_file_5='".$e5_filerename."',
	etc_file_6='".$e6_filerename."'
	WHERE idx='$idx' 
	";
	//echo $sql;
	mysqli_query($GLOBALS['dblink'], $sql);
	########################################################## 기타 파일 업로드 ########################################################## ED
}


//파일정보 가져오기
function getGoodFileInfo($b_idx, $idx){
	$tbl = $GLOBALS["_conf_tbl"]["shop_good_files"];

    $sql  = "SELECT * ";
    $sql .= "FROM " .$tbl." ";
    $sql .= "WHERE b_idx = '$b_idx' ";
    $sql .= "AND idx = '$idx' ";

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

//상품 파일 삭제 처리
function delGoodFiles($idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];

	//현재 정보 가져오기
	$arrCurInfo = getArticleInfo($tbl, $idx);

	//이미지 파일삭제 코딩 시작 - 삭제체크 한것만 처리
    if ($_POST['delPhoto']) {
        for ($i=0;$i<count($_POST['delPhoto']);$i++) {
            if ($_POST['delPhoto'][$i]>0) {
                $fileinfo = getGoodFileInfo($arrCurInfo["list"][0]['idx'], $_POST['delPhoto'][$i]);
                //디비에서 파일정보 삭제
                mysqli_query($GLOBALS['dblink'], "DELETE FROM ".$tbl_files." WHERE idx='".$fileinfo["list"][0]['idx']."' ");
                //디스크에서 파일 삭제
                @unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/" . $arrCurInfo["list"][0]['idx']."/".$fileinfo["list"][0]['re_name']);
                @unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/" . $arrCurInfo["list"][0]['idx']."/s_".$fileinfo["list"][0]['re_name']);
                @unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/" . $arrCurInfo["list"][0]['idx']."/m_".$fileinfo["list"][0]['re_name']);
                @unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/" . $arrCurInfo["list"][0]['idx']."/l_".$fileinfo["list"][0]['re_name']);
            }
        }
    }
	//이미지 파일삭제 코딩 종료
}



//상품 가져오기 - 파일 포함
function getGoodListBaseNFile($cat_no, $orderby, $sw="", $sk="", $scale, $offset=0, $is_show=""){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	$tbl_category = $GLOBALS["_conf_tbl"]["category"];//카테고리
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];//상품 옵션 정보
	$tbl_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];//연계재고옵션 정보

	//카테고리가 있을경우
	if($cat_no !=""){
		$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $cat_no));
		$que_where .= " and A.cat_code like '" . $arrCategoryInfo["list"][0]['cat_code'] . "%' ";
	}

	//진열하는 상품만 가져올 경우
	if($is_show !=""){
		$que_where .= " and A.is_show ='Y' ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("name") :
			$que_where .= " and (A.g_name like '%$sk%') ";
		break;
		case("category") :
			$que_where .= " and (C.cat_name like '%$sk%') ";
		break;
		case("author") :
			$que_where .= " and (A.author_name like '%$sk%') ";
		break;
		case("isbn") :
			$que_where .= " and (A.isbn like '%$sk%') ";
		break;
		case("contents") :
			$que_where .= " and (A.contents like '%$sk%') ";
		break;
		default :
			$que_where .= " and (A.g_name like '%$sk%' or C.cat_name like '%$sk%' or A.contents like '%$sk%' or A.author_name like '%$sk%' or A.isbn like '%$sk%' or A.g_code like '%$sk%') ";
		}
	}


	//order by 가 있을경우
	if($orderby !=""){
		$orderby = $orderby;
	}else{
		$orderby = "A.idx DESC";
	}
	
	//카운트
	$sql = "select count(A.idx) from $tbl A LEFT JOIN ".$tbl_category." C ON A.cat_no=C.cat_no WHERE 1=1 $que_where ";
	//echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	$row = mysql_fetch_row($rs);
    $total_rs = $row['0'];


	//목록
    $sql  = "SELECT A.*, B.idx AS f_idx, B.ori_name, B.re_name, B.type, B.size, C.cat_name ";
    $sql .= "FROM ".$tbl." A ";
		$sql .= "LEFT JOIN ".$tbl_files." B ON A.idx=B.b_idx ";
    $sql .= "LEFT JOIN ".$tbl_category." C ON A.cat_no=C.cat_no ";
    $sql .= "WHERE 1=1 $que_where group by A.idx order by $orderby ";


    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
			$sql  = "SELECT MIN(stock) as min_stock FROM $tbl_opt_rel WHERE g_idx='".$list['list'][$i]['idx']."' group by g_idx ";
			$rs_stock = mysqli_fetch_assoc(mysqli_query($GLOBALS['dblink'], $sql));
			$list['list'][$i]['min_stock'] = $rs_stock[min_stock];
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}

//상품 가져오기 - 파일 포함
function getGoodListBaseNFileFromCat($cat_no, $orderby, $sw="", $sk="", $scale, $offset=0, $is_show="", $event_show="", $subQuery="", $onlyCount=""){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	$tbl_category = $GLOBALS["_conf_tbl"]["category"];//카테고리
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];//상품 옵션 정보
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];//상품 추가 카테고리

	//카테고리가 있을경우
	if($cat_no !=""){
		$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $cat_no));
		$que_where .= " and D.cat_code like '" . $arrCategoryInfo["list"][0]['cat_code'] . "%' ";
	}

	//진열하는 상품만 가져올 경우
	if($is_show !=""){
		$que_where .= " and A.is_show ='Y' ";
	}

	if($_GET['stock_type'] !=""){
		$que_where .= " and A.stock_type ='".$_GET['stock_type']."' ";
	}
	if($_GET['isshow'] !=""){
		$que_where .= " and A.is_show ='".$_GET['isshow']."' ";
	}

	if($_GET['show1']=="Y" || $_GET['show2']=="Y" || $_GET['show3']=="Y" || $_GET['show4']=="Y" ){
		
		$que_where .= " and (";
		
		if($_GET['show1']=="Y"){
			$que_where .= " A.main_show ='Y' ";
			if($_GET['show2']=="Y" || $_GET['show3']=="Y" || $_GET['show4']=="Y" ){
				$que_where .= " OR ";
			}
		}
		if($_GET['show2']=="Y"){
			$que_where .= " A.brand_show ='Y' ";
			if($_GET['show3']=="Y" || $_GET['show4']=="Y" ){
				$que_where .= " OR ";
			}
		}
		if($_GET['show3']=="Y"){
			$que_where .= " A.special_show ='Y' ";
			if($_GET['show4']=="Y" ){
				$que_where .= " OR ";
			}
		}
		if($_GET['show4']=="Y"){
			$que_where .= " A.best_show ='Y' ";
		}

		$que_where .= ")";
	}

	if($_REQUEST['s_date']){
		$que_where .= " AND A.published_date >= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['s_date'])." 00:00:00' ";
	}
	if($_REQUEST['e_date']){
		$que_where .= " AND A.published_date <= '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['e_date'])." 23:59:59' ";
	}
	if($_REQUEST['model']){
		$que_where .= " AND A.model = '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['model'])."' ";
	}
	if($_REQUEST['brand']){
		$que_where .= " AND A.brand = '".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['brand'])."' ";
	}

	//개월수 검색
	if($_GET['age'] && $_GET['age'][0]!="A") {
		$que_where .= " AND (";
		for($i=0; $i < count($_GET['age']); $i++) {
			
			$que_where .= " A.movie = '".$_GET['age'][$i]."'";
			if($i != count($_GET['age'])-1){
				$que_where .= " OR ";
			}
		}
		$que_where .= ")";
	}	
	//성별 검색
	if($_GET['gender'] && $_GET['gender'][0]!="A") {
		$que_where .= " AND (";
		for($i=0; $i < count($_GET['gender']); $i++) {
			
			$que_where .= " A.cdrom = '".$_GET['gender'][$i]."'";
			if($i != count($_GET['gender'])-1){
				$que_where .= " OR ";
			}
		}
		$que_where .= ")";
	}
	//가격 검색
	if($_GET['sprice'] && $_GET['sprice'][0]!="A") {
		$que_where .= " AND (";
		for($i=0; $i < count($_GET['sprice']); $i++) {
			
			if($_GET['sprice'][$i]=="1") {
				$que_where .= " A.price <= '30000' ";
			} else if($_GET['sprice'][$i]=="2") {
				$que_where .= " (A.price >='30000' AND A.price <='50000') ";
			} else if($_GET['sprice'][$i]=="3") {
				$que_where .= " (A.price >='50000' AND A.price <='80000') ";
			} else if($_GET['sprice'][$i]=="4") {
				$que_where .= " (A.price >='80000' AND A.price <='100000') ";
			} else if($_GET['sprice'][$i]=="5") {
				$que_where .= " (A.price >='100000' AND A.price <='150000') ";
			} else if($_GET['sprice'][$i]=="6") {
				$que_where .= " A.price >= '150000' ";
			}

			if($i != count($_GET['sprice'])-1){
				$que_where .= " OR ";
			}
		}
		$que_where .= ")";
	}	

	//왼쪽추가카테고리검색
	if($_GET['scat']) {
		$que_where .= "AND A.idx in (SELECT g_idx
			FROM tbl_shop_good_search
			WHERE g_idx = A.idx
			AND cat_no = '".$_GET['scat']."') ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("goodlist") :
			$que_where .= " and (A.".$sk."='T') ";
		break;
		case("name") :
			$que_where .= " and (A.g_name like '%$sk%') ";
		break;
		case("searchAll") :	## 통합검색용
			$que_where .= " and (A.g_name like '%$sk%' or A.search_keyword like '%$sk%' ) ";
		break;
		case("gcode") :
			$que_where .= " and (A.g_code like '%$sk%') ";
		break;
		case("opt") :
			$que_where .= " and (A.option_title like '%$sk%') ";
		break;
		case("allsearch") :
			$que_where .= " and ( (A.g_name like '%$sk%') or (C.cat_name like '%$sk%') or (A.option_title like '%$sk%') or (A.g_code Like '%$sk%') 
							OR D.cat_no IN (SELECT cat_no FROM tbl_category WHERE cat_name like '%$sk%') )";
		break;
		case("category") :
			$que_where .= " and (C.cat_name like '%$sk%') ";
		break;
		case("author") :
			$que_where .= " and (A.author_name like '%$sk%') ";
		break;
		case("best") :
			$que_where .= " and A.main_show = '$sk' ";
		break;
		case("month") :
			if($sk=="all") {
				$que_where .= " and A.movie != '' ";
			} else { 
				$que_where .= " and A.movie = '$sk' ";
			}
		break;
		case("event") :
			$que_where .= " and A.cdrom = '$sk' ";
		break;
		case("all") :
			$que_where .= " and (A.g_name like '%$sk%' or A.g_code like '%$sk%')";
		break;
		case("recom") :
			$que_where .= " and A.pages = '$sk' ";
		break;
		case("contents") :
			$que_where .= " and (A.contents like '%$sk%') ";
		break;
		default :
			$que_where .= " and (A.g_name like '%$sk%' or C.cat_name like '%$sk%' or A.author_name like '%$sk%' or A.isbn like '%$sk%') ";
		}
	}
	if($subQuery){
		$que_where .= $subQuery;
	}


	//order by 가 있을경우
	if($orderby !=""){
		$orderby = $orderby;
	}else{
		$orderby = "A.idx DESC";
	}
	
	//카운트
	$sql = "select count(D.g_idx) from $tbl_good_cat D LEFT JOIN $tbl A ON D.g_idx=A.idx LEFT JOIN $tbl_category C ON A.cat_no=C.cat_no WHERE 1=1 AND A.cat_no IS NOT NULL $que_where group by D.g_idx ";
	//	echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
	/*
	$row = mysql_fetch_row($rs);
    $total_rs = $row['0'];
	*/

	//목록
    $sql  = "SELECT A.*, B.idx AS f_idx, B.ori_name, B.re_name, B.type, B.size, C.cat_name, D.cat_no AS ext_cat_no, D.cat_code AS ext_cat_code ";
	$sql .= ", case when A.stock < 1 then '0' else '1' END as stock_num ";
    $sql .= " FROM ".$tbl_good_cat." D ";
	$sql .= " LEFT JOIN ".$tbl." A ON D.g_idx=A.idx ";
	$sql .= " LEFT JOIN ".$tbl_files." B ON A.idx=B.b_idx ";
    $sql .= " LEFT JOIN ".$tbl_category." C ON A.cat_no=C.cat_no ";
    $sql .= " WHERE 1=1 AND A.cat_no IS NOT NULL $que_where group by A.idx order by $orderby ";
	//	echo $sql;
	if($onlyCount=="Yes"){	## 전체카운트만 구할경우
		$list['total'] = $total_rs;
	}else{
		if($total_rs > 0){
			$list['total'] = $total_rs;
			// 페이지 네비게이션 오프셋 지정.
			if(!$offset){
				$offset=0;
			}else{
				$offset=$offset;
			}

			// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
			if($total_rs<=$offset){
				$offset = $total_rs - $scale;
			}

			if($scale != "0"){
				$sql .= " limit $offset,$scale ";
			}
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			//echo $sql;

			// offset 을 이용한 limit 가 적용된 갯수
			$total = mysqli_num_rows($rs);
			$list['list']['total'] = $total;
			// 페이지 네비게이션 오프셋 지정.
			
			for($i=0; $i < $total; $i++){
				$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
		}else{
			$list['total'] = 0;
		}
	}
    return $list;
}

//상품 가져오기 - 통합검색용
function getGoodListBaseAllSearch($cat_no, $orderby, $sw="", $sk="", $scale, $offset=0, $is_show="", $event_show="", $subQuery="", $onlyCount=""){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	$tbl_category = $GLOBALS["_conf_tbl"]["category"];//카테고리
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];//상품 옵션 정보
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];//상품 추가 카테고리

	//카테고리가 있을경우
	if($cat_no !=""){
		$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $cat_no));
		$que_where .= " and D.cat_code like '" . $arrCategoryInfo["list"][0]['cat_code'] . "%' ";
	}

	//진열하는 상품만 가져올 경우
	if($is_show !=""){
		$que_where .= " and A.is_show ='Y' ";
	}

	// 행사상품 전용
	if($event_show =="Y"){		
		$que_where .= " and ( D.cat_code like '2/11/%' OR  D.cat_code like '3/17/%' )";
	}else if($event_show =="SMN"){		
		$que_where .= " and ( D.cat_code like '4/%' )";
	}else{
		$que_where .= " and ( D.cat_code Not like '2/11/%' AND  D.cat_code Not like '3/17/%' AND D.cat_code Not like '4/%')";
	}

	if($_GET['isshow'] !=""){
		$que_where .= " and A.is_show ='".$_GET['isshow']."' ";
	}

	if($_GET['show1']=="Y" || $_GET['show2']=="Y" || $_GET['show3']=="Y" || $_GET['show4']=="Y" ){
		
		$que_where .= " and (";
		
		if($_GET['show1']=="Y"){
			$que_where .= " A.main_show ='Y' ";
			if($_GET['show2']=="Y" || $_GET['show3']=="Y" || $_GET['show4']=="Y" ){
				$que_where .= " OR ";
			}
		}
		if($_GET['show2']=="Y"){
			$que_where .= " A.brand_show ='Y' ";
			if($_GET['show3']=="Y" || $_GET['show4']=="Y" ){
				$que_where .= " OR ";
			}
		}
		if($_GET['show3']=="Y"){
			$que_where .= " A.special_show ='Y' ";
			if($_GET['show4']=="Y" ){
				$que_where .= " OR ";
			}
		}
		if($_GET['show4']=="Y"){
			$que_where .= " A.best_show ='Y' ";
		}

		$que_where .= ")";
	}

	//개월수 검색
	if($_GET['age'] && $_GET['age'][0]!="A") {
		$que_where .= " AND (";
		for($i=0; $i < count($_GET['age']); $i++) {
			
			$que_where .= " A.movie = '".$_GET['age'][$i]."'";
			if($i != count($_GET['age'])-1){
				$que_where .= " OR ";
			}
		}
		$que_where .= ")";
	}	
	//성별 검색
	if($_GET['gender'] && $_GET['gender'][0]!="A") {
		$que_where .= " AND (";
		for($i=0; $i < count($_GET['gender']); $i++) {
			
			$que_where .= " A.cdrom = '".$_GET['gender'][$i]."'";
			if($i != count($_GET['gender'])-1){
				$que_where .= " OR ";
			}
		}
		$que_where .= ")";
	}
	//가격 검색
	if($_GET['sprice'] && $_GET['sprice'][0]!="A") {
		$que_where .= " AND (";
		for($i=0; $i < count($_GET['sprice']); $i++) {
			
			if($_GET['sprice'][$i]=="1") {
				$que_where .= " A.price <= '30000' ";
			} else if($_GET['sprice'][$i]=="2") {
				$que_where .= " (A.price >='30000' AND A.price <='50000') ";
			} else if($_GET['sprice'][$i]=="3") {
				$que_where .= " (A.price >='50000' AND A.price <='80000') ";
			} else if($_GET['sprice'][$i]=="4") {
				$que_where .= " (A.price >='80000' AND A.price <='100000') ";
			} else if($_GET['sprice'][$i]=="5") {
				$que_where .= " (A.price >='100000' AND A.price <='150000') ";
			} else if($_GET['sprice'][$i]=="6") {
				$que_where .= " A.price >= '150000' ";
			}

			if($i != count($_GET['sprice'])-1){
				$que_where .= " OR ";
			}
		}
		$que_where .= ")";
	}	

	//왼쪽추가카테고리검색
	if($_GET['scat']) {
		$que_where .= "AND A.idx in (SELECT g_idx
			FROM tbl_shop_good_search
			WHERE g_idx = A.idx
			AND cat_no = '".$_GET['scat']."') ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("goodlist") :
			$que_where .= " and (A.".$sk."='T') ";
		break;
		case("name") :
			$que_where .= " and (A.g_name like '%$sk%') ";
		break;
		case("allsearch") :
			$que_where .= " and ( (A.g_name like '%$sk%') or (C.cat_name like '%$sk%') ) ";
		break;
		case("category") :
			$que_where .= " and (C.cat_name like '%$sk%') ";
		break;
		case("author") :
			$que_where .= " and (A.author_name like '%$sk%') ";
		break;
		case("best") :
			$que_where .= " and A.main_show = '$sk' ";
		break;
		case("month") :
			if($sk=="all") {
				$que_where .= " and A.movie != '' ";
			} else { 
				$que_where .= " and A.movie = '$sk' ";
			}
		break;
		case("event") :
			$que_where .= " and A.cdrom = '$sk' ";
		break;
		case("isbn") :
			$que_where .= " and A.isbn = '$sk' ";
		break;
		case("recom") :
			$que_where .= " and A.pages = '$sk' ";
		break;
		case("contents") :
			$que_where .= " and (A.contents like '%$sk%') ";
		break;
		default :
			$que_where .= " and (A.g_name like '%$sk%' or C.cat_name like '%$sk%' or A.author_name like '%$sk%' or A.isbn like '%$sk%') ";
		}
	}
	if($subQuery){
		$que_where .= $subQuery;
	}


	//order by 가 있을경우
	if($orderby !=""){
		$orderby = $orderby;
	}else{
		$orderby = "A.idx DESC";
	}
	
	//카운트
	$sql = "select count(D.g_idx) from $tbl_good_cat D LEFT JOIN $tbl A ON D.g_idx=A.idx LEFT JOIN $tbl_category C ON A.cat_no=C.cat_no WHERE 1=1  $que_where group by D.g_idx ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
	/*
	$row = mysql_fetch_row($rs);
    $total_rs = $row['0'];
	*/

	//목록
    $sql  = "
		SELECT A.*, B.idx AS f_idx, B.ori_name, B.re_name, B.type, B.size, C.cat_name, D.cat_no AS ext_cat_no, D.cat_code AS ext_cat_code
		FROM tbl_shop_good_cat D 
		LEFT JOIN tbl_shop_good A ON D.g_idx=A.idx
		LEFT JOIN ".$tbl_files." B ON A.idx=B.b_idx
		LEFT JOIN ".$tbl_category." C ON A.cat_no=C.cat_no
		WHERE 1=1 AND A.cat_no IS NOT NULL $que_where group by A.idx order by $orderby 
	";
	//	echo $sql;
	if($onlyCount=="Yes"){	## 전체카운트만 구할경우
		$list['total'] = $total_rs;
	}else{
		if($total_rs > 0){
			$list['total'] = $total_rs;
			// 페이지 네비게이션 오프셋 지정.
			if(!$offset){
				$offset=0;
			}else{
				$offset=$offset;
			}

			// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
			if($total_rs<=$offset){
				$offset = $total_rs - $scale;
			}

			if($scale != "0"){
				$sql .= " limit $offset,$scale ";
			}
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			//echo $sql;

			// offset 을 이용한 limit 가 적용된 갯수
			$total = mysqli_num_rows($rs);
			$list['list']['total'] = $total;
			// 페이지 네비게이션 오프셋 지정.
			
			for($i=0; $i < $total; $i++){
				$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
		}else{
			$list['total'] = 0;
		}
	}
    return $list;
}

//리스트 카운트 (하이덴탈_브랜드 & 카테고리)
function getGoodCount($cat_no, $orderby="", $sw="", $sk="", $scale, $offset=0, $is_show="", $sub_cat_no=""){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	$tbl_category = $GLOBALS["_conf_tbl"]["category"];//카테고리
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];//상품 옵션 정보
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];//상품 추가 카테고리

	//카테고리가 있을경우
	if($cat_no !=""){
		$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $cat_no));
		$que_where .= " and D.cat_code like '" . $arrCategoryInfo["list"][0]['cat_code'] . "%' ";
	}

	//서브카테고리가 있을경우
	if($sub_cat_no){
		$arrCategoryInfo2 = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $sub_cat_no));
		$que_where .= " and D.g_idx IN (SELECT g_idx FROM tbl_shop_good_cat WHERE cat_code like '". $arrCategoryInfo2["list"][0]['cat_code'] ."%') ";
	}

	//진열하는 상품만 가져올 경우
	if($is_show !=""){
		$que_where .= " and A.is_show ='Y' ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("name") :
			$que_where .= " and (A.g_name like '%$sk%') ";
		break;
		case("category") :
			$que_where .= " and (C.cat_name like '%$sk%') ";
		break;		
		case("contents") :
			$que_where .= " and (A.contents like '%$sk%') ";
		break;
		default :
			$que_where .= " and (A.g_name like '%$sk%' or C.cat_name like '%$sk%' or A.author_name like '%$sk%' or A.isbn like '%$sk%') ";
		}
	}
	
	//카운트
	$sql = "select count(D.g_idx) from $tbl_good_cat D LEFT JOIN $tbl A ON D.g_idx=A.idx LEFT JOIN $tbl_category C ON A.cat_no=C.cat_no WHERE 1=1  $que_where group by D.g_idx ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

	if($total_rs > 0){
        $list['total'] = $total_rs;        
    }else{
        $list['total'] = 0;
    }
    return $list;
}

//상품 가져오기 - 파일 포함 (하이덴탈_브랜드 & 카테고리)
function getGoodListHighDT($cat_no, $orderby, $sw="", $sk="", $scale, $offset=0, $is_show="", $sub_cat_no=""){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	$tbl_category = $GLOBALS["_conf_tbl"]["category"];//카테고리
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];//상품 옵션 정보
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];//상품 추가 카테고리

	//카테고리가 있을경우
	if($cat_no !=""){
		$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $cat_no));
		$que_where .= " and D.cat_code like '" . $arrCategoryInfo["list"][0]['cat_code'] . "%' ";
	}

	//서브카테고리가 있을경우
	if($sub_cat_no){
		$arrCategoryInfo2 = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $sub_cat_no));
		$que_where .= " and D.g_idx IN (SELECT g_idx FROM tbl_shop_good_cat WHERE cat_code like '". $arrCategoryInfo2["list"][0]['cat_code'] ."%') ";
	}

	//진열하는 상품만 가져올 경우
	if($is_show !=""){
		$que_where .= " and A.is_show ='Y' ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("name") :
			$que_where .= " and (A.g_name like '%$sk%') ";
		break;
		case("category") :
			$que_where .= " and (C.cat_name like '%$sk%') ";
		break;		
		case("contents") :
			$que_where .= " and (A.contents like '%$sk%') ";
		break;
		default :
			$que_where .= " and (A.g_name like '%$sk%' or C.cat_name like '%$sk%' or A.author_name like '%$sk%' or A.isbn like '%$sk%') ";
		}
	}

	//order by 가 있을경우
	if($orderby !=""){
		$orderby = $orderby;
	}else{
		$orderby = "A.idx DESC";
	}
	
	//카운트
	$sql = "select count(D.g_idx) from $tbl_good_cat D LEFT JOIN $tbl A ON D.g_idx=A.idx LEFT JOIN $tbl_category C ON A.cat_no=C.cat_no WHERE 1=1  $que_where group by D.g_idx ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
	/*
	$row = mysql_fetch_row($rs);
    $total_rs = $row['0'];
	*/

	//목록
    $sql  = "SELECT A.*, B.idx AS f_idx, B.ori_name, B.re_name, B.type, B.size, C.cat_name, D.cat_no AS ext_cat_no, D.cat_code AS ext_cat_code ";
    $sql .= "FROM ".$tbl_good_cat." D ";
	$sql .= "LEFT JOIN ".$tbl." A ON D.g_idx=A.idx ";
	$sql .= "LEFT JOIN ".$tbl_files." B ON A.idx=B.b_idx ";
    $sql .= "LEFT JOIN ".$tbl_category." C ON A.cat_no=C.cat_no ";
    $sql .= "WHERE 1=1 AND A.cat_no IS NOT NULL $que_where group by A.idx order by $orderby ";
	//	echo $sql;

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		//echo $sql;

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}


//상품 검색 - 파일 포함
function getGoodListBaseNFileFromSearch($orderby, $sw="", $sk="", $scale, $offset=0, $is_show=""){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	$tbl_category = $GLOBALS["_conf_tbl"]["category"];//카테고리
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];//상품 옵션 정보
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];//상품 추가 카테고리

	//진열하는 상품만 가져올 경우
	if($is_show !=""){
		$que_where .= " and A.is_show ='Y' ";
	}

	//검색키워드가 있을경우
	if($sk !=""){
		switch($sw){
		case("name") :
			$que_where .= " and (A.g_name like '%$sk%') ";
		break;
		case("category") :
			$que_where .= " and (C.cat_name like '%$sk%') ";
		break;
		case("author") :
			$que_where .= " and (A.author_name like '%$sk%') ";
		break;
		case("best") :
			$que_where .= " and A.main_show = '$sk' ";
		break;
		case("new") :
			$que_where .= " and A.movie = '$sk' ";
		break;
		case("event") :
			$que_where .= " and A.cdrom = '$sk' ";
		break;
		case("isbn") :
			$que_where .= " and A.isbn = '$sk' ";
		break;
		case("top5") :
			$que_where .= " and A.published_text = '$sk' ";
		break;
		case("recom") :
			$que_where .= " and A.pages = '$sk' ";
		break;
		case("contents") :
			$que_where .= " and (A.contents like '%$sk%') ";
		break;
		default :
			$que_where .= " and (A.g_name like '%$sk%' or C.cat_name like '%$sk%' or A.author_name like '%$sk%' or A.isbn like '%$sk%') ";
		}
	}


	//order by 가 있을경우
	if($orderby !=""){
		$orderby = $orderby;
	}else{
		$orderby = "A.idx DESC";
	}
	
	//카운트
	$sql = "select count(D.g_idx) from $tbl_good_cat D LEFT JOIN $tbl A ON D.g_idx=A.idx LEFT JOIN $tbl_category C ON A.cat_no=C.cat_no WHERE A.cat_no!='3' $que_where group by D.g_idx ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	/*
	$row = mysql_fetch_row($rs);
    $total_rs = $row['0'];
	*/

	//목록
    $sql  = "SELECT A.*, B.idx AS f_idx, B.ori_name, B.re_name, B.type, B.size, C.cat_name, D.cat_no AS ext_cat_no, D.cat_code AS ext_cat_code ";
    $sql .= "FROM ".$tbl_good_cat." D ";
	$sql .= "LEFT JOIN ".$tbl." A ON D.g_idx=A.idx ";
	$sql .= "LEFT JOIN ".$tbl_files." B ON A.idx=B.b_idx ";
    $sql .= "LEFT JOIN ".$tbl_category." C ON A.cat_no=C.cat_no ";
    $sql .= "WHERE A.cat_no!='3' $que_where group by A.idx order by $orderby ";

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}


//메인노출 상품 가져오기
function getGoodListMain($cat_no="", $scale, $offset=0, $gb){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];//상품 추가 카테고리

	//목록
    $sql  = "SELECT A.*, D.cat_no AS ext_cat_no, D.cat_code AS ext_cat_code ";
    $sql .= "FROM ".$tbl_good_cat." D ";
	$sql .= "LEFT JOIN ".$tbl." A ON D.g_idx=A.idx ";
	if($gb){
		$sql .= "WHERE 1=1 AND A.is_show='Y' AND A.".$gb."='Y' ";
	}else{
		$sql .= "WHERE 1=1 AND A.is_show='Y' ";
	}
	if($cat_no) {
		$catno = explode(",",$cat_no);
		
		if(count($catno) > 1) {

			$sql .= " and (";
			for($k=0; $k < count($catno); $k++){
				$arrCategoryInfo[$k] = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $catno[$k]));

				$sql .= " D.cat_code like '".$arrCategoryInfo[$k]["list"][0]['cat_code']."%'";
				if($k != count($catno)-1) {
					$sql .= " or ";
				}
			}
			$sql .= ")";
		} else {
			$arrCategoryInfo = getCategoryInfo(mysqli_real_escape_string($GLOBALS['dblink'], $cat_no));
			$sql .= " AND D.cat_code like '" . $arrCategoryInfo["list"][0]['cat_code'] . "%' ";
		}
	}
	$sql .= " group by A.idx order by A.sort_num desc, A.idx DESC ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;

        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}

//상품 조회수 업데이트
function setGoodHitsUpdate($idx){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보

	//기본정보 가져오기
	$sql .= "UPDATE ".$tbl." SET ";
	$sql .= "hit = hit + 1 ";
	$sql .= " WHERE idx = '$idx' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	if($rs){	
		return true;
	}else{
		return false;
	}
}


//카탈로그 파일처리
function inputCatalogFilesShop($idx){
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_catalog_files"];

    if ($_FILES['catalog_file']['error']) {
        for ($i=0;$i<count($_FILES['catalog_file']['error']);$i++) {
            if ($_FILES['catalog_file']['error'][$i] == 0) {
                //확장자 검사후 파일이름 생성
                $filename = $_FILES['catalog_file']['name'][$i];
                $attach_ext = explode(".", $filename);
                $extension = $attach_ext[sizeof($attach_ext)-1];
                $extension = strtolower($extension);
                $filerename = "sample_" . md5(mktime()) . $i . "." . $extension;
                $filesize = $_FILES['catalog_file']['size'][$i];
                $filetype = $_FILES['catalog_file']['type'][$i];
                
                // 파일 확장자 검사
                if (!strcmp($extension, "htm") ||!strcmp($extension, "html") ||!strcmp($extension, "phtml") ||!strcmp($extension, "php") ||!strcmp($extension, "php3") ||!strcmp($extension, "php4") ||!strcmp($extension, "inc") ||!strcmp($extension, "pl") ||!strcmp($extension, "cgi")) {
                    jsMsg("not allowed file extension");
                    jsHistory("-1");
                }
            
                if (is_uploaded_file($_FILES['catalog_file']['tmp_name'][$i])) {
                    move_uploaded_file($_FILES['catalog_file']['tmp_name'][$i], $GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/".$idx."/".$filerename);
                }

                $sql = "insert into ".$tbl_files." set 
				b_idx='".$idx."',/* 글 번호 id*/
				ori_name='".$filename."',/*파일원본이름*/
				re_name='".$filerename."',/*md5로 변환된 파일이름*/
				type='".$filetype."',/*파일타입*/
				ext ='".$extension."',/*파일확장자*/
				size='".$filesize."',/*첨부파일 용량*/
				wdate=now()
			";
                $rsf = mysqli_query($GLOBALS['dblink'], $sql);
            }
        }
    }
}

//카탈로그 파일정보 가져오기
function getCatalogFileInfoShop($b_idx, $idx){
	$tbl = $GLOBALS["_conf_tbl"]["shop_catalog_files"];

    $sql  = "SELECT * ";
    $sql .= "FROM " .$tbl." ";
    $sql .= "WHERE b_idx = '$b_idx' ";
    $sql .= "AND idx = '$idx' ";
//	echo $sql;
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

//카탈로그 파일 삭제 처리
function delCatalogFilesShop($idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_catalog_files"];

	//현재 정보 가져오기
	$arrCurInfo = getArticleInfo($tbl, $idx);

	//파일삭제 코딩 시작 - 삭제체크 한것만 처리
    if ($_POST['delCatalog']) {
        for ($i=0;$i<count($_POST['delCatalog']);$i++) {
            if ($_POST['delCatalog'][$i]>0) {
                $fileinfo = getCatalogFileInfoShop($arrCurInfo["list"][0]['idx'], $_POST['delCatalog'][$i]);
                //디비에서 파일정보 삭제
                mysql_query("DELETE FROM ".$tbl_files." WHERE idx='".$fileinfo["list"][0]['idx']."' ", $GLOBALS['dblink']);
                //디스크에서 파일 삭제
                unlink($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/" . $arrCurInfo["list"][0]['idx']."/".$fileinfo["list"][0]['re_name']);
            }
        }
    }
	//파일삭제 코딩 종료
}


//상품정보 가져오기 - id
function getGoodInfo($idx, $gubun=""){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	$tbl_catalog_files = $GLOBALS["_conf_tbl"]["shop_catalog_files"];//카탈로그 파일
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];//옵션정보
	$tbl_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];//연계재고
	$tbl_category = $GLOBALS["_conf_tbl"]["category"];//상품분류

	//기본정보 가져오기
	$sql  = "SELECT A.* ";
	$sql .= "FROM ".$tbl." A ";
	$sql .= " WHERE A.idx = '$idx' ";
	if($gubun){$sql .= " AND A.".$gubun." = 'T' ";}
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['list'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total'] = 0;
	}

	//옵션갯수 정보 가져오기(상품)
	$sql  = "SELECT opt_1 ";
	$sql .= "FROM ".$tbl_opt." ";
	$sql .= "WHERE g_idx = '$idx' group by opt_1 order by idx";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total_opt'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['opt'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total_opt'] = 0;
	}

	//옵션정보 가져오기(상품)
	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl_opt." ";
	$sql .= "WHERE g_idx = '$idx' order by idx";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total_opt_info'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['opt_info'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total_opt_info'] = 0;
	}


	//연계재고관리
	if($list["list"][0]["stock_type"]=="3"){
		//연계옵션 타이틀 정보 가져오기
		$sql  = "SELECT opt_1, opt_2 ";
		$sql .= "FROM ".$tbl_opt_rel." ";
		$sql .= "WHERE g_idx = '$idx' group by opt_1, opt_2 order by idx";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		
		if($total_rs > 0){
				$list['total_opt_rel'] = $total_rs;
				for($i=0; $i < $total_rs; $i++){
						$list['opt_rel'][$i] = mysqli_fetch_assoc($rs);
				}
		}else{
				$list['total_opt_rel'] = 0;
		}

		//연계옵션 가로값 정보 가져오기
		$sql  = "SELECT opt_1_value ";
		$sql .= "FROM ".$tbl_opt_rel." ";
		$sql .= "WHERE g_idx = '$idx' group by opt_1_value order by idx";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		
		if($total_rs > 0){
				$list['total_opt_rel_1'] = $total_rs;
				for($i=0; $i < $total_rs; $i++){
						$list['opt_rel_1'][$i] = mysqli_fetch_assoc($rs);
				}
		}else{
				$list['total_opt_rel_1'] = 0;
		}

		//연계옵션 세로값 정보 가져오기
		$sql  = "SELECT opt_2_value ";
		$sql .= "FROM ".$tbl_opt_rel." ";
		$sql .= "WHERE g_idx = '$idx' group by opt_2_value order by idx";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		
		if($total_rs > 0){
				$list['total_opt_rel_2'] = $total_rs;
				for($i=0; $i < $total_rs; $i++){
						$list['opt_rel_2'][$i] = mysqli_fetch_assoc($rs);
				}
		}else{
				$list['total_opt_rel_2'] = 0;
		}

		//연계옵션 및 재고정보 가져오기
		$sql  = "SELECT * ";
		$sql .= "FROM ".$tbl_opt_rel." ";
		$sql .= "WHERE g_idx = '$idx' order by idx";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		
		if($total_rs > 0){
				$list['total_opt_rel_info'] = $total_rs;
				for($i=0; $i < $total_rs; $i++){
						$row = mysqli_fetch_assoc($rs);
						$list['opt_rel_info'][$row['opt_1_value']][$row['opt_2_value']]['price'] = $row['price'];
						$list['opt_rel_info'][$row['opt_1_value']][$row['opt_2_value']]['stock'] = $row['stock'];
				}
		}else{
				$list['total_opt_rel_info'] = 0;
		}
	}


	//파일정보 가져오기(상품)
	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl_files." ";
	$sql .= "WHERE b_idx = '$idx' order by idx ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total_files'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['files'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['total_files'] = 0;
	}


	//파일정보 가져오기(카탈로그)
	$sql  = "SELECT * ";
	$sql .= "FROM ".$tbl_catalog_files." ";
	$sql .= "WHERE b_idx = '$idx' order by idx ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_num_rows($rs);
	
	if($total_rs > 0){
			$list['total_catalog_files'] = $total_rs;
			for($i=0; $i < $total_rs; $i++){
					$list['catalog_files'][$i] = mysqli_fetch_assoc($rs);
			}
	}else{
			$list['catalog_total_files'] = 0;
	}

	//관련상품 가져오기
	if($list['list'][0]['rel_g_idx']){
		$sql  = "SELECT A.idx, A.g_code, A.g_name, A.price, A.image_m ";
		$sql .= "FROM ".$tbl." A ";
		$sql .= " WHERE A.idx in (".$list['list'][0]['rel_g_idx'].") ORDER BY A.idx desc ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		//echo $sql;
		$total_rs = mysqli_num_rows($rs);
		
		if($total_rs > 0){
				$list['list_rel_good_total'] = $total_rs;
				for($i=0; $i < $total_rs; $i++){
						$list['list_rel_good'][$i] = mysqli_fetch_assoc($rs);
				}
		}else{
				$list['list_rel_good_total'] = 0;
		}
	}


	return $list;
}

//연계옵션 정보 가져오기
function getOptRelInfo($g_idx, $opt_1_value){
	$tbl_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];//연계옵션
    
    $sql  = "SELECT * ";
    $sql .= "FROM $tbl_opt_rel ";
    $sql .= "WHERE g_idx = '$g_idx' AND opt_1_value='$opt_1_value' order by idx";
    $rs = mysql_query($sql);
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


//연계옵션 재고수량 가져오기
function checkStockRel($g_idx, $opt_1_value, $opt_2_value){
	$tbl_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];//연계옵션
    
	$opt_1 = explode("|",$opt_1_value);
	$opt_2 = explode("|",$opt_2_value);

    $sql  = "SELECT * ";
    $sql .= "FROM $tbl_opt_rel ";
    $sql .= "WHERE g_idx = '$g_idx' AND opt_1_value='".$opt_1[0]."' AND opt_2_value='".$opt_2[0]."' ";
    $rs = mysql_query($sql);
    $total_rs = mysqli_num_rows($rs);
    
	if($total_rs > 0){
		$row = mysqli_fetch_assoc($rs);
		$list['opt_1_name'] = stripslashes($row['opt_1']);
		$list['opt_1_value'] = stripslashes($row['opt_1_value']);
		$list['opt_2_name'] = stripslashes($row['opt_2']);
		$list['opt_2_value'] = stripslashes($row['opt_2_value']);
		$list['opt_stock'] = stripslashes($row['stock']);
	}else{
		$list['opt_stock'] = 0;
	}

	return $list;
}

//재고체크
function checkPreOderStock($arrList){
	if($arrList["total"]>0){
		for($i=0;$i<$arrList["total"];$i++){
			if($arrList["list"][$i]['stock_type']=="2"){
				jsGo("/member/cart.php","",$arrList["list"][$i]['g_name'] . "\\n\\n해당 상품이 품절 중입니다.");
				exit();				
			}else{
				if($arrList["list"][$i]['stock'] < $arrList["list"][$i]['qty']){
					jsGo("/member/cart.php","",$arrList["list"][$i]['g_name'] . "\\n\\n의 재고가 현재 " . number_format($arrList["list"][$i]['stock']) . "개 있습니다.\\n\\n주문수량을 낮춰 주시기 바랍니다.");
					exit();
				}
			}
		}
	}
}

//추가 카테고리 가져오기
function getGoodExtCat($g_idx){
	$tbl = $GLOBALS["_conf_tbl"]["shop_good_cat"];

    $sql  = "SELECT * ";
    $sql .= "FROM " .$tbl." ";
    $sql .= "WHERE g_idx = '$g_idx' ";

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


//추가 검색 가져오기
function getGoodExtSearch($g_idx){
	$tbl = $GLOBALS["_conf_tbl"]["shop_good_search"];

    $sql  = "SELECT * ";
    $sql .= "FROM " .$tbl." ";
    $sql .= "WHERE g_idx = '$g_idx' ";

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


function deleteGood($idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tbl_files = $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	$tbl_opt = $GLOBALS["_conf_tbl"]["shop_good_opt"];//상품옵션
	$tbl_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];//연계옵션
	$tbl_catalog_files = $GLOBALS["_conf_tbl"]["shop_catalog_files"];//카탈로그 파일
	$tbl_good_cat = $GLOBALS["_conf_tbl"]["shop_good_cat"];

	$arrInfo = getArticleInfo($tbl, $idx);

	if($arrInfo["total"] > 0){
		//상품 정보 삭제
		$sql = "DELETE FROM ".$tbl." WHERE idx='".$arrInfo["list"][0]['idx']."'	";
		//echo $sql . "<br>";
		$rs1 = mysqli_query($GLOBALS['dblink'], $sql);

		//상품 파일정보 삭제
		$sql = "DELETE FROM ".$tbl_files." WHERE b_idx='".$arrInfo["list"][0]['idx']."'	";
		//echo $sql . "<br>";
		$rs2 = mysqli_query($GLOBALS['dblink'], $sql);

		//상품 옵션정보 삭제
		$sql = "DELETE FROM ".$tbl_opt." WHERE g_idx='".$arrInfo["list"][0]['idx']."'	";
		//echo $sql . "<br>";
		$rs3 = mysqli_query($GLOBALS['dblink'], $sql);

		//상품 옵션정보 삭제
		$sql = "DELETE FROM ".$tbl_opt_rel." WHERE g_idx='".$arrInfo["list"][0]['idx']."'	";
		//echo $sql . "<br>";
		$rs4 = mysqli_query($GLOBALS['dblink'], $sql);

		//상품 카탈로그 파일 삭제
		$sql = "DELETE FROM ".$tbl_catalog_files." WHERE b_idx='".$arrInfo["list"][0]['idx']."'	";
		//echo $sql . "<br>";
		$rs5 = mysqli_query($GLOBALS['dblink'], $sql);

		//추가 카테고리에서 삭제
		$sql = "DELETE FROM ".$tbl_good_cat." WHERE g_idx='".$arrInfo["list"][0]['idx']."'	";
		//echo $sql . "<br>";
		$rs6 = mysqli_query($GLOBALS['dblink'], $sql);
		
		/*
		if($rs1 && $rs2 && $arrInfo["list"][0]['idx']){
			//상품관련 파일삭제
			rrmdir ($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/" . $arrInfo["list"][0]['idx']);
			//위 함수가 하위에 파일이 없으면 디렉토리를 삭제하지 못하는 버그로 아래줄 추가함
			@rmdir ($GLOBALS["_SITE"]["UPLOADED_DATA"]."/shop_good/" . $arrInfo["list"][0]['idx']);
			return true;
		}else{
			return false;
		}
		*/
		return true;
	}else{
		return false;
	}
}


//장바구니에 담기
function addCart($session_id, $user_id, $tp, $g_idx, $qty){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];

	//있는 상품인지 체크
	$exists_chk = getGoodInfo($g_idx);

	if($exists_chk["total"] > 0){
		$sql  = "SELECT * ";
		$sql .= "FROM $tbl ";
		$sql .= "WHERE ";

		//세션아이디, 유저아이디중 선택
		if($tp =="1"){
			$sql .= "user_id='".$user_id."' ";
		}else{
			$sql .= "session_id='".$session_id."' ";
		}
		$sql .= "AND g_idx='".$g_idx."' ";
		$sql .= "AND opt_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_1'])."' ";
		$sql .= "AND opt_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_2'])."' ";
		$sql .= "AND opt_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_3'])."' ";
		$sql .= "AND opt_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_4'])."' ";
		$sql .= "AND opt_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_5'])."' ";
		$sql .= "AND opt_rel_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_1'])."' ";
		$sql .= "AND opt_rel_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_2'])."' ";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);

		//있다면 수량 업데이트
		if($total_rs > 0){
			$sql = "UPDATE ".$tbl." set 
				qty=qty+'".$qty."'
				WHERE ";
			
			if($tp=="1"){
				$sql .="user_id='".$user_id."' ";
			}else{
				$sql .="session_id='".$session_id."' ";
			}
			$sql .= "AND g_idx='".$g_idx."' ";

		//없다면 인서트
		}else{
			$sql = "INSERT INTO ".$tbl." set 
				session_id='".$session_id."',
				user_id='".$user_id."',
				g_idx='".$g_idx."',
				qty='".$qty."',
				opt_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_1'])."',
				opt_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_2'])."',
				opt_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_3'])."',
				opt_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_4'])."',
				opt_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_5'])."',
				opt_rel_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_1'])."',
				opt_rel_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_2'])."',
				wdate=now()
			";
		}

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
######################################################################### 하이덴탈 전용 #################################################################### ST
//장바구니에 담기
function addCartHigh($session_id, $user_id, $tp, $g_idx, $qty, $optnm, $optnm02=""){	
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];

	//있는 상품인지 체크
	$exists_chk = getGoodInfo($g_idx);	
	
	if($exists_chk["total"] > 0){
		$sql  = "SELECT * ";
		$sql .= "FROM $tbl ";
		$sql .= "WHERE ";

		//세션아이디, 유저아이디중 선택
		if($tp =="1"){
			$sql .= "user_id='".$user_id."' ";
		}else{
			$sql .= "session_id='".$session_id."' ";
		}
		$sql .= "AND g_idx='".$g_idx."' ";
		$sql .= "AND opt_1='".$optnm."' ";
		$sql .= "AND opt_2='".$optnm02."' ";
		$sql .= "AND opt_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_3'])."' ";
		$sql .= "AND opt_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_4'])."' ";
		$sql .= "AND opt_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_5'])."' ";
		$sql .= "AND opt_rel_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_1'])."' ";
		$sql .= "AND opt_rel_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_2'])."' ";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		//echo $sql; 

		//있다면 수량 업데이트
		if($total_rs > 0){
			$sql = "UPDATE ".$tbl." set qty=qty+'".$qty."' WHERE ";
			//$sql = "UPDATE ".$tbl." set qty=1 WHERE ";
			
			if($tp=="1"){
				$sql .="user_id='".$user_id."' ";
			}else{
				$sql .="session_id='".$session_id."' ";
			}
			$sql .= "AND g_idx='".$g_idx."' ";
			$sql .= "AND opt_1='".$optnm."' ";
			$sql .= "AND opt_2='".$optnm02."' ";
			$retTxt = "update";

		//없다면 인서트
		}else{
			$sql = "INSERT INTO ".$tbl." set 
				session_id='".$session_id."',
				user_id='".$user_id."',
				g_idx='".$g_idx."',
				qty='".$qty."',
				opt_1='".$optnm."',
				opt_2='".$optnm02."',
				opt_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_3'])."',
				opt_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_4'])."',
				opt_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_5'])."',
				opt_rel_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_1'])."',
				opt_rel_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_2'])."',
				wdate=now()
			";
			$retTxt = "insert";
		}

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			return $retTxt;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
//장바구니에 담기 - 관심상품 장바구니
function addCartWish($session_id, $user_id, $tp, $g_idx, $qty, $optnm, $optnm02=""){	
	//상품정보 테이블
	$tbl = "tbl_shop_cart_wish";

	//있는 상품인지 체크
	$exists_chk = getGoodInfo($g_idx);	
	
	if($exists_chk["total"] > 0){
		$sql  = "SELECT * ";
		$sql .= "FROM $tbl ";
		$sql .= "WHERE ";

		//세션아이디, 유저아이디중 선택
		if($tp =="1"){
			$sql .= "user_id='".$user_id."' ";
		}else{
			$sql .= "session_id='".$session_id."' ";
		}
		$sql .= "AND g_idx='".$g_idx."' ";
		$sql .= "AND opt_1='".$optnm."' ";
		$sql .= "AND opt_2='".$optnm02."' ";
		$sql .= "AND opt_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_3'])."' ";
		$sql .= "AND opt_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_4'])."' ";
		$sql .= "AND opt_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_5'])."' ";
		$sql .= "AND opt_rel_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_1'])."' ";
		$sql .= "AND opt_rel_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_2'])."' ";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);
		//echo $sql; 

		//있다면 수량 업데이트
		if($total_rs > 0){
			$sql = "UPDATE ".$tbl." set qty=qty+'".$qty."' WHERE ";
			//$sql = "UPDATE ".$tbl." set qty=1 WHERE ";
			
			if($tp=="1"){
				$sql .="user_id='".$user_id."' ";
			}else{
				$sql .="session_id='".$session_id."' ";
			}
			$sql .= "AND g_idx='".$g_idx."' ";
			$sql .= "AND opt_1='".$optnm."' ";
			$sql .= "AND opt_2='".$optnm02."' ";

		//없다면 인서트
		}else{
			$sql = "INSERT INTO ".$tbl." set 
				session_id='".$session_id."',
				user_id='".$user_id."',
				g_idx='".$g_idx."',
				qty='".$qty."',
				opt_1='".$optnm."',
				opt_2='".$optnm02."',
				opt_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_3'])."',
				opt_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_4'])."',
				opt_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_5'])."',
				opt_rel_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_1'])."',
				opt_rel_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_2'])."',
				wdate=now()
			";
		}

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
//바로구매
function directOrderHigh($session_id, $user_id, $tp, $g_idx, $qty, $optnm, $loopi=0, $order_flag="T"){
	//테이블
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];

	//주문번호 생성
	$new_order_no = makeOrderNo($order_flag);

	if($loopi<1){
		//테이블 비움
		$sql = "DELETE FROM ".$tbl_order_cart." 
			WHERE 
		";
		if($tp=="1"){
			$sql .="user_id='".$user_id."' ";
		}else{
			$sql .="session_id='".$session_id."' ";
		}

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);
	}

	$sql = "INSERT INTO ".$tbl_order_cart." set 
		order_no='".$new_order_no."',
		session_id='".$session_id."',
		user_id='".$user_id."',		
		g_idx='".$g_idx."',
		qty='".$qty."',
		opt_1='".$optnm."',		
		opt_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_2'])."',
		opt_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_3'])."',
		opt_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_4'])."',
		opt_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_5'])."',
		opt_rel_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_1'])."',
		opt_rel_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_2'])."',
		wdate=now()
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);


	if($total > 0){
		return true;
	}else{
		return false;
	}
}
######################################################################### 하이덴탈 전용 #################################################################### ED

//장바구니 아이템 수량 업데이트
function updateCart($session_id, $user_id, $tp){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];

	$sql = "UPDATE ".$tbl." set 
		qty='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['qty'])."'
		WHERE ";
	
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

	$sql .=" AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['c_idx'])."'	";


	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}

function updateCartWish($session_id, $user_id, $tp){
	//상품정보 테이블
	$tbl = $tbl = "tbl_shop_cart_wish";

	$sql = "UPDATE ".$tbl." set 
		qty='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['qty'])."'
		WHERE ";
	
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

	$sql .=" AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['c_idx'])."'	";


	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}
//장바구니에서 아이템 삭제
function deleteCartOrderEnd($user_id, $gidx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];

	$sql = "DELETE FROM tbl_shop_cart_wish WHERE user_id='".$user_id."' AND g_idx in (".mysqli_real_escape_string($GLOBALS['dblink'], $gidx).") ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	$sql = "DELETE FROM ".$tbl." WHERE user_id='".$user_id."' AND g_idx in (".mysqli_real_escape_string($GLOBALS['dblink'], $gidx).") ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//장바구니에서 아이템 삭제
function deleteCart($session_id, $user_id, $tp){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];

	$sql = "DELETE FROM ".$tbl." 
		WHERE ";
		
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

//	$sql .=" AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['c_idx'])."' ";
	$sql .=" AND c_idx in (".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['c_idx']).") ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//장바구니에서 아이템 삭제
function deleteCartWish($session_id, $user_id, $tp){
	//상품정보 테이블
	$tbl = "tbl_shop_cart_wish";

	$sql = "DELETE FROM ".$tbl." 
		WHERE ";
		
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

//	$sql .=" AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['c_idx'])."' ";
	$sql .=" AND c_idx in (".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['c_idx']).") ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//장바구니에서 체크한 아이템 삭제
function deleteCartChecked($session_id, $user_id, $tp){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];

	if(count($_REQUEST['items']) > 0){
		foreach($_REQUEST['items'] AS $key => $val){
			$sql = "DELETE FROM ".$tbl." 
				WHERE ";
	
			if($tp=="1"){
				$sql .="user_id='".$user_id."' ";
			}else{
				$sql .="session_id='".$session_id."' ";
			}

			$sql .= " AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $val)."' ";

			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$total = mysqli_affected_rows($GLOBALS['dblink']);
		}

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//장바구니에서 체크한 아이템 삭제
function deleteCartOut($session_id, $user_id, $tp){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];

	if(count($_REQUEST['outs']) > 0){
		foreach($_REQUEST['outs'] AS $key => $val){
			$sql = "DELETE FROM ".$tbl." 
				WHERE ";
	
			if($tp=="1"){
				$sql .="user_id='".$user_id."' ";
			}else{
				$sql .="session_id='".$session_id."' ";
			}

			$sql .= " AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $val)."' ";

			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$total = mysqli_affected_rows($GLOBALS['dblink']);
		}

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}


//장바구니에 담겨진 상품 회원 아이디와 연결 - 로그인시 세션 업데이트
function updateCartSession($session_id, $user_id){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];//주문직전 장바구니

	//장바구니에 담겨진것 회원아이디에 연결
	$sql = "UPDATE ".$tbl_order_cart." set 
		user_id='".$user_id."'
		WHERE 
		session_id='".$session_id."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
/*
	//주문직전 장바구니 비우기 (이미 주문신청한것 삭제)
	$sql = "DELETE FROM ".$tbl_order_cart." 
		WHERE 
		user_id='".$user_id."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
*/

	//================================================
	// 이전 카트에 아이디로 등록된 값
	// 세션으로 현재적용되어 있는 아이디로변경
	// 카트에서 구매가능케 함
	// 20100629
	// 테스트후 적용할것
	//================================================
	$sql2 = "UPDATE ".$tbl." set 
		user_id='".$user_id."'
		WHERE 
		session_id='".$session_id."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql2);

	$sql2 = "UPDATE ".$tbl." set 
		session_id='".$session_id."'
		WHERE 
		user_id='".$user_id."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql2);
	//================================================

	//주문직전 장바구니에 담겨진것 회원아이디에 연결
	$sql = "UPDATE ".$tbl_order_cart." set 
		user_id='".$user_id."'
		WHERE 
		session_id='".$session_id."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}

}

//장바구니 가져오기
function getCartList($session_id, $user_id, $tp){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];//장바구니
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];

	//세션아이디, 유저아이디중 선택
	if($tp =="1"){
		$que_where .= " AND A.user_id='$user_id' ORDER BY A.wdate desc";
	}else{
		$que_where .= " AND A.session_id='$session_id' ORDER BY A.wdate desc";
	}

	
	//목록
    $sql  = "SELECT A.*, B.* ";
    $sql .= "FROM ".$tbl." A ";
    $sql .= "LEFT JOIN ".$tbl_good." B ON A.g_idx=B.idx ";
    $sql .= "WHERE 1=1 $que_where ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

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

//장바구니 가져오기
function getCartListWish($session_id, $user_id, $tp){
	//테이블 지정
	$tbl = "tbl_shop_cart_wish";//장바구니
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];

	//세션아이디, 유저아이디중 선택
	if($tp =="1"){
		$que_where .= " AND A.user_id='$user_id' ORDER BY A.wdate desc";
	}else{
		$que_where .= " AND A.session_id='$session_id' ORDER BY A.wdate desc";
	}

	
	//목록
    $sql  = "SELECT A.*, B.* ";
    $sql .= "FROM ".$tbl." A ";
    $sql .= "LEFT JOIN ".$tbl_good." B ON A.g_idx=B.idx ";
    $sql .= "WHERE 1=1 $que_where ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

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

//장바구니 유무확인
function getCartListIdx($session_id, $user_id, $tp, $idx){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];//장바구니
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];

	//세션아이디, 유저아이디중 선택
	if($tp =="1"){
		$que_where .= " AND A.user_id='$user_id' AND A.g_idx='$idx' ORDER BY A.wdate desc";
	}else{
		$que_where .= " AND A.session_id='$session_id' AND A.g_idx='$idx' ORDER BY A.wdate desc";
	}

	
	//목록
    $sql  = "SELECT A.*, B.* ";
    $sql .= "FROM ".$tbl." A ";
    $sql .= "LEFT JOIN ".$tbl_good." B ON A.g_idx=B.idx ";
    $sql .= "WHERE 1=1 $que_where ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

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

//주문직전 장바구니 가져오기
function getPreOrderList($session_id, $user_id, $tp){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_order_cart"];//주문직전 장바구니
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];

	//세션아이디, 유저아이디중 선택
	if($tp =="1"){		
		$que_where .= " AND A.user_id='$user_id' ORDER BY A.wdate desc";	
	}else{
		$que_where .= " AND A.session_id='$session_id' ORDER BY A.wdate desc";
	}
	
	//목록
    $sql  = "SELECT A.*, B.* ";
    $sql .= "FROM ".$tbl." A ";
    $sql .= "LEFT JOIN ".$tbl_good." B ON A.g_idx=B.idx ";
    $sql .= "WHERE 1=1 $que_where ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

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

//예약주문의 상품정보 가져오기
function getReserveOrderList($user_id, $orderNo){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_order_good"];
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];

	$que_where .= " AND A.order_id='$user_id' AND A.order_no='$orderNo' ";	
	
	//목록
    $sql  = "SELECT A.order_no,A.g_coupon_pay,A.g_coupon_idx,A.g_qty,A.g_vendor,A.idx as order_idx, B.* ";
    $sql .= "FROM ".$tbl." A ";
    $sql .= "LEFT JOIN ".$tbl_good." B ON A.g_idx=B.idx ";
    $sql .= "WHERE 1=1 $que_where ";
	//	echo $sql;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

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


//장바구니에서 체크한 아이템 구매
function preOrder($session_id, $user_id, $tp){
	//테이블
	$tbl_cart = $GLOBALS["_conf_tbl"]["shop_cart"];
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];

	//주문번호 생성
	$new_order_no = makeOrderNo();

	//테이블 비움
	$sql = "DELETE FROM ".$tbl_order_cart." 
		WHERE ";
	
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="user_id='".$user_id."' ";
//		$sql .="session_id='".$session_id."' ";
	}


	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if(count($_REQUEST['items']) > 0){
		foreach($_REQUEST['items'] AS $key => $val){
			$sql = "INSERT INTO ".$tbl_order_cart." (
				c_idx,
				order_no,
				session_id,
				user_id,
				g_idx,
				qty,
				opt_1,
				opt_2,
				opt_3,
				opt_4,
				opt_5,
				opt_rel_1,
				opt_rel_2,
				wdate
			)
			SELECT 
				c_idx,
				'".$new_order_no."',
				session_id,
				user_id,
				g_idx,
				qty,
				opt_1,
				opt_2,
				opt_3,
				opt_4,
				opt_5,
				opt_rel_1,
				opt_rel_2,
				now()
			FROM ".$tbl_cart."
				WHERE ";

			if($tp=="1"){
				$sql .="user_id='".$user_id."' ";
			}else{
				$sql .="session_id='".$session_id."' ";
			}

			$sql .= " AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $val)."' ";

			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$total = mysqli_affected_rows($GLOBALS['dblink']);
		}

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//장바구니에서 체크한 아이템 구매
function preOrderWish($session_id, $user_id, $tp){
	//테이블
	$tbl_cart = "tbl_shop_cart_wish";
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];

	//주문번호 생성
	$new_order_no = makeOrderNo();

	//테이블 비움
	$sql = "DELETE FROM ".$tbl_order_cart." 
		WHERE ";
	
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="user_id='".$user_id."' ";
//		$sql .="session_id='".$session_id."' ";
	}


	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if(count($_REQUEST['items']) > 0){
		foreach($_REQUEST['items'] AS $key => $val){
			$sql = "INSERT INTO ".$tbl_order_cart." (
				c_idx,
				order_no,
				session_id,
				user_id,
				g_idx,
				qty,
				opt_1,
				opt_2,
				opt_3,
				opt_4,
				opt_5,
				opt_rel_1,
				opt_rel_2,
				wdate
			)
			SELECT 
				c_idx,
				'".$new_order_no."',
				session_id,
				user_id,
				g_idx,
				qty,
				opt_1,
				opt_2,
				opt_3,
				opt_4,
				opt_5,
				opt_rel_1,
				opt_rel_2,
				now()
			FROM ".$tbl_cart."
				WHERE ";

			if($tp=="1"){
				$sql .="user_id='".$user_id."' ";
			}else{
				$sql .="session_id='".$session_id."' ";
			}

			$sql .= " AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $val)."' ";

			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$total = mysqli_affected_rows($GLOBALS['dblink']);
		}

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//장바구니에서 해당아이템 한개 구매
function preOrderOne($session_id, $user_id, $idx, $tp){
	//테이블
	$tbl_cart = $GLOBALS["_conf_tbl"]["shop_cart"];
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];

	//주문번호 생성
	$new_order_no = makeOrderNo();

	//테이블 비움
	$sql = "DELETE FROM ".$tbl_order_cart." 
		WHERE ";

	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	$sql = "INSERT INTO ".$tbl_order_cart." (
		c_idx,
		order_no,
		session_id,
		user_id,
		g_idx,
		qty,
		opt_1,
		opt_2,
		opt_3,
		opt_4,
		opt_5,
		opt_rel_1,
		opt_rel_2,
		wdate
	)
	SELECT 
		c_idx,
		'".$new_order_no."',
		session_id,
		user_id,
		g_idx,
		qty,
		opt_1,
		opt_2,
		opt_3,
		opt_4,
		opt_5,
		opt_rel_1,
		opt_rel_2,
		now()
	FROM ".$tbl_cart."
		WHERE 
	";

	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

	$sql .= "
		AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $idx)."'
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//바로구매
function directOrder($session_id, $user_id, $tp){
	//테이블
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];

	//주문번호 생성
	$new_order_no = makeOrderNo();

	//테이블 비움
	$sql = "DELETE FROM ".$tbl_order_cart." 
		WHERE 
	";
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	$sql = "INSERT INTO ".$tbl_order_cart." set 
		order_no='".$new_order_no."',
		session_id='".$session_id."',
		user_id='".$user_id."',
		g_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx'])."',
		qty='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['qty'])."',
		opt_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_1'])."',
		opt_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_2'])."',
		opt_3='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_3'])."',
		opt_4='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_4'])."',
		opt_5='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_5'])."',
		opt_rel_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_1'])."',
		opt_rel_2='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['opt_rel_2'])."',
		wdate=now()
	";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);


	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//바로구매2
function directOrder2($session_id, $user_id, $tp){
	//테이블
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];

	//주문번호 생성
	$new_order_no = makeOrderNo();

	//테이블 비움
	$sql = "DELETE FROM ".$tbl_order_cart." 
		WHERE 
	";
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	for($i=0; $i<$_REQUEST['topt']; $i++) {
		$j=$i+1;
		$sql = "INSERT INTO ".$tbl_order_cart." set 
			order_no='".$new_order_no."',
			session_id='".$session_id."',
			user_id='".$user_id."',
			g_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx'])."',
			qty='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["qty_".$j])."',
			opt_1='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST["opt_".$j])."',
			wdate=now()
		";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
	}
	$total = mysqli_affected_rows($GLOBALS['dblink']);


	if($total > 0){
		return true;
	}else{
		return false;
	}
}


//위시리스트에 담기
function addWish($user_id, $g_idx){
	//위시리스트 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_wish"];

	//있는 상품인지 체크
	$exists_chk = getGoodInfo($g_idx);

	if($exists_chk["total"] > 0){
		$sql  = "SELECT * ";
		$sql .= "FROM $tbl ";
		$sql .= "WHERE ";

		//세션아이디, 유저아이디중 선택
		$sql .= "user_id='".$user_id."' ";
		$sql .= "AND g_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx'])."' ";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);

		//있다면 그냥 리턴
		if($total_rs > 0){
			return true;
		//없다면 인서트
		}else{
			$subQuery = "viewflag='G',";
			if($_REQUEST['vf']){
				$subQuery = "viewflag='".$_REQUEST['vf']."',";
			}
			$sql = "INSERT INTO ".$tbl." set 
				user_id='".$user_id."',
				g_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx'])."',
				".$subQuery."
				wdate=now()
			";
		}

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//위시리스트에서 아이템 삭제
function deleteWish($user_id, $g_idx){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_wish"];

	$sql = "DELETE FROM ".$tbl." 
		WHERE ";
		
	$sql .="user_id='".$user_id."' ";

	$sql .=" AND g_idx in (".$g_idx.") ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//위시리스트에서 체크한 아이템 삭제
function deleteWishChecked($user_id){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_wish"];

	if(count($_REQUEST['items']) > 0){
		foreach($_REQUEST['items'] AS $key => $val){
			$sql = "DELETE FROM ".$tbl." 
				WHERE ";
	
			$sql .="user_id='".$user_id."' ";

			$sql .= " AND c_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $val)."' ";

			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$total = mysqli_affected_rows($GLOBALS['dblink']);
		}

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//위시리스트 가져오기
function getWishList($user_id, $scale, $offset=0, $viewflag=""){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_wish"];//위시리스트
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];


	if($viewflag=="S"){
		$que_where = " AND viewflag='S' ";
	}else{
		$que_where = " AND viewflag!='S' ";
	}
	$que_where .= " AND A.user_id='$user_id' ORDER BY A.wdate desc";

	
	//목록
    $sql  = "SELECT A.*, B.* ";
    $sql .= "FROM ".$tbl." A ";
    $sql .= "LEFT JOIN ".$tbl_good." B ON A.g_idx=B.idx ";
    $sql .= "WHERE 1=1 AND B.idx>0 AND B.is_show ='Y' $que_where ";
	//	echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}
//위시리스트 가져오기 - 상품idx
function getWishListGood($g_idx, $user_id){
	$sql = "SELECT * FROM tbl_shop_wish WHERE g_idx='$g_idx' AND user_id='$user_id' ";
	//	echo $sql;
	
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){
        $list['total'] = $total_rs;
        for($i=0; $i<$total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
	
    return $list;
}


//주문번호 생성
function makeOrderNo($tmp=""){
	if($tmp=="W"){
		$orderTxt = "WAIT";
	}else if($tmp=="S"){
		$orderTxt = "S";
	}else{
		$orderTxt = "T";
	}
	return date("ymdHis"). $orderTxt . substr(microtime(),3,4);
}

//해당 주문번호로 주문건이 있는지 체크
function checkVaildOrderNo($order_no){
	$tbl = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
    
    $sql  = "SELECT order_no ";
    $sql .= "FROM $tbl ";
    $sql .= "WHERE order_no = '$order_no' ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
	if($total_rs > 0){
			return true;
	}else{
			return false;
	}

	return $list;
}

function nullZero($checkStr){
	if(!$checkStr){$checkStr=0;}
	return $checkStr;
}
//주문서 업데이트
function setOrderReserve($user_id, $order_no){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	$ship_phone		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_phone01'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_phone02'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_phone03']);
	$ship_mobile	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_mobile01'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_mobile02'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_mobile03']);

	//주문정보 테이블에 입력
	$sql = "UPDATE ".$tbl_order_info." SET		
		ship_name		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_name'])."',
		ship_phone		='".$ship_phone."',
		ship_mobile		='".$ship_mobile."',
		ship_zip		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_zip'])."',
		ship_address	='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_address'])."',
		ship_address_ext='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_address_ext'])."',
		ship_email		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_email'])."',
		pay_type		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['pay_type'])."',		
		using_point		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['using_point'])."',
		coupon_amount	='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_price'])."',		
		pay_amount		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['hiddenPayAmount'])."',
		order_date		=now(),
		order_comment	='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_comment'])."',			
		ip				='".$_SERVER[REMOTE_ADDR]."'
		where order_no='".$order_no."' and order_id='".$user_id."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	//쿠폰사용처리
	$arr_coupon_idx = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_idx']);
	$arrCoupon = explode("|", $arr_coupon_idx);
	for($i=0; $i<count($arrCoupon); $i++) {
		$idx = mysqli_real_escape_string($GLOBALS['dblink'], $arrCoupon[$i]);
		###################### 쿠폰 사용등록
		if($idx){
			//$coupon_idx = getUserCouponReturn($idx, $order_id);
			$sql_up = "UPDATE tbl_mycoupon SET coupon_use='Y', udate=now()  WHERE idx='".$idx."' ";
			$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);
		}			
	}

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

//주문서 입력
function setOrderInfo($session_id, $user_id, $tp, $order_no, $order_state){
	$tbl_cart = $GLOBALS["_conf_tbl"]["shop_cart"];//장바구니
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];//주문직전 장바구니
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	//주문직전 장바구니에서 해당 주문내역 가져옴
	$arrList = getPreOrderList($session_id, $user_id, $tp);

	//변수 설정
	if($tp=="1"){
		$order_id = $user_id;
	}else{
		$order_id = "guest";
	}
	$order_phone	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_phone']);
	$order_mobile	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_mobile']);
	$order_zip		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_zip']);
	//	$ship_phone		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_phone01'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_phone02'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_phone03']);
	//	$ship_mobile	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_mobile01'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_mobile02'])."-".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_mobile03']);
	$ship_phone		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_phone']);
	$ship_mobile	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_mobile']);
	$ship_zip		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_zip']);
	$shipemail		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_email']);
	$ordermail		= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_email']);	
	$shipping_date	= mysqli_real_escape_string($GLOBALS['dblink'], $_POST['shipping_date']);	

	//입금확인의 경우
	if($order_state=="6"){
		$order_state = "6";
		$ipkum_date = date("Y-m-d H:i:s");
	}else if($order_state=="11"){	## 세미나 결제완료
		$order_state = "11";
		$ipkum_date = date("Y-m-d H:i:s");
	}else if($order_state=="12"){	## 대기 예약
		$order_state = "12";
		$ipkum_date = "0000-00-00";
	//입금대기로
	}else if($order_state=="10") {
		$order_state = "10";
		$ipkum_date = "0000-00-00";
	}else{
		$order_state = "1";
		$ipkum_date = "0000-00-00";
	}

	//적립금 사용체크
	$nowPoint = getNowPoint($user_id); 
	if($_POST['using_point'] > intval($nowPoint[nowpoint])){
		echo "사용하려는 적립금이 보유액보다 많습니다.";
		exit;
	}	

	if($arrList["total"]>0){
		for($i=0;$i<$arrList["total"];$i++){	
			$arrOpt1[$i] = explode("||",$arrList["list"][$i]['opt_1']);
			$arrOpt2[$i] = explode("|",$arrList["list"][$i]['opt_2']);
			$arrOpt3[$i] = explode("|",$arrList["list"][$i]['opt_3']);
			$arrOpt4[$i] = explode("|",$arrList["list"][$i]['opt_4']);
			$arrOpt5[$i] = explode("|",$arrList["list"][$i]['opt_5']);
			$arrOptRel1[$i] = explode("|",$arrList["list"][$i]['opt_rel_1']);
			$arrOptRel2[$i] = explode("|",$arrList["list"][$i]['opt_rel_2']);

				//추가금액 계산
			$optionPrice = $arrOpt1[$i]['1'] + $arrOpt2[$i]['1'] + $arrOpt3[$i]['1'] + $arrOpt4[$i]['1'] + $arrOpt5[$i]['1'] + $arrOptRel1[$i]['1'] + $arrOptRel2[$i]['1'];

			//적립금계산
			//if($arrList["list"][$i]['point_unit']=="P"){
				$optionPrice = 0; ## 하이덴탈전용
				$thisPoint = (($_POST['pointunit']*($arrList["list"][$i]['price']+$optionPrice))/100) * $arrList["list"][$i]['qty'];
			//}else{
			//	$thisPoint = $arrList["list"][$i]['point'] * $arrList["list"][$i]['qty'];
			//}

			//합계금액 계산 (적립금사용, 배송비를 포함하지 않은 순수 금액+옵션가격)
			$TotalAmount += ($arrList["list"][$i]['price']*$arrList["list"][$i]['qty'])+($optionPrice * $arrList["list"][$i]['qty']);

			if(!$arrOpt1[$i]['1']){$arrOpt1[$i]['1']=0;}
			if(!$arrOpt2[$i]['1']){$arrOpt2[$i]['1']=0;}
			if(!$arrOpt3[$i]['1']){$arrOpt3[$i]['1']=0;}
			if(!$arrOpt4[$i]['1']){$arrOpt4[$i]['1']=0;}
			if(!$arrOpt5[$i]['1']){$arrOpt5[$i]['1']=0;}
			if(!$arrOptRel1[$i]['1']){$arrOptRel1[$i]['1']=0;}
			if(!$arrOptRel2[$i]['1']){$arrOptRel2[$i]['1']=0;}
	
			######################################################### 상품별 쿠폰 적용 ######################################################### ST
			for($g=0;$g<count($_POST['good_idx']);$g++){
				
				if($_POST['good_idx'][$g]==$arrList["list"][$i]["g_idx"]){
					
					if($_POST['good_coupon_pay']){					
						$goodQuery = "
							g_coupon_idx = '".$_POST['good_coupon_idx'][$g]."',
							g_coupon_pay = '".$_POST['good_coupon_pay'][$g]."',
						";
					}
				}
			}
			######################################################### 상품별 쿠폰 적용 ######################################################### ED

			//주문상품 정보 테이블에 입력
			$sql = "INSERT INTO ".$tbl_order_good." SET
				order_no='$order_no',
				order_id='$order_id',
				g_idx='".$arrList["list"][$i]["g_idx"]."',
				g_cat_no='".$arrList["list"][$i]["cat_no"]."',
				g_code='".$arrList["list"][$i]["g_code"]."',
				g_name='".$arrList["list"][$i]["g_name"]."',
				g_vendor='".$arrList["list"][$i]["vendor"]."',
				g_brand='".$arrList["list"][$i]["brand"]."',
				g_model='".$arrList["list"][$i]["model"]."',
				g_price='".$arrList["list"][$i]["price"]."',
				g_qty='".$arrList["list"][$i]["qty"]."',
				g_point='".$thisPoint."',
				g_opt_1='".$arrOpt1[$i]['0']."',
				g_opt_1_price='".$arrOpt1[$i]['1']."',
				g_opt_2='".$arrOpt2[$i]['0']."',
				g_opt_2_price='".$arrOpt2[$i]['1']."',
				g_opt_3='".$arrOpt3[$i]['0']."',
				g_opt_3_price='".$arrOpt3[$i]['1']."',
				g_opt_4='".$arrOpt4[$i]['0']."',
				g_opt_4_price='".$arrOpt4[$i]['1']."',
				g_opt_5='".$arrOpt5[$i]['0']."',
				g_opt_5_price='".$arrOpt5[$i]['1']."',
				g_opt_rel_1='".$arrOptRel1[$i]['0']."',
				g_opt_rel_1_price='".$arrOptRel1[$i]['1']."',
				g_opt_rel_2='".$arrOptRel2[$i]['0']."',
				g_opt_rel_2_price='".$arrOptRel2[$i]['1']."',
				$goodQuery
				order_status ='X'
			";
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);
			//	echo $sql;
		}

		//for loop 뒤의 변수 설정
		//주문요약 정보
		if($arrList["total"]==1){
			$order_summary = $arrList["list"][0]["g_name"];
		}else{
			$order_summary = $arrList["list"][0]["g_name"] . " 외 " . ($arrList["total"]-1). "건";
		}
		
		//쿠폰사용처리
		/*
		$arr_coupon_idx = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_idx']);
		$arrCoupon = explode("|", $arr_coupon_idx);
		for($i=0; $i<count($arrCoupon); $i++) {
			$idx = mysqli_real_escape_string($GLOBALS['dblink'], $arrCoupon[$i]);
			###################### 쿠폰 사용등록
			if($idx){
				//$coupon_idx = getUserCouponReturn($idx, $order_id);
				$sql_up = "UPDATE tbl_mycoupon SET coupon_use='Y', udate=now()  WHERE idx='".$idx."' ";
				$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);
			}			
		}
		*/
		$coupon_idx = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['coupon_idx']);
		if($coupon_idx){
			//$coupon_idx = getUserCouponReturn($idx, $order_id);
			$sql_up = "UPDATE tbl_mycoupon SET coupon_use='Y', udate=now()  WHERE idx='".$coupon_idx."' ";
			$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);
		}			

		//상품권사용처리
		if($_POST['giftcard_idx']) {
			$sql_up = "UPDATE tbl_mygiftcard SET giftcard_use='Y', udate=now()  WHERE idx='".$_POST['giftcard_idx']."' ";
			$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);
		}
		
		//상품권사용후 잔액 적립금으로 전환
		if($_POST['addpoint']>0) {	
			$RS2 = setPlusPoint($order_id, $_POST['addpoint'], mysqli_real_escape_string($GLOBALS['dblink'], $order_summary)." 구매, 상품권 잔액 적립");
		}


		//배송비 -> 합계금액 (적립금사용, 배송비를 포함하지 않은 순수 금액+옵션가격)이 무료배송금액보다 작을 때 배송비 포함시킴
		if($TotalAmount < $GLOBALS["_SITE"]["SHOP"]["SHIP"]["FREE_PRICE"]){
			$ship_price = $GLOBALS["_SITE"]["SHOP"]["SHIP"]["SHIP_PRICE"];
		}else{
			$ship_price = 0 ;
		}

		$couponPrice = str_replace(",","",$_POST['coupon_price']);
		$giftcardPrice = str_replace(",","",$_POST['giftcard_price']);

		//실 결제금액
		//	$PayAmount = $TotalAmount + $ship_price - $_POST['using_point'] - $couponPrice - $giftcardPrice;
		$ship_price		= $_POST['shipPrice'];						# 배송비를 전달받아서 입력
		$TotalAmount	= $_POST['hiddenPayAmount']-$ship_price;	# 배송비 포함전 금액
		$PayAmount		= $_POST['hiddenPayAmount'];				# 결제금액
		
		if($PayAmount<0) {
			$PayAmount = 0;
		}
		//for loop 뒤의 변수 설정


		//사용한 적립금 차감
		if($order_id != "guest" && $_POST['using_point'] > 0){
			$RS = setMinusPoint($order_id, $_POST['using_point'], mysqli_real_escape_string($GLOBALS['dblink'], $order_summary)." 구매");
		}

		//분할배송 상품개수/받으시는분/연락처/주소
		if($_POST['shipment']>0){
			$arrIdx			= "";
			$arrQty			= "";
			$arrName		= "";
			$arrPhone		= "";
			$arrZip			= "";
			$arrAddress		= "";
			$arrAddressExt	= "";
			for($i=0;$i<count($_POST['arr_idx']);$i++){
				$arrIdx .= "|".$_POST['arr_idx'][$i]."|";
			}
			for($i=0;$i<count($_POST['arr_qty']);$i++){
				$arrQty .= "|".$_POST['arr_qty'][$i]."|";
			}
			for($i=0;$i<count($_POST['arr_name']);$i++){
				$arrName .= "|".$_POST['arr_name'][$i]."|";
			}
			for($i=0;$i<count($_POST['arr_phone1']);$i++){
				$arrPhone .= "|".$_POST['arr_phone1'][$i]."-".$_POST['arr_phone2'][$i]."-".$_POST['arr_phone3'][$i]."|";
			}
			for($i=0;$i<count($_POST['arr_zip']);$i++){
				$arrZip .= "|".$_POST['arr_zip'][$i]."|";
			}
			for($i=0;$i<count($_POST['arr_address']);$i++){
				$arrAddress .= "|".$_POST['arr_address'][$i]."|";
			}
			for($i=0;$i<count($_POST['arr_address_ext']);$i++){
				$arrAddressExt .= "|".$_POST['arr_address_ext'][$i]."|";
			}
			$subInsertQuery = "
				arrIdx='".$arrIdx."',
				arrQty='".$arrQty."',
				arrName='".$arrName."',
				arrPhone='".$arrPhone."',
				arrZip='".$arrZip."',
				arrAddress='".$arrAddress."',
				arrAddressExt='".$arrAddressExt."',
				shipment='".$_POST['shipment']."',
			";
		}

		if($_POST['tmp_comment']!="기타"){
			$_POST['order_comment'] = $_POST['tmp_comment'];
		}

		//주문정보 테이블에 입력
		$sql = "INSERT INTO ".$tbl_order_info." SET
			order_no='$order_no',
			order_summary='$order_summary',
			order_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_name'])."',
			order_id='$order_id',
			order_regnum1='P',
			order_regnum2='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_pw'])."',
			order_phone='$order_phone',
			order_mobile='$order_mobile',
			order_zip='$order_zip',
			order_address='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_address'])."',
			order_address_ext='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_address_ext'])."',
			order_email='".$ordermail."',
			order_class='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_class'])."',
			order_cname='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_cname'])."',
			order_cust='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_cust'])."',
			ship_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_name'])."',
			ship_phone='$ship_phone',
			ship_mobile='$ship_mobile',
			ship_zip='$ship_zip',
			ship_address='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_address'])."',
			ship_address_ext='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_address_ext'])."',
			ship_email='".$shipemail."',
			pay_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['pay_type'])."',
			bank_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['bank_type'])."',
			bank_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['bank_name'])."',
			bank_date='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['bank_date'])."',
			using_point='".nullZero($_POST['using_point'])."',
			using_point_idx='".nullZero($RS)."',
			add_point='".nullZero($_POST['addpoint'])."',
			add_point_idx='".nullZero($RS2)."',
			coupon_amount='".nullZero($couponPrice)."',
			giftcard_amount='".nullZero($giftcardPrice)."',
			coupon_idx='".$coupon_idx."', 
			giftcard_idx='".$_POST['giftcard_idx']."',
			ship_amount='$ship_price',
			login_amount='".nullZero($_POST['loginAmount'])."',
			birth_amount='".nullZero($_POST['birthsale'])."',
			total_amount='$TotalAmount',
			pay_amount='$PayAmount',
			order_date=now(),
			order_state='$order_state',
			ipkum_date='$ipkum_date',
			shipping_date='$shipping_date',
			shipping_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['shipping_type'])."',
			order_comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_comment'])."',			
			cash_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cash_name'])."',
			cash_request='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cash_request'])."',
			cash_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cash_type'])."',
			cash_num='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cash_num'])."',
			mail_sms='".$_POST['sendgb']."',
			giftgb='".$_POST['giftgb']."',
			pcmo='".$_POST['pcmo']."',
			$subInsertQuery
			ip='".$_SERVER[REMOTE_ADDR]."'
		";
	    $rs = mysqli_query($GLOBALS['dblink'], $sql);
		// echo $sql;
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			//주문직전 장바구니에서 장바구니 번호 선택
			/* 
			************************************************* 결제후 삭제로 변경됨 밥스누 201806
			$sql = "SELECT c_idx FROM ".$tbl_order_cart." 
				WHERE order_no='$order_no'
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			$oc_total = mysqli_num_rows($rs);

			if($oc_total > 0){
				for($i=0;$i<$oc_total; $i++){
					$row = mysqli_fetch_assoc($rs);
					//장바구니에서 주문한 상품 삭제
					$sql = "DELETE FROM ".$tbl_cart." 
						WHERE c_idx = '".$row['c_idx']."'
					";
					mysqli_query($GLOBALS['dblink'], $sql);
				}
			}

			//주문직전 장바구니에서 삭제
			$sql = "DELETE FROM ".$tbl_order_cart." 
				WHERE order_no='$order_no'
			";
			$rs = mysqli_query($GLOBALS['dblink'], $sql);
			***************************************************
			*/


			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//장바구니에서 삭제 밥스누 적용
function delcartLast($order_no){
	$tbl_cart = $GLOBALS["_conf_tbl"]["shop_cart"];//장바구니
	$tbl_order_cart = $GLOBALS["_conf_tbl"]["shop_order_cart"];//주문직전 장바구니

	if($order_no){
		$sql = "SELECT c_idx FROM ".$tbl_order_cart." 
			WHERE order_no='$order_no'
		";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$oc_total = mysqli_num_rows($rs);

		if($oc_total > 0){
			for($i=0;$i<$oc_total; $i++){
				$row = mysqli_fetch_assoc($rs);
				//장바구니에서 주문한 상품 삭제
				$sql = "DELETE FROM ".$tbl_cart." 
					WHERE c_idx = '".$row['c_idx']."'
				";
				mysqli_query($GLOBALS['dblink'], $sql);
			}
		}

		//주문직전 장바구니에서 삭제
		$sql = "DELETE FROM ".$tbl_order_cart." 
			WHERE order_no='$order_no'
		";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		return true;
	}else{
		return false;
	}	
}

//주문서 입력
function setEscrowInfo($order_no, $bank_type, $bank_date){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블

	//주문정보 테이블에 수정
	$sql = "UPDATE ".$tbl_order_info." SET
		bank_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['bank_type'])."',
		bank_date='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['bank_date'])."'
		WHERE order_no='$order_no'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	if($rs){
		return true;
	}else{
		return false;
	}
}


//주문정보 가져오기
function getOrderInfo($user_id, $tp, $order_no){
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];//상품 테이블
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	//변수 설정
	if($tp=="1"){
		$order_id = $user_id;
	}else{
		$order_id = "guest";
	}
	
	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
    $sql .= "WHERE A.order_id='$order_id' AND A.order_no='$order_no' ";
	//echo $sql;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

    if($total_rs > 0){
        $list['total'] = $total_rs;

        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }

		//주문상품 목록
		$sql  = "SELECT B.*, C.image_m as image_s, C.sale_price, C.p_price, C.price ";
		$sql .= "FROM ".$tbl_order_good." B LEFT JOIN ".$tbl_good." C ON B.g_idx=C.idx ";
		$sql .= "WHERE B.order_no='$order_no' ";
		//echo $sql;

		$rs_good = mysqli_query($GLOBALS['dblink'], $sql);
		$total_good = mysqli_affected_rows($GLOBALS['dblink']);
		if($total_good > 0){
			$list['good_total'] = $total_good;

			for($i=0; $i < $total_good; $i++){
				$list['good_list'][$i] = mysqli_fetch_assoc($rs_good);
			}
		}

    }else{
        $list['total'] = 0;
    }
    return $list;
}

//주문정보 가져오기 - 손님
function getOrderInfoGuest($order_name, $pw, $order_no){
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];//상품 테이블
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	$que_where = " AND A.order_id='guest' ";
	$que_where .= " AND A.order_name='$order_name' ";
	$que_where .= " AND A.order_regnum2='$pw' ";
	$que_where .= " AND A.order_no='$order_no' ";
	
	//목록
    //$sql  = "SELECT A.*, B.subject,B.contents ";
	$sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
	//$sql .= "LEFT JOIN tbl_board_delivery B ON A.shipping_company=B.idx ";
    $sql .= "WHERE 1=1 $que_where ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

    if($total_rs > 0){
        $list['total'] = $total_rs;

        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }

		//주문상품 목록
		$sql  = "SELECT B.*, C.image_s, C.author_name ";
		$sql .= "FROM ".$tbl_order_good." B LEFT JOIN ".$tbl_good." C ON B.g_idx=C.idx ";
		$sql .= "WHERE B.order_no='$order_no' ";

		$rs_good = mysqli_query($GLOBALS['dblink'], $sql);
		$total_good = mysqli_affected_rows($GLOBALS['dblink']);
		if($total_good > 0){
			$list['good_total'] = $total_good;

			for($i=0; $i < $total_good; $i++){
				$list['good_list'][$i] = mysqli_fetch_assoc($rs_good);
			}
		}

    }else{
        $list['total'] = 0;
    }
    return $list;
}

//주문정보 가져오기 - 관리자
function getOrderGoodList($order_no){
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_good." A ";
    $sql .= "WHERE A.order_no='$order_no' ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

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

//주문정보 가져오기
function orderGoodList($order_id, $s_date, $e_date, $order_state, $scale, $offset=0, $subQuery=""){
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];	//상품 주문정보 테이블 A
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];	//상품 주문정보 테이블 B

	$que_where = "AND B.order_id='$order_id' AND length(B.order_no_parent)<1 ";
	if($s_date){
		$que_where .= "AND B.order_date >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND B.order_date <='$e_date 23:59:59' ";
	}

	if($order_state) {
		$orderstate = explode(",", $order_state);
		for($i=0; $i < count($orderstate); $i++){
			$str_state .= "'".$orderstate[$i]."'";
			if($i != count($orderstate)-1){
				$str_state .= ",";
			}
		}
		$que_where .= "AND B.order_state in ($str_state) ";
	}

	$que_where .= $subQuery;

	//카운트
	$sql = "select count(A.idx) from ".$tbl_order_good." A JOIN ".$tbl_order_info." AS B ON A.order_no=B.order_no WHERE 1=1 $que_where  ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];	
	//echo $sql;

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_good." A JOIN ".$tbl_order_info." AS B ON A.order_no=B.order_no ";
    $sql .= "WHERE 1=1 $que_where ORDER BY A.idx DESC ";
	//	echo "//////////////////////////".$sql;

	if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		//echo $sql;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }

    return $list;
}

//주문정보 가져오기 - 주문상품관리(상품별)
function getOrderUserList($userID, $s_date="", $e_date="", $subQuery=""){
	if($s_date){
		$que_where .= "AND B.order_date >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND B.order_date <='$e_date 23:59:59' ";
	}
	$que_where .= $subQuery;

	//목록
    $sql  = "SELECT A.*,G.price,G.p_price,G.sale_price ,G.image_m, G.special_show, G.best_show
		, B.order_date, B.total_amount, B.pay_amount, B.re_amount, B.order_no_parent, B.reserve_state, B.ipkum_date, B.order_state, B.shipping_no
		, B.ship_name, B.ship_mobile, B.ship_zip, B.ship_address, B.ship_address_ext, B.order_comment, B.shipping_order_no
		FROM tbl_shop_order_good A 
		JOIN tbl_shop_order_info AS B ON A.order_no=B.order_no 
		JOIN tbl_shop_good AS G ON A.g_idx=G.idx
		WHERE 1=1 AND B.order_id='".$userID."' AND B.order_state not IN (10) ".$que_where."  ORDER BY B.idx DESC
	";
	
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

	//	echo $sql;

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
//주문정보 가져오기 - 주문상품관리
function getOrderUserList2($userID, $s_date="", $e_date="", $subQuery="", $scale=0, $offset=0){
	if($s_date){
		$que_where .= "AND A.order_date >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND A.order_date <='$e_date 23:59:59' ";
	}
	$que_where .= $subQuery;

	$sql  = "SELECT count(A.idx)
		FROM tbl_shop_order_info A 
		WHERE 1=1 AND A.order_id='".$userID."' AND A.order_state not IN (10) ".$que_where." ";	
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];	
	
	if($total_rs > 0){
        $list['total'] = $total_rs;

		//목록
		$sql  = "SELECT A.*
			FROM tbl_shop_order_info A 
			WHERE 1=1 AND A.order_id='".$userID."' AND A.order_state not IN (10) ".$que_where."  ORDER BY A.order_date DESC
		";	
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}
		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

		//echo $sql;

		if($total_rs > 0){
			$list['list']['total'] = $total_rs;

			for($i=0; $i < $total_rs; $i++){
				$list['list'][$i] = mysqli_fetch_assoc($rs);
				//주문상품 목록
				$sql  = "SELECT B.*, C.image_m, C.p_price, C.special_show, C.best_show ";
				$sql .= "FROM tbl_shop_order_good B LEFT JOIN tbl_shop_good C ON B.g_idx=C.idx ";
				$sql .= "WHERE B.order_no='".$list['list'][$i]['order_no']."' ";

				$rs_good = mysqli_query($GLOBALS['dblink'], $sql);
				$total_good = mysqli_affected_rows($GLOBALS['dblink']);
				if($total_good > 0){
					$list['list'][$i]['good_total'] = $total_good;

					for($j=0; $j < $total_good; $j++){
						$list['list'][$i]['goodlist'][$j] = mysqli_fetch_assoc($rs_good);
					}
				}
			}
		}else{
			$list['total'] = 0;
		}
	}else{
		$list['total'] = 0;
	}
    return $list;
}

//주문정보 가져오기 - 관리자
function getOrderInfoAdmin($order_no){
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];//상품 테이블
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블


	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
    $sql .= "WHERE A.order_no='$order_no' ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

    if($total_rs > 0){
        $list['total'] = $total_rs;

        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }

		//주문상품 목록
		$sql  = "SELECT B.*, C.image_s, C.p_price, C.member_choice, C.member_price, C.member_sale ";
		$sql .= "FROM ".$tbl_order_good." B LEFT JOIN ".$tbl_good." C ON B.g_idx=C.idx ";
		$sql .= "WHERE B.order_no='$order_no' ";

		$rs_good = mysqli_query($GLOBALS['dblink'], $sql);
		$total_good = mysqli_affected_rows($GLOBALS['dblink']);
		if($total_good > 0){
			$list['good_total'] = $total_good;

			for($i=0; $i < $total_good; $i++){
				$list['good_list'][$i] = mysqli_fetch_assoc($rs_good);
			}
		}

    }else{
        $list['total'] = 0;
    }
    return $list;
}
//주문정보 수정 - 관리자
function setOrderInfoSeminar($order_no){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//주문정보 테이블
	//주문정보 테이블 수정
	$sql = "UPDATE ".$tbl_order_info." SET
		order_state='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_state'])."'		
		WHERE order_no='$order_no'
	";
	//echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//현재 주문정보 가져오기
	$arrInfo = getOrderInfoAdmin($order_no);

	// 환불완료 알림톡
	if($_POST['order_state']=="13" && $_POST['allim']=="Y"){
		if($_POST['snsflag']=="영상구매"){
			kakaoAllim("34", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], "", "", "", $arrInfo["list"][0]["order_summary"], "");
		}else{
			kakaoAllim("31", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], "", "", "", $arrInfo["list"][0]["order_summary"], "");
		}
	}

	if($rs){
		return true;
    }else{
        return false;
    }
}
function setOrderFinish($order_no){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//주문정보 테이블

	$arrInfo = getOrderInfoAdmin($order_no);

	if($arrInfo['list'][0]['pay_point']!="Y"){
		for($i=0;$i<$arrInfo["good_total"];$i++){
			//적립금 계산
			$pay_plus_point += $arrInfo["good_list"][$i]['g_point'];
		}
		//적립해줘야할 금액이 있다면 적립
		if($pay_plus_point > 0 && $arrInfo["list"][0]["order_id"] !="guest"){
			$RS = setPlusPoint($arrInfo["list"][0]["order_id"], $pay_plus_point, $arrInfo["list"][0]["order_summary"] . " 구매확정");
			if($RS > 0){
				$p_sql = " , pay_point='Y', pay_point_date=now(), pay_point_idx='$RS' ";			
			}else{
				//jsMsg("적립금 지금에 실패하였습니다.");
			}
		}
	}

	$sql = "UPDATE ".$tbl_order_info." SET
	order_state = '19'
	".$p_sql."
	WHERE order_no='$order_no'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
}

//주문정보 수정 - 관리자
function setOrderInfoAdmin($order_no){
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];//상품 테이블
	$tbl_good_opt_rel = $GLOBALS["_conf_tbl"]["shop_good_opt_rel"];//상품 주문정보 테이블
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문상품 테이블


	//현재 주문정보 가져오기
	$arrInfo = getOrderInfoAdmin($order_no);


	//적립금 지급 처리
	//if($_POST['pay_point']=="Y"){
	$snsFlag = true;
	if($_POST['order_state']=="9" && $_POST['pay_point']=="Y") {
		/**************** 적립금 구매 확정시 발급으로 변경함
		for($i=0;$i<$arrInfo["good_total"];$i++){
			//적립금 계산
			$pay_plus_point += $arrInfo["good_list"][$i]['g_point'];
		}
		//적립해줘야할 금액이 있다면 적립
		if($pay_plus_point > 0 && $arrInfo["list"][0]["order_id"] !="guest"){
			$RS = setPlusPoint($arrInfo["list"][0]["order_id"], $pay_plus_point, $arrInfo["list"][0]["order_summary"] . " 구매");
			if($RS > 0){
				$p_sql = " pay_point='Y', pay_point_date=now(), pay_point_idx='$RS', ";
			}else{
				//jsMsg("적립금 지금에 실패하였습니다.");
			}
		}
		//발송완료 알림톡
		kakaoAllim("16", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], $arrInfo["list"][0]["order_summary"], $arrInfo["list"][0]["order_no"], $_POST['shipping_no'], "", "");
		$snsFlag = false;
		*********************************************************/
	}
	// 입금완료 알림톡
	if($_POST['order_state']=="8" && $arrInfo['list'][0]["mail_sms"]!="send"){
		//$note1 = weekday($arrInfo['list'][0]['shipping_date'])."요일";
		$note1 = $arrInfo['list'][0]['shipping_date'].weekday($arrInfo['list'][0]['shipping_date'])."요일";
		$note2 = $arrInfo['list'][0]['order_summary'];

		$sql = "update tbl_shop_order_info set mail_sms='send',send_date=now() where order_no='".$arrInfo['list'][0]["order_no"]."' ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		kakaoApiTalk("G08", $arrInfo['list'][0]['order_cname'], $arrInfo['list'][0]['order_mobile'], $arrInfo['list'][0]['order_id'], $note1, $note2, "", "", "");
		//	kakaoAllim("13", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], $arrInfo["list"][0]["order_summary"], $arrInfo["list"][0]["order_no"], "", "", "");		
	}
	// 배송중 알림톡
	if($_POST['order_state']=="8" && $_POST['allim']=="Y"){
		kakaoAllim("004", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], $arrInfo['list'][0]['order_id'], substr($arrInfo["list"][0]["order_date"],0,10), $arrInfo["list"][0]["order_summary"], $arrInfo["list"][0]["shipping_no"], "", "");		
	}
	// 배송완료 알림톡
	if($_POST['order_state']=="9" && $_POST['allim']=="Y" && $snsFlag){
		kakaoAllim("005", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], $arrInfo['list'][0]['order_id'], $arrInfo["list"][0]["order_no"], $arrInfo["list"][0]["order_summary"], "", "", "");		
	}
	// 환불완료 알림톡
	if($_POST['order_state']=="18" && $_POST['allim']=="Y"){
		kakaoAllim("006", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], $arrInfo['list'][0]['order_id'], $arrInfo["list"][0]["order_no"], $arrInfo["list"][0]["order_summary"], $arrInfo["list"][0]["pay_amount"], "", "");		
	}	
	// 교환신청 알림톡
	if($_POST['order_state']=="3" && $_POST['allim']=="Y" && $_POST['re_goodname']){
		kakaoAllim("012", $arrInfo['list'][0]['order_name'], $arrInfo['list'][0]['order_mobile'], $arrInfo['list'][0]['order_id'], $arrInfo["list"][0]["order_summary"], $_POST['re_goodname'], "", "", "");		
	}	

	//재고수량 차감 처리
	if($_POST['stock_apply']=="Y"){
		for($i=0;$i<$arrInfo["good_total"];$i++){
			$arrList = getGoodInfo($arrInfo["good_list"][$i]["g_idx"]);

			//재고관리를 할 경우에는 상품재고수량 감소시킴
			if($arrList["list"][0]['stock_type']=="2"){	## 품절인경우
				continue;
			}else{
				$sql = "UPDATE $tbl_good SET
				stock = stock - ".$arrInfo["good_list"][$i]["g_qty"]."
				WHERE idx = '".$arrInfo["good_list"][$i]["g_idx"]."'
				";
				$rs = mysqli_query($GLOBALS['dblink'], $sql);
			}			
		}
		$s_sql = " stock_apply='Y', stock_apply_date=now(), ";
	}

	## 상품별 주문상태 변경
	$cancelCnt = 0;
	for($i=0;$i<count($_POST["order_status"]);$i++){
		$arrOrderStatus = explode("|",$_POST["order_status"][$i]);
		
		if($_POST["order_status_all"]){	## 전체상품상태변경 값이 있으면 전체 상품 일괄 변경
			$sql = "update tbl_shop_order_good set order_status='".$_POST["order_status_all"]."' where idx='".$arrOrderStatus[0]."'";
			mysqli_query($GLOBALS['dblink'], $sql);
		}else{
			$sql = "update tbl_shop_order_good set order_status='".$arrOrderStatus[1]."' where idx='".$arrOrderStatus[0]."'";
			mysqli_query($GLOBALS['dblink'], $sql);
		}

		if($_POST['order_state']=="9" && $arrOrderStatus[1]=="6"){	## 주문 상태값이 발송완료이고 상품상태값이 배송준비중이면 해당상품만 발송완료상태로 변경
			$sql = "update tbl_shop_order_good set order_status='9' where idx='".$arrOrderStatus[0]."'";
			mysqli_query($GLOBALS['dblink'], $sql);
		}
		############################# 부분취소/교환/환불 처리 ## 상품별로 		
		if($arrOrderStatus[1]=="4"){
			$cancelCnt++;
		}
	}
	if($cancelCnt > 0 && $cancelCnt < count($_POST["order_status"]) ){	
		$_POST['order_state']="4"; ############################# 부분취소/교환/환불 처리
	}

	//카드취소
	if( ($_POST['order_state']=="3" || ($_POST['order_state']=="5" && $arrInfo["list"][0]["charge_type"]=="반품")) && $arrInfo["list"][0]["pay_type"]!="bank" && $arrInfo["list"][0]["tid"] && $arrInfo["list"][0]["handling_date"]=="0000-00-00 00:00:00") {
	//		$c_sql = " handling_date = now(), ";
	}
	if($_POST['handling_date']){
		$c_sql = " handling_date = '".$_POST['handling_date']." 00:00:00', ";
	}

	if(!$_POST['shipping_date']){
		$_POST['shipping_date'] = "0000-00-00";
	}
	$subQuery = "		
		,claim_type			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['claim_type'])."'
		,claim_comment		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['claim_comment'])."'
		,refund_bankname		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['refund_bankname'])."'
		,refund_username		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['refund_username'])."'
		,refund_number			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['refund_number'])."'
		,re_goodname			='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['re_goodname'])."'		
	";
	if($_POST['claim_amount']>0){
		$subQuery .= "
			,claim_amount		='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['claim_amount'])."'
		";
	}
	if($_POST['claim_date']){
		$subQuery .= "
			,claim_date		='".$_POST['claim_date']." 00:00:00'
		";
	}
	//주문정보 테이블 수정
	$sql = "UPDATE ".$tbl_order_info." SET
		order_state='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_state'])."',		
		shipping_date='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['shipping_date'])."',		
		shipping_no='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['shipping_no'])."',
		cash_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cash_name'])."',
		$p_sql
		$s_sql
		$c_sql		
		ship_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_name'])."',
		ship_zip='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_zip'])."',
		ship_address='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_address'])."',
		ship_address_ext='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_address_ext'])."',
		ship_mobile='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_mobile'])."',
		ship_email='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['ship_email'])."',
		cash_request='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cash_request'])."',
		cash_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cash_type'])."',
		cash_num='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cash_num'])."',
		refund_username='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['refund_username'])."',
		refund_number='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['refund_number'])."',
		admin_comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['admin_comment'])."',
		order_comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_comment'])."'
		".$subQuery."
		WHERE order_no='$order_no'
	";
	//echo $sql;
	//exit();
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	
	

	//주문상품 판매정보 업데이트
	/****	해당부분은 사용안함 하이덴탈은 각상품별 부분취소가 가능하여 변경됨
	if($_POST['order_state'] > 5){
		$order_good_status = "o";
	}else{
		$order_good_status = "x";
	}
	$sql = "UPDATE ".$tbl_order_good." SET
		order_status='$order_good_status'
		WHERE order_no='$order_no'
	";
	mysqli_query($GLOBALS['dblink'], $sql);
	****/

    if($rs){
		return true;
    }else{
        return false;
    }
}


//주문정보 삭제 - 관리자
function delOrderInfoAdmin($order_no, $direct_gb=""){
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];//상품 테이블
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블
	$tbl_point = $GLOBALS["_conf_tbl"]["point"];//적립금 테이블

	$arrInfo = getOrderInfoAdmin($order_no);
	
	if($arrInfo["list"][0]["using_point_idx"]>0) { //적립금사용시 롤백
		if($direct_gb=="Y") {
			$sql_del = "DELETE FROM ".$tbl_point." WHERE idx='".$arrInfo["list"][0]["using_point_idx"]."' ";
			$rs_del = mysqli_query($GLOBALS['dblink'], $sql_del);
		} else {
			$arrPointInfo = getArticleInfo($tbl_point, $arrInfo["list"][0]["using_point_idx"]);
			$RS = setPlusPoint($arrPointInfo["list"][0]["user_id"], $arrPointInfo["list"][0]["minus"], mysqli_real_escape_string($GLOBALS['dblink'], $arrPointInfo["list"][0]["contents"])." 구매취소");
		}
	}

	if($arrInfo["list"][0]["add_point_idx"]>0) { //상품권구입시 남은 적립금 환불 롤백
		if($direct_gb=="Y") {
			$sql_del = "DELETE FROM ".$tbl_point." WHERE idx='".$arrInfo["list"][0]["add_point_idx"]."' ";
			$rs_del = mysqli_query($GLOBALS['dblink'], $sql_del);
		} else {
			$arrPointInfo2 = getArticleInfo($tbl_point, $arrInfo["list"][0]["add_point_idx"]);
			$RS = setMinusPoint($arrPointInfo2["list"][0]["user_id"], $arrPointInfo2["list"][0]["plus"], mysqli_real_escape_string($GLOBALS['dblink'], $arrPointInfo2["list"][0]["contents"]).", 구매취소");
		}
	}

	############################## 쿠폰 미사용 전환 
	$sql = "select * FROM tbl_shop_order_cart WHERE order_no='$order_no' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_num_rows($rs);
	// 페이지 네비게이션 오프셋 지정.		
	for($i=0; $i < $total; $i++){
		$list['list'][$i] = mysqli_fetch_assoc($rs);
		if($list['list'][$i]['g_coupon_idx']){
			$sql_up = "UPDATE tbl_mycoupon SET coupon_use='N' WHERE idx='".$list['list'][$i]['g_coupon_idx']."' ";		## 쿠폰 미사용 처리
			//$sql_up = "delete from tbl_mycoupon WHERE idx='".$idx."' ";
			$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);

			$sql_up2 = "UPDATE tbl_shop_order_cart SET g_coupon_idx='0', g_coupon_pay='0' WHERE p_idx='".$list['list'][$i]['p_idx']."' ";	## 주문카트에서 쿠폰 삭제
			//$sql_up = "delete from tbl_mycoupon WHERE idx='".$idx."' ";
			$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up2);
		}
	}

	if($arrInfo["list"][0]["coupon_idx"]>0) { //쿠폰사용시 롤백
		
		$arrCoupon = explode("|", $arrInfo["list"][0]["coupon_idx"]);
		for($i=0; $i<count($arrCoupon); $i++) {
			$idx = mysqli_real_escape_string($GLOBALS['dblink'], $arrCoupon[$i]);

			$sql_up = "UPDATE tbl_mycoupon SET coupon_use='N' WHERE idx='".$idx."' ";
			//$sql_up = "delete from tbl_mycoupon WHERE idx='".$idx."' ";
			$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);
		}
	}
	if($arrInfo["list"][0]["giftcard_idx"]>0) { //상품권사용시 롤백
		$sql_up = "UPDATE tbl_mygiftcard SET giftcard_use='N' WHERE idx='".$arrInfo["list"][0]["giftcard_idx"]."' ";
		$rs_up = mysqli_query($GLOBALS['dblink'], $sql_up);
	}
	
	//주문정보 테이블 삭제
	$sql = "DELETE FROM ".$tbl_order_info." WHERE order_no='$order_no' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	//주문상품 테이블 삭제
	$sql = "DELETE FROM ".$tbl_order_good." WHERE order_no='$order_no' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

    if($rs){
		return true;
    }else{
        return false;
    }
}
//주문정보 가져오기 /include/order_delivery_inquiry.php 전용
function getOrderListUser($order_id, $s_date, $e_date, $order_state, $scale, $offset=0, $viewflag="G"){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블

	$que_where = "AND A.order_id='$order_id'";
	if($s_date){
		$que_where .= "AND A.order_date >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND A.order_date <='$e_date 23:59:59' ";
	}

	if($order_state) {
		$orderstate = explode(",", $order_state);
		for($i=0; $i < count($orderstate); $i++){
			$str_state .= "'".$orderstate[$i]."'";
			if($i != count($orderstate)-1){
					$str_state .= ",";
			}
		}
		$que_where .= "AND A.order_state in ($str_state) ";
	}
	if($viewflag=="G"){
		$que_where .= " AND A.order_state<10 ";
	}else if($viewflag=="S"){
		$que_where .= " AND A.order_state>10 ";
	}

	//카운트
	$sql = "select count(A.idx) from $tbl_order_info A WHERE order_state!=10 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
    $sql .= "WHERE A.order_state!=10 $que_where ORDER BY A.idx DESC ";
	//echo $sql;

	if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }

    return $list;
}
//주문정보 가져오기
function getOrderList($order_id, $s_date, $e_date, $order_state, $scale, $offset=0, $viewflag="G", $subQuery=""){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	$que_where = "AND A.order_id='$order_id'";
	if($s_date){
		$que_where .= "AND A.order_date >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND A.order_date <='$e_date 23:59:59' ";
	}

	if($order_state) {
		$orderstate = explode(",", $order_state);
		for($i=0; $i < count($orderstate); $i++){
			$str_state .= "'".$orderstate[$i]."'";
			if($i != count($orderstate)-1){
					$str_state .= ",";
			}
		}
		$que_where .= "AND A.order_state in ($str_state) ";
	}
	if($viewflag=="G"){
		$que_where .= " AND A.order_state<10 ";
	}else if($viewflag=="S"){
		$que_where .= " AND A.order_state>10 ";
	}
	if($subQuery){
		$que_where .= $subQuery;
	}

	//카운트
	$sql = "select count(A.idx) from $tbl_order_info A WHERE order_state!=10 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysqli_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.*,B.g_idx,B.g_opt_1 ";
    $sql .= "FROM ".$tbl_order_info." A ";
	$sql .= "LEFT JOIN ".$tbl_order_good." B ON A.order_no=B.order_no ";
    $sql .= "WHERE A.order_state!=10 $que_where ORDER BY A.idx DESC ";
	//echo $sql;

	if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }

    return $list;
}
//주문한 상품정보 가져오기
function getOrderImage($user_id, $order_no){
	$tbl_good = $GLOBALS["_conf_tbl"]["shop_good"];//상품 테이블
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	$sql = "SELECT C.image_m as image_s,C.idx FROM ".$tbl_order_info." A ";
	$sql .= " join ". $tbl_order_good ." B ";
	$sql .= " on A.order_no = B.order_no ";
	$sql .= " join ". $tbl_good ." C ";
	$sql .= " on B.g_idx = C.idx ";
	$sql .= " WHERE A.order_id='$user_id' AND A.order_no='$order_no' ";

	//echo $sql;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

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

//주문정보 가져오기 - 비회원
function getOrderListGuest($order_name, $pw, $scale, $offset=0, $order_state=""){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	$que_where = " AND A.order_id='guest' ";
	$que_where .= " AND A.order_name='$order_name' ";
//	$que_where .= " AND A.order_regnum2='$pw' ";
	$que_where .= " AND A.order_no='$pw' ";
	
	if($order_state) {
		$orderstate = explode(",", $order_state);
		for($i=0; $i < count($orderstate); $i++){
			$str_state .= "'".$orderstate[$i]."'";
			if($i != count($orderstate)-1){
					$str_state .= ",";
			}
		}
		$que_where .= "AND A.order_state in ($str_state) ";
	}

	//카운트
	$sql = "select count(A.idx) from $tbl_order_info A WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysql_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    //$sql  = "SELECT A.*, B.subject,B.contents ";
	$sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
	//$sql .= "LEFT JOIN tbl_board_delivery B ON A.shipping_company=B.idx ";
    $sql .= "WHERE 1=1 $que_where ORDER BY A.idx DESC ";

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }

    return $list;
}

//주문정보 가져오기 - 관리자
function getOrderListAdmin($sw, $sk, $s_date, $e_date, $order_state, $scale, $offset=0){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문 상품 테이블


	if($sw=="all"){
		$que_where .= "AND (A.order_name like '%$sk%' OR A.order_id like '%$sk%') ";
	}else if($sw=="name"){
		$que_where .= "AND A.order_name like '%$sk%' ";
	}else if($sw=="id"){
		$que_where .= "AND A.order_id like '%$sk%' ";
	}else if($sw=="id2"){
		$que_where .= "AND A.order_id = '$sk' ";
	}
	
	if($_REQUEST['sk2']) {
		$que_where .= "AND B.g_name like '%".$_REQUEST['sk2']."%' ";
	}
	if($_REQUEST['coupon_amount']=="Y") {
		$que_where .= "AND A.coupon_amount>0 ";
	}
	if($_REQUEST['using_point']=="Y") {
		$que_where .= "AND A.using_point>0 ";
	}

	if($s_date){
		$que_where .= "AND A.".$_REQUEST['sh_date']." >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND A.".$_REQUEST['sh_date']." <='$e_date 23:59:59' ";
	}
	
	if($_REQUEST['order_states']) {
		for($i=0; $i < count($_REQUEST['order_states']); $i++){
			$str_state .= "'".$_REQUEST['order_states'][$i]."'";
			if($i != count($_REQUEST['order_states'])-1){
					$str_state .= ",";
			}
		}
		$que_where .= "AND A.order_state in ($str_state) ";
	}
	if($_REQUEST['orderstate']){
		$arrOrder = str_replace("/", "", mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['orderstate']));
		$str_state =  explode(",",$arrOrder); 
		$que_where .= " and A.order_state regexp '(";

		for($k=0; $k < count($str_state)-1; $k++){
			$que_where .= $str_state[$k];
			if($k != count($str_state)-2) {
				$que_where .= "|";
			}
		}
		$que_where .= ")' ";
	}
	
	if($_GET['mode'] == "1") {
		$que_where .= " and (A.order_state='1' or  A.order_state='6' or  A.order_state='7' or  A.order_state='8' or  A.order_state='9')  ";
		//$que_where .= " and A.order_state in ('1','2','3','4','5','6','7','8','9') ";
	} else if($_GET['mode'] == "2") {
		$que_where .= " and A.order_state regexp '(2|3|4|5)' ";
		//$que_where .= " and A.order_state regexp '(2|3|4|5|6|7|8|9)' ";
	} else if($_GET['mode'] == "3") {
		$que_where .= " and A.order_state = '10' ";
	}
	
	if($_REQUEST['pay_type']) {
		$que_where .= "AND A.pay_type in ('".$_REQUEST['pay_type']."') ";
	}
	if($_REQUEST['shipping_type']=="visit") {
		$que_where .= "AND A.shipping_type in ('visit') ";
	}
	if($_REQUEST['shipping_type']=="delivery") {
		$que_where .= "AND A.shipping_type not in ('visit') ";
	}
	if($_REQUEST['shipping_state']) {
		$que_where .= "AND A.order_state in ('".$_REQUEST['shipping_state']."') ";
	}
	if($_REQUEST['paytype']){
		
		$str_type =  explode(",",$_REQUEST['paytype']); 
		$que_where .= " and A.pay_type regexp '(";

		for($k=0; $k < count($str_type)-1; $k++){
			$que_where .= $str_type[$k];
			if($k != count($str_type)-2) {
				$que_where .= "|";
			}
		}
		$que_where .= ")' ";
	}

	if($_REQUEST['s_price']){
		$que_where .= "AND A.pay_amount >='".str_replace(",", "",$_REQUEST['s_price'])."' ";
	}
	if($_REQUEST['e_price']){
		$que_where .= "AND A.pay_amount <='".str_replace(",", "",$_REQUEST['e_price'])."' ";
	}

	if($order_state){
		$arr_state = explode(",",$order_state);
		for($i=0;$i<count($arr_state);$i++){
			$str_state .= "'".$arr_state[$i]."'";
			if($i != count($arr_state)-1){
				$str_state .= ",";
			}
		}
		
		$que_where .= "AND A.order_state in ($str_state) ";
	}

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
	$sql .= "LEFT JOIN ".$tbl_order_good." B ON A.order_no=B.order_no ";
    $sql .= "WHERE 1=1  $que_where GROUP BY A.order_no ORDER BY A.idx DESC ";
	//	echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	
    $total_rs = mysqli_num_rows($rs);

	/******
	$sql = "select count(A.idx) from ".$tbl_order_good." A ";
	$sql .= "LEFT JOIN ".$tbl_order_info." B ON A.order_no=B.order_no ";
	$sql .= "WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysql_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.idx AS sog_idx, A.*, B.* ";
    $sql .= "FROM ".$tbl_order_good." A ";
	$sql .= "LEFT JOIN ".$tbl_order_info." B ON A.order_no=B.order_no ";
    $sql .= "WHERE 1=1 $que_where ORDER BY A.idx DESC ";
	*******/

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    // offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

			if($scale != "0"){
				$sql .= " limit $offset,$scale ";
			}
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);

		    // offset 을 이용한 limit 가 적용된 갯수
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
	//echo $sql;

    return $list;
}
// 매출 집계용
function getOrderSalesSum($sw, $sk, $s_date, $e_date){
	if($s_date){
		$que_where .= "AND order_date >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND order_date <='$e_date 23:59:59' ";
	}
	
	$sql = "
		SELECT SUM(pay_amount) as tot_amount, SUM(ship_amount) as ship_amount FROM tbl_shop_order_info
		WHERE order_state='9' ".$que_where."
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);	
    $total = mysqli_num_rows($rs);

	$list['list']['total'] = $total;
	for($i=0; $i < $total; $i++){
		$list['list'][$i] = mysqli_fetch_assoc($rs);			
	}
	return $list;
}

//주문정보 가져오기 - 관리자
function getOrderListAll($sw, $sk, $s_date, $e_date, $order_state, $scale, $offset=0, $subQuery=""){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문 상품 테이블

	if($sk){
		if($sw=="all"){
			$que_where .= "AND (A.order_name like '%$sk%' OR A.order_id like '%$sk%') ";
		}else if($sw=="name"){
			$que_where .= "AND A.order_name like '%$sk%' ";
		}else if($sw=="id"){
			$que_where .= "AND A.order_id like '%$sk%' ";
		}else if($sw=="id2"){
			$que_where .= "AND A.order_id = '$sk' ";
		}else if($sw=="ono"){
			$que_where .= "AND A.order_no = '$sk' ";
		}else if($sw=="tel"){
			$que_where .= "AND A.order_mobile like '%$sk%' ";
		}else if($sw=="email"){
			$que_where .= "AND A.order_email like '%$sk%' ";
		}else if($sw=="gcode"){
			$que_where .= "AND B.g_code like '%$sk%' ";
		}else if($sw=="gname"){
			$que_where .= "AND A.order_summary like '%$sk%' ";
		}else if($sw=="order_cname"){
			$que_where .= "AND A.order_cname like '%$sk%' ";
		}else if($sw=="order_cust"){
			$que_where .= "AND A.order_cust like '%$sk%' ";
		}
	}
	
	if($_REQUEST['sk2']) {
		$que_where .= "AND B.g_name like '%".$_REQUEST['sk2']."%' ";
	}
	if($_REQUEST['coupon_amount']=="Y") {
		$que_where .= "AND A.coupon_amount>0 ";
	}
	if($_REQUEST['using_point']=="Y") {
		$que_where .= "AND A.using_point>0 ";
	}
	if($_REQUEST['a_class']) {
		$que_where .= "AND A.order_class ='".$_REQUEST['a_class']."' ";
	}
	if($_REQUEST['shipping_date']) {
		$que_where .= "AND A.shipping_date ='".$_REQUEST['shipping_date']."' ";
	}

	if($s_date){
		$que_where .= "AND A.".$_REQUEST['sh_date']." >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND A.".$_REQUEST['sh_date']." <='$e_date 23:59:59' ";
	}

	if($_GET['s_sdate']){	## 발송일시 시작일
		$que_where .= "AND A.shipping_date >='".$_GET['s_sdate']."' ";
	}
	if($_GET['e_sdate']){	## 발송일시 종료일
		$que_where .= "AND A.shipping_date <='".$_GET['e_sdate']."' ";
	}

	if($_GET['s_hdate']){	## 처리일시 시작일
		$que_where .= "AND A.handling_date >='".$_GET['s_hdate']." 00:00:00' ";
	}
	if($_GET['e_hdate']){	## 처리일시 종료일
		$que_where .= "AND A.handling_date <='".$_GET['e_hdate']." 23:59:59' ";
	}

	if($_GET['s_cdate']){	## 요청 시작일
		$que_where .= "AND A.claim_date >='".$_GET['s_cdate']." 00:00:00' ";
	}
	if($_GET['e_cdate']){	## 요청 종료일
		$que_where .= "AND A.claim_date <='".$_GET['e_cdate']." 23:59:59' ";
	}
	
	if($_REQUEST['order_states']) {
		for($i=0; $i < count($_REQUEST['order_states']); $i++){
			$str_state .= "'".$_REQUEST['order_states'][$i]."'";
			if($i != count($_REQUEST['order_states'])-1){
					$str_state .= ",";
			}
		}
		$que_where .= "AND A.order_state in ($str_state) ";
	}
	if($_REQUEST['orderstate']){
		$arrOrder = str_replace("/", "", mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['orderstate']));
		$str_state =  explode(",",$arrOrder); 
		$que_where .= " and A.order_state regexp '(";

		for($k=0; $k < count($str_state)-1; $k++){
			$que_where .= $str_state[$k];
			if($k != count($str_state)-2) {
				$que_where .= "|";
			}
		}
		$que_where .= ")' ";
	}
	
	if(!$_GET['cm']){				## 취소/교환/환불
		if($_GET['mode'] == "1") {				## 전체주문조회
			$que_where .= " and A.order_state Not in ('10') ";
		} else if($_GET['mode'] == "2") {		## 취소/교환/반품
			$que_where .= " and A.order_state regexp '(2|3|4|5|16|17|18)' ";
			//$que_where .= " and A.order_state regexp '(2|3|4|5|6|7|8|9)' ";
		} else if($_GET['mode'] == "3") {		## 배송 관리
			$que_where .= " and A.order_state regexp '(6|9)' ";
		} else if($_GET['mode'] == "4") {		## 예약 관리
			$que_where .= " and A.order_state in ('1','2','3','4','5','6','7','8','9','19') AND reserve_state <> 'N' AND reserve_state<>'13' ";
		} else if($_GET['mode'] == "5") {		## 세미나 관리
			$que_where .= " and A.order_state in (11,12,13) ";
		} else if($_GET['mode'] == "6") {		## 강의 관리
			$que_where .= " and A.order_state in (11,13) and B.g_opt_1 in ('free', '30D') ";
		}
	}
	
	if($_REQUEST['pay_type']) {
		$que_where .= "AND A.pay_type in ('".$_REQUEST['pay_type']."') ";
	}
	if($_REQUEST['shipping_type']=="visit") {
		$que_where .= "AND A.shipping_type in ('visit') ";
	}
	if($_REQUEST['shipping_type']=="delivery") {
		$que_where .= "AND A.shipping_type not in ('visit') ";
	}
	if($_REQUEST['shipping_state']) {
		$que_where .= "AND A.order_state in ('".$_REQUEST['shipping_state']."') ";
	}
	if($_REQUEST['paytype']){
		
		$str_type =  explode(",",$_REQUEST['paytype']); 
		$que_where .= " and A.pay_type regexp '(";

		for($k=0; $k < count($str_type)-1; $k++){
			$que_where .= $str_type[$k];
			if($k != count($str_type)-2) {
				$que_where .= "|";
			}
		}
		$que_where .= ")' ";
	}

	if($_REQUEST['s_price']){
		$que_where .= "AND A.pay_amount >='".str_replace(",", "",$_REQUEST['s_price'])."' ";
	}
	if($_REQUEST['e_price']){
		$que_where .= "AND A.pay_amount <='".str_replace(",", "",$_REQUEST['e_price'])."' ";
	}

	if($order_state){
		$arr_state = explode(",",$order_state);
		for($i=0;$i<count($arr_state);$i++){
			$str_state .= "'".$arr_state[$i]."'";
			if($i != count($arr_state)-1){
				$str_state .= ",";
			}
		}
		
		$que_where .= "AND A.order_state in ($str_state) ";
	}

	if($subQuery){
		$que_where .= $subQuery;
	}

	//목록
    $sql  = "SELECT A.*, B.g_idx, B.g_opt_1, B.g_code ";
    $sql .= "FROM ".$tbl_order_info." A ";
	$sql .= "LEFT JOIN ".$tbl_order_good." B ON A.order_no=B.order_no ";
    $sql .= "WHERE 1=1  $que_where GROUP BY A.order_no ";
	//	echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	
    $total_rs = mysqli_num_rows($rs);

	/******
	$sql = "select count(A.idx) from ".$tbl_order_good." A ";
	$sql .= "LEFT JOIN ".$tbl_order_info." B ON A.order_no=B.order_no ";
	$sql .= "WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysql_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.idx AS sog_idx, A.*, B.* ";
    $sql .= "FROM ".$tbl_order_good." A ";
	$sql .= "LEFT JOIN ".$tbl_order_info." B ON A.order_no=B.order_no ";
    $sql .= "WHERE 1=1 $que_where ORDER BY A.idx DESC ";
	*******/

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    // offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }
			if($_GET['rdnm'] && $_GET['rdsc']){
				if($scale != "0"){
					$sql .= " ORDER BY A.".$_GET['rdnm']." ".$_GET['rdsc']." limit $offset,$scale ";
				}else{
					$sql .= " ORDER BY A.".$_GET['rdnm']." ".$_GET['rdsc']." ";
				}
			}else{
				if($scale != "0"){
					$sql .= " ORDER BY A.idx DESC limit $offset,$scale ";
				}else{
					$sql .= " ORDER BY A.idx DESC ";
				}
			}
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);

		    // offset 을 이용한 limit 가 적용된 갯수
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
	//echo $sql;

    return $list;
}

//주문정보 가져오기 - 관리자/엑셀 토탈 카운트용
function getOrderListAdminXls($sw, $sk, $s_date, $e_date, $order_state, $scale, $offset=0){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문 상품 테이블


	if($sw=="all"){
		$que_where .= "AND (B.order_name like '%$sk%' OR B.order_id like '%$sk%') ";
	}else if($sw=="name"){
		$que_where .= "AND B.order_name like '%$sk%' ";
	}else if($sw=="id"){
		$que_where .= "AND B.order_id like '%$sk%' ";
	}else if($sw=="id2"){
		$que_where .= "AND B.order_id = '$sk' ";
	}
	
	if($_REQUEST['sk2']) {
		$que_where .= "AND A.g_name like '%".$_REQUEST['sk2']."%' ";
	}
	if($_REQUEST['coupon_amount']=="Y") {
		$que_where .= "AND B.coupon_amount>0 ";
	}
	if($_REQUEST['using_point']=="Y") {
		$que_where .= "AND B.using_point>0 ";
	}

	if($s_date){
		$que_where .= "AND B.".$_REQUEST['sh_date']." >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND B.".$_REQUEST['sh_date']." <='$e_date 23:59:59' ";
	}
	
	if($_REQUEST['order_states']) {
		for($i=0; $i < count($_REQUEST['order_states']); $i++){
			$str_state .= "'".$_REQUEST['order_states'][$i]."'";
			if($i != count($_REQUEST['order_states'])-1){
					$str_state .= ",";
			}
		}
		$que_where .= "AND B.order_state in ($str_state) ";
	}
	if($_REQUEST['orderstate']){
		$arrOrder = str_replace("/", "", mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['orderstate']));
		$str_state =  explode(",",$arrOrder); 
		$que_where .= " and B.order_state regexp '(";

		for($k=0; $k < count($str_state)-1; $k++){
			$que_where .= $str_state[$k];
			if($k != count($str_state)-2) {
				$que_where .= "|";
			}
		}
		$que_where .= ")' ";
	}
	
	if($_GET['mode'] == "1") {
		$que_where .= " and B.order_state IN ('1','6','7','8','9')  ";
		//$que_where .= " and A.order_state in ('1','2','3','4','5','6','7','8','9') ";
	} else if($_GET['mode'] == "2") {
		$que_where .= " and B.order_state regexp '(2|3|4|5)' ";
		//$que_where .= " and A.order_state regexp '(2|3|4|5|6|7|8|9)' ";
	} else if($_GET['mode'] == "3") {
		$que_where .= " and B.order_state = '10' ";
	}
	
	if($_REQUEST['pay_type']) {
		$que_where .= "AND B.pay_type in ('".$_REQUEST['pay_type']."') ";
	}
	if($_REQUEST['shipping_type']=="visit") {
		$que_where .= "AND B.shipping_type in ('visit') ";
	}
	if($_REQUEST['shipping_type']=="delivery") {
		$que_where .= "AND B.shipping_type not in ('visit') ";
	}
	if($_REQUEST['shipping_state']) {
		$que_where .= "AND B.order_state in ('".$_REQUEST['shipping_state']."') ";
	}
	if($_REQUEST['paytype']){
		
		$str_type =  explode(",",$_REQUEST['paytype']); 
		$que_where .= " and B.pay_type regexp '(";

		for($k=0; $k < count($str_type)-1; $k++){
			$que_where .= $str_type[$k];
			if($k != count($str_type)-2) {
				$que_where .= "|";
			}
		}
		$que_where .= ")' ";
	}

	if($_REQUEST['s_price']){
		$que_where .= "AND B.pay_amount >='".str_replace(",", "",$_REQUEST['s_price'])."' ";
	}
	if($_REQUEST['e_price']){
		$que_where .= "AND B.pay_amount <='".str_replace(",", "",$_REQUEST['e_price'])."' ";
	}

	if($order_state){
		$arr_state = explode(",",$order_state);
		for($i=0;$i<count($arr_state);$i++){
			$str_state .= "'".$arr_state[$i]."'";
			if($i != count($arr_state)-1){
				$str_state .= ",";
			}
		}
		
		$que_where .= "AND B.order_state in ($str_state) ";
	}

	//목록
    $sql  = "
	SELECT SUM(g_qty) as qty, g_name FROM (
		SELECT A.g_qty,A.g_name FROM ".$tbl_order_good." A 
		LEFT JOIN ".$tbl_order_info." B ON A.order_no=B.order_no 
		WHERE 1=1 $que_where ORDER BY A.idx DESC 
	) AS C group by C.g_name order by C.g_name asc 
	";
	//	echo $sql;
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
/*
function getOrderListAdmin($sw, $sk, $s_date, $e_date, $order_state, $scale, $offset=0){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문 상품 테이블


	if($sw=="all"){
		$que_where .= "AND (A.order_name like '%$sk%' OR A.order_id like '%$sk%') ";
	}else if($sw=="name"){
		$que_where .= "AND A.order_name like '%$sk%' ";
	}else if($sw=="id"){
		$que_where .= "AND A.order_id like '%$sk%' ";
	}

	if($s_date){
		$que_where .= "AND A.order_date >='$s_date 00:00:00' ";
	}
	if($e_date){
		$que_where .= "AND A.order_date <='$e_date 23:59:59' ";
	}

	if($order_state){
		$que_where .= "AND A.order_state='$order_state' ";
	}

	//카운트
	$sql = "select count(A.idx) from $tbl_order_info A WHERE 1=1 $que_where ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysql_fetch_row($rs);
    $total_rs = $row['0'];

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
    $sql .= "WHERE 1=1  $que_where ORDER BY A.idx DESC ";

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    // offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

			if($scale != "0"){
				$sql .= " limit $offset,$scale ";
			}
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);

		    // offset 을 이용한 limit 가 적용된 갯수
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
	//echo $sql;

    return $list;
}
*/

//내가 주문한 상품인지 체크
function getMyOrderGood($order_no, $g_idx, $order_id){
	$tbl = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블

	$que_where = "AND A.order_no='$order_no' AND A.g_idx='$g_idx' AND A.order_id='$order_id'";

	//목록
    $sql  = "SELECT A.idx ";
    $sql .= "FROM ".$tbl." A ";
    $sql .= "WHERE 1=1 $que_where ";

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_num_rows($rs);

	if($total > 0){
		return true;
	}else{
		return false;
	}

}

//매출관리
function getAccountStatus($s_date, $e_date){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블

	$sql  = "SELECT count(order_no) as order_count, ";
	$sql .= "SUM(using_point) as using_point, ";
	$sql .= "SUM(ship_amount) as ship_amount, ";
	$sql .= "SUM(total_amount) as total_amount, ";
	$sql .= "SUM(pay_amount) as pay_amount, ";
	$sql .= "LEFT(order_date,10) AS order_date, ";
	$sql .= "pay_type ";

	$sql .= "FROM $tbl_order_info ";

	$sql .= "WHERE order_state  >= '6' AND order_state != '10' ";
	$sql .= "AND order_date >= '$s_date 00:00:00' "; 
	$sql .= "AND order_date <= '$e_date 23:59:59' "; 

	if($_GET['site']) {
		$sql .= "AND order_regnum1='".$_GET['site']."' ";
	}

	//전체 매출뽑기
	$sql_total = $sql . "GROUP BY LEFT(order_date,10) ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql_total);
    $total_rs = mysqli_num_rows($rs);

    if($total_rs > 0){
        $list['total'] = $total_rs;
        for($i=0; $i < $total_rs; $i++){
			$row =  mysqli_fetch_assoc($rs);
            $list['list'][$row['order_date']] = $row;
            $list['list_sum'][order_count] += $row['order_count'];
            $list['list_sum'][using_point] += $row['using_point'];
            $list['list_sum'][ship_amount] += $row['ship_amount'];
            $list['list_sum'][total_amount] += $row['total_amount'];
            $list['list_sum'][pay_amount] += $row['pay_amount'];
        }
	}else{
	    $list['total'] = 0;
	}

	//결제타입별 매출뽑기
	$sql_p_type = $sql . "GROUP BY LEFT(order_date,10), pay_type ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql_p_type);
    $total_rs = mysqli_num_rows($rs);

    if($total_rs > 0){
        $list['p_total'] = $total_rs;
        for($i=0; $i < $total_rs; $i++){
			$row =  mysqli_fetch_assoc($rs);
            $list['p_list'][$row['order_date']][$row['pay_type']] = $row;
            $list['p_list_sum'][$row['pay_type']][pay_amount] += $row['pay_amount'];
        }
	}else{
	    $list['p_total'] = 0;
	}

	return $list;
}

function getBankNameByCode($VIRTUAL_CENTERCD){
	if($VIRTUAL_CENTERCD == "39"){
		return "경남은행";
	}else if($VIRTUAL_CENTERCD == "34"){
		return "광주은행";
	}else if($VIRTUAL_CENTERCD == "04"){
		return "국민은행";
	}else if($VIRTUAL_CENTERCD == "11"){
		return "농협중앙회";
	}else if($VIRTUAL_CENTERCD == "31"){
		return "대구은행";
	}else if($VIRTUAL_CENTERCD == "32"){
		return "부산은행";
	}else if($VIRTUAL_CENTERCD == "02"){
		return "산업은행";
	}else if($VIRTUAL_CENTERCD == "45"){
		return "새마을금고";
	}else if($VIRTUAL_CENTERCD == "07"){
		return "수협중앙회";
	}else if($VIRTUAL_CENTERCD == "48"){
		return "신용협동조합";
	}else if($VIRTUAL_CENTERCD == "26"){
		return "(구)신한은행";
	}else if($VIRTUAL_CENTERCD == "05"){
		return "외환은행";
	}else if($VIRTUAL_CENTERCD == "20"){
		return "우리은행";
	}else if($VIRTUAL_CENTERCD == "71"){
		return "우체국";
	}else if($VIRTUAL_CENTERCD == "37"){
		return "전북은행";
	}else if($VIRTUAL_CENTERCD == "23"){
		return "제일은행";
	}else if($VIRTUAL_CENTERCD == "35"){
		return "제주은행";
	}else if($VIRTUAL_CENTERCD == "21"){
		return "(구)조흥은행";
	}else if($VIRTUAL_CENTERCD == "03"){
		return "중소기업은행";
	}else if($VIRTUAL_CENTERCD == "81"){
		return "하나은행";
	}else if($VIRTUAL_CENTERCD == "88"){
		return "신한은행";
	}else if($VIRTUAL_CENTERCD == "27"){
		return "한미은행";
	}
}

//상품정보 가져오기 - g_code
function getGoodInfoGcode($g_code){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];//상품정보

	//기본정보 가져오기
	$sql  = "SELECT idx ";
	$sql .= "FROM ".$tbl." A ";
	$sql .= " WHERE A.g_code = '$g_code' ";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$row = mysqli_fetch_assoc($rs);
	
	return getGoodInfo($row['idx']);
}


//================================================
// 주문제품 목록 상품연동 CSV
// 주문관리에서 CSV 파일 만들기
// 20100614
//================================================
function getOrderListCSV($sw, $sk, $s_date, $e_date, $order_state, $scale, $offset=0){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문 상품 테이블
	$tbl_category = $GLOBALS["_conf_tbl"]["category"];//카테고리


	if($sw=="all"){
		$que_where .= "AND ( B.order_name like '%$sk%' OR B.order_id like '%$sk%') ";
	}else if($sw=="name"){
		$que_where .= "AND B.order_name like '%$sk%' ";
	}else if($sw=="id"){
		$que_where .= "AND B.order_id like '%$sk%' ";
	}else if($sw=="id2"){
		$que_where .= "AND B.order_id = '$sk' ";
	}

	if($_REQUEST['sk2']) {
		$que_where .= "AND A.g_name like '%".$_REQUEST['sk2']."%' ";
	}

	if($_GET['mode'] == "1") {
		$que_where .= " and (B.order_state='1' or  B.order_state='6' or  B.order_state='7' or  B.order_state='8' or  B.order_state='9')  ";
	} else if($_GET['mode'] == "2") {
		$que_where .= " and B.order_state regexp '(2|3|4|5)' ";
	} else if($_GET['mode'] == "3") {
		$que_where .= " and B.order_state = '10' ";
	}

	if($s_date){
		//$que_where .= "AND A.order_date >='$s_date 00:00:00' ";
		$que_where .= "AND order_date >='$s_date 00:00:00' ";		
	}
	if($e_date){
		//$que_where .= "AND A.order_date <='$e_date 23:59:59' ";
		$que_where .= "AND order_date <='$e_date 23:59:59' ";
	}

	if($_REQUEST['order_states']) {
		for($i=0; $i < count($_REQUEST['order_states']); $i++){
			$str_state .= "'".$_REQUEST['order_states'][$i]."'";
			if($i != count($_REQUEST['order_states'])-1){
					$str_state .= ",";
			}
		}
		$que_where .= "AND B.order_state in ($str_state) ";
	}
	if($_REQUEST['orderstate']){
		$arrOrder = str_replace("/", "", mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['orderstate']));
		$str_state =  explode(",",$arrOrder); 
		$que_where .= " and B.order_state regexp '(";

		for($k=0; $k < count($str_state)-1; $k++){
			$que_where .= $str_state[$k];
			if($k != count($str_state)-2) {
				$que_where .= "|";
			}
		}
		$que_where .= ")' ";
	}

	if($_GET['mode'] == "1") {
		$que_where .= " and (B.order_state='1' or  B.order_state='6' or  B.order_state='7' or  B.order_state='8' or  B.order_state='9')  ";
	} else if($_GET['mode'] == "2") {
		$que_where .= " and B.order_state regexp '(2|3|4|5)' ";
	} else if($_GET['mode'] == "3") {
		$que_where .= " and B.order_state = '10' ";
	}
	
	if($_REQUEST['pay_type']) {
		for($i=0; $i < count($_REQUEST['pay_type']); $i++){
			$str_type .= "'".$_REQUEST['pay_type'][$i]."'";
			if($i != count($_REQUEST['pay_type'])-1){
					$str_type .= ",";
			}
		}
		$que_where .= "AND B.pay_type in ($str_type) ";
	}
	if($_REQUEST['paytype']){
		
		$str_type =  explode(",",$_REQUEST['paytype']); 
		$que_where .= " and B.pay_type regexp '(";

		for($k=0; $k < count($str_type)-1; $k++){
			$que_where .= $str_type[$k];
			if($k != count($str_type)-2) {
				$que_where .= "|";
			}
		}
		$que_where .= ")' ";
	}

	if($_REQUEST['s_price']){
		$que_where .= "AND B.pay_amount >='".str_replace(",", "",$_REQUEST['s_price'])."' ";
	}
	if($_REQUEST['e_price']){
		$que_where .= "AND B.pay_amount <='".str_replace(",", "",$_REQUEST['e_price'])."' ";
	}

	if($order_state){
		$arr_state = explode(",",$order_state);
		for($i=0;$i<count($arr_state);$i++){
			$str_state .= "'".$arr_state[$i]."'";
			if($i != count($arr_state)-1){
				$str_state .= ",";
			}
		}
		
		$que_where .= "AND B.order_state in ($str_state) ";
	}


	//카운트
	$sql = "SELECT COUNT(A.idx)  ";
    $sql .= " FROM ".$tbl_order_good." A ";
    $sql .= " LEFT JOIN ".$tbl_order_info." B ";
	$sql .= " ON A.order_no = B.order_no ";
	$sql .= " WHERE 1=1  ".$que_where;

    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $row = mysql_fetch_row($rs);
    $total_rs = $row['0'];
	
	/*
	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
    $sql .= "WHERE 1=1  $que_where ORDER BY A.idx DESC ";
	*/
	
	
	$sql  = " SELECT A. * ";
	$sql .= ", B.order_name AS join_name, B.ship_name AS ship_name , B.order_id AS join_id, B.order_date AS join_date";
	$sql .= ", B.ship_zip AS join_zip, B.ship_address AS join_address, C.cat_name ";	
	$sql .= ", B.ship_address_ext AS join_address_ext, B.ship_phone, B.ship_mobile, B.order_comment, B.pay_amount, B.pay_type, B.order_phone ";		
    $sql .= " FROM ".$tbl_order_good." A ";
    $sql .= " LEFT JOIN ".$tbl_order_info." B ON A.order_no = B.order_no ";
	$sql .= " LEFT JOIN ".$tbl_category." C ON A.g_cat_no=C.cat_no ";
	$sql .= " WHERE 1=1  ".$que_where;
    $sql .= " ORDER BY A.order_no DESC ";
	
	//echo $sql;
	
    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    // offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

			if($scale != "0"){
				$sql .= " limit $offset,$scale ";
			}
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);

		    // offset 을 이용한 limit 가 적용된 갯수
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
	//echo $sql;

    return $list;
}


//옵션 가져오기
function getOptionList($sw="", $sk="", $scale, $offset=0){
	//테이블 지정
	$tbl = $GLOBALS["_conf_tbl"]["shop_opt"];//옵션정보

	if($sw=="all") {
		$que_where .= " and opt_name like '%$sk%' ";
	}
	
	//목록
    $sql  = "SELECT * ";
    $sql .= "FROM ".$tbl." ";
    $sql .= "WHERE 1=1 $que_where order by idx desc ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);

    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		if(!$offset){
			$offset=0;
		}else{
			$offset=$offset;
		}

		// offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		if($total_rs<=$offset){
			$offset = $total_rs - $scale;
		}

		if($scale != "0"){
			$sql .= " limit $offset,$scale ";
		}
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

		// offset 을 이용한 limit 가 적용된 갯수
		$total = mysqli_num_rows($rs);
		$list['list']['total'] = $total;
		// 페이지 네비게이션 오프셋 지정.
		
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}

function getOptionInfo($code){
	//옵션정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_opt"];
	$tbl_val = $GLOBALS["_conf_tbl"]["shop_opt_val"];

	$sql  = "SELECT * ";
    $sql .= "FROM $tbl ";
    $sql .= "WHERE opt_code = '$code' ";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){
        $list['total'] = $total_rs;
        for($i=0; $i < $total_rs; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }

	//옵션정보 가져오기
    $sql  = "SELECT * ";
    $sql .= "FROM ".$tbl_val." ";
    $sql .= "WHERE opt_code = '$code' order by idx";
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
    $total_rs = mysqli_num_rows($rs);
    
    if($total_rs > 0){
        $list['total_opt'] = $total_rs;
        for($i=0; $i < $total_rs; $i++){
            $list['opt'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total_opt'] = 0;
    }

    return $list;

}

//상품옵션 등록
function insertOption(){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_opt"];
	$tbl_val = $GLOBALS["_conf_tbl"]["shop_opt_val"];

	// 옵션넘버 만들기
	$sql = "select max(opt_code) as optcode from $tbl ";
	$result = mysqli_query($GLOBALS['dblink'], $sql) or error(mysqli_error());
	if($row = mysqli_fetch_object($result)){

		$opt_num = substr($row->optcode,1,4);
		$opt_code = substr("000".(++$opt_num),-4);
		
		if($opt_num) {
			$opt_code = "O".$opt_code;
		} else {
			$opt_code = "O0001";
		}
	}else{
		$opt_code = "O0001";
	}

	$sql = "INSERT INTO ".$tbl." set 
		opt_code='".$opt_code."',
		opt_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["option_name"])."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);


	for($i=0; $i < count($_POST['o_name']); $i++){
		$sql_opt = "INSERT INTO ".$tbl_val." set 
			opt_code='".$opt_code."',
			opt_value='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['o_name'][$i])."',
			opt_price='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['o_price'][$i])."'
		";
		$rs_opt = mysqli_query($GLOBALS['dblink'], $sql_opt);
	}

	if($total > 0){
		return true;
	}else{
		return false;
	}

}

//상품옵션 수정
function editOption($code){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_opt"];
	$tbl_val = $GLOBALS["_conf_tbl"]["shop_opt_val"];

	$sql = "UPDATE ".$tbl." set 
			opt_name='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST["option_name"])."'
		WHERE opt_code='".$code."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);
	
	//기존 항목
	for($i=0; $i < count($_POST['edit_opt']); $i++){
		$idx = mysqli_real_escape_string($GLOBALS['dblink'], $_POST['edit_opt'][$i]);
		$sql = "UPDATE ".$tbl_val." set 
				opt_value='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['e_o_name'][$idx])."',
				opt_price='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['e_o_price'][$idx])."'
			WHERE idx='".$idx."'
		";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);

	}

	//새로운 항목
	for($i=0; $i < count($_POST['o_name']); $i++){
		$sql_opt = "INSERT INTO ".$tbl_val." set 
			opt_code='".$code."',
			opt_value='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['o_name'][$i])."',
			opt_price='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['o_price'][$i])."'
		";
		$rs_opt = mysqli_query($GLOBALS['dblink'], $sql_opt);
	}

	return true;
}

//옵션 삭제
function deleteOption($code) {
	$tbl = $GLOBALS["_conf_tbl"]["shop_opt"];
	$tbl_val = $GLOBALS["_conf_tbl"]["shop_opt_val"];

	//옵션 정보 삭제
	$sql = "DELETE FROM ".$tbl." WHERE opt_code='".$code."'	";
	$rs1 = mysqli_query($GLOBALS['dblink'], $sql);

	//옵션 상세정보 삭제
	$sql = "DELETE FROM ".$tbl_val." WHERE opt_code='".$code."'	";
	$rs2 = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs1 > 0){
		return true;
	}else{
		return false;
	}
}


//옵션 항목삭제
function deleteOptionValue($idx) {
	$tbl = $GLOBALS["_conf_tbl"]["shop_opt_val"];

	//옵션 상세정보 삭제
	$sql = "DELETE FROM ".$tbl." WHERE idx='".$idx."'	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs > 0){
		return true;
	}else{
		return false;
	}
}

//교환 입력
function setOrderReturn($order_no, $goodidx=""){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	$order_state = "3";

	if($goodidx){
		$sql = "UPDATE ".$tbl_order_good." SET order_status='".$order_state."' WHERE idx='$goodidx' ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
	}

	//주문정보 테이블에 수정
	$sql = "UPDATE ".$tbl_order_info." SET
		order_state='".$order_state."',
		charge_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['status'])."',
		claim_comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['return_comment'])."',
		claim_date=now()
		WHERE order_no='$order_no'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	
	if($rs){
		return true;
	}else{
		return false;
	}
}

//환불 입력
function setOrderRefund($order_no, $goodidx=""){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	$order_state = "16";

	if($goodidx){
		$sql = "UPDATE ".$tbl_order_good." SET order_status='".$order_state."' WHERE idx='$goodidx' ";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
	}

	//주문정보 테이블에 수정
	$sql = "UPDATE ".$tbl_order_info." SET
		order_state='".$order_state."',
		charge_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['status'])."',
		claim_comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['refund_comment'])."',
		claim_date=now()
		WHERE order_no='$order_no'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	
	if($rs){
		return true;
	}else{
		return false;
	}
}

// 묵음배송 등록
function setOrderBind($order_no){
	for ($i=0; $i < count($_POST['orderno']); $i++) {
		$sql = "UPDATE tbl_shop_order_info AS t1, tbl_shop_order_info AS t2
		SET t1.ship_name = t2.ship_name, t1.ship_mobile = t2.ship_mobile, t1.ship_zip = t2.ship_zip, t1.ship_address = t2.ship_address
		, t1.ship_address_ext = t2.ship_address_ext, t1.order_comment = t2.order_comment, t1.shipping_order_no = t2.order_no
		WHERE t1.order_no = '".$_POST['orderno'][$i]."' AND t2.order_no='".$order_no."'
		";
		//	echo $sql;
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
	}
	if($rs){
		return true;
	}else{
		return false;
	}

}

//취소완료 처리(미입금 상태)
function setOrderCancel($order_no, $goodidx=""){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블

	//주문정보 테이블에 수정	

	if($goodidx){
		$sql = "UPDATE ".$tbl_order_good." SET order_status='3' WHERE idx='$goodidx' ";
	}else{
		$sql = "UPDATE ".$tbl_order_info." SET
			order_state='3',
			charge_type='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['status'])."',
			claim_comment='".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['comment'])."',
			claim_date=now()
			WHERE order_no='$order_no'
		";
		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$sql = "UPDATE ".$tbl_order_good." SET order_status='3' WHERE order_no='$order_no' ";
	}
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	if($rs){
		return true;
	}else{
		return false;
	}
}

//배송지 변경
function setOrderAddEdit($order_no){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블

	//주문정보 테이블에 수정
	$sql = "UPDATE ".$tbl_order_info." SET
		order_name='".$_POST['order_name']."',
		order_phone='".$_POST['order_phone1']."-".$_POST['order_phone2']."-".$_POST['order_phone3']."',
		order_zip='".$_POST['order_zip']."',
		order_address='".$_POST['order_address']."',
		order_address_ext='".$_POST['order_address_ext']."',
		ship_name='".$_POST['ship_name']."',
		ship_phone='".$_POST['ship_phone1']."-".$_POST['ship_phone2']."-".$_POST['ship_phone3']."',
		ship_zip='".$_POST['ship_zip']."',
		ship_address='".$_POST['ship_address']."',
		ship_address_ext='".$_POST['ship_address_ext']."',
		ship_email='".$_POST['ship_email1']."@".$_POST['ship_email2']."',
		order_comment='".$_POST['order_comment']."'
		WHERE order_no='$order_no'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}
}


//상품권구매후 상품권 발행
function setGiftCard($idx, $price){
	$tbl_my = $GLOBALS["_conf_tbl"]["mygiftcard"];//상품권 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문 상품 테이블
	
	if($price=="50000") {
		$arrGiftCardInfo = getGiftcardInfo(5); //상품권정보
	} else if($price=="100000") {
		$arrGiftCardInfo = getGiftcardInfo(6); //상품권정보
	} else if($price=="300000") {
		$arrGiftCardInfo = getGiftcardInfo(7); //상품권정보
	} 
	
	$arrInfo = getArticleInfo($tbl_order_good, $idx);
	if($arrInfo["list"][0]["order_id"]=="guest") {
		$sql_add = "";
	} else {
		$sql_add = "user_id='".$arrInfo["list"][0]["order_id"]."', ";
	}

	$serial = substr(strtoupper(md5($arrGiftCardInfo["list"][0]['giftcard_name'].$i.microtime(true))),0,16);
	$edate =  date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")+1));


	$sql = "INSERT INTO ".$tbl_my." set 
		$sql_add
		e_idx = '".$arrGiftCardInfo["list"][0]['idx']."',
		giftcard_no = '".$serial."',
		giftcard_name = '".$arrGiftCardInfo["list"][0]['giftcard_name']."',
		giftcard_content = '".$arrGiftCardInfo["list"][0]['giftcard_content']."',
		giftcard_sdate = now(),
		giftcard_edate = '".$edate."',
		giftcard_dis = '".$arrGiftCardInfo["list"][0]['giftcard_dis']."',
		giftcard_unit = '".$arrGiftCardInfo["list"][0]['giftcard_unit']."',
		over_price = '".$arrGiftCardInfo["list"][0]['over_price']."',
		under_price = '".$arrGiftCardInfo["list"][0]['under_price']."',
		giftcard_use = 'N',
		order_no = '".mysqli_real_escape_string($GLOBALS['dblink'], $_POST['order_no'])."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	$sql_up = "UPDATE ".$tbl_order_good." set
			g_vendor='".$serial."'
		WHERE idx='".$idx."'
	";
	$rs_up = mysql_query($sql_up, $GLOBALS['dblink']);
    
	if($rs){
		return true;
    }else{
        return false;
    }

}

// 이벤트 카운트
//주문정보 가져오기 - 관리자
function getEventUserCount($idx, $scale=0, $offset=0){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문 상품 테이블
	
	$que_where = "AND B.g_idx='". $idx ."' AND A.order_state in (1,6,7,8,9,10) ";
	//	$que_where = "AND B.g_idx in ('25','". $idx ."') AND A.order_state in (6,7,8,9) ";	// 이벤트 2차때 적용해야함 8/15일
	//	$que_where = "AND B.g_idx='". $idx ."' ";	// 테스트용

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
	$sql .= "LEFT JOIN ".$tbl_order_good." B ON A.order_no=B.order_no ";
    $sql .= "WHERE 1=1  $que_where GROUP BY A.order_no ORDER BY A.idx DESC ";
	//	echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	
    $total_rs = mysqli_num_rows($rs);


    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    // offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

			if($scale != "0"){
				$sql .= " limit $offset,$scale ";
			}
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);

		    // offset 을 이용한 limit 가 적용된 갯수
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }

    return $list;
}

// 이벤트 카운트 유저
function getEventUserIDCount($idx, $userid, $scale=0, $offset=0){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];//상품 주문정보 테이블
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//주문 상품 테이블
	
	//$que_where = "AND B.g_idx='". $idx ."' AND A.order_state in (6,7,8,9) AND A.order_id='". $userid ."' ";
	$que_where = "AND B.g_idx in ('25','". $idx ."') AND A.order_state in (6,7,8,9) AND A.order_id='". $userid ."' ";	// 이벤트 2차때 적용해야함 8/15일
	//	$que_where = "AND B.g_idx in ('25','". $idx ."') AND A.order_id='". $userid ."' ";	// 테스트용

	//목록
    $sql  = "SELECT A.* ";
    $sql .= "FROM ".$tbl_order_info." A ";
	$sql .= "LEFT JOIN ".$tbl_order_good." B ON A.order_no=B.order_no ";
    $sql .= "WHERE 1=1  $que_where GROUP BY A.order_no ORDER BY A.idx DESC ";
	//	echo $sql;
    $rs = mysqli_query($GLOBALS['dblink'], $sql);
	
    $total_rs = mysqli_num_rows($rs);


    if($total_rs > 0){
        $list['total'] = $total_rs;
        // 페이지 네비게이션 오프셋 지정.
		    if(!$offset){
		        $offset=0;
		    }else{
		        $offset=$offset;
		    }

		    // offset 이 전체 게시물수보다 작을때 offset 을 전체게시물 - 페이지당 보여줄 글 수로 offset 설정
		    if($total_rs<=$offset){
		        $offset = $total_rs - $scale;
		    }

			if($scale != "0"){
				$sql .= " limit $offset,$scale ";
			}
		    $rs = mysqli_query($GLOBALS['dblink'], $sql);

		    // offset 을 이용한 limit 가 적용된 갯수
		    $total = mysqli_num_rows($rs);
		    $list['list']['total'] = $total;
		    // 페이지 네비게이션 오프셋 지정.
		    
        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);
        }
    }else{
        $list['total'] = 0;
    }

    return $list;
}

//상품가격정보 가져오기
function getInwonInfo($ptype, $dweek, $gCat="2", $sCat="4"){
	$tbl = $GLOBALS["_conf_tbl"]["shop_good"];
	
	$subQuery = " where 1=1 ";
	/*일반*/
	$subQuery .= " AND cat_code like '".$gCat."/".$sCat."/%' ";
	// 백제 (4:일반, 5:부여군민, 6:단체)
	// 부여 (7:일반, 8:부여군민, 9:단체)
	
	/*성인 ~ 어린이*/
	if($ptype=="adult"){
		$subQuery .= " AND g_name='성인' ";
	}
	if($ptype=="teen"){
		$subQuery .= " AND g_name='청소년' ";
	}
	if($ptype=="child"){
		$subQuery .= " AND g_name='어린이' ";
	}
	if($ptype=="실버"){
		$subQuery .= " AND g_name='경로' ";
	}
	/*요일*/
	if($dweek=="0" || $dweek=="6"){ // 주말	
		$priceName = "p_price";
	}else{	// 평일
		$priceName = "price";
	}

    $sql  = "SELECT ".$priceName." as paywon FROM " .$tbl." ".$subQuery." ";
	//echo $sql;

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
################################################### function - 부여관광 예약 ST
function setOrderInfoInsert($order_summary,$PayAmount, $order_state, $order_no, $order_id){
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];	//상품 주문정보 테이블	

	$order_phone	= $_REQUEST['phone01']."-".$_REQUEST['phone02']."-".$_REQUEST['phone03'];	
	$order_email	= $_REQUEST['email01'].'@'.$_REQUEST['email01'];
	$user_name		= $_REQUEST['orderName'];

	$adult_pay		= $_REQUEST['adult_pay'];
	$teen_pay		= $_REQUEST['teen_pay'];
	$child_pay		= $_REQUEST['child_pay'];
	$adult_inwon	= $_REQUEST['adult_inwon'];
	$teen_inwon		= $_REQUEST['teen_inwon'];
	$child_inwon	= $_REQUEST['child_inwon'];	
	
	$bcate			= $_REQUEST['bcate'];
	$bdate			= $_REQUEST['bDate'];
	$btime_idx		= $_REQUEST['btime'];
	$scat			= $_REQUEST['sCat'];	
	
	$sql = "INSERT INTO ".$tbl_order_info." SET
		order_no='$order_no',
		order_summary='$order_summary',
		order_name='".$user_name."',
		order_id='$order_id',
		order_regnum1='P',
		order_regnum2='',
		order_phone='$order_phone',
		order_mobile='$order_phone',
		order_zip='$order_zip',
		order_address='".$_POST[order_address]."',
		order_address_ext='".$_POST[order_address_ext]."',
		order_email='".$order_email."',
		ship_name='".$user_name."',
		ship_phone='$order_phone',
		ship_mobile='$order_phone',
		ship_zip='$ship_zip',
		ship_address='".$_POST[ship_address]."',
		ship_address_ext='".$_POST[ship_address_ext]."',
		ship_email='".$order_email."',
		pay_type='".$_POST['pay_type']."',
		bank_type='".$_POST['bank_type']."',
		bank_name='".$_POST['bank_name']."',
		bank_date='".$_POST['bank_date']."',
		using_point='0',
		using_point_idx='0',
		add_point='0',
		add_point_idx='0',
		coupon_amount='0',
		giftcard_amount='0',
		coupon_idx='0', 
		giftcard_idx='0',
		ship_amount='0',
		login_amount='0',
		birth_amount='0',
		total_amount='$PayAmount',
		pay_amount='$PayAmount',
		adult_pay='$adult_pay',
		teen_pay='$teen_pay',
		child_pay='$child_pay',
		adult_inwon='$adult_inwon',
		teen_inwon='$teen_inwon',
		child_inwon='$child_inwon',
		bcate='$bcate',
		bdate='$bdate',
		btime_idx='$btime_idx',
		scat='$scat',
		order_date=now(),
		order_state='$order_state',		
		order_comment='".$_POST['order_comment']."',	
		ip='".$_SERVER['REMOTE_ADDR']."'
	";
	//echo $sql;
	//$rs = mysqli_query($GLOBALS['dblink'], $sql);
    //$total = mysqli_affected_rows($GLOBALS['dblink']);
	
	//echo $total ;

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

function getFreeView($tbl, $subQuery, $col="*", $scale=0, $offset=0, $orderBy=""){
	$sql	= "select count(*) from $tbl WHERE 1=1 ".$subQuery;
    $rs		= mysqli_query($GLOBALS['dblink'], $sql);	
    $row	= mysqli_fetch_row($rs);
    $total_rs = $row[0];
	//echo $sql;

	if($total_rs > 0){
        $list['total'] = $total_rs;
		
		$sql	= "SELECT ".$col." FROM $tbl WHERE 1=1 ".$subQuery.$orderBy;
		if($scale > 0){
			if(!$offset){
				$offset = "0";
			}
			$sql.= " limit ".$offset.",".$scale;
		}
		//	echo $sql;
		$rs		= mysqli_query($GLOBALS['dblink'], $sql);		
		$total	= mysqli_num_rows($rs);
		$list['list']['total'] = $total;

        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);			
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}
function getFreeQueryR($Query){
	$rs	= mysqli_query($GLOBALS['dblink'], $Query);		
	$total	= mysqli_num_rows($rs);
	//	echo "[".$total."]";
	$list['list']['total'] = $total;
	$list['total'] = $total;
	for($i=0; $i < $total; $i++){
		$list['list'][$i] = mysqli_fetch_assoc($rs);			
	}
	return $list;
}

// 커리문 직접 입력&수정
function getFreeQueryCud($Query){
	$rs = mysqli_query($GLOBALS['dblink'], $Query);
	if($rs > 0){
		return true;
	}else{
		return false;
	}
}
//상품 가져오기
function getFreeGoodList($subQuery, $col="*", $filend="N", $scale=0, $offset=0, $orderBy=""){
	$tbl	= $GLOBALS["_conf_tbl"]["shop_good"];//상품정보
	$tblFile= $GLOBALS["_conf_tbl"]["shop_good_files"];//상품파일
	
	#################### 시간설정 ################### ST
	/*$thisDate = date("Y-m-d H:i:s");
	$timeQuery = " AND CONCAT(sdate,' ',stime,':00') < '".$thisDate."' AND sdate!='0000-00-00' 
	AND (date_yn='Y' OR CONCAT(edate,' ',etime,':00') > '".$thisDate."' )"; */
	#################### 시간설정 ################### ED

	$sql	= "select count(idx) from $tbl WHERE 1=1 ".$subQuery.$timeQuery;

    $rs		= mysqli_query($GLOBALS['dblink'], $sql);
    $row	= mysqli_fetch_row($rs);
    $total_rs = $row[0];
    
    if($total_rs > 0){
        $list['total'] = $total_rs;
		
		$sql	= "SELECT ".$col." FROM $tbl WHERE 1=1 ".$subQuery.$timeQuery.$orderBy;
		if($scale > 0){
			if(!$offset){
				$offset = "0";
			}
			$sql.= " limit ".$offset.",".$scale;
		}
		//	echo $sql;
		$rs		= mysqli_query($GLOBALS['dblink'], $sql);
		$total	= mysqli_num_rows($rs);
		$list['list']['total'] = $total;

        for($i=0; $i < $total; $i++){
            $list['list'][$i] = mysqli_fetch_assoc($rs);	
			if($filend=="Y"){
				################################################### 파일정보 가져오기 ################################################### ST
				$sqlF  = "SELECT re_name,ori_name FROM ".$tblFile." WHERE b_idx='".$list['list'][$i]['idx']."' order by idx";
				//echo $sqlF;
				$rsF = mysqli_query($GLOBALS['dblink'], $sqlF);
				$total_file = mysqli_num_rows($rsF);

				if($total_file > 0){
					$list['list'][$i]['total_files'] = $total_file;
					for($j=0; $j < $total_file; $j++){
						$list['list'][$i]['files'][$j] = mysqli_fetch_assoc($rsF);
					}
				}else{
					$list['list'][$i]['total_files'] = 0;
				}
				################################################### 파일정보 가져오기 ################################################### ED
			}
        }
    }else{
        $list['total'] = 0;
    }
    return $list;
}

//장바구니 아이템 수량 업데이트
function onoffCart($session_id, $user_id, $tp){
	//상품정보 테이블
	$tbl = $GLOBALS["_conf_tbl"]["shop_cart"];

	$sql = "UPDATE ".$tbl." set 
		onoff='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['onoff'])."'
		WHERE ";
	
	if($tp=="1"){
		$sql .="user_id='".$user_id."' ";
	}else{
		$sql .="session_id='".$session_id."' ";
	}

	$sql .=" AND c_idx in (".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['c_idx']).")	";

//	echo $sql;
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	if($rs){
		return true;
	}else{
		return false;
	}

}
//주문한 상품정보 가져오기
function getOrderGoodJoinList($order_no){
	$sql = "
		SELECT *,A.idx AS ord_idx FROM tbl_shop_order_good A 
		JOIN tbl_shop_good AS B ON A.g_idx=B.idx
		WHERE order_no = '".$order_no."'
		order by A.idx asc 
	";
	//	echo $sql;

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total_rs = mysqli_affected_rows($GLOBALS['dblink']);

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

//알람에 담기
function addAlarm($user_id, $g_idx, $atime){
	//알람 테이블
	$tbl = "tbl_shop_alarm";
	
	$exists_chk["total"] = 1;

	if($exists_chk["total"] > 0){
		$sql  = "SELECT * ";
		$sql .= "FROM $tbl ";
		$sql .= "WHERE ";

		//세션아이디, 유저아이디중 선택
		$sql .= "user_id='".$user_id."' ";
		$sql .= "AND g_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx'])."' ";

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total_rs = mysqli_num_rows($rs);

		//있다면 그냥 리턴
		if($total_rs > 0){
			return true;
		//없다면 인서트
		}else{
			$sql = "INSERT INTO ".$tbl." set 
				user_id='".$user_id."',
				g_idx='".mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['g_idx'])."',
				sdate='".mysqli_real_escape_string($GLOBALS['dblink'], $atime)."',
				stime='".strtotime($atime)."',
				wdate=now()
			";
		}

		$rs = mysqli_query($GLOBALS['dblink'], $sql);
		$total = mysqli_affected_rows($GLOBALS['dblink']);

		if($total > 0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

//알람 아이템 삭제
function deleteAlarm($user_id, $c_idx, $liveflag=""){
	//상품정보 테이블
	$tbl = "tbl_shop_alarm";

	$sql = "DELETE FROM ".$tbl." WHERE ";		
	$sql .=" user_id='".$user_id."' ";
	$sql .=" AND g_idx in (".$c_idx.") ";
	//echo $sql; 

	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}
//상품카트에 쿠폰등록
function setGoodCoupon($couponIdx, $couponPay, $userID, $g_idx){
	$sql = "UPDATE tbl_shop_order_cart SET g_coupon_idx='".$couponIdx."', g_coupon_pay='".$couponPay."' WHERE user_id='".$userID."' AND g_idx='".$g_idx."'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);
	
	echo $sql;
	if($total > 0){
		return true;
	}else{
		return false;
	}
}
//상품카트에 쿠폰삭제
function setGoodCouponZero($userID, $g_idx){
	$sql = "UPDATE tbl_shop_order_cart SET g_coupon_idx='0', g_coupon_pay='0' WHERE user_id='".$userID."' AND g_idx='".$g_idx."'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);
	
	echo $sql;
	if($total > 0){
		return true;
	}else{
		return false;
	}
}
//상품정보에 쿠폰등록(예약전용)
function setReserveCoupon($couponIdx, $couponPay, $userID, $orderno){
	$sql = "UPDATE tbl_shop_order_good SET g_coupon_idx='".$couponIdx."', g_coupon_pay='".$couponPay."' WHERE order_id='".$userID."' AND order_no='".$orderno."'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);
	
	echo $sql;
	if($total > 0){
		return true;
	}else{
		return false;
	}
}
//상품정보에 쿠폰삭제(예약전용)
function setReserveCouponZero($userID, $orderno){
	$sql = "UPDATE tbl_shop_order_good SET g_coupon_idx='0', g_coupon_pay='0' WHERE order_id='".$userID."' AND order_no='".$orderno."'";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	$total = mysqli_affected_rows($GLOBALS['dblink']);
	
	echo $sql;
	if($total > 0){
		return true;
	}else{
		return false;
	}
}

function getGoodMemberInfo($idx){
	$tbl = $GLOBALS["_conf_tbl"]["member"];

	$sql  = "SELECT * FROM ".$tbl." WHERE idx = '$idx' ";
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
// 예약관련 주문등록
function setReserveOrder($userIDX, $goodIDX){
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];	//상품 주문정보 테이블
	
	//주문번호 생성
	$new_order_no = makeOrderNo();

	//상품정보
	$arrInfo = getGoodInfo($goodIDX);

	$arrMemberInfo = getGoodMemberInfo($userIDX);

	$pointunit = 0;	//적립될 포인트 퍼센트
	if($GLOBALS["_SITE"]["SHOP_POINT_YN"]=="Y"){				## 기본적립 사용여부
		$pointunit = $GLOBALS["_SITE"]["SHOP_POINT_DEF"];			## 기본적립 퍼센트
	}

	if($arrInfo["list"][0]["point_type"]=="D"){	## 기본 적립금 설정
		$thisPoint = (($pointunit*$arrInfo["list"][0]["price"])/100);
	}else{										## 개별설정
		$thisPoint = (($arrInfo["list"][0]["point"]*$arrInfo["list"][0]["price"])/100);
	}

	//주문상품 정보 테이블에 입력
	$sql = "INSERT INTO ".$tbl_order_good." SET
		order_no='".$new_order_no."',
		order_id='".$arrMemberInfo['list'][0]['user_id']."',
		g_idx='".$arrInfo["list"][0]["idx"]."',
		g_cat_no='".$arrInfo["list"][0]["cat_no"]."',
		g_code='".$arrInfo["list"][0]["g_code"]."',
		g_name='".$arrInfo["list"][0]["g_name"]."',
		g_vendor='".$arrInfo["list"][0]["vendor"]."',
		g_brand='".$arrInfo["list"][0]["brand"]."',
		g_model='".$arrInfo["list"][0]["model"]."',
		g_price='".$arrInfo["list"][0]["price"]."',
		g_qty='1',
		g_point='".$thisPoint."',
		g_opt_1='',
		g_opt_1_price='0',
		g_opt_2='',
		g_opt_2_price='0',
		g_opt_3='',
		g_opt_3_price='0',
		g_opt_4='',
		g_opt_4_price='0',
		g_opt_5='',
		g_opt_5_price='0',
		g_opt_rel_1='',
		g_opt_rel_1_price='0',
		g_opt_rel_2='',
		g_opt_rel_2_price='0',
		g_coupon_idx = '0',
		g_coupon_pay = '0',
		order_status ='R'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);

	$af10day = date("Y-m-d",strtotime("+1 day", time()));	## 한달후 30일
	//주문정보 테이블에 입력
	$sql = "INSERT INTO ".$tbl_order_info." SET
		order_no='".$new_order_no."',
		order_summary='".$arrInfo["list"][0]["g_name"]."',
		order_name='".mysqli_real_escape_string($GLOBALS['dblink'], $arrMemberInfo['list'][0]['user_name'])."',
		order_id='".$arrMemberInfo['list'][0]['user_id']."',
		order_regnum1='P',
		order_regnum2='',
		order_phone='".$arrMemberInfo['list'][0]['phone']."',
		order_mobile='".$arrMemberInfo['list'][0]['mobile']."',
		order_zip='".$arrMemberInfo['list'][0]['zip']."',
		order_address='".mysqli_real_escape_string($GLOBALS['dblink'], $arrMemberInfo['list'][0]['address'])."',
		order_address_ext='".mysqli_real_escape_string($GLOBALS['dblink'], $arrMemberInfo['list'][0]['address_ext'])."',
		order_email='".$arrMemberInfo['list'][0]['email']."',
		ship_name='".mysqli_real_escape_string($GLOBALS['dblink'], $arrMemberInfo['list'][0]['user_name'])."',
		ship_phone='".$arrMemberInfo['list'][0]['phone']."',
		ship_mobile='".$arrMemberInfo['list'][0]['mobile']."',
		ship_zip='".$arrMemberInfo['list'][0]['zip']."',
		ship_address='".mysqli_real_escape_string($GLOBALS['dblink'], $arrMemberInfo['list'][0]['address'])."',
		ship_address_ext='".mysqli_real_escape_string($GLOBALS['dblink'], $arrMemberInfo['list'][0]['address_ext'])."',
		ship_email='".$arrMemberInfo['list'][0]['email']."',
		pay_type='card',
		bank_type='',
		bank_name='',
		bank_date='',
		using_point='0',
		using_point_idx='0',
		add_point='0',
		add_point_idx='0',
		coupon_amount='0',
		giftcard_amount='0',
		coupon_idx='0', 
		giftcard_idx='0',
		ship_amount='0',
		login_amount='0',
		birth_amount='0',
		total_amount='".$arrInfo["list"][0]["price"]."',
		pay_amount='".$arrInfo["list"][0]["price"]."',
		order_date=now(),
		order_state='1',
		ipkum_date='".$af10day."',
		shipping_date='0000-00-00',
		shipping_type='',
		order_comment='',			
		cash_name='',
		cash_request='',
		cash_type='',
		cash_num='',
		mail_sms='',
		giftgb='',
		pcmo='PC',
		reserve_state = '13',
		ip='".$_SERVER[REMOTE_ADDR]."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	//	echo $sql;	
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}

// 예약관련 주문등록 (사용자1차예약결제시)
function setReserveOrderNext($orderNo){
	$tbl_order_good = $GLOBALS["_conf_tbl"]["shop_order_good"];//상품 주문정보 테이블
	$tbl_order_info = $GLOBALS["_conf_tbl"]["shop_order_info"];	//상품 주문정보 테이블
	
	//주문번호 생성
	$new_order_no = makeOrderNo();

	//상품정보
	//	$arrInfo = getGoodInfo($goodIDX);
	$subQuery = " AND order_no ='".$orderNo."' ";
	$arrInfo = getFreeView($tbl_order_good, $subQuery, $col="*", $scale=0, $offset=0, $orderBy="");
	//주문정보
	$subQuery = " AND order_no ='".$orderNo."' ";
	$arrOrderInfo = getFreeView($tbl_order_info, $subQuery, $col="*", $scale=0, $offset=0, $orderBy="");

	
	//주문상품 정보 테이블에 입력
	$sql = "INSERT INTO ".$tbl_order_good." SET
		order_no='".$new_order_no."',
		order_id='".$arrInfo['list'][0]['order_id']."',
		g_idx='".$arrInfo["list"][0]["g_idx"]."',
		g_cat_no='".$arrInfo["list"][0]["g_cat_no"]."',
		g_code='".$arrInfo["list"][0]["g_code"]."',
		g_name='".$arrInfo["list"][0]["g_name"]."',
		g_vendor='".$arrInfo["list"][0]["g_vendor"]."',
		g_brand='".$arrInfo["list"][0]["g_brand"]."',
		g_model='".$arrInfo["list"][0]["g_model"]."',
		g_price='".$arrInfo["list"][0]["g_price"]."',
		g_qty='1',
		g_point='".$arrInfo["list"][0]["g_point"]."',
		g_opt_1='',
		g_opt_1_price='0',
		g_opt_2='',
		g_opt_2_price='0',
		g_opt_3='',
		g_opt_3_price='0',
		g_opt_4='',
		g_opt_4_price='0',
		g_opt_5='',
		g_opt_5_price='0',
		g_opt_rel_1='',
		g_opt_rel_1_price='0',
		g_opt_rel_2='',
		g_opt_rel_2_price='0',
		g_coupon_idx = '0',
		g_coupon_pay = '0',
		order_status ='R'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;	

	$af10day = date("Y-m-d",strtotime("+30 day", time()));	## 한달후 30일

	//주문정보 테이블에 입력
	$sql = "INSERT INTO ".$tbl_order_info." SET
		order_no			='".$new_order_no."',
		order_no_parent		='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_no'])."',
		order_summary		='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_summary'])."',
		order_name			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_name'])."',
		order_id			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_id'])."',
		order_regnum1		='P',
		order_regnum2		='',
		order_phone			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_phone'])."',
		order_mobile		='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_mobile'])."',
		order_zip			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_zip'])."',
		order_address		='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_address'])."',
		order_address_ext	='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_address_ext'])."',
		order_email			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['order_email'])."',
		ship_name			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['ship_name'])."',
		ship_phone			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['ship_phone'])."',
		ship_mobile			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['ship_mobile'])."',
		ship_zip			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['ship_zip'])."',
		ship_address		='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['ship_address'])."',
		ship_address_ext	='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['ship_address_ext'])."',
		ship_email			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['ship_email'])."',
		pay_type			='card',
		bank_type			='',
		bank_name			='',
		bank_date			='',
		using_point			='0',
		using_point_idx		='0',
		add_point			='0',
		add_point_idx		='0',
		coupon_amount		='0',
		giftcard_amount		='0',
		coupon_idx			='0', 
		giftcard_idx		='0',
		ship_amount			='0',
		login_amount		='0',
		birth_amount		='0',
		total_amount		='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['re_amount'])."',
		pay_amount			='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['re_amount'])."',
		order_date			=now(),
		order_state			='1',
		ipkum_date			='".$af10day."',
		shipping_date		='0000-00-00',
		shipping_type		='',
		order_comment		='',		
		admin_comment		='".mysqli_real_escape_string($GLOBALS['dblink'], $arrOrderInfo['list'][0]['admin_comment'])."',
		cash_name			='',
		cash_request		='',
		cash_type			='',
		cash_num			='',
		mail_sms			='',
		giftgb				='',
		pcmo				='PC',
		reserve_state		='12',
		ip					='".$_SERVER[REMOTE_ADDR]."'
	";
	$rs = mysqli_query($GLOBALS['dblink'], $sql);
	//echo $sql;	
	$total = mysqli_affected_rows($GLOBALS['dblink']);

	if($total > 0){
		return true;
	}else{
		return false;
	}
}
//	배송정보 확인 ST
function defShipDay($dayPlus = 0){
	//$dayPlus = 0;						## 기본 발송일 +0	
	if(date("H") > 11){ $dayPlus++; }	## 12시 기준 / 넘어가면 +1일
	$thsDate = date("Y-m-d");			## 현재일
	$nowDate = date("Y-m-d", strtotime("+".$dayPlus." day", strtotime($thsDate)));	## 최초 발송일
	$strDate = "";						## 발송 확정일
	
	############## 영업일 확인 ############## ST
	for($i=0;$i<31;$i++){
		$tmpDate = date("Y-m-d", strtotime("+".$i." day", strtotime($nowDate)));
		$yesnoFlag = fnShipDate($tmpDate, "영업 휴무");	## 휴무일 확인
		if($yesnoFlag==true){
			$strDate = $tmpDate;
			break;
		}
	}
	############## 영업일 확인 ############## ED
	//	echo "발송일".$strDate;

	$defDate = date("Y-m-d", strtotime("+1 day", strtotime($strDate)));  ## 최초 배송일
	$retDate = "";						## 배송 확정일
	############## 배송일 확인 ############## ST
	for($i=0;$i<31;$i++){
		$tmpDate = date("Y-m-d", strtotime("+".$i." day", strtotime($defDate)));		
		$yesnoFlag = fnShipDate($tmpDate, "배송 휴무");	## 휴무일 확인
		if($yesnoFlag==true){
			$retDate = $tmpDate;
			break;
		}
	}
	############## 배송일 확인 ############## ED
	//	echo "배송일".$retDate;
	return $retDate;
}
function fnShipDate($defDate, $flg){
	if( date('w', strtotime($defDate))==0 ){	## 일요일은 무조건 휴무
		return false;
	}else{
		if($flg == "배송 휴무" && $_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]){		
			$sql = "select orderday from tbl_member where user_id='".$_SESSION[$GLOBALS["_SITE"]["DOMAIN"]]["MEMBER"]["ID"]."' ";
			$headUserInfo = getFreeQueryR($sql);
			$arrUserHoly = explode(",",$headUserInfo['list'][0]['orderday']);

			if( in_array( "all", $arrUserHoly ) ){
				## 전체 배송 가능
			}else{			
				if( in_array( date('w', strtotime($defDate)), $arrUserHoly ) ){				
					## 해당 요일 배송 가능
				}else{
					return false;
				}
			}
		}

		$sql = "select schedule_date from tbl_board_schedule where schedule_date='".$defDate."' and subject in ('".$flg."','공휴일') ";
		$arrDateList = getFreeQueryR($sql);
		if($arrDateList['total']<1){
			return true;
		}else{
			return false;
		}
	}
}
//	배송정보 확인 ED
?>