;(function($){
	$.each(['show', 'hide'], function(i, ev) {
		var el = $.fn[ev];
		$.fn[ev] = function() {
			this.trigger(ev);
			return el.apply(this, arguments);
		};
	});

	$.fn.randText = function(options){
		var root = $(this);
		var targetText = root.text();

		var defaults = {
			interval : 50,
			delay : 0,
			duration : 500,
			shuffleText : 1,
			ignored : []
		}
		var opts = $.extend({}, defaults, options);

		var shuffle = function(array){
			var elementsRemaining = array.length, temp, randomIndex;
			while (elementsRemaining > 1){
				randomIndex = Math.floor(Math.random() * elementsRemaining--);
				if (randomIndex != elementsRemaining) {
					temp = array[elementsRemaining];
					array[elementsRemaining] = array[randomIndex];
					array[randomIndex] = temp;
				}
			}
			return array;
		}

		var array_temp=[];
		var txt="";
		var input="";
		var engType = /^[A-Za-z+]*$/;
		for(i=0; i <= targetText.length-1; i++){
			array_temp.push(i)
			if(targetText.substr(i,1)==" "){
				input="&nbsp;"
				txt+="<p>"+input+"</p>";
			}else if(targetText.substr(i,1)=="|" && opts.ignored.indexOf("|") == -1){
				input="<div></div>";
				txt+=input;
			}else if(targetText.substr(i,1)=="/" && opts.ignored.indexOf("/") == -1){
				input="<br>";
				txt+=input;
			}else if(engType.test(targetText.substr(i,1))!=true){
				input=targetText.substr(i,1);
				txt+="<p class='hangul'>"+input+"</p>";
			}else{
				input=targetText.substr(i,1);
				txt+="<p>"+input+"</p>";
			}
		}
		if(opts.shuffleText==1){
		var temp = shuffle(array_temp);
		}else{
		var temp = array_temp;
		}

		root.css({'opacity':'1'}).html(txt);
		$(root).css({ 'display' : 'block' }).children("p").css({ 'opacity' : '0'});
		$('p', root).each(function(e){
			$(this).stop().delay(temp[e] * opts.interval+opts.delay).animate({'opacity':'1'}, opts.duration, "linear");
		});
	};
}(jQuery));