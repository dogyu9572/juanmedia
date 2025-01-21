
$(document).ready(function() {
	$("input[name='filedel[]']").on("click", function() {
		if(this.checked){
			deleteFile(this);
		}
	});
});
function deleteFile(file_obj){
	if(confirm("해당 파일을 삭제하시겠습니까?\n 삭제한 파일은 복구가 불가능합니다.")){
		$.post("/module/board/ajax_board_file_del.php",{boardid:document.form1.boardid.value,idx:document.form1.idx.value,file_idx:file_obj.value},function(result){
			if(result.success){
				alert("파일이 삭제되었습니다.");
				location.reload();
			}else{
				alert("파일 삭제에 실패했습니다.");
				location.reload();
			}
		},"json");
	}else{
		$(file_obj).prop("checked",false);
	}
}