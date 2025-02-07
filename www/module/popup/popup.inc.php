<script language="javascript">
    function setCookie( name, value, expiredays ){
        var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }

    function getCookie( name ){
        var nameOfCookie = name + "=";
        var x = 0;
        while ( x <= document.cookie.length ) {
            var y = (x+nameOfCookie.length);
            if ( document.cookie.substring( x, y ) == nameOfCookie ) {
                if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 ) endOfCookie = document.cookie.length;
                return unescape( document.cookie.substring( y, endOfCookie ) );
            }
            x = document.cookie.indexOf( " ", x ) + 1;
            if ( x == 0 ) break;
        }
        return "";
    }
    //-->
</script>
<?
$mAgent = array("iPhone","iPod","Android","Blackberry",
    "Opera Mini", "Windows ce", "Nokia", "sony" );
$chkMobile = false;
for($i=0; $i<sizeof($mAgent); $i++){
    if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
        $chkMobile = true;
        break;
    }
}
include_once $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/module/popup/popup.lib.php";
//DB연결
$dblink = SetConn($_conf_db["main_db"]);
$arrPopupList = getActivePopup();
//DB해제
SetDisConn($dblink);
//팝업갯수만큼 창 띄움
if($arrPopupList["total"] > 0){
    for($i=0;$i<$arrPopupList["total"];$i++){
        if($arrPopupList["list"][$i]['p_mode'] == "P") {
            ?>
            <script language="javascript">
                if ( getCookie( "POPUP<?=$arrPopupList["list"][$i]['idx']?>" ) != "done" ){
                    obj = window.open('/module/popup/popup.php?idx=<?=$arrPopupList["list"][$i]['idx']?>','popup<?=$arrPopupList["list"][$i]['idx']?>','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=<?=$arrPopupList["list"][$i]['width']?>,height=<?=$arrPopupList["list"][$i]['height']?>,top=<?=$arrPopupList["list"][$i]['pop_top']?>,left=<?=$arrPopupList["list"][$i]['pop_left']?>');
                    obj.opener = self;
                }
                //-->
            </script>
            <?
        } else {
            if($chkMobile) {
                $arrPopupList["list"][$i]['pop_left'] = 60;
                $arrPopupList["list"][$i]['pop_top'] = 340;
                $arrPopupList["list"][$i]['width'] = 250;
                $arrPopupList["list"][$i]['height'] = "auto";
            }
            include $_SERVER['DOCUMENT_ROOT'] . "/module/popup/popup_layer.php";
        }
    }//end for
}//end if
?>
