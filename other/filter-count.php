<?php
if( $query_vars ):
	print_r($query_vars);
	// $text = $query_vars['s'];
	// $search = new WP_Query( array(
	// 	's' => $text,
	// 	'posts_per_page' => -1,
	// 	'post_status' => 'publish'
	// ) ); 
	// $search_count = $search->found_posts;
	// echo $search_count;
	// wp_reset_query();
endif;
?>