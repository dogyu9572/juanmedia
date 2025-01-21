<?

@session_start();
if ($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"] != "") {
	//echo "<meta http-equiv=refresh content='0; URL=/'>";
?>
<script type="text/javascript">
<!--
alert('해당 페이지는 로그인 후 사용할 수 없습니다.');
location.href="/";
//-->
</script>
<?
	exit;
}
?>