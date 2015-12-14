<?php 
	get_header();

	$post_type = get_post_type();
	$id = get_the_ID();
	switch($post_type) {
		case 'resident':
			get_template_part('paginate/next_residents');
			break;
		case 'event':
			get_template_part('sections/event');
			break;
	}
	
	get_footer();
?>
