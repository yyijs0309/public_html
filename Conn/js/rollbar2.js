(function($) {

 var shopeFfect = function(element, options){
   var settings = $.extend({}, $.fn.shopeffect.defaults, options); //초반 셋팅값 가져오기
     var vars = {
            currentSlide: 0,
            currentImage: '',
            totalSlides: 0,
            randAnim: '',
			currentchange : 'prev',
            running: false,
            paused: false,
            stop: false
        };

   var slider = $(element);		
   var kids = slider.children();

     var timer = 0;
         timer = setInterval(function(){ nivoRun(slider, kids, settings, false); }, settings.pauseTime);

 slider.bind('nivo:animFinished', function(){ 
            vars.running = false; 
            //Hide child links
		if(vars.currentchange == 'prev'){
             slider.find('.mitem').css({left: '0px'});
			 slider.find('.mitem').find('li').eq(0).remove();
		}else{
		
			 slider.find('.mitem').find('li:last').remove();
		}
            //Trigger the afterChange callback
            settings.afterChange.call(this);
        }); 

  if(settings.pauseOnHover){
            slider.hover(function(){
                vars.paused = true;
                clearInterval(timer);
                timer = '';
            }, function(){
                vars.paused = false;
                //Restart the timer
                if(timer == '' && !settings.manualAdvance){
                    timer = setInterval(function(){ nivoRun(slider, kids, settings, vars.currentchange, false); }, settings.pauseTime);
                }
            });
    }
    $('.pre', slider).live('click', function(){
                if(vars.running) return false;
                clearInterval(timer);
                timer = '';
				vars.currentchange = 'prev';
                nivoRun(slider, kids, settings, vars.currentchange, false);
     });
	 $('.next', slider).live('click', function(){
                if(vars.running) return false;
                clearInterval(timer);
                timer = '';
				vars.currentchange = 'next';
                nivoRun(slider, kids, settings, vars.currentchange , false);
     });
	 $('li', slider).live('mouseover',function(){
		  $(this).find("img").attr("src",$(this).find("img").attr("over"));
	 });
      $('li', slider).live('mouseout',function(){
		  $(this).find("img").attr("src",$(this).find("img").attr("out"));
	 });
	  $('.btn', slider).hover(function(){$(this).attr("src",$(this).attr("on"));},function(){$(this).attr("src",$(this).attr("off"));});
	var nivoRun = function(slider, kids, settings, change, nudge){
		    if(!change){ change='prev'; }
             vars.running = true;
			 if(change == "prev"){
					 slider.find('.mitem').append("<li>" + slider.find('.mitem').find('li:first').html() + "</li>");	
					 slider.find('.mitem').animate({left: - settings.SlideMoveWidth +"px" },settings.animSpeed, function(){  slider.trigger('nivo:animFinished'); 	});		
			 }else{
					 slider.find('.mitem').prepend("<li>" + slider.find('.mitem').find('li:last').html() + "</li>");	
					// slider.find('.mitem').css({left : - settings.SlideMoveWidth}).animate({left: + 0 +"px" },settings.animSpeed).find('li:first').css({width : 0 + 'px'}).animate({ width: settings.SlideMoveWidth + 'px' },settings.animSpeed, function(){  slider.trigger('nivo:animFinished'); 	})
				slider.find('.mitem').css({left : - settings.SlideMoveWidth}).animate({ left: + 0 +"px" },settings.animSpeed, function(){  slider.trigger('nivo:animFinished'); 	})

					 

			 }


    }

   settings.afterLoad.call(this);
	return this;
	 };

	
	
 $.fn.shopeffect = function(options) {
    //데이터 로딩셋팅
        return this.each(function(key, value){
            var element = $(this);			
			shopeFfect($(element), options);
        });

	};

//Default settings
	$.fn.shopeffect.defaults = {
		animSpeed: 1000, //이벤트 속도
		pauseTime: 3000, //대기시간
		startSlide: 0,
		SlideMoveWidth: 240, //이동길이
		pauseOnHover: true,
		beforeChange: function(){},
		afterChange: function(){},
		slideshowEnd: function(){},
        lastSlide: function(){},
        afterLoad: function(){}
	};
	
	$.fn._reverse = [].reverse;

})(jQuery);