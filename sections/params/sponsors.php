<?php
global $post;
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$country_param = get_query_var( 'from' );
$paged = 1;
$page_url = get_the_permalink();
$page_param = $slug;
$page_query = null;
$filter_query = [];
if( isset($query_vars) ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	$country_param = $query_vars['from'];
endif;
$country_param_obj = get_page_by_path( $country_param, OBJECT, 'country' );
$country_param_title = $country_param_obj ? $country_param_obj->post_title : null;
$country_param_id = $country_param_obj ? $country_param_obj->ID : null;
if( $country_param ):
	$filter_query = array(
		'key' => 'country',
		'value' => '"' . $country_param_id . '"',
		'compare' => 'LIKE'
	);
endif;
$sponsors_query = array(
	'post_type' => 'sponsor',
	'posts_per_page' => 18,
	'orderby' => 'name',
	'order' => 'ASC',
	'paged' => $paged,
	'post_status' => 'publish',
	'meta_query' => array( $filter_query )
);
?>