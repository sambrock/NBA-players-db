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

//Functions to open and close drop downs
function close(){
    var header = $(".drop-down");
    var items = $(".drop-down-items");
    items.hide();
    items.removeClass("active");
    header.removeClass("active");
}

//Drop down menus
$(".drop-down").click(function(){
    close();
    var header = $(this);
    var items = $(this).next();

    if(items.hasClass("active")){
        items.hide();
        items.removeClass("active");
        header.removeClass("active");
    }else{
        items.fadeIn(100);
        items.addClass("active");
        header.addClass("active");
    }
    event.stopPropagation();
})

//close drop downs if clicked outisde
$(window).click(function() {
    var header = $(".drop-down");
    var items = $(".drop-down-items");
    items.hide();
    items.removeClass("active");
    header.removeClass("active");
});

//add value to php
$("#items-team ul li").on("click", function(e){
    var value = $(this).attr("data-value");
    $("#team-select").val(value);
    $("#drop-down-team").text(value);
    close();
})

$("#items-position ul li").on("click", function(e){
    var value = $(this).attr("data-value");
    $("#position-select").val(value);
    $("#drop-down-position").text(value);
    close();
})

//stops the drop down lists from closing when clicked inside
$(".drop-down-items").on("click", function(e){
    e.stopPropagation();
})
