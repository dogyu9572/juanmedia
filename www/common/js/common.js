// 필드 미리 글써놓기
function ClearField(field){
	if (field.value == field.defaultValue) {
		field.value = "";
	}
}

function FillField(field){
	if (!field.value) {
		field.value = field.defaultValue;
	}
}
// 필드 미리 글써놓기

function open_window(name, url, left, top, width, height, toolbar, menubar, statusbar, scrollbar, resizable){
  toolbar_str = toolbar ? 'yes' : 'no';
  menubar_str = menubar ? 'yes' : 'no';
  statusbar_str = statusbar ? 'yes' : 'no';
  scrollbar_str = scrollbar ? 'yes' : 'no';
  resizable_str = resizable ? 'yes' : 'no';
  window.open(url, name, 'left='+left+',top='+top+',width='+width+',height='+height+',toolbar='+toolbar_str+',menubar='+menubar_str+',status='+statusbar_str+',scrollbars='+scrollbar_str+',resizable='+resizable_str);
}


// 한글 입력 검색
function hangul_chk(word) {
	var str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890_-";
	
	for (i=0; i< word.length; i++)
	{
		idcheck = word.charAt(i);
		
		for ( j = 0 ;  j < str.length ; j++){
		
			if (idcheck == str.charAt(j)) break;
				
     			if (j+1 == str.length){
   				return false;
     			}
     		}
     	}
     	return true;
}

function noSpChar(str) 
    { 
        str=str.toUpperCase(); 
        checking=1 
        for (i=0;i<str.length;i++) 
        { 
            if((str.charCodeAt(i)<65 && (str.charCodeAt(i)!=64) && (str.charCodeAt(i)!=46) && !(str.charCodeAt(i)>=48 && str.charCodeAt(i)<=57)) || str.charCodeAt(i)>90 || (str.charAt(0) >= "0" && str.charAt(0) <= "9" )) 
            { 
                checking=0 
            } 
        }
        return (checking); 
    } 

// 이메일 입력 검색
function email_chk(word) {
	var str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890-._@";
	
	for (i=0; i< word.length; i++)
	{
		idcheck = word.charAt(i);
		
		for ( j = 0 ;  j < str.length ; j++){
		
			if (idcheck == str.charAt(j)) break;
				
     			if (j+1 == str.length){
   				return false;
     			}
     		}
     	}
     	return true;
}

function addComma (str){
	var input_str = String(str);

	if (input_str == '') return false;
	input_str = parseInt(input_str.replace(/[^0-9]/g, '')).toString();
	if (isNaN(input_str)) { return false; }

	var sliceChar = ',';
	var step = 3;
	var step_increment = -1;
	var tmp  = '';
	var retval = '';
	var str_len = input_str.length;

	for (var i=str_len; i>=0; i--)	{
		tmp = input_str.charAt(i);
		if (tmp == sliceChar) continue;
		if (step_increment%step == 0 && step_increment != 0) retval = tmp + sliceChar + retval;
		else retval = tmp + retval;
		step_increment++;
	}

	return retval;
}

function zipSearch(tp){
	obj = window.open('/module/zipcode/zipcode.php?tp='+tp,'주소찾기','width=463, height=600, scrollbars=1');
}

function startPage(Obj,urlStr){
    if (document.all && window.external){
        Obj.style.behavior='url(#default#homepage)';
        Obj.setHomePage(urlStr);
    } else {
        
    }
}

//배너체크
function go_banner(idx, url, tar){
	if(idx !=""){
		$.get("/module/banner/ajax_count_banner.php", {idx: idx},
		function(data){
			if(data=="1"){
					go_banner_page(idx, url, tar);
			}
		});
	}
}

function go_banner_page(idx,url,tar){
	if(tar=="_blank"){
		obj = window.open(url,idx,"");
	}else{
		window.location.href = url;
	}
}
//일반회원가입시 수취인
function memberShipInfoAssign(st){
	f = document.memberForm;
	if(st==true){
		f.etc_2.value = f.user_name.value;
		f.tel_1.value = f.phone_1.value;
		f.tel_2.value = f.phone_2.value;
		f.tel_3.value = f.phone_3.value;
	}else{
		f.etc_2.value = "";
		f.tel_1.value = "";
		f.tel_2.value = "";
		f.tel_3.value = "";
	}
}

//법인회원가입시 
function comp1ShipInfoAssign(st){
	f = document.memberForm;
	if(st==true){
		f.etc_51.value = f.user_name.value;
	}else{
		f.etc_51.value = "";
	}
}
function comp2ShipInfoAssign(st){
	f = document.memberForm;
	if(st==true){
		f.etc_52.value = f.mobile_1.value;
		f.etc_53.value = f.mobile_2.value;
		f.etc_54.value = f.mobile_3.value;
	}else{
		f.etc_52.value = "";
		f.etc_53.value = "";
		f.etc_54.value = "";
	}
}
function comp3ShipInfoAssign(st){
	f = document.memberForm;
	if(st==true){
		f.etc_55.value = f.email_id.value;
		f.etc_56.value = f.email_domain.value;
	}else{
		f.etc_55.value = "";
		f.etc_56.value = "";
	}
}
function comp4ShipInfoAssign(st){
	f = document.memberForm;
	if(st==true){
		f.etc_2.value = f.user_name.value;
		f.tel_1.value = f.phone_1.value;
		f.tel_2.value = f.phone_2.value;
		f.tel_3.value = f.phone_3.value;
		f.zip1.value = f.com_zip1.value;
		f.zip2.value = f.com_zip2.value;
		f.address.value = f.com_address.value;
		f.address_ext.value = f.com_address_ext.value;
	}else{
		f.etc_2.value = "";
		f.tel_1.value = "";
		f.tel_2.value = "";
		f.tel_3.value = "";
		f.zip1.value = "";
		f.zip2.value = "";
		f.address.value = "";
		f.address_ext.value = "";
	}
}

