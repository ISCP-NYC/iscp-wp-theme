$(function() {
	$('main').on('click', '.toggle', function() {
		$('header.main').toggleClass('opened');
	});

	$(window).resize(function() {
		setMainWidth();
	}).resize();
	
});



function setMainWidth() {
	var main = $('main');
	var pages = $('.page');
	var count = 4;
	var winWidth = $(window).innerWidth();
	var pageWidth = winWidth - 200;
	var fullWidth = count * pageWidth;
	$(main).css({width:fullWidth});
	$(pages).each(function(i) {
		$(this).css({
			left: i * pageWidth + 100,
			width: pageWidth
		});
	});
}