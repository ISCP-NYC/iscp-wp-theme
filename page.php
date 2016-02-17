<?php
	get_header();
	global $post;
	$page_slug = get_post( $post )->post_name;
	$parent = $post->post_parent;
	$parent_slug = get_post( $parent )->post_name;
	$resident_types = array( 'current-residents', 'past-residents' );
	$event_types = array( 'events', 'exhbitions', 'iscp-talks', 'off-site-projects', 'open-studios' );
	if( $page_slug == 'current-residents' ):

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

	elseif ( $page_slug == 'past-residents' ):

		$current_residents_page_id = get_page_by_path( 'current-residents' )->ID;
		$post = get_post( $current_residents_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/residents' );
		wp_reset_postdata();

		get_template_part( 'sections/residents' );

		// $residents_map_page_id = get_page_by_path('map')->ID;
		// $post = get_post( $residents_map_page_id, OBJECT );
		// setup_postdata( $post );
		// get_template_part( 'sections/map' );
		// wp_reset_postdata();

	elseif( in_array( $page_slug, $event_types ) ):

		get_template_part( 'sections/events' );

		$visit_page_id = get_page_by_path( 'visit' )->ID;
		$post = get_post( $visit_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/visit' );
		wp_reset_postdata();

	elseif( $page_slug == 'journal' ):

		get_template_part( 'sections/journals' );	

	elseif( $page_slug == 'sponsors' ):

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

	elseif( $page_slug == 'apply' ):

		$programs_page_id = get_page_by_path( 'residency-programs' )->ID;
		$post = get_post( $programs_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/programs' );
		wp_reset_postdata();

		get_template_part( 'sections/apply' );

		$sponsors_page_id = get_page_by_path( 'support/sponsors' )->ID;
		$post = get_post( $sponsors_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/sponsors' );
		wp_reset_postdata();

	elseif( $page_slug == 'about' ):

		get_template_part( 'sections/about' );

	elseif( $page_slug == 'visit' ):

		get_template_part( 'sections/visit' );

	elseif( $page_slug == 'resident-resources'):

		if (user_is_resident()):
			get_template_part( 'sections/resources' );
		else:
			get_template_part( 'sections/login' );
		endif;

	elseif( in_array( $page_slug, array( 'at-iscp', 'in-nyc', 'staff-messages', 'to-do' ) ) ):

		if (user_is_resident()):
			$resources_page_id = get_page_by_path( 'resident-resources' )->ID;
			$post = get_post( $resources_page_id, OBJECT );
			setup_postdata( $post );
			get_template_part( 'sections/resources' );
			wp_reset_postdata();

			$page_slug = str_replace( '-', '_', $page_slug );
			$resource_page_id = get_page_by_path( 'resident-resources/' . $page_slug )->ID;
			$post = get_post( $resource_page_id, OBJECT );
			setup_postdata( $post );
			get_template_part( 'sections/resource' );
			wp_reset_postdata();
		else:
			get_template_part( 'sections/login' );
		endif;

	elseif ( $page_slug == 'map' ):

		get_template_part( 'sections/map' );

	endif;

get_footer(); ?>
