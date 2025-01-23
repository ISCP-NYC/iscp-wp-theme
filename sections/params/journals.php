<?php
global $post;
$title = get_the_title();
$slug = $post ? $post->post_name : null;
$id = $post ? $post->ID : null;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$page_url = get_the_permalink();
$paged = 1;
$tag_param = get_query_var( 'tag' );
$filter_query = [];
if( isset($query_vars) ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$tag_param = array_key_exists('tag', $query_vars) ? $query_vars['tag'] : null;
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	$page_param = $slug;
endif;
if( $tag_param ):
	$filter_query = array(
		'tag' => $tag_param,
	);
endif;
?>