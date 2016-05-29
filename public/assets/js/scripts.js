$(document).ready(function(){
    $('.contactButton').click(function(){
        $(this).children().toggleClass('rotate');
        $(this).prev().toggleClass('grow');
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

	$('header nav ul li a').click(function() {
		ga('send', 'event', 'Navigation', 'click', 'Top');
	});

	$('aside nav ul li a').click(function() {
		ga('send', 'event', 'Navigation', 'click', 'Side');
	});
});
