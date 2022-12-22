$(document).ready(function(){
	//$('select').select2();
	/*
	$('input[type="text"][calendartimeviewis]').datetimepicker({
		defaultDate: new Date()
		, format: 'YYYY-MM-DD HH:ss'
		, sideBySide: true
		, locale : 'ko'
	});


	$('input[type="text"][calendartimeOnlyviewis]').datetimepicker({
		defaultDate: new Date()
		, format: 'HH:ss'
		, sideBySide: true
		, locale : 'ko'
	});

	$('input[type="text"][calendarviewis]').datepicker({
	
		language : 'ko'
		, pickTime: false
		, defalutDate : new Date()
		, format: 'yyyy-mm-dd'
		, autoclose: true
	
	
	});
	*/

	$('input[fucus]').live("keyup", function(e){
		e.preventDefault();
		var key;

		//alert('w');
		//alert(window.event);
		if(e.which){
			key = e.which;
		}else{
			key = window.e.keyCode;
		}
		/*
		if(window.event){ 
			key = window.e.keyCode;
			alert(window.e.keyCode);
		}else{
			key = e.which;
		}
		*/
		//alert(key);
		if(key==8)	return;
		if(key==13)	return;
		if(key==32)	return;	//space
		
		if(key==16)	return;
		if(key==35)	return;
		if(key==36)	return;

		if(key==37)	return;
		if(key==38)	return;
		if(key==39)	return;
		if(key==40)	return;

		if(key==46)	return;
		
		if(key==116)	return;

		//alert($(this).attr("nextinput"));
		$(this).val( $(this).val().replace(/[^0-9]/gi,"") );
		var charLimit = $(this).attr("maxlength");
		if($(this).val().length >= charLimit){
			$($('#'+$(this).attr("nextinput"))).focus();
			return false;
		}
	});

	$("[type=tel][hpinput]").live("keyup", function() {
		//$(this).val(phone_format($(this).val())); 
		$(this).val(autoHypenPhone($(this).val())); 
	});
	
	$("[type=tel][dateinput]").live("keyup", function() {
		//alert('w');
		//$(this).val(phone_format($(this).val())); 
		$(this).val(autoDateformat($(this).val())); 
	});

	$("[type=tel][numberformat]").live("keyup", function() {
		//$(this).val(phone_format($(this).val())); 
		$(this).val(number_format($(this).val())); 
	});

	$("[type=tel][numberonly]").live("keyup", function() {
		//$(this).val(phone_format($(this).val())); 
		$(this).val(numberonly($(this).val())); 
	});
	
	$("input[maxlength]").on("keyup blur" , function(){
		var  str = $(this).val();
		var  limit = $(this).attr('maxlength');
		if(str.length> limit){
			$(this).val(str.substr (0, limit));
		}
	});


	//$("[type=text][idchecke]").on("keyup blur" , function(){
	// $("[type=text][idchecke]").on("blur" , function(){
	// 	var  str = $(this).val();
	// 	var chid	=	$(this).attr('id');
	// 	//alert(str);
	// 	var returnMsg;
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '/lib/db.confirm.siso',
	// 		data: {
	// 			'dbControl': 'userIDConfirm'
	// 			, 'USERID': str
	// 		},
	// 		cache: false,
	// 		async: false,
	// 		success: function(data){
	// 			var ojson = $.parseJSON(data);
	// 			if(ojson.result	==	'Y'){
	// 				//setIDX = text;
	// 				//document.location = '/detail.siso?CODE='+ojson.key;
	// 				returnMsg	=	'<div class="alert alert-info" role="alert">'+ojson.message+'</div>';
	// 				$('#idCheck').val('Y');
	// 			}else{
	// 				//alert(ojson.txt);
	// 				returnMsg	=	'<div class="alert alert-danger" role="alert">'+ojson.message+'</div>';
	// 				//$('#'+chid).val('').focus();
	// 				//$('#'+chid).focus();
	// 				$('#idCheck').val('N');
	// 			}
	// 		}
	// 	});
	// 	$('#returnMsgZone').html(returnMsg);
	// });
});