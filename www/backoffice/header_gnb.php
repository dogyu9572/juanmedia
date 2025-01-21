		<div class="newtop">
			<ul>
			<?
			## ion-ios-construct	ion-ios-people		ion-ios-attach		ion-ios-cube		ion-ios-basket
			## ion-ios-videocam		ion-ios-film		ion-ios-create		ion-ios-calendar	ion-ios-cafe		ion-ios-body
			?>
				<? if(in_array("admin_manage", $arrayMyMenu) && (in_array("admin_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<li><a href="/backoffice/module/admin/admin.php" ><i class="icon ion-ios-construct"></i><br/><span>관리자관리</span></a></li>
				<?}?>				
				<? if(in_array("member_manage", $arrayMyMenu) && (in_array("member_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<!-- 회원관리 -->
				<li><a href="/backoffice/module/member/member.php"><i class="icon ion-ios-people"></i><br/><span>직원관리</span></a></li>
				<?}?>
				<? if(in_array("point_manage", $arrayMyMenu) && (in_array("point_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<!-- 프로모션관리 -->
				<li><a href="/backoffice/module/coupon/member_coupon.php"><i class="icon ion-ios-attach"></i><br/><span>쿠폰 관리</span></a></li>
				<!-- 프로모션관리 -->
				<?}?>
				<? if(in_array("shop_good_manage", $arrayMyMenu) && (in_array("shop_good_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<!-- 상품관리 -->
				<li><a href="/backoffice/module/shop/good.php"><i class="icon ion-ios-cube"></i><br/><span>상품관리</span></a></li>
				<?}?>

				<? if(in_array("shop_order_manage", $arrayMyMenu) && (in_array("shop_order_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<!-- 주문관리 -->
				<li><a href="/backoffice/module/shop/order.php?mode=1"><i class="icon ion-ios-basket"></i><br/><span>주문관리</span></a></li>
				<?}?>

				<? if(in_array("board_manage", $arrayMyMenu) && (in_array("vod_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<!-- 게시판 -->
					<li><a href="/backoffice/module/board/board_view.php?boardid=notice"><i class="icon ion-ios-cafe"></i><br/><span>게시판관리</span></a></li>
				<?}?>			
			
			
				<? if(in_array("banner_manage", $arrayMyMenu) && (in_array("banner_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<!-- 배너관리 -->
				<li><a href="/backoffice/module/banner/banner.php"><i class="icon ion-md-images"></i><br/><span>이미지 관리</span></a></li>
				<!-- 배너관리 -->
				<?}?>		

				<? if(in_array("popup_manage", $arrayMyMenu) && (in_array("popup_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<!-- 팝업관리 -->
				<li><a href="/backoffice/module/popup/popup_list.php"><i class="icon ion-md-settings"></i><br/><span>팝업 관리</span></a></li>
				<?}?>				
				
				<? if(in_array("log_manage", $arrayMyMenu) && (in_array("log_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]=="ROOT")){?>
				<li><a href="/backoffice/module/log/log_hourly_view.php" ><i class="icon ion-md-stats"></i><br/><span>접속 통계</span></a></li>
				<?}?>			
			</ul>
		</div>