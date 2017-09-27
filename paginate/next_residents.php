<?php
	$next_residents = get_residents( $id, 'next', 10 );
	while ( $next_residents->have_posts() ) : $next_residents->the_post();
		global $post;
		setup_postdata( $post );
		get_template_part('sections/resident');
		wp_reset_postdata();
	endwhile;
?>