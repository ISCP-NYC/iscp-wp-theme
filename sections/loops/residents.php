<?php
include( locate_template( 'sections/params/residents.php' ) );
$residents_query = array_merge( $query, $orderby_array );
$residents = new WP_Query( $query );
$GLOBALS['wp_query'] = $residents;
$last_page = $residents->max_num_pages;
if( have_posts() ):
	while ( have_posts() ) :
		the_post();
		get_template_part( 'sections/items/resident' );
	endwhile;
	if( $paged < $last_page ):
		get_template_part('partials/load-more');
	endif;
else:
	get_template_part( 'partials/no-posts' );
endif;
wp_reset_query(); 
?>