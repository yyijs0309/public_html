/* =Main INIT Functions
-------------------------------------------------------------- */
function initializeVisia() {

	"use strict";

	//IE9 RECOGNITION
	if (jQuery.browser.msie && jQuery.browser.version == 9) {

		jQuery('html').addClass('ie9');
	}

	//NAVIGATION CUSTOM FUNCTION
	jQuery(document).aetherNavigation();

	//LOCAL SCROLL
	jQuery('.navigation, .call-to-action').localScroll({
		offset: -50
	});

	jQuery("#top").click(function () {

		if ( window.location.href == _visia.home_url ) {

			return jQuery("body,html").stop().animate({
				scrollTop: 0
			}, 800, "easeOutCubic"), !1;

		}


	});

	//RESPONSIVE HEADINGS
	jQuery("h6").fitText(1.8, { minFontSize: '30px', maxFontSize: '52px' });
	jQuery("h2").fitText(1.5, { minFontSize: '20px', maxFontSize: '36px' });
	jQuery("h5").fitText(2.3, { minFontSize: '10px', maxFontSize: '16px' });
	jQuery(".data_txt").fitText(2.9, { minFontSize: '10px', maxFontSize: '16px' });
	//jQuery("#map li").fitText(2.5, { minFontSize: '10px', maxFontSize: '16px' });


	//HERO DIMENSTION AND CENTER
	(function() {
	    function heroInit(){
	       var hero = jQuery('.hero'),
				ww = jQuery(window).width(),
				wh = jQuery(window).height(),
				heroHeight = wh;

			hero.css({
				height: heroHeight+"px",
			});

			var heroContent = jQuery('.hero .content'),
				contentHeight = heroContent.height(),
				parentHeight = hero.height(),
				topMargin = (parentHeight - contentHeight) / 2;

			heroContent.css({
				"margin-top" : topMargin+"px"
			});
	    }

	    jQuery(window).on("resize", heroInit);
	    jQuery(document).on("ready", heroInit);
	})();

	//HERO TICKER
	var current = 1; 
	var height = jQuery('.ticker').height(); 
	var numberDivs = jQuery('.ticker').children().length; 
	var first = jQuery('.ticker h1:nth-child(1)'); 
	
	//SERVICES TOOLTIP
	(function() {
		function tooltipInit(){
			var tooltip = jQuery('.tooltip'),
				target = jQuery('.feature-icon'),
				arrow = jQuery ('.arrow-down'),
				mobile = jQuery(window).width() < 960,
				desktop = jQuery(window).width() > 960

			if (mobile) {

				jQuery( ".overview:odd" ).addClass('pull-left');

				target.click(function(){
					target.css({ "background-position": "top" });
					jQuery(this).css({ "background-position": "bottom" });

					tooltip.removeClass('visible'); arrow.removeClass('visible');
					jQuery(this).siblings('.tooltip, .arrow-down').addClass('visible');
				});

				tooltip.click(function(){
					jQuery(this).removeClass('visible');
					jQuery(this).siblings('.arrow-down').removeClass('visible');
					jQuery(this).siblings('.icon').css({
						"background-position": "top"
					});
				});

				target.unbind('mouseenter');
				target.unbind('mouseleave');
			}

			if (desktop) {
				jQuery('.pull-left').removeClass('pull-left');
				target.css({"background-position" : "top"})
				tooltip.removeClass('visible');
				arrow.removeClass('visible');
				target.bind('mouseenter', function(){
					jQuery(this).siblings('.tooltip, .arrow-down').addClass('visible');
					jQuery(this).css({"background-position" : "bottom"});

					var removeTooltip = function(){ tooltip.removeClass('visible'); arrow.removeClass('visible'); };
					target.bind( 'mouseleave', removeTooltip );
					target.bind( 'mouseleave', function(){
						jQuery(this).css({"background-position" : "top"});
					});
				});
			}

		}

		jQuery(window).on("resize", tooltipInit);
	    jQuery(document).on("ready", tooltipInit);

	})();

	//ANIMATIONS
	
	//QUOTES
	
	//CONTACT-FORM
	jQuery('#contact-open').click(function (e) {
		e.preventDefault();
		if ( jQuery('#contact-form').is(':hidden') ) {
			jQuery('#contact-form').show();
			jQuery('html, body').animate({ 
				scrollTop: jQuery('#contact-form').offset().top 
			}, 1000);
			/*
			jQuery('#contact-form').slideDown(200,function () {
				console.log(jQuery('#contact-form').offset().top);
				return;
				jQuery('html, body').animate({ 
					scrollTop: jQuery('#contact-form').offset().top 
				}, 1000);
			});
			 */
			
		} else {
			jQuery('#contact-form').slideUp();
		}
	});

	//PARALLAX EFFECTS
	
	//PORTFOLIO FILTER
	/*
	jQuery(function(){
		jQuery('.projectlist').each( function() {

			var $this = jQuery( this );

			$this.mixitup({
				effects        : ['fade','scale','rotateX'],
				easing         : 'snap',
				filterSelector : $this.closest( 'section') .find( '.filter' )
			});

		});
	});
	*/

	//RESPONSIVE VIDEO
	
	
	
	
	// test this
	

	//FULLSCREEN SLIDER CONTROLS
	jQuery('#vegas-next').click(function(){
		jQuery.vegas('next');

		return false;
	});
	jQuery('#vegas-prev').click(function(){
		jQuery.vegas('previous');

		return false;
	});

	//FULLSCREEN VIDEO
	jQuery(function() {
		if ( jQuery( '.hero-video' ).length === 0 ) return;
		
		var ua = navigator.userAgent.toLowerCase();
		var iDev = ua.match(/(iphone|ipod|ipad)/) !== null;
		var android = !iDev && ua.match(/android ([^;]+)/);
		var mobile = (iDev || android || ua.match(/(android|blackberry|webOS|opera mobi)/) !== null) || jQuery(window).width() < 960;


		var videomp4 = jQuery( '.hero-video' ).data( 'video' ),
			videoogv = videomp4.replace( '.mp4', '.ogv' ),
	  mobilejpg = videomp4.replace( '.mp4', '.jpg' );
		
	    var BV = new jQuery.BigVideo({useFlashForFirefox:false});
	    if ( mobile ) {
			jQuery('.hero-video').css("background","url('"+mobilejpg+"')");
		} else {
			BV.init();
	    	BV.show(videomp4,{altSource:videoogv, ambient:true});
		}
	});
};

function initializePortfolio() {

	"use strict";

	var current,
		next, 
		prev,
		target, 
		hash,
		url,
		page,
		title,	  	  	  
		projectIndex,
		scrollPostition,
		projectLength,
		ajaxLoading = false,
		wrapperHeight,
		pageRefresh = true,
		content =false,
		correction = 30,
		headerH = jQuery('.logo').height()+correction,
		portfolioGrid = jQuery('.projectlist'),
		easing = 'easeOutExpo',
		folderName ='projects';

		jQuery('.project-navigation ul').hide();
		jQuery('.closeProject a').hide();

	jQuery('.projectlist').on( 'click', '.project a', function(e) {

		if ( jQuery( this ).closest( '.project' ).hasClass( 'doajax' ) ) {
			window.location.hash = ("!/" + jQuery(this).attr("data-slug"));
			e.preventDefault();

			portfolioGrid = jQuery( this ).closest( '.projectlist' );
			url = jQuery( this ).attr( 'href' );

			portfolioGrid.find('.project.current').children().removeClass('active');
			portfolioGrid.find('.project.current').removeClass('current');
			jQuery('.portfolio').find('.projectlist.active-folio').removeClass('active-folio');
			jQuery('.portfolio').find('.ajax-content.active-ajax, .project-navigation.active-ajax, .closeProject.active-ajax, .loader.active-ajax').removeClass('active-ajax');

			portfolioGrid.find('.project a[href="' + url + '"]' ).parent().addClass( 'current' );
		 	portfolioGrid.find('.project.current').find('a[href="' + url + '"]').addClass('active');

		 	portfolioGrid.find('.project a[href="' + url + '"]' ).parents('.projectlist').addClass( 'active-folio' );
		 	jQuery('.active-folio').siblings('.ajax-section').children('.ajax-content, .project-navigation, .closeProject, .loader').addClass('active-ajax');

		 	var projectContainer = jQuery('.ajax-content.active-ajax');
		 	var loader = jQuery('.loader.active-ajax');
		 	var projectNav = jQuery('.project-navigation.active-ajax ul');
		 	var exitProject = jQuery('.closeProject.active-ajax a');
			
			var done = false;
			jQuery('html,body').stop().animate({scrollTop: (projectContainer.offset().top-headerH)+'px'},800,'easeOutExpo', function(){
			if (done) {
				return;
			}
			done = true;
																																
			if(content == false){						
				loadProject();							
			}else{	
				projectContainer.animate({opacity:0,height:wrapperHeight},function(){
					loadProject();
				});
			}
					
			projectNav.fadeOut('100');
			exitProject.fadeOut('100');
					
			});
		}

	});

	function loadProject(){
		var loader = jQuery('.loader.active-ajax');

		loader.fadeIn().removeClass('projectError').html('');


		if(!ajaxLoading) {				
			ajaxLoading = true;

			var projectContainer = jQuery('.ajax-content.active-ajax');

			projectContainer.load( url +' div#ajaxpage', function(xhr, statusText, request){

				if(statusText == "success"){				

				ajaxLoading = false;

				page = jQuery('#ajaxpage');
					
				page.find("img[data-original]:not(img.pe-lazyload-inited)").peLazyLoading();

				setTimeout( function() {

					page.find("img[data-original]:not(img.pe-lazyload-inited)").peLazyLoading();

					jQuery( window ).scroll();

				}, 1500 );

				jQuery('.accordion').each(function(){
					var acc = jQuery(this).attr("rel") * 2;
					jQuery(this).find('.accordion-inner:nth-child(' + acc + ')').show();
					jQuery(this).find('.accordion-inner:nth-child(' + acc + ')').prev().addClass("active");
				});

				jQuery('.accordion .accordion-title').click(function() {
					if(jQuery(this).next().is(':hidden')) {
						jQuery(this).parent().find('.accordion-title').removeClass('active').next().slideUp(200);
						jQuery(this).toggleClass('active').next().slideDown(200);
					}
				return false;
				});

				jQuery( '.tabs' ).not( '.tabs-init' ).each( function() {

					var $this = jQuery( this );

					$this.addClass( '.tabs-init' );

					$this
						.find( 'div' )
							.hide()
							.end()
						.find( 'div:first' )
							.show()
							.end()
						.find( 'ul li:first' )
							.addClass('active');

					$this
						.find( 'ul li a' )
							.click( function(){

								var $_this = jQuery( this );

								$this.find( 'ul li' ).removeClass('active');

								$_this.parent().addClass('active');

								var currentTab = $_this.attr('href');

								$this.find( 'div' ).hide();

								jQuery( currentTab ).show();

								return false;

							});

				});
					
				jQuery('.slider').bxSlider({
					mode: 'horizontal',
					touchEnabled: true,
					swipeThreshold: 50,
					oneToOneTouch: true,
					pagerSelector: '.slider-pager',
					nextSelector: ".project-gallery-next",
					prevSelector: ".project-gallery-prev",
					nextText: jQuery( '.project-gallery-next a' ).text(),
					prevText: jQuery( '.project-gallery-prev a' ).text(),
					tickerHover: true
				});
					
				// test this
				page.find("li.bx-clone img[src^='data:image']").addClass("pe-lazyloading-forceload").peLazyLoading();
				jQuery(window).trigger("pe-lazyloading-refresh");

				jQuery('#ajaxpage').waitForImages(function() {
					hideLoader();  
				});

				jQuery(".container").fitVids();

				}

			});

		}
			
	}

	function hideLoader(){
		var loader = jQuery('.loader.active-ajax');

		loader.delay(400).fadeOut( function(){
					showProject();					
			});			 
	}

	function showProject(){

		var projectContainer = jQuery('.ajax-content.active-ajax');
		var projectNav = jQuery('.project-navigation.active-ajax ul');
		var exitProject = jQuery('.closeProject.active-ajax a');

		wrapperHeight = projectContainer.children('#ajaxpage').outerHeight()+'px';
		
		if(content==false){

			wrapperHeight = projectContainer.children('#ajaxpage').outerHeight()+'px';

			projectContainer.animate({opacity:1,height:wrapperHeight}, function(){
				jQuery(".container").fitVids();
				scrollPostition = jQuery('html,body').scrollTop();
				projectNav.fadeIn();
				exitProject.fadeIn();
				content = true;	
				
			});

		} else {
			wrapperHeight = projectContainer.children('#ajaxpage').outerHeight()+'px';

			projectContainer.animate({opacity:1,height:wrapperHeight}, function(){
				jQuery(".container").fitVids();																		  
				scrollPostition = jQuery('html,body').scrollTop();
				projectNav.fadeIn();
				exitProject.fadeIn();

			});					
		}


		projectIndex = portfolioGrid.find('.project.current').index();
		projectLength = jQuery('.project a').length-1;


		if(projectIndex == projectLength){

			jQuery('.nextProject a').addClass('disabled');
			jQuery('.prevProject a').removeClass('disabled');

		} else if(projectIndex == 0) {

			jQuery('.prevProject a').addClass('disabled');
			jQuery('.nextProject a').removeClass('disabled');

		} else {

			jQuery('.nextProject a, .prevProject a').removeClass('disabled');

		}

		setTimeout( function() {

			page.find("img[data-original]:not(img.pe-lazyload-inited)").peLazyLoading();

			jQuery( window ).scroll();

		}, 1500 );
	
  	}

  	function deleteProject(closeURL){

  		var projectContainer = jQuery('.ajax-content.active-ajax');
  		var projectNav = jQuery('.project-navigation.active-ajax ul');
  		var exitProject = jQuery('.closeProject.active-ajax a');

		projectNav.fadeOut();
		exitProject.fadeOut();

		if(typeof closeURL!='undefined' && closeURL!='') {
			window.location.hash = '#_';
		}

		projectContainer.animate({opacity:0,height:'0px'},800,'easeOutExpo');
		projectContainer.empty();
		jQuery('html,body').stop().animate({
			scrollTop: (projectContainer.offset().top-headerH-100)+'px'},600
		);

		jQuery('.portfolio').find('.projectlist.active-folio').removeClass('active-folio');
		jQuery('.portfolio').find('.ajax-content.active-ajax, .project-navigation.active-ajax, .closeProject.active-ajax').removeClass('active-ajax');
		portfolioGrid.find('.project.current').children().removeClass('active');
		portfolioGrid.find('.project.current').removeClass('current');			
 	}

 	jQuery('.nextProject a').on('click',function (e) {

 		e.preventDefault();

		current = portfolioGrid.find('.project.current');
		next = current.nextAll('.project.doajax').first();

		target = jQuery(next).children('a');
		

		if (next.length === 0) { 
			return false;			  
		} 

		current.removeClass('current'); 
		current.children().removeClass('active');
		next.addClass('current');
		next.children().addClass('active');

		jQuery(target).click();
	   
	});

	jQuery('.prevProject a').on('click',function (e) {

		e.preventDefault();

		current = portfolioGrid.find('.project.current');
		prev = current.prevAll('.project.doajax').first();

		target = jQuery(prev).children('a');
		

		if (prev.length === 0) { 
			return false;			  
		} 

		current.removeClass('current'); 
		current.children().removeClass('active');
		prev.addClass('current');
		prev.children().addClass('active');

		jQuery(target).click();

	});

	jQuery('.closeProject a').on('click',function () {

		var loader = jQuery('.loader.active-ajax'); 
							
		deleteProject(jQuery(this).attr('href'));					
		
		portfolioGrid.find('.project.current').children().removeClass('active');			
		loader.fadeOut();

		return false;
	});

	pageRefresh = false;
};
/* END ------------------------------------------------------- */


/* =Window Load Trigger
-------------------------------------------------------------- */
jQuery(window).load(function(){

	jQuery(window).trigger( 'hashchange' );
	jQuery(window).trigger( 'resize' );
	var p = window.location.hash.match(/\/(.+)/);
	if (p && p[1]) {
		jQuery("a[data-slug='"+p[1]+"']").trigger("click");
	}
  	jQuery('[data-spy="scroll"]').each(function () {
    	var $spy = $(this).scrollspy('refresh');
	});

});
/* END ------------------------------------------------------- */


/* =Document Ready Trigger
-------------------------------------------------------------- */
jQuery(document).ready(function(){

	initializeVisia();
	initializePortfolio();

});
/* END ------------------------------------------------------- */