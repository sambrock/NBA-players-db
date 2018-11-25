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
$(".drop-down").click(function(e){
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
    e.stopPropagation();
})

//close drop downs if clicked outisde
$(window).click(function() {
    var header = $(".drop-down");
    var items = $(".drop-down-items");
    items.hide();
    items.removeClass("active");
    header.removeClass("active");
    $("#clear-team").remove();
    $("#clear-pos").remove();
});

//add value to php
$("#items-team ul li").on("click", function(e){
    var value = $(this).attr("data-value");
    $("#team-select").val(value);
    $("#team").text(value);
    close();
})

$("#items-position ul li").on("click", function(e){
    var value = $(this).attr("data-value");
    $("#position-select").val(value);
    $("#position").text(value);
    close();
})

//stops the drop down lists from closing when clicked inside
$(".drop-down-items").on("click", function(e){
    e.stopPropagation();
})

$("#drop-down").on("click", function(e){
    e.stopPropagation();
    e.preventDefault();
})

//add clear button to remove the currrent drop down filter
$("#team").click(function(){
    if($(this).text() != "Team" && $("#clear-team").length == 0){
        $(this).append("<span id='clear-team'>clear</span>");
        $("#clear-team").click(function(e){
            close();
            $("#team").text("Team");
            $("#team-select").val("");
            e.stopPropagation();
        })
    }
})

$("#position").click(function(){
    if($(this).text() != "Position" && $("#clear-pos").length == 0){
        $(this).append("<span id='clear-pos'>clear</span>");
        $("#clear-pos").click(function(e){
            close();
            $("#position").text("Position");
            $("#position-select").val("");
            e.stopPropagation();
        })
    }
})



