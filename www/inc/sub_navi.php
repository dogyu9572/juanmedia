<div class="lnb">
	<a href="/"><img src="/images/ico_home.svg" alt="home"></a>
	<div class="lnbSub">
		<div class="tit"><?=$gName?></div>
		<ul>
			<li class="<?if($gNum=="01"){?>on<?}?>"><a href="/edu/info.php">미디어교육</a></li>
			<li class="<?if($gNum=="02"){?>on<?}?>"><a href="/equ/info.php">장비대여</a></li>
			<li class="<?if($gNum=="03"){?>on<?}?>"><a href="/place/info.php">공간대관</a></li>
			<li class="<?if($gNum=="04"){?>on<?}?>"><a href="/media/info.php">미디어체험</a></li>
			<li class="<?if($gNum=="05"){?>on<?}?>"><a href="/cm/notice.php">게시판</a></li>
			<li class="<?if($gNum=="06"){?>on<?}?>"><a href="/center/intro.php">센터안내</a></li>
		</ul>
	</div>
	<div class="lnbSub">
		<div class="tit"><?=$sName?></div>
		<ul>
	<?php if ($gNum=="01") { ?>
		   <li class="<?if($gNum=="01"&&$sNum=="01"){?>on<?}?>"><a href="/edu/info.php">교육신청 안내</a></li>
		   <li class="<?if($gNum=="01"&&$sNum=="02"){?>on<?}?>"><a href="/edu/list.php?cat_no=63">상설교육</a></li>
		   <li class="<?if($gNum=="01"&&$sNum=="03"){?>on<?}?>"><a href="/edu/list.php?cat_no=64">공동체교육</a></li>
		   <li class="<?if($gNum=="01"&&$sNum=="04"){?>on<?}?>"><a href="/edu/teacher.php">강사지원</a></li>
	<?php } elseif ($gNum=="02") { ?>
		   <li class="<?if($gNum=="02"&&$sNum=="01"){?>on<?}?>"><a href="/equ/info.php">대여안내</a></li>
		   <li class="<?if($gNum=="02"&&$sNum=="02"){?>on<?}?>"><a href="/equ/list.php">장비대여신청</a></li>
	<?php } elseif ($gNum=="03") { ?>
		   <li class="<?if($gNum=="03"&&$sNum=="01"){?>on<?}?>"><a href="/place/info.php">대관안내</a></li>
		   <li class="<?if($gNum=="03"&&$sNum=="02"){?>on<?}?>"><a href="/place/list.php">공간대관신청</a></li>
	<?php } elseif ($gNum=="04") { ?>
		   <li class="<?if($gNum=="04"&&$sNum=="01"){?>on<?}?>"><a href="/media/info.php">미디어체험</a></li>
		   <li class="<?if($gNum=="04"&&$sNum=="02"){?>on<?}?>"><a href="/media/order.php">체험신청</a></li>
		   <li class="<?if($gNum=="04"&&$sNum=="03"){?>on<?}?>"><a href="/media/video.php">상영회</a></li>
		   <li class="<?if($gNum=="04"&&$sNum=="04"){?>on<?}?>"><a href="/media/list.php">상영회신청</a></li>
	<?php } elseif ($gNum=="05") { ?>
		   <li class="<?if($gNum=="05"&&$sNum=="01"){?>on<?}?>"><a href="/cm/notice.php">공지&뉴스</a></li>
		   <li class="<?if($gNum=="05"&&$sNum=="02"){?>on<?}?>"><a href="/cm/free.php">자유게시판</a></li>
		   <li class="<?if($gNum=="05"&&$sNum=="03"){?>on<?}?>"><a href="/cm/dataList.php">자료실</a></li>
	<?php } elseif ($gNum=="06") { ?>
		   <li class="<?if($gNum=="06"&&$sNum=="01"){?>on<?}?>"><a href="/center/intro.php">센터소개</a></li>
		   <li class="<?if($gNum=="06"&&$sNum=="02"){?>on<?}?>"><a href="/center/park.php">미디어파크 소개</a></li>
		   <li class="<?if($gNum=="06"&&$sNum=="03"){?>on<?}?>"><a href="/center/organ.php">조직 및 스탭소개</a></li>
		   <li class="<?if($gNum=="06"&&$sNum=="04"){?>on<?}?>"><a href="/center/fac.php">시설안내</a></li>
		   <li class="<?if($gNum=="06"&&$sNum=="05"){?>on<?}?>"><a href="/center/info.php">이용안내</a></li>
		   <li class="<?if($gNum=="06"&&$sNum=="06"){?>on<?}?>"><a href="/center/schedule.php">센터일정</a></li>
		   <li class="<?if($gNum=="06"&&$sNum=="07"){?>on<?}?>"><a href="/center/location.php">찾아오시는 길</a></li>
	<?php } ?>
		</ul>
	</div>
</div>