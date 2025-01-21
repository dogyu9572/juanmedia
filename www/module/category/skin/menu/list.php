<div class="container">
	<?php include_once "./menu.php"; ?>
    <div id="admin-content">
		<div class="title">
			<?=$arrCategoryInfo["list"][0]["categoryname"]?> 관리
		</div>

		<div class="inbox top_search">
			<form name="form1" method="get" action="<?=$_SERVER["PHP_SELF"]?>">
				<input type="hidden" name="categoryid" value="<?=$arrCategoryInfo["list"][0]["categoryid"]?>">
				<input type="hidden" name="sw" value="s">
				<dl>
					<dt>사용여부</dt>
					<dd>
						<label class="radio"><input type="radio" name="is_show" value="" <?=$_GET["is_show"] == ""?"checked":""?>><i></i>전체</label>
						<label class="radio"><input type="radio" name="is_show" value="Y" <?=$_GET["is_show"] == "Y"?"checked":""?>><i></i>Y</label>
						<label class="radio"><input type="radio" name="is_show" value="N" <?=$_GET["is_show"] == "N"?"checked":""?>><i></i>N</label>
					</dd>
				</dl>
				<dl class="search_wrap">
					<dt>메뉴명</dt>
					<dd>
						<input type="text" name="sk" value="<?=$_GET['sk']?>" />
						<button type="button" class="search" onclick="document.form1.submit()">검색</button>
					</dd>
				</dl>
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
						  <th>메뉴명</th>
						  <th>사용여부</th>
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
							 <a href="category_evn.php?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&evnMode=deleteCategory&cat_no=<?=$arrList["list"][$i]['cat_no']?>&s_cat_no=<?=$arrInfo["list"][0]["cat_no"]?>" onclick="if(confirm('[<?=$arrList["list"][$i]['cat_no']?>] 번호 부설연구소를 정말 삭제 하시겠습니까? 해당 작업은 복구되지 않습니다.')){return true;}else{return false;}"><img src="/backoffice/images/k_delete.gif" alt="삭제" /></a></td>
						</tr>
					<?
						}
					}else{
						echo"
						<tr height=100>
							<td colspan=7>메뉴가 존재하지 않습니다.</td>
						</tr>
						";
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="bdr_btm">
			<div class="btns">
				<?php if($arrInfo["list"][0]["cat_parent_no"] != ""){?>
				<a href="<?=$_SERVER["PHP_SELF"]?>?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&cat_no=<?=$arrInfo["list"][0]["cat_parent_no"]?>" class="btn">상위이동</a>
				<?php } ?>
				<a href="<?=$_SERVER["PHP_SELF"]?>?categoryid=<?=$arrCategoryInfo["list"][0]["categoryid"]?>&mode=write&cat_parent_no=<?=$_GET["cat_no"]?>" class="btn">신규등록</a>
			</div>
		</div>
	</div>
</div>