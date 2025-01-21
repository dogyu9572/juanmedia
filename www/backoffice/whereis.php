<?
/*
관리자 페이지의 현재 위치 표시 정보
*/
//$Find_Where = explode($_SERVER['DOCUMENT_ROOT'],$SCRIPT_FILENAME);//SCRIPT_FILENAME 은 아파치 환경변수
$Find_Where = explode($_SERVER['DOCUMENT_ROOT'],$_SERVER['SCRIPT_FILENAME']);//SCRIPT_FILENAME 은 아파치 환경변수
switch($Find_Where[1]){	
	case("/backoffice/index.php"):
		$whereis_admin="Administrator Mainpage";
	break;
	case("/backoffice/module/admin/admin.php"):
	case("/backoffice/module/admin/admin_info.php"):
		$whereis_admin="관리자 관리";
		break;
	//분류관리
	case("/backoffice/module/category/category.php"):
	case("/backoffice/module/category/category_info.php"):
		$whereis_admin="분류 관리";
		break;
	//제품 관리
	case("/backoffice/module/product/product.php"):
	case("/backoffice/module/product/product_info.php"):
	case("/backoffice/module/product/product_add.php"):
		$whereis_admin="제품 관리";
		break;
	//상품 관리
	case("/backoffice/module/shop/good.php"):
	case("/backoffice/module/shop/good_info.php"):
	case("/backoffice/module/shop/good_add.php"):
		$whereis_admin="상품 관리";
		break;
	//주문조회
	case("/backoffice/module/shop/order.php"):
		$whereis_admin="주문 관리";
		break;
	//매출조회
	case("/backoffice/module/shop/accounts.php"):
		$whereis_admin="매출 관리";
		break;
	case("/backoffice/module/shop/order_detail.php"):
		$whereis_admin="주문 상세정보";
		break;
	case("/backoffice/module/mail/mail.php"):
		$whereis_admin="메일 관리";
		break;
	//메일발송 관리
	case("/backoffice/module/mail/send_list.php"):
	case("/backoffice/module/mail/send_add.php"):
	case("/backoffice/module/mail/send_info.php"):
		$whereis_admin="메일발송 관리";
		break;
	case("/backoffice/module/one_to_one/one_to_one.php"):
	case("/backoffice/module/one_to_one/one_to_one_info.php"):
		$whereis_admin="1:1 문의 관리";
		break;
	//적립금관리
	case("/backoffice/module/point/point_list.php"):
	case("/backoffice/module/point/point_add.php"):
		$whereis_admin="적립금 관리";
		break;
	case("/backoffice/module/shop/review/review.php"):
	case("/backoffice/module/shop/review/review_info.php"):
		$whereis_admin="이용후기 관리";
		break;
	//게시판 관리
	case("/backoffice/module/board/board.php"):
	case("/backoffice/module/board/board_info.php"):
		$whereis_admin="게시판 관리";
		break;
	//게시물 관리
	case("/backoffice/module/board/board_view.php"):
		$whereis_admin="게시물 관리";
		break;
	//댓글 관리
	case("/backoffice/module/board/comment_list.php"):
		$whereis_admin="댓글 관리";
		break;
	//온라인견적 관리
	case("/backoffice/module/online/online_list.php"):
	case("/backoffice/module/online/online_info.php"):
		$whereis_admin="온라인견적 관리";
		break;
	//회원관리
	case("/backoffice/module/member/member_level.php"):
	case("/backoffice/module/member/member_level_info.php"):
		$whereis_admin="회원등급 관리";
		break;
	case("/backoffice/module/member/member.php"):
	case("/backoffice/module/member/member_info.php"):
		$whereis_admin="회원 관리";
		break;
	//팝업관리
	case("/backoffice/module/popup/popup_list.php"):
	case("/backoffice/module/popup/popup_add.php"):
	case("/backoffice/module/popup/popup_info.php"):
		$whereis_admin="팝업 관리";
		break;
	//배너관리
	case("/backoffice/module/banner/banner.php"):
	case("/backoffice/module/banner/banner_add.php"):
	case("/backoffice/module/banner/banner_info.php"):
		$whereis_admin="배너 관리";
		break;
	case("/backoffice/module/poll/poll.php"):
	case("/backoffice/module/poll/poll_add.php"):
	case("/backoffice/module/poll/poll_info.php"):
	case("/backoffice/module/poll/poll_result.php"):
		$whereis_admin="투표 관리";
		break;
	case("/backoffice/module/research/research.php"):
	case("/backoffice/module/research/research_add.php"):
	case("/backoffice/module/research/research_info.php"):
	case("/backoffice/module/research/research_join_list.php"):
	case("/backoffice/module/research/research_view.php"):
		$whereis_admin="설문조사 관리";
		break;

	//접속통계관련
	case("/backoffice/module/log/log_hourly_view.php"):
		$whereis_admin="시간대별 접속통계";
		break;
	case("/backoffice/module/log/log_daily_view.php"):
		$whereis_admin="일별 접속통계";
		break;
	case("/backoffice/module/log/log_monthly_view.php"):
		$whereis_admin="월별 접속통계";
		break;
	case("/backoffice/module/log/log_os_view.php"):
		$whereis_admin="OS 별 접속통계";
		break;
	case("/backoffice/module/log/log_ip_view.php"):
		$whereis_admin="IP 별 접속통계";
		break;
	case("/backoffice/module/log/log_browser_view.php"):
		$whereis_admin="브라우저별 접속통계";
		break;
	case("/backoffice/module/log/log_domain_view.php"):
		$whereis_admin="링크된 도메인";
		break;
	case("/backoffice/module/log/log_referer_view.php"):
		$whereis_admin="링크된 주소";
		break;
	case("/backoffice/module/log/log_page_view.php"):
		$whereis_admin="최초 접속 페이지";
		break;
	case("/backoffice/module/log/log_searchengin_view.php"):
		$whereis_admin="검색엔진별 통계";
		break;
	case("/backoffice/module/log/log_keyword_view.php"):
		$whereis_admin="검색 키워드별 통계";
		break;
	case("/backoffice/module/log/log_log_view.php"):
		$whereis_admin="방문자 로그";
		break;
	default:
		$whereis_admin="Not Configured in whereis.php";
	break;
}
?>
