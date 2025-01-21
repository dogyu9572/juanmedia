<? 
//샵 기본모듈 포함
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/mail/mail.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/member/member.lib.php"; 
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/point/point.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/one_to_one/one_to_one.lib.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/shop/review/review.lib.php";

if(!$_REQUEST['goPage'] && $_REQUEST['boardid']){
	$_REQUEST['goPage']="GoodDetail"; 
	$_REQUEST['idx']=$_REQUEST['sk']; 
}

//표시할 페이지
if($_REQUEST['goPage']){
	switch($_REQUEST['goPage']){
		case("GoodDetail"):	
			$incPage = "/en/brand/pr_detail.php";			// 상품 상세
			break;
		case("GoodDetail2"):	
			$incPage = "shop/good_detail2.php";
			break;
		case("Cart"):	
			$incPage = "/pay/cart.php";				// 장바구니
			break;
		case("GoodList"):	
			$incPage = "/en/brand/brand_01.php";			// 상품 리스트
			break;
		case("GoodAll"):	
			$incPage = "/brand/pr_list.php";			// 상품 리스트
			break;
		case("Payment"):	
			$incPage = "/pay/order_payment.php";		// 결제
			break;
		case("Search"):	
			$incPage = "shop/good_search.php";
			break;
		case("Order"):	
			$incPage = "/pay/order.php";				// 주문서 작성
			break;
		case("OrderGiftCard"):	
			$incPage = "shop/order_giftcard.php";
			break;
		case("Thanks"):	
			$incPage = "/pay/confirm.php";			// 주문 완료
			break;
		//여기서부터 마이페이지
		case("MyPage"):	
			$incPage = "/member/list.php";			// 마이페이지
			break;
		case("Cancel"):	
			$incPage = "/my/cancel.php";			// 취소
			break;
		case("Purchase"):	
			$incPage = "/my/search.php";			// 구매내역
			break;
		case("Address"):	
			$incPage = "shop/mypage/address.php";
			$sNum="5";
			break;
		case("Point"):	
			$incPage = "shop/mypage/point.php";
			$incMenu = "mypage_menu.php";
			$sNum="3";
			break;
		case("OrderList"):	
			$incPage = "/my/delivery.php";
			$incMenu = "mypage_menu.php";
			$sNum="3";
			break;
		case("OrderInfo"):	
			$incPage = "shop/mypage/order_info.php";
			$incMenu = "mypage_menu.php";
			$sNum="3";
			break;
		case("WishList"):	
			$incPage = "/my/get.php";								// 위시리스트 shop/mypage/wish_list.php
			$incMenu = "mypage_menu.php";
			$sNum="2";
			break;
		case("MyQna"):	
			$incPage = "one_to_one/one_to_one.php";
			$sNum="5";
			break;
		case("MyQnaWrite"):	
			$incPage = "one_to_one/one_to_one_form.php";
			$sNum="5";
			break;
		case("MyQnaView"):	
			$incPage = "one_to_one/one_to_one_view.php";
			$sNum="5";
			break;
		case("MyReview"):	
			$incPage = "shop/review/my_review.php";
			$incMenu = "mypage_menu.php";
			break;
		case("MyReviewWrite"):	
			$incPage = "shop/review/form.php";
			$incMenu = "mypage_menu.php";
			break;
		case("MyReviewView"):	
			$incPage = "shop/review/my_review_view.php";
			$incMenu = "mypage_menu.php";
			break;
		default: 
			$incPage = "";
			$incMenu = "mypage_menu.php";
			break;
	}

	if($incPage !="" && file_exists($_SERVER['DOCUMENT_ROOT'] . $incPage)){
		include($_SERVER['DOCUMENT_ROOT'] . $incPage);
	}
}
?>
