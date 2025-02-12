<script language=javascript>
    function popupClose<?=$arrPopupList["list"][$i]['idx']?>(){
        if ( document.frm<?=$arrPopupList["list"][$i]['idx']?>.no_popup.checked ){
            setCookie("POPUP<?=$arrPopupList["list"][$i]['idx']?>", "done", 1);
        }
        popup<?=$arrPopupList["list"][$i]['idx']?>.style.display = 'none';
    }

    //이미지 클릭시 이동
    function go<?=$arrPopupList["list"][$i]['idx']?>(){
        <?if($arrPopupList["list"][$i]['p_target']=="O"):?>
        document.location.href='<?=$arrPopupList["list"][$i]['p_url']?>';
        <?else:?>
        obj = window.open('<?=$arrPopupList["list"][$i]['p_url']?>','','');
        <?endif;?>
        //self.close();
    }
</script>

<div class="popupLayerCs" id="popup<?=$arrPopupList["list"][$i]['idx']?>" style="z-index:100; position:absolute; left: <?=$arrPopupList["list"][$i]['pop_left']?>px; top: <?=$arrPopupList["list"][$i]['pop_top']?>px; background-color:#fff; border:1px solid black;">
    <table class="popupTableCs" border="0"  cellspacing="0" cellpadding="0" style="width:<?=$arrPopupList["list"][$i]['width']?>px;">
        <form name="frm<?=$arrPopupList["list"][$i]['idx']?>">
            <tr>
                <td valign="top">
                    <div class="pop_tit"><?=$arrPopupList['list'][$i]['subject']?></div>
                    <table border="0" width="<?=$arrPopupList["list"][$i]['width']?>px" height="<?=$arrPopupList["list"][$i]['height']?>" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <? if($arrPopupList["list"][$i]['p_type']=="IMG")://이미지타입일경우?>
                                    <? if($arrPopupList["list"][$i]['p_url']){?>
                                        <a href="javascript:go<?=$arrPopupList["list"][$i]['idx']?>();"><img src="/uploaded/popup/<?=stripslashes($arrPopupList["list"][$i]['p_image'])?>" border="0" style="width:<?=$arrPopupList["list"][$i]['width']?>px;"></a>
                                    <? }else{?>
                                        <img src="/uploaded/popup/<?=stripslashes($arrPopupList["list"][$i]['p_image'])?>" style="width:<?=$arrPopupList["list"][$i]['width']?>px;" border="0">
                                    <? }?>
                                <? else:?>
                                    <?=stripslashes($arrPopupList["list"][$i]['contents'])?>
                                <?endif;?>
                            </td>
                        </tr>
                    </table>

                    <div class="pop_btm">
                        <label class="time"><i class="fa-regular fa-clock"></i>24시간동안 다시 열람하지 않습니다.<input type="checkbox" name="no_popup" onclick="popupClose<?=$arrPopupList["list"][$i]['idx']?>();"></label>
                        <a href="javascript:popupClose<?=$arrPopupList["list"][$i]['idx']?>()">[닫기]</a>
                    </div>

                </td>
            </tr>
        </form>
    </table>
</div>
<!--	<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>	-->
<script language="javascript">
    <!--
    //$("#popup<?=$arrPopupList["list"][$i]['idx']?>").draggable();	// 오류로 주석처리
    if($(window).innerWidth()<750){
        $(".popupLayerCs").prop("style","z-index:1000; position:absolute; left:60px; top: 340px; background-color:#fff; border:1px solid black;");
        //$(".popupTableCs").prop("style","width:250px;");
        //$(".popupLayerCs").prop("style","z-index:1000; position:absolute; left:0; top: 0; background-color:#fff; border:1px solid black;");
        //$(".popupTableCs").prop("style","width:"+$(window).innerWidth()-10+"px;");
    }
    if ( getCookie( "POPUP<?=$arrPopupList["list"][$i]['idx']?>" ) == "done" ){
        popup<?=$arrPopupList["list"][$i]['idx']?>.style.display = 'none';
    }
    -->

</script>
<style>
    .pop_tit {font-size: 16px; color: #222; font-weight: 700; line-height: 30px; background: #f5f5f5; text-align: left; padding: 5px 10px;}
    .pop_btm {display: flex; justify-content: flex-end; gap:5px; padding: 10px; background: #f5f5f5;}
    .pop_btm > * {width: auto; max-width:none; height: 30px; line-height:28px; text-align: center; font-size: 15px; color: #999; padding: 0 10px; border-radius:5px; border: #ddd 1px solid; background: #fff;}
    .pop_btm > * input {display: none;}
    .pop_btm .time i {margin-right: 10px;}
    @media screen and (max-width:750px){
        .popupLayerCs {left:50% !important; transform:translateX(-50%);}
        .popupLayerCs table,
        .popupLayerCs img {width:auto !important; max-width: calc(100vw - 30px);}
        .pop_tit {font-size: 13px; line-height: 20px;}
        /* .pop_btm {flex-direction: column; align-items: flex-end;} */
        .pop_btm > * {font-size: 13px; padding: 0 5px;}
    }
</style>
