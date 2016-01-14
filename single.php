<?php
	global $post;
	get_header();
	$post_type = get_post_type();
	switch($post_type) {
		case 'resident':
			$this_resident = $post;
			$this_resident_id = $this_resident->ID;
			if( is_current( $this_resident_id ) ):
				get_residents( $this_resident_id, 'prev', 3 );
				setup_postdata( $this_resident );
				get_template_part('sections/resident');
				wp_reset_postdata();
				get_residents( $this_resident_id, 'next', 3 );
			elseif( is_alumni( $this_resident_id ) ):
				$alumni_id = get_page_by_path('alumni')->ID;
				$post = get_post( $alumni_id, OBJECT );
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
	}
	get_footer();
?>
