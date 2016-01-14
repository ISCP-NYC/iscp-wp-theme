<?php
	$center_resident = $post;
	$center_resident_id = $center_resident->ID;
	get_residents( $center_resident_id, 'prev', 3 );
	setup_postdata( $center_resident );
	get_template_part('sections/resident');
	wp_reset_postdata();
	get_residents( $center_resident_id, 'next', 3 );
?>