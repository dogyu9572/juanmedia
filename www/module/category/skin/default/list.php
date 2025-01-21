<div class="container">
	<?php include_once "./menu.php"; ?>
    <div id="admin-content">
		<div class="title">
			<?=$arrCategoryInfo["list"][0]["categoryname"]?> 관리
		</div>

		<div class="inbox top_search">
			<form action="category_evn.php" method="post" name="categoryFrm">
			<input type="hidden" name="evnMode" value="createCategory">
			<input type="hidden" name="s_categoryid" value='<?=$arrCategoryInfo["list"][0]["categoryid"]?>'>
			<input type="hidden" name="s_cat_no" value='<?=$arrInfo["list"][0]["cat_no"]==""?0:$arrInfo["list"][0]["cat_no"]?>'>
			<input type="hidden" name="s_category" value='<?=$arrInfo["list"][0]["cat_code"]?>'>
			<input type="hidden" name="s_depth" value='<?=($arrInfo["list"][0]["cat_no"]?$arrInfo["list"][0]["cat_depth"]+1:$arrInfo["list"][0]["cat_depth"])?>'>
			<div class="total">&nbsp;<strong>
			<a href="<?=$_SERVER['PHP_SELF']?>?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>">ROOT</a>
			<?
			if($arrPath['total'] > 0){
				for($i=0; $i < $arrPath['total']; $i++){
			?>
			 > <a href="<?=$_SERVER['PHP_SELF']?>?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&cat_no=<?=$arrPath['list'][$i]['cat_no']?>"><?=$arrPath['list'][$i]['cat_name']?></a>
			<?
				}
			}
			?>
			</strong>
			</div>
			<div class="keyword">
				<input size="50" type="text" name="new_name" onBlur="FillField(this)" onFocus="ClearField(this)" value="새로운 카테고리" class="input" /> <input type="image" src="/backoffice/images/btn_add_cat.gif" alt='신규생성' />
			</div>
			</form>
		</div>
		<div class="inbox">
			<div class="over_tbl mo_break_tbl">
				<div class="bdr_list tac">
					<table>
					  <colgroup  class="pc_vw">
					  <col width="70" />
					  <col width="70" />
					  <col width="130" />
					  <col width="160" />
					  <col width="*" />
					  <col width="70" />
					  <col width="130" />
					  </colgroup>
					  <thead>		  
						<tr>
						  <th>No.</th>
						  <th>하위</th>
						  <th>하위보기</th>
						  <th>UP / DOWN</th>
						  <th>카테고리명</th>
						  <th>노출여부</th>
						  <th>관리</th>
						</tr>
					  </thead>
					  <tbody>
					<?
					if($arrList["total"]>0){
						for($i=0; $i<$arrList["total"]; $i++){		
					?>
						<tr>
							<td><?=$arrList["list"][$i]['cat_no']?></td>
							<td><?=number_format($arrList["list"][$i]['total_sub'])?></td>			
							<td><a href="<?=$_SERVER['PHP_SELF']?>?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&cat_no=<?=$arrList["list"][$i]['cat_no']?>"><img src="./images/btn_view.gif" border=0 alt="<?=$arrList["list"][$i]['cat_name']?>"></a></td>
							<td><a href="category_evn.php?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&evnMode=sort_up&cat_no=<?=$arrList["list"][$i]['cat_no']?>&s_cat_no=<?=$arrInfo["list"][0]["cat_no"]?>"><img src="/backoffice/images/k_up.gif" alt="UP" /></a> <a href="category_evn.php?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&evnMode=sort_down&cat_no=<?=$arrList["list"][$i]['cat_no']?>&s_cat_no=<?=$arrInfo["list"][0]["cat_no"]?>"><img src="/backoffice/images/k_down.gif" alt="DOWN" /></a></td>
							<td><?=stripslashes($arrList["list"][$i]['cat_name'])?></td>
							<td><?=stripslashes($arrList["list"][$i]['cat_is_show'])?></td>
							<td><a href="<?=$_SERVER['PHP_SELF']?>?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&mode=modify&cat_no=<?=$arrList["list"][$i]['cat_no']?>"><img src="/backoffice/images/k_modify.gif" alt="수정" /></a> 
							 <a href="category_evn.php?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&evnMode=deleteCategory&cat_no=<?=$arrList["list"][$i]['cat_no']?>&s_cat_no=<?=$arrInfo["list"][0]["cat_no"]?>" onclick="if(confirm('[<?=$arrList["list"][$i]['cat_no']?>] 번호 카테고리를 정말 삭제 하시겠습니까?\n\n하위 카테고리는 현재 <?=number_format($arrList["list"][$i]['total_sub'])?> 개 입니다.\n\n하위 카테고리도 모두 삭제되며 복구되지 않습니다.')){return true;}else{return false;}"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
						</tr>
					<?
						}
					}else{
						echo"
						<tr height=100>
							<td colspan=7>카테고리가 존재하지 않습니다.</td>
						</tr>
						";
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>