jQuery(document).ready(function($) {
var isMobile = false;
var loader = '<div class="loader"><div></div><div></div><div></div></div>';
// $(window).load(function() {
	mobileCheck();
	setUp();
	setImageSliderSize();
	if($('.journal.grid').length) {
		$masonry = $('.journal.grid').masonry({
			columnWidth: '.sizer',
			itemSelector: '.item',
			gutter: 30,
			transitionDuration: 0
		});
		$masonry.masonry('layout');
	}
	if($('.image_slider').length > 0) {
		setUpImageSlider();
	}
	if($('section#map').length) {
		setUpEarth();
	}
	if($('section.resource').length) {
		scrollToResourceItem();
	}
	if($('section#events').length && (getParam('date') || getParam('type') || window.location.hash == '#past')) {
		var pastWrapper = $('.past.wrapper');
		var pastWrapperTop = $(pastWrapper).offset().top - 130;
		$(pastWrapper).height($(window).innerHeight());
		$('section#events .content').scrollTop(pastWrapperTop);
	}
	if($('.center').is('.greenroom:not(#greenroom)')) {
		var hash = parseInt(window.location.hash.replace('#', ''));
		var index = hash + 1;
		var item = $('.item').eq(index);
		var scrollTop = item[0].offsetTop;
		$('.center .content').scrollTo(scrollTop);
	}
	if($('.items[data-delay]')) {
		$('.items[data-delay]').each(function() {
			var delay = $(this).attr('data-delay');
			var container = $(this);
			setTimeout(function() {
				var slug = $(container).parents('section').attr('id');
				$(this).parent('section').attr('data-page', 1);
				loadMore(slug, 1);
				insertFilterList(slug);
			},delay*100);
		})
	}
// });



//sets width for full slider field and each section within
function setUp() {
	var main = $('main');
	var sections = $('section');
	var count = $(sections).length;
	var aside = $('section').eq(0).children('aside.main');
	if($(aside).length) {
		var asideWidth = $(aside)[0].clientWidth;
	} else {
		var asideWidth = 0;
	}
	var winWidth = winW();
	var pageWidth = winWidth;
	if(pageWidth < 320) {pageWidth=320;}
	var mainWidth = count * pageWidth;
	var winHeight = winH();
	var asideLabelHeight = winHeight - asideWidth + 10;
	$(main).width(mainWidth);
	$(sections).each(function(i) {
		var section = $('section').eq(i);
		var asideShift = i * asideWidth;
		var left = i * pageWidth - asideShift;
		if($(section).is('#map')) {
			left += asideShift;
		}
		$(section).css({
			left: left,
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
			$(this).height(optionsHeight);
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
	if(centerIndex == -1) {
		centerIndex = $('section#error').index();
	}
	if(window.location.search.length) {
		$('section#' + centerSlug).attr('data-permalink', window.location);
	}
	slideTo(centerIndex, false);
}

function fixScroll(section, content) {
	var scrollHeight = content[0].scrollHeight;
	var contentHeight = $(content)[0].clientHeight;
	// if content is same height as window
	if(contentHeight == $(window).innerHeight()) {
		if(scrollHeight <= contentHeight) {
			$(section).addClass('show-footer');
		} else {
			$(section).removeClass('show-footer');
		}
	}
	// if content is too short to scroll -> scroll to footer
	else if(scrollHeight <= contentHeight && scrollHeight != 0) {
		$(section).addClass('show-footer');
	}
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
	//mobile menu fix screen
	if($('body').hasClass('mobile')) {
	  $('section.open-nav header.main').css('position', 'fixed');
	}
}).on('click', '.open-nav .nav-toggle', function() {
	var section = $(this).parent('section');
	$(section).removeClass('open-nav');
	if($('body').hasClass('mobile')) {
		$('section header.main').css('position', 'absolute');
	}
});



$('body').on('click', 'nav .parent .overlay a', function(e) {
	e.preventDefault();
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
		var content = $(section).find('.content');
		var scrollHeight = content[0].scrollHeight;
		//scrolled to top of footer -> scroll in content
		if(scrollTop <= 0 && scrollHeight != $(window).innerHeight()) {
			$(section).removeClass('show-footer');
		}
	}

	if(isSmall()) {
		var navTop = $(section).find('header.main nav').offset().top;
		var navHeight = $(section).find('header.main nav').height();
		if(navTop + navHeight < 0) {
			$(section).removeClass('open-nav');
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
		var pastTop = past[0].offsetTop - 170;
		if(scrollTop > pastTop) {
			$(section).addClass('past');
			if(window.location.hash != '#past') {
				history.pushState('', document.title, window.location.href + '#past');
			}
			updateFavicons('blue');
		} else {
			if(window.location.hash == '#past') {
				history.pushState('', document.title, window.location.href.replace('#past',''));
			}
			$(section).removeClass('past');
			updateFavicons('orange');
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
	var aside = $(section).find('aside.main');
	var size = $('#size').css('content');
	if($(aside).length) {
		var asideWidth = $(aside)[0].clientWidth;
	} else {
		var asideWidth = 0;
	}

	if(section.length) {
		$('main').addClass('sliding');
		$('section.center').removeClass('center').removeClass('hover-left').removeClass('hover-right');
		$('section.right').removeClass('right');
		$('section.left').removeClass('left');
		var pageWidth = $(window).innerWidth();
		var duration = 800;
		$(section).addClass('center');
		$(section).removeClass('hide-shelves');
		$(section).css({
			zIndex: 0
		}).addClass('center');
		$(section).next().addClass('right');
		$(section).prev().addClass('left');
		if(animate) {
			$('main').transition({
				left: -pageWidth * index + (index*asideWidth),
			}, duration, 'cubic-bezier(0.645, 0.045, 0.355, 1)', function() {
				$('section:not(.center)').addClass('hide-shelves').removeClass('open-nav');
				$('section:not(.center)').find('.content').scrollTop(0);
				$('section:not(.center)').scrollTop(0);
				$('main').removeClass('sliding');
				var content = $(section).find('.content');
				var scrollHeight = content[0].scrollHeight;
				if(scrollHeight != $(window).innerHeight()) {
					$(section).removeClass('show-footer');
				}
			});
		} else {
			$('main').css({
				left: -pageWidth * index + (index*asideWidth),
			});
			$('section:not(.center)').addClass('hide-shelves');
			$('section:not(.center)').find('.content').scrollTop(0);
			$('section:not(.center)').scrollTop(0).removeClass('show-footer');
			$('main').removeClass('sliding');
		}
		var url = $(section).attr('data-permalink');
		var id = $(section).attr('data-id');
		var slug = $(section).attr('data-slug');
		$('main').attr('data-center-id', id).attr('data-center-slug', slug);
		window.history.replaceState({page: index}, null, url);
		if($(section).hasClass('past') || $(section).hasClass('error') || $(section).hasClass('past-residents')) {
			updateFavicons('blue');
		} else {
			updateFavicons('orange');
		}
	}
}

$('body').on('mouseenter', 'main:not(.waiting) aside.main .move', function() {
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
	if($('main').is('.sliding')||$('main').is('.waiting')||$(this).is('.clicked')){return;}
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
	var slug = $(nextUp).attr('id');
	if(sectionsLength > 0) {
		if(direction == 'prev') {
			getNeighbors(direction, type, slug);
		} else if(direction == 'next') {
			getNeighbors(direction, type, slug);
		}
	}
	slideTo(nextIndex, true);
	$(this).removeClass('.clicked');
});

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
///////////////////////AJAX AJAX AJAX////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////
//////////////////////////LOAD MORE//////////////////////////
/////////////////////////////////////////////////////////////
$('body').on('click', '.load-more a', function(event) {
	event.preventDefault();
	var slug = $(this).parents('section').attr('id');
	var paged = parseInt($(this).parents('section').attr('data-page')) + 1;
	loadMore(slug, paged);
});

function loadMore(slug, paged) {
	var section = $('section#'+slug);
	var params = getParams();
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	if(!vars.length) {
		vars = {
			'page': paged,
			'pagename': slug
		};
	}
	vars = $.extend(vars, params);
	if($(section).is('.events')) {
		var upcomingIds = [];
		$(section).find('.item.upcoming').each(function() {
			var upcomingId = $(this).attr('data-id');
			upcomingIds.push(upcomingId)
		});
		vars['upcoming_ids'] = upcomingIds;
		vars['events_section'] = 'past';
		vars['type'] = getParam('type');
	}
	vars['pagename'] = slug;
	if($(section).is('.sponsor')) {
		vars['pagetype'] = 'sponsor';
	}
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
			loading(vars, 'loading');
		},
		success: function(response) {
			addItems(response, vars);
			if($(section).is('#events') && $(response).find('.item').length != 0) {
				$(section).find('.wrapper.past').attr('style','');
			}
		}
	});
}

function loading(vars, classes) {
	var vars = JSON.parse(vars);
	var sectionSlug = vars.pagename;
	var section = $('section#'+sectionSlug);
	var filterLists = $(section).find('.filter-lists');
	if(!$(filterLists).find('.loader').length) {
		$(filterLists).append(loader);
	}
	$(section).addClass(classes);
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
		var contentScrollTop = $(content).scrollTop();
		var sectionScrollTop = $(section).scrollTop();
		$(section).attr('data-page', vars['paged']);
		$(section).removeClass('loading');
		$(section).find('.load-more').remove();
		$(html).each(function(i, item) {
			if(!$(item).hasClass('load-more')) {
				$(item).addClass('hide');
			}
			if($(container).hasClass('journal')) {
				$masonry.append(item).masonry('appended', item);
			} else {
				$(container).append($(item));
			}
			$(item).imagesLoaded(function() {
				setTimeout(function() {
					$(item).removeClass('hide');
				}, i*100);
				if($(container).hasClass('journal')) {
					$masonry.masonry('layout');
				}
			});
		});
		$(section).removeClass('show-footer');
		$(content).animate({ scrollTop: contentScrollTop + sectionScrollTop }, 300, 'easeOutQuart');
		$(section).animate({ scrollTop: 0 }, 300, 'easeOutQuart');
	}
}
/////////////////////////////////////////////////////////////
//////////////////////LOAD NEIGHBORS/////////////////////////
/////////////////////////////////////////////////////////////
var loadedFrom = [];
var click = 0;
//query wordpress for section 'neighbor' and insert them into main wrapper
function getNeighbors(direction, type, slug) {
	click++;
	if(direction == 'prev') {
		var section = $('section.'+type).first();
	} else if(direction == 'next') {
		var section = $('section.'+type).last();
	}
	var id = $(section).attr('data-id');
	// if(loadedFrom.indexOf(id) != -1) {
	// 	return;
	// }
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
			$('main').addClass('waiting').addClass(direction);
		},
		success: function(newSection) {
			var id = $(newSection).attr('data-id');
			if($('section[data-id="'+id+'"]').length) {
				$('main').removeClass('waiting '+direction);
				return;
			}
			$(newSection).addClass('new').attr('data-loaded-from', id);
			if(newSection.length > 0) {
				var nextSection = $('section#'+slug);
				if(direction == 'prev') {
					var deleteThis = $('section.single.'+type).last();
					var deleteCheck = [
						$(deleteThis).attr('id'),
						$(deleteThis).prev().attr('id'),
						$(deleteThis).prev().next().attr('id')
					];
					if($.inArray(slug, deleteCheck) < 0) {
						$(deleteThis).remove();
						var deletedIndex = loadedFrom.indexOf($(deleteThis).attr('data-id'));
						loadedFrom.splice(deletedIndex, 1);
					}
					$('section.'+type).first().before(newSection);
				} else if(direction == 'next') {
					var deleteThis = $('section.single.'+type).first();
					var deleteCheck = [
						$(deleteThis).attr('id'),
						$(deleteThis).next().attr('id'),
						$(deleteThis).next().next().attr('id')
					];
					if($.inArray(slug, deleteCheck) < 0) {
						$(deleteThis).remove();
						var deletedIndex = loadedFrom.indexOf($(deleteThis).attr('data-id'));
						loadedFrom.splice(deletedIndex, 1);
					}
					$('section.'+type).last().after(newSection);
				}
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
			$('main').removeClass('waiting ' + direction);
		}
	});
}
/////////////////////////////////////////////////////////////
//////////////////////////FILTER/////////////////////////////
/////////////////////////////////////////////////////////////
function insertFilterList(slug) {
	var section = $('section#'+slug);
	$(section).addClass('loading-filter');
	var params = getParams();
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	if(!vars.length) {
		vars = {
			'pagename': slug
		};
	}
	vars = $.extend(vars, params);
	vars['pagename'] = slug;
	vars['url'] = $(section).attr('data-permalink');
	if($(section).is('.sponsor')) {
		vars['pagetype'] = 'sponsor';
	}
	vars = JSON.stringify(vars);
	$.ajax({
		url: ajaxpagination.ajaxurl,
		type: 'post',
		data: {
			action: 'insert_filter_list',
			query_vars: vars,
		},
		success: function(response) {
			$(section).find('.filter-lists').html(response);
		}
	});
}

var filterVars = [
	'when',
	'date',
	'country',
	'from',
	'residency_program',
	'type',
	'filter'
];
$('body').on('click', 'section:not(#apply) .filter-list .option a', function(event) {
	event.preventDefault();
	var option = $(this).parents('.option');
	var optionText = $(this).text();
	var optionType = $(this).parents('.filter-list').attr('data-filter');
	var optionValue = $(this).attr('data-value');
	var slug = $(this).parents('section').attr('id');
	var section = $(this).parents('section');
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	$(filterVars).each(function() {
		delete vars[this];
	});
	if($(section).is('.events')) {
		var upcomingIds = [];
		$(section).find('.item.upcoming').each(function() {
			var upcomingId = $(this).attr('data-id');
			upcomingIds.push(upcomingId);
		});
		vars['upcoming_ids'] = upcomingIds;
		vars['events_section'] = 'past';
	}
	var url = $(this).attr('href');
	if($(option).is('.selected')) {
		$(option).removeClass('selected');
		$(section).find('.filter .select.' + optionType + ' .count').text('');
 		var currentUrl = window.location.href;
 		url = removeParam(optionType, currentUrl);
 		if (url.substring(url.length-1) == '?') {
	        url = url.substring(0, url.length-1);
	    }

	} else {
		$(section).find('.filter-list.'+optionType+' .option.selected').removeClass('selected');
		$(option).addClass('selected');
		var filterType = $(this).parents('.filter-list').attr('data-filter');
		var filterCount = $(section).find('.select[data-filter="'+filterType+'"]').find('.count');
		$(filterCount).text(': '+optionText);
	}
	var params = getParams(url);
	$.each(params, function(key, value) {
		vars[key] = value;
	});
	vars['filter_params'] = JSON.stringify(params);
	if($(section).is('.sponsor')) {
		vars['pagetype'] = 'sponsor';
	}
	vars['pagename'] = slug;
	updateCounts(option, vars);
	if(isSmall()) {
		$(section).find('.select[data-filter="'+filterType+'"]').click();
	}
	filterQuery(vars, section, url, option);
});

$('body').on('click', '.bar:not(.hide) .tags a', function(event) {
	event.preventDefault();
	var section = $(this).parents('section');
	var url = this.href;
	var tag = getParam('tag', url);
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	vars['tag'] = tag;
	$('#journal .bar.hide').removeClass('hide');
	$('.select.tag span').html('&#34' + tag + '&#34');
	filterQuery(vars, section, url);
});

$('body').on('click', '.bar:not(.hide) .select.tag', function(event) {
	var section = $(this).parents('section');
	var currentUrl = window.location.href;
	var vars = ajaxpagination.query_vars;
	vars = JSON.parse(vars);
	delete vars['tag'];
	$('#journal .bar').addClass('hide');
	url = removeParam('tag', currentUrl);
	filterQuery(vars, section, url);
});

function filterQuery(vars, section, url, option) {
	vars = JSON.stringify(vars);
	$.ajax({
		url: ajaxpagination.ajaxurl,
		type: 'post',
		data: {
			action: 'filter_items',
			query_vars: vars,
			page: 1
		},
		beforeSend: function() {

		},
		success: function(response) {
			var container = $(section).find('.filter-this');
			var transitionEnd = 'transitionend webkitTransitionEnd oTransitionEnd';
			var items = $(container).find('.item');
			$(container).addClass('removing');
			$(section).one(transitionEnd, function(e) {
				$(container).removeClass('removing');
				if($(section).is('.journal')) {
					$(container).masonry('remove', items);
					$masonry.masonry('layout');
				} else {
					$(container).html('');
					if($(option).length) {
						updateFilterLinks(option, vars);
					}
					filterThis(response, vars);
					if($(section).is('#events') && $(response).find('.item').length != 0) {
						$(section).find('.wrapper.past').attr('style','');
					}
				}
				$(section).off(transitionEnd);
			});
			loading(vars, 'loading filtering');
			window.history.pushState({path:url},'',url);
			$(section).attr('data-permalink', url);
		}
	});
}

function filterThis(html, vars) {
	if($(html).length > 0) {
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
		var contentScrollTop = $(content).scrollTop();
		var sectionScrollTop = $(section).scrollTop();
		$(section).attr('data-page', 1);
		$(section).removeClass('loading');
		$(section).find('.load-more').remove();
		$(html).each(function(i, item) {
			if(!$(item).hasClass('load-more')) {
				$(item).addClass('hide');
			}
			if($(container).hasClass('journal')) {
				$masonry.append(item).masonry('appended', item);
			} else {
				$(container).append($(item));
			}
			$(item).imagesLoaded(function() {
				setTimeout(function() {
					$(item).removeClass('hide');
				}, i*100);
				if($(container).hasClass('journal')) {
					$masonry.masonry('layout');
				}
			});
		});

		$(section).removeClass('show-footer filtering');
		$(content).animate({ scrollTop: contentScrollTop + sectionScrollTop }, 300, 'easeOutQuart');
		$(section).animate({ scrollTop: 0 }, 300, 'easeOutQuart');
		// keep content scrolled to bottom when footer is visible
		if($(section).hasClass('show-footer')) {
			$(content).scrollTop(scrollHeight);
		}
		fixScroll(section, content);
	}
}

function updateFilterLinks(option) {
	var selected = $(option).is('.selected');
	var section = $(option).parents('section');
	var thisList = $(option).parents('.filter-list').attr('data-filter');
	var filterType = $(option).parents('.filter-list').attr('data-filter');
	var filterValue = $(option).find('a').attr('data-value');
	$(section).find('.filter-list:not([data-filter="' + thisList + '"]) .option a').each(function() {
		var url = $(this).attr('href');
		filterType = renameFilterType(filterType);
		if(getParam(filterType, url)) {
			url = removeParam(filterType, url);
		}
		if(selected) {
			url = url + '&' + filterType + '=' + filterValue;
		}
		$(this).attr('href', url);
	});
}

function renameFilterType(filterType) {
	switch(filterType) {
		case 'country':
			return 'from';
			break;
		case 'year':
			return 'date';
			break;
	}
	return filterType;
}

$('body').on('click', '.filter .select:not(.tag)', function() {
	var slug = $(this).attr('data-slug');
	var filterThis = $('.filter-this.'+slug);
	var section = $(filterThis).parents('section');
	var content = $(section).find('.content');
	if($(this).hasClass('view toggle')) {
		//toggle view style
		$(filterThis).toggleClass('list').toggleClass('grid');
		$(this).toggleClass('list').toggleClass('grid');
		fixScroll(section, content);
	} else if($(this).hasClass('dropdown')) {
		var property = $(this).attr('data-filter');
		var filterList = $('.filter-list.'+property+'.'+slug);
		var filterListOptions = $(filterList).children('.options');
		if(!filterListOptions.length) {return;}
		var filterListOptionsHeight = $(filterListOptions)[0].clientHeight;
		if($(this).hasClass('dropped')) {
			//toggle to hide this list
			$(this).removeClass('dropped');
			$(filterList).removeClass('show').css({height : 0});
			$(filterList).one('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function() {
				fixScroll(section, content);
			});
		} else {
			//hide already opened filter list
			$('.dropdown').removeClass('dropped');
			$('.filter-list.show'+'.'+slug).removeClass('show').css({height : 0});
			//open this filter list
			$(this).addClass('dropped');
			$(filterList).addClass('show growing').css({height : filterListOptionsHeight});
			$(filterList).one('transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd', function() {
				fixScroll(section, content);
				$(filterList).removeClass('growing');
			});
		}

	}
});
function getParam(paramType, url) {
    if (!url) url = window.location.href;
    paramType = paramType.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + paramType + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return '';
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
function getParams(url) {
	if (!url) url = window.location.href;
    var params = {}, hash;
    var hashes = url.slice(url.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        params[hash[0]] = hash[1];
    }
    return params;
}
function removeParam(paramType, url) {
	if(paramType=='year'){paramType='date';}
	if(paramType=='country'){paramType='from';}
    var rtn = url.split("?")[0],
        param,
        params_arr = [],
        queryString = (url.indexOf("?") !== -1) ? url.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === paramType) {
                params_arr.splice(i, 1);
            }
        }
        rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}
function updateCounts(option, vars) {
	// var section = $(option).parents('section');
	// var filterType = $(option).parents('.filter-list').attr('data-filter');
	// var filterValue = $(option).find('a').attr('data-value');
	// var url = $(option).find('a').attr('href');
	// var params = getParams(url);
	// $(section).find('.filter-list .option a').each(function() {

	// });
	// var option = this;
	// vars = JSON.stringify(vars);
	// getCount(vars);
}
function getCount(vars) {
	$.ajax({
		url: ajaxpagination.ajaxurl,
		type: 'post',
		data: {
			action: 'get_filter_count',
			query_vars: vars
		},
		beforeSend: function() {

		},
		success: function(response) {

		}
	});
}
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
//////////////////////////SEARCH/////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
$('body').on('mouseenter click touchstart', 'form', function() {
	var input = $(this).find('input:not(.no)');
	var value = $(input).attr('value');
	var placeholder = $(this).find('.placeholder');
	$(placeholder).css({'opacity':0});
	$(input).focus();
}).on('mouseleave','form', function() {
	var input = $(this).find('input:not(.no)');
	var value = $(input).attr('value');
	var placeholder = $(this).find('.placeholder');
	if (!/\S/.test(value)) {
		$(placeholder).css({'opacity':1});
		$(input).siblings('.counter').html('');
	}
	if(!$(input).is('.main-search')) {
		$(input).blur();
	}
});

var navTimer;
$('body').on('keydown', 'form input:not(.no)', function() {
	var input = this;
	var value = input.value;
	var placeholder = $(input).attr('data-placeholder');
	if(value === placeholder) {
		$(input).attr('value', '');
		$(input).siblings('.counter').html('');
	}
});

$('body').on('keyup', 'nav .search input:not(.no)', function() {
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
	vars.pagename = 'search';
	vars = JSON.stringify(vars);
	if(/\S/.test(text)) {
		mainTimer = setTimeout(function() {

			// $.ajax({
			// 	url: ajaxpagination.ajaxurl,
			// 	type: 'post',
			// 	data: {
			// 		action: 'get_search_count',
			// 		query_vars: vars
			// 	},
			// 	success: function(count) {
			// 		var counter = $(section).find('.title .counter');
			// 		var value = $(section).find('.title .value');
			// 		$(counter).text(count);
			// 		$(value).text(text);
			// 	}
			// });

			$.ajax({
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'update_search_results',
					query_vars: vars
				},
				beforeSend: function() {
					$(section).addClass('loading');
					$(section).find('.results').html(loader);
				},
				success: function(response) {
					$(section).removeClass('loading');
					addItems(response, vars);
					if (history.pushState) {
						var s = JSON.parse(vars)['s'];
					    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?s=' + s;
					    window.history.pushState({path:newurl},'',newurl);
					}
				}
			});
		}, 200);
	} else {
		var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?s=' + '';
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
function setUpImageSlider() {
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
	setImageSliderSize();
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

function setImageSliderSize() {
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
		$(this).css({
			width:sliderWidth,
		});
		if($(slider).is('.full')) {
			$(this).css({
				height: $(window).innerHeight()
			});
			$(this).find('.landscape').each(function() {
				$(this).css({
					'height': $(window).innerHeight() - 200
				}).find('.captionWrap').css({
					'max-height': $(window).innerHeight() - 200
				}).find('img').css({
					'max-height': $(window).innerHeight() - 200
				});
			});
		}
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

$('body').on('mousemove', '.gallery:not(.full) ', function(e) {
	var cursor = $(this).find('.cursor');
	if ($(this).find('.image img:hover').length != 0 || $(cursor).is(':hover')) {
		var x = e.clientX==undefined?e.layerX:e.clientX;
		var y = e.clientY==undefined?e.layerY:e.clientY;
		$(cursor).attr('data-icon', 'zoom');
		$(cursor).css({
			'left': x,
			'top': y,
			'display': 'block',
			'cursor': 'none !important'
		});
	} else {
		$(cursor).css({
			'display': 'none'
		});
	}
}).on('mouseleave', '.gallery:not(.full)', function() {
	var cursor = $(this).find('.cursor');
	$(cursor).css({
		'display': 'none'
	});
});

$('body').on('click', '.gallery:not(.full) img', function() {
	var index = $(this).parents('.slide').index();
	var gallery = $(this).parents('.gallery');
	$(gallery).attr('data-show', index).addClass('full').addClass('image_slider').css({'cursor':'none'});
	var cursor = $(gallery).find('.cursor');
	var slidesLength = $(gallery).find('.slide').length;
	setUpImageSlider();
	$(cursor).css({'display':'none'});
	if(isSmall()) {
		var distance = 75;
	} else {
		var distance = 200;
	}
	$(gallery).on('mousemove', function(event) {
		var x = event.pageX;
		var y = event.pageY;
		if(x >= window.innerWidth - distance && slidesLength > 1) {
			$(cursor).attr('data-icon', 'right');
		} else if(x <= distance && slidesLength > 1) {
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
				$(this).off('click');
				$(this).off('mousemove');
				closeFullSLider(this);
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
		if(isMobile && !$(event.target).is('.arrow') && !$(event.target).is('.icon')) {
			$(this).off('click');
			$(this).off('mousemove');
			closeFullSLider(this);
		}

	});
});

function closeFullSLider(slider) {
	var cursor = $(slider).find('.cursor');
	$(slider).removeClass('full')
	.attr('style','')
	.find('.slides').attr('style','')
	.find('.slide').attr('style','');
	$(cursor).attr('style','');
	$(slider).find('.landscape').each(function() {
		$(this).attr('style','');
		$(this).find('.captionWrap').attr('style','');
		$(this).find('img').attr('style','');
	});
	if($(slider).hasClass('stack')) {
		$(slider).removeClass('image_slider');
	} else {
		setUpImageSlider();
	}
}

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
      // minZoom: 0,
      // maxZoom: 1,
      tms: true,
      atmosphere: true
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
    var markerUrl = null;
    var markerTemp = $('#markerTemp').html();
    $('section#map').addClass('show');
    setTimeout(function() {
    	$('#mapWrap').addClass('show');
    },1000);
    $(countries).each(function(i, country) {
    	var name = country['name'];
    	var slug = country['slug'];
    	var count = country['count'];
    	var lat = country['lat'];
    	var lng = country['lng'];
    	if(isNumeric(lat) && isNumeric(lng)) {
    		var marker = WE.marker([lat, lng], markerUrl, 30, 30).addTo(earth);
    		var markerHtml = marker.element;
    		var inner = $(markerHtml).find('.we-pm-icon');
    		$(markerHtml).addClass('marker')
    		.attr('data-slug', slug)
    		.attr('data-name', name)
    		.attr('data-count', count)
    		.attr('data-lat', lat)
    		.attr('data-lng', lng);
    		$(inner).html(markerTemp);
    		$(inner).find('.count span').text(count);
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
    }).on('mouseenter', '.marker:not(.teasing)', function() {
    	$(this).addClass('hover');
    }).on('mouseleave', '.marker', function() {
    	$(this).removeClass('hover');
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
    	var lat = $(this).attr('data-lat');
    	var lng = $(this).attr('data-lng');
    	if($(residents).hasClass('show')) {
    		$(residents)
    		.removeClass('show')
    		.attr('style','')
    		.css({'height':'auto'})
    		.transition({
				y: 0
			}, function() {
				$(residentsHead).html(name+' ('+count+')');
			});
    	} else {
    		$(residentsHead).html(name+' ('+count+')');
    	}
    	earth.panTo([lat, lng], 5000);
    	$(marker).addClass('showing');
    	if (history.pushState) {
		    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?where=' + slug;
		    window.history.pushState({path:newurl},'',newurl);
		}
    	getMapList(slug);
    }).on('click', '.close', function() {
    	$(residents).removeClass('show').transition({y:0}, function() {
    		$(this).attr('style','');
    	});
    	$('.marker.showing').removeClass('showing');
    	if (history.pushState) {
		    var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
		    window.history.pushState({path:newurl},'',newurl);
		}
    }).on('click', '.zoom', function() {
    	var zoom = earth.getZoom();
    	var factor = 1;
    	if($(this).is('.out')) {
    		factor = -1;
    	}
    	var zoomTo = zoom + factor;
    	var before = null;
    	var delta = zoom;
        requestAnimationFrame(function animate() {
            delta+= 0.05*factor;
            earth.setZoom(delta);
            if(factor == 1) {
	            if(delta <= zoomTo) {
	            	requestAnimationFrame(animate);
	            }
	        } else {
	        	if(delta >= zoomTo) {
	            	requestAnimationFrame(animate);
	            }
	        }
        });
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

		},
		success: function(response) {
			$(residentsList).html(response);
			var residentsHeight = $(residents).innerHeight();
			if(residentsHeight > winH() - 110) {
				residentsHeight = 'calc(100% - 110px)';
			}
			$(residents).addClass('show').height(residentsHeight).transition({
				y: '-100%'
			}).removeClass('tease');
		}
	});
}
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
///////////////////////RESOURCES/////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
function scrollToResourceItem() {
	var hash = parseInt(window.location.hash.replace('#', ''));
	var index = hash - 1;
	var section = $('section.resource');
	var content = $(section).find('.content');
	var item = $(section).find('.item').eq(index);
	var offset;
	if(index == 0) {
		var itemTop = 0;
	} else {
		var itemTop = $(item).offset().top - 110;
	}
	$(content).scrollTop(itemTop);
}

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////

function updateFavicons(newColor) {
	$('link[data-update]').each(function() {
		var href = $(this).attr('href');
		if(newColor=='orange') {
			currentColor = 'blue';
		} else {
			currentColor = 'orange';
		}
		var newHref = href.replace(currentColor, newColor);
		$(this).attr('href', newHref);
	});
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function winW() {
	return window.innerWidth;
}

function winH() {
	return window.innerHeight;
}

function isSmall() {
	var size = $('#size').css('content');
	if(size == 'xsmall' || size == 'small') {
		return true;
	} else {
		return false;
	}
}


$(window).resize(function() {
	setUp();
	setImageSliderSize();
	if($('section.journal').length) {
		$masonry.masonry('layout');
	}
});


function mobileCheck() {
	if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
	    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
		$('body').addClass('mobile');
	    isMobile = true;
	}
}

});
