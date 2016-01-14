<?php
	global $post;
	get_header();
	$post_type = get_post_type();
	switch($post_type) {
		case 'resident':
			get_template_part('paginate/neighbor_residents');
			break;
		case 'event':
			get_template_part('sections/event');
			break;
	}
	get_footer();
?>
