<?
if(!$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]) {
?>
<script type="text/javascript">
<!--
// alert('해당 페이지는 로그인 후 사용할 수 있습니다.');
location.href="/member/login.php?rt_url=<?=str_replace("&","||",$_SERVER['REQUEST_URI'])?>";
//-->
</script>
<?
	exit;
}
?>