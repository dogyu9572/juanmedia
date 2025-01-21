<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/header.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/memo/memo.lib.php");
log_session($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);
$dblink = SetConn($_conf_db["main_db"]);
$scale = 15;
$pagescale = 10;
if(!$type){$type="receive";}
if($type){
	switch($type){
	case("receive") :		
		$memoList = receivememoList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $_GET[sw], $_GET[sk], $scale, $_GET[offset]);
	break;
	case("send") :
		$memoList = sendmemoList($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $_GET[sw], $_GET[sk], $scale, $_GET[offset]);	
	break;	
	}
}
?>

      <table width="730" border="0" cellspacing="0" cellpadding="0">

        <tr>
          <td>

            <table width="710" border="0" cellpadding="0" cellspacing="0" background="img/login_box_bg.gif">
			<tr><td><a href="memo_list.php?type=receive">����������</a> / <a href="memo_list.php?type=send">����������</a> / <a href="memo_savelist.php?type=send">����������</a> / <a href="memo_insert.php">��������</a></td></tr>
              <tr>
                <td align="center" style="padding:10px 0 10px 0;">
		
                  <table width="710" border="1" cellspacing="0" cellpadding="0">
                    <tr bgcolor="#0099CC">
                      <td width=10 align=center>No.</td>
                      <td width=350 align=center>Contents</td>
                      <td width=100 align=center>ID</td>
                      <td width=150 align=center>Date</td>
                      <td width=100 align=center>Stat</td>
                      <td width=150 align=center>Read Date</td>
                    </tr>

                    <?
					if($type=="receive"){
                    if($memoList["list"]["total"] > 0){
                    for($i=0; $i < $memoList["list"]["total"]; $i++){
                    //�űԱ� ǥ��
                    if(strtotime($memoList["list"][$i][wdate])+(1*86400) > mktime()){
                    $newImage =" <img src='".$_SITE["BOARD_SKIN_URL"]."/".$arrBoardInfo["list"][0][skin]."/images/icon_new.gif' align='absmiddle' />";
                    }
                    else{
                    $newImage ="";
                    }
                    ?>
                    <tr onmouseover="this.style.backgroundColor='#3399FF'" onmouseout="this.style.backgroundColor=''">
                      <td width=10 align=center><?=$memoList["total"]-$i-$_GET[offset]?></td>
                      <td width=350 align=center><a href="/module/memo/memo_view.php?idx=<?=$memoList['list'][$i]['idx']?>&stat=<?=$memoList['list'][$i]['is_read']?>&type=receive"><?=text_cut($memoList['list'][$i]['content'],30)?></a></td>
                      <td width=100 align=center><?=$memoList['list'][$i]['from_user_id']?></td>
                      <td width=150 align=center><?=$memoList['list'][$i]['wdate']?></td>
                      <td width=100 align=center><?=$memoList['list'][$i]['is_read']?></td>
                      <td width=150 align=center><?=$memoList['list'][$i]['rdate']?></td>
                    </tr>
                    <?
                    }
                    }
                    else{
                    ?>
                    <tr>
                      <td colspan="7" align="center" height="100">��ϵ� ������ �����ϴ�.</td>
                    </tr>
                    <?
                    }
                    ?>
                  </table>
                </td>
              </tr>
              <tr>
                <td align=center><?=pageNavigation($memoList["total"],$scale,$pagescale,$_GET[offset],"&boardid=tbl_memo_receive&sk=".$_GET[sk]."&sw=".$_GET[sw])?></td>
              </tr>
            </table>
<?	
}elseif($type=="send"){
	if($memoList["list"]["total"] > 0){
                    for($i=0; $i < $memoList["list"]["total"]; $i++){
                    //�űԱ� ǥ��
                    if(strtotime($memoList["list"][$i][wdate])+(1*86400) > mktime()){
                    $newImage =" <img src='".$_SITE["BOARD_SKIN_URL"]."/".$arrBoardInfo["list"][0][skin]."/images/icon_new.gif' align='absmiddle' />";
                    }
                    else{
                    $newImage ="";
                    }
                    ?>
                    <tr onmouseover="this.style.backgroundColor='#AAE3FF'" onmouseout="this.style.backgroundColor=''">
                      <td width=10 align=center><?=$memoList["total"]-$i-$_GET[offset]?></td>
                      <td width=350 align=center><a href="/module/memo/memo_view.php?idx=<?=$memoList['list'][$i]['idx']?>&stat=<?=$memoList['list'][$i]['is_read']?>&type=send"><?=text_cut($memoList['list'][$i]['content'],30)?></a></td>
                      <td width=100 align=center><?=$memoList['list'][$i]['to_user_id']?></td>
                      <td width=150 align=center><?=$memoList['list'][$i]['wdate']?></td>
                      <td width=100 align=center><?=$memoList['list'][$i]['is_read']?></td>
                      <td width=150 align=center><?=$memoList['list'][$i]['rdate']?></td>
                    </tr>
                    <?
                    }
                    }
                    else{
                    ?>
                    <tr>
                      <td colspan="7" align="center" height="100">��ϵ� ������ �����ϴ�.</td>
                    </tr>
                    <?
                    }
                    ?>
                  </table>
                </td>
              </tr>
              <tr>
                <td align=center><?=pageNavigation($memoList["total"],$scale,$pagescale,$_GET[offset],"&boardid=tbl_memo_receive&sk=".$_GET[sk]."&sw=".$_GET[sw])?></td>
              </tr>
            </table>
<?	}	?>

          </td>
        </tr>
      </table>
<? include $_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"; ?>