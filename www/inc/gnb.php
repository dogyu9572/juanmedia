<?php

include_once $_SERVER["DOCUMENT_ROOT"]."/common/conf/config.inc.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/module/category/category.lib.php";

//DB연결
$dblink = SetConn($_conf_db["main_db"]);

$arrTopMenuList = getCategoryList("3","Y");	// 메뉴

$arrTopMenu = array();
$arrSubMenu = array();

for($i=0;$i<$arrTopMenuList["total"];$i++){
    $arrTopMenu[$arrTopMenuList["list"][$i]["cat_no"]] = $arrTopMenuList["list"][$i];
    $arrSubMenuList = getCategoryList($arrTopMenuList["list"][$i]["cat_no"],"Y");	// 메뉴
    for($j=0;$j<$arrSubMenuList["total"];$j++){
        $arrSubMenu[$arrTopMenuList["list"][$i]["cat_no"]][$arrSubMenuList["list"][$j]["cat_no"]] = $arrSubMenuList["list"][$j];
    }
}

//DB해제
SetDisConn($dblink);
?>
               <div class="gnbWrap">
                   <div class="gnbList">
                       <ul>
                           <?php
                           $num = 1;
                           foreach($arrTopMenu as $key => $arrVal){
                               ?>
                               <li class="menu gnb<?=$num?> <?if($gNum == $arrVal["cat_etc_1"]){?>on<?}?>">
                                   <div class="tit"><a href="<?=$arrVal["cat_engname"]?>"><?=$arrVal["cat_name"]?></a></div>
                                   <div class="subGnb">
                                       <?php if ($arrVal["cat_engname"] == "/edu/info.php") { ?>
                                           <ul>
                                               <li class="on"><a href="/edu/info.php">교육신청 안내</a></li>
											   <li><a href="/edu/list.php">교육신청</a></li>
                                               <!-- <li><a href="/edu/list.php">상설교육</a></li>
                                               <li><a href="/edu/list.php?cat_no=116">공동체교육</a></li> -->
                                               <li><a href="/edu/teacher.php">강사지원</a></li>
                                           </ul>
                                       <?php } elseif ($arrVal["cat_engname"] == "/equ/info.php") { ?>
                                           <ul>
                                               <li><a href="/equ/info.php">대여안내</a></li>
                                               <li><a href="/equ/list.php">장비대여신청</a></li>
                                           </ul>
                                       <?php } elseif ($arrVal["cat_engname"] == "/place/info.php") { ?>
                                           <ul>
                                               <li><a href="/place/info.php">대관안내</a></li>
                                               <li><a href="/place/list.php">공간대관신청</a></li>
                                           </ul>
                                       <?php } elseif ($arrVal["cat_engname"] == "/media/info.php") { ?>
                                           <ul>
                                               <li><a href="/media/info.php">미디어 체험 안내</a></li>
                                               <li><a href="/media/order.php">체험신청</a></li>
                                               <li><a href="/media/video.php">상영회 안내</a></li>
                                               <li><a href="/media/list.php">상영회신청</a></li>
                                           </ul>
                                       <?php } elseif ($arrVal["cat_engname"] == "/cm/notice.php") { ?>
                                           <ul>
                                               <li><a href="/cm/notice.php">공지 사항</a></li>
                                               <li><a href="/cm/free.php">자유게시판</a></li>
                                               <li><a href="/cm/dataList.php">자료실</a></li>
                                           </ul>
                                       <?php } elseif ($arrVal["cat_engname"] == "/center/intro.php") { ?>
                                           <ul>
                                               <li><a href="/center/intro.php">센터소개</a></li>
                                               <li><a href="/center/park.php">미디어파크 소개</a></li>
                                               <li><a href="/center/organ.php">조직 및 스탭소개</a></li>
                                               <li><a href="/center/fac.php">시설안내</a></li>
                                               <li><a href="/center/info.php">이용안내</a></li>
                                               <li><a href="/center/schedule.php">센터일정</a></li>
                                               <li><a href="/center/location.php">찾아오시는 길</a></li>
                                           </ul>
                                       <?php } ?>
                                   </div>
                               </li>
                               <?php
                               $num++;
                           }
                           ?>
                       </ul>
                   </div>
					<div class="mo_vw mem_area">
						<a href="/equ/list.php?boardid=equ_applicants_cart&mode=cart">장바구니</a>
					<?php if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] != ""){?>
						<a href="/module/member/logout.php">로그아웃</a>
						<a href="/mypage/orderList.php">마이페이지</a> <!-- 임시로 모바일에서는 히든처리 -->
					<?php }else{ ?>
						<a href="/member/login.php">로그인</a>
						<a href="/member/agree.php">회원가입</a>
					<?php } ?>
					</div>
				</div>
