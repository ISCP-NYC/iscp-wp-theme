jQuery(document).ready(function($) {
	$('body').on('click', '.load-more a', function(event) {
		event.preventDefault();
		var paged = parseInt($(this).parents('section').attr('data-page')) + 1;
		var slug = $(this).parents('section').attr('id');
		var vars = ajaxpagination.query_vars;
		vars = JSON.parse(vars);
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
				replaceContent(response, vars);
			}
		})
	});
	function loading(vars) {
		var vars = JSON.parse(vars);
		var sectionSlug = vars.pagename;
		var section = $('section#'+sectionSlug);
		var shelves = $(section).find('.shelves');
		$(section).addClass('loading');
	}
	function replaceContent(html, vars) {
		var vars = JSON.parse(vars);
		var sectionSlug = vars.pagename;
		var section = $('section#'+sectionSlug);
		var content = $(section).find('.content');
		var shelves = $(section).find('.shelves');
		var paged = parseInt($(section).attr('data-page')) + 1;
		$(section).attr('data-page', paged);
		$(section).removeClass('loading');		
		$(shelves).append(html);
		var scrollHeight = content[0].scrollHeight;
		if($(section).hasClass('show-footer')) {
			$(content).scrollTop(scrollHeight);
		}
	}
});