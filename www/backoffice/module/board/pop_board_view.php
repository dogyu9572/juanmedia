<?
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/pub/inc/pop_top.php";

include $_SERVER['DOCUMENT_ROOT'] . "/module/board/board.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/calendar/calendar.lib.php";//일정관리 형식
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";

//	if(!in_array("board_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
//	jsMsg("권한이 없습니다.");
//	jsHistory("-1");
//	endif;

if(isset($_REQUEST['mode'])){
	$bMode = $_REQUEST['mode'];
}else{
	$bMode = "";
}
if(!isset($_GET["category"])){	$_GET["category"]="";}
if(!isset($_GET["sw"])){		$_GET['sw']="";	}
if(!isset($_GET["sk"])){		$_GET['sk']="";	}
if(!isset($_GET["offset"])){	$_GET['offset']="";	}


//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if(!isset($boardid)){
	$boardid = $_REQUEST['boardid'];
}

if($boardid=="timetable" && !$_GET["sk"]){
	$_GET["sw"]	= "sd";
	$_GET["sk"]	= date("Y-m-d");
}
if($boardid=="covid" && !$_REQUEST["scsdate"] && !$_REQUEST["scedate"]){
	$_REQUEST["scsdate"]	= date("Y-m-d");
	$_REQUEST["scedate"]	= date("Y-m-d");
}
if($boardid=="slideblock" && !$_REQUEST["scsdate"] && !$_REQUEST["scedate"] && $_REQUEST["dateflag"]!="all"){
	$_REQUEST["scsdate"]	= date("Y-m-d");
	$_REQUEST["scedate"]	= date("Y-m-d");
}


//게시판 정보
$arrBoardInfo = getBoardInfo($_conf_tbl['board_info'], $boardid);
//회원등급 목록
$arrLevelList = getArticleList($_conf_tbl["member_level"], 0, 0, "");
for($i=0;$i<$arrLevelList["total"];$i++){
	$arrLevelInfo[$arrLevelList["list"][$i]['level_no']] = $arrLevelList["list"][$i]['level_name'];
}
if(!isset($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"])){
	$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] = 0;
}
//_DEBUG($arrBoardInfo);
//DB해제
SetDisConn($dblink);

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

if($arrBoardInfo["total"] > 0){
	//카테고리 정보
	if($arrBoardInfo["list"][0]["category"] !=""){
		$arrBoardCategory = explode(",",$arrBoardInfo["list"][0]["category"]);
		$arrBoardCatTotal = count($arrBoardCategory);
	}else{
		$arrBoardCatTotal = 0;
		$arrBoardCategory = null;
	}
	//게시판 헤더
	echo stripslashes($arrBoardInfo["list"][0]["header"]);
	switch($bMode){
		case("write"):
			//관리자이거나 회원등급이 게시물 등록등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["writelevel"]){
				if($arrBoardInfo["list"][0]["boardid"]=="trmcal"){	## 일정관리
					$arrDoctorList = getBoardListBase("trmdoctor", "","","",0,0);
				}
				include($_SITE["BOARD_SKIN"] .$arrBoardInfo["list"][0]['skin']."/form.php");
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["writelevel"]] . " 이상 글 등록이 가능 합니다.");
				jsHistory("-1");
			}
			break;
		case("modify"):
			//관리자이거나 회원등급이 게시물 등록등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["writelevel"]){
				$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"modify");
				echo "<script src='/backoffice/pub/js/board.js'></script>";
				if($arrBoardArticle["total"] > 0){
					//글잠금이 아니거나, 인증을 했거나, 관리자일 경우 글 보여줌
					if($arrBoardArticle["list"][0]['uselock']!="Y" || $_SESSION[$_SITE["DOMAIN"]][$boardid."|".$_GET["idx"]]==TRUE || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
						if($arrBoardInfo["list"][0]["boardid"]=="qna1"){	## 일정관리
							$arrEtc2List = getCategoryList("2","Y");	// 업종
						}else if($boardid == "sub_evaluation"){
							$arrEvaluationInfo =  getBoardArticleView("evaluation", "", $_GET["category"],"extend");
						}
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/form.php");
					}else{
						$_REQUEST[mode]="unlock";
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pass.php");
					}
				}else{
					jsMsg("존재하지 않는 게시물 입니다.");
					jsHistory("-1");
				}
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["writelevel"]] . " 이상 글 수정이 가능 합니다.");
				jsHistory("-1");
			}
			break;
		#################### 부킹 > 진행 상태 변경창
		case("state_modify"):
			$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"modify");

			if($arrBoardArticle["total"] > 0){
				include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/form_state.php");
			}else{
				jsMsg("존재하지 않는 게시물 입니다.");
			}

			break;
		#################### 부킹 > 비고/메모 변경창
		case("memo_modify"):
			$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"modify");

			if($arrBoardArticle["total"] > 0){
				include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/form_memo.php");
			}else{
				jsMsg("존재하지 않는 게시물 입니다.");
			}

			break;
		case("writeall"):	####################################일괄등록
			if($arrBoardInfo["list"][0]["boardid"]=="trmcal"){	## 일정관리
				$arrDoctorList = getBoardListBase("trmdoctor", "","","",0,0);
			}
			include($_SITE["BOARD_SKIN"] .$arrBoardInfo["list"][0]['skin']."/allform.php");
			break;

		case("mlist"):	####################################달력 추가 (월별)

			include($_SITE["BOARD_SKIN"] .$arrBoardInfo["list"][0]['skin']."/mlist.php");
			break;

		case("wlist"):	####################################달력 추가 (주별)

			include($_SITE["BOARD_SKIN"] .$arrBoardInfo["list"][0]['skin']."/wlist.php");
			break;

		case("cash"):
			//관리자이거나 회원등급이 게시물 등록등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["writelevel"]){
				$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"modify");

				if($arrBoardArticle["total"] > 0){
					//글잠금이 아니거나, 인증을 했거나, 관리자일 경우 글 보여줌
					if($arrBoardArticle["list"][0]['uselock']!="Y" || $_SESSION[$_SITE["DOMAIN"]][$boardid."|".$_GET["idx"]]==TRUE || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
						if($arrBoardInfo["list"][0]["boardid"]=="barobill"){	##
							include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pop_cashform.php");
						}else{
							include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/form.php");
						}
					}else{
						$_REQUEST[mode]="unlock";
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pass.php");
					}
				}else{
					jsMsg("존재하지 않는 게시물 입니다.");
					jsHistory("-1");
				}
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["writelevel"]] . " 이상 글 수정이 가능 합니다.");
				jsHistory("-1");
			}
			break;
		case("tax_insert"):	// 세금계산서 발행
			include($_SITE["BOARD_SKIN"] .$arrBoardInfo["list"][0]['skin']."/pop_taxform.php");
			break;
		case("cash_insert"):	// 현금영수증 발행
			include($_SITE["BOARD_SKIN"] .$arrBoardInfo["list"][0]['skin']."/pop_cashform.php");
			break;
		case("tax"):
			//관리자이거나 회원등급이 게시물 등록등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["writelevel"]){
				$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"modify");

				if($arrBoardArticle["total"] > 0){
					//글잠금이 아니거나, 인증을 했거나, 관리자일 경우 글 보여줌
					if($arrBoardArticle["list"][0]['uselock']!="Y" || $_SESSION[$_SITE["DOMAIN"]][$boardid."|".$_GET["idx"]]==TRUE || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
						if($arrBoardInfo["list"][0]["boardid"]=="barobill"){	##
							include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pop_taxform.php");
						}else{
							include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/form.php");
						}
					}else{
						$_REQUEST[mode]="unlock";
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pass.php");
					}
				}else{
					jsMsg("존재하지 않는 게시물 입니다.");
					jsHistory("-1");
				}
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["writelevel"]] . " 이상 글 수정이 가능 합니다.");
				jsHistory("-1");
			}
			break;
		case("cashview"):
			//관리자이거나 회원등급이 게시물 등록등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["writelevel"]){
				$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"modify");

				if($arrBoardArticle["total"] > 0){
					//글잠금이 아니거나, 인증을 했거나, 관리자일 경우 글 보여줌
					if($arrBoardArticle["list"][0]['uselock']!="Y" || $_SESSION[$_SITE["DOMAIN"]][$boardid."|".$_GET["idx"]]==TRUE || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
						if($arrBoardInfo["list"][0]["boardid"]=="cashtax"){	##
							include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pop_cashview.php");
						}else{
							include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/form.php");
						}
					}else{
						$_REQUEST[mode]="unlock";
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pass.php");
					}
				}else{
					jsMsg("존재하지 않는 게시물 입니다.");
					jsHistory("-1");
				}
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["writelevel"]] . " 이상 글 수정이 가능 합니다.");
				jsHistory("-1");
			}
			break;
		case("taxview"):
			//관리자이거나 회원등급이 게시물 등록등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["writelevel"]){
				$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"modify");

				if($arrBoardArticle["total"] > 0){
					//글잠금이 아니거나, 인증을 했거나, 관리자일 경우 글 보여줌
					if($arrBoardArticle["list"][0]['uselock']!="Y" || $_SESSION[$_SITE["DOMAIN"]][$boardid."|".$_GET["idx"]]==TRUE || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
						if($arrBoardInfo["list"][0]["boardid"]=="cashtax"){	##
							include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pop_taxview.php");
						}else{
							include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/form.php");
						}
					}else{
						$_REQUEST[mode]="unlock";
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pass.php");
					}
				}else{
					jsMsg("존재하지 않는 게시물 입니다.");
					jsHistory("-1");
				}
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["writelevel"]] . " 이상 글 수정이 가능 합니다.");
				jsHistory("-1");
			}
			break;

		case("reply"):
			//관리자이거나 회원등급이 게시물 등록등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["replylevel"]){
				$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"reply");

				if($arrBoardArticle["total"] > 0){
					//글잠금이 아니거나, 인증을 했거나, 관리자일 경우 글 보여줌
					if($arrBoardArticle["list"][0][uselock]!="Y" || $_SESSION[$_SITE["DOMAIN"]][$boardid."|".$_GET["idx"]]==TRUE || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/form.php");
					}else{
						$_REQUEST[mode]="unlock";
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pass.php");
					}
				}else{
					jsMsg("존재하지 않는 게시물 입니다.");
					jsHistory("-1");
				}
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["replylevel"]] . " 이상 글 등록이 가능 합니다.");
				jsHistory("-1");
			}
			break;

		case("view"):
			//관리자이거나 회원등급이 게시물 읽기등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["readlevel"]){
				$arrBoardArticle = getBoardArticleView($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET["idx"],"read");

				if($arrBoardArticle["total"] > 0){

					//글잠금이 아니거나, 인증을 했거나, 관리자일 경우 글 보여줌
					if($arrBoardArticle["list"][0]['uselock']!="Y" || $_SESSION[$_SITE["DOMAIN"]][$boardid."|".$_GET["idx"]]==TRUE || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]){
						//댓글목록 가져오기
						$arrCommentList = getCommentList($arrBoardInfo["list"][0]["boardid"], $arrBoardArticle["list"][0]['idx'], $scale, $_GET['offset2']);
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/view.php");
					}else{
						$_REQUEST[mode]="unlock";
						include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pass.php");
					}

				}else{
					jsMsg("존재하지 않는 게시물 입니다.");
					jsHistory("-1");
				}
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["readlevel"]] . " 이상 글 읽기가 가능 합니다.");
				jsHistory("-1");
			}
			break;

		case("delete"):
			//관리자이거나 회원등급이 게시물 등록등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["writelevel"]){
				include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pass.php");
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["writelevel"]] . " 이상 글 삭제가 가능 합니다.");
				jsHistory("-1");
			}
			break;

		case("list"):
		default:
			//getBoardListBase : 파일첨부여부는 부르지 않음 (다중파일을 올려서 group by 를 써야하므로 일반적일때는 이걸로만)
			//getBoardListBaseNFile : 파일테이블과 left join
			//getBoardListBaseNMemoCnt : 베이스 + 메모카운트

			if(isset($_GET['page_size'])){
				if($_GET['page_size']){
					$arrBoardInfo["list"][0]["scale"] = $_GET['page_size'];
				}
			}

			//관리자이거나 회원등급이 게시물 목록보기등급 이상일 경우
			if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] || $_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["LEVEL"] >= $arrBoardInfo["list"][0]["listlevel"]){
				if($arrBoardInfo["list"][0]["boardid"]=="estimate"){	## 견적서
					$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], " AND A.homepage!='sample' ", '');
				}else if($arrBoardInfo["list"][0]["boardid"]=="booking"){	## 부킹
					$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], " AND length(A.etc_2)>0 ", '');
				}else if($arrBoardInfo["list"][0]['skin']=="schedule"){
					//칼렌다 틀 가져오기 날짜설정
					if(!$_REQUEST[cal_date]){
						$cal_date = date("Y-m-d");
					}else{
						$cal_date = $_REQUEST[cal_date];
					}
					//날짜를 - 구분자로 배열로 만듬
					$arrDate = explode("-",$cal_date);

					//양력달력
					$arrSolarCalendar = getDiarySet(intval($arrDate[0]), intval($arrDate[1]), intval($arrDate[2]));

					$arrBoardList = getBoardListSchedule($arrBoardInfo["list"][0]["boardid"], $arrSolarCalendar[first_before], $arrSolarCalendar[last_after]);
				}else if($arrBoardInfo["list"][0]['skin']=="trm_neon"){
					$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET[sw], $_GET[sk], 0, 0, $_GET['reply']);
				}else if($arrBoardInfo["list"][0]["boardid"]=="cdtourist"){
					$arrSDate = explode(",",$_GET['sdate']);
					$squery="";
					for($d=0;$d<count($arrSDate);$d++){
						$weekday = date('w', strtotime($arrSDate[$d]));
						if( $weekday==0 ){
							$weekday = 7;
						}
						$squery .= " AND dayoff not LIKE '%".$weekday."%' ";
					}
					//	echo $squery;
					$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'], $squery);
				}else{
					$arrBoardList = getBoardListBaseNFile($arrBoardInfo["list"][0]["boardid"], $_GET["category"], $_GET['sw'], $_GET['sk'], $arrBoardInfo["list"][0]["scale"], $_GET['offset'],'', $_GET['reply']);
				}

				include($_SITE["BOARD_SKIN"].$arrBoardInfo["list"][0]['skin']."/pop_list.php");

				echo "</div>";
			}else{
				jsMsg($arrLevelInfo[$arrBoardInfo["list"][0]["listlevel"]] . " 이상 글 목록보기가 가능 합니다.");
				jsHistory("-1");
			}
			break;
	}
	//게시판 푸터
	echo stripslashes($arrBoardInfo["list"][0]["footer"]);
}else{
	jsMsg("존재하지 않는 게시판 아이디 입니다.");
	jsHistory("-1");
}

//DB해제
SetDisConn($dblink);
?>
<?php include("pub/inc/footer.php") ?>