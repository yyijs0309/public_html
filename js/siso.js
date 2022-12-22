// 플레이스홀더
$(document).ready(function() {
	$('input, textarea').placeholder();
});


// a 태그 이동액션
$(function() {
	$('.go_ani').bind('click',function(event){
		var $anchor = $(this);

		$('html, body').animate({
			scrollTop: $($anchor.attr('href')).offset().top
		}, 500,'easeInOutExpo');

		event.preventDefault();
	});
});


// totop 실행스크립트
$(document).ready(function() {

			// var defaults = {
	  // 			containerID: 'toTop', // fading element id
			// 	containerHoverID: 'toTopHover', // fading element hover id
			// 	scrollSpeed: 1200,
			// 	easingType: 'linear'
	 	// 	};


	 	$().UItoTop({ easingType: 'easeOutQuart' });

	 });


// 팝업
// $(document).ready(function(){
// 	$(".pop_open_btn").bind('click',function(event){

// 		$(".siso_pop_wrap").fadeOut(10);
// 		$(".siso_pop").empty();
// 		var PopTarget = $(this).attr("pop-target");
// 		var PopTargetClass = "."+ PopTarget;
// 		var PopTargetHtml = "<div class='"+PopTarget+"'>" + $(PopTargetClass).html() + "</div>";
// 		// alert(PopTargetHtml);
// 		$(".siso_pop").append(PopTargetHtml);
// 		$(".siso_pop_wrap").fadeIn();
// 		var SisoH = $('.siso_pop').height() ;
// 		var SisoW = $('.siso_pop').width() ;
// 		$(".siso_pop_box").css( { "margin-top" : - SisoH/2 } );
// 		$(".siso_pop_box").css( { "margin-left" : - SisoW/2 } );
// 		event.preventDefault();
// 	});
// 	$(".siso_pop_bg").bind('click',function(event){
// 		$(".siso_pop_wrap").fadeOut();
// 		$(".siso_pop").empty();
// 		event.preventDefault();
// 	});
// 	$('.siso_pop_box').on('click', '.pop_open_btn', function() {
// 		$(".siso_pop_wrap").fadeOut(10);
// 		$(".siso_pop").empty();
// 		var PopTarget = $(this).attr("pop-target");
// 		var PopTargetClass = "."+ PopTarget;
// 		var PopTargetHtml = "<div class='"+PopTarget+"'>" + $(PopTargetClass).html() + "</div>";
// 		// alert(PopTargetHtml);
// 		$(".siso_pop").append(PopTargetHtml);
// 		$(".siso_pop_wrap").fadeIn();
// 		var SisoH = $('.siso_pop').height() ;
// 		var SisoW = $('.siso_pop').width() ;
// 		$(".siso_pop_box").css( { "margin-top" : - SisoH/2 } );
// 		$(".siso_pop_box").css( { "margin-left" : - SisoW/2 } );
// 		event.preventDefault();
// 	});
// });

$(document).ready(function(){
	$(document).on("click", ".pop_open_btn", function() {
		$("#wrap").addClass("siso_popup");
		$("#toTop").addClass("top_none");
		$("html").css( { "height" : "100%","overflow":"hidden" }  );
		$(".siso_pop_wrap").fadeOut(10);
		$(".siso_pop").empty();
		var PopTarget = $(this).attr("pop-target");
		var PopTargetClass = "."+ PopTarget;
		var PopTargetHtml = "<div class='"+PopTarget+"'>" + $(PopTargetClass).html() + "</div>";
		// alert(PopTargetHtml);
		$(".siso_pop").append(PopTargetHtml);
		$(".siso_pop_wrap").fadeIn();
		var SisoW = $('.siso_pop').width() ;
		$(".siso_pop_box").css( { "margin-left" : - SisoW/2 } );
		event.preventDefault();
	});
	$(document).on("click", ".siso_pop_bg", function() {
		$("#wrap").removeClass("siso_popup");
		$("#toTop").removeClass("top_none");
		$("html").css( { "height" : "auto","overflow":"auto" }  );
		$(".siso_pop_wrap").fadeOut();
		$(".siso_pop").empty();
		event.preventDefault();
	});
	$(document).on("click", ".siso_pop_close", function() {
		$("#wrap").removeClass("siso_popup");
		$("#toTop").removeClass("top_none");
		$("html").css( { "height" : "auto","overflow":"auto" }  );
		$(".siso_pop_wrap").fadeOut('fast');
		$(".siso_pop").empty();
		event.preventDefault();
	});
});

$(document).ready(function(){
	$('.pc_wrap .con_01_right_02').css({"opacity":"0","top":"-20px"});
	$('.pc_wrap .con_01_left_01').css({"opacity":"0","left":"10px"});
	$('.pc_wrap .con_01_left_02').css({"opacity":"0","left":"10px"});
	$('.pc_wrap .con_02_right_01').css({"opacity":"0" });
	$('.pc_wrap .con_02_left_01').css({"opacity":"0","left":"10px"});
	$('.pc_wrap .con_02_left_02').css({"opacity":"0","left":"10px"});
	$('.seokfadeInUp').css({"opacity":"0","top":"100px"});
	$('.seokfadeInDown').css({"opacity":"0","top":"-100px"});
	$('.seokfadeInRight').css({"opacity":"0","left":"-100px"});
	$('.seokfadeInLeft').css({"opacity":"0","left":"100px"});
	$('.seokfadeIn').css({"opacity":"0"});
});

// 애니효과
$(window).load(function(){
	
});
$(document).ready(function() {
	$('.pc_wrap .con_01_right_02').each( function(i){
		var seok_object2 = $(this).offset().top + $(this).outerHeight();
		var seok_window2 = $(window).scrollTop() + $(window).height();
		if( seok_window2 > seok_object2 ){
			$(this).delay(300).animate({'opacity':'1','top':'-68px'},700);
		}
	});
	$('.pc_wrap .con_01_left_01').each( function(i){
		var seok_object3 = $(this).offset().top + $(this).outerHeight();
		var seok_window3 = $(window).scrollTop() + $(window).height();
		if( seok_window3 > seok_object3 ){
			$(this).delay(0).animate({'opacity':'1','left':'0px'},500);
		}
	});
	$('.pc_wrap .con_01_left_02').each( function(i){
		var seok_object4 = $(this).offset().top + $(this).outerHeight();
		var seok_window4 = $(window).scrollTop() + $(window).height();
		if( seok_window4 > seok_object4 ){
			$(this).delay(300).animate({'opacity':'1','left':'0px'},500);
		}
	});
	// $('.pc_wrap .con_02_right_02').each( function(i){
	// 	var seok_object6 = $(this).offset().top + $(this).outerHeight();
	// 	var seok_window6 = $(window).scrollTop() + $(window).height();
	// 	if( seok_window6 > seok_object6 ){
	// 		$(this).delay(0).animate({'opacity':'1','top':'0px'},700);
	// 	}
	// });
	$('.pc_wrap .con_02_left_01').each( function(i){
		var seok_object7 = $(this).offset().top + $(this).outerHeight();
		var seok_window7 = $(window).scrollTop() + $(window).height();
		if( seok_window7 > seok_object7 ){
			$(this).delay(0).animate({'opacity':'1','left':'0px'},500);
		}
	});
	$('.pc_wrap .con_02_left_02').each( function(i){
		var seok_object8 = $(this).offset().top + $(this).outerHeight();
		var seok_window8 = $(window).scrollTop() + $(window).height();
		if( seok_window8 > seok_object8 ){
			$(this).delay(300).animate({'opacity':'1','left':'0px'},500);
		}
	});
	$('.seokfadeInUp').each( function(i){
		var seok_object18 = $(this).offset().top + $(this).outerHeight();
		var seok_window18 = $(window).scrollTop() + $(window).height();
		if( seok_window18 > seok_object18 ){
			$(this).animate({'opacity':'1','top':'0px'},800);
		}
	});
	$('.seokfadeInDown').each( function(i){
		var seok_object19 = $(this).offset().top + $(this).outerHeight();
		var seok_window19 = $(window).scrollTop() + $(window).height();
		if( seok_window19 > seok_object19 ){
			$(this).animate({'opacity':'1','top':'0px'},800);
		}
	});
	$('.seokfadeInRight').each( function(i){
		var seok_object20 = $(this).offset().top + $(this).outerHeight();
		var seok_window20 = $(window).scrollTop() + $(window).height();
		if( seok_window20 > seok_object20 ){
			$(this).animate({'opacity':'1','left':'0px'},800);
		}
	});
	$('.seokfadeInLeft').each( function(i){
		var seok_object21 = $(this).offset().top + $(this).outerHeight();
		var seok_window21 = $(window).scrollTop() + $(window).height();
		if( seok_window21 > seok_object21 ){
			$(this).animate({'opacity':'1','left':'0px'},800);
		}
	});
	$('.seokfadeIn').each( function(i){
		var seok_object22 = $(this).offset().top + $(this).outerHeight();
		var seok_window22 = $(window).scrollTop() + $(window).height();
		if( seok_window22 > seok_object22 ){
			$(this).animate({'opacity':'1'},800);
		}
	});
	
});
$(window).scroll( function(){

	$('.pc_wrap .con_01_right_02').each( function(i){
		var seok_object2 = $(this).offset().top + $(this).outerHeight();
		var seok_window2 = $(window).scrollTop() + $(window).height();
		if( seok_window2 > seok_object2 ){
			$(this).delay(300).animate({'opacity':'1','top':'-68px'},700);
		}
	});
	$('.pc_wrap .con_01_left_01').each( function(i){
		var seok_object3 = $(this).offset().top + $(this).outerHeight();
		var seok_window3 = $(window).scrollTop() + $(window).height();
		if( seok_window3 > seok_object3 ){
			$(this).delay(0).animate({'opacity':'1','left':'0px'},500);
		}
	});
	$('.pc_wrap .con_01_left_02').each( function(i){
		var seok_object4 = $(this).offset().top + $(this).outerHeight();
		var seok_window4 = $(window).scrollTop() + $(window).height();
		if( seok_window4 > seok_object4 ){
			$(this).delay(300).animate({'opacity':'1','left':'0px'},500);
		}
	});

	// $('.pc_wrap .con_02_right_02').each( function(i){
	// 	var seok_object6 = $(this).offset().top + $(this).outerHeight();
	// 	var seok_window6 = $(window).scrollTop() + $(window).height();
	// 	if( seok_window6 > seok_object6 ){
	// 		$(this).delay(0).animate({'opacity':'1','top':'0px'},700);
	// 	}
	// });
	$('.pc_wrap .con_02_left_01').each( function(i){
		var seok_object7 = $(this).offset().top + $(this).outerHeight();
		var seok_window7 = $(window).scrollTop() + $(window).height();
		if( seok_window7 > seok_object7 ){
			$(this).delay(0).animate({'opacity':'1','left':'0px'},500);
		}
	});
	$('.pc_wrap .con_02_left_02').each( function(i){
		var seok_object8 = $(this).offset().top + $(this).outerHeight();
		var seok_window8 = $(window).scrollTop() + $(window).height();
		if( seok_window8 > seok_object8 ){
			$(this).delay(300).animate({'opacity':'1','left':'0px'},500);
		}
	});
	$('.seokfadeInUp').each( function(i){
		var seok_object18 = $(this).offset().top + $(this).outerHeight();
		var seok_window18 = $(window).scrollTop() + $(window).height();
		if( seok_window18 > seok_object18 ){
			$(this).animate({'opacity':'1','top':'0px'},800);
		}
	});
	$('.seokfadeInDown').each( function(i){
		var seok_object19 = $(this).offset().top + $(this).outerHeight();
		var seok_window19 = $(window).scrollTop() + $(window).height();
		if( seok_window19 > seok_object19 ){
			$(this).animate({'opacity':'1','top':'0px'},800);
		}
	});
	$('.seokfadeInRight').each( function(i){
		var seok_object20 = $(this).offset().top + $(this).outerHeight();
		var seok_window20 = $(window).scrollTop() + $(window).height();
		if( seok_window20 > seok_object20 ){
			$(this).animate({'opacity':'1','left':'0px'},800);
		}
	});
	$('.seokfadeInLeft').each( function(i){
		var seok_object21 = $(this).offset().top + $(this).outerHeight();
		var seok_window21 = $(window).scrollTop() + $(window).height();
		if( seok_window21 > seok_object21 ){
			$(this).animate({'opacity':'1','left':'0px'},800);
		}
	});
	$('.seokfadeIn').each( function(i){
		var seok_object22 = $(this).offset().top + $(this).outerHeight();
		var seok_window22 = $(window).scrollTop() + $(window).height();
		if( seok_window22 > seok_object22 ){
			$(this).animate({'opacity':'1'},800);
		}
	});
});