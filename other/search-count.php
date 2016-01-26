<?php
if( $query_vars ):
	$text = $query_vars['s'];
	$search = new WP_Query( array(
		's' => $text,
		'posts_per_page' => -1,
		'post_status' => 'publish'
	) ); 
	$search_count = $search->found_posts;
	$response = array(
		'count' => $search_count, 
		'text' => $text
	);
	echo json_encode($response);
	wp_reset_query();
endif;
?>