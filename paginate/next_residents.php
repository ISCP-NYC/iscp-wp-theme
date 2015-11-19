<?php
	
	
	$next_residents = get_next_residents( '' );
	
	while ( $next_residents->have_posts() ) : $next_residents->the_post();

		$id = get_the_ID();
		$title = get_the_title( $id );
		$country = ucwords( get_field('country_temp', $id ) );
		$sponsor = get_field( 'sponsor_temp', $id );
		$studio_number = get_field( 'studio_number', $id );
		$url = get_permalink();

		if($append_query && is_alum( $id )) {
			$url .= $append_query;
		}

		get_template_part('sections/resident');

	endwhile;

?>