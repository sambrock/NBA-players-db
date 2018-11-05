$(window).scroll(function() {
    if ($(document).scrollTop() > 50) {
        $("header, .logo, .logo-txt, nav").addClass('shrink');
    } else {
        $("header, .logo, .logo-txt ,nav").removeClass('shrink');
    }
});

//If form control is not selected, hide blank value from query string
$("#search-form").submit(function() {
    $("#search-form").find('.form-control').each(function() {
        var input = $(this);
        if (!input.val()) {
            input.prop('disabled', true);
        }
    });
});
