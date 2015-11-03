$(function() {
	headerView();
	footerView();

	$(window).resize(function() {
		setMainWidth();
	}).resize();
	
});

//sets width for full slider field and each section within
function setMainWidth() {
	var main = $('main');
	var sections = $('section');
	var count = $('section').length;
	var winWidth = $(window).innerWidth();
	var pageWidth = winWidth;
	var fullWidth = count * pageWidth;
	$(main).css({width:fullWidth+130});
	$(sections).each(function(i) {
		$(this).css({
			left: i * pageWidth,
			width: pageWidth
		});
	});
}

function headerView() {
	//toggle nav visibility with button
	$('section').on('click', '.toggle', function() {
		var section = $(this).parent('section');
		$(section).toggleClass('open-nav');
	});

	//toggle header visibility with scroll behavior
	$('section').each(function() {
		var lastScrollTop = 0;
		var section = $(this);
		var content = $(this).children('.content');
		$(content).scroll(function(event) {
			var scrollTop = $(this).scrollTop();
			if(scrollTop > lastScrollTop + 10) {
				$(section).addClass('hide-header');
				$(section).removeClass('open-nav');
			} else if(scrollTop < lastScrollTop - 5) {
				$(section).removeClass('hide-header');
			}
			lastScrollTop = scrollTop;
		});
	});
}


function footerView() {
	//toggle ability to scroll to footer
	$('section').each(function() {
		var section = $(this);
		var content = $(this).children('.content');
		var footer = $(this).children('footer')[0];
		$(content).scroll(function(event) {
			var scrollTop = this.scrollTop;
			var scrollHeight = this.scrollHeight;
			var contentHeight = this.clientHeight;

			//scrolled to end of content -> scroll in footer
			if(scrollHeight - scrollTop === contentHeight) {
				$(section).addClass('show-footer');
				$(section).scroll(function(event) {
					var scrollTop = this.scrollTop;
					var scrollHeight = this.scrollHeight;
					var footerHeight = footer.clientHeight;
					if(scrollTop === 0) {
						$(section).removeClass('show-footer');
					}
				});
			}
			
			//scrolled to top of footer -> scroll in content 
			
		});
	});
}


