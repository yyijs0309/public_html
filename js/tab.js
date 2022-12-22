// JavaScript Document

$(document).ready(function() {
    $('ul#tab > li:first > a').addClass('on'); //처음 시작시 탭버튼중 첫번째 파랑색이미지 처리
  $('div#tab_content > div:first').css('display','block'); //첫번째이미지박스 보이게 처리
  
  $('ul#tab > li').click(function() {
        $('ul#tab > li >a').removeClass('on'); //모든 탭버튼에 클래스 on 제거
    $(this).find('>a').addClass('on'); //클릭한 탭버튼만 클래스 on적용
    
    i = $(this).index(); // 클릭한 엘리먼트의 index번호를 변수에 할당
    
    $('div#tab_content > div').css('display', 'none'); //모든 컨텐츠 안의 div박스 안보이게 처리
    $('div#tab_content > div').eq(i).css('display', 'block'); //index번호에 맞는 div박스만 보이게 처리
    });
});