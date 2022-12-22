// JavaScript Document

$(document).ready(function() {
    var wd = 298; //li 한개의 넓이 = 298px
	var maxSize = 1788; //ul의 전체 width 1788px
	$( 'div.m_box1 > div.m_wrap > ul').width(maxSize);
	$( 'div.m_box1 > div.m_wrap > ul > li:last').prependTo( 'div.m_box1 > div.m_wrap > ul'); //미리 마지막li를 앞에 붙여넣기
	$( 'div.m_box1 > div.m_wrap > ul').css('margin-left',-wd);// margin-left값을 -298px로 가져다 놓기
	
	$('div.m_box1 > span.m_next').click(function(){//다음버튼 클릭시
		$( 'div.m_box1 > div.m_wrap > ul').animate({//ul의 전체가 애니메이션처리
			marginLeft : parseInt($( 'div.m_box1 > div.m_wrap > ul').css('margin-left'))-wd+'px'
			//margin-left를 li사이즈만큼 -처리
		},function(){
			$( 'div.m_box1 > div.m_wrap > ul > li:first').appendTo( 'div.m_box1 > div.m_wrap > ul');//첫번째li를 마지막으로 옮김
			$( 'div.m_box1 > div.m_wrap > ul').css('margin-left' ,-wd); //ul전체의 위치도 li넓이만큼 -처리
		});
		return false;
	});
	$('div.m_box1 > span.m_prev').click(function(){
		$( 'div.m_box1 > div.m_wrap > ul').animate({
			marginLeft : parseInt($( 'div.m_box1 > div.m_wrap > ul').css('margin-left'))+wd+'px'
		},function(){
			$( 'div.m_box1 > div.m_wrap > ul > li:last').prependTo( 'div.m_box1 > div.m_wrap > ul');
			$( 'div.m_box1 > div.m_wrap > ul').css('margin-left' , -wd)
		})
		return false;
	});
	
	$('div.m_box1 > div.m_wrap > ul > li').click(function(){
		document.location.href = "sub/port01.html";
	});
	
});



$(document).ready(function() {
    var wd = 298; //li 한개의 넓이 = 298px
	var maxSize = 1788; //ul의 전체 width 1788px
	$( 'div.m_box2 > div.m_wrap > ul').width(maxSize);
	$( 'div.m_box2 > div.m_wrap > ul > li:last').prependTo( 'div.m_box2 > div.m_wrap > ul'); //미리 마지막li를 앞에 붙여넣기
	$( 'div.m_box2 > div.m_wrap > ul').css('margin-left',-wd);// margin-left값을 -298px로 가져다 놓기
	
	$('div.m_box2 > span.m_next').click(function(){//다음버튼 클릭시
		$( 'div.m_box2 > div.m_wrap > ul').animate({//ul의 전체가 애니메이션처리
			marginLeft : parseInt($( 'div.m_box2 > div.m_wrap > ul').css('margin-left'))-wd+'px'
			//margin-left를 li사이즈만큼 -처리
		},function(){
			$( 'div.m_box2 > div.m_wrap > ul > li:first').appendTo( 'div.m_box2 > div.m_wrap > ul');//첫번째li를 마지막으로 옮김
			$( 'div.m_box2 > div.m_wrap > ul').css('margin-left' ,-wd); //ul전체의 위치도 li넓이만큼 -처리
		});
		return false;
	});
	$('div.m_box2 > span.m_prev').click(function(){
		$( 'div.m_box2 > div.m_wrap > ul').animate({
			marginLeft : parseInt($( 'div.m_box2 > div.m_wrap > ul').css('margin-left'))+wd+'px'
		},function(){
			$( 'div.m_box2 > div.m_wrap > ul > li:last').prependTo( 'div.m_box2 > div.m_wrap > ul');
			$( 'div.m_box2 > div.m_wrap > ul').css('margin-left' , -wd)
		})
		return false;
	});
	
	$('div.m_box2 > div.m_wrap > ul > li').click(function(){
		document.location.href = "sub/port03.html";
	});
	
});



$(document).ready(function() {
    var wd = 298; //li 한개의 넓이 = 298px
	var maxSize = 1788; //ul의 전체 width 1788px
	$( 'div.m_box3 > div.m_wrap > ul').width(maxSize);
	$( 'div.m_box3 > div.m_wrap > ul > li:last').prependTo( 'div.m_box3 > div.m_wrap > ul'); //미리 마지막li를 앞에 붙여넣기
	$( 'div.m_box3 > div.m_wrap > ul').css('margin-left',-wd);// margin-left값을 -298px로 가져다 놓기
	
	$('div.m_box3 > span.m_next').click(function(){//다음버튼 클릭시
		$( 'div.m_box3 > div.m_wrap > ul').animate({//ul의 전체가 애니메이션처리
			marginLeft : parseInt($( 'div.m_box3 > div.m_wrap > ul').css('margin-left'))-wd+'px'
			//margin-left를 li사이즈만큼 -처리
		},function(){
			$( 'div.m_box3 > div.m_wrap > ul > li:first').appendTo( 'div.m_box3 > div.m_wrap > ul');//첫번째li를 마지막으로 옮김
			$( 'div.m_box3 > div.m_wrap > ul').css('margin-left' ,-wd); //ul전체의 위치도 li넓이만큼 -처리
		});
		return false;
	});
	$('div.m_box3 > span.m_prev').click(function(){
		$( 'div.m_box3 > div.m_wrap > ul').animate({
			marginLeft : parseInt($( 'div.m_box3 > div.m_wrap > ul').css('margin-left'))+wd+'px'
		},function(){
			$( 'div.m_box3 > div.m_wrap > ul > li:last').prependTo( 'div.m_box3 > div.m_wrap > ul');
			$( 'div.m_box3 > div.m_wrap > ul').css('margin-left' , -wd)
		})
		return false;
	});
	
	$('div.m_box3 > div.m_wrap > ul > li').click(function(){
		document.location.href = "sub/port02.html";
	});
	
});


$(document).ready(function() {
    var wd = 298; //li 한개의 넓이 = 298px
	var maxSize = 1788; //ul의 전체 width 1788px
	$( 'div.m_box4 > div.m_wrap > ul').width(maxSize);
	$( 'div.m_box4 > div.m_wrap > ul > li:last').prependTo( 'div.m_box4 > div.m_wrap > ul'); //미리 마지막li를 앞에 붙여넣기
	$( 'div.m_box4 > div.m_wrap > ul').css('margin-left',-wd);// margin-left값을 -298px로 가져다 놓기
	
	$('div.m_box4 > span.m_next').click(function(){//다음버튼 클릭시
		$( 'div.m_box4 > div.m_wrap > ul').animate({//ul의 전체가 애니메이션처리
			marginLeft : parseInt($( 'div.m_box4 > div.m_wrap > ul').css('margin-left'))-wd+'px'
			//margin-left를 li사이즈만큼 -처리
		},function(){
			$( 'div.m_box4 > div.m_wrap > ul > li:first').appendTo( 'div.m_box4 > div.m_wrap > ul');//첫번째li를 마지막으로 옮김
			$( 'div.m_box4 > div.m_wrap > ul').css('margin-left' ,-wd); //ul전체의 위치도 li넓이만큼 -처리
		});
		return false;
	});
	$('div.m_box4 > span.m_prev').click(function(){
		$( 'div.m_box4 > div.m_wrap > ul').animate({
			marginLeft : parseInt($( 'div.m_box4 > div.m_wrap > ul').css('margin-left'))+wd+'px'
		},function(){
			$( 'div.m_box4 > div.m_wrap > ul > li:last').prependTo( 'div.m_box4 > div.m_wrap > ul');
			$( 'div.m_box4 > div.m_wrap > ul').css('margin-left' , -wd)
		})
		return false;
	});
	
	$('div.m_box4 > div.m_wrap > ul > li').click(function(){
		document.location.href = "sub/port04.html";
	});
	
});




