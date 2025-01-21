<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/include/headHtml.php'; 
?>
<body>

<?	$boardid = "faq";	$category=$_GET['category']; include $_SITE[BOARD_PATH]."/board.php";	?>	

</body>
</html>