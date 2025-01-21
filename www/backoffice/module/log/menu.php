<?
$thisPHPname = basename($_SERVER['PHP_SELF']);
switch($thisPHPname){
	case 'log_hourly_view.php' :
		$leftMenuClass[0] = "on";	break;
	case 'log_daily_view.php' : 
		$leftMenuClass[1] = "on";	break;
	case 'log_monthly_view.php' : 
		$leftMenuClass[2] = "on";	break;
	case 'log_os_view.php' : 
		$leftMenuClass[3] = "on";	break;
	case 'log_browser_view.php' : 
		$leftMenuClass[4] = "on";	break;
	case 'log_ip_view.php' : 
		$leftMenuClass[5] = "on";	break;
	case 'log_domain_view.php' : 
		$leftMenuClass[6] = "on";	break;
	case 'log_referer_view.php' : 
		$leftMenuClass[7] = "on";	break;
	case 'log_page_view.php' : 
		$leftMenuClass[8] = "on";	break;
}
?>
<div class="aside">
	<a href="javascript:void(0);" class="btn_aside"></a>
	<div class="in_scroll">
		<div class="menu">			
			<dl class="on">
				<dt>접속통계 <i></i></dt>
				<dd style="display:block;"><!-- 열려있는 페이지에는 dd에 display:block 해주세요. -->
					<a class="<?=$leftMenuClass[0]?>" href="/backoffice/module/log/log_hourly_view.php">· 시간대별 접속통계</a>
					<a class="<?=$leftMenuClass[1]?>" href="/backoffice/module/log/log_daily_view.php">· 일별 접속통계</a>
					<a class="<?=$leftMenuClass[2]?>" href="/backoffice/module/log/log_monthly_view.php">· 월별 접속통계</a>
					<a class="<?=$leftMenuClass[3]?>" href="/backoffice/module/log/log_os_view.php">· OS별 접속통계</a>
					<a class="<?=$leftMenuClass[4]?>" href="/backoffice/module/log/log_browser_view.php">· 브라우저별 접속통계</a>
					<a class="<?=$leftMenuClass[5]?>" href="/backoffice/module/log/log_ip_view.php">· IP별 접속통계</a>
					<a class="<?=$leftMenuClass[6]?>" href="/backoffice/module/log/log_domain_view.php">· 링크된 서버</a>
					<a class="<?=$leftMenuClass[7]?>" href="/backoffice/module/log/log_referer_view.php">· 링크된 주소</a>
					<a class="<?=$leftMenuClass[8]?>" href="/backoffice/module/log/log_page_view.php">· 최초 접속 페이지</a>
				</dd>
			</dl>			
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>