var PSKIN_PATH = '';

function timeToMinSec(timeVal){
	time = Math.round(timeVal);
	min = parseInt((time%3600)/60);
	sec = time%60;
	return (str_pad(min, 2, 0, 'STR_PAD_LEFT')+""+str_pad(sec, 2, 0, 'STR_PAD_LEFT'));
}

function str_pad(input, pad_length, pad_string, pad_type){
	var half = '',
	pad_to_go;

	var str_pad_repeater = function (s, len){
		var collect = '',
		i;

		while (collect.length < len) {
			collect += s;
		}
		collect = collect.substr(0, len);

		return collect;
	};

	input += '';
	pad_string = pad_string !== undefined ? pad_string : ' ';

	if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') {
		pad_type = 'STR_PAD_RIGHT';
	}
	if ((pad_to_go = pad_length - input.length) > 0) {
		if (pad_type == 'STR_PAD_LEFT') {
			input = str_pad_repeater(pad_string, pad_to_go) + input;
		} else if (pad_type == 'STR_PAD_RIGHT') {
			input = input + str_pad_repeater(pad_string, pad_to_go);
		} else if (pad_type == 'STR_PAD_BOTH') {
			half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
			input = half + input + half;
			input = input.substr(0, pad_length);
		}
	}

	return input;
}

function numToImg(num){
	var img = '';
	for(var i=0; i < num.length; i++){
		img += "<img src='"+PSKIN_PATH+'/'+num.substr(i, 1)+".jpg' align='absmiddle'>";
	}

	return img;

}

function clearAndLoad(xmlUrl){
	clearAll();
	loadXML(xmlUrl);
}


function clearAll(){
	$("#mainContents").empty();
}


function xmlListNetxConfirm(img){
	if(img.indexOf("1_50")	!=	'-1'){
		$('#1_50').attr("src", $('#1_50').attr("src").replace("_off","_on"));
		$('#51_100').attr("src", $('#51_100').attr("src").replace("_on","_off"));
		clearAndLoad(xmlLoadUrl.replace("51-100", "1-50"));
	}else{
		$('#51_100').attr("src", $('#51_100').attr("src").replace("_off","_on"));
		$('#1_50').attr("src", $('#1_50').attr("src").replace("_on","_off"));
		clearAndLoad(xmlLoadUrl.replace("1-50", "51-100"));
	}
}


function downInfoLayerOpenFn(id, url, pageUrl, iframe, heightSize){
	if(iframe	==	undefined)	iframe	=	'YES';
	if(heightSize	==	undefined)	heightSize	=	250;

	var popID = id;
	var popURL = url;

	var query= popURL.split('?');
	var dim= query[1].split('&');
	var popWidth = dim[0].split('=')[1];

	$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close" onclick="downInfoLayerCloseFn()"><img src="'+PSKIN_PATH+'/close_pop.png" class="btn_close" alt="Close" /></a>');

	//var popMargTop = ($('#' + popID).height() + 80) / 2;
	var popMargTop = 10;//($('#' + popID).height() + 80) / 2;
	var popMargLeft = ($('#' + popID).width() + 80) / 2;

	$('#' + popID).css({
		/*
		'margin-top' : -popMargTop+'px',
		'margin-left' : -popMargLeft
		*/
		'top' : popMargTop,
		'margin-left' : -popMargLeft
	});

	$('body').append('<div id="fade"></div>');
	$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn();

	if(pageUrl	==	'login'){
		$('#musicDiv').load('/login/login.php').css({'display' : 'block'});
	}else if(pageUrl	==	'pay'){
		$('#musicDiv').load('/pay/pay.php').css({'display' : 'block'});
	}else{
		if(iframe	==	'YES'){
			$('#musicDiv').html("<iframe src='"+pageUrl+'&thisConnectType=iframe'+"' style='width:"+popWidth+"px; height:"+heightSize+"px' frameborder='0' scrolling='no' id='playFrame' name='playFrame'></iframe>").css({'display' : 'block', "height":heightSize+"px"});
		}else
			$('#musicDiv').load(pageUrl).css({'display' : 'block'});
	}

	return false;
}

function downInfoLayerCloseFn(){
	$('a.close, #fade').live('click', function() {
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();
			$('#musicDiv').html("").css({'display' : 'none'});
		});

		return false;
	});
}

//JJ
function downInfoLayerCloseFnJJ(){
	$('#fade , .popup_block').fadeOut(function() {
		$('#fade, a.close').remove();
		$('#musicDiv').html("").css({'display' : 'none'});
	});

	return false;
}

function getSkinSet(id, dir, _MAIN_CATE, _SUB_CATE, _GROUP_CATE, _PAGE_ROW, _SKIN, _OPTION){
	if(_OPTION	==	undefined)	_OPTION	=	'';
	$('#'+id).load(dir+'/get.index.html?'+ getmicrotime +'&_MAIN_CATE='+_MAIN_CATE+'&_SUB_CATE='+_SUB_CATE+'&_GROUP_CATE='+_GROUP_CATE+'&_PAGE_ROW='+_PAGE_ROW+'&_SKIN='+_SKIN+'&_OPTION='+_OPTION);
}


var old;
var oldgp;
function tabTxtSelected(id, gp){
	if(old && id != old && old.indexOf(gp) != '-1'){
		document.getElementById(old).style.fontWeight	=	'normal';
	}
	document.getElementById(id).style.fontWeight	=	'bold';
	old		=	id;
	oldgp	=	gp;
}

function is_cellular(_val){
	if(_val.search(/^(010|011|016|017|018|019)\-?[0-9]{3,4}\-?[0-9]{4}$/gi)==-1) return false;
	return true;
}

function is_pin(_val){
	if(!_val) return false;

	var sum = 0
	var date = new Date();
	var year = (date.getYear()+10).toString().substring(2,4);
	var chkNum = _val.substring(6,7);

	if(chkNum.search(/[1-2]/)!=-1){
		if(parseInt(_val.substring(0,2)) < year) return false;
	}else if(chkNum.search(/[3-4]/)!=-1){
		if(parseInt(_val.substring(0,2)) > year) return false;
	}else return false;

	var arrNo = new Array(2, 3, 4, 5, 6, 7, 8, 9, 2, 3, 4, 5);
	for (i=0;i<12;i++) sum += parseInt(_val.substring(i,i+1)) * parseInt(arrNo[i]);
	num = (11-sum%11)%10;
	return (num == parseInt(_val.substring(12,13))?true:false);
}

function is_adult(_pin){
	var odate = new Date();
	var month = odate.getMonth()+1;
	var date = odate.getDate();
	var adult_year = (odate.getFullYear()-20)+(month<10?'0':'')+month+(date<10?'0':'')+date;

	var year = '';
	var delimiter = _pin.substring(6,7);
	switch(delimiter){
		case '1': case '2': year = '19'; break;
		case '3': case '4': year = '20'; break;
	}
	var birthdate = year+_pin.substring(0,6);

	return ( parseInt(adult_year, 10)>parseInt(birthdate, 10)?true:false);
}

function login(){
	var obj = $('#mphone');
	if( !is_cellular( obj.val() ) ){
		alert('전화번호를 정확히 입력하세요');
		obj.focus();
		return false;
	}

	var obj = $('#mpin');
	if( !is_pin( obj.val() ) ){
		alert('주민번호를 정확히 입력하세요');
		obj.focus();
		return false;
	}

	if( !is_adult( obj.val() ) ){
		alert('성인이 아닙니다.');
		obj.focus();
		return false;
	}

	var url = '/lib/member.check.php';
	var param = $('#frmJoin').serialize();

	$.post(url, param, function(data){
		var ojson = $.parseJSON(data);
		if( ojson.result=='Y' ){
			alert('실명인증을 완료 하셨습니다.');
			if(ojson.url	!=	'')
				go(ojson.url);
			else
				document.location.reload();
		}else{
			alert( ojson.message );
		}
	});
}

function getSearchFn(){
	document.location='/chart/?word='+$('#word').val();
}

function go(url){
	document.location =url;
}

function getPushMenu(nowstep, nextstep, url){
	var param = "&code="+$("#level"+nowstep).val();

	param = param + "&nowstep="+nowstep;
	param = param + "&nextstep="+nextstep;
	sendRequest(rstGetPushMenu, param, "post", url, true, true);


}

function rstGetPushMenu(oj){
	var res = $.trim(oj.responseText);
	var line = res.split("\n");
	var temp = res.split("|");


	var addSelect = document.getElementById("level"+temp[1]);
	for(ii=temp[1]; ii<=2; ii++){
		var chekSEL = document.getElementById("level"+ii);
		for(var i=chekSEL.length - 1; i > 0; i--){
			chekSEL.options[i].value = null;
			chekSEL.options[i] = null;
			$("#step"+ii).val('');
		}
	}

	if(line.length>0){
		addSelect.options[0] = new Option('선택', '');
		for(i=0; i<line.length; i++){
			tmp = line[i].split("|");
			if(tmp[2]){
				addSelect.options[i+1] = new Option(tmp[3], tmp[2]);
			}
		}
	}

	$('#level2').val($('#leve2Value').val());
}

function cal(id, min){
	$("#"+id).datepicker({
		dateFormat: "yy-mm-dd"
		, dayNamesMin: [ "일", "월", "화", "수", "목", "금", "토" ]
		, monthNames: [ "1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월" ]
		, minDate: min
		/*"selectOtherMonths": true*/
		/*"changeMonth", true */
		, disabled : false
		, showMonthAfterYear: true
		, yearSuffix: "년 "
		, showAnim: "show"
	 });
}


function ajaxFormSave(f, path){
	$('#loadingmodal').modal();
	//alert("#"+f+':::'+$("#"+f).serialize());return;
	//alert($("#"+f).serialize());return;
	//alert(path);
	var url = path;
	var option = {
		url : url
		, type : "post"
		, data : $("#"+f).serialize()
		, beforeSend: function(){
			//$('#loadingmodal').modal();
		}
		, success : function(data){
			console.log(data);
			//alert(data);
			var ojson = $.parseJSON(data);

			if(ojson.result=='Y'){
				if(ojson.message)
					alert(ojson.message);
				else
					;

				if($.trim(ojson.url)	==	'f5'){
					document.location = document.URL;//document.location.reload();
				}else if($.trim(ojson.url)	==	'scroll'){
					document.location.reload();
					$('html,body').animate({scrollTop:$(this.hash).offset().top}, 500);
				}else if($.trim(ojson.url)	==	'cardpay'){
					$('#ZEROEXTEND1').val(ojson.code);
					var url = "/payment/card.paymnet.siso?"+$("#frmConfirm").serialize();
					$('#paymentModal').bPopup({
						content:'iframe'
						, iframeAttr:('scrolling="no" frameborder="0" width="100%" height="100%"')
						, contentContainer:'.paymentcontent'
						, loadUrl:url
						, modalClose: false
						, escClose: false
					});
				}else if($.trim(ojson.url)	!=	''){
					location.replace($.trim(ojson.url));
				}else{
					;
				}
			}else{
				alert(ojson.message);


			}
		}
		, complete : function(){
			$('#loadingmodal').modal('hide');
		}
		, error: function(xhr, status, error){
			//alert(error);
		}
	};
	//alert(option);
	$("#"+f).ajaxSubmit(option);
}

function ajaxFormUserSave(f, path){
	$('#loadingmodal').modal();
	//alert("#"+f+':::'+$("#"+f).serialize());return;
	//alert($("#"+f).serialize());return;
	//alert(path);
	dataLodingFn();
	var url = path;
	var option = {
		url : url
		, type : "post"
		, data : $("#"+f).serialize()
		, beforeSend: function(){
			//$('#loadingmodal').modal();
			dataLodingFn();
		}
		, success : function(data){
			//console.log(data);
			//alert(data);
			var ojson = $.parseJSON(data);

			if(ojson.result=='Y'){
				if(ojson.message)
					alert(ojson.message);
				else
					;

				if($.trim(ojson.url)	==	'f5'){
					document.location = document.URL;//document.location.reload();
				}else if($.trim(ojson.url)	==	'scroll'){
					document.location.reload();
					$('html,body').animate({scrollTop:$(this.hash).offset().top}, 500);
				}else if($.trim(ojson.url)	!=	''){
					location.replace($.trim(ojson.url));
				}else{
					;
				}
			}else{
				alert(ojson.message);


			}
		}
		, complete : function(){
			$('#loadingmodal').modal('hide');
			$('.loding_popup').bPopup().close();
		}
		, error: function(xhr, status, error){
			//alert(error);
		}
	};
	//alert(option);
	$("#"+f).ajaxSubmit(option);
}

function ajaxFormSaveCate(f, path){
	var img_half_width = 50;
	if(!$('#loading_img').length){
		$('body').append('<img src="'+PSKIN_PATH+'/sis_loading.gif" id="loading_img" />');
		$('#loading_img').css({"position":"absolute","zIndex":10,"display":"none"});
	}

	var url = path;
	var option = {
		url : url
		, type : "post"
		, data : $("#"+f).serialize()
		, beforeSend: function(){
			var popMargTop = $(document).height()-($(window).height()/2+img_half_width);
			var popMargLeft =$(document).width()/2-img_half_width;
			$('#loading_img').css({"z-index":"9997","display":"block","top":$(document).height()-($(window).height()/2+img_half_width),"left":$(document).width()/2-img_half_width});
		}
		, success : function(data){
			downInfoLayerCloseFnJJ();
		}
		, complete : function(){
			$('#loading_img').css({"display":"none"});
		}
	};
	$("#"+f).ajaxSubmit(option);
}

function checkFormFn(obj, objName){
	var result;
	var result = $('#'+obj);

	if(!$.trim(result.val())){
		alert(objName+'을(를) 입력(선택)하세요.');
		result.focus();
		return(false);
	}
}


function checkFormLenFn(obj, objName, minLen){
	var result;

	var result = $('#'+obj);
	if($.trim(result.val()).length < minLen){
		alert( objName + "은(는) " + minLen + "자이상 입력하세요.");
		result.focus();
		return(false);
	}
}

function number_format(number, decimals, dec_point, thousands_sep){
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function (n, prec){
	  var k = Math.pow(10, prec);
	  return '' + Math.round(n * k) / k;
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
	s[1] = s[1] || '';
	s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}

function payCheckingFn(rUrl, rSize, IDX, pageType){
	if(pageType	==	undefined)	pageType	=	'MUSICAPP';
	var d = new Date();
	time	=	d.getTime();
	var scriptUrl = "/lib/pay.ck.php?"+time+'&musicIDX='+IDX+"&whe=W";

	$.ajax({
		url: scriptUrl,
		type: 'get',
		dataType: 'html',
		async: false,
		success: function(data) {
			var ojson = $.parseJSON(data);
			//alert(ojson.result);
			if(ojson.result	==	'PAY'){
				//alert(PAYMENTTYPE);
				if(ojson.message != '')
					alert(ojson.message);
				msg	=	'pay';
				//alert(PAYMENTTYPE);//return;
				if(PAYMENTTYPE){
					if(PAYMENTCOMPANY	==	'DANAL'){
						//alert("DANAL TEST");
						downInfoLayerOpenFn('popup1', '#?w=500', '/pay/'+PAYMENTCOMPANY+'_TEST/select.php?', 'YES', 680);
						//downInfoLayerOpenFn('popup1', '#?w=500', '/pay/'+PAYMENTCOMPANY+'/Ready.php?', 'YES', 680);
					}else if(PAYMENTCOMPANY	==	'GALAXIA'){
						//alert("GAL TEST");
						downInfoLayerOpenFn('popup1', '#?w=500', '/pay/'+PAYMENTCOMPANY+'/pay.php?', 'YES', 477);
						//downInfoLayerOpenFn('popup1', '#?w=500', '/pay/'+PAYMENTCOMPANY+'/select.php?', 'YES', 477);
					}
				}else
					downInfoLayerOpenFn('popup1', '#?w=560', 'pay');
				return;
			}else if(ojson.result	==	'LOGIN'){
				if(ojson.message != '')
					alert('회원 로그인을 먼저 하시기 바랍니다.');//alert(ojson.message);
				msg	=	'login';
				//downInfoLayerOpenFn('popup1', '#?w=282', 'login');
				return;
			}else{
				if(pageType	==	'MUSICAPP')
					downInfoLayerOpenFn('popup1', rSize, rUrl+'&'+time);
				else if(pageType	==	'SECURITYAPP')
					alert('앱 다운로드후 이용하시기 바랍니다.');
				else if(pageType	==	'SUPERDOWNAPP')
					alert('앱 다운로드후 이용하시기 바랍니다.');
			}
		}
	});
}

// 2013-07-29 JJ 월자동결제해지 팝업 추가
function payCancelFn(AFTER){
	//alert("asdasd");
	var d = new Date();
	time	=	d.getTime();
	var scriptUrl = "/lib/pay.ck.php?"+time+'&musicIDX=1&whe=W';

	$.ajax({
		url: scriptUrl,
		type: 'get',
		dataType: 'html',
		async: false,
		success: function(data) {
			var ojson = $.parseJSON(data);
			//alert(ojson.message);
			if(ojson.result	==	'PAY'){
				downInfoLayerOpenFn('popup1', '#?w=500', '/cancel/cancel.php?AFTER='+AFTER, 'YES', 200);
				return;
			}else{
				if(ojson.message != '')
					alert('로그인을 먼저 해주시기 바랍니다.');
				msg	=	'login';
				return;
			}
		}
	});
}

function testPayCheckingFn(IDX){
	var d = new Date();
	time	=	d.getTime();
	if(PAYMENTCOMPANY	==	'DANAL'){
		//alert('/pay/'+PAYMENTCOMPANY+'_TEST/Ready.php');return;
		downInfoLayerOpenFn('popup1', '#?w=500', '/pay/'+PAYMENTCOMPANY+'_TEST/Ready.php?', 'YES', 680);
	}else if(PAYMENTCOMPANY	==	'GALAXIA')
		downInfoLayerOpenFn('popup1', '#?w=500', '/pay/'+PAYMENTCOMPANY+'/pay.php?', 'YES', 477);
}

function priceDetailPageLoad(gp, selectedDate, vType){
	var d = new Date();
	time	=	d.getTime();
	downInfoLayerOpenFn('popup1', '#?w=1000', 'price.statistical.php?curdate='+selectedDate+'|'+time+'|'+gp+'|YES|'+vType, 'NO');
}

function downlaodDetailPageLoad(gp, selectedDate, vType){

	var d = new Date();
	time	=	d.getTime();
	downInfoLayerOpenFn('popup1', '#?w=1000', 'down.statistical.php?curdate='+selectedDate+'|'+time+'|'+gp+'|YES|'+vType, 'NO');
}

function loadTab(tabObj){
	var d = new Date();
	time	=	d.getTime();
	$('#tabs'.panel).html(tabObj.attr('href'));
}

function apkExtCheck(obj, versionName, packageName, url, frm,fileUpDirFile,fileUploadLocation){
	$('#'+frm).attr("enctype","application/x-www-form-urlencoded");

	filename	=	obj.value;

	if(filename	==	''){
		alert('파일이 선택되지 않았습니다.');
		return;
	}
	//if(filename.match(/\.(apk)$/i)){

		var img_half_width = 50;
		if(!$('#loading_img').length){
			$('body').append('<img src="'+PSKIN_PATH+'/sis_loading.gif" id="loading_img" />');
			$('#loading_img').css({"position":"absolute","zIndex":10,"display":"none"});
		}
		//alert($("#"+frm).serialize());return;
		var option = {
			url : url
			, type : "post"
			, data : $("#"+frm).serialize()
			, beforeSend: function(){
				var popMargTop = $(document).height()-($(window).height()/2+img_half_width);
				var popMargLeft =$(document).width()/2-img_half_width;
				$('#loading_img').css({"z-index":"9997","display":"block","top":$(document).height()-($(window).height()/2+img_half_width),"left":$(document).width()/2-img_half_width});
			}
			, success : function(data){
				var ojson = $.parseJSON(data);
				if(ojson.result=='Y'){
					$('#'+packageName).val(ojson.PackageName);
					$('#'+versionName).val(ojson.VersionName);
					$('#'+fileUploadLocation).empty();
					$('#'+fileUploadLocation).html('업로드가 완료되었습니다.');
					$('#'+fileUpDirFile).val(ojson.uploadApk);
				}else{
					alert(ojson.message);
					obj.outerHTML = obj.outerHTML;
				}
			}
			, complete : function(){
				$('#loading_img').css({"display":"none"});
			}
			, error: function(xhr, status, error){
				alert(error);
			}
		};
		$("#"+frm).ajaxSubmit(option);


		return true;
	/*
	}else{
		alert('APK 확장자가 아닙니다.');
		obj.outerHTML = obj.outerHTML;
		obj.focus();
		obj.select()
	}
	*/
}


//장부장 함수
function JoinGo(){
	alert("회원가입 후 이용가능합니다.");
	location.href="/join/join.php";
}

function ChkLen(obj){
	var tmpStr;
	var temp=0;
	var onechar;
	var tcount;
	tcount = 0;

	tmpStr = new String(obj.value);
	temp = tmpStr.length;

	for(k=0;k<temp;k++){
		onechar = tmpStr.charAt(k);
		if(escape(onechar) =='%0D'){
			;
		}else if(escape(onechar).length > 4){
			tcount += 2;
		}else{
			tcount++;
		}
	}

	if(tcount > 80){
		reserve = tcount-80;
		alert("내용은 80바이트 이상은 입력 하실수 없습니다.\r\n 입력하신 내용은 "+reserve+"바이트가 초과되었습니다.\r\n초과된 부분은 자동으로 삭제됩니다.\와 안된다.. ㅜㅜ 좀 맞춰서 해주세요 곧 수정하도록 하겟습니다.");
		//nets_check(obj);
		//alert(escape(onechar).length);
		//tmpStr.substring(0,)
		//alert(reserve)
		return;
	}
	$('#smsInputTxt').html(tcount);
	return tcount;
}

function nets_check(obj){

	var tmpStr2;
	var temp2=0;
	var onechar;
	var tcount;
	tcount = Number($('#smsInputTxt').html());
	//alert(tcount);

	tmpStr2 = new String(obj.value);
	//alert(tmpStr2);return;
	temp2 = tmpStr2.length;
	//alert(temp2);return;

	for(k=0;k<temp2;k++){
		//alert(k);
		onechar = tmpStr2.charAt(k);

		if(escape(onechar).length > 4) {
			tcount += 2;
		} else {
			// 엔터값이 들어왔을때 값(\r\n)이 두번실행되는데 첫번째 값(\n)이 들어왔을때 tcount를 증가시키지 않는다.
			if(escape(onechar)=='%0A') {
			} else {
				tcount++;
			}
		}

		if(tcount>80) {
			alert(k)
			tmpStr2 = tmpStr2.substring(0,k);
			break;
		}

	}
	//document.MsgForm.strMsg.value = tmpStr;
	$('#smsInputTxt').html(tcount);

	//ChkLen(tmpStr);
}

function addMarkFn(obj, length, maxLength, focuss, add){
	//str = obj.value.replace(/-/g, "");
	//alert(obj.value);return;
	str = obj.value.replace(/-/g, "");

	keyCode = event.charCode ? event.charCode : event.keyCode;
	//alert(keyCode);return;
	if(length  == 'tel'){	//전화 혹은 휴대폰경우는 예외로 따로 처리 "-" 몇개 붙을지 모르겠네.
		if(str.indexOf("02") == 0){	//서울은 국번이 두자리 예외처리
			if(str.length == 2 && keyCode != 8)
				obj.value = str + add;
			else if(str.length == 5 && keyCode != 8)
				obj.value = str.substring(0,2) + add + str.substring(2,5) + add;
			else if(str.length == 9 && keyCode != 8)
				obj.value = str.substring(0,2) + add + str .substring(2,5) + add + str.substring(5);
			else if(str.length == 10 && keyCode != 8)
				obj.value = str.substring(0,2) + add + str.substring(2,6) + add + str.substring(6);
			else if(str.length > 10 && keyCode != 8){
				obj.value = str.substring(0,10);
				obj.value = str.substring(0,2) + add + str.substring(2,6) + add + str.substring(6,10);
			}
		}else{
			if(str.length == 3 && keyCode != 8)
				obj.value = str + add;
			else if(str.length == 6  && keyCode != 8)
				obj.value = str.substring(0,3) + add + str.substring(3,6) + add;
			else if(str.length == 8  && keyCode != 8)
				obj.value = str.substring(0,4) + add + str.substring(4,8);
			else if(str.length == 9  && keyCode != 8)
				obj.value = str.substring(0,3) + add + str.substring(3,6) + add+str.substring(6);
			else if(str.length == 11  && keyCode != 8)
				obj.value = str.substring(0,3) + add + str.substring(3,7) + add+str.substring(7);
			else if(str.length > 11 && keyCode != 8){
				obj.value = str.substring(0,11);
				obj.value = str.substring(0,3) + add + str.substring(3,7) + add + str.substring(7,11);
			}
		}
	} else if (length == 'hp'){ //기초신상정보에서 핸드폰국번만따로처리하기위해서 사용 - 민호
		if(str.length == 3 && keyCode != 8)
			obj.value = str + add;
		else if(str.length == 7  && keyCode != 8)
			obj.value = str.substring(0,3) + add + str.substring(3,7);
		else if(str.length == 8  && keyCode != 8)
			obj.value = str.substring(0,4) + add + str.substring(4,8);
	} else{
		if(str.length > 0 && keyCode != 8){
			if(focuss == 'jumin'){
				addDelim	=	str.length == 6 ? str : '';
			}else{
				addDelim	=	add;
			}
			if(str.length >= length){
				obj.value = str.substring(0,length) + addDelim + str.substring(length,maxLength);
			}
		}
	}
}


function onlyNumFn(){
	if(event.keyCode >= 48 && event.keyCode <= 57){
		return true;
	}else{
		event.returnValue = false;
	}
}

//숫자+점만 입력
function onlyNumorDotFn(){
	if(event.keyCode == 46 || event.keyCode >= 48 && event.keyCode <= 57){
		return true;
	}else{
		event.returnValue = false;
	}
}

//숫자+점+마이너스만 입력
function onlyNumorDotMinusFn(){
	if(event.keyCode == 45 || event.keyCode == 46 || event.keyCode >= 48 && event.keyCode <= 57){
		return true;
	}else{
		event.returnValue = false;
	}
}

function setAjaxPostSend(path, code, control, val){
	//alert(code);return;
	//console.log("11111"+control); return;
	jQuery.ajax({
		type: 'POST'
		, url: path
		, data: {'CODE':code,'dbControl':control,'v':val}
		, beforeSend: function(){
			//$('#loadingmodal').modal();
			console.log("22222222222");
		}
		, async: false
		, success: function(data) {
			//console.log(data);
			var ojson = jQuery.parseJSON(data);
			//console.log("11111111111111");

			if(ojson.result=='Y'){
				//alert(ojson.url);
				if(ojson.message)
					alert(ojson.message);

				if(jQuery.trim(ojson.option)	!=	'')
					opener.jQuery('#groupZone').html(ojson.option);

				if(jQuery.trim(ojson.url)	==	'f5'){
					//alert();
					//document.location = document.URL;//document.location.reload();
					location.replace(document.URL.replace(/#/g,''));
				}else if(jQuery.trim(ojson.url)	==	'APPLOCATIONSIGN'){

					//alert('ww :: '+document.URL.replace(/#/g,''));return;
					//document.location = document.URL;//document.location.reload();
					Android.selectGps(ojson.type, ojson.appurl, ojson.gpsIs, ojson.gpsMent);
					location.replace(document.URL.replace(/#/g,''));

				}else if(jQuery.trim(ojson.url)	==	'RestFn'){

					//alert('ww :: '+document.URL.replace(/#/g,''));return;
					//document.location = document.URL;//document.location.reload();
					Android.RestFn(ojson.appsendurl, 'Y')
					location.replace(document.URL.replace(/#/g,''));

				}else if(jQuery.trim(ojson.url)	!=	''){
					//alert('w');
					//document.location	=	jQuery.trim(ojson.url);
					location.replace(jQuery.trim(ojson.url));
				}
			}else
				alert(ojson.message);
		}
		, complete : function(){
			//$('#loadingmodal').modal('hide');
		}
		, error: function(xhr, status, error){
			console.log("22222222222");
			//alert(error);
		}
	});
}

function setAjaxIOSPostSend(path, code, control, val){
	//alert(code);return;
	jQuery.ajax({
		type: 'POST'
		, url: path
		, data: {'CODE':code,'dbControl':control,'v':val}
		, beforeSend: function(){
			$('#loadingmodal').modal();
		}
		, async: false
		, success: function(data) {
			var ojson = jQuery.parseJSON(data);
			if(ojson.result=='Y'){
				//alert(ojson.url);
				if(ojson.logintype == 'N'){
					var message = {
						'logintype':'N'
					};
					try{
						// swal(ojson.logintype);
						webkit.messageHandlers.logintype.postMessage(message);
					}catch (err){
						swal(err);
					}
					var url = "/_ios/index.siso";
					location.replace(url);
				}
				if(ojson.message){
					//swal(ojson.message);
				}
				if(jQuery.trim(ojson.option)	!=	''){
					opener.jQuery('#groupZone').html(ojson.option);
				}
				if(jQuery.trim(ojson.url)	==	'f5'){
					//alert();
					//document.location = document.URL;//document.location.reload();
					location.replace(document.URL.replace(/#/g,''));
				}else if(jQuery.trim(ojson.url)	==	'logout'){
					//alert('ww :: '+document.URL.replace(/#/g,''));return;
					//document.location = document.URL;//document.location.reload();
					//Android.selectGps(ojson.type, ojson.appurl, ojson.gpsIs, ojson.gpsMent);
					//location.replace(document.URL.replace(/#/g,''));
					var message = {
						'logintype':'N'
					};
					try{
						//swal(ojson.logintype);
						webkit.messageHandlers.logintype.postMessage(message);
					}catch (err){
						swal(err);
					}
					var url = "/_ios";
					location.replace(url);
				}else if(jQuery.trim(ojson.url)	==	'RestFn'){

					//alert('ww :: '+document.URL.replace(/#/g,''));return;
					//document.location = document.URL;//document.location.reload();
					Android.RestFn(ojson.appsendurl, 'Y')
					location.replace(document.URL.replace(/#/g,''));

				}else if(jQuery.trim(ojson.url)	==	'IOS'){
					//location.replace(jQuery.trim(ojson.url));
					//테라 서버변경창 오픈 리로드작업
					var url = "/_ios/index.siso?"+$("#ListState").serialize()+"&"+$("#s_cty_code").serialize()+"&"+$("#search_server_name").serialize();
					location.replace(url);

				}else if(jQuery.trim(ojson.url)	!=	''){
					//alert('w');
					//document.location	=	jQuery.trim(ojson.url);
					location.replace(jQuery.trim(ojson.url));
				}
			}else{
				//swal(ojson.message);
			}
		}
		, complete : function(){
			$('#loadingmodal').modal('hide');
		}
	});
}

function setAjaxIOSSettingPostSend(path, code, control, val){
	//alert(code);return;
	jQuery.ajax({
		type: 'POST'
		, url: path
		, data: {'CODE':code,'dbControl':control,'v':val}
		, beforeSend: function(){
			$('#loadingmodal').modal();
		}
		, async: false
		, success: function(data) {
			var ojson = jQuery.parseJSON(data);
			if(ojson.result=='Y'){
				//alert(ojson.url);
				if(ojson.message){
					//swal(ojson.message);
				}
				if(jQuery.trim(ojson.option)	!=	'')
					opener.jQuery('#groupZone').html(ojson.option);
				if(jQuery.trim(ojson.url)	==	'f5'){
					var message = {
						'ios_idx':ojson.ios_idx,
						'id':ojson.id,
						'pw':ojson.pw,
						'logintype':ojson.check
					};
					try{
						  // swal(ojson.check);
						webkit.messageHandlers.logintype.postMessage(message);
					}catch (err){
						swal(err);
					}
					var url = "/_ios/setting.siso?"+$("#s_push_login_value").serialize();
					location.replace(url);
					//location.replace(document.URL.replace(/#/g,''));
				}else if(jQuery.trim(ojson.url)	==	'IOS'){
					location.replace(jQuery.trim(ojson.url));
				}else if(jQuery.trim(ojson.url)	!=	''){
					location.replace(jQuery.trim(ojson.url));
				}
			}else{
				//swal(ojson.message);
			}
		}
		, complete : function(){
			$('#loadingmodal').modal('hide');
		}
	});
}

function onSelectChange(srcElement, destElement, url, control, defaultVal){
	if(defaultVal == 'undefined') defaultVal = '';
    if(srcElement != null){
        var selectedValue = srcElement.val();
		//alert(selectedValue);

		if(selectedValue == ""){
			destElement.empty();
			destElement.append("<option value='" + "" + "'>선택</option>");
			return;
		}

		var img_half_width = 50;
		$('#loadingmodal').modal();

        $.getJSON(
			url
			, {"value": selectedValue, "dbControl":control}
			, function(data){
				destElement.empty();
				destElement.append("<option value='" + "" + "'>선택</option>");
				for(var index=0;index<data.length;index++){
					destElement.append("<option value='" + data[index].id + "' "+(defaultVal == data[index].id ? 'selected' : '')+">" + data[index].value + "</option>");
				}
				destElement.removeAttr("disabled");
				destElement.focus();
				$('#loadingmodal').modal('hide');
			}
		);
    }
}

function valComparisonFn(obj1, obj2, txt){
	var result;

	var val1 = $('#'+obj1);
	var val2 = $('#'+obj2);
	if($.trim(val1.val())	!=	$.trim(val2.val())){
		alert(txt+'값이 일치하지 않습니다.');
		val1.focus();
		return(false);
	}
}

function setAjaxLogOutFn(path, code, control, val){
	if(confirm('로그아웃을 하시겠습니까?')){
		setAjaxPostSend(path, code, control, val);
	}
}

function setAjaxIOSLogOutFn(path, code, control, val){
	//ios연결?
	var message = {
		'logout':''
	};
	try{
		webkit.messageHandlers.callbackHandler.postMessage(message);
	}catch (err){
		swal(err);
	}

	setAjaxIOSPostSend(path, code, control, val);
}

function autoHypenPhone(str){
	str = str.replace(/[^0-9]/g, '');
	//alert(str.length);
	var tmp = '';
	if(str.length < 4){
		return str;
	}else if(str.length < 7){
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3);
		return tmp;
	}else if(str.length == 8){
		tmp += str.substr(0, 4);
		tmp += '-';
		tmp += str.substr(4, 4);
		return tmp;
	}else if(str.length == 9){
		tmp += str.substr(0, 2);
		tmp += '-';
		tmp += str.substr(2, 3);
		tmp += '-';
		tmp += str.substr(5, 4);
		return tmp;
	}else if(str.length < 11){
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3, 3);
		tmp += '-';
		tmp += str.substr(6);
		return tmp;
	}else{
		tmp += str.substr(0, 3);
		tmp += '-';
		tmp += str.substr(3, 4);
		tmp += '-';
		tmp += str.substr(7);
		return tmp;
	}

	return str;
}


function autoDateformat( str ){

	str = str.replace(/[^0-9]/g, '');
	//alert(str.length);
	var tmp = '';
	if(str.length < 6){
		return str;
	}else if(str.length < 9){
		tmp += str.substr(0, 4);
		tmp += '-';
		tmp += str.substr(4, 2);
		tmp += '-';
		tmp += str.substr(6);
		return tmp;
	}

	return str;
}

function numberonly(str){
	str = str.replace(/[^0-9]/g, '');


	return str;
}

//setAjaxPostSend(path, code, control, val)
function resellerCk(path, code, control, val){
	//setAjaxPostSend('/lib/control.siso', '', 'setPartnerNtoY', '')
	jQuery.ajax({
		type: 'POST'
		, url: path
		, data: {'CODE':code,'dbControl':control,'v':val}
		, beforeSend: function(){
			$('#loadingmodal').modal();
		}
		, async: false
		, success: function(data) {

			var ojson = jQuery.parseJSON(data);
			if(ojson.result=='Y'){
				//alert(ojson.url);
				if(ojson.message)
					alert(ojson.message);

				if(jQuery.trim(ojson.option)	!=	'')
					opener.jQuery('#groupZone').html(ojson.option);

				if(jQuery.trim(ojson.url)	==	'f5'){
					//alert();
					//document.location = document.URL;//document.location.reload();
					location.replace(document.URL.replace(/#/g,''));
				}else if(jQuery.trim(ojson.url)	==	'APPLOCATIONSIGN'){

					//alert('ww :: '+document.URL.replace(/#/g,''));return;
					//document.location = document.URL;//document.location.reload();
					Android.selectGps(ojson.type, ojson.appurl, ojson.gpsIs, ojson.gpsMent);
					location.replace(document.URL.replace(/#/g,''));

				}else if(jQuery.trim(ojson.url)	==	'RestFn'){

					//alert('ww :: '+document.URL.replace(/#/g,''));return;
					//document.location = document.URL;//document.location.reload();
					Android.RestFn(ojson.appsendurl, 'Y')
					location.replace(document.URL.replace(/#/g,''));

				}else if(jQuery.trim(ojson.url)	!=	''){
					//alert('w');
					//document.location	=	jQuery.trim(ojson.url);
					location.replace(jQuery.trim(ojson.url));
				}
			}else{
				swal({
					title: "알림"
					, text: ojson.message
					, type: "error"
					, showCancelButton: true
					, confirmButtonColor: "#DD6B55"
					, confirmButtonText: "다운로드"
					, cancelButtonText: "확인"
					, closeOnConfirm: false
					, closeOnCancel: false
				}, function (isConfirm) {
					if(isConfirm){
						//setAjaxPostSend(path, code, control, val);
						//swal("Deleted!", "Your imaginary file has been deleted.", "success");
						window.open('/UPLOAD/ADMIN/DB_Maker_Install.exe');
					}else{
						swal.close();
						//swal("취소", "취소하였습니다.", "error");
					}

				});
			}

		}
		, complete : function(){
			$('#loadingmodal').modal('hide');
		}
	});


}

function boardDelFn(path, code, control, val){
	if(confirm('삭제하시겠습니까?\n한번 삭제한 데이터는 복구 불가능합니다.')){
		setAjaxPostSend(path, code, control, val);
	}
}

function publicTemplateDelFn(path, code, control, val){
	if(confirm('삭제하시겠습니까?\n한번 삭제한 데이터는 복구 불가능합니다.')){
		setAjaxPostSend(path, code, control, val);
	}
}

function balRegiConfirm(path, code, control, val){
	if(confirm('발신번호로 등록 승인하시겠습니까?')){
		setAjaxPostSend(path, code, control, val);
	}
}

function setCookie(name, value, expiredays){
	var today = new Date();
	today.setDate( today.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";"
}

function getCookie(cookie_name) {
	var x, y;
	var val = document.cookie.split(';');

	for (var i = 0; i < val.length; i++) {
		x = val[i].substr(0, val[i].indexOf('='));
		y = val[i].substr(val[i].indexOf('=') + 1);
		x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤의 공백 제거하기
		if (x == cookie_name) {
			return unescape(y); // unescape로 디코딩 후 값 리턴
		}
	}
}

function adressDataloadingFn(pagenum, rejection_is, groupCODE){
	//alert(groupCODE);
	//alert(pagenum);
	$(".list_ct dl").click(function(){
		//alert('w');
		if ($(this).hasClass('active') == true) {
			$(this).find("input[type=checkbox]").prop("checked", false);
			$(this).find("input[type=checkbox]").removeClass('active');
			$(this).removeClass('active');
		} else if ($(this).hasClass('active') == false){
			$(this).find("input[type=checkbox]").prop("checked", true);
			$(this).find("input[type=checkbox]").addClass('active');
			$(this).addClass('active');
		}

	});
	$.ajax({
		url: '/lib/control.siso',
		type: 'POST',
		dataType: 'html',
		data: {'dbControl':'getMemberAddressBookList', 'pagenum':pagenum, 'rejection_is':rejection_is, 'groupCODE':groupCODE},
		async: false,
		success: function(data) {


			//$("#innerPhoneDetailImg").empty();
			//$('.contain').hide();
			var ojson = $.parseJSON(data);
			//alert(ojson);
			if(ojson.result	==	'Y'){
				$('.personnel').html(number_format(ojson.total));
				var textHtml	=	'';
				var state		=	'';
				for(var i=0; i<ojson.data.length; i++){
					state		=	ojson.data[i]['state'].split("|");
					textHtml	+=	'<dl class="kinds after address_dl">';
					textHtml	+=	'	<dd class="number"><input type="checkbox" name="mab_idx" id="mab_idx" value="'+ojson.data[i]['mab_idx']+'" class="addresscheck" username="'+ojson.data[i]['mab_name']+'" usertel="'+ojson.data[i]['mab_hp']+'"> '+ojson.data[i]['num']+' </dd>';
					textHtml	+=	'	<dd class="group"><a class="Ellipsis" id="mab_name">'+ojson.data[i]['mabg_name']+'</a></dd>';
					textHtml	+=	'	<dd class="name"><a class="Ellipsis" id="mab_name">'+ojson.data[i]['mab_name']+'</a></dd>';
					textHtml	+=	'	<dd class="phone" !id="mab_hp">'+ojson.data[i]['print_hp']+'</dd>';
					textHtml	+=	'	<dd class="mail"><a class="Ellipsis">'+ojson.data[i]['mab_email']+'</a></dd>';
					textHtml	+=	'	<dd class="tel">'+ojson.data[i]['mab_tel']+'</dd>';
					textHtml	+=	'	<dd class="fax">'+ojson.data[i]['mab_fax']+'</dd>';
					textHtml	+=	'	<dd class="recept" alt="'+state[1]+'" title="'+state[1]+'"><span class="'+state[0]+'"><img src="'+state[2]+'" alt="'+state[1]+'"></span></dd>';
					textHtml	+=	'</dl>';
					//alert(textHtml);
				}
				$('#container').append(textHtml);


			}



			//}

		}
	});
}

function adressDataloadingTxtSetFn(rejection_is, groupCODE){
	//alert(groupCODE);
	//alert(pagenum);
	window.open('set.addressbook.txt.siso?rejection_is='+rejection_is+'&groupCODE='+groupCODE);return;
	$(".list_ct dl").click(function(){
		//alert('w');
		if ($(this).hasClass('active') == true) {
			$(this).find("input[type=checkbox]").prop("checked", false);
			$(this).find("input[type=checkbox]").removeClass('active');
			$(this).removeClass('active');
		} else if ($(this).hasClass('active') == false){
			$(this).find("input[type=checkbox]").prop("checked", true);
			$(this).find("input[type=checkbox]").addClass('active');
			$(this).addClass('active');
		}

	});

	$.ajax({
		url: '/lib/control.siso',
		type: 'POST',
		dataType: 'html',
		data: {'dbControl':'getMemberAddressBookSetTXT', 'rejection_is':rejection_is, 'groupCODE':groupCODE},
		async: false,
		success: function(data) {


			//$("#innerPhoneDetailImg").empty();
			//$('.contain').hide();
			var ojson = $.parseJSON(data);
			//alert(ojson);
			if(ojson.result	==	'Y'){
				$('.personnel').html(number_format(ojson.total));
				var textHtml	=	'';
				var state		=	'';
				for(var i=0; i<ojson.data.length; i++){
					state		=	ojson.data[i]['state'].split("|");
					textHtml	+=	'<dl class="kinds after address_dl">';
					textHtml	+=	'	<dd class="number"><input type="checkbox" name="mab_idx" id="mab_idx" value="'+ojson.data[i]['mab_idx']+'" class="addresscheck" username="'+ojson.data[i]['mab_name']+'" usertel="'+ojson.data[i]['mab_hp']+'"> '+ojson.data[i]['num']+' </dd>';
					textHtml	+=	'	<dd class="group"><a class="Ellipsis" id="mab_name">'+ojson.data[i]['mabg_name']+'</a></dd>';
					textHtml	+=	'	<dd class="name"><a class="Ellipsis" id="mab_name">'+ojson.data[i]['mab_name']+'</a></dd>';
					textHtml	+=	'	<dd class="phone" !id="mab_hp">'+ojson.data[i]['mab_hp']+'</dd>';
					textHtml	+=	'	<dd class="mail"><a class="Ellipsis">'+ojson.data[i]['mab_email']+'</a></dd>';
					textHtml	+=	'	<dd class="tel">'+ojson.data[i]['mab_tel']+'</dd>';
					textHtml	+=	'	<dd class="fax">'+ojson.data[i]['mab_fax']+'</dd>';
					textHtml	+=	'	<dd class="recept" alt="'+state[1]+'" title="'+state[1]+'"><span class="'+state[0]+'"><img src="'+state[2]+'" alt="'+state[1]+'"></span></dd>';
					textHtml	+=	'</dl>';
					//alert(textHtml);
				}
				$('#container').append(textHtml);


			}



			//}

		}
	});
}

function insertAtCaret(areaId, text) {
  var txtarea = document.getElementById(areaId);
  if (!txtarea) {
    return;
  }

  var scrollPos = txtarea.scrollTop;
  var strPos = 0;
  var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
    "ff" : (document.selection ? "ie" : false));
  if (br == "ie") {
    txtarea.focus();
    var range = document.selection.createRange();
    range.moveStart('character', -txtarea.value.length);
    strPos = range.text.length;
  } else if (br == "ff") {
    strPos = txtarea.selectionStart;
  }

  var front = (txtarea.value).substring(0, strPos);
  var back = (txtarea.value).substring(strPos, txtarea.value.length);
  txtarea.value = front + text + back;
  strPos = strPos + text.length;
  if (br == "ie") {
    txtarea.focus();
    var ieRange = document.selection.createRange();
    ieRange.moveStart('character', -txtarea.value.length);
    ieRange.moveStart('character', strPos);
    ieRange.moveEnd('character', 0);
    ieRange.select();
  } else if (br == "ff") {
    txtarea.selectionStart = strPos;
    txtarea.selectionEnd = strPos;
    txtarea.focus();
  }

  txtarea.scrollTop = scrollPos;
}

function getDaumZipcodeApi(zip, addr1, addr2){
	//alert(addr1);
	//$('body').append('<script src=\'http://dmaps.daum.net/map_js_init/postcode.v2.js\'><\/script>');
	//document.write("<script type='text/javascript' src='http://dmaps.daum.net/map_js_init/postcode.v2.js'><"+"/script>");
	//$.getScript("", function() {

	new daum.Postcode({
		oncomplete: function(data) {
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			//alert(data);

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			//alert(data.userSelectedType);
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}
			//alert(fullAddr);
			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.


			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			//alert(data.zonecode+'::::'+fullAddr)
			$('#'+zip).val(data.zonecode);
			$('#'+addr1).val(fullAddr);
			$('#'+addr2).focus();
		}
	}).open();
	//});
}

function setTemplatgeImgDel(path, code, control, val){
	if(confirm('이미지를 삭제하시겠습니까?n한번 삭제한 데이터는 복구 불가능합니다.\n계속 진행하시겠습니까?')){
		setAjaxPostSend(path, code, control, val);
	}
}

function in_array(needle, haystack, argStrict){
	var key = '',strict = !! argStrict;

	if(strict){
		for(key in haystack){
			if(haystack[key] === needle){
				return true;
			}
		}
	}else{
		for(key in haystack){
			if(haystack[key] == needle){
				return true;
			}
		}
	}
	return false;
}

// //알림 권한 요청
// function getNotificationPermission() {
// 	// 브라우저 지원 여부 체크
// 	if (!("Notification" in window)) {
// 		alert("데스크톱 알림을 지원하지 않는 브라우저입니다.");
// 	}
// 	// 데스크탑 알림 권한 요청
// 	Notification.requestPermission(function (result) {
// 		// 권한 거절
// 		if(result == 'denied') {
// 			alert('알림을 차단하셨습니다.\n브라우저의 사이트 설정에서 변경하실 수 있습니다.');
// 			return false;
// 		}
// 	});
// }
//
// function spawnNotification(theBody,theTitle) {
// 	var options = {
// 		body: theBody
// 	}
// 	var n = new Notification(theTitle,options);
// }
//
// Notification.requestPermission().then(function(result) {
// 	console.log(result);
// });