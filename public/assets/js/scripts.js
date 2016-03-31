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

    //NEED HELP HERE!! (make it better :P )
    $('nav li').click(function(){
        event.preventDefault();
    });
    $('.daily-li').click(function(){
        $('.daily').removeClass('none').addClass('block');
        $('.weekly').addClass('none');
        $('.bar').addClass('none');
    });
    $('.weekly-li').click(function(){
        $('.weekly').removeClass('none').addClass('block');
        $('.daily').addClass('none');
        $('.bar').addClass('none');
    });
    $('.bar-li').click(function(){
        $('.bar').removeClass('none').addClass('block');
        $('.weekly').addClass('none');
        $('.daily').addClass('none');
    });
    $('.random-li').click(function(){
        maxDeals = $('.deal').length;
        random = Math.floor((Math.random() * maxDeals) + 1);
        $('.deal').addClass('none');
        $('.deal').removeClass('block');
        $('.deal:nth-of-type(' + random + ')').addClass('block');
        $('.deal:nth-of-type(' + random + ')').removeClass('none');
        console.log(random);
    });
});
