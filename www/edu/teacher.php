<?php include("../inc/header.php"); ?>
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/module/member/member.lib.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/module/category/category.lib.php";

// DB연결
$dblink = SetConn ( $_conf_db ["main_db"] );

$arrMemberInfo = getUserInfo($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);

// DB해제
SetDisConn ( $dblink );
?>
<?php $gNum="01"; $sNum="04"; $gName="미디어교육"; $sName="강사지원"; ?>
<!-- Container -->
<div class="container sub" id="container">
    <!-- subTopBg -->
    <div class="subTopBg mediaEdu">
        <div class="inner">
            <div class="enName">MEDIA EDUCATION</div>
            <div class="korName">미디어교육</div>
            <?php include("../inc/sub_navi.php"); ?>
        </div>
    </div>
    <!-- //subTopBg -->
    <?php
    $boardid = "teacher";
    include $_SERVER["DOCUMENT_ROOT"]."/module/board/board.php";
    ?>
</div>
<!-- //Container -->

<?php include("../inc/quick.php"); ?>

<?php include("../inc/footer.php"); ?>

</div>

</body>
</html>
