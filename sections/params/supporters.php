<?php
global $post;
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$paged = 1;
$page_url = get_the_permalink();
$page_param = $slug;
$page_query = null;
if( $query_vars ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
endif;
if( $type_slug ):
	$filter_query = array(
		'key' => 'type',
		'value' => $type_slug,
		'compare' => 'LIKE'
	);
endif;
$supporters_query = array(
	'post_type' => 'supporter',
	'posts_per_page' => -1,
	'orderby' => 'name',
	'order' => 'ASC',
	'post_status' => 'publish',
	'meta_query' => array( $filter_query )
);
?>