<?php
//DEBUG 용
function _DEBUG($var){
    echo "<div id='DEBUG'><fieldset><legend>DEBUG</legend><xmp>";
    var_dump($var);
    echo "</xmp></fieldset></div>";
}

function jsGo($sURL,$target="",$msg=""){
		
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'DTD/xhtml1-transitional.dtd'>\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv='Content-Type' content='text/html; charset=euc-kr' />\n";
	echo "<script type='text/javascript'>\n";
	if($msg != "") {echo "alert('".$msg."');\n";}
	if($target != ""){
		echo $target . "." . "location.href = '" . $sURL . "';\n";
	}else{
		echo "document.location.href = '" . $sURL . "';\n";
	}
	echo "</script>\n";
	echo "</head>";
	exit;
}

function metaGo($sURL){
		
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'DTD/xhtml1-transitional.dtd'>\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv='Content-Type' content='text/html; charset=euc-kr' />\n";
	echo "<meta http-equiv='Refresh' content='0; URL=$sURL'>";	
	echo "</head>";
	exit;
}

function jsMsg($msg="") 
{
		
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'DTD/xhtml1-transitional.dtd'>\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv='Content-Type' content='text/html; charset=euc-kr' />\n";
	echo "<script type='text/javascript'>\n";
	echo "alert('".$msg."');\n";
	echo "</script>\n";
	echo "</head>";
}

function jsHistory($hist="-1")
{
	echo "<script type='text/javascript'>\n";
	echo "history.go('".$hist."');\n";
	echo "</script>\n";
	exit;
}

function selfClose(){
	echo "<script type='text/javascript'>\n";
	echo "self.close();\n";
	echo "</script>\n";
	exit;
}

?>