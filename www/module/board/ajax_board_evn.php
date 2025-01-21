<?
session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php");
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], $_REQUEST['boardid']);

if($_POST['evnMode']=="write"){
	###############################################자동등록방지############################################ //ST
	if($_POST['boardid']=="inquiry" ){
		include_once $_SERVER['DOCUMENT_ROOT'] . "/_securimage/securimage.php";
		$img = new Securimage();
		$valid = $img->check($_POST['code']);
		if($valid == true) {
		} else {
			jsMsg("자동등록방지 입력 오류");
			jsHistory("-1") ;
			exit;
		}
	}
	###############################################자동등록방지############################################ //ED
	if($arrBoardInfo["list"][0]["useadminonly"] =="Y" && !$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
		jsMsg("관리자만 글을 쓸 수 있는 게시판 입니다.");
		jsHistory("-1") ;
		exit;
	}	

	//게시물 등록
	if($_POST['boardid']=="covid" ){
		$writeRS = insertBoardCovid($_POST['boardid'],$arrBoardInfo["list"][0]["thumwidth"]);
	}else{
		$writeRS = insertBoardArticle($_POST['boardid'],$arrBoardInfo["list"][0]["thumwidth"]);
	}

	if($_POST['boardid']=="online" ){
		$_SESSION["MEMBER"]["NAME"]			= $_POST['name'];
		$_SESSION["MEMBER"]["COMPANY"]		= $_POST['company'];
	}

	if($writeRS==true){
		###############################################답변 메일발송 & 문자발송############################################ //ST	
		if($_POST['boardid']=="request"){
			$arrSetInfo = getShopsetInfo($GLOBALS["_conf_tbl"]["shop_set"]);
			$arrInfo["list"][0]["name"]	= $_POST["name"];
			$arrInfo["list"][0]["subject"]	= $_POST["subject"];
			if($_POST['boardid']=="request"){
				$arrInfo["list"][0]["subject"] = "1:1문의";
				$mailTo	= $arrSetInfo["list"][0]['consult_email'];	//받는분 이메일
			}
			$arrInfo["list"][0]["tel"]		= $_POST["tel"];
			$arrInfo["list"][0]["contents"] = $_POST["contents"];
			//$arrInfo["list"][0]["sdate"]	= $_POST["schedule_date"];
			//$arrInfo["list"][0]["stime"]	= $_POST["etc_2"];
			sendMailAsk("email",$arrInfo,$mailTo);
		}
		###############################################답변 메일발송 & 문자발송############################################ //ED
		############################################### 리뷰작성시 포인트 지급 ############################################ //ST
		if($_POST['boardid']=="after"){
			// $point = setPlusPoint($_POST['r_user'], "100", "주문번호:".$_POST["etc_3"]." 리뷰작성포인트");	## 임시로 주석처리 고객사 요청 08/20
		}
		############################################### 리뷰작성시 포인트 지급 ############################################ //ED
		if($_POST['boardid']=="slideblock"){
			jsGo("/take_out/slide_block3.php?no=".$writeRS,"","등록되었습니다.");
		}else{
			jsGo($_POST['returnURL'],"","등록되었습니다.");
		}		
	}else{
		jsMsg("게시물 등록에 실패하였습니다.");
		//jsHistory("-1") ;
	}
}else if($_POST['evnMode']=="user_write"){	// 코로나 전용
	$writeRS = insertBoardCovid($_POST['boardid'],$arrBoardInfo["list"][0]["thumwidth"]);
	if($_POST['etc_1']=="EN"){
		jsGo("/corona/reservation3_en.php?arridx=".$writeRS,"","");
	}else{
		jsGo("/corona/reservation3.php?arridx=".$writeRS,"","");
	}

}else if($_POST['evnMode']=="reply"){
	if($arrBoardInfo["list"][0]["useadminonly"] =="Y" && !$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
		jsMsg("관리자만 글을 쓸 수 있는 게시판 입니다.");
		jsHistory("-1") ;
		exit;
	}
	if($_POST['evnMode']=="reply" && $arrBoardInfo["list"][0]["usereply"] !="Y"){
		jsMsg("답글쓰기가 제한된 게시판 입니다.");
		jsHistory("-1");
		exit;
	}
	//게시물 등록
	$replyRS = insertBoardArticleReply($_POST['boardid'], $_POST['idx'], $arrBoardInfo["list"][0]["thumwidth"]);

	if($replyRS==true){
		jsGo($_POST['returnURL'],"","게시물을 등록하였습니다.");
	}else{
		jsMsg("게시물 등록에 실패하였습니다.");
		jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="modify"){
	if($arrBoardInfo["list"][0]["useadminonly"] =="Y" && !$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
		jsMsg("관리자만 글을 쓸 수 있는 게시판 입니다.");
		jsHistory("-1") ;
		exit;
	}
	//게시물 수정
	$modifyRS = modifyBoardArticle($_POST['boardid'], $_POST['idx'], $arrBoardInfo["list"][0]["thumwidth"]);

	if($modifyRS==true){
		###############################################답변 메일발송 & 문자발송############################################ //ST
		if($_POST['boardid']=="covid" && $_POST["etc_3"]=="Y"){			
			//	smsCovidMailsend("email_02", $_POST['email'], "검사 결과 안내", $_POST['rcode'], $_POST['name'], $_POST['bday']);	## 메일 발송			
			jsGo($_POST['returnURL'],"","검사결과를 발송하였습니다.");
		} else {
			jsGo($_POST['returnURL'],"","수정하였습니다.");
		}
		###############################################답변 메일발송 & 문자발송############################################ //ED				
	}else{
		//jsMsg("게시물 수정에 실패하였습니다.");
		jsHistory("-1") ;
	}
}else if($_POST['evnMode']=="user_modify"){
	if($arrBoardInfo["list"][0]["useadminonly"] =="Y" && !$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
		jsMsg("관리자만 글을 쓸 수 있는 게시판 입니다.");
		jsHistory("-1") ;
		exit;
	}
	//게시물 수정
	$modifyRS = modifyBoardCovid($_POST['boardid'], $_POST['idx'], $arrBoardInfo["list"][0]["thumwidth"]);

	if($modifyRS==true){
		jsGo($_POST['returnURL'],"","수정하였습니다.");			
	}else{
		jsMsg("게시물 수정에 실패하였습니다.");
		//jsHistory("-1") ;
	}
}else if($_POST['evnMode']=="cancel"){
	$deleteRS = cancelBoardArticle($_POST['boardid'], $_POST['idx'], $_POST['pass']);
	
	if($deleteRS==true){
		jsGo("/","","예약이 취소되었습니다.");
	}else{
		//jsMsg("게시물 삭제에 실패하였습니다.");
		jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="delete"){
	//게시물 삭제
	$deleteRS = deleteBoardArticle($_POST['boardid'], $_POST['idx'], $_POST['pass']);

	if($deleteRS==true){
		jsGo($_POST['returnURL'],"","게시물을 삭제하였습니다.");
	}else{
		//jsMsg("게시물 삭제에 실패하였습니다.");
		jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="comment_write"){

	$RS = insertComment(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['boardid']), mysqli_real_escape_string($GLOBALS['dblink'], $_POST['board_idx']));

	if($RS==true){
		echo "true";
	}else{		
		echo "false";
	}
}else if($_POST['evnMode']=="comment_list"){

	$arrCommentList = getCommentList(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['boardid']), mysqli_real_escape_string($GLOBALS['dblink'], $_POST['board_idx']), 0, 0);

	if($arrCommentList["total"] > 0){
		for($i=0;$i<$arrCommentList["total"];$i++){
			if(strlen($arrCommentList["list"][$i]['user_name'])>1){
	?>
		<li>
			<div class="top_info">
				<div class="writer">
					<span><?=iconv_substr($arrCommentList["list"][$i]['user_name'],0,1,"utf-8")?>*<?=iconv_substr($arrCommentList["list"][$i]['user_name'],2,1,"utf-8")?></span>
					<span><?
					echo substr($arrCommentList["list"][$i]['user_id'],0,3);
					for($s=4;$s<strlen($arrCommentList["list"][$i]['user_id']);$s++){
						echo "*";
					}
					echo substr($arrCommentList["list"][$i]['user_id'],-1);
					?></span>
					<span class="date"><?=str_replace("-",".",substr($arrCommentList["list"][$i]['wdate'],0,10))?></span>
				</div>
				<div class="btn">
				<?if($arrCommentList["list"][$i]['user_id']==$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){?>
				<button type="button" class="delete" onclick="fnComment('comment_delete','<?=$arrCommentList["list"][$i]['idx']?>')">삭제</button>
				<?}?>								
				</div>
			</div>
			<div class="comment"><?=nl2br($arrCommentList["list"][$i]['comment'])?></div>
		</li>		
	<?
			}	## End if
		}
	}

}else if($_POST['evnMode']=="comment_modify"){

	$RS = updateComment(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['boardid']), mysqli_real_escape_string($GLOBALS['dblink'], $_POST['board_idx']), mysqli_real_escape_string($GLOBALS['dblink'], $_POST['comm_idx']));

	if($RS==true){
		jsGo($_POST['returnURL'],"","댓글을 수정 하였습니다.");
	}else{
		jsMsg("댓글 수정에 실패하였습니다.");
		jsHistory("-1") ;
	}

}else if($_POST['evnMode']=="comment_reply"){

	$RS = replyComment(mysqli_real_escape_string($GLOBALS['dblink'], $_POST['boardid']), mysqli_real_escape_string($GLOBALS['dblink'], $_POST['board_idx']), mysqli_real_escape_string($GLOBALS['dblink'], $_POST['comm_idx']));

	if($RS==true){
		jsGo($_POST['returnURL'],"","댓글을 등록 하였습니다.");
	}else{
		jsMsg("댓글 등록에 실패하였습니다.");
		jsHistory("-1") ;
	}


}else if($_REQUEST['evnMode']=="comment_delete"){

	$RS = deleteComment(mysqli_real_escape_string($GLOBALS['dblink'], mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST[idx])));

	if($RS==true){
		echo "true";
	}else{
		echo "false";
	}
}

//DB해제
SetDisConn($dblink);
?>