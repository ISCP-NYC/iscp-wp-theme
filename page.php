<?php
	get_header();
	global $post;
	$slug = get_post( $post )->post_name;
	$parent = $post->post_parent;
	$parent_slug = get_post( $parent )->post_name;

	$resident_types = array( 'current-residents', 'past-residents' );
	$event_types = array( 'events', 'exhbitions', 'iscp-talks', 'off-site-projects', 'open-studios' );
	
	if( $slug == 'current-residents' ):

		$programs_page_id = get_page_by_path( 'residency-programs' )->ID;
		$post = get_post( $programs_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/programs' );
		wp_reset_postdata();

		get_template_part( 'sections/residents' );

		$past_residents_page_id = get_page_by_path( 'past-residents' )->ID;
		$post = get_post( $past_residents_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/residents' );
		wp_reset_postdata();

	elseif ( $slug == 'past-residents' ):

		$current_residents_page_id = get_page_by_path( 'current-residents' )->ID;
		$post = get_post( $current_residents_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/residents' );
		wp_reset_postdata();

		get_template_part( 'sections/residents' );

	elseif( in_array( $slug, $event_types ) ):

		get_template_part( 'sections/events' );

	elseif( $slug == 'sponsors' ):

		get_template_part( 'sections/sponsors' );		

	elseif( $parent_slug == 'residency-programs' ):

		$current_residents_page_id = get_page_by_path( 'current-residents' )->ID;
		$post = get_post( $current_residents_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/residents' );
		wp_reset_postdata();

		get_template_part( 'sections/programs' );

		$apply_page_id = get_page_by_path( 'apply' )->ID;
		$post = get_post( $apply_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/apply' );
		wp_reset_postdata();

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
