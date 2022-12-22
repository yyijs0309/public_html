// JavaScript Document


// 패밀리사이트
$(document).ready(function() {
			var cnt = 1;
			$('#family_site> ul').animate({
				top: 150
			},0);
            $('#family_site > button').click(function() {
               if(cnt == 1){
					$('#family_site> ul').animate({
						top: 1
					}, 'fast');
					cnt = 0;	
				}else{
					$('#family_site> ul').animate({
						top: 150
					}, 'fast');
					cnt = 1;		
				};
            });
			
			$('#family_site').focusin(function() {
                $('#family_site> ul').animate({
					top: 1
				}, 'fast');
            });
			
			$('#family_site > ul > li:last').focusout(function() {
                $('#family_site> ul').animate({
					top: 150
				}, 'fast');
            });
        });
		