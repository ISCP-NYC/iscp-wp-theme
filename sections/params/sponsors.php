<?php
$title = get_the_title();
$slug = $post->post_name;
$id = $post->ID;
$country_param = get_query_var( 'from' );
$type_param = get_query_var( 'type' );
$paged = 1;
$page_url = get_the_permalink();
$page_param = $slug;
if( $query_vars ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	$country_param = $query_vars['from'];
endif;
$country_param_obj = get_page_by_path( $country_param, OBJECT, 'country' );
$country_param_title = $country_param_obj->post_title;
$country_param_id = $country_param_obj->ID;
?>