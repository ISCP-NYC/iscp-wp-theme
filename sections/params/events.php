<?php
$title = get_post( $post )->post_title;
$slug = get_post( $post )->post_name;
$id = get_post( $post )->ID;
$today = new DateTime();
$today = $today->format( 'Ymd' );
$event_classes = $event_type_param . ' ' . $year_param;
$page_url = get_the_permalink();
$delay = $post->delay;
$paged = 1;
if( $query_vars ):
	$slug = $query_vars['pagename'];
	$paged = $query_vars['paged'];
	$event_type_param = $query_vars['type'];
	$year_param = $query_vars['date'];
	$upcoming_ids = $query_vars['upcoming_ids'];
	$post = get_page_by_path( $slug, OBJECT, 'page' );
	$events_section = $query_vars['events_section'];
	$page_param = $slug;
else:
	$event_type_param = get_query_var( 'type' );
	$year_param = get_query_var( 'date' );
endif;
$event_type_param_title = pretty( $event_type_param );
?>