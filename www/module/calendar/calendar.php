<? 
@session_start();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/calendar/calendar.lib.php");

//Į���� Ʋ ��������
if(!$_REQUEST[cal_date]){
	$cal_date = date("Y-m-d");
}else{
	$cal_date = $_REQUEST[cal_date];
}

//��¥�� - �����ڷ� �迭�� ����
$arrDate = explode("-",$cal_date);


//��´޷�
//��´޷��� ���� �׸���, ���´޷��� ������ �߰��� ���δ�.
//�߰������Ͱ� �ִٸ� ���´޷�ó�� �迭�� ����� �߰��� ���δ�.
$arrSolarCalendar = getDiarySet(intval($arrDate[0]), intval($arrDate[1]), intval($arrDate[2]));

//DB���� - ������
$dblink = SetConn($_conf_db["zipcode"]);

//���´޷�
//�����ó�¥ ������ ~ ������ (yyyy-mm-dd, yyyy-mm-dd)
$arrLunarCalendar = getCalendarLunar($arrSolarCalendar[first_before], $arrSolarCalendar[last_after]);

//DB����
SetDisConn($dblink);
?>
<style>
TD {font-size:9pt}
</style>
<table border="0" cellspacing="0" cellpadding="0" width="700">
	<tr>
		<th align="left"><a href="<?=$_SERVER["PHP_SELF"]?>?cal_date=<?=$arrSolarCalendar[prev_month]?>"><?=substr($arrSolarCalendar[prev_month],0,7)?></a></th>
		<th align="center"><?=$arrDate[0]?>-<?=$arrDate[1]?></th>
		<th align="right"><a href="<?=$_SERVER["PHP_SELF"]?>?cal_date=<?=$arrSolarCalendar[next_month]?>"><?=substr($arrSolarCalendar[next_month],0,7)?></a></th>
	</tr>
</table>
<table border="1" cellspacing="0" cellpadding="0" width="700">

	<?for($i=0;$i<count($arrSolarCalendar["box"]);$i++){?>
	<tr>
		<?for($j=0;$j<7;$j++){
			//������, ����������, �Ͽ����� ���
			if($arrLunarCalendar[$arrSolarCalendar["box"][$i][$j]][holiday]=="1" || $j==0){
				$bgcolor = "#FFA8A8";
			//������� ���
			}else if($j==6){
				$bgcolor = "#99CCFF";
			}else{
				$bgcolor = "#EEEEEE";
			}
		?>
		<td align="center" width="100" height="100" valign="top">
		<table border="0" cellspacing="0" cellpadding="3" width="100%">
		<tr bgcolor="<?=$bgcolor?>">
			<td align="left"><b><?=substr($arrSolarCalendar["box"][$i][$j],-2)?></b></td>
			<td align="right"><?=$arrLunarCalendar[$arrSolarCalendar["box"][$i][$j]][cd_lm]?>/<?=$arrLunarCalendar[$arrSolarCalendar["box"][$i][$j]][cd_ld]?></td>
		</tr>
		<tr>
			<td colspan="2" align="left">
			<?=$arrLunarCalendar[$arrSolarCalendar["box"][$i][$j]][cd_sol_plan]?"<li>".$arrLunarCalendar[$arrSolarCalendar["box"][$i][$j]][cd_sol_plan]."</li>":""?>
			<?=$arrLunarCalendar[$arrSolarCalendar["box"][$i][$j]][cd_lun_plan]?"<li>".$arrLunarCalendar[$arrSolarCalendar["box"][$i][$j]][cd_lun_plan]."</li>":""?>
			</td>
		</tr>
		</table>
		</td>
		<?}?>
	</tr>
	<?}?>

</table>
<?
//_DEBUG($arrSolarCalendar);
//_DEBUG($arrLunarCalendar);
?>
