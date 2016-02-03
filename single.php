<?php
global $post;
get_header();
$post_type = get_post_type();
switch($post_type) {
	case 'resident':
		$this_resident = $post;
		$this_resident_id = $this_resident->ID;
		if( is_current( $this_resident_id ) ):

			// current residents list
			$current_residents_page_id = get_page_by_path('current-residents')->ID;
			$post = get_post( $current_residents_page_id, OBJECT );
			setup_postdata( $post );
			get_template_part( 'sections/residents' );
			wp_reset_postdata();

			//previous current residents by studio number
			insert_neighbor_residents( $this_resident_id, 'prev', 3 );

			//opened current resident
			setup_postdata( $this_resident );
			get_template_part('sections/resident');
			wp_reset_postdata();

			//next current residents by studio number
			insert_neighbor_residents( $this_resident_id, 'next', 3 );

			// current residents list
			$current_residents_page_id = get_page_by_path('current-residents')->ID;
			$post = get_post( $current_residents_page_id, OBJECT );
			setup_postdata( $post );
			get_template_part( 'sections/residents' );
			wp_reset_postdata();

		elseif( is_past( $this_resident_id ) ):
			$past_residents_page_id = get_page_by_path('past-residents')->ID;
			$post = get_post( $past_residents_page_id, OBJECT );
			setup_postdata( $post );
			get_template_part( 'sections/residents' );
			wp_reset_postdata();

			setup_postdata( $this_resident );
			get_template_part('sections/resident');
			wp_reset_postdata();

		endif;
		break;
	case 'event':
		get_template_part('sections/event');
		break;
	case 'sponsor':
		get_template_part('sections/sponsor');
		break;
	case 'journal':

		//all journal posts
		$journal_page_id = get_page_by_path('journal')->ID;
		$post = get_post( $journal_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/journals' );
		wp_reset_postdata();

		$this_post = $post;
		$this_post_id = $this_post->ID;

		//newer journal posts
		insert_neighbor_journal_posts( $this_post_id, 'new' );

		//opened journal post
		setup_postdata( $this_post );
		get_template_part('sections/journal');
		wp_reset_postdata();

		//older journal posts
		insert_neighbor_journal_posts( $this_post_id, 'old' );

		//all journal posts
		$journal_page_id = get_page_by_path('journal')->ID;
		$post = get_post( $journal_page_id, OBJECT );
		setup_postdata( $post );
		get_template_part( 'sections/journals' );
		wp_reset_postdata();

		break;
}
get_footer();
?>