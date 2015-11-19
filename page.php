<?php
	get_header();
	global $post;
	$slug = get_post( $post )->post_name;
	$parent = $post->post_parent;
	$parent_slug = get_post( $parent )->post_name;

	$resident_types = array( 'current-residents', 'alumni' );

	if( in_array( $slug, $resident_types ) ):
		get_template_part( 'sections/residents' );
	elseif( $parent_slug == 'residency-programs' ):
		get_template_part( 'sections/programs' );
	elseif( $slug == 'apply' ):
		get_template_part( 'sections/apply' );
	endif;

get_footer(); ?>
