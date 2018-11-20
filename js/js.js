//Shrink header on scroll down
$(window).scroll(function() {
    if ($(document).scrollTop() > 50) {
        $("header, .logo, .logo-txt, nav").addClass('shrink');
    } else {
        $("header, .logo, .logo-txt ,nav").removeClass('shrink');
    }
});

//If form control is not selected, hide blank value from query string
$("#search-form").submit(function() {
    $('.form-control').each(function() {
        var input = $(this);
        if (!input.val()) {
            input.prop('disabled', true);
        }
    });
});

$( document ).ready(function() {
    //set height of results container to height of child absolute element
    $height = $(".results").height();
    $(".results-container").css("height", $height);
    if($(".player-lname").text().length > 11){
        $(".player-lname").css("font-size", "40");
    }
});
