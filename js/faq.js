// JavaScript Document

$(document).ready(function() {
	$('dd').css('display','none');
	$('dl dt').click(function(){

		if($('+dd',this).css('display')=='none'){
			$('dd').slideUp('fast')
			$('+dd',this).slideDown('fast');
			$('div#content dl dt > a').removeClass('on');
			$(this).children('a').addClass('on');
			$('dl dt').css('font-weight','normal');
			$(this).css('font-weight','bold');
			
			
			$('div#content dl dt > span.icon > a').removeClass('on');
			$(this).find('>span.f_icon > a').addClass('on');
			
			
		}else{
			$('dd').slideUp('fast');
			$('div#content dl dt > a').removeClass('on');
			
			$('div#content dl dt > span.f_icon > a').removeClass('on');
			
		};
				
	});
});
