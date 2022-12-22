// JavaScript Document


// main_menu
$(document).ready(function() {
    //하위 네비게이션 처음부터 보이지 않게 처리
	$('nav#sub_nav').slideUp(0);
	
	//메인네비게이션에 마우스 올리면 보이게 처리
	$('div#main_nav > ul > li > a').mouseenter(function(){
		$('nav#sub_nav').slideDown(300);
	});
	
	//하위네비게이션에서 마우스가 떠나면 안보이게 처리
	$('div#sub_nav_wrap').mouseleave(function(){
		$('nav#sub_nav').slideUp(300);
	});
	
	$('div#sub_nav_wrap > ul:last > li:last').focusout(function() {
			$('nav#sub_nav').slideUp('fast');
		});
		
		//로고와 탑네이게이션에 마우스 올리면 보이지 않게 처리
		$('#header h1, nav#t_nav').mouseenter(function() {
			$('nav#sub_nav').slideUp('fast');
		});
});


//하위메뉴에 마우스오버시 메인메뉴의 색이 바뀌게 설정
	$('div#main_nav > ul > li').each(function(index, element){
		$(this).attr('data-index',index);
	});		
	$('div#sub_nav_wrap > ul').each(function(index, element){
		$(this).attr('data-index',index);
	}).mouseenter(function(){
		var index = $(this).attr('data-index');
		v_class(index);
	}).mouseleave(function(){
		$('nav#main_nav > ul > li').removeClass('active');
	}).focusin(function() {
        var index = $(this).attr('data-index');
		v_class(index);
    }).focusout(function() {
        $('nav#main_nav > ul > li').removeClass('active');
    });
	
	function v_class(index){
		$('nav#main_nav > ul > li[data-index='+index+']').addClass('active');
		$('nav#main_nav > ul > li[data-index!='+index+']').removeClass('active');
	};
	