
$(document).ready(function() {
	var owl = $('.owl-carousel');
	owl.owlCarousel({
		items: 1,
		loop: true,
		margin: 0,
		autoplay: true,
		autoplayTimeout: 3000,
		autoplayHoverPause: false,
		lazyEffect : "fade"
	});
	$('.play').on('click', function() {
		owl.trigger('play.owl.autoplay', [1000])
	})
	$('.stop').on('click', function() {
		owl.trigger('stop.owl.autoplay')
	})
})
