<?php
global $post;
// $title = get_post( $post )->post_title;
$title = get_the_title();
$slug = $post ? $post->post_name : null;
$id = $post ? $post->ID : null;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$page_url = get_the_permalink();
$delay = $post ? $post->delay : null;
$paged = 1;
if( isset($query_vars) && $query_vars ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$event_type_param = $query_vars['type'];
	$year_param = array_key_exists('date', $query_vars) ? $query_vars['date'] : null;
	$upcoming_ids = $query_vars['upcoming_ids'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	$events_section = $query_vars['events_section'];
	$page_param = $slug;
	$event_classes = $event_type_param . ' ' . $year_param;
else:
	$event_type_param = get_query_var( 'type' );
	$year_param = get_query_var( 'date' );
	$event_classes = $event_type_param . ' ' . $year_param;
endif;
$event_type_param_title = pretty( $event_type_param );
?>