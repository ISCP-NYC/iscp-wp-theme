<?php
	get_header();
	global $post;
	$page_slug = get_post( $post )->post_name;
	$parent = $post->post_parent;
	$parent_slug = get_post( $parent )->post_name;
	$resident_types = array( 'current-residents', 'past-residents' );
	$event_types = array( 'events', 'exhbitions', 'iscp-talks', 'offsite-projects', 'open-studios' );
	
	if( $page_slug == 'current-residents' ):

		$programs_page_id = get_page_by_path( 'residency-programs' )->ID;
		$post = get_post( $programs_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/programs' );
		wp_reset_postdata();

		$post->delay = 0;
		get_template_part( 'sections/residents' );

		$past_residents_page_id = get_page_by_path( 'past-residents' )->ID;
		$post = get_post( $past_residents_page_id, OBJECT );
		$post->delay = 1;
		setup_postdata( $post );
		get_template_part( 'sections/residents' );
		wp_reset_postdata();

	elseif ( $page_slug == 'past-residents' ):

		$current_residents_page_id = get_page_by_path( 'current-residents' )->ID;
		$post = get_post( $current_residents_page_id, OBJECT );
		setup_postdata( $post );
		$post->delay = 1;
		get_template_part( 'sections/residents' );
		wp_reset_postdata();

		$post->delay = 0;
		get_template_part( 'sections/residents' );

	elseif ( $page_slug == 'residents' ):

		$past_residents_page_id = get_page_by_path( 'past-residents' )->ID;
		$post = get_post( $past_residents_page_id, OBJECT );
		setup_postdata( $post );
		$post->delay = 1;
		get_template_part( 'sections/residents' );
		wp_reset_postdata();

		$post->delay = 0;
		get_template_part( 'sections/residents' );

		$current_residents_page_id = get_page_by_path( 'current-residents' )->ID;
		$post = get_post( $current_residents_page_id, OBJECT );
		setup_postdata( $post );
		$post->delay = 1;
		get_template_part( 'sections/residents' );
		wp_reset_postdata();

	elseif( $page_slug == 'events' ):

		$post->delay = 0;
		get_template_part( 'sections/events' );

		$visit_page_id = get_page_by_path( 'visit' )->ID;
		$post = get_post( $visit_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/visit' );
		wp_reset_postdata();

	elseif( $page_slug == 'journal' ):
		$post->delay = 0;
		get_template_part( 'sections/journals' );	

	elseif( $page_slug == 'sponsors' ):

		get_template_part( 'sections/sponsors' );		

	elseif( $page_slug == 'contributors' ):

		get_template_part( 'sections/contributors' );		

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

	elseif( $parent_slug == 'public-programs' ):

		get_template_part( 'sections/programs' );
	
		$events_page_id = get_page_by_path( 'events' )->ID;
		$post = get_post( $events_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/events' );
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

	elseif( $page_slug == 'donate' ):

		get_template_part( 'sections/donate' );

	elseif( $page_slug == 'faq' ):

		get_template_part( 'sections/faq' );

	elseif( $page_slug == 'greenroom'):

		if (user_is_resident()):
			get_template_part( 'sections/greenroom' );
		else:
			get_template_part( 'sections/login' );
		endif;

	elseif( $page_slug == 'visiting-critics'):

		get_template_part( 'sections/critics' );

	elseif( in_array( $page_slug, array( 'at-iscp', 'in-nyc', 'staff-messages', 'to-do' ) ) ):

		if (user_is_resident()):
			$greenroom_page_id = get_page_by_path( 'greenroom' )->ID;
			$post = get_post( $greenroom_page_id, OBJECT );
			setup_postdata( $post );
			get_template_part( 'sections/greenroom' );
			wp_reset_postdata();

			$page_slug = str_replace( '-', '_', $page_slug );
			$resource_page_id = get_page_by_path( 'greenroom/' . $page_slug )->ID;
			$post = get_post( $resource_page_id, OBJECT );
			setup_postdata( $post );
			get_template_part( 'sections/resource' );
			wp_reset_postdata();
		else:
			get_template_part( 'sections/login' );
		endif;

	elseif ( $page_slug == 'map' ):

		get_template_part( 'sections/map' );

	elseif ( $page_slug == 'export' ):

		get_template_part( 'sections/export' );

	elseif ( $parent_slug ):

		get_template_part( 'sections/page' );

	else:

		get_template_part( 'sections/error' );

	endif;

get_footer(); ?>
