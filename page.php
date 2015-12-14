<?php
	get_header();
	global $post;
	$slug = get_post( $post )->post_name;
	$parent = $post->post_parent;
	$parent_slug = get_post( $parent )->post_name;

	$resident_types = array( 'current-residents', 'alumni' );
	$event_types = array( 'events', 'exhbitions', 'iscp-talks', 'off-site-projects', 'open-studios' );

	if( in_array( $slug, $resident_types ) ):
		get_template_part( 'sections/residents' );
	elseif( in_array( $slug, $event_types ) ):
		get_template_part( 'sections/events' );
	elseif( $parent_slug == 'residency-programs' ):
		get_template_part( 'sections/programs' );
	elseif( $slug == 'apply' ):
		get_template_part( 'sections/apply' );
	elseif( $slug == 'about' ):
		get_template_part( 'sections/about' );
	elseif( $slug == 'visit' ):
		get_template_part( 'sections/visit' );
	elseif( $slug == 'resident-resources'):
		if (user_is_resident()):
			get_template_part( 'sections/resident-resources' );
		else:
			get_template_part( 'sections/login' );
		endif;
	endif;

get_footer(); ?>
