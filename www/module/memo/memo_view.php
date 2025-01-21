<?
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/header.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/memo/memo.lib.php");
log_session($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);
$dblink = SetConn($_conf_db["main_db"]);
if($type=="receive"){	$memoView = receivememoView($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $stat, $idx);	}
if($type=="send"){	$memoView = sendmemoView($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $stat, $idx);	}
if($type=="save"){	$memoView = savememoView($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"], $idx);	}
?>
<script>
function del(idx,type){
if(confirm('�����Ͻðڽ��ϱ�?')){
		location.href='/module/memo/memo_evn.php?evnMode=delete&idx='+idx+'&type='+type;
	}
}
</script>

      <!-- S center box -->
      <table width="730" border="0" cellspacing="0" cellpadding="0">
		<tr><td><a href="memo_list.php?type=receive">����������</a> / <a href="memo_list.php?type=send">����������</a> / <a href="memo_savelist.php?type=send">����������</a> / <a href="memo_insert.php">��������</a></td></tr>
        <tr>
          <td>

            <table width="610" border="0" cellpadding="0" cellspacing="0" background="img/login_box_bg.gif">
              <tr>
                <td align="center" style="padding:10px 0 10px 0;">
                  <table width="550" border="1" cellspacing="0" cellpadding="0">
                    <form name='insertform' action="/module/memo/memo_evn.php" method="POST">
					<input type="hidden" name="evnMode" value="save">
					<input type="hidden" name="idx" value="<?=$memoView['list'][0]['idx']?>">
					<input type="hidden" name="type" value="<?=$type?>">
					<tr>
					<?	if($type!="send"){	?>
					<td width=150>�������̵�:</td>
					<td><?=$memoView['list'][0]['from_user_id']?></td>
					<?	}else{	?>
					<td width=150>�޴¾��̵�:</td>
					<td><?=$memoView['list'][0]['to_user_id']?></td>
					<?	}	?>
                    </tr>
					<tr>
					<td>��������: </td>
					<td><?=$memoView['list'][0]['content']?></td>
                    </tr>
					<tr>
					<td align=center colspan=2 height=30>
					<input type="button" onclick="history.back();" value="���"> | <?if($type=="receive"){?><input type="submit" value="����"> |<?}?> <input type="button" onclick="del('<?=$memoView['list'][0]['idx']?>','<?=$type?>');" value="����">
					</td>
                    </tr>
                    </form>
                  </table>
                </td>
              </tr>
              <tr>
                <td></td>
              </tr>
            </table>


          </td>
        </tr>
      </table>
<? include $_SERVER['DOCUMENT_ROOT'] . "/common/footer.php"; ?>