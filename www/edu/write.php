<?php include("../inc/header.php"); ?>
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/module/member/member.lib.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/module/category/category.lib.php";

function sanitizeInput($input) {
	if (is_array($input)) {
		return array_map('sanitizeInput', $input);
	}
	return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// POST 데이터 필터링
$_POST = sanitizeInput($_POST);

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
<!-- //Wrap -->
</body>
</html>
