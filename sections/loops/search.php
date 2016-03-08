<?php
if( $query_vars ):
	$s = $query_vars['s'];
	$paged = $query_vars['paged'];
else:
	$s = get_query_var( 's' );
endif;
$search_query = new WP_Query( array(
	's' => $s,
	'posts_per_page' => 24,
	'paged' => $paged,
	'post_status' => 'publish'
) );
$GLOBALS['wp_query'] = $search_query;
$last_page = $search_query->max_num_pages;
if( have_posts() ):
	while ( have_posts() ) :
		the_post();
		get_template_part( 'sections/items/search');
	endwhile;
	if( $paged < $last_page ):
		get_template_part('partials/load-more');
	endif;
else:
	get_template_part( 'partials/no-posts' );
endif;
?>