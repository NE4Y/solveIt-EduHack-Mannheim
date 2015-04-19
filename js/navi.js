// Navi JS
// Copyright by Steffen Lindner
$(document).ready(function () {
    $('nav li a').click(function () {
        $('nav li.active').removeClass('active');
        $(this).parent().addClass('active');
        return false;
    });

    $('a').click(function () {
        return false;
    });
});
