// Navigation slide down
// Get the headers position from the top of the page, plus its own height
var startY = 422;
$(window).scroll(function(){
     checkY();
});
function checkY(){
    if( $(window).scrollTop() > startY ){
        $('.navbar-slide').slideDown();
    }else{
        $('.navbar-slide').slideUp();
    }
}
// Do this on load just in case the user starts half way down the page
checkY();
// End navigation slide down