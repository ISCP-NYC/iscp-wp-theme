$(function() {
	headerExposure();
});

function headerExposure() {
	$('section .content').each(function() {
		var lastScrollTop = 0;
		var scrollArea = $(this);
		console.log($(this));
		$(scrollArea).scroll(function(event) {
			console.log($(this));
			var scrollTop = $(this).scrollTop();
			if(scrollTop > lastScrollTop + 10) {
				$(page).addClass('nohead');
				$(page).children('header').removeClass('super');
			} else if(scrollTop < lastScrollTop - 5) {
				$(page).removeClass('nohead');
			}
			lastScrollTop = scrollTop;
		});
	});
}