<?php
if( $query_vars ):
	$text = $query_vars['s'];
	$paged = $query_vars['paged'];
else:
	$text = get_query_var( 's' );
endif;
$search_query = new WP_Query( array(
	's' => $text,
	'posts_per_page' => 24,
	'paged' => $paged,
	'post_status' => 'publish'
) );
$GLOBALS['wp_query'] = $search_query;
while ( have_posts() ) :
	the_post();
	get_template_part( 'sections/items/search');
endwhile;
?>