<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>js excel</title>
<style type="text/css">
	body{margin:0;}
</style>
<script src="./js/jquery-3.4.1.min.js"></script>
<script src="./js/xlsx.full.min.js"></script>
<script>
let total = 0;
let complete = 0;
function excelExport(event){	
	excelExportCommon(event, handleExcelDataAll);
}
function excelExportCommon(event, callback){
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function(){
        var fileData = reader.result;
        var wb = XLSX.read(fileData, {type : 'binary'});
        var sheetNameList = wb.SheetNames; // 시트 이름 목록 가져오기 
        var firstSheetName = sheetNameList[0]; // 첫번째 시트명
        var firstSheet = wb.Sheets[firstSheetName]; // 첫번째 시트 
        callback(firstSheet);      
    };
    reader.readAsBinaryString(input.files[0]);
}
function handleExcelDataAll(sheet){
	//	handleExcelDataHeader(sheet); // header 정보 
	handleExcelDataJson(sheet); // json 형태
	//	handleExcelDataCsv(sheet); // csv 형태
	//	handleExcelDataHtml(sheet); // html 형태
}
function handleExcelDataHeader(sheet){
    var headers = get_header_row(sheet);
    $("#displayHeaders").html(JSON.stringify(headers));
}
async function handleExcelDataJson(sheet){
    $("#displayExcelJson").html(JSON.stringify(XLSX.utils.sheet_to_json (sheet)));
	var formData = JSON.stringify(XLSX.utils.sheet_to_json (sheet));
	let data = JSON.parse(formData);
	
	total = data.length;
	if(confirm("선택하신 엑셀을 등록합니다. 계속 하시겠습니까?")) {
		$("#excelFile").hide();

		for(let i=0;i<data.length;i++){
			
			var formdata_once = JSON.stringify(data[i]);
			var return_data = await activePost(formdata_once);
			console.log(return_data);
			if(return_data != "OK"){
				if(!confirm("프로세스 진행중 오류가 발생했습니다. 계속진행 하시겠습니까?")){
					break;
				}
			}
		}
	}
}

async function activePost(formdata_once){
	try {
		const result = await $.post("./accept_excel_to_db.php", { chatlist: formdata_once });
		complete++;
		printComplete();
		return result;
	} catch (error) {
		console.error("Error in activePost:", error);
		return null; // You may want to handle the error more gracefully
	}
	
	
}

function printComplete(){
	let html = "";
	html += '<div style="height: 20px;width:90%;border:1px solid grey;border-radius: 8px;">';
	html += '<div style="height: 20px;width:'+((complete/total) *100)+'%;background: #3df956;border-radius: 8px;"></div>';
	html += '<div style="height: 20px;width:100%;position:absolute;top: 0;">'+complete+'/'+total+'</div>';
	html += '</div>';
	$("#process_count").html(html);
	if(complete == total){
		location.reload();
	}
}

function handleExcelDataCsv(sheet){
    $("#displayExcelCsv").html(XLSX.utils.sheet_to_csv (sheet));
}
function handleExcelDataHtml(sheet){
    $("#displayExcelHtml").html(XLSX.utils.sheet_to_html (sheet));
}
// 출처 : https://github.com/SheetJS/js-xlsx/issues/214
function get_header_row(sheet) {
    var headers = [];
    var range = XLSX.utils.decode_range(sheet['!ref']);
    var C, R = range.s.r; /* start in the first row */
    /* walk every column in the range */
    for(C = range.s.c; C <= range.e.c; ++C) {
        var cell = sheet[XLSX.utils.encode_cell({c:C, r:R})] /* find the cell in the first row */

        var hdr = "UNKNOWN " + C; // <-- replace with your desired default 
        if(cell && cell.t) hdr = XLSX.utils.format_cell(cell);

        headers.push(hdr);
    }
    return headers;
}
</script>
</head>
<body>
<div id="process_count">
</div>
<input type="file" id="excelFile" onchange="excelExport(event)"/>
<!-- <h1>Header 정보 보기</h1>
<div id="displayHeaders"></div>
<h1>CSV 형태로 보기</h1>
<div id="displayExcelCsv"></div>
<h1>HTML 형태로 보기</h1>
<div id="displayExcelHtml"></div>
<h1>JSON 형태로 보기</h1>-->
<div id="displayExcelJson" style="display:none;"></div>
</body>
</html>