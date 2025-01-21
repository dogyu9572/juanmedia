<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
if(!$_SESSION[$_SITE["DOMAIN"]]["SESSIONID"]){
        $_SESSION[$_SITE["DOMAIN"]]["SESSIONID"] = md5(rand().microtime());//���θ� ���� ���� ���̵�
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html;charset=euc-kr">
<meta http-equiv=Cache-Control content=No-Cache>
<meta http-equiv=Pragma	content=No-Cache>
<meta name="naver-site-verification" content="60ee23e2c46088474b4140244d28fe38037e8bfa" />
<link href="/common/css/style.css" rel=stylesheet type="text/css">
<script src="/common/js/prototype-1.6.0.3-euc-kr.js" type="text/javascript"></script>
<script src="/common/js/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script src="/common/js/scriptaculous/effects.js" type="text/javascript"></script>
<script src="/common/js/calendar.js" type="text/javascript"></script>
<script src="/common/js/common.js" type="text/javascript"></script>
<script src="/common/js/shop.js" type="text/javascript"></script>
<script src="/common/js/layer.js" type="text/javascript"></script>

<title><?=$_SITE["NAME"]?></title>
</head>
<body>
<a name="top" id="top"></a>

<!-- ž�޴� -->
<table width="950" height="80" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="170"><a href="/"><img src="/common/images/logo.gif" width="170" height="80" border="0" /></a></td>
    <td width="780" align="right" valign="top" style="padding-top:10px">
	
    <?if($_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]){?>
    <!--�α�����-->
	<table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><a href="/main/"><img src="/common/images/t_home.gif" width="32" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/module/member/logout.php"><img src="/common/images/t_logout.gif" width="41" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=MyPage"><img src="/common/images/t_mypage.gif" width="48" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=Cart"><img src="/common/images/t_cart.gif" width="40" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=OrderList"><img src="/common/images/t_jumun.gif" width="58" height="13" border="0" /></a></td>
      </tr>
    </table>

	  <table border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td><strong><?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?></strong>�� �α������Դϴ�. <a href="/module/member/logout.php"><img src="/common/images/btn_logout_t.gif" /></a></td>
        </tr>
      </table>

    <?}else if($_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"]){?>
    <!--�մ� �α�����-->
	<table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><a href="/main/"><img src="/common/images/t_home.gif" width="32" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/member.php?goPage=Login"><img src="/common/images/t_login.gif" width="31" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/member.php?goPage=Agree"><img src="/common/images/t_join.gif" width="39" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=MyPage"><img src="/common/images/t_mypage.gif" width="48" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=Cart"><img src="/common/images/t_cart.gif" width="40" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=OrderList"><img src="/common/images/t_jumun.gif" width="58" height="13" border="0" /></a></td>
      </tr>
    </table>

	  <table border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td><strong><?=$_SESSION[$_SITE["DOMAIN"]]["GUEST"]["NAME"]?></strong>��(�մ�) �α������Դϴ�. <a href="/module/member/logout.php"><img src="/common/images/btn_logout_t.gif" /></a></td>
        </tr>
      </table>

	<?}else{?>
	<!--�α�����-->
	<script language="JavaScript">
	function checkLoginTop(f) { //�Է°� �˻�
		if (!f.id.value) {
			alert("���̵� �Է��ϼ���."); f.id.focus(); return false;
		}
		if (!f.pw.value) {
			alert("��й�ȣ�� �Է��ϼ���."); f.pw.focus(); return false;
		}
	}
	</script> 
	<table border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td><a href="/main/"><img src="/common/images/t_home.gif" width="32" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/member.php?goPage=Login"><img src="/common/images/t_login.gif" width="31" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/member.php?goPage=Agree"><img src="/common/images/t_join.gif" width="39" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=MyPage"><img src="/common/images/t_mypage.gif" width="48" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=Cart"><img src="/common/images/t_cart.gif" width="40" height="13" border="0" /></a><img src="/common/images/t_dv.gif" width="3" height="13" /><a href="/shop.php?goPage=OrderList"><img src="/common/images/t_jumun.gif" width="58" height="13" border="0" /></a></td>
      </tr>
    </table>

      <table border="0" cellspacing="0" cellpadding="5">
		<form action='/module/member/member_evn.php' method='post' name='loginForm' onsubmit="return checkLoginTop(this);" align=center>
		<input type="hidden" name="evnMode" value="login">
		<input type="hidden" name="rt_url" value="<?=$_SERVER[REQUEST_URI]?>">
        <tr>
          <td><img src="/common/images/login_left.gif" width="9" height="20" align="absmiddle" /><input name="id" type="text" class="loginbar" style="background:url(/common/images/t_id.gif) no-repeat 5px 7px #fff" onFocus="this.style.backgroundImage='';" title="���̵�" /><img src="/common/images/login_right.gif" width="9" height="20" align="absmiddle" />
            <img src="/common/images/login_left.gif" width="9" height="20" align="absmiddle" /><input name="pw" type="password" class="pwbar"  style="background:url(/common/images/t_pw.gif) no-repeat 5px 7px #fff" onFocus="this.style.backgroundImage='';" title="��й�ȣ"/><img src="/common/images/login_right.gif" width="9" height="20" align="absmiddle" />
          <input type="image" name="imageField" src="/common/images/btn_login_t.gif" /></td>
        </tr>
		</form>
      </table>    
	<?}?>
	</td>
  </tr>
</table>
<!-- ž�޴� -->

<!-- �˻��� -->
<script language="javascript">
function checkTopSearch(f){
	if(f.sk.value.length < 1){
		alert("�˻�� �Է��� �ּ���.");
		f.sk.focus();
		return false;
	}
}
</script>
<table width="950" height="45" border="0" align="center" cellpadding="0" cellspacing="0">
  <form name="frmTopSearch" method="get" action="/shop.php" onsubmit="return checkTopSearch(this);">
  <input type="hidden" name="goPage" value="Search">
  <tr>
    <td width="170" style="background:url(/common/images/search_bg.gif) repeat-x;">
	<div style="margin:8px;">
	<span class="ksnw"><?=date("Y�� m�� d��")?><br />
    <?=str_replace("am","����",str_replace("pm","����",date("a g�� i��")))?></span></div>
	</td>
    <td width="600" align="center" style="background:url(/common/images/search_bg.gif) repeat-x;">
	<select name="sw">
      <option>���հ˻�</option>
      <option value="author"<?=$_REQUEST[sw]=="author"?" selected":""?>>����</option>
      <option value="isbn"<?=$_REQUEST[sw]=="isbn"?" selected":""?>>ISBN</option>
      <option value="name"<?=$_REQUEST[sw]=="name"?" selected":""?>>å����</option>
    </select>
	
	<img src="/common/images/input_left.gif" align="absmiddle" /><input name="sk" type="text" class="sbar" style="width:360px; <?if(!$_REQUEST[sk]):?>background:url(/common/images/t_word.gif) no-repeat 5px 5px #fff<?endif;?>" onFocus="this.style.backgroundImage='';" title="�˻�� �Է��� �ּ���" value="<?=$_REQUEST[sk]?>"/><img src="/common/images/input_right.gif" align="absmiddle" />
    <input type="image" src="/common/images/btn_topsearch.gif" /></td>
    <td width="180" style="background:url(/common/images/search_bg.gif) repeat-x;"><img src="/common/images/startpage.gif" width="170" height="44" border="0" onClick="startPage(this,'http://<?=$_SITE["DOMAIN"]?>');" href="javascript:" style="cursor:hand"></td>
  </tr>
  </form>
</table>
<!-- �˻��� -->