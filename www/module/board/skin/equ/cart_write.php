<?php
	$writeRS = insertBoardArticle($_GET['boardid'], $arrBoardInfo["list"][0]["thumwidth"]);
	jsGo($_SERVER["PHP_SELF"]."?boardid=equ_applicants_cart&mode=cart","","");
?>
