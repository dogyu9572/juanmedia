<?
$thisPHPname = basename($_SERVER['PHP_SELF']);
switch($thisPHPname){
	case 'statistic_total.php' :
		$leftMenuClass[0] = "on";	break;
	case 'statistic_gender.php' :
		$leftMenuClass[1] = "on";	break;
	case 'statistic_edu.php' :
		$leftMenuClass[2] = "on";	break;
	case 'statistic_equ.php' :
		$leftMenuClass[3] = "on";	break;
	case 'statistic_place.php' :
		$leftMenuClass[4] = "on";	break;
    case 'statistic_video.php' :
        $leftMenuClass[5] = "on";	break;
    case 'statistic_media.php' :
        $leftMenuClass[6] = "on";	break;
}
?>
<div class="aside">
	<a href="javascript:void(0);" class="btn_aside"></a>
	<div class="in_scroll">
		<div class="menu">			
			<dl class="on">
				<dt>접속통계 <i></i></dt>
				<dd style="display:block;"><!-- 열려있는 페이지에는 dd에 display:block 해주세요. -->
					<a class="<?=$leftMenuClass[0]?>" href="/backoffice/module/statistic/statistic_total.php">· 전체통계</a>
					<a class="<?=$leftMenuClass[1]?>" href="/backoffice/module/statistic/statistic_gender.php">· 성별/나이통계</a>
					<a class="<?=$leftMenuClass[2]?>" href="/backoffice/module/statistic/statistic_edu.php">· 교육통계</a>
					<a class="<?=$leftMenuClass[3]?>" href="/backoffice/module/statistic/statistic_equ.php">· 장비통계</a>
					<a class="<?=$leftMenuClass[4]?>" href="/backoffice/module/statistic/statistic_place.php">· 공간통계</a>
                    <a class="<?=$leftMenuClass[5]?>" href="/backoffice/module/statistic/statistic_video.php">· 상영회통계</a>
                    <a class="<?=$leftMenuClass[6]?>" href="/backoffice/module/statistic/statistic_media.php">· 미디어체험통계</a>
				</dd>
			</dl>			
		</div>
		<?include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/admin_info.php";?>
	</div>
</div>