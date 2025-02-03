<?
################################################### PHP 7 Set ST
if(!isset($_GET["category"])){	$_GET["category"]=""; }
if(!isset($_GET["sw"])){		$_GET['sw']="";	}
if(!isset($_GET["sk"])){		$_GET['sk']="";	}
if(!isset($_GET["offset"])){	$_GET['offset']="";	}
if(!isset($_GET["s_date"])){	$_GET['s_date']="";	}
if(!isset($_GET["e_date"])){	$_GET['e_date']="";	}
if(!isset($_GET["page_size"])){	$_GET['page_size']=""; }
if(!isset($arrBoardArticle["total_files"])){		$arrBoardArticle["total_files"]=0; }
if(!isset($arrBoardArticle["list"][0]['subject'])){ $arrBoardArticle["list"][0]['subject']=""; }
if(!isset($arrBoardArticle["list"][0]['etc_1'])){	$arrBoardArticle["list"][0]['etc_1']=""; }
if(!isset($arrBoardArticle["list"][0]['contents'])){	$arrBoardArticle["list"][0]['contents']=""; }
################################################### PHP 7 Set ED

if($_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"] && $_SERVER["PHP_SELF"]=="/backoffice/module/board/board_view.php"){
###################################################### 관리자 페이지 ######################################################?>
<?if($_GET['mode']=="write"){$inputText="등록";}else{$inputText="수정";}?>
<script src='https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js'></script>
<script type="text/javascript">
<!--
function execDaumPostcode(pr_zip, pr_Add1, pr_Add2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var addr = ''; // 주소 변수
			var extraAddr = ''; // 참고항목 변수

			//사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				addr = data.roadAddress;
			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				addr = data.jibunAddress;
			}
			// 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
			if(data.userSelectedType === 'R'){
				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
					extraAddr += data.bname;
				}
				// 건물명이 있고, 공동주택일 경우 추가한다.
				if(data.buildingName !== '' && data.apartment === 'Y'){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if(extraAddr !== ''){
					extraAddr = ' (' + extraAddr + ')';
				}
				// 조합된 참고항목을 해당 필드에 넣는다.
//				document.getElementById(pr_Add1).value = extraAddr;
			
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(pr_zip).value = data.zonecode;
			document.getElementById(pr_Add1).value = addr + " " + extraAddr;
			// 커서를 상세주소 필드로 이동한다.
			document.getElementById(pr_Add2).focus();
		}
	}).open();
}	
//-->
</script>
<script language="javascript">
function frmCheck(frm){
	/*
	if(frm.subject.value.length < 1){
		alert('제목을 입력해 주세요.');
		frm.subject.focus();
		return ;
	}
	*/
	
	try{ contents.outputBodyHTML(); } catch(e){ }

	frm.submit();

}
$(document).ready(function() {
	$.each($('input.calendar'), function() {
		set_datepicker($(this));
	});	
	// 숫자만 입력
	$(".numberOnly").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9]/g,""));
	});
	<?if($_REQUEST['mode']=="modify"){?>
	fnGoodPrint("image1idxs","<?=$arrBoardArticle["list"][0]["image1by"]?>");
	fnGoodPrint("image2idxs","<?=$arrBoardArticle["list"][0]["image2by"]?>");
	<?}?>
});
function set_datepicker($cont) {
	$cont.prop('readonly', true).datepicker({
		closeText: '닫기',
		prevText: '',
		nextText: '',
		currentText: '오늘',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)','7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		defaultDate: '+1w',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '년 ',
		changeMonth: true,
		changeYear: true,
		yearRange: '1921:c+5'
	});
}

//첨부파일 열 추가
var rowcount = 0;
function append() {   
	var tbl = document.getElementById("files_table").getElementsByTagName("TBODY")[0];  
	var html1 = "<input name='upfiles[]' type='file' style='width: 400px;'>";  
	var row = document.createElement("tr"); 
	var col1 = document.createElement("td");   
	row.appendChild(col1);  
	col1.innerHTML = html1;  
	tbl.appendChild(row);  
	rowcount++;
}
var filecount = 0;
function appendfile(){
	if(filecount<20){
		filecount++;
		$("#filetd"+filecount).css("display","");	
	}
}
function removefile(){
	$("#filetd"+filecount).css("display","none");	
	if(filecount>0){
		filecount--;
	}
}
function remove() {  
	if(rowcount > 0){
		var tbl = document.getElementById("files_table").getElementsByTagName("TBODY")[0];  
		if (tbl.hasChildNodes()) {      
			tbl.removeChild(tbl.lastChild);     // 마지막 로우   //tbl.removeChild(tbl.firstChild);  // 첫번째 로우  
		}
		rowcount--;
	}
}
function fnCat1(tval){	
	
	$.post("/module/shop/ajax_board_category.php", { snum : '2',cat_no: tval, eventMd : '' },
		function(data){
			if(data){
//				alert(data);
				$("#catTd").html(data);				
			}else{
				alert("실패.");
			}
		}
	);
}
//첨부파일 열 추가

function fnSave(){
	var frm = document.form1;
	frm.returnURL.value = '<?=$_SERVER['REQUEST_URI']?>';
	frm.altYN.value = 'Y';
	frm.submit();
}

function fnGoodSelect(stridx, inputName){			
	if(stridx){ $(".is-close-btn").click();	}
	var msds = $("input[name='"+inputName+"']").val();
	if(msds){
		$("input[name='"+inputName+"']").val(msds+","+stridx);
	}else{
		$("input[name='"+inputName+"']").val(stridx);
	}	
	fnGoodPrint(inputName);
}

function fnGoodPrint(inputName, orderby){
	var msds = $("input[name='"+inputName+"']").val();
	var listName = "#msdslist1";
	if(inputName=="image2idxs"){
		listName = "#msdslist2";
	}
	if(msds<1){
		$(listName).html('<tr><td colspan="4" style="text-align:center;padding:14px 0;">등록된 데이터가 없습니다.</td></tr>');				
	}else{
		$.post("/module/board/ajax_droneimagelist.php", { bid: 'droneimage', idx : msds, fname: inputName, orderby: orderby},
			function(data){
				if(data){
				//	alert(data);
					$(listName).html(data);				
				}else{
					alert("실패.");
				}
			}
		);
	}
}
function fnAddDel(stridx, inputName){
	var msds = $("input[name='"+inputName+"']").val();
	var arrMsds = msds.split(',');
	var reArrMsds = "";
	var comma = "";
	for(var i=0; i<arrMsds.length;i++){
		if(arrMsds[i]!=stridx){
			reArrMsds += comma+arrMsds[i]
			comma = ",";
		}
	}
	$("input[name='"+inputName+"']").val(reArrMsds);
	fnGoodPrint(inputName);
}
</script>	
<script language="javascript">
function fileDownload(boardid,b_idx,idx){
	obj = window.open("/module/board/download.php?boardid="+boardid+"&b_idx="+b_idx+"&idx="+idx,"download","width=100,height=100,menubars=0, toolbars=0");
}
</script>
<?######################################### iframe fancybox ######################################### ST?>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
<style type="text/css">
.fancybox__content { padding: 5px 0;border-radius: 4px; }
.fancybox__slide {padding-bottom:20px;}
</style>
<script type="text/javascript">
<!--
function OpenApplyView(fname)
{
	Fancybox.show([
	{
		src: "/backoffice/module/board/pop_board_view.php?boardid=droneimage&fname="+fname,
		type: "iframe",
		preload: false,
		width: 1100,
		height: 700
	},
	]);
}	
//-->
</script>
<?######################################### iframe fancybox ######################################### ED?>
<?
for($i=0;$i<$arrBoardArticle["total_files"];$i++){	
	if(substr($arrBoardArticle["files"][$i]['re_name'],0,2) == "l_") {
		$arrBoardArticle["list"][0]["file_l_name"] = $arrBoardArticle["files"][$i]['ori_name'];
		$arrBoardArticle["list"][0]["file_l_url"] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][$i]['re_name'];
		$arrBoardArticle["list"][0]["file_l_idx"] = $arrBoardArticle["files"][$i]['idx'];
	}
	if(substr($arrBoardArticle["files"][$i]['re_name'],0,3) == "t1_") {
		$arrBoardArticle["list"][0]["file_t1_name"] = $arrBoardArticle["files"][$i]['ori_name'];
		$arrBoardArticle["list"][0]["file_t1_url"] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][$i]['re_name'];
		$arrBoardArticle["list"][0]["file_t1_idx"] = $arrBoardArticle["files"][$i]['idx'];
	}	
	if(substr($arrBoardArticle["files"][$i]['re_name'],0,3) == "t2_") {
		$arrBoardArticle["list"][0]["file_t2_name"] = $arrBoardArticle["files"][$i]['ori_name'];
		$arrBoardArticle["list"][0]["file_t2_url"] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][$i]['re_name'];
		$arrBoardArticle["list"][0]["file_t2_idx"] = $arrBoardArticle["files"][$i]['idx'];
	}	
	if(substr($arrBoardArticle["files"][$i]['re_name'],0,3) == "t3_") {
		$arrBoardArticle["list"][0]["file_t3_name"] = $arrBoardArticle["files"][$i]['ori_name'];
		$arrBoardArticle["list"][0]["file_t3_url"] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][$i]['re_name'];
		$arrBoardArticle["list"][0]["file_t3_idx"] = $arrBoardArticle["files"][$i]['idx'];
	}
	if(substr($arrBoardArticle["files"][$i]['re_name'],0,3) == "t4_") {
		$arrBoardArticle["list"][0]["file_t4_name"] = $arrBoardArticle["files"][$i]['ori_name'];
		$arrBoardArticle["list"][0]["file_t4_url"] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][$i]['re_name'];
		$arrBoardArticle["list"][0]["file_t4_idx"] = $arrBoardArticle["files"][$i]['idx'];
	}
	if(substr($arrBoardArticle["files"][$i]['re_name'],0,3) == "t5_") {
		$arrBoardArticle["list"][0]["file_t5_name"] = $arrBoardArticle["files"][$i]['ori_name'];
		$arrBoardArticle["list"][0]["file_t5_url"] = "/uploaded/board/".$arrBoardInfo["list"][0]["boardid"]."/".$arrBoardArticle["files"][$i]['re_name'];
		$arrBoardArticle["list"][0]["file_t5_idx"] = $arrBoardArticle["files"][$i]['idx'];
	}
}
$arrCategory01 = getCategoryList("114","Y");	// 분류
?>
<script type="text/javascript">
<!--
function fnEtc5Val(ths)	{
	var frm = document.form1;
	if($(ths).is(':checked')){
		frm.etc_4.value = "<?=date("Y-m-d")?>";
	}else{
		frm.etc_4.value = "<?=stripslashes($arrBoardArticle["list"][0]['etc_4'])?>";	
	}
}
//-->
</script>
<div class="container">

	<div class="title"><?=$arrBoardInfo["list"][0]["boardname"]?> <?=$inputText?></div>
	
	<div class="inbox write_tbl mo_break_write">
		
		<form name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
		<input type="hidden" name="boardid" value="<?=$arrBoardInfo["list"][0]["boardid"]?>">
		<input type="hidden" name="altYN" value="N">
		<input type="hidden" name="returnURL" value="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&category=<?=$_GET['category']?>&offset=<?=$_GET['offset']??""?>">
		<input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
		<input type="hidden" name="usehtml" value="Y">
		<?if($_REQUEST['mode']=="reply"):?>
		<input type="hidden" name="evnMode" value="reply">
		<?elseif($_REQUEST['mode']=="modify"):?>
		<input type="hidden" name="evnMode" value="modify">
		<?else:?>
		<input type="hidden" name="evnMode" value="write">
		<?endif;?>
		<input type="hidden" name="image1idxs" value="<?=$arrBoardArticle["list"][0]["image1idxs"]?>">
		<input type="hidden" name="image2idxs" value="<?=$arrBoardArticle["list"][0]["image2idxs"]?>">

		
		<input type="hidden" name="name" value="<?=stripslashes($arrBoardArticle["list"][0]['name'])?>">
		<input type="hidden" name="tel" value="<?=stripslashes($arrBoardArticle["list"][0]['tel'])?>">
		<input type="hidden" name="email" value="<?=stripslashes($arrBoardArticle["list"][0]['email'])?>">
		<input type="hidden" name="w_user" value="<?=stripslashes($arrBoardArticle["list"][0]['w_user'])?>">
		<input type="hidden" name="wdate" value="<?=stripslashes($arrBoardArticle["list"][0]['wdate'])?>">
		<input type="hidden" name="etc_4" value="<?=stripslashes($arrBoardArticle["list"][0]['etc_4'])?>">

		<div class="tit"><?=$arrBoardInfo["list"][0]["boardname"]?> 정보 <i>*</i></div>
		<table>
			<tr style="display:none;">
				<th>순서</th>
				<td><div class="inputs"><input type="text" class="w2" style="text-align:right;" name="b_sort" maxlength="100" value="<?=$arrBoardArticle["list"][0]['b_sort']?$arrBoardArticle["list"][0]['b_sort']:"0"?>"></div></td>
			</tr>	
			<tr>
				<th>이름(ID)</th>
				<td><?=stripslashes($arrBoardArticle["list"][0]['name'])?>(<?=stripslashes($arrBoardArticle["list"][0]['w_user'])?>)</td>
			</tr>
			<tr>
				<th>연락처</th>
				<td><?=stripslashes($arrBoardArticle["list"][0]['tel'])?></td>
			</tr>
			<tr>
				<th>이메일</th>
				<td><?=stripslashes($arrBoardArticle["list"][0]['email'])?></td>
			</tr>
			<tr>
				<th>제목</th>
				<td><?=stripslashes($arrBoardArticle["list"][0]['subject'])?></td>
			</tr>
			<tr>
				<th>문의내용</th>
				<td><?=nl2br($arrBoardArticle["list"][0]['contents'])?></td>
			</tr>
			<tr>
				<th>문의일</th>
				<td><?=nl2br($arrBoardArticle["list"][0]['wdate'])?></td>
			</tr>
			<tr style="display:none;">
				<th>첨부파일 <em>&nbsp;&nbsp;&nbsp;<a href="javascript:appendfile();"><img src="/common/images/btnPlus3.png" alt="파일추가" style="width:20px;"></a>
				<a href="javascript:removefile();"><img src="/common/images/btnMin3.png" alt="파일삭제" style="width:20px;"></a></em></th>
				<td>
					<table id="files_table" border="0" cellpadding="0" cellspacing="0" width="500">
						<tbody>
							<tr height="25">
								<td align='left' width='100%' style="border: 0px solid #aaa;text-align:left;">
									<div class="inputs">
										<div class="filebutton">
											<span>파일 선택</span>							
											<input name="upfiles[]" type="file" class="searchfile" title="파일 찾기">					
										</div>
										<div class="filebox">선택된 파일 없음</div>
										
										
									</div>
									
								</td>
							</tr>
							<?for($i=1;$i<21;$i++){?>
							<tr>
								<td align='left' width='100%' id="filetd<?=$i?>" style="display:none;">
									<div class="inputs">
										<div class="filebutton">
											<span>파일 선택</span>							
											<input name="upfiles[]" type="file" class="searchfile" title="파일 찾기">					
										</div>
										<div class="filebox">선택된 파일 없음</div>
									</div>
								</td>
							</tr>
							<?}?>
						</tbody>
					</table>
					<?
					if($arrBoardArticle["total_files"]>0 && $_REQUEST['mode']=="modify"){
					?>
					<table id="files_list" border="0" cellpadding="3" cellspacing="1" width="100%" style="padding:1%">
						<tbody>
						<?
						for($i=0;$i<$arrBoardArticle["total_files"];$i++){
							if(substr($arrBoardArticle["files"][$i]['re_name'],0,2) != "l_" && substr($arrBoardArticle["files"][$i]['re_name'],0,2) != "v_") {
						?>
							<tr> 
								<td><label class="check"><input type="checkbox" name="filedel[]" value="<?=$arrBoardArticle["files"][$i]['idx']?>"><i></i>삭제</label>
								file :  <a href="javascript:void(0);" onclick="fileDownload('<?=$arrBoardArticle["files"][$i]['boardid']?>','<?=$arrBoardArticle["files"][$i]['b_idx']?>','<?=$arrBoardArticle["files"][$i]['idx']?>');"><?=$arrBoardArticle["files"][$i]['ori_name']?></a>
								</td>
							</tr>
						<?
							}
						}?>
						</tbody>
					</table>
					<?}?>
				</td>
			</tr>
			<tr>
				<th>답변상태</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="etc_1" value="N" <?=$arrBoardArticle["list"][0]['etc_1']=="Y"?"":"checked"?>><i></i>답변대기</label>
					<label class="radio"><input type="radio" name="etc_1" value="Y" <?=$arrBoardArticle["list"][0]['etc_1']=="Y"?"checked":""?>><i></i>답변완료</label> 
					<em></em>
					
				</div></td>
			</tr>
			<tr>
				<th>답변일</th>
				<td><input type="text" class="w2 datepicker" name="schedule_date" value="<?=$arrBoardArticle["list"][0]['schedule_date']?>" maxlength="10" /></td>
			</tr>
			<tr style="display:none;">
				<th>답변담당자</th>
				<td><div class="inputs"><input type="text" class="w4" name="homepage" maxlength="100" value="<?=$arrBoardArticle["list"][0]['homepage']?$arrBoardArticle["list"][0]['homepage']:$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["NAME"]?>"></div></td>
			</tr>
			<tr>
				<th>답변</th>
				<td>
					<textarea id="etc_5" name="etc_5" style="width:600px;height:100px;padding:10px;"><?=stripslashes($arrBoardArticle["list"][0]['etc_5'])?></textarea>
				</td>
			</tr>
			<tr>
				<th>답변 메일 발송</th>
				<td>
					<label class="check"><input type="checkbox" name="sms_qna" value="Y" onclick="fnEtc5Val(this)"><i></i>발송
					<?=$arrBoardArticle["list"][0]['etc_4']?"(".$arrBoardArticle["list"][0]['etc_4'].")":""?></label>
				</td>
			</tr>	


			<tr style="display:none;">
				<th>제목</th>
				<td><div class="inputs"><input type="text" class="w4" name="subject" maxlength="100" value="<?=stripslashes($arrBoardArticle["list"][0]['subject'])?>"></div></td>
			</tr>
			<tr style="display:none;">
				<th>공지</th>
				<td><div class="inputs">
					<label class="radio"><input type="radio" name="is_notice" value="" <?if($arrBoardArticle["list"][0]['no']!="0"){echo " checked";}?>><i></i>일반</label>
					<label class="radio"><input type="radio" name="is_notice" value="Y" <?if($arrBoardArticle["list"][0]['no']=="0"){echo " checked";}?>><i></i>공지</label> 
					<em></em>
				</div></td>
			</tr>
			
			<tr style="display:none;">
				<th>내용</th>
				<td>
					<textarea id="contents" name="contents"><?=stripslashes($arrBoardArticle["list"][0]['contents'])?></textarea>
					<?
					$CKContent = "contents";
					include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
					?>
				</td>
			</tr>		
			<tr style="display:none;">
				<th>썸네일</th>
				<td>
					<div class="inputs">
					<?if($arrBoardArticle["list"][0]["file_l_idx"]){?>
					<img src="<?=$arrBoardArticle["list"][0]["file_l_url"]?>" style="padding-right:10px;max-height:200px;max-width:200px;">
					<label class="check"><input type="checkbox" name="filedel[]" value="<?=$arrBoardArticle["list"][0]["file_l_idx"]?>"><i></i>삭제</label>
					<em>file : <a href="<?=$arrBoardArticle["list"][0]["file_l_url"]?>" download="<?=$arrBoardArticle["list"][0]["file_l_name"]?>"><?=$arrBoardArticle["list"][0]["file_l_name"]?></a></em>
					<?}else{?>
						<div class="filebutton">
							<span>파일 선택</span>							
							<input name="upfiles[]" type="file" class="searchfile" title="파일 찾기"><input type="hidden" name="memo_name[]" value="l">							
						</div>
						<div class="filebox">선택된 파일 없음</div>
					<?}?>
					</div>
				</td>
			</tr>			
			
			<tr style="display:none;">
				<th>조회수</th>
				<td><div class="inputs"><input type="text" class="w2" name="hit" maxlength="100" value="<?=$arrBoardArticle["list"][0]['hit']??0?>"></div></td>
			</tr>
						
		</table>		

		<div class="btns">
			<a href="<?=$_SERVER["PHP_SELF"]?>?boardid=<?=$arrBoardInfo["list"][0]["boardid"]?>&mode=list&category=<?=$_GET['category']?>" class="btn btn_list">목록보기</a>
			<a href="javascript:void(0)" onclick="location.reload()" class="btn btn_cancel">취소</a>
			<button class="btn btn_save" type="button" onclick="frmCheck(document.form1)">저장</button>
		</div>
		</form>
	</div> <!-- //inbox -->
</div>
<script type="text/javascript">
//<![CDATA[
$(window).load(function(){
//달력
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd',
		showMonthAfterYear:true,
		showOn: "both",
		buttonImage: "/images/icon_month.gif", 
        buttonImageOnly: true,
		changeYear: true,
		changeMonth: true,
		yearRange: 'c-100:c+10',
		yearSuffix: "년 ",
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
		dayNamesMin: ['일','월','화','수','목','금','토']
	});
//파일선택
	$(".searchfile").on('change',function(){
		val = $(this).val().split("\\");
		f_name = val[val.length-1]; 
		s_name = f_name.substring(f_name.length-4, f_name.length);
		$(this).parent().siblings('.filebox').html(f_name);
	});
});
//]]>
</script>
<?}else{###################################################### 사용자 페이지 ######################################################?>
<?
//관리자만 글쓰기 기능 체크
if($arrBoardInfo["list"][0]["useadminonly"] !="Y" || $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["ID"]):
	if($_REQUEST['mode']=="reply" && $arrBoardInfo["list"][0]["usereply"] !="Y"):
		jsMsg("답글쓰기가 제한된 게시판 입니다.");
		jsHistory("-1");
		exit;
	endif;
?>
<script type="text/javascript">
<!--
function frmCheck(frm){	
	if(frm.subject.value.length < 1){
		alert('제목을 입력해 주세요.');
		frm.subject.focus();
		return ;
	}
	
	try{ contents.outputBodyHTML(); } catch(e){ }

	frm.submit();

}	
//-->
</script>
<div class="inner">
	<form name="form1" method="post" action="/module/board/board_evn.php" ENCTYPE="multipart/form-data">
		<input type="hidden" name="boardid" value="<?=$arrBoardInfo["list"][0]["boardid"]?>">
		<input type="hidden" name="altYN" value="N">
		<input type="hidden" name="returnURL" value="/mypage/mypage_01.php">
		<input type="hidden" name="idx" value="<?=$arrBoardArticle["list"][0]["idx"]?>">
		<input type="hidden" name="usehtml" value="Y">
		<?if($_REQUEST['mode']=="reply"):?>
		<input type="hidden" name="evnMode" value="reply">
		<?elseif($_REQUEST['mode']=="modify"):?>
		<input type="hidden" name="evnMode" value="modify">
		<?else:?>
		<input type="hidden" name="evnMode" value="write">
		<?endif;?>
		<input type="hidden" name="w_user" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["ID"]?>">
		<input type="hidden" name="name" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["NAME"]?>">
		<input type="hidden" name="tel" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["TEL"]?>">
		<input type="hidden" name="email" value="<?=$_SESSION[$_SITE["DOMAIN"]]["MEMBER"]["EMAIL"]?>">
		<h3 class="heading3">Q&A</h3>
		<div class="form-wrap">
			<dl class="row">
				<dt class="label">제목</dt>
				<dd class="value">
					<input type="text" name="subject" maxlength="100" value="<?=stripslashes($arrBoardArticle["list"][0]['subject'])?>" placeholder="제목을 입력해주세요.">
				</dd>
			</dl>
			<dl class="row">
				<dt class="label">내용</dt>
				<dd class="value">				
					<textarea id="contents" name="contents"><?=stripslashes($arrBoardArticle["list"][0]['contents'])?></textarea>
					<?
					$CKContent = "contents";
					include $_SERVER['DOCUMENT_ROOT'] . "/ckeditor/Editor.php";
					?>				
				</dd>
			</dl>
			<dl class="row">
				<dt class="label">첨부파일</dt>
				<dd class="value">
					<input name="upfiles[]" type="file" class="searchfile" title="파일 찾기">				
				</dd>
			</dl>
		</div>
		<div class="btn-wrap">
			<button type="button" class="btn primary" onclick="frmCheck(document.form1)">문의하기</button>
		</div>
	</form>
</div>
<?
else:
jsMsg("관리자만 등록/수정/삭제 할 수 있는 게시판 입니다.");
jsHistory("-1");
endif;
?>
<?}?>