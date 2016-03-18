<?php
$title = get_post( $post )->post_title;
$slug = get_post( $post )->post_name;
$id = get_post( $post )->ID;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$page_url = get_the_permalink();
$paged = 1;
$tag_param = get_query_var( 'tag' );
if( $query_vars ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$tag_param = $query_vars['tag'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	$page_param = $slug;
endif;
if( $tag_param ):
	$filter_query = array(
		'tag' => $tag_param,
	);
endif;
?>