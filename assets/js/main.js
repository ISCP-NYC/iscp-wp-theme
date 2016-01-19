jQuery(document).ready(function($) {
	filter();

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
			var slides = $(this).find('aside .slide');
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
		var centerSlug = $('body').data('center-slug');
		var centerIndex = $('section[data-slug="' + centerSlug + '"').index();
		slideTo(centerIndex, false);
	}

	var firstSlide = true;
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
			}, duration, 'cubic-bezier(0.645, 0.045, 0.355, 1)', function() {
				$('section:not(.center)').find('.content').scrollTop(0);
				$('section:not(.center)').scrollTop(0);
			});
		}
	}

	
	$('body').on('click', 'aside .slide', function(event) {
		event.preventDefault();
		var side = $(this).parents('aside');
		var index = $(side).parents('section').index();
		var currentUrl = $('section').eq(index).data('permalink');
		window.history.pushState({page: index}, null, currentUrl);
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
		window.history.replaceState({page: shift}, null, url);
	});

	$('body').on('mouseenter', 'aside.main .slide', function() {
		var side = $(this).parents('aside');
		if($(side).find('.label').text().length) {
			$(side).addClass('hover');
		}
	});
	$('body').on('mouseleave', 'aside.main .slide', function() {
		var side = $(this).parents('aside');
		$(side).removeClass('hover');
	});

	//slide to pages with browser forward and backward navigation
	window.addEventListener('popstate', function(e) {
	    var state = e.state;
	    if(state) {
	    	console.log(state);
		    var index = state.page;
		    slideTo(index, true);
		}
	});

	//tease header on hover
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
	}).on('click', '.open-nav .nav-toggle', function() {
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


	$('section').scroll(function(event) {
		var scrollTop = $(this).scrollTop();
		var footer = $(this).find('footer');
		var footerMargin = parseInt($(footer).css('marginTop').replace('px', ''));
		var footerHeight = $(footer).outerHeight() + footerMargin;
		if(scrollTop >= footerHeight - 100) {
			$(this).addClass('show-footer-bottom');
		} else if(scrollTop <= footerMargin) {
			$(this).removeClass('show-footer-bottom');
		}
	});

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

	$('body').on('mousewheel', 'aside.main', function(e) {
		var section = $(this).parents('section');
		if(!$(section).hasClass('show-footer')) {
			var e = window.event || e;
			var delta = e.deltaY;
			var content = $(section).find('.content');
			var scrollTop = $(content).scrollTop();
			var scrollTo = scrollTop + delta;
			$(content).scrollTop(scrollTo);
		}
	});


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
		if(slider.length == 0) {return;}
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
		var startIndex = $(slider).attr('data-show');
		//size slide wrapper to fit all slides
		$(slideWrapper).css({width:sliderWidth*slidesLength});
		//size all slides to fit in viewport
		$(slides).each(function() {
			$(this).css({width:sliderWidth});
		});

		//don't allow transition on size
		$(slideWrapper).addClass('static');
		if(startIndex != undefined) {
			$(slideWrapper).find('.show').removeClass('show');
			$(slides).eq(startIndex).addClass('show');
		}
		var showingSlide = $('.slide.show');
		var showIndex = $(showingSlide).index();
		$(slideWrapper).css({
			'left' : -sliderWidth * showIndex
		}, 600);
	}

	$('body').on('click', '.gallery .slide .image', function() {
		var index = $(this).parents('.slide').index();
		var gallery = $(this).parents('.gallery');
		$(gallery).attr('data-show', index).addClass('full').addClass('image_slider').css({'cursor':'none'});
		var cursor = $(gallery).find('.cursor');
		var slidesLength = $(gallery).find('.slide').length;
		setUpSlider();
		$(gallery).on('mousemove', function(event) {
			var x = event.pageX;
			var y = event.pageY;
			if(x >= window.innerWidth - 100 && slidesLength > 1) {
				$(cursor).attr('data-icon', 'right');
			} else if(x <= 100 && slidesLength > 1) {
				$(cursor).attr('data-icon', 'left');
			} else {
				$(cursor).attr('data-icon', 'close');
			}
			$(cursor).css({
				'left': x,
				'top': y,
				'display': 'block',
				'cursor': 'none !important'
			});
		}).on('click', function(event) {
			var cursor = $(gallery).find('.cursor');
			var icon = $(cursor).attr('data-icon');
			var sliderWidth = $(gallery).innerWidth();
			var showingSlide = $('.slide.show');
			var showingCaption = $('.caption.show');
			var showIndex = $(showingSlide).index();
			var slideWrapper = $(gallery).children('.slides');
			var slides = $(slideWrapper).children('.slide');
			var slidesLength = $(slides).length;
			var shift = $(slideWrapper).css('left');
			switch(icon) {
				case 'close':
					$(this).removeClass('full').attr('style','');
					$(gallery).off('mousemove');
					$(this).off('click');
					$(this).find('.slides').attr('style','');
					$(this).find('.slide').attr('style','');
					$(cursor).attr('style','');
					if($(this).hasClass('stack')) {
						$(this).removeClass('image_slider');
					} else {
						setUpSlider();
					}
					break;
				case 'right':
					var nextIndex = showIndex + 1;
					if(nextIndex == slidesLength) {
						nextIndex = 0;
					}
					break;
				case 'left':
					var nextIndex = showIndex - 1;
					if(nextIndex == -1) {
						nextIndex = slidesLength - 1;
					}
					break;
			}
			if(nextIndex != undefined) {
				var nextSlide = $(slides).eq(nextIndex);
				$(showingSlide).removeClass('show');
				$(nextSlide).addClass('show');
				$(gallery).attr('data-show', nextIndex);
				$(slideWrapper).removeClass('static').css({
					'left' : -sliderWidth * nextIndex
				});
			}
		});
	});

	function winW() {
		return window.innerWidth;
	}

	$(window).resize(function() {
		setMainWidth();
		setSliderWidth();
	}).resize();

});