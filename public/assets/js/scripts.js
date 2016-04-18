$(document).ready(function(){
    $('.contactButton').click(function(){
        $(this).children().toggleClass('rotate');
        $(this).next().toggleClass('open');
    });

    $('.sideNav').click(function(){
        $('aside').addClass('slideIn');
        $('.pageCover').addClass('block');
        $('html').addClass('overflow');
    });

    $('.pageCover').click(function(){
        $('aside').removeClass('slideIn');
        $('.pageCover').removeClass('block');
        $('html').removeClass('overflow');
    });

    $('.li-open').click(function(){
        $(this).children().slideToggle();
    });

    var number = $('ul.pageList').length;
    document.getElementById("number").innerHTML = number;
});
