$(document).ready(function () {
    $(".menu-item").hide();


    $("#menuDropdown > li").click(function () {
        $(".menu-item").hide();
        $("." + $(this).attr("id")).show();
    });

//    $(".list-group-item").click(function () {
//        alert("click");
//        $(this).prepend($(this));
//    });

    $('.list-group-item').click(function () {
        $(this).prependTo($.tester);
    })
});