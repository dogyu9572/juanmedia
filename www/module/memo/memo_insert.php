<?php include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/header.php"); 
include_once ($_SERVER['DOCUMENT_ROOT'] . "/module/memo/memo.lib.php"); 
log_session($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]);
?>
<script language="JavaScript">
function checkit2(f) {
	//�Է°� �˻�
	if (f.user_id.value <1) {
		alert("�������̵� �Է��ϼ���.");
		f.user_id.focus();
		return false;
	}
	if (f.contents.value <1) {
		alert("���������� �Է��ϼ���.");
		f.contents.focus();
		return false;
	}
}
</script>

      <table width="730" border="0" cellspacing="0" cellpadding="0">
			<tr><td><a href="memo_list.php?type=receive">����������</a> / <a href="memo_list.php?type=send">����������</a> / <a href="memo_savelist.php?type=send">����������</a> / <a href="memo_insert.php">��������</a></td></tr>
        <tr>
          <td>

            <table width="610" border="0" cellpadding="0" cellspacing="0" background="img/login_box_bg.gif">
              <tr>
                <td align="center" style="padding:10px 0 10px 0;">
                  <table width="550" border="0" cellspacing="0" cellpadding="0">
                    <form action='/module/memo/memo_evn.php' method='post' name='insertform' onsubmit="return checkit2(this);" align=center>
                    <input type="hidden" name="evnMode" value="insert">
                    <input type="hidden" name="rt_url" value="<?=$_REQUEST[rt_url]?>">
					<tr>
					<td>������ ���̵�:</td>
					<td>&nbsp;<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?></td>
					</tr>
					<tr>
					<td width=150>�޴¾��̵�:</td>
					<td><input type="text" name="to_id"></td>
                    </tr>
					<tr>
					<td>��������: </td>
					<td><textarea cols=60 rows=20 name=contents></textarea></td>
                    </tr>
					<tr>
					<td align=center colspan=2 height=30><input type="submit" value="SEND"></td>
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