$(document).ready(function(){
    $('.contactButton').click(function(){
        //$(this).next().toggle("slide", {direction:"right"}, 400);
        $(this).children().toggleClass('rotate');
        $(this).next().toggleClass('open');
    });

    //var imageLogo = ""; //image url
    //var logoAlt = "Hungry Huntington"; //logo alt tag
    var link = ""; //add url link
    var textLogo = "<a href="  + link +  "><span>" + logoAlt + "</span></a>";
    /*if (imageLogo){
        $('.logo').prepend('<a href='+ link +'><img src='+ imageLogo +' alt='+ logoAlt +' /></a>');
    }
    else {
        $('.logo').prepend(textLogo);
    }*/

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
