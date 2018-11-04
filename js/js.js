$(window).scroll(function() {
    if ($(document).scrollTop() > 50) {
        $("header, .logo, .logo-txt, nav").addClass('shrink');
    } else {
        $("header, .logo, .logo-txt ,nav").removeClass('shrink');
    }
});

if($(".results-container").length){
    $(".search-container").addClass("shrink");
}else{
    $(".search-container").removeClass("shrink");
}
