<?php
	$this_resident = $post;
	$this_resident_id = $this_resident->ID;

	if( is_current( $this_resident_id ) ):
		get_residents( $this_resident_id, 'prev', 3 );
		setup_postdata( $this_resident );
		get_template_part('sections/resident');
		wp_reset_postdata();
		get_residents( $this_resident_id, 'next', 3 );
	elseif( is_past( $this_resident_id ) ):

		$alumni = get_page_by_path('alumni');
		setup_postdata( $alumni );
		get_template_part( 'sections/residents' );
		wp_reset_postdata();


		setup_postdata( $this_resident );
		get_template_part('sections/resident');
		wp_reset_postdata();

	endif;
?>