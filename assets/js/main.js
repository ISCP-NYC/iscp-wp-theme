jQuery(document).ready(function($) {
	headerView();
	footerView();
	filter();
	loadMore();
	paginate();

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
		$('section').on('click', '.nav-toggle', function() {
			var section = $(this).parent('section');
			$(section).toggleClass('open-nav');
		});

		$('section').on('hover', '.nav-toggle', function() {
			$(this).parent('section').toggleClass('tease-header');
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
				} else if(scrollTop < lastScrollTop - 5 || scrollTop <= 50) {
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
					console.log(filterListOptions);
				}
				
			}
		});
	}

	function paginate() {
		$('aside').on('click', function() {
			var index = $(this).parent('section').index();
			var winWidth = $(window).innerWidth();
			var pageWidth = winWidth;

			if($(this).hasClass('right')) {
				var shift = index + 1;
			} else {
				var shift = index - 1;
			}

			var nextUp = $('section')[shift];
			if(nextUp == undefined) { return; }

			$('section.center').removeClass('center');

			$('main').css({
				left: -pageWidth * shift
			});

			$(nextUp).addClass('center');

		});
	}


	function loadMore() {
		
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
		});
	}

	function winW() {
		return window.innerWidth;
	}

	$(window).resize(function() {
		setMainWidth();
		setSliderWidth();
	}).resize();

});