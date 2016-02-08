jQuery(document).ready(function($) {
$(window).load(function() {
	setUp();
	setSliderWidth();
	$masonry.masonry('layout');
	if($('.image_slider').length > 0) {
		setUpSlider();
	}
	if($('.earth').length > 0) {
		setUpEarth();
	}
	if($('.center').is('.resident-resources')) {
		var hash = parseInt(window.location.hash.replace('#', ''));
		var index = hash + 1; 
		var item = $('.item').eq(index);
		var scrollTop = item[0].offsetTop;
		$('.center .content').scrollTop(scrollTop);

	}
});

$masonry = $('.journal.grid').masonry({
	columnWidth: '.sizer',
	itemSelector: '.item',
	gutter: 20,
	transitionDuration: 0
});

//sets width for full slider field and each section within
function setUp() {
	var main = $('main');
	var sections = $('section');
	var count = $(sections).length;
	var asideWidth = $('section').eq(0).find('aside').innerWidth();
	var winWidth = winW();
	var pageWidth = winWidth;
	var fullWidth = count * pageWidth;
	var winHeight = $(window).innerHeight();
	var asideLabelHeight = winHeight - asideWidth;
	$(main).css({width:fullWidth});
	$(sections).each(function(i) {
		var section = $('section').eq(i);
		var shift = i * asideWidth;
		$(section).css({
			left: i * pageWidth - shift,
			width: pageWidth
		});
		var asideLinks = $(section).find('aside .move');
		var prevSideLink = $(section).find('aside.left .move');
		var nextSideLink = $(section).find('aside.right .move');
		$(asideLinks).css({
			width: asideLabelHeight
		});
		
		var prevTitle = $(section).prev().attr('data-title');
		var nextTitle = $(section).next().attr('data-title');

		if(nextTitle) {
			if($(section).next().is('.single.journal-post')) {
				nextTitle = 'Next';
			}
			$(nextSideLink).find('.label').text(nextTitle);
		}
		if(prevTitle) {
			if($(section).prev().is('.single.journal-post')) {
				prevTitle = 'Previous';
			}
			$(prevSideLink).find('.label').text(prevTitle);
		}
		var prevUrl = $(section).prev().attr('data-permalink');
		var nextUrl = $(section).next().attr('data-permalink');
		if(nextUrl) {
			$(nextSideLink).attr('href', nextUrl);
		}
		if(prevUrl) {
			$(prevSideLink).attr('href', prevUrl);
		}
		$(section).find('.filter-list.show').each(function() {
			var optionsHeight = $(this).find('.options')[0].clientHeight;
			$(section).height(optionsHeight);
		});
		var content = $(section).find('.content');	
		setTimeout(function() {
			if($(content).length) {
				var scrollHeight = content[0].scrollHeight;
				var contentHeight = $(content)[0].clientHeight;
				// keep content scrolled to bottom when footer is visible
				if($(section).hasClass('show-footer')) {
					$(content).scrollTop(scrollHeight);
				}
				// if content is too short to scroll -> scroll to footer
				if(scrollHeight <= contentHeight && scrollHeight != 0) {
					$(section).addClass('show-footer');
				}
			}
			$(section).removeClass('static').addClass('ready');
		});
	});
	var centerSlug = $('main').attr('data-center-slug');
	var centerIndex = $('section#' + centerSlug).index();
	$('section#' + centerSlug).attr('data-permalink', window.location);
	slideTo(centerIndex, false);
}


$('body').on('mouseenter', '.shelf-item .wrap', function() {
	$(this).parents('.shelf-item').addClass('hover');
}).on('mouseleave', '.shelf-item .wrap', function() {
	$(this).parents('.shelf-item').removeClass('hover');
});


$('body').on('wheel', 'section', function(e) {
	var section = $(this);
	if(!$(section).hasClass('show-footer')) {
		var e = window.event || e;
		var delta = e.deltaY;
		if(delta == undefined) {
			 delta = e.originalEvent.deltaY;
		}
		var content = $(section).find('.content');
		var scrollTop = $(content).scrollTop();
		var scrollTo = scrollTop + delta;
		$(content).scrollTop(scrollTo);
	}
});


// $('body').on('mousewheel', 'aside.main', function(e) {
// 	var section = $(this).parents('section');
// 	if(!$(section).hasClass('show-footer')) {
// 		var e = window.event || e;
// 		var delta = e.deltaY;
// 		var content = $(section).find('.content');
// 		var scrollTop = $(content).scrollTop();
// 		var scrollTo = scrollTop + delta;
// 		$(content).scrollTop(scrollTo);
// 	}
// });


/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
///////////////////////HEADER & FOOTER///////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////

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
function sectionScrollListener(section) {
	var scrollTop = $(section).scrollTop();
	var footer = $(section).find('footer');
	var footerMargin = parseInt($(footer).css('marginTop').replace('px', ''));
	var footerHeight = $(footer).outerHeight() + footerMargin;
	if(scrollTop >= footerHeight - 100) {
		$(section).addClass('show-footer-bottom');
	} else if(scrollTop <= footerMargin) {
		$(section).removeClass('show-footer-bottom');
	}
	if($(section).hasClass('show-footer')) {
		var scrollTop = section.scrollTop;
		var scrollHeight = section.scrollHeight;
		var footerHeight = footer.clientHeight;
		//scrolled to top of footer -> scroll in content 
		if(scrollTop <= 0) {
			$(section).removeClass('show-footer');
		}
	}
}
$('section').scroll(function() {
	sectionScrollListener(this);
});

var lastScrollTop;
//toggle ability to scroll to footer
function sectionContentScrollListener(content) {
	var section = $(content).parents('section');
	var footer = $(section).children('footer')[0];
	var header = $(section).find('header.main');
	var scrollHeight = content.scrollHeight;
	var contentHeight = content.clientHeight;
	var scrollTop = content.scrollTop;
	//scrolled to end of content -> scroll to footer
	if(scrollHeight - scrollTop == contentHeight && scrollTop > lastScrollTop) {
		$(section).addClass('show-footer');
	}
	if(scrollTop > lastScrollTop + 10 && scrollTop > 100) {
		$(section).addClass('hide-header');
		$(section).removeClass('open-nav');
		$(section).removeClass('tease-nav');
	} else if(scrollTop < lastScrollTop - 5) {
		$(section).removeClass('tease-nav');
		$(section).removeClass('hide-header');
	}
	lastScrollTop = scrollTop;

	if($(section).is('#events')) {
		var past = $(content).find('.past.wrapper');
		var pastTop = past[0].offsetTop - 90;
		if(scrollTop > pastTop) {
			$(section).addClass('past');
		} else {
			$(section).removeClass('past');
		}
	}
}
$('section .content').scroll(function() {
	sectionContentScrollListener(this);
});


/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
///////////////////////ASIDE & SLIDE ////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
var firstSlide = true;
function slideTo(index, animate) {
	var section = $('section').eq(index);
	var side = $(section).find('aside');
	var size = $('#size').css('content');
	var asideWidth = $(side).innerWidth();
	if(section.length) {
		$('main').addClass('sliding');
		$('section.center').removeClass('center').removeClass('hover-left').removeClass('hover-right');
		$('section.right').removeClass('right');
		$('section.left').removeClass('left');
		var pageWidth = $(window).innerWidth();
		if(animate) {
			var duration = 800;	
		} else {
			var duration = 0;
		}
		$(section).addClass('center');
		$(section).removeClass('hide-shelves');
		$(section).css({
			zIndex: 0
		}).addClass('center');
		$(section).next().addClass('right');
		$(section).prev().addClass('left');
		$('main').transition({
			left: -pageWidth * index + (index*asideWidth),
		}, duration, 'cubic-bezier(0.645, 0.045, 0.355, 1)', function() {
			$('section:not(.center)').addClass('hide-shelves');
			$('section:not(.center)').find('.content').scrollTop(0);
			$('section:not(.center)').scrollTop(0);
			$('main').removeClass('sliding');
		});
		var url = $(section).attr('data-permalink');
		var id = $(section).attr('data-id');
		var slug = $(section).attr('data-slug');
		$('main').attr('data-center-id', id).attr('data-center-slug', slug);
		window.history.replaceState({page: index}, null, url);
	}
}

$('body').on('mouseenter', 'aside.main .move', function() {
	var section = $(this).parents('section');
	var aside = $(this).parents('aside');
	if($(aside).hasClass('right')) {
		var side = 'right';
	} else if($(aside).hasClass('left')) {
		var side = 'left';
	}
	if($(aside).find('.label').text().length) {
		$(section).addClass('hover-' + side);
		$(aside).addClass('hover');
	}
});
$('body').on('mouseleave', 'aside.main .move', function() {
	var aside = $(this).parents('aside');
	var section = $(this).parents('section');
	$(aside).removeClass('hover');
	$(section).removeClass('hover-right').removeClass('hover-left');
});

//slide to pages with browser forward and backward navigation
window.addEventListener('popstate', function(e) {
    var state = e.state;
    if(state) {
	    var index = state.page;
	    slideTo(index, true);
	    if($('section#map').length) {
	    	openMapResidentsFromUrl();
	    }
	}
});

$('body').on('click', 'aside .move', function(event) {
	event.preventDefault();
	if($('main').is('.sliding')){return;}
	var aside = $(this).parents('aside');
	var index = $(aside).parents('section').index();
	var section = $('section').eq(index);
	var currentUrl = $(section).attr('data-permalink');
	window.history.pushState({page: index}, null, currentUrl);

	if($(section).is('.resident')) {
		var type = 'resident';
	} else if($(section).is('.journal-post')) {
		var type = 'journal-post';
	}

	if($(aside).hasClass('left')) {
		var nextIndex = index - 1;
		var direction = 'prev';
	}
	else {
		var nextIndex = index + 1;
		var direction = 'next';
	} 
	var nextUp = $('section')[nextIndex];
	if(nextUp == undefined) { return; }
	var sectionsLength = $('section.'+type).length;
	if(sectionsLength > 0) {
		if(direction == 'prev') {
			getNeighbors(direction, type);
		} else if(direction == 'next') {
			getNeighbors(direction, type);
		}
	}
	slideTo(nextIndex, true);
});


/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
///////////////////////AJAX AJAX AJAX////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
$('body').on('click', '.load-more a', function(event) {
	event.preventDefault();
	var paged = parseInt($(this).parents('section').attr('data-page')) + 1;
	var slug = $(this).parents('section').attr('id');
	var vars = ajaxpagination.query_vars;
	var section = $(this).parents('section');
	vars = JSON.parse(vars);
	if($(section).is('.events')) {
		var upcomingIds = [];
		$(section).find('.item.upcoming').each(function() {
			var upcomingId = $(this).attr('data-id');
			upcomingIds.push(upcomingId)
		});
		vars['upcoming_ids'] = upcomingIds; 
	}
	vars['pagename'] = slug;
	vars['paged'] = paged;
	vars = JSON.stringify(vars);
	$.ajax({
		url: ajaxpagination.ajaxurl,
		type: 'post',
		data: {
			action: 'load_more',
			query_vars: vars,
			page: paged
		},
		beforeSend: function() {
			loading(vars);
		},
		success: function(response) {
			addItems(response, vars);
		}
	});
});
function loading(vars) {
	var vars = JSON.parse(vars);
	var sectionSlug = vars.pagename;
	if(sectionSlug == 'journals') {sectionSlug='journal';}
	var section = $('section#'+sectionSlug);
	var shelves = $(section).find('.items');
	$(section).addClass('loading');
}
//append section items to bottom of section content
function addItems(html, vars) {
	if(html.length > 0) {
		var vars = JSON.parse(vars);
		var sectionSlug = vars.pagename;
		if(sectionSlug == 'journals') {sectionSlug='journal';}
		var section = $('section#'+sectionSlug);
		var content = $(section).find('.content');
		if($(section).is('.events')) {
			var container = $(section).find('.items.past');
		} else {
			var container = $(section).find('.items');
		}
		var footer = $(section).find('footer')
		var paged = parseInt($(section).attr('data-page')) + 1;
		var contentScrollTop = $(content).scrollTop();
		var sectionScrollTop = $(section).scrollTop();
		$(section).attr('data-page', paged);
		$(section).removeClass('loading');
		$(section).find('.load-more').remove();
		$(html).each(function() {
			var item = this;
			if(!$(item).hasClass('load-more')) {
				$(item).addClass('hide');
			}
			if($(container).hasClass('journal')) {
				$masonry.append(item).masonry('appended', item);
			} else {
				$(container).append($(item));
			}
			$(container).imagesLoaded(function() {
				$(item).removeClass('hide');
				$masonry.masonry('layout');
			});
		});
		$(section).removeClass('show-footer');
		$(content).animate({ scrollTop: contentScrollTop + sectionScrollTop }, 300, 'easeOutQuart');
		$(section).animate({ scrollTop: 0 }, 300, 'easeOutQuart');
	}
}
var loadedFrom = [];
var click = 0;
//query wordpress for section 'neighbor' and insert them into main wrapper
function getNeighbors(direction, type) {
	click++;
	if(direction == 'prev') {
		var section = $('section.'+type).first();
	} else if(direction == 'next') {
		var section = $('section.'+type).last();
	}
	var id = $(section).attr('data-id');
	if(loadedFrom.indexOf(id) != -1) {
		return;
	}
	loadedFrom.push(id);
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	vars['id'] = id;
	vars['direction'] = direction;
	vars = JSON.stringify(vars);
	var action = 'get_neighbor_' + type.replace(/[-]/g, '_') + 's';
	$.ajax({
		url: ajaxpagination.ajaxurl,
		type: 'post',
		data: {
			action: action,
			query_vars: vars
		},
		beforeSend: function() {
			$('main').addClass('loading-neighbors');
		},
		success: function(newSection) {
			var id = $(newSection).attr('data-id');
			$(newSection).addClass('new').attr('data-loaded-from', id);
			if(direction == 'prev') {
				$('section.'+type).first().before(newSection);
			} else if(direction == 'next') {
				$('section.'+type).last().after(newSection);
			}
			if(newSection.length > 0) {
				var newSection = $('section[data-id="' + id + '"]');
				var newSectionContent = $(newSection).find('.content');
				$(newSection).removeClass('new');
				$(newSection).scroll(function() {
					sectionScrollListener(this);
				});
				$(newSectionContent).scroll(function() {
					sectionContentScrollListener(this);
				});
				setUp();
			}
			$('main').removeClass('loading-neighbors');
		}
	});
}

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
//////////////////////////SEARCH/////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
$('body').on('mouseenter', 'form', function() {
	var input = $(this).find('input');
	var value = $(input).attr('value');
	var placeholder = $(input).attr('data-placeholder');
	if(value === placeholder) {
		$(input).attr('value', '');
		$(input).siblings('.counter').html('');
	}
	$(input).focus();
}).on('mouseleave', 'form', function() {
	var input = $(this).find('input');
	var value = $(input).attr('value');
	var placeholder = $(input).attr('data-placeholder');
	if (!/\S/.test(value)) {
		$(input).attr('value', placeholder);
		$(input).siblings('.counter').html('');
	}
	if(!$(input).is('.main-search')) {
		$(input).blur();
	}
});

var navTimer;
$('body').on('keydown', 'form input', function() {
	var input = this;
	var value = input.value;
	var placeholder = $(input).attr('data-placeholder');
	if(value === placeholder) {
		$(input).attr('value', '');
		$(input).siblings('.counter').html('');
	}
});

$('body').on('keyup', 'nav .search input', function() {
	clearTimeout(navTimer);
	var input = this;
	var form = $(input).parents('form');
	var section = $(input).parents('section');
	var text = input.value;
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	vars['s'] = text;
	vars = JSON.stringify(vars);
	if(/\S/.test(text)) {
		navTimer = setTimeout(function() {
			$.ajax({
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'get_search_count',
					query_vars: vars
				},
				success: function(count) {
					var counter = $(form).find('.counter');
					count = '(' + count + ')';
					$(counter).text(count);
				}
			});
		}, 200);
	} else {
		$(counter).text('');
	}
});

var mainTimer;
$('body').on('keyup', 'section#search .content input.s', function() {
	clearTimeout(mainTimer);
	var input = this;
	var section = $(input).parents('section');
	var text = input.value;
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	vars['s'] = text;
	vars = JSON.stringify(vars);
	if(/\S/.test(text)) {
		mainTimer = setTimeout(function() {
			$.ajax({
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'get_search_count',
					query_vars: vars
				},
				success: function(count) {
					var counter = $(section).find('.title .counter');
					var value = $(section).find('.title .value');
					$(counter).text(count);
					$(value).text(text);
				}
			});

			$.ajax({
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'update_search_results',
					query_vars: vars
				},
				success: function(response) {
					$(section).find('.results').html(response);
					$(section).find('.head').css({opacity:1});
					if (history.pushState) {
					    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?s=' + text;
					    window.history.pushState({path:newurl},'',newurl);
					}
				}
			});
		}, 200);
	} else {
		var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?s=' + text;
		window.history.pushState({path:newurl},'',newurl);
		$(section).find('.results').children().fadeOut(500);
		$(section).find('.head').css({opacity:0});
	}
});

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
////////////////////////IMAGE SLIDER/////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
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
	$(arrow).off('click');
	$(arrow).on('click', function() {
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

// $('body').on('mousemove', '.gallery:not(.full)', function(event) {
// 	var cursor = $(this).find('.cursor');
// 	if ($(this).find('.image img:hover').length != 0 || $(cursor).is(':hover')) {
// 		var x = event.pageX;
// 		var y = event.pageY;
// 		$(cursor).attr('data-icon', 'zoom');
// 		$(cursor).css({
// 			'left': x,
// 			'top': y,
// 			'display': 'block',
// 			'cursor': 'none !important'
// 		});
// 	} else {
// 		$(cursor).css({
// 			'display': 'none'
// 		});
// 	}
// })
// $('body').on('mouseleave', '.gallery:not(.full)', function() {
// 	var cursor = $(this).find('.cursor');
// 	$(cursor).css({
// 		'display': 'none'
// 	});
// });

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
		if(x >= window.innerWidth - 200 && slidesLength > 1) {
			$(cursor).attr('data-icon', 'right');
		} else if(x <= 200 && slidesLength > 1) {
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

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////MAP/////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
var earth;
function setUpEarth() {
	var section = $('section#map');
	var residents = $(section).find('.residents');
	var residentsList = $(residents).find('.list');
	var residentsHead = $(residents).find('.head');
	var earth = new WE.map('mapWrap');
	var canvas = earth.canvas;
    earth.setView([46.8011, 8.2266], 1.6);
    WE.tileLayer('http://data.webglearth.com/natural-earth-color/{z}/{x}/{y}.jpg', {
      tileSize: 256,
      bounds: [[-85, -180], [85, 180]],
      minZoom: 0,
      maxZoom: 5,
      tms: true
    }).addTo(earth); 

    $(canvas).on('mousedown', function() {
    	$(this).addClass('grabbing');
    }).on('mouseup', function() {
    	$(this).removeClass('grabbing');
    });

    $(window).resize(function() {
    	$(canvas).width(winW());
    });	

    var mapWidth = $('#mapWrap').width();
    var mapHeight = $('#mapWrap').innerHeight();
    $('#mapWrap canvas').width(mapWidth).height(mapHeight);
    var countries = window.countries;
    var themeUrl = window.wp_info['theme_url'];
    var markerUrl = themeUrl+'/assets/images/bullet-orange.svg';

    $(countries).each(function(i, country) {
    	var name = country['name'];
    	var slug = country['slug'];
    	var count = country['count'];
    	var lat = country['lat'];
    	var lng = country['lng'];
    	if(isNumeric(lat) && isNumeric(lng)) {
    		var marker = WE.marker([lat, lng], markerUrl, 30, 30).addTo(earth);
    		var html = marker.element;
    		var inner = $(html).find('.we-pm-icon');
    		$(html).addClass('marker').attr('data-slug', slug).attr('data-name', name).attr('data-count', count);
    		$(inner).html('<span class="count">' + count + '</span>');
    	}
    });

    $('body').on('mouseenter', '.marker:not(.teasing)', function() {
    	if(!$(residents).hasClass('show')) {
	    	var marker = $(this);
	    	var name = $(this).attr('data-name');
	    	var slug = $(this).attr('data-slug');
	    	var count = $(this).attr('data-count');
	    	$(marker).addClass('teasing');
	    	$(residentsHead).html(name +' ('+count+')');
	    	var headHeight = $(residentsHead).innerHeight();
	    	$(residents).addClass('tease');
	    	if($(residents).hasClass('show')) {
	    		$(residents).removeClass('show').attr('style','');
	    		if (history.pushState) {
				    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
				    window.history.pushState({path:newurl},'',newurl);
				}
	    	}
	    }
    }).on('mouseleave', '.marker', function() {
    	if(!$(residents).hasClass('show')) {
	    	var marker = $(this);
	    	$(marker).removeClass('teasing');
	    	setTimeout(function() {
	    		if(!$('.marker.teasing').length) {
		    		$(residents).removeClass('tease');
		    	}
	    	},200);
	    }
    }).on('click', '.marker', function() {
    	var marker = $(this);
    	var name = $(this).attr('data-name');
    	var slug = $(this).attr('data-slug');
    	var count = $(this).attr('data-count');
    	$(marker).addClass('showing');
    	$(residentsHead).html(name+' ('+count+')');
    	$(residents).addClass('show');
    	if (history.pushState) {
		    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?where=' + slug;
		    window.history.pushState({path:newurl},'',newurl);
		}
    	getMapList(slug);
    }).on('click', '.close', function() {
    	$(residents).removeClass('show').attr('style','');
    	$('.marker.showing').removeClass('showing');
    	if (history.pushState) {
		    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
		    window.history.pushState({path:newurl},'',newurl);
		}
    });
    openMapResidentsFromUrl();
}

function openMapResidentsFromUrl() {
	var urlParam = getParam('where');
    if(urlParam.length) {
    	var slug = urlParam;
		var marker = $('.marker[data-slug="' + slug + '"]');
		var count = $(marker).attr('data-count');
		var name = $(marker).attr('data-name');
		$(marker).addClass('showing');
		$('.residents .head').html(name +' ('+count+')');
		getMapList(slug);
	}
}

function getMapList(slug) {
	var section = $('section#map');
	var residents = $(section).find('.residents');
	var residentsList = $(residents).find('.list');
	var head = $(residents).find('.head');
	var earth = new WE.map('mapWrap');
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	vars['pagename'] = slug;
	vars = JSON.stringify(vars);
	$.ajax({
		url: ajaxpagination.ajaxurl,
		type: 'post',
		data: {
			action: 'get_map_list',
			query_vars: vars
		},
		beforeSend: function() {
			if($(residents).is('.show')) {
				$(residents).removeClass('show').attr('style','');
				$(residents).css({'height':'auto'});
			}
		},
		success: function(response) {
			$(residentsList).html(response);
			var residentsHeight = $(residents).innerHeight();
			if(residentsHeight > winH()) {
				residentsHeight = winH() - 100;
			}
			$(residents).addClass('show').height(residentsHeight).transition({
				y: -residentsHeight
			}).removeClass('tease');
		}
	});
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////FOOTER//////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
$('body').on('click', '.filter .select', function() {
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
			$(filterList).addClass('show growing').css({height : filterListOptionsHeight});
			$(filterList).one('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function() {
				$(filterList).removeClass('growing');
			});
		}
	
	}
});

function winW() {
	return window.innerWidth;
}

function winH() {
	return window.innerHeight;
}

function getParam(paramType) {
    var url = window.location.search.substring(1);
    var urlVars = url.split('&');
    for(var i = 0; i < urlVars.length; i++) {
        var thisParamType = urlVars[i].split('=');
        if(thisParamType[0] == paramType) {
            return thisParamType[1];
        }
    }
    return false;
}


$(window).resize(function() {
	setUp();
	setSliderWidth();
	$masonry.masonry('layout');
});

});