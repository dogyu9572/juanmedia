<?
//DB연결
$dblink = SetConn($_conf_db["main_db"]);


$scale = "25";

//상품 리스트
$arrList = getGoodListMain("",  $scale, $_REQUEST['offset'], "movie");

SetDisConn($dblink);
?>

<? include $_SERVER['DOCUMENT_ROOT'] . "/include/left.php"; ?>
    <div id="content">
		<div id="subTitle">
			<h3>SALE 상품</h3>
			<span class="location"> HOME &gt; SALE 상품</span>
		</div>

		<div class="productList">
			<ul>
				<?if($arrList['list']['total'] > 0):?>
				<?for ($i=0;$i<$arrList['list']['total'];$i++) {?>
				<li<?=$i%5==0?" class='none'":""?>><a href="<?=$_SERVER['PHP_SELF']?>?goPage=GoodDetail&g_code=<?=$arrList['list'][$i]['g_code']?>"><span><img src="/uploaded/shop_good/<?=$arrList['list'][$i]['idx']?>/<?=$arrList['list'][$i]['image_m']?>" width="127" alt="" /></span><p><?=stripslashes($arrList['list'][$i]['g_name'])?><br /><em><?=number_format($arrList['list'][$i]['price'])?>원</em></p></a></li>
				<?} else: ?>
				<li>등록된 제품이 없습니다.</li>
				<?endif;?>
			</ul>
        </div>

	<div class="paginate">  
	<?=pageNavigation($arrList['total'],$scale,$pagescale,$offset,"cat_no=".$catno."&sw=".$_REQUEST['sw']."&sk=".$_REQUEST['sk']."&st=".$_REQUEST['st']."&goPage=".$_REQUEST['goPage'])?>
	</div>
</div>