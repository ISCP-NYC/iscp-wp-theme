jQuery(document).ready(function($) {
	headerView();
	footerView();
	filter();
	loadMore();
	var slug = $('body').data('center-slug');
	// window.history.pushState({page:slug}, null, null);
	$(window).load(function() {
		if($('.image_slider')) {
			setUpSlider();
		}
	});

	//sets width for full slider field and each section within
	function setMainWidth() {
		var main = $('main');
		var sections = $('section');
		var count = $('section').length;
		var winWidth = $(window).innerWidth();
		var pageWidth = winWidth;
		var fullWidth = count * pageWidth;
		var winHeight = $(window).innerHeight();
		var sideLabelHeight = winHeight - 110;
		$(main).css({width:fullWidth+130});
		$(sections).each(function(i) {
			$(this).css({
				left: i * pageWidth,
				width: pageWidth
			});
			var slides = $(this).find('.slide');
			var prevSlide = $(this).find('aside.left .slide');
			var nextSlide = $(this).find('aside.right .slide');
			$(slides).css({
				width: sideLabelHeight
			});
			var prevTitle = $(this).prev().data('title');
			var nextTitle = $(this).next().data('title');
			if(nextTitle) {
				$(nextSlide).find('.label').text(nextTitle);
			}
			if(prevTitle) {
				$(prevSlide).find('.label').text(prevTitle);
			}
			var prevLink = $(this).prev().data('permalink');
			var nextLink = $(this).next().data('permalink');
			if(nextLink) {
				$(nextSlide).attr('href', nextLink);
			}
			if(prevLink) {
				$(prevSlide).attr('href', prevLink);
			}
		});
		var centerIndex = $('body').index();
		slideTo(centerIndex, false);
	}

	function slideTo(index, animate) {
		// var centerSection = $('section[data-slug="' + centerSlug + '"');
		var section = $('section').eq(index);
		if(section.length) {
			// var centerIndex = $(centerSection).index();
			var pageWidth = $(window).innerWidth();
			if(animate) {
				var duration = 800;	
			} else {
				var duration = 0;
			}
			$(section).addClass('center');
			$('main').transition({
				left: -pageWidth * index
			}, duration, 'cubic-bezier(0.645, 0.045, 0.355, 1)');
		}
	}

	
	$('.slide').on('click', function(event) {
		event.preventDefault();
		var side = $(this).parents('aside');
		var index = $(side).parents('section').index();
		var pageWidth = $(window).innerWidth();
		if($(side).hasClass('right')) {
			var shift = index + 1;
		} else {
			var shift = index - 1;
		}
		var nextUp = $('section')[shift];
		if(nextUp == undefined) { return; }

		var slug = $(nextUp).data('slug');
		$('section.center').removeClass('center');
		slideTo(shift, true);
		$(nextUp).addClass('center');
		var url = $(nextUp).data('permalink');
		var id = $(nextUp).data('id');
		var slug = $(nextUp).data('slug');
		$('body').attr('data-center-id', id).attr('data-center-slug', slug)
		window.history.pushState({page:slug}, null, url);
	});

	// $('a').on('click', function(event) {
	// 	var url = $(this).attr('href');
	// 	var section = $('section[data-permalink="' + url + '"]');
	// 	var slug = $(section).data('slug');
	// 	if(section.length) {
	// 		event.preventDefault();
	// 		slideTo(slug, true);
	// 	}
	// });
	


	function loadMore() {
		
	}

	window.addEventListener('popstate', function(e) {
	    var state = e.state;
	    if(state) {
		    var slug = state.page;
		    slideTo(slug, true);
		}
	});

	function headerView() {
		
		$('body').on('mouseenter', 'section:not(.open-nav) .nav-hover', function() {
			var section = $(this).parent('section');
			$(section).addClass('tease-nav');
		}).on('mouseleave', 'section:not(.open-nav) .nav-hover', function() {
			var section = $(this).parent('section');
			$(section).removeClass('tease-nav');
		});

		//toggle nav visibility with button
		$('body').on('click', 'section:not(.open-nav) .nav-hover', function() {
			var section = $(this).parent('section');
			$(section).addClass('open-nav').removeClass('tease-nav');
		});

		$('body').on('click', '.open-nav .nav-toggle', function() {
			var section = $(this).parent('section');
			$(section).removeClass('open-nav');
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
					$(section).removeClass('tease-nav');
				} else if(scrollTop < lastScrollTop - 5 || scrollTop <= 50) {
					$(section).removeClass('tease-nav');
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
				var scrollHeight = content[0].scrollHeight;
				var contentHeight = content[0].clientHeight;
				var scrollTop = this.scrollTop;
				//scrolled to end of content -> scroll to footer
				if(scrollHeight - scrollTop == contentHeight) {
					$(section).addClass('show-footer');
					$(section).scroll(function(event) {
						var scrollTop = this.scrollTop;
						var scrollHeight = this.scrollHeight;
						var footerHeight = footer.clientHeight;
						//scrolled to top of footer -> scroll in content 
						if(scrollTop == 0) {
							$(section).removeClass('show-footer');
						}
					});
				}
			});
			// if content is too short to scroll -> scroll to footer
			var scrollHeight = content[0].scrollHeight;
			var contentHeight = content[0].clientHeight;
			if(scrollHeight <= contentHeight) {
				$(section).addClass('show-footer');
			}
		});
	}


	function filter() {
		$('.filter').on('click', '.select', function() {
			var slug = $(this).attr('data-slug');
			var filterThis = $('.filter-this.'+slug);
			if($(this).hasClass('view toggle')) {
				//toggle view style
				$(filterThis).toggleClass('list').toggleClass('grid');
				$(this).toggleClass('list').toggleClass('grid');
			} else if($(this).hasClass('dropdown')) {
				var property = $(this).attr('data-filter');
				var filterList = $('.filter-list.'+property+'.'+slug);
				var filterListOptions = $(filterList).children('.options');
				var filterListOptionsHeight = $(filterListOptions)[0].clientHeight;
				if($(this).hasClass('dropped')) {
					//toggle to hide this list
					$(this).removeClass('dropped');
					$(filterList).removeClass('show').css({height : 0});
				} else {
					//hide already opened filter list
					$('.dropdown').removeClass('dropped');
					$('.filter-list.show'+'.'+slug).removeClass('show').css({height : 0});
					//open this filter list
					$(this).addClass('dropped');
					$(filterList).addClass('show').css({height : filterListOptionsHeight});
				}
				
			}
		});
	}

	function setUpSlider() {
		var slider = $('.image_slider');
		var sliderWidth = $(slider).innerWidth();
		var slideWrapper = $(slider).children('.slides');
		var slides = $(slideWrapper).children('.slide');
		var slidesLength = $(slides).length;
		var captions = $(slider).children('.captions');
		var arrow = $(slider).children('.arrow');
		var left_arrow = $(arrow).filter('.left');
		var right_arrow = $(arrow).filter('.right');

		var showingImage = $(slides)[0];
		var showingCaption = $(captions).children('.caption')[0];

		$(showingImage).addClass('show');
		$(showingCaption).addClass('show');

		setSliderWidth();

		$(arrow).click(function() {
			var sliderWidth = $(slider).innerWidth();
			var showingSlide = $('.slide.show');
			var showingCaption = $('.caption.show');
			var showIndex = $(showingSlide).index();
			var shift = $(slideWrapper).css('left');
			if($(this).is('.left')) {
				var nextIndex = showIndex - 1;
				if(nextIndex == -1) {
					nextIndex = slidesLength - 1;
				}
			} else if($(this).is('.right')) {
				var nextIndex = showIndex + 1;
				if(nextIndex == slidesLength) {
					nextIndex = 0;
				}
			}
			var nextSlide = $(slides).eq(nextIndex);
			$(showingSlide).removeClass('show');
			$(nextSlide).addClass('show');

			$(slideWrapper).removeClass('static').css({
				'left' : -sliderWidth * nextIndex
			});
		});
	}

	function setSliderWidth() {
		var slider = $('.image_slider');
		var sliderWidth = $(slider).innerWidth();
		var slideWrapper = $(slider).children('.slides');
		var slides = $(slideWrapper).children('.slide');
		var slidesLength = $(slides).length;

		//size slide wrapper to fit all slides
		$(slideWrapper).css({width:sliderWidth*slidesLength});
		//size all slides to fit in viewport
		$(slides).each(function() {
			$(this).css({width:sliderWidth});
		});

		//don't allow transition on size
		$(slideWrapper).addClass('static');
		var showingSlide = $('.slide.show');
		var showIndex = $(showingSlide).index();
		$(slideWrapper).css({
			'left' : -sliderWidth * showIndex
		}, 600);
	}

	$('.gallery .piece .image').click(function() {
		var gallery = $(this).parents('.gallery');
		if($(gallery).hasClass('full')) {
			$(gallery).removeClass('full');
		} else {
			$(gallery).addClass('full');
		}
	});

	$('.gallery .close').click(function() {
		$(this).parents('.gallery').removeClass('full');
	});

	function winW() {
		return window.innerWidth;
	}

	$(window).resize(function() {
		setMainWidth();
		setSliderWidth();
	}).resize();

});