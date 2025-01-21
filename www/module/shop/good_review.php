<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/include/headHtml.php'; 
?>
<body>
            
	<?	$boardid = "after";	
	$_GET['sw']="e"; 
	if(!$_GET['sk']) $_GET['sk']=$_GET['idx']; 
	include $_SITE[BOARD_PATH]."/board.php";	?>		
    
</body>
</html>