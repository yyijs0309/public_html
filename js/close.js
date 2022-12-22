// JavaScript Document
		$(document).ready(function() {
			$('#popup').slideUp(0);
			$('#popup').slideDown('slow');
			
            function setCookie(name, value, expiredays) { //24시간을 계산해서 시간이 지날수록 숫자가 떨어지는 함수선언
				var todayDate = new Date();
				todayDate.setDate( todayDate.getDate() + expiredays );
				document.cookie = name + '=' + escape( value ) + '; path=/; expires=' + todayDate.toGMTString() + ';'
			};
			
			function closeWin() { //체크박스를 체크했을때 슬라이드업처리되는 함수선언
				if ( $('#chkbox').prop('checked') == true){
					setCookie( 'maindiv', 'done', 1 ); //24시간 계산 함수 실행	
				}
				$('div#popup').slideUp('fast');
			};
			
			cookiedata = document.cookie;
			if(cookiedata. indexOf('maindiv=done') < 0 ){ //하루가 지났다면 다시 보이고
				$('div#popup').show();
			}else{//아직 안지났으면 보이지 않음
				$('div#popup').hide();
			}
			
			$('div#popup a').click(function() {
				closeWin(); //클릭했을때 사라지는 함수 실행
			});
        });